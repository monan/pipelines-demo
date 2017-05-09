<?php

namespace Drupal\migrate_plus\Plugin;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\migrate_plus\Entity\Migration;

/**
 * Expose migration entities in the active config store as derivative plugins.
 */
class MigrationConfigDeriver extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (empty($this->derivatives)) {
      $migrations = Migration::loadMultiple();
      /** @var \Drupal\migrate_plus\Entity\MigrationInterface $migration */
      foreach ($migrations as $id => $migration) {
        $this->derivatives[$id] = $migration->toArray();
      }
    }
    return $this->derivatives;
  }

}
