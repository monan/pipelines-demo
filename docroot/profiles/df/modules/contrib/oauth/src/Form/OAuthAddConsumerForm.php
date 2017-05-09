<?php

/**
 * @file
 * Contains \Drupal\oauth\Form\OAuthAddConsumerForm.
 */

namespace Drupal\oauth\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form to add OAuth consumers.
 */
class OAuthAddConsumerForm extends FormBase {

  const NAME = 'oauth_add_consumer_form';

  /**
   * The current user service.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var \Drupal\Core\Database\Connection $connection */
    $connection = $container->get('database');

    /** @var \Drupal\Core\Session\AccountProxyInterface $current_user */
    $current_user = $container->get('current_user');

    return new static($connection, $current_user);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return static::NAME;
  }

  /**
   * {@inheritdoc}
   * @param \Drupal\Core\Database\Connection $connection
   *   The database service.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The current user service.
   */
  public function __construct(Connection $connection, AccountProxyInterface $account) {
    $this->connection = $connection;
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['save'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $consumer_key = user_password(32);
    $consumer_secret  = user_password(32);
    $key_hash = sha1($consumer_key);
    $this->connection->insert('oauth_consumer')
      ->fields(array(
        'uid' => $this->account->id(),
        'consumer_key' => $consumer_key,
        'consumer_secret' => $consumer_secret,
        'key_hash' => $key_hash,
      ))
      ->execute();

    drupal_set_message($this->t('Added a new consumer.'));
    $form_state->setRedirect('oauth.user_consumer', array('user' => \Drupal::currentUser()->id()));
  }

}
