<?
include("sessionCheck.php");
include("db.php");

if(isset($_GET['wellId'])) {

	$well_id = $_GET['wellId'];

	list($well_id, $active, $activity, $location_lsd, $zone, $length_nca, $width_nca, $length_ea, $width_ea) = $infosystem->Execute("SELECT `well_id`, `active`, `activity`, `location_lsd`, `zone`, `length_nca`, `width_nca`, `length_ea`, `width_ea` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;

}

if(isset($_POST['submit'])) {

	foreach($_POST as $key => $value) $$key = $value;

	$active = (isset($_POST['active'])) ? 1 : 0;

	if($well_id == '') $infosystem->Execute("INSERT INTO `wells_construction` SET `well_id` = '{$well_id_new}', `active` = {$active}, `activity` = '{$activity}', `location_lsd` = '{$location_lsd}', `zone` = '{$zone}', `length_nca` = '{$length_nca}', `width_nca` = '{$width_nca}', `length_ea` = '{$length_ea}', `width_ea` = '{$width_ea}'");
	else $infosystem->Execute("UPDATE `wells_construction` SET `active` = {$active}, `activity` = '{$activity}', `location_lsd` = '{$location_lsd}', `zone` = '{$zone}', `length_nca` = '{$length_nca}', `width_nca` = '{$width_nca}', `length_ea` = '{$length_ea}', `width_ea` = '{$width_ea}' WHERE `well_id` = '{$well_id}'");

}

$rsWell = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` ORDER BY `well_id`");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Well Administration - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="js/jquery.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#selWell').change(function() {
			window.location.href = ($(this).val() == '') ? '<?= $_SERVER["PHP_SELF"] ?>' : '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
		});
		$('#frm').submit(function() {
			if(!confirm('Are you sure you want to make changes?')) return false;
		});
	});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($well_id)) ? "?wellId={$well_id}" : "" ?>" method="post">
	<input type="hidden" name="well_id" id="well_id" value="<?= (isset($well_id)) ? $well_id : ""?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
		<tr valign="top">
			<td>Well ID</td>
			<td>
				<select name="selWell" id="selWell">
					<option value=""></option>
					<?
					while(!$rsWell->EOF) {
						list($x_well_id) = $rsWell->fields;
					?>
					<option value="<?= $x_well_id ?>"<?= (isset($well_id) && $well_id == $x_well_id) ? " selected" : ""?>><?= $x_well_id ?></option>
					<?
						$rsWell->MoveNext();
					}
					?>
				</select>
			</td>
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<td>Well&nbsp;ID</td>
						<td><input type="text" name="well_id_new" value="<?= (isset($well_id)) ? $well_id : ""?>"<?= (isset($well_id)) ? " readonly" : ""?>></td>
					</tr>
					<tr>
						<td>Active</td>
						<td><input type="checkbox" name="active" value="1"<?= (isset($well_id) && $active == 1) ? " checked" : "" ?>></td>
					</tr>
					<tr>
						<td>Activity</td>
						<td><input type="text" name="activity" value="<?= (isset($well_id)) ? $activity : ""?>"></td>
					</tr>
					<tr>
						<td>Location LSD</td>
						<td><input type="text" name="location_lsd" value="<?= (isset($well_id)) ? $location_lsd : ""?>" size="40"></td>
					</tr>
					<tr>
						<td>Zone</td>
						<td><input type="text" name="zone" value="<?= (isset($well_id)) ? $zone : ""?>" size="40"></td>
					</tr>
					<tr>
						<td>Length NCA</td>
						<td><input type="text" name="length_nca" value="<?= (isset($well_id)) ? $length_nca : ""?>" size="40"></td>
					</tr>
					<tr>
						<td>Width NCA</td>
						<td><input type="text" name="width_nca" value="<?= (isset($well_id)) ? $width_nca : ""?>" size="40"></td>
					</tr>
					<tr>
						<td>Length EA</td>
						<td><input type="text" name="length_ea" value="<?= (isset($well_id)) ? $length_ea : ""?>" size="40"></td>
					</tr>
					<tr>
						<td>Width EA</td>
						<td><input type="text" name="width_ea" value="<?= (isset($well_id)) ? $width_ea : ""?>" size="40"></td>
					</tr>
				</table>
			</td>
			<td width="100%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"><input type="submit" name="submit" value="Save"></td>
		</tr>
	</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>