<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Render\Markup;
use Drupal\Core\Render\Renderer;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\dhsc_result_viewer\DhscDomPdfGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dhsc_result_viewer\Controller\ResultSummaryAssuredSolutionsController;

/**
 * Class DhscGeneratePdf.
 */
class DhscGeneratePdf extends ControllerBase implements ContainerInjectionInterface
{

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * The configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * DompdfGenerator service.
   *
   * @var \Drupal\dhsc_result_viewer\DhscDomPdfGenerator
   */
  protected $dompdfGenerator;

  /**
   * TempStore.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStore
   */
  protected $tempStore;


  /**
   * GeneratePdf constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory service.
   * @param \Drupal\tsp_result_viewer\ResultViewerInterface $result_viewer
   * @param \Drupal\tsp_result_viewer\TspDomPdfGenerator $dompdf_generator
   */
  public function __construct(
    EntityTypeManager $entityTypeManager,
    Renderer $renderer,
    ConfigFactoryInterface $configFactory,
    DhscDomPdfGenerator $dompdf_generator,
    PrivateTempStoreFactory $temp_store
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->renderer = $renderer;
    $this->configFactory = $configFactory;
    $this->dompdfGenerator = $dompdf_generator;
    $this->tempStore = $temp_store;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('renderer'),
      $container->get('config.factory'),
      $container->get('dhsc_result_viewer.dompdf_generator'),
      $container->get('tempstore.private')
    );
  }

  /**
   * Generate a PDF file.
   *
   * @return array
   *   Response headers.
   *
   * @throws \Exception
   */
  public function generate()
  {
    $content = $this->getContent();
    $pdf_generator = $this->dompdfGenerator;
    $stylesheet = '';
    $filename = 'dhsc-assured-solutions-results';
    $response = $pdf_generator->getResponse($filename, $content, FALSE, [], 'A4', 'portrait', NULL, $stylesheet);

    return $response;
  }

  /**
   * Get submission result.
   *
   * @return array
   *   Render array.
   */
  protected function getSubmissionResult()
  {

    // Get saved result data from tempstore.
    // $tempStore = \Drupal::service('tempstore.private')->get('dhsc_result_viewer');
    $result_data = $this->tempStore->get('dhsc_result_viewer')->get('assured_solutions_result_data');
    $result_markup = ResultSummaryAssuredSolutionsController::buildResultMarkup($result_data);


    return [
      '#theme' => 'dhsc_pdf_results',
      '#results' => $result_markup,
    ];
  }

  /**
   * Get pdf content.
   *
   * @return array
   *   Render array.
   */
  protected function getContent()
  {

    $results = $this->getSubmissionResult();

    return [
      '#theme' => 'dhsc_results_pdf_content',
      '#content' => $results,
    ];
  }
}
