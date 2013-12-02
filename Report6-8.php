<?
include("sessionCheck.php");
include("db.php");
include_once("adodb/toexport.inc.php");

$resourceType = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 'vacuum' : 'water';
$report = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 8 : 6;

$todayShort = date("y-m-d", mktime());
$today = date("Y-m-d", mktime());
$dateOfReport = isset($_POST['txtDate'])?$_POST['txtDate']:$today;
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report {$report}', '{$_SESSION['username']}')");

$rsReport = $infosystem->Execute("SELECT wv.`unit`, chv.`area`, chv.`cell_number`, wv.`volume` FROM `con_hydro_vac` chv, `water_vacuum` wv WHERE chv.`con_hydro_vac_id` = wv.`con_hydro_vac_id` AND `type` = '{$resourceType}' AND wv.`input_date` = '{$dateOfReport}' ORDER BY chv.`area`, chv.`cell_number`");
$rsReportColumns = $infosystem->Execute("SELECT DISTINCT chv.`cell_number` FROM `con_hydro_vac` chv, `water_vacuum` wv WHERE chv.`con_hydro_vac_id` = wv.`con_hydro_vac_id` AND `type` = '{$resourceType}' AND wv.`input_date` = '{$dateOfReport}' ORDER BY chv.`cell_number`");

// $report = array();
while(!$rsReport->EOF) {
	list($xUnit, $xArea, $xCellNumber, $xVolume) = $rsReport->fields;
	$reportData[$xUnit][$xArea][$xCellNumber] += $xVolume;
	$rsReport->MoveNext();
}

$reportURL = "reports/Report{$report}-{$todayShort}.csv";

$fp = fopen("{$reportURL}", "w");
if($fp) {
	rs2csvfile($rsReport, $fp);
	fclose($fp);
}
//mail("predragl@wmasters.ca", "WM Digital - Report #{$report}, {$today}", "Please find Report #{$report} created on {$today} on the following URL:\r\n\r\nhttp://wmasters.d-zine.ca/{$reportURL}", "From: WM Digital <admin@wellsitemasters.com>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html><head>
<title>Report <?=$report?> - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$( ".datepicker" ).datepicker();
			$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

		});
	</script>
</head>

<body>
<? include("header.inc"); ?>
<table cellspacing="1" cellpadding="5" width="100%">
	<tr>
		<td>
			<form name="frm" action="<?=$_SERVER['PHP_SELF']?>?resourceType=<?= $resourceType ?>" method="post">
			<table cellspacing="1" cellpadding="3">
				<tr>
					<td align="center">Date:</td>
			        <td><input type="txt" name="txtDate" id="txtDate" class="datepicker"></td>
			        <td><input type="submit" name="submit" value="Create Report" /></td>
			    </tr>
			</table>
			<table cellspacing="1" cellpadding="6"><?
			if($rsReport->RecordCount()==0) { ?>
				<tr>
			        <td colspan="7">No records for this report.</td>
			    </tr><?
			} else { ?>
			    <tr>
			        <th>Unit</th>
			        <th>Area</th>
				    <?
				    while(!$rsReportColumns->EOF) {
					    list($yCellNumber) = $rsReportColumns->fields;
				    ?>
			        <th><?= $yCellNumber ?></th>
				    <?
				        $rsReportColumns->MoveNext();
				    }
				    ?>
			    </tr><?
				foreach($reportData as $zUnit => $value1) {
					$zArea = array_keys($reportData[$zUnit]);
					$zArea = $zArea[0];
				?>
			    <tr>
					<td><?= $zUnit ?></td>
			        <td><?= $zArea ?></td>
				    <?
				    $rsReportColumns->MoveFirst();
				    while(!$rsReportColumns->EOF) {
					    list($yCellNumber) = $rsReportColumns->fields;
					    ?>
					    <td align="right"><?= ($reportData[$zUnit][$zArea][$yCellNumber] != 0) ? number_format($reportData[$zUnit][$zArea][$yCellNumber], 2) : "" ?></td>
					    <?
					    $rsReportColumns->MoveNext();
				    }
			    }
			    ?>
			    </tr><?
			} ?>
			</table>
			</form>
		</td>
	</tr>
</table>
<? include ('footer.inc'); ?>
</body>
</html>