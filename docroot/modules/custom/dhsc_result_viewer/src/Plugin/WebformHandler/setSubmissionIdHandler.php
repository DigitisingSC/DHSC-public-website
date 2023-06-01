<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Form submission handler.
 *
 * @WebformHandler(
 *   id = "dhsc_set_submission_id_handler",
 *   label = @Translation("Sets webform submission ID in tempstore"),
 *   category = @Translation("Form Handler"),
 *   description = @Translation("Handle decision tree webform submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
class setSubmissionIdHandler extends WebformHandlerBase
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
    $sid = $webform_submission->id();
    if ($sid) {
      $tempstore = \Drupal::service('tempstore.private')->get('dhsc_result_viewer');
      $tempstore->set('sid', $sid);
    }
  }
}
