<?php

namespace Drupal\dhsc_site\EventSubscriber;

use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Render\RendererInterface;
use Drupal\views\Ajax\ViewAjaxResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Response subscriber to handle AJAX responses.
 */
class AjaxResponseSubscriber implements EventSubscriberInterface {

    /**
   * The Renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * CustomEventSubscriber constructor.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The Renderer service.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * Renders the ajax commands right before preparing the result.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The response event, which contains the possible AjaxResponse object.
   */
  public function onRespond(ResponseEvent $event) {
    $response = $event->getResponse();

    // Only alter views ajax responses
    if (!($response instanceof ViewAjaxResponse)) {
      return;
    }

    $view = $response->getView();
    // Only alter commands if view is ours
    if (!in_array($view->storage->id(), ['digital_skills_training_page', 'digital_skills_training_search'])) {
      return;
    }

    $args = [
      '#theme' => 'skills_active_filters',
      '#links' => $this->buildPillLinks($view->exposed_raw_input),
    ];

    $html_output = $this->renderer->renderPlain($args);

    // Add new replace response to AJAX
    $response->addCommand(new ReplaceCommand('.view-digital-skills-training-page__pills .m-active-filter__wrapper', $html_output));
    $event->setResponse($response);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onRespond'];
    return $events;
  }

  /**
   * Build skills active filter links for the pills
   *
   *  @var array $exposedFormInput
   *  @return array
   */
  private function buildPillLinks($exposedFormInput) {
    $term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $activeFilters = $activeFilterLabels = [];

    // Loop through active filters
    foreach ($exposedFormInput as $key => $values) {
      // Simple text filter
      if ($key == 'skills_for_care_endorsed') {
        foreach ($values as $value) {
          $activeFilterLabels[] = [
            'title' => t('Skills for Care endorsed: ') . ucfirst($value),
            'target' => $value,
          ];
        }
      }
      // Simple text filter
      elseif ($key == 'price') {
        foreach ($values as $value) {
          $activeFilterLabels[] = [
            'title' => ucfirst($value),
            'target' => $value,
          ];
        }
      }
      // Taxonomy term filters
      elseif (!empty($values)) {
        $activeFilters = array_merge($activeFilters, $values);
      }
    }

    // Load active filter taxonomy terms
    $selectedTerms = $term_storage->loadMultiple($activeFilters);

    // Assign tax terms to links pairs
    /** @var \Drupal\Core\Entity\EntityInterface $term */
    foreach ($selectedTerms as $term) {
      $activeFilterLabels[] = [
        'title' => $term->label(),
        'target' => $term->id(),
      ];
    }

    return $activeFilterLabels;
  }

}
