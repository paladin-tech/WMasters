<?
session_start();
//if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());
$user = $_SESSION['username'];
$formDate = (isset($_GET['date']))?$_GET['date']:$today;

// Gathering data for combo's
$rsWaterVacuum = $infosystem->Execute("SELECT `unit`, `truck_type`, `area`, `loc_1`, `loc_2`, `loc_3`, `loc_4`, `loc_5`, `loc_6`, `loc_7`, `loc_8`, `loc_9`, `loc_10`, `loc_11`, `loc_12` FROM `water_vacuum` WHERE `input_date` = '{$formDate}'");
list($sumKearl1, $sumKearl2, $sumKearl3) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2, SUM(`loc_3`) s3 FROM `water_vacuum` WHERE `input_date` = '{$formDate}' AND `area` = 'Kearl'")->fields;
list($sumFirebag1, $sumFirebag2, $sumFirebag3, $sumFirebag4, $sumFirebag5, $sumFirebag6, $sumFirebag7, $sumFirebag8, $sumFirebag9, $sumFirebag10, $sumFirebag11, $sumFirebag12) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2, SUM(`loc_3`) s3, SUM(`loc_4`) s4, SUM(`loc_5`) s5, SUM(`loc_6`) s6, SUM(`loc_7`) s7, SUM(`loc_8`) s8, SUM(`loc_9`) s9, SUM(`loc_10`) s10, SUM(`loc_11`) s11, SUM(`loc_12`) s12 FROM `water_vacuum` WHERE `input_date` = '{$formDate}' AND `area` = 'Firebag'")->fields;
list($sumSyncrude1, $sumSyncrude2) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2 FROM `water_vacuum` WHERE `input_date` = '{$formDate}' AND `area` = 'Syncrude'")->fields;

