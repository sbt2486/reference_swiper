<?php

namespace Drupal\field_swiper\Form;

use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;

// @todo create sections using details elements according to http://idangero.us/swiper/api
class SwiperOptionSetForm extends EntityForm {

  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\field_swiper\SwiperOptionSetInterface */
    $swiper_option_set = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $swiper_option_set->label(),
      '#description' => $this->t('Label for the Example.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $swiper_option_set->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$swiper_option_set->isNew(),
    ];

    $form['parameters'] = [
      '#type' => 'details',
      '#title' => t('Swiper parameters'),
      '#tree' => TRUE,
      '#open' => TRUE,
    ];

    $form['parameters']['initialSlide'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Index number of initial slide.'),
    ];
    $form['parameters']['direction'] = [
      '#type' => 'select',
      '#options' => [
        'horizontal' => t('Horizontal'),
        'vertical' => t('Vertical'),
      ],
      '#description' => t("Could be 'horizontal' or 'vertical' (for vertical slider)."),
    ];
    $form['parameters']['speed'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Duration of transition between slides (in ms).'),
    ];
    $form['parameters']['setWrapperSize'] = [
      '#type' => 'checkbox',
      '#description' => t("Enabled this option and plugin will set width/height on swiper wrapper equal to total size of all slides. Mostly should be used as compatibility fallback option for browser that don't support flexbox layout well."),
    ];
    $form['parameters']['virtualTranslate'] = [
      '#type' => 'checkbox',
      '#description' => t('Enabled this option and swiper will be operated as usual except it will not move, real translate values on wrapper will not be set. Useful when you may need to create custom slide transition.'),
    ];
    $form['parameters']['width'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Swiper width (in px). Parameter allows to force Swiper width. Useful only if you initialize Swiper when it is hidden.<strong>Setting this parameter will make Swiper not responsive</strong>'),
    ];
    $form['parameters']['height'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Swiper height (in px). Parameter allows to force Swiper height. Useful only if you initialize Swiper when it is hidden.<strong>Setting this parameter will make Swiper not responsive</strong>'),
    ];
    $form['parameters']['autoHeight'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true and slider wrapper will adopt its height to the height of the currently active slide.'),
    ];
    $form['parameters']['roundLengths'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to round values of slides width and height to prevent blurry texts on usual resolution screens (if you have such).'),
    ];
    $form['parameters']['nested'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true on nested Swiper for correct touch events interception. Use only on nested swipers that use same direction as the parent one.'),
    ];

    // Autoplay.
    $form['parameters']['autoplay'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Delay between transitions (in ms). If set to zero or blank, auto play will be disabled.'),
    ];
    $form['parameters']['autoplayStopOnLast'] = [
      '#type' => 'checkbox',
      '#description' => t('Enable this parameter and autoplay will be stopped when it reaches last slide (has no effect in loop mode).'),
    ];
    $form['parameters']['autoplayDisableOnInteraction'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction.'),
    ];


    // Progress.
    $form['parameters']['watchSlidesProgress'] = [
      '#type' => 'checkbox',
      '#description' => t('Enable this feature to calculate each slides progress.'),
    ];
    $form['parameters']['watchSlidesVisibility'] = [
      '#type' => 'checkbox',
      '#description' => t("'Watch slides progress' should be enabled. Enable this option and slides that are in viewport will have additional visible class."),
    ];
    // Freemode.
    $form['parameters']['freeMode'] = [
      '#type' => 'checkbox',
      '#description' => t('If true then slides will not have fixed positions.'),
    ];
    $form['parameters']['freeModeMomentum'] = [
      '#type' => 'checkbox',
      '#description' => t('If true, then slide will keep moving for a while after you release it.'),
    ];
    $form['parameters']['freeModeMomentumRatio'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Higher value produces larger momentum distance after you release slider.'),
    ];
    $form['parameters']['freeModeMomentumBounce'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false if you want to disable momentum bounce in free mode.'),
    ];
    $form['parameters']['freeModeMomentumBounceRatio'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Higher value produces larger momentum bounce effect.'),
    ];
    $form['parameters']['freeModeMinimumVelocity'] = [
      '#min' => 0,
      '#step' => 0.1,
      '#description' => t('Minimum touchmove-velocity required to trigger free mode momentum.'),
    ];
    $form['parameters']['freeModeSticky'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to enable snap to slides positions in free mode.'),
    ];

