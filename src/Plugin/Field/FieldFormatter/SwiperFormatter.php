<?php

namespace Drupal\field_swiper\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\field_swiper\Entity\SwiperOptionSet;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'Swiper' field formatter.
 *
 * @FieldFormatter(
 *   id = "swiper_formatter",
 *   label = @Translation("Swiper Field"),
 *   description = @Translation("Displays multi value field contents as Swiper slider."),
 *   field_types = {
 *     "entity_reference",
 *     "image",
 *     "file",
 *   }
 * )
 */
class SwiperFormatter extends EntityReferenceEntityFormatter implements ContainerFactoryPluginInterface {

  /**
   * The url generator service.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('logger.factory'),
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository'),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new SwiperFormatter.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, LoggerChannelFactoryInterface $logger_factory, EntityTypeManagerInterface $entity_type_manager, EntityDisplayRepositoryInterface $entity_display_repository, UrlGeneratorInterface $url_generator) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, $logger_factory, $entity_type_manager, $entity_display_repository);
    $this->loggerFactory = $logger_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'swiper_option_set' => NULL,
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    // Check whether any option sets are available.
    if (SwiperOptionSet::loadMultiple()) {
      $form['swiper_option_set'] = array(
        '#type' => 'entity_autocomplete',
        '#title' => t('Swiper option set'),
        '#target_type' => 'swiper_option_set',
        '#default_value' => SwiperOptionSet::load($this->getSetting('swiper_option_set')),
        // Validation is done in static::validateConfigurationForm().
        '#validate_reference' => FALSE,
        '#size' => '60',
        '#maxlength' => '60',
        '#description' => t('Select the Swiper option set you would like to use for this field'),
      );
    }
    else {
      $form['no_sets_info'] = $this->getNoOptionSetsAvailableInfo();
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    // Check whether any option sets are available.
    if (SwiperOptionSet::loadMultiple()) {
      if ($this->getSetting('swiper_option_set')) {
        $summary[] = t(
          'Swiper option set: @option_set',
          ['@option_set' => SwiperOptionSet::load($this->getSetting('swiper_option_set'))->label()]
        );
      }
      else {
        $summary[] = t('No Swiper option set selected');
      }
    }
    else {
      $summary[] = $this->getNoOptionSetsAvailableInfo();
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   *
   * @see ::prepareView()
   * @see ::getEntitiestoView()
   */
  public function view(FieldItemListInterface $items, $langcode = NULL) {
    $elements = parent::view($items, $langcode);
    // If there's more than one reference to display, add the Swiper library
    // and some markup for the Swiper.
    if ($items->count() > 1) {
      /** @var \Drupal\field_swiper\Entity\SwiperOptionSet $swiper_option_set */
      $swiper_option_set = SwiperOptionSet::load(
        $this->getSetting('swiper_option_set')
      );

      // Create a key that allows fetching the view mode and field specific
      // option set in JS. This is necessary in order to support different
      // Swiper option sets for the same node that might be displayed multiple
      // times on a page in different view modes with different Swiper options.
      $parameter_key = $this->fieldDefinition->id() . '.' . $this->viewMode;

      // Swiper JS requires this class.
      $elements['#attributes']['class'] = 'swiper-wrapper';

      // This will render the required markup and add the library.
      $elements = [
        '#theme' => 'swiper_container',
        '#children' => $elements,
        '#swiper_parameters' => $swiper_option_set->getParameters(),
        '#attributes' => [
          'class' => ['swiper-container'],
          'data-swiper-param-key' => $parameter_key,
        ],
        '#attached' => [
          'library' => ['field_swiper/field_swiper.swiper'],
          'drupalSettings' => [
            'fieldSwiper' => [
              'parameters' => [
                $parameter_key => $swiper_option_set->getParameters(),
              ],
            ],
          ],
        ],
      ];
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    foreach ($items as $delta => $item) {
      $items[$delta]->_attributes += ['class' => ['swiper-slide']];
    }
    return parent::viewElements($items, $langcode);
  }

  /**
   * Returns a markup element for summary and settings form.
   *
   * @return array
   *   Render array of element that indicates that there aren't any option sets.
   */
  protected function getNoOptionSetsAvailableInfo() {
    return [
      '#type' => 'item',
      '#markup' => t(
        'There are no Swiper option sets available currently. Please <a href="@url" target="_blank">create an option set</a> first.',
        ['@url' => $this->urlGenerator->generateFromRoute('entity.swiper_option_set.collection')]
      ),
    ];
  }

}
