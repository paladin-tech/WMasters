<?
include("sessionCheck.php");
include("db.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

$user = $_SESSION['username'];

if(isset($_GET['wellId'])) {
	$well_id = $_GET['wellId'];
	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount() != 0) {
		list($designed_north, $designed_east, $rr, $flagged, $survey_requested_BASE, $survey_completed_BASE, $gps_north_BASE, $gps_east_BASE, $GL_BASE, $TOC_BASE, $instrumentation_comments_BASE, $general_comments_BASE, $as_built_completed_BASE, $final_as_built_BASE, $survey_requested_AS_BUILT, $survey_completed_AS_BUILT, $gps_north_AS_BUILT, $gps_east_AS_BUILT, $GL_AS_BUILT, $TOC_AS_BUILT, $instrumentation_comments_AS_BUILT, $general_comments_AS_BUILT, $as_built_completed_AS_BUILT, $final_as_built_AS_BUILT, $survey_requested_R1, $survey_completed_R1, $gps_north_R1, $gps_east_R1, $GL_R1, $TOC_R1, $instrumentation_comments_R1, $general_comments_R1, $as_built_completed_R1, $final_as_built_R1, $survey_requested_R2, $survey_completed_R2, $gps_north_R2, $gps_east_R2, $GL_R2, $TOC_R2, $instrumentation_comments_R2, $general_comments_R2, $as_built_completed_R2, $final_as_built_R2, $survey_requested_R3, $survey_completed_R3, $gps_north_R3, $gps_east_R3, $GL_R3, $TOC_R3, $instrumentation_comments_R3, $general_comments_R3, $as_built_completed_R3, $final_as_built_R3, $survey_requested_R4, $survey_completed_R4, $gps_north_R4, $gps_east_R4, $GL_R4, $TOC_R4, $instrumentation_comments_R4, $general_comments_R4, $as_built_completed_R4, $final_as_built_R4) = $infosystem->Execute("SELECT `designed_north`, `designed_east`, `rr`, `flagged`, `survey_requested_BASE`, `survey_completed_BASE`, `gps_north_BASE`, `gps_east_BASE`, `GL_BASE`, `TOC_BASE`, `instrumentation_comments_BASE`, `general_comments_BASE`, `as_built_completed_BASE`, `final_as_built_BASE`, `survey_requested_AS_BUILT`, `survey_completed_AS_BUILT`, `gps_north_AS_BUILT`, `gps_east_AS_BUILT`, `GL_AS_BUILT`, `TOC_AS_BUILT`, `instrumentation_comments_AS_BUILT`, `general_comments_AS_BUILT`, `as_built_completed_AS_BUILT`, `final_as_built_AS_BUILT`, `survey_requested_R1`, `survey_completed_R1`, `gps_north_R1`, `gps_east_R1`, `GL_R1`, `TOC_R1`, `instrumentation_comments_R1`, `general_comments_R1`, `as_built_completed_R1`, `final_as_built_R1`, `survey_requested_R2`, `survey_completed_R2`, `gps_north_R2`, `gps_east_R2`, `GL_R2`, `TOC_R2`, `instrumentation_comments_R2`, `general_comments_R2`, `as_built_completed_R2`, `final_as_built_R2`, `survey_requested_R3`, `survey_completed_R3`, `gps_north_R3`, `gps_east_R3`, `GL_R3`, `TOC_R3`, `instrumentation_comments_R3`, `general_comments_R3`, `as_built_completed_R3`, `final_as_built_R3`, `survey_requested_R4`, `survey_completed_R4`, `gps_north_R4`, `gps_east_R4`, `GL_R4`, `TOC_R4`, `instrumentation_comments_R4`, `general_comments_R4`, `as_built_completed_R4`, `final_as_built_R4` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;

		$date = new DateTime($rr);
		$date5DaysBefore = new DateTime();
		date_sub($date5DaysBefore, new DateInterval("P5D"));
		$lockedAsBuilt = false;
		if($rr != '0000-00-00 00:00:00' && ($date5DaysBefore > $date) ) {
			$infosystem->Execute("UPDATE `wells_construction` SET `as_built_completed_BASE` = 1, `as_built_completed_AS_BUILT` = 1, `as_built_completed_R1` = 1, `as_built_completed_R2` = 1, `as_built_completed_R3` = 1, `as_built_completed_R4` = 1 WHERE `well_id` = '{$well_id}'");
			$lockedAsBuilt = true;
		}
	}
}

list($SurveyAccessLvl) = $infosystem->Execute("SELECT `SurveyAccess` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($SurveyAccessLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($SurveyAccessLvl == "o" || !(isset($well_id)) || $well_id == "") ? " disabled=\"disabled\"" : "";

$dateFields = array('flagged', 'survey_requested_BASE', 'survey_completed_BASE', 'survey_requested_AS_BUILT', 'survey_completed_AS_BUILT', 'survey_requested_R1', 'survey_completed_R1', 'survey_requested_R2', 'survey_completed_R2', 'survey_requested_R3', 'survey_completed_R3', 'survey_requested_R4', 'survey_completed_R4');

// Dynamic SQL creation and execution
$dbFields = array();
$dbFieldValues = array();
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;

	$rows = array('gps_north', 'gps_east', 'GL', 'TOC', 'instrumentation_comments', 'general_comments');
	$rowsChk = array('as_built_completed', 'final_as_built');
	$columns = array('BASE', 'AS_BUILT', 'R1', 'R2', 'R3', 'R4');

	$as_built_completed_BASE = (isset($_POST['as_built_completed_BASE']) || $lockedAsBuilt) ? 1 : 0;
	$as_built_completed_AS_BUILT = (isset($_POST['as_built_completed_AS_BUILT']) || $lockedAsBuilt) ? 1 : 0;
	$as_built_completed_R1 = (isset($_POST['as_built_completed_R1']) || $lockedAsBuilt) ? 1 : 0;
	$as_built_completed_R2 = (isset($_POST['as_built_completed_R2']) || $lockedAsBuilt) ? 1 : 0;
	$as_built_completed_R3 = (isset($_POST['as_built_completed_R3']) || $lockedAsBuilt) ? 1 : 0;
	$as_built_completed_R4 = (isset($_POST['as_built_completed_R4']) || $lockedAsBuilt) ? 1 : 0;
	$final_as_built_BASE = (isset($_POST['final_as_built_BASE'])) ? 1 : 0;
	$final_as_built_AS_BUILT = (isset($_POST['final_as_built_AS_BUILT'])) ? 1 : 0;
	$final_as_built_R1 = (isset($_POST['final_as_built_R1'])) ? 1 : 0;
	$final_as_built_R2 = (isset($_POST['final_as_built_R2'])) ? 1 : 0;
	$final_as_built_R3 = (isset($_POST['final_as_built_R3'])) ? 1 : 0;
	$final_as_built_R4 = (isset($_POST['final_as_built_R4'])) ? 1 : 0;

	$SQL = "UPDATE `wells_construction` SET `flagged` = '{$flagged}', `survey_completed_BASE` = '{$survey_completed_BASE}', `gps_north_BASE` = '{$gps_north_BASE}', `gps_east_BASE` = '{$gps_east_BASE}', `GL_BASE` = '{$GL_BASE}', `TOC_BASE` = '{$TOC_BASE}', `instrumentation_comments_BASE` = '{$instrumentation_comments_BASE}', `general_comments_BASE` = '{$general_comments_BASE}', `as_built_completed_BASE` = '{$as_built_completed_BASE}', `final_as_built_BASE` = '{$final_as_built_BASE}', `survey_completed_AS_BUILT` = '{$survey_completed_AS_BUILT}', `gps_north_AS_BUILT` = '{$gps_north_AS_BUILT}', `gps_east_AS_BUILT` = '{$gps_east_AS_BUILT}', `GL_AS_BUILT` = '{$GL_AS_BUILT}', `TOC_AS_BUILT` = '{$TOC_AS_BUILT}', `instrumentation_comments_AS_BUILT` = '{$instrumentation_comments_AS_BUILT}', `general_comments_AS_BUILT` = '{$general_comments_AS_BUILT}', `as_built_completed_AS_BUILT` = '{$as_built_completed_AS_BUILT}', `final_as_built_AS_BUILT` = '{$final_as_built_AS_BUILT}', `survey_completed_R1` = '{$survey_completed_R1}', `gps_north_R1` = '{$gps_north_R1}', `gps_east_R1` = '{$gps_east_R1}', `GL_R1` = '{$GL_R1}', `TOC_R1` = '{$TOC_R1}', `instrumentation_comments_R1` = '{$instrumentation_comments_R1}', `general_comments_R1` = '{$general_comments_R1}', `as_built_completed_R1` = '{$as_built_completed_R1}', `final_as_built_R1` = '{$final_as_built_R1}', `survey_completed_R2` = '{$survey_completed_R2}', `gps_north_R2` = '{$gps_north_R2}', `gps_east_R2` = '{$gps_east_R2}', `GL_R2` = '{$GL_R2}', `TOC_R2` = '{$TOC_R2}', `instrumentation_comments_R2` = '{$instrumentation_comments_R2}', `general_comments_R2` = '{$general_comments_R2}', `as_built_completed_R2` = '{$as_built_completed_R2}', `final_as_built_R2` = '{$final_as_built_R2}', `survey_completed_R3` = '{$survey_completed_R3}', `gps_north_R3` = '{$gps_north_R3}', `gps_east_R3` = '{$gps_east_R3}', `GL_R3` = '{$GL_R3}', `TOC_R3` = '{$TOC_R3}', `instrumentation_comments_R3` = '{$instrumentation_comments_R3}', `general_comments_R3` = '{$general_comments_R3}', `as_built_completed_R3` = '{$as_built_completed_R3}', `final_as_built_R3` = '{$final_as_built_R3}', `survey_completed_R4` = '{$survey_completed_R4}', `gps_north_R4` = '{$gps_north_R4}', `gps_east_R4` = '{$gps_east_R4}', `GL_R4` = '{$GL_R4}', `TOC_R4` = '{$TOC_R4}', `instrumentation_comments_R4` = '{$instrumentation_comments_R4}', `general_comments_R4` = '{$general_comments_R4}', `as_built_completed_R4` = '{$as_built_completed_R4}', `final_as_built_R4` = '{$final_as_built_R4}' WHERE `well_id` = '{$selWell}'";

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
	<script type="text/javascript">
		function UpdateDelta(p1, p2, p3) {
			cell_source = 'tdDesigned' + p1;
			desN = document.getElementById(cell_source).innerHTML;
			cell = 'tdDelta' + p1 + p2;
			delta = Math.abs(desN - p3).toFixed(3);
			if(delta>10) delta =  ' <font color="red"><b>' + delta + '</b></font>';
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
	<script type="text/javascript">
		$(document).ready(function() {

			$( ".datepicker" ).datepicker();
			$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

			$( ".datepicker" ).each(function() {
				$(this).datepicker( "setDate", $(this).val() );
			});
			<?
			foreach($dateFields as $val) {
				if($$val != '0000-00-00') {
			?>
			$( "#<?= $val ?>").datepicker( "setDate", "<?= $$val ?>" );
			<?
				}
			}
			?>

			$('#selWell').change(function() {
				window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
			});

			if( (<?= ($lockedAsBuilt) ? "false" : "true" ?>) && $('.finalAsBuilt:checked').length > 1) {
				$('.finalAsBuilt').removeAttr('checked');
				alert('Only one \'Final As-Built\' can be checked!');
			}
			$('.finalAsBuilt').change(function() {
				if($('.finalAsBuilt:checked').length > 1) {
					$('.finalAsBuilt:checked').removeAttr('checked');
					$(this).click();
				}
			});

		});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($well_id)) ? "?wellId={$well_id}" : "" ?>" method="post">
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
					<td><input type="text" id="flagged" class="datepicker" name="flagged" value="<?= (isset($well_id)) ? $flagged : "" ?>"></td>
				</tr>
				<tr>
					<td>Designed North</td>
					<td id="tdDesignedNorth"><?= $designed_north ?></td>
				</tr>
				<tr>
					<td>Designed East</td>
					<td id="tdDesignedEast"><?= $designed_east ?></td>
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
					<td>SURVEY REQUESTED</td>
					<td><?= ($survey_requested_BASE != '0000-00-00') ? $survey_requested_BASE : "" ?></td>
					<td><?= ($survey_requested_AS_BUILT != '0000-00-00') ? $survey_requested_AS_BUILT : "" ?></td>
					<td><?= ($survey_requested_R1 != '0000-00-00') ? $survey_requested_R1 : "" ?></td>
					<td><?= ($survey_requested_R2 != '0000-00-00') ? $survey_requested_R2 : "" ?></td>
					<td><?= ($survey_requested_R3 != '0000-00-00') ? $survey_requested_R3 : "" ?></td>
					<td><?= ($survey_requested_R4 != '0000-00-00') ? $survey_requested_R4 : "" ?></td>
				</tr>
				<tr>
					<td>SURVEY COMPLETED</td>
					<td><input type="text" id="survey_completed_BASE" class="datepicker" name="survey_completed_BASE" value="<?=(isset($well_id))?$survey_completed_BASE:""?>"></td>
					<td><input type="text" id="survey_completed_AS_BUILT" class="datepicker" name="survey_completed_AS_BUILT" value="<?=(isset($well_id))?$survey_completed_AS_BUILT:""?>"></td>
					<td><input type="text" id="survey_completed_R1" class="datepicker" name="survey_completed_R1" value="<?=(isset($well_id))?$survey_completed_R1:""?>"></td>
					<td><input type="text" id="survey_completed_R2" class="datepicker" name="survey_completed_R2" value="<?=(isset($well_id))?$survey_completed_R2:""?>"></td>
					<td><input type="text" id="survey_completed_R3" class="datepicker" name="survey_completed_R3" value="<?=(isset($well_id))?$survey_completed_R3:""?>"></td>
					<td><input type="text" id="survey_completed_R4" class="datepicker" name="survey_completed_R4" value="<?=(isset($well_id))?$survey_completed_R4:""?>"></td>
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
					<td id="tdDeltaNorthBase"><?= (($designed_north - $gps_north_BASE) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_north - $gps_north_BASE), 3, ".", "") . "</b></font>" : number_format(abs($designed_north - $gps_north_BASE), 3, ".", "") ?></td>
					<td id="tdDeltaNorthAsBuilt"><?= (($designed_north - $gps_north_AS_BUILT) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_north - $gps_north_AS_BUILT), 3, ".", "") . "</b></font>" : number_format(abs($designed_north - $gps_north_AS_BUILT), 3, ".", "") ?></td>
					<td id="tdDeltaNorthR1"><?= (($designed_north - $gps_north_R1) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_north - $gps_north_R1), 3, ".", "") . "</b></font>" : number_format(abs($designed_north - $gps_north_R1), 3, ".", "") ?></td>
					<td id="tdDeltaNorthR2"><?= (($designed_north - $gps_north_R2) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_north - $gps_north_R2), 3, ".", "") . "</b></font>" : number_format(abs($designed_north - $gps_north_R2), 3, ".", "") ?></td>
					<td id="tdDeltaNorthR3"><?= (($designed_north - $gps_north_R3) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_north - $gps_north_R3), 3, ".", "") . "</b></font>" : number_format(abs($designed_north - $gps_north_R3), 3, ".", "") ?></td>
					<td id="tdDeltaNorthR4"><?= (($designed_north - $gps_north_R4) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_north - $gps_north_R4), 3, ".", "") . "</b></font>" : number_format(abs($designed_north - $gps_north_R4), 3, ".", "") ?></td>
				</tr>
				<tr>
					<td>&Delta;E</td>
					<td id="tdDeltaEastBase"><?= (($designed_east - $gps_east_BASE) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_east - $gps_east_BASE), 3, ".", "") . "</b></font>" : number_format(abs($designed_east - $gps_east_BASE), 3, ".", "") ?></td>
					<td id="tdDeltaEastAsBuilt"><?= (($designed_east - $gps_east_AS_BUILT) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_east - $gps_east_AS_BUILT), 3, ".", "") . "</b></font>" : number_format(abs($designed_east - $gps_east_AS_BUILT), 3, ".", "") ?></td>
					<td id="tdDeltaEastR1"><?= (($designed_east - $gps_east_R1) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_east - $gps_east_R1), 3, ".", "") . "</b></font>" : number_format(abs($designed_east - $gps_east_R1), 3, ".", "") ?></td>
					<td id="tdDeltaEastR2"><?= (($designed_east - $gps_east_R2) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_east - $gps_east_R2), 3, ".", "") . "</b></font>" : number_format(abs($designed_east - $gps_east_R2), 3, ".", "") ?></td>
					<td id="tdDeltaEastR3"><?= (($designed_east - $gps_east_R3) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_east - $gps_east_R3), 3, ".", "") . "</b></font>" : number_format(abs($designed_east - $gps_east_R3), 3, ".", "") ?></td>
					<td id="tdDeltaEastR4"><?= (($designed_east - $gps_east_R4) > 10) ? "<font color=\"red\"><b>" . number_format(abs($designed_east - $gps_east_R4), 3, ".", "") . "</b></font>" : number_format(abs($designed_east - $gps_east_R4), 3, ".", "") ?></td>
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
					<td><input type="checkbox" name="as_built_completed_BASE" id="as_built_completed_BASE" value="1"<?=(isset($well_id) && $as_built_completed_BASE == 1)?" checked":""?><?= ($lockedAsBuilt) ? " disabled" : ""?>></td>
					<td><input type="checkbox" name="as_built_completed_AS_BUILT" id="as_built_completed_AS_BUILT" value="1"<?=(isset($well_id) && $as_built_completed_AS_BUILT == 1)?" checked":""?><?= ($lockedAsBuilt) ? " disabled" : ""?>></td>
					<td><input type="checkbox" name="as_built_completed_R1" id="as_built_completed_R1" value="1"<?=(isset($well_id) && $as_built_completed_R1 == 1)?" checked":""?><?= ($lockedAsBuilt) ? " disabled" : ""?>></td>
					<td><input type="checkbox" name="as_built_completed_R2" id="as_built_completed_R2" value="1"<?=(isset($well_id) && $as_built_completed_R2 == 1)?" checked":""?><?= ($lockedAsBuilt) ? " disabled" : ""?>></td>
					<td><input type="checkbox" name="as_built_completed_R3" id="as_built_completed_R3" value="1"<?=(isset($well_id) && $as_built_completed_R3 == 1)?" checked":""?><?= ($lockedAsBuilt) ? " disabled" : ""?>></td>
					<td><input type="checkbox" name="as_built_completed_R4" id="as_built_completed_R4" value="1"<?=(isset($well_id) && $as_built_completed_R4 == 1)?" checked":""?><?= ($lockedAsBuilt) ? " disabled" : ""?>></td>
				</tr>
				<tr>
					<td>Final As-Built</td>
					<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_BASE" id="final_as_built_BASE" value="1"<?=(isset($well_id) && $final_as_built_BASE == 1)?" checked":""?>></td>
					<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_AS_BUILT" id="final_as_built_AS_BUILT" value="1"<?=(isset($well_id) && $final_as_built_AS_BUILT == 1)?" checked":""?>></td>
					<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R1" id="final_as_built_R1" value="1"<?=(isset($well_id) && $final_as_built_R1 == 1)?" checked":""?>></td>
					<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R2" id="final_as_built_R2" value="1"<?=(isset($well_id) && $final_as_built_R2 == 1)?" checked":""?>></td>
					<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R3" id="final_as_built_R3" value="1"<?=(isset($well_id) && $final_as_built_R3 == 1)?" checked":""?>></td>
					<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R4" id="final_as_built_R4" value="1"<?=(isset($well_id) && $final_as_built_R4 == 1)?" checked":""?>></td>
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
	<script>
	$(document).ready(function() {
		$( ".datepicker" ).each(function() {
			$(this).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$(this).datepicker( "setDate", $(this).val() );
		});
	});
	</script>
</html><?
} ?>