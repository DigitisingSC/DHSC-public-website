<?php

namespace Drupal\dhsc_result_viewer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ResultSummaryForm.
 */
class ResultSummaryForm  extends ConfigFormBase {

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


    $form['self_assessment_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Self assessment'),
      '#collapsible' => TRUE,
      '#group' => 'dhsc',
    ];

    $form['assured_solutions_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Assured solutions'),
      '#collapsible' => TRUE,
      '#group' => 'dhsc',
    ];

    $form['dsf_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Skills self assessment'),
      '#collapsible' => TRUE,
      '#group' => 'dhsc',
    ];

    $form['self_assessment_settings']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Results listing title'),
      '#default_value' => $config->get('title'),
      '#required' => TRUE,
    ];

    $form['self_assessment_settings']['sa_result_summary'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Results listing summary'),
      '#default_value' => $config->get('sa_result_summary'),
      '#required' => TRUE,
    ];

    $form['self_assessment_settings']['sa_landing_page'] = [
      '#type' => 'linkit',
      '#title' => $this->t('Tool start page'),
      '#description' => $this->t('Start typing to see a list of results.'),
      '#required' => TRUE,
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => [
        'linkit_profile_id' => 'default',
      ],
      '#default_value' => $config->get('sa_landing_page'),
    ];

    $results_variant_text_default = $config->get('results_variant_text');
    $form['self_assessment_settings']['results_variant_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Results variant text'),
      '#default_value' => $results_variant_text_default['value'],
      '#format' => 'full_html',
      '#allowed_formats' => ['full_html'],
      '#required' => TRUE,
    ];

    $form['assured_solutions_settings']['as_landing_page'] = [
      '#type' => 'linkit',
      '#title' => $this->t('Tool start page'),
      '#description' => $this->t('Start typing to see a list of results.'),
      '#required' => TRUE,
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => [
        'linkit_profile_id' => 'default',
      ],
      '#default_value' => $config->get('as_landing_page'),
    ];

    $as_result_summary = $config->get('as_result_summary');
    $form['assured_solutions_settings']['as_result_summary'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Results listing text'),
      '#default_value' => $as_result_summary['value'],
      '#format' => 'full_html',
      '#allowed_formats' => ['full_html'],
      '#required' => TRUE,
    ];

    $form['dsf_settings']['dsf_landing_page'] = [
      '#type' => 'linkit',
      '#title' => $this->t('Tool start page'),
      '#description' => $this->t('Start typing to see a list of results.'),
      '#required' => TRUE,
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => [
        'linkit_profile_id' => 'default',
      ],
      '#default_value' => $config->get('dsf_landing_page'),
    ];

    $dsf_result_summary = $config->get('dsf_result_summary');
    $form['dsf_settings']['dsf_result_summary'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Results listing text'),
      '#default_value' => $dsf_result_summary['value'],
      '#format' => 'full_html',
      '#allowed_formats' => ['full_html'],
      '#required' => TRUE,
    ];

    $form['dsf_settings']['dsf_advanced_landing_page'] = [
      '#type' => 'linkit',
      '#title' => $this->t('Advanced tool start page'),
      '#description' => $this->t('Start typing to see a list of results.'),
      '#required' => TRUE,
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => [
        'linkit_profile_id' => 'default',
      ],
      '#default_value' => $config->get('dsf_landing_page'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('title', $form_state->getValue('title'))
      ->set('sa_result_summary', $form_state->getValue('sa_result_summary'))
      ->set('sa_landing_page', $form_state->getValue('sa_landing_page'))
      ->set('as_result_summary', $form_state->getValue('as_result_summary'))
      ->set('as_landing_page', $form_state->getValue('as_landing_page'))
      ->set('dsf_result_summary', $form_state->getValue('dsf_result_summary'))
      ->set('dsf_landing_page', $form_state->getValue('dsf_landing_page'))
      ->set('dsf_advanced_landing_page', $form_state->getValue('dsf_advanced_landing_page'))
      ->set('results_variant_text', $form_state->getValue('results_variant_text'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
