<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\Render\Renderer;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\dhsc_result_viewer\DhscDomPdfGenerator;
use Drupal\dhsc_result_viewer\Service\WebformToolService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class DhscGeneratePdf.
 */
class DhscGeneratePdf extends ControllerBase implements ContainerInjectionInterface {

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
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStore;

  /**
   * The extension path resolver service.
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected $extensionPathResolver;

  /**
   * Symfony session.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * GeneratePdf constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory service.
   * @param \Drupal\dhsc_result_viewer\DhscDomPdfGenerator $dompdf_generator
   *   The DOMPDF generator service for creating PDF documents.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store
   *   The TempStore factory service.
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extension_path_resolver
   *   The extension path resolver service.
   * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
   *   Symfony session.
   */
  public function __construct(
    EntityTypeManager $entityTypeManager,
    Renderer $renderer,
    ConfigFactoryInterface $configFactory,
    DhscDomPdfGenerator $dompdf_generator,
    PrivateTempStoreFactory $temp_store,
    ExtensionPathResolver $extension_path_resolver,
    SessionInterface $session,
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->renderer = $renderer;
    $this->configFactory = $configFactory;
    $this->dompdfGenerator = $dompdf_generator;
    $this->tempStore = $temp_store;
    $this->extensionPathResolver = $extension_path_resolver;
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('renderer'),
      $container->get('config.factory'),
      $container->get('dhsc_result_viewer.dompdf_generator'),
      $container->get('tempstore.private'),
      $container->get('extension.path.resolver'),
      $container->get('session'),
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
  public function generate() {
    $content = $this->getContent();
    $stylesheet = $this->extensionPathResolver->getPath('module', 'dhsc_result_viewer') . '/css/style.css';
    $filename = 'dhsc-assured-solutions-results';
    $response = $this->dompdfGenerator->getResponse($filename, $content, FALSE, [], 'A4', 0, 0, 0, 'portrait', NULL, $stylesheet);

    return $response;
  }

  /**
   * Get submission result.
   *
   * @return array
   *   Render array.
   */
  protected function getSubmissionResult() {
    // Get saved result data from tempstore.
    $result_data = $this->session->get('assured_solutions_result_data');
    $data = ResultSummaryAssuredSolutionsController::buildResultMarkup($result_data, TRUE);

    return $data;
  }

  /**
   * Get pdf content.
   *
   * @return array
   *   Render array.
   */
  protected function getContent() {
    $results = $this->getSubmissionResult();

    return [
      '#theme' => 'dhsc_results_pdf_content',
      '#content' => $results,
    ];
  }

}
