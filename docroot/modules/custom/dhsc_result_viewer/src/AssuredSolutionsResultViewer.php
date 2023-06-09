<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\TermInterface;

/**
 * Class AssuredSolutionsResultViewer.
 *
 * @package Drupal\dhsc_assured_solutions_result_viewer
 */
class AssuredSolutionsResultViewer implements AssuredSolutionsInterface
{

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
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    PrivateTempStoreFactory $temp_store_factory
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
  public function getCategories()
  {
    return $this->taxonomyStorage->loadTree('category', 0, NULL, TRUE);;
  }

  /**
   * {@inheritdoc}
   */
  public function getResultsSummary($data)
  {
    $results = $this->getResultIds($data);

    if (!$results) {
      return;
    }

    foreach ($results as $key => $result) {
      if ($key === 'matches') {
        foreach ($result as $node) {
          $values[] = [
            '#theme' => 'result_item',
            '#title' => $node->getTitle(),
            '#content' => [
              '#type' => 'processed_text',
              '#text' => $node->get('field_body_paragraphs')->entity->localgov_text->value,
              '#format' => 'full_html',
            ],
          ];
        }
      }
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function getSortsResultIds()
  {
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
  protected function getResultIds(array $data)
  {
    $answers = [];

    foreach ($data as $key => $answer) {
      // check we have an answer value in both checkboxes and radio form fields.
      if ($answer === '0' || empty($answer)) {
        continue;
      }
      if ($answer === '1') {
        $answer = $key;
      }
      $answers[] = $answer;
    }

    // start by querying for all nodes which contain at least one answer.
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'supplier');
    $or = $query->orConditionGroup();
    foreach ($answers as $answer) {
      $or->condition('field_possible_answers.value', $answer);
    }
    $query->condition($or);

    $results = $query->execute();

    $nodes = Node::loadMultiple($results);

    $partial_matches = [];
    $no_matches = [];
    $matches = [];
    $nids = [];

    foreach ($nodes as $node) {
      foreach ($node->get('field_possible_answers')->getValue() as $value) {
        // create array of all supplier node ids used for filtering non matching criteria.
        $nids[] = $node->id();

        // filter results where not all criteria is met.
        if (!in_array($value['value'], $answers)) {
          $node_title = $node->getTitle();
          $node_url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node->id());

          $partial_matches[$node_title]['title'] = $node_title;
          $partial_matches[$node_title]['node_url'] = $node_url;
          $partial_matches[$node_title][] = $value['value'];
        }
      }

      // filter nodes where all criteria is met.
      if (
        !isset($no_matches[$node->getTitle()]) &&
        !array_key_exists($node->getTitle(), $partial_matches)
      ) {
        $matches[] = $node;
      }
    }

    // query for all supplier nodes where there is no match
    $nids = array_unique($nids);
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'supplier');
    foreach ($nids as $nid) {
      $query->condition('nid', $nid, 'NOT IN');
    }
    $result = $query->execute();

    $no_result_nodes = Node::loadMultiple($result);

    foreach ($no_result_nodes as $node) {
      $node_title = $node->getTitle();
      $node_url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node->id());

      $no_matches[$node_title]['title'] = $node_title;
      $no_matches[$node_title]['node_url'] = $node_url;
      $no_matches[$node_title][] = $value['value'];
    }

    $result_data = [
      'matches' => $matches,
      'partial_matches' => $partial_matches,
      'no_matches' => $no_matches,
    ];

    return $result_data;
  }

  /**
   * Get id of result node.
   *
   * @param TermInterface $term
   *   Category term.
   * @param array $data
   *   Webform values.
   *
   * @return array|int|void
   *   Return result ids.
   */
  protected function getResultId(TermInterface $term, array $data)
  {
    if ($term->bundle() != 'category') {
      return;
    }

    if (!$term->get('field_answer_machine_name')->isEmpty()) {
      $machine_name = $term->get('field_answer_machine_name')->getString();
      if (isset($data[$machine_name])) {
        $result = $this->nodeStorage->getQuery()
          ->condition('field_possible_answers', $data[$machine_name])
          ->condition('field_category.target_id', $term->id())
          ->execute();
      }

      if (isset($result)) {
        return reset($result);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmissionId()
  {
    return $this->tempStore->get('sid');
  }

  /**
   * {@inheritdoc}
   */
  public function questionsAllReset()
  {
    return $this->tempStore->set('yes_to_all_questions', NULL);
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmission()
  {
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
  protected function getSubmissionData()
  {
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
  protected function getWebFormUrl()
  {
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
  protected function getConfirmationPagePath()
  {
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
