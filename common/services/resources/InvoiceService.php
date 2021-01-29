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
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\cart\common\models\resources\Invoice;

use cmsgears\cart\common\services\interfaces\resources\IInvoiceService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\StatusTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * InvoiceService provide service methods of order model.
 *
 * @since 1.0.0
 */
class InvoiceService extends \cmsgears\core\common\services\base\ModelResourceService implements IInvoiceService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\cart\common\models\resources\Invoice';

	public static $parentType = CartGlobal::TYPE_INVOICE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $orderService;

	protected $orderItemService;

	protected $invoiceItemService;

	protected $addressService;
	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultiSiteTrait;
	use StatusTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->orderService		= Yii::$app->factory->get( 'orderService' );
		$this->orderItemService	= Yii::$app->factory->get( 'orderItemService' );

		$this->invoiceItemService = Yii::$app->factory->get( 'invoiceItemService' );

		$this->addressService = Yii::$app->factory->get( 'addressService' );

		$this->modelAddressService = Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// InvoiceService ------------------------

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
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'status' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'code' => [
					'asc' => [ "$modelTable.code" => SORT_ASC ],
					'desc' => [ "$modelTable.code" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Code'
				],
				'total' => [
					'asc' => [ "$modelTable.total" => SORT_ASC ],
					'desc' => [ "$modelTable.total" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Total'
				],
				'discount' => [
					'asc' => [ "$modelTable.discount" => SORT_ASC ],
					'desc' => [ "$modelTable.discount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Discount'
				],
				'grandTotal' => [
					'asc' => [ "$modelTable.grandTotal" => SORT_ASC ],
					'desc' => [ "$modelTable.grandTotal" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Grand Total'
				],
				'currency' => [
					'asc' => [ "$modelTable.currency" => SORT_ASC ],
					'desc' => [ "$modelTable.currency" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Currency'
				],
				'issueDate' => [
					'asc' => [ "$modelTable.issueDate" => SORT_ASC ],
					'desc' => [ "$modelTable.issueDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Date Issued'
				],
				'dueDate' => [
					'asc' => [ "$modelTable.dueDate" => SORT_ASC ],
					'desc' => [ "$modelTable.dueDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Due Date'
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
			'title' => "$modelTable.title",
			'code' => "$modelTable.code",
			'desc' => "$modelTable.description",
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
			'title' => "$modelTable.title",
			'code' => "$modelTable.code",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		return $this->getPage( $config );
	}

	public function getPageByUserIdType( $userId, $type, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		return $this->getPage( $config );
	}

	public function getPageByUserIdParentType( $userId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		$config[ 'conditions' ][ "$modelTable.parentType" ] = $parentType;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * Use only if title is unique for order.
	 *
	 * @param string $title
	 * @return Order
	 */
	public function getByTitle( $title ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByTitle( $title );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getCountByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByParent( $parentId, $parentType )->count();
	}

	public function getCountByUserId( $userId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByUserId( $userId )->count();
	}

	// Create -------------

	public function createFromOrder( $invoice, $order, $config = [] ) {

		$addressClass = $this->addressService->getModelClass();

		// Set Attributes
		$billingAddress		= $order->billingAddress;
		$shippingAddress	= $order->shippingAddress;

		// Order
		$invoice->parentId		= $order->parentId;
		$invoice->parentType	= $order->parentType;
		$invoice->type			= $order->type;
		$invoice->status		= $invoice->status ?? Order::STATUS_NEW;

		// Generate UID if required
		if( empty( $invoice->title ) ) {

			$invoice->generateTitle();
		}

		// Set Order Totals
		$invoice->subTotal		= $order->subTotal;
		$invoice->discount		= $order->discount;
		$invoice->tax			= $order->tax;
		$invoice->shipping		= $order->shipping;
		$invoice->total			= $order->total;
		$invoice->grandTotal	= $order->grandTotal;

		// Init Transaction
		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Create Invoice
			$invoice->save();

			// Create Billing Address
			if( isset( $billingAddress ) ) {

				$address = $this->addressService->create( $billingAddress );

				$this->modelAddressService->activateByParentModelId( $invoice->id, static::$parentType, $address->id, $addressClass::TYPE_BILLING );
			}

			// Create Shipping Address
			if( isset( $shippingAddress ) ) {

				$address = $this->addressService->create( $shippingAddress );

				$this->modelAddressService->activateByParentModelId( $invoice->id, static::$parentType, $address->id, $addressClass::TYPE_SHIPPING );
			}

			// Create Invoice Items
			$orderItems = $order->activeItems;

			foreach( $orderItems as $item ) {

				$this->invoiceItemService->createFromOrderItem( $invoice, $item, $config );
			}

			// Commit Order
			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		// Return Order
		return $invoice;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$refresh	= isset( $config[ 'refreshTotal' ] ) ? $config[ 'refreshTotal' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'code', 'service', 'title', 'description',
			'issueDate', 'dueDate', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'currency'
			]);
		}

		$date = DateUtil::getDate();

		if( !empty( $model->dueDate ) && DateUtil::greaterThan( $model->dueDate, $date ) ) {

			$model->status = Invoice::STATUS_OVERDUE;

			$attributes	= ArrayHelper::merge( $attributes, [ 'status' ] );
		}

		if( $refresh ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'subTotal', 'itemDiscount', 'tax1', 'tax2', 'tax3', 'tax4', 'tax5',
				'shipping', 'total', 'discount', 'grandTotal'
			]);

			$model->refreshTotal();
		}

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'template' ] = CartGlobal::TPL_NOTIFY_INVOICE_STATUS_CHANGE;

			$config[ 'data' ][ 'message' ] = 'Invoice status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	public function refreshTotal( $model ) {

		$model->refreshTotal();

		$attributes	= [
			'subTotal', 'itemDiscount', 'tax1', 'tax2', 'tax3', 'tax4', 'tax5',
			'shipping', 'total', 'discount', 'grandTotal'
		];

		// Update Invoice
		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateCode( $model, $code ) {

		$model->code = $code;

		return parent::update( $model, [
			'attributes' => [ 'code' ]
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => [ 'status' ]
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Invoice status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	public function approve( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_APPROVED ) {

			$this->updateStatus( $model, Invoice::STATUS_APPROVED );
		}
	}

	public function hold( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_HOLD ) {

			$this->updateStatus( $model, Invoice::STATUS_HOLD );
		}
	}

	public function cancel( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_CANCELLED ) {

			$this->updateStatus( $model, Invoice::STATUS_CANCELLED );

			$transactionService = Yii::$app->factory->get( 'transactionService' );

			$transactions = $model->transactions;

			foreach( $transactions as $transaction ) {

				$transactionService->cancel( $transaction );
			}
		}
	}

	public function send( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_SENT ) {

			$this->updateStatus( $model, Invoice::STATUS_SENT );
		}
	}

	public function overdue( $model, $config = [] ) {

		$date = DateUtil::getDate();

		if( $model->status != Invoice::STATUS_OVERDUE &&
			( empty( $model->dueDate ) || DateUtil::greaterThan( $model->dueDate, $date ) ) ) {

			$this->updateStatus( $model, Invoice::STATUS_OVERDUE );
		}
	}

	public function paid( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_PAID ) {

			$this->updateStatus( $model, Invoice::STATUS_PAID );
		}
	}

	public function confirm( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_CONFIRMED ) {

			$this->updateStatus( $model, Invoice::STATUS_CONFIRMED );
		}
	}

	public function refund( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_REFUNDED ) {

			$this->updateStatus( $model, Invoice::STATUS_REFUNDED );
		}
	}

	public function complete( $model, $config = [] ) {

		if( $model->status != Invoice::STATUS_COMPLETED ) {

			$this->updateStatus( $model, Invoice::STATUS_COMPLETED );
		}
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'hold': {

						$this->hold( $model );

						break;
					}
					case 'cancel': {

						$this->cancel( $model );

						break;
					}
					case 'send': {

						$this->send( $model );

						break;
					}
					case 'paid': {

						$this->paid( $model );

						break;
					}
					case 'confirm': {

						$this->confirm( $model );

						break;
					}
					case 'refund': {

						$this->refund( $model );

						break;
					}
					case 'complete': {

						$this->complete( $model );

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

	// InvoiceService ------------------------

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
