/**
 * @file field_swiper.js
 *
 * Contains the behavior that initializes a field's Swiper.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Registers behaviours related to tab display.
   */
  Drupal.behaviors.fieldSwiper = {
    attach: function (context) {
      var swiperInstances = [];
      $('.swiper-container').once('field-swiper').each(function (index) {
        var parameterKey = $(this).data('swiper-param-key');
        console.log(drupalSettings.fieldSwiper.parameters[parameterKey]);
        drupalSettings.fieldSwiper.parameters[parameterKey]['pagination'] = null;
        swiperInstances[index] = new Swiper(
          $(this)[0],
          //drupalSettings.fieldSwiper.parameters[parameterKey]
          {
            speed: 400,
            spaceBetween: 100
          }
        );
      });
    }
  };

}(jQuery, Drupal, drupalSettings));
