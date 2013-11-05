<?
require_once("xajax_core/xajaxAIO.inc.php");
$xajax = new xajax();
// $xajax->setFlag("debug", true);

$xajax->registerFunction("GetWellInfoConAccess");
$xajax->registerFunction("GetWellInfoConPerWell");
$xajax->registerFunction("GetWellInfoConstContr");
$xajax->registerFunction("GetInfoConCrossWater");
$xajax->registerFunction("GetUnitsForTruckType");
$xajax->registerFunction("GetWellInfoSurvey");
$xajax->registerFunction("GetWellInfoDrillingGeotech");

function GetWellInfoConAccess($well_id)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	$check = $infosystem->Execute("SELECT `well_id` FROM `con_access` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount()!=0) {
		list($x_permits, $x_name_main, $x_name_sec, $x_well_id, $x_area1_gps_north, $x_area1_gps_east, $x_area1_conifer, $x_area1_volume, $x_area2_gps_north, $x_area2_gps_east, $x_area2_conifer, $x_area2_volume, $x_area3_gps_north, $x_area3_gps_east, $x_area3_conifer, $x_area3_volume, $x_location1_gps_north, $x_location1_gps_east, $x_location1_number_of_rig_mats, $x_location2_gps_north, $x_location2_gps_east, $x_location2_number_of_rig_mats, $x_location3_gps_north, $x_location3_gps_east, $x_location3_number_of_rig_mats, $x_location4_gps_north, $x_location4_gps_east, $x_location4_number_of_rig_mats, $x_location5_gps_north, $x_location5_gps_east, $x_location5_number_of_rig_mats) = $infosystem->Execute("SELECT `permits`, `name_main`, `name_sec`, `well_id`, `area1_gps_north`, `area1_gps_east`, `area1_conifer`, `area1_volume`, `area2_gps_north`, `area2_gps_east`, `area2_conifer`, `area2_volume`, `area3_gps_north`, `area3_gps_east`, `area3_conifer`, `area3_volume`, `location1_gps_north`, `location1_gps_east`, `location1_number_of_rig_mats`, `location2_gps_north`, `location2_gps_east`, `location2_number_of_rig_mats`, `location3_gps_north`, `location3_gps_east`, `location3_number_of_rig_mats`, `location4_gps_north`, `location4_gps_east`, `location4_number_of_rig_mats`, `location5_gps_north`, `location5_gps_east`, `location5_number_of_rig_mats` FROM `con_access` WHERE `well_id` = '{$well_id}'")->fields;
	} else {
		$x_zone = $x_permits = $x_name_main = $x_name_sec = $x_length_nca = $x_width_nca = $x_length_ea = $x_width_ea = $x_surveyed = $x_salvaged = $x_mulched = $x_bladed = $x_completed = $x_as_built_notes = $x_reclaimed = $x_no_entry_not_built = $x_area1_gps_north = $x_area1_gps_east = $x_area1_conifer = $x_area1_volume = $x_area2_gps_north = $x_area2_gps_east = $x_area2_conifer = $x_area2_volume = $x_area3_gps_north = $x_area3_gps_east = $x_area3_conifer = $x_area3_volume = $x_location1_gps_north = $x_location1_gps_east = $x_location1_number_of_rig_mats = $x_location2_gps_north = $x_location2_gps_east = $x_location2_number_of_rig_mats = $x_location3_gps_north = $x_location3_gps_east = $x_location3_number_of_rig_mats = $x_location4_gps_north = $x_location4_gps_east = $x_location4_number_of_rig_mats = $x_location5_gps_north = $x_location5_gps_east = $x_location5_number_of_rig_mats = "";
	}
	list($x_zone, $x_length_nca, $x_width_nca, $x_length_ea, $x_width_ea) = $infosystem->Execute("SELECT `zone`, `length_nca`, `width_nca`, `length_ea`, `width_ea` FROM `wells` WHERE `well_id` = '{$well_id}'")->fields;

	$objResponse->assign("tdZone", "innerHTML", "{$x_zone}");
	$objResponse->assign("permit", "value", "{$x_permit}");
	$objResponse->assign("name_main", "value", "{$x_name_main}");
	$objResponse->assign("name_sec", "value", "{$x_name_sec}");
	$objResponse->assign("tdLengthNCA", "innerHTML", "{$x_length_nca}");
	$objResponse->assign("tdWidthNCA", "innerHTML", "{$x_width_nca}");
	$objResponse->assign("tdLengthEA", "innerHTML", "{$x_length_ea}");
	$objResponse->assign("tdWidthEA", "innerHTML", "{$x_width_ea}");
	$objResponse->assign("area1_gps_north", "value", "{$x_area1_gps_north}");
	$objResponse->assign("area1_gps_east", "value", "{$x_area1_gps_east}");
	$objResponse->assign("area1_conifer", "value", "{$x_area1_conifer}");
	$objResponse->assign("area1_volume", "value", "{$x_area1_volume}");
	$objResponse->assign("area2_gps_north", "value", "{$x_area2_gps_north}");
	$objResponse->assign("area2_gps_east", "value", "{$x_area2_gps_east}");
	$objResponse->assign("area2_conifer", "value", "{$x_area2_conifer}");
	$objResponse->assign("area2_volume", "value", "{$x_area2_volume}");
	$objResponse->assign("area3_gps_north", "value", "{$x_area3_gps_north}");
	$objResponse->assign("area3_gps_east", "value", "{$x_area3_gps_east}");
	$objResponse->assign("area3_conifer", "value", "{$x_area3_conifer}");
	$objResponse->assign("area3_volume", "value", "{$x_area3_volume}");
	$objResponse->assign("location1_gps_north", "value", "{$x_location1_gps_north}");
	$objResponse->assign("location1_gps_east", "value", "{$x_location1_gps_east}");
	$objResponse->assign("location1_number_of_rig_mats", "value", "{$x_location1_number_of_rig_mats}");
	$objResponse->assign("location2_gps_north", "value", "{$x_location2_gps_north}");
	$objResponse->assign("location2_gps_east", "value", "{$x_location2_gps_east}");
	$objResponse->assign("location2_number_of_rig_mats", "value", "{$x_location2_number_of_rig_mats}");
	$objResponse->assign("location3_gps_north", "value", "{$x_location3_gps_north}");
	$objResponse->assign("location3_gps_east", "value", "{$x_location3_gps_east}");
	$objResponse->assign("location3_number_of_rig_mats", "value", "{$x_location3_number_of_rig_mats}");
	$objResponse->assign("location4_gps_north", "value", "{$x_location4_gps_north}");
	$objResponse->assign("location4_gps_east", "value", "{$x_location4_gps_east}");
	$objResponse->assign("location4_number_of_rig_mats", "value", "{$x_location4_number_of_rig_mats}");
	$objResponse->assign("location5_gps_north", "value", "{$x_location5_gps_north}");
	$objResponse->assign("location5_gps_east", "value", "{$x_location5_gps_east}");
	$objResponse->assign("location5_number_of_rig_mats", "value", "{$x_location5_number_of_rig_mats}");

    return $objResponse;
}

