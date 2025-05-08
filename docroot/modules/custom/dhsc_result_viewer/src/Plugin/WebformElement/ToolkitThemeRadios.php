<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\Radios;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a radios webform element with scoring functionality.
 *
 * @WebformElement(
 *   id = "toolkit_theme_radios",
 *   label = @Translation("Toolkit Theme Radios"),
 *   description = @Translation("A customised radio button element with score inputs."),
 *   category = @Translation("Options elements"),
 * )
 */
class ToolkitThemeRadios extends Radios {

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    $properties = parent::defineDefaultProperties();

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // Build the base configuration form.
    $form = parent::buildConfigurationForm($form, $form_state);

    // Add a section for setting scores.
    $form['options_scores'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Assign Scores to Each Option'),
      '#description' => $this->t('Provide a score for each option.'),
    ];

    // Load existing scores.
    $saved_scores = $this->getCurrentScores($form, $form_state);

    // Retrieve options from form_state storage.
    $options = $form_state->getStorage()['element_properties']['options'] ?? [];

    // Populate score fields for each option.
    $default_score_count = 1;

    foreach ($options as $key => $label) {

      $before_dash = strpos($label, ' -- ') !== FALSE ? strstr($label, ' -- ', TRUE) : $label;

      // The parent class explicitly removed any array structures when saving
      // to the form state, so we'll prefix the key here.
      $form['options_scores']['score_' . $key] = [
        '#type' => 'number',
        '#title' => $this->t('Score for "%label"', ['%label' => $before_dash]),
        '#default_value' => !empty($saved_scores[$key]) ? $saved_scores[$key] : $default_score_count,
        '#min' => -1,
        '#size' => 5,
      ];
      $default_score_count++;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Call the parent submit handler.
    parent::submitConfigurationForm($form, $form_state);

    $decorated_form_state = $form_state->getCompleteFormState();

    // Get the current element's unique key.
    $element_key = $decorated_form_state->getValue('key');

    // Extract scores from submitted form values.
    $scores = [];
    $submitted_values = $decorated_form_state->getValues();

    // Filter only keys that start with "score_". This is being carried out
    // to get round the flattening of any array structure that's carried out
    // automatically.
    foreach ($submitted_values as $key => $value) {
      if (strpos($key, 'score_') === 0) {

        // Remove the "score_" prefix and store the value.
        $option_key = substr($key, 6);
        $scores[$option_key] = (int) $value;
      }
    }

    // Load existing theme selections or create a new array.
    $options_scores = $this->webform->getThirdPartySetting('dhsc_result_viewer', 'options_scores', []);

    // Save the TID against the unique element key.
    $options_scores[$element_key] = $scores;

    // Store the updated settings.
    $this->webform->setThirdPartySetting('dhsc_result_viewer', 'options_scores', $options_scores);

    // Save the Webform.
    $this->webform->save();
  }

  /**
   * Get the default theme selection for the element.
   */
  protected function getCurrentScores($form, FormStateInterface $form_state) {
    // Get the element key to load the set of scores.
    $element_key = $form_state->getBuildInfo()['args'][1];

    // Retrieve the theme settings for this specific element.
    $response_scores = $this->webform->getThirdPartySetting('dhsc_result_viewer', 'options_scores', []);

    return $response_scores[$element_key] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, ?WebformSubmissionInterface $webform_submission = NULL) {

    // These types are hardcoded into the parent prepare method, so we need to
    // manually force the type here so that we can ensure the elements are
    // rendered correctly as radios on the frontend form.
    $element['#type'] = 'radios';
    parent::prepare($element, $webform_submission);

  }

}
