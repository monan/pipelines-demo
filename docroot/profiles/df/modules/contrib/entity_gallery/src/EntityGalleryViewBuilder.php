<?php

namespace Drupal\entity_gallery;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\entity_gallery\Entity\EntityGallery;

/**
 * View builder handler for entity galleries.
 */
class EntityGalleryViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildComponents(array &$build, array $entities, array $displays, $view_mode) {
    /** @var \Drupal\entity_gallery\EntityGalleryInterface[] $entities */
    if (empty($entities)) {
      return;
    }

    parent::buildComponents($build, $entities, $displays, $view_mode);

    foreach ($entities as $id => $entity) {
      $bundle = $entity->bundle();
      $display = $displays[$bundle];

      if ($display->getComponent('links')) {
        $build[$id]['links'] = array(
          '#lazy_builder' => [get_called_class() . '::renderLinks', [
            $entity->id(),
            $view_mode,
            $entity->language()->getId(),
            !empty($entity->in_preview),
          ]],
        );
      }

      // Add Language field text element to entity gallery render array.
      if ($display->getComponent('langcode')) {
        $build[$id]['langcode'] = array(
          '#type' => 'item',
          '#title' => t('Language'),
          '#markup' => $entity->language()->getName(),
          '#prefix' => '<div id="field-language-display">',
          '#suffix' => '</div>'
        );
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, $view_mode) {
    $defaults = parent::getBuildDefaults($entity, $view_mode);

    // Don't cache entity galleries that are in 'preview' mode.
    if (isset($defaults['#cache']) && isset($entity->in_preview)) {
      unset($defaults['#cache']);
    }

    return $defaults;
  }

  /**
   * #lazy_builder callback; builds an entity gallery's links.
   *
   * @param string $entity_gallery_entity_id
   *   The entity gallery entity ID.
   * @param string $view_mode
   *   The view mode in which the entity gallery entity is being viewed.
   * @param string $langcode
   *   The language in which the entity gallery entity is being viewed.
   * @param bool $is_in_preview
   *   Whether the entity gallery is currently being previewed.
   *
   * @return array
   *   A renderable array representing the entity gallery links.
   */
  public static function renderLinks($entity_gallery_entity_id, $view_mode, $langcode, $is_in_preview) {
    $links = array(
      '#theme' => 'links__entity_gallery',
      '#pre_render' => array('drupal_pre_render_links'),
      '#attributes' => array('class' => array('links', 'inline')),
    );

    if (!$is_in_preview) {
      $entity = EntityGallery::load($entity_gallery_entity_id)->getTranslation($langcode);
      $links['entity_gallery'] = static::buildLinks($entity, $view_mode);

      // Allow other modules to alter the entity gallery links.
      $hook_context = array(
        'view_mode' => $view_mode,
        'langcode' => $langcode,
      );
      \Drupal::moduleHandler()->alter('entity_gallery_links', $links, $entity, $hook_context);
    }
    return $links;
  }

  /**
   * Build the default links (Read more) for an entity gallery.
   *
   * @param \Drupal\entity_gallery\EntityGalleryInterface $entity
   *   The entity gallery object.
   * @param string $view_mode
   *   A view mode identifier.
   *
   * @return array
   *   An array that can be processed by drupal_pre_render_links().
   */
  protected static function buildLinks(EntityGalleryInterface $entity, $view_mode) {
    $links = array();

    // Always display a read more link on teasers because we have no way
    // to know when a teaser view is different than a full view.
    if ($view_mode == 'teaser') {
      $entity_gallery_title_stripped = strip_tags($entity->label());
      $links['entity-gallery-readmore'] = array(
        'title' => t('Read more<span class="visually-hidden"> about @title</span>', array(
          '@title' => $entity_gallery_title_stripped,
        )),
        'url' => $entity->urlInfo(),
        'language' => $entity->language(),
        'attributes' => array(
          'rel' => 'tag',
          'title' => $entity_gallery_title_stripped,
        ),
      );
    }

    return array(
      '#theme' => 'links__entity_gallery__entity_gallery',
      '#links' => $links,
      '#attributes' => array('class' => array('links', 'inline')),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    /** @var \Drupal\entity_gallery\EntityGalleryInterface $entity */
    parent::alterBuild($build, $entity, $display, $view_mode);
    if ($entity->id()) {
      $build['#contextual_links']['entity_gallery'] = array(
        'route_parameters' => array('entity_gallery' => $entity->id()),
        'metadata' => array('changed' => $entity->getChangedTime()),
      );
    }
  }

}
