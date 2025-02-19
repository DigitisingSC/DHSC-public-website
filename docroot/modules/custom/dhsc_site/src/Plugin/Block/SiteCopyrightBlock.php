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

    $site_settings = \Drupal::service('plugin.manager.site_settings_loader')->getActiveLoaderPlugin();
    $copyright = $site_settings->loadByGroup('footer')['footer_copyright'];

    return [
      '#theme' => 'site_copyright',
      '#text' => $copyright,
    ];
  }
}
