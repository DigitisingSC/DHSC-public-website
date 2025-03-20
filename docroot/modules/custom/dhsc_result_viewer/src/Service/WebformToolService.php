<?php

namespace Drupal\dhsc_result_viewer\Service;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\webform\Entity\WebformSubmission;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\dhsc_result_viewer\Constants\WebformToolConstants;

/**
 * Provides a service for managing Webform result submissions.
 */
class WebformToolService {

  use StringTranslationTrait;

  /**
   * The private temp store factory.
   */
  protected PrivateTempStoreFactory $tempStoreFactory;

  /**
   * The current user.
   */
  protected AccountProxyInterface $currentUser;

  /**
   * The messenger service.
   */
  protected MessengerInterface $messenger;

  /**
   * The logger factory.
   */
  protected LoggerChannelFactoryInterface $loggerFactory;

  /**
   * The request stack service.
   */
  protected RequestStack $requestStack;

  /**
   * The form builder service.
   */
  protected FormBuilderInterface $formBuilder;

  /**
   * The renderer service.
   */
  protected RendererInterface $renderer;

  /**
   * Translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected $stringTranslation;

  /**
   * Constructs the WebformToolService.
   */
  public function __construct(
    PrivateTempStoreFactory $temp_store_factory,
    AccountProxyInterface $current_user,
    MessengerInterface $messenger,
    LoggerChannelFactoryInterface $loggerFactory,
    RequestStack $request_stack,
    FormBuilderInterface $formBuilder,
    RendererInterface $renderer,
    TranslationInterface $stringTranslation,
  ) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->currentUser = $current_user;
    $this->messenger = $messenger;
    $this->loggerFactory = $loggerFactory;
    $this->requestStack = $request_stack;
    $this->formBuilder = $formBuilder;
    $this->renderer = $renderer;
    $this->stringTranslation = $stringTranslation;
  }

  /**
   * Retrieves the last submission ID for a given webform.
   *
   * @param string $webform_id
   *   The webform ID.
   *
   * @return int|null
   *   The SID
   */
  public function getSubmissionId(string $webform_id): ?int {
    $submission_id = $this->tempStoreFactory->get('dhsc_result_viewer')
      ->get('last_submission_' . $webform_id);
    $this->logMessage('Retrieved submission ID', $submission_id, $webform_id);
    return $submission_id;
  }

  /**
   * Saves a submission ID in the temp store.
   *
   * @param string $webform_id
   *   The webform ID.
   * @param int $submission_id
   *   The submission ID we're storing.
   *
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function setSubmissionId(string $webform_id, int $submission_id): void {
    $this->tempStoreFactory->get('dhsc_result_viewer')
      ->set('last_submission_' . $webform_id, $submission_id);
    $this->logMessage('Saved submission ID', $submission_id, $webform_id);
  }

  /**
   * Retrieves the last step value for a given webform.
   *
   * @param string $webform_id
   *   The webform ID.
   *
   * @return int|null
   *   The step value.
   */
  public function getStepValue(string $webform_id): ?int {
    $step_value = $this->tempStoreFactory->get('dhsc_result_viewer')
      ->get('last_step_' . $webform_id);
    $this->logMessage('Retrieved step value', $step_value, $webform_id);
    return $step_value;
  }

  /**
   * Saves a step value in the temp store.
   *
   * @param string $webform_id
   *   The webform ID.
   * @param int $current_step
   *   The step value we're storing.
   *
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function setStepValue(string $webform_id, int $current_step): void {
    $this->tempStoreFactory->get('dhsc_result_viewer')
      ->set('last_step_' . $webform_id, $current_step);
    $this->logMessage('Saved step value', $current_step, $webform_id);
  }

  /**
   * Creates a draft Webform submission.
   *
   * @param string $webform_id
   *   The webform ID.
   *
   * @return \Drupal\webform\Entity\WebformSubmission|null
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function createDraftSubmission(string $webform_id): ?WebformSubmission {
    $submission = WebformSubmission::create([
      'webform_id' => $webform_id,
      'uid' => $this->currentUser->id(),
      'is_draft' => TRUE,
      'data' => [],
    ]);
    $submission->save();

    $this->setSubmissionId($webform_id, $submission->id());
    return $submission;
  }

  /**
   * Get the requested step from the current request.
   */
  public function getRequestedStep(): ?int {
    $request = $this->requestStack->getCurrentRequest();
    return (int) $request->query->get('edit-page');
  }

  /**
   * Generate the summary URL for re-edit mode.
   *
   * @param string $webform_id
   *   The webform ID.
   * @param string $token
   *   Submission token string.
   * @param int $requested_step
   *   The step we're returning to.
   *
   * @return string
   *   The URL as a string.
   */
  public function getSummaryUrl(string $webform_id, string $token, int $requested_step): string {
    return Url::fromRoute(
      'dhsc_result_viewer.themed_response_summary',
      ['webform_id' => $webform_id, 'token' => $token],
      ['absolute' => TRUE, 'fragment' => 'response-' . $requested_step]
    )->toString();
  }

  /**
   * Get the previous step in the wizard.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state object.
   *
   * @return string|null
   *   Return previous step as a string.
   */
  public function getPreviousStep(FormStateInterface $form_state): ?string {
    $current_page = $form_state->get('current_page');
    $all_pages = $form_state->get('pages');
    $current_step = array_search($current_page, array_keys($all_pages), TRUE) + 1;
    return ($current_step > 1) ? array_keys($all_pages)[$current_step - 2] : NULL;
  }

  /**
   * Get the landing page URL or fallback to front page.
   */
  public function getLandingPageUrl(): string {
    return Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
  }

  /**
   * Perform a redirect response.
   *
   * @param string $url
   *   The URL to redirect to.
   */
  public function performRedirect(string $url) {
    $response = new RedirectResponse($url);
    $response->send();
    exit();
  }

  /**
   * Rebuild the webform via AJAX.
   *
   * @param array $form
   *   Form array provided via a form submit callback.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state object.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Returns an AjaxResponse.
   *
   * @throws \Exception
   */
  public function rebuildWebformAjax(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $form = $this->formBuilder->rebuildForm($form_state->getBuildInfo()['form_id'], $form_state);
    $wrapper_id = $form['#id'] ?? 'webform-ajax-wrapper';
    $response->addCommand(new ReplaceCommand("#{$wrapper_id}", $this->renderer->render($form)));
    return $response;
  }

  /**
   * Logs and displays messages.
   *
   * @param string $message
   *   THe message to log.
   * @param mixed $value
   *   The value to report.
   * @param string $webform_id
   *   The webform_id our message is referring to.
   */
  private function logMessage(string $message, mixed $value, string $webform_id): void {
    if (WebformToolConstants::DEBUG_MODE) {
      $this->messenger->addStatus($this->t("@message: @value for webform: @webform", [
        '@message' => $message,
        '@value' => $value,
        '@webform' => $webform_id,
      ]));

      $this->loggerFactory->get('dhsc_result_viewer')
        ->info("$message: @value for webform: @webform", [
          '@value' => $value,
          '@webform' => $webform_id,
        ]);
    }
  }

  /**
   * Checks if the form ID belongs to a tool submission form.
   *
   * @param string $form_id
   *   Form ID that is being tested.
   *
   * @return bool
   *   Truth of statement.
   */
  public function isToolSubmissionForm(string $form_id): bool {
    return str_starts_with($form_id, 'webform_submission') &&
      !empty(array_filter(WebformToolConstants::WEBFORM_TOOLS, fn($word) => str_contains($form_id, $word)));
  }

  /**
   * Checks if the form ID belongs to a themed tool submission form.
   *
   * @param string $form_id
   *   Form ID that is being tested.
   *
   * @return bool
   *   Truth of statement.
   */
  public function isThemedToolSubmissionForm(string $form_id): bool {
    return str_starts_with($form_id, 'webform_submission') &&
      !empty(array_filter(WebformToolConstants::WEBFORM_TOOLS_THEMED, fn($word) => str_contains($form_id, $word)));
  }

  /**
   * Checks if the form ID belongs to an individual tool submission form.
   *
   * @param string $form_id
   *   Form ID that is being tested.
   *
   * @return bool
   *   Truth of statement.
   */
  public function isIndividualToolSubmissionForm(string $form_id): bool {
    return str_starts_with($form_id, 'webform_submission') &&
      !empty(array_filter(WebformToolConstants::WEBFORM_TOOLS_INDIVIDUAL, fn($word) => str_contains($form_id, $word)));
  }

  /**
   * Checks if the webform ID is in the themed tools list.
   *
   * @param string $webform_id
   *   The webform ID.
   *
   * @return bool
   *   Truth of statement.
   */
  public function isThemedTool(string $webform_id): bool {
    return in_array($webform_id, WebformToolConstants::WEBFORM_TOOLS_THEMED, TRUE);
  }

  /**
   * Clears the tempstore values for a webform after successful submission.
   *
   * @param string $webform_id
   *   The webform ID.
   */
  public function clearSubmissionData(string $webform_id): void {
    $tempstore = $this->tempStoreFactory->get('dhsc_result_viewer');
    $tempstore->delete('last_submission_' . $webform_id);
    $tempstore->delete('last_step_' . $webform_id);

    $this->logMessage('Cleared tempstore data', 'N/A', $webform_id);
  }

}
