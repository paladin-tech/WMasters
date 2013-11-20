<?
include("sessionCheck.php");
include("db.php");

if(isset($_POST['submit'])) {

	foreach($_POST as $key => $value) $$key = $value;

	$active = (isset($_POST['active'])) ? 1 : 0;

	if($bertramMaterialId == "") $infosystem->Execute("INSERT INTO `bertram_material_list` SET `materialCode` = '{$txtBertramProductCode}', `materialName` = '{$txtBertramProduct}', `active` = {$active}");
	else $infosystem->Execute("UPDATE `bertram_material_list` SET `materialCode` = '{$txtBertramProductCode}', `materialName` = '{$txtBertramProduct}', `active` = {$active} WHERE `materialCode` = '{$bertramMaterialId}'");

}

$rsBertramMaterialList = $infosystem->Execute("SELECT `materialCode`, `materialName` FROM `bertram_material_list`");

if(isset($_GET['bertramMaterialId'])) {

	$bertramMaterialId = $_GET['bertramMaterialId'];

	list($materialCode, $materialName, $active) = $infosystem->Execute("SELECT `materialCode`, `materialName`, `active` FROM `bertram_material_list` WHERE `materialCode` = '{$bertramMaterialId}'")->fields;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Bertram Material List Administration - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="js/jquery.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#selMudProduct').change(function() {
			window.location.href = ($(this).val() == '') ? '<?= $_SERVER["PHP_SELF"] ?>' : '<?= $_SERVER["PHP_SELF"] ?>?bertramMaterialId='+$(this).val();
		});
		$('#frm').submit(function() {
			if(!confirm('Are you sure you want to make changes?')) return false;
		});
	});
	</script>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($bertramMaterialId) && $bertramMaterialId != "") ? "?bertramMaterialId={$bertramMaterialId}" : "" ?>" method="post">
	<input type="hidden" name="bertramMaterialId" id="bertramMaterialId" value="<?= (isset($bertramMaterialId)) ? $bertramMaterialId : ""?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
		<tr valign="top">
			<td colspan="2">
				Bertram Material<br>
				<select name="selMudProduct" id="selMudProduct">
					<option value="">[New Bertram Material]</option>
					<?
					while(!$rsBertramMaterialList->EOF) {
						list($x_materialCode, $x_materialName) = $rsBertramMaterialList->fields;
					?>
					<option value="<?= $x_materialCode ?>"<?= (isset($bertramMaterialId) && $bertramMaterialId == $x_materialCode) ? " selected" : ""?>><?= $x_materialCode ?>: <?= $x_materialName ?></option>
					<?
						$rsBertramMaterialList->MoveNext();
					}
					?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<th colspan="2" nowrap><?= (isset($bertramMaterialId)) ? "Update" : "New" ?> Product Data</th>
					</tr>
					<tr>
						<td nowrap>Bertram<br>Product Code</td>
						<td><input type="text" name="txtBertramProductCode" value="<?= (isset($bertramMaterialId)) ? $materialCode : ""?>"></td>
					</tr>
					<tr>
						<td nowrap>Bertram<br>Product</td>
						<td><input type="text" name="txtBertramProduct" value="<?= (isset($bertramMaterialId)) ? htmlentities($materialName) : ""?>" size="60"></td>
					</tr>
					<tr>
						<td>Active</td>
						<td><input type="checkbox" name="active" value="1"<?= (isset($bertramMaterialId) && $active == 1) ? " checked" : "" ?>></td>
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