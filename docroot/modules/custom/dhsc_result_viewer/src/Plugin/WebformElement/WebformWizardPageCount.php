<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformElement;

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
    parent::showPage($element);

    // Wrap wizard page in AlpineJS x-data context for counts.
    // Attach AlpineJS wrapper and define a counts structure.
    $element['#prefix'] = '<div x-data="supplierCounts()" x-init="init()" class="webform-wizard-page-with-counts">';
    $element['#suffix'] = '</div>';
    $element['#attached']['library'][] = 'dhsc_result_viewer/supplier_counts';
  }

}
