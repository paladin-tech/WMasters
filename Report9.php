<?
include("sessionCheck.php");
include("db.php");
include_once("adodb/toexport.inc.php");

$todayShort = date("y-m-d", mktime());
$today = date("Y/m/d", mktime());
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report 9', '{$_SESSION['username']}')");

$rsReport = $infosystem->Execute("SELECT `area`, `sump_number`, `sump_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_vacuum`");

$rsConVacuum = $infosystem->Execute("SELECT `area`, `sump_number`, `sump_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_vacuum`");

$areas = array("Kearl", "Syncrude");
$conVacuumArray = array();
while(!$rsConVacuum->EOF) {
	list($x_area, $x_sump_number, $x_sump_ID, $x_program_zone, $x_location_LSD, $x_start_date, $x_end_date, $x_total_licensed_volume) = $rsConVacuum->fields;
	$conVacuumArray["sump_ID"][$x_area][$x_sump_number] = $x_sump_ID;
	$conVacuumArray["program_zone"][$x_area][$x_sump_number] = $x_program_zone;
	$conVacuumArray["location_LSD"][$x_area][$x_sump_number] = $x_location_LSD;
	$conVacuumArray["start_date"][$x_area][$x_sump_number] = ($x_start_date!="0000-00-00")?$x_start_date:"";
	$conVacuumArray["end_date"][$x_area][$x_sump_number] = ($x_end_date!="0000-00-00")?$x_end_date:"";
	$conVacuumArray["total_licensed_volume"][$x_area][$x_sump_number] = $x_total_licensed_volume;
	list($conVacuumArray["total_used_to_date"][$x_area][$x_sump_number]) = $infosystem->Execute("SELECT SUM(loc_{$x_sump_number}) FROM `vacuum` WHERE `area` = '{$x_area}'")->fields;
	$rsConVacuum->MoveNext();
}

foreach($areas as $a) {
	foreach($conVacuumArray["total_used_to_date"][$a] as $v) {
		$total_used_to_date[$a] += $v;
	}
}

$report = 9;
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

<body onLoad="collapseAll()">
<div id="mainForm" style="padding:20px;">
<p>
  <? include ('header.inc');?>
</p>
<p>&nbsp;</p>
<table cellspacing="1" cellpadding="5">
    <tr>
        <td rowspan="2">&nbsp;</td>
        <td colspan="6" align="center"><strong>Kearl</strong></td>
        <td align="center"><strong>Syncrude</strong></td>
    </tr>
    <tr>
        <td width="80" align="center"><strong>1</strong></td>
        <td width="80" align="center"><strong>2</strong></td>
        <td width="80" align="center"><strong>3</strong></td>
        <td width="80" align="center"><strong>4</strong></td>
        <td width="80" align="center"><strong>5</strong></td>
        <td width="80" align="center"><strong>6</strong></td>
        <td width="80" align="center"><strong>1</strong></td>
    </tr>
    <tr>
        <td align="center">Sump ID</td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">Description</td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">Location LSD</td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">Start Date</td>
        <td align="center"><?=$conVacuumArray["start_date"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["start_date"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["start_date"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["start_date"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["start_date"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["start_date"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["start_date"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">End Date</td>
        <td align="center"><?=$conVacuumArray["end_date"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["end_date"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["end_date"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["end_date"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["end_date"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["end_date"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["end_date"]["Syncrude"][1]?></td>
        <td align="center">Total All Sources</td>
    </tr>
    <tr>
        <td align="center">Total Licenced Volume (m3)</td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["total_licensed_volume"]["Syncrude"][1]?></td>
		<td align="center"><?=$conVacuumArray["total_licensed_volume"]["Kearl"][1]+
			$conVacuumArray["total_licensed_volume"]["Kearl"][2]+
			$conVacuumArray["total_licensed_volume"]["Kearl"][3]+
			$conVacuumArray["total_licensed_volume"]["Kearl"][4]+
			$conVacuumArray["total_licensed_volume"]["Kearl"][5]+
			$conVacuumArray["total_licensed_volume"]["Kearl"][6]+
			$conVacuumArray["total_licensed_volume"]["Syncrude"][1]?></td>

    </tr>
    <tr>
        <td align="center">Total Used to Date (m3)</td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Kearl"][6]?></td>
        <td align="center"><?=$conVacuumArray["total_used_to_date"]["Syncrude"][1]?></td>
        <td align="center"><?=$total_used_to_date["Kearl"]+$total_used_to_date["Syncrude"]?></td>
    </tr>
</table>
</div>
<? include ('footer.inc'); ?>
</body>
</html>