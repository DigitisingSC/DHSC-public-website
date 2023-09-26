<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a copyright block.
 *
 * @Block(
 *   id = "site_copyright",
 *   admin_label = @Translation("Copyright"),
 *   category = @Translation("DHSC Site")
 * )
 */
class SiteCopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $site_settings = \Drupal::service('site_settings.loader');
    $copyright = $site_settings->loadByFieldset('footer')['footer_copyright'];

    return [
      '#theme' => 'site_copyright',
      '#text' => $copyright,
    ];
  }
}