    // Effects.
    $form['parameters']['effect'] = [
      '#type' => 'select',
      '#options' => [
        'slide' => t('Slide'),
        'fade' => t('Fade'),
        'cube' => t('Cube'),
        'coverflow' => t('Coverflow'),
        'flip' => t('Flip'),
      ],
      '#description' => t('Could be "slide", "fade", "cube", "coverflow" or "flip".'),
    ];
    $form['parameters']['fade'] = [
      '#type' => 'textarea',
      '#description' => t('Fade effect parameters.'),
    ];
    $form['parameters']['cube'] = [
      '#type' => 'textarea',
      '#description' => t('Cube effect parameters. For better performance you may disable shadows.'),
    ];
    $form['parameters']['coverflow'] = [
      '#type' => 'textarea',
      '#description' => t('Coverflow effect parameters. For better performance you may disable shadows.'),
    ];
    $form['parameters']['flip'] = [
      '#type' => 'textarea',
      '#description' => t('Flip effect parameters. limitRotation (when enabled) limits slides rotation angle to 180deg maximum. It allows to quickly "flip" between different slides. If you use "slow" transitions then it is better to disable it.'),
    ];

    // Parallax.
    $form['parameters']['parallax'] = [
      '#type' => 'checkbox',
      '#description' => t('Enable, if you want to use "parallaxed" elements inside of slider.'),
    ];
    // Slides grid.
    $form['parameters']['spaceBetween'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Distance between slides in px.'),
    ];
    $form['parameters']['slidesPerView'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t("Number of slides per view (slides visible at the same time on slider's container) or zero to set automatic."),
    ];
    $form['parameters']['slidesPerColumn'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Number of slides per column, for multirow layout.'),
    ];
    $form['parameters']['slidesPerColumnFill'] = [
      '#type' => 'select',
      '#options' => [
        'row' => t('Row'),
        'column' => t('Column'),
      ],
      '#description' => t("Could be 'column' or 'row'. Defines how slides should fill rows, by column or by row."),
    ];
    $form['parameters']['slidesPerGroup'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Set numbers of slides to define and enable group sliding. Useful to use with slidesPerView > 1.'),
    ];
    $form['parameters']['centeredSlides'] = [
      '#type' => 'checkbox',
      '#description' => t('If true, then active slide will be centered, not always on the left side.'),
    ];
    $form['parameters']['slidesOffsetBefore'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Add (in px) additional slide offset in the beginning of the container (before all slides).'),
    ];
    $form['parameters']['slidesOffsetAfter'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Add (in px) additional slide offset in the end of the container (after all slides).'),
    ];

    // Grab Cursor
    $form['parameters']['grabCursor'] = [
      '#type' => 'checkbox',
      '#description' => t('This option may a little improve desktop usability. If true, user will see the "grab" cursor when hover on Swiper.'),
    ];

    // Touches
    $form['parameters']['touchEventsTarget'] = [
      '#type' => 'textfield',
      '#description' => t('	Target element to listen touch events on. Can be "container" (to listen for touch events on swiper-container) or "wrapper" (to listen for touch events on swiper-wrapper).'),
    ];
    $form['parameters']['touchRatio'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
    ];
    $form['parameters']['touchAngle'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Allowable angle (in degrees) to trigger touch move.'),
    ];
    $form['parameters']['simulateTouch'] = [
      '#type' => 'checkbox',
      '#description' => t('If true, Swiper will accept mouse events like touch events (click and drag to change slides).'),
    ];
    $form['parameters']['shortSwipes'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false if you want to disable short swipes.'),
    ];
    $form['parameters']['longSwipes'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false if you want to disable long swipes.'),
    ];
    $form['parameters']['longSwipesRatio'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 0.1,
      '#description' => t('Ratio to trigger swipe to next/previous slide during long swipes.'),
    ];
    $form['parameters']['longSwipesMs'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Minimal duration (in ms) to trigger swipe to next/previous slide during long swipes.'),
    ];
    $form['parameters']['followFinger'] = [
      '#type' => 'checkbox',
      '#description' => t('If disabled, then slider will be animated only when you release it, it will not move while you hold your finger on it.'),
    ];
    $form['parameters']['onlyExternal'] = [
      '#type' => 'checkbox',
      '#description' => t('If true, then the only way to switch the slide is use of external API functions like slidePrev or slideNext.'),
    ];
    $form['parameters']['threshold'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Threshold value in px. If "touch distance" will be lower than this value then swiper will not move.'),
    ];
    $form['parameters']['touchMoveStopPropagation'] = [
      '#type' => 'checkbox',
      '#description' => t('If enabled, then propagation of "touchmove" will be stopped.'),
    ];
    $form['parameters']['iOSEdgeSwipeDetection'] = [
      '#type' => 'checkbox',
      '#description' => t('IEnable to release Swiper events for swipe-to-go-back work in iOS UIWebView.'),
    ];
    $form['parameters']['iOSEdgeSwipeThreshold'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Area (in px) from left edge of the screen to release touch events for swipe-to-go-back in iOS UIWebView.'),
    ];

