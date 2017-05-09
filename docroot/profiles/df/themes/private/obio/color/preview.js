/**
 * @file
 * Color preview enhancements for the Obio theme (not functional).
 */
(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.color = {
    logoChanged: false,
    callback: function (context, settings, $form) {
      // Change the logo to be the real one.
      if (!this.logoChanged) {
        $('.color-preview .color-preview-logo img').attr('src', settings.color.logo);
        this.logoChanged = true;
      }
      // Remove the logo if the setting is toggled off.
      if (settings.color.logo === null) {
        $('div').remove('.color-preview-logo');
      }

      var $colorPreview = $form.find('.color-preview');
      var $colorPalette = $form.find('.js-color-palette');

      // Solid background.
      $colorPreview.css('background-color', $colorPalette.find('input[name="palette[headerbg]"]').val());
    }
  };
})(jQuery, Drupal, drupalSettings);
