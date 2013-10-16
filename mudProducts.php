<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

// If a Well is chosen, get the data for the form
if(isset($_GET['wellId'])) {
	$wellId = $_GET['wellId'];
	list($spud, $rr, $logged) = $infosystem->Execute("SELECT `spud`, `rr`, `logged` FROM `wells_construction` WHERE `well_id` = '{$wellId}'")->fields;
	$rsMudProduct = $infosystem->Execute("SELECT `mud_product_id`, `mud_product`, `unit`, `quantity` FROM `mud_products` WHERE `well_id` = '{$wellId}'");
	$j = 1;
	while(!$rsMudProduct->EOF) {
		list($mud_product_id, $mud_product, $unit, $quantity) = $rsMudProduct->fields;
		$mudProduct[$j] = array($mud_product_id, $mud_product, $unit, $quantity);
		$rsMudProduct->MoveNext();
		$j++;
	}
}
if(isset($_POST['submit'])) {
	foreach($_POST['txtMudProduct'] as $key => $value) {
		$mudProduct0 = $_POST['hidProductID'][$key];
		$mudProduct1 = $_POST['txtMudProduct'][$key];
		$mudProduct2 = $_POST['selUnit'][$key];
		$mudProduct3 = ($_POST['txtQuantity'][$key] == "") ? 0 : $_POST['txtQuantity'][$key];
		$well_id = $_POST['well_id'];
		if($mudProduct0 == "" && $mudProduct1 != "") $infosystem->Execute("INSERT INTO `mud_products` SET `well_id` = '{$well_id}', `mud_product` = '{$mudProduct1}', `unit` = '{$mudProduct2}', `quantity` = {$mudProduct3}");
		if($mudProduct0 != "" && $mudProduct1 != "") $infosystem->Execute("UPDATE `mud_products` SET `mud_product` = '{$mudProduct1}', `unit` = '{$mudProduct2}', `quantity` = {$mudProduct3} WHERE `mud_product_id` = {$mudProduct0}");
	}
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
				window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
			});
		});
	</script>
</head>

<body>
<? include ('header.inc');?>
</body>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="well_id" value="<?= (isset($wellId)) ? $wellId : "" ?>">
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr valign="top">
		<td colspan="2">
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td>Well ID</td>
					<td>
						<select name="selWell" id="selWell">
							<option value="">[choose the well]</option>
							<?
							while(!$rsWellLicence->EOF) {
								list($well_id) = $rsWellLicence->fields;
								var_dump((isset($wellId) && $wellId == $well_id)); ?>
								<option value="<?= $well_id ?>"<?= (isset($wellId) && $wellId == $well_id) ? " selected" : "" ?>><?= $well_id ?></option><?
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
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td>Mud Products</td>
					<td>Units</td>
					<td>Quantity</td>
				</tr>
				<?
				for($i = 1; $i <= 10; $i++) {
				?>
				<tr id="row<?= $i ?>">
					<input type="hidden" name="hidProductID[]" value="<?= (isset($wellId)) ? $mudProduct[$i][0] : "" ?>">
					<td><input type="text" name="txtMudProduct[]" value="<?= (isset($wellId)) ? $mudProduct[$i][1] : "" ?>"></td>
					<td>
						<select name="selUnit[]">
							<option value="">[select a unit]</option>
							<option value="sacks"<?= (isset($wellId) && $mudProduct[$i][2] == "sacks") ? " selected" : "" ?>>Sacks</option>
							<option value="kg"<?= (isset($wellId) && $mudProduct[$i][2] == "kg") ? " selected" : "" ?>>kg</option>
						</select>
					</td>
					<td><input type="text" name="txtQuantity[]" value="<?= (isset($wellId)) ? $mudProduct[$i][3] : "" ?>"></td>
				</tr><?
				}
				?>
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
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
				<tr>
					<td><input type="submit" name="submit" value="Submit"<?= (isset($wellId)) ? "" : "disabled=disabled" ?>></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<? include("footer.inc"); ?>
</body>
</html>