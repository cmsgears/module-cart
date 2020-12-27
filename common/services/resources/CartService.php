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
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\resources\Cart;

use cmsgears\cart\common\services\interfaces\resources\ICartService;
use cmsgears\cart\common\services\interfaces\resources\ICartItemService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;

/**
 * CartService provide service methods of cart model.
 *
 * @since 1.0.0
 */
class CartService extends \cmsgears\core\common\services\base\ModelResourceService implements ICartService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\Cart';

	public static $parentType = CartGlobal::TYPE_CART;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $cartItemService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultiSiteTrait;

	// Constructor and Initialisation ------------------------------

	public function setCartItemService( ICartItemService $cartItemService ) {

		$this->cartItemService = $cartItemService;
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

	/**
	 * @inheritdoc
	 */
	public function getByToken( $token ) {

		$modelClass	= self::$modelClass;

		$cart = $modelClass::findByToken( $token );

		if( !isset( $cart ) ) {

			$cart = $this->createByParams( [ 'token' => $token ] );
		}

		return $cart;
	}

	/**
	 * @inheritdoc
	 */
	public function getByUserId( $userId ) {

		$modelClass	= self::$modelClass;

		$cart = $modelClass::findActiveByUserId( $userId );

		if( !isset( $cart ) ) {

			$cart = $this->createByParams([
				'userId' => $userId, 'title' => 'Generic Order',
				'parentId' => $userId, 'parentType' => CoreGlobal::TYPE_USER
			]);
		}

		return $cart;
	}

	public function getByUserIdParent( $userId, $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		$cart = $modelClass::findActiveByParentUserId( $parentId, $parentType, $userId );

		if( !isset( $cart ) ) {

			$cart = $this->createByParams([
				'userId' => $userId, 'title' => 'Generic Order',
				'parentId' => $parentId, 'parentType' => $parentType
			]);
		}

		return $cart;
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

		$user = Yii::$app->core->getUser();

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

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'title', 'guest', 'firstName', 'lastName',
			'email', 'mobile', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status' ] );
		}

		// Update Cart
		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		// Update Cart
		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function setAbandoned( $model ) {

		return $this->updateStatus( $model, Cart::STATUS_ABANDONED );
	}

	public function setSuccess( $model ) {

		return $this->updateStatus( $model, Cart::STATUS_SUCCESS );
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
			// Update
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
