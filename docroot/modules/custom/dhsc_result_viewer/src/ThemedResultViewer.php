<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\Markup;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ThemedResultViewer.
 *
 * @package Drupal\dhsc_result_viewer
 */
class ThemedResultViewer implements ThemedResultViewerInterface {

  use StringTranslationTrait;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Node storage.
   *
   * @var \Drupal\Node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * View builder.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $viewBuilder;

  /**
   * View builder.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $paragraphViewBuilder;

  /**
   * Taxonomy storage.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $taxonomyStorage;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * TempStore.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStore
   */
  protected $tempStore;

  /**
   * Logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * Translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected $stringTranslation;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * ThemeResultViewer constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The temporary storage factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager service.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager service.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    PrivateTempStoreFactory $temp_store_factory,
    LoggerChannelFactoryInterface $logger_factory,
    RequestStack $request_stack,
    MessengerInterface $messenger,
    LanguageManagerInterface $language_manager,
    MailManagerInterface $mail_manager,
    TranslationInterface $string_translation,
    RendererInterface $renderer,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->tempStore = $temp_store_factory;
    $this->loggerFactory = $logger_factory;
    $this->requestStack = $request_stack;
    $this->messenger = $messenger;
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
    $this->stringTranslation = $string_translation;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public function sortQuestionsByTheme($webform, array $responses): array {
    $grouped_by_theme = [];
    $elementsDecoded = $webform->getElementsDecoded();

    // Load the assigned themes from third-party settings.
    $step_themes = $webform->getThirdPartySetting('dhsc_result_viewer', 'toolkit_theme', []);

    // Obtain the score lookup data for the webform elements.
    $response_scores = $webform->getThirdPartySetting('dhsc_result_viewer', 'options_scores', []);

    $question_number = 1;
    // Iterate through each step in elementsDecoded.
    foreach ($elementsDecoded as $step_key => $step_element) {
      // Ensure this is a toolkit_theme_selector wizard element and has
      // an assigned theme set via third party settings.
      if ($step_element['#type'] === 'toolkit_theme_selector' && isset($step_themes[$step_key])) {
        $theme_uuid = $step_themes[$step_key];

        // Process each child element under the step.
        $children_keys = Element::children($step_element, TRUE);

        $element_count = 1;
        foreach ($children_keys as $child_key) {
          // Process selected options and extract the question number.
          if ($step_element[$child_key]['#type'] === 'toolkit_theme_radios') {
            if (isset($responses[$child_key])) {
              $response_key = $responses[$child_key];

              // Map response score to descriptive text.
              $response_text = $step_element[$child_key]['#options'][$response_key];

              // Store response data.
              $grouped_by_theme[$theme_uuid][$question_number]['score'] = $response_scores[$child_key][$response_key];

              $before_dash = strpos($response_text, ' -- ') !== FALSE ? strstr($response_text, ' -- ', TRUE) : $response_text;
              $grouped_by_theme[$theme_uuid][$question_number]['response_text'] = $before_dash;
            }
          }

          if ($step_element[$child_key]['#type'] === 'processed_text') {
            $grouped_by_theme[$theme_uuid][$question_number]['processed_text_' . $element_count] = $step_element[$child_key];
            $element_count++;
          }
        }
      }
      $question_number++;
    }

    return $grouped_by_theme;
  }

  /**
   * {@inheritdoc}
   */
  public function getResultSummaryContent($theme_uuid): array {
    $summaries_by_quartile = [];

    // Load the taxonomy term.
    /** @var \Drupal\taxonomy\Entity\Term $theme_term */
    $theme_term = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadByProperties(['uuid' => $theme_uuid]);

    // Assign the first (and only) element.
    $theme_term = reset($theme_term);

    if ($theme_term && !$theme_term->get('field_result_summary_ref')->isEmpty()) {
      $summaries = [];

      /** @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem $paragraph_ref */
      foreach ($theme_term->get('field_result_summary_ref') as $paragraph_ref) {
        // Load the Paragraph by revision ID.
        $paragraph = $this->entityTypeManager->getStorage('paragraph')
          ->loadRevision($paragraph_ref->get('target_revision_id')->getValue());

        if ($paragraph) {
          $summaries[] = $paragraph->get('field_result_summary');
        }
      }

      // Map sorted paragraphs to quartiles QA - QD.
      $quartiles = ['QA', 'QB', 'QC', 'QD'];
      foreach ($summaries as $index => $summary) {
        $quartile = $quartiles[$index];
        $summaries_by_quartile[$quartile] = $summary;
      }
    }

    return $summaries_by_quartile;
  }

