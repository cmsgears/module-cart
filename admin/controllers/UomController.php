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

use cmsgears\cart\common\models\resources\Uom;

class UomController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CartGlobal::PERM_CART_ADMIN;

		$this->viewPath			= '@cmsgears/module-cart/admin/views/uom';

		$this->modelService		= Yii::$app->factory->get( 'uomService' );

		$this->sidebar			= [ 'parent' => 'sidebar-cart', 'child' => 'uom' ];

		$this->returnUrl		= Url::previous( 'uoms' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/cart/uom/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UomController -------------------------

	public function actionAll( $config = [] ) {

		Url::remember( [ 'uom/all' ], 'uoms' );

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}
}
