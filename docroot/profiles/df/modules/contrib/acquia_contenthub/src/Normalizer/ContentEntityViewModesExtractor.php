<?php

/**
 * @file
 * Contains \Drupal\acquia_contenthub\Normalizer\ContentEntityViewModesExtractor.
 */

namespace Drupal\acquia_contenthub\Normalizer;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\Markup;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Session\AccountSwitcherInterface;
use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Drupal\acquia_contenthub\ContentHubSubscription;

/**
 * Extracts the rendered view modes from a given ContentEntity Object.
 */
class ContentEntityViewModesExtractor implements ContentEntityViewModesExtractorInterface {
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\Core\Entity\ContentEntityInterface';

  /**
   * The current_user service used by this plugin.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The Basic HTTP Kernel to make requests.
   *
   * @var \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  protected $kernel;

  /**
   * The account switcher service.
   *
   * @var \Drupal\Core\Session\AccountSwitcherInterface
   */
  protected $accountSwitcher;

  /**
   * Content Hub Subscription.
   *
   * @var \Drupal\acquia_contenthub\ContentHubSubscription
   */
  protected $contentHubSubscription;

  /**
   * Constructs a ContentEntityViewModesExtractor object.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current session user.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Symfony\Component\HttpKernel\HttpKernelInterface $kernel
   *   The Kernel.
   * @param \Drupal\Core\Session\AccountSwitcherInterface $account_switcher
   *   The Account Switcher Service.
   * @param \Drupal\acquia_contenthub\ContentHubSubscription $contenthub_subscription
   *   The Content Hub Subscription.
   */
  public function __construct(AccountProxyInterface $current_user, EntityDisplayRepositoryInterface $entity_display_repository, EntityTypeManagerInterface $entity_type_manager, RendererInterface $renderer, HttpKernelInterface $kernel, AccountSwitcherInterface $account_switcher, ContentHubSubscription $contenthub_subscription) {
    $this->currentUser = $current_user;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
    $this->kernel = $kernel;
    $this->accountSwitcher = $account_switcher;
    $this->contentHubSubscription = $contenthub_subscription;
  }

  /**
   * Obtains the Configuration entity for the current entity type.
   *
   * @param string $entity_type_id
   *   The Entity Type ID.
   *
   * @return bool|\Drupal\acquia_contenthub\ContentHubEntityTypeConfigInterface
   *   The ContentHubEntityType Configuration Entity if exists, FALSE otherwise.
   */
  protected function getContentHubEntityTypeConfigEntity($entity_type_id) {
    /** @var \Drupal\rest\RestResourceConfigInterface $contenthub_entity_config_storage */
    $contenthub_entity_config_storage = $this->entityTypeManager->getStorage('acquia_contenthub_entity_config');

    /** @var \Drupal\acquia_contenthub\ContentHubEntityTypeConfigInterface[] $contenthub_entity_config_ids */
    $contenthub_entity_config_ids = $contenthub_entity_config_storage->loadMultiple(array($entity_type_id));
    $contenthub_entity_config_id = isset($contenthub_entity_config_ids[$entity_type_id]) ? $contenthub_entity_config_ids[$entity_type_id] : FALSE;

    return $contenthub_entity_config_id;
  }


  /**
   * Checks whether the given class is supported for normalization.
   *
   * @param mixed $data
   *   Data to normalize.
   *
   * @return bool
   *   TRUE if is child of supported class.
   */
  private function isChildOfSupportedClass($data) {
    // If we aren't dealing with an object that is not supported return
    // now.
    if (!is_object($data)) {
      return FALSE;
    }
    $supported = (array) $this->supportedInterfaceOrClass;

    return (bool) array_filter($supported, function($name) use ($data) {
      return $data instanceof $name;
    });
  }

