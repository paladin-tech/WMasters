<?
include("sessionCheck.php");
include("db.php");

// Data for Report #15
$rsWells = $infosystem->Execute("SELECT `well_id`, `well_licence`, `permit`, `activity`, `location_lsd`, `letter_of_authority`, `designed_north`, `designed_east`, `start_date_of_entry`, `flagged`, `salvaged`, `mulched`, `bladed`, `ready_for_drilling`, `approved_by_drilling`, `spud`, `rr`, `logged`, `abandonment`, `reclaimed_except_vegetation`, `constructed_not_drilled`, `nill_entry_not_built`, `length_nca`, `width_nca`, `length_ea`, `width_ea`, `lease_length`, `lease_width`, `lease_salvaged`, `lease_remote_sump`, `log_conifer_1`, `log_volume_1`, `log_conifer_2`, `log_volume_2`, `gps_north_BASE`, `gps_east_BASE`, `GL_BASE`, `final_as_built_BASE`, `gps_north_AS_BUILT`, `gps_east_AS_BUILT`, `GL_AS_BUILT`, `final_as_built_AS_BUILT`, `gps_north_R1`, `gps_east_R1`, `GL_R1`, `final_as_built_R1`, `gps_north_R2`, `gps_east_R2`, `GL_R2`, `final_as_built_R2`, `gps_north_R3`, `gps_east_R3`, `GL_R3`, `final_as_built_R3`, `gps_north_R4`, `gps_east_R4`, `GL_R4`, `final_as_built_R4` FROM `wells_construction` ORDER BY `well_id`");