  /**
   * {@inheritdoc}
   */
  public function buildQuartileSummariesRenderArray(FieldItemListInterface $summaries_by_quartile): array {
    return [
      'content' => $summaries_by_quartile->view([
        'label' => 'hidden',
        'type' => 'text_default',
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getThemeScores($webform, array $grouped_by_theme): array {
    $theme_scores = [];

    // Iterate through each theme group.
    foreach ($grouped_by_theme as $uuid => $data) {
      $total_score = 0;

      // Initialise a counter for the answered questions, as some
      // questions can be skipped if not relevant.
      $answer_count = 0;

      // Loop through each question under the theme.
      foreach ($data as $item) {
        // We check the score is within an admissible range (e.g. if the score
        // is -1, we know the question was marked as not relevant, so we won't
        // increment the total score or counter).
        if (isset($item['score']) && $item['score'] >= 0) {
          // Directly sum the score from the response data.
          $total_score += $item['score'];

          // The question was answered so increment the counter.
          $answer_count++;
        }
      }

      // Store the total score for the theme.
      if ($answer_count > 0) {
        $theme_scores[$uuid]['score'] = $total_score / $answer_count;
      }
    }

    return $theme_scores;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmissionByToken(string $token, string $webform_id) {
    // Load the webform entity.
    $webform = $this->entityTypeManager->getStorage('webform')
      ->load($webform_id);

    if ($webform) {
      // Attempt to load submission using token.
      $submission = $this->entityTypeManager
        ->getStorage('webform_submission')
        ->loadFromToken($token, $webform);

      if ($submission) {
        return $submission;
      }

      // Log a warning if submission not found.
      $this->loggerFactory->get('dhsc_result_viewer')->warning(
        'Invalid submission token: @token for webform: @webform_id',
        ['@token' => $token, '@webform_id' => $webform_id]
      );
    }
    else {
      // Log a warning if the webform ID is invalid.
      $this->loggerFactory->get('dhsc_result_viewer')->warning(
        'Invalid webform ID provided: @webform_id',
        ['@webform_id' => $webform_id]
      );
    }

    return NULL;
  }

  /**
   * Email submission results.
   *
   * @param string $email
   *   The email address.
   * @param string $token
   *   The token.
   *
   * @return bool
   *   Return true if email was sent successfully.
   */
  public function sendResultEmail(string $email, string $token) {
    $tempStore = $this->tempStore->get('dhsc_result_viewer');

    // Get saved result data from tempstore.
    $results = $tempStore->get('themed_summary_result_data');
    $webform_title = $tempStore->get('themed_summary_webform_title');
    $webform_id = $tempStore->get('themed_summary_webform_id');

    if (!empty($results)) {
      // Construct email using the results array and send to the email address
      // specified.
      $result = $this->buildEmail($email, $results, $webform_title, $webform_id);

      if (!$result['result']) {
        // Provide a message indicating there was an issue.
        $message = $this->t('There was a problem sending the email to @email', ['@email' => $email]);
        $this->loggerFactory->get('Error in email sending')->error($message);
      }
      else {
        $this->loggerFactory->get('dhsc_result_viewer')
          ->notice(' Digital Skills Framework Tool email sent to ' . $email);
      }

      return TRUE;
    }
    return FALSE;
  }

  /**
   * Construct results email.
   *
   * @param string $email
   *   The email address.
   * @param array $results
   *   Results array.
   * @param string $webform_title
   *   Title of the webform.
   * @param string $webform_id
   *   The webform_id.
   *
   * @return array
   *   Array containing all details of the message.
   */
  public function buildEmail($email, array $results, string $webform_title, string $webform_id) {
    $module = 'dhsc_result_viewer';
    $key = 'email_result';
    $to = $email;
    $langcode = $this->languageManager->getDefaultLanguage()->getId();

    $params['subject'] = $this->t('Here are your results for @webform_title', [
      '@webform_title' => $webform_title,
    ]);

    // Build mail body render array.
    $render_array = [
      '#theme' => 'dhsc_themed_results_email_content',
      '#results' => $results,
      '#webform_id' => $webform_id,
    ];

    // Render the output.
    $rendered_body = $this->renderer->render($render_array);

    // Add the rendered HTML to email parameters.
    $params['body'] = Markup::create($rendered_body);

    return $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);
  }

}
