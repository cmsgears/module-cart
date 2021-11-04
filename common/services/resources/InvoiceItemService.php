<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\cart\common\models\resources\InvoiceItem;

use cmsgears\cart\common\services\interfaces\resources\IInvoiceItemService;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

/**
 * InvoiceItemService provide service methods of order item model.
 *
 * @since 1.0.0
 */
class InvoiceItemService extends \cmsgears\core\common\services\base\ModelResourceService implements IInvoiceItemService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\InvoiceItem';

	public static $parentType = CartGlobal::TYPE_INVOICE_ITEM;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// InvoiceItemService --------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'invoice' => [
					'asc' => [ "$modelTable.invoiceId" => SORT_ASC ],
					'desc' => [ "$modelTable.invoiceId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Invoice'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'sku' => [
					'asc' => [ "$modelTable.sku" => SORT_ASC ],
					'desc' => [ "$modelTable.sku" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'SKU'
				],
				'status' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'price' => [
					'asc' => [ "$modelTable.price" => SORT_ASC ],
					'desc' => [ "$modelTable.price" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Price'
				],
				'discount' => [
					'asc' => [ "$modelTable.discount" => SORT_ASC ],
					'desc' => [ "$modelTable.discount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Discount'
				],
				'total' => [
					'asc' => [ "$modelTable.total" => SORT_ASC ],
					'desc' => [ "$modelTable.total" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Total'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		// Filter - Status
		if( isset( $status ) && empty( $config[ 'conditions' ][ "$modelTable.status" ] ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'sku' => "$modelTable.sku",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'sku' => "$modelTable.sku",
			'content' => "$modelTable.content",
			'status' => 'Price',
			'price' => 'Discount',
			'discount' => 'Total'
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByInvoiceId( $invoiceId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.invoiceId" ] = $invoiceId;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByInvoiceId( $invoiceId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByInvoiceId( $invoiceId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		// Update Item Total
		$model->refreshTotal();

		$model = parent::create( $model, $config );

		Yii::$app->factory->get( 'invoiceService' )->refreshTotal( $model->invoice );

		return $model;
	}

	// Create Invoice Item from Order Item
	public function createFromOrderItem( $invoice, $orderItem, $config = [] ) {

		$model = $this->getModelObject();

		// Set Attributes
		$model->invoiceId = $invoice->id;

		// Copy from Cart Item
		$model->copyForUpdateFrom( $orderItem, [
			'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId',
			'parentId', 'parentType', 'type', 'name', 'sku', 'status', 'price', 'discount', 'tax1', 'tax2', 'tax3', 'tax4', 'tax5', 'total',
			'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius'
		]);

		$model->save();

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId',
			'name', 'sku', 'price', 'discount', 'tax1', 'tax2', 'tax3', 'tax4', 'tax5', 'total',
			'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius',
			'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'invoiceId', 'status' ] );
		}

		// Update Item Total
		$model->refreshTotal();

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Update Invoice Total
		Yii::$app->factory->get( 'invoiceService' )->refreshTotal( $model->invoice );

		return $model;
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		Yii::$app->factory->get( 'invoiceService' )->refreshTotal( $model->invoice );

		return $model;
	}

	public function paid( $model, $config = [] ) {

		if( !$model->isPaid() ) {

			$this->updateStatus( $model, InvoiceItem::STATUS_PAID );
		}
	}

	public function cancel( $model, $config = [] ) {

		if( !$model->isCancelled() ) {

			$this->updateStatus( $model, InvoiceItem::STATUS_CANCELLED );
		}
	}

	public function deliver( $model, $config = [] ) {

		if( !$model->isDelivered() ) {

			$this->updateStatus( $model, InvoiceItem::STATUS_DELIVERED );
		}
	}

	public function back( $model, $config = [] ) {

		if( !$model->isReturned() ) {

			$this->updateStatus( $model, InvoiceItem::STATUS_RETURNED );
		}
	}

	public function receive( $model, $config = [] ) {

		if( !$model->isReceived() ) {

			$this->updateStatus( $model, InvoiceItem::STATUS_RECEIVED );
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		if( parent::delete( $model, $config ) ) {

			// Update Invoice Total
			Yii::$app->factory->get( 'invoiceService' )->refreshTotal( $model->invoice );

			return true;
		}

		return false;
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'cancel': {

						$this->cancel( $model );

						break;
					}
					case 'deliver': {

						$this->deliver( $model );

						break;
					}

					case 'back': {

						$this->back( $model );

						break;
					}
					case 'receive': {

						$this->receive( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// InvoiceItemService --------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
