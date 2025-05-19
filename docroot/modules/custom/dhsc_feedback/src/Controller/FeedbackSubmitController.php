<?php

namespace Drupal\dhsc_feedback\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Handles AJAX feedback form submissions.
 */
class FeedbackSubmitController extends ControllerBase {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $bundleInfo;

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new FeedbackSubmitController.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, RequestStack $request_stack, EntityTypeBundleInfoInterface $bundle_info, AccountProxyInterface $current_user) {
    $this->entityTypeManager = $entity_type_manager;
    $this->requestStack = $request_stack;
    $this->bundleInfo = $bundle_info;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('request_stack'),
      $container->get('entity_type.bundle.info'),
      $container->get('current_user'),
    );
  }

  /**
   * Handles POST requests for feedback submission.
   */
  public function submit(Request $request): JsonResponse {
    // Decode JSON request payload.
    $data = json_decode($request->getContent(), TRUE);
    // Extract the feedback type from request data.
    $type = $data['feedback_type'] ?? NULL;

    // Validate that the feedback type is recognised.
    if (!in_array($type, ['yes', 'no', 'problem'], TRUE)) {
      return new JsonResponse([
        'status' => 'error',
        'message' => 'Invalid feedback type',
        'received_type' => $type,
        'full_data' => $data,
      ], 400);
    }

    // Determine message bundle based on feedback type.
    $bundle = match ($type) {
      'problem' => 'page_feedback_problem',
      'yes', 'no' => 'page_feedback_improvement',
    };

    // Ensure a bundle was determined.
    if (!$bundle) {
      return new JsonResponse(['status' => 'error', 'message' => 'Unable to determine feedback bundle.'], 400);
    }

    // Load message storage and validate the bundle exists.
    $storage = $this->entityTypeManager->getStorage('message');
    $bundle_info = $this->bundleInfo->getBundleInfo('message');
    if (!isset($bundle_info[$bundle])) {
      return new JsonResponse(['status' => 'error', 'message' => 'Feedback bundle doesn\'t exist.'], 400);
    }

    // Build message entity values.
    $values = [
      'template' => $bundle,
      'uid' => $this->currentUser->id(),
      'field_feedback_type' => $type,
      'field_page_url' => $data['page_url'] ?? '',
    ];

    // Add problem-specific fields if applicable.
    if ($bundle === 'page_feedback_problem') {
      $values['field_problem_context'] = $data['context'] ?? '';
      $values['field_problem_description'] = $data['description'] ?? '';
    }
    // Otherwise, include any improvement suggestion.
    elseif (!empty($data['suggestion'])) {
      $values['field_improvement_suggestion'] = $data['suggestion'];
    }

    // Create and save the message entity.
    $message = $storage->create($values);
    $message->save();

    return new JsonResponse(['status' => 'success']);
  }

}
