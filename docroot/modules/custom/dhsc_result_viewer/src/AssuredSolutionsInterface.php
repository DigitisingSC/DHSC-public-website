<?php

namespace Drupal\dhsc_result_viewer;

/**
 * Interface AssuredSolutionsInterface.
 *
 * @package Drupal\dhsc_result_viewer
 */
interface AssuredSolutionsInterface {

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
   * Get webform submission.
   *
   * @return mixed
   *   Return webform submission entity.
   */
  public function getSubmission();

}
