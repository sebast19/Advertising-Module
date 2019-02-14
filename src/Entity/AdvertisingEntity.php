<?php

namespace Drupal\advertising\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Component\Serialization\PhpSerialize;

/**
 * Defines the Advertising entity entity.
 *
 * @ConfigEntityType(
 *   id = "advertising_entity",
 *   label = @Translation("Advertising entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\advertising\AdvertisingEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\advertising\Form\AdvertisingEntityForm",
 *       "edit" = "Drupal\advertising\Form\AdvertisingEntityForm",
 *       "delete" = "Drupal\advertising\Form\AdvertisingEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\advertising\AdvertisingEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "advertising_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "url_ad" = "url_ad"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/advertising_entity/{advertising_entity}",
 *     "add-form" = "/admin/structure/advertising_entity/add",
 *     "edit-form" = "/admin/structure/advertising_entity/{advertising_entity}/edit",
 *     "delete-form" = "/admin/structure/advertising_entity/{advertising_entity}/delete",
 *     "collection" = "/admin/structure/advertising_entity"
 *   }
 * )
 */
class AdvertisingEntity extends ConfigEntityBase implements AdvertisingEntityInterface {

  /**
   * The Advertising entity ID.
   *
   * @var string
   */
  public $id;

  /**
   * The Advertising entity label.
   *
   * @var string
   */
  public $label;

  /**
   * The Advertising entity Url.
   *
   * @var string
   */
  public $url_ad;

  /**
   * The Advertising entity id of ad.
   *
   * @var string
   */
  public $id_ad;

  /**
   * The Advertising entity place.
   *
   * @var string
   */
  protected $place;

  /**
   * The Advertising entity breakpoints.
   *
   * @var array
   */
  public $breakpoints;

   /**
   * Set the default place to put an AD.
   *
   * @param string $place
   *   The place to set.
   *
   * @return string
   */
  public function setPlace($place) {
    return $this->set('place', $place);
  }

  /**
   * Get the default place to put an AD.
   *
   * @return string
   */
  public function getPlace() {
    return $this->get('place');
  }

  /**
   * Set the default breakpoints.
   *
   * @param string $breakpoints
   *   The breakpoints to set.
   *
   * @return string
   */
  public function setBreakpoints($breakpoints) {
    $serializer = \Drupal::service('serialization.phpserialize');
    $this->breakpoints = $serializer->encode($breakpoints);
  }

  /**
   * Get the breakpoints.
   *
   * @return string
   */
  public function getBreakpoints() {
    $serializer = \Drupal::service('serialization.phpserialize');
    return $serializer->decode($this->breakpoints);
  }
}
