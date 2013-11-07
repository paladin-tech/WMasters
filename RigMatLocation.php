<?
include("sessionCheck.php");
include("db.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` ORDER BY `mainboard`");

if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	$well_id = $_POST['well_id'];
	$infosystem->Execute("UPDATE `wells_construction` SET `location1_gps_north` = {$txtLogGPSNorth}, `location1_gps_east` = {$txtLogGPSEast}, `location1_number_of_rig_mats` = {$txtNumberOfRigMats} WHERE `well_id` = '{$well_id}'");
}

// If a Well is chosen, get the data for the form
if(isset($_GET['wellId'])) {
	$wellId = $_GET['wellId'];
	list($location1_gps_north, $location1_gps_east, $location1_number_of_rig_mats) = $infosystem->Execute("SELECT `location1_gps_north`, `location1_gps_east`, `location1_number_of_rig_mats` FROM `wells_construction` WHERE `well_id` = '{$wellId}'")->fields;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Rig Mat Location - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#selWell').change(function() {
				if($(this).val() == '') $('#submit').attr('disabled', true);
				if($(this).val() != '') window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
			});
		});
	</script>
	<style type="text/css">
		table td {
			white-space: nowrap;
		}
	</style>
</head>

<body>
<? include ('header.inc');?>
</body>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($wellId)) ? "?wellId={$wellId}" : "" ?>" method="post">
<input type="hidden" name="well_id" value="<?= (isset($wellId)) ? $wellId : "" ?>">
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr valign="top">
		<td colspan="3">
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td>Well ID</td>
					<td>
						<select name="selWell" id="selWell">
							<option value="">[choose the well]</option>
							<?
							while(!$rsWellLicence->EOF) {
								list($well_id) = $rsWellLicence->fields;
								?>
								<option value="<?= $well_id ?>"<?= (isset($wellId) && $wellId == $well_id) ? " selected" : "" ?>><?= $well_id ?></option>
								<?
								$rsWellLicence->MoveNext();
							}
							?>
						</select>
					</td>
				</tr>
			</table>
		</td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td>GPS Coordinates<br>North</td>
		<td>GPS<br>East</td>
		<td>Number of<br>Rig Mats</td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="text" name="txtLogGPSNorth" value="<?= (isset($wellId)) ? $location1_gps_north : "" ?>"></td>
		<td><input type="text" name="txtLogGPSEast" value="<?= (isset($wellId)) ? $location1_gps_east : "" ?>"></td>
		<td><input type="text" name="txtNumberOfRigMats" value="<?= (isset($wellId)) ? $location1_number_of_rig_mats : "" ?>"></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td><input type="submit" name="submit" id="submit" value="Submit"<?= (isset($wellId)) ? "" : "disabled=disabled" ?>></td>
				</tr>
			</table>
		</td>
		<td width="100%">&nbsp;</td>
	</tr>
</table>
</form>
<? include("footer.inc"); ?>
</body>
</html>