function GetWellInfoConPerWell($well_id)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount()!=0) {
		list($x_well_id, $x_well_licence, $x_permit, $x_activity, $x_location_lsd, $x_length_nca, $x_width_nca, $x_length_ea, $x_width_ea, $x_letter_of_authority, $x_start_date_of_entry, $x_final_date_of_entry, $x_flag_requested, $x_flagged, $x_salvaged, $x_mulched, $x_bladed, $x_ready_for_drilling, $x_approved_by_drilling, $x_ready_roll_back, $x_roll_back_ready, $x_as_built, $x_date_A, $x_date_B, $x_date_C, $x_date_D, $x_date_O1, $x_date_O2, $x_date_O3, $x_reclaimed_except_vegetation, $x_constructed_not_drilled, $x_nill_entry_not_built, $x_lease_length, $x_lease_width, $x_lease_salvaged, $x_lease_remote_sump, $x_lease_snow_fill, $x_log_gps_north_1, $x_log_gps_east_1, $x_log_conifer_1, $x_log_volume_1, $x_log_gps_north_2, $x_log_gps_east_2, $x_log_conifer_2, $x_log_volume_2, $x_location1_gps_north, $x_location1_gps_east, $x_location1_number_of_rig_mats, $x_gps_north_BASE, $x_gps_east_BASE, $x_gps_north_A, $x_gps_east_A, $x_gps_north_B, $x_gps_east_B, $x_gps_north_C, $x_gps_east_C, $x_gps_north_D, $x_gps_east_D, $x_gps_north_O1, $x_gps_east_O1, $x_gps_north_O2, $x_gps_east_O2, $x_gps_north_O3, $x_gps_east_O3) = $infosystem->Execute("SELECT `well_id`, `well_licence`, `permit`, `activity`, `location_lsd`, `length_nca`, `width_nca`, `length_ea`, `width_ea`, `letter_of_authority`, `start_date_of_entry`, `final_date_of_entry`, `flag_requested`, `flagged`, `salvaged`, `mulched`, `bladed`, `ready_for_drilling`, `approved_by_drilling`, `ready_roll_back`, `roll_back_ready`, `date_BASE`, `date_A`, `date_B`, `date_C`, `date_D`, `date_O1`, `date_O2`, `date_O3`, `reclaimed_except_vegetation`, `constructed_not_drilled`, `nill_entry_not_built`, `lease_length`, `lease_width`, `lease_salvaged`, `lease_remote_sump`, `lease_snow_fill`, `log_gps_north_1`, `log_gps_east_1`, `log_conifer_1`, `log_volume_1`, `log_gps_north_2`, `log_gps_east_2`, `log_conifer_2`, `log_volume_2`, `location1_gps_north`, `location1_gps_east`, `location1_number_of_rig_mats`, `gps_north_BASE`, `gps_east_BASE`, `gps_north_A`, `gps_east_A`, `gps_north_B`, `gps_east_B`, `gps_north_C`, `gps_east_C`, `gps_north_D`, `gps_east_D`, `gps_north_O1`, `gps_east_O1`, `gps_north_O2`, `gps_east_O2`, `gps_north_O3`, `gps_east_O3` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;
		if($x_letter_of_authority=="0000-00-00") $x_letter_of_authority = "";
		if($x_start_date_of_entry=="0000-00-00") $x_start_date_of_entry = "";
		if($x_final_date_of_entry=="0000-00-00") $x_final_date_of_entry = "";
		if($x_flag_requested=="0000-00-00") $x_flag_requested = "";
		if($x_flagged=="0000-00-00") $x_flagged = "";
		if($x_salvaged=="0000-00-00") $x_salvaged = "";
		if($x_mulched=="0000-00-00") $x_mulched = "";
		if($x_bladed=="0000-00-00") $x_bladed = "";
		if($x_ready_for_drilling=="0000-00-00") $x_ready_for_drilling = "";
		if($x_approved_by_drilling=="0000-00-00") $x_approved_by_drilling = "";
		if($x_ready_roll_back=="0000-00-00") $x_ready_roll_back = "";
		if($x_roll_back_ready=="0000-00-00") $x_roll_back_ready = "";
		if($x_as_built=="0000-00-00") $x_as_built = "";
		if($x_date_A=="0000-00-00") $x_date_A= "";
		if($x_date_B=="0000-00-00") $x_date_B= "";
		if($x_date_C=="0000-00-00") $x_date_C= "";
		if($x_date_D=="0000-00-00") $x_date_D= "";
		if($x_date_O1=="0000-00-00") $x_date_O1= "";
		if($x_date_O2=="0000-00-00") $x_date_O2= "";
		if($x_date_O3=="0000-00-00") $x_date_O3= "";
		if($x_reclaimed_except_vegetation=="0000-00-00") $x_reclaimed_except_vegetation = "";
		if($x_constructed_not_drilled=="0000-00-00") $x_constructed_not_drilled = "";
		if($x_nill_entry_not_built=="0000-00-00") $x_nill_entry_not_built = "";
		if($x_gps_north_BASE=="0.000") $x_gps_north_BASE = "";
		if($x_gps_north_A=="0.000") $x_gps_north_A = "";
		if($x_gps_north_B=="0.000") $x_gps_north_B = "";
		if($x_gps_north_C=="0.000") $x_gps_north_C = "";
		if($x_gps_north_D=="0.000") $x_gps_north_D = "";
		if($x_gps_north_O1=="0.000") $x_gps_north_O1 = "";
		if($x_gps_north_O2=="0.000") $x_gps_north_O2 = "";
		if($x_gps_north_O3=="0.000") $x_gps_north_O3 = "";
		if($x_gps_east_BASE=="0.000") $x_gps_east_BASE = "";
		if($x_gps_east_A=="0.000") $x_gps_east_A = "";
		if($x_gps_east_B=="0.000") $x_gps_east_B = "";
		if($x_gps_east_C=="0.000") $x_gps_east_C = "";
		if($x_gps_east_D=="0.000") $x_gps_east_D = "";
		if($x_gps_east_O1=="0.000") $x_gps_east_O1 = "";
		if($x_gps_east_O2=="0.000") $x_gps_east_O2 = "";
		if($x_gps_east_O3=="0.000") $x_gps_east_O3 = "";
	} else {
		$x_well_licence = $x_permit = $x_activity = $x_location_lsd = $x_length_nca = $x_width_nca = $x_length_ea = $x_width_ea = $x_letter_of_authority = $x_start_date_of_entry = $x_final_date_of_entry = $x_flag_requested = $x_flagged = $x_salvaged = $x_mulched = $x_bladed = $x_ready_for_drilling = $x_approved_by_drilling = $x_ready_roll_back = $x_roll_back_ready = $x_as_built = $x_date_A = $x_date_B = $x_date_C = $x_date_D = $x_date_O1 = $x_date_O2 = $x_date_O3 = $x_reclaimed_except_vegetation = $x_constructed_not_drilled = $x_nill_entry_not_built = $x_lease_length = $x_lease_width = $x_lease_salvaged = $x_lease_remote_sump = $x_lease_snow_fill = $x_log_gps_north_1 = $x_log_gps_east_1 = $x_log_conifer_1 = $x_log_volume_1 = $x_log_gps_north_2 = $x_log_gps_east_2 = $x_log_conifer_2 = $x_log_volume_2 = $x_location1_gps_north = $x_location1_gps_east = $x_location1_number_of_rig_mats = $x_gps_north_BASE = $x_gps_north_A = $x_gps_north_B = $x_gps_north_C = $x_gps_north_D = $x_gps_north_O1 = $x_gps_north_O3 = $x_gps_north_O3 = $x_gps_east_BASE = $x_gps_east_A = $x_gps_east_B = $x_gps_east_C = $x_gps_east_D = $x_gps_east_O1 = $x_gps_east_O2 = $x_gps_east_O3 = $x_display_or_not = "";
	}
	$objResponse->assign("well_licence", "value", "{$x_well_licence}");
	$objResponse->assign("permit", "value", "{$x_permit}");
	$objResponse->assign("tdLeaseActivity", "innerHTML", "{$x_activity}");
	$objResponse->assign("tdLocationLSD", "innerHTML", "{$x_location_lsd}");
	$objResponse->assign("tdLengthNCA", "innerHTML", "{$x_length_nca}");
	$objResponse->assign("tdWidthNCA", "innerHTML", "{$x_width_nca}");
	$objResponse->assign("tdLengthEA", "innerHTML", "{$x_length_ea}");
	$objResponse->assign("tdWidthEA", "innerHTML", "{$x_width_ea}");
	$objResponse->assign("tdLetterOfAuthority", "innerHTML", "{$x_letter_of_authority}");
	$objResponse->assign("tdStartDateOfEntry", "innerHTML", "{$x_start_date_of_entry}");
	$objResponse->assign("tdFinalDateOfEntry", "innerHTML", "{$x_final_date_of_entry}");
	$objResponse->assign("flag_requested", "value", "{$x_flag_requested}");
	$objResponse->assign("tdFlagged", "innerHTML", "{$x_flagged}");
	$objResponse->assign("tdSalvaged", "innerHTML", "{$x_salvaged}");
	$objResponse->assign("tdMulched", "innerHTML", "{$x_mulched}");
	$objResponse->assign("tdBladed", "innerHTML", "{$x_bladed}");
	$objResponse->assign("tdConstructionCompleted", "innerHTML", "{$x_ready_for_drilling}");
	$objResponse->assign("tdApprovedByDrilling", "innerHTML", "{$x_approved_by_drilling}");
	$objResponse->assign("tdLeaseRelease", "innerHTML", "{$x_ready_roll_back}");
	$objResponse->assign("roll_back_ready", "value", "{$x_roll_back_ready}");
	$objResponse->assign("tdAsBuilt", "innerHTML", "{$x_as_built}");
	$objResponse->assign("tdDate_BASE", "innerHTML", "{$x_as_built}");
	$objResponse->assign("tdDate_A", "innerHTML", "{$x_date_A}");
	$objResponse->assign("tdDate_B", "innerHTML", "{$x_date_B}");
	$objResponse->assign("tdDate_C", "innerHTML", "{$x_date_C}");
	$objResponse->assign("tdDate_D", "innerHTML", "{$x_date_D}");
	$objResponse->assign("tdDate_O1", "innerHTML", "{$x_date_O1}");
	$objResponse->assign("tdDate_O2", "innerHTML", "{$x_date_O2}");
	$objResponse->assign("tdDate_O3", "innerHTML", "{$x_date_O3}");
	$objResponse->assign("tdRolledBack", "innerHTML", "{$x_reclaimed_except_vegetation}");
	$objResponse->assign("constructed_not_drilled", "value", "{$x_constructed_not_drilled}");
	$objResponse->assign("nill_entry_not_built", "value", "{$x_nill_entry_not_built}");
	$objResponse->assign("tdLength", "innerHTML", "{$x_lease_length}");
	$objResponse->assign("tdWidth", "innerHTML", "{$x_lease_width}");
	$objResponse->script("tdSalvaged", "innerHTML", "{$x_lease_salvaged}");
	$objResponse->assign("tdRemoteSump", "innerHTML", "{$x_lease_remote_sump}");
	$objResponse->assign("tdSnowFillWaterCrossing", "innerHTML", "{$x_lease_snow_fill}");
	$objResponse->assign("tdGPSCoordinatesNorth1", "innerHTML", "{$x_log_gps_north_1}");
	$objResponse->assign("tdGPSEast1", "innerHTML", "{$x_log_gps_east_1}");
	$objResponse->assign("tdConiferDeciduous1", "innerHTML", "{$x_log_conifer_1}");
	$objResponse->assign("tdVolume1", "innerHTML", "{$x_log_volume_1}");
	$objResponse->assign("tdGPSCoordinatesNorth2", "innerHTML", "{$x_log_gps_north_2}");
	$objResponse->assign("tdGPSEast2", "innerHTML", "{$x_log_gps_east_2}");
	$objResponse->assign("tdConiferDeciduous2", "innerHTML", "{$x_log_conifer_2}");
	$objResponse->assign("tdVolume2", "innerHTML", "{$x_log_volume_2}");
	$objResponse->assign("tdGPSCoordinatesNorth3", "innerHTML", "{$x_location1_gps_north}");
	$objResponse->assign("tdGPSEast3", "innerHTML", "{$x_location1_gps_east}");
	$objResponse->assign("tdNumberOfRigMats", "innerHTML", "{$x_location1_number_of_rig_mats}");
	$objResponse->assign("tdGPSNBASE", "innerHTML", "{$x_gps_north_BASE}");
	$objResponse->assign("tdGPSEBASE", "innerHTML", "{$x_gps_east_BASE}");
	$objResponse->assign("tdGPSNA", "innerHTML", "{$x_gps_north_A}");
	$objResponse->assign("tdGPSEA", "innerHTML", "{$x_gps_east_A}");
	$objResponse->assign("tdGPSNB", "innerHTML", "{$x_gps_north_B}");
	$objResponse->assign("tdGPSEB", "innerHTML", "{$x_gps_east_B}");
	$objResponse->assign("tdGPSNC", "innerHTML", "{$x_gps_north_C}");
	$objResponse->assign("tdGPSEC", "innerHTML", "{$x_gps_east_C}");
	$objResponse->assign("tdGPSND", "innerHTML", "{$x_gps_north_D}");
	$objResponse->assign("tdGPSED", "innerHTML", "{$x_gps_east_D}");
	$objResponse->assign("tdGPSNO1", "innerHTML", "{$x_gps_north_O1}");
	$objResponse->assign("tdGPSEO1", "innerHTML", "{$x_gps_east_O1}");
	$objResponse->assign("tdGPSNO2", "innerHTML", "{$x_gps_north_O2}");
	$objResponse->assign("tdGPSEO2", "innerHTML", "{$x_gps_east_O2}");
	$objResponse->assign("tdGPSNO3", "innerHTML", "{$x_gps_north_O3}");
	$objResponse->assign("tdGPSEO3", "innerHTML", "{$x_gps_east_O3}");
    return $objResponse;
}

