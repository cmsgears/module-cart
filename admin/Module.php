<?php
namespace cmsgears\cart\admin;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

class Module extends \cmsgears\core\common\base\Module {

	public $controllerNamespace = 'cmsgears\cart\admin\controllers';

	public $config				= [ CartGlobal::CONFIG_CART ];

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/cart/admin/views' );
	}

	public function getSidebarHtml() {

		$path	= Yii::getAlias( '@cmsgears' ) . "/cart/admin/views/sidebar.php";

		return $path;
	}
}
