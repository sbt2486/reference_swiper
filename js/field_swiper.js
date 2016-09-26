/**
 * @file field_swiper.js
 *
 * Contains the behavior that initializes fields using the swiper formatter.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Registers behaviours related to field swiper.
   */
  Drupal.behaviors.fieldSwiper = {
    attach: function (context) {
      var swiperInstances = {};
      $('.swiper-container', context).once('field-swiper').each(function () {
        var parameterKey = $(this).data('swiper-param-key');
        swiperInstances[parameterKey] = new Swiper(
          $(this)[0],
          drupalSettings.fieldSwiper.parameters[parameterKey]
        );
      });
    }
  };

}(jQuery, Drupal, drupalSettings));
