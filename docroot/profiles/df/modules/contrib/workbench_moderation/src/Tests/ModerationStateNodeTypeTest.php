<?php

namespace Drupal\workbench_moderation\Tests;

use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\Role;

/**
 * Tests moderation state node type integration.
 *
 * @group workbench_moderation
 */
class ModerationStateNodeTypeTest extends ModerationStateTestBase {

  /**
   * A node type without moderation state disabled.
   */
  public function testNotModerated() {
    $this->drupalLogin($this->adminUser);
    $this->createContentTypeFromUI('Not moderated', 'not_moderated');
    $type_exists = (bool) NodeType::load('not_moderated');
    $this->assertTrue($type_exists, 'The new content type has been created in the database.');
    $this->grantUserPermissionToCreateContentOfType($this->adminUser, 'not_moderated');
    $this->drupalGet('node/add/not_moderated');
    $this->assertRaw('Save as unpublished');
    $this->drupalPostForm(NULL, [
      'title[0][value]' => 'Test',
    ], t('Save and publish'));
    $this->assertText('Not moderated Test has been created.');
  }

  /**
   * Tests enabling moderation on an existing node-type, with content.
   */
  /**
   * A node type without moderation state enabled.
   */
  public function testEnablingOnExistingContent() {

    // Create a node type that is not moderated.
    $this->drupalLogin($this->adminUser);
    $this->createContentTypeFromUI('Not moderated', 'not_moderated');
    $this->grantUserPermissionToCreateContentOfType($this->adminUser, 'not_moderated');

    // Create content.
    $this->drupalGet('node/add/not_moderated');
    $this->drupalPostForm(NULL, [
      'title[0][value]' => 'Test',
    ], t('Save and publish'));
    $this->assertText('Not moderated Test has been created.');

    // Now enable moderation state.
    $this->enableModerationThroughUI('not_moderated', ['draft', 'needs_review', 'published'], 'draft');

    // And make sure it works.
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
      'title' => 'Test'
    ]);
    if (empty($nodes)) {
      $this->fail('Could not load node with title Test');
      return;
    }
    $node = reset($nodes);
    $this->drupalGet('node/' . $node->id());
    $this->assertResponse(200);
    $this->assertLinkByHref('node/' . $node->id() . '/edit');
    $this->drupalGet('node/' . $node->id() . '/edit');
    $this->assertResponse(200);
    $this->assertRaw('Save and Create New Draft');
    $this->assertNoRaw('Save and publish');
  }
}
