<?php

namespace Drupal\dhsc_result_viewer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DhscResultSummaryForm.
 */
class DhscResultSummaryForm  extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'dhsc_result_viewer.result_summary_settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'result_summary';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Results listing title'),
      '#default_value' => $config->get('title'),
      '#required' => TRUE,
    ];

    $form['summary'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Results listing summary'),
      '#default_value' => $config->get('summary'),
      '#required' => TRUE,
    ];

    $results_variant_text_default = $config->get('results_variant_text');
    $form['results_variant_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Results variant text'),
      '#default_value' => $results_variant_text_default['value'],
      '#format' => 'full_html',
      '#allowed_formats' => ['full_html'],
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('title', $form_state->getValue('title'))
      ->set('summary', $form_state->getValue('summary'))
      ->set('results_variant_text', $form_state->getValue('results_variant_text'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
