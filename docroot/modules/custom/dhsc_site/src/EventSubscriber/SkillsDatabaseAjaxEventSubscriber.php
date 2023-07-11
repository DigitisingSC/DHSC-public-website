<?php

namespace Drupal\dhsc_site\EventSubscriber;

use Drupal\views\Ajax\ViewAjaxResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Response subscriber to handle AJAX responses.
 */
class SkillsDatabaseAjaxEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onResponse'];
    return $events;
  }

  /**
   * Event callback method.
   */
  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();

    // Only alter views ajax responses.
    if (!($response instanceof ViewAjaxResponse)) {
      return;
    }
    $view = $response->getView();

    $kek = [];

    $active_filters = $view->exposed_raw_input;

    // Perform actions with the active filters.
    // For example, print the active filters.
    foreach ($active_filters as $filter => $value) {
      $kek[$filter] = $value;
    }


    // echo '<pre>';
    // die(var_dump(
    //   $kek,
    //   $view->exposed_widgets,
    //   $view->getExposedInput(),
    // ));


    if ($view->storage->id() == 'digital_skills_training_page') {
      $view->filter_list;
    }

    if ($response instanceof ViewAjaxResponse) {
      // Modify the AJAX response as needed.
      $commands = $response->getCommands();
      foreach ($commands as $command) {
        // if ($command instanceof ReplaceCommand) {
        //   // Modify the replace command as needed.
        //   $command->setSelector('.my-selector');
        // }
      }
    }
  }
}