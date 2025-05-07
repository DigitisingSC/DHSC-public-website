<?php

namespace Drupal\dhsc_result_viewer\Routing;

use Drupal\dhsc_result_viewer\Service\WebformToolService;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\dhsc_result_viewer\Constants\WebformToolConstants;

/**
 * Handles redirection logic for webform submissions.
 */
class RouteSubscriber extends RouteSubscriberBase implements EventSubscriberInterface {

  /**
   * The request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  private RequestStack $requestStack;

  /**
   * The custom webform tool service.
   *
   * @var \Drupal\dhsc_result_viewer\Service\WebformToolService
   */
  private WebformToolService $webformToolService;

  /**
   * Constructs a new RouteSubscriber object.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack service.
   * @param \Drupal\dhsc_result_viewer\Service\WebformToolService $webformToolService
   *   The custom webform tool service.
   */
  public function __construct(
    RequestStack $requestStack,
    WebformToolService $webformToolService,
  ) {
    $this->requestStack = $requestStack;
    $this->webformToolService = $webformToolService;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection): void {
    // No need to modify _controller, just handle redirection dynamically.
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      // Priority 30 ensures it runs early.
      KernelEvents::REQUEST => ['checkForRedirection', 30],
    ];
  }

  /**
   * Checks if the user should be redirected to their latest webform submission.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The request event.
   */
  public function checkForRedirection(RequestEvent $event): void {
    $request = $event->getRequest();

    $webform = $request->attributes->get('webform');
    if (!$webform) {
      return;
    }

    $webform_id = $webform->get('id');

    if (in_array($webform_id, WebformToolConstants::WEBFORM_AUTOSAVE, TRUE)) {
      $route_name = $request->attributes->get('_route');

      // Only apply redirection for the webform page.
      if ($route_name !== 'entity.webform.canonical') {
        return;
      }

      // Prevent redirect loop by checking if the token is already present.
      if ($this->requestStack->getCurrentRequest()->query->get('token')) {
        return;
      }

      // Get session ID.
      $submission_id = $this->webformToolService->getSubmissionId($webform_id);

      if (!$submission_id) {
        $submission = $this->webformToolService->createDraftSubmission($webform_id);
      }
      else {
        $submission = WebformSubmission::load($submission_id);
      }

      if ($submission->isDraft()) {
        $token = $submission->getToken();

        // Build the redirect URL.
        $redirect_url = '/form/' . str_replace('_', '-', $webform_id) . '?token=' . $token;
        $response = new RedirectResponse($redirect_url);
        $event->setResponse($response);
      }
    }
  }

}
