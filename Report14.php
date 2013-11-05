<?
include("sessionCheck.php");
include("db.php");

$rsWells = $infosystem->Execute("SELECT `well_id`, `issued_for_rollback`  FROM `wells_construction` WHERE `active` = 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Construction Requests - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		table td {
			white-space: nowrap;
		}
		.bold, th {
			font-weight: bold;
		}
	</style>
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
	<tr>
		<th>Rollback Issued</th>
		<th width="100%"></th>
	</tr>
	<?
	while(!$rsWells->EOF) {
		list($well_id, $issued_for_rollback) = $rsWells->fields;

		$tokenRollback = ($issued_for_rollback != '0000-00-00' && $issued_for_rollback != '') ? true : false;

		// Print only Wells that have any relevant data (omit Wells with those data missing)
		if($tokenRollback) {
		?>
		<tr>
			<td><?= ($tokenRollback) ? $well_id : "" ?></td>
			<td width="100%"></td>
		</tr>
		<?
		}
		$rsWells->MoveNext();
	}
	?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>