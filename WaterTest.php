<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
$infosystem->debug = true;

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());
$user = $_SESSION['username'];
// $conType = $_SESSION['conType'];
$conType = "Water";

list($WaterVacuumLvl) = $infosystem->Execute("SELECT `Water` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($WaterVacuumLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($WaterVacuumLvl=="o")?" disabled=\"disabled\"":"";
list($DistinctSourcesCount) = $infosystem->Execute("SELECT count(distinct(`source_number`)) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}'")->fields;

if(isset($_POST['submit'])) {
	foreach($_POST['unit'] as $key => $value) {
		echo $value, " / ", $key, " / ";
		$fieldsArray = array("unit", "area", "source_number", "volume");
		foreach($fieldsArray as $fa) {
			$var = "x_{$fa}";
			$$var = mysql_real_escape_string($_POST[$fa][$key]);
			if((substr($fa, 0, 3)=="loc")&&$$var=="") $$var = 0;
		}

		if($x_unit!="") {
			if($key=="new") {
				$SQL = "INSERT INTO `watervacuum`(`input_date`, `unit`, `truck_type`, `user`) VALUES('{$today}', '{$x_unit}', '{$conType}', '{$user}')";
				$infosystem->Execute($SQL);
			}

			$SQL = "UPDATE `watervacuum` SET `area` = '{$x_area}' WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
			$infosystem->Execute($SQL);
			$rsConWatVac = $infosystem->Execute("SELECT `area`, `source_number` FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}'");

			while(!$rsConWatVac->EOF) {
				list($y_area, $y_source_number) = $rsConWatVac->fields;
				if($x_area==$y_area) {
					if ($y_source_number==1) $SQL = "UPDATE `watervacuum` SET `loc_1` = {$x_loc_1} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
					else if ($y_source_number==2) $SQL = "UPDATE `watervacuum` SET `loc_2` = {$x_loc_2} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
						else if ($y_source_number==3) $SQL = "UPDATE `watervacuum` SET `loc_3` = {$x_loc_3} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
							else if ($y_source_number==4) $SQL = "UPDATE `watervacuum` SET `loc_4` = {$x_loc_4} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
								else if ($y_source_number==5) $SQL = "UPDATE `watervacuum` SET `loc_5` = {$x_loc_5} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
									else if ($y_source_number==6) $SQL = "UPDATE `watervacuum` SET `loc_6` = {$x_loc_6} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
										else if ($y_source_number==7) $SQL = "UPDATE `watervacuum` SET `loc_7` = {$x_loc_7} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
											else if ($y_source_number==8) $SQL = "UPDATE `watervacuum` SET `loc_8` = {$x_loc_8} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
												else if ($y_source_number==9) $SQL = "UPDATE `watervacuum` SET `loc_9` = {$x_loc_9} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
													else if ($y_source_number==10) $SQL = "UPDATE `watervacuum` SET `loc_10` = {$x_loc_10} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
														else if ($y_source_number==11) $SQL = "UPDATE `watervacuum` SET `loc_11` = {$x_loc_11} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
															else if ($y_source_number==12) $SQL = "UPDATE `watervacuum` SET `loc_12` = {$x_loc_12} WHERE `input_date` = '{$today}' AND `unit` = '{$x_unit}'";
					$infosystem->Execute($SQL);
				}
				$rsConWatVac->MoveNext();
			}
		}
	}
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('{$conType}', '{$user}')");
}

$rsTruckAll = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = '{$conType}'");
$rsTruck = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = '{$conType}' AND `unit` NOT IN (SELECT `unit` FROM `watervacuum` WHERE `input_date` = '{$today}' AND `truck_type` = '{$conType}')");

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
<p>Access Level: <?=$accessLevelDesc[$WaterVacuumLvl]?></p>
<?
$rsAreas = $infosystem->Execute("SELECT distinct(`area`) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0  AND `type` = '{$conType}' ORDER BY `area`");

while(!$rsAreas->EOF) {
	list($l_area) = $rsAreas->fields; ?>
	<table cellpadding="3" cellspacing="1">
		<?=$l_area?>
	    <tr>
		<td>Water Source</td>
		<?
		$rsSources = $infosystem->Execute("SELECT `source_number` FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `area` = '$l_area' AND `type` = '{$conType}' ORDER BY `source_number`");

		while(!$rsSources->EOF) {
			list($l_sources) = $rsSources->fields; ?>
			<td align="center" width="50"><?=$l_sources?></td>
			<?
		$rsSources->MoveNext();
		} ?>
	    </tr>
	    <tr>
		<td><?=$conType?> Amount</td>
		<?
		$rsSources = $infosystem->Execute("SELECT `source_number` FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `area` = '$l_area' AND `type` = '{$conType}' ORDER BY `source_number`");

		while(!$rsSources->EOF) {
			list($l_sources) = $rsSources->fields;
			list($sum_source) = $infosystem->Execute("SELECT SUM(`volume`) FROM `watervacuum` WHERE `input_date` = '{$today}' AND `area` = '$l_area' AND `truck_type` = '{$conType}' AND `source_number` = '{$l_sources}'")->fields;
			?>
			<td align="center"><?=$sum_source?></td>
			<?
		$rsSources->MoveNext();
		} ?>
	    </tr>
	</table>
	<br clear="all" />
	<br />
	<?
$rsAreas->MoveNext();
} ?>
<table cellpadding="3" cellspacing="1">
	DATE
	<tr>
    	<td>This is today input only. If you want to make or change any inputs in the past please contact WM Field Team.</td>
    </tr>
</table>
<br clear="all" />
<br />
<form name="frm" method="post" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return CheckForm(this)">
<input type="hidden" name="input_date" value="<?=$today?>" />
<table cellpadding="3" cellspacing="1">
    <tr>
    <td colspan="2"></td>
    <td align="center" colspan="<?=$DistinctSourcesCount?>"><?=$conType?> Sources</td>
    </tr>
    <tr>
        <td align="center">Unit</td>
        <td align="center">Area</td>
		<?
		$rsDistinctSources = $infosystem->Execute("SELECT distinct(`source_number`) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}' ORDER BY `source_number`");

		while(!$rsDistinctSources->EOF) {
			list($l_distinctSources) = $rsDistinctSources->fields; ?>
			<td align="center"><?=$l_distinctSources?></td>
			<?
		$rsDistinctSources->MoveNext();
		} ?>
    </tr><?
	$rsWaterVacuum = $infosystem->Execute("SELECT `unit`, `area` FROM `watervacuum` WHERE `input_date` = '{$today}' AND `truck_type` = '{$conType}'");
	$i = 1;
	while(!$rsWaterVacuum->EOF) {
	list($x_unit, $x_area) = $rsWaterVacuum->fields; ?>
    <tr>
        <td align="center"><input type="text" name="unit[W<?=$i?>]" style="width:100px;" value="<?=$x_unit?>" readonly="readonly" /></td>
        <td align="center"><input type="text" name="area[W<?=$i?>]" style="width:80px;" value="<?=$x_area?>" readonly="readonly" /></td>
		<?
		$rsDistinctSources = $infosystem->Execute("SELECT distinct(`source_number`) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}' ORDER BY `source_number`");

		while(!$rsDistinctSources->EOF) {
			list($l_distinctSources) = $rsDistinctSources->fields;
			list($validSource) = $infosystem->Execute("SELECT count(`source_number`) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}' AND `source_number` = '{$l_distinctSources}' AND `area` = '{$x_area}'")->fields;
			?>
			<td align="center" width="50">
			<?
			if ($validSource!=0) {
				list($x_volume) = $infosystem->Execute("SELECT `volume` FROM `watervacuum` WHERE `input_date` = '{$today}' AND `truck_type` = '{$conType}' AND `unit` = '{$x_unit}' AND `area` = '{$x_area}' AND `source_number` = '{$l_distinctSources}'")->fields;
				?>
				<input type="text" name="volume[S<?=$l_distinctSources?>][R<?=$i?>]" size="3" value="<?=$x_volume?>"<?=$readOnly?> />
				<?
			}
			?>
			</td>
			<?
		$rsDistinctSources->MoveNext();
		} ?>
    </tr>
    <?
	$rsWaterVacuum->MoveNext();
	$i++;
	}
	?>
    <tr>
<!--Dynamic TRUCK UNIT drop down from "trucks" table -->
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
<!--Dynamic AREA drop down from "construction_water_vaccum" table -->
        <td align="center">
        	<select name="area[new]" style="width:100px;" >
            	<option value=""></option>
            	<?
				$rsAreas = $infosystem->Execute("SELECT distinct(`area`) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}' ORDER BY `area`");

				while(!$rsAreas->EOF) {
					list($l_area) = $rsAreas->fields; ?>
            		<option value="<?=$l_area?>"><?=$l_area?></option>
            		<?
				$rsAreas->MoveNext();
				} ?>
            </select>
        </td>
		<?
		$rsDistinctSources = $infosystem->Execute("SELECT distinct(`source_number`) FROM `construction_water_vaccum` WHERE `ReadOnly` = 0 AND `type` = '{$conType}' ORDER BY `source_number`");

		while(!$rsDistinctSources->EOF) {
			list($l_distinctSources) = $rsDistinctSources->fields;
			?>
			<td align="center" width="50"><input type="text" name="volume[S<?=$l_distinctSources?>][new]" size="3"<?=$readOnly?> /></td>
			<?
		$rsDistinctSources->MoveNext();
		} ?>
    </tr>
</table>
<p><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></p>
</form>
<!--For this form please use "Google Chrome" or "Firefox"-->
</div>
<? include("footer.inc"); ?>
</body>
</html>