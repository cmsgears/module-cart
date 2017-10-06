<?php
namespace cmsgears\cart\frontend;

// CMG Imports

class Module extends \cmsgears\core\common\base\Module {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $controllerNamespace = 'cmsgears\cart\frontend\controllers';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-cart/frontend/views' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

}
