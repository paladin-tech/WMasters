<?
include("sessionCheck.php");
include("db.php");

$rsDailyMud = $infosystem->Execute("SELECT `well_id`, `subwell_id`, `sump`, `cell`, `quantity` FROM `wm_dailymud` ORDER BY `well_id`");
$rsSubWell = $infosystem->Execute("SELECT `wellId` FROM `sub_wells`");
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
		<td>Sump</td>
		<td>Cell</td>
		<td>Quantity</td>
		<td width="100%"></td>
	</tr>
	<?
	$sum = 0;
	while(!$rsDailyMud->EOF) {
		list($well_id, $subwell_id, $sump, $cell, $quantity) = $rsDailyMud->fields;
	?>
	<tr>
		<td nowrap><?= $well_id ?></td>
		<?
		$rsSubWell->MoveFirst();
		while(!$rsSubWell->EOF) {
			list($xSubWell) = $rsSubWell->fields;
		?>
		<td align="center"><?= ($subwell_id == $xSubWell) ? "x" : "" ?></td>
		<?
			$rsSubWell->MoveNext();
		}
		?>
		<td nowrap align="center"><?= $sump ?></td>
		<td nowrap align="center"><?= $cell ?></td>
		<td nowrap align="right"><?= $quantity ?></td>
		<td width="100%"></td>
	</tr><?
		$rsDailyMud->MoveNext();
		$sum += $quantity;
	}
	?>
	<tr>
		<td colspan="8">Total</td>
		<td align="right"><?= number_format($sum, 3) ?></td>
		<td width="100%"></td>
	</tr>
</table>
<? include ('footer.inc'); ?>
</body>
</html>