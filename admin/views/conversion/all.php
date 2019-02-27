<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'UOM Conversions | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-bank/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'UOM Conversions', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'source' => 'Source', 'target' => 'Target' ],
	'sortColumns' => [
		'source' => 'Source', 'target' => 'Target'
	],
	'filters' => [],
	'reportColumns' => [
		'source' => [ 'title' => 'Source', 'type' => 'text' ],
		'target' => [ 'title' => 'Target', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x2', 'x2', 'x2', 'x2', 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'source' => [ 'title' => 'Source', 'generate' => function( $model ) { return isset( $model->source ) ? $model->source->name : null; } ],
		'sourceg' => [ 'title' => 'Source Group', 'generate' => function( $model ) { return isset( $model->source ) ? $model->source->getGroupStr() : null; } ],
		'target' => [ 'title' => 'Target', 'generate' => function( $model ) { return isset( $model->target ) ? $model->target->name : null; } ],
		'targetg' => [ 'title' => 'Target Group', 'generate' => function( $model ) { return isset( $model->target ) ? $model->target->getGroupStr() : null; } ],
		'quantity' => 'Quantity',
		'conversion' => [ 'title' => 'Conversion', 'generate' => function( $model ) { return "1 {$model->source->name} = {$model->quantity} {$model->target->name}"; } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/uomc",
	//'cardView' => "$moduleTemplates/grid/cards/uomc",
	//'actionView' => "$moduleTemplates/grid/actions/uomc"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'UOM Conversion', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete UOM Conversion', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'UOM Conversion', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
