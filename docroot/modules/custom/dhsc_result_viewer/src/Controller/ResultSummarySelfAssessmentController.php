<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\dhsc_result_viewer\Form\ResultSummaryForm;
use Drupal\dhsc_result_viewer\SelfAssessmentResultViewer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ResultSummarySelfAssessmentController.
 *
 * @package Drupal\dhsc_result_viewer\Controller
 */
class ResultSummarySelfAssessmentController extends ControllerBase {

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
   * @var \Drupal\dhsc_result_viewer\SelfAssessmentResultViewer
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
  protected $tempStoreFactory;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The logger factory service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * ResultSummaryController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\dhsc_result_viewer\SelfAssessmentResultViewer $result_viewer
   *   ResultViewer service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager service.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The Messenger service.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The TempStore factory service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    SelfAssessmentResultViewer $result_viewer,
    LanguageManagerInterface $language_manager,
    MailManagerInterface $mail_manager,
    MessengerInterface $messenger,
    PrivateTempStoreFactory $temp_store_factory,
    FormBuilderInterface $form_builder,
    RequestStack $request_stack,
    LoggerChannelFactoryInterface $logger_factory,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->resultViewer = $result_viewer;
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
    $this->messenger = $messenger;
    $this->tempStoreFactory = $temp_store_factory;
    $this->formBuilder = $form_builder;
    $this->requestStack = $request_stack;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('dhsc_self_assessment_result_viewer.service'),
      $container->get('language_manager'),
      $container->get('plugin.manager.mail'),
      $container->get('messenger'),
      $container->get('tempstore.private'),
      $container->get('form_builder'),
      $container->get('request_stack'),
      $container->get('logger.factory')
    );
  }

  /**
   * Get submission results.
   *
   * @return mixed
   *   Return submission results.
   */
  public function getResults($submission) {
    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    if ($submission) {
      return $this->resultViewer->getResultsSummary($submission->getData());
    }
  }

  /**
   * Build summary result page.
   *
   * @return array
   *   Return render array.
   *
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function build() {
    $config = $this->configFactory->get(ResultSummaryForm::SETTINGS);

    // Get the value from tempstore if we need to display a result variant.
    $result_variant = $this->resultViewer->questionsAllYes();

    // Reset the value if question choices are edited.
    // @todo may need to do this on another action
    $this->resultViewer->questionsAllReset();

    $submission = $this->resultViewer->getSubmission();
    $webform = $submission->getWebform();

    // Extract unique submission token value from URL.
    if ($submission_token = $this->requestStack->getCurrentRequest()->query->get('token')) {
      $webform_url = Url::fromRoute('entity.webform.canonical', ['webform' => $webform->id()])->toString();
      $submission_url = Url::fromUserInput($webform_url, ['query' => ['token' => $submission_token]])->toString();
    }

    if ($result = $this->getResults($submission)) {

      $tempStore = $this->tempStoreFactory->get('dhsc_result_viewer');

      // Save result data in tempstore for email result behaviour.
      $tempStore->set('self_assessment_result_data', $result);

      $element = [
        '#theme' => 'dhsc_results_list_self_assessment',
        '#result_variant' => $result_variant === TRUE ? $config->get('results_variant_text') : NULL,
        '#title' => $config->get('title') ? $config->get('title') : NULL,
        '#summary' => $config->get('sa_result_summary') ? $config->get('sa_result_summary') : NULL,
        '#result' => $result,
        '#submission_url' => $submission_url ?? NULL,
        '#email_form' => $this->formBuilder->getForm('Drupal\dhsc_result_viewer\Form\ResultEmailForm'),
      ];
    }
    else {
      $element = [
        '#theme' => 'dhsc_results_list_self_assessment',
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
   *   The email address.
   * @param string $token
   *   The token.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The response.
   */
  public function sendResultEmail($email, $token) {
    $tempStore = $this->tempStoreFactory->get('dhsc_result_viewer');

    // Get saved result data from tempstore.
    $results = $tempStore->get('self_assessment_result_data');

    if (!empty($results)) {
      $result = $this->buildEmail($email, $results);

      if (!$result['result']) {
        $message = $this->t('There was a problem sending self assessment result email to @email', ['@email' => $email]);
        $this->loggerFactory->get('Error in email sending')->error($message);
      }
      else {
        $this->loggerFactory->get('dhsc_result_viewer')->notice('self assessment result email sent to ' . $email);
      }

      $submission_url = Url::fromRoute(
        'dhsc_result_viewer.result_summary_self_assessment',
        ['token' => $token]
      )->toString();

      $response = $submission_url ?
        new RedirectResponse($submission_url) :
        new RedirectResponse('/');

      $this->messenger->addStatus($this->t('Email sent'));

      return $response;
    }
    $this->messenger->addError($this->t('Unable to send email'));
  }

  /**
   * Construct results email.
   *
   * @param string $email
   *   The email address.
   * @param array $results
   *   Results array.
   *
   * @return array
   *   Array containing all details of the message.
   */
  public function buildEmail(string $email, array $results) {
    $module = 'dhsc_result_viewer';
    $key = 'email_result';
    $to = $email;
    $langcode = $this->languageManager->getDefaultLanguage()->getId();
    $params['subject'] = $this->t('Get started with email recommendations');

    $result_items = '';
    if ($results) {
      foreach ($results as $node) {
        $result_items .= "<h4>{$node['#title']}</h4>";
        $result_items .= "<p>Your response: <strong>{$node['#answer']}</strong></p>";
        foreach ($node['#content'] as $key => $item) {
          if ($key === '#text') {
            $result_items .= "{$item}";
          }
        }
      }
    }

    $params['body'] = Markup::create("
    <table class='results'>
    <tr class='result'>{$result_items}</td></tr>
    </table>");

    return $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);
  }

}
