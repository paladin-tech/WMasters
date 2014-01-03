<?
include("sessionCheck.php");
include("db.php");

$wasteCategory = array('1' => 'Non Hazardous', '2' => 'Hazardous', '3' => 'Recyclables');

$rsWaste = $infosystem->Execute("SELECT w.`date`, wc.`contractor`, w.`siteId`, wt.`wasteType`, w.`quantity`, w.`manifest` FROM `wm_waste` w, `wm_waste_contractor` wc, `wm_waste_type` wt WHERE w.`contractorId` = wc.`contractorId` AND w.`wasteTypeId` = wt.`wasteTypeId` ORDER BY w.`date` DESC");
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
		<th>Date</th>
		<th>Description</th>
		<th>Contractor</th>
		<th>Kearl (t)</th>
		<th>Aspen (t)</th>
		<th>Manifest #</th>
		<th width="100%">&nbsp;</th>
	</tr>
	<?
	while(!$rsWaste->EOF) {
		list($xDate, $xContractor, $xSiteId, $xWasteType, $xQuantity, $xManifest) = $rsWaste->fields;
		$sum[$xSiteId] += $xQuantity;
	?>
	<tr>
		<td align="center"><?= $xDate ?></td>
		<td align="center"><?= $xWasteType ?></td>
		<td align="center"><?= $xContractor ?></td>
		<td align="right"><?= ($xSiteId == 1) ? $xQuantity : '' ?></td>
		<td align="right"><?= ($xSiteId == 2) ? $xQuantity : '' ?></td>
		<td><?= $xManifest ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsWaste->MoveNext();
	}
	?>
	<tr>
		<th colspan="3" align="right">Total</th>
		<th align="right"><?= number_format($sum[1], 2) ?></th>
		<th align="right"><?= number_format($sum[2], 2) ?></th>
		<th>&nbsp;</th>
		<th width="100%">&nbsp;</th>
	</tr>
</table>
<? include ('footer.inc'); ?>
</body>
</html>