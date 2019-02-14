<?php

namespace Drupal\advertising\Plugin\Derivative;

use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Component\Serialization\PhpSerialize;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides block plugin definitions advertising Entities Configuration.
 *
 * @see \Drupal\advertising\Plugin\Block\AdvertisingEntityBlock
 */
class AdvertisingEntityBlock extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The database connection
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The PHP serlilizer
   *
   * @var Drupal\Component\Serialization\PhpSerialize
   */
  protected $serializer;

  /**
   * Constructs new AdvertisingEntityBlock.
   *
   * @param Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param Drupal\Component\Serialization\PhpSerialize $serializer
   *   The PHP serlilizer.
   */
  public function __construct(Connection $connection, PhpSerialize $serializer) {
    $this->connection = $connection;
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('database'),
      $container->get('serialization.phpserialize')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    $result = $this->connection
      ->query(
        'SELECT data FROM `config` WHERE name LIKE :name ', [':name' => "advertising.advertising_entity.%"]
    );

    $blocks_ids = [];

    if($result) {
      while ($row = $result->fetchAssoc()) {
        $data = $this->serializer->decode($row['data']);
        $blocks_ids[] = $data['id_ad'];
      }
    }

    if(isset($blocks_ids)) {
      foreach ($blocks_ids as $block_id) {
        $this->derivatives[$block_id] = $base_plugin_definition;
        $this->derivatives[$block_id]['admin_label'] = 'Block:' . $block_id;
      }
    }

    return $this->derivatives;

  }

}
