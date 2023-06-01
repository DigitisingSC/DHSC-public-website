<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\dhsc_result_viewer\Form\DhscResultSummaryForm;
use Drupal\dhsc_result_viewer\ResultViewerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DhscResultSummaryController.
 *
 * @package Drupal\dhsc_result_viewer\Controller
 */
class DhscResultSummaryController extends ControllerBase {

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
   * @var \Drupal\dhsc_result_viewer\ResultViewerInterface
   */
  protected $resultViewer;

  /**
   * DhscResultSummaryController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\dhsc_result_viewer\ResultViewerInterface $result_viewer
   *   ResultViewer service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    ResultViewerInterface $result_viewer) {
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
      $container->get('dhsc_result_viewer.service')
    );
  }

  /**
   * Get submission results.
   *
   * @return mixed
   *   Resurn submission results.
   */
  public function getResults() {
    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    if ($submission = $this->resultViewer->getSubmission()) {
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
    $config = $this->configFactory->get(DhscResultSummaryForm::SETTINGS);
    if ($result = $this->getResults()) {
      $element = [
        '#theme' => 'dhsc_results_list',
        '#title' => $config->get('title') ? $config->get('title') : NULL,
        '#summary' => $config->get('summary') ? $config->get('summary') : NULL,
        '#result' => $result,
      ];
    }
    else {
      $element = [
        '#theme' => 'dhsc_results_list',
        '#title' => $config->get('title') ? $config->get('title') : NULL,
        '#summary' => $config->get('summary') ? $config->get('summary') : NULL,
        '#no_result' => $this->t('No result'),
      ];
    }

    return $element;
  }

}
