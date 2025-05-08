<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;

/**
 * Defines a Webform Handler for storing a configurable landing page path.
 *
 * @WebformHandler(
 *   id = "landing_page_path_handler",
 *   label = @Translation("Landing Page Path Handler"),
 *   category = @Translation("DHSC"),
 *   description = @Translation("Stores a configurable relative path for reference within DHSC tools."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class LandingPagePathHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['landing_page_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Landing Page Path'),
      '#default_value' => $this->configuration['landing_page_path'] ?? '',
      '#description' => $this->t('Enter a relative path (e.g., /your-landing-page) or use <front>.'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state): void {
    parent::validateConfigurationForm($form, $form_state);

    $path = $form_state->getValue('landing_page_path');
    $pathValidator = \Drupal::service('path.validator');

    // Allow '<front>' or validate the path using Drupal's path validator.
    if ($path !== '<front>' && !$pathValidator->isValid($path)) {
      $form_state->setErrorByName('landing_page_path', $this->t('Please enter a valid relative path starting with / or use <front>. Example: /thank-you'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state): void {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['landing_page_path'] = (string) $form_state->getValue('landing_page_path');
  }

  /**
   * Retrieves the configured landing page path.
   *
   * @return string|null
   *   The stored landing page path, or NULL if not set.
   */
  public function getLandingPagePath(): ?string {
    return $this->configuration['landing_page_path'] ?? NULL;
  }

}
