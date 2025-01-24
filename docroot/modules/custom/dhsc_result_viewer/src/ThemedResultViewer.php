<?php

namespace Drupal\dhsc_result_viewer;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ThemedResultViewer.
 *
 * @package Drupal\dhsc_result_viewer
 */
class ThemedResultViewer implements ThemedResultViewerInterface {

  use StringTranslationTrait;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Node storage.
   *
   * @var \Drupal\Node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * View builder.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $viewBuilder;

  /**
   * View builder.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $paragraphViewBuilder;

  /**
   * Taxonomy storage.
   *
   * @var \Drupal\Taxonomy\TermStorageInterface
   */
  protected $taxonomyStorage;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * TempStore.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStore
   */
  protected $tempStore;

  /**
   * Logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * Translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected $stringTranslation;

  /**
   * ThemeResultViewer constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The temporary storage factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager service.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager service.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    PrivateTempStoreFactory $temp_store_factory,
    LoggerChannelFactoryInterface $logger_factory,
    RequestStack $request_stack,
    MessengerInterface $messenger,
    LanguageManagerInterface $language_manager,
    MailManagerInterface $mail_manager,
    TranslationInterface $string_translation,
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->tempStore = $temp_store_factory;
    $this->loggerFactory = $logger_factory;
    $this->requestStack = $request_stack;
    $this->messenger = $messenger;
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function sortQuestionsByTheme($webform, array $responses): array {
    $grouped_by_theme = [];
    $elementsDecoded = $webform->getElementsDecoded();

    // Load the assigned themes from third-party settings.
    $step_themes = $webform->getThirdPartySetting('dhsc_result_viewer', 'toolkit_theme', []);

    // Define response value to label mapping.
    $response_labels = [
      0 => "Not applicable to my role",
      1 => "I am unsure",
      2 => "I can do this with help",
      3 => "I can do this without help",
      4 => "I can do this and help others to do it too",
    ];

    // Iterate through each step in elementsDecoded.
    foreach ($elementsDecoded as $step_key => $step_element) {
      // Ensure this is a step and has an assigned theme.
      if (strpos($step_key, 'step_') === 0 && isset($step_themes[$step_key])) {
        $theme_tid = $step_themes[$step_key];

        // Process each child element under the step.
        foreach ($step_element as $child_key => $child_element) {
          // Process selected options and extract the question number.
          if (preg_match('/_(\d+)_options/', $child_key, $matches)) {
            $question_number = (int) $matches[1];
            $response_score = $responses[$child_key] ?? 0;

            // Map response score to descriptive text.
            $response_text = $response_labels[$response_score] ?? "Unknown response";

            // Store response data.
            $grouped_by_theme[$theme_tid][$question_number]['score'] = $response_score;
            $grouped_by_theme[$theme_tid][$question_number]['response_text'] = $response_text;
          }

          // Process question text by targeting the corresponding key.
          if (strpos($child_key, 'question_') !== FALSE) {
            $question_text = strip_tags($child_element['#text']);
            $question_number = (int) filter_var($child_key, FILTER_SANITIZE_NUMBER_INT);

            // Store question text.
            $grouped_by_theme[$theme_tid][$question_number]['question_text'] = $question_text;
          }
        }
      }
    }

    return $grouped_by_theme;
  }

  /**
   * {@inheritdoc}
   */
  public function getResultSummaryContent($theme_tid): array {
    $summaries_by_quartile = [];

    // Load the taxonomy term.
    /** @var \Drupal\taxonomy\Entity\Term $theme_term */
    $theme_term = $this->entityTypeManager->getStorage('taxonomy_term')
      ->load($theme_tid);

    if ($theme_term && !$theme_term->get('field_result_summary_ref')
      ->isEmpty()) {
      $summaries = [];

      /** @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem $paragraph_ref */
      foreach ($theme_term->get('field_result_summary_ref') as $paragraph_ref) {
        // Load the Paragraph by revision ID.
        $paragraph = $this->entityTypeManager->getStorage('paragraph')
          ->loadRevision($paragraph_ref->get('target_revision_id')->getValue());

        if ($paragraph) {
          $summaries[] = $paragraph->get('field_result_summary');
        }
      }

      // Map sorted paragraphs to quartiles QA - QD.
      $quartiles = ['QA', 'QB', 'QC', 'QD'];
      foreach ($summaries as $index => $summary) {
        $quartile = $quartiles[$index];
        $summaries_by_quartile[$quartile] = $summary;
      }
    }

    return $summaries_by_quartile;
  }

