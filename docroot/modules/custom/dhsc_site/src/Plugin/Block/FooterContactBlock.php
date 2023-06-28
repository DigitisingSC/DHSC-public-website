<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a footer contact block.
 *
 * @Block(
 *   id = "footer_contact",
 *   admin_label = @Translation("Footer contact"),
 *   category = @Translation("DHSC Site")
 * )
 */
class FooterContactBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $site_settings = \Drupal::service('site_settings.loader');
    $contacts = $site_settings->loadByFieldset('footer')['footer_contacts'];
    if (!empty($contacts)) {
      $link = $contacts['field_settings_link'];
      $link = Link::fromTextAndUrl($link['title'], Url::fromUri($link['uri']));
    }
    return [
      '#theme' => 'footer_contact',
      '#link' => $link ?? '',
      '#phone' => $contacts['field_settings_phone'] ?? '',
    ];
  }

}
