<?php

namespace Drupal\dhsc_site\Breadcrumb;

use Drupal\system\PathBasedBreadcrumbBuilder;
use Drupal\Core\Routing\RouteMatchInterface;

class PageBreadcrumbBuilder extends PathBasedBreadcrumbBuilder
{

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match)
  {
    return parent::build($route_match);
  }
}
