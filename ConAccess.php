<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

list($ConAccessLvl) = $infosystem->Execute("SELECT `ConAccess` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($ConAccessLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($ConAccessLvl=="o")?" disabled=\"disabled\"":"";

// Gathering data for combo's
$rsWells = $infosystem->Execute("SELECT `well_id` FROM `wells`");

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());
$user = $_SESSION['username'];

$dbFields = array(); $dbFieldValues = array();
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) {
		if($key!="submit") array_push($dbFields, "`{$key}`");
		if($value!="Submit") array_push($dbFieldValues, "'{$value}'");
	}
	array_push($dbFields, "`user`", "`input_date`");
	array_push($dbFieldValues, "'{$user}'", "'{$today}'");

	$check = $infosystem->Execute("SELECT `well_id` FROM `con_access` WHERE `well_id` = '{$well_id}'");

	if($check->RecordCount()==0) {
		$dbFields = str_replace(", `submit`", "", implode(", ", $dbFields));
		$dbFieldValues = str_replace(", 'Submit'", "", implode(", ", $dbFieldValues));
		$SQL = "INSERT INTO `con_access`({$dbFields}) VALUES({$dbFieldValues})";
	} else {
		$SQL = "UPDATE `con_access` SET ";
		foreach($dbFields as $f => $v) $SQL .= "{$v} = ".$dbFieldValues[$f].", ";
		$SQL = substr($SQL, 0, -2);
		$SQL .= " WHERE `well_id` = '{$well_id}'";
	}
	$infosystem->Execute($SQL);

include("confirm.html");
ob_end_flush();
}
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Con Access - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<? $xajax->printJavascript(); ?>
</head>
<? include("header.inc"); ?>

<body>
<div id="mainForm" style="padding:20px;">
<p>Access Level: <?=$accessLevelDesc[$ConAccessLvl]?>
<form name="frm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table cellspacing="1" cellpadding="3" border="0">

<tr>
<td colspan="2">Zone</td>
<td id="tdZone">&nbsp;</td>
</tr>

<tr>
<td colspan="2">Permits</td>
<td><input type="text" name="permits" id="permits"<?=$readOnly?> /></td>
</tr>

<tr>
<td rowspan="1">Name</td>
<td>Well ID</td>
<td>
<select name="well_id" id="well_id" onchange="xajax_GetWellInfoConAccess(this.value)"<?=$readOnly?>>
<option value=""></option><?
while(!$rsWells->EOF) {
list($well_id) = $rsWells->fields; ?>
<option value="<?=$well_id?>"><?=$well_id?></option><?
$rsWells->MoveNext();
} ?>
</select>
</td>
</tr>

<tr>
<td rowspan="2">New Cut Access</td>
<td>Length (m)</td>
<td id="tdLengthNCA">&nbsp;</td>
</tr>

<tr>
<td>Width (m)</td>
<td id="tdWidthNCA">&nbsp;</td>
</tr>

<tr>
<td rowspan="2">Existing Access</td>
<td>Length (m)</td>
<td id="tdLengthEA">&nbsp;</td>
</tr>

<tr>
<td>Width (m)</td>
<td id="tdWidthEA">&nbsp;</td>
</tr>

<tr>
<td colspan="3" style="padding: 10px; font-weight:bold; text-align: center; background: #DDDDDD;">Temporary Log Deck</td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Area 1</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="area1_gps_north" id="area1_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="area1_gps_east" id="area1_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Conifer / Deciduous</td>
<td>
   <select name="area1_conifer" id="area1_conifer"<?=$readOnly?>>
          <option value=""></option>
          <option value="Conifer">Conifer</option>
          <option value="Deciduous">Deciduous</option>
      </select>
    </td>
</tr>
<tr>
<td colspan="2">Volume (m3)</td>
<td><input type="text" name="area1_volume" id="area1_volume"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Area 2</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="area2_gps_north" id="area2_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="area2_gps_east" id="area2_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Conifer / Deciduous</td>
<td>
   <select name="area2_conifer" id="area2_conifer"<?=$readOnly?>>
          <option value=""></option>
          <option value="Conifer">Conifer</option>
          <option value="Deciduous">Deciduous</option>
      </select>
    </td>
</tr>
<tr>
<td colspan="2">Volume (m3)</td>
<td><input type="text" name="area2_volume" id="area2_volume"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Area 3</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="area3_gps_north" id="area3_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="area3_gps_east" id="area3_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Conifer / Deciduous</td>
<td>
   <select name="area3_conifer" id="area3_conifer"<?=$readOnly?>>
          <option value=""></option>
          <option value="Conifer">Conifer</option>
          <option value="Deciduous">Deciduous</option>
      </select>
    </td>
</tr><tr>
<td colspan="2">Volume (m3)</td>
<td><input type="text" name="area3_volume" id="area3_volume"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 10px; font-weight:bold; text-align: center; background: #DDDDDD;">RIG Mat Locations</td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Location 1</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="location1_gps_north" id="location1_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="location1_gps_east" id="location1_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Number of Rig Mats</td>
<td><input type="text" name="location1_number_of_rig_mats" id="location1_number_of_rig_mats"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Location 2</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="location2_gps_north" id="location2_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="location2_gps_east" id="location2_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Number of Rig Mats</td>
<td><input type="text" name="location2_number_of_rig_mats" id="location2_number_of_rig_mats"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Location 3</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="location3_gps_north" id="location3_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="location3_gps_east" id="location3_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Number of Rig Mats</td>
<td><input type="text" name="location3_number_of_rig_mats" id="location3_number_of_rig_mats"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Location 4</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="location4_gps_north" id="location4_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="location4_gps_east" id="location4_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Number of Rig Mats</td>
<td><input type="text" name="location4_number_of_rig_mats" id="location4_number_of_rig_mats"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px;"><b>Location 5</b></td>
</tr>
<tr>
<td rowspan="2">GPS Coordinates</td>
<td>North</td>
<td><input type="text" name="location5_gps_north" id="location5_gps_north"<?=$readOnly?> /></td>
</tr>
<tr>
<td>East</td>
<td><input type="text" name="location5_gps_east" id="location5_gps_east"<?=$readOnly?> /></td>
</tr>
<tr>
<td colspan="2">Number of Rig Mats</td>
<td><input type="text" name="location5_number_of_rig_mats" id="location5_number_of_rig_mats"<?=$readOnly?> /></td>
</tr>
</table>
<p><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></p>
</form>
</div>
<? include("footer.inc"); ?>
</body>
</html><?
} ?>