  /**
   * Renders all the view modes that are configured to be rendered.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $object
   *   The Content Entity object.
   *
   * @return array|null
   *   The normalized array.
   */
  public function getRenderedViewModes(ContentEntityInterface $object) {
    $normalized = [];

    // Exit if the class does not support normalizing to the given format.
    if (!$this->isChildOfSupportedClass($object)) {
      return NULL;
    }

    $entity_type_id = $object->getEntityTypeId();
    $entity_bundle_id = $object->bundle();
    $contenthub_entity_config_id = $this->getContentHubEntityTypeConfigEntity($entity_type_id);

    // Stop processing if 'view modes' are not configured for this entity type.
    if (!$contenthub_entity_config_id || $contenthub_entity_config_id->isEnabledViewModes($entity_bundle_id) === FALSE) {
      return NULL;
    }

    // Obtain the list of view modes.
    $configured_view_modes = $contenthub_entity_config_id->getRenderingViewModes($entity_bundle_id);

    // Normalize.
    $view_modes = $this->entityDisplayRepository->getViewModes($entity_type_id);

    // Generate preview image URL, if possible.
    $preview_image_url = $this->getPreviewImageUrl($object);

    foreach ($view_modes as $view_mode_id => $view_mode) {
      if (!in_array($view_mode_id, $configured_view_modes)) {
        continue;
      }
      // Generate our URL where the isolated rendered view mode lives.
      // This is the best way to really make sure the content in Content Hub
      // and the content shown to any user is 100% the same.
      $url = Url::fromRoute('acquia_contenthub.content_entity_display.entity', [
        'entity_type' => $object->getEntityTypeId(),
        'entity_id' => $object->id(),
        'view_mode_name' => $view_mode_id,
      ])->toString();

      $request = Request::create($url);
      $request = $this->contentHubSubscription->setHmacAuthorization($request);

      /** @var \Drupal\Core\Render\HtmlResponse $response */
      $response = $this->kernel->handle($request, HttpKernelInterface::SUB_REQUEST);

      $normalized[$view_mode_id] = [
        'id' => $view_mode_id,
        'preview_image' => $preview_image_url,
        'label' => $view_mode['label'],
        'url' => $url,
        'html' => $response->getContent(),
      ];
    }

    return $normalized;
  }

  /**
   * Get preview image URL.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The Content Entity object.
   *
   * @return string
   *   Preview image URL.
   */
  protected function getPreviewImageUrl(ContentEntityInterface $entity) {
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();

    $contenthub_entity_config_id = $this->getContentHubEntityTypeConfigEntity($entity_type_id);

    // Obtaining preview image field and style from the configuration entity.
    $preview_image_field = $contenthub_entity_config_id->getPreviewImageField($bundle);
    $preview_image_style = $contenthub_entity_config_id->getPreviewImageStyle($bundle);

    // Don't set, if no preview image has been configured.
    if (empty($preview_image_field) || empty($preview_image_style)) {
      return '';
    }

    $preview_image_config_array = explode('->', $preview_image_field);
    foreach ($preview_image_config_array as $field_key) {
      // Don't set, if node has no such field or field has no such entity.
      if (empty($entity->{$field_key}->entity) ||
        method_exists($entity->{$field_key}, 'isEmpty') && $entity->{$field_key}->isEmpty()
      ) {
        return '';
      }
      $entity = $entity->{$field_key}->entity;
    }

    if (!in_array($entity->bundle(), array('image', 'file'))) {
      return '';
    }
    $file_uri = $entity->getFileUri();

    // Process Image style.
    $image_style = ImageStyle::load($preview_image_style);
    // Return empty if no such image style.
    if (empty($image_style)) {
      return '';
    }

    // Return preview image URL.
    $preview_image_uri = $image_style->buildUrl($file_uri);
    return file_create_url($preview_image_uri);
  }