    // Touch Resistance.
    $form['parameters']['resistance'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false if you want to disable resistant bounds.'),
    ];
    $form['parameters']['resistanceRatio'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 0.01,
      '#description' => t('This option allows you to control resistance ratio.'),
    ];

    // Clicks.
    $form['parameters']['preventClicks'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to prevent accidental unwanted clicks on links during swiping.'),
    ];
    $form['parameters']['preventClicksPropagation'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to stop clicks event propagation on links during swiping.'),
    ];
    $form['parameters']['slideToClickedSlide'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true and click on any slide will produce transition to this slide.'),
    ];

    // Swiping / No swiping.
    $form['parameters']['allowSwipeToPrev'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false to disable swiping to previous slide direction (to left or top).'),
    ];
    $form['parameters']['allowSwipeToNext'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false to disable swiping to next slide direction (to right or bottom).'),
    ];
    $form['parameters']['noSwiping'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to false to disable swiping to next slide direction (to right or bottom).'),
    ];
    $form['parameters']['noSwipingClass'] = [
      '#type' => 'textfield',
      '#description' => t("If true, then you can add noSwipingClass class to swiper's slide to prevent/disable swiping on this element."),
    ];
    $form['parameters']['swipeHandler'] = [
      '#type' => 'textfield',
      '#description' => t('String with CSS selector of the container with pagination that will work as only available handler for swiping.'),
    ];

    // Pagination.
    $form['parameters']['pagination'] = [
      '#type' => 'textfield',
      '#description' => t('String with CSS selector of the container with pagination.'),
    ];
    $form['parameters']['paginationType'] = [
      '#type' => 'select',
      '#options' => [
        'bullets' => t('Bullets'),
        'fraction' => t('Fraction'),
        'progress' => t('Progress'),
        'custom' => t('Custom'),
      ],
      '#description' => t('Type of pagination. Can be "bullets", "fraction", "progress" or "custom"'),
    ];
    $form['parameters']['paginationHide'] = [
      '#type' => 'checkbox',
      '#description' => t("Toggle (hide/true) pagination container visibility when click on Slider's container."),
    ];
    $form['parameters']['paginationClickable'] = [
      '#type' => 'checkbox',
      '#description' => t('If true then clicking on pagination button will cause transition to appropriate slide.'),
    ];
    $form['parameters']['paginationElement'] = [
      '#type' => 'textfield',
      '#description' => t('Defines which HTML tag will be use to represent single pagination bullet. . Only for bullets pagination type.'),
    ];
    $form['parameters']['paginationBulletRender'] = [
      '#type' => 'textarea',
      '#description' => t('This parameter allows totally customize pagination bullets, you need to pass here a function that accepts index number of pagination bullet and required element class name (className). Only for bullets pagination type.'),
    ];
    $form['parameters']['paginationFractionRender'] = [
      '#type' => 'textarea',
      '#description' => t('This parameter allows to customize "fraction" pagination html. Only for fraction pagination type.'),
    ];
    $form['parameters']['paginationProgressRender'] = [
      '#type' => 'textarea',
      '#description' => t('This parameter allows to customize "progress" pagination. Only for progress pagination type.'),
    ];
    $form['parameters']['paginationCustomRender'] = [
      '#type' => 'textarea',
      '#description' => t('This parameter is required for custom pagination type where you have to specify how it should be rendered.'),
    ];

