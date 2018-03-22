<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\components;

// Yii Imports
use Yii;
use yii\base\Component;

/**
 * Cart component register the services provided by Cart Module.
 *
 * @since 1.0.0
 */
class Cart extends Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialize the services.
	 */
	public function init() {

		parent::init();

		// Register components and objects
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cart ----------------------------------

	// Properties ----------------

	// Components and Objects ----

	/**
	 * Register the services.
	 */
	public function registerComponents() {

		// Register services
		$this->registerResourceServices();
		$this->registerSystemServices();

		// Init services
		$this->initResourceServices();
		$this->initSystemServices();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomConversionService', 'cmsgears\cart\common\services\resources\UomConversionService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IVoucherService', 'cmsgears\cart\common\services\entities\VoucherService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ITransactionService', 'cmsgears\cart\common\services\entities\TransactionService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ICartItemService', 'cmsgears\cart\common\services\entities\CartItemService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ICartService', 'cmsgears\cart\common\services\entities\CartService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IOrderItemService', 'cmsgears\cart\common\services\entities\OrderItemService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IOrderService', 'cmsgears\cart\common\services\entities\OrderService' );
	}

	/**
	 * Registers system services.
	 */
	public function registerSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\system\IGuestService', 'cmsgears\cart\common\services\system\GuestService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\system\ISalesService', 'cmsgears\cart\common\services\system\SalesService' );
	}

	/**
	 * Initialize resource services.
	 */
	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'uomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'uomConversionService', 'cmsgears\cart\common\services\resources\UomConversionService' );

		$factory->set( 'voucherService', 'cmsgears\cart\common\services\entities\VoucherService' );

		$factory->set( 'transactionService', 'cmsgears\cart\common\services\entities\TransactionService' );

		$factory->set( 'cartItemService', 'cmsgears\cart\common\services\entities\CartItemService' );
		$factory->set( 'cartService', 'cmsgears\cart\common\services\entities\CartService' );

		$factory->set( 'orderItemService', 'cmsgears\cart\common\services\entities\OrderItemService' );
		$factory->set( 'orderService', 'cmsgears\cart\common\services\entities\OrderService' );
	}

	/**
	 * Initialize system services.
	 */
	public function initSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cartGuestService', 'cmsgears\cart\common\services\system\GuestService' );

		$factory->set( 'salesService', 'cmsgears\cart\common\services\system\SalesService' );
	}

}
