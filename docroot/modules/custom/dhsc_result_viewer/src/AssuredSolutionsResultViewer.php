<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Url;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\webform\Utility\WebformOptionsHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
   * Symfony session.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * Logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Device option webform keys.
   *
   * @var string[]
   */
  protected array $deviceOptionKeys = [
    'device_option_yes',
    'device_option_no',
    'device_option_not_sure',
  ];

  /**
   * AssuredSolutionsResultViewer constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\path_alias\AliasManagerInterface $alias_manager
   *   The alias manager.
   * @param \Drupal\Core\Entity\Query\QueryFactoryInterface $entity_query
   *   The entity query factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
   *   Symfony session.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    AliasManagerInterface $alias_manager,
    QueryFactoryInterface $entity_query,
    RequestStack $request_stack,
    SessionInterface $session,
    LoggerChannelFactoryInterface $logger_factory,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->viewBuilder = $entity_type_manager->getViewBuilder('node');
    $this->paragraphViewBuilder = $entity_type_manager->getViewBuilder('paragraph');
    $this->taxonomyStorage = $entity_type_manager->getStorage('taxonomy_term');
    $this->configFactory = $config_factory;
    $this->aliasManager = $alias_manager;
    $this->entityQuery = $entity_query;
    $this->requestStack = $request_stack;
    $this->session = $session;
    $this->loggerFactory = $logger_factory;
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
            '#edit_link' => $search_criteria['edit_link'] ?? NULL,
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
   * Processes webform submission data to find matching and non-matching nodes.
   *
   * Takes webform data, normalises answers, queries for potential 'supplier'
   * nodes, filters them based on 'field_non_possible_answers', identifies
   * non-matching nodes (and the reasons if available), formats the results,
   * stores them in the session, and returns the structured data.
   *
   * @param array $data
   *   Raw webform submission data associative array.
   * @param \Drupal\webform\WebformInterface $webform
   *   The webform entity associated with the submission.
   *
   * @return array
   *   Returns an empty structure if no valid answers are processed.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   Can be thrown by entity storage operations.
   */
  protected function getResultIds(array $data, $webform) {
    // Initialise variables for results.
    $answers = [];
    $search_criteria = [];
    $submission_url = '';

    // 1. Get Submission URL (if token is present).
    // Extract the submission token from the URL query parameters to build a
    // link back.
    if ($submission_token = $this->requestStack->getCurrentRequest()->query->get('token')) {
      $webform_url = Url::fromRoute('entity.webform.canonical', ['webform' => $webform->id()])->toString();
      $submission_url = Url::fromUserInput($webform_url, ['query' => ['token' => $submission_token]])->toString();
    }

    // 2. Normalise submitted answers.
    // We end up with an array containing all checked answers.
    foreach ($data as $key => $answer) {
      if ($answer === '1') {
        $answers[$key] = $key;
      }
      if ($key === 'device_option') {
        $answers[$key] = $answer;
      }
    }

    // 3. Build human-readable search criteria for display.
    // Generate a structured list of the user's selections, grouped by form
    // section/page title.
    if (!empty($answers)) {

      $elementsDecoded = $webform->getElementsDecoded();
      foreach ($elementsDecoded as $key => $element) {
        if (isset($element['#type']) && $element['#type'] === 'webform_wizard_page_count') {
          $section_key = $key;
          $section_title = $element['#title'] ?? '';

          preg_match('/step_(\d+)/', $section_key, $matches);
          $step_number = isset($matches[1]) ? (int) $matches[1] : 1;
          $edit_link = $submission_url . '&edit-page=' . $step_number;

          // Initialise the section regardless of whether it has answers.
          $search_criteria[$section_key] = [
            'section' => $section_title,
            'edit_link' => $edit_link,
            'answers' => [],
          ];
        }
      }

      foreach ($answers as $key => $value) {
        // Check for radios using the exception keys.
        $is_radio = in_array($value, $this->deviceOptionKeys);

        // Get the element (radios need the key, checkboxes use key === value).
        $element = $is_radio ? $webform->getElement($key) : $webform->getElement($value);

        if (!$element || !isset($element['#webform_parent_key'])) {
          continue;
        }

        $section_key = $element['#webform_parent_key'];

        if ($is_radio && $element['#type'] === 'radios') {
          $options_text = WebformOptionsHelper::getOptionsText((array) $value, $element['#options']);
          $answer_text = reset($options_text);
        }
        else {
          $answer_text = $element['#title'] ?? '';
        }

        // Add answer to the correct section (already initialised earlier).
        if (!empty($answer_text)) {
          $search_criteria[$section_key]['answers'][] = $answer_text;
        }
      }

      rsort($search_criteria);
    }

    $matching_nids = [];
    $matching_nodes = [];

    // 4. Find initial potential matching nodes.
    if (!empty($answers)) {
      // Returns node IDs.
      $matching_nids = $this->getResultNodesWithMatches($answers);
    }

    // 5. Filter matches & identify / process non-matches.
    if (!empty($matching_nids)) {
      // Returns node objects.
      $matching_nodes = $this->filterResultNodesWithExclusions($matching_nids, $answers);

      // Get Node IDs of the final matching nodes.
      $matching_nids = array_map(function ($node) {
        return $node->id();
      }, $matching_nodes);

      // Find nodes that did NOT make the final match list.
      // Query for all other published 'supplier' nodes (excluding the final
      // matches).
      $matching_nids = array_unique($matching_nids);
      $non_matching_nids = $this->getNonMatches($matching_nids);

      // Process non-matches: explain exclusion if due to 'field_non
      // possible_answers'.
      if (!empty($non_matching_nids)) {
        /** @var \Drupal\node\NodeStorageInterface $node_storage */
        $node_storage = $this->entityTypeManager->getStorage('node');
        // Variable $nodes reused.
        /** @var \Drupal\node\Entity\Node[] $nodes */
        // Load non-matching nodes.
        $non_matching_nodes = $node_storage->loadMultiple($non_matching_nids);

        foreach ($non_matching_nodes as $node) {

          // Only explain exclusion if the node has non-empty
          // 'field_non_possible_answers'.
          if (empty($node->get('field_non_possible_answers')->getValue())) {
            continue;
          }

          $node_title = $node->getTitle();
          $nid = $node->id();
          $node_url = $this->aliasManager->getAliasByPath('/node/' . $nid);

          // Find which user answers conflicted with this node's
          // non-possible answers.
          foreach ($node->get('field_non_possible_answers')->getValue() as $value) {
            foreach ($answers as $answer) {
              // Check if the non-possible key matches the user's answer value.
              if ($value['value'] === $answer) {
                $no_matches[$nid]['title'] = $node_title;
                $no_matches[$nid]['node_url'] = $node_url;

                $field_key = NULL;
                // Special check for specific radio keys
                // (e.g., 'device_option_yes').
                if (in_array($value['value'], $this->deviceOptionKeys)) {
                  $field_key = substr($answer, 0, strrpos($answer, '_'));
                }
                // Get human-readable section/answer text for the conflicting
                // answer.
                $answer_value = $this->getFormElementValue($answer, $webform, $field_key);
                $no_matches[$nid]['answers'][$answer_value['section']][] = $answer_value['answer'];
              }
            }
          }
        }
      }
    }

    // 6. Assemble final results structure.
    // Keys:
    // - search_criteria: Flattened array of selected answers, grouped by
    //   section.
    // - matches: Matched supplier node entities.
    // - non_matching_count: Count of excluded suppliers.
    // - count: Count of matched suppliers.
    // - total_count: Sum of matched and non-matching suppliers.
    // - no_matches: Explanation of excluded suppliers, keyed by title.
    // - submission_url: URL back to the user's tokenised submission view.
    $result_data = [
      'search_criteria' => $search_criteria,
      'matches' => !empty($matching_nodes) ? $matching_nodes : [],
      'non_matching_count' => !empty($no_matches) ? count($no_matches) : 0,
      'count' => !empty($matching_nodes) ? count($matching_nodes) : 0,
      'total_count' => (!empty($matching_nodes) ? count($matching_nodes) : 0) + (!empty($no_matches) ? count($no_matches) : 0),
      'no_matches' => !empty($no_matches) ? $no_matches : [],
      'submission_url' => $submission_url ?? [],
    ];

    // Store results in session.
    $this->session->set('assured_solutions_result_data', $result_data);

    // Return assembled data.
    return $result_data;
  }

  /**
   * Returns all result nodes which contain at least one answer.
   *
   * @param array $answers
   *   Answer array.
   * @param bool $match
   *   Return matches or non-matches based on boolean.
   *
   * @return array
   *   Results array.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getResultNodesWithMatches($answers, bool $match = TRUE): array {
    // Fetch the entity type definition for 'node'.
    $entity_type = $this->entityTypeManager->getDefinition('node');

    // Create the query using the entity type definition.
    $query = $this->entityQuery->get($entity_type, 'AND')
      ->accessCheck(FALSE)
      ->condition('type', 'supplier')
      ->condition('status', 1);

    $group = $match ? $query->orConditionGroup() : $query->andConditionGroup();
    foreach ($answers as $answer) {
      $group->condition('field_answers_supplier.value', $answer, $match ? '=' : '<>');
    }
    $query->condition($group);

    $query->sort('title', 'ASC');

    $results = $query->execute();

    return $results;
  }

  /**
   * Returns all result nodes which contain at least one answer.
   *
   * @param array $results
   *   Results array.
   * @param array $answers
   *   Answers array.
   *
   * @return array
   *   Results array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function filterResultNodesWithExclusions(array $results, array $answers): array {
    // Load initial potential match nodes.
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = $this->entityTypeManager->getStorage('node');
    /** @var \Drupal\node\Entity\Node[] $nodes */
    $result_nodes = $node_storage->loadMultiple($results);

    // 5a. Filter: Remove nodes where user answers conflict with 'field_non
    // possible_answers'
    // Only keep nodes where none of the user's answers are listed in the
    // node's exclusion field.
    $matches = array_filter($result_nodes, function ($result_node) use ($answers) {
      $fieldItems = $result_node->get('field_non_possible_answers')->getValue();
      foreach ($fieldItems as $fieldItem) {
        // Check if $fieldItem['value'] exists in the $answers values.
        if (in_array($fieldItem['value'], array_values($answers))) {
          // Exclude node if conflict found.
          return FALSE;
        }
      }
      // Keep node if no conflicts found.
      return TRUE;
      // $matches now holds the final array of matching Node objects.
    });

    return $matches;
  }

  /**
   * Returns all supplier nodes which do not match user search criteria.
   *
   * @param array $nids
   *   Node ID array.
   *
   * @return array
   *   Array containing NIDs.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
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

    if (isset($element['#type']) && $element['#type'] === 'checkbox_count') {
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
  public function getResultsSummaryFromSession() {
    return $this->session->get('assured_solutions_result_data');
  }

  /**
   * {@inheritdoc}
   */
  public function questionsAllReset() {
    return $this->session->set('yes_to_all_questions', NULL);
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmissionByToken(string $token, string $webform_id): ?WebformSubmissionInterface {
    // Load the webform entity.
    $webform = $this->entityTypeManager->getStorage('webform')
      ->load($webform_id);

    if ($webform) {
      // Attempt to load submission using token.
      $submission = $this->entityTypeManager
        ->getStorage('webform_submission')
        ->loadFromToken($token, $webform);

      if ($submission) {
        return $submission;
      }

      // Log a warning if submission not found.
      $this->loggerFactory->get('dhsc_result_viewer')->warning(
        'Invalid submission token: @token for webform: @webform_id',
        ['@token' => $token, '@webform_id' => $webform_id]
      );
    }
    else {
      // Log a warning if the webform ID is invalid.
      $this->loggerFactory->get('dhsc_result_viewer')->warning(
        'Invalid webform ID provided: @webform_id',
        ['@webform_id' => $webform_id]
      );
    }

    return NULL;
  }

}
