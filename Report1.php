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
	`active`,
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
	`released_to_construction`,
	`roll_back_ready`,
	`rollback_complete`,
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
	`survey_requested_BASE`,
	`survey_completed_BASE`,
	`gps_north_BASE`,
	`gps_east_BASE`,
	`GL_BASE`,
	`TOC_BASE`,
	`survey_requested_AS_BUILT`,
	`survey_completed_AS_BUILT`,
	`gps_north_AS_BUILT`,
	`gps_east_AS_BUILT`,
	`GL_AS_BUILT`,
	`TOC_AS_BUILT`,
	`instrumentation_comments_AS_BUILT`,
	`general_comments_AS_BUILT`,
	`as_built_completed_AS_BUILT`,
	`final_as_built_AS_BUILT`,
	`survey_requested_R1`,
	`survey_completed_R1`,
	`gps_north_R1`,
	`gps_east_R1`,
	`GL_R1`,
	`TOC_R1`,
	`instrumentation_comments_R1`,
	`general_comments_R1`,
	`as_built_completed_R1`,
	`final_as_built_R1`,
	`survey_requested_R2`,
	`survey_completed_R2`,
	`gps_north_R2`,
	`gps_east_R2`,
	`GL_R2`,
	`TOC_R2`,
	`instrumentation_comments_R2`,
	`general_comments_R2`,
	`as_built_completed_R2`,
	`final_as_built_R2`,
	`survey_requested_R3`,
	`survey_completed_R3`,
	`gps_north_R3`,
	`gps_east_R3`,
	`GL_R3`,
	`TOC_R3`,
	`instrumentation_comments_R3`,
	`general_comments_R3`,
	`as_built_completed_R3`,
	`final_as_built_R3`,
	`survey_requested_R4`,
	`survey_completed_R4`,
	`gps_north_R4`,
	`gps_east_R4`,
	`GL_R4`,
	`TOC_R4`,
	`instrumentation_comments_R4`,
	`general_comments_R4`,
	`as_built_completed_R4`,
	`final_as_built_R4`,
	`mainboard`
FROM `wells_construction`
ORDER BY `mainboard`");

