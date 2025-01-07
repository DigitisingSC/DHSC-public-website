<?php

namespace Drupal\dhsc_result_viewer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ResultEmailForm.
 */
class ResultEmailForm extends FormBase {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a ResultEmailForm object.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(RequestStack $request_stack, RouteMatchInterface $route_match) {
    $this->requestStack = $request_stack;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dhsc_result_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
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
        'class' => ['a-button', 'a-button--secondary'],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email', '');
    $token = $this->requestStack->getCurrentRequest()->query->get('token');
    $result_summary_type = explode('.', $this->routeMatch->getRouteName())[1];

    $form_state->setRedirect("dhsc_result_viewer." . $result_summary_type . "_email", [
      'email' => $email,
      'token' => $token,
    ]);
  }

}
