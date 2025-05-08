<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\taxonomy\TermInterface;

/**
 * Class SelfAssessmentResultViewer.
 *
 * @package Drupal\dhsc_self_assessment_result_viewer
 */
class SelfAssessmentResultViewer implements SelfAssessmentInterface {

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
   * ResultViewer constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The private temp store factory.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    PrivateTempStoreFactory $temp_store_factory,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->viewBuilder = $entity_type_manager->getViewBuilder('node');
    $this->paragraphViewBuilder = $entity_type_manager->getViewBuilder('paragraph');
    $this->taxonomyStorage = $entity_type_manager->getStorage('taxonomy_term');
    $this->configFactory = $config_factory;
    $this->tempStore = $temp_store_factory->get('dhsc_result_viewer');
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories() {
    return $this->taxonomyStorage->loadTree('category', 0, NULL, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getResultsSummary($data) {
    $nids = $this->getResultIds($data);
    if (!$nids) {
      return;
    }

    $nodes = $this->nodeStorage->loadMultiple($nids);

    foreach ($nodes as $node) {
      $values[] = [
        '#theme' => 'result_item_self_assessment',
        '#title' => $node->getTitle(),
        '#answer' => ucfirst(str_replace('_', ' ', explode('_', $node->get('field_answers_recommendation')->value, 3)[2])),
        '#content' => [
          '#type' => 'processed_text',
          '#text' => $node->get('field_body_paragraphs')->entity->localgov_text->value,
          '#format' => 'full_html',
        ],
      ];
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function getSortsResultIds() {
    if ($data = $this->getSubmissionData()) {
      $nids = $this->getResultIds($data);
      if (!$nids) {
        return;
      }
      $nodes = $this->nodeStorage->loadMultiple($nids);
    }
    return $nodes;
  }

  /**
   * Get ids of result nodes.
   *
   * @param array $data
   *   Webform values.
   *
   * @return array
   *   Return result ids.
   */
  protected function getResultIds(array $data) {
    $categories = $this->getCategories();
    $nids = [];

    /** @var \Drupal\taxonomy\TermInterface $category */
    foreach ($categories as $category) {
      if ($nid = $this->getResultId($category, $data)) {
        $nids[] = $nid;
      }
    }

    if ($nids) {
      $weighted_nids = $this->nodeStorage->getQuery()
        ->accessCheck(FALSE)
        ->condition('nid', $nids, 'IN')
        ->sort('field_weight', 'ASC')
        ->execute();
    }

    return $weighted_nids;
  }

  /**
   * Get id of result node.
   *
   * @param \Drupal\taxonomy\TermInterface $term
   *   Category term.
   * @param array $data
   *   Webform values.
   *
   * @return int|void
   *   Return result ids.
   */
  protected function getResultId(TermInterface $term, array $data) {
    if ($term->bundle() != 'category') {
      return NULL;
    }

    if (!$term->get('field_answer_machine_name')->isEmpty()) {
      $machine_name = $term->get('field_answer_machine_name')->getString();
      if (isset($data[$machine_name])) {
        $result = $this->nodeStorage->getQuery()
          ->accessCheck(FALSE)
          ->condition('status', 1)
          ->condition('field_answers_recommendation', $data[$machine_name])
          ->condition('field_category.target_id', $term->id())
          ->execute();
      }

      if (!empty($result)) {
        return reset($result);
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmissionId() {
    return $this->tempStore->get('sid');
  }

  /**
   * {@inheritdoc}
   */
  public function questionsAllYes() {
    return $this->tempStore->get('yes_to_all_questions');
  }

  /**
   * {@inheritdoc}
   */
  public function questionsAllReset() {
    return $this->tempStore->set('yes_to_all_questions', NULL);
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmission() {
    $sid = $this->getSubmissionId();
    if ($sid) {
      $submission = $this->entityTypeManager->getStorage('webform_submission')->load($sid);
      return $submission ? $submission : NULL;
    }
  }

  /**
   * Get webform submission data.
   *
   * @return array
   *   Return webform submission data.
   */
  protected function getSubmissionData() {
    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    if ($submission = $this->getSubmission()) {
      return $submission->getData();
    }
  }

  /**
   * Get webform url.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   *   Return webform url.
   */
  protected function getWebFormUrl() {
    if ($this->getSubmission()) {
      /** @var \Drupal\webform\WebformInterface $webform */
      $webform = $this->getSubmission()->getWebform();
      if ($webform) {
        return $webform->toUrl()->toString();
      }
    }
  }

  /**
   * Get webform confirmation page path.
   *
   * @return mixed|void
   *   Return webform confirmation page path.
   */
  protected function getConfirmationPagePath() {
    if ($this->getSubmission()) {
      /** @var \Drupal\webform\WebformInterface $webform */
      $webform = $this->getSubmission()->getWebform();
      if (!$webform) {
        return NULL;
      }

      $config_name = $webform->getConfigDependencyName();
      $config = $this->configFactory->get($config_name);
      if (!$config) {
        return NULL;
      }
      $raw_data = $config->getRawData();

      return $raw_data['settings']['page_confirm_path'];
    }
  }

}
