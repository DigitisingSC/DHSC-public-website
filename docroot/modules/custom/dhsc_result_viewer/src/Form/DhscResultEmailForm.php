<?php

namespace Drupal\dhsc_result_viewer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Email results form.
 */
class DhscResultEmailForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'dhsc_result_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Input email address'),
      '#required' => TRUE,
      '#attributes' => [
        'aria-label' => $this->t('Email Input'),
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send email'),
      '#attributes' => [
        'role' => 'button',
        'aria-label' => $this->t('Send email'),
        'class' => ['a-button', 'a-button--secondary']
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $email = $form_state->getValue('email', '');
    $token = \Drupal::request()->query->get('token');

    $form_state->setRedirect('dhsc_result_viewer.result_summary_assured_solutions_email', [
      'email' => $email,
      'token' => $token,
    ]);

    return;
  }
}
