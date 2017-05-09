<?php

/**
 * @file
 * Contains form submit handlers for Lift event creation.
 */

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function as_lift_form_dfs_fin_member_quote_form_alter(array &$form, FormStateInterface $form_state) {
  $form['#submit'][] = '_as_lift_form_dfs_fin_member_quote_form_submit';
}

/**
 * Form submit callback for pushing a new event to Lift.
 *
 * @param array $form
 *   The form render array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The form state.
 */
function _as_lift_form_dfs_fin_member_quote_form_submit(array &$form, FormStateInterface $form_state) {
  /** @var \Drupal\node\Entity\Node $quote */
  $quote = Node::load($form_state->getValue('quote_nid'));
  if ($quote && $form_state->getValue('quote_accept')) {
    // Load the customer user object.
    $customer = $quote->getRevisionAuthor();
    // Load the associated Insurance Product node.
    $product = Node::load($quote->field_associated_product->target_id);

    // Add information from the quote for Lift.
    $vin = Term::load($quote->field_vin->target_id);
    $car_make = $vin->field_make->value;
    $car_model = $vin->field_model->value;
    $car_year = $vin->field_year->value;
    $product_name = $product->title->value;
    $mail = $customer->getEmail();

    $cookie_id = $_COOKIE['tc_ptid'];

    $event_data = [
      ['person', '13', $product_name],
    ];

    if (!empty($car_make)) {
      $event_data[] = ['person', '14', $car_make];
    }

    if (!empty($car_model)) {
      $event_data[] = ['person', '15', $car_model];
    }

    if (!empty($car_year)) {
      // We are sending this as a date so we choose 1st January and push that.
      // The purpose of this demo is to see cars older than X.
      $event_data[] = ['person', '17', "$car_year-01-01 23:59:00.0 EDT"];
    }

    _as_lift_create_event($mail, 'email', [$cookie_id => 'tracking'], 'Product purchase', 'Web', $event_data);
  }
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function as_lift_form_dfs_fin_newsletter_form_alter(array &$form, FormStateInterface $form_state) {
  $form['#submit'][] = '_as_lift_form_dfs_fin_newsletter_form_submit';
}

/**
 * Form submit callback for pushing a new event to Lift.
 *
 * @param array $form
 *   The form render array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The form state.
 */
function _as_lift_form_dfs_fin_newsletter_form_submit(array &$form, FormStateInterface $form_state) {
  $address = $form_state->getValue('email');
  $cookie_id = $_COOKIE['tc_ptid'];
  $event_data = [
    ['event', '12', TRUE],
  ];
  _as_lift_create_event($cookie_id, 'tracking', [$address => 'email'], 'Newsletter subscription', 'Web', $event_data);
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function as_lift_form_contact_message_get_a_quote_form_alter(array &$form, FormStateInterface $form_state) {
  $form['actions']['submit']['#submit'][] = '_as_lift_form_contact_message_get_a_quote_form_submit';
}

/**
 * Form submit callback for pushing a new event to Lift.
 *
 * @param array $form
 *   The form render array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The form state.
 */
function _as_lift_form_contact_message_get_a_quote_form_submit(array &$form, FormStateInterface $form_state) {
  $values = $form_state->getValues();

  $email = $values['mail'];
  $product_request = str_replace('_', ' ', $values['field_interested_in'][0]['value']);
  $product_name = ucwords($product_request);
  // TODO we should normalize the phone number before submitting to the database
  $phone = $values['field_phone'][0]['value'];
  // Get the person name - it might be just first name or both so separate by space if there is a last name
  $name = join(' ', [$values['field_first_name'][0]['value'], $values['field_last_name'][0]['value']]);
  $cookie_id = $_COOKIE['tc_ptid'];

  $identities = [$email => 'email'];

  if (!empty($phone)) {
    $identities[$phone] = 'phone';
  }
  if (!empty($name)) {
    $identities[$name] = 'name';
  }

  $event_data = [
    ['event', '11', $product_name],
  ];

  _as_lift_create_event($cookie_id, 'tracking', $identities, 'Product interest', 'Quote Form', $event_data);
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function as_lift_form_dfs_obio_subscribe_form_alter(array &$form,  FormStateInterface $form_state) {
  $form['#submit'][] = '_as_lift_form_dfs_obio_subscribe_form_submit';
}

/**
 * Form submit callback for pushing a new event to Lift.
 *
 * @param array $form
 *   The form render array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The form state.
 */
function _as_lift_form_dfs_obio_subscribe_form_submit(array &$form, FormStateInterface $form_state) {
  $address = $form_state->getValue('email');
  $cookie_id = $_COOKIE['tc_ptid'];
  $event_data = [
    ['person', '10', TRUE],
  ];
  _as_lift_create_event($cookie_id, 'tracking', [$address => 'email'], 'Newsletter subscription', 'Web', $event_data);
}

/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function as_lift_form_commerce_order_item_add_to_cart_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  $form['actions']['submit']['#submit'][] = '_as_lift_form_commerce_order_item_add_to_cart_form_submit';
}

/**
 * Form submit callback for pushing a new event to Lift.
 *
 * @param array $form
 *   The form render array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The form state.
 */
function _as_lift_form_commerce_order_item_add_to_cart_form_submit(array &$form, FormStateInterface $form_state) {
  $cookie_id = $_COOKIE['tc_ptid'];
  $product = \Drupal::request()->attributes->get('node');
  if (!$product || !($product instanceof \Drupal\node\NodeInterface)) {
    return;
  }
  $event_data = [
    ['event', '11', $product->title->value],
  ];
  $extra_identities = [];
  if ($email = \Drupal::currentUser()->getAccount()->getEmail()) {
    $extra_identities[$email] = 'email';
  }
  _as_lift_create_event($cookie_id, 'tracking', $extra_identities, 'Product purchase', 'Web', $event_data);
}
