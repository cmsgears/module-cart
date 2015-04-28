<?php
namespace cmsgears\cart\admin;

// Yii Imports
use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\cart\admin\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-cart/admin/views' );
    }
}

?>