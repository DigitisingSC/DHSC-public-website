<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Utility\WebformOptionsHelper;
use Drupal\webform\Element\WebformEntityTrait;



/**
 * Form submission handler.
 *
 * @WebformHandler(
 *   id = "dhsc_set_submission_id_handler",
 *   label = @Translation("Sets form metadata in tempstore"),
 *   category = @Translation("Form Handler"),
 *   description = @Translation("form metadata is used to loads results and show result page variant"),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
class setFormMetdataHandler extends WebformHandlerBase
{

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE)
  {
    $webform = $webform_submission->getWebform();
    $data = $webform_submission->getData();
    $submitted_values = [];

    // retrieve option values for submitted data.
    foreach ($data as $key => &$item) {
      $element = $webform->getElement($key);
      if (isset($element['#options'])) {
        $value = WebformOptionsHelper::getOptionsText((array) $item, $element['#options']);
        if (count($value) > 1) {
          $item = $value;
        } elseif (count($value) === 1) {
          $item = reset($value);
        }
        $submitted_values[] = $item;
      }
    }

    $tempstore = \Drupal::service('tempstore.private')->get('dhsc_result_viewer');

    // if all answers are 'Yes' set tempstore var to show result page variation.
    if (!empty($submitted_values) && array_unique($submitted_values) === ['Yes']) {
      $tempstore->set('yes_to_all_questions', TRUE);
    }

    // set submission id tempstore var to load submission data prior to showing results.
    if ($sid = $webform_submission->id()) {
      $tempstore->set('sid', $sid);
    }
  }
}
