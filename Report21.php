<?
require("adodb/adodb.inc.php");
require("infosystem.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

$rsWells = $infosystem->Execute("SELECT `well_id`, `location1_gps_north`, `location1_gps_east`, `location1_number_of_rig_mats` FROM `wells_construction` WHERE `active` = 1");

// Data for old Report #2, now part of Report #15

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Rig Mat Location Report - WM Digital System</title>
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
		<th>Number of<br>Rig Mats</th>
		<th width="100%">&nbsp;</th>
	</tr>
	<?
	while(!$rsWells->EOF) {
		list($well_id, $location1_gps_north, $location1_gps_east, $location1_number_of_rig_mats) = $rsWells->fields;
	?>
	<tr>
		<td><?= $well_id ?></td>
		<td><?= $location1_gps_north ?></td>
		<td><?= $location1_gps_east ?></td>
		<td><?= $location1_number_of_rig_mats ?></td>
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