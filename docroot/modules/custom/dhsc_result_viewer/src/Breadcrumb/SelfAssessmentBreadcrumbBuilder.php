<?php

namespace Drupal\dhsc_result_viewer\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\webform\Entity\Webform;

/**
 * Class SelfAssessmentBreadcrumbBuilder.
 */
class SelfAssessmentBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * Constructs the HelpBreadcrumbBuilder.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation service.
   */
  public function __construct(TranslationInterface $string_translation) {
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    // Check if the current route matches your custom page controller route.
    return ($route_match->getRouteName() == 'dhsc_result_viewer.result_summary_self_assessment');
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();

    $breadcrumb->addCacheContexts(['url.path.parent']);
    // Add the homepage link as the first item in the breadcrumb trail.
    $breadcrumb->addLink(Link::createFromRoute($this->t('Home'), '<front>'));

    // Add Self assessment tool form to breadcrumb trail.
    /** @var \Drupal\webform\Entity\Webform $webform */
    $webform = Webform::load('self_assessment_tool');

    // Check if the webform exists.
    if ($webform && $webform->status()) {
      // Get the route name of the webform.
      $webformURL = $webform->toUrl();

      $breadcrumb->addLink(Link::fromTextAndUrl($this->t('Get started with digital'), $webformURL));
    }

    return $breadcrumb;
  }

}
