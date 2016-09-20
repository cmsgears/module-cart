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

		// Use service provided exclusively for the filter
		if( $this->cartProperties->isActive() ) {

			return true;
		}

		// Halt action execution in case user is not a merchant
		throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
	}
}
