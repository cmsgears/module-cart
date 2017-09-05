<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use Yii;

class Cart extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialise the CMG Core Component.
	 */
	public function init() {

		parent::init();

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cart ----------------------------------

	// Properties

	// Components and Objects

	public function registerComponents() {

		// Register services
		$this->registerResourceServices();
		$this->registerEntityServices();
		$this->registerSystemServices();

		// Init services
		$this->initResourceServices();
		$this->initEntityServices();
		$this->initSystemServices();
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomConversionService', 'cmsgears\cart\common\services\resources\UomConversionService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\forms\IGuestService', 'cmsgears\cart\common\services\forms\GuestService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ICartService', 'cmsgears\cart\common\services\entities\CartService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ICartItemService', 'cmsgears\cart\common\services\entities\CartItemService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IOrderService', 'cmsgears\cart\common\services\entities\OrderService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IOrderItemService', 'cmsgears\cart\common\services\entities\OrderItemService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IVoucherService', 'cmsgears\cart\common\services\entities\VoucherService' );

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ITransactionService', 'cmsgears\cart\common\services\entities\TransactionService' );
	}

	public function registerSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\system\ISalesService', 'cmsgears\cart\common\services\system\SalesService' );
	}

	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'uomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'uomConversionService', 'cmsgears\cart\common\services\resources\UomConversionService' );

		$factory->set( 'cartGuestService', 'cmsgears\cart\common\services\forms\GuestService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cartService', 'cmsgears\cart\common\services\entities\CartService' );
		$factory->set( 'cartItemService', 'cmsgears\cart\common\services\entities\CartItemService' );

		$factory->set( 'orderService', 'cmsgears\cart\common\services\entities\OrderService' );
		$factory->set( 'orderItemService', 'cmsgears\cart\common\services\entities\OrderItemService' );

		$factory->set( 'voucherService', 'cmsgears\cart\common\services\entities\VoucherService' );

		$factory->set( 'transactionService', 'cmsgears\cart\common\services\entities\TransactionService' );
	}

	public function initSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'salesService', 'cmsgears\cart\common\services\system\SalesService' );
	}
}
