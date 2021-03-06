<?php

namespace Drupal\entity_gallery\Tests;

use Drupal\Component\Utility\Crypt;
use Drupal\simpletest\WebTestBase;
use Drupal\system\Entity\Action;

/**
 * Tests configuration of actions provided by the Entity Gallery module.
 *
 * @group entity_gallery
 */
class EntityGalleryActionsConfigurationTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['action', 'entity_gallery'];

  /**
   * Tests configuration of the entity_gallery_assign_owner_action action.
   */
  public function testAssignOwnerEntityGalleryActionConfiguration() {
    // Create a user with permission to view the actions administration pages.
    $user = $this->drupalCreateUser(['administer actions']);
    $this->drupalLogin($user);

    // Make a POST request to admin/config/system/actions.
    $edit = [];
    $edit['action'] = Crypt::hashBase64('entity_gallery_assign_owner_action');
    $this->drupalPostForm('admin/config/system/actions', $edit, t('Create'));
    $this->assertResponse(200);

    // Make a POST request to the individual action configuration page.
    $edit = [];
    $action_label = $this->randomMachineName();
    $edit['label'] = $action_label;
    $edit['id'] = strtolower($action_label);
    $edit['owner_uid'] = $user->id();
    $this->drupalPostForm('admin/config/system/actions/add/' . Crypt::hashBase64('entity_gallery_assign_owner_action'), $edit, t('Save'));
    $this->assertResponse(200);

    // Make sure that the new action was saved properly.
    $this->assertText(t('The action has been successfully saved.'), 'The entity_gallery_assign_owner_action action has been successfully saved.');
    $this->assertText($action_label, 'The label of the entity_gallery_assign_owner_action action appears on the actions administration page after saving.');

    // Make another POST request to the action edit page.
    $this->clickLink(t('Configure'));
    preg_match('|admin/config/system/actions/configure/(.+)|', $this->getUrl(), $matches);
    $aid = $matches[1];
    $edit = [];
    $new_action_label = $this->randomMachineName();
    $edit['label'] = $new_action_label;
    $edit['owner_uid'] = $user->id();
    $this->drupalPostForm(NULL, $edit, t('Save'));
    $this->assertResponse(200);

    // Make sure that the action updated properly.
    $this->assertText(t('The action has been successfully saved.'), 'The entity_gallery_assign_owner_action action has been successfully updated.');
    $this->assertNoText($action_label, 'The old label for the entity_gallery_assign_owner_action action does not appear on the actions administration page after updating.');
    $this->assertText($new_action_label, 'The new label for the entity_gallery_assign_owner_action action appears on the actions administration page after updating.');

    // Make sure that deletions work properly.
    $this->drupalGet('admin/config/system/actions');
    $this->clickLink(t('Delete'));
    $this->assertResponse(200);
    $edit = [];
    $this->drupalPostForm("admin/config/system/actions/configure/$aid/delete", $edit, t('Delete'));
    $this->assertResponse(200);

    // Make sure that the action was actually deleted.
    $this->assertRaw(t('The action %action has been deleted.', ['%action' => $new_action_label]), 'The delete confirmation message appears after deleting the entity_gallery_assign_owner_action action.');
    $this->drupalGet('admin/config/system/actions');
    $this->assertResponse(200);
    $this->assertNoText($new_action_label, 'The label for the entity_gallery_assign_owner_action action does not appear on the actions administration page after deleting.');

    $action = Action::load($aid);
    $this->assertFalse($action, 'The entity_gallery_assign_owner_action action is not available after being deleted.');
  }

}
