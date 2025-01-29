<?php

namespace Drupal\dhsc_result_viewer\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Plugin\WebformElement\WebformWizardPage;

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
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return [
      'toolkit_theme' => NULL,
    ] + parent::getDefaultProperties();
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
      $term = Term::load($selected_term_uuid);

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
    // Get the selected taxonomy term ID from the dropdown.
    $selected_term_uuid = $form_state->getValue('toolkit_theme');

    // Initialise the wrapper.
    $edit_link = [
      '#type' => 'container',
      '#attributes' => ['id' => 'theme-edit-link-wrapper'],
    ];

    // If a theme is selected, generate the edit link.
    if ($selected_term_uuid) {
      $term = Term::load($selected_term_uuid);
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
      $term = Term::load($theme_uuid);

      if ($term) {

        // Return the theme markup in the form build.
        $element['tool_theme'] = [
          '#theme' => 'toolkit_theme_selector',
          '#theme_name' => $term->getName(),
          '#weight' => -10,
        ];
      }
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

    // Use the entity type manager service.
    $entity_type_manager = \Drupal::entityTypeManager();

    // Check if the vocabulary exists.
    $vocabulary = $entity_type_manager
      ->getStorage('taxonomy_vocabulary')
      ->load('toolkit_theme');

    if (!$vocabulary) {
      \Drupal::logger('dhsc_result_viewer')->warning('The "toolkit_theme" vocabulary does not exist.');
      return $options;
    }

    // Load taxonomy terms.
    $terms = $entity_type_manager
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
