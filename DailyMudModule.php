<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

if(isset($_POST['submit'])) {
	foreach($_POST['selWell'] as $key => $value) {
		$dailyMudHid = $_POST['hidDaily'][$key];
		$dailyMudWell = $_POST['selWell'][$key];
		$dailyMudSubWell = $_POST['selSubWell'][$key];
		$dailyMudSump = $_POST['selSump'][$key];
		$dailyMudCell = $_POST['selCell'][$key];
		$dailyMudQuantity = $_POST['txtQuantity'][$key];
		if($dailyMudHid == "" && $dailyMudWell != "" && $dailyMudSubWell != "" && $dailyMudSump != "" && $dailyMudCell != "" && $dailyMudQuantity != "") $infosystem->Execute("INSERT INTO `wm_dailymud` SET `truck_id` = '{$truckId}', `date` = '{$dateDaily}', `well_id` = '{$dailyMudWell}', `sub_well_id` = '{$dailyMudSubWell}', `area` = '{$dailyMudSump}', `sump_number` = {$dailyMudCell}, `quantity` = {$dailyMudQuantity}");
		if($dailyMudHid != "") $infosystem->Execute("UPDATE `wm_dailymud` SET `sub_well_id` = '{$dailyMudSubWell}', `area` = '{$dailyMudSump}', `sump_number` = {$dailyMudCell}, `quantity` = {$dailyMudQuantity} WHERE `truck_id` = '{$truckId}' AND `date` = '{$dateDaily}' AND `well_id` = '{$dailyMudWell}'");
	}
}

if(isset($_GET['truckId']) && isset($_GET['dateDaily'])) {
	$truckId = $_GET['truckId'];
	$dateDaily = $_GET['dateDaily'];
	$rsDailyMud = $infosystem->Execute("SELECT `well_id`, `sub_well_id`, `area`, `sump_number`, `quantity` FROM `wm_dailymud` WHERE `truck_id` = '{$truckId}' AND `date` = '{$dateDaily}'");
	$wellsUsed = array();
	while(!$rsDailyMud->EOF) {
		array_push($wellsUsed, "'".$rsDailyMud->Fields("well_id")."'");
		$rsDailyMud->MoveNext();
	}
	$rsDailyMud->MoveFirst();
	$wellsUsed = implode(',', $wellsUsed);
}

