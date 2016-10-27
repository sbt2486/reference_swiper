/**
 * @file reference_swiper.field.js
 *
 * Contains the behavior, that initializes Swiper on fields using the reference
 * swiper field formatter.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.referenceSwiper = Drupal.referenceSwiper || {};

  /**
   * Register behavior that initializes Swiper instances.
   *
   * The created instances are stored in Drupal.referenceSwiper.swiperInstances
   * and may be accessed by any other module's library by depending on the
   * reference_swiper/reference_swiper.field library.
   */
  Drupal.behaviors.referenceSwiperField = {
    attach: function (context) {
      Drupal.referenceSwiper.swiperInstances = Drupal.referenceSwiper.swiperInstances || {};
      $('.swiper-container', context).once('reference-swiper').each(function () {
        var parameterKey = $(this).data('swiper-param-key');
        Drupal.referenceSwiper.swiperInstances[parameterKey] = new Swiper(
          $(this)[0],
          drupalSettings.referenceSwiper.parameters[parameterKey]
        );
      });
    }
  };

}(jQuery, Drupal, drupalSettings));
