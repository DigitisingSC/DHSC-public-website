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
   * @param object $webform
   *   Webform entity.
   *
   * @return mixed
   */
  public function getResultsSummary($data, $webform);

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
