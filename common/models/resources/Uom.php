<?php
namespace cmsgears\cart\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\base\CartTables;

/**
 * Uom Entity - The primary class.
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $group
 * @property boolean $base
 */
class Uom extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// http://www.infoplease.com/encyclopedia/science/common-weights-measures-table.html
	// https://en.wikipedia.org/wiki/Imperial_units#Length
	// https://en.wikipedia.org/wiki/United_States_customary_units#Units_of_length

	const GROUP_QUANTITY		= 0;

	const GROUP_LENGTH_METRIC	= 10;
	const GROUP_LENGTH_IMPERIAL	= 20;
	const GROUP_LENGTH_US		= 30;

	const GROUP_WEIGHT_METRIC	= 40;
	const GROUP_WEIGHT_IMPERIAL	= 50;
	const GROUP_WEIGHT_US		= 60;

	const GROUP_VOLUME_METRIC	= 70;
	const GROUP_VOLUME_IMPERIAL	= 80;
	const GROUP_VOLUME_US		= 90;

	public static $groupMap = [
		self::GROUP_QUANTITY => 'Quantity',
		self::GROUP_LENGTH_METRIC => 'Metric Length',
		self::GROUP_LENGTH_IMPERIAL => 'Imperial Length',
		self::GROUP_LENGTH_US => 'US Length',
		self::GROUP_WEIGHT_METRIC => 'Metric Weight',
		self::GROUP_WEIGHT_IMPERIAL => 'Imperial Weight',
		self::GROUP_WEIGHT_US => 'US Weight',
		self::GROUP_VOLUME_METRIC => 'Metric Volume',
		self::GROUP_VOLUME_IMPERIAL => 'Imperial Volume',
		self::GROUP_VOLUME_US => 'US Volume'
	];

	// Used for external docs
	public static $revGroupMap = [
		'Quantity' => self::GROUP_QUANTITY,
		'Metric Length' => self::GROUP_LENGTH_METRIC,
		'Imperial Length' => self::GROUP_LENGTH_IMPERIAL,
		'US Re Length' => self::GROUP_LENGTH_US,
		'Metric Weight' => self::GROUP_WEIGHT_METRIC,
		'Imperial Weight' => self::GROUP_WEIGHT_IMPERIAL,
		'US Weight' => self::GROUP_WEIGHT_US,
		'Metric Volume' => self::GROUP_VOLUME_METRIC,
		'Imperial Volume' => self::GROUP_VOLUME_IMPERIAL,
		'US Volume' => self::GROUP_VOLUME_US
	];

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

	public function rules() {

		$rules = [
			[ [ 'code', 'name', 'group' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			[ [ 'name', 'group' ], 'unique', 'targetAttribute' => [ 'name', 'group' ] ],
			[ 'code', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'name' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'group' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'base', 'active' ], 'boolean' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'group' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GROUP ),
			'base' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BASE ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Uom -----------------------------------

	public function getConversions() {

		return $this->hasMany( UomConversion::className(), [ 'uomId' => 'id' ] );
	}

	public function getGroupStr() {

		return self::$groupMap[ $this->group ];
	}

	public function getBaseStr() {

		return Yii::$app->formatter->asBoolean( $this->base );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_UOM;
	}

	// CMG parent classes --------------------

	// Uom -----------------------------------

	// Read - Query -----------

	public static function queryWithConversions( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'conversions' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
