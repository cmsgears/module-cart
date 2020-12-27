<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The cart data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m161005_034404_cart_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

	public function up() {

		// Create RBAC and Site Members
		$this->insertRolePermission();

		// Create various config
		$this->insertCartConfig();

		// Init default config
		$this->insertDefaultConfig();

		// UOMs
		$this->insertUom();
		$this->insertUomConversions();
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Cart Admin', CartGlobal::ROLE_CART_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Cart Admin is limited to manage carts from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( CoreGlobal::ROLE_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$cartAdminRole	= Role::findBySlugType( CartGlobal::ROLE_CART_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Admin Permissions - Hard Coded
			[ $this->master->id, $this->master->id, 'Admin Cart', CartGlobal::PERM_CART_ADMIN, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Admin Cart allows user to administer cart, uom and conversions from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Orders', CartGlobal::PERM_ORDER_MANAGE, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission Manage Orders allows user to manage orders from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Order Author', CartGlobal::PERM_ORDER_AUTHOR, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission order author allows user to perform crud operations of order belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Order Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Orders', CartGlobal::PERM_ORDER_VIEW, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission View Orders allows user to view orders from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Order', CartGlobal::PERM_ORDER_ADD, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Add Order allows user to add orders from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Order', CartGlobal::PERM_ORDER_UPDATE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Update Order allows user to update order from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Order', CartGlobal::PERM_ORDER_DELETE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Delete Order allows user to delete order from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Order', CartGlobal::PERM_ORDER_APPROVE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Approve Order allows user to approve, cancel, refund order from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Order', CartGlobal::PERM_ORDER_PRINT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Print Order allows user to print order from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Orders', CartGlobal::PERM_ORDER_IMPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Import Orders allows user to import orders from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Orders', CartGlobal::PERM_ORDER_EXPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission Export Orders allows user to export orders from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Admin
		$adminPerm		= Permission::findBySlugType( CoreGlobal::PERM_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( CoreGlobal::PERM_USER, CoreGlobal::TYPE_SYSTEM );
		$cartAdminPerm	= Permission::findBySlugType( CartGlobal::PERM_CART_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permission Groups
		$orderManagePerm	= Permission::findBySlugType( CartGlobal::PERM_ORDER_MANAGE, CoreGlobal::TYPE_SYSTEM );
		$orderAuthorPerm	= Permission::findBySlugType( CartGlobal::PERM_ORDER_AUTHOR, CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vOrdersPerm	= Permission::findBySlugType( CartGlobal::PERM_ORDER_VIEW, CoreGlobal::TYPE_SYSTEM );
		$aOrderPerm		= Permission::findBySlugType( CartGlobal::PERM_ORDER_ADD, CoreGlobal::TYPE_SYSTEM );
		$uOrderPerm		= Permission::findBySlugType( CartGlobal::PERM_ORDER_UPDATE, CoreGlobal::TYPE_SYSTEM );
		$dOrderPerm		= Permission::findBySlugType( CartGlobal::PERM_ORDER_DELETE, CoreGlobal::TYPE_SYSTEM );
		$apOrderPerm	= Permission::findBySlugType( CartGlobal::PERM_ORDER_APPROVE, CoreGlobal::TYPE_SYSTEM );
		$pOrderPerm		= Permission::findBySlugType( CartGlobal::PERM_ORDER_PRINT, CoreGlobal::TYPE_SYSTEM );
		$iOrdersPerm	= Permission::findBySlugType( CartGlobal::PERM_ORDER_IMPORT, CoreGlobal::TYPE_SYSTEM );
		$eOrdersPerm	= Permission::findBySlugType( CartGlobal::PERM_ORDER_EXPORT, CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $cartAdminPerm->id ], [ $superAdminRole->id, $cartAdminPerm->id ],
			[ $adminRole->id, $cartAdminPerm->id ], [ $adminRole->id, $cartAdminPerm->id ],
			[ $cartAdminRole->id, $adminPerm->id ], [ $cartAdminRole->id, $userPerm->id ], [ $cartAdminRole->id, $cartAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );

		// Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// Order Manager - Organization
			[ null, null, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $orderManagePerm->id, $vOrdersPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $orderManagePerm->id, $aOrderPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $orderManagePerm->id, $uOrderPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $orderManagePerm->id, $dOrderPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $orderManagePerm->id, $apOrderPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $orderManagePerm->id, $pOrderPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $orderManagePerm->id, $iOrdersPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ],
			[ $orderManagePerm->id, $eOrdersPerm->id, $orderManagePerm->id, CoreGlobal::TYPE_PERMISSION, 16, 17 ],

			// Order Author - Individual
			[ null, null, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $orderAuthorPerm->id, $vOrdersPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $orderAuthorPerm->id, $aOrderPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $orderAuthorPerm->id, $uOrderPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $orderAuthorPerm->id, $dOrderPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $orderAuthorPerm->id, $pOrderPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $orderAuthorPerm->id, $iOrdersPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $orderAuthorPerm->id, $eOrdersPerm->id, $orderAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertCartConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Cart', 'slug' => 'config-' . CartGlobal::TYPE_CART,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Cart configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CartGlobal::TYPE_CART, CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'active','Active', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Enable/disable cart."}' ],
			[ $config->id, 'remove_cart','Remove Cart', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Remove cart after converted to order."}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'active', 'valueType', 'value', 'data' ];

		$metas	= [
			[ $this->site->id, 'active', 'Active', 'cart', 1, 'flag', '1', NULL ],
			[ $this->site->id, 'remove_cart', 'Remove Cart', 'cart', 1, 'flag', '1', NULL ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	private function insertUom() {

		// UOMs

		$columns = [ 'code', 'name', 'group', 'base', 'active' ];

		$uoms = [
			[ 'BG', 'Bag', Uom::GROUP_QUANTITY, false, true ],
			[ 'BL', 'Bale', Uom::GROUP_QUANTITY, false, true ],
			[ 'BT', 'Bottle', Uom::GROUP_QUANTITY, false, true ],
			[ 'BX', 'Box', Uom::GROUP_QUANTITY, false, true ],
			[ 'JR', 'Jar', Uom::GROUP_QUANTITY, false, true ],
			[ 'CL', 'Cylinder', Uom::GROUP_QUANTITY, false, true ],
			[ 'CS', 'Case', Uom::GROUP_QUANTITY, false, true ],
			[ 'CT', 'Carton', Uom::GROUP_QUANTITY, false, true ],
			[ 'PK', 'Pack', Uom::GROUP_QUANTITY, false, true ],
			[ 'PA', 'Package', Uom::GROUP_QUANTITY, false, true ],
			[ 'PC', 'Piece', Uom::GROUP_QUANTITY, false, true ],
			[ 'PL', 'Pail', Uom::GROUP_QUANTITY, false, true ],
			[ 'PR', 'Pair', Uom::GROUP_QUANTITY, false, true ],
			[ 'RL', 'Roll', Uom::GROUP_QUANTITY, false, true ],
			[ 'RM', 'Ream', Uom::GROUP_QUANTITY, false, true ],
			[ 'tray', 'Tray', Uom::GROUP_QUANTITY, false, true ],
			[ 'CN', 'Can', Uom::GROUP_QUANTITY, false, true ],
			[ 'bundle', 'Bundle', Uom::GROUP_QUANTITY, false, true ],
			[ 'DR', 'Drum', Uom::GROUP_QUANTITY, false, true ],
			[ 'DZ', 'Dozen', Uom::GROUP_QUANTITY, false, true ],
			[ 'EA', 'Each', Uom::GROUP_QUANTITY, true, true ],
			[ 'QR', 'Quarter', Uom::GROUP_QUANTITY, false, true ],

			[ 'MM', 'Millimeter', Uom::GROUP_LENGTH_METRIC, false, true ],
			[ 'CM', 'Centimeter', Uom::GROUP_LENGTH_METRIC, false, true ],
			[ 'dm', 'Decimeter', Uom::GROUP_LENGTH_METRIC, false, true ],
			[ 'MT', 'Meter', Uom::GROUP_LENGTH_METRIC, true, true ],
			[ 'dem', 'Decameter', Uom::GROUP_LENGTH_METRIC, false, true ],
			[ 'km', 'Kilometer', Uom::GROUP_LENGTH_METRIC, false, true ],

			[ 'ha', 'Hectare', Uom::GROUP_AREA_METRIC, true, true ],
			[ 'a', 'Are', Uom::GROUP_AREA_METRIC, false, true ],
			[ 'km2', 'Square Kilometer', Uom::GROUP_AREA_METRIC, false, true ],
			[ 'm2', 'Square Meter', Uom::GROUP_AREA_METRIC, false, true ],

			[ 'IN', 'Inch', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'FT', 'Feet', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'YD', 'Yard', Uom::GROUP_LENGTH_IMPERIAL, true, true ],
			[ 'acre', 'Acre', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'RD', 'Rod', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'fu', 'Furlong', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'm', 'Mile', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'f', 'Fathom', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'nm', 'Nautical Mile', Uom::GROUP_LENGTH_IMPERIAL, false, true ],

			[ 'IN', 'Inch', Uom::GROUP_LENGTH_US, false, true ],
			[ 'FT', 'Feet', Uom::GROUP_LENGTH_US, false, true ],
			[ 'YD', 'Yard', Uom::GROUP_LENGTH_US, true, true ],
			[ 'acre', 'Acre', Uom::GROUP_LENGTH_US, false, true ],
			[ 'RD', 'Rod', Uom::GROUP_LENGTH_US, false, true ],
			[ 'fu', 'Furlong', Uom::GROUP_LENGTH_US, false, true ],
			[ 'm', 'Mile', Uom::GROUP_LENGTH_US, false, true ],
			[ 'f', 'Fathom', Uom::GROUP_LENGTH_US, false, true ],
			[ 'nm', 'Nautical Mile', Uom::GROUP_LENGTH_US, false, true ],

			[ 'MG', 'Milligram', Uom::GROUP_WEIGHT_METRIC, false, true ],
			[ 'cg', 'Centigram', Uom::GROUP_WEIGHT_METRIC, false, true ],
			[ 'deg', 'Decigram', Uom::GROUP_WEIGHT_METRIC, false, true ],
			[ 'GM', 'Gram', Uom::GROUP_WEIGHT_METRIC, false, true ],
			[ 'dg', 'Dekagram', Uom::GROUP_WEIGHT_METRIC, false, true ],
			[ 'hg', 'Hectogram', Uom::GROUP_WEIGHT_METRIC, false, true ],
			[ 'KG', 'Kilogram', Uom::GROUP_WEIGHT_METRIC, true, true ],
			[ 'ton', 'Metric Ton', Uom::GROUP_WEIGHT_METRIC, false, true ],

			[ 'GN', 'Grain', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],
			[ 'd', 'Dram', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],
			[ 'OZ', 'Ounce', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],
			[ 'LB', 'Pound', Uom::GROUP_WEIGHT_IMPERIAL, true, true ],
			[ 'sh', 'Short Hundredweight', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],
			[ 'lh', 'Long Hundredweight', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],
			[ 'st', 'Short Ton', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],
			[ 'lt', 'Long Ton', Uom::GROUP_WEIGHT_IMPERIAL, false, true ],

			[ 'GN', 'Grain', Uom::GROUP_WEIGHT_US, false, true ],
			[ 'd', 'Dram', Uom::GROUP_WEIGHT_US, false, true ],
			[ 'OZ', 'Ounce', Uom::GROUP_WEIGHT_US, false, true ],
			[ 'LB', 'Pound', Uom::GROUP_WEIGHT_US, true, true ],
			[ 'sh', 'Short Hundredweight', Uom::GROUP_WEIGHT_US, false, true ],
			[ 'lh', 'Long Hundredweight', Uom::GROUP_WEIGHT_US, false, true ],
			[ 'st', 'Short Ton', Uom::GROUP_WEIGHT_US, false, true ],
			[ 'lt', 'Long Ton', Uom::GROUP_WEIGHT_US, false, true ],

			[ 'CC', 'Cubic Centimeter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'cd', 'Cubic Decimeter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'cma', 'Cubic Meter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'ML', 'Milliliter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'cl', 'Centiliter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'dl', 'Deciliter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'LI', 'Liter', Uom::GROUP_VOLUME_METRIC, true, true ],
			[ 'dl', 'Dekaliter', Uom::GROUP_VOLUME_METRIC, false, true ],
			[ 'hl', 'Hectoliter', Uom::GROUP_VOLUME_METRIC, false, true ],

			[ 'ci', 'Cubic Inch', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'CF', 'Cubic Feet', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'CY', 'Cubic Yard', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'bfo', 'Fluid Ounce', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'PT', 'Pint', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'QT', 'Quart', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'GL', 'Gallon', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'dp', 'Dry Pint', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'dq', 'Dry Quart', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'p', 'Peck', Uom::GROUP_VOLUME_IMPERIAL, false, true ],
			[ 'ib', 'Bushel', Uom::GROUP_VOLUME_IMPERIAL, false, true ],

			[ 'ci', 'Cubic Inch', Uom::GROUP_VOLUME_US, false, true ],
			[ 'CF', 'Cubic Feet', Uom::GROUP_VOLUME_US, false, true ],
			[ 'CY', 'Cubic Yard', Uom::GROUP_VOLUME_US, false, true ],
			[ 'ufo', 'Fluid Ounce', Uom::GROUP_VOLUME_US, false, true ],
			[ 'PT', 'Pint', Uom::GROUP_VOLUME_US, false, true ],
			[ 'QT', 'Quart', Uom::GROUP_VOLUME_US, false, true ],
			[ 'GL', 'Gallon', Uom::GROUP_VOLUME_US, true, true ],
			[ 'dp', 'Dry Pint', Uom::GROUP_VOLUME_US, false, true ],
			[ 'dq', 'Dry Quart', Uom::GROUP_VOLUME_US, false, true ],
			[ 'p', 'Peck', Uom::GROUP_VOLUME_US, false, true ],
			[ 'ub', 'Bushel', Uom::GROUP_VOLUME_US, false, true ],

			[ 's', 'Second', Uom::GROUP_TIME, false, true ],
			[ 'min', 'Minute', Uom::GROUP_TIME, false, true ],
			[ 'hrs', 'Hour', Uom::GROUP_TIME, false, true ],
			[ 'day', 'Day', Uom::GROUP_TIME, false, true ],
			[ 'week', 'Week', Uom::GROUP_TIME, false, true ],
			[ 'month', 'Month', Uom::GROUP_TIME, false, true ],
			[ 'year', 'Year', Uom::GROUP_TIME, false, true ],
		];

		$this->batchInsert( $this->prefix . 'cart_uom', $columns, $uoms );
	}

	private function insertUomConversions() {

		// UOM Conversions -> 1 Uom Unit = Quantity * (1 Target Unit)

		$hectare	= Uom::find()->where( [ 'code' => 'ha', 'group' => Uom::GROUP_AREA_METRIC ] )->one();
		$a			= Uom::find()->where( [ 'code' => 'a', 'group' => Uom::GROUP_AREA_METRIC ] )->one();
		$km2		= Uom::find()->where( [ 'code' => 'km2', 'group' => Uom::GROUP_AREA_METRIC ] )->one();
		$m2			= Uom::find()->where( [ 'code' => 'm2', 'group' => Uom::GROUP_AREA_METRIC ] )->one();

		$columns = [ 'uomId', 'targetId', 'quantity' ];

		$conversions = [
			[ $hectare->id, $a->id, 100 ],
			[ $hectare->id, $km2->id, 0.01 ],
			[ $hectare->id, $m2->id, 10000 ],
			[ $hectare->id, $hectare->id, 1 ],
			[ $a->id, $hectare->id, 0.01 ],
			[ $km2->id, $hectare->id, 100 ],
			[ $m2->id, $hectare->id, 0.0001 ]
		];

		$this->batchInsert( $this->prefix . 'cart_uom_conversion', $columns, $conversions );
	}

	public function down() {

		echo "m161005_034404_cart_data will be deleted with m160621_014408_core and m161005_034400_cart.\n";

		return true;
	}

}
