<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\webform\WebformSubmissionInterface;

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
   *   Returns result summary values.
   */
  public function getResultsSummary(array $data, $webform);

  /**
   * Get webform submission id.
   *
   * @param string $token
   *   Token string.
   * @param string $webform_id
   *   Webform ID.
   *
   * @return \Drupal\webform\WebformSubmissionInterface|null
   *   Return submission or NULL.
   */
  public function getSubmissionByToken(string $token, string $webform_id): WebformSubmissionInterface|null;

  /**
   * Returns webform result nodes which contain at least one answer.
   *
   * @param array $answers
   *   User supplied answers.
   *
   * @return array
   *   Return result node ids.
   */
  public function getResultNodesWithMatches(array $answers);

  /**
   * Returns all supplier nodes which do not match user search criteria.
   *
   * @param array $nids
   *   Result node nids.
   *
   * @return array
   *   Return non matching nids.
   */
  public function getNonMatches(array $nids);

}
