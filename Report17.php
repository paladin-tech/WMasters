<?
include("sessionCheck.php");
include("db.php");

$rsDailyMud = $infosystem->Execute("SELECT `well_id`, `r1`, `r2`, `r3`, `r4`, `sump`, `quantity` FROM `daily_mud` ORDER BY `well_id`");
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
		<td>R1</td>
		<td>R2</td>
		<td>R3</td>
		<td>R4</td>
		<td>Sump</td>
		<td>Quantity</td>
		<td width="100%"></td>
	</tr>
	<?
	$sum = 0;
	while(!$rsDailyMud->EOF) {
		list($well_id, $r1, $r2, $r3, $r4, $sump, $quantity) = $rsDailyMud->fields;
		$r1 = ($r1 == 1) ? 'x' : '';
		$r2 = ($r2 == 1) ? 'x' : '';
		$r3 = ($r3 == 1) ? 'x' : '';
		$r4 = ($r4 == 1) ? 'x' : '';
	?>
	<tr>
		<td nowrap><?= $well_id ?></td>
		<td nowrap align="center"><?= $r1 ?></td>
		<td nowrap align="center"><?= $r2 ?></td>
		<td nowrap align="center"><?= $r3 ?></td>
		<td nowrap align="center"><?= $r4 ?></td>
		<td nowrap align="center"><?= $sump ?></td>
		<td nowrap align="right"><?= $quantity ?></td>
		<td width="100%"></td>
	</tr><?
		$rsDailyMud->MoveNext();
		$sum += $quantity;
	}
	?>
	<tr>
		<td colspan="6">Total</td>
		<td align="right"><?= number_format($sum, 3) ?></td>
		<td width="100%"></td>
	</tr>
</table>
<? include ('footer.inc'); ?>
</body>
</html>