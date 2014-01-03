<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

//$infosystem->debug = true;

$wasteCategory = array('1' => 'Non Hazardous', '2' => 'Hazardous', '3' => 'Recyclables');

$rsContractor = $infosystem->Execute("SELECT `contractorId`, `contractor` FROM `wm_waste_contractor`");
$rsSite = $infosystem->Execute("SELECT `siteId`, `site` FROM `wm_site`");

if(isset($_POST['submit'])) {

	foreach($_POST as $key => $value) $$key = $value;

	if($txtDate != "" && $selContractor != "" && $selSite != "" && $selWasteCategory != "" && $selWasteType != "" && $txtQuantity != "" && $txtManifest != "") {
		$infosystem->Execute("INSERT INTO `wm_waste` SET `date` = '{$txtDate}', `contractorId` = {$selContractor}, `siteId` = {$selSite}, `wasteTypeId` = {$selWasteType}, `quantity` = {$txtQuantity}, `manifest` = '{$txtManifest}', `user` = {$userID}, `dateTimeStamp` = NOW()");
	} else {
		$errorMsg = "Save failed. Please fill all the data in the form.";
	}

}

$rsWaste = $infosystem->Execute("SELECT w.`date`, w.`contractorId`, wc.`contractor`, w.`siteId`, s.`site`, w.`wasteTypeId`, wt.`wasteType`, wt.`wasteCategoryId`, w.`quantity`, w.`manifest` FROM `wm_waste` w, `wm_waste_contractor` wc, `wm_site` s, `wm_waste_type` wt WHERE w.`contractorId` = wc.`contractorId` AND w.`siteId` = s.`siteId` AND w.`wasteTypeId` = wt.`wasteTypeId` AND DATE(w.`dateTimeStamp`) = DATE(NOW())");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Waste Module - WM Digital System</title>
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

			$('#frm').submit(function(event) {
				$('.newData').each(function() {
					if($(this).val() == '') {
						alert('You have to enter the data!');
						event.preventDefault();
						return false;
					} else {
						if(($('#txtQuantity').val() != '' && !($.isNumeric($('#txtQuantity').val()))) || $('#txtQuantity').val() <= 0) {
							alert('Quantity must be a numeric value greater than zero!');
							event.preventDefault();
							return false;
						}
					}
				});
			});

			$('#selWasteCategory').change(function() {
				xajax_getWasteType($(this).val());
			});
		});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
		<tr>
			<td>
				<? if($errorMsg != "") echo "{$errorMsg}<br>"; ?>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<th>Date</th>
						<th>Contractor</th>
						<th>Site</th>
						<th>Waste Category</th>
						<th nowrap style="width: 185px">Waste Type</th>
						<th>Quantity</th>
						<th>Manifest</th>
					</tr>
					<?
					while(!$rsWaste->EOF) {
						list($xDate, $xContractorId, $xContractor, $xSiteId, $xSite, $xWasteTypeId, $xWasteType, $xWasteCategoryId, $xQuantity, $xManifest) = $rsWaste->fields;
						$locked = ($dateTimeStamp != date('Y-m-d'));
					?>
					<tr>
						<td align="center"><?= $xDate ?></td>
						<td align="center"><?= $xContractor ?></td>
						<td align="center"><?= $xSite ?></td>
						<td align="center"><?= $wasteCategory[$xWasteCategoryId] ?></td>
						<td align="center" nowrap><?= $xWasteType ?></td>
						<td align="right"><?= $xQuantity ?></td>
						<td><?= $xManifest ?></td>
					</tr>
					<?
						$rsWaste->MoveNext();
					}
					?>
					<tr>
						<td><input type="text" class="datepicker newData" name="txtDate" id="txtDate" value=""></td>
						<td>
							<select name="selContractor" class="newData">
								<option value="">[Contractor]</option>
								<?
								while(!$rsContractor->EOF) {
									list($xContractorId, $xContractor) = $rsContractor->fields;
								?>
								<option value="<?= $xContractorId ?>"><?= $xContractor ?></option>
								<?
									$rsContractor->MoveNext();
								}
								?>
							</select>
						</td>
						<td>
							<select name="selSite" class="newData">
								<option value="">[Site]</option>
								<?
								while(!$rsSite->EOF) {
									list($xSiteId, $xSite) = $rsSite->fields;
								?>
								<option value="<?= $xSiteId ?>"><?= $xSite ?></option>
								<?
									$rsSite->MoveNext();
								}
								?>
							</select>
						</td>
						<td>
							<select name="selWasteCategory" id="selWasteCategory" class="newData">
								<option value="">[Category]</option>
								<?
								foreach($wasteCategory as $key => $value) {
								?>
								<option value="<?= $key ?>"><?= $value ?></option>
								<?
								}
								?>
							</select>
						</td>
						<td id="tdWasteType">
							<select name="selWasteType" id="selWasteType" class="newData">
								<option value="">[Type]</option>
							</select>
						</td>
						<td>
							<input type="text" name="txtQuantity" id="txtQuantity" class="quantity newData" size="6" style="text-align: right">
						</td>
						<td>
							<input type="text" name="txtManifest" class="newData">
						</td>
					</tr>
					<tr>
						<td colspan="7">
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
	</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>