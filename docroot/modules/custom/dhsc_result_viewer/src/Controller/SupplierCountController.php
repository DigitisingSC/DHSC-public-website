<?php

namespace Drupal\dhsc_result_viewer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Drupal\webform\Entity\WebformSubmission;

/**
 * Controller for returning supplier counts based on current selections.
 */
class SupplierCountController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The session service.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

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
   * Constructs the controller.
   *
   * Injects the entity type manager and session service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, SessionInterface $session) {
    $this->entityTypeManager = $entity_type_manager;
    $this->session = $session;
  }

  /**
   * Returns supplier counts for each answer, filtered by current selections.
   */
  public function getCounts(Request $request): JsonResponse {
    // Check for correct content type.
    if ($request->headers->get('Content-Type') !== 'application/json') {
      return new JsonResponse(['error' => 'Unsupported Media Type'], 415);
    }

    // Decode and validate incoming JSON.
    $input = json_decode($request->getContent(), TRUE);
    if (!is_array($input) || !isset($input['form_state']) || !is_array($input['form_state'])) {
      return new JsonResponse(['error' => 'Invalid request format.'], 400);
    }
    $submitted_form_state = $input['form_state'];

    $webform_id = 'assured_solutions_tool';
    $submission_id = $this->session->get('last_submission_' . $webform_id);

    $current_answers = [];

    // If a previous submission exists and belongs to the current user.
    if ($submission_id) {
      $submission = WebformSubmission::load($submission_id);
      if ($submission && $submission->getOwnerId() === $this->currentUser()->id()) {
        $data = $submission->getData();
        $current_answers = array_keys(array_filter(
          $data,
          fn($value) => $value === "1"
        ));

        if (isset($data['device_option']) && in_array($data['device_option'], $this->deviceOptionKeys, TRUE)) {
          $current_answers[] = $data['device_option'];
        }
      }
    }

    // Merge submitted form state with the session-based state, tracking current
    // answers.
    if (!empty($submitted_form_state)) {
      $current_answers = array_fill_keys($current_answers, TRUE);
      foreach ($submitted_form_state as $key => $value) {
        if ($value === TRUE) {
          $current_answers[$key] = TRUE;
        }
        elseif ($value === FALSE) {
          unset($current_answers[$key]);
        }
      }
      $current_answers = array_keys($current_answers);
    }

    // Load all published supplier nodes for evaluation.
    $storage = $this->entityTypeManager->getStorage('node');
    $supplier_nodes = $storage->loadByProperties([
      'type' => 'supplier',
      'status' => 1,
    ]);

    // Initialise all answer counts to 0 for all possible supplier answers.
    $all_possible_answers = [];
    foreach ($supplier_nodes as $node) {
      $answers = array_column($node->get('field_answers_supplier')->getValue(), 'value');
      foreach ($answers as $answer) {
        $all_possible_answers[$answer] = 0;
      }
    }

    $answer_counts = $all_possible_answers;
    $included_supplier_count = 0;

    // Count answers only from suppliers not excluded by current answers.
    foreach ($supplier_nodes as $node) {
      $answers = array_column($node->get('field_answers_supplier')->getValue(), 'value');
      $excluded = array_column($node->get('field_non_possible_answers')->getValue(), 'value');

      if (!empty($current_answers) && array_intersect($excluded, $current_answers)) {
        continue;
      }

      $included_supplier_count++;

      foreach ($answers as $answer) {
        $answer_counts[$answer]++;
      }
    }

    // Return the counts and total matching suppliers as JSON.
    return new JsonResponse([
      'counts' => $answer_counts,
      'total_counts' => $included_supplier_count,
    ]);
  }

}
