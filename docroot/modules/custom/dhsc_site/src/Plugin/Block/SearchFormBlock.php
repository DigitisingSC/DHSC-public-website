<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a social media links block.
 *
 * @Block(
 *   id = "search_form",
 *   admin_label = @Translation("Search form"),
 *   category = @Translation("DHSC Site")
 * )
 */
class SearchFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $quicksearch_links = $this->getQuickSearchLinks();
    return [
      '#theme' => 'search_form',
      '#items' => $quicksearch_links,
    ];
  }

  /**
   * Retrieves social links from site settings.
   */
  private function getQuickSearchLinks():array {
    $links = [];
    $site_settings = \Drupal::service('site_settings.loader');
    $quicksearch_links = $site_settings->loadByFieldset('search')['quick_search'];
    if (!empty($quicksearch_links)) {
      if (is_array($quicksearch_links[0])) {
        foreach ($quicksearch_links as $key => $quicksearch_link) {
          if (is_numeric($key)) {
            $links[] = [
              'url'   => Url::fromUri($quicksearch_link['uri'])->toString(),
              'title' => $quicksearch_link['title'],
            ];
          }
        }
      }
      else {
        $links[] = [
          'url'   => Url::fromUri($quicksearch_links['uri'])->toString(),
          'title' => $quicksearch_links['title'],
        ];
      }
    }
    return $links;
  }
}
