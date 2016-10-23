#Reference swiper

##Description

This module integrates the Swiper JS library (http://idangero.us/swiper) with
drupal's entity reference fields by providing a new formatter for entity
reference fields.

Please read the following description of the Swiper library before using this
module:

"Swiper - is the free and most modern mobile touch slider with hardware
accelerated transitions and amazing native behavior. It is intended to be used
in mobile websites, mobile web apps, and mobile native/hybrid apps. Designed
mostly for iOS, but also works great on latest Android, Windows Phone 8 and
modern Desktop browsers

Swiper is not compatible with all platforms, it is a modern touch slider which
is focused only on modern apps/platforms to bring the best experience and
simplicity.".

##Features

- Format any multivalue (content) entity reference field as a Swiper slider, for
  example, use file or media entities to create image sliders, or just apply it
  to multiple node references to slide through them.
- Swiper JS supports sliders on touch devices.
- Make use of the many configuration options available in Swiper's API, by just
  simply creating a Swiper option set on the provided admin UI.
- Swiper option sets are config entities, so you may import and export them, or
  even edit them with drush.
- Developers may access each field's Swiper instance separately in JS.

##Installation and configuration

1. Download the Swiper library from
https://github.com/nolimits4web/Swiper/archive/master.zip and unzip it into
DRUPAL_ROOT/libraries/swiper such that your the swiper.jquery.min.js is
accessible at DRUPAL_ROOT/libraries/swiper/dist/js/swiper.jquery.min.js .
2. Install the module as usual using the UI or drush.
3. Add a Swiper option set by navigating to
/admin/config/system/reference-swiper . Refer to
http://idangero.us/swiper/api for further information.
4. Navigate to the "Manage display" tab of your entity or bundle and switch the
   formatter of your entity reference field to "Reference Swiper".
5. Edit the formatter settings and select the option set created in step 3 by
   entering its name in the autocomplete field.

## Known issues

1. Floating a field that is using the Reference Swiper formatter with CSS will
   break the Swiper.
2. The following Swiper features arent't supported yet:
- parallax
- lazy loading
- zoom
- other new parameters that were added in version 3.4

Feel free to create an issue at
http://drupal.org/project/issues/reference_swiper in case you find a bug/have a
feature request.

##Credits:

Current maintainers:

- Sebastian Leu - https://www.drupal.org/u/s_leu