function GetWellInfoConstContr($well_id)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount()!=0) {
		list($x_well_id, $x_well_licence, $x_permit, $x_activity, $x_location_lsd, $x_length_nca, $x_width_nca, $x_length_ea, $x_width_ea, $x_letter_of_authority, $x_start_date_of_entry, $x_final_date_of_entry, $x_flag_requested, $x_flagged, $x_salvaged, $x_mulched, $x_bladed, $x_ready_for_drilling, $x_approved_by_drilling, $x_ready_roll_back, $x_roll_back_ready, $x_as_built, $x_date_A, $x_date_B, $x_date_C, $x_date_D, $x_date_O1, $x_date_O2, $x_date_O3, $x_reclaimed_except_vegetation, $x_constructed_not_drilled, $x_nill_entry_not_built, $x_lease_length, $x_lease_width, $x_lease_salvaged, $x_lease_remote_sump, $x_lease_snow_fill, $x_log_gps_north_1, $x_log_gps_east_1, $x_log_conifer_1, $x_log_volume_1, $x_log_gps_north_2, $x_log_gps_east_2, $x_log_conifer_2, $x_log_volume_2, $x_location1_gps_north, $x_location1_gps_east, $x_location1_number_of_rig_mats, $x_gps_north_BASE, $x_gps_east_BASE, $x_gps_north_A, $x_gps_east_A, $x_gps_north_B, $x_gps_east_B, $x_gps_north_C, $x_gps_east_C, $x_gps_north_D, $x_gps_east_D, $x_gps_north_O1, $x_gps_east_O1, $x_gps_north_O2, $x_gps_east_O2, $x_gps_north_O3, $x_gps_east_O3, $x_condition, $survey_requested_R1, $survey_requested_R2, $survey_requested_R3, $survey_requested_R4) = $infosystem->Execute("SELECT `well_id`, `well_licence`, `permit`, `activity`, `location_lsd`, `length_nca`, `width_nca`, `length_ea`, `width_ea`, `letter_of_authority`, `start_date_of_entry`, `final_date_of_entry`, `flag_requested`, `flagged`, `salvaged`, `mulched`, `bladed`, `ready_for_drilling`, `approved_by_drilling`, `ready_roll_back`, `roll_back_ready`, `date_BASE`, `date_A`, `date_B`, `date_C`, `date_D`, `date_O1`, `date_O2`, `date_O3`, `reclaimed_except_vegetation`, `constructed_not_drilled`, `nill_entry_not_built`, `lease_length`, `lease_width`, `lease_salvaged`, `lease_remote_sump`, `lease_snow_fill`, `log_gps_north_1`, `log_gps_east_1`, `log_conifer_1`, `log_volume_1`, `log_gps_north_2`, `log_gps_east_2`, `log_conifer_2`, `log_volume_2`, `location1_gps_north`, `location1_gps_east`, `location1_number_of_rig_mats`, `gps_north_BASE`, `gps_east_BASE`, `gps_north_A`, `gps_east_A`, `gps_north_B`, `gps_east_B`, `gps_north_C`, `gps_east_C`, `gps_north_D`, `gps_east_D`, `gps_north_O1`, `gps_east_O1`, `gps_north_O2`, `gps_east_O2`, `gps_north_O3`, `gps_east_O3`, `condition`, `survey_requested_R1`, `survey_requested_R2`, `survey_requested_R3`, `survey_requested_R4` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;
		if($x_letter_of_authority=="0000-00-00") $x_letter_of_authority = "";
		if($x_start_date_of_entry=="0000-00-00") $x_start_date_of_entry = "";
		if($x_final_date_of_entry=="0000-00-00") $x_final_date_of_entry = "";
		if($x_flag_requested=="0000-00-00") $x_flag_requested = "";
		if($x_flagged=="0000-00-00") $x_flagged = "";
		if($x_salvaged=="0000-00-00") $x_salvaged = "";
		if($x_mulched=="0000-00-00") $x_mulched = "";
		if($x_bladed=="0000-00-00") $x_bladed = "";
		if($x_ready_for_drilling=="0000-00-00") $x_ready_for_drilling = "";
		if($x_approved_by_drilling=="0000-00-00") $x_approved_by_drilling = "";
		if($x_ready_roll_back=="0000-00-00") $x_ready_roll_back = "";
		if($x_roll_back_ready=="0000-00-00") $x_roll_back_ready = "";
		if($x_as_built=="0000-00-00") $x_as_built = "";
		if($x_date_A=="0000-00-00") $x_date_A= "";
		if($x_date_B=="0000-00-00") $x_date_B= "";
		if($x_date_C=="0000-00-00") $x_date_C= "";
		if($x_date_D=="0000-00-00") $x_date_D= "";
		if($x_date_O1=="0000-00-00") $x_date_O1= "";
		if($x_date_O2=="0000-00-00") $x_date_O2= "";
		if($x_date_O3=="0000-00-00") $x_date_O3= "";
		if($x_reclaimed_except_vegetation=="0000-00-00") $x_reclaimed_except_vegetation = "";
		if($x_constructed_not_drilled=="0000-00-00") $x_constructed_not_drilled = "";
		if($x_nill_entry_not_built=="0000-00-00") $x_nill_entry_not_built = "";
		if($x_gps_north_BASE=="0.000") $x_gps_north_BASE = "";
		if($x_gps_north_A=="0.000") $x_gps_north_A = "";
		if($x_gps_north_B=="0.000") $x_gps_north_B = "";
		if($x_gps_north_C=="0.000") $x_gps_north_C = "";
		if($x_gps_north_D=="0.000") $x_gps_north_D = "";
		if($x_gps_north_O1=="0.000") $x_gps_north_O1 = "";
		if($x_gps_north_O2=="0.000") $x_gps_north_O2 = "";
		if($x_gps_north_O3=="0.000") $x_gps_north_O3 = "";
		if($x_gps_east_BASE=="0.000") $x_gps_east_BASE = "";
		if($x_gps_east_A=="0.000") $x_gps_east_A = "";
		if($x_gps_east_B=="0.000") $x_gps_east_B = "";
		if($x_gps_east_C=="0.000") $x_gps_east_C = "";
		if($x_gps_east_D=="0.000") $x_gps_east_D = "";
		if($x_gps_east_O1=="0.000") $x_gps_east_O1 = "";
		if($x_gps_east_O2=="0.000") $x_gps_east_O2 = "";
		if($x_gps_east_O3=="0.000") $x_gps_east_O3 = "";
	} else {
		$x_well_licence = $x_permit = $x_activity = $x_location_lsd = $x_length_nca = $x_width_nca = $x_length_ea = $x_width_ea = $x_letter_of_authority = $x_start_date_of_entry = $x_final_date_of_entry = $x_flag_requested = $x_flagged = $x_salvaged = $x_mulched = $x_bladed = $x_ready_for_drilling = $x_approved_by_drilling = $x_ready_roll_back = $x_roll_back_ready = $x_as_built = $x_date_A = $x_date_B = $x_date_C = $x_date_D = $x_date_O1 = $x_date_O2 = $x_date_O3 = $x_reclaimed_except_vegetation = $x_constructed_not_drilled = $x_nill_entry_not_built = $x_lease_length = $x_lease_width = $x_lease_salvaged = $x_lease_remote_sump = $x_lease_snow_fill = $x_log_gps_north_1 = $x_log_gps_east_1 = $x_log_conifer_1 = $x_log_volume_1 = $x_log_gps_north_2 = $x_log_gps_east_2 = $x_log_conifer_2 = $x_log_volume_2 = $x_location1_gps_north = $x_location1_gps_east = $x_location1_number_of_rig_mats = $x_gps_north_BASE = $x_gps_north_A = $x_gps_north_B = $x_gps_north_C = $x_gps_north_D = $x_gps_north_O1 = $x_gps_north_O3 = $x_gps_north_O3 = $x_gps_east_BASE = $x_gps_east_A = $x_gps_east_B = $x_gps_east_C = $x_gps_east_D = $x_gps_east_O1 = $x_gps_east_O2 = $x_gps_east_O3 = $x_display_or_not = $survey_requested_R1 = $survey_requested_R2 = $survey_requested_R3 = $survey_requested_R4 = "";
	}
	$objResponse->assign("well_licence", "value", "{$x_well_licence}");
	$objResponse->assign("permit", "value", "{$x_permit}");
	$objResponse->assign("tdLeaseActivity", "innerHTML", "{$x_activity}");
	$objResponse->assign("tdLocationLSD", "innerHTML", "{$x_location_lsd}");
	$objResponse->assign("tdLengthNCA", "innerHTML", "{$x_length_nca}");
	$objResponse->assign("tdWidthNCA", "innerHTML", "{$x_width_nca}");
	$objResponse->assign("tdLengthEA", "innerHTML", "{$x_length_ea}");
	$objResponse->assign("tdWidthEA", "innerHTML", "{$x_width_ea}");
	$objResponse->assign("tdLetterOfAuthority", "innerHTML", "{$x_letter_of_authority}");
	$objResponse->assign("tdStartDateOfEntry", "innerHTML", "{$x_start_date_of_entry}");
	$objResponse->assign("tdFinalDateOfEntry", "innerHTML", "{$x_final_date_of_entry}");
	$objResponse->assign("tdFlagRequested", "innerHTML", "{$x_flag_requested}");
	$objResponse->assign("tdFlagged", "innerHTML", "{$x_flagged}");
	$objResponse->assign("salvaged", "value", "{$x_salvaged}");
	$objResponse->assign("mulched", "value", "{$x_mulched}");
	$objResponse->assign("bladed", "value", "{$x_bladed}");
	$objResponse->assign("ready_for_drilling", "value", "{$x_ready_for_drilling}");
	$objResponse->assign("tdApprovedByDrilling", "innerHTML", "{$x_approved_by_drilling}");
	$objResponse->assign("tdLeaseRelease", "innerHTML", "{$x_ready_roll_back}");
	$objResponse->assign("tdRollBackReady", "innerHTML", "{$x_roll_back_ready}");
	$objResponse->assign("tdAsBuilt", "innerHTML", "{$x_as_built}");
	$objResponse->assign("tdDate_BASE", "innerHTML", "{$x_as_built}");
	$objResponse->assign("tdDate_A", "innerHTML", "{$x_date_A}");
	$objResponse->assign("tdDate_B", "innerHTML", "{$x_date_B}");
	$objResponse->assign("tdDate_C", "innerHTML", "{$x_date_C}");
	$objResponse->assign("tdDate_D", "innerHTML", "{$x_date_D}");
	$objResponse->assign("tdDate_O1", "innerHTML", "{$x_date_O1}");
	$objResponse->assign("tdDate_O2", "innerHTML", "{$x_date_O2}");
	$objResponse->assign("tdDate_O3", "innerHTML", "{$x_date_O3}");
	$objResponse->assign("reclaimed_except_vegetation", "value", "{$x_reclaimed_except_vegetation}");
	$objResponse->assign("tdConstructedNotDrilled", "innerHTML", "{$x_constructed_not_drilled}");
	$objResponse->assign("tdNilEntryNotBuilt", "innerHTML", "{$x_nill_entry_not_built}");
	$objResponse->assign("lease_length", "value", "{$x_lease_length}");
	$objResponse->assign("lease_width", "value", "{$x_lease_width}");
	$objResponse->script("setSelectedIndex('lease_salvaged', '{$x_lease_salvaged}')");
	$objResponse->assign("lease_remote_sump", "value", "{$x_lease_remote_sump}");
	$objResponse->script("setSelectedIndex('lease_snow_fill', '{$x_lease_snow_fill}')");
	$objResponse->assign("log_gps_north_1", "value", "{$x_log_gps_north_1}");
	$objResponse->assign("log_gps_east_1", "value", "{$x_log_gps_east_1}");
	$objResponse->script("setSelectedIndex('log_conifer_1', '{$x_log_conifer_1}')");
	$objResponse->assign("log_volume_1", "value", "{$x_log_volume_1}");
	$objResponse->assign("log_gps_north_2", "value", "{$x_log_gps_north_2}");
	$objResponse->assign("log_gps_east_2", "value", "{$x_log_gps_east_2}");
	$objResponse->script("setSelectedIndex('log_conifer_2', '{$x_log_conifer_2}')");
	$objResponse->assign("log_volume_2", "value", "{$x_log_volume_2}");
	$objResponse->assign("location1_gps_north", "value", "{$x_location1_gps_north}");
	$objResponse->assign("location1_gps_east", "value", "{$x_location1_gps_east}");
	$objResponse->assign("location1_number_of_rig_mats", "value", "{$x_location1_number_of_rig_mats}");
	$objResponse->assign("tdGPSNBASE", "innerHTML", "{$x_gps_north_BASE}");
	$objResponse->assign("tdGPSEBASE", "innerHTML", "{$x_gps_east_BASE}");
	$objResponse->assign("tdGPSNA", "innerHTML", "{$x_gps_north_A}");
	$objResponse->assign("tdGPSEA", "innerHTML", "{$x_gps_east_A}");
	$objResponse->assign("tdGPSNB", "innerHTML", "{$x_gps_north_B}");
	$objResponse->assign("tdGPSEB", "innerHTML", "{$x_gps_east_B}");
	$objResponse->assign("tdGPSNC", "innerHTML", "{$x_gps_north_C}");
	$objResponse->assign("tdGPSEC", "innerHTML", "{$x_gps_east_C}");
	$objResponse->assign("tdGPSND", "innerHTML", "{$x_gps_north_D}");
	$objResponse->assign("tdGPSED", "innerHTML", "{$x_gps_east_D}");
	$objResponse->assign("tdGPSNO1", "innerHTML", "{$x_gps_north_O1}");
	$objResponse->assign("tdGPSEO1", "innerHTML", "{$x_gps_east_O1}");
	$objResponse->assign("tdGPSNO2", "innerHTML", "{$x_gps_north_O2}");
	$objResponse->assign("tdGPSEO2", "innerHTML", "{$x_gps_east_O2}");
	$objResponse->assign("tdGPSNO3", "innerHTML", "{$x_gps_north_O3}");
	$objResponse->assign("tdGPSEO3", "innerHTML", "{$x_gps_east_O3}");
    return $objResponse;
}

