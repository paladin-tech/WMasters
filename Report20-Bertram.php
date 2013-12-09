<?
include("sessionCheck.php");
include("db.php");

$rsMaterialTransaction = $infosystem->Execute("SELECT mt.`materialCode`, ml.`materialName`, mt.`quantity`, mt.`wellId`, mt.`date` FROM `material_transactions` mt, `material_list` ml WHERE mt.`materialCode` = ml.`materialCode` AND mt.`transactionType` = 'w' ORDER BY `date` DESC, ml.`materialName`");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Report #19 - Total Material Left In Yard - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		table td {
			white-space: nowrap;
		}
		.bold, th {
			font-weight: bold;
		}
	</style>
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<th>Code</th>
		<th>Material</th>
		<th>Quantity</th>
		<th>Well ID</th>
		<th>Date</th>
		<th width="100%">&nbsp;</th>
	</tr>
	<?
	while(!$rsMaterialTransaction->EOF) {
		list($yMaterialCode, $yMaterialName, $yQuantity, $yWellId, $yDate) = $rsMaterialTransaction->fields;
	?>
	<tr>
		<td><?= $yMaterialCode ?></td>
		<td><?= $yMaterialName ?></td>
		<td align="right"><?= number_format($yQuantity, 2) ?></td>
		<td><?= $yWellId ?></td>
		<td><?= $yDate ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsMaterialTransaction->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>