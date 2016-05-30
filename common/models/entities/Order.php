<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\base\CmgEntity;
use cmsgears\payment\common\models\entities\Payment;

use cmsgears\core\common\models\traits\AddressTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Order Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $name
 * @property integer $status
 * @property integer $subTotal
 * @property integer $tax
 * @property integer $shipping
 * @property integer $total
 * @property integer $discount
 * @property integer $grandTotal
 * @property string $notes
 * @property date $deliveryDate
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Order extends CmgEntity {

	const STATUS_NEW				=  0;
	const STATUS_CONFIRMED			= 10;
	const STATUS_CANCELLED			= 20;
	const STATUS_PLACED				= 30;
	const STATUS_PAID				= 40;
	const STATUS_DELIVERED			= 50;
	const STATUS_RETURNED			= 60;

	public static $statusMap = array(
	    self::STATUS_NEW  => 'New',
	    self::STATUS_CONFIRMED => 'Confirmed',
	    self::STATUS_CANCELLED => 'Cancelled',
	    self::STATUS_PLACED => 'Placed',
	    self::STATUS_PAID => 'Paid',
	    self::STATUS_DELIVERED => 'Delivered',
	    self::STATUS_RETURNED => 'Returned'
	   	);

	public $addressType		= CartGlobal::TYPE_ORDER;

	use AddressTrait;

	use CreateModifyTrait;

	// Instance methods --------------------------------------------------

	public function getParentOrder() {

		return $this->hasOne( Order::className(), [ 'id' => 'parentOrderId' ] );
	}

	public function getPayment(){

		return $this->hasOne( Payment::className(),[ 'parentId' => 'id' ] );
	}

	public function getChildOrders() {

		return $this->hasMany( Order::className(), [ 'parentOrderId' => 'id' ] );
	}

	public function getItems() {

    	return $this->hasMany( OrderItem::className(), [ 'orderId' => 'id' ] );
	}

	public function generateName() {

		$this->name = Yii::$app->security->generateRandomString();;
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isConfirmed() {

		return $this->status == self::STATUS_CONFIRMED;
	}

	public function isCancelled() {

		return $this->status == self::STATUS_CANCELLED;
	}

	public function isPlaced() {

		return $this->status == self::STATUS_PLACED;
	}

	public function isPaid() {

		return $this->status == self::STATUS_PAID;
	}

	public function isDelivered() {

		return $this->status == self::STATUS_DELIVERED;
	}

	public function isReturned() {

		return $this->status == self::STATUS_RETURNED;
	}

	public function isPrintable() {

		return in_array( $this->status, [ self::STATUS_PAID, self::STATUS_DELIVERED ] );
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
			[ [ 'id', 'parentId', 'parentType', 'name', 'status', 'subTotal', 'tax', 'shipping', 'total', 'discount', 'grandTotal', 'notes' ], 'safe' ],
            [ [ 'parentId', 'parentOrderId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'deliveryDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentOrderId' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_PARENT_ORDER ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'subTotal' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TOTAL_SUB ),
			'tax' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TAX ),
			'shipping' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_SHIPPING ),
			'total' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'discount' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'grandTotal' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_TOTAL_GRAND ),
			'deliveryDate' => Yii::$app->cmgCartMessage->getMessage( CartGlobal::FIELD_DELIVERY_DATE ),
		];
	}

	// Static Methods ----------------------------------------------

	public static function findByName( $name ) {

        return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_ORDER;
	}

	// Cart ------------------------------

}

?>