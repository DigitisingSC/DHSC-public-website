<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\webform\Entity\Webform;

/**
 * Interface for the DSF result viewer service.
 */
interface ThemedResultViewerInterface {

  /**
   * Retrieves a webform submission using a token and webform ID.
   *
   * @param string $token
   *   The webform submission token from the URL.
   * @param string $webform_id
   *   The machine name of the webform.
   *
   * @return \Drupal\webform\WebformSubmissionInterface|null
   *   The webform submission object or NULL if not found.
   */
  public function getSubmissionByToken(string $token, string $webform_id);

  /**
   * Groups all questions by their assigned theme.
   *
   * @param \Drupal\webform\Entity\Webform $webform
   *   The webform entity.
   * @param array $responses
   *   User responses, keyed by question key (e.g., sfa_01_options).
   *
   * @return array
   *   Responses grouped by their assigned themes with structured question data.
   */
  public function sortQuestionsByTheme(Webform $webform, array $responses): array;

  /**
   * Get content for result summary.
   *
   * @param int $theme_tid
   *   The taxonomy term ID.
   *
   * @return array
   *   An associative array of quartiles and their summary texts.
   */
  public function getResultSummaryContent(int $theme_tid): array;

  /**
   * Builds a render array for the summaries by quartile.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $summaries_by_quartile
   *   An associative array of summaries, where each value is
   *   TextFieldItemList.
   *
   * @return array
   *   A render array displaying the summary.
   */
  public function buildQuartileSummariesRenderArray(FieldItemListInterface $summaries_by_quartile): array;

  /**
   * Sums the scores of all questions grouped by their assigned theme.
   *
   * @param \Drupal\webform\Entity\Webform $webform
   *   The Webform entity.
   * @param array $grouped_by_theme
   *   Responses grouped by theme with their individual scores.
   *
   * @return array
   *   Total scores for each theme.
   */
  public function getThemeScores(Webform $webform, array $grouped_by_theme): array;

  /**
   * Calculates scores based on user responses.
   *
   * @param array $data
   *   The submission data.
   *
   * @return array
   *   The mapped scores for each question.
   */
  public function getResultsScores(array $data): array;

}
