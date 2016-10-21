#Reference swiper

##Description

This module integrates the Swiper JS library (http://idangero.us/swiper) with
drupal's entity reference fields by providing a new formatter for entity
reference fields.

Please read the following before using this module:

"Swiper - is the free and most modern mobile touch slider with hardware
accelerated transitions and amazing native behavior. It is intended to be used
in mobile websites, mobile web apps, and mobile native/hybrid apps. Designed
mostly for iOS, but also works great on latest Android, Windows Phone 8 and
modern Desktop browsers

Swiper is not compatible with all platforms, it is a modern touch slider which
is focused only on modern apps/platforms to bring the best experience and
simplicity.".

##Benefits

- Format anything you can reference within an entity reference field as a swiper
  gallery, so this swiping can be applied to almost anything.
- Swiper JS supports Swiping on mobile browsers quite well, so unlike the
  field_slideshow module, your galleries will work on those devices as well.
- Tons of configuration options exposed in the Swiper option set form.
- Swiper option sets are config entities, so you can import and export them, or
  even edit them with drush.
- Developers may access each field's Swiper instance separately in JS.

##Installation and configuration

1. Install the module as usual using the UI or drush.
2. Add a Swiper option set by navigating to
/admin/config/system/reference-swiper
3. Navigate to the "Manage display" tab of your entity or bundle and switch the
   formatter of your entity reference field to "Reference Swiper".
4. Edit the formatter settings and select the option set created in step 2 by
   entering its name in the autocomplete field

## Known issues

1. Floating a field that is using the Reference Swiper formatter with CSS will
   break the Swiper.
2. Swiper's parallax and lazy loading features aren't supported yet.

Feel free to create an issue at the module page in case you find a bug/have a
feature request.

##Credits:

Current maintainers:

- Sebastian Leu - https://www.drupal.org/u/s_leu
