<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactoryInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\webform\Utility\WebformOptionsHelper;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AssuredSolutionsResultViewer.
 *
 * @package Drupal\dhsc_assured_solutions_result_viewer
 */
class AssuredSolutionsResultViewer implements AssuredSolutionsInterface {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * View builder for nodes.
   *
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  protected $viewBuilder;

  /**
   * View builder for paragraphs.
   *
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  protected $paragraphViewBuilder;

  /**
   * Taxonomy storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
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
   * The alias manager.
   *
   * @var \Drupal\path_alias\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * The entity query service.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactoryInterface
   */
  protected $entityQuery;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Device option webform keys.
   *
   * @var array
   */
  protected $deviceOptionKeys;

  /**
   * AssuredSolutionsResultViewer constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The temp store factory.
   * @param \Drupal\path_alias\AliasManagerInterface $alias_manager
   *   The alias manager.
   * @param \Drupal\Core\Entity\Query\QueryFactoryInterface $entity_query
   *   The entity query factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    PrivateTempStoreFactory $temp_store_factory,
    AliasManagerInterface $alias_manager,
    QueryFactoryInterface $entity_query,
    RequestStack $request_stack,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->viewBuilder = $entity_type_manager->getViewBuilder('node');
    $this->paragraphViewBuilder = $entity_type_manager->getViewBuilder('paragraph');
    $this->taxonomyStorage = $entity_type_manager->getStorage('taxonomy_term');
    $this->configFactory = $config_factory;
    $this->tempStore = $temp_store_factory->get('dhsc_result_viewer');
    $this->aliasManager = $alias_manager;
    $this->entityQuery = $entity_query;
    $this->requestStack = $request_stack;
    $this->deviceOptionKeys = [
      'device_option_yes',
      'device_option_no',
      'device_option_not_sure',
    ];
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
  public function getResultsSummary($data, $webform) {
    $results = $this->getResultIds($data, $webform);

    if (!$results) {
      return NULL;
    }

    foreach ($results as $key => $result) {
      if ($key === 'search_criteria') {
        foreach ($result as $key => $search_criteria) {
          $values['search_criteria'][] = [
            '#theme' => 'search_criteria',
            '#section' => $search_criteria['section'] ?? NULL,
            '#answers' => $search_criteria['answers'] ?? NULL,
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
            '#title' => $no_match['title'] ?? NULL,
            '#url' => $no_match['node_url'] ?? NULL,
            '#answers' => $no_match['answers'] ?? NULL,
          ];
        }
      }
    }

    $values = [
      'submission_url' => $results['submission_url'] ?? NULL,
      'search_criteria' => !empty($values['search_criteria']) ? $values['search_criteria'] : NULL,
      'result_items' => !empty($values['result_items']) ? $values['result_items'] : NULL,
      'no_matches' => !empty($values['no_matches']) ? $values['no_matches'] : NULL,
      'count' => $results['count'] ?? NULL,
      'non_matching_count' => $results['non_matching_count'] ?? NULL,
      'total_count' => $results['total_count'] ?? NULL,
    ];

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  protected function getResultIds(array $data, $webform) {
    $answers = [];
    $search_criteria = [];

    // Extract unique submission token value from URL.
    if ($submission_token = $this->requestStack->getCurrentRequest()->query->get('token')) {
      $webform_url = Url::fromRoute('entity.webform.canonical', ['webform' => $webform->id()])->toString();
      $submission_url = Url::fromUserInput($webform_url, ['query' => ['token' => $submission_token]])->toString();
    }

    foreach ($data as $key => $answer) {
      // Check we have an answer value in both checkboxes and radio form fields.
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
          }
          elseif (count($value) === 1) {
            $item = reset($value);
          }
          $section = $webform->getElement($element['#webform_parent_key'])['#title'];
          $search_criteria[$section]['section'] = $section;
          $search_criteria[$section]['answers'][] = $item;
        }
      }
      sort($search_criteria);
    }

    if (!empty($answers)) {
      $results = $this->getResultNodes($answers);
    }

    if (!empty($results)) {
      /** @var \Drupal\node\NodeStorageInterface $node_storage */
      $node_storage = $this->entityTypeManager->getStorage('node');
      /** @var \Drupal\node\Entity\Node[] $nodes */
      $nodes = $node_storage->loadMultiple($results);

      $no_matches = [];

