<?php
/**
 * @file
 * Enables modules and site configuration for the DF site installation.
 */

use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function df_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  // Don't enable update.module by default.
  $form['update_notifications']['enable_update_status_module']['#access'] = FALSE;
  $form['update_notifications']['enable_update_status_module']['#default_value'] = 0;

  // Pre-populate the site name with the server name.
  $form['site_information']['site_name']['#default_value'] = \Drupal::request()->server->get('SERVER_NAME');
  $form['#submit'][] = 'df_form_install_configure_submit';
}

/**
 * Submission handler to sync the contact.form.feedback recipient.
 */
function df_form_install_configure_submit($form, FormStateInterface $form_state) {
  $site_mail = $form_state->getValue('site_mail');
  ContactForm::load('feedback')->setRecipients([$site_mail])->trustData()->save();
}

/**
 * Implements template_preprocess_block().
 */
function df_preprocess_block(array &$variables) {
  $variables['attributes']['data-block-plugin-id'] = $variables['elements']['#plugin_id'];
}
