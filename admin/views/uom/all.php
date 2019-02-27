<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'UOMs | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-bank/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'UOMs', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'code' => 'Code' ],
	'sortColumns' => [
		'name' => 'Name', 'code' => 'Code', 'group' => 'Group', 'base' => 'Base'
	],
	'filters' => [
		'model' => [ 'group' => 'Group', 'base' => 'Base' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'code' => [ 'title' => 'Code', 'type' => 'text' ],
		'group' => [ 'title' => 'Group', 'type' => 'flag' ],
		'base' => [ 'title' => 'Base', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'model' => [ 'group' => 'Group', 'base' => 'Base', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x6', 'x5', null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'code' => 'Code',
		'group' => [ 'title' => 'Group', 'generate' => function( $model ) { return $model->getGroupStr(); } ],
		'base' => [ 'title' => 'Base', 'generate' => function( $model ) { return $model->getBaseStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/uom",
	//'cardView' => "$moduleTemplates/grid/cards/uom",
	//'actionView' => "$moduleTemplates/grid/actions/uom"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'UOM', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete UOM', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'UOM', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
