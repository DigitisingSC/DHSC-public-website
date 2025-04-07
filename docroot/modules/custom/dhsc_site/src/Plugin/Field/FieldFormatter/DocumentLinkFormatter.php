<?php

namespace Drupal\dhsc_site\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\file\FileInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\ByteSizeMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Plugin implementation of the 'document_link_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "document_link_formatter",
 *   label = @Translation("Document Link with Metadata"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class DocumentLinkFormatter extends FormatterBase {

  /**
   * The file URL generator service.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a DocumentLinkFormatter object.
   */
  public function __construct(string $plugin_id, mixed $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, string $label, string $view_mode, array $third_party_settings, FileUrlGeneratorInterface $file_url_generator, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->fileUrlGenerator = $file_url_generator;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('file_url_generator'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $file = $item->entity;

      if (!$file instanceof FileInterface) {
        continue;
      }

      $file_url = $this->fileUrlGenerator->generateAbsoluteString($file->getFileUri());

      $media = $this->loadParentEntity($file, 'media', $items->getName());
      $plain_name = $media->get('field_plain_language_name')->value;
      $raw_size = $media->get('field_file_size')->value;
      $file_size = ByteSizeMarkup::create((int) $raw_size);
      $mime_type = $media->get('field_mime_types')->value;
      $file_type = $this->getMimeTypeLabels($mime_type);
      $link_text = $plain_name ?: $media->label();

      $metadata = sprintf('%s %s', $file_type, $file_size);

      $link = Link::fromTextAndUrl($link_text, Url::fromUri($file_url))
        ->toRenderable();
      $link['#suffix'] = Markup::create(' <span>' . $metadata . '</span>');

      $elements[$delta] = $link;
    }

    return $elements;
  }

  /**
   * Load the parent entity that references a given entity via a specific field.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The referenced entity.
   * @param string $parent_entity_type
   *   The entity type to search (e.g. 'node', 'media').
   * @param string $field_name
   *   The field name on the parent entity that references the child.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The first parent entity found, or NULL.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function loadParentEntity($entity, $parent_entity_type, $field_name) {
    $storage = $this->entityTypeManager->getStorage($parent_entity_type);
    $ids = $storage->getQuery()
      ->condition($field_name . '.target_id', $entity->id())
      ->accessCheck(TRUE)
      ->range(0, 1)
      ->execute();

    return $ids ? $storage->load(reset($ids)) : NULL;
  }

  /**
   * Returns a human-readable label for a given MIME type.
   *
   * @param string $mime_type
   *   The MIME type (e.g., 'application/pdf').
   *
   * @return string
   *   The human-readable label (e.g., 'PDF document').
   */
  public function getMimeTypeLabels($mime_type) {
    $mime_labels = [
      // Documents.
      'application/pdf' => 'PDF document',
      'application/msword' => 'Word document',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word document (DOCX)',
      'application/vnd.ms-excel' => 'Excel spreadsheet',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel spreadsheet (XLSX)',
      'application/vnd.ms-powerpoint' => 'PowerPoint presentation',
      'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'PowerPoint presentation (PPTX)',
      'application/rtf' => 'Rich Text Format document',
      'text/plain' => 'Plain text file',

      // Images.
      'image/jpeg' => 'JPEG image',
      'image/png' => 'PNG image',
      'image/gif' => 'GIF image',
      'image/webp' => 'WebP image',
      'image/svg+xml' => 'SVG vector image',
      'image/tiff' => 'TIFF image',

      // Archives & compressed.
      'application/zip' => 'ZIP archive',
      'application/x-tar' => 'TAR archive',
      'application/x-gzip' => 'GZIP archive',
      'application/x-bzip2' => 'BZIP2 archive',
      'application/x-7z-compressed' => '7-Zip archive',

      // Audio & video (optional).
      'audio/mpeg' => 'MP3 audio',
      'audio/ogg' => 'Ogg audio',
      'video/mp4' => 'MP4 video',
      'video/x-msvideo' => 'AVI video',
    ];

    return $mime_labels[$mime_type] ?? $mime_type;
  }

}
