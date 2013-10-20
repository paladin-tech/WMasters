<?
require("adodb/adodb.inc.php");
require("infosystem.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

$rsMudProduct = $infosystem->Execute("SELECT `well_id`, `mud_product`, `quantity` FROM `mud_products` ORDER BY `well_id`, `mud_product`");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Consumables for all the wells - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td>Well ID</td>
		<td>Mud Products</td>
		<td>Quantity</td>
		<td width="100%"></td>
	</tr>
	<?
	while(!$rsMudProduct->EOF) {
		list($well_id, $mud_product, $quantity) = $rsMudProduct->fields;
	?>
	<tr>
		<td nowrap><?= $well_id ?></td>
		<td nowrap align="center"><?= $mud_product ?></td>
		<td nowrap align="right"><?= $quantity ?></td>
		<td width="100%"></td>
	</tr><?
		$rsMudProduct->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>