$rsTruck = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = 'Vacuum'");
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` ORDER BY `mainboard`");
$rsSubWell = $infosystem->Execute("SELECT `wellId` FROM `sub_wells`");
$rsSump = $infosystem->Execute("SELECT DISTINCT `area` FROM `con_hydro_vac` WHERE `type` = 'vacuum' AND (NOW() BETWEEN `start_date` AND `end_date`) OR (NOW() > `start_date` AND `end_date` = '0000-00-00')");

if($wellsUsed != '') $rsWellLicenceNew = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` NOT IN (" . $wellsUsed . ") ORDER BY `mainboard`");
else $rsWellLicenceNew = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` ORDER BY `mainboard`");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Daily Mud Module - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<? $xajax->printJavascript(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$( ".datepicker" ).datepicker();
			$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

			$( ".selectDailyMud").change(function() {
				if( $("#selTruck").val() != "" && $("#txtDate").val() != "") {
					window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?truckId=' + $("#selTruck").val() + '&dateDaily=' + $("#txtDate").val();
				}
			});
			<?
			if((isset($truckId) && isset($dateDaily))) {
			?>
			$("#txtDate").datepicker( "setDate", "<?= $dateDaily ?>" );
			<?
			}
			?>

//			$('#frm').submit(function(event) {
//				$('.newData').each(function() {
//					if($(this).val() == '') {
//						alert('You have to enter the data!');
//						event.preventDefault();
//						error = true;
//						return false;
//					}
//				});
//				if(!error) {
//					$('.quantity').each(function() {
//						if(($(this).val() != '' && !($.isNumeric($(this).val()))) || $(this).val() <= 0) {
//							alert('Quantity must be a numeric value greater than zero!');
//							event.preventDefault();
//						}
//					});
//				}
//			});
		});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($truckId) && isset($dateDaily)) ? "?truckId={$truckId}&dateDaily={$dateDaily}" : "" ?>" method="post">
	<input type="hidden" name="truckId" value="<?= (isset($truckId)) ? $truckId : "" ?>">
	<input type="hidden" name="dateDaily" value="<?= (isset($dateDaily)) ? $dateDaily : "" ?>">
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
	<tr>
		<td colspan="8">Truck #:
			<select name="selTruck" id="selTruck" class="selectDailyMud">
				<option value="">[Truck #]</option>
				<?
				while(!$rsTruck->EOF) {
					list($unit) = $rsTruck->fields;
				?>
				<option value="<?= $unit ?>"<?= (isset($truckId) && $truckId == $unit) ? " selected" : "" ?>><?= $unit ?></option>
				<?
					$rsTruck->MoveNext();
				}
				?>
			</select>
			&nbsp;Date:
			<input type="text" class="datepicker selectDailyMud" name="txtDate" id="txtDate" value="<?= (isset($truckId) && isset($dateDaily)) ? $dateDaily : "" ?>">
		</td>
	</tr>
	<tr>
		<td>Well ID</td>
		<td>SubWell ID</td>
		<td>Sump</td>
		<td>Cell</td>
		<td>Quantity</td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
	if(isset($truckId) && isset($dateDaily)) {
	$i = 1;
	while(!$rsDailyMud->EOF) {
		list($well_id, $sub_well_id, $sump, $cell, $quantity) = $rsDailyMud->fields;
	?>
	<tr>
		<input type="hidden" name="hidDaily[]" value="1">
		<td>
			<select name="selWell[]" class="newData">
				<option value="">[Well ID]</option><?
				$rsWellLicence->MoveFirst();
				while(!$rsWellLicence->EOF) {
					list($y_well_id) = $rsWellLicence->fields; ?>
					<option value="<?=$y_well_id?>"<?= (isset($truckId) && isset($dateDaily) && $well_id == $y_well_id) ? " selected" : "" ?>><?=$y_well_id?></option><?
					$rsWellLicence->MoveNext();
				} ?>
			</select>
		</td>
		<td>
			<select name="selSubWell[]" class="newData">
				<option value="">[SubWell ID]</option><?
				$rsSubWell->MoveFirst();
				while(!$rsSubWell->EOF) {
					list($ySubWellId) = $rsSubWell->fields; ?>
					<option value="<?=$ySubWellId?>"<?= (isset($truckId) && isset($dateDaily) && $sub_well_id == $ySubWellId) ? " selected" : "" ?>><?=$ySubWellId?></option><?
					$rsSubWell->MoveNext();
				} ?>
			</select>
		</td>
		<td>
			<select name="selSump[]" class="newData" onchange="xajax_getSumpCells('<?= $i ?>', this.value)">
				<option value="">[Sump]</option><?
				$rsSump->MoveFirst();
				while(!$rsSump->EOF) {
					list($yArea) = $rsSump->fields; ?>
					<option value="<?=$yArea?>"<?= (isset($truckId) && isset($dateDaily) && $sump == $yArea) ? " selected" : "" ?>><?=$yArea?></option><?
					$rsSump->MoveNext();
				} ?>
			</select>
		</td>
		<td id="cellTd<?= $i ?>">
			<select name="selCell[]" class="newData">
				<option value="">[Cell]</option>
				<option value="<?= $cell ?>" selected><?= $cell ?></option>
			</select>
		</td>
		<td><input type="text" name="txtQuantity[]" value="<?= $quantity ?>" class="quantity"></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<?
		$rsDailyMud->MoveNext();
		$i++;
	}
	}
	?>
	<tr>
		<input type="hidden" name="hidDaily[]" value="">
		<td>
			<select name="selWell[]" class="newData">
				<option value="">[Well ID]</option><?
				$rsWellLicence->MoveFirst();
				while(!$rsWellLicence->EOF) {
					list($y_well_id) = $rsWellLicence->fields; ?>
					<option value="<?=$y_well_id?>"><?=$y_well_id?></option><?
					$rsWellLicence->MoveNext();
				} ?>
			</select>
		</td>
		<td>
			<select name="selSubWell[]" class="newData">
				<option value="">[SubWell ID]</option><?
				$rsSubWell->MoveFirst();
				while(!$rsSubWell->EOF) {
					list($ySubWellId) = $rsSubWell->fields; ?>
					<option value="<?=$ySubWellId?>"><?=$ySubWellId?></option><?
					$rsSubWell->MoveNext();
				} ?>
			</select>
		</td>
		<td>
			<select name="selSump[]" class="newData" onchange="xajax_getSumpCells('0', this.value)">
				<option value="">[Sump]</option><?
				$rsSump->MoveFirst();
				while(!$rsSump->EOF) {
					list($yArea) = $rsSump->fields; ?>
					<option value="<?=$yArea?>"><?=$yArea?></option><?
					$rsSump->MoveNext();
				} ?>
			</select>
		</td>
		<td id="cellTd0">
			<select name="selCell[]" class="newData">
				<option value="">[Cell]</option>
			</select>
		</td>
		<td><input type="text" name="txtQuantity[]" id="txtQuantity" value="" class="newData quantity"></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="8">
			<table cellspacing="1" cellpadding="7" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td><input type="submit" name="submit" value="Submit"<?= (isset($truckId) && isset($dateDaily)) ? "" : "disabled=disabled" ?>></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>