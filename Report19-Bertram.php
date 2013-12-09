<?
include("sessionCheck.php");
include("db.php");

$rsMaterialTransaction = $infosystem->Execute("SELECT `materialCode`, `transactionType`, `quantity` FROM `material_transactions`");
$rsMaterialList = $infosystem->Execute("SELECT `materialCode`, `materialName` FROM `material_list`");

$reportData = array();
while(!$rsMaterialTransaction->EOF) {
	list($xMaterialCode, $xTransactionType, $xQuantity) = $rsMaterialTransaction->fields;
	if($xTransactionType == 'd') $reportData[$xMaterialCode]['input'] += $xQuantity;
	else $reportData[$xMaterialCode]['output'] += $xQuantity;
	$rsMaterialTransaction->MoveNext();
}
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
		<th nowrap>Total Used<br>up to Date</th>
		<th>Total in<br>Laydown</th>
		<th>Total<br>Left</th>
		<th width="100%">&nbsp;</th>
	</tr>
	<?
	while(!$rsMaterialList->EOF) {
		list($yMaterialCode, $yMaterialName) = $rsMaterialList->fields;
		if(isset($reportData[$yMaterialCode])) {
			$input = $reportData[$yMaterialCode]['input'];
			$output = $reportData[$yMaterialCode]['output'];
			$left = $input - $output;
		}
	?>
	<tr>
		<td><?= $yMaterialCode ?></td>
		<td><?= $yMaterialName ?></td>
		<td align="right"><?= isset($reportData[$yMaterialCode]) ? number_format($output, 2) : '' ?></td>
		<td align="right"><?= isset($reportData[$yMaterialCode]) ? number_format($input, 2) : '' ?></td>
		<td align="right"<? if($left < 0) { ?> class="warning"<? } ?>><?= isset($reportData[$yMaterialCode]) ? number_format($left, 2) : '' ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsMaterialList->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>