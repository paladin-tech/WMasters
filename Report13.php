<?
include("sessionCheck.php");
include("db.php");

$rsWells = $infosystem->Execute("SELECT `well_id`, `flag_requested`, `flagged`, `survey_requested_BASE`, `survey_completed_BASE`, `survey_requested_AS_BUILT`, `survey_requested_R1`, `survey_completed_R1`, `survey_requested_R2`, `survey_completed_R2`, `survey_requested_R3`, `survey_completed_R3`, `survey_requested_R4`, `survey_completed_R4`  FROM `wells_construction` WHERE `active` = 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>DSR Report - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		table td {
			white-space: nowrap;
		}
		.bold, th {
			font-weight: bold;
		}
	</style>
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<th>Flag Requested</th>
		<th>Prestake Requested</th>
		<th>As-Built Requested</th>
		<th>R1-Requested</th>
		<th>R2-Requested</th>
		<th>R3-Requested</th>
		<th>R4-Requested</th>
	</tr>
	<?
	while(!$rsWells->EOF) {
		list($well_id, $flag_requested, $flagged, $survey_requested_BASE, $survey_completed_BASE, $survey_requested_AS_BUILT, $survey_completed_AS_BUILT, $survey_requested_R1, $survey_completed_R1, $survey_requested_R2, $survey_completed_R2, $survey_requested_R3, $survey_completed_R3, $survey_requested_R4, $survey_completed_R4) = $rsWells->fields;

		$tokenFlagged = (($flag_requested != '0000-00-00' && $flag_requested != '') && ($flagged == '0000-00-00' || $flagged == '')) ? true : false;
		$tokenBase = (($survey_requested_BASE != '0000-00-00' && $survey_requested_BASE != '') && ($survey_completed_BASE == '0000-00-00' || $survey_completed_BASE == '')) ? true : false;
		$tokenAsBuilt = (($survey_requested_AS_BUILT != '0000-00-00' && $survey_requested_AS_BUILT != '') && ($survey_completed_AS_BUILT == '0000-00-00' || $survey_completed_AS_BUILT == '')) ? true : false;
		$tokenR1 = (($survey_requested_R1 != '0000-00-00' && $survey_requested_R1 != '') && ($survey_completed_R1 == '0000-00-00' || $survey_completed_R1 == '')) ? true : false;
		$tokenR2 = (($survey_requested_R2 != '0000-00-00' && $survey_requested_R2 != '') && ($survey_completed_R2 == '0000-00-00' || $survey_completed_R2 == '')) ? true : false;
		$tokenR3 = (($survey_requested_R3 != '0000-00-00' && $survey_requested_R3 != '') && ($survey_completed_R3 == '0000-00-00' || $survey_completed_R3 == '')) ? true : false;
		$tokenR4 = (($survey_requested_R4 != '0000-00-00' && $survey_requested_R4 != '') && ($survey_completed_R4 == '0000-00-00' || $survey_completed_R4 == '')) ? true : false;

		// Print only Wells that have any relevant data (omit Wells with those data missing)
		if($tokenFlagged || $tokenBase || $tokenAsBuilt || $tokenR1 || $tokenR2 || $tokenR3 || $tokenR4) {
		?>
		<tr>
			<td><?= ($tokenFlagged) ? $well_id : "" ?></td>
			<td><?= ($tokenBase) ? $well_id : "" ?></td>
			<td><?= ($tokenAsBuilt) ? $well_id : "" ?></td>
			<td><?= ($tokenR1) ? $well_id : "" ?></td>
			<td><?= ($tokenR2) ? $well_id : "" ?></td>
			<td><?= ($tokenR3) ? $well_id : "" ?></td>
			<td><?= ($tokenR4) ? $well_id : "" ?></td>
		</tr>
		<?
		}
		$rsWells->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>