  /**
   * Renders all the view modes that are configured to be rendered.
   *
   * In this method we also switch to an anonymous user because we only want
   * to see what the Anonymous user's see.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $object
   *   The Content Entity Object.
   * @param string $view_mode
   *   The request view mode identifier.
   *
   * @see https://www.drupal.org/node/218104
   *
   * @return array
   *   The render array for the complete page, as minimal as possible.
   */
  public function getViewModeMinimalHtml(ContentEntityInterface $object, $view_mode) {
    $context = new RenderContext();

    // We're early-rendering content as a part of $this->getMinimalHtml(), and
    // need to make sure that metadata is not lost in that process.
    $html = $this->renderer->executeInRenderContext($context, function () use ($object, $view_mode) {
      // Switch to anonymous user for rendering as configured role.
      $entity_type_id = $object->getEntityTypeId();
      $this->accountSwitcher->switchTo(new \Drupal\Core\Session\AnonymousUserSession());

      $build = $this->entityTypeManager->getViewBuilder($entity_type_id)
        ->view($object, $view_mode);

      // Add our cacheableDependency. If this config changes, clear the render
      // cache.
      $contenthub_entity_config_id = $this->getContentHubEntityTypeConfigEntity($entity_type_id);
      $this->renderer->addCacheableDependency($build, $contenthub_entity_config_id);

      // Wrap our view mode in the most minimal HTML possible.
      $html = $this->getMinimalHtml($build);
      // Restore user account.
      $this->accountSwitcher->switchBack();

      return $html;
    });

    // Merge the context's metadata, if present.
    if (!$context->isEmpty()) {
      /** @var \Drupal\Core\Render\BubbleableMetadata $early_rendering_bubbleable_metadata */
      $early_rendering_bubbleable_metadata = $context->pop();
      BubbleableMetadata::createFromRenderArray($html)
        ->merge($early_rendering_bubbleable_metadata)
        ->applyTo($html);
    }

    return $html;
  }

  /**
   * Renders a given render array in minimal HTML.
   *
   * Minimal HTML is in this case defined as:
   * - valid HTML
   * - <head> only containing CSS and JS
   * - <body> only containing the passed in content plus footer JS
   * - i.e. no meta tags, no title, no theme CSS …
   *
   *
   * Renders a HTML response with a hardcoded HTML template (i.e. no theme
   * involved), optimized for the purposes of Content Hub, with only the
   * absolutely minimal HTML required.
   *
   * Only $body still goes through the theme system, because it is rendered
   * using Render API, which itself calls the theme system, and hence uses the
   * active theme.
   *
   * @param array $body
   *   A render array.
   *
   * @return array
   *   The render array for the complete page, as minimal as possible.
   */
  protected function getMinimalHtml(array $body) {
    // Attachments to render the CSS, header JS and footer JS.
    // @see \Drupal\Core\Render\HtmlResponseSubscriber
    $html_attachments = [];
    $types = [
      'styles' => 'css',
      'scripts' => 'js',
      'scripts_bottom' => 'js-bottom',
    ];
    $placeholder_token = Crypt::randomBytesBase64(55);
    foreach ($types as $type => $placeholder_name) {
      $placeholder = '<' . $placeholder_name . '-placeholder token="' . $placeholder_token . '">';
      $html_attachments['html_response_attachment_placeholders'][$type] = $placeholder;
    }

    // Hardcoded equivalent of core/modules/system/templates/html.html.twig.
    $html_top = <<<HTML
<!DOCTYPE html>
<html>
  <head>
    <css-placeholder token="$placeholder_token">
    <js-placeholder token="$placeholder_token">
  </head>
  <body>
HTML;
    $html_bottom = <<<HTML
    <js-bottom-placeholder token="$placeholder_token">
  </body>
</html>
HTML;

    // Render array representing the entire HTML to be rendered.
    $html = [
      '#prefix' => Markup::create($html_top),
      'body' => $body,
      '#suffix' => Markup::create($html_bottom),
      '#attached' => $html_attachments,
    ];

    // Render the render array.
    $this->renderer->renderRoot($html);

    return $html;
  }

}
