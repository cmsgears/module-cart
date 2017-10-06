<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\cart\common\models\resources\Uom;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Delete UOM | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete UOM</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-page' ] );?>

		<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
		<?= $form->field( $model, 'code' )->textInput( [ 'readonly' => 'true' ] ) ?>
		<?= $form->field( $model, 'group' )->dropDownList( Uom::$groupMap, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'base' )->checkbox( [ 'disabled' => true ] ) ?>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel',  [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>