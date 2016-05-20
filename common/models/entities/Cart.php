<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgEntity;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Cart Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $name
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $token
 * @property datetime $status
 */
class Cart extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    const STATUS_NEW            = 10;
    const STATUS_USER_CHECKOUT  = 20;
    const STATUS_PAYMENT        = 30;
    const STATUS_SUCCESS        = 40;
    const STATUS_FAILED         = 50;
    const STATUS_ABANDONED      = 60;
    const STATUS_ACTIVE         = 70;

    public static $statusMap = [
        self::STATUS_NEW => 'New',
        self::STATUS_USER_CHECKOUT => 'User Checkout',
        self::STATUS_PAYMENT => 'Payment',
        self::STATUS_SUCCESS => 'Success',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_ABANDONED => 'Abandoned',
        self::STATUS_ACTIVE => 'Active',
    ];

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    use CreateModifyTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    public function generateName() {

        $this->name = Yii::$app->security->generateRandomString();;
    }

    public function getCartTotal( $items ) {

        $cartTotal  = 0;

        foreach ( $items as $item ) {

            $cartTotal += $item->getTotalPrice();
        }

        return $cartTotal;
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
            [ [ 'id', 'parentId', 'parentType', 'name' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
        ];
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    public static function tableName() {

        return CartTables::TABLE_CART;
    }

    // Cart ------------------------------

    public static function findByParentIdParentType( $parentId, $parentType ) {

        return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] )->one();
    }

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>