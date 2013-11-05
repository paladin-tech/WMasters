<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

// If a Well is chosen, get the data for the form
if(isset($_GET['wellId'])) {
	$wellId = $_GET['wellId'];
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
				window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?wellId='+$(this).val();
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
					<td><?= $mud_product ?></td>
					<td><?= $quantity ?></td>
				</tr><?
					$rsMudProduct->MoveNext();
				}
				}
				?>
			</table>
		</td>
		<td width="100%">&nbsp;</td>
	</tr>
</table>
</form>
<? include("footer.inc"); ?>
</body>
</html>