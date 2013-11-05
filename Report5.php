<?
include("sessionCheck.php");
include("db.php");

// Create Y-m-d format of current time
	$nowDate = date("Y-m-d", mktime());
//	$nowTime = date("Y-m-d H:i:s", $now);
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report 5', '{$_SESSION['username']}')");

$rsWaterVacuumDates = $infosystem->Execute("SELECT DISTINCT `input_date` FROM `water` ORDER BY `input_date` DESC");
$nrRows = $rsWaterVacuumDates->RecordCount();

$i = 1;
$grandSum = array();
while(!$rsWaterVacuumDates->EOF) {
	list($x_date) = $rsWaterVacuumDates->fields;
	$sumDate[$i] = $x_date;
	list($sumSyncrude1[$i], $sumSyncrude2[$i], $sumSyncrude3[$i], $sumSyncrude4[$i], $sumSyncrude5[$i], $sumSyncrude6[$i], $sumSyncrude7[$i], $sumSyncrude8[$i], $sumSyncrude9[$i], $sumSyncrude10[$i], $sumSyncrude11[$i], $sumSyncrude12[$i]) = $infosystem->Execute("SELECT SUM(loc_1) l1, SUM(loc_2) l2, SUM(loc_3) l3, SUM(loc_4) l4, SUM(loc_5) l5, SUM(loc_6) l6, SUM(loc_7) l7, SUM(loc_8) l8, SUM(loc_9) l9, SUM(loc_10) l10, SUM(loc_11) l11, SUM(loc_12) l12 FROM `water` WHERE `input_date` = '{$x_date}' AND `area` = 'Syncrude'")->fields;
	for($j=1; $j<=12; $j++) {
		$fld = "sumSyncrude{$j}";
		$fld2 = $$fld;
		$grandSum["Syncrude"][$j] += $fld2[$i];
	}
	list($sumKearl1[$i], $sumKearl2[$i], $sumKearl3[$i]) = $infosystem->Execute("SELECT SUM(loc_1) lk1, SUM(loc_2) lk2, SUM(loc_3) lk3 FROM `water` WHERE `input_date` = '{$x_date}' AND `area` = 'Kearl'")->fields;
	for($j=1; $j<=3; $j++) {
		$fld = "sumKearl{$j}";
		$fld2 = $$fld;
		$grandSum["Kearl"][$j] += $fld2[$i];
	}
	$rsWaterVacuumDates->MoveNext();
	$i++;
}
?>
<html><head>
<title>Water Report 5 - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="collapseAll()">
<div id="mainForm" style="padding:20px;">
<? include ('header.inc');?>
<br/><!-- begin form -->
<table cellspacing="1" cellpadding="5">
    <tr>
        <td width="120" align="center" rowspan="2">Date / Water Source</td>
        <td width="30" align="center" colspan="3">Kearl</td>
        <td width="30" align="center" colspan="12">Syncrude</td>
    </tr>
    <tr>
        <td width="30" align="center">1</td>
        <td width="30" align="center">2</td>
        <td width="30" align="center">3</td>
        <td width="30" align="center">1</td>
        <td width="30" align="center">2</td>
        <td width="30" align="center">3</td>
        <td width="30" align="center">4</td>
        <td width="30" align="center">5</td>
        <td width="30" align="center">6</td>
        <td width="30" align="center">7</td>
        <td width="30" align="center">8</td>
        <td width="30" align="center">9</td>
        <td width="30" align="center">10</td>
        <td width="30" align="center">11</td>
        <td width="30" align="center">12</td>
     </tr><?
	$csvContent = "Date / Water Source,Kearl1,Kearl2,Kearl3,Syncrude1,Syncrude2,Syncrude3,Syncrude4,Syncrude5,Syncrude6,Syncrude7,Syncrude8,Syncrude9,Syncrude10,Syncrude11,Syncrude12".chr(13).chr(10);
	for($i=1; $i<=$nrRows; $i++) {
	$csvContent .= $sumDate[$i].",".$sumKearl1[$i].",".$sumKearl2[$i].",".$sumKearl3[$i].",".$sumSyncrude1[$i].",".$sumSyncrude2[$i].",".$sumSyncrude3[$i].",".$sumSyncrude4[$i].",".$sumSyncrude5[$i].",".$sumSyncrude6[$i].",".$sumSyncrude7[$i].",".$sumSyncrude8[$i].",".$sumSyncrude9[$i].",".$sumSyncrude10[$i].",".$sumSyncrude11[$i].",".$sumSyncrude12[$i].chr(13).chr(10); ?>
    <tr>
    	<td align="center"><?=$sumDate[$i]?></td>
        <td align="center"><?=$sumKearl1[$i]?></td>
        <td align="center"><?=$sumKearl2[$i]?></td>
        <td align="center"><?=$sumKearl3[$i]?></td>
        <td align="center"><?=$sumSyncrude1[$i]?></td>
        <td align="center"><?=$sumSyncrude2[$i]?></td>
        <td align="center"><?=$sumSyncrude3[$i]?></td>
        <td align="center"><?=$sumSyncrude4[$i]?></td>
        <td align="center"><?=$sumSyncrude5[$i]?></td>
        <td align="center"><?=$sumSyncrude6[$i]?></td>
        <td align="center"><?=$sumSyncrude7[$i]?></td>
        <td align="center"><?=$sumSyncrude8[$i]?></td>
        <td align="center"><?=$sumSyncrude9[$i]?></td>
        <td align="center"><?=$sumSyncrude10[$i]?></td>
        <td align="center"><?=$sumSyncrude11[$i]?></td>
        <td align="center"><?=$sumSyncrude12[$i]?></td>
    </tr><?
	}
	$csvContent .= "TOTAL,".$grandSum["Kearl"][1].",".$grandSum["Kearl"][2].",".$grandSum["Kearl"][3].",".$grandSum["Syncrude"][1].",".$grandSum["Syncrude"][2].",".$grandSum["Syncrude"][3].",".$grandSum["Syncrude"][4].",".$grandSum["Syncrude"][5].",".$grandSum["Syncrude"][6].",".$grandSum["Syncrude"][7].",".$grandSum["Syncrude"][8].",".$grandSum["Syncrude"][9].",".$grandSum["Syncrude"][10].",".$grandSum["Syncrude"][11].",".$grandSum["Syncrude"][12]; ?>
    <tr>
    	<td align="center">TOTAL</td>
    	<td align="center"><?=$grandSum["Kearl"][1]?></td>
    	<td align="center"><?=$grandSum["Kearl"][2]?></td>
    	<td align="center"><?=$grandSum["Kearl"][3]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][1]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][2]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][3]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][4]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][5]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][6]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][7]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][8]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][9]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][10]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][11]?></td>
    	<td align="center"><?=$grandSum["Syncrude"][12]?></td>
    </tr>
</table><?
$report = 5;
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