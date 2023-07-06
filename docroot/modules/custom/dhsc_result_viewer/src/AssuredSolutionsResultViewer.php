<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\TermInterface;
use Drupal\webform\Utility\WebformOptionsHelper;

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
   * Device option webform keys.
   *
   * @var array
   */
  protected $device_option_keys;

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
    $this->device_option_keys = ['device_option_yes', 'device_option_no', 'device_option_not_sure'];
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
  public function getResultsSummary($data, $webform)
  {
    $results = $this->getResultIds($data, $webform);

    if (!$results) {
      return;
    }

    foreach ($results as $key => $result) {
      if ($key === 'search_criteria') {
        foreach ($result as $key => $search_criteria) {
          $values['search_criteria'][] = [
            '#theme' => 'search_criteria',
            '#section' => isset($search_criteria['section']) ? $search_criteria['section'] : NULL,
            '#answers' => isset($search_criteria['answers']) ? $search_criteria['answers'] : NULL,
          ];
        }
      }
      if ($key === 'matches') {
        foreach ($result as $node) {
          $values['result_items'][] = [
            '#theme' => 'result_item',
            '#content' => $this->viewBuilder->view($node, 'teaser'),
          ];
        }
      }
      if ($key === 'no_matches') {
        foreach ($result as $key => $no_match) {
          $values['no_matches'][] = [
            '#theme' => 'no_match',
            '#title' => isset($no_match['title']) ? $no_match['title'] : NULL,
            '#url' => isset($no_match['node_url']) ? $no_match['node_url'] : NULL,
            '#answers' => isset($no_match['answers']) ? $no_match['answers'] : NULL,
          ];
        }
      }
    }

    $values = [
      'submission_url' => isset($results['submission_url']) ? $results['submission_url'] : NULL,
      'search_criteria' => !empty($values['search_criteria']) ? $values['search_criteria'] : NULL,
      'result_items' => !empty($values['result_items']) ? $values['result_items'] : NULL,
      'no_matches' => !empty($values['no_matches']) ? $values['no_matches'] : NULL,
      'count' => isset($results['count']) ? $results['count'] : NULL,
      'non_matching_count' => isset($results['non_matching_count']) ? $results['non_matching_count'] : NULL,
      'total_count' => isset($results['total_count']) ? $results['total_count'] : NULL,
    ];

    return $values;
  }

  /**
   * Get ids of all result nodes.
   *
   * @param array $data
   *   Webform values.
   * @param object $webform
   *   Webform entity.
   * @param bool $top_tips
   *   Check if top tip.
   *
   * @return array
   *   Return result ids.
   */
  protected function getResultIds(array $data, $webform)
  {
    $answers = [];
    $search_criteria = [];
    $field_key = NULL;

    // Extract unique submission token value from URL.
    if ($submission_token = \Drupal::request()->query->get('token')) {
      $submission_url = Url::fromUserInput($webform->url(), ['query' => ['token' => $submission_token]])->toString();
    }

    foreach ($data as $key => $answer) {
      // check we have an answer value in both checkboxes and radio form fields.
      if ($answer === '0' || empty($answer)) {
        continue;
      }
      if ($answer === '1') {
        $answer = $key;
      }

      $answers[$key] = $answer;
    }

    if (!empty($answers)) {
      // Add all search criteria grouped by webform wizard title.
      foreach ($answers as $key => $value) {
        $element = $webform->getElement($value) ?? $webform->getElement($key);
        if (isset($element['#type']) && $element['#type'] === 'checkbox') {
          $section = $webform->getElement($element['#webform_parent_key'])['#title'];
          $search_criteria[$section]['section'] = $section;
          $search_criteria[$section]['answers'][] = $element['#title'];
        }
        if (isset($element['#type']) && $element['#type'] === 'radios') {
          // Use the options text for radio fields.
          $value = WebformOptionsHelper::getOptionsText((array) $value, $element['#options']);
          if (count($value) > 1) {
            $item = $value;
          } elseif (count($value) === 1) {
            $item = reset($value);
          }
          $section = $webform->getElement($element['#webform_parent_key'])['#title'];
          $search_criteria[$section]['section'] = $section;
          $search_criteria[$section]['answers'][] = $item;
        }
      }
      sort($search_criteria);
    }

    $results = $this->getResultNodes($answers);

    if (!empty($results)) {
      $nodes = Node::loadMultiple($results);
      $no_matches = [];
      $matches = [];
      $nids = [];

      foreach ($nodes as $node) {
        $nids[] = $node->id();
        $matches[] = $node;
      }

      // query for all supplier nodes where there is no match
      $nids = array_unique($nids);

      $results = $this->getNonMatches($nids);

      if (!empty($results)) {
        $no_result_nodes = Node::loadMultiple($results);

        foreach ($no_result_nodes as $node) {

          if (empty($node->get('field_non_possible_answers')->getValue())) {
            continue;
          }

          $node_title = $node->getTitle();
          $node_url = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node->id());
          $no_matches[$node_title]['title'] = $node_title;
          $no_matches[$node_title]['node_url'] = $node_url;

          foreach ($node->get('field_non_possible_answers')->getValue() as $value) {
            foreach ($answers as $answer) {
              if ($value['value'] === $answer) {

                $field_key = NULL;
                // We don't have the element key for the first radio field
                // so check against pre-defined values and set the field_key from the form.
                if (in_array($value['value'], $this->device_option_keys)) {
                  $field_key = substr($answer, 0, strrpos($answer, '_'));
                }
                $answer_value = $this->getFormElementValue($field_key, $answer, $webform);
                $no_matches[$node_title]['answers'][] = $answer_value;
              }
            }
          }
        }
      }
    }

    $result_data = [
      'search_criteria' => $search_criteria,
      'matches' => $matches,
      'non_matching_count' => count($no_matches),
      'count' => count($matches),
      'total_count' => count($matches) + count($no_matches),
      'no_matches' => $no_matches,
      'submission_url' => isset($submission_url) ? $submission_url : NULL,
    ];

    // Save result data in tempstore for email result behaviour.
    $this->tempStore->set('result_data', $result_data);

    return $result_data;
  }

  /**
   * Returns all result nodes which contain at least one answer.
   *
   * @param array $answers
   * @return array
   */
  public function getResultNodes($answers)
  {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'supplier');
    $or = $query->orConditionGroup();
    foreach ($answers as $key => $answer) {
      $or->condition('field_answers_supplier.value', $answer);
    }
    $query->condition($or);

    $results = $query->execute();
    return $results;
  }

  /**
   * Returns all suplier nodes which do not match user search criteria.
   *
   * @param array $nids
   * @return array
   */
  public function getNonMatches($nids)
  {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'supplier');
    foreach ($nids as $nid) {
      $query->condition('nid', $nid, 'NOT IN');
    }
    $results = $query->execute();
    return $results;
  }

  /**
   * Returns the webform field answer value for checkbox and radio elements.
   *
   * @param string $value
   * @param object $webform
   * @return void
   */
  public function getFormElementValue(string $field_key = NULL, $value, $webform)
  {
    $element = $field_key ? $webform->getElement($field_key) : $webform->getElement($value);

    if ($element === NULL) {
      return;
    }

    $section = $webform->getElement($element['#webform_parent_key'])['#title'];

    if (isset($element['#type']) && $element['#type'] === 'checkbox') {
      // Use the title for checkbox fields.
      $title = $element['#title'];
      return [
        'section' => $section,
        'answer' => $title,
      ];
    }
    if (isset($element['#type']) && $element['#type'] === 'radios') {
      // Use the options text for radio fields.
      $value = WebformOptionsHelper::getOptionsText((array) $value, $element['#options']);
      if (count($value) > 1) {
        $item = $value;
      } elseif (count($value) === 1) {
        $item = reset($value);
      }
      return [
        'section' => $section,
        'answer' => $item,
      ];
    }
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
          ->condition('field_answers_supplier', $data[$machine_name])
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
