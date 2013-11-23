<?
include("sessionCheck.php");
include("db.php");
$infosystem->debug = true;
list($ConHydroLvl) = $infosystem->Execute("SELECT `ConHydro` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($ConHydroLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($ConHydroLvl=="o")?" disabled=\"disabled\"":"";

if(isset($_POST['submit'])) {
	die(var_dump($_POST));
//	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Construction Hydro', '{$_SESSION['username']}')");
//	foreach($_POST as $key => $value) $$key = $value;
//		$areas = array("Kearl","Syncrude");
//		foreach($areas as $ar) {
//			foreach($WaterLicence[$ar] as $key=>$wl) {
//				$sd = $StartDate[$ar][$key];
//				$ed = $EndDate[$ar][$key];
//				$efd = $EffectiveDate[$ar][$key];
//				$exd = $ExpiryDate[$ar][$key];
//				$infosystem->Execute("UPDATE `con_hydro` SET `water_licence` = '{$wl}', `licence_effective_date` = '{$efd}', `licence_expiry_date` = '{$exd}', `start_date` = '{$sd}', `end_date` = '{$ed}' WHERE `area` = '{$ar}' AND `source_number` = {$key}");
//			}
//		}
	}


if(isset($_GET['truckId']) && isset($_GET['date'])) {
	$truckId = $_GET['truckId'];
	$date = $_GET['date'];

	$rsConHydro = $infosystem->Execute("SELECT `input_date`, `unit`, `truck_type`, `area`, `source_number`, `licence`, `licence_effective_date`, `licence_expiry_date`, `program_zone`, `location_lsd`, `start_date`, `end_date`, `volume` FROM `water_vacuum` WHERE `unit` = '{$truckId}' AND `input_date` = '{$date}'");
//	$wellsUsed = array();
//	while(!$rsDailyMud->EOF) {
//		array_push($wellsUsed, "'".$rsDailyMud->Fields("well_id")."'");
//		$rsDailyMud->MoveNext();
//	}
//	$rsDailyMud->MoveFirst();
//	$wellsUsed = implode(',', $wellsUsed);
}

$rsTruck = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = 'Water'");
$rsSource = $infosystem->Execute("SELECT `number` FROM `source_sump` WHERE `type` = 'Water'");
$rsArea = array('Kearl', 'Syncrude');

//	list($sumKearl1, $sumKearl2) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2 FROM `water` WHERE `input_date`< CURDATE() AND `area` = 'Kearl'")->fields;
//	list($sumSyncrude1, $sumSyncrude2, $sumSyncrude3, $sumSyncrude4, $sumSyncrude5, $sumSyncrude6, $sumSyncrude7, $sumSyncrude8, $sumSyncrude9, $sumSyncrude10, $sumSyncrude11, $sumSyncrude12) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2, SUM(`loc_3`) s3, SUM(`loc_4`) s4, SUM(`loc_5`) s5, SUM(`loc_6`) s6, SUM(`loc_7`) s7, SUM(`loc_8`) s8, SUM(`loc_9`) s9, SUM(`loc_10`) s10, SUM(`loc_11`) s11, SUM(`loc_12`) s12 FROM `water` WHERE `input_date` = < CURDATE() AND `area` = 'Syncrude'")->fields;
//
//	$rsConHydro = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `licence_effective_date`, `licence_expiry_date`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro`");
//	$conHydroArray = array();
//	while(!$rsConHydro->EOF) {
//		list($x_area, $x_source_number, $x_water_licence, $x_licence_effective_date, $x_licence_expiry_date, $x_source_ID, $x_program_zone, $x_location_LSD, $x_start_date, $x_end_date, $x_total_licensed_volume) = $rsConHydro->fields;
//		$conHydroArray["water_licence"][$x_area][$x_source_number] = $x_water_licence;
//		$conHydroArray["licence_effective_date"][$x_area][$x_source_number] = $x_licence_effective_date;
//		$conHydroArray["licence_expiry_date"][$x_area][$x_source_number] = $x_licence_expiry_date;
//		$conHydroArray["source_ID"][$x_area][$x_source_number] = $x_source_ID;
//		$conHydroArray["program_zone"][$x_area][$x_source_number] = $x_program_zone;
//		$conHydroArray["location_LSD"][$x_area][$x_source_number] = $x_location_LSD;
//		$conHydroArray["start_date"][$x_area][$x_source_number] = $x_start_date;
//		$conHydroArray["end_date"][$x_area][$x_source_number] = $x_end_date;
//		$conHydroArray["total_licensed_volume"][$x_area][$x_source_number] = $x_total_licensed_volume;
//		$rsConHydro->MoveNext();
//	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Construction Hydro - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$( ".datepicker" ).datepicker();
			$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

			$( ".selectDailyMud").change(function() {
				if( $("#selTruck").val() != "" && $("#txtDate").val() != "") {
					window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?truckId=' + $("#selTruck").val() + '&date=' + $("#txtDate").val();
				}
			});
			<?
			if((isset($truckId) && isset($date))) {
			?>
			$( "#txtDate").datepicker( "setDate", "<?= $date ?>" );
			<?
			}
			?>

			$('#frm').submit(function(event) {
				$('.quantity').each(function() {
					if(($(this).val() != '' && !($.isNumeric($(this).val()))) || $(this).val() <= 0) {
						alert('Quantity must be a numeric value greater than zero!');
						event.preventDefault();
					}
				});
			});
		});
	</script>
