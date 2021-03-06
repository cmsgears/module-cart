<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Delete UOM Conversion | ' . $coreProperties->getSiteTitle();

$conMap			= Yii::$app->factory->get( 'uomService' )->getMapForConversion();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete UOM Conversion</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-page' ] );?>

		<?= $form->field( $model, 'uomId' )->dropDownList( $conMap, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'targetId' )->dropDownList( $conMap, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'quantity' )->textInput( [ 'readonly' => 'true' ] ) ?>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel',  [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>