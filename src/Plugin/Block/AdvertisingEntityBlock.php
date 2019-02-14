<?php

namespace Drupal\advertising\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

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
class AdvertisingEntityBlock extends BlockBase  {

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

}
