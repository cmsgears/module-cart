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

/**
 * Transaction represents a financial transaction.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $orderId
 * @property integer $invoiceId
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
 *
 * @since 1.0.0
 */
class Transaction extends \cmsgears\payment\common\models\resources\Transaction {

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

		$rules = parent::rules();

		$rules[] = [ [ 'orderId', 'invoiceId' ], 'number', 'integerOnly' => true, 'min' => 1 ];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		$labels	= parent::attributeLabels();

		$labels[ 'orderId' ] = Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ORDER );
		$labels[ 'invoiceId' ] = Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_INVOICE );

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

	/**
	 * Return the corresponding invoice.
	 *
	 * @return \cmsgears\cart\common\models\entities\Invoice
	 */
	public function getInvoice() {

		return $this->hasOne( Invoice::class, [ 'id' => 'invoiceId' ] );
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user', 'order', 'invoice' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryByOrderId( $orderId ) {

		return static::find()->where( 'orderId=:oid', [ ':oid' => $orderId ] );
	}

	public static function queryByInvoiceId( $invoiceId ) {

		return static::find()->where( 'invoiceId=:iid', [ ':iid' => $invoiceId ] );
	}

	// Read - Find ------------

	public static function findByOrderId( $orderId ) {

		return self::queryByOrderId( $orderId )->all();
	}

	public static function findFirstByOrderId( $orderId ) {

		return self::queryByOrderId( $orderId )->one();
	}

	public static function findByInvoiceId( $invoiceId ) {

		return self::queryByInvoiceId( $invoiceId )->all();
	}

	public static function findFirstInvoiceId( $invoiceId ) {

		return self::queryByInvoiceId( $invoiceId )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
