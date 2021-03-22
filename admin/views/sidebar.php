<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\cart\common\config\CartGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->core->getUser();
?>

<?php if( $core->hasModule( 'cart' ) && $user->isPermitted( CartGlobal::PERM_CART_ADMIN ) ) { ?>
	<div id="sidebar-cart" class="collapsible-tab has-children <?= $parent == 'sidebar-cart' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-balance-scale"></span></div>
			<div class="tab-title">UOMs</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-cart' ? 'expanded visible' : null ?>">
			<ul>
				<li class="uom <?= $child == 'uom' ? 'active' : null ?>"><?= Html::a( 'UOMs', [ '/cart/uom/all' ] ) ?></li>
				<li class="conversion <?= $child == 'conversion' ? 'active' : null ?>"><?= Html::a( 'Conversions', [ '/cart/conversion/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
