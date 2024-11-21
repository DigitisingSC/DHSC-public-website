<?php

/**
* Migrates Section links.
*/
function dhsc_site_deploy_0001_migrate_section_links() {

  $paragraphs = \Drupal::entityTypeManager()
    ->getStorage('paragraph')
    ->loadByProperties(['type' => 'content_listing_section_links']);

  foreach ($paragraphs as $paragraph) {
    if (
      $paragraph->hasField('field_section_link')
      && !empty($paragraph->get('field_section_link')->referencedEntities())
    ) {
      $old_link = $paragraph->get('field_section_link')->referencedEntities()[0];
      $paragraph->set('field_parent_link', [
        'uri' => $old_link->toUrl('canonical', ['absolute' => TRUE])->toString(),
        'title' => $old_link->getTitle(),
      ]);
    }

    if (
      $paragraph->hasField('field_section_child_links')
      && !empty($paragraph->get('field_section_child_links')->referencedEntities())
    ) {
      $old_links = $paragraph->get('field_section_child_links')->referencedEntities();
      foreach ($old_links as $old_link) {
        $links[] = [
          'uri' => $old_link->toUrl('canonical', ['absolute' => TRUE])->toString(),
          'title' => $old_link->getTitle(),
        ];
      }
      $paragraph->set('field_children_links', $links);
      $paragraph->save();
      $links = [];
    }
  }
}


/**
 * Deletes old Section links.
 */
function dhsc_site_deploy_0002_remove_old_section_links_fields()
{
  $paragraph_type = 'content_listing_section_links'; // Replace with your paragraph type machine name.
  $fields_to_remove = [
    'field_section_link',
    'field_section_child_links',
  ];

  // Load the field storage configuration and field configuration services.
  $field_storage_config = \Drupal::entityTypeManager()->getStorage('field_storage_config');
  $field_config_storage = \Drupal::entityTypeManager()->getStorage('field_config');

  foreach ($fields_to_remove as $field_name) {
    // Check if the field exists in the paragraph type.
    $field_config = $field_config_storage->load("paragraph.$paragraph_type.$field_name");
    if ($field_config) {
      // Delete the field instance configuration for this paragraph type.
      $field_config->delete();
    }

    // Check if the field storage configuration exists.
    $field_storage = $field_storage_config->load($field_name);
    if ($field_storage) {
      // Delete the field storage configuration if it exists.
      $field_storage->delete();
    }
  }

  // Clear the cache to ensure the changes are applied immediately.
  drupal_flush_all_caches();
}
