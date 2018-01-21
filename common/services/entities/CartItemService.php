<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\CartItem;

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

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CartItemService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCartId( $cartId ) {

		return CartItem::findByCartId( $cartId );
	}

	public function getByParentCartId( $parentId, $parentType, $cartId ) {

		return CartItem::findByParentCartId( $parentId, $parentType, $cartId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$cart = isset( $config[ 'cart' ] ) ? $config[ 'cart' ] : null;

		if( empty( $model->cartId ) && isset( $cart ) ) {

			$model->cartId = $cart->id;
		}

		// Create Cart Item
		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes		= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId', 'type', 'price', 'discount', 'total', 'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius', 'keep' ];
		$addAttributes	= isset( $config[ 'addAttributes' ] ) ? $config[ 'addAttributes' ] : [ ];
		$attributes		= ArrayHelper::merge( $attributes, $addAttributes );

		$user				= Yii::$app->user->getIdentity();
		$model->modifiedBy	= isset( $user ) ? $user->id : null;

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
