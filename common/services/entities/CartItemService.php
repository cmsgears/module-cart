<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use \Yii;

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

	public function getByUserId( $userId ) {

		$cart	= $this->cartService->createByUserId( $userId );

		return self::findByCartId( $cart->id );
	}

	public function getByCartId( $id ) {

		return CartItem::findByCartId( $id );
	}

	public function getByParentCartId( $parentId, $parentType, $cartId ) {

		return CartItem::findByParentCartId( $parentId, $parentType, $cartId );
	}

	public function getObjectMapByUserId( $userId ) {

		$cart	= $this->cartService->createByUserId( $userId );

		return $this->getObjectMap( [ 'key' => 'parentId', 'conditions' => [ 'cartId' => $cart->id ] ] );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $cart, $config = [] ) {

		$cartItem	= new CartItem();
		$model		= $config[ 'model' ];

		$cartItem->cartId		= $cart->id;
		$cartItem->quantity		= $model[ 'quantity' ];
		$cartItem->createdBy	= $cart->createdBy;
		$cartItem->name			= $model[ 'name' ];
		$cartItem->price		= $model[ 'price' ];
		$cartItem->parentId		= $model[ 'parentId' ];
		$cartItem->parentType	= $model[ 'parentType' ];
		$cartItem->save();

		return $cartItem;
	}

	// Update -------------

	public function update( $cartItem, $config = [] ) {

		$user				= Yii::$app->cmgCore->getAppUser();
		$cartItemToUpdate	= self::findById( $cartItem->id );

		if( $user != null ) {

		  $cartItemToUpdate->modifiedBy	= $user->id;
		}

		// Copy required params
		$cartItemToUpdate->copyForUpdateFrom( $cartItem, [ 'quantityUnitId', 'weightUnitId', 'metricUnitId', 'price', 'quantity', 'weight', 'length', 'width', 'height' ] );

		// Copy Additional Params
		$cartItemToUpdate->copyForUpdateFrom( $cartItem, $config );

		// Update CartItem
		$cartItemToUpdate->update();

		// Return CartItem
		return $cartItemToUpdate;
	}

	// Delete -------------

	public function delete( $cartItem, $config = [] ) {

		$cartItemToDelete	= self::findById( $cartItem->id );

		// Delete CartItem
		$cartItemToDelete->delete();

		// Return true
		return true;
	}

	public static function deleteByCartId( $cartId ) {

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
