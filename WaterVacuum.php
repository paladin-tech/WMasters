<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

$resourceType = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 'vacuum' : 'water';
$moduleLabels = array('water' => array('ModuleName' => 'Water', 'CellName' => 'Source'), 'vacuum' => array('ModuleName' => 'Vacuum', 'CellName' => 'Sump'));
$dateWaterVacuum = (isset($_GET['dateWaterVacuum']) && strtotime($_GET['dateWaterVacuum']) !== false) ? $_GET['dateWaterVacuum'] : date('Y-m-d');

$errorMsg = "";
$today = date('Y-m-d');

if(isset($_POST['submit'])) {

	foreach($_POST as $key => $value) $$key = $value;

	if($selUnit != "" && $selArea != "" && $selCell != "" && $txtVolume != "") {
		$rsCheck = $infosystem->Execute("SELECT `con_hydro_vac_id` FROM `con_hydro_vac` WHERE `type` = '{$resourceType}' AND `area` = '{$selArea}' AND `cell_number` = {$selCell}");
		if($rsCheck->RecordCount() > 0) {
			list($conHydroVacId) = $rsCheck->fields;
			$infosystem->Execute("INSERT INTO `water_vacuum` SET `con_hydro_vac_id` = {$conHydroVacId}, `input_date` = '{$txtDate}', `unit` = '{$selUnit}', `volume` = {$txtVolume}, `user` = {$userID}");
		} else {
			$errorMsg = "Save failed. No associated data for chosen Unit, Area and " . $moduleLabels[$resourceType]['CellName'] . ".";
		}
	}
}

$rsWaterVacuum = $infosystem->Execute("SELECT chv.`con_hydro_vac_id`, chv.`area`, chv.`cell_number`, wv.`input_date`, wv.`unit`, wv.`volume`, wv.`user`, wv.`dateTimeStamp` FROM `con_hydro_vac` chv, `water_vacuum` wv WHERE chv.`type` = '{$resourceType}' AND  wv.`con_hydro_vac_id` = chv.`con_hydro_vac_id` AND DATE(wv.`dateTimeStamp`) = DATE(NOW())");
$rsTrucks = $infosystem->Execute("SELECT `unit` FROM `trucks` WHERE `type` = '{$resourceType}' ORDER BY `unit`");
$rsArea = $infosystem->Execute("SELECT DISTINCT `area` FROM `con_hydro_vac` WHERE (NOW() BETWEEN `start_date` AND `end_date`) OR (NOW() > `start_date` AND `end_date` = '0000-00-00') AND `type` = '{$resourceType}' ORDER BY `area`");
$rsCell = $infosystem->Execute("SELECT DISTINCT `cell_number` FROM `con_hydro_vac` WHERE (NOW() BETWEEN `start_date` AND `end_date`) OR (NOW() > `start_date` AND `end_date` = '0000-00-00') ORDER BY `cell_number`");

