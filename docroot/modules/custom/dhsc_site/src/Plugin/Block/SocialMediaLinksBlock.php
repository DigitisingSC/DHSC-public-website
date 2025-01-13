<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a social media links block.
 *
 * @Block(
 *   id = "social_media_links",
 *   admin_label = @Translation("Social media links"),
 *   category = @Translation("DHSC Site")
 * )
 */
class SocialMediaLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $social_links = $this->getSocialLinks();
    return [
      '#theme' => 'social_media_links',
      '#social_links' => $social_links,
    ];
  }

  /**
   * Retrieves social links from site settings.
   */
  private function getSocialLinks():array {
    $links = [];
    $site_settings = \Drupal::service('plugin.manager.site_settings_loader')->getActiveLoaderPlugin();
    $social_links = $site_settings->loadByGroup('global')['social_media_links'];

    if (!empty($social_links)) {
      if (is_array($social_links[0])) {
        foreach ($social_links as $key => $social_link) {
          if (is_numeric($key)) {
            $links[] = [
              'url'   => $social_link['uri'],
              'title' => $social_link['title'],
              'icon'  => $this->getSocialName($social_link['uri']),
            ];
          }
        }
      } else {
        $links[] = [
          'url'   => $social_links['uri'],
          'title' => $social_links['title'],
          'icon'  => $this->getSocialName($social_links['uri']),
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
      case str_contains($host, 'x'):
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