function GetWellInfoSurvey($well_id)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount()!=0) {
		list($x_well_id, $x_need_as_built_BASE, $x_need_as_built_A, $x_need_as_built_B, $x_need_as_built_C, $x_need_as_built_D, $x_need_as_built_O1, $x_need_as_built_O2, $x_need_as_built_O3, $x_date_BASE, $x_date_A, $x_date_B, $x_date_C, $x_date_D, $x_date_O1, $x_date_O2, $x_date_O3, $x_flag_requested, $x_flagged, $x_gps_north_BASE, $x_gps_north_A, $x_gps_north_B, $x_gps_north_C, $x_gps_north_D, $x_gps_north_O1, $x_gps_north_O2, $x_gps_north_O3, $x_gps_east_BASE, $x_gps_east_A, $x_gps_east_B, $x_gps_east_C, $x_gps_east_D, $x_gps_east_O1, $x_gps_east_O2, $x_gps_east_O3, $x_EL_BASE, $x_EL_A, $x_EL_B, $x_EL_C, $x_EL_D, $x_EL_O1, $x_EL_O2, $x_EL_O3, $x_GL_BASE, $x_GL_A, $x_GL_B, $x_GL_C, $x_GL_D, $x_GL_O1, $x_GL_O2, $x_GL_O3, $x_designed_north, $x_designed_east, $x_desc_O1, $x_desc_O2, $x_desc_O3, $x_condition) = $infosystem->Execute("SELECT `well_id`, `need_as_built_BASE`, `need_as_built_A`, `need_as_built_B`, `need_as_built_C`, `need_as_built_D`, `need_as_built_O1`, `need_as_built_O2`, `need_as_built_O3`, `date_BASE`, `date_A`, `date_B`, `date_C`, `date_D`, `date_O1`, `date_O2`, `date_O3`, `flag_requested`, `flagged`, `gps_north_BASE`, `gps_north_A`, `gps_north_B`, `gps_north_C`, `gps_north_D`, `gps_north_O1`, `gps_north_O2`, `gps_north_O3`, `gps_east_BASE`, `gps_east_A`, `gps_east_B`, `gps_east_C`, `gps_east_D`, `gps_east_O1`, `gps_east_O2`, `gps_east_O3`, `EL_BASE`, `EL_A`, `EL_B`, `EL_C`, `EL_D`, `EL_O1`, `EL_O2`, `EL_O3`, `GL_BASE`, `GL_A`, `GL_B`, `GL_C`, `GL_D`, `GL_O1`, `GL_O2`, `GL_O3`, `designed_north`, `designed_east`, `desc_O1`, `desc_O2`, `desc_O3`, `condition`  FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;
		if($x_need_as_built_BASE=="0000-00-00") $x_need_as_built_BASE = "";
		if($x_need_as_built_A=="0000-00-00") $x_need_as_built_A = "";
		if($x_need_as_built_B=="0000-00-00") $x_need_as_built_B = "";
		if($x_need_as_built_C=="0000-00-00") $x_need_as_built_C = "";
		if($x_need_as_built_D=="0000-00-00") $x_need_as_built_D = "";
		if($x_need_as_built_O1=="0000-00-00") $x_need_as_built_O1 = "";
		if($x_need_as_built_O2=="0000-00-00") $x_need_as_built_O2 = "";
		if($x_need_as_built_O3=="0000-00-00") $x_need_as_built_O3 = "";
		if($x_date_BASE=="0000-00-00") $x_date_BASE = "";
		if($x_date_A=="0000-00-00") $x_date_A = "";
		if($x_date_B=="0000-00-00") $x_date_B = "";
		if($x_date_C=="0000-00-00") $x_date_C = "";
		if($x_date_D=="0000-00-00") $x_date_D = "";
		if($x_date_O1=="0000-00-00") $x_date_O1 = "";
		if($x_date_O2=="0000-00-00") $x_date_O2 = "";
		if($x_date_O3=="0000-00-00") $x_date_O3 = "";
		if($x_flag_requested=="0000-00-00") $x_flag_requested = "";
		if($x_flagged=="0000-00-00") $x_flagged = "";
		if($x_gps_north_BASE=="0.000") $x_gps_north_BASE = "";
		if($x_gps_north_A=="0.000") $x_gps_north_A = "";
		if($x_gps_north_B=="0.000") $x_gps_north_B = "";
		if($x_gps_north_C=="0.000") $x_gps_north_C = "";
		if($x_gps_north_D=="0.000") $x_gps_north_D = "";
		if($x_gps_north_O1=="0.000") $x_gps_north_O1 = "";
		if($x_gps_north_O2=="0.000") $x_gps_north_O2 = "";
		if($x_gps_north_O3=="0.000") $x_gps_north_O3 = "";
		if($x_gps_east_BASE=="0.000") $x_gps_east_BASE = "";
		if($x_gps_east_A=="0.000") $x_gps_east_A = "";
		if($x_gps_east_B=="0.000") $x_gps_east_B = "";
		if($x_gps_east_C=="0.000") $x_gps_east_C = "";
		if($x_gps_east_D=="0.000") $x_gps_east_D = "";
		if($x_gps_east_O1=="0.000") $x_gps_east_O1 = "";
		if($x_gps_east_O2=="0.000") $x_gps_east_O2 = "";
		if($x_gps_east_O3=="0.000") $x_gps_east_O3 = "";
		if($x_EL_BASE=="0.000") $x_EL_BASE = "";
		if($x_EL_A=="0.000") $x_EL_A = "";
		if($x_EL_B=="0.000") $x_EL_B = "";
		if($x_EL_C=="0.000") $x_EL_C = "";
		if($x_EL_D=="0.000") $x_EL_D = "";
		if($x_EL_O1=="0.000") $x_EL_O1 = "";
		if($x_EL_O2=="0.000") $x_EL_O2 = "";
		if($x_EL_O3=="0.000") $x_EL_O3 = "";
		if($x_GL_BASE=="0.000") $x_GL_BASE = "";
		if($x_GL_A=="0.000") $x_GL_A = "";
		if($x_GL_B=="0.000") $x_GL_B = "";
		if($x_GL_C=="0.000") $x_GL_C = "";
		if($x_GL_D=="0.000") $x_GL_D = "";
		if($x_GL_O1=="0.000") $x_GL_O1 = "";
		if($x_GL_O2=="0.000") $x_GL_O2 = "";
		if($x_GL_O3=="0.000") $x_GL_O3 = "";
		$deltaNorthBase = abs($x_designed_north-$x_gps_north_BASE);
		$deltaNorthBase .= ($deltaNorthBase>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthA = abs($x_designed_north-$x_gps_north_A);
		$deltaNorthA .= ($deltaNorthA>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthB = abs($x_designed_north-$x_gps_north_B);
		$deltaNorthB .= ($deltaNorthB>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthC = abs($x_designed_north-$x_gps_north_C);
		$deltaNorthC .= ($deltaNorthC>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthD = abs($x_designed_north-$x_gps_north_D);
		$deltaNorthD .= ($deltaNorthD>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthO1 = abs($x_designed_north-$x_gps_north_D);
		$deltaNorthO1 .= ($deltaNorthO1>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthO2 = abs($x_designed_north-$x_gps_north_D);
		$deltaNorthO2 .= ($deltaNorthO2>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaNorthO3 = abs($x_designed_north-$x_gps_north_D);
		$deltaNorthO3 .= ($deltaNorthO3>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastBase = abs($x_designed_east-$x_gps_east_BASE);
		$deltaEastBase .= ($deltaEastBase>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastA = abs($x_designed_east-$x_gps_east_A);
		$deltaEastA .= ($deltaEastA>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastB = abs($x_designed_east-$x_gps_east_B);
		$deltaEastB .= ($deltaEastB>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastC = abs($x_designed_east-$x_gps_east_C);
		$deltaEastC .= ($deltaEastC>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastD = abs($x_designed_east-$x_gps_east_D);
		$deltaEastD .= ($deltaEastD>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastO1 = abs($x_designed_east-$x_gps_east_D);
		$deltaEastO1 .= ($deltaEastO1>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastO2 = abs($x_designed_east-$x_gps_east_D);
		$deltaEastO2 .= ($deltaEastO2>10)?" <font color='red'><b>!!</b></font>":"";
		$deltaEastO3 = abs($x_designed_east-$x_gps_east_D);
		$deltaEastO3 .= ($deltaEastO3>10)?" <font color='red'><b>!!</b></font>":"";
		if($x_condition==1) {
			$x_display_or_not = "none";
			} else {
			$x_display_or_not = "";
			}
	} else {
		$x_need_as_built_BASE = $x_need_as_built_A = $x_need_as_built_B = $x_need_as_built_C = $x_need_as_built_D = $x_need_as_built_O1 = $x_need_as_built_O2 = $x_need_as_built_O3 = $x_date_BASE = $x_date_A = $x_date_B = $x_date_C = $x_date_D = $x_date_O1 = $x_date_O2 = $x_date_O3 = $x_flag_requested = $x_flagged = $x_gps_north_BASE = $x_gps_north_A = $x_gps_north_B = $x_gps_north_C = $x_gps_north_D = $x_gps_north_O1 = $x_gps_north_O2 = $x_gps_north_O3 = $x_gps_east_BASE = $x_gps_east_A = $x_gps_east_B = $x_gps_east_C = $x_gps_east_D = $x_gps_east_O1 = $x_gps_east_O2 = $x_gps_east_O3 = $x_EL_BASE = $x_EL_A = $x_EL_B = $x_EL_C = $x_EL_D = $x_EL_O1 = $x_EL_O2 = $x_EL_O3 = $x_GL_BASE = $x_GL_A = $x_GL_B = $x_GL_C = $x_GL_D = $x_GL_O1 = $x_GL_O2 = $x_GL_O3 = $x_designed_north = $x_designed_east = $x_display_or_not = "";
	}
	$objResponse->assign("tdNeed_as_built_BASE", "innerHTML", "{$x_need_as_built_BASE}");
	$objResponse->assign("tdNeed_as_built_A", "innerHTML", "{$x_need_as_built_A}");
	$objResponse->assign("tdNeed_as_built_B", "innerHTML", "{$x_need_as_built_B}");
	$objResponse->assign("tdNeed_as_built_C", "innerHTML", "{$x_need_as_built_C}");
	$objResponse->assign("tdNeed_as_built_D", "innerHTML", "{$x_need_as_built_D}");
	$objResponse->assign("tdNeed_as_built_O1", "innerHTML", "{$x_need_as_built_O1}");
	$objResponse->assign("tdNeed_as_built_O2", "innerHTML", "{$x_need_as_built_O2}");
	$objResponse->assign("tdNeed_as_built_O3", "innerHTML", "{$x_need_as_built_O3}");
	$objResponse->assign("date_BASE", "value", "{$x_date_BASE}");
	$objResponse->assign("date_A", "value", "{$x_date_A}");
	$objResponse->assign("date_B", "value", "{$x_date_B}");
	$objResponse->assign("date_C", "value", "{$x_date_C}");
	$objResponse->assign("date_D", "value", "{$x_date_D}");
	$objResponse->assign("date_O1", "value", "{$x_date_O1}");
	$objResponse->assign("date_O2", "value", "{$x_date_O2}");
	$objResponse->assign("date_O3", "value", "{$x_date_O3}");
	$objResponse->assign("tdFlagRequested", "innerHTML", "{$x_flag_requested}");
	$objResponse->assign("flagged", "value", "{$x_flagged}");
	$objResponse->assign("gps_north_BASE", "value", "{$x_gps_north_BASE}");
	$objResponse->assign("gps_north_A", "value", "{$x_gps_north_A}");
	$objResponse->assign("gps_north_B", "value", "{$x_gps_north_B}");
	$objResponse->assign("gps_north_C", "value", "{$x_gps_north_C}");
	$objResponse->assign("gps_north_D", "value", "{$x_gps_north_D}");
	$objResponse->assign("gps_north_O1", "value", "{$x_gps_north_O1}");
	$objResponse->assign("gps_north_O2", "value", "{$x_gps_north_O2}");
	$objResponse->assign("gps_north_O3", "value", "{$x_gps_north_O3}");
	$objResponse->assign("gps_east_BASE", "value", "{$x_gps_east_BASE}");
	$objResponse->assign("gps_east_A", "value", "{$x_gps_east_A}");
	$objResponse->assign("gps_east_B", "value", "{$x_gps_east_B}");
	$objResponse->assign("gps_east_C", "value", "{$x_gps_east_C}");
	$objResponse->assign("gps_east_D", "value", "{$x_gps_east_D}");
	$objResponse->assign("gps_east_O1", "value", "{$x_gps_east_O1}");
	$objResponse->assign("gps_east_O2", "value", "{$x_gps_east_O2}");
	$objResponse->assign("gps_east_O3", "value", "{$x_gps_east_O3}");
	$objResponse->assign("EL_BASE", "value", "{$x_EL_BASE}");
	$objResponse->assign("EL_A", "value", "{$x_EL_A}");
	$objResponse->assign("EL_B", "value", "{$x_EL_B}");
	$objResponse->assign("EL_C", "value", "{$x_EL_C}");
	$objResponse->assign("EL_D", "value", "{$x_EL_D}");
	$objResponse->assign("EL_O1", "value", "{$x_EL_O1}");
	$objResponse->assign("EL_O2", "value", "{$x_EL_O2}");
	$objResponse->assign("EL_O3", "value", "{$x_EL_O3}");
	$objResponse->assign("GL_BASE", "value", "{$x_GL_BASE}");
	$objResponse->assign("GL_A", "value", "{$x_GL_A}");
	$objResponse->assign("GL_B", "value", "{$x_GL_B}");
	$objResponse->assign("GL_C", "value", "{$x_GL_C}");
	$objResponse->assign("GL_D", "value", "{$x_GL_D}");
	$objResponse->assign("GL_O1", "value", "{$x_GL_O1}");
	$objResponse->assign("GL_O2", "value", "{$x_GL_O2}");
	$objResponse->assign("GL_O3", "value", "{$x_GL_O3}");
	$objResponse->assign("tdDesignedNorth", "innerHTML", "{$x_designed_north}");
	$objResponse->assign("tdDesignedEast", "innerHTML", "{$x_designed_east}");
	$objResponse->assign("tdDesc01", "innerHTML", "{$x_desc_O1}");
	$objResponse->assign("tdDesc02", "innerHTML", "{$x_desc_O2}");
	$objResponse->assign("tdDesc03", "innerHTML", "{$x_desc_O3}");
	$objResponse->assign("tdDeltaNorthBase", "innerHTML", "{$deltaNorthBase}");
	$objResponse->assign("tdDeltaNorthA", "innerHTML", "{$deltaNorthA}");
	$objResponse->assign("tdDeltaNorthB", "innerHTML", "{$deltaNorthB}");
	$objResponse->assign("tdDeltaNorthC", "innerHTML", "{$deltaNorthC}");
	$objResponse->assign("tdDeltaNorthD", "innerHTML", "{$deltaNorthD}");
	$objResponse->assign("tdDeltaNorthO1", "innerHTML", "{$deltaNorthO1}");
	$objResponse->assign("tdDeltaNorthO2", "innerHTML", "{$deltaNorthO2}");
	$objResponse->assign("tdDeltaNorthO3", "innerHTML", "{$deltaNorthO3}");
	$objResponse->assign("tdDeltaEastBase", "innerHTML", "{$deltaEastBase}");
	$objResponse->assign("tdDeltaEastA", "innerHTML", "{$deltaEastA}");
	$objResponse->assign("tdDeltaEastB", "innerHTML", "{$deltaEastB}");
	$objResponse->assign("tdDeltaEastC", "innerHTML", "{$deltaEastC}");
	$objResponse->assign("tdDeltaEastD", "innerHTML", "{$deltaEastD}");
	$objResponse->assign("tdDeltaEastO1", "innerHTML", "{$deltaEastO1}");
	$objResponse->assign("tdDeltaEastO2", "innerHTML", "{$deltaEastO2}");
	$objResponse->assign("tdDeltaEastO3", "innerHTML", "{$deltaEastO3}");
	$objResponse->assign("display_or_not", "style.display", "{$x_display_or_not}");
    return $objResponse;
}

function GetWellInfoDrillingGeotech($well_id)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount()!=0) {
		list($x_well_id, $x_need_as_built_BASE, $x_need_as_built_A, $x_need_as_built_B, $x_need_as_built_C, $x_need_as_built_D, $x_need_as_built_O1, $x_need_as_built_O2, $x_need_as_built_O3, $x_date_BASE, $x_date_A, $x_date_B, $x_date_C, $x_date_D, $x_date_O1, $x_date_O2, $x_date_O3, $x_gps_north_BASE, $x_gps_north_A, $x_gps_north_B, $x_gps_north_C, $x_gps_north_D, $x_gps_north_O1, $x_gps_north_O2, $x_gps_north_O3, $x_ready_for_drilling, $x_approved_by_drilling, $x_gps_east_BASE, $x_gps_east_A, $x_gps_east_B, $x_gps_east_C, $x_gps_east_D, $x_gps_east_O1, $x_gps_east_O2, $x_gps_east_O3, $x_EL_BASE, $x_EL_A, $x_EL_B, $x_EL_C, $x_EL_D, $x_EL_O1, $x_EL_O2, $x_EL_O3, $x_GL_BASE, $x_GL_A, $x_GL_B, $x_GL_C, $x_GL_D, $x_GL_O1, $x_GL_O2, $x_GL_O3, $x_spud, $x_rr, $x_logged, $x_no_abandonment_required, $x_permanent_installation, $x_abandonment, $x_ready_roll_back, $x_roll_back, $x_desc_O1, $x_desc_O2, $x_desc_O3) = $infosystem->Execute("SELECT `well_id`, `need_as_built_BASE`, `need_as_built_A`, `need_as_built_B`, `need_as_built_C`, `need_as_built_D`, `need_as_built_O1`, `need_as_built_O2`, `need_as_built_O3`, `date_BASE`, `date_A`, `date_B`, `date_C`, `date_D`, `date_O1`, `date_O2`, `date_O3`, `gps_north_BASE`, `gps_north_A`, `gps_north_B`, `gps_north_C`, `gps_north_D`, `gps_north_O1`, `gps_north_O2`, `gps_north_O3`, `ready_for_drilling`, `approved_by_drilling`, `gps_east_BASE`, `gps_east_A`, `gps_east_B`, `gps_east_C`, `gps_east_D`, `gps_east_O1`, `gps_east_O2`, `gps_east_O3`, `EL_BASE`, `EL_A`, `EL_B`, `EL_C`, `EL_D`, `EL_O1`, `EL_O2`, `EL_O3`, `GL_BASE`, `GL_A`, `GL_B`, `GL_C`, `GL_D`, `GL_O1`, `GL_O2`, `GL_O3`, `spud`, `rr`, `logged`, `no_abandonment_required`, `permanent_installation`, `abandonment`, `ready_roll_back`, `reclaimed_except_vegetation`, `desc_O1`, `desc_O2`, `desc_O3` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;
		if($x_need_as_built_BASE=="0000-00-00") $x_need_as_built_BASE = "";
		if($x_need_as_built_A=="0000-00-00") $x_need_as_built_A = "";
		if($x_need_as_built_B=="0000-00-00") $x_need_as_built_B = "";
		if($x_need_as_built_C=="0000-00-00") $x_need_as_built_C = "";
		if($x_need_as_built_D=="0000-00-00") $x_need_as_built_D = "";
		if($x_need_as_built_O1=="0000-00-00") $x_need_as_built_O1 = "";
		if($x_need_as_built_O2=="0000-00-00") $x_need_as_built_O2 = "";
		if($x_need_as_built_O3=="0000-00-00") $x_need_as_built_O3 = "";
		if($x_date_BASE=="0000-00-00") $x_date_BASE = "";
		if($x_date_A=="0000-00-00") $x_date_A = "";
		if($x_date_B=="0000-00-00") $x_date_B = "";
		if($x_date_C=="0000-00-00") $x_date_C = "";
		if($x_date_D=="0000-00-00") $x_date_D = "";
		if($x_date_O1=="0000-00-00") $x_date_O1 = "";
		if($x_date_O2=="0000-00-00") $x_date_O2 = "";
		if($x_date_O3=="0000-00-00") $x_date_O3 = "";
		if($x_ready_for_drilling=="0000-00-00") $x_ready_for_drilling = "";
		if($x_approved_by_drilling=="0000-00-00") $x_approved_by_drilling = "";
		if($x_gps_north_BASE=="0.000") $x_gps_north_BASE = "";
		if($x_gps_north_A=="0.000") $x_gps_north_A = "";
		if($x_gps_north_B=="0.000") $x_gps_north_B = "";
		if($x_gps_north_C=="0.000") $x_gps_north_C = "";
		if($x_gps_north_D=="0.000") $x_gps_north_D = "";
		if($x_gps_north_O1=="0.000") $x_gps_north_O1 = "";
		if($x_gps_north_O2=="0.000") $x_gps_north_O2 = "";
		if($x_gps_north_O3=="0.000") $x_gps_north_O3 = "";
		if($x_gps_east_BASE=="0.000") $x_gps_east_BASE = "";
		if($x_gps_east_A=="0.000") $x_gps_east_A = "";
		if($x_gps_east_B=="0.000") $x_gps_east_B = "";
		if($x_gps_east_C=="0.000") $x_gps_east_C = "";
		if($x_gps_east_D=="0.000") $x_gps_east_D = "";
		if($x_gps_east_O1=="0.000") $x_gps_east_O1 = "";
		if($x_gps_east_O2=="0.000") $x_gps_east_O2 = "";
		if($x_gps_east_O3=="0.000") $x_gps_east_O3 = "";
		if($x_EL_BASE=="0.000") $x_EL_BASE = "";
		if($x_EL_A=="0.000") $x_EL_A = "";
		if($x_EL_B=="0.000") $x_EL_B = "";
		if($x_EL_C=="0.000") $x_EL_C = "";
		if($x_EL_D=="0.000") $x_EL_D = "";
		if($x_EL_O1=="0.000") $x_EL_O1 = "";
		if($x_EL_O2=="0.000") $x_EL_O2 = "";
		if($x_EL_O3=="0.000") $x_EL_O3 = "";
		if($x_GL_BASE=="0.000") $x_GL_BASE = "";
		if($x_GL_A=="0.000") $x_GL_A = "";
		if($x_GL_B=="0.000") $x_GL_B = "";
		if($x_GL_C=="0.000") $x_GL_C = "";
		if($x_GL_D=="0.000") $x_GL_D = "";
		if($x_GL_O1=="0.000") $x_GL_O1 = "";
		if($x_GL_O2=="0.000") $x_GL_O2 = "";
		if($x_GL_O3=="0.000") $x_GL_O3 = "";
		if($x_spud=="0000-00-00 00:00:00") $x_spud = "";
		if($x_rr=="0000-00-00 00:00:00") $x_rr = "";
		if($x_logged=="0000-00-00 00:00:00") $x_logged = "";
		if($x_permanent_installation=="0000-00-00") $x_permanent_installation = "";
		if($x_abandonment=="0000-00-00 00:00:00") $x_abandonment = "";
		if($x_ready_roll_back=="0000-00-00") $x_ready_roll_back = "";
		if($x_roll_back=="0000-00-00") $x_roll_back = "";
	} else {
		$x_need_as_built_BASE = $x_need_as_built_A = $x_need_as_built_B = $x_need_as_built_C = $x_need_as_built_D = $x_need_as_built_O1 = $x_need_as_built_O2 = $x_need_as_built_O3 = $x_date_BASE = $x_date_A = $x_date_B = $x_date_C = $x_date_D = $x_date_O1 = $x_date_O2 = $x_date_O3 = $x_gps_north_BASE = $x_gps_north_A = $x_gps_north_B = $x_gps_north_C = $x_gps_north_D = $x_gps_north_O1 = $x_gps_north_O2 = $x_gps_north_O3 = $x_ready_for_drilling = $x_approved_by_drilling = $x_gps_east_BASE = $x_gps_east_A = $x_gps_east_B = $x_gps_east_C = $x_gps_east_D = $x_gps_east_O1 = $x_gps_east_O2 = $x_gps_east_O3 = $x_spud = $x_rr = $x_logged = $x_no_abandonment_required = $x_permanent_installation = $x_abandonment = $x_ready_roll_back = $x_roll_back = $x_desc_O1 = $x_desc_O2 = $x_desc_O3 = $x_EL_BASE = $x_EL_A = $x_EL_B = $x_EL_C = $x_EL_D = $x_EL_O1 = $x_EL_O2 = $x_EL_O3 = $x_GL_BASE = $x_GL_A = $x_GL_B = $x_GL_C = $x_GL_D = $x_GL_O1 = $x_GL_O2 = $x_GL_O3 = "";
	}
	$objResponse->assign("need_as_built_BASE", "value", "{$x_need_as_built_BASE}");
	$objResponse->assign("need_as_built_A", "value", "{$x_need_as_built_A}");
	$objResponse->assign("need_as_built_B", "value", "{$x_need_as_built_B}");
	$objResponse->assign("need_as_built_C", "value", "{$x_need_as_built_C}");
	$objResponse->assign("need_as_built_D", "value", "{$x_need_as_built_D}");
	$objResponse->assign("need_as_built_O1", "value", "{$x_need_as_built_O1}");
	$objResponse->assign("need_as_built_O2", "value", "{$x_need_as_built_O2}");
	$objResponse->assign("need_as_built_O3", "value", "{$x_need_as_built_O3}");
	$objResponse->assign("tdDate_BASE", "innerHTML", "{$x_date_BASE}");
	$objResponse->assign("tdDate_A", "innerHTML", "{$x_date_A}");
	$objResponse->assign("tdDate_B", "innerHTML", "{$x_date_B}");
	$objResponse->assign("tdDate_C", "innerHTML", "{$x_date_C}");
	$objResponse->assign("tdDate_D", "innerHTML", "{$x_date_D}");
	$objResponse->assign("tdDate_O1", "innerHTML", "{$x_date_O1}");
	$objResponse->assign("tdDate_O2", "innerHTML", "{$x_date_O2}");
	$objResponse->assign("tdDate_O3", "innerHTML", "{$x_date_O3}");
	$objResponse->assign("tdGps_north_BASE", "innerHTML", "{$x_gps_north_BASE}");
	$objResponse->assign("tdGps_north_A", "innerHTML", "{$x_gps_north_A}");
	$objResponse->assign("tdGps_north_B", "innerHTML", "{$x_gps_north_B}");
	$objResponse->assign("tdGps_north_C", "innerHTML", "{$x_gps_north_C}");
	$objResponse->assign("tdGps_north_D", "innerHTML", "{$x_gps_north_D}");
	$objResponse->assign("tdGps_north_O1", "innerHTML", "{$x_gps_north_O1}");
	$objResponse->assign("tdGps_north_O2", "innerHTML", "{$x_gps_north_O2}");
	$objResponse->assign("tdGps_north_O3", "innerHTML", "{$x_gps_north_O3}");
	$objResponse->assign("tdReady_for_drilling", "innerHTML", "{$x_ready_for_drilling}");
	$objResponse->assign("approved_by_drilling", "value", "{$x_approved_by_drilling}");
	$objResponse->assign("tdGps_east_BASE", "innerHTML", "{$x_gps_east_BASE}");
	$objResponse->assign("tdGps_east_A", "innerHTML", "{$x_gps_east_A}");
	$objResponse->assign("tdGps_east_B", "innerHTML", "{$x_gps_east_B}");
	$objResponse->assign("tdGps_east_C", "innerHTML", "{$x_gps_east_C}");
	$objResponse->assign("tdGps_east_D", "innerHTML", "{$x_gps_east_D}");
	$objResponse->assign("tdGps_east_O1", "innerHTML", "{$x_gps_east_O1}");
	$objResponse->assign("tdGps_east_O2", "innerHTML", "{$x_gps_east_O2}");
	$objResponse->assign("tdGps_east_O3", "innerHTML", "{$x_gps_east_O3}");
	$objResponse->assign("tdEL_BASE", "innerHTML", "{$x_EL_BASE}");
	$objResponse->assign("tdEL_A", "innerHTML", "{$x_EL_A}");
	$objResponse->assign("tdEL_B", "innerHTML", "{$x_EL_B}");
	$objResponse->assign("tdEL_C", "innerHTML", "{$x_EL_C}");
	$objResponse->assign("tdEL_D", "innerHTML", "{$x_EL_D}");
	$objResponse->assign("tdEL_O1", "innerHTML", "{$x_EL_O1}");
	$objResponse->assign("tdEL_O2", "innerHTML", "{$x_EL_O2}");
	$objResponse->assign("tdEL_O3", "innerHTML", "{$x_EL_O3}");
	$objResponse->assign("tdGL_BASE", "innerHTML", "{$x_GL_BASE}");
	$objResponse->assign("tdGL_A", "innerHTML", "{$x_GL_A}");
	$objResponse->assign("tdGL_B", "innerHTML", "{$x_GL_B}");
	$objResponse->assign("tdGL_C", "innerHTML", "{$x_GL_C}");
	$objResponse->assign("tdGL_D", "innerHTML", "{$x_GL_D}");
	$objResponse->assign("tdGL_O1", "innerHTML", "{$x_GL_O1}");
	$objResponse->assign("tdGL_O2", "innerHTML", "{$x_GL_O2}");
	$objResponse->assign("tdGL_O3", "innerHTML", "{$x_GL_O3}");
	$objResponse->assign("tdSpud", "innerHTML", "{$x_spud}");
	$objResponse->assign("desc_O1", "value", "{$x_desc_O1}");
	$objResponse->assign("desc_O2", "value", "{$x_desc_O2}");
	$objResponse->assign("desc_O3", "value", "{$x_desc_O3}");
	$objResponse->assign("tdRR", "innerHTML", "{$x_rr}");
	$objResponse->assign("tdLogged", "innerHTML", "{$x_logged}");
	$objResponse->assign("tdNoAbandonmentRequired", "innerHTML", "{$x_no_abandonment_required}");
	$objResponse->assign("tdPermanentInstallation", "innerHTML", "{$x_permanent_installation}");
	$objResponse->assign("tdAbandonment", "innerHTML", "{$x_abandonment}");
	$objResponse->assign("ready_roll_back", "value", "{$x_ready_roll_back}");
	$objResponse->assign("tdRollBack", "innerHTML", "{$x_roll_back}");

    return $objResponse;
}

function GetInfoConCrossWater($source_id)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	list($x_permit_no, $x_description, $x_location, $x_start_date, $x_completed, $x_reclaimed, $x_area) = $infosystem->Execute("SELECT `permit_no`, `description`, `location`, `start_date`, `completed`, `reclaimed`, `area` FROM `water_crossings` WHERE `source_id` = '{$source_id}'")->fields;

	if($x_start_date=="0000-00-00") $x_start_date = "";
	if($x_completed=="0000-00-00") $x_completed = "";
	if($x_reclaimed=="0000-00-00") $x_reclaimed = "";

	$objResponse->assign("permit_no", "value", "{$x_permit_no}");
	$objResponse->assign("description", "value", "{$x_description}");
	$objResponse->assign("location", "value", "{$x_location}");
	$objResponse->assign("start_date", "value", "{$x_start_date}");
	$objResponse->assign("completed", "value", "{$x_completed}");
	$objResponse->assign("reclaimed", "value", "{$x_reclaimed}");
	$objResponse->assign("area", "value", "{$x_area}");
//	$objResponse->script("for(i=0; i<document.getElementById('area').length; i++) { if(document.getElementById('area').options[i].value=={$x_area} { document.getElementById('area').selectedIndex=i } }");

    return $objResponse;
}

function GetUnitsForTruckType($truckType)
{
	global $infosystem;
    $objResponse = new xajaxResponse();

	$rsTruckUnit = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = '{$truckType}'");
	$html = "<select name=\"unit\" id=\"unit\" style=\"width:70px;\"><option value=\"\"></option>";
	while(!$rsTruckUnit->EOF) {
		list($x_unit) = $rsTruckUnit->fields;
		$html .= "<option value=\"{$x_unit}\">{$x_unit}</option>";
		$rsTruckUnit->MoveNext();
	}
	$html .= "</select>";

	$objResponse->assign("unit", "innerHTML", "{$html}");

    return $objResponse;
}

$xajax->processRequest();
?>