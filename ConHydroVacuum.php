<?
include("sessionCheck.php");
include("db.php");

$resourceType = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 'vacuum' : 'water';
$moduleName = array('water' => 'Hydro', 'vacuum' => 'Vacuum');
$cellLabel = array('water' => 'Water', 'vacuum' => 'Sump');

list($ConHydroLvl) = $infosystem->Execute("SELECT `ConHydro` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($ConHydroLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($ConHydroLvl=="o")?" disabled=\"disabled\"":"";

if(isset($_POST['submit'])) {

	foreach($_POST as $key => $value) $$key = $value;

	foreach($txtArea as $key => $value) {
		$infosystem->Execute("UPDATE `con_hydro_vac` SET `cell_licence` = '" . $txtCellLicence[$key]. "', `licence_effective_date` = '" . $txtEffectiveDate[$key] . "', `licence_expiry_date` = '" . $txtExpiryDate[$key] . "', `start_date` = '" . $txtStartDate[$key] . "', `end_date` = '" . $txtEndDate[$key] . "' WHERE `area` = '" . $txtArea[$key] . "' AND `cell_number` = '" . $txtCellNumber[$key] . "'");
	}

}

$rsConHydroVac = $infosystem->Execute("SELECT `area`, `cell_number`, `cell_licence`, `licence_effective_date`, `licence_expiry_date`, `cell_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro_vac` WHERE `type` = '{$resourceType}'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Construction <?= $moduleName[$resourceType] ?> - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$( ".datepicker" ).datepicker();
			$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

			<?
			$i = 1;
			while(!$rsConHydroVac->EOF) {
				list($area, $cell_number, $cell_licence, $licence_effective_date, $licence_expiry_date, $cell_ID, $program_zone, $location_lsd, $start_date, $end_date, $volume) = $rsConHydroVac->fields;
				if($licence_effective_date != '0000-00-00' && $licence_effective_date != '') { ?>
				$( "#txtEffectiveDate<?= $i ?>").datepicker( "setDate", "<?= $licence_effective_date ?>" );
				<? }
				if($licence_expiry_date != '0000-00-00' && $licence_expiry_date != '') { ?>
				$( "#txtExpiryDate<?= $i ?>").datepicker( "setDate", "<?= $licence_expiry_date ?>" );
				<? }
				if($start_date != '0000-00-00' && $start_date != '') { ?>
				$( "#txtStartDate<?= $i ?>").datepicker( "setDate", "<?= $start_date ?>" );
				<? }
				if($end_date != '0000-00-00' && $end_date != '') { ?>
				$( "#txtEndDate<?= $i ?>").datepicker( "setDate", "<?= $end_date ?>" );
				<? }
				$rsConHydroVac->MoveNext();
				$i++;
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
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?>?resourceType=<?= $resourceType ?>" method="post">
	<input type="hidden" name="resourceType" value="<?= $resourceType ?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
		<tr>
			<th>Area</th>
			<th>Cell #</th>
			<th><?= $cellLabel[$resourceType] ?> Licence # (TDL)</th>
			<th>Licence Effective Date</th>
			<th>Licence Expiry Date</th>
			<th><?= $cellLabel[$resourceType] ?> ID</th>
			<th>Program Zone</th>
			<th>Location LSD</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Total Licensed Volume</th>
			<th width="100%">&nbsp;</th>
		</tr>
		<?
		$rsConHydroVac->MoveFirst();
		$i = 1;
		while(!$rsConHydroVac->EOF) {
			list($area, $cell_number, $cell_licence, $licence_effective_date, $licence_expiry_date, $cell_ID, $program_zone, $location_lsd, $start_date, $end_date, $volume) = $rsConHydroVac->fields;
			?>
			<tr>
				<td><input type="text" name="txtArea[]" value="<?= $area ?>" readonly></td>
				<td><input type="text" name="txtCellNumber[]" value="<?= $cell_number ?>" size="4" readonly></td>
				<td><input type="text" name="txtCellLicence[]" value="<?= $cell_licence ?>"></td>
				<td><input type="text" name="txtEffectiveDate[]" id="txtEffectiveDate<?= $i ?>" class="datepicker" value="<?= $licence_effective_date ?>"></td>
				<td><input type="text" name="txtExpiryDate[]" id="txtExpiryDate<?= $i ?>" class="datepicker" value="<?= $licence_expiry_date ?>"></td>
				<td><?= $cell_ID ?></td>
				<td><?= $program_zone ?></td>
				<td nowrap><?= $location_lsd ?></td>
				<td><input type="text" name="txtStartDate[]" id="txtStartDate<?= $i ?>" class="datepicker" value="<?= $start_date ?>"></td>
				<td><input type="text" name="txtEndDate[]" id="txtEndDate<?= $i ?>" class="datepicker" value="<?= $end_date ?>"></td>
				<td><?= $volume ?></td>
				<td width="100%">&nbsp;</td>
			</tr>
			<?
			$rsConHydroVac->MoveNext();
			$i++;
		}
		?>
		<tr>
			<td colspan="12">
				<table cellspacing="1" cellpadding="7" bgcolor="#CCCCCC" width="100%">
					<tr>
						<td><input type="submit" name="submit" value="Submit"></td>
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