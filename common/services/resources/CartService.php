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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\resources\Cart;

use cmsgears\cart\common\services\interfaces\resources\ICartService;
use cmsgears\cart\common\services\interfaces\resources\ICartItemService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * CartService provide service methods of cart model.
 *
 * @since 1.0.0
 */
class CartService extends ModelResourceService implements ICartService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\cart\common\models\entities\Cart';

	public static $parentType	= CartGlobal::TYPE_CART;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $cartItemService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function setCartItemService( ICartItemService $cartItemService ) {

		$this->cartItemService	= $cartItemService;
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CartService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByToken( $token ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByToken( $token );
	}

	public function getByModelToken( $model, $type ) {

		$modelClass	= self::$modelClass;

		$data = Yii::$app->order->getCartToken( $model, $type );

		if( isset( $data ) ) {

			$cart = $modelClass::findByToken( $data[ 'token' ] );

			if( empty( $cart ) ) {

				$cart = $this->createByParams([
					'parentId' => $model->id, 'parentType' => $type,
					'title' => $model->name,
					'token' => $data[ 'token' ]
				]);
			}

			return $cart;
		}

		return null;
	}

	/**
	 * Find cart if exist for the given user. If does not exist create, it.
	 */
	public function getByUserId( $userId ) {

		$modelClass	= self::$modelClass;

		$cart = $modelClass::findByParentIdParentType( $userId, CoreGlobal::TYPE_USER );

		if( !isset( $cart ) ) {

			$cart = $this->createByUserId( $userId );
		}

		return $cart;
	}

	public function getByParent( $parentId, $parentType, $first = true ) {

		$modelClass	= static::$modelClass;

		if( $first ) {

			return $modelClass::find()->where( "parentId=:pId AND parentType=:pType", [ ':pId' => $parentId, ':pType' => $parentType ] )->one();
		}

		return $modelClass::find()->where( "parentId=:pId AND parentType=:pType", [ ':pId' => $parentId, ':pType' => $parentType ] )->all();
	}

	public function getActiveByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		$active = Cart::STATUS_ACTIVE;

		return $modelClass::find()->where( "parentId=:pId AND parentType=:pType AND status=$active", [ ':pId' => $parentId, ':pType' => $parentType ] )->one();
	}

	public function getByType( $parentId, $parentType, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()->where( "parentId=:pId AND parentType=:pType AND type=:type", [ ':pId' => $parentId, ':pType' => $parentType, ':type' => $type ] )->one();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByParams( $params = [], $config = [] ) {

		$parentId	= isset( $params[ 'parentId' ] ) ? $params[ 'parentId' ] : null;
		$parentType	= isset( $params[ 'parentType' ] ) ? $params[ 'parentType' ] : null;

		$title	= isset( $params[ 'title' ] ) ? $params[ 'title' ] : null;
		$token	= isset( $params[ 'token' ] ) ? $params[ 'token' ] : Yii::$app->security->generateRandomString();
		$type	= isset( $params[ 'type' ] ) ? $params[ 'type' ] : null;

		$user = Yii::$app->user->getIdentity();

		$cart = $this->getModelObject();

		$cart->title = $title;

		if( empty( $cart->title ) ) {

			$cart->generateName();
		}

		$cart->createdBy	= isset( $user ) ? $user->id : null;
		$cart->parentId		= $parentId;
		$cart->parentType	= $parentType;
		$cart->type			= $type;
		$cart->status		= Cart::STATUS_ACTIVE;
		$cart->token		= $token;

		$cart->save();

		return $cart;
	}

	public function createByUserId( $userId, $config = [] ) {

		// Set Attributes
		$user	= Yii::$app->core->getAppUser();
		$cart	= $this->getModelObject();

		$cart->createdBy	= $user->id;
		$cart->parentId		= $userId;
		$cart->parentType	= CoreGlobal::TYPE_USER;

		$cart->title = isset( $config[ 'title' ] ) ? $config[ 'title' ] : "Generic Order";

		$cart->save();

		// Return Cart
		return $cart;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'type', 'title', 'token', 'status' ];

		$user = Yii::$app->core->getAppUser();

		$model->modifiedBy = $user->id;

		// Update Cart
		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateStatus( $model, $status, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'status' ];

		$model->status = $status;

		// Update Cart
		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function setAbandoned( $model, $config = [] ) {

		return $this->updateStatus( $model, Cart::STATUS_ABANDONED );
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete items
		Yii::$app->factory->get( 'cartItemService' )->deleteByCartId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Items --------------

	public function addItem( $model, $item, $config = [] ) {

		$cartItemService = Yii::$app->factory->get( 'cartItemService' );

		$item->cartId = $model->id;

		// Remove in case it's not required
		if( isset( $item->id ) && $item->id > 0 && !$item->keep ) {

			$cartItemService->delete( $item );
		}
		else if( $item->keep ) {

			// Create
			if( !isset( $item->id ) || $item->id <= 0 ) {

				return $cartItemService->create( $item );
			}
			// update
			else {

				return $cartItemService->update( $item, $config );
			}
		}

		return false;
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CartService ---------------------------

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
