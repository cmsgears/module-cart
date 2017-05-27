<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\common\utilities\DateUtil;

class m160622_034404_cart_data extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;

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
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Order Admin', 'order-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Order Admin is limited to manage abandoned carts and orders from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$orderAdminRole		= Role::findBySlugType( 'order-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Orders', 'admin-orders', CoreGlobal::TYPE_SYSTEM, null, 'The permission Admin Orders is to manage abandoned carts and orders from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm			= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm			= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$adminOrdersPerm	= Permission::findBySlugType( 'admin-orders', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $adminOrdersPerm->id ],
			[ $adminRole->id, $adminOrdersPerm->id ],
			[ $orderAdminRole->id, $adminPerm->id ], [ $orderAdminRole->id, $userPerm->id ], [ $orderAdminRole->id, $adminOrdersPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertCartConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Cart', 'slug' => 'config-cart',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Cart configuration form.',
			'successMessage' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'active' => true, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlug( 'config-cart', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'active','Active', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{"title":"Enable/disable cart."}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'valueType', 'value' ];

		$metas	= [
			[ $this->site->id, 'active', 'Active', 'cart', 'flag', '1' ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	private function insertUom() {

		// Roles

		$columns	= [ 'code', 'name', 'group', 'base', 'active' ];

		$uoms		= [
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

			[ 'IN', 'Inch', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'FT', 'Feet', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'YD', 'Yard', Uom::GROUP_LENGTH_IMPERIAL, true, true ],
			[ 'RD', 'Rod', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'fu', 'Furlong', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'm', 'Mile', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'f', 'Fathom', Uom::GROUP_LENGTH_IMPERIAL, false, true ],
			[ 'nm', 'Nautical Mile', Uom::GROUP_LENGTH_IMPERIAL, false, true ],

			[ 'IN', 'Inch', Uom::GROUP_LENGTH_US, false, true ],
			[ 'FT', 'Feet', Uom::GROUP_LENGTH_US, false, true ],
			[ 'YD', 'Yard', Uom::GROUP_LENGTH_US, true, true ],
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
			[ 'ub', 'Bushel', Uom::GROUP_VOLUME_US, false, true ]
		];

		$this->batchInsert( $this->prefix . 'cart_uom', $columns, $uoms );
	}

	public function down() {

		echo "m160622_034404_cart_data will be deleted with m160621_014408_core and m160622_034400_cart.\n";

		return true;
	}
}
