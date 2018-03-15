<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\models\resources;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\payment\common\models\resources\Transaction as BaseTransaction;

/**
 * Transaction represents a financial transaction.
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $description
 * @property integer $type
 * @property integer $mode
 * @property boolean $refund
 * @property string $code
 * @property string $service
 * @property integer $status
 * @property float $amount
 * @property string $currency
 * @property string $link
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property date $processedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 */
class Transaction extends BaseTransaction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		$rules		= parent::rules();

		$rules[]	= [ 'orderId', 'number', 'integerOnly' => true, 'min' => 1 ];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		$labels	= parent::attributeLabels();

		$labels[ 'orderId' ] = Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ORDER );

		return $labels;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Transaction ---------------------------

	/**
	 * Return the corresponding order.
	 *
	 * @return \cmsgears\cart\common\models\entities\Order
	 */
	public function getOrder() {

		return $this->hasOne( Order::class, [ 'id' => 'orderId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// Transaction ---------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'order' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
