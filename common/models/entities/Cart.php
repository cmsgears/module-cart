<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Cart Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property integer $parentType
 * @property string $type
 * @property string $title
 * @property string $token
 * @property short $status
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class Cart extends \cmsgears\core\common\models\base\Entity {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    const STATUS_ACTIVE         = 1000;
    const STATUS_USER_CHECKOUT  = 2000;
    const STATUS_PAYMENT        = 3000;
    const STATUS_SUCCESS        = 4000;
    const STATUS_FAILED         = 5000;
    const STATUS_ABANDONED      = 6000;

    // Public -----------------

    public static $statusMap = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_USER_CHECKOUT => 'User Checkout',
        self::STATUS_PAYMENT => 'Payment',
        self::STATUS_SUCCESS => 'Success',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_ABANDONED => 'Abandoned'
    ];

    // Protected --------------

    // Variables -----------------------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Traits ------------------------------------------------------

    use CreateModifyTrait;
    use ResourceTrait;

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'authorBehavior' => [
                'class' => AuthorBehavior::className()
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    // yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'content', 'data' ], 'safe' ],
            [ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'parentType', 'type', 'token' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'title' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
            'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
            'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Validators ----------------------------

    // Cart ----------------------------------

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // yii\db\ActiveRecord ----

    public static function tableName() {

        return CartTables::TABLE_CART;
    }

    // CMG parent classes --------------------

    // Cart ----------------------------------

    // Read - Query -----------

    public static function queryWithAll( $config = [] ) {

        $relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator' ];
        $config[ 'relations' ]	= $relations;

        return parent::queryWithAll( $config );
    }

    // Read - Find ------------

    public static function findByToken( $token ) {

        return self::find()->where( 'token=:token', [ 'token' => $token ] )->one();
    }

	public static function findByParentIdParentType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:pId AND parentType=:pType', [ ':pId' => $parentId, ':pType' => $parentType ] )->one();
	}

    // Create -----------------

    // Update -----------------

    // Delete -----------------
}
