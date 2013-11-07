<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` ORDER BY `mainboard`");
$rsMudProductList = $infosystem->Execute("SELECT `mud_product_id`, `mud_product` FROM `mud_product_list`");

if(isset($_POST['submit'])) {
	foreach($_POST['selMudProduct'] as $key => $value) {
		$mudProduct0 = $_POST['hidProductID'][$key];
		$mudProduct1 = $_POST['selMudProduct'][$key];
		$mudProduct2 = ($_POST['txtQuantity'][$key] == "") ? 0 : $_POST['txtQuantity'][$key];
		$well_id = $_POST['well_id'];
		if($mudProduct0 == "" && $mudProduct1 != "") $infosystem->Execute("INSERT INTO `mud_products` SET `well_id` = '{$well_id}', `mud_product` = '{$mudProduct1}', `quantity` = {$mudProduct2}");
		if($mudProduct0 != "" && $mudProduct1 != "") $infosystem->Execute("UPDATE `mud_products` SET `mud_product` = '{$mudProduct1}', `quantity` = {$mudProduct2} WHERE `mud_product_id` = {$mudProduct0}");
	}
}

// If a Well is chosen, get the data for the form
if(isset($_GET['wellId'])) {
	$wellId = $_GET['wellId'];
	list($spud, $rr, $logged) = $infosystem->Execute("SELECT `spud`, `rr`, `logged` FROM `wells_construction` WHERE `well_id` = '{$wellId}'")->fields;
	$rsMudProduct = $infosystem->Execute("SELECT `mud_product_id`, `mud_product`, `quantity` FROM `mud_products` WHERE `well_id` = '{$wellId}'");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Mud Products - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="js/jquery.js"></script>
	<? $xajax->printJavascript(); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#selWell').change(function() {
				if($(this).val() == '') $('#submit').attr('disabled', true);
				if($(this).val() != '') window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
			});

//			$('#mudProducts > tbody > tr').not('tr:first').length;
		});
	</script>
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
	</tr>
	<tr valign="top">
		<td>
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%" id="mudProducts">
				<tr>
					<td>Mud Products</td>
					<td>Quantity</td>
				</tr>
				<?
				if(isset($wellId)) {
				while(!$rsMudProduct->EOF) {
					list($mud_product_id, $mud_product, $quantity) = $rsMudProduct->fields;
				?>
				<tr>
					<input type="hidden" name="hidProductID[]" value="<?= (isset($wellId)) ? $mud_product_id : "" ?>">
					<td>
						<select name="selMudProduct[]">
							<option value="">[Select a Mud Product]</option>
							<?
							$rsMudProductList->MoveFirst();
							while(!$rsMudProductList->EOF) {
								list($x_mud_product_id, $x_mud_product) = $rsMudProductList->fields;
							?>
							<option value="<?= $x_mud_product_id ?>"<?= (isset($wellId) && $mud_product == $x_mud_product_id) ? " selected" : ""?>><?= $x_mud_product ?></option>
							<?
								$rsMudProductList->MoveNext();
							}
							?>
						</select>
					</td>
					<td><input type="text" name="txtQuantity[]" value="<?= (isset($wellId)) ? $quantity : "" ?>"></td>
				</tr><?
					$rsMudProduct->MoveNext();
				}
				}
				?>
				<tr>
					<input type="hidden" name="hidProductID[]" value="">
					<td>
						<select name="selMudProduct[]">
							<option value="">[Select a Mud Product]</option>
							<?
							$rsMudProductList->MoveFirst();
							while(!$rsMudProductList->EOF) {
								list($x_mud_product_id, $x_mud_product) = $rsMudProductList->fields;
								?>
								<option value="<?= $x_mud_product_id ?>"><?= $x_mud_product ?></option>
								<?
								$rsMudProductList->MoveNext();
							}
							?>
						</select>
					</td>
					<td><input type="text" name="txtQuantity[]" value=""></td>
				</tr>
			</table>
		</td>
		<td>
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td>Spud</td>
					<td><input type="text" name="txtSpud" value="<?= $spud ?>"></td>
				</tr>
				<tr>
					<td>RR</td>
					<td><input type="text" name="txtRR" value="<?= $rr ?>"></td>
				</tr>
				<tr>
					<td>Logged</td>
					<td><input type="text" name="txtLogged" value="<?= $logged ?>"></td>
				</tr>
				<tr>
					<td>Cemented</td>
					<td><input type="text" name="txtCemented" value=""></td>
				</tr>
			</table>
		</td>
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
	</tr>
</table>
</form>
<? include("footer.inc"); ?>
</body>
</html>