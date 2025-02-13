<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Plugin\WebformElement\WebformWizardPage;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Toolkit Theme Selector' Webform element.
 *
 * @WebformElement(
 *   id = "toolkit_theme_selector",
 *   label = @Translation("Toolkit theme wizard page"),
 *   description = @Translation("Wizard page with an associated a toolkit theme taxonomy term."),
 *   category = @Translation("Custom"),
 * )
 */
class ThemeSelector extends WebformWizardPage {

  /**
   * The Entity Type Manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // The base class uses dependency injection so call parent::create() to
    // ensure any base services are loaded.
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);

    // Inject the Entity Type Manager service manually.
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * Load a taxonomy term by UUID.
   *
   * @param string $uuid
   *   The UUID of the taxonomy term.
   *
   * @return \Drupal\taxonomy\Entity\Term|null
   *   The taxonomy term or NULL if not found.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function loadTermByUuid($uuid) {
    // Load term by UUID, and return the first (and only) result if the term
    // exists.
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadByProperties(['uuid' => $uuid]);
    return reset($terms) ?: NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return parent::defineDefaultProperties();
  }

  /**
   * Builds the configuration form for the Webform element.
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Get the current selection from form state or saved value.
    $selected_term_uuid = $form_state->getValue('toolkit_theme') ?? $this->getCurrentTheme($form, $form_state);

    // Add the theme selector dropdown.
    $form['toolkit_theme'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Toolkit Theme'),
      '#options' => $this->getToolkitThemeOptions(),
      '#default_value' => $selected_term_uuid,
      '#empty_option' => $this->t('- Select a Theme -'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [static::class, 'updateThemeEditLink'],
        'wrapper' => 'theme-edit-link-wrapper',
        'event' => 'change',
      ],
    ];

    // Add a wrapper for the dynamic edit link.
    $form['theme_edit_link_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'theme-edit-link-wrapper'],
    ];

    // Add the edit link if a theme is selected.
    if ($selected_term_uuid) {
      $term = $this->loadTermByUuid($selected_term_uuid);

      if ($term) {
        $form['theme_edit_link_wrapper']['theme_edit_link'] = [
          '#type' => 'link',
          '#title' => $this->t('Edit "@name" Theme', ['@name' => $term->getName()]),
          '#url' => $term->toUrl('edit-form'),
          '#attributes' => [
            'target' => '_blank',
            'class' => ['button', 'button--secondary'],
            'style' => 'margin-top: 10px;',
          ],
          '#weight' => 5,
        ];
      }
    }

    return $form;
  }

  /**
   * AJAX callback to update the theme edit link when a new theme is selected.
   */
  public static function updateThemeEditLink(array &$form, FormStateInterface $form_state) {
    // Get the selected taxonomy term UUID from the dropdown.
    $selected_term_uuid = $form_state->getValue('toolkit_theme');

    // Initialize the wrapper.
    $edit_link = [
      '#type' => 'container',
      '#attributes' => ['id' => 'theme-edit-link-wrapper'],
    ];

    // If a theme is selected, generate the edit link.
    if ($selected_term_uuid) {

      // Manually load the Entity Type Manager since we can't use $this (static
      // method).
      $entity_type_manager = \Drupal::entityTypeManager();

      // Load term by UUID.
      $terms = $entity_type_manager->getStorage('taxonomy_term')->loadByProperties(['uuid' => $selected_term_uuid]);
      $term = reset($terms);

      if ($term) {
        $edit_link['theme_edit_link'] = [
          '#type' => 'link',
          '#title' => t('Edit "@name" Theme', ['@name' => $term->getName()]),
          '#url' => $term->toUrl('edit-form'),
          '#attributes' => [
            'target' => '_blank',
            'class' => ['button', 'button--secondary'],
            'style' => 'margin-top: 10px;',
          ],
          '#weight' => 5,
        ];
      }
    }

    return $edit_link;
  }