      // Check against non possible answers values on the supplier node to
      // further refine matches.
      $matches = array_filter($nodes, function ($node) use ($answers) {
        $fieldItems = $node->get('field_non_possible_answers')->getValue();
        foreach ($fieldItems as $value) {
          if (count(array_intersect($value, $answers)) > 0) {
            return FALSE;
          }
        }
        return TRUE;
      });
      $nids = array_map(function ($node) {
        return $node->id();
      }, $matches);

      // Query for all supplier nodes where there is no match.
      $nids = array_unique($nids);

      $results = $this->getNonMatches($nids);

      if (!empty($results)) {
        /** @var \Drupal\node\NodeStorageInterface $node_storage */
        $node_storage = $this->entityTypeManager->getStorage('node');
        /** @var \Drupal\node\Entity\Node[] $nodes */
        $no_result_nodes = $node_storage->loadMultiple($results);

        foreach ($no_result_nodes as $node) {

          if (empty($node->get('field_non_possible_answers')->getValue())) {
            continue;
          }

          $node_title = $node->getTitle();
          $node_url = $this->aliasManager->getAliasByPath('/node/' . $node->id());

          foreach ($node->get('field_non_possible_answers')->getValue() as $value) {
            foreach ($answers as $answer) {
              if ($value['value'] === $answer) {

                $no_matches[$node_title]['title'] = $node_title;
                $no_matches[$node_title]['node_url'] = $node_url;

                $field_key = NULL;
                // We don't have the element key for the first radio field
                // so check against pre-defined values and set the field_key
                // from the form.
                if (in_array($value['value'], $this->deviceOptionKeys)) {
                  $field_key = substr($answer, 0, strrpos($answer, '_'));
                }
                $answer_value = $this->getFormElementValue($answer, $webform, $field_key);
                $no_matches[$node_title]['answers'][$answer_value['section']][] = $answer_value['answer'];
              }
            }
          }
        }
      }
    }

    $result_data = [
      'search_criteria' => $search_criteria,
      'matches' => !empty($matches) ? $matches : [],
      'non_matching_count' => !empty($no_matches) ? count($no_matches) : [],
      'count' => !empty($matches) ? count($matches) : [],
      'total_count' => !empty($matches) ? count($matches) + count($no_matches) : [],
      'no_matches' => !empty($no_matches) ? $no_matches : [],
      'submission_url' => $submission_url ?? [],
    ];

    // Save result data in tempstore for email result behaviour.
    $this->tempStore->set('assured_solutions_result_data', $result_data);

    return $result_data;
  }

  /**
   * Returns all result nodes which contain at least one answer.
   */
  public function getResultNodes($answers) {
    // Fetch the entity type definition for 'node'.
    $entity_type = $this->entityTypeManager->getDefinition('node');

    // Create the query using the entity type definition.
    $query = $this->entityQuery->get($entity_type, 'AND')
      ->accessCheck(FALSE)
      ->condition('type', 'supplier')
      ->condition('status', 1);

    $or = $query->orConditionGroup();
    foreach ($answers as $answer) {
      $or->condition('field_answers_supplier.value', $answer, '=');
    }
    $query->condition($or);

    $query->sort('title', 'ASC');

    $results = $query->execute();

    return $results;
  }

  /**
   * Returns all supplier nodes which do not match user search criteria.
   *
   * @param array $nids
   *   Node ID array.
   *
   * @return array
   *   Results array.
   */
  public function getNonMatches(array $nids) {
    $entity_type = $this->entityTypeManager->getDefinition('node');
    $query = $this->entityQuery->get($entity_type, 'AND')
      ->accessCheck(FALSE)
      ->condition('type', 'supplier')
      ->condition('status', 1);
    foreach ($nids as $nid) {
      $query->condition('nid', $nid, 'NOT IN');
    }

    $query->sort('title', 'ASC');

    $results = $query->execute();
    return $results;
  }

  /**
   * Returns the webform field answer value for checkbox and radio elements.
   *
   * @param string $value
   *   Value string.
   * @param object $webform
   *   Webform object.
   * @param string|null $field_key
   *   Field key.
   *
   * @return array|void
   *   Array containing section and answer if available.
   */
  public function getFormElementValue($value, $webform, $field_key = NULL) {
    $element = $field_key ? $webform->getElement($field_key) : $webform->getElement($value);

    if ($element === NULL) {
      return NULL;
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
      }
      elseif (count($value) === 1) {
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
  public function getSubmissionId() {
    return $this->tempStore->get('sid');
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
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
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
