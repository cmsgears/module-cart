<?php
namespace cmsgears\cart\frontend;

// Yii Imports
use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\cart\frontend\controllers';

	private $mailer;

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/cart/frontend/views' );

	}  
}

?>