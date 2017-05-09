<?php

/**
 * @file
 * Contains \Drupal\oauth\Form\OAuthDeleteConsumerForm.
 */

namespace Drupal\oauth\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an oauth_consumer deletion confirmation form.
 */
class OAuthDeleteConsumerForm extends ConfirmFormBase implements ContainerInjectionInterface {

  const NAME = 'oauth_delete_consumer_form';

  /**
   * Factory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The DIC.
   *
   * @return static
   *   The form instance.
   */
  public static function create(ContainerInterface $container) {
    /** @var \Drupal\Core\Database\Connection $database */
    $database = $container->get('database');
    return new static($database);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database service.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return static::NAME;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete this OAuth consumer?');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('oauth.user_consumer', ['user' => \Drupal::currentUser()->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

   /**
    * {@inheritdoc}
    */
  public function getCancelText() {
    return $this->t('Cancel');
   }

   /**
    * {@inheritdoc}
    */
  public function getFormName() {
    return static::NAME;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {
    $form['cid'] = array(
      '#type' => 'hidden',
      '#value' => $cid,
    );

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $cid = $values['cid'];
    $this->connection->delete('oauth_consumer')
      ->condition('cid', $cid)
      ->execute();
    drupal_set_message($this->t('OAuth consumer deleted.'));
    $form_state->setRedirect('oauth.user_consumer', ['user' => \Drupal::currentUser()->id()]);
  }

}
