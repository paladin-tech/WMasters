<?
include("sessionCheck.php");
include("db.php");
include_once("adodb/toexport.inc.php");

$todayShort = date("y-m-d", mktime());
$today = date("Y/m/d", mktime());
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report 1', '{$_SESSION['username']}')");
list($updateTime) = $infosystem->Execute("SELECT MAX(`csv_import_date_time`) FROM `wells_construction`")->fields;

$rsReport = $infosystem->Execute("SELECT
	`zone`,
	`well_id`,
	`well_licence`,
	`permit`,
	`activity`,
	`location_lsd`,
	`length_nca`,
	`width_nca`,
	`length_ea`,
	`width_ea`,
	`letter_of_authority`,
	`designed_north`,
	`designed_east`,
	`start_date_of_entry`,
	`final_date_of_entry`,
	`flag_requested`,
	`flagged`,
	`salvaged`,
	`mulched`,
	`bladed`,
	`ready_for_drilling`,
	`approved_by_drilling`,
	`spud`,
	`rr`,
	`logged`,
	`no_abandonment_required`,
	`permanent_installation`,
	`abandonment`,
	`ready_roll_back`,
	`roll_back_ready`,
	`reclaimed_except_vegetation`,
	`as_built`,
	`constructed_not_drilled`,
	`nill_entry_not_built`,
	`lease_length`,
	`lease_width`,
	`lease_salvaged`,
	`lease_remote_sump`,
	`lease_snow_fill`,
	`log_gps_north_1`,
	`log_gps_east_1`,
	`log_conifer_1`,
	`log_volume_1`,
	`log_gps_north_2`,
	`log_gps_east_2`,
	`log_conifer_2`,
	`log_volume_2`,
	`location1_number_of_rig_mats`,
	`location1_gps_north`,
	`location1_gps_east`,
	`need_as_built_BASE`,
	`date_BASE`,
	`gps_north_BASE`,
	`gps_east_BASE`,
	`EL_BASE`,
	`GL_BASE`,
	`need_as_built_A`,
	`date_A`,
	`gps_north_A`,
	`gps_east_A`,
	`EL_A`,
	`GL_A`,
	`need_as_built_B`,
	`date_B`,
	`gps_north_B`,
	`gps_east_B`,
	`EL_B`,
	`GL_B`,
	`need_as_built_C`,
	`date_C`,
	`gps_north_C`,
	`gps_east_C`,
	`EL_C`,
	`GL_C`,
	`need_as_built_D`,
	`date_D`,
	`gps_north_D`,
	`gps_east_D`,
	`EL_D`,
	`GL_D`,
	`need_as_built_O1`,
	`date_O1`,
	`gps_north_O1`,
	`gps_east_O1`,
	`EL_O1`,
	`GL_O1`,
	`need_as_built_O2`,
	`date_O2`,
	`gps_north_O2`,
	`gps_east_O2`,
	`EL_O2`,
	`GL_O2`,
	`desc_O2`,
	`need_as_built_O3`,
	`date_O3`,
	`gps_north_O3`,
	`gps_east_O3`,
	`EL_O3`,
	`GL_O3`,
	`desc_O3`,
	`mainboard`
FROM `wells_construction`
ORDER BY `mainboard`,`zone`,`well_id`");

$report = 1;
$reportURL = "reports/Report{$report}-{$todayShort}.csv";

$fp = fopen("{$reportURL}", "w");
if($fp) {
	rs2csvfile($rsReport, $fp);
	fclose($fp);
}
//mail("predragl@wmasters.ca", "WM Digital - Report #{$report}, {$today}", "Please find Report #{$report} created on {$today} on the following URL:\r\n\r\nhttp://wmasters.d-zine.ca/{$reportURL}", "From: WM Digital <admin@wellsitemasters.com>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report <?=$report?> - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="formHeader">
		<p class="formInfo"></p>
		<h2 class="formInfo">WM Digital</h2>
		<p class="formInfo">Last Drilling Update: <?=$updateTime?></p>
</div>
<table cellspacing="1" cellpadding="3">
    <tr>
        <td>Zone</td>
        <td>WELL ID (Lease ID)</td>
        <td>Well Licence</td>
        <td>Permit</td>
        <td>Lease Activity</td>
        <td>Location LSD</td>
        <td>New Cut Access Length [m]</td>
        <td>New Cut Access Width [m]</td>
        <td>Existing Access Length [m]</td>
        <td>Existing Access Width [m]</td>
        <td>Letter of Authority</td>
        <td>Designed North</td>
        <td>Designed East</td>
        <td>Start Date of Entry</td>
        <td>Final Date of Entry</td>
        <td>Flag Requested</td>
        <td>Flag Done</td>
        <td>Salvaged</td>
        <td>Mulched</td>
        <td>Bladed</td>
        <td>Construction Completed</td>
        <td>Rig Ready</td>
        <td>Last Spud</td>
        <td>Last RR</td>
        <td>Last Logged</td>
        <td>No Abandonment Required</td>
        <td>Permanent Installation</td>
        <td>Last Abandonment</td>
        <td>Lease Release</td>
        <td>Roll Back Ready</td>
        <td>Rolled Back</td>
        <td>As Built Date</td>
        <td>Constructed - Not Drilled</td>
        <td>Nil Entry - Not Built</td>
        <td>Lease Length [m]</td>
        <td>Lease Width [m]</td>
        <td>Lease Salvaged</td>
        <td>Lease Remote Sump</td>
        <td>Lease Snow Fill</td>
        <td>GPS N</td>
        <td>GPS E</td>
        <td>Conifer / Deciduous</td>
        <td>Volume [m3]</td>
        <td>GPS N</td>
        <td>GPS E</td>
        <td>Conifer / Deciduous</td>
        <td>Volume [m3]</td>
        <td>Number of Rig Mats</td>
        <td>GPS N</td>
        <td>GPS E</td>
        <td>Prestake Requested</td>
        <td>Prestake Completed Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Other Description</td>
        <td>Well Sufix</td>
        <td>Survey Request</td>
        <td>As Built Date</td>
        <td>As Built GPS N</td>
        <td>As Built GPS E</td>
        <td>EL</td>
        <td>GL</td>
        <td>Other Description</td>
        <td>MAINBOARD</td>
    </tr><?
	$rsReport->MoveFirst();
	while(!$rsReport->EOF) {
	list(	$x_zone,
			$x_well_id,
			$x_well_licence,
			$x_permit,
			$x_activity,
			$x_location_lsd,
			$x_length_nca,
			$x_width_nca,
			$x_length_ea,
			$x_width_ea,
			$x_letter_of_authority,
			$x_designed_north,
			$x_designed_east,
			$x_start_date_of_entry,
			$x_final_date_of_entry,
			$x_flag_requested,
			$x_flagged,
			$x_salvaged,
			$x_mulched,
			$x_bladed,
			$x_ready_for_drilling,
			$x_approved_by_drilling,
			$x_spud,
			$x_rr,
			$x_logged,
			$x_no_abandonment_required,
			$x_permanent_installation,
			$x_abandonment,
			$x_ready_roll_back,
			$x_roll_back_ready,
			$x_roll_back,
			$x_as_built,
			$x_constructed_not_drilled,
			$x_nill_entry_not_built,
			$x_lease_length,
			$x_lease_width,
			$x_lease_salvaged,
			$x_lease_remote_sump,
			$x_lease_snow_fill,
			$x_log_gps_north_1,
			$x_log_gps_east_1,
			$x_log_conifer_1,
			$x_log_volume_1,
			$x_log_gps_north_2,
			$x_log_gps_east_2,
			$x_log_conifer_2,
			$x_log_volume_2,
			$x_location1_number_of_rig_mats,
			$x_location1_gps_north,
			$x_location1_gps_east,
			$x_need_as_built_BASE,
			$x_date_BASE,
			$x_gps_north_BASE,
			$x_gps_east_BASE,
			$x_EL_BASE,
			$x_GL_BASE,
			$x_need_as_built_A,
			$x_date_A,
			$x_gps_north_A,
			$x_gps_east_A,
			$x_EL_A,
			$x_GL_A,
			$x_need_as_built_B,
			$x_date_B,
			$x_gps_north_B,
			$x_gps_east_B,
			$x_EL_B,
			$x_GL_B,
			$x_need_as_built_C,
			$x_date_C,
			$x_gps_north_C,
			$x_gps_east_C,
			$x_EL_C,
			$x_GL_C,
			$x_need_as_built_D,
			$x_date_D,
			$x_gps_north_D,
			$x_gps_east_D,
			$x_EL_D,
			$x_GL_D,
			$x_need_as_built_O1,
			$x_date_O1,
			$x_gps_north_O1,
			$x_gps_east_O1,
			$x_EL_O1,
			$x_GL_O1,
			$x_need_as_built_O2,
			$x_date_O2,
			$x_gps_north_O2,
			$x_gps_east_O2,
			$x_EL_O2,
			$x_GL_O2,
			$x_desc_O2,
			$x_need_as_built_O3,
			$x_date_O3,
			$x_gps_north_O3,
			$x_gps_east_O3,
			$x_EL_O3,
			$x_GL_O3,
			$x_desc_O3,
			$x_mainboard) = $rsReport->fields;
	?>
    <tr>
        <td><?=$x_zone?></td>
        <td><?=$x_well_id?></td>
        <td><?=$x_well_licence?></td>
        <td><?=$x_permit?></td>
        <td><?=$x_activity?></td>
        <td><?=$x_location_lsd?></td>
		<td><?=($x_length_nca!="0.00")?$x_length_nca:""?></td>
		<td><?=($x_width_nca!="0")?$x_width_nca:""?></td>
		<td><?=($x_length_ea!="0.00")?$x_length_ea:""?></td>
		<td><?=($x_width_ea!="0")?$x_width_ea:""?></td>
        <td><?=($x_letter_of_authority!="0000-00-00")?$x_letter_of_authority:""?></td>
		<td><?=($x_designed_north!="0.000")?$x_designed_north:""?></td>
		<td><?=($x_designed_east!="0.000")?$x_designed_east:""?></td>
		<td><?=($x_start_date_of_entry!="0000-00-00")?$x_start_date_of_entry:""?></td>
		<td><?=($x_final_date_of_entry!="0000-00-00")?$x_final_date_of_entry:""?></td>
		<td><?=($x_flag_requested!="0000-00-00")?$x_flag_requested:""?></td>
		<td><?=($x_flagged!="0000-00-00")?$x_flagged:""?></td>
		<td><?=($x_salvaged!="0000-00-00")?$x_salvaged:""?></td>
		<td><?=($x_mulched!="0000-00-00")?$x_mulched:""?></td>
		<td><?=($x_bladed!="0000-00-00")?$x_bladed:""?></td>
		<td><?=($x_ready_for_drilling!="0000-00-00")?$x_ready_for_drilling:""?></td>
		<td><?=($x_approved_by_drilling!="0000-00-00")?$x_approved_by_drilling:""?></td>
		<td><?=($x_spud!="0000-00-00 00:00:00")?$x_spud:""?></td>
		<td><?=($x_rr!="0000-00-00 00:00:00")?$x_rr:""?></td>
		<td><?=($x_logged!="0000-00-00 00:00:00")?$x_logged:""?></td>
		<td><?=$x_no_abandonment_required?></td>
		<td><?=($x_permanent_installation!="0000-00-00")?$x_permanent_installation:""?></td>
		<td><?=($x_abandonment!="0000-00-00 00:00:00")?$x_abandonment:""?></td>
		<td><?=($x_ready_roll_back!="0000-00-00")?$x_ready_roll_back:""?></td>
		<td><?=($x_roll_back_ready!="0000-00-00")?$x_roll_back_ready:""?></td>
		<td><?=($x_roll_back!="0000-00-00")?$x_roll_back:""?></td>
		<td><?=($x_as_built!="0000-00-00")?$x_as_built:""?></td>
		<td><?=($x_constructed_not_drilled!="0000-00-00")?$x_constructed_not_drilled:""?></td>
		<td><?=($x_nill_entry_not_built!="0000-00-00")?$x_nill_entry_not_built:""?></td>
		<td><?=($x_lease_length!="0.00")?$x_lease_length:""?></td>
		<td><?=($x_lease_width!="0")?$x_lease_width:""?></td>
        <td><?=$x_lease_salvaged?></td>
        <td><?=$x_lease_remote_sump?></td>
        <td><?=$x_lease_snow_fill?></td>
		<td><?=($x_log_gps_north_1!="0")?$x_log_gps_north_1:""?></td>
		<td><?=($x_log_gps_east_1!="0")?$x_log_gps_east_1:""?></td>
        <td><?=$x_log_conifer_1?></td>
		<td><?=($x_log_volume_1!="0.000")?$x_log_volume_1:""?></td>
		<td><?=($x_log_gps_north_2!="0")?$x_log_gps_north_2:""?></td>
		<td><?=($x_log_gps_east_2!="0")?$x_log_gps_east_2:""?></td>
        <td><?=$x_log_conifer_2?></td>
		<td><?=($x_log_volume_2!="0.000")?$x_log_volume_2:""?></td>
		<td><?=($x_location1_number_of_rig_mats!="0")?$x_location1_number_of_rig_mats:""?></td>
		<td><?=($x_location1_gps_north!="0")?$x_location1_gps_north:""?></td>
		<td><?=($x_location1_gps_east!="0")?$x_location1_gps_east:""?></td>
		<td><?=($x_need_as_built_BASE!="0000-00-00")?$x_need_as_built_BASE:""?></td>
		<td><?=($x_date_BASE!="0000-00-00")?$x_date_BASE:""?></td>
		<td><?=($x_gps_north_BASE!="0")?$x_gps_north_BASE:""?></td>
		<td><?=($x_gps_east_BASE!="0")?$x_gps_east_BASE:""?></td>
		<td><?=($x_EL_BASE!="0.000")?$x_EL_BASE:""?></td>
		<td><?=($x_GL_BASE!="0.000")?$x_GL_BASE:""?></td>
        <td>As Built</td>
		<td><?=($x_need_as_built_A!="0000-00-00")?$x_need_as_built_A:""?></td>
		<td><?=($x_date_A!="0000-00-00")?$x_date_A:""?></td>
		<td><?=($x_gps_north_A!="0")?$x_gps_north_A:""?></td>
		<td><?=($x_gps_east_A!="0")?$x_gps_east_A:""?></td>
		<td><?=($x_EL_A!="0.000")?$x_EL_A:""?></td>
		<td><?=($x_GL_A!="0.000")?$x_GL_A:""?></td>
        <td>A</td>
		<td><?=($x_need_as_built_B!="0000-00-00")?$x_need_as_built_B:""?></td>
		<td><?=($x_date_B!="0000-00-00")?$x_date_B:""?></td>
		<td><?=($x_gps_north_B!="0")?$x_gps_north_B:""?></td>
		<td><?=($x_gps_east_B!="0")?$x_gps_east_B:""?></td>
		<td><?=($x_EL_B!="0.000")?$x_EL_B:""?></td>
		<td><?=($x_GL_B!="0.000")?$x_GL_B:""?></td>
        <td>B</td>
		<td><?=($x_need_as_built_C!="0000-00-00")?$x_need_as_built_C:""?></td>
		<td><?=($x_date_C!="0000-00-00")?$x_date_C:""?></td>
		<td><?=($x_gps_north_C!="0")?$x_gps_north_C:""?></td>
		<td><?=($x_gps_east_C!="0")?$x_gps_east_C:""?></td>
		<td><?=($x_EL_C!="0.000")?$x_EL_C:""?></td>
		<td><?=($x_GL_C!="0.000")?$x_GL_C:""?></td>
        <td>C</td>
		<td><?=($x_need_as_built_D!="0000-00-00")?$x_need_as_built_D:""?></td>
		<td><?=($x_date_D!="0000-00-00")?$x_date_D:""?></td>
		<td><?=($x_gps_north_D!="0")?$x_gps_north_D:""?></td>
		<td><?=($x_gps_east_D!="0")?$x_gps_east_D:""?></td>
		<td><?=($x_EL_D!="0.000")?$x_EL_D:""?></td>
		<td><?=($x_GL_D!="0.000")?$x_GL_D:""?></td>
        <td>D</td>
		<td><?=($x_need_as_built_O1!="0000-00-00")?$x_need_as_built_O1:""?></td>
		<td><?=($x_date_O1!="0000-00-00")?$x_date_O1:""?></td>
		<td><?=($x_gps_north_O1!="0")?$x_gps_north_O1:""?></td>
		<td><?=($x_gps_east_O1!="0")?$x_gps_east_O1:""?></td>
		<td><?=($x_EL_O1!="0.000")?$x_EL_O1:""?></td>
		<td><?=($x_GL_O1!="0.000")?$x_GL_O1:""?></td>
        <td>O1</td>
		<td><?=($x_need_as_built_O2!="0000-00-00")?$x_need_as_built_O2:""?></td>
		<td><?=($x_date_O2!="0000-00-00")?$x_date_O2:""?></td>
		<td><?=($x_gps_north_O2!="0")?$x_gps_north_O2:""?></td>
		<td><?=($x_gps_east_O2!="0")?$x_gps_east_O2:""?></td>
		<td><?=($x_EL_O2!="0.000")?$x_EL_O2:""?></td>
		<td><?=($x_GL_O2!="0.000")?$x_GL_O2:""?></td>
        <td><?=$x_desc_O2?></td>
        <td>O2</td>
		<td><?=($x_need_as_built_O3!="0000-00-00")?$x_need_as_built_O3:""?></td>
		<td><?=($x_date_O3!="0000-00-00")?$x_date_O3:""?></td>
		<td><?=($x_gps_north_O3!="0")?$x_gps_north_O3:""?></td>
		<td><?=($x_gps_east_O3!="0")?$x_gps_east_O3:""?></td>
		<td><?=($x_EL_O3!="0.000")?$x_EL_O3:""?></td>
		<td><?=($x_GL_O3!="0.000")?$x_GL_O3:""?></td>
        <td><?=$x_desc_O3?></td>
        <td><?=$x_mainboard?></td>
     </tr><?
	$rsReport->MoveNext();
	} ?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>