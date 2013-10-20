<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");

$rsWell = $infosystem->Execute("SELECT `well_id`, `active`, `activity`, `location_lsd`, `zone`, `length_nca`, `width_nca`, `length_ea`, `width_ea` FROM `wells_construction` ORDER BY `well_id`");
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
			$('.submit').click(function() {
				nr = $(this).attr('id').replace('submit_', '');
				alert(nr);
				id = $('#well_id_' + nr).val();
				alert(id);
				$('#well_id').val(id);
				alert($('#well_id').val());
			});
		});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm_<?= $i ?>" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="well_id" id="well_id" value="">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
		<tr>
			<td>Well ID</td>
			<td>Active</td>
			<td>Activity</td>
			<td>Location LSD</td>
			<td>Zone</td>
			<td>Length NCA</td>
			<td>Width NCA</td>
			<td>Length EA</td>
			<td>Width EA</td>
			<td>&nbsp;</td>
	</tr>
	<?
	$i = 1;
	while(!$rsWell->EOF) {
		list($well_id, $active, $activity, $location_lsd, $zone, $length_nca, $width_nca, $length_ea, $width_ea) = $rsWell->fields;
	?>
	<tr>
		<td><input type="text" name="well_id_<?= $i ?>" id="well_id_<?= $i ?>" value="<?= $well_id ?>" readonly></td>
		<td><input type="checkbox" name="active_<?= $i ?>" value="1"<?= ($active == 1) ? " checked" : "" ?>></td>
		<td><input type="text" name="activity_<?= $i ?>" value="<?= $activity ?>"></td>
		<td><input type="text" name="location_lsd_<?= $i ?>" value="<?= $location_lsd ?>" size="40"></td>
		<td><input type="text" name="zone_<?= $i ?>" value="<?= $zone ?>" size="8"></td>
		<td><input type="text" name="length_nca_<?= $i ?>" value="<?= $length_nca ?>" size="8"></td>
		<td><input type="text" name="width_nca_<?= $i ?>" value="<?= $width_nca ?>" size="8"></td>
		<td><input type="text" name="length_ea_<?= $i ?>" value="<?= $length_ea ?>" size="8"></td>
		<td><input type="text" name="width_ea_<?= $i ?>" value="<?= $width_ea ?>" size="8"></td>
		<td><input type="button" class="submit" id="submit_<?= $i ?>" name="submit_<?= $i ?>" value="Save"></td>
	</tr>
	<?
		$rsWell->MoveNext();
		$i++;
	}
	?>
	<tr>
		<td><input type="text" name="well_id_0" id="well_id_0" value="" readonly></td>
		<td><input type="checkbox" name="active_0" value="1" checked></td>
		<td><input type="text" name="activity_0" value=""></td>
		<td><input type="text" name="location_lsd_0" value="" size="40"></td>
		<td><input type="text" name="zone_0" value="" size="8"></td>
		<td><input type="text" name="length_nca_0" value="" size="8"></td>
		<td><input type="text" name="width_nca_0" value="" size="8"></td>
		<td><input type="text" name="length_ea_0" value="" size="8"></td>
		<td><input type="text" name="width_ea_0" value="" size="8"></td>
		<td><input type="button" class="submit" id="submit_0" name="submit_0" value="Save"></td>
	</tr>
</table>
</form>