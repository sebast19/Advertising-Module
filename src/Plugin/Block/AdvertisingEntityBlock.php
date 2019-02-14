<?php

namespace Drupal\advertising\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Component\Serialization\PhpSerialize;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Advertising Block' Block.
 *
 * @Block(
 * 	 id = "advertising_block",
 * 	 admin_label = @Translation("Advertising Block"),
 * 	 category = @Translation("Advertising"),
 * 	 deriver = "Drupal\advertising\Plugin\Derivative\AdvertisingEntityBlock"
 *  )
 */
class AdvertisingEntityBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
   * The Entity Type Manager
   *
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity_manager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
      $container->get('serialization.phpserialize'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection, PhpSerialize $serializer, EntityTypeManager $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->connection = $connection;
    $this->serializer = $serializer;
    $this->entity_manager = $entity_manager;
  }
	
	/*
	 *  {@inheritdoc}
	 */
	public function build(){

		$class_name = 'content-div-' . $this->getDerivativeId();

		$build['container'] = [
			'#type' => 'container',
			'#attributes' => [
				'class' => [$class_name],
			],
    ];

    return $build;

	}


	/**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

		return AccessResult::allowedIfHasPermission($account, 'access  content');

	}

	/* *
   * Get names for all taxonomy vocabularies.
   * 
   * @return array array A list of existing vocabulary IDs.
   */
  public function taxonomy_vocabulary_get_names() {
    $names =& drupal_static(__FUNCTION__);
    if (!isset($names)) {
      $names = [];
      $config_names = \Drupal::configFactory()
        ->listAll('taxonomy.vocabulary.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('taxonomy.vocabulary.'));
        $names[$id] = entity_load('taxonomy_vocabulary', $id)->label();
      }
    }
    return $names;
  }

  /**
   * Get names for all content types.
   * 
   * @return array array A list of existing content types IDs.
   */
  public function content_type_get_names() {
    $names =& drupal_static(__FUNCTION__);
    if (!isset($names)) {
      $names = [];
      $config_names = \Drupal::configFactory()
        ->listAll('node_type.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('node_type.'));
        $names[$id] = entity_load('node_type', $id)->label();
        
      }
    }
    return $names;
	}
	
	/**
	 * 
	 */
	public function placeBlocks(){
		$result = $this->connection
      ->query(
        'SELECT data FROM `config` WHERE name LIKE :name ', [':name' => "advertising.advertising_entity.%"]
			)->fetchAssoc();
			
		$data_serilized[] = \Drupal::service('serialization.phpserialize')->decode($result['data']);		
		$places = [];

		foreach ($data_serilized as $blocks) {

			if (array_key_exists($blocks['place'], $this->taxonomy_vocabulary_get_names())) {
				$terms = $this->entity_manager->getStorage('taxonomy_term')->loadTree($blocks['place']);
        foreach ($terms as $term) {
          $places[] = "//taxonomy/term//" . $term->tid;
        }
			}

			if (array_key_exists($blocks['place'], content_type_get_names())) {
        $entities = $this->entity_manager->getStorage('node')->loadByProperties(['type' => $blocks['place']]);
        foreach ($entities as $entity) {
          $places[] = "//node//" . $entity->id();
        }
			}


			$ids[] = \Drupal::entityQuery('block')
				->condition('plugin', 'advertising_block:' . $blocks['id_ad'])
				->execute();

			foreach ($ids[0] as $id) {
				$ids_blocks[] = $id;
			}

			for ($i = 0; $i < count($ids_blocks); $i++) { 
				$block = \Drupal\block\Entity\Block::load($ids_blocks[$i]);
				$visibility = $block->getVisibility();
				
				if (isset($visibility['request_path']['pages'])) {
          for($i = 0; $i < count($places); $i++) {
            $visibility['request_path']['pages'] = $places[$i];
					  $block->setVisibilityConfig('request_path', $visibility['request_path']);
					  $block->save();
          }
				}
			}

    }
    
    ksm($places);

	}

}
