<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\localgov_guides\Plugin\Block\GuidesAbstractBaseBlock;
use Drupal\localgov_guides\Plugin\Block\GuidesContentsBlock;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Custom Target Block' block.
 *
 * @Block(
 *   id = "dhsc_content_guide_block",
 *   admin_label = @Translation("DHSC Content Guide Block"),
 *   category = @Translation("DHSC")
 * )
 */
//class DhscContentGuideBlock extends GuidesContentsBlock {
//  /**
//   * {@inheritdoc}
//   */
//  public function build() {
//    $build = parent::build();
//    $build[0]['#theme'] = 'dhsc_content_guide_block';
//    $links = $build[0]['#links'];
//    foreach ($links as $link) {
//      $linked_url = $link->getUrl();
//      $linked_node = Node::load($linked_url->getRouteParameters()['node']);
//      if (!$linked_node->isPublished()) {
//        $linked_url->setOption('attributes', ['class' => 'unpublished']);
//      }
//    }
//
//    return $build;
//  }
//}

class DhscContentGuideBlock extends GuidesAbstractBaseBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->setPages();
    $links = [];
    $classes = [
      $this->node->id() == $this->overview->id() ? 'lgd-guide-nav__list-item--active' : '',
      !$this->overview->isPublished() ? 'lgd-guide-nav__list-item--unpublished' : '',
    ];
    $options = ['attributes' => ['class' => $classes]];
    $links[] = $this->overview->toLink($this->overview->localgov_guides_section_title->value, 'canonical', $options);

    foreach ($this->guidePages as $guide_node) {
      $classes = [
        $this->node->id() == $guide_node->id() ? 'lgd-guide-nav__list-item--active' : '',
        !$guide_node->isPublished() ? 'lgd-guide-nav__list-item--unpublished' : '',
      ];
      $options = ['attributes' => ['class' => $classes]];
      $links[] = $guide_node->toLink($guide_node->localgov_guides_section_title->value, 'canonical', $options);
    }

    $build = [];
    $build[] = [
      '#theme' => 'dhsc_content_guide_block',
      '#links' => $links,
      '#format' => $this->format,
    ];

    return $build;
  }
}
