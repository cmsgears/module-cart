<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use \Yii;
use yii\di\Container;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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

		// Init services
		$this->initResourceServices();
		$this->initEntityServices();
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IUomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\resources\IGuestService', 'cmsgears\cart\common\services\resources\GuestService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IOrderService', 'cmsgears\cart\common\services\entities\OrderService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\IOrderItemService', 'cmsgears\cart\common\services\entities\OrderItemService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ICartService', 'cmsgears\cart\common\services\entities\CartService' );
		$factory->set( 'cmsgears\cart\common\services\interfaces\entities\ICartItemService', 'cmsgears\cart\common\services\entities\CartItemService' );
	}

	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'uomService', 'cmsgears\cart\common\services\resources\UomService' );
		$factory->set( 'cartGuestService', 'cmsgears\cart\common\services\resources\GuestService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'orderService', 'cmsgears\cart\common\services\entities\OrderService' );
		$factory->set( 'orderItemService', 'cmsgears\cart\common\services\entities\OrderItemService' );
		$factory->set( 'cartService', 'cmsgears\cart\common\services\entities\CartService' );
		$factory->set( 'cartItemService', 'cmsgears\cart\common\services\entities\CartItemService' );

		// Resolve additional dependencies
		/*$orderItemService	= $factory->get( 'orderItemService' );
		$factory->get( 'orderService' )->setOrderItemService( $orderItemService );*/
	}
}
