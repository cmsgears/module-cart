<?php
namespace cmsgears\cart\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

class CartProperties extends \cmsgears\core\common\config\CmgProperties {

    // Variables ---------------------------------------------------

    // Global -----------------

    /**
     * The property to find whether cart is active for site.
     */
    const PROP_ACTIVE		= 'active';

    // Public -----------------

    // Protected --------------

    // Private ----------------

    private static $instance;

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // CartProperties ------------------------

    // Singleton

    public static function getInstance() {

        if( !isset( self::$instance ) ) {

            self::$instance	= new CartProperties();

            self::$instance->init( CartGlobal::CONFIG_CART );
        }

        return self::$instance;
    }

    // Properties

    /**
     * Returns whether cart is active for site.
     */
    public function isActive() {

        return $this->properties[ self::PROP_ACTIVE ];
    }
}
