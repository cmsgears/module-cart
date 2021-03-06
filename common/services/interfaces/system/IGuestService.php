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
 * IGuestService declares methods specific to manage guests for guest checkouts.
 *
 * @since 1.0.0
 */
interface IGuestService {

	public function create( $model, $config = [] );

}