    // Navigation Buttons.
    $form['parameters']['nextButton'] = [
      '#type' => 'textfield',
      '#description' => t('String with CSS selector of the element that will work like "next" button after click on it.'),
    ];
    $form['parameters']['prevButton'] = [
      '#type' => 'textfield',
      '#description' => t('String with CSS selector of the element that will work like "prev" button after click on it.'),
    ];

    // Scollbar.
    $form['parameters']['scrollbar'] = [
      '#type' => 'textfield',
      '#description' => t('String with CSS selector of the container with scrollbar.'),
    ];
    $form['parameters']['scrollbarHide'] = [
      '#type' => 'checkbox',
      '#description' => t('Hide scrollbar automatically after user interaction.'),
    ];
    $form['parameters']['scrollbarDraggable'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to enable make scrollbar draggable that allows you to control slider position.'),
    ];
    $form['parameters']['scrollbarSnapOnRelease'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to snap slider position to slides when you release scrollbar.'),
    ];

    // Accessibility.
    $form['parameters']['a11y'] = [
      '#type' => 'checkbox',
      '#description' => t('Option to enable keyboard accessibility to provide foucsable navigation buttons and basic ARIA for screen readers.'),
    ];
    $form['parameters']['prevSlideMessage'] = [
      '#type' => 'textfield',
      '#description' => t('Message for screen readers for previous button.'),
    ];
    $form['parameters']['nextSlideMessage'] = [
      '#type' => 'textfield',
      '#description' => t('Message for screen readers for next button.'),
    ];
    $form['parameters']['firstSlideMessage'] = [
      '#type' => 'textfield',
      '#description' => t('Message for screen readers for previous button when swiper is on first slide.'),
    ];
    $form['parameters']['lastSlideMessage'] = [
      '#type' => 'textfield',
      '#description' => t('Message for screen readers for previous button when swiper is on last slide.'),
    ];
    $form['parameters']['paginationBulletMessage'] = [
      '#type' => 'textfield',
      '#description' => t('Message for screen readers for single pagination bullet.'),
    ];

    // Keyboard / Mousewheel
    $form['parameters']['keyboardControl'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to enable navigation through slides using keyboard right and left (for horizontal mode), top and borrom (for vertical mode) keyboard arrows.'),
    ];
    $form['parameters']['mousewheelControl'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to enable navigation through slides using mouse wheel.'),
    ];
    $form['parameters']['mousewheelForceToAxis'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to force mousewheel swipes to axis. So in horizontal mode mousewheel will work only with horizontal mousewheel scrolling, and only with vertical scrolling in vertical mode.'),
    ];
    $form['parameters']['mousewheelReleaseOnEdges'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true and swiper will release mousewheel event and allow page scrolling when swiper is on edge positions (in the beginning or in the end).'),
    ];
    $form['parameters']['mousewheelInvert'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to invert sliding direction.'),
    ];
    $form['parameters']['mousewheelSensitivity'] = [
      '#type' => 'number',
      '#min' => 1,
      '#step' => 1,
      '#description' => t('Multiplier of mousewheel data, allows to tweak mouse wheel sensitivity.'),
    ];

    // Hash Navigation
    $form['parameters']['hashnav'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to enable hash url navigation to for slides.'),
    ];

    // Images.
    $form['parameters']['preloadImages'] = [
      '#type' => 'checkbox',
      '#description' => t('When enabled Swiper will force to load all images.'),
    ];
    $form['parameters']['updateOnImagesReady'] = [
      '#type' => 'checkbox',
      '#description' => t('When enabled Swiper will be reinitialized after all inner images (<img> tags) are loaded. Required preloadImages: true.'),
    ];
    $form['parameters']['lazyLoading'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to "true" to enable images lazy loading. Note that preloadImages should be disabled.'),
    ];
    $form['parameters']['lazyLoadingInPrevNext'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to "true" to enable lazy loading for the closest slides images (for previous and next slide images).'),
    ];
    $form['parameters']['lazyLoadingInPrevNextAmount'] = [
      '#type' => 'number',
      '#min' => !empty($swiper_option_set->getParameters()['slidesPerView']) ? $swiper_option_set->getParameters()['slidesPerView'] : 1,
      '#step' => 1,
      '#description' => t("Amount of next/prev slides to preload lazy images in. Can't be less than slidesPerView."),
    ];
    $form['parameters']['lazyLoadingOnTransitionStart'] = [
      '#type' => 'checkbox',
      '#description' => t('By default, Swiper will load lazy images after transition to this slide, so you may enable this parameter if you need it to start loading of new image in the beginning of transition.'),
    ];

