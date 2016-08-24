<?php

namespace Drupal\field_swiper;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a Swiper option set config entity.
 */
interface SwiperOptionSetInterface extends ConfigEntityInterface {

  /**
   * Returns the value of the option set configuration.
   *
   * @return array
   *   Option set parameters as used by the Swiper library.
   */
  public function getOptionSet();

  /**
   * Sets the option set to the given value.
   *
   * @param array $values
   *   The option set's values that will be set on the entity.
   *
   * @return \Drupal\field_swiper\SwiperOptionSetInterface
   *   The swiper option set config entity.
   */
  public function setOptionSet(array $values);

}
