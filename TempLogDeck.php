<?
include("sessionCheck.php");
include("db.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	$well_id = $_POST['well_id'];
	$infosystem->Execute("UPDATE `wells_construction` SET `log_gps_north_1` = {$txtLogGPSNorth1}, `log_gps_east_1` = {$txtLogGPSEast1}, `log_conifer_1` = '{$selLogConifer1}', `log_volume_1` = {$txtLogVolume1}, `log_gps_north_2` = {$txtLogGPSNorth2}, `log_gps_east_2` = {$txtLogGPSEast2}, `log_conifer_2` = '{$selLogConifer2}', `log_volume_2` = {$txtLogVolume2} WHERE `well_id` = '{$well_id}'");
}

// If a Well is chosen, get the data for the form
if(isset($_GET['wellId'])) {
	$wellId = $_GET['wellId'];
	list($log_gps_north_1, $log_gps_east_1, $log_conifer_1, $log_volume_1, $log_gps_north_2, $log_gps_east_2, $log_conifer_2, $log_volume_2) = $infosystem->Execute("SELECT `log_gps_north_1`, `log_gps_east_1`, `log_conifer_1`, `log_volume_1`, `log_gps_north_2`, `log_gps_east_2`, `log_conifer_2`, `log_volume_2` FROM `wells_construction` WHERE `well_id` = '{$wellId}'")->fields;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Temp Log Deck - WM Digital System</title>
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
		<td colspan="4">
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
		<td>Conifer<br>Deciduous</td>
		<td>Volume<br>[m3]</td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="text" name="txtLogGPSNorth1" value="<?= (isset($wellId)) ? $log_gps_north_1 : "" ?>"></td>
		<td><input type="text" name="txtLogGPSEast1" value="<?= (isset($wellId)) ? $log_gps_east_1 : "" ?>"></td>
		<td>
			<select name="selLogConifer1">
				<option value=""></option>
				<option value="Conifer"<?= (isset($wellId) && $log_conifer_1 == "Conifer") ? " selected" : "" ?>>Conifer</option>
				<option value="Deciduous"<?= (isset($wellId) && $log_conifer_1 == "Deciduous") ? " selected" : "" ?>>Deciduous</option>
			</select>
		</td>
		<td><input type="text" name="txtLogVolume1" value="<?= (isset($wellId)) ? $log_volume_1 : "" ?>"></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="text" name="txtLogGPSNorth2" value="<?= (isset($wellId)) ? $log_gps_north_2 : "" ?>"></td>
		<td><input type="text" name="txtLogGPSEast2" value="<?= (isset($wellId)) ? $log_gps_east_2 : "" ?>"></td>
		<td>
			<select name="selLogConifer2">
				<option value=""></option>
				<option value="Conifer"<?= (isset($wellId) && $log_conifer_2 == "Conifer") ? " selected" : "" ?>>Conifer</option>
				<option value="Deciduous"<?= (isset($wellId) && $log_conifer_2 == "Deciduous") ? " selected" : "" ?>>Deciduous</option>
			</select>
		</td>
		<td><input type="text" name="txtLogVolume2" value="<?= (isset($wellId)) ? $log_volume_2 : "" ?>"></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4">
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