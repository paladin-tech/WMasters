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

while(!$rsArea->EOF) {
	list($xArea) = $rsArea->fields;
	$rsWaterVacuumReport[$xArea] = $infosystem->Execute("SELECT chv.`cell_number`, chv.`cell_licence`, chv.`cell_ID`, chv.`program_zone`, chv.`location_LSD`, chv.`start_date`, chv.`end_date`, chv.`total_licensed_volume`, SUM(wv.`volume`) FROM `water_vacuum` wv, `con_hydro_vac` chv WHERE wv.`con_hydro_vac_id` = chv.`con_hydro_vac_id` AND chv.`type` = '{$resourceType}' AND chv.`area` = '{$xArea}' GROUP BY wv.`volume` ORDER BY chv.`cell_number`");

	while(!$rsWaterVacuumReport[$xArea]->EOF) {
		list($xCellNumber, $xCellLicence, $xCellID, $xProgramZone, $xLocationLSD, $xStartDate, $xEndDate, $xTotalLicensedVolume, $xVolume) = $rsWaterVacuumReport[$xArea]->fields;
		$reportData[$xArea][$xCellNumber] = $rsWaterVacuumReport[$xArea]->fields;
		$volumeData[$xArea][$xCellNumber] += $xVolume;
		$rsWaterVacuumReport[$xArea]->MoveNext();
	}

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
				$rsArea->MoveFirst();
				while(!$rsArea->EOF) {
					list($yArea) = $rsArea->fields;
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
					<th>Amount<br>Left (m3)</th>
				</tr>
				<?
				if(sizeof($reportData[$yArea]) > 0) {
				$i = 1;
				$warning = false;
				foreach($reportData[$yArea] as $xCellNumber => $value) {
					$sumTotalLicensedVolume += $reportData[$yArea][$xCellNumber]['total_licensed_volume'];
					$sumTotalUsedToDate += $volumeData[$yArea][$xCellNumber];
					$amountLeft = $reportData[$yArea][$xCellNumber]['total_licensed_volume'] - $volumeData[$yArea][$xCellNumber];
					if($amountLeft <= 200) $warning = true;
				?>
					<tr>
						<?
						if($i == 1) {
						?>
						<th rowspan="<?= sizeof($reportData[$yArea]) ?>"><?= $yArea ?></th>
						<?
						}
						?>
						<td><?= $reportData[$yArea][$xCellNumber]['cell_number'] ?></td>
						<td><?= $reportData[$yArea][$xCellNumber]['cell_licence'] ?></td>
						<td><?= $reportData[$yArea][$xCellNumber]['cell_ID'] ?></td>
						<td><?= $reportData[$yArea][$xCellNumber]['program_zone'] ?></td>
						<td><?= $reportData[$yArea][$xCellNumber]['location_LSD'] ?></td>
						<td><?= $reportData[$yArea][$xCellNumber]['start_date'] ?></td>
						<td><?= $reportData[$yArea][$xCellNumber]['end_date'] ?></td>
						<td align="right"><?= number_format($reportData[$yArea][$xCellNumber]['total_licensed_volume'], 2) ?></td>
						<td align="right"><?= number_format($volumeData[$yArea][$xCellNumber], 2) ?></td>
						<td align="right"<? if($amountLeft <= 200) { ?> class="warning"<? } ?>><?= number_format($amountLeft, 2) ?></td>
					</tr>
					<?
					$i++;
				}
				}
				?>
				<tr>
					<? if($warning) { ?>
					<td colspan="11" align="center" class="warning">Warning: 200m3 limit is reached, please advise supervisor.</td>
					<? } else { ?>
					<td colspan="11">&nbsp;</td>
					<? } ?>
				</tr>
				<?
					$rsArea->MoveNext();
				}
				?>
				<tr>
					<th colspan="8" align="right">Total All Sources:</th>
					<th align="right"><?= number_format($sumTotalLicensedVolume, 2) ?></th>
					<th align="right"><?= number_format($sumTotalUsedToDate, 2) ?></th>
					<th align="right"><?= number_format(($sumTotalLicensedVolume - $sumTotalUsedToDate), 2) ?></th>
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