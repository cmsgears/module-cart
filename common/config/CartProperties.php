<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\config;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\config\Properties;

/**
 * CartProperties provide methods to access the properties specific to cart and orders.
 *
 * @since 1.0.0
 */
class CartProperties extends Properties {

	// Variables ---------------------------------------------------

	// Global -----------------

	/**
	 * The property to find whether cart is active for site.
	 */
	const PROP_ACTIVE = 'active';

	/**
	 * The property to check whether cart has to be deleted after converted to order.
	 */
	const PROP_CART_REMOVE = 'remove_cart';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new CartProperties();

			self::$instance->init( CartGlobal::CONFIG_CART );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CartProperties ------------------------

	/**
	 * Returns whether cart is active for site.
	 */
	public function isActive() {

		return $this->properties[ self::PROP_ACTIVE ];
	}

	/**
	 * Returns whether cart can be removed after converted to order.
	 */
	public function isRemoveCart() {

		return $this->properties[ self::PROP_CART_REMOVE ];
	}

}
