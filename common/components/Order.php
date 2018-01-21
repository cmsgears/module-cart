<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use Yii;

class Order extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	const COOKIE_CART	= 'cart'; // Cart Token

	const COOKIE_GUEST	= 'cart_guest'; // Cart Guest

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Order ---------------------------------

	public function setCartToken( $model, $type ) {

		// Cookie not set or token is set for a different business
		if( !isset( $_COOKIE[ self::COOKIE_CART ] ) ) {

			$data = [ 'slug' => $model->slug, 'type' => $type, 'token' => Yii::$app->security->generateRandomString() ];

			$this->setCookie( self::COOKIE_CART, json_encode( $data ) );
		}
		else {

			$data = json_decode( $_COOKIE[ self::COOKIE_CART ], true );

			if( $type !== $data[ 'type' ] || $model->slug !== $data[ 'slug' ] ) {

				$data = [ 'slug' => $model->slug, 'type' => $type, 'token' => Yii::$app->security->generateRandomString() ];

				$this->setCookie( self::COOKIE_CART, json_encode( $data ) );
			}
		}
	}

	public function getCartToken( $model, $type ) {

		if( isset( $_COOKIE[ self::COOKIE_CART ] ) ) {

			$data	= json_decode( $_COOKIE[ self::COOKIE_CART ], true );

			$token	= $data[ 'slug' ] == $model->slug && $type == $data[ 'type' ] ? $data : null;

			if( isset( $token ) ) {

				return $token;
			}
			else {

				$this->setCartToken( $model, $type );

				$data	= json_decode( $_COOKIE[ self::COOKIE_CART ], true );

				return $data;
			}
		}

		return null;
	}

	public function getCartTokenValue( $model, $type ) {

		if( isset( $_COOKIE[ self::COOKIE_CART ] ) ) {

			$data	= json_decode( $_COOKIE[ self::COOKIE_CART ], true );

			$token	= $data[ 'slug' ] == $model->slug && $type == $data[ 'type' ] ? $data[ 'token' ] : null;

			if( isset( $token ) ) {

				return $token;
			}
			else {

				$this->setCartToken( $model, $type );

				$data	= json_decode( $_COOKIE[ self::COOKIE_CART ], true );

				return $data[ 'token' ];
			}
		}

		return null;
	}

	public function removeCartToken() {

		if( isset( $_COOKIE[ self::COOKIE_CART ] ) ) {

			$this->setcookie( self::COOKIE_CART, "", -( 60 * 24 * 30 ) );
		}
	}

	public function setGuest( $guest ) {

		// Cookie not set or token is set for a different business
		if( !isset( $_COOKIE[ self::COOKIE_GUEST ] ) ) {

			$data = [ 'firstName' => $guest->firstName, 'lastName' => $guest->lastName, 'email' => $guest->email, 'phone' => $guest->phone ];

			$this->setCookie( self::COOKIE_GUEST, json_encode( $data ) );
		}
	}

	public function getGuest() {

		if( isset( $_COOKIE[ self::COOKIE_GUEST ] ) ) {

			$data = json_decode( $_COOKIE[ self::COOKIE_GUEST ], true );

			return $data;
		}

		return null;
	}

	public function removeGuest() {

		if( isset( $_COOKIE[ self::COOKIE_GUEST ] ) ) {

			$this->setcookie( self::COOKIE_GUEST, "", -( 60 * 24 * 30 ) );
		}
	}

	// TODO: Change reservation mins to 30
	protected function setCookie( $key, $value, $mins = 90 ) {

		return setcookie( $key, $value, time() + ( $mins * 60 ), "/", null );
	}
}