  /**
   * Saves the selected taxonomy term (UUID) into the Webform's configuration.
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    // Get the selected UUID.
    $decorated_form_state = $form_state->getCompleteFormState();
    $theme_uuid = $decorated_form_state->getValue('toolkit_theme');

    // Get the Webform ID.
    $webform_id = $form_state->getBuildInfo()['args'][0]->id();

    // Get the element key to uniquely identify this instance.
    $element_key = $form_state->getBuildInfo()['args'][1];

    if ($theme_uuid) {
      // Load existing theme selections or create a new array.
      $theme_settings = $this->webform->getThirdPartySetting('dhsc_result_viewer', 'toolkit_theme', []);

      // Save the UUID against the unique element key.
      $theme_settings[$element_key] = $theme_uuid;

      // Store the updated settings.
      $this->webform->setThirdPartySetting('dhsc_result_viewer', 'toolkit_theme', $theme_settings);

      // Save the Webform.
      $this->webform->save();

      // Debug log.
      \Drupal::logger('dhsc_result_viewer')
        ->notice('Saved Toolkit Theme UUID @uuid for element @key in Webform @webform', [
          '@uuid' => $theme_uuid,
          '@key' => $element_key,
          '@webform' => $webform_id,
        ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function showPage(array &$element) {

    // Get the element key to load the correct theme.
    $element_key = $element['#webform_key'];

    $webform_id = $element['#webform'];
    $webform = Webform::load($webform_id);

    // Load the saved themes.
    $theme_settings = $webform->getThirdPartySetting('dhsc_result_viewer', 'toolkit_theme', []);

    // Check if this element has a stored theme.
    if (isset($theme_settings[$element_key])) {
      $theme_uuid = $theme_settings[$element_key];
      $term = $this->loadTermByUuid($theme_uuid);

      if ($term) {

        // Return the theme markup in the form build.
        $element['tool_theme'] = [
          '#theme' => 'toolkit_theme_selector',
          '#theme_name' => $term->getName(),
          '#weight' => -10,
        ];
      }
    }

    /** @var \Drupal\webform\WebformSubmissionInterface $submission */
    $submission = $element['#webform_submission'];

    if ($submission instanceof WebformSubmissionInterface) {
      $submission->set('in_draft', TRUE);
      $submission->save();
      \Drupal::logger('dhsc_result_viewer')
        ->notice('Auto-saved draft for Webform submission ID @id.', ['@id' => $submission->id()]);
    }

    // Add back button.
    $element['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#limit_validation_errors' => [],
      '#submit' => ['dhsc_result_viewer_back_button_submit'],
      '#attributes' => ['class' => ['button', 'button--primary'], 'data-twig-suggestion' => 'previous'],
      '#theme_wrapper' => ['form_element'],
      '#weight' => -10,
    ];

    parent::showPage($element);

  }

  /**
   * Saves the webform submission as a draft.
   */
  protected function saveDraft(FormStateInterface $form_state) {
    $submission = $form_state->getFormObject()->getEntity();
    if ($submission instanceof WebformSubmissionInterface) {
      $submission->set('in_draft', TRUE);
      $submission->save();

      \Drupal::logger('dhsc_result_viewer')
        ->notice('Auto-saved draft for Webform submission ID @id.', ['@id' => $submission->id()]);
    }
  }

  /**
   * Helper function to load all taxonomy terms from 'toolkit_theme' vocabulary.
   */
  protected function getToolkitThemeOptions() {
    $cid = 'toolkit_theme_selector:theme_options';

    // Use the default cache backend.
    $cache_backend = \Drupal::cache();

    if ($cache = $cache_backend->get($cid)) {
      return $cache->data;
    }

    $options = [];

    // Check if the vocabulary exists.
    $vocabulary = $this->entityTypeManager
      ->getStorage('taxonomy_vocabulary')
      ->load('toolkit_theme');

    if (!$vocabulary) {
      \Drupal::logger('dhsc_result_viewer')->warning('The "toolkit_theme" vocabulary does not exist.');
      return $options;
    }

    // Load taxonomy terms.
    $terms = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadTree('toolkit_theme');

    foreach ($terms as $term_data) {
      $term = Term::load($term_data->tid);
      if ($term) {
        // Use getName() for multilingual support.
        $options[$term->uuid()] = $term->getName();
      }
    }

    // Cache the results for 6 hours.
    $cache_backend->set($cid, $options, time() + 21600);

    return $options;
  }

  /**
   * Get the default theme selection for the element.
   */
  protected function getCurrentTheme($form, FormStateInterface $form_state) {

    // Get the element key to load the correct theme.
    $element_key = $form_state->getBuildInfo()['args'][1];

    // Retrieve the theme settings for this specific element.
    $theme_settings = $this->webform->getThirdPartySetting('dhsc_result_viewer', 'toolkit_theme', []);

    return $theme_settings[$element_key] ?? NULL;
  }

}
