<?php

namespace Drupal\dhsc_result_viewer;

/**
 * Interface ResultViewerInterface.
 *
 * @package Drupal\dhsc_result_viewer
 */
interface ResultViewerInterface {

  /**
   * Get summary.
   *
   * @param array $data
   *   Webform values.
   *
   * @return mixed
   */
  public function getResultsSummary($data);

  /**
   * Get sorts result ids.
   *
   * @return mixed
   *   Return ids like on Result Summary page.
   */
  public function getSortsResultIds();

  /**
   * Get webform submission id.
   *
   * @return mixed
   *   Return sid.
   */
  public function getSubmissionId();


  /**
   * Get webform question completion.
   *
   * @return boolean
   *   Return yes_to_all_questions.
   */
  public function questionsAllYes();

  /**
   * Reset webform question completion.
   */
  public function questionsAllReset();

  /**
   * Get webform submission.
   *
   * @return mixed
   *   Return webform submission entity.
   */
  public function getSubmission();

}
