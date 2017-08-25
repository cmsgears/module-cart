<?php
namespace cmsgears\cart\common\models\resources;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\traits\resources\DataTrait;

use cmsgears\cart\common\models\base\CartTables;
use cmsgears\cart\common\models\entities\Order;

/**
 * OrderHistory Entity - The primary class.
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $createdBy
 * @property string $type
 * @property string $message
 * @property datetime $createdAt
 * @property string $content
 * @property string $data
 */
class OrderHistory extends \cmsgears\core\common\models\base\Entity {

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

	use DataTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

		return [
			// Required, Safe
			[ [ 'orderId' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'message', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'orderId', 'createdBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];
	}

	public function attributeLabels() {

		return [
			'orderId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_ORDER ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// OrderHistory --------------------------

	public function getOrder() {

		return $this->hasOne( Order::className(), [ 'id' => 'orderId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_ORDER_HISTORY;
	}

	// CMG parent classes --------------------

	// OrderHistory --------------------------

	// Read - Query -----------

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
