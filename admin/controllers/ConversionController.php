<?php
namespace cmsgears\cart\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\resources\UomConversion;

class ConversionController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CartGlobal::PERM_CART;

		$this->viewPath			= '@cmsgears/module-cart/admin/views/conversion';

		$this->modelService		= Yii::$app->factory->get( 'uomConversionService' );

		$this->sidebar			= [ 'parent' => 'sidebar-cart', 'child' => 'uom-con' ];

		$this->returnUrl		= Url::previous( 'uomcons' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/cart/conversion/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ConversionController ------------------

	public function actionAll() {

		Url::remember( [ 'conversion/all' ], 'uoms' );

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}
}
