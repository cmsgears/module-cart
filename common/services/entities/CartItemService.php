<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\CartItem;

use cmsgears\cart\common\services\interfaces\entities\ICartService;
use cmsgears\cart\common\services\interfaces\entities\ICartItemService;

class CartItemService extends \cmsgears\core\common\services\base\EntityService implements ICartItemService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\entities\CartItem';

	public static $modelTable	= CartTables::TABLE_CART_ITEM;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $cartService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( ICartService $cartService, $config = [] ) {

		$this->cartService	= $cartService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CartItemService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCartId( $id ) {

		return CartItem::findByCartId( $id );
	}

	public function getByUserId( $userId ) {

		$cart	= $this->cartService->getByUserId( $userId );

		return CartItem::findByCartId( $cart->id );
	}

	public function getByParentCartId( $parentId, $parentType, $cartId ) {

		return CartItem::findByParentCartId( $parentId, $parentType, $cartId );
	}

	public function getParentIdObjectMap( $cart ) {

		return $this->getObjectMap( [ 'key' => 'parentId', 'conditions' => [ 'cartId' => $cart->id ] ] );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$user	= Yii::$app->core->getAppUser();
		$cart	= $config[ 'cart' ];

		$model->cartId		= $cart->id;
		$model->createdBy	= isset( $user ) ? $user->id : null;

		// Create Cart Item
		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes		= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'price', 'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius' ];
		$addAttributes	= isset( $config[ 'addAttributes' ] ) ? $config[ 'addAttributes' ] : [ ];
		$attributes		= ArrayHelper::merge( $attributes, $addAttributes );

		$user				= Yii::$app->core->getAppUser();
		$model->updatedBy	= isset( $user ) ? $user->id : null;

		// Update Cart Item
		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function deleteByCartId( $cartId ) {

		CartItem::deleteByCartId( $cartId );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CartItemService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
