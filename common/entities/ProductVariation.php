<?php
namespace cmsgears\cart\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\entities\CmgEntity;

class ProductVariation extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getProduct() {

    	return $this->hasOne( Product::className(), [ 'id' => 'productId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'productId', 'name', 'value' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'increment' ], 'number', 'integerOnly' => true ],
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

		return CartTables::TABLE_PRODUCT_VARIATION;
	}

	// Mall ------------------------------

	public static function findByNameProductId( $name, $productId ) {

		return self::find()->where( 'name=:name AND productId=:id', [ ':name' => $name, ':id' => $productId ] )->one();
	}

	public static function isExistByNameProductId( $name, $productId ) {

		$variation = self::findByNameProductId( $name, $productId );

		return isset( $variation );
	}
}

?>