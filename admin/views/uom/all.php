<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'All UOMs | ' . $coreProperties->getSiteTitle();
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
			<?= $dataProvider->sort->link( 'name', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'code', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'group', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'base', [ 'class' => 'sort btn btn-medium' ] ) ?>
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
					<th>Name</th>
					<th>Code</th>
					<th>Group</th>
					<th>Base</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $uom ) {

						$id		= $uom->id;
				?>
					<tr>
						<td><?= $uom->name ?></td>
						<td><?= $uom->code ?></td>
						<td><?= $uom->getGroupStr() ?></td>
						<td><?= $uom->getBaseStr() ?></td>
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