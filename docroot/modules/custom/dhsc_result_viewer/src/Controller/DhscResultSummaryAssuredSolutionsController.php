<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\dhsc_result_viewer\AssuredSolutionsInterface;
use Drupal\dhsc_result_viewer\Form\DhscResultSummaryForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DHSCResultSummaryAssuredSolutionsController.
 *
 * @package Drupal\dhsc_result_viewer\Controller
 */
class DHSCResultSummaryAssuredSolutionsController extends ControllerBase
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
   * DHSCResultSummaryAssuredSolutionsController constructor.
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
    MessengerInterface $messenger
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->resultViewer = $result_viewer;
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
    $this->messenger = $messenger;
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
    $config = $this->configFactory->get(DhscResultSummaryForm::SETTINGS);

    if ($result = $this->getResults()) {
      $element = [
        '#theme' => 'dhsc_results_list_assured_solutions',
        '#title' => $config->get('title') ? $config->get('title') : NULL,
        '#summary' => $config->get('summary') ? $config->get('summary') : NULL,
        '#search_criteria' => $result['search_criteria'],
        '#count' => $result['count'],
        '#non_matching_count' => $result['non_matching_count'],
        '#total_count' => $result['total_count'],
        '#submission_url' => $result['submission_url'],
        '#no_matches' => $result['no_matches'],
        '#partial_matches' => $result['partial_matches'],
        '#result' => $result['result_items'],
        '#email_form' => \Drupal::formBuilder()->getForm('Drupal\dhsc_result_viewer\Form\DhscResultEmailForm'),
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
    $result = $this->buildEmail($email);

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

  /**
   * Construct email based on form email parameter.
   *
   * @param string $email
   * @return array
   */
  public function buildEmail($email)
  {
    $module = 'dhsc_result_viewer';
    $key = 'email_result';
    $to = $email;
    $langcode = $this->languageManager->getDefaultLanguage()->getId();
    $params['subject'] = t('Assured solutions: Email result');
    $params['body'] = 'Result email!';
    $send = TRUE;

    return $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }
}
