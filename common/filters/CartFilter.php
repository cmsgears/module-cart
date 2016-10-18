<?php
namespace cmsgears\cart\common\filters;

// Yii Imports
use Yii;
use yii\web\ForbiddenHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartProperties;

class CartFilter {

	public function doFilter( $args = [] ) {

		$this->cartProperties = CartProperties::getInstance();

		// Check whether cart is still active and available
		if( $this->cartProperties->isActive() ) {

			return true;
		}

		// Stop action in case cart is inactive
		throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
	}
}
