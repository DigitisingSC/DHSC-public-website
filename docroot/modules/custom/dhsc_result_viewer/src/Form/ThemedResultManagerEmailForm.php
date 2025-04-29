<?php

namespace Drupal\dhsc_result_viewer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dhsc_result_viewer\ThemedResultViewer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ResultManagerEmailForm.
 */
class ThemedResultManagerEmailForm extends FormBase {

  /**
   * The ThemeResultViewer service.
   *
   * @var \Drupal\dhsc_result_viewer\ThemedResultViewer
   */
  protected $resultViewer;

  /**
   * The request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a ResultManagerEmailForm object.
   *
   * @param \Drupal\dhsc_result_viewer\ThemedResultViewer $result_viewer
   *   The result viewer service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(ThemedResultViewer $result_viewer, RequestStack $request_stack) {
    $this->resultViewer = $result_viewer;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dhsc_themed_result_viewer.service'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dhsc_dsf_result_manager_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t("Your Email Address"),
      '#required' => TRUE,
      '#attributes' => ['aria-label' => $this->t("Email Input")],
    ];
    $form['manager_email'] = [
      '#type' => 'email',
      '#title' => $this->t("Manager's Email Address"),
      '#required' => TRUE,
      '#attributes' => ['aria-label' => $this->t("Manager's Email Input")],
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send email'),
      '#attributes' => [
        'role' => 'button',
        'aria-label' => $this->t('Send email'),
        'class' => ['a-button', 'a-button--secondary'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $manager_email = $form_state->getValue('manager_email');
    $token = $this->requestStack->getCurrentRequest()->query->get('token');

    if (!$token) {
      $this->messenger()->addError($this->t('Invalid submission token.'));
      return;
    }

    // Send emails to both the user and manager.
    $email_status = $this->resultViewer->sendResultEmail($email, $token);
    $manager_email_status = $this->resultViewer->sendResultEmail($manager_email, $token);

    $this->messenger()->addStatus($this->t('The results have been sent to both email addresses.'));

    if ($email_status && $manager_email_status) {
      $this->messenger()->addStatus($this->t('The results have been sent to both email addresses.'));
    }
    else {
      $this->messenger()->addStatus($this->t('There was a problem sending the emails.'));
    }

    // Redirect back to the previous page.
    $request = $this->requestStack->getCurrentRequest();
    $referer = $request->headers->get('referer');

    if ($referer && filter_var($referer, FILTER_VALIDATE_URL)) {
      $form_state->setRedirectUrl(Url::fromUri($referer));
    }
    else {
      $form_state->setRedirect('<front>');
    }
  }

}
