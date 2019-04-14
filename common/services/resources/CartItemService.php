<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\resources;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\cart\common\services\interfaces\resources\ICartItemService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * CartItemService provide service methods of cart item model.
 *
 * @since 1.0.0
 */
class CartItemService extends ModelResourceService implements ICartItemService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\CartItem';

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

		$modelClass	= static::$modelClass;

		return $modelClass::findByCartId( $cartId );
	}

	public function getByParentCartId( $parentId, $parentType, $cartId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentCartId( $parentId, $parentType, $cartId );
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

		$modelClass	= static::$modelClass;

		$modelClass::deleteByCartId( $cartId );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
