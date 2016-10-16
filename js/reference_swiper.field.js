/**
 * @file reference_swiper.field.js
 *
 * Contains the behavior, that initializes Swiper on fields using the reference
 * swiper field formatter.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Register behavior that initializes Swiper instances.
   */
  Drupal.behaviors.referenceSwiperField = {
    attach: function (context) {
      var swiperInstances = {};
      $('.swiper-container', context).once('reference-swiper').each(function () {
        var parameterKey = $(this).data('swiper-param-key');
        swiperInstances[parameterKey] = new Swiper(
          $(this)[0],
          drupalSettings.referenceSwiper.parameters[parameterKey]
        );
      });
    }
  };

}(jQuery, Drupal, drupalSettings));
