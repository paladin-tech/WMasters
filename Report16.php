<?
include("sessionCheck.php");
include("db.php");

$rsDailyMud = $infosystem->Execute("SELECT `truck_id`, `date`, `well_id`, `sub_well_id`, `area`, `sump_number`, `quantity` FROM `wm_dailymud` ORDER BY `date`, `truck_id`, `well_id`");
$rsSubWell = $infosystem->Execute("SELECT `wellId` FROM `sub_wells`");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Mud Report per days - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td>Date</td>
		<td>Truck #</td>
		<td>Well ID</td>
		<?
		while(!$rsSubWell->EOF) {
			list($xSubWell) = $rsSubWell->fields;
		?>
		<td><?= $xSubWell ?></td>
		<?
			$rsSubWell->MoveNext();
		}
		?>
		<td>Area</td>
		<td>Sump</td>
		<td>Quantity</td>
		<td width="100%"></td>
	</tr>
	<?
	while(!$rsDailyMud->EOF) {
		list($truck_id, $date, $well_id, $sub_well_id, $area, $sump_number, $quantity) = $rsDailyMud->fields;
	?>
	<tr>
		<td nowrap><?= $date ?></td>
		<td nowrap><?= $truck_id ?></td>
		<td nowrap><?= $well_id ?></td>
		<?
		$rsSubWell->MoveFirst();
		while(!$rsSubWell->EOF) {
			list($xSubWell) = $rsSubWell->fields;
		?>
		<td align="center"><?= ($sub_well_id == $xSubWell) ? "x" : "" ?></td>
			<?
			$rsSubWell->MoveNext();
		}
		?>
		<td nowrap><?= $area ?></td>
		<td align="center" nowrap><?= $sump_number ?></td>
		<td nowrap><?= $quantity ?></td>
		<td width="100%"></td>
	</tr><?
		$rsDailyMud->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>