// Recordset transformed to array is used to fill the MU field of correspondent SubWell of Well record
$rsDailyMud = $infosystem->Execute("SELECT `well_id`, `sub_well_id`, SUM(`quantity`) FROM `wm_dailymud` GROUP BY `well_id`, `sub_well_id`;");
while(!$rsDailyMud->EOF) {
	list($xWellId, $xSubWellId, $xQuantity) = $rsDailyMud->fields;
	$MU[$xWellId][$xSubWellId] = $xQuantity;
	$rsDailyMud->MoveNext();
}

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
		<td>CONST WELLS</td>
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
		<td>Prepped</td>
		<td>Released to Drilling</td>
		<td>Accepted by Drilling</td>
		<td>Last Spud</td>
		<td>Last RR</td>
		<td>Last Logged</td>
		<td>No Abandonment Required</td>
		<td>Permanent Installation</td>
		<td>Last Abandonment</td>
		<td>Released to Construction</td>
		<td>Issued for Rollback</td>
		<td>Rollback Complete</td>
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
		<td>Well Sufix</td>
		<td>Survey Requested</td>
		<td>Survey Completed</td>
		<td>As Built GPS N</td>
		<td>As Built GPS E</td>
		<td>GL</td>
		<td>TOC</td>
		<td>Well Sufix</td>
		<td>Survey Requested</td>
		<td>Survey Completed</td>
		<td>As Built GPS N</td>
		<td>As Built GPS E</td>
		<td>GL</td>
		<td>TOC</td>
		<td>Instrumentation Comments</td>
		<td>General Comments</td>
		<td>As-Built Completed</td>
		<td>Final As-Built</td>
		<td>MU</td>
		<td>Well Sufix</td>
		<td>Survey Requested</td>
		<td>Survey Completed</td>
		<td>As Built GPS N</td>
		<td>As Built GPS E</td>
		<td>GL</td>
		<td>TOC</td>
		<td>Instrumentation Comments</td>
		<td>General Comments</td>
		<td>As-Built Completed</td>
		<td>Final As-Built</td>
		<td>MU</td>
		<td>Well Sufix</td>
		<td>Survey Requested</td>
		<td>Survey Completed</td>
		<td>As Built GPS N</td>
		<td>As Built GPS E</td>
		<td>GL</td>
		<td>TOC</td>
		<td>Instrumentation Comments</td>
		<td>General Comments</td>
		<td>As-Built Completed</td>
		<td>Final As-Built</td>
		<td>MU</td>
		<td>Well Sufix</td>
		<td>Survey Requested</td>
		<td>Survey Completed</td>
		<td>As Built GPS N</td>
		<td>As Built GPS E</td>
		<td>GL</td>
		<td>TOC</td>
		<td>Instrumentation Comments</td>
		<td>General Comments</td>
		<td>As-Built Completed</td>
		<td>Final As-Built</td>
		<td>MU</td>
		<td>Well Sufix</td>
		<td>Survey Requested</td>
		<td>Survey Completed</td>
		<td>As Built GPS N</td>
		<td>As Built GPS E</td>
		<td>GL</td>
		<td>TOC</td>
		<td>Instrumentation Comments</td>
		<td>General Comments</td>
		<td>As-Built Completed</td>
		<td>Final As-Built</td>
		<td>MU</td>
		<td>MAINBOARD</td>
    </tr><?
	$rsReport->MoveFirst();
	while(!$rsReport->EOF) {
	list(	$x_zone,
			$x_well_id,
			$x_active,
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
			$x_released_to_construction,
			$x_roll_back_ready,
			$x_rollback_complete,
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
			$x_survey_requested_BASE,
			$x_survey_completed_BASE,
			$x_gps_north_BASE,
			$x_gps_east_BASE,
			$x_GL_BASE,
			$x_TOC_BASE,
			$x_survey_requested_AS_BUILT,
			$x_survey_completed_AS_BUILT,
			$x_gps_north_AS_BUILT,
			$x_gps_east_AS_BUILT,
			$x_GL_AS_BUILT,
			$x_TOC_AS_BUILT,
			$x_instrumentation_comments_AS_BUILT,
			$x_general_comments_AS_BUILT,
			$x_as_built_completed_AS_BUILT,
			$x_final_as_built_AS_BUILT,
			$x_survey_requested_R1,
			$x_survey_completed_R1,
			$x_gps_north_R1,
			$x_gps_east_R1,
			$x_GL_R1,
			$x_TOC_R1,
			$x_instrumentation_comments_R1,
			$x_general_comments_R1,
			$x_as_built_completed_R1,
			$x_final_as_built_R1,
			$x_survey_requested_R2,
			$x_survey_completed_R2,
			$x_gps_north_R2,
			$x_gps_east_R2,
			$x_GL_R2,
			$x_TOC_R2,
			$x_instrumentation_comments_R2,
			$x_general_comments_R2,
			$x_as_built_completed_R2,
			$x_final_as_built_R2,
			$x_survey_requested_R3,
			$x_survey_completed_R3,
			$x_gps_north_R3,
			$x_gps_east_R3,
			$x_GL_R3,
			$x_TOC_R3,
			$x_instrumentation_comments_R3,
			$x_general_comments_R3,
			$x_as_built_completed_R3,
			$x_final_as_built_R3,
			$x_survey_requested_R4,
			$x_survey_completed_R4,
			$x_gps_north_R4,
			$x_gps_east_R4,
			$x_GL_R4,
			$x_TOC_R4,
			$x_instrumentation_comments_R4,
			$x_general_comments_R4,
			$x_as_built_completed_R4,
			$x_final_as_built_R4,
			$x_mainboard) = $rsReport->fields;
	?>
    <tr>
			<td><?=$x_zone?></td>
			<td><?=$x_well_id?></td>
			<td><?=$x_active?></td>
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
			<td><?=($x_released_to_construction!="0000-00-00")?$x_released_to_construction:""?></td>
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
			<td>Pre Stake</td>
			<td><?=($x_survey_requested_BASE!="0000-00-00")?$x_survey_requested_BASE:""?></td>
			<td><?=($x_survey_completed_BASE!="0000-00-00")?$x_survey_completed_BASE:""?></td>
			<td><?=($x_gps_north_BASE!="0")?$x_gps_north_BASE:""?></td>
			<td><?=($x_gps_east_BASE!="0")?$x_gps_east_BASE:""?></td>
			<td><?=($x_GL_BASE!="0.000")?$x_GL_BASE:""?></td>
			<td><?=($x_TOC_BASE!="0.000")?$x_TOC_BASE:""?></td>
			<td>As Built</td>
			<td><?=($x_survey_requested_AS_BUILT!="0000-00-00")?$x_survey_requested_AS_BUILT:""?></td>
			<td><?=($x_survey_completed_AS_BUILT!="0000-00-00")?$x_survey_completed_AS_BUILT:""?></td>
			<td><?=($x_gps_north_AS_BUILT!="0.000")?$x_gps_north_AS_BUILT:""?></td>
			<td><?=($x_gps_east_AS_BUILT!="0.000")?$x_gps_east_AS_BUILT:""?></td>
			<td><?=($x_GL_AS_BUILT!="0.000")?$x_GL_AS_BUILT:""?></td>
			<td><?=($x_TOC_AS_BUILT!="0.000")?$x_TOC_AS_BUILT:""?></td>
			<td><?=$x_instrumentation_comments_AS_BUILT?></td>
			<td><?=$x_general_comments_AS_BUILT?></td>
			<td><?=$x_as_built_completed_AS_BUILT?></td>
			<td><?=$x_final_as_built_AS_BUILT?></td>
			<td><?= isset($MU[$x_well_id]['AS_BUILT']) ? $MU[$x_well_id]['AS_BUILT'] : "" ?></td>
			<td>R1</td>
			<td><?=($x_survey_requested_R1!="0000-00-00")?$x_survey_requested_R1:""?></td>
			<td><?=($x_survey_completed_R1!="0000-00-00")?$x_survey_completed_R1:""?></td>
			<td><?=($x_gps_north_R1!="0.000")?$x_gps_north_R1:""?></td>
			<td><?=($x_gps_east_R1!="0.000")?$x_gps_east_R1:""?></td>
			<td><?=($x_GL_R1!="0.000")?$x_GL_R1:""?></td>
			<td><?=($x_TOC_R1!="0.000")?$x_TOC_R1:""?></td>
			<td><?=$x_instrumentation_comments_R1?></td>
			<td><?=$x_general_comments_R1?></td>
			<td><?=$x_as_built_completed_R1?></td>
			<td><?=$x_final_as_built_R1?></td>
			<td><?= isset($MU[$x_well_id]['R1']) ? $MU[$x_well_id]['R1'] : "" ?></td>
			<td>R2</td>
			<td><?=($x_survey_requested_R2!="0000-00-00")?$x_survey_requested_R2:""?></td>
			<td><?=($x_survey_completed_R2!="0000-00-00")?$x_survey_completed_R2:""?></td>
			<td><?=($x_gps_north_R2!="0.000")?$x_gps_north_R2:""?></td>
			<td><?=($x_gps_east_R2!="0.000")?$x_gps_east_R2:""?></td>
			<td><?=($x_GL_R2!="0.000")?$x_GL_R2:""?></td>
			<td><?=($x_TOC_R2!="0.000")?$x_TOC_R2:""?></td>
			<td><?=$x_instrumentation_comments_R2?></td>
			<td><?=$x_general_comments_R2?></td>
			<td><?=$x_as_built_completed_R2?></td>
			<td><?=$x_final_as_built_R2?></td>
			<td><?= isset($MU[$x_well_id]['R2']) ? $MU[$x_well_id]['R2'] : "" ?></td>
			<td>R3</td>
			<td><?=($x_survey_requested_R3!="0000-00-00")?$x_survey_requested_R3:""?></td>
			<td><?=($x_survey_completed_R3!="0000-00-00")?$x_survey_completed_R3:""?></td>
			<td><?=($x_gps_north_R3!="0.000")?$x_gps_north_R3:""?></td>
			<td><?=($x_gps_east_R3!="0.000")?$x_gps_east_R3:""?></td>
			<td><?=($x_GL_R3!="0.000")?$x_GL_R3:""?></td>
			<td><?=($x_TOC_R3!="0.000")?$x_TOC_R3:""?></td>
			<td><?=$x_instrumentation_comments_R3?></td>
			<td><?=$x_general_comments_R3?></td>
			<td><?=$x_as_built_completed_R3?></td>
			<td><?=$x_final_as_built_R3?></td>
			<td><?= isset($MU[$x_well_id]['R3']) ? $MU[$x_well_id]['R3'] : "" ?></td>
			<td>R4</td>
			<td><?=($x_survey_requested_R4!="0000-00-00")?$x_survey_requested_R4:""?></td>
			<td><?=($x_survey_completed_R4!="0000-00-00")?$x_survey_completed_R4:""?></td>
			<td><?=($x_gps_north_R4!="0.000")?$x_gps_north_R4:""?></td>
			<td><?=($x_gps_east_R4!="0.000")?$x_gps_east_R4:""?></td>
			<td><?=($x_GL_R4!="0.000")?$x_GL_R4:""?></td>
			<td><?=($x_TOC_R4!="0.000")?$x_TOC_R4:""?></td>
			<td><?=$x_instrumentation_comments_R4?></td>
			<td><?=$x_general_comments_R4?></td>
			<td><?=$x_as_built_completed_R4?></td>
			<td><?=$x_final_as_built_R4?></td>
			<td><?= isset($MU[$x_well_id]['R4']) ? $MU[$x_well_id]['R4'] : "" ?></td>
			<td><?=$x_mainboard?></td>
     </tr><?
	$rsReport->MoveNext();
	} ?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>