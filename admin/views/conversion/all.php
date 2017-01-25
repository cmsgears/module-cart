<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'All UOM Conversions | ' . $coreProperties->getSiteTitle();
$siteUrl		= $coreProperties->getSiteUrl();

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

// Searching
$searchTerms	= Yii::$app->request->getQueryParam( 'keywords' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="header-content clearfix">
	<div class="header-actions col15x10">
		<span class="frm-icon-element element-small">
			<i class="cmti cmti-plus"></i>
			<?= Html::a( 'Add', [ 'create' ], [ 'class' => 'btn' ] ) ?>
		</span>
	</div>
	<div class="header-search col15x5">
		<input id="search-terms" class="element-large" type="text" name="search" value="<?= $searchTerms ?>">
		<span class="frm-icon-element element-medium">
			<i class="cmti cmti-search"></i>
			<button id="btn-search">Search</button>
		</span>
	</div>
</div>
<div class="header-content clearfix">
	<div class="header-actions col12x8">
		<span class="bold">Sort By:</span>
		<span class="wrap-sort">
			<?= $dataProvider->sort->link( 'source', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'target', [ 'class' => 'sort btn btn-medium' ] ) ?>
		</span>
	</div>
	<div class="header-actions col12x4 align align-right">
		<span class="wrap-filters"></span>
	</div>
</div>

<div class="data-grid">
	<div class="grid-header clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
	<div class="grid-content">
		<table>
			<thead>
				<tr>
					<th>Source</th>
					<th>Source Group</th>
					<th>Target</th>
					<th>Target Group</th>
					<th>Quantity</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $conversion ) {

						$id			= $conversion->id;
						$source		= $conversion->source;
						$target		= $conversion->target;
				?>
					<tr>
						<td><?= $source->name ?></td>
						<td><?= $source->getGroupStr() ?></td>
						<td><?= $target->name ?></td>
						<td><?= $target->getGroupStr() ?></td>
						<td><?= $conversion->quantity ?></td>
						<td class="actions">
							<span title="Update"><?= Html::a( "", [ "update?id=$id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
							<span title="Delete"><?= Html::a( "", [ "delete?id=$id" ], [ 'class' => 'cmti cmti-close-c-o' ] )  ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-header clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>