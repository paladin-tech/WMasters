<?
include("sessionCheck.php");
include("db.php");

$wasteCategory = array('1' => 'Non Hazardous', '2' => 'Hazardous', '3' => 'Recyclables');

foreach($wasteCategory as $key => $value) {
	$rsWaste[$key] = $infosystem->Execute("SELECT wc.`contractor`, s.`site`, wt.`wasteType`, w.`quantity` FROM `wm_waste` w, `wm_waste_contractor` wc, `wm_site` s, `wm_waste_type` wt WHERE w.`contractorId` = wc.`contractorId` AND w.`siteId` = s.`siteId` AND w.`wasteTypeId` = wt.`wasteTypeId` AND wt.`wasteCategoryId` = {$key} ORDER BY w.`wasteTypeId`, w.`siteId`, w.`contractorId`");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Waste Report #1 - Material Removed from Site (by Material) - WM Digital System</title>
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
		<th>Description</th>
		<th>Site</th>
		<th>Contractor</th>
		<th nowrap>Quantity<br>Removed (t)</th>
		<th width="100%">&nbsp;</th>
	</tr>
<?
foreach($wasteCategory as $key => $value) {
?>
	<tr>
		<td colspan="4"><b><?= $value ?></b></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
	while(!$rsWaste[$key]->EOF) {
		list($xContractor, $xSite, $xWasteType, $xQuantity) = $rsWaste[$key]->fields;
		$sum += $xQuantity;
	?>
	<tr>
		<td><?= $xWasteType ?></td>
		<td><?= $xSite ?></td>
		<td><?= $xContractor ?></td>
		<td align="right"><?= number_format($xQuantity, 2) ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsWaste[$key]->MoveNext();
	}
}
?>
	<tr>
		<th colspan="3" align="right">Total</th>
		<th align="right"><?= number_format($sum, 2) ?></th>
		<th width="100%">&nbsp;</th>
	</tr>
</table>
<? include ('footer.inc'); ?>
</body>
</html>