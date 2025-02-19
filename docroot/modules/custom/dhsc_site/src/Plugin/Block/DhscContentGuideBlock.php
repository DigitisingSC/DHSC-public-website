<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\localgov_guides\Plugin\Block\GuidesAbstractBaseBlock;
use Drupal\localgov_guides\Plugin\Block\GuidesContentsBlock;
use Drupal\node\Entity\Node;

/**
 * Provides a 'DHSC Content Guide Block' block.
 *
 * @Block(
 *   id = "dhsc_content_guide_block",
 *   admin_label = @Translation("DHSC Content Guide Block"),
 *   category = @Translation("DHSC")
 * )
 */
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
