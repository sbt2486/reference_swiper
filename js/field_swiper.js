/**
 * @file field_swiper.js
 *
 * Contains the behavior that initializes field Swipers.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Registers behaviours related to field swiper.
   */
  Drupal.behaviors.fieldSwiper = {
    attach: function (context) {
      var swiperInstances = {};
      $('.swiper-container').once('field-swiper').each(function (index) {
        var parameterKey = $(this).data('swiper-param-key');
        console.log(drupalSettings.fieldSwiper.parameters[parameterKey]);
        swiperInstances[parameterKey] = new Swiper(
          $(this)[0],
          drupalSettings.fieldSwiper.parameters[parameterKey]
        );
      });
    }
  };

}(jQuery, Drupal, drupalSettings));
