<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\dhsc_result_viewer\AssuredSolutionsInterface;
use Drupal\dhsc_result_viewer\Form\ResultSummaryForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ResultSummaryAssuredSolutionsController.
 *
 * @package Drupal\dhsc_result_viewer\Controller
 */
class ResultSummaryAssuredSolutionsController extends ControllerBase
{

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
   * @var \Drupal\dhsc_assured_solutions_result_viewer\AssuredSolutionsInterface
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
   * TempStore.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStore
   */
  protected $tempStore;

  /**
   * ResultSummaryAssuredSolutionsController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\dhsc_assured_solutiions_result_viewer\AssuredSolutionsInterface $result_viewer
   *   ResultViewer service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager service.
   * @param \Drupal\Core\Language\MailManagerInterface $mail_manager
   *   The mail manager service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    AssuredSolutionsInterface $result_viewer,
    LanguageManagerInterface $language_manager,
    MailManagerInterface $mail_manager,
    MessengerInterface $messenger,
    PrivateTempStoreFactory $temp_store
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->resultViewer = $result_viewer;
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
    $this->messenger = $messenger;
    $this->tempStore = $temp_store;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('dhsc_assured_solutions_result_viewer.service'),
      $container->get('language_manager'),
      $container->get('plugin.manager.mail'),
      $container->get('messenger'),
      $container->get('tempstore.private')
    );
  }

  /**
   * Get submission results.
   *
   * @return mixed
   *   Resurn submission results.
   */
  public function getResults()
  {
    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    if ($submission = $this->resultViewer->getSubmission()) {
      return $this->resultViewer->getResultsSummary($submission->getData(), $submission->getWebform());
    }
  }

  /**
   * Build summary result page.
   *
   * @return array
   *   Return render array.
   */
  public function build()
  {
    $config = $this->configFactory->get(ResultSummaryForm::SETTINGS);

    if ($result = $this->getResults()) {
      $element = [
        '#theme' => 'dhsc_results_list_assured_solutions',
        '#summary' => $config->get('as_result_summary') ? $config->get('as_result_summary') : NULL,
        '#search_criteria' => $result['search_criteria'],
        '#count' => $result['count'],
        '#non_matching_count' => $result['non_matching_count'],
        '#total_count' => $result['total_count'],
        '#submission_url' => $result['submission_url'],
        '#no_matches' => $result['no_matches'],
        '#result' => $result['result_items'],
        '#email_form' => !empty($result['total_count']) ? \Drupal::formBuilder()->getForm('Drupal\dhsc_result_viewer\Form\ResultEmailForm') : FALSE,
      ];
    } else {
      $element = [
        '#theme' => 'dhsc_results_list_assured_solutions',
        '#title' => $config->get('title') ? $config->get('title') : NULL,
        '#summary' => $config->get('summary') ? $config->get('summary') : NULL,
        '#no_result' => $this->t('No result'),
      ];
    }

    return $element;
  }

  /**
   * Email submission results.
   *
   * @param string $email
   * @return void
   */
  public function sendResultEmail($email, $token)
  {

    $tempStore = \Drupal::service('tempstore.private')->get('dhsc_result_viewer');

    // Get saved result data from tempstore.
    $results = $tempStore->get('assured_solutions_result_data');

    if (!empty($results)) {

      $result = $this->buildEmail($email, $results);

      if (!$result['result']) {
        $message = t('There was a problem sending assured solutions result email to @email', array('@email' => $email));
        \Drupal::logger('Error in email sending')->error($message);
      } else {
        \Drupal::logger('dhsc_result_viewer')->notice('assured solutions result email sent to ' . $email);
      }

      $submission_url = Url::fromRoute(
        'dhsc_result_viewer.result_summary_assured_solutions',
        ['token' => $token]
      )->toString();

      $response = $submission_url ?
        new RedirectResponse($submission_url) :
        new RedirectResponse('/');

      $this->messenger->addStatus(t('Email sent'));

      return $response;
    }
    $this->messenger->addError(t('Unable to send email'));
  }

  /**
   * Construct results email.
   *
   * @param string $email
   * @return array
   */
  public function buildEmail($email, $results)
  {
    $module = 'dhsc_result_viewer';
    $key = 'email_result';
    $to = $email;
    $langcode = $this->languageManager->getDefaultLanguage()->getId();
    $params['subject'] = t('Assured solutions: Email result');

    $criteria = '';
    if ($results['search_criteria']) {
      foreach ($results['search_criteria'] as $item) {
        $criteria .= "<h4>{$item['section']}</h4><ul>";
        foreach ($item['answers'] as $answer) {
          $criteria .= "<li>{$answer}</li>";
        }
        $criteria .= "</ul>";
      }
    }

    $result_items = '';
    if ($results['matches']) {
      foreach ($results['matches'] as $node) {
        if ($node->get('field_body_paragraphs')->entity->getType() === 'localgov_text') {
          $summary = $node->get('field_body_paragraphs')->entity->get('localgov_text')->value;
        } else {
          $summary = '';
        }
        $result_items .= "<h4>
      {$node->getTitle()}</h4>
      {$summary}
      <p>";
        $result_items .= "</p>";
      }
    }

    $no_matches = '';
    if ($results['no_matches']) {
      foreach ($results['no_matches'] as $item) {
        $no_matches .= "<h4>{$item['title']}</h4><strong>Criteria not met:</strong><ul>";
        foreach ($item['answers'] as $key => $answers) {
          $no_matches .= "<p>$key</p>";
          foreach ($answers as $answer) {
            $no_matches .= "<li>{$answer}</li>";
          }
        }
        $no_matches .= "</ul>";
      }
    }

    $non_matching_count = !empty($results['non_matching_count']) ?
    $results['non_matching_count'] . " suppliers don't match your criteria" : FALSE;
    $non_matching_html = '';

    if ($non_matching_count) {
      $non_matching_html = Markup::create("<tr class='non-matches'><td><h3>
      {$non_matching_count}</h3>
      {$no_matches}</td>
      </tr>");
    }

    $params['body'] = Markup::create("
    <table class='results'><tr><td><h3>Showing {$results['count']} out of {$results['total_count']} results</h3></td></tr>
    <tr class='search-criteria'><td><h3>Search criteria:</h3>{$criteria}</td></tr>
    <tr class='matches'><td><h3>Matching suppliers:</h3>{$result_items}</td></tr>
    {$non_matching_html}
    </table>");

    return $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);
  }
}
