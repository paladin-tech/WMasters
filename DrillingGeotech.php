<?
include("sessionCheck.php");
include("db.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `active` = 1  ORDER BY `mainboard`");

if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	$infosystem->Execute("UPDATE `wells_construction` SET `flagged` = '{$flagged}', `survey_requested_BASE` = '{$survey_requested_BASE}', `survey_requested_AS_BUILT` = '{$survey_requested_AS_BUILT}', `survey_requested_R1` = '{$survey_requested_R1}', `survey_requested_R2` = '{$survey_requested_R2}', `survey_requested_R3` = '{$survey_requested_R3}', `survey_requested_R4` = '{$survey_requested_R4}' WHERE `well_id` = '{$selWell}'");
}

if(isset($_GET['wellId'])) {
	$well_id = $_GET['wellId'];
	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");
	if($check->RecordCount() != 0) {
		list($designed_north, $designed_east, $rr, $flagged, $survey_requested_BASE, $survey_completed_BASE, $gps_north_BASE, $gps_east_BASE, $GL_BASE, $TOC_BASE, $instrumentation_comments_BASE, $general_comments_BASE, $as_built_completed_BASE, $final_as_built_BASE, $survey_requested_AS_BUILT, $survey_completed_AS_BUILT, $gps_north_AS_BUILT, $gps_east_AS_BUILT, $GL_AS_BUILT, $TOC_AS_BUILT, $instrumentation_comments_AS_BUILT, $general_comments_AS_BUILT, $as_built_completed_AS_BUILT, $final_as_built_AS_BUILT, $survey_requested_R1, $survey_completed_R1, $gps_north_R1, $gps_east_R1, $GL_R1, $TOC_R1, $instrumentation_comments_R1, $general_comments_R1, $as_built_completed_R1, $final_as_built_R1, $survey_requested_R2, $survey_completed_R2, $gps_north_R2, $gps_east_R2, $GL_R2, $TOC_R2, $instrumentation_comments_R2, $general_comments_R2, $as_built_completed_R2, $final_as_built_R2, $survey_requested_R3, $survey_completed_R3, $gps_north_R3, $gps_east_R3, $GL_R3, $TOC_R3, $instrumentation_comments_R3, $general_comments_R3, $as_built_completed_R3, $final_as_built_R3, $survey_requested_R4, $survey_completed_R4, $gps_north_R4, $gps_east_R4, $GL_R4, $TOC_R4, $instrumentation_comments_R4, $general_comments_R4, $as_built_completed_R4, $final_as_built_R4) = $infosystem->Execute("SELECT `designed_north`, `designed_east`, `rr`, `flagged`, `survey_requested_BASE`, `survey_completed_BASE`, `gps_north_BASE`, `gps_east_BASE`, `GL_BASE`, `TOC_BASE`, `instrumentation_comments_BASE`, `general_comments_BASE`, `as_built_completed_BASE`, `final_as_built_BASE`, `survey_requested_AS_BUILT`, `survey_completed_AS_BUILT`, `gps_north_AS_BUILT`, `gps_east_AS_BUILT`, `GL_AS_BUILT`, `TOC_AS_BUILT`, `instrumentation_comments_AS_BUILT`, `general_comments_AS_BUILT`, `as_built_completed_AS_BUILT`, `final_as_built_AS_BUILT`, `survey_requested_R1`, `survey_completed_R1`, `gps_north_R1`, `gps_east_R1`, `GL_R1`, `TOC_R1`, `instrumentation_comments_R1`, `general_comments_R1`, `as_built_completed_R1`, `final_as_built_R1`, `survey_requested_R2`, `survey_completed_R2`, `gps_north_R2`, `gps_east_R2`, `GL_R2`, `TOC_R2`, `instrumentation_comments_R2`, `general_comments_R2`, `as_built_completed_R2`, `final_as_built_R2`, `survey_requested_R3`, `survey_completed_R3`, `gps_north_R3`, `gps_east_R3`, `GL_R3`, `TOC_R3`, `instrumentation_comments_R3`, `general_comments_R3`, `as_built_completed_R3`, `final_as_built_R3`, `survey_requested_R4`, `survey_completed_R4`, `gps_north_R4`, `gps_east_R4`, `GL_R4`, `TOC_R4`, `instrumentation_comments_R4`, `general_comments_R4`, `as_built_completed_R4`, `final_as_built_R4` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;
	}
}

$dateFields = array('flagged', 'survey_requested_BASE', 'survey_requested_AS_BUILT', 'survey_requested_R1', 'survey_requested_R2', 'survey_requested_R3', 'survey_requested_R4');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Drilling Geotech Module - WM Digital System</title>
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
						<td><input type="text" id="survey_requested_BASE" class="datepicker" name="survey_requested_BASE" value="<?=(isset($well_id))?$survey_requested_BASE:""?>"></td>
						<td><input type="text" id="survey_requested_AS_BUILT" class="datepicker" name="survey_requested_AS_BUILT" value="<?=(isset($well_id))?$survey_requested_AS_BUILT:""?>"></td>
						<td><input type="text" id="survey_requested_R1" class="datepicker" name="survey_requested_R1" value="<?=(isset($well_id))?$survey_requested_R1:""?>"></td>
						<td><input type="text" id="survey_requested_R2" class="datepicker" name="survey_requested_R2" value="<?=(isset($well_id))?$survey_requested_R2:""?>"></td>
						<td><input type="text" id="survey_requested_R3" class="datepicker" name="survey_requested_R3" value="<?=(isset($well_id))?$survey_requested_R3:""?>"></td>
						<td><input type="text" id="survey_requested_R4" class="datepicker" name="survey_requested_R4" value="<?=(isset($well_id))?$survey_requested_R4:""?>"></td>
					</tr>
					<tr>
						<td>SURVEY COMPLETED</td>
						<td><?= (isset($well_id) && $survey_completed_BASE != '0000-00-00') ? $survey_completed_BASE : "" ?></td>
						<td><?= (isset($well_id) && $survey_completed_AS_BUILT != '0000-00-00') ? $survey_completed_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id) && $survey_completed_R1 != '0000-00-00') ? $survey_completed_R1 : "" ?></td>
						<td><?= (isset($well_id) && $survey_completed_R2 != '0000-00-00') ? $survey_completed_R2 : "" ?></td>
						<td><?= (isset($well_id) && $survey_completed_R3 != '0000-00-00') ? $survey_completed_R3 : "" ?></td>
						<td><?= (isset($well_id) && $survey_completed_R4 != '0000-00-00') ? $survey_completed_R4 : "" ?></td>
					</tr>
					<tr>
						<td>As Built GPS N</td>
						<td><?= (isset($well_id) && $gps_north_BASE != '0000-00-00') ? $gps_north_BASE : "" ?></td>
						<td><?= (isset($well_id) && $gps_north_AS_BUILT != '0000-00-00') ? $gps_north_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id) && $gps_north_R1 != '0000-00-00') ? $gps_north_R1 : "" ?></td>
						<td><?= (isset($well_id) && $gps_north_R2 != '0000-00-00') ? $gps_north_R2 : "" ?></td>
						<td><?= (isset($well_id) && $gps_north_R3 != '0000-00-00') ? $gps_north_R3 : "" ?></td>
						<td><?= (isset($well_id) && $gps_north_R4 != '0000-00-00') ? $gps_north_R4 : "" ?></td>
					</tr>
					<tr>
						<td>As Built GPS E</td>
						<td><?= (isset($well_id) && $gps_east_BASE != '0000-00-00') ? $gps_east_BASE : "" ?></td>
						<td><?= (isset($well_id) && $gps_east_AS_BUILT != '0000-00-00') ? $gps_east_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id) && $gps_east_R1 != '0000-00-00') ? $gps_east_R1 : "" ?></td>
						<td><?= (isset($well_id) && $gps_east_R2 != '0000-00-00') ? $gps_east_R2 : "" ?></td>
						<td><?= (isset($well_id) && $gps_east_R3 != '0000-00-00') ? $gps_east_R3 : "" ?></td>
						<td><?= (isset($well_id) && $gps_east_R4 != '0000-00-00') ? $gps_east_R4 : "" ?></td>
					</tr>
					<tr>
						<td>GL</td>
						<td><?= (isset($well_id) && $GL_BASE != 0) ? $GL_BASE : "" ?></td>
						<td><?= (isset($well_id) && $GL_AS_BUILT != 0) ? $GL_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id) && $GL_R1 != 0) ? $GL_R1 : "" ?></td>
						<td><?= (isset($well_id) && $GL_R2 != 0) ? $GL_R2 : "" ?></td>
						<td><?= (isset($well_id) && $GL_R3 != 0) ? $GL_R3 : "" ?></td>
						<td><?= (isset($well_id) && $GL_R4 != 0) ? $GL_R4 : "" ?></td>
					</tr>
					<tr>
						<td>TOC</td>
						<td><?= (isset($well_id) && $TOC_BASE != 0) ? $TOC_BASE : "" ?></td>
						<td><?= (isset($well_id) && $TOC_AS_BUILT != 0) ? $TOC_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id) && $TOC_R1 != 0) ? $TOC_R1 : "" ?></td>
						<td><?= (isset($well_id) && $TOC_R2 != 0) ? $TOC_R2 : "" ?></td>
						<td><?= (isset($well_id) && $TOC_R3 != 0) ? $TOC_R3 : "" ?></td>
						<td><?= (isset($well_id) && $TOC_R4 != 0) ? $TOC_R4 : "" ?></td>
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
						<td><?= (isset($well_id)) ? $instrumentation_comments_BASE : "" ?></td>
						<td><?= (isset($well_id)) ? $instrumentation_comments_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id)) ? $instrumentation_comments_R1 : "" ?></td>
						<td><?= (isset($well_id)) ? $instrumentation_comments_R2 : "" ?></td>
						<td><?= (isset($well_id)) ? $instrumentation_comments_R3 : "" ?></td>
						<td><?= (isset($well_id)) ? $instrumentation_comments_R4 : "" ?></td>
					</tr>
					<tr>
						<td>General Comments</td>
						<td><?= (isset($well_id)) ? $general_comments_BASE : "" ?></td>
						<td><?= (isset($well_id)) ? $general_comments_AS_BUILT : "" ?></td>
						<td><?= (isset($well_id)) ? $general_comments_R1 : "" ?></td>
						<td><?= (isset($well_id)) ? $general_comments_R2 : "" ?></td>
						<td><?= (isset($well_id)) ? $general_comments_R3 : "" ?></td>
						<td><?= (isset($well_id)) ? $general_comments_R4 : "" ?></td>
					</tr>
					<tr>
						<td>As-Built Completed</td>
						<td><input type="checkbox" name="as_built_completed_BASE" id="as_built_completed_BASE" value="1"<?=(isset($well_id) && $as_built_completed_BASE == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" name="as_built_completed_AS_BUILT" id="as_built_completed_AS_BUILT" value="1"<?=(isset($well_id) && $as_built_completed_AS_BUILT == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" name="as_built_completed_R1" id="as_built_completed_R1" value="1"<?=(isset($well_id) && $as_built_completed_R1 == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" name="as_built_completed_R2" id="as_built_completed_R2" value="1"<?=(isset($well_id) && $as_built_completed_R2 == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" name="as_built_completed_R3" id="as_built_completed_R3" value="1"<?=(isset($well_id) && $as_built_completed_R3 == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" name="as_built_completed_R4" id="as_built_completed_R4" value="1"<?=(isset($well_id) && $as_built_completed_R4 == 1)?" checked":""?> disabled></td>
					</tr>
					<tr>
						<td>Final As-Built</td>
						<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_BASE" id="final_as_built_BASE" value="1"<?=(isset($well_id) && $final_as_built_BASE == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_AS_BUILT" id="final_as_built_AS_BUILT" value="1"<?=(isset($well_id) && $final_as_built_AS_BUILT == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R1" id="final_as_built_R1" value="1"<?=(isset($well_id) && $final_as_built_R1 == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R2" id="final_as_built_R2" value="1"<?=(isset($well_id) && $final_as_built_R2 == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R3" id="final_as_built_R3" value="1"<?=(isset($well_id) && $final_as_built_R3 == 1)?" checked":""?> disabled></td>
						<td><input type="checkbox" class="finalAsBuilt" name="final_as_built_R4" id="final_as_built_R4" value="1"<?=(isset($well_id) && $final_as_built_R4 == 1)?" checked":""?> disabled></td>
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