<?
include("sessionCheck.php");
include("db.php");

if(isset($_POST['submit'])) {

	foreach($_POST as $key => $value) $$key = $value;

	$active = (isset($_POST['active'])) ? 1 : 0;

	if($mud_product_id == 0) $infosystem->Execute("INSERT INTO `mud_product_list` SET `mud_product` = '{$txtMudProduct}', `active` = {$active}");
	else $infosystem->Execute("UPDATE `mud_product_list` SET `mud_product` = '{$txtMudProduct}', `active` = {$active} WHERE `mud_product_id` = '{$mud_product_id}'");

}

$rsMudProductList = $infosystem->Execute("SELECT `mud_product_id`, `mud_product` FROM `mud_product_list`");

if(isset($_GET['mud_product_id'])) {

	$mud_product_id = $_GET['mud_product_id'];

	list($mud_product, $active) = $infosystem->Execute("SELECT `mud_product`, `active` FROM `mud_product_list` WHERE `mud_product_id` = '{$mud_product_id}'")->fields;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Mud Products Administration - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="js/jquery.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#selMudProduct').change(function() {
			window.location.href = ($(this).val() == '') ? '<?= $_SERVER["PHP_SELF"] ?>' : '<?= $_SERVER["PHP_SELF"] ?>?mud_product_id='+$(this).val();
		});
		$('#frm').submit(function() {
			if(!confirm('Are you sure you want to make changes?')) return false;
		});
	});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($mud_product_id)) ? "?mud_product_id={$mud_product_id}" : "" ?>" method="post">
	<input type="hidden" name="mud_product_id" id="mud_product_id" value="<?= (isset($mud_product_id)) ? $mud_product_id : 0?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
		<tr valign="top">
			<td>Mud Product</td>
			<td>
				<select name="selMudProduct" id="selMudProduct">
					<option value="">[New Mud Product]</option>
					<?
					while(!$rsMudProductList->EOF) {
						list($x_mud_product_id, $x_mud_product) = $rsMudProductList->fields;
					?>
					<option value="<?= $x_mud_product_id ?>"<?= (isset($mud_product_id) && $mud_product_id == $x_mud_product_id) ? " selected" : ""?>><?= $x_mud_product ?></option>
					<?
						$rsMudProductList->MoveNext();
					}
					?>
				</select>
			</td>
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<td>Mud Product</td>
						<td><input type="text" name="txtMudProduct" value="<?= (isset($mud_product_id)) ? $mud_product : ""?>"></td>
					</tr>
					<tr>
						<td>Active</td>
						<td><input type="checkbox" name="active" value="1"<?= (isset($mud_product_id) && $active == 1) ? " checked" : "" ?>></td>
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