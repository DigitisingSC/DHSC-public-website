<?php

namespace Drupal\dhsc_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a sitewide alter block with a text input field.
 *
 * @Block(
 *   id = "sitewide_alert",
 *   admin_label = @Translation("Sitewide alert"),
 *   category = @Translation("DHSC Site")
 * )
 */
class SiteWideAlert extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text = !empty($this->configuration['alert_text']['value']) ?
    $this->configuration['alert_text']['value'] : '';

    return [
      '#theme' => 'alert_message',
      '#alert_text' => $this->t($text),
      '#cache' => [
        'tags' => ['alert_text_value'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['alert_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Alert text'),
      '#default_value' => $this->configuration['alert_text']['value'],
      '#format' => $this->configuration['alert_text']['format'] ?? 'basic_html',
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['alert_text'] = $form_state->getValue('alert_text');

    // Invalidate cache when the alert text field value is updated.
    Cache::invalidateTags(['alert_text_value']);
  }

}