</head>
<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($truckId) && isset($date)) ? "?truckId={$truckId}&date={$date}" : "" ?>" method="post">
	<input type="hidden" name="truckId" value="<?= (isset($truckId)) ? $truckId : "" ?>">
	<input type="hidden" name="dateDaily" value="<?= (isset($date)) ? $date : "" ?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
		<tr>
			<td colspan="11">Truck #:
				<select name="selTruck" id="selTruck" class="selectDailyMud">
					<option value="">[Truck #]</option>
					<?
					while(!$rsTruck->EOF) {
						list($unit) = $rsTruck->fields;
						?>
						<option value="<?= $unit ?>"<?= (isset($truckId) && isset($date) && $truckId == $unit) ? " selected" : "" ?>><?= $unit ?></option>
						<?
						$rsTruck->MoveNext();
					}
					?>
				</select>
				&nbsp;Date:
				<input type="text" class="datepicker selectDailyMud" name="txtDate" id="txtDate" value="<?= (isset($truckId) && isset($date)) ? $date : "" ?>">
			</td>
		</tr>
		<tr>
			<td>Area</td>
			<td>Source</td>
			<td>Licence</td>
			<td>Licence Effective Date</td>
			<td>Licence Expiry Date</td>
			<td>Program Zone</td>
			<td>Location LSD</td>
			<td>Start Date</td>
			<td>End Date</td>
			<td>Volume</td>
			<td width="100%">&nbsp;</td>
		</tr>
		<?
		if(isset($truckId) && isset($date)) {
			while(!$rsConHydro->EOF) {
				list($input_date, $unit, $truck_type, $area, $source_number, $licence, $licence_effective_date, $licence_expiry_date, $program_zone, $location_lsd, $start_date, $end_date, $volume) = $rsConHydro->fields;
				?>
				<tr>
					<input type="hidden" name="hidDaily[]" value="1">
					<td>
						<select name="selArea[]">
							<option value="">[Area]</option>
							<?
							foreach($rsArea as $areaData) {
							?>
							<option value="<?=$areaData?>"<?= ($area == $areaData) ? " selected" : "" ?>><?=$areaData?></option>
							<?
							}
							?>
						</select>
					</td>
					<td>
						<select name="selSource[]">
							<option value="">[Source]</option><?
							$rsSource->MoveFirst();
							while(!$rsSource->EOF) {
								list($yNumber) = $rsSource->fields; ?>
								<option value="<?=$yNumber?>"<?= ($subwell_id == $yNumber) ? " selected" : "" ?>><?=$yNumber?></option><?
								$rsSource->MoveNext();
							} ?>
						</select>
					</td>
					<td><input type="text" name="txtLicence[]" value="<?= $licence ?>"></td>
					<td><input type="text" name="txtLicenceEffectiveDate[]" value="<?= $licence_effective_date ?>"></td>
					<td><input type="text" name="txtLicenceExpiryDate[]" value="<?= $licence_expiry_date ?>"></td>
					<td><input type="text" name="txtProgramZone[]" value="<?= $program_zone ?>"></td>
					<td><input type="text" name="txtLocationLSD[]" value="<?= $location_lsd ?>"></td>
					<td><input type="text" name="txtLicenceExpiryDate[]" value="<?= $start_date ?>"></td>
					<td><input type="text" name="txtLicenceExpiryDate[]" value="<?= $end_date ?>"></td>
					<td><input type="text" name="txtQuantity[]" value="<?= $volume ?>" class="quantity"></td>
					<td width="100%">&nbsp;</td>
				</tr>
				<?
				$rsConHydro->MoveNext();
			}
		}
		?>
		<tr>
			<input type="hidden" name="hidDaily[]" value="1">
			<td>
				<select name="selArea[]">
					<option value="">[Area]</option>
					<?
					foreach($rsArea as $areaData) {
					?>
					<option value="<?=$areaData?>"><?=$areaData?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>
				<select name="selSource[]">
					<option value="">[Source]</option><?
					$rsSource->MoveFirst();
					while(!$rsSource->EOF) {
						list($yNumber) = $rsSource->fields; ?>
						<option value="<?=$yNumber?>"><?=$yNumber?></option><?
						$rsSource->MoveNext();
					} ?>
				</select>
			</td>
			<td><input type="text" name="txtLicence[]"></td>
			<td><input type="text" name="txtLicenceEffectiveDate[]"></td>
			<td><input type="text" name="txtLicenceExpiryDate[]"></td>
			<td><input type="text" name="txtProgramZone[]"></td>
			<td><input type="text" name="txtLocationLSD[]"></td>
			<td><input type="text" name="txtLicenceExpiryDate[]"></td>
			<td><input type="text" name="txtLicenceExpiryDate[]"></td>
			<td><input type="text" name="txtQuantity[]" class="quantity"></td>
			<td width="100%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11">
				<table cellspacing="1" cellpadding="7" bgcolor="#CCCCCC" width="100%">
					<tr>
						<td><input type="submit" name="submit" value="Submit"<?= (isset($truckId) && isset($date)) ? "" : "disabled=disabled" ?>></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</table>
<? include ('footer.inc');?>
</body>
</html>