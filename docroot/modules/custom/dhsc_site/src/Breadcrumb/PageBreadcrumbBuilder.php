<?php

namespace Drupal\dhsc_site\Breadcrumb;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\system\PathBasedBreadcrumbBuilder;

/**
 * Provides a breadcrumb builder that extends the path-based breadcrumb builder.
 *
 * This builder customizes breadcrumb generation based on the matched route.
 *
 * @inheritdoc
 */
class PageBreadcrumbBuilder extends PathBasedBreadcrumbBuilder {

  /**
   * Builds the breadcrumb for the given route.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   *
   * @return \Drupal\Core\Breadcrumb\Breadcrumb
   *   The breadcrumb object.
   */
  public function build(RouteMatchInterface $route_match) {
    return parent::build($route_match);
  }

}
