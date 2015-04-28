<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\CmgEntity;

class ProductPlan extends CmgEntity {

	const DURATION_WEEKLY			= 1;
	const DURATION_BI_WEEKLY		= 5;
	const DURATION_MONTHLY			=10;
	const DURATION_QUARTERLY		=15;
	const DURATION_HALF_YEARLY		=20;
	const DURATION_YEARLY			=25;

	public static $durationMap = [
		self::DURATION_WEEKLY => "weekly",
		self::DURATION_BI_WEEKLY => "bi weekly",
		self::DURATION_MONTHLY => "monthly",
		self::DURATION_QUARTERLY => "quarterly",
		self::DURATION_HALF_YEARLY => "half yearly",
		self::DURATION_YEARLY => "yearly"
	];

	// Instance Methods --------------------------------------------

	public function getProduct() {

    	return $this->hasOne( Product::className(), [ 'id' => 'productId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'productId', 'name', 'period', 'trial', 'price' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'period', 'trial', 'interval' ], 'number', 'integerOnly' => true ],
            [ 'price', 'number', 'min' => 0 ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description',
			'mode' => 'Operation Mode',
			'chargeType' => 'Charge Type',
			'chargeAmount' => 'Charge Amount'
		];
	}

	/**
	 * Validates to ensure that only one name exist for a Product.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameProductId( $this->name, $this->productId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one name exist for a Product.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingVariation = self::findByNameProductId( $this->name, $this->productId );

			if( isset( $existingVariation ) && $existingVariation->id != $this->id && 
				strcmp( $existingVariation->name, $this->name ) == 0 && $existingVariation->productId == $this->productId ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CartTables::TABLE_PRODUCT_PLAN;
	}

	// ProductPlan -----------------------

	public static function findByNameProductId( $name, $productId ) {

		return self::find()->where( 'name=:name AND productId=:id', [ ':name' => $name, ':id' => $productId ] )->one();
	}

	public static function isExistByNameProductId( $name, $productId ) {

		$variation = self::findByNameProductId( $name, $productId );

		return isset( $variation );
	}
}

?>