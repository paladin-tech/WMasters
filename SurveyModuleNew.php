<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
// $infosystem->debug = true;

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

$user = $_SESSION['username'];

list($SurveyAccessLvl) = $infosystem->Execute("SELECT `SurveyAccess` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($SurveyAccessLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($SurveyAccessLvl=="o")?" disabled=\"disabled\"":"";

if(isset($_GET['wellId'])) {
	$well_id = $_GET['wellId'];
	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount() != 0) {
		list($gps_north_BASE, $gps_east_BASE, $GL_BASE, $TOC_BASE, $instrumentation_comments_BASE, $general_comments_BASE, $as_built_completed_BASE, $final_as_built_BASE, $gps_north_AS_BUILT, $gps_east_AS_BUILT, $GL_AS_BUILT, $TOC_AS_BUILT, $instrumentation_comments_AS_BUILT, $general_comments_AS_BUILT, $as_built_completed_AS_BUILT, $final_as_built_AS_BUILT, $gps_north_R1, $gps_east_R1, $GL_R1, $TOC_R1, $instrumentation_comments_R1, $general_comments_R1, $as_built_completed_R1, $final_as_built_R1, $gps_north_R2, $gps_east_R2, $GL_R2, $TOC_R2, $instrumentation_comments_R2, $general_comments_R2, $as_built_completed_R2, $final_as_built_R2, $gps_north_R3, $gps_east_R3, $GL_R3, $TOC_R3, $instrumentation_comments_R3, $general_comments_R3, $as_built_completed_R3, $final_as_built_R3, $gps_north_R4, $gps_east_R4, $GL_R4, $TOC_R4, $instrumentation_comments_R4, $general_comments_R4, $as_built_completed_R4, $final_as_built_R4) = $infosystem->Execute("SELECT `gps_north_BASE`, `gps_east_BASE`, `GL_BASE`, `TOC_BASE`, `instrumentation_comments_BASE`, `general_comments_BASE`, `as_built_completed_BASE`, `final_as_built_BASE`, `gps_north_AS_BUILT`, `gps_east_AS_BUILT`, `GL_AS_BUILT`, `TOC_AS_BUILT`, `instrumentation_comments_AS_BUILT`, `general_comments_AS_BUILT`, `as_built_completed_AS_BUILT`, `final_as_built_AS_BUILT`, `gps_north_R1`, `gps_east_R1`, `GL_R1`, `TOC_R1`, `instrumentation_comments_R1`, `general_comments_R1`, `as_built_completed_R1`, `final_as_built_R1`, `gps_north_R2`, `gps_east_R2`, `GL_R2`, `TOC_R2`, `instrumentation_comments_R2`, `general_comments_R2`, `as_built_completed_R2`, `final_as_built_R2`, `gps_north_R3`, `gps_east_R3`, `GL_R3`, `TOC_R3`, `instrumentation_comments_R3`, `general_comments_R3`, `as_built_completed_R3`, `final_as_built_R3`, `gps_north_R4`, `gps_east_R4`, `GL_R4`, `TOC_R4`, `instrumentation_comments_R4`, `general_comments_R4`, `as_built_completed_R4`, `final_as_built_R4` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;
	}
}

// Dynamic SQL creation and execution
$dbFields = array();
$dbFieldValues = array();
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;


	$rows = array('gps_north', 'gps_east', 'GL', 'TOC', 'instrumentation_comments', 'general_comments');
	$rowsChk = array('as_built_completed', 'final_as_built');
	$columns = array('BASE', 'AS_BUILT', 'R1', 'R2', 'R3', 'R4');

	$as_built_completed_BASE = (isset($as_built_completed_BASE)) ? 1 : 0;
	$as_built_completed_AS_BUILT = (isset($as_built_completed_AS_BUILT)) ? 1 : 0;
	$as_built_completed_R1 = (isset($as_built_completed_R1)) ? 1 : 0;
	$as_built_completed_R2 = (isset($as_built_completed_R2)) ? 1 : 0;
	$as_built_completed_R3 = (isset($as_built_completed_R3)) ? 1 : 0;
	$as_built_completed_R4 = (isset($as_built_completed_R4)) ? 1 : 0;
	$final_as_built_BASE = (isset($final_as_built_BASE)) ? 1 : 0;
	$final_as_built_AS_BUILT = (isset($final_as_built_AS_BUILT)) ? 1 : 0;
	$final_as_built_R1 = (isset($final_as_built_R1)) ? 1 : 0;
	$final_as_built_R2 = (isset($final_as_built_R2)) ? 1 : 0;
	$final_as_built_R3 = (isset($final_as_built_R3)) ? 1 : 0;
	$final_as_built_R4 = (isset($final_as_built_R4)) ? 1 : 0;

	$SQL = "UPDATE `wells_construction` SET `gps_north_BASE` = '{$gps_north_BASE}', `gps_east_BASE` = '{$gps_east_BASE}', `GL_BASE` = '{$GL_BASE}', `TOC_BASE` = '{$TOC_BASE}', `instrumentation_comments_BASE` = '{$instrumentation_comments_BASE}', `general_comments_BASE` = '{$general_comments_BASE}', `as_built_completed_BASE` = '{$as_built_completed_BASE}', `final_as_built_BASE` = '{$final_as_built_BASE}', `gps_north_AS_BUILT` = '{$gps_north_AS_BUILT}', `gps_east_AS_BUILT` = '{$gps_east_AS_BUILT}', `GL_AS_BUILT` = '{$GL_AS_BUILT}', `TOC_AS_BUILT` = '{$TOC_AS_BUILT}', `instrumentation_comments_AS_BUILT` = '{$instrumentation_comments_AS_BUILT}', `general_comments_AS_BUILT` = '{$general_comments_AS_BUILT}', `as_built_completed_AS_BUILT` = '{$as_built_completed_AS_BUILT}', `final_as_built_AS_BUILT` = '{$final_as_built_AS_BUILT}', `gps_north_R1` = '{$gps_north_R1}', `gps_east_R1` = '{$gps_east_R1}', `GL_R1` = '{$GL_R1}', `TOC_R1` = '{$TOC_R1}', `instrumentation_comments_R1` = '{$instrumentation_comments_R1}', `general_comments_R1` = '{$general_comments_R1}', `as_built_completed_R1` = '{$as_built_completed_R1}', `final_as_built_R1` = '{$final_as_built_R1}', `gps_north_R2` = '{$gps_north_R2}', `gps_east_R2` = '{$gps_east_R2}', `GL_R2` = '{$GL_R2}', `TOC_R2` = '{$TOC_R2}', `instrumentation_comments_R2` = '{$instrumentation_comments_R2}', `general_comments_R2` = '{$general_comments_R2}', `as_built_completed_R2` = '{$as_built_completed_R2}', `final_as_built_R2` = '{$final_as_built_R2}', `gps_north_R3` = '{$gps_north_R3}', `gps_east_R3` = '{$gps_east_R3}', `GL_R3` = '{$GL_R3}', `TOC_R3` = '{$TOC_R3}', `instrumentation_comments_R3` = '{$instrumentation_comments_R3}', `general_comments_R3` = '{$general_comments_R3}', `as_built_completed_R3` = '{$as_built_completed_R3}', `final_as_built_R3` = '{$final_as_built_R3}', `gps_north_R4` = '{$gps_north_R4}', `gps_east_R4` = '{$gps_east_R4}', `GL_R4` = '{$GL_R4}', `TOC_R4` = '{$TOC_R4}', `instrumentation_comments_R4` = '{$instrumentation_comments_R4}', `general_comments_R4` = '{$general_comments_R4}', `as_built_completed_R4` = '{$as_built_completed_R4}', `final_as_built_R4` = '{$final_as_built_R4}' WHERE `well_id` = '{$selWell}'";

	$infosystem->Execute($SQL);
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Survey Module', '{$user}')");
	include("confirm.html");

	ob_end_flush();
} else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Survey Module - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<? $xajax->printJavascript(); ?>
	<script src="js/CalendarPopupCombined.js"></script>
	<script type="text/javascript">
		function UpdateDelta(p1, p2, p3) {
			cell_source = 'tdDesigned' + p1;
			desN = document.getElementById(cell_source).innerHTML;
			cell = 'tdDelta' + p1 + p2;
			delta = Math.abs(desN - p3);
			if(delta>10) delta +=  ' <font color="red"><b>!!</b></font>';
			document.getElementById(cell).innerHTML = delta;
		}
	</script>
	<script type="text/javascript">
		<!--
		function popup(mylink, windowname)
		{
			if (! window.focus)return true;
			var href;
			if (typeof(mylink) == 'string')
				href=mylink;
			else
				href=mylink.href;
			window.open(href, windowname, 'width=400,height=200,scrollbars=yes');
			return false;
		}
		//-->
	</script>
	<script>
		$(function() {
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {

			$( ".datepicker" ).datepicker();
			$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

			$('#selWell').change(function() {
				window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
			});

		});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr valign="top">
		<td width="300">
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
				<tr>
					<td>Well ID (Lease ID)</td>
					<td>
						<select name="selWell" id="selWell">
							<option value=""></option><?
							while(!$rsWellLicence->EOF) {
								list($y_well_id) = $rsWellLicence->fields; ?>
								<option value="<?=$y_well_id?>"<?= (isset($wellId) && $wellId == $y_well_id) ? " selected" : "" ?>><?=$y_well_id?></option><?
								$rsWellLicence->MoveNext();
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Flag Requested</td>
					<td id="tdFlagRequested">&nbsp;</td>
				</tr>
				<tr>
					<td>Flag Done</td>
					<td><input type="text" id="datepicker1" class="datepicker"></td>
				</tr>
				<tr>
					<td>Designed North</td>
					<td id="tdDesignedNorth">&nbsp;</td>
				</tr>
				<tr>
					<td>Designed East</td>
					<td id="tdDesignedEast">&nbsp;</td>
				</tr>
			</table>
		</td>
		<td>
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
				<tr>
					<td>Date</td>
					<td>Pre-stake</td>
					<td>As-Built</td>
					<td>R1</td>
					<td>R2</td>
					<td>R3</td>
					<td>R4</td>
				</tr>
				<tr>
					<td colspan="7">SURVEY REQUESTED</td>
				</tr>
				<tr>
					<td colspan="7">SURVEY COMPLETED</td>
				</tr>
				<tr>
					<td>As Built GPS N</td>
					<td><input class="GPS" type="text" name="gps_north_BASE" id="gps_north_BASE" value="<?=(isset($well_id))?$gps_north_BASE:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'Base', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_north_AS_BUILT" id="gps_north_AS_BUILT" value="<?=(isset($well_id))?$gps_north_AS_BUILT:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'AsBuilt', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_north_R1" id="gps_north_R1" value="<?=(isset($well_id))?$gps_north_R1:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'R1', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_north_R2" id="gps_north_R2" value="<?=(isset($well_id))?$gps_north_R2:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'R2', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_north_R3" id="gps_north_R3" value="<?=(isset($well_id))?$gps_north_R3:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'R3', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_north_R4" id="gps_north_R4" value="<?=(isset($well_id))?$gps_north_R4:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'R4', this.value)"></td>
				</tr>
				<tr>
					<td>As Built GPS E</td>
					<td><input class="GPS" type="text" name="gps_east_BASE" id="gps_east_BASE" value="<?=(isset($well_id))?$gps_east_BASE:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'Base', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_east_AS_BUILT" id="gps_east_AS_BUILT" value="<?=(isset($well_id))?$gps_east_AS_BUILT:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'AsBuilt', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_east_R1" id="gps_east_R1" value="<?=(isset($well_id))?$gps_east_R1:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'R1', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_east_R2" id="gps_east_R2" value="<?=(isset($well_id))?$gps_east_R2:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'R2', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_east_R3" id="gps_east_R3" value="<?=(isset($well_id))?$gps_east_R3:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'R3', this.value)"></td>
					<td><input class="GPS" type="text" name="gps_east_R4" id="gps_east_R4" value="<?=(isset($well_id))?$gps_east_R4:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'R4', this.value)"></td>
				</tr>
				<tr>
					<td>GL</td>
					<td><input class="GPS" type="text" name="GL_BASE" id="GL_BASE" value="<?=(isset($well_id))?$GL_BASE:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="GL_AS_BUILT" id="GL_AS_BUILT" value="<?=(isset($well_id))?$GL_AS_BUILT:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="GL_R1" id="GL_R1" value="<?=(isset($well_id))?$GL_R1:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="GL_R2" id="GL_R2" value="<?=(isset($well_id))?$GL_R2:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="GL_R3" id="GL_R3" value="<?=(isset($well_id))?$GL_R3:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="GL_R4" id="GL_R4" value="<?=(isset($well_id))?$GL_R4:""?>"<?=$readOnly?>></td>
				</tr>
				<tr>
					<td>TOC</td>
					<td><input class="GPS" type="text" name="TOC_BASE" id="TOC_BASE" value="<?=(isset($well_id))?$TOC_BASE:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="TOC_AS_BUILT" id="TOC_AS_BUILT" value="<?=(isset($well_id))?$TOC_AS_BUILT:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="TOC_R1" id="TOC_R1" value="<?=(isset($well_id))?$TOC_R1:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="TOC_R2" id="TOC_R2" value="<?=(isset($well_id))?$TOC_R2:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="TOC_R3" id="TOC_R3" value="<?=(isset($well_id))?$TOC_R3:""?>"<?=$readOnly?>></td>
					<td><input class="GPS" type="text" name="TOC_R4" id="TOC_R4" value="<?=(isset($well_id))?$TOC_R4:""?>"<?=$readOnly?>></td>
				</tr>
				<tr>
					<td>&Delta;N</td>
					<td id="tdDeltaNorthBase"></td>
					<td id="tdDeltaNorthAsBuilt"></td>
					<td id="tdDeltaNorthR1"></td>
					<td id="tdDeltaNorthR2"></td>
					<td id="tdDeltaNorthR3"></td>
					<td id="tdDeltaNorthR4"></td>
				</tr>
				<tr>
					<td>&Delta;E</td>
					<td id="tdDeltaEastBase"></td>
					<td id="tdDeltaEastAsBuilt"></td>
					<td id="tdDeltaEastR1"></td>
					<td id="tdDeltaEastR2"></td>
					<td id="tdDeltaEastR3"></td>
					<td id="tdDeltaEastR4"></td>
				</tr>
				<tr>
					<td>Instrumentation Comments</td>
					<td><textarea name="instrumentation_comments_BASE" id="instrumentation_comments_BASE"><?=(isset($well_id))?$instrumentation_comments_BASE:""?></textarea></td>
					<td><textarea name="instrumentation_comments_AS_BUILT" id="instrumentation_comments_AS_BUILT"><?=(isset($well_id))?$instrumentation_comments_AS_BUILT:""?></textarea></td>
					<td><textarea name="instrumentation_comments_R1" id="instrumentation_comments_R1"><?=(isset($well_id))?$instrumentation_comments_R1:""?></textarea></td>
					<td><textarea name="instrumentation_comments_R2" id="instrumentation_comments_R2"><?=(isset($well_id))?$instrumentation_comments_R2:""?></textarea></td>
					<td><textarea name="instrumentation_comments_R3" id="instrumentation_comments_R3"><?=(isset($well_id))?$instrumentation_comments_R3:""?></textarea></td>
					<td><textarea name="instrumentation_comments_R4" id="instrumentation_comments_R4"><?=(isset($well_id))?$instrumentation_comments_R4:""?></textarea></td>
				</tr>
				<tr>
					<td>General Comments</td>
					<td><textarea name="general_comments_BASE" id="general_comments_BASE"><?=(isset($well_id))?$general_comments_BASE:""?></textarea></td>
					<td><textarea name="general_comments_AS_BUILT" id="general_comments_AS_BUILT"><?=(isset($well_id))?$general_comments_AS_BUILT:""?></textarea></td>
					<td><textarea name="general_comments_R1" id="general_comments_R1"><?=(isset($well_id))?$general_comments_R1:""?></textarea></td>
					<td><textarea name="general_comments_R2" id="general_comments_R2"><?=(isset($well_id))?$general_comments_R2:""?></textarea></td>
					<td><textarea name="general_comments_R3" id="general_comments_R3"><?=(isset($well_id))?$general_comments_R3:""?></textarea></td>
					<td><textarea name="general_comments_R4" id="general_comments_R4"><?=(isset($well_id))?$general_comments_R4:""?></textarea></td>
				</tr>
				<tr>
					<td>As-Built Completed</td>
					<td><input type="checkbox" name="as_built_completed_BASE" id="as_built_completed_BASE" value="1"<?=(isset($well_id) && $as_built_completed_BASE == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="as_built_completed_AS_BUILT" id="as_built_completed_AS_BUILT" value="1"<?=(isset($well_id) && $as_built_completed_AS_BUILT == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="as_built_completed_R1" id="as_built_completed_R1" value="1"<?=(isset($well_id) && $as_built_completed_R1 == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="as_built_completed_R2" id="as_built_completed_R2" value="1"<?=(isset($well_id) && $as_built_completed_R2 == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="as_built_completed_R3" id="as_built_completed_R3" value="1"<?=(isset($well_id) && $as_built_completed_R3 == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="as_built_completed_R4" id="as_built_completed_R4" value="1"<?=(isset($well_id) && $as_built_completed_R4 == 1)?" checked":""?>></td>
				</tr>
				<tr>
					<td>Final As-Built</td>
					<td><input type="checkbox" name="final_as_built_BASE" id="final_as_built_BASE" value="1"<?=(isset($well_id) && $final_as_built_BASE == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="final_as_built_AS_BUILT" id="final_as_built_AS_BUILT" value="1"<?=(isset($well_id) && $final_as_built_AS_BUILT == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="final_as_built_R1" id="final_as_built_R1" value="1"<?=(isset($well_id) && $final_as_built_R1 == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="final_as_built_R2" id="final_as_built_R2" value="1"<?=(isset($well_id) && $final_as_built_R2 == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="final_as_built_R3" id="final_as_built_R3" value="1"<?=(isset($well_id) && $final_as_built_R3 == 1)?" checked":""?>></td>
					<td><input type="checkbox" name="final_as_built_R4" id="final_as_built_R4" value="1"<?=(isset($well_id) && $final_as_built_R4 == 1)?" checked":""?>></td>
				</tr>
				<tr>
					<td colspan="7">Note: Make sure that your delta is +/- 10m</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><a href="SurveyModuleHelp.html" onClick="return popup(this, 'notes')">HELP</a>&nbsp;<input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></td>
	</tr>
</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html><?
} ?>