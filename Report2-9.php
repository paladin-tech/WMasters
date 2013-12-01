<?
include("sessionCheck.php");
include("db.php");
include_once("adodb/toexport.inc.php");

$resourceType = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 'vacuum' : 'water';
$report = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 9 : 2;
$moduleLabels = array('water' => array('LicenceName' => 'Water', 'CellName' => 'Source'), 'vacuum' => array('LicenceName' => 'Vacuum', 'CellName' => 'Sump'));

$todayShort = date("y-m-d", mktime());
$today = date("Y/m/d", mktime());
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report {$report}', '{$_SESSION['username']}')");

$rsReport = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro` ORDER BY `area`, `source_number`");

$rsConHydro = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro` ORDER BY `area`, `source_number`");

$rsArea = $infosystem->Execute("SELECT DISTINCT `area` FROM `con_hydro_vac` WHERE `type` = '{$resourceType}'ORDER BY `area`");
// $rsArea = $infosystem->Execute("SELECT DISTINCT `area` FROM `con_hydro_vac` WHERE `type` = '{$resourceType}' AND (NOW() BETWEEN `start_date` AND `end_date`) OR (NOW() > `start_date` AND `end_date` = '0000-00-00')");
while(!$rsArea->EOF) {
	list($xArea) = $rsArea->fields;
	$rsWaterVacuumReport[$xArea] = $infosystem->Execute("SELECT chv.`cell_number`, chv.`cell_licence`, chv.`cell_ID`, chv.`program_zone`, chv.`location_LSD`, chv.`start_date`, chv.`end_date`, chv.`total_licensed_volume`, SUM(wv.`volume`) FROM `water_vacuum` wv, `con_hydro_vac` chv WHERE wv.`con_hydro_vac_id` = chv.`con_hydro_vac_id` AND chv.`type` = '{$resourceType}' AND chv.`area` = '{$xArea}'");
	$rsArea->MoveNext();
}

$reportURL = "reports/Report{$report}-{$todayShort}.csv";

$fp = fopen("{$reportURL}", "w");
if($fp) {
	rs2csvfile($rsReport, $fp);
	fclose($fp);
}
//mail("predragl@wmasters.ca", "WM Digital - Report #{$report}, {$today}", "Please find Report #{$report} created on {$today} on the following URL:\r\n\r\nhttp://www.wellsitemasters.com/{$reportURL}", "From: WM Digital <admin@wellsitemasters.com>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report <?=$report?> - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="5" width="100%">
	<tr>
		<td>
			<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
				<?
				$sumTotalLicensedVolume = $sumTotalUsedToDate = 0;
				foreach ($rsWaterVacuumReport as $key => $rs) {
				?>
				<tr>
					<th>Area</th>
					<th>Cell #</th>
					<th><?= $moduleLabels[$resourceType]['LicenceName']?> Licence #<br>(TDL)</th>
					<th><?= $moduleLabels[$resourceType]['CellName']?></th>
					<th>Description</th>
					<th>Location LSD</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Total Licenced<br>Volume (m3)</th>
					<th>Total Used<br>to Date (m3)</th>
				</tr>
				<?
				while(!$rs->EOF) {
					list($xCellNumber, $xCellLicence, $xCellID, $xProgramZone, $xLocationLSD, $xStartDate, $xEndDate, $xTotalLicensedVolume, $xVolume) = $rs->fields;
					$sumTotalLicensedVolume += $xTotalLicensedVolume;
					$sumTotalUsedToDate += $xVolume;
					?>
					<tr>
						<th rowspan="<?= $rs->RecordCount() ?>"><?= $key ?></th>
						<td><?= $xCellNumber ?></td>
						<td><?= $xCellLicence ?></td>
						<td><?= $xCellID ?></td>
						<td><?= $xProgramZone ?></td>
						<td><?= $xLocationLSD ?></td>
						<td><?= $xStartDate ?></td>
						<td><?= $xEndDate ?></td>
						<td align="right"><?= number_format($xTotalLicensedVolume, 2) ?></td>
						<td align="right"><?= number_format($xVolume, 2) ?></td>
					</tr>
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>
					<?
					$rs->MoveNext();
				}
				}
				?>
				<tr>
					<th colspan="8" align="right">Total All Sources:</th>
					<th align="right"><?= number_format($sumTotalLicensedVolume, 2) ?></th>
					<th align="right"><?= number_format($sumTotalUsedToDate, 2) ?></th>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</table>
<? include ('footer.inc');?>
</body>
</html>