    // Loop.
    $form['parameters']['loop'] = [
      '#type' => 'checkbox',
      '#description' => t('.'),
    ];
    $form['parameters']['loopAdditionalSlides'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t('Addition number of slides that will be cloned after creating of loop.'),
    ];
    $form['parameters']['loopedSlides'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#description' => t("If you use slidesPerView:'auto' with loop mode you should tell to Swiper how many slides it should loop (duplicate) using this parameter."),
    ];

    // Controller.
    // $form['parameters']['control'] = [
    //   '#type' => '[Swiper Instance]',
    //   '#description' => t('Pass here another Swiper instance or array with Swiper instances that should be controlled by this Swiper.'),
    // ];
    $form['parameters']['controlInverse'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true and controlling will be in inverse direction.'),
    ];
    $form['parameters']['controlBy'] = [
      '#type' => 'textfield',
      '#description' => t("Can be 'slide' or 'container'. Defines a way how to control another slider: slide by slide (with respect to other slider's grid) or depending on all slides/container (depending on total slider percentage) Observer"),
    ];

    // Observer.
    $form['parameters']['observer'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true to enable Mutation Observer on Swiper and its elements. In this case Swiper will be updated (reinitialized) each time if you change its style (like hide/show) or modify its child elements (like adding/removing slides).'),
    ];
    $form['parameters']['observeParents'] = [
      '#type' => 'checkbox',
      '#description' => t('Set to true if you also need to watch Mutations for Swiper parent elements.'),
    ];

    // Breakpoints
    $form['parameters']['breakpoints'] = [
      '#type' => 'textarea',
      '#description' => t("	Allows to set different parameter for different responsive breakpoints (screen sizes). Not all parameters can be changed in breakpoints, only those which are not required different layout and logic, like slidesPerView, slidesPerGroup, spaceBetween. Such parameters like slidesPerColumn, loop, direction, effect won't work. "),
    ];

