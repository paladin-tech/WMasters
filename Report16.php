<?
include("sessionCheck.php");
include("db.php");

$rsDailyMud = $infosystem->Execute("SELECT `date`, `truck_id`, `well_id`, `r1`, `r2`, `r3`, `r4`, `sump`, `quantity` FROM `daily_mud` ORDER BY `date`, `truck_id`, `well_id`");
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
		<td>R1</td>
		<td>R2</td>
		<td>R3</td>
		<td>R4</td>
		<td>Sump</td>
		<td>Quantity</td>
		<td width="100%"></td>
	</tr>
	<?
	while(!$rsDailyMud->EOF) {
		list($date, $truck_id, $well_id, $r1, $r2, $r3, $r4, $sump, $quantity) = $rsDailyMud->fields;
	?>
	<tr>
		<td nowrap><?= $date ?></td>
		<td nowrap><?= $truck_id ?></td>
		<td nowrap><?= $well_id ?></td>
		<td nowrap><?= $r1 ?></td>
		<td nowrap><?= $r2 ?></td>
		<td nowrap><?= $r3 ?></td>
		<td nowrap><?= $r4 ?></td>
		<td nowrap><?= $sump ?></td>
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