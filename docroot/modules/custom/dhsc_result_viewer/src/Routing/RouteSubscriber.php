<?php

namespace Drupal\dhsc_result_viewer\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
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
   * The private temp store factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected PrivateTempStoreFactory $tempStoreFactory;

  /**
   * Constructs a new RouteSubscriber.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The private temp store factory.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory) {
    $this->tempStoreFactory = $temp_store_factory;
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

    $webform_id = $webform->id();

    if (in_array($webform_id, WebformToolConstants::WEBFORM_TOOLS_THEMED, TRUE)) {
      $route_name = $request->attributes->get('_route');

      // Only apply redirection for the webform page.
      if ($route_name !== 'entity.webform.canonical') {
        return;
      }

      // Prevent redirect loop by checking if the token is already present.
      if ($request->query->has('token')) {
        return;
      }

      // Retrieve the last submission and step from private tempstore.
      $tempstore = $this->tempStoreFactory->get('dhsc_result_viewer');
      $submission_id = $tempstore->get('last_submission_' . $webform_id);

      if ($submission_id && ($submission = WebformSubmission::load($submission_id))) {
        $token = $submission->getToken();

        // Build the redirect URL.
        $redirect_url = '/form/' . str_replace('_', '-', $webform_id) . '?token=' . $token;
        $response = new RedirectResponse($redirect_url);
        $event->setResponse($response);
      }
    }
  }

}