    // Callbacks.
    $form['parameters']['runCallbacksOnInit'] = [
      '#type' => 'checkbox',
      '#description' => t('Run on[Transition/SlideChange][Start/End] callbacks on swiper initialization. Such callbacks will be fired on initialization in case of your initialSlide is not 0, or you use loop mode.'),
    ];
    $form['parameters']['onInit'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed right after Swiper initialization'),
    ];
    $form['parameters']['onSlideChangeStart'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed in the beginning of animation to other slide (next or previous). Receives swiper instance as an argument.'),
    ];
    $form['parameters']['onSlideChangeEnd'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed after animation to other slide (next or previous). Receives slider instance as an argument.'),
    ];
    $form['parameters']['onSlideNextStart'] = [
      '#type' => 'textarea',
      '#description' => t('Same as "onSlideChangeStart" but for "forward" direction only.'),
    ];
    $form['parameters']['onSlideNextStart'] = [
      '#type' => 'textarea',
      '#description' => t('Same as "onSlideChangeEnd" but for "forward" direction only.'),
    ];
    $form['parameters']['onSlidePrevStart'] = [
      '#type' => 'textarea',
      '#description' => t('Same as "onSlideChangeStart" but for "backward" direction only.'),
    ];
    $form['parameters']['onSlidePrevEnd'] = [
      '#type' => 'textarea',
      '#description' => t('Same as "onSlideChangeEnd" but for "backward" direction only.'),
    ];
    $form['parameters']['onTransitionStart'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed in the beginning of transition. Receives swiper instance as an argument.'),
    ];
    $form['parameters']['onTransitionEnd'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed after transition. Receives slider instance as an argument.'),
    ];
    $form['parameters']['onTouchStart'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user touch Swiper. Receives swiper instance and 'touchstart' event as an arguments."),
    ];
    $form['parameters']['onTouchMove'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user touch and move finger over Swiper. Receives swiper instance and 'touchmove' event as an arguments."),
    ];
    $form['parameters']['onTouchMoveOpposite'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user touch and move finger over Swiper in direction opposite to direction parameter. Receives swiper instance and 'touchmove' event as an arguments."),
    ];
    $form['parameters']['onSliderMove'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user touch and move finger over Swiper and move it. Receives swiper instance and 'touchmove' event as an arguments."),
    ];
    $form['parameters']['onTouchEnd'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user release Swiper. Receives swiper instance and 'touchend' event as an arguments."),
    ];
    $form['parameters']['onClick'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user click/tap on Swiper after 300ms delay. Receives swiper instance and 'touchend' event as an arguments."),
    ];
    $form['parameters']['onTap'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user click/tap on Swiper. Receives swiper instance and 'touchend' event as an arguments."),
    ];
    $form['parameters']['onDoubleTap'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, event, will be executed when user double tap on Swiper's container. Receives swiper instance and 'touchend' event as an arguments"),
    ];
    $form['parameters']['onImagesReady'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed right after all inner images are loaded. updateOnImagesReady should be also enabled'),
    ];
    $form['parameters']['onProgress'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, progress, will be executed when Swiper progress is changed, as second arguments it receives progress that is always from 0 to 1'),
    ];
    $form['parameters']['onReachBeginning'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed when Swiper reach its beginning (initial position)'),
    ];
    $form['parameters']['onReachEnd'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed when Swiper reach last slide'),
    ];
    $form['parameters']['onDestroy'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed when you destroy Swiper'),
    ];
    $form['parameters']['onSetTranslate'] = [
      '#type' => 'textarea',
      '#description' => t("Callback function with arguments, swiper, translate, will be executed when swiper's wrapper change its position. Receives swiper instance and current translate value as an arguments"),
    ];
    $form['parameters']['onSetTransition'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, transition, will be executed everytime when swiper starts animation. Receives swiper instance and current transition duration (in ms) as an arguments'),
    ];
    $form['parameters']['onAutoplay'] = [
      '#type' => 'textarea',
      '#description' => t('Same as onSlideChangeStart but caused by autoplay.'),
    ];
    $form['parameters']['onAutoplayStart'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed when when autoplay started'),
    ];
    $form['parameters']['onAutoplayStop'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, will be executed when when autoplay stopped'),
    ];
    $form['parameters']['onLazyImageLoad'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, slide, image, will be executed in the beginning of lazy loading of image'),
    ];
    $form['parameters']['onLazyImageReady'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function with arguments, swiper, slide, image, will be executed when lazy loading image will be loaded'),
    ];
    $form['parameters']['onPaginationRendered'] = [
      '#type' => 'textarea',
      '#description' => t('Callback function, will be executed after pagination elements generated and added to DOM.'),
    ];