$dbFields = array(); $dbFieldValues = array();
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) {
		if($key!="submit") array_push($dbFields, "`{$key}`");
		if($value!="Submit") array_push($dbFieldValues, "'{$value}'");
	}
	array_push($dbFields, "`user`");
	array_push($dbFieldValues, "'{$user}'");
	$check = $infosystem->Execute("SELECT `well_id` FROM `con_access` WHERE `well_id` = '{$well_id}'");

	if($check->RecordCount()==0) {
		$dbFields = str_replace(", `submit`", "", implode(", ", $dbFields));
		$dbFieldValues = str_replace(", 'Submit'", "", implode(", ", $dbFieldValues));
		$SQL = "INSERT INTO `water_vacuum`({$dbFields}) VALUES({$dbFieldValues})";
	} else {
/*		$SQL = "UPDATE `con_access` SET ";
		foreach($dbFields as $f => $v) $SQL .= "{$v} = ".$dbFieldValues[$f].", ";
		$SQL = substr($SQL, 0, -2);
		$SQL .= " WHERE `well_id` = '{$well_id}'";*/
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
<title>Water - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<? $xajax->printJavascript(); ?>
<script language="javascript">
function CheckForm(f)
{
	frmsubmit = true;
	if(document.getElementById('truck_type').value==''||document.getElementById('unit').value==''||document.getElementById('area').value=='') {
		alert('Please fill the data for Truck Type, Unit and Area!');
		frmsubmit = false;
	}
	return frmsubmit;
}
</script>
</head>
<? include("header.inc"); ?>

<body>
<div id="mainForm" style="padding:20px;">
<p>Access Level: <?=$accessLevelDesc[$ConAccessLvl]?></p>
<table cellpadding="3" cellspacing="1" align="left">
<caption>Kearl</caption>
    <tr>
        <td>Water Source</td>
        <td align="center" width="50">1</td>
        <td align="center" width="50">2</td>
        <td align="center" width="50">3</td>
    </tr>
    <tr>
        <td>Water Amount</td>
        <td align="center"><?=$sumKearl1?></td>
        <td align="center"><?=$sumKearl2?></td>
        <td align="center"><?=$sumKearl3?></td>
    </tr>
</table>
<table cellpadding="3" cellspacing="1" align="left" style="margin-left:100px;">
<caption>DATE</caption>
	<tr>
    	<td><input type="txt" name="txtDate" value="<?=$formDate?>" onchange="window.location = 'WaterVacuum.php?date='+this.value;" /></td>
    </tr>
</table>
<br clear="all" />
<br />
<table cellpadding="3" cellspacing="1">
<caption>Firebag</caption>
    <tr>
        <td>Water Source</td>
        <td align="center" width="50">1</td>
        <td align="center" width="50">2</td>
        <td align="center" width="50">3</td>
        <td align="center" width="50">4</td>
        <td align="center" width="50">5</td>
        <td align="center" width="50">6</td>
        <td align="center" width="50">7</td>
        <td align="center" width="50">8</td>
        <td align="center" width="50">9</td>
        <td align="center" width="50">10</td>
        <td align="center" width="50">11</td>
        <td align="center" width="50">12</td>
    </tr>
    <tr>
        <td align="center">Water Amount</td>
        <td align="center"><?=$sumFirebag1?></td>
        <td align="center"><?=$sumFirebag2?></td>
        <td align="center"><?=$sumFirebag3?></td>
        <td align="center"><?=$sumFirebag4?></td>
        <td align="center"><?=$sumFirebag5?></td>
        <td align="center"><?=$sumFirebag6?></td>
        <td align="center"><?=$sumFirebag7?></td>
        <td align="center"><?=$sumFirebag8?></td>
        <td align="center"><?=$sumFirebag9?></td>
        <td align="center"><?=$sumFirebag10?></td>
        <td align="center"><?=$sumFirebag11?></td>
        <td align="center"><?=$sumFirebag12?></td>
    </tr>
</table>
<br />
<table cellpadding="3" cellspacing="1">
<caption>Syncrude</caption>
    <tr>
        <td>Water Source</td>
        <td align="center" width="50">1</td>
        <td align="center" width="50">2</td>
    </tr>
    <tr>
        <td>Water Amount</td>
        <td align="center"><?=$sumSyncrude1?></td>
        <td align="center"><?=$sumSyncrude2?></td>
    </tr>
</table>
<br />
<form name="frm" method="post" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return CheckForm(this)">
<input type="hidden" name="input_date" value="<?=$formDate?>" />
<table cellpadding="3" cellspacing="1">
    <tr>
        <td align="center">Truck Type</td>
        <td align="center">Unit</td>
        <td align="center">Area</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">5</td>
        <td align="center">6</td>
        <td align="center">7</td>
        <td align="center">8</td>
        <td align="center">9</td>
        <td align="center">10</td>
        <td align="center">11</td>
        <td align="center">12</td>
    </tr><?
	while(!$rsWaterVacuum->EOF) {
	list($x_unit, $x_truck_type, $x_area, $x_loc_1, $x_loc_2, $x_loc_3, $x_loc_4, $x_loc_5, $x_loc_6, $x_loc_7, $x_loc_8, $x_loc_9, $x_loc_10, $x_loc_11, $x_loc_12) = $rsWaterVacuum->fields; ?>
    <tr>
        <td align="center"><?=$x_truck_type?></td>
        <td align="center"><?=$x_unit?></td>
        <td align="center"><?=$x_area?></td>
        <td align="center"><?=$x_loc_1?></td>
        <td align="center"><?=$x_loc_2?></td>
        <td align="center"><?=$x_loc_3?></td>
        <td align="center"><?=$x_loc_4?></td>
        <td align="center"><?=$x_loc_5?></td>
        <td align="center"><?=$x_loc_6?></td>
        <td align="center"><?=$x_loc_7?></td>
        <td align="center"><?=$x_loc_8?></td>
        <td align="center"><?=$x_loc_9?></td>
        <td align="center"><?=$x_loc_10?></td>
        <td align="center"><?=$x_loc_11?></td>
        <td align="center"><?=$x_loc_12?></td>
    </tr><?
	$rsWaterVacuum->MoveNext();
	} ?>
    <tr>
        <td align="center">
        	<select name="truck_type" id="truck_type" onchange="xajax_GetUnitsForTruckType(this.value)">
            	<option value=""></option>
            	<option value="Water">Water Truck</option>
            </select>
        </td>
        <td align="center">
        	<select name="unit" id="unit" style="width:70px;">
            	<option value=""></option>
            </select>
        </td>
        <td align="center">
        	<select name="area" id="area">
            	<option value=""></option>
            	<option value="Kearl">Kearl</option>
            	<option value="Firebag">Firebag</option>
            	<option value="Syncrude">Syncrude</option>
            </select>
        </td>
        <td align="center" width="50"><input type="text" name="loc_1" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_2" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_3" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_4" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_5" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_6" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_7" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_8" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_9" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_10" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_11" size="3" /></td>
        <td align="center" width="50"><input type="text" name="loc_12" size="3" /></td>
    </tr>
</table>
<p><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></p>
</form>
For this form please use "Google Chrome" or "Firefox"
</div>
<? include("footer.inc"); ?>
</body>
</html><?
} ?>