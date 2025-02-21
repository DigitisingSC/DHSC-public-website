<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a featuredlinksbock block.
 *
 * @Block(
 *   id = "featured_links_block",
 *   admin_label = @Translation("Featured links"),
 *   category = @Translation("DHSC Site")
 * )
 */
class FeaturedLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $featured_links = $this->getFeaturedLinks();
    return [
      '#theme' => 'featured_links',
      '#items' => $featured_links,
    ];
  }

  /**
   * Retrieves social links from site settings.
   */
  private function getFeaturedLinks():array {
    $links = [];
    $site_settings = \Drupal::service('site_settings.loader');
    $featured_links = $site_settings->loadByFieldset('menu')['featured_links'];

    if (isset($featured_links['target_id'])) {
      $featured_links = [$featured_links];
    }

    foreach ($featured_links as $featured_link) {
      $paragraph_id = $featured_link['target_id'] ?? NULL;

      if ($paragraph_id) {
        $paragraph = Paragraph::load($paragraph_id);
        $links[] = [
          'url' => Url::fromUri($paragraph->field_featured_link->uri)
            ->toString(),
          'title' => $paragraph->field_featured_link->title,
          'description' => $paragraph->field_description->value,
        ];
      }
    }

    return $links;
  }
}
