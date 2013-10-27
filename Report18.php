<?
require("adodb/adodb.inc.php");
require("infosystem.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

$rsMudProductList = $infosystem->Execute("SELECT `mud_product_id`, `mud_product` FROM `mud_product_list`");
$mudProductCount = $rsMudProductList->RecordCount();
$rsWells = $infosystem->Execute("SELECT DISTINCT `well_id` FROM `mud_products` ORDER BY `well_id`");

$rsMudProduct = $infosystem->Execute("SELECT `mud_product`, `well_id`, `quantity` FROM `mud_products`");

$report18 = array();
while(!$rsMudProduct->EOF) {
	list($mud_product, $well_id, $quantity) = $rsMudProduct->fields;
	$report18[$well_id][$mud_product] = $quantity;
	$rsMudProduct->MoveNext();
}

$i = 1;
while(!$rsMudProductList->EOF) {
	list($mud_product_id, $mud_product) = $rsMudProductList->fields;
	$report18ID[$i] = $mud_product_id;
	$report18ProductName[$i] = $mud_product;
	$rsMudProductList->MoveNext();
	$i++;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Mud Report per Wells - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td>&nbsp;</td>
		<td align="center" colspan="<?= $mudProductCount ?>">Mud Products</td>
		<td>&nbsp;</td>
		<td width="100%"></td>
	</tr>
	<tr>
		<td>Well ID</td>
		<?
		foreach($report18ProductName as $val) {
		?>
		<td><?= $val ?></td>
		<?
		}
		?>
		<td>Quantity</td>
		<td width="100%"></td>
	</tr>
	<?
	while(!$rsWells->EOF) {
		list($x_well_id) = $rsWells->fields;
		$sum = 0;
	?>
	<tr>
		<td nowrap><?= $x_well_id ?></td>
		<?
		foreach($report18ID as $mudID) {
			if(isset($report18[$x_well_id][$mudID])) {
				$sum += $report18[$x_well_id][$mudID];
		?>
		<td align="right"><?= $report18[$x_well_id][$mudID] ?></td>
		<?
			} else {
		?>
		<td>&nbsp;</td>
		<?
			}
		}
		?>
		<td align="right"><?= number_format($sum, 2) ?></td>
		<td width="100%"></td>
	</tr><?
		$rsWells->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>