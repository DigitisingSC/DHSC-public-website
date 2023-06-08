<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a search trigger block.
 *
 * @Block(
 *   id = "dhsc_site_search_trigger",
 *   admin_label = @Translation("Search trigger"),
 *   category = @Translation("DHSC Site")
 * )
 */
class SearchTriggerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t("Search trigger Block"),
    ];
  }

}
