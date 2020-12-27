<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

class ConversionController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $uomService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// View Path
		$this->viewPath = '@cmsgears/module-cart/admin/views/conversion';

		// Permission
		$this->crudPermission = CartGlobal::PERM_CART_ADMIN;

		// Config
		$this->apixBase = 'cart/uom';

		// Services
		$this->modelService = Yii::$app->factory->get( 'uomConversionService' );

		$this->uomService = Yii::$app->factory->get( 'uomService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-cart', 'child' => 'conversion' ];

		// Return Url
		$this->returnUrl = Url::previous( 'uom-conversions' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/cart/conversion/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'UOM Conversions' ] ],
			'create' => [ [ 'label' => 'UOM Conversions', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'UOM Conversions', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'UOM Conversions', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ConversionController ------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'uom-conversions' );

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
		}

		$conMap = $this->uomService->getMapForConversion();

		return $this->render( 'create', [
			'model' => $model,
			'conMap' => $conMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			$conMap = $this->uomService->getMapForConversion();

			// Render view
			return $this->render( 'update', [
				'model' => $model,
				'conMap' => $conMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
