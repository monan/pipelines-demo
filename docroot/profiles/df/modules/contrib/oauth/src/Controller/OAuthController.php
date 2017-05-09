<?php

/**
 * @file
 * Contains \Drupal\oauth\Controller\OAuthController.
 */

namespace Drupal\oauth\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\user\UserInterface;
use Drupal\oauth\Form\OAuthDeleteConsumerForm;

/**
 * Controller routines for oauth routes.
 */
class OAuthController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The URL generator service.
   *
   * @var \Drupal\Core\Utility\LinkGeneratorInterface
   */
  protected $linkGenerator;

  /**
   * Constructs an OauthController object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database service.
   * @param \Drupal\Core\Utility\LinkGeneratorInterface $link_generator
   *   The link generator service.
   */
  public function __construct(Connection $connection, LinkGeneratorInterface $link_generator) {
    $this->connection = $connection;
    $this->linkGenerator = $link_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var \Drupal\Core\Database\Connection $connection */
    $connection = $container->get('database');

    /** @var \Drupal\Core\Utility\LinkGeneratorInterface $link_generator */
    $link_generator = $container->get('link_generator');

    return new static($connection, $link_generator);
  }

  /**
   * Returns the list of consumers for a user.
   *
   * @param \Drupal\user\UserInterface $user
   *   A user account object.
   *
   * @return string
   *   A HTML-formatted string with the list of OAuth consumers.
   */
  public function consumers(UserInterface $user) {
    $list = array();

    $list['heading']['#markup'] = $this->linkGenerator->generate($this->t('Add consumer'), Url::fromRoute('oauth.user_consumer_add'));

    // Get the list of consumers.
    $result = $this->connection->query('select * from {oauth_consumer} where uid = :uid', array(':uid' => $user->id()));

    // Define table headers.
    $list['table'] = array(
      '#theme' => 'table',
      '#header' => array(
        'consumer_key' => array(
          'data' => $this->t('Consumer key'),
        ),
        'consumer_secret' => array(
          'data' => $this->t('Consumer secret'),
        ),
        'operations' => array(
          'data' => $this->t('Operations'),
        ),
      ),
      '#rows' => array(),
    );

    // Add existing consumers to the table.
    foreach ($result as $row) {
      $list['table']['#rows'][] = array(
        'data' => array(
          'consumer_key' => $row->consumer_key,
          'consumer_secret' => $row->consumer_secret,
          'operations' => array(
            'data' => array(
              '#type' => 'operations',
              '#links' => array(
                'delete' => array(
                  'title' => $this->t('Delete'),
                  'url' => Url::fromRoute('oauth.user_consumer_delete', array('cid' => $row->cid)),
                ),
              ),
            ),
          ),
        ),
      );
    }

    $list['table']['#empty'] = $this->t('There are no OAuth consumers.');

    return $list;
  }

}
