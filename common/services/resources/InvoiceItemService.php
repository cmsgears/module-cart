<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\resources;

// CMG Imports
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

	// Create Invoice Item from Order Item
	public function createFromOrderItem( $invoice, $orderItem, $config = [] ) {

		$model = $this->getModelObject();

		// Set Attributes
		$model->invoiceId = $invoice->id;

		// Copy from Cart Item
		$model->copyForUpdateFrom( $orderItem, [
			'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId',
			'parentId', 'parentType', 'name', 'price', 'primary', 'purchase', 'quantity', 'total', 'weight', 'volume',
			'length', 'width', 'height', 'radius'
		]);

		$model->save();

		// Return InvoiceItem
		return $model;
	}

	// Update -------------

	// Delete -------------

	// Bulk ---------------

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
