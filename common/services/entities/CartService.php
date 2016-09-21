<?php
namespace cmsgears\cart\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\CartTables;
use cmsgears\cart\common\models\entities\Cart;

use cmsgears\core\common\services\traits\ResourceTrait;
use cmsgears\cart\common\services\interfaces\entities\ICartService;

class CartService extends \cmsgears\core\common\services\base\EntityService implements ICartService {

	public function init() {

		self::$modelClass	= '\cmsgears\cart\common\models\entities\Cart';
	}

	// Static Methods ----------------------------------------------

	use ResourceTrait;

	// Read ----------------

	public function getByToken( $token ) {

		return Cart::findByToken( $token );
	}

	/**
	 * Find cart if exist for the given user
	 */
	public function getByUserId( $userId ) {

		return self::findByParentIdParentType( $userId, CoreGlobal::TYPE_USER );
	}

	/**
	 * Find cart if exist for the given user. If does not exist create, it.
	 */
	public function createByUserId( $userId ) {

		$cart = self::findByParentIdParentType( $userId, CoreGlobal::TYPE_USER );

		if( !isset( $cart ) ) {

			$cart = self::createForUserId( $userId );
		}

		return $cart;
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public function getPagination( $config = [] ) {

		return self::getDataProvider( new Cart(), $config );
	}

	public function getActiveByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::find()->where( 'parentId=:pId AND parentType=:pType AND status='.Cart::STATUS_ACTIVE, [ ':pId' => $parentId, ':pType' => $parentType ] )->one();
	}

	// Create -----------

	public function create( $parentId, $config	= [] ) {

		$cart	= new Cart();

		$cart->parentId		= $parentId;
		$cart->parentType	= $config[ 'parentType' ];
		$cart->createdBy	= isset( $config[ 'userId' ] ) ? $config[ 'userId' ] : null;
		$cart->title		= isset( $config[ 'title' ] ) ? $config[ 'title' ] : null ;
		$cart->status		= Cart::STATUS_ACTIVE;
		$cart->token		= Yii::$app->security->generateRandomString();

		$cart->save();

		return $cart;
	}

	// Update -----------

	public function update( $cart, $config = [] ) {

		$user			= Yii::$app->cmgCore->getAppUser();
		$cartToUpdate	= self::findById( $cart->id );

		$cartToUpdate->modifiedBy	= $user->id;

		$cartToUpdate->copyForUpdateFrom( $order, [ 'status', 'subTotal', 'tax', 'shipping', 'total', 'discount', 'grandTotal' ] );

		// Update Cart
		$cartToUpdate->update();

		// Return Cart
		return $cartToUpdate;
	}

	public function setAbandoned( $existingCart = null ) {

		Cart::updateAll( [ 'status' => Cart::STATUS_ABANDONED ] );

		if( $existingCart != null ) {

			$cart	= self::findById( $existingCart->id );

			if( isset( $cart ) ) {

				$cart->status	= Cart::STATUS_ACTIVE;
				$cart->update();
			}
		}

		return true;
	}

	public function updateStatus( $cart, $status ) {

		$cart->status	= $status;

		$cart->update();

		return $cart;
	}

	// Delete -----------

	public function delete( $cart, $config = [] ) {

		$cartToDelete	= self::findById( $cart->id );

		if( isset( $cartToDelete ) ) {

		  $cartToDelete->delete();

		  return true;
		}
	}

	// Item Management

	public function addItemToCart( $cart, $cartItem, $additionalParams = [] ) {

		$user				= Yii::$app->cmgCore->getAppUser();
		$cartItem->cartId	= $cart->id;

		// remove if exist
		if( $cartItem->id > 0 && !$cartItem->addToCart ) {

			CartItemService::delete( $cartItem );
		}

		if( $cartItem->addToCart ) {

			// create
			if( $cartItem->id <= 0 ) {

				$cartItem->setScenario( "create" );

				return CartItemService::create( $cartItem );
			}
			// update
			else {

				$cartItem->setScenario( "update" );

				return CartItemService::update( $cartItem, $additionalParams );
			}
		}

		return null;
	}
}
