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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\cart\common\models\base\CartTables;

/**
 * Uom represents Unit Of Measurement.
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $group
 * @property boolean $base
 * @property boolean $active
 *
 * @since 1.0.0
 */
class Uom extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// http://www.infoplease.com/encyclopedia/science/common-weights-measures-table.html
	// https://en.wikipedia.org/wiki/Imperial_units#Length
	// https://en.wikipedia.org/wiki/United_States_customary_units#Units_of_length

	const GROUP_QUANTITY = 0;

	const GROUP_LENGTH_METRIC	= 20;
	const GROUP_LENGTH_IMPERIAL	= 40;
	const GROUP_LENGTH_US		= 60;

	const GROUP_AREA_METRIC		= 80;
	const GROUP_AREA_IMPERIAL	= 100;

	const GROUP_WEIGHT_METRIC	= 120;
	const GROUP_WEIGHT_IMPERIAL	= 140;
	const GROUP_WEIGHT_US		= 160;

	const GROUP_VOLUME_METRIC	= 180;
	const GROUP_VOLUME_IMPERIAL	= 200;
	const GROUP_VOLUME_US		= 220;

	const GROUP_TIME = 240;

	public static $groupMap = [
		self::GROUP_QUANTITY => 'Quantity',
		self::GROUP_LENGTH_METRIC => 'Metric Length',
		self::GROUP_LENGTH_IMPERIAL => 'Imperial Length',
		self::GROUP_LENGTH_US => 'US Length',
		self::GROUP_AREA_METRIC => 'Metric Area',
		self::GROUP_AREA_IMPERIAL => 'Imperial Area',
		self::GROUP_WEIGHT_METRIC => 'Metric Weight',
		self::GROUP_WEIGHT_IMPERIAL => 'Imperial Weight',
		self::GROUP_WEIGHT_US => 'US Weight',
		self::GROUP_VOLUME_METRIC => 'Metric Volume',
		self::GROUP_VOLUME_IMPERIAL => 'Imperial Volume',
		self::GROUP_VOLUME_US => 'US Volume',
		self::GROUP_TIME => 'Time'
	];

	// Used for external docs
	public static $revGroupMap = [
		'Quantity' => self::GROUP_QUANTITY,
		'Metric Length' => self::GROUP_LENGTH_METRIC,
		'Imperial Length' => self::GROUP_LENGTH_IMPERIAL,
		'US Length' => self::GROUP_LENGTH_US,
		'Metric Area' => self::GROUP_AREA_METRIC,
		'Imperial Area' => self::GROUP_AREA_IMPERIAL,
		'Metric Weight' => self::GROUP_WEIGHT_METRIC,
		'Imperial Weight' => self::GROUP_WEIGHT_IMPERIAL,
		'US Weight' => self::GROUP_WEIGHT_US,
		'Metric Volume' => self::GROUP_VOLUME_METRIC,
		'Imperial Volume' => self::GROUP_VOLUME_IMPERIAL,
		'US Volume' => self::GROUP_VOLUME_US,
		'Time' => self::GROUP_TIME
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

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'code', 'name', 'group' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ [ 'group', 'name' ], 'unique', 'targetAttribute' => [ 'group', 'name' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ 'code', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ 'group', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'base', 'active' ], 'boolean' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
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

	/**
	 * Returns the conversions belonging to the Unit.
	 * @return UomConversion[]
	 */
	public function getConversions() {

		return $this->hasMany( UomConversion::class, [ 'uomId' => 'id' ] );
	}

	/**
	 * Return string representation of group.
	 *
	 * @return string
	 */
	public function getGroupStr() {

		return self::$groupMap[ $this->group ];
	}

	/**
	 * Return string representation of base flag.
	 *
	 * @return string
	 */
	public function getBaseStr() {

		return Yii::$app->formatter->asBoolean( $this->base );
	}

	/**
	 * Return string representation of active flag.
	 *
	 * @return string
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CartTables::getTableName( CartTables::TABLE_UOM );
	}

	// CMG parent classes --------------------

	// Uom -----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithConversions( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'conversions' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
