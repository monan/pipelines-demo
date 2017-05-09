<?php

namespace Drupal\dfs_obio\Plugin\migrate\source;

use Drupal\migrate\Row;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\df_tools_migration\Plugin\migrate\source\AuthorMigrationTrait;

/**
 * Source for Entity Gallery via CSV with a coded Creator author.
 *
 * @MigrateSource(
 *   id = "dfs_obio_image_gallery"
 * )
 */
class DfsObioImageGallery extends CSV {

  use AuthorMigrationTrait;

  public function prepareRow(Row $row) {
    if ($images = $row->getSourceProperty('Images')) {
      $image_names = explode(',', $images);
      $files = [];
      foreach ($image_names as $image_name) {
        $path = dirname($this->configuration['path']) . '/images/' . $image_name;
        $data = file_get_contents($path);
        $uri = file_build_uri($image_name);
        $files[] = file_save_data($data, $uri);
      }
      $row->setSourceProperty('Images', $files);
    }
    $this->setUidProperty($row, null, 'creator');
  }

}
