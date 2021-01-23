<?php
// Yii Imports
use yii\helpers\Html;

$siteProperties = Yii::$app->controller->getSiteProperties();

$defaultIncludes = Yii::getAlias( '@cmsgears' ) . '/module-core/common/mails/views/includes';

$siteName	= Html::encode( $coreProperties->getSiteName() );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$logoUrl	= "$siteUrl/images/" . $siteProperties->getMailAvatar();
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

$name	= Html::encode( $user->getName() );
$email	= Html::encode( $user->email );

$invoiceLink = "$siteUrl/invoice/details?id=$invoice->id";
?>
<?php include "$defaultIncludes/header.php"; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear <?= $name ?>,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="Roboto, Arial, sans-serif">Your Invoice # <b><?= $invoice->code ?></b> has been updated. The details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Invoice Status: <?= $invoice->getStatusStr() ?>"></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Invoice Link: <a href="<?= $invoiceLink ?>">Click Here</a></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Total Amount (<?= $invoice->currency ?>): <?= $invoice->grandTotal ?></font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include "$defaultIncludes/footer.php"; ?>
