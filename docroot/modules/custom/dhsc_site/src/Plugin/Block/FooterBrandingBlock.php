<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

/**
 * Provides a footer branding block.
 *
 * @Block(
 *   id = "footer_branding_block",
 *   admin_label = @Translation("Footer branding"),
 *   category = @Translation("DHSC Site")
 * )
 */
class FooterBrandingBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $site_settings = \Drupal::service('plugin.manager.site_settings_loader')->getActiveLoaderPlugin();
    $footer_logo_setting = $site_settings->loadByGroup('footer')['footer_logo'];

    if (!empty($footer_logo_setting)) {
      $media = Media::load($footer_logo_setting);
      $file_id = $media->getSource()->getSourceFieldValue($media);
      $svg_file = File::load($file_id);
      $file_path = $svg_file->createFileUrl();
    }

    $site_config = \Drupal::config('system.site');
    $site_slogan = $site_config->get('slogan') ?? $this->t('Digitising Social Care');

    return [
      '#theme' => 'footer_branding',
      '#variant' => '',
      '#logo' => $file_path ?? '',
      '#site_name' => '',
      '#site_slogan' => $site_slogan,
    ];
  }
}
