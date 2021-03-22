<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\cart\common\services\interfaces\system;

/**
 * ISalesService declares methods specific to sales data and graphs.
 *
 * @since 1.0.0
 */
interface ISalesService {

	public function getOrderSalesData( $duration );

	public function getInvoiceSalesData( $duration );

}
