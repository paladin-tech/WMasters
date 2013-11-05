<?
include("sessionCheck.php");
include("db.php");

$rsWells = $infosystem->Execute("SELECT `well_id`, `log_gps_north_1`, `log_gps_east_1`, `log_conifer_1`, `log_volume_1`, `log_gps_north_2`, `log_gps_east_2`, `log_conifer_2`, `log_volume_2` FROM `wells_construction` WHERE `active` = 1");

// Data for old Report #2, now part of Report #15

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Temporary Log Deck Report - WM Digital System</title>
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
		<th>Well<br>ID</th>
		<th>GPS Coordinates<br>North</th>
		<th>GPS<br>East</th>
		<th>Conifer<br>Deciduous</th>
		<th>Volume<br>[m3]</th>
		<th width="100%">&nbsp;</th>
	</tr>
	<?
	while(!$rsWells->EOF) {
		list($well_id, $log_gps_north_1, $log_gps_east_1, $log_conifer_1, $log_volume_1, $log_gps_north_2, $log_gps_east_2, $log_conifer_2, $log_volume_2) = $rsWells->fields;
	?>
	<tr>
		<td><?= $well_id ?></td>
		<td><?= $log_gps_north_1 ?></td>
		<td><?= $log_gps_east_1 ?></td>
		<td><?= $log_conifer_1 ?></td>
		<td><?= $log_volume_1 ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td><?= $well_id ?></td>
		<td><?= $log_gps_north_2 ?></td>
		<td><?= $log_gps_east_2 ?></td>
		<td><?= $log_conifer_2 ?></td>
		<td><?= $log_volume_2 ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsWells->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>