<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\taxonomy\TermInterface;

/**
 * Create the result viewer for DSF tool.
 *
 * @package Drupal\dhsc_dsf_result_viewer
 */
class DSFResultViewer implements DSFInterface {

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
   *   The temporary storage factory.
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
   * Get taxonomy terms for type Skill reference.
   */
  public function getSkillReferences() {
    return $this->taxonomyStorage->loadTree('skill_reference', 0, NULL, TRUE);
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
      $theme = $node->get('field_themes')->referencedEntities()[0]->get('name')->value;
      $values[] = [
        '#theme' => 'result_item_dsf',
        '#title' => $node->getTitle(),
        '#answer' => ucfirst(str_replace('_', ' ', explode('_', $node->get('field_skill_answer')->value, 3)[2])),
        '#category' => $theme,
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
   * @param bool $top_tips
   *   Check if top tip.
   *
   * @return array
   *   Return result ids.
   */
  protected function getResultIds(array $data) {
    $skill_references = $this->getSkillReferences();
    $nids = [];

    /** @var \Drupal\taxonomy\TermInterface $theme */
    foreach ($skill_references as $skill_reference) {
      if ($nid = $this->getResultId($skill_reference, $data)) {
        $nids[] = $nid;
      }
    }

    return $nids;
  }

  /**
   * Get id of result node.
   *
   * @param array $data
   *   Webform values.
   * @param \Drupal\taxonomy\TermInterface $term
   *   Taxonomy term.
   *
   * @return array|int|void
   *   Return result ids.
   */
  protected function getResultId(TermInterface $term, array $data) {
    if (!$term->get('field_answer_machine_name')->isEmpty()) {
      $machine_name = $term->get('field_answer_machine_name')->getString();
      if (isset($data[$machine_name])) {
        $result = $this->nodeStorage->getQuery()
          ->accessCheck(FALSE)
          ->condition('status', 1)
          ->condition('field_skill_answer', $data[$machine_name])
          ->execute();
      }

      if (isset($result)) {
        return reset($result);
      }
    }
  }

  /**
   * {@inheritdoc}
   *
   * Return webform submission id.
   */
  public function getSubmissionId() {
    return $this->tempStore->get('sid');
  }

  /**
   * {@inheritdoc}
   *
   * Return webform submission.
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
    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    if ($submission = $this->getSubmission()) {
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
    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    if ($submission = $this->getSubmission()) {
      /** @var \Drupal\webform\WebformInterface $webform */
      $webform = $this->getSubmission()->getWebform();
      if (!$webform) {
        return;
      }

      $config_name = $webform->getConfigDependencyName();
      $config = $this->configFactory->get($config_name);
      if (!$config) {
        return;
      }
      $raw_data = $config->getRawData();

      return $raw_data['settings']['page_confirm_path'];
    }
  }

}
