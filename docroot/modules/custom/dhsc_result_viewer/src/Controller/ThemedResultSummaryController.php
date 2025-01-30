<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Component\Transliteration\TransliterationInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\dhsc_result_viewer\DhscDomPdfGenerator;
use Drupal\dhsc_result_viewer\Form\ResultSummaryForm;
use Drupal\dhsc_result_viewer\ThemedResultViewer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * ThemedResultSummaryController for DHSC tools.
 *
 * Provides a controller for all tools that produce summary results per
 * themed group.
 *
 * @package Drupal\dhsc_result_viewer\Controller
 */
class ThemedResultSummaryController extends ControllerBase {

  /**
   * Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * ResultViewer service.
   *
   * @var \Drupal\dhsc_result_viewer\ThemedResultViewer
   */
  protected $resultViewer;

  /**
   * The language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The TempStore factory service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStore;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The logger factory service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * DompdfGenerator service.
   *
   * @var \Drupal\dhsc_result_viewer\DhscDomPdfGenerator
   */
  protected $dompdfGenerator;

  /**
   * The extension path resolver service.
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected $extensionPathResolver;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The transliteration service.
   *
   * @var \Drupal\Component\Transliteration\TransliterationInterface
   */
  protected $transliteration;

  /**
   * ResultSummaryController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\dhsc_result_viewer\ThemedResultViewer $result_viewer
   *   ResultViewer service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store
   *   The TempStore factory service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack service.
   * @param \Drupal\dhsc_result_viewer\DhscDomPdfGenerator $dompdf_generator
   *   The DOMPDF generator service for creating PDF documents.
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extension_path_resolver
   *   The extension path resolver service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   * @param \Drupal\Component\Transliteration\TransliterationInterface $transliteration
   *   The transliteration service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    ThemedResultViewer $result_viewer,
    MessengerInterface $messenger,
    PrivateTempStoreFactory $temp_store,
    FormBuilderInterface $form_builder,
    LoggerChannelFactoryInterface $logger_factory,
    RequestStack $request_stack,
    DhscDomPdfGenerator $dompdf_generator,
    ExtensionPathResolver $extension_path_resolver,
    RouteMatchInterface $route_match,
    TransliterationInterface $transliteration,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->resultViewer = $result_viewer;
    $this->messenger = $messenger;
    $this->tempStore = $temp_store;
    $this->formBuilder = $form_builder;
    $this->loggerFactory = $logger_factory;
    $this->requestStack = $request_stack;
    $this->dompdfGenerator = $dompdf_generator;
    $this->extensionPathResolver = $extension_path_resolver;
    $this->routeMatch = $route_match;
    $this->transliteration = $transliteration;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('dhsc_themed_result_viewer.service'),
      $container->get('messenger'),
      $container->get('tempstore.private'),
      $container->get('form_builder'),
      $container->get('logger.factory'),
      $container->get('request_stack'),
      $container->get('dhsc_result_viewer.dompdf_generator'),
      $container->get('extension.path.resolver'),
      $container->get('current_route_match'),
      $container->get('transliteration')
    );
  }

  /**
   * Build summary result page.
   *
   * @return array|RedirectResponse
   *   Return render array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function build($webform_id) {
    // Convert hyphens to underscores.
    $webform_id = str_replace('-', '_', $webform_id);

    $config = $this->configFactory->get(ResultSummaryForm::SETTINGS);

    // Extract unique submission token value from URL.
    $submission_token = $this->requestStack->getCurrentRequest()->query->get('token');

    // Load the webform_submission entity.
    $submission = $this->resultViewer->getSubmissionByToken($submission_token, $webform_id);

    // Load the webform entity.
    if ($submission) {
      $webform = $submission->getWebform();

      // Construct the submission URL.
      if ($submission_token) {
        $webform_url = Url::fromRoute('entity.webform.canonical', ['webform' => $webform->id()])
          ->toString();
        $submission_url = Url::fromUserInput($webform_url, ['query' => ['token' => $submission_token]])
          ->toString();
      }

      $data = $submission->getData();

      if (!empty($data)) {
        // Sort all responses by associated theme.
        $sorted_results = $this->resultViewer->sortQuestionsByTheme($webform, $data);

        // Retrieve result summaries.
        $summaries_by_quartile = [];
        foreach ($sorted_results as $uuid => $result) {
          $summaries_by_quartile[$uuid] = $this->resultViewer->getResultSummaryContent($uuid);
        }

        // Provide an overall score for each theme; normalised to fall between 1
        // and 4.
        $scores_by_theme = $this->resultViewer->getThemeScores($webform, $sorted_results);

        // Process results content based on derived theme data.
        foreach ($scores_by_theme as $uuid => $theme_score) {
          // Reset response array.
          $responses = [];

          foreach ($sorted_results[$uuid] as $item) {
            // Assign all processed text elements, score and response data for
            // potential display by theme templates.
            $responses[] = $item;
          }

          // @todo Work out theme score range based on max and min possible values.
          // A generic scoring solution was implemented to avoid needing to
          // assign a score to standard radio options based on weight or order.
          // The new radio button element allows scoring from a larger range,
          // but the logic determining which quartile of content (toolkit_theme
          // taxonomy term content) to display does not yet account for this.
          // While not needed for this project, a future enhancement could
          // refine this logic for better adaptability.
          $quartile = match (TRUE) {

            // Normalise the score into quartile levels.
            $theme_score['score'] >= 1 && $theme_score['score'] < 1.75 => 'QA',
            $theme_score['score'] >= 1.75 && $theme_score['score'] < 2.5 => 'QB',
            $theme_score['score'] >= 2.5 && $theme_score['score'] < 3.25 => 'QC',
            $theme_score['score'] >= 3.25 && $theme_score['score'] <= 4 => 'QD',
            default => NULL,
          };

          // Handle unexpected values gracefully.
          $result = NULL;

          switch ($quartile) {
            case 'QA':
              $result = $this->resultViewer->buildQuartileSummariesRenderArray($summaries_by_quartile[$uuid]['QA']);
              break;

            case 'QB':
              $result = $this->resultViewer->buildQuartileSummariesRenderArray($summaries_by_quartile[$uuid]['QB']);
              break;

            case 'QC':
              $result = $this->resultViewer->buildQuartileSummariesRenderArray($summaries_by_quartile[$uuid]['QC']);
              break;

            case 'QD':
              $result = $this->resultViewer->buildQuartileSummariesRenderArray($summaries_by_quartile[$uuid]['QD']);
              break;

            default:
              $result = NULL;
              break;
          }

          // Get theme taxonomy term title from field.
          $theme = $this->entityTypeManager->getStorage('taxonomy_term')
            ->loadByProperties(['uuid' => $uuid]);

          // Assign the first (and only) element.
          $theme = reset($theme);

          $theme_name = $theme ? $theme->get('field_theme_title')->value : 'Unassigned Theme';

          $elements[] = [
            '#theme' => 'dhsc_themed_results_list',
            '#title' => $theme_name,
            '#summary' => $config->get('dsf_result_summary') ? $config->get('dsf_result_summary') : NULL,
            '#responses' => $responses,
            '#result' => $result,
            '#webform_id' => $webform_id,
          ];
        }

        $tempStore = $this->tempStore->get('dhsc_result_viewer');

        // Save result data in temp store for email result behaviour.
        if (isset($elements)) {
          $tempStore->set('themed_summary_result_data', $elements);
          $tempStore->set('themed_summary_webform_title', $webform->label());
          $tempStore->set('themed_summary_webform_id', $webform->id());
        }

        $download_results_url = Url::fromRoute('dhsc_result_viewer.generate_theme_summary_pdf')
          ->toString();
      }
      else {
        $elements[] = [
          '#theme' => 'dhsc_themed_results_list',
          '#title' => $config->get('title') ? $config->get('title') : NULL,
          '#summary' => $config->get('summary') ? $config->get('summary') : NULL,
          '#no_result' => $this->t('No result'),
        ];
      }

      if (isset($elements)) {
        $render_array = [
          '#theme' => 'dhsc_tool__themed_results_summary',
          '#type' => 'container',
          '#title' => $this->t('Your answers'),
          '#result_summary' => $elements,
          '#submission_url' => $submission_url ?? NULL,
          '#download_results_path' => $download_results_url,
          '#email_form' => $this->formBuilder->getForm('Drupal\dhsc_result_viewer\Form\ThemedResultEmailForm'),
          '#manager_email_form' => $this->formBuilder->getForm('Drupal\dhsc_result_viewer\Form\ThemedResultManagerEmailForm'),
          '#webform_id' => $webform_id,
        ];
        return $render_array;
      }
      else {
        $this->messenger->addMessage($this->t('No results available.'));
        return new RedirectResponse(Url::fromRoute('<front>')->toString());
      }
    }
    else {
      $this->messenger->addMessage($this->t('No results available.'));

      // If no submission is found, redirect to the front page.
      return new RedirectResponse(Url::fromRoute('<front>')->toString());
    }
  }

  /**
   * Generate a PDF file.
   *
   * @return \Symfony\Component\HttpFoundation\Response|null
   *   PDF response or null.
   */
  public function generateDownload() {
    // Retrieve the results from temporary storage.
    $results = $this->tempStore->get('dhsc_result_viewer')
      ->get('themed_summary_result_data');
    $webform_title = $this->tempStore->get('dhsc_result_viewer')
      ->get('themed_summary_webform_title');
    $webform_id = $this->tempStore->get('dhsc_result_viewer')
      ->get('themed_summary_webform_id');

    // Check if results are available.
    if (empty($results)) {
      $this->messenger->addError($this->t('No results found to generate the PDF.'));
      $this->loggerFactory->get('dhsc_result_viewer')
        ->warning('PDF generation attempted with empty results.');
      return NULL;
    }

    // Prepare the render array for the PDF content.
    $content = [
      '#theme' => 'dhsc_themed_results_pdf_content',
      '#results' => $results,
      '#webform_id' => $webform_id,
    ];

    // Get the path to the stylesheet.
    $stylesheet = $this->extensionPathResolver->getPath('module', 'dhsc_result_viewer') . '/css/style.css';

    // Generate the PDF.
    $filename = $webform_title . '-results-' . date('d-M-Y');
    $filename = $this->transliteration->transliterate($filename);
    $filename = preg_replace('/[^a-z0-9_]+/', '_', strtolower($filename));
    $response = $this->dompdfGenerator->getResponse($filename, $content, FALSE, [], 'A4', 0, 0, 0, 'portrait', NULL, $stylesheet);

    if (!$response) {
      $this->messenger->addError($this->t('Failed to generate the PDF. Please try again later.'));
      $this->loggerFactory->get('dhsc_result_viewer')
        ->error('PDF generation failed for unknown reasons.');
      return NULL;
    }

    return $response;
  }

}
