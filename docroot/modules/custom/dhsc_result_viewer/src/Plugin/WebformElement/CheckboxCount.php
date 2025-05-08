<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\Checkbox;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'checkbox_count' element with a live count placeholder.
 *
 * @WebformElement(
 *   id = "checkbox_count",
 *   label = @Translation("Checkbox with count"),
 *   description = @Translation("Provides a checkbox element with a live count placeholder for supplier matches."),
 *   category = @Translation("Custom elements"),
 * )
 */
class CheckboxCount extends Checkbox {

  /**
   * {@inheritdoc}
   */
  public function getThemeSuggestions(array $element) {
    $suggestions = parent::getThemeSuggestions($element);

    // Add a specific theme suggestion for this custom element.
    $suggestions[] = 'webform_element__checkbox_count';

    return $suggestions;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    $properties = parent::defineDefaultProperties();
    $properties['show_count'] = TRUE;
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['display']['show_count'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show count placeholder'),
      '#description' => $this->t('Display a dynamic count below this checkbox based on supplier matches.'),
      '#return_value' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, ?WebformSubmissionInterface $webform_submission = NULL) {
    // These types are hardcoded into the parent prepare method, so we need to
    // manually force the type here so that we can ensure the elements are
    // rendered correctly as radios on the frontend form.
    $element['#type'] = 'checkbox';

    parent::prepare($element, $webform_submission);

    if (!empty($element['#show_count'])) {
      $answer_key = $element['#webform_key'] ?? 'unknown_key';

      // Add a wrapper span with AlpineJS dynamic count binding.
      $element['#prefix'] = '<div class="checkbox-count-info" x-cloak>';
      $element['#suffix'] = '<span class="checkbox-count" x-text="counts[\'' . $answer_key . '\'] ?? 0"></span> out of <span class="checkbox-count" x-text="total_counts ?? 0"></span> solutions support this.</div>';
    }
  }

}
