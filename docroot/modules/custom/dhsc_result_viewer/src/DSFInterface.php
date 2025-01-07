<?php

namespace Drupal\dhsc_result_viewer;

/**
 * Interface DSFInterface.
 *
 * @package Drupal\dhsc_result_viewer
 */
interface DSFInterface {

  /**
   * Get summary.
   *
   * @param array $data
   *   Webform values.
   *
   * @return mixed
   *   Returns result summary values.
   */
  public function getResultsSummary(array $data);

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
   * Get webform submission.
   *
   * @return mixed
   *   Return webform submission entity.
   */
  public function getSubmission();

}
