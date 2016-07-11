<?php
namespace cmsgears\cart\common\components;

// Yii Imports
use \Yii;
use yii\di\Container;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Cart extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

    /**
     * Initialise the CMG Core Component.
     */
    public function init() {

        parent::init();

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cart ----------------------------------

	// Properties

	// Components and Objects

	public function registerComponents() {

		// Init system services
		$this->initSystemServices();

		// Register services
		$this->registerResourceServices();
		$this->registerMapperServices();
		$this->registerEntityServices();

		// Init services
		$this->initResourceServices();
		$this->initMapperServices();
		$this->initEntityServices();
	}

	public function initSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( '<name>', '<classpath>' );
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( 'cmsgears\cms\common\services\interfaces\resources\ICategoryService', 'cmsgears\cms\common\services\resources\CategoryService' );
	}

	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( 'cmsgears\cms\common\services\interfaces\mappers\IModelBlockService', 'cmsgears\cms\common\services\mappers\ModelBlockService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( 'cmsgears\cms\common\services\interfaces\entities\IElementService', 'cmsgears\cms\common\services\entities\ElementService' );
	}

	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( 'categoryService', 'cmsgears\cms\common\services\resources\CategoryService' );
	}

	public function initMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( 'modelBlockService', 'cmsgears\cms\common\services\mappers\ModelBlockService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( 'elementService', 'cmsgears\cms\common\services\entities\ElementService' );
	}
}
