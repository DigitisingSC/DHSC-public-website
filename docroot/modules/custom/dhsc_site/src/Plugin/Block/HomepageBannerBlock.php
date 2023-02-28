<?php

namespace Drupal\dhsc_site\Plugin\Block;

/**
 * Class HomepageBannerBlock.
 *
 * @package Drupal\dhsc_site\Plugin\Block
 *
 * @Block(
 *   id = "homepage_banner",
 *   admin_label = "Homepage banner",
 *   context_definitions = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *       label = @Translation("Current node"),
 *       constraints = {
 *         "Bundle" = {
 *           "dhsc_homepage"
 *         },
 *       }
 *     )
 *   }
 * )
 */
class HomepageBannerBlock extends HomepageAbstractBlockBase {

  /**
   * The entity view builder interface.
   *
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  private $viewBuilder;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    if ($this->getHomepage($this->node)) {
      if ($banner = $this->getHomepageBanner()) {
        $viewBuilder = $this->entityTypeManager->getViewBuilder('paragraph');
        $build = $viewBuilder->view($banner);
      }
    }

    return $build;
  }

}
