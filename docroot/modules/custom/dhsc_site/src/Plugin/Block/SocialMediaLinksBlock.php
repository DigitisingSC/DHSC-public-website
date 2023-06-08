<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a social media links block.
 *
 * @Block(
 *   id = "dhsc_site_social_media_links",
 *   admin_label = @Translation("Social media links"),
 *   category = @Translation("Custom")
 * )
 */
class SocialMediaLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $social_links = $this->getSocialLinks();
    return [
      '#theme' => 'dhsc_site_social_links',
      '#social_links' => $social_links,
    ];
  }

  /**
   * Retrieves social links from site settings.
   */
  private function getSocialLinks():array {
    $links = [];
    $site_settings = \Drupal::service('site_settings.loader');
    $social_links = $site_settings->loadByFieldset('global')['social_media_links'];

    foreach ($social_links as $key => $social_link) {
      if (is_numeric($key)) {
        $links[] = [
          'url'   => $social_link['uri'],
          'title' => $social_link['title'],
          'icon'  => $this->getSocialName($social_link['uri']),
        ];
      }
    }
    return $links;
  }

  /**
   * Get social media name from URL.
   */
  private function getSocialName($url):string {
    $parsed_url = parse_url($url);
    $host = $parsed_url['host'];
    switch ($host) {
      case str_contains($host, 'twitter'):
        $icon_name = 'twitter';
        break;

      case str_contains($host, 'facebook'):
        $icon_name = 'facebook';
        break;

      case str_contains($host, 'linkedin'):
        $icon_name = 'linkedin';
        break;

      case str_contains($host, 'youtube'):
        $icon_name = 'youtube';
        break;

      case str_contains($host, 'instagram'):
        $icon_name = 'instagram';
        break;

      default:
        $icon_name = 'globe';
        break;
    }

    return $icon_name;

  }

}
