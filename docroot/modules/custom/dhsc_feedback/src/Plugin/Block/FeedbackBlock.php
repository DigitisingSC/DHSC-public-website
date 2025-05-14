<?php

namespace Drupal\dhsc_feedback\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a Feedback block.
 *
 * @Block(
 *   id = "dhsc_feedback_block",
 *   admin_label = @Translation("Page Feedback"),
 *   category = @Translation("Custom")
 * )
 */
class FeedbackBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new FeedbackBlock instance.
   *
   * @param array $configuration
   *   A configuration array.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_url = $this->requestStack->getCurrentRequest()->getRequestUri();

    return [
      '#theme' => 'dhsc_feedback_block',
      '#attached' => [
        'library' => [
          'dhsc_feedback/feedback',
          'core/drupalSettings',
        ],
      ],
      '#current_url' => $current_url,
      '#question_text' => $this->t('Is this page useful?'),
      '#yes_text' => $this->t('Yes'),
      '#no_text' => $this->t('No'),
      '#problem_link_text' => $this->t('Report a problem with this page'),
      '#cache' => [
        'contexts' => ['url'],
      ],
    ];
  }

}
