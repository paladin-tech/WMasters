<?
include("sessionCheck.php");
include("db.php");

$resourceType = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 'vacuum' : 'water';

if(isset($_POST['submit'])) {
	foreach($_POST['selWell'] as $key => $value) {

	}
}

$rsArea = $infosystem->Execute("SELECT DISTINCT `area` FROM `con_hydro_vac` WHERE `type` = 'water' AND (NOW() BETWEEN `start_date` AND `end_date`) OR (NOW() > `start_date` AND `end_date` = '0000-00-00')");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Daily Mud Module - WM Digital System</title>
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
					window.location.href = '<?= $_SERVER["PHP_SELF"] ?>?truckId=' + $("#selTruck").val() + '&dateDaily=' + $("#txtDate").val();
				}
			});
			<?
			if((isset($truckId) && isset($dateDaily))) {
			?>
			$( "#txtDate").datepicker( "setDate", "<?= $dateDaily ?>" );
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
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($truckId) && isset($dateDaily)) ? "?truckId={$truckId}&dateDaily={$dateDaily}" : "" ?>" method="post">
	<input type="hidden" name="truckId" value="<?= (isset($truckId)) ? $truckId : "" ?>">
	<input type="hidden" name="dateDaily" value="<?= (isset($dateDaily)) ? $dateDaily : "" ?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
		<tr>
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<td>Report</td>
					</tr>
				</table>
				<br>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
					<tr>
						<th>Area</th>
						<th>Source #</th>
						<th>Water Licence # (TDL)</th>
						<th>Source ID</th>
						<th>Description</th>
						<th>Location LSD</th>
						<th>Start Date</th>
						<th>End Date</th>
					</tr>
					<?
					while(!$rsArea->EOF) {
						list() = $rsArea->fields;
					?>
					<tr>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
						<td>a</td>
					</tr>
					<?
						$rsArea->MoveNext();
					}
					?>
				</table>
			</td>
			<td width="100%">&nbsp;</td>
		</tr>
	</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>