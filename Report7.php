<?
include("sessionCheck.php");
include("db.php");

// Create Y-m-d format of current time
	$nowDate = date("Y-m-d", mktime());
//	$nowTime = date("Y-m-d H:i:s", $now);
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report 7', '{$_SESSION['username']}')");

$rsWaterVacuumDates = $infosystem->Execute("SELECT DISTINCT `input_date` FROM `vacuum` ORDER BY `input_date` DESC");
$nrRows = $rsWaterVacuumDates->RecordCount();

$i = 1;
$grandSum = array();
while(!$rsWaterVacuumDates->EOF) {
	list($x_date) = $rsWaterVacuumDates->fields;
	$sumDate[$i] = $x_date;
	list($sumKearl1[$i], $sumKearl2[$i], $sumKearl3[$i], $sumKearl4[$i], $sumKearl5[$i], $sumKearl6[$i]) = $infosystem->Execute("SELECT SUM(loc_1) l1, SUM(loc_2) l2, SUM(loc_3) l3, SUM(loc_4) l4, SUM(loc_5) l5, SUM(loc_6) l6 FROM `vacuum` WHERE `input_date` = '{$x_date}' AND `area` = 'Kearl'")->fields;
	for($j=1; $j<=6; $j++) {
		$fld = "sumKearl{$j}";
		$fld2 = $$fld;
		$grandSum["Kearl"][$j] += $fld2[$i];
	}
	list($sumSyncrude1[$i]) = $infosystem->Execute("SELECT SUM(loc_1) ls1 FROM `vacuum` WHERE `input_date` = '{$x_date}' AND `area` = 'Syncrude'")->fields;
	$grandSum["Syncrude"][1] += $sumSyncrude1[$i];
	$rsWaterVacuumDates->MoveNext();
	$i++;
}
?>
<html><head>
<title>Vacuum Report 7 - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="collapseAll()">
<div id="mainForm" style="padding:20px;">
<? include ('header.inc');?>
<br/><!-- begin form -->
<table cellspacing="1" cellpadding="5">
    <tr>
        <td width="120" align="center" rowspan="2">Date / Sump Source</td>
        <td width="30" align="center" colspan="6">Kearl</td>
        <td width="30" align="center">Syncrude</td>
    </tr>
    <tr>
        <td width="30" align="center">1</td>
        <td width="30" align="center">2</td>
        <td width="30" align="center">3</td>
        <td width="30" align="center">4</td>
        <td width="30" align="center">5</td>
        <td width="30" align="center">6</td>
        <td width="30" align="center">1</td>
    </tr><?
	$csvContent = "Date / Sump Source,Kearl1,Kearl2,Kearl3,Kearl4,Kearl5,Kearl6,Syncrude1".chr(13).chr(10);
	for($i=1; $i<=$nrRows; $i++) {
	$csvContent .= $sumDate[$i].",".$sumKearl1[$i].",".$sumKearl2[$i].",".$sumKearl3[$i].",".$sumKearl4[$i].",".$sumKearl5[$i].",".$sumKearl6[$i].",".$sumSyncrude1[$i].chr(13).chr(10); ?>
    <tr>
    	<td align="center"><?=$sumDate[$i]?></td>
        <td align="center"><?=$sumKearl1[$i]?></td>
        <td align="center"><?=$sumKearl2[$i]?></td>
        <td align="center"><?=$sumKearl3[$i]?></td>
        <td align="center"><?=$sumKearl4[$i]?></td>
        <td align="center"><?=$sumKearl5[$i]?></td>
        <td align="center"><?=$sumKearl6[$i]?></td>
        <td align="center"><?=$sumSyncrude1[$i]?></td>
    </tr><?
	}
	$csvContent .= "TOTAL,".$grandSum["Kearl"][1].",".$grandSum["Kearl"][2].",".$grandSum["Kearl"][3].",".$grandSum["Kearl"][4].",".$grandSum["Kearl"][5].",".$grandSum["Kearl"][6].",".$grandSum["Syncrude"][1]; ?>
    <tr>
    	<td align="center">TOTAL</td>
    	<td align="center"><?=$grandSum["Kearl"][1]?></td>
    	<td align="center"><?=$grandSum["Kearl"][2]?></td>
    	<td align="center"><?=$grandSum["Kearl"][3]?></td>
    	<td align="center"><?=$grandSum["Kearl"][4]?></td>
    	<td align="center"><?=$grandSum["Kearl"][5]?></td>
    	<td align="center"><?=$grandSum["Kearl"][6]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][1]?></td>
    </tr>
</table><?
$report = 7;
$todayShort = date("y-m-d", mktime());
$today = date("Y/m/d", mktime());
$reportURL = "reports/Report{$report}-{$todayShort}.csv";

$fp = fopen("{$reportURL}", "w");
if($fp) {
	fwrite($fp, $csvContent);
	fclose($fp);
}
//mail("predragl@wmasters.ca", "WM Digital - Report #{$report}, {$today}", "Please find Report #{$report} created on {$today} on the following URL:\r\n\r\nhttp://wmasters.d-zine.ca/{$reportURL}", "From: WM Digital <admin@wellsitemasters.com>");
?>
<!-- end of form -->

<!-- end of this page -->
<!-- close the display stuff for this page -->
</div>
<? include ('footer.inc');?>
</body>
</html>