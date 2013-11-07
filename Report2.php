<?
include("sessionCheck.php");
include("db.php");
include_once("adodb/toexport.inc.php");

$todayShort = date("y-m-d", mktime());
$today = date("Y/m/d", mktime());
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report 2', '{$_SESSION['username']}')");

$rsReport = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro`");

$rsConHydro = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro` WHERE `water_licence` != ''");

$areas = array("Kearl", "Syncrude");
$conHydroArray = array();
while(!$rsConHydro->EOF) {
	list($x_area, $x_source_number, $x_water_licence, $x_source_ID, $x_program_zone, $x_location_LSD, $x_start_date, $x_end_date, $x_total_licensed_volume) = $rsConHydro->fields;
	$conHydroArray["water_licence"][$x_area][$x_source_number] = $x_water_licence;
	$conHydroArray["source_ID"][$x_area][$x_source_number] = $x_source_ID;
	$conHydroArray["program_zone"][$x_area][$x_source_number] = $x_program_zone;
	$conHydroArray["location_LSD"][$x_area][$x_source_number] = $x_location_LSD;
	$conHydroArray["start_date"][$x_area][$x_source_number] = ($x_start_date!="0000-00-00")?$x_start_date:"";
	$conHydroArray["end_date"][$x_area][$x_source_number] = ($x_end_date!="0000-00-00")?$x_end_date:"";
	$conHydroArray["total_licensed_volume"][$x_area][$x_source_number] = $x_total_licensed_volume;
	list($conHydroArray["total_used_to_date"][$x_area][$x_source_number]) = $infosystem->Execute("SELECT SUM(loc_{$x_source_number}) FROM `water` WHERE `area` = '{$x_area}'")->fields;
	$rsConHydro->MoveNext();
}

foreach($areas as $a) {
	foreach($conHydroArray["total_used_to_date"][$a] as $v) {
		$total_used_to_date[$a] += $v;
	}
}

$report = 2;
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
        <td colspan="3" align="center"><strong>Kearl</strong></td>
        <td colspan="12" align="center"><strong>Syncrude</strong></td>
    </tr>
    <tr>
        <td width="80" align="center"><strong>1</strong></td>
        <td width="80" align="center"><strong>2</strong></td>
        <td width="80" align="center"><strong>3</strong></td>
        <td width="80" align="center"><strong>1</strong></td>
        <td width="80" align="center"><strong>2</strong></td>
        <td width="80" align="center"><strong>3</strong></td>
        <td width="80" align="center"><strong>4</strong></td>
        <td width="80" align="center"><strong>5</strong></td>
        <td width="80" align="center"><strong>6</strong></td>
        <td width="80" align="center"><strong>7</strong></td>
        <td width="80" align="center"><strong>8</strong></td>
        <td width="80" align="center"><strong>9</strong></td>
        <td width="80" align="center"><strong>10</strong></td>
        <td width="80" align="center"><strong>11</strong></td>
        <td width="80" align="center"><strong>12</strong></td>
    </tr>
    <tr>
        <td align="center">Water Licence # (TDL)</td>
        <td align="center"><?=$conHydroArray["water_licence"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["water_licence"]["Syncrude"][12]?></td>
    </tr>
    <tr>
        <td align="center">Source ID</td>
        <td align="center"><?=$conHydroArray["source_ID"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Syncrude"][12]?></td>
    </tr>
    <tr>
        <td align="center">Description</td>
        <td align="center"><?=$conHydroArray["program_zone"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Syncrude"][12]?></td>
    </tr>
    <tr>
        <td align="center">Location LSD</td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Syncrude"][12]?></td>
    </tr>
    <tr>
        <td align="center">Start Date</td>
        <td align="center"><?=$conHydroArray["start_date"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["start_date"]["Syncrude"][12]?></td>
    </tr>
    <tr>
        <td align="center">End Date</td>
        <td align="center"><?=$conHydroArray["end_date"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["end_date"]["Syncrude"][12]?></td>
        <td align="center">Total All Sources</td>
    </tr>
    <tr>
        <td align="center">Total Licenced Volume (m3)</td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["total_licensed_volume"]["Syncrude"][12]?></td>
		<td align="center"><?=$conHydroArray["total_licensed_volume"]["Kearl"][1]+
			$conHydroArray["total_licensed_volume"]["Kearl"][2]+
			$conHydroArray["total_licensed_volume"]["Kearl"][3]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][1]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][2]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][3]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][4]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][5]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][6]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][7]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][8]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][9]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][10]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][11]+
			$conHydroArray["total_licensed_volume"]["Syncrude"][12]?></td>

    </tr>
    <tr>
        <td align="center">Total Used to Date (m3)</td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Kearl"][2]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Kearl"][3]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][1]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][2]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][3]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][4]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][5]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][6]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][7]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][8]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][9]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][10]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][11]?></td>
        <td align="center"><?=$conHydroArray["total_used_to_date"]["Syncrude"][12]?></td>
        <td align="center"><?=$total_used_to_date["Kearl"]+$total_used_to_date["Syncrude"]?></td>
    </tr>
</table>
</div>
<? include ('footer.inc'); ?>
</body>
</html>