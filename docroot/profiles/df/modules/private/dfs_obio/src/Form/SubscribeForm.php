<?php

namespace Drupal\dfs_obio\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Egulias\EmailValidator\EmailValidator;

/**
 * Provides a subscription sign up form.
 */
class SubscribeForm extends FormBase {

  /**
   * The email validator.
   *
   * @var \Egulias\EmailValidator\EmailValidator
   */
  protected $emailValidator;

  /**
   * Constructs a new UpdateSettingsForm.
   *
   * @param \Egulias\EmailValidator\EmailValidator $email_validator
   *   The email validator.
   */
  public function __construct(EmailValidator $email_validator) {
    $this->emailValidator = $email_validator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('email.validator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dfs_obio_subscribe_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $form['form'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['row']]
    ];

    $form['form']['email'] = [
      '#prefix' => '<div class="columns small-9">',
      '#suffix' =>'</div>',
      '#type' => 'textfield',
      '#attributes' => ['class' => ['email-form-textbox'], 'placeholder' => t('Enter Email Address')],
      '#size' => 80,
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    $form['form']['submit'] = [
      '#prefix' => '<div class="columns small-3">',
      '#suffix' =>'</div>',
      '#type' => 'submit',
      '#attributes' => ['class' => ['subscribe-submit']],
      '#value' => t('Sign Up'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Validate the email address.
    $address = $form_state->getValue('email');

    if (!$this->emailValidator->isValid($address)) {
      $form_state->setErrorByName('email', $this->t('%address is an invalid email address.', array('%address' => $address)));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Send an email message to the given address.
    $address = $form_state->getValue('email');

    /** @var \Drupal\Core\Mail\MailManager $mail_manager */
    $mail_manager = \Drupal::service('plugin.manager.mail');

    $message = $mail_manager->mail('dfs_obio', 'sign-up', $address, LanguageInterface::LANGCODE_NOT_APPLICABLE);

    // Check for success.
    if ($message['result']) {
      drupal_set_message(t('Thanks for signing up! An email confirmation has been sent to @address', ['@address' => $address]));
    }
  }

}
