<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\dhsc_result_viewer\Form\ResultSummaryForm;
use Drupal\dhsc_result_viewer\SelfAssessmentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * @var \Drupal\dhsc_self_assessment_result_viewer\SelfAssessmentInterface
   */
  protected $resultViewer;

  /**
   * ResultSummaryController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\dhsc_self_assessment_result_viewer\SelfAssessmentInterface $result_viewer
   *   ResultViewer service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    SelfAssessmentInterface $result_viewer) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->resultViewer = $result_viewer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('dhsc_self_assessment_result_viewer.service'),
    );
  }

  /**
   * Get submission results.
   *
   * @return mixed
   *   Resurn submission results.
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
   */
  public function build() {
    $config = $this->configFactory->get(ResultSummaryForm::SETTINGS);

    // Get the value from tempstore if we need to display a result variant.
    $result_variant = $this->resultViewer->questionsAllYes();

    // Reset the value if question choices are edited
    // @todo may need to do this on another action
    $this->resultViewer->questionsAllReset();

    $submission = $this->resultViewer->getSubmission();
    $webform = $submission->getWebform();

    // Extract unique submission token value from URL.
    if ($submission_token = \Drupal::request()->query->get('token')) {
      $submission_url = Url::fromUserInput($webform->url(), ['query' => ['token' => $submission_token]])->toString();
    }

    if ($result = $this->getResults($submission)) {
      $element = [
        '#theme' => 'dhsc_results_list_self_assessment',
        '#result_variant' => $result_variant === TRUE ? $config->get('results_variant_text') : NULL,
        '#title' => $config->get('title') ? $config->get('title') : NULL,
        '#summary' => $config->get('sa_result_summary') ? $config->get('sa_result_summary') : NULL,
        '#result' => $result,
        '#submission_url' => isset($submission_url) ? $submission_url : NULL,
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

}