  /**
   * {@inheritdoc}
   */
  public function buildQuartileSummariesRenderArray(FieldItemListInterface $summaries_by_quartile): array {
    return [
      'content' => $summaries_by_quartile->view([
        'label' => 'hidden',
        'type' => 'text_default',
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getThemeScores($webform, array $grouped_by_theme): array {
    $theme_scores = [];

    // Iterate through each theme group.
    foreach ($grouped_by_theme as $tid => $data) {
      $total_score = 0;

      // Initialise a counter for the answered questions, as some
      // questions can be skipped if not relevant.
      $answer_count = 0;

      // Loop through each question under the theme.
      foreach ($data as $item) {
        // We check the score is within the range 1 to 4 here, if the score
        // is 0, we know the question was marked as not relevant, so we won't
        // increment the total score or counter.
        if ($item['score'] >= 1 && $item['score'] <= 4) {
          // Directly sum the score from the response data.
          $total_score += $item['score'];

          // The question was answered so increment the counter.
          $answer_count++;
        }
      }

      // Store the total score for the theme.
      $theme_scores[$tid]['score'] = $total_score / $answer_count;
    }

    return $theme_scores;
  }

  /**
   * {@inheritdoc}
   */
  public function getResultsScores($data): array {
    // Define the response-to-score mapping.
    $score_map = [
      'ignore' => 0,
      'unsure' => 1,
      'with_help' => 2,
      'confident' => 3,
      'advanced' => 4,
    ];

    $scores = [];

    // Loop through each response and assign the corresponding score.
    foreach ($data as $question => $answer) {
      // Extract the response type dynamically by checking the valid keys.
      foreach (array_keys($score_map) as $key) {
        if (str_contains($answer, $key)) {
          $scores[$question] = $score_map[$key];
          break;
        }
      }

      // If no valid response is found, assign a default score of 0.
      if (!isset($scores[$question])) {
        $scores[$question] = 0;
      }
    }

    return $scores;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmissionByToken(string $token, string $webform_id) {
    // Load the webform entity.
    $webform = $this->entityTypeManager->getStorage('webform')
      ->load($webform_id);

    if ($webform) {
      // Attempt to load submission using token.
      $submission = $this->entityTypeManager
        ->getStorage('webform_submission')
        ->loadFromToken($token, $webform);

      if ($submission) {
        return $submission;
      }

      // Log a warning if submission not found.
      $this->loggerFactory->get('dhsc_result_viewer')->warning(
        'Invalid submission token: @token for webform: @webform_id',
        ['@token' => $token, '@webform_id' => $webform_id]
      );
    }
    else {
      // Log a warning if the webform ID is invalid.
      $this->loggerFactory->get('dhsc_result_viewer')->warning(
        'Invalid webform ID provided: @webform_id',
        ['@webform_id' => $webform_id]
      );
    }

    return NULL;
  }

  /**
   * Email submission results.
   *
   * @param string $email
   *   The email address.
   * @param string $token
   *   The token.
   *
   * @return bool
   *   Return true if email was sent successfully.
   */
  public function sendResultEmail(string $email, string $token) {
    $tempStore = $this->tempStore->get('dhsc_result_viewer');

    // Get saved result data from tempstore.
    $results = $tempStore->get('dsf_result_data');
    $webform_title = $tempStore->get('dsf_webform_title');

    if (!empty($results)) {
      // Construct email using the results array and send to the email address
      // specified.
      $result = $this->buildEmail($email, $results, $webform_title);

      if (!$result['result']) {
        // Provide a message indicating there was an issue.
        $message = $this->t('There was a problem sending the email to @email', ['@email' => $email]);
        $this->loggerFactory->get('Error in email sending')->error($message);
      }
      else {
        $this->loggerFactory->get('dhsc_result_viewer')
          ->notice(' Digital Skills Framework Tool email sent to ' . $email);
      }

      return TRUE;
    }
    return FALSE;
  }

  /**
   * Construct results email.
   *
   * @param string $email
   *   The email address.
   * @param array $results
   *   Results array.
   * @param string $webform_title
   *   Title of the webform.
   *
   * @return array
   *   Array containing all details of the message.
   */
  public function buildEmail($email, array $results, string $webform_title) {
    $module = 'dhsc_result_viewer';
    $key = 'email_result';
    $to = $email;
    $langcode = $this->languageManager->getDefaultLanguage()->getId();
    $params['subject'] = $this->t('Here are your results for @webform_title', ['@webform_title' => $webform_title]);

    $rows = [];
    if (!empty($results)) {
      foreach ($results as $result) {
        // Start a new row with the theme title.
        $row_content = "<tr><td colspan='2'><h3>{$result['#title']}</h3></td></tr>";

        if (!empty($result['#responses'])) {
          foreach ($result['#responses'] as $response) {
            // Add each question and response as a new row.
            $row_content .= "<tr>
            <td><strong>{$response['question']}</strong></td>
            <td>{$response['response']}</td>
          </tr>";
          }
        }

        // Add the summary result text if available.
        if (isset($result['#result']['content'][0]['#text'])) {
          $row_content .= "<tr>
          <td colspan='2' style='border-left: #29a189 solid 2px; padding-left: 20px'>{$result['#result']['content'][0]['#text']}</td>
        </tr>";
          $row_content .= "<tr><td><br></td></tr>";
        }

        $rows[] = $row_content;
      }
    }
    else {
      // Handle case where no results exist.
      $rows[] = "<tr>
          <td colspan='2' style='border-left: #29a189 solid 2px; padding-left: 20px'>{$this->t('No results available')}</td>
          </tr>";
    }

    // Combine all rows into a single string.
    $table_rows = implode("\n", $rows);

    // Construct the full email body.
    $params['body'] = Markup::create("
    <table class='results' border='0' cellpadding='10' cellspacing='10' width='100%'>
      <tbody>
        {$table_rows}
      </tbody>
    </table>");

    return $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);
  }

}
