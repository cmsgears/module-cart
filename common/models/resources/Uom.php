<?php
namespace cmsgears\cart\common\models\resources;

// Yii Imports
use \Yii;

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
 * @property string $group
 * @property boolean $base
 */
class Uom extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// http://www.infoplease.com/encyclopedia/science/common-weights-measures-table.html

	const GROUP_QUANTITY		= 'quantity';

	const GROUP_LENGTH_METRIC	= 'length-metric';
	const GROUP_LENGTH_A_N_B	= 'length-anb';

	const GROUP_WEIGHT_METRIC	= 'weight-metric';
	const GROUP_WEIGHT_A_N_B	= 'weight-anb-a';

	const GROUP_VOLUME_METRIC	= 'volume-metric';
	const GROUP_VOLUME_A_N_B	= 'volume-anb';

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
			[ [ 'name', 'group' ], 'unique' ],
			[ 'code', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'name', 'group' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
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

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CartTables::TABLE_UOM;
	}

	// CMG parent classes --------------------

	// Uom -----------------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'conversions' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
