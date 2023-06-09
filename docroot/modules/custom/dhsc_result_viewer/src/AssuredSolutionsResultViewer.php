<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\dhsc_result_viewer\Form\DhscResultSummaryForm;

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
    $nids = $this->getResultIds($data);
    if (!$nids) {
      return;
    }

    $nodes = $this->nodeStorage->loadMultiple($nids);

    foreach ($nodes as $node) {
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
    $categories = $this->getCategories();

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

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'supplier');
    $or = $query->orConditionGroup();
    foreach ($answers as $answer) {
      $or->condition('field_possible_answers.value', $answer);
    }
    $query->condition($or);

    // Node ids of suppliers which match at least one answer.
    $orResults = $query->execute();

    $nodes = \Drupal\node\Entity\Node::loadMultiple($orResults);
    $nonMatchingResults = [];
    $matchingResults = [];

    foreach($nodes as $node){
      foreach($node->get('field_possible_answers')->getValue() as $value) {
        // Filter results where not all criteria is met.
        if (!in_array($value['value'], $answers)) {
          $nonMatchingResults[$node->getTitle()][] = 'Criteria not met: ' . $value['value'];
        }
      }

      // Filter results where all criteria is met.
      if (!isset($nonMatchingResults[$node->getTitle()]) &&
       !array_key_exists($node->getTitle(), $nonMatchingResults)) {
        $matchingResults[] = $node->getTitle();
      }
    }

    echo "<pre>";
    echo "Matching supplier results:";
    print_r($matchingResults);
    echo "</pre>";

    echo "<pre>";
    echo "Non matching supplier results:";
    print_r($nonMatchingResults);
    echo "</pre>";


    $nids = [];
    /** @var TermInterface $category */
    foreach ($categories as $category) {
      if ($nid = $this->getResultId($category, $data)) {
        $nids[] = $nid;
      }
    }
    return $nids;
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
