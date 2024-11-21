<?php

/**
* Migrates Section links.
*/
function dhsc_site_deploy_migrate_section_links() {

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
    }
    $paragraph->save();
  }
}
