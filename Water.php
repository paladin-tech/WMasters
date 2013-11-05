<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());
$user = $_SESSION['username'];

list($WaterLvl) = $infosystem->Execute("SELECT `Water` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($WaterLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($WaterLvl=="o")?" disabled=\"disabled\"":"";
$rsConHydro = $infosystem->Execute("SELECT `area`, `source_number` FROM `con_hydro` WHERE `ReadOnly` = 0");
$chSourceDesc = $infosystem->Execute("SELECT `area`,`program_zone` FROM `con_hydro` ORDER BY `area`, `source_number`");

if(isset($_POST['submit'])) {
	foreach($_POST['unit'] as $key => $value) {
		$fieldsArray = array("unit", "area", "loc_1", "loc_2", "loc_3", "loc_4", "loc_5", "loc_6", "loc_7", "loc_8", "loc_9", "loc_10", "loc_11", "loc_12");
		foreach($fieldsArray as $fa) {
			$var = "x_{$fa}";
			$$var = mysql_real_escape_string($_POST[$fa][$key]);
			if((substr($fa, 0, 3)=="loc")&&$$var=="") $$var = 0;
		}

		if($x_unit!="") {
			if($key=="new") {
				$SQL = "INSERT INTO `water`(`input_date`, `unit`, `truck_type`, `user`) VALUES('{$today}', '{$x_unit}', 'Water', '{$user}')";
				$infosystem->Execute($SQL);
			}

			$SQL = "UPDATE `water` SET `area` = '{$x_area}' WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
			$infosystem->Execute($SQL);

			$rsConHydro->MoveFirst();
			while(!$rsConHydro->EOF) {
				list($y_area, $y_source_number) = $rsConHydro->fields;
				if($x_area==$y_area) {
					if ($y_source_number==1) $SQL = "UPDATE `water` SET `loc_1` = {$x_loc_1} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
					else if ($y_source_number==2) $SQL = "UPDATE `water` SET `loc_2` = {$x_loc_2} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
						else if ($y_source_number==3) $SQL = "UPDATE `water` SET `loc_3` = {$x_loc_3} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
							else if ($y_source_number==4) $SQL = "UPDATE `water` SET `loc_4` = {$x_loc_4} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
								else if ($y_source_number==5) $SQL = "UPDATE `water` SET `loc_5` = {$x_loc_5} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
									else if ($y_source_number==6) $SQL = "UPDATE `water` SET `loc_6` = {$x_loc_6} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
										else if ($y_source_number==7) $SQL = "UPDATE `water` SET `loc_7` = {$x_loc_7} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
											else if ($y_source_number==8) $SQL = "UPDATE `water` SET `loc_8` = {$x_loc_8} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
												else if ($y_source_number==9) $SQL = "UPDATE `water` SET `loc_9` = {$x_loc_9} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
													else if ($y_source_number==10) $SQL = "UPDATE `water` SET `loc_10` = {$x_loc_10} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
														else if ($y_source_number==11) $SQL = "UPDATE `water` SET `loc_11` = {$x_loc_11} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
															else if ($y_source_number==12) $SQL = "UPDATE `water` SET `loc_12` = {$x_loc_12} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
					$infosystem->Execute($SQL);
				}
				$rsConHydro->MoveNext();
			}
		}
	}
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Water', '{$user}')");
}

$rsTruckAll = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = 'Water'");
$rsTruck = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = 'Water' AND `unit` NOT IN (SELECT `unit` FROM `water` WHERE `input_date` = '{$today}')");

// Gathering data for combo's
$rsWater = $infosystem->Execute("SELECT `unit`, `truck_type`, `area`, `loc_1`, `loc_2`, `loc_3`, `loc_4`, `loc_5`, `loc_6`, `loc_7`, `loc_8`, `loc_9`, `loc_10`, `loc_11`, `loc_12` FROM `water` WHERE `input_date` = '{$today}'");
list($sumKearl1, $sumKearl2, $sumKearl3) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`), SUM(`loc_3`) s3 FROM `water` WHERE `input_date` = '{$today}' AND `area` = 'Kearl'")->fields;
list($sumSyncrude1, $sumSyncrude2, $sumSyncrude3, $sumSyncrude4, $sumSyncrude5, $sumSyncrude6, $sumSyncrude7, $sumSyncrude8, $sumSyncrude9, $sumSyncrude10, $sumSyncrude11, $sumSyncrude12) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2, SUM(`loc_3`) s3, SUM(`loc_4`) s4, SUM(`loc_5`) s5, SUM(`loc_6`) s6, SUM(`loc_7`) s7, SUM(`loc_8`) s8, SUM(`loc_9`) s9, SUM(`loc_10`) s10, SUM(`loc_11`) s11, SUM(`loc_12`) s12 FROM `water` WHERE `input_date` = '{$today}' AND `area` = 'Syncrude'")->fields;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Water - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<? $xajax->printJavascript(); ?>
<script src="CalendarPopupCombined.js"></script>
<script language="javascript">
function CheckForm(f)
{
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
<p>Access Level: <?=$accessLevelDesc[$WaterLvl]?></p>
<table cellpadding="3" cellspacing="1" align="left">
<caption>Kearl</caption>
    <tr>
        <td>Water Source</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
    </tr>
    <tr>
        <td>Description</td>
	<?
 	while(!$chSourceDesc->EOF) {
		list($ch_area,$ch_description) = $chSourceDesc->fields;
		if($ch_area=="Kearl") {
 	?>
		<td align="center" width="75"><?=$ch_description?></td>
	<?
		}
	$chSourceDesc->MoveNext();
	}
    ?>
    </tr>
    <tr>
        <td>Water Amount</td>
        <td align="center"><?=$sumKearl1?></td>
        <td align="center"><?=$sumKearl2?></td>
        <td align="center"><?=$sumKearl3?></td>
    </tr>
</table>
<br clear="all" />
<br />
<table cellpadding="3" cellspacing="1">
<caption>Syncrude</caption>
    <tr>
        <td>Water Source</td>
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
    </tr>
    <tr>
        <td>Description</td>
	<?
	$chSourceDesc->MoveFirst();
 	while(!$chSourceDesc->EOF) {
		list($ch_area,$ch_description) = $chSourceDesc->fields;
		if($ch_area=="Syncrude") {
 	?>
		<td align="center" width="75"><?=$ch_description?></td>
	<?
		}
	$chSourceDesc->MoveNext();
	}
    ?>
    </tr>
    <tr>
        <td>Water Amount</td>
        <td align="center"><?=$sumSyncrude1?></td>
        <td align="center"><?=$sumSyncrude2?></td>
        <td align="center"><?=$sumSyncrude3?></td>
        <td align="center"><?=$sumSyncrude4?></td>
        <td align="center"><?=$sumSyncrude5?></td>
        <td align="center"><?=$sumSyncrude6?></td>
        <td align="center"><?=$sumSyncrude7?></td>
        <td align="center"><?=$sumSyncrude8?></td>
        <td align="center"><?=$sumSyncrude9?></td>
        <td align="center"><?=$sumSyncrude10?></td>
        <td align="center"><?=$sumSyncrude11?></td>
        <td align="center"><?=$sumSyncrude12?></td>
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
        <td align="center">7</td>
        <td align="center">8</td>
        <td align="center">9</td>
        <td align="center">10</td>
        <td align="center">11</td>
        <td align="center">12</td>
    </tr><?
	$i = 1;
	while(!$rsWater->EOF) {
	list($x_unit, $x_truck_type, $x_area, $x_loc_1, $x_loc_2, $x_loc_3, $x_loc_4, $x_loc_5, $x_loc_6, $x_loc_7, $x_loc_8, $x_loc_9, $x_loc_10, $x_loc_11, $x_loc_12) = $rsWater->fields; ?>
    <tr>
        <td align="center"><input type="text" name="unit[W<?=$i?>]" style="width:100px;" value="<?=$x_unit?>" readonly="readonly" /></td>
        <td align="center"><input type="text" name="area[W<?=$i?>]" style="width:80px;" value="<?=$x_area?>" readonly="readonly" /></td>
        <td align="center" width="50"><input type="text" name="loc_1[W<?=$i?>]" size="3" value="<?=$x_loc_1?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_2[W<?=$i?>]" size="3" value="<?=$x_loc_2?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_3[W<?=$i?>]" size="3" value="<?=$x_loc_3?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_4[W<?=$i?>]" size="3" value="<?=$x_loc_4?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_5[W<?=$i?>]" size="3" value="<?=$x_loc_5?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_6[W<?=$i?>]" size="3" value="<?=$x_loc_6?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_7[W<?=$i?>]" size="3" value="<?=$x_loc_7?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_8[W<?=$i?>]" size="3" value="<?=$x_loc_8?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_9[W<?=$i?>]" size="3" value="<?=$x_loc_9?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_10[W<?=$i?>]" size="3" value="<?=$x_loc_10?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_11[W<?=$i?>]" size="3" value="<?=$x_loc_11?>"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_12[W<?=$i?>]" size="3" value="<?=$x_loc_12?>"<?=$readOnly?> /></td>
    </tr><?
	$rsWater->MoveNext();
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
        <td align="center" width="50"><input type="text" name="loc_7[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_8[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_9[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_10[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_11[new]" size="3"<?=$readOnly?> /></td>
        <td align="center" width="50"><input type="text" name="loc_12[new]" size="3"<?=$readOnly?> /></td>
    </tr>
</table>
<p><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></p>
</form>
</div>
<? include("footer.inc"); ?>
</body>
</html>