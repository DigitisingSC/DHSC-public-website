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
      $entities = $paragraph->get('field_section_link')->referencedEntities();
      if ($old_link = reset($entities)) {
        $url = $old_link->toUrl('canonical');
        $url->setAbsolute();
        $paragraph->set('field_parent_link', [
          'uri' => 'internal:/' . $url->getInternalPath(),
          'title' => $old_link->getTitle(),
        ]);
        $paragraph->save();
      }
    }

    if (
      $paragraph->hasField('field_section_child_links')
      && !empty($paragraph->get('field_section_child_links')->referencedEntities())
    ) {
      $links = [];
      $old_links = $paragraph->get('field_section_child_links')->referencedEntities();
      foreach ($old_links as $old_link) {
        $url = $old_link->toUrl('canonical');
        $url->setAbsolute();
        $links[] = [
          'uri' => 'internal:/' . $url->getInternalPath(),
          'title' => $old_link->getTitle(),
        ];
      }
      $paragraph->set('field_children_links', $links);
      $paragraph->save();
    }
  }
}


/**
 * Deletes old Section links.
 */
function dhsc_site_deploy_0002_remove_old_section_links_fields()
{
  $paragraph_type = 'content_listing_section_links';
  $fields_to_remove = [
    'field_section_link',
    'field_section_child_links',
  ];

  $field_storage_config = \Drupal::entityTypeManager()->getStorage('field_storage_config');
  $field_config_storage = \Drupal::entityTypeManager()->getStorage('field_config');

  foreach ($fields_to_remove as $field_name) {
    $field_config = $field_config_storage->load("paragraph.$paragraph_type.$field_name");
    if ($field_config) {
      $field_config->delete();
    }

    $field_storage = $field_storage_config->load($field_name);
    if ($field_storage) {
      $field_storage->delete();
    }
  }

  drupal_flush_all_caches();
}