    // Namespace.
    $form['parameters']['slideClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of slide.'),
    ];
    $form['parameters']['slideActiveClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of currently active slide.'),
    ];
    $form['parameters']['slideVisibleClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of currently visible slide.'),
    ];
    $form['parameters']['slideDuplicateClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of slide duplicated by loop mode.'),
    ];
    $form['parameters']['slideNextClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of slide which is right after currently active slide.'),
    ];
    $form['parameters']['slidePrevClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of slide which is right before currently active slide.'),
    ];
    $form['parameters']['wrapperClass'] = [
      '#type' => 'textfield',
      '#description' => t("CSS class name of slides' wrapper."),
    ];
    $form['parameters']['bulletClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of single pagination bullet.'),
    ];
    $form['parameters']['bulletActiveClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of currently active pagination bullet.'),
    ];
    $form['parameters']['paginationHiddenClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of pagination when it becomes inactive.'),
    ];
    $form['parameters']['paginationCurrentClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of the element with currently active index in "fraction" pagination.'),
    ];
    $form['parameters']['paginationTotalClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of the element with total number of "snaps" in "fraction" pagination.'),
    ];
    $form['parameters']['paginationProgressbarClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of pagination progressbar.'),
    ];
    $form['parameters']['buttonDisabledClass'] = [
      '#type' => 'textfield',
      '#description' => t('CSS class name of next/prev button when it becomes disabled.'),
    ];

    // Set defaults and add titles for the fields.
    $options = $this->getSwiperDefaults();
    foreach (array_keys($options) as $key) {
      $title = ucfirst(strtolower(preg_replace('/([A-Z])/', ' $1', $key)));
      $default = !empty($swiper_option_set->getParameters()[$key]) ? $swiper_option_set->getParameters()[$key] : $options[$key];
      $form['parameters'][$key]['#title'] = $title;
      $form['parameters'][$key]['#default_value'] = $default;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\field_swiper\SwiperOptionSetInterface */
    $swiper_option_set = $this->entity;
    $status = $swiper_option_set->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label Swiper option set.', array(
        '%label' => $swiper_option_set->label(),
      )));
    }
    else {
      drupal_set_message($this->t('The %label Swiper option set was not saved.', array(
        '%label' => $swiper_option_set->label(),
      )));
    }

    $form_state->setRedirect('entity.swiper_option_set.collection');
  }

  public function exist($id) {
    $entity = $this->entityQuery->get('swiper_option_set')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

  /**
   * Provides the Swiper library's parameter default values.
   *
   * @return array
   *   Array of default parameter values, indexed by parameter id.
   */
  protected function getSwiperDefaults() {
    // @todo make defaults configurable using simple config.
    return [
      'initialSlide' => 0,
      'direction' => 'horizontal',
      'speed' => 300,
      'setWrapperSize' => FALSE,
      'virtualTranslate' => FALSE,
      'width' => '',
      'height' => '',
      'autoHeight' => FALSE,
      'roundLengths' => FALSE,
      'nested' => FALSE,
      // Autoplay.
      'autoplay' => 1000,
      'autoplayStopOnLast' => FALSE,
      'autoplayDisableOnInteraction' => TRUE,
      // Progress.
      'watchSlidesProgress' => FALSE,
      'watchSlidesVisibility' => FALSE,
      // Freemode.
      'freeMode' => FALSE,
      'freeModeMomentum' => TRUE,
      'freeModeMomentumRatio' => 1,
      'freeModeMomentumBounce' => TRUE,
      'freeModeMomentumBounceRatio' => 1,
      'freeModeMinimumVelocity' => 0.02,
      'freeModeSticky' => FALSE,
      // Effects.
      'effect' => 'slide',
      'fade' => '',
      'cube' => '',
      'coverflow' => '',
      'flip' => '',
      // Parallax.
      'parallax' => FALSE,
      // Slides grid.
      'spaceBetween' => 0,
      'slidesPerView' => 1,
      'slidesPerColumn' => 1,
      'slidesPerColumnFill' => 'column',
      'slidesPerGroup' => 1,
      'centeredSlides' => FALSE,
      'slidesOffsetBefore' => 0,
      'slidesOffsetAfter' => 0,
      // Grab cursor.
      'grabCursor' => FALSE,
      // Touches.
      'touchEventsTarget' => 'container',
      'touchRatio' => 1,
      'touchAngle' => 45,
      'simulateTouch' => TRUE,
      'shortSwipes' => TRUE,
      'longSwipes' => TRUE,
      'longSwipesRatio' => 0.5,
      'longSwipesMs' => 300,
      'followFinger' => TRUE,
      'onlyExternal' => FALSE,
      'threshold' => 0,
      'touchMoveStopPropagation' => TRUE,
      'iOSEdgeSwipeDetection' => FALSE,
      'iOSEdgeSwipeThreshold' => 20,
      // Touch resistance.
      'resistance' => TRUE,
      'resistanceRatio' => 0.85,
      // Clicks
      'preventClicks' => TRUE,
      'preventClicksPropagation' => TRUE,
      'slideToClickedSlide' => FALSE,
      // Swiping / no swiping.
      'allowSwipeToPrev' => TRUE,
      'allowSwipeToNext' => TRUE,
      'noSwiping' => TRUE,
      'noSwipingClass' => 'swiper-no-swiping',
      'swipeHandler' => NULL,
      // Pagination.
      'pagination' => '.swiper-pagination',
      'paginationType' => 'bullets',
      'paginationHide' => TRUE,
      'paginationClickable' => FALSE,
      'paginationElement' => 'span',
      'paginationBulletRender' => NULL,
      'paginationFractionRender' => NULL,
      'paginationProgressRender' => NULL,
      'paginationCustomRender' => NULL,
      // Navigation Buttons.
      'nextButton' => '.swiper-button-next',
      'prevButton' => '.swiper-button-prev',
      // Scrollbar.
      'scrollbar' => '.swiper-scrollbar',
      'scrollbarHide' => TRUE,
      'scrollbarDraggable' =>	FALSE,
      'scrollbarSnapOnRelease' => FALSE,
      // Accessibility.
      'a11y' => FALSE,
      'prevSlideMessage' => t('Previous slide'),
      'nextSlideMessage' => t('Next slide'),
      'firstSlideMessage' => t('This is the first slide'),
      'lastSlideMessage' => t('This is the last slide'),
      'paginationBulletMessage' => t(	'Go to slide {{index}}'),
      // Keyboard / mousewheel.
      'keyboardControl' => FALSE,
      'mousewheelControl' => FALSE,
      'mousewheelForceToAxis' => FALSE,
      'mousewheelReleaseOnEdges' => FALSE,
      'mousewheelInvert' => FALSE,
      'mousewheelSensitivity' => 1,
      // Hash navigation.
      'hashnav' => FALSE,
      // Images
      'preloadImages' => TRUE,
      'updateOnImagesReady' => TRUE,
      'lazyLoading' => FALSE,
      'lazyLoadingInPrevNext' => FALSE,
      'lazyLoadingInPrevNextAmount' => 1,
      'lazyLoadingOnTransitionStart' => FALSE,
      // Loop.
      'loop' => FALSE,
      'loopAdditionalSlides' => 0,
      'loopedSlides' => NULL,
      // Control
      //'control' => '', // Named reference to Swiper instances
      'controlInverse' => FALSE,
      'controlBy' => 'slide',
      // Observer.
      'observer' => FALSE,
      'observeParents' => FALSE,
      // Breakpoints.
      'breakpoints' => '',
      // Callbacks.
      'runCallbacksOnInit' => TRUE,
      'onInit' => '',
      'onSlideChangeStart' => '',
      'onSlideChangeEnd' => '',
      'onSlideNextStart' => '',
      'onSlideNextEnd' => '',
      'onSlidePrevStart' => '',
      'onSlidePrevEnd' => '',
      'onTransitionStart' => '',
      'onTransitionEnd' => '',
      'onTouchStart' => '',
      'onTouchMove' => '',
      'onTouchMoveOpposite' => '',
      'onSliderMove' => '',
      'onTouchEnd' => '',
      'onClick' => '',
      'onTap' => '',
      'onDoubleTap' => '',
      'onImagesReady' => '',
      'onProgress' => '',
      'onReachBeginning' => '',
      'onReachEnd' => '',
      'onDestroy' => '',
      'onSetTranslate' => '',
      'onSetTransition' => '',
      'onAutoplay' => '',
      'onAutoplayStart' => '',
      'onAutoplayStop' => '',
      'onLazyImageLoad' => '',
      'onLazyImageReady' => '',
      'onPaginationRendered' => '',
      // Namespace.
      'slideClass' => 'swiper-slide',
      'slideActiveClass' => 'swiper-slide-active',
      'slideVisibleClass' => 'swiper-slide-visible',
      'slideDuplicateClass' => 'swiper-slide-duplicate',
      'slideNextClass' => 'swiper-slide-next',
      'slidePrevClass' => 'swiper-slide-prev',
      'wrapperClass' => 'swiper-wrapper',
      'bulletClass' => 'swiper-pagination-bullet',
      'bulletActiveClass' => 'swiper-pagination-bullet-active',
      'paginationHiddenClass' => 'swiper-pagination-hidden',
      'paginationCurrentClass' =>'swiper-pagination-current',
      'paginationTotalClass' =>'swiper-pagination-total',
      'paginationProgressbarClass' => 'swiper-pagination-progressbar',
      'buttonDisabledClass' => 'swiper-button-disabled',
    ];
  }

}
