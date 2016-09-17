<?php
namespace cmsgears\cart\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\entities\CartTables;
use cmsgears\cart\common\models\entities\Cart;

class CartService extends \cmsgears\core\common\services\base\Service {

    // Static Methods ----------------------------------------------

    // Read ----------------

    public static function findById( $id ) {

        return Cart::findById( $id );
    }

    public static function findByToken( $token ) {

        return Cart::findByToken( $token );
    }

    /**
     * Find cart if exist for the given user
     */
    public static function findByUserId( $userId ) {

        return self::findByParentIdParentType( $userId, CoreGlobal::TYPE_USER );
    }

    /**
     * Find cart if exist for the given user. If does not exist create, it.
     */
    public static function findAndCreateByUserId( $userId ) {


        $cart = self::findByParentIdParentType( $userId, CoreGlobal::TYPE_USER );

        if( !isset( $cart ) ) {

            $cart = self::createForUserId( $userId );
        }

        return $cart;
    }

    public static function findByParentIdParentType( $parentId, $parentType ) {

        return Cart::findByParentIdParentType( $parentId, $parentType );
    }

    // Data Provider ------

    /**
     * @param array $config to generate query
     * @return ActiveDataProvider
     */
    public static function getPagination( $config = [] ) {

        return self::getDataProvider( new Cart(), $config );
    }

    // Create -----------

    public static function create( $parentId, $parentType, $user = null, $name = null ) {

        $cart   = new Cart();

        $cart->parentId     = $parentId;
        $cart->parentType   = $parentType;
        $cart->name         = $name;
        $cart->status       = Cart::STATUS_ACTIVE;
        $cart->token        = Yii::$app->security->generateRandomString();
        $cart->save();

        return $cart;
    }

    public static function createForUserId( $userId ) {

        // Set Attributes
        $user				= Yii::$app->cmgCore->getAppUser();
        $cart				= new Cart();

        $cart->createdBy	= $user->id;
        $cart->parentId		= $userId;
        $cart->parentType	= CoreGlobal::TYPE_USER;

        $cart->generateName();

        $cart->save();

        // Return Cart
        return $cart;
    }

    // Update -----------

    public static function update( $cart ) {

        $user			= Yii::$app->cmgCore->getAppUser();
        $cartToUpdate	= self::findById( $cart->id );

        $cartToUpdate->modifiedBy	= $user->id;

        $cartToUpdate->copyForUpdateFrom( $order, [ 'status', 'subTotal', 'tax', 'shipping', 'total', 'discount', 'grandTotal' ] );

        // Update Cart
        $cartToUpdate->update();

        // Return Cart
        return $cartToUpdate;
    }

    public static function setAbandoned( $existingCart = null ) {

        Cart::updateAll( [ 'status' => Cart::STATUS_ABANDONED ] );

        if( $existingCart != null ) {

            $cart   = self::findById( $existingCart->id );

            if( isset( $cart ) ) {

                $cart->status   = Cart::STATUS_ACTIVE;
                $cart->update();
            }
        }

        return true;
    }

    public static function updateStatus( $cart, $status ) {

        $cart->status   = $status;
        $cart->update();

        return $cart;
    }

    // Delete -----------

    public static function delete( $cart ) {

        $cartToDelete	= self::findById( $cart->id );

        if( isset( $cartToDelete ) ) {

          $cartToDelete->delete();

          return true;
        }
    }

    // Item Management

    public static function addItemToCart( $cart, $cartItem, $additionalParams = [] ) {

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

?>