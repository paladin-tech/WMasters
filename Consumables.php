<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	$infosystem->Execute("INSERT INTO `material_transactions` SET `materialCode` = '{$selMaterial}', `transactionType` = '{$selTransactionType}', `quantity` = {$txtQuantity}, `date` = '{$txtDate}', `wellId` = '{$selWell}', `modified` = DATE(NOW())");
}

$rsMaterial = $infosystem->Execute("SELECT `materialCode`, `materialName` FROM `material_list` WHERE `active` = 1");
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` ORDER BY `mainboard`");

$rsMaterialTransaction = $infosystem->Execute("SELECT ml.`materialName`, ml.`materialCode`, mt.`transactionType`, mt.`quantity`, mt.`date`, mt.`wellId` FROM `material_list` ml, `material_transactions` mt WHERE ml.`materialCode` = mt.`materialCode` AND `modified` = DATE(NOW())");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Consumables - WM Digital System</title>
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

			$('#selTransactionType').change(function() {
				if($(this).val() == 'w') $('#selWell').removeAttr('disabled');
				else $('#selWell').attr('disabled', 'disabled');
			});

			$('#selMaterial').change(function() {
				$('#tdMaterialCode').html($(this).val());
			});

			$('#frm').submit(function(event) {
				$('.newData').each(function() {
					transactionType = $("input[name='radTransactionType']:checked").val();
					if($(this).val() == '' || (transactionType == 'd' && $('#selWell').val() == '')) {
						alert('You have to enter the data!');
						event.preventDefault();
						return false;
					}
				});
			});
		});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
		<tr>
			<th>Material</th>
			<th>Code</th>
			<th>Quantity</th>
			<th>Date</th>
			<th>Transaction Type</th>
			<th>Well ID</th>
			<th width="100%">&nbsp;</th>
		</tr>
		<?
		while(!$rsMaterialTransaction->EOF) {
			list($xMaterialName, $xMaterialCode, $xTransactionType, $xQuantity, $xDate, $xWellId) = $rsMaterialTransaction->fields;
		?>
		<tr>
			<td><?= $xMaterialName ?></td>
			<td><?= $xMaterialCode ?></td>
			<td align="right"><?= $xQuantity ?></td>
			<td align="center"><?= $xDate ?></td>
			<td align="center"><?= ($xTransactionType == 'd') ? 'Add to Yard' : 'Use at Well' ?></td>
			<td align="center"><?= $xWellId ?></td>
			<td width="100%">&nbsp;</td>
		</tr>
		<?
			$rsMaterialTransaction->MoveNext();
		}
		?>
		<tr>
			<td>
				<select name="selMaterial" id="selMaterial" class="newData">
					<option value="">[Material]</option><?
					while(!$rsMaterial->EOF) {
						list($yMaterialCode, $yMaterialName) = $rsMaterial->fields; ?>
						<option value="<?=$yMaterialCode?>"><?=$yMaterialName?></option><?
						$rsMaterial->MoveNext();
					} ?>
				</select>
			</td>
			<td id="tdMaterialCode">&nbsp;</td>
			<td align="right"><input type="text" name="txtQuantity" id="txtQuantity" class="newData" size="6" style="text-align: right"></td>
			<td><input type="text" name="txtDate" id="txtDate" class="newData datepicker"></td>
			<td id="tdTransactionType">
				<select name="selTransactionType" id="selTransactionType" class="newData">
					<option value="">[Transaction Type]</option>
					<option value="d">Add to Yard</option>
					<option value="w">Use at Well</option>
				</select>
			</td>
			<td class="columnWell">
				<select name="selWell" id="selWell">
					<option value="">[Well ID]</option><?
					while(!$rsWellLicence->EOF) {
						list($yWellId) = $rsWellLicence->fields; ?>
						<option value="<?=$yWellId?>"><?=$yWellId?></option><?
						$rsWellLicence->MoveNext();
					} ?>
				</select>
			</td>
			<td width="100%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="8">
				<table cellspacing="1" cellpadding="7" bgcolor="#CCCCCC" width="100%">
					<tr>
						<td><input type="submit" name="submit" value="Submit"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>