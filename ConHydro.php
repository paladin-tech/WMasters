<?
include("sessionCheck.php");
include("db.php");

list($ConHydroLvl) = $infosystem->Execute("SELECT `ConHydro` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($ConHydroLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($ConHydroLvl=="o")?" disabled=\"disabled\"":"";

if(isset($_POST['submit'])) {
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Construction Hydro', '{$_SESSION['username']}')");
	foreach($_POST as $key => $value) $$key = $value;
		$areas = array("Kearl","Syncrude");
		foreach($areas as $ar) {
			foreach($WaterLicence[$ar] as $key=>$wl) {
				$sd = $StartDate[$ar][$key];
				$ed = $EndDate[$ar][$key];
				$efd = $EffectiveDate[$ar][$key];
				$exd = $ExpiryDate[$ar][$key];
				$infosystem->Execute("UPDATE `con_hydro` SET `water_licence` = '{$wl}', `licence_effective_date` = '{$efd}', `licence_expiry_date` = '{$exd}', `start_date` = '{$sd}', `end_date` = '{$ed}' WHERE `area` = '{$ar}' AND `source_number` = {$key}");
			}
		}
	}
	list($sumKearl1, $sumKearl2) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2 FROM `water` WHERE `input_date`< CURDATE() AND `area` = 'Kearl'")->fields;
	list($sumSyncrude1, $sumSyncrude2, $sumSyncrude3, $sumSyncrude4, $sumSyncrude5, $sumSyncrude6, $sumSyncrude7, $sumSyncrude8, $sumSyncrude9, $sumSyncrude10, $sumSyncrude11, $sumSyncrude12) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`) s2, SUM(`loc_3`) s3, SUM(`loc_4`) s4, SUM(`loc_5`) s5, SUM(`loc_6`) s6, SUM(`loc_7`) s7, SUM(`loc_8`) s8, SUM(`loc_9`) s9, SUM(`loc_10`) s10, SUM(`loc_11`) s11, SUM(`loc_12`) s12 FROM `water` WHERE `input_date` = < CURDATE() AND `area` = 'Syncrude'")->fields;

	$rsConHydro = $infosystem->Execute("SELECT `area`, `source_number`, `water_licence`, `licence_effective_date`, `licence_expiry_date`, `source_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_hydro`");
	$conHydroArray = array();
	while(!$rsConHydro->EOF) {
		list($x_area, $x_source_number, $x_water_licence, $x_licence_effective_date, $x_licence_expiry_date, $x_source_ID, $x_program_zone, $x_location_LSD, $x_start_date, $x_end_date, $x_total_licensed_volume) = $rsConHydro->fields;
		$conHydroArray["water_licence"][$x_area][$x_source_number] = $x_water_licence;
		$conHydroArray["licence_effective_date"][$x_area][$x_source_number] = $x_licence_effective_date;
		$conHydroArray["licence_expiry_date"][$x_area][$x_source_number] = $x_licence_expiry_date;
		$conHydroArray["source_ID"][$x_area][$x_source_number] = $x_source_ID;
		$conHydroArray["program_zone"][$x_area][$x_source_number] = $x_program_zone;
		$conHydroArray["location_LSD"][$x_area][$x_source_number] = $x_location_LSD;
		$conHydroArray["start_date"][$x_area][$x_source_number] = $x_start_date;
		$conHydroArray["end_date"][$x_area][$x_source_number] = $x_end_date;
		$conHydroArray["total_licensed_volume"][$x_area][$x_source_number] = $x_total_licensed_volume;
		$rsConHydro->MoveNext();
	}
?>
<html>
<head>
<title>Construction Hydro - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<script src="CalendarPopupCombined.js"></script>
</head>
<body onLoad="collapseAll()">
<div id="mainForm" style="padding:20px;">
<? include ('header.inc');?>
<br/>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="frm">
<p align="left">WATER LEFT PER EACH SOURCE (ADVISE SUPERVISOR WHEN 200 m3 LIMIT IS REACHED)</p>
<table cellspacing="1" cellpadding="5">
	<caption>Kearl</caption>
    <tr>
    	<td width="120" align="left">Water Source</td>
        <td width="30" align="center">1</td>
        <td width="30" align="center">2</td>
    </tr>
    <tr>
    	<td>Water Amount Left (m3) </td>
        <td><?=$conHydroArray["total_licensed_volume"]["Kearl"][1]-$sumKearl1?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Kearl"][2]-$sumKearl2?></td>
    </tr>
</table>
<br />
<table cellspacing="1" cellpadding="5">
    <tr>
        <td><strong>Kearl</strong></td>
        <td align="center"><strong>1</strong></td>
        <td align="center"><strong>2</strong></td>
    </tr>
    <tr>
        <td align="center">Water Licence # (TDL)</td>
        <td align="center"><input type="text" name="WaterLicence[Kearl][1]" id="WaterLicenceKearl1" size="12" value="<?=$conHydroArray["water_licence"]["Kearl"][1]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Kearl][2]" id="WaterLicenceKearl2" size="12" value="<?=$conHydroArray["water_licence"]["Kearl"][2]?>"<?=$readOnly?> /></td>
    </tr>
    <tr>
        <td align="center">Effective Date</td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][1]" id="EffectiveDateKearl1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Kearl"][1]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx1.select(document.forms[0].elements[2],'anchor1xx1','yyyy-MM-dd')" name="anchor1xx1" id="anchor1xx1"></td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][2]" id="EffectiveDateKearl2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Kearl"][2]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx2.select(document.forms[0].elements[3],'anchor1xx2','yyyy-MM-dd')" name="anchor1xx2" id="anchor1xx2"></td>
    </tr>
    <tr>
    <td align="center">Expiry Date</td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][1]" id="ExpiryDateKearl1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Kearl"][1]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx3.select(document.forms[0].elements[4],'anchor1xx3','yyyy-MM-dd')" name="anchor1xx3" id="anchor1xx3"></td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][2]" id="ExpiryDateKearl2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Kearl"][2]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx4.select(document.forms[0].elements[5],'anchor1xx4','yyyy-MM-dd')" name="anchor1xx4" id="anchor1xx4"></td>
     </tr>
    <tr>
        <td align="center">Source ID</td>
        <td align="center"><?=$conHydroArray["source_ID"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["source_ID"]["Kearl"][2]?></td>
    </tr>
    <tr>
        <td align="center">Program-Zone</td>
        <td align="center"><?=$conHydroArray["program_zone"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["program_zone"]["Kearl"][2]?></td>
    </tr>
    <tr>
        <td align="center">Location LSD</td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Kearl"][1]?></td>
        <td align="center"><?=$conHydroArray["location_LSD"]["Kearl"][2]?></td>
    </tr>
    <tr>
        <td align="center">Start Date</td>
        <td align="center"><input type="text" name="StartDate[Kearl][1]" id="StartDateKearl1" size="10" value="<?=($conHydroArray["start_date"]["Kearl"][1]!="0000-00-00")?$conHydroArray["start_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx5.select(document.forms[0].elements[6],'anchor1xx5','yyyy-MM-dd')" name="anchor1xx5" id="anchor1xx5"></td>
        <td align="center"><input type="text" name="StartDate[Kearl][2]" id="StartDateKearl2" size="10" value="<?=($conHydroArray["start_date"]["Kearl"][2]!="0000-00-00")?$conHydroArray["start_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx6.select(document.forms[0].elements[7],'anchor1xx6','yyyy-MM-dd')" name="anchor1xx6" id="anchor1xx6"></td>
    </tr>
    <tr>
        <td align="center">End Date</td>
        <td align="center"><input type="text" name="EndDate[Kearl][1]" id="EndDateKearl1" size="10" value="<?=($conHydroArray["end_date"]["Kearl"][1]!="0000-00-00")?$conHydroArray["end_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx7.select(document.forms[0].elements[8],'anchor1xx7','yyyy-MM-dd')" name="anchor1xx7" id="anchor1xx7"></td>
        <td align="center"><input type="text" name="EndDate[Kearl][2]" id="EndDateKearl2" size="10" value="<?=($conHydroArray["end_date"]["Kearl"][2]!="0000-00-00")?$conHydroArray["end_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx8.select(document.forms[0].elements[9],'anchor1xx8','yyyy-MM-dd')" name="anchor1xx8" id="anchor1xx8"></td>
    </tr>
</table>
<br />
<table cellspacing="1" cellpadding="5">
	<caption>Syncrude</caption>
    <tr>
    	<td width="120" align="left">Water Source</td>
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
    </tr>
    <tr>
    	<td>Water Amount Left (m3) </td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][1]-$sumSyncrude1?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][2]-$sumSyncrude2?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][3]-$sumSyncrude3?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][4]-$sumSyncrude4?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][5]-$sumSyncrude5?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][6]-$sumSyncrude6?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][7]-$sumSyncrude7?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][8]-$sumSyncrude8?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][9]-$sumSyncrude9?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][10]-$sumSyncrude10?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][11]-$sumSyncrude11?></td>
        <td><?=$conHydroArray["total_licensed_volume"]["Syncrude"][12]-$sumSyncrude12?></td>
    </tr>
</table>
<br />
<table cellspacing="1" cellpadding="5">
    <tr>
        <td><strong>Syncrude</strong></td>
        <td align="center"><strong>1</strong></td>
        <td align="center"><strong>2</strong></td>
        <td align="center"><strong>3</strong></td>
        <td align="center"><strong>4</strong></td>
        <td align="center"><strong>5</strong></td>
        <td align="center"><strong>6</strong></td>
        <td align="center"><strong>7</strong></td>
        <td align="center"><strong>8</strong></td>
        <td align="center"><strong>9</strong></td>
        <td align="center"><strong>10</strong></td>
        <td align="center"><strong>11</strong></td>
        <td align="center"><strong>12</strong></td>
    </tr>
    <tr>
        <td align="center">Water Licence # (TDL)</td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][1]" id="WaterLicenceSyncrude1" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][1]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][2]" id="WaterLicenceSyncrude2" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][2]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][3]" id="WaterLicenceSyncrude3" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][3]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][4]" id="WaterLicenceSyncrude4" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][4]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][5]" id="WaterLicenceSyncrude5" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][5]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][6]" id="WaterLicenceSyncrude6" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][6]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][7]" id="WaterLicenceSyncrude7" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][7]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][8]" id="WaterLicenceSyncrude8" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][8]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][9]" id="WaterLicenceSyncrude9" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][9]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][10]" id="WaterLicenceSyncrude10" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][10]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][11]" id="WaterLicenceSyncrude11" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][11]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="WaterLicence[Syncrude][12]" id="WaterLicenceSyncrude12" size="12" value="<?=$conHydroArray["water_licence"]["Syncrude"][12]?>"<?=$readOnly?> /></td>
    </tr>
    <tr>
        <td align="center">Effective Date</td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][1]" id="EffectiveDateSyncrude1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][1]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx9.select(document.forms[0].elements[22],'anchor1xx9','yyyy-MM-dd')" name="anchor1xx9" id="anchor1xx9"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][2]" id="EffectiveDateSyncrude2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][2]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx10.select(document.forms[0].elements[23],'anchor1xx10','yyyy-MM-dd')" name="anchor1xx10" id="anchor1xx10"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][3]" id="EffectiveDateSyncrude1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][3]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx11.select(document.forms[0].elements[24],'anchor1xx11','yyyy-MM-dd')" name="anchor1xx11" id="anchor1xx11"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][4]" id="EffectiveDateSyncrude2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][4]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx12.select(document.forms[0].elements[25],'anchor1xx12','yyyy-MM-dd')" name="anchor1xx12" id="anchor1xx12"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][5]" id="EffectiveDateSyncrude1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][5]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx13.select(document.forms[0].elements[26],'anchor1xx13','yyyy-MM-dd')" name="anchor1xx13" id="anchor1xx13"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][6]" id="EffectiveDateSyncrude2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][6]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx14.select(document.forms[0].elements[27],'anchor1xx14','yyyy-MM-dd')" name="anchor1xx14" id="anchor1xx14"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][7]" id="EffectiveDateSyncrude1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][7]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][7]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx15.select(document.forms[0].elements[28],'anchor1xx15','yyyy-MM-dd')" name="anchor1xx15" id="anchor1xx15"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][8]" id="EffectiveDateSyncrude2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][8]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][8]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx16.select(document.forms[0].elements[29],'anchor1xx16','yyyy-MM-dd')" name="anchor1xx16" id="anchor1xx16"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][9]" id="EffectiveDateSyncrude1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][9]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][9]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx17.select(document.forms[0].elements[30],'anchor1xx17','yyyy-MM-dd')" name="anchor1xx17" id="anchor1xx17"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][10]" id="EffectiveDateSyncrude2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][10]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][10]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx18.select(document.forms[0].elements[31],'anchor1xx18','yyyy-MM-dd')" name="anchor1xx18" id="anchor1xx18"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][11]" id="EffectiveDateSyncrude1" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][11]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][11]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx19.select(document.forms[0].elements[32],'anchor1xx19','yyyy-MM-dd')" name="anchor1xx19" id="anchor1xx19"></td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][12]" id="EffectiveDateSyncrude2" size="10" value="<?=($conHydroArray["licence_effective_date"]["Syncrude"][12]!="0000-00-00")?$conHydroArray["licence_effective_date"]["Syncrude"][12]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx20.select(document.forms[0].elements[33],'anchor1xx20','yyyy-MM-dd')" name="anchor1xx20" id="anchor1xx20"></td>
    </tr>
    <tr>
    <td align="center">Expiry Date</td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][1]" id="ExpiryDateSyncrude1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][1]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx21.select(document.forms[0].elements[34],'anchor1xx21','yyyy-MM-dd')" name="anchor1xx21" id="anchor1xx21"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][2]" id="ExpiryDateSyncrude2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][2]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx22.select(document.forms[0].elements[35],'anchor1xx22','yyyy-MM-dd')" name="anchor1xx22" id="anchor1xx22"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][3]" id="ExpiryDateSyncrude1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][3]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx23.select(document.forms[0].elements[36],'anchor1xx23','yyyy-MM-dd')" name="anchor1xx23" id="anchor1xx23"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][4]" id="ExpiryDateSyncrude2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][4]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx24.select(document.forms[0].elements[37],'anchor1xx24','yyyy-MM-dd')" name="anchor1xx24" id="anchor1xx24"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][5]" id="ExpiryDateSyncrude1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][5]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx25.select(document.forms[0].elements[38],'anchor1xx25','yyyy-MM-dd')" name="anchor1xx25" id="anchor1xx25"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][6]" id="ExpiryDateSyncrude2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][6]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx26.select(document.forms[0].elements[39],'anchor1xx26','yyyy-MM-dd')" name="anchor1xx26" id="anchor1xx26"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][7]" id="ExpiryDateSyncrude1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][7]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][7]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx27.select(document.forms[0].elements[40],'anchor1xx27','yyyy-MM-dd')" name="anchor1xx27" id="anchor1xx27"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][8]" id="ExpiryDateSyncrude2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][8]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][8]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx28.select(document.forms[0].elements[41],'anchor1xx28','yyyy-MM-dd')" name="anchor1xx28" id="anchor1xx28"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][9]" id="ExpiryDateSyncrude1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][9]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][9]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx29.select(document.forms[0].elements[42],'anchor1xx29','yyyy-MM-dd')" name="anchor1xx29" id="anchor1xx29"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][10]" id="ExpiryDateSyncrude2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][10]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][10]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx30.select(document.forms[0].elements[43],'anchor1xx30','yyyy-MM-dd')" name="anchor1xx30" id="anchor1xx30"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][11]" id="ExpiryDateSyncrude1" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][11]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][11]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx31.select(document.forms[0].elements[44],'anchor1xx31','yyyy-MM-dd')" name="anchor1xx31" id="anchor1xx31"></td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][12]" id="ExpiryDateSyncrude2" size="10" value="<?=($conHydroArray["licence_expiry_date"]["Syncrude"][12]!="0000-00-00")?$conHydroArray["licence_expiry_date"]["Syncrude"][12]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx32.select(document.forms[0].elements[45],'anchor1xx32','yyyy-MM-dd')" name="anchor1xx12" id="anchor1xx32"></td>
     </tr>
    <tr>
        <td align="center">Source ID</td>
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
        <td align="center">Program-Zone</td>
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
        <td align="center"><input type="text" name="StartDate[Syncrude][1]" id="StartDateSyncrude1" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][1]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx33.select(document.forms[0].elements[46],'anchor1xx33','yyyy-MM-dd')" name="anchor1xx33" id="anchor1xx33"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][2]" id="StartDateSyncrude2" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][2]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx34.select(document.forms[0].elements[47],'anchor1xx34','yyyy-MM-dd')" name="anchor1xx34" id="anchor1xx34"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][3]" id="StartDateSyncrude1" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][3]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx35.select(document.forms[0].elements[48],'anchor1xx35','yyyy-MM-dd')" name="anchor1xx35" id="anchor1xx35"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][4]" id="StartDateSyncrude2" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][4]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx36.select(document.forms[0].elements[49],'anchor1xx36','yyyy-MM-dd')" name="anchor1xx36" id="anchor1xx36"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][5]" id="StartDateSyncrude1" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][5]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx37.select(document.forms[0].elements[50],'anchor1xx37','yyyy-MM-dd')" name="anchor1xx37" id="anchor1xx37"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][6]" id="StartDateSyncrude2" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][6]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx38.select(document.forms[0].elements[51],'anchor1xx38','yyyy-MM-dd')" name="anchor1xx38" id="anchor1xx38"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][7]" id="StartDateSyncrude1" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][7]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][7]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx39.select(document.forms[0].elements[52],'anchor1xx39','yyyy-MM-dd')" name="anchor1xx39" id="anchor1xx39"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][8]" id="StartDateSyncrude2" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][8]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][8]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx40.select(document.forms[0].elements[53],'anchor1xx40','yyyy-MM-dd')" name="anchor1xx40" id="anchor1xx40"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][9]" id="StartDateSyncrude1" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][9]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][9]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx41.select(document.forms[0].elements[54],'anchor1xx41','yyyy-MM-dd')" name="anchor1xx41" id="anchor1xx41"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][10]" id="StartDateSyncrude2" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][10]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][10]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx42.select(document.forms[0].elements[55],'anchor1xx42','yyyy-MM-dd')" name="anchor1xx42" id="anchor1xx42"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][11]" id="StartDateSyncrude1" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][11]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][11]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx43.select(document.forms[0].elements[56],'anchor1xx43','yyyy-MM-dd')" name="anchor1xx43" id="anchor1xx43"></td>
        <td align="center"><input type="text" name="StartDate[Syncrude][12]" id="StartDateSyncrude2" size="10" value="<?=($conHydroArray["start_date"]["Syncrude"][12]!="0000-00-00")?$conHydroArray["start_date"]["Syncrude"][12]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx44.select(document.forms[0].elements[57],'anchor1xx44','yyyy-MM-dd')" name="anchor1xx44" id="anchor1xx44"></td>
    </tr>
    <tr>
        <td align="center">End Date</td>
        <td align="center"><input type="text" name="EndDate[Syncrude][1]" id="EndDateSyncrude1" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][1]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx45.select(document.forms[0].elements[58],'anchor1xx45','yyyy-MM-dd')" name="anchor1xx45" id="anchor1xx45"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][2]" id="EndDateSyncrude2" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][2]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx46.select(document.forms[0].elements[59],'anchor1xx46','yyyy-MM-dd')" name="anchor1xx46" id="anchor1xx46"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][3]" id="EndDateSyncrude1" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][3]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx47.select(document.forms[0].elements[60],'anchor1xx47','yyyy-MM-dd')" name="anchor1xx47" id="anchor1xx47"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][4]" id="EndDateSyncrude2" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][4]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx48.select(document.forms[0].elements[61],'anchor1xx48','yyyy-MM-dd')" name="anchor1xx48" id="anchor1xx48"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][5]" id="EndDateSyncrude1" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][5]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx49.select(document.forms[0].elements[62],'anchor1xx49','yyyy-MM-dd')" name="anchor1xx49" id="anchor1xx49"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][6]" id="EndDateSyncrude2" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][6]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx50.select(document.forms[0].elements[63],'anchor1xx50','yyyy-MM-dd')" name="anchor1xx50" id="anchor1xx50"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][7]" id="EndDateSyncrude1" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][7]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][7]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx51.select(document.forms[0].elements[64],'anchor1xx51','yyyy-MM-dd')" name="anchor1xx51" id="anchor1xx51"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][8]" id="EndDateSyncrude2" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][8]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][8]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx52.select(document.forms[0].elements[65],'anchor1xx52','yyyy-MM-dd')" name="anchor1xx52" id="anchor1xx52"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][9]" id="EndDateSyncrude1" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][9]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][9]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx53.select(document.forms[0].elements[66],'anchor1xx53','yyyy-MM-dd')" name="anchor1xx53" id="anchor1xx53"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][10]" id="EndDateSyncrude2" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][10]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][10]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx54.select(document.forms[0].elements[67],'anchor1xx54','yyyy-MM-dd')" name="anchor1xx54" id="anchor1xx54"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][11]" id="EndDateSyncrude1" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][11]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][11]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx55.select(document.forms[0].elements[68],'anchor1xx55','yyyy-MM-dd')" name="anchor1xx55" id="anchor1xx55"></td>
        <td align="center"><input type="text" name="EndDate[Syncrude][12]" id="EndDateSyncrude2" size="10" value="<?=($conHydroArray["end_date"]["Syncrude"][12]!="0000-00-00")?$conHydroArray["end_date"]["Syncrude"][12]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx56.select(document.forms[0].elements[69],'anchor1xx56','yyyy-MM-dd')" name="anchor1xx56" id="anchor1xx56"></td>
    </tr>
</table>
<p><input type="submit" name="submit" value="Submit"<?=$btnSubmitDisabled?> /></p>
</form>
<!-- end of form --><!-- end of this page --><!-- close the display stuff for this page -->
</div>
<script language="javascript"><?
for($i=1; $i<=64; $i++) { ?>
	var cal1xx<?=$i?> = new CalendarPopup("testdiv1");
	cal1xx<?=$i?>.showNavigationDropdowns();<?
} ?>
</script>
<div id="testdiv1" style="position: absolute; visibility: hidden; background-color: white; left: 42px; top: 859px;">
<table class="cpBorder" borderwidth="1" border="1" cellpadding="1" cellspacing="0" width="144">
<tbody>
<tr>
<td align="CENTER"><center>
<table borderwidth="0" border="0" cellpadding="0" cellspacing="0" width="144">
<tbody>
<tr>
<td class="cpMonthNavigation" colspan="3" width="78"><select class="cpMonthNavigation" name="cpMonth" onmouseup="CP_stop(event)" onChange="CP_refreshCalendar(2,this.options[this.selectedIndex].value-0,2010);"><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11" selected="selected">November</option><option value="12">December</option></select></td>
<td class="cpMonthNavigation" width="10">&nbsp;</td>
<td class="cpYearNavigation" colspan="3" width="56"><select class="cpYearNavigation" name="cpYear" onmouseup="CP_stop(event)" onChange="CP_refreshCalendar(2,11,this.options[this.selectedIndex].value-0);"><option value="2008">2008</option><option value="2009">2009</option><option value="2010" selected="selected">2010</option><option value="2011">2011</option><option value="2012">2012</option></select></td></tr></tbody>
</table>
<table align="CENTER" border="0" cellpadding="1" cellspacing="0" width="120">
    <tbody>
    <tr>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">S</span></td>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">M</span></td>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">T</span></td>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">W</span></td>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">T</span></td>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">F</span></td>
        <td class="cpDayColumnHeader" width="14%"><span class="cpDayColumnHeader">S</span></td>
	</tr>
    <tr>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,10,31);CP_hideCalendar('2');" class="cpOtherMonthDate">31</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,1);CP_hideCalendar('2');" class="cpCurrentMonthDate">1</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,2);CP_hideCalendar('2');" class="cpCurrentMonthDate">2</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,3);CP_hideCalendar('2');" class="cpCurrentMonthDate">3</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,4);CP_hideCalendar('2');" class="cpCurrentMonthDate">4</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,5);CP_hideCalendar('2');" class="cpCurrentMonthDate">5</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,6);CP_hideCalendar('2');" class="cpCurrentMonthDate">6</a></td>
    </tr>
    <tr>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,7);CP_hideCalendar('2');" class="cpCurrentMonthDate">7</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,8);CP_hideCalendar('2');" class="cpCurrentMonthDate">8</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,9);CP_hideCalendar('2');" class="cpCurrentMonthDate">9</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,10);CP_hideCalendar('2');" class="cpCurrentMonthDate">10</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,11);CP_hideCalendar('2');" class="cpCurrentMonthDate">11</a></td>
        <td class="cpCurrentDate"><a href="javascript:CP_tmpReturnFunction(2010,11,12);CP_hideCalendar('2');" class="cpCurrentDate">12</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,13);CP_hideCalendar('2');" class="cpCurrentMonthDate">13</a></td>
    </tr>
    <tr>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,14);CP_hideCalendar('2');" class="cpCurrentMonthDate">14</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,15);CP_hideCalendar('2');" class="cpCurrentMonthDate">15</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,16);CP_hideCalendar('2');" class="cpCurrentMonthDate">16</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,17);CP_hideCalendar('2');" class="cpCurrentMonthDate">17</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,18);CP_hideCalendar('2');" class="cpCurrentMonthDate">18</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,19);CP_hideCalendar('2');" class="cpCurrentMonthDate">19</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,20);CP_hideCalendar('2');" class="cpCurrentMonthDate">20</a></td>
    </tr>
    <tr>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,21);CP_hideCalendar('2');" class="cpCurrentMonthDate">21</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,22);CP_hideCalendar('2');" class="cpCurrentMonthDate">22</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,23);CP_hideCalendar('2');" class="cpCurrentMonthDate">23</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,24);CP_hideCalendar('2');" class="cpCurrentMonthDate">24</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,25);CP_hideCalendar('2');" class="cpCurrentMonthDate">25</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,26);CP_hideCalendar('2');" class="cpCurrentMonthDate">26</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,27);CP_hideCalendar('2');" class="cpCurrentMonthDate">27</a></td>
    </tr>
    <tr>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,28);CP_hideCalendar('2');" class="cpCurrentMonthDate">28</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,29);CP_hideCalendar('2');" class="cpCurrentMonthDate">29</a></td>
        <td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,30);CP_hideCalendar('2');" class="cpCurrentMonthDate">30</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,1);CP_hideCalendar('2');" class="cpOtherMonthDate">1</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,2);CP_hideCalendar('2');" class="cpOtherMonthDate">2</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,3);CP_hideCalendar('2');" class="cpOtherMonthDate">3</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,4);CP_hideCalendar('2');" class="cpOtherMonthDate">4</a></td>
    </tr>
    <tr>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,5);CP_hideCalendar('2');" class="cpOtherMonthDate">5</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,6);CP_hideCalendar('2');" class="cpOtherMonthDate">6</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,7);CP_hideCalendar('2');" class="cpOtherMonthDate">7</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,8);CP_hideCalendar('2');" class="cpOtherMonthDate">8</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,9);CP_hideCalendar('2');" class="cpOtherMonthDate">9</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,10);CP_hideCalendar('2');" class="cpOtherMonthDate">10</a></td>
        <td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,11);CP_hideCalendar('2');" class="cpOtherMonthDate">11</a></td>
    </tr>
    <tr>
        <td colspan="7" class="cpTodayText" align="CENTER">
        <a class="cpTodayText" href="javascript:CP_tmpReturnFunction('2010','11','12');CP_hideCalendar('2');">Today</a>
        <br>
        </td>
    </tr>
    </tbody>
</table>
</center>
</td>
</tr>
</tbody>
</table>
</div>
<? include ('footer.inc');?>
</body>
</html>