// Data for old Report #2, now part of Report #15
$rsConHydro = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro` WHERE `water_licence` != ''");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>DSR Report - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		table td {
			white-space: nowrap;
		}
		tr.bold td, th {
			font-weight: bold;
		}
	</style>
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<th>WELL ID<br>(Lease ID)</th>
		<th>Well<br>Licence</th>
		<th>Permit</th>
		<th>Lease<br>Activity</th>
		<th>Location<br>LSD</th>
		<th>Letter of<br>Authority</th>
		<th>Designed<br>North</th>
		<th>Designed<br>East</th>
		<th>Start Date<br>of Entry</th>
		<th>Flag<br>Done</th>
		<th>Salvaged</th>
		<th>Mulched</th>
		<th>Bladed</th>
		<th>Construction<br>Completed</th>
		<th>Rig<br>Ready</th>
		<th>Spud</th>
		<th>Rig<br>Release</th>
		<th>Last<br>Logged</th>
		<th>Last<br>Abandonment</th>
		<th>Rolled<br>Back</th>
		<th>Constructed<br>Not Drilled</th>
		<th>Nil Entry<br>Not Built</th>
		<th>New Cut<br>Access Length [m]</th>
		<th>New Cut<br>Access Width [m]</th>
		<th>Existing<br>Access Length [m]</th>
		<th>Existing<br>Access Width [m]</th>
		<th>Lease<br>Length [m]</th>
		<th>Lease<br>Width [m]</th>
		<th>Lease<br>Salvaged</th>
		<th>Lease<br>Remote Sump</th>
		<th>Conifer<br>Deciduous</th>
		<th>Volume<br>[m3]</th>
		<th>Conifer<br>Deciduous</th>
		<th>Volume<br>[m3]</th>
		<th>As Built<br>GPS N</th>
		<th>As Built<br>GPS E</th>
		<th>GL</th>
	</tr>
	<?
	$sum1 = $sum2 = $sum3 = 0;
	while(!$rsWells->EOF) {
		list($well_id, $well_licence, $permit, $activity, $location_lsd, $letter_of_authority, $designed_north, $designed_east, $start_date_of_entry, $flagged, $salvaged, $mulched, $bladed, $ready_for_drilling, $approved_by_drilling, $spud, $rr, $logged, $abandonment, $reclaimed_except_vegetation, $constructed_not_drilled, $nill_entry_not_built, $length_nca, $width_nca, $length_ea, $width_ea, $lease_length, $lease_width, $lease_salvaged, $lease_remote_sump, $log_conifer_1, $log_volume_1, $log_conifer_2, $log_volume_2, $gps_north_BASE, $gps_east_BASE, $GL_BASE, $final_as_built_BASE, $gps_north_AS_BUILT, $gps_east_AS_BUILT, $GL_AS_BUILT, $final_as_built_AS_BUILT, $gps_north_R1, $gps_east_R1, $GL_R1, $final_as_built_R1, $gps_north_R2, $gps_east_R2, $GL_R2, $final_as_built_R2, $gps_north_R3, $gps_east_R3, $GL_R3, $final_as_built_R3, $gps_north_R4, $gps_east_R4, $GL_R4, $final_as_built_R4) = $rsWells->fields;
		$gpsNorth = $gpsEast = $GL = '';
		if($final_as_built_BASE == 1) {
			$gpsNorth = $gps_north_BASE;
			$gpsEast = $gps_east_BASE;
			$GL = $GL_BASE;
		}
		if($final_as_built_AS_BUILT == 1) {
			$gpsNorth = $gps_north_AS_BUILT;
			$gpsEast = $gps_east_AS_BUILT;
			$GL = $GL_AS_BUILT;
		}
		if($final_as_built_R1 == 1) {
			$gpsNorth = $gps_north_R1;
			$gpsEast = $gps_east_R1;
			$GL = $GL_R1;
		}
		if($final_as_built_R2 == 1) {
			$gpsNorth = $gps_north_R2;
			$gpsEast = $gps_east_R2;
			$GL = $GL_R2;
		}
		if($final_as_built_R3 == 1) {
			$gpsNorth = $gps_north_R3;
			$gpsEast = $gps_east_R3;
			$GL = $GL_R3;
		}
		if($final_as_built_R4 == 1) {
			$gpsNorth = $gps_north_R4;
			$gpsEast = $gps_east_R4;
			$GL = $GL_R4;
		}
	?>
	<tr>
		<td><?= $well_id ?></td>
		<td><?= $well_licence ?></td>
		<td><?= $permit ?></td>
		<td><?= $activity ?></td>
		<td><?= $location_lsd ?></td>
		<td><?= ($letter_of_authority != '0000-00-00') ? $letter_of_authority : '' ?></td>
		<td><?= $designed_north ?></td>
		<td><?= $designed_east ?></td>
		<td><?= ($start_date_of_entry != '0000-00-00') ? $start_date_of_entry : '' ?></td>
		<td><?= ($flagged != '0000-00-00') ? $flagged : '' ?></td>
		<td><?= ($salvaged != '0000-00-00') ? $salvaged : '' ?></td>
		<td><?= ($mulched != '0000-00-00') ? $mulched : '' ?></td>
		<td><?= ($bladed != '0000-00-00') ? $bladed : '' ?></td>
		<td><?= ($ready_for_drilling != '0000-00-00') ? $ready_for_drilling : '' ?></td>
		<td><?= ($approved_by_drilling != '0000-00-00') ? $approved_by_drilling : '' ?></td>
		<td><?= ($spud != '0000-00-00 00:00:00') ? $spud : '' ?></td>
		<td><?= ($rr != '0000-00-00 00:00:00') ? $rr : '' ?></td>
		<td><?= ($logged != '0000-00-00 00:00:00') ? $approved_by_drilling : '' ?></td>
		<td><?= ($abandonment != '0000-00-00 00:00:00') ? $abandonment : '' ?></td>
		<td><?= ($reclaimed_except_vegetation != '0000-00-00') ? $reclaimed_except_vegetation : '' ?></td>
		<td><?= ($constructed_not_drilled != '0000-00-00') ? $constructed_not_drilled : '' ?></td>
		<td><?= ($nill_entry_not_built != '0000-00-00') ? $nill_entry_not_built : '' ?></td>
		<td><?= $length_nca ?></td>
		<td><?= $width_nca ?></td>
		<td><?= $length_ea ?></td>
		<td><?= $width_ea ?></td>
		<td><?= $lease_length ?></td>
		<td><?= $lease_width ?></td>
		<td><?= $lease_salvaged ?></td>
		<td><?= $lease_remote_sump ?></td>
		<td><?= $log_conifer_1 ?></td>
		<td><?= $log_volume_1 ?></td>
		<td><?= $log_conifer_2 ?></td>
		<td><?= $log_volume_2 ?></td>
		<td><?= $gpsNorth ?></td>
		<td><?= $gpsEast ?></td>
		<td><?= $GL ?></td>
	</tr>
	<?
		$sum1 += $length_nca * $width_nca;
		$sum2 += $length_ea * $width_ea;
		$sum3 += $lease_length * $lease_width;
		$rsWells->MoveNext();
	}
	$sum4 = $sum1 + $sum2 + $sum3;
	?>
	<tr>
		<td colspan="22" class="bold">&nbsp;</td>
		<td colspan="2" class="bold">Total New Access</td>
		<td colspan="2" class="bold">Total Existing Access</td>
		<td colspan="2" class="bold">Total Leases</td>
		<td>&nbsp;</td>
		<td colspan="2" class="bold">Total Land Use</td>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="22">&nbsp;</td>
		<td class="bold"><?= number_format(($sum1 / 10000), 2) ?></td>
		<td class="bold">ha</td>
		<td class="bold"><?= number_format(($sum2 / 10000), 2) ?></td>
		<td class="bold">ha</td>
		<td class="bold"><?= number_format(($sum3 / 10000), 2) ?></td>
		<td class="bold">ha</td>
		<td>&nbsp;</td>
		<td class="bold"><?= number_format(($sum4 / 10000), 2) ?></td>
		<td class="bold">ha</td>
		<td colspan="6">&nbsp;</td>
	</tr>
</table>
<br>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<th>Water Licence # (TDL)</th>
		<th>Source ID</th>
		<th>Description</th>
		<th>Location LSD</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th>Total Licenced Volume (m3)</th>
		<th>Total Used to Date (m3)</th>
		<th width="100%">&nbsp;</th>
	</tr>
	<?
	$sum1 = $sum2 = 0;
	while(!$rsConHydro->EOF) {
		list($area, $source_number, $water_licence, $source_ID, $program_zone, $location_LSD, $start_date, $end_date, $total_licensed_volume) = $rsConHydro->fields;
		list($total_used_to_date) = $infosystem->Execute("SELECT SUM(loc_{$source_number}) FROM `water` WHERE `area` = '{$area}'")->fields;
		$sum1 += $total_licensed_volume;
		$sum2 += $total_used_to_date;
	?>
	<tr>
		<td><?= $water_licence ?></td>
		<td><?= $source_ID ?></td>
		<td><?= $program_zone ?></td>
		<td><?= $location_LSD ?></td>
		<td><?= ($start_date != '0000-00-00') ? $start_date : "" ?></td>
		<td><?= ($end_date != '0000-00-00') ? $end_date : "" ?></td>
		<td align="right"><?= number_format($total_licensed_volume, 2) ?></td>
		<td align="right"><?= number_format($total_used_to_date, 2) ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsConHydro->MoveNext();
	}
	?>
	<tr class="bold">
		<td colspan="6" align="right">Total All Sources</td>
		<td align="right"><?= number_format($sum1, 2) ?></td>
		<td align="right"><?= number_format($sum2, 2) ?></td>
		<td width="100%">&nbsp;</td>
	</tr>
</table>
<? include ('footer.inc'); ?>
</body>
</html>