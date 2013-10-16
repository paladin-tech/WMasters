<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
// $infosystem->debug = true;

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());
$user = $_SESSION['username'];

list($VacuumLvl) = $infosystem->Execute("SELECT `Vacuum` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($VacuumLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($VacuumLvl=="o")?" disabled=\"disabled\"":"";
$rsConVacuum = $infosystem->Execute("SELECT `area`, `sump_number` FROM `con_vacuum` WHERE `ReadOnly` = 0");
$chSumpDesc = $infosystem->Execute("SELECT `area`,`program_zone` FROM `con_vacuum` ORDER BY `area`, `sump_number`");

if(isset($_POST['submit'])) {
	foreach($_POST['unit'] as $key => $value) {
		$fieldsArray = array("unit", "area", "loc_1", "loc_2", "loc_3", "loc_4", "loc_5", "loc_6");
		foreach($fieldsArray as $fa) {
			$var = "x_{$fa}";
			$$var = mysql_real_escape_string($_POST[$fa][$key]);
			if((substr($fa, 0, 3)=="loc")&&$$var=="") $$var = 0;
		}

		if($x_unit!="") {
			if($key=="new") {
				$SQL = "INSERT INTO `vacuum`(`input_date`, `unit`, `truck_type`, `user`) VALUES('{$today}', '{$x_unit}', 'Vacuum', '{$user}')";
				$infosystem->Execute($SQL);
			}

			$SQL = "UPDATE `vacuum` SET `area` = '{$x_area}' WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
			$infosystem->Execute($SQL);

			$rsConVacuum->MoveFirst();
			while(!$rsConVacuum->EOF) {
				list($y_area, $y_sump_number) = $rsConVacuum->fields;
				if($x_area==$y_area) {
					if ($y_sump_number==1) $SQL = "UPDATE `vacuum` SET `loc_1` = {$x_loc_1} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
					else if ($y_sump_number==2) $SQL = "UPDATE `vacuum` SET `loc_2` = {$x_loc_2} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
						else if ($y_sump_number==3) $SQL = "UPDATE `vacuum` SET `loc_3` = {$x_loc_3} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
							else if ($y_sump_number==4) $SQL = "UPDATE `vacuum` SET `loc_4` = {$x_loc_4} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
								else if ($y_sump_number==5) $SQL = "UPDATE `vacuum` SET `loc_5` = {$x_loc_5} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
									else if ($y_sump_number==6) $SQL = "UPDATE `vacuum` SET `loc_6` = {$x_loc_6} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
					$infosystem->Execute($SQL);
				}
				$rsConVacuum->MoveNext();
			}
		}
	}
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Vacuum', '{$user}')");
}

$rsTruckAll = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = 'Vacuum'");
$rsTruck = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = 'Vacuum' AND `unit` NOT IN (SELECT `unit` FROM `vacuum` WHERE `input_date` = '{$today}')");

// Gathering data for combo's
$rsVacuum = $infosystem->Execute("SELECT `unit`, `truck_type`, `area`, `loc_1`, `loc_2`, `loc_3`, `loc_4`, `loc_5`, `loc_6` FROM `vacuum` WHERE `input_date` = '{$today}'");
list($sumKearl1, $sumKearl2, $sumKearl3, $sumKearl4, $sumKearl5, $sumKearl6) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2, SUM(`loc_3`) s3, SUM(`loc_4`) s4, SUM(`loc_5`) s5, SUM(`loc_6`) s6 FROM `vacuum` WHERE `input_date` = '{$today}' AND `area` = 'Kearl'")->fields;
list($sumSyncrude1) = $infosystem->Execute("SELECT SUM(`loc_1`) FROM `vacuum` WHERE `input_date` = '{$today}' AND `area` = 'Syncrude'")->fields;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Vacuum - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<? $xajax->printJavascript(); ?>
<script src="CalendarPopupCombined.js"></script>
<script language="javascript">
function CheckForm(f)
{
	log('In JavaScript Function');
	frmsubmit = true;
	for(i=0; i<document.frm.elements.length; i++) {
		if(document.frm.elements[i].name.search('new')==-1&&document.frm.elements[i].value=='') {
			alert('Please fill all the data in existing records!');
			frmsubmit = false;
			break;
		}
	}
	return frmsubmit;
}
</script>
</head>
<? include("header.inc"); ?>

<body>
<div id="mainForm" style="padding:20px;">
<p>Access Level: <?=$accessLevelDesc[$VacuumLvl]?></p>
<table cellpadding="3" cellspacing="1" align="left">
<caption>Kearl</caption>
    <tr>
        <td>Sump Source</td>
        <td align="center" width="50">1</td>
        <td align="center" width="50">2</td>
        <td align="center" width="50">3</td>
        <td align="center" width="50">4</td>
        <td align="center" width="50">5</td>
        <td align="center" width="50">6</td>
    </tr>
    <tr>
        <td>Description</td>
	<?
 	while(!$chSumpDesc->EOF) {
		list($ch_area,$ch_description) = $chSumpDesc->fields;
		if($ch_area=="Kearl") {
 	?>
		<td align="center" width="75"><?=$ch_description?></td>
	<?
		}
	$chSumpDesc->MoveNext();
	}
    ?>
    </tr>
    <tr>
        <td>Sump Amount</td>
        <td align="center"><?=$sumKearl1?></td>
        <td align="center"><?=$sumKearl2?></td>
        <td align="center"><?=$sumKearl3?></td>
        <td align="center"><?=$sumKearl4?></td>
        <td align="center"><?=$sumKearl5?></td>
        <td align="center"><?=$sumKearl6?></td>
    </tr>
</table>
<br clear="all" />
<br />
<table cellpadding="3" cellspacing="1" align="left">
<caption>Syncrude</caption>
    <tr>
        <td>Sump Source</td>
        <td align="center" width="50">1</td>
    </tr>
    <tr>
        <td>Description</td>
	<?
	$chSumpDesc->MoveFirst();
 	while(!$chSumpDesc->EOF) {
		list($ch_area,$ch_description) = $chSumpDesc->fields;
		if($ch_area=="Syncrude") {
 	?>
		<td align="center" width="75"><?=$ch_description?></td>
	<?
		}
	$chSumpDesc->MoveNext();
	}
    ?>
    </tr>
    <tr>
        <td>Sump Amount</td>
        <td align="center"><?=$sumSyncrude1?></td>
    </tr>
</table>
<br clear="all" />
<br />
<table cellpadding="3" cellspacing="1">
<caption>DATE</caption>
	<tr>
    	<td>This is today's day input only. If you want to make or change any inputs in the past please contact WM Field Team.</td>
    </tr>
</table>
<br clear="all" />
<br />
<form name="frm" method="post" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return CheckForm(this)">
<input type="hidden" name="input_date" value="<?=$today?>" />
<table cellpadding="3" cellspacing="1">
    <tr>
        <td align="center">Unit</td>
        <td align="center">Area</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">5</td>
        <td align="center">6</td>
    </tr><?
	$i = 1;
	while(!$rsVacuum->EOF) {
	list($x_unit, $x_truck_type, $x_area, $x_loc_1, $x_loc_2, $x_loc_3, $x_loc_4, $x_loc_5, $x_loc_6) = $rsVacuum->fields; ?>
    <tr>
        <td align="center"><input type="text" name="unit[W<?=$i?>]" style="width:100px;" value="<?=$x_unit?>" readonly="readonly" /></td>
        <td align="center"><input type="text" name="area[W<?=$i?>]" style="width:80px;" value="<?=$x_area?>" readonly="readonly" /></td>
        <td align="center" width="50"><input type="text" name="loc_1[W<?=$i?>]" size="3" value="<?=$x_loc_1?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_2[W<?=$i?>]" size="3" value="<?=$x_loc_2?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_3[W<?=$i?>]" size="3" value="<?=$x_loc_3?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_4[W<?=$i?>]" size="3" value="<?=$x_loc_4?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_5[W<?=$i?>]" size="3" value="<?=$x_loc_5?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_6[W<?=$i?>]" size="3" value="<?=$x_loc_6?>"<?=$readOnly?> /></td>
    </tr><?
	$rsVacuum->MoveNext();
	$i++;
	} ?>
    <tr>
        <td align="center">
        	<select name="unit[new]" style="width:100px;">
            	<option value=""></option><?
				$rsTruck->MoveFirst();
				while(!$rsTruck->EOF) {
				list($y_unit) = $rsTruck->fields; ?>
            	<option value="<?=$y_unit?>"><?=$y_unit?></option><?
                $rsTruck->MoveNext();
                } ?>
            </select>
        </td>
        <td align="center">
        	<select name="area[new]" style="width:100px;" >
            	<option value="Kearl">Kearl</option>
            	<option value="Syncrude">Syncrude</option>
            </select>
        </td>
        <td align="center" width="50"><input type="text" name="loc_1[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_2[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_3[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_4[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_5[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_6[new]" size="3"<?=$readOnly?> /></td>
    </tr>
</table>
<p><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></p>
</form>
<!--For this form please use "Google Chrome" or "Firefox"-->
</div>
<? include("footer.inc"); ?>
</body>
</html>