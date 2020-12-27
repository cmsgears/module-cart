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

/**
 * The Cart Factory component initialize the services available in Cart Module.
 *
 * @since 1.0.0
 */
class Factory extends \cmsgears\core\common\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerResourceServices();
		$this->registerSystemServices();
	}

	public function registerServiceAlias() {

		$this->registerResourceAliases();
		$this->registerSystemAliases();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomConversionService', 'cmsgears\cart\common\services\resources\UomConversionService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IVoucherService', 'cmsgears\cart\common\services\resources\VoucherService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\ITransactionService', 'cmsgears\cart\common\services\resources\TransactionService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\ICartItemService', 'cmsgears\cart\common\services\resources\CartItemService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\ICartService', 'cmsgears\cart\common\services\resources\CartService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IOrderItemService', 'cmsgears\cart\common\services\resources\OrderItemService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IOrderService', 'cmsgears\cart\common\services\resources\OrderService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IInvoiceItemService', 'cmsgears\cart\common\services\resources\InvoiceItemService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IInvoiceService', 'cmsgears\cart\common\services\resources\InvoiceService' );
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
	 * Registers resource aliases.
	 */
	public function registerResourceAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'uomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'uomConversionService', 'cmsgears\cart\common\services\resources\UomConversionService' );

		$factory->set( 'voucherService', 'cmsgears\cart\common\services\resources\VoucherService' );

		$factory->set( 'transactionService', 'cmsgears\cart\common\services\resources\TransactionService' );

		$factory->set( 'cartItemService', 'cmsgears\cart\common\services\resources\CartItemService' );
		$factory->set( 'cartService', 'cmsgears\cart\common\services\resources\CartService' );

		$factory->set( 'orderItemService', 'cmsgears\cart\common\services\resources\OrderItemService' );
		$factory->set( 'orderService', 'cmsgears\cart\common\services\resources\OrderService' );

		$factory->set( 'invoiceItemService', 'cmsgears\cart\common\services\resources\InvoiceItemService' );
		$factory->set( 'invoiceService', 'cmsgears\cart\common\services\resources\InvoiceService' );
	}

	/**
	 * Registers system aliases.
	 */
	public function registerSystemAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cartGuestService', 'cmsgears\cart\common\services\system\GuestService' );

		$factory->set( 'salesService', 'cmsgears\cart\common\services\system\SalesService' );
	}

}
