<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformElement;

use Drupal\webform\Entity\Webform;
use Drupal\webform\Plugin\WebformElement\WebformWizardPage;

/**
 * Extends the webform wizard page element to support supplier count wrappers.
 *
 * @WebformElement(
 *   id = "webform_wizard_page_count",
 *   label = @Translation("Wizard page with counts"),
 *   description = @Translation("Provides a wizard page that supports dynamic count data via AlpineJS."),
 *   category = @Translation("Custom"),
 * )
 */
class WebformWizardPageCount extends WebformWizardPage {

  /**
   * {@inheritdoc}
   */
  public function showPage(array &$element) {

    // Get the element key to load the correct theme.
    $element_key = $element['#webform_key'];

    $webform_id = $element['#webform'];
    $webform = Webform::load($webform_id);

    // Add back button.
    $element['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#limit_validation_errors' => [],
      '#submit' => ['dhsc_result_viewer_back_button_submit'],
      '#attributes' => [
        'class' => ['button', 'button--primary'],
        'data-twig-suggestion' => 'previous',
      ],
      '#theme_wrapper' => ['form_element'],
      '#weight' => -10,
    ];

    // Get current page and total pages.
    $all_pages = $webform->get('elementsWizardPages');
    $current_page = array_search($element_key, array_keys($all_pages), TRUE) + 1;
    $total_steps = count($all_pages);

    // This is required as this code is run when webform config is imported via
    // the 'source' tab. We need to avoid division by zero.
    if ($total_steps >= 1) {
      // Calculate values for progress bar.
      $max = '100';
      $current = (string) round(($current_page / $total_steps) * 100);
      $percentage = $current . '%';

      // Add the step indicator at the top.
      $element['step_indicator'] = [
        '#type' => 'markup',
        '#markup' => '<div class="m-progress-bar--text">Question ' . $current_page . ' of ' . $total_steps . '</div><progress class="m-progress-bar--indicator" max="' . $max . '" value="' . $current . '">' . $percentage . '</progress>',
        '#prefix' => '<div class="m-progress-bar">',
        '#suffix' => '</div>',
        '#weight' => -5,
      ];
    }

    parent::showPage($element);

    // Wrap wizard page in AlpineJS x-data context for counts.
    // Attach AlpineJS wrapper and define a counts structure.
    $element['#prefix'] = '<div x-data="supplierCounts()" x-init="init()" class="webform-wizard-page-with-counts">';
    $element['#suffix'] = '</div>';
    $element['#attached']['library'][] = 'dhsc_result_viewer/supplier_counts';
  }

}
