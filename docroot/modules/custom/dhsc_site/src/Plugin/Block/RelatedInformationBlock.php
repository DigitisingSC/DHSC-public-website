<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides block with related information
 * when node has the related information field with values.
 *
 * @Block(
 *   id = "related_information_block",
 *   admin_label = @Translation("Related information block"),
 *   category = @Translation("DHSC Site"),
 *   context_definitions = {
 *     "node" = @ContextDefinition("entity:node", label = @Translation("Current node"))
 *   }
 * )
 */
class RelatedInformationBlock extends BlockBase {

  private $entityRepository;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->entityRepository = \Drupal::service('entity.repository');

    $config = $this->getConfig();
    return [
      '#theme' => 'related_information',
      '#title' => $config['title'] ?? '',
      '#items' => $config['items'] ?? '',
      '#read_more_link' => isset($config['read_more_link']) ?? '',
    ];
  }

  /**
   * Retrieves social links from site settings.
   */
  private function getConfig() : array {
    $node = $this->getContextValue('node');
    $return = [];

    if ($node instanceof Node) {
      $relatedParagraph = $node->get('field_sidebar_related')->getValue();
      if (isset($relatedParagraph[0]) && isset($relatedParagraph[0]['target_id'])) {
        $paragraph = Paragraph::load($relatedParagraph[0]['target_id']);
        if ($paragraph) {
          if ($paragraph->field_title->value) {
            $return['title'] = $paragraph->field_title->value;
          }

          /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem $link */
          foreach ($paragraph->field_referenced_pages as $link) {
            $item = [
              'title' => $link->title,
              'url' => $link->getUrl()->toString(),
              'external' => $link->isExternal(),
            ];

            // Load internal link referenced node.
            if (!$link->isExternal() && $link->getUrl()->getRouteName() === 'entity.node.canonical') {
              $params = $link->getUrl()->getRouteParameters();
              $referencedNode = Node::load($params['node']);

              if ($referencedNode instanceof Node) {
                // Add subtitle to items.
                switch ($referencedNode->bundle()) {
                  case 'event':
                    $item['subtitle'] = $referencedNode->field_date->date->format('d F Y');

                    /** @var Drupal\Core\Field\FieldItemList $type */
                    $type = $referencedNode->get('field_event_type')->view();

                    // Add event type to subtitle.
                    if (isset($type[0]) && isset($type[0]['#markup'])) {
                      $item['subtitle'] .= ' - ' . $type[0]['#markup'];
                    }

                    break;

                  case 'article':
                    $item['subtitle'] = $referencedNode->field_date->date->format('d F Y');
                    break;

                  case 'case_study':
                    // @todo Add care provide name as subtitle
                    break;
                }
              }
            }

            $return['items'][] = $item;
          }

          // Add read more link if exists.
          if ($paragraph->field_link->uri) {

            $return['read_more_link'] = $paragraph->field_link->view(['label' => 'hidden']);
          }

        }
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

}
