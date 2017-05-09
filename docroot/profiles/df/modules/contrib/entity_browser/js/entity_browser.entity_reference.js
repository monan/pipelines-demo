/**
 * @file entity_browser.entity_reference.js
 *
 * Defines the behavior of the entity reference widget that utilizes entity
 * browser.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Registers behaviours related to entity reference field widget.
   */
  Drupal.behaviors.entityBrowserEntityReference = {
    attach: function (context) {
      $(context).find('.field--widget-entity-browser-entity-reference').each(function () {
        var $list = $(this).find('.entities-list');
        $list.sortable({
          stop: Drupal.entityBrowserEntityReference.entitiesReordered
        });
        // Trigger an update when an action button (Remove, Edit) is clicked.
        $list.find('input[type="submit"]').on('mousedown', function () {
          $(this).trigger('formUpdated');
        });
      });
    }
  };

  Drupal.entityBrowserEntityReference = {};

  /**
   * Reacts on sorting of the entities.
   *
   * @param {object} event
   *   Event object.
   * @param {object} ui
   *   Object with detailed information about the sort event.
   */
  Drupal.entityBrowserEntityReference.entitiesReordered = function (event, ui) {
    var items = $(this).find('.item-container');
    var ids = [];
    for (var i = 0; i < items.length; i++) {
      ids[i] = $(items[i]).attr('data-entity-id');
    }

    $(this).parent().parent().find('input[type*=hidden][name*="[target_id]"]').val(ids.join(' ')).trigger('formUpdated');
  };

}(jQuery, Drupal));
