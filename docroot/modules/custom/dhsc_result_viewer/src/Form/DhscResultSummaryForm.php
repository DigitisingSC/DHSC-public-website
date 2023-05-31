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

    $form['green_tab_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Green tab label'),
      '#default_value' => $config->get('green_tab_label'),
      '#required' => TRUE,
    ];

    $form['yellow_tab_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Yellow tab label'),
      '#default_value' => $config->get('yellow_tab_label'),
      '#required' => TRUE,
    ];

    $form['orange_tab_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Orange tab label'),
      '#default_value' => $config->get('orange_tab_label'),
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
      ->set('green_tab_label', $form_state->getValue('green_tab_label'))
      ->set('yellow_tab_label', $form_state->getValue('yellow_tab_label'))
      ->set('orange_tab_label', $form_state->getValue('orange_tab_label'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