// Report Creating
while(!$rsArea->EOF) {
	list($xArea) = $rsArea->fields;
	$rsWaterVacuumReport[$xArea] = $infosystem->Execute("SELECT chv.`cell_number`, chv.`program_zone`, SUM(wv.`volume`) FROM `water_vacuum` wv, `con_hydro_vac` chv WHERE wv.`con_hydro_vac_id` = chv.`con_hydro_vac_id` AND chv.`type` = '{$resourceType}' AND chv.`area` = '{$xArea}' AND ((NOW() BETWEEN chv.`start_date` AND chv.`end_date`) OR (NOW() > chv.`start_date` AND chv.`end_date` = '0000-00-00')) AND DATE(wv.`input_date`) = DATE(NOW()) GROUP BY chv.`cell_number`");
	$rsArea->MoveNext();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= $moduleLabels[$resourceType]['ModuleName'] ?> Module - WM Digital System</title>
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

			<?
			if(isset($dateWaterVacuum)) {
			?>
			$( "#txtDate").datepicker( "setDate", "<?= $dateWaterVacuum ?>" );
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
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?>?resourceType=<?=$resourceType?>" method="post">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
<!--		<tr>-->
<!--			<td colspan="2">Ticket Date:<br><input type="text" class="datepicker selectWaterVacuumDate" name="txtDate" id="txtDate" value="--><?//= (isset($dateWaterVacuum)) ? $dateWaterVacuum : "" ?><!--"></td>-->
<!--		</tr>-->
		<?
		if($dateWaterVacuum > $today) {
		?>
		<tr>
			<td class="warning">You cannot change the data in the future.</td>
		</tr>
		<?
		} else {
		?>
		<tr>
			<td>
				<?
				foreach ($rsWaterVacuumReport as $key => $rs) {
//					if(!is_null($rs->Fields("cell_number"))) {
						?>
						<br>
						<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
							<tr>
								<th colspan="2"><?= $key ?></th>
							</tr>
							<tr>
								<th><?= $moduleLabels[$resourceType]['CellName'] ?></th>
								<th>Description</th>
								<th>Volume</th>
							</tr>
							<?
							while(!$rs->EOF) {
								list($xCellNumber, $xCell, $xVolume) = $rs->fields;
								?>
								<tr>
									<td><?= $xCellNumber ?></td>
									<td><?= $xCell ?></td>
									<td><?= number_format($xVolume, 2) ?></td>
								</tr>
								<?
								$rs->MoveNext();
							}
							?>
						</table>
						<br>
					<?
//					}
				}
				?>
				<? if($errorMsg != "") echo "{$errorMsg}<br>"; ?>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<th>Ticket Date</th>
						<th>Unit</th>
						<th>Area</th>
						<th><?= $moduleLabels[$resourceType]['CellName'] ?></th>
						<th>Volume</th>
					</tr>
					<?
					while(!$rsWaterVacuum->EOF) {
						list($con_hydro_vac_id, $area, $cell_number, $input_date, $unit, $volume, $user, $dateTimeStamp) = $rsWaterVacuum->fields;
						$locked = ($dateTimeStamp != date('Y-m-d'));
					?>
					<tr>
						<td><?= $input_date ?></td>
						<td><?= $unit ?></td>
						<td><?= $area ?></td>
						<td><?= $cell_number ?></td>
						<td><?= $volume ?></td>
					</tr>
					<?
						$rsWaterVacuum->MoveNext();
					}
					?>
					<tr>
						<td><input type="text" class="datepicker selectWaterVacuumDate" name="txtDate" id="txtDate" value=""></td>
						<td>
							<select name="selUnit">
								<option value="">[Unit ID]</option>
								<?
								$rsTrucks->MoveFirst();
								while(!$rsTrucks->EOF) {
									list($xUnit) = $rsTrucks->fields;
								?>
								<option value="<?= $xUnit ?>"><?= $xUnit ?></option>
								<?
									$rsTrucks->MoveNext();
								}
								?>
							</select>
						</td>
						<td>
							<select name="selArea" onchange="xajax_getCells('<?= $resourceType ?>', '<?= $moduleLabels[$resourceType]['CellName'] ?>', this.value)">
								<option value="">[Area]</option>
								<?
								$rsArea->MoveFirst();
								while(!$rsArea->EOF) {
									list($xArea) = $rsArea->fields;
									?>
									<option value="<?= $xArea ?>"><?= $xArea ?></option>
									<?
									$rsArea->MoveNext();
								}
								?>
							</select>
						</td>
						<td id="cellTd">
							<select name="selCell">
								<option value="">[<?= $moduleLabels[$resourceType]['CellName'] ?>]</option>
								<?
								$rsCell->MoveFirst();
								while(!$rsCell->EOF) {
									list($xCellNumber) = $rsCell->fields;
									?>
									<option value="<?= $xCellNumber ?>"><?= $xCellNumber ?></option>
									<?
									$rsCell->MoveNext();
								}
								?>
							</select>
						</td>
						<td>
							<input type="text" name="txtVolume">
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<table cellspacing="1" cellpadding="7" bgcolor="#CCCCCC" width="100%">
								<tr>
									<td><input type="submit" name="submit" value="Submit"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td width="100%">&nbsp;</td>
		</tr>
		<?
		}
		?>
	</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>