<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

list($ConVacuumLvl) = $infosystem->Execute("SELECT `ConVacuum` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($ConVacuumLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($ConVacuumLvl=="o")?" disabled=\"disabled\"":"";

if(isset($_POST['submit'])) {
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Construction Vacuum', '{$_SESSION['username']}')");
	foreach($_POST as $key => $value) $$key = $value;
		$areas = array("Kearl","Syncrude");
		foreach($areas as $ar) {
			foreach($SumpLicence[$ar] as $key=>$wl) {
				$sd = $StartDate[$ar][$key];
				$ed = $EndDate[$ar][$key];
				$efd = $EffectiveDate[$ar][$key];
				$exd = $ExpiryDate[$ar][$key];
				$infosystem->Execute("UPDATE `con_vacuum` SET `sump_licence` = '{$wl}', `licence_effective_date` = '{$efd}', `licence_expiry_date` = '{$exd}', `start_date` = '{$sd}', `end_date` = '{$ed}' WHERE `area` = '{$ar}' AND `sump_number` = {$key}");
			}
		}
	}
	list($sumKearl1, $sumKearl2, $sumKearl3, $sumKearl4, $sumKearl5, $sumKearl6) = $infosystem->Execute("SELECT SUM(`loc_1`) s1, SUM(`loc_2`), SUM(`loc_3`), SUM(`loc_4`), SUM(`loc_5`), SUM(`loc_6`) s2 FROM `vacuum` WHERE `input_date`< CURDATE() AND `area` = 'Kearl'")->fields;
	list($sumSyncrude1) = $infosystem->Execute("SELECT SUM(`loc_1`) s1 FROM `vacuum` WHERE `input_date` = < CURDATE() AND `area` = 'Syncrude'")->fields;

	$rsConVacuum = $infosystem->Execute("SELECT `area`, `sump_number`, `sump_licence`, `licence_effective_date`, `licence_expiry_date`, `sump_ID`, `program_zone`, `location_LSD`, `start_date`, `end_date`, `total_licensed_volume` FROM `con_vacuum`");
	$conVacuumArray = array();
	while(!$rsConVacuum->EOF) {
		list($x_area, $x_sump_number, $x_sump_licence, $x_licence_effective_date, $x_licence_expiry_date, $x_sump_ID, $x_program_zone, $x_location_LSD, $x_start_date, $x_end_date, $x_total_licensed_volume) = $rsConVacuum->fields;
		$conVacuumArray["sump_licence"][$x_area][$x_sump_number] = $x_sump_licence;
		$conVacuumArray["licence_effective_date"][$x_area][$x_sump_number] = $x_licence_effective_date;
		$conVacuumArray["licence_expiry_date"][$x_area][$x_sump_number] = $x_licence_expiry_date;
		$conVacuumArray["sump_ID"][$x_area][$x_sump_number] = $x_sump_ID;
		$conVacuumArray["program_zone"][$x_area][$x_sump_number] = $x_program_zone;
		$conVacuumArray["location_LSD"][$x_area][$x_sump_number] = $x_location_LSD;
		$conVacuumArray["start_date"][$x_area][$x_sump_number] = $x_start_date;
		$conVacuumArray["end_date"][$x_area][$x_sump_number] = $x_end_date;
		$conVacuumArray["total_licensed_volume"][$x_area][$x_sump_number] = $x_total_licensed_volume;
		$rsConVacuum->MoveNext();
	}
?>
<html>
<head>
<title>Construction Vacuum - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<script src="CalendarPopupCombined.js"></script>
</head>
<body onLoad="collapseAll()">
<div id="mainForm" style="padding:20px;">
<? include ('header.inc');?>
<br/>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="frm">
<p align="left">SUMP AMOUNT LEFT PER EACH SUMP (ADVISE SUPERVISOR WHEN 200 m3 LIMIT IS REACHED)</p>
<table cellspacing="1" cellpadding="6">
	<caption>Kearl</caption>
    <tr>
    	<td width="120" align="left">Sump Source</td>
        <td width="30" align="center">1</td>
        <td width="30" align="center">2</td>
        <td width="30" align="center">3</td>
        <td width="30" align="center">4</td>
        <td width="30" align="center">5</td>
        <td width="30" align="center">6</td>
    </tr>
    <tr>
    	<td>Sump Amount Left (m3) </td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Kearl"][1]-$sumKearl1?></td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Kearl"][2]-$sumKearl2?></td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Kearl"][3]-$sumKearl3?></td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Kearl"][4]-$sumKearl4?></td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Kearl"][5]-$sumKearl5?></td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Kearl"][6]-$sumKearl6?></td>
    </tr>
</table>
<br />
<table cellspacing="1" cellpadding="5">
    <tr>
        <td><strong>Kearl</strong></td>
        <td align="center"><strong>1</strong></td>
        <td align="center"><strong>2</strong></td>
        <td align="center"><strong>3</strong></td>
        <td align="center"><strong>4</strong></td>
        <td align="center"><strong>5</strong></td>
        <td align="center"><strong>6</strong></td>
    </tr>
    <tr>
        <td align="center">Sump Licence # (TDL)</td>
        <td align="center"><input type="text" name="SumpLicence[Kearl][1]" id="SumpLicenceKearl1" size="12" value="<?=$conVacuumArray["sump_licence"]["Kearl"][1]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="SumpLicence[Kearl][2]" id="SumpLicenceKearl2" size="12" value="<?=$conVacuumArray["sump_licence"]["Kearl"][2]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="SumpLicence[Kearl][3]" id="SumpLicenceKearl3" size="12" value="<?=$conVacuumArray["sump_licence"]["Kearl"][3]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="SumpLicence[Kearl][4]" id="SumpLicenceKearl4" size="12" value="<?=$conVacuumArray["sump_licence"]["Kearl"][4]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="SumpLicence[Kearl][5]" id="SumpLicenceKearl5" size="12" value="<?=$conVacuumArray["sump_licence"]["Kearl"][5]?>"<?=$readOnly?> /></td>
        <td align="center"><input type="text" name="SumpLicence[Kearl][6]" id="SumpLicenceKearl6" size="12" value="<?=$conVacuumArray["sump_licence"]["Kearl"][6]?>"<?=$readOnly?> /></td>
 </tr>
    <tr>
        <td align="center">Effective Date</td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][1]" id="EffectiveDateKearl1" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Kearl"][1]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx1.select(document.forms[0].elements[6],'anchor1xx1','yyyy-MM-dd')" name="anchor1xx1" id="anchor1xx1"></td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][2]" id="EffectiveDateKearl2" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Kearl"][2]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx2.select(document.forms[0].elements[7],'anchor1xx2','yyyy-MM-dd')" name="anchor1xx2" id="anchor1xx2"></td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][3]" id="EffectiveDateKearl3" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Kearl"][3]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Kearl"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx3.select(document.forms[0].elements[8],'anchor1xx3','yyyy-MM-dd')" name="anchor1xx3" id="anchor1xx3"></td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][4]" id="EffectiveDateKearl4" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Kearl"][4]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Kearl"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx4.select(document.forms[0].elements[9],'anchor1xx4','yyyy-MM-dd')" name="anchor1xx4" id="anchor1xx4"></td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][5]" id="EffectiveDateKearl5" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Kearl"][5]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Kearl"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx5.select(document.forms[0].elements[10],'anchor1xx5','yyyy-MM-dd')" name="anchor1xx5" id="anchor1xx5"></td>
        <td align="center"><input type="text" name="EffectiveDate[Kearl][6]" id="EffectiveDateKearl6" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Kearl"][6]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Kearl"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx6.select(document.forms[0].elements[11],'anchor1xx6','yyyy-MM-dd')" name="anchor1xx6" id="anchor1xx6"></td>

    </tr>
    <tr>
    <td align="center">Expiry Date</td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][1]" id="ExpiryDateKearl1" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Kearl"][1]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx7.select(document.forms[0].elements[12],'anchor1xx7','yyyy-MM-dd')" name="anchor1xx7" id="anchor1xx7"></td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][2]" id="ExpiryDateKearl2" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Kearl"][2]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx8.select(document.forms[0].elements[13],'anchor1xx8','yyyy-MM-dd')" name="anchor1xx8" id="anchor1xx8"></td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][3]" id="ExpiryDateKearl3" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Kearl"][3]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Kearl"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx9.select(document.forms[0].elements[14],'anchor1xx9','yyyy-MM-dd')" name="anchor1xx9" id="anchor1xx9"></td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][4]" id="ExpiryDateKearl4" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Kearl"][4]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Kearl"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx10.select(document.forms[0].elements[15],'anchor1xx10','yyyy-MM-dd')" name="anchor1xx10" id="anchor1xx10"></td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][5]" id="ExpiryDateKearl5" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Kearl"][5]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Kearl"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx11.select(document.forms[0].elements[16],'anchor1xx11','yyyy-MM-dd')" name="anchor1xx11" id="anchor1xx11"></td>
        <td align="center"><input type="text" name="ExpiryDate[Kearl][6]" id="ExpiryDateKearl6" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Kearl"][6]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Kearl"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx12.select(document.forms[0].elements[17],'anchor1xx12','yyyy-MM-dd')" name="anchor1xx12" id="anchor1xx12"></td>
 </tr>
    <tr>
        <td align="center">Sump ID</td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Kearl"][6]?></td>
    </tr>
    <tr>
        <td align="center">Program-Zone</td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Kearl"][6]?></td>
    </tr>
    <tr>
        <td align="center">Location LSD</td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][1]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][2]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][3]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][4]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][5]?></td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Kearl"][6]?></td>
    </tr>
    <tr>
        <td align="center">Start Date</td>
        <td align="center"><input type="text" name="StartDate[Kearl][1]" id="StartDateKearl1" size="10" value="<?=($conVacuumArray["start_date"]["Kearl"][1]!="0000-00-00")?$conVacuumArray["start_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx13.select(document.forms[0].elements[18],'anchor1xx13','yyyy-MM-dd')" name="anchor1xx13" id="anchor1xx13"></td>
        <td align="center"><input type="text" name="StartDate[Kearl][2]" id="StartDateKearl2" size="10" value="<?=($conVacuumArray["start_date"]["Kearl"][2]!="0000-00-00")?$conVacuumArray["start_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx14.select(document.forms[0].elements[19],'anchor1xx14','yyyy-MM-dd')" name="anchor1xx14" id="anchor1xx14"></td>
        <td align="center"><input type="text" name="StartDate[Kearl][3]" id="StartDateKearl3" size="10" value="<?=($conVacuumArray["start_date"]["Kearl"][3]!="0000-00-00")?$conVacuumArray["start_date"]["Kearl"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx15.select(document.forms[0].elements[20],'anchor1xx15','yyyy-MM-dd')" name="anchor1xx15" id="anchor1xx15"></td>
        <td align="center"><input type="text" name="StartDate[Kearl][4]" id="StartDateKearl4" size="10" value="<?=($conVacuumArray["start_date"]["Kearl"][4]!="0000-00-00")?$conVacuumArray["start_date"]["Kearl"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx16.select(document.forms[0].elements[21],'anchor1xx16','yyyy-MM-dd')" name="anchor1xx16" id="anchor1xx16"></td>
        <td align="center"><input type="text" name="StartDate[Kearl][5]" id="StartDateKearl5" size="10" value="<?=($conVacuumArray["start_date"]["Kearl"][5]!="0000-00-00")?$conVacuumArray["start_date"]["Kearl"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx17.select(document.forms[0].elements[22],'anchor1xx17','yyyy-MM-dd')" name="anchor1xx17" id="anchor1xx17"></td>
        <td align="center"><input type="text" name="StartDate[Kearl][6]" id="StartDateKearl6" size="10" value="<?=($conVacuumArray["start_date"]["Kearl"][6]!="0000-00-00")?$conVacuumArray["start_date"]["Kearl"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx18.select(document.forms[0].elements[23],'anchor1xx18','yyyy-MM-dd')" name="anchor1xx18" id="anchor1xx18"></td>
    </tr>
    <tr>
        <td align="center">End Date</td>
        <td align="center"><input type="text" name="EndDate[Kearl][1]" id="EndDateKearl1" size="10" value="<?=($conVacuumArray["end_date"]["Kearl"][1]!="0000-00-00")?$conVacuumArray["end_date"]["Kearl"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx19.select(document.forms[0].elements[24],'anchor1xx19','yyyy-MM-dd')" name="anchor1xx19" id="anchor1xx19"></td>
        <td align="center"><input type="text" name="EndDate[Kearl][2]" id="EndDateKearl2" size="10" value="<?=($conVacuumArray["end_date"]["Kearl"][2]!="0000-00-00")?$conVacuumArray["end_date"]["Kearl"][2]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx20.select(document.forms[0].elements[25],'anchor1xx20','yyyy-MM-dd')" name="anchor1xx20" id="anchor1xx20"></td>
        <td align="center"><input type="text" name="EndDate[Kearl][3]" id="EndDateKearl3" size="10" value="<?=($conVacuumArray["end_date"]["Kearl"][3]!="0000-00-00")?$conVacuumArray["end_date"]["Kearl"][3]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx21.select(document.forms[0].elements[26],'anchor1xx21','yyyy-MM-dd')" name="anchor1xx21" id="anchor1xx21"></td>
        <td align="center"><input type="text" name="EndDate[Kearl][4]" id="EndDateKearl4" size="10" value="<?=($conVacuumArray["end_date"]["Kearl"][4]!="0000-00-00")?$conVacuumArray["end_date"]["Kearl"][4]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx22.select(document.forms[0].elements[27],'anchor1xx22','yyyy-MM-dd')" name="anchor1xx22" id="anchor1xx22"></td>
        <td align="center"><input type="text" name="EndDate[Kearl][5]" id="EndDateKearl5" size="10" value="<?=($conVacuumArray["end_date"]["Kearl"][5]!="0000-00-00")?$conVacuumArray["end_date"]["Kearl"][5]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx23.select(document.forms[0].elements[28],'anchor1xx23','yyyy-MM-dd')" name="anchor1xx23" id="anchor1xx23"></td>
        <td align="center"><input type="text" name="EndDate[Kearl][6]" id="EndDateKearl6" size="10" value="<?=($conVacuumArray["end_date"]["Kearl"][6]!="0000-00-00")?$conVacuumArray["end_date"]["Kearl"][6]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx24.select(document.forms[0].elements[29],'anchor1xx24','yyyy-MM-dd')" name="anchor1xx24" id="anchor1xx24"></td>
    </tr>
</table>
<br />
<table cellspacing="1" cellpadding="5">
	<caption>Syncrude</caption>
    <tr>
    	<td width="120" align="left">Sump Source</td>
        <td width="30" align="center">1</td>
    </tr>
    <tr>
    	<td>Sump Amount Left (m3) </td>
        <td><?=$conVacuumArray["total_licensed_volume"]["Syncrude"][1]-$sumSyncrude1?></td>
    </tr>
</table>
<br />
<table cellspacing="1" cellpadding="5">
    <tr>
        <td><strong>Syncrude</strong></td>
        <td align="center"><strong>1</strong></td>
    </tr>
    <tr>
        <td align="center">Sump Licence # (TDL)</td>
        <td align="center"><input type="text" name="SumpLicence[Syncrude][1]" id="SumpLicenceSyncrude1" size="12" value="<?=$conVacuumArray["sump_licence"]["Syncrude"][1]?>"<?=$readOnly?> /></td>
    </tr>
    <tr>
        <td align="center">Effective Date</td>
        <td align="center"><input type="text" name="EffectiveDate[Syncrude][1]" id="EffectiveSyncrude1" size="10" value="<?=($conVacuumArray["licence_effective_date"]["Syncrude"][1]!="0000-00-00")?$conVacuumArray["licence_effective_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx25.select(document.forms[0].elements[31],'anchor1xx25','yyyy-MM-dd')" name="anchor1xx25" id="anchor1xx25"></td>
    </tr>
    <tr>
    <td align="center">Expiry Date</td>
        <td align="center"><input type="text" name="ExpiryDate[Syncrude][1]" id="ExpiryDateSyncrude1" size="10" value="<?=($conVacuumArray["licence_expiry_date"]["Syncrude"][1]!="0000-00-00")?$conVacuumArray["licence_expiry_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx26.select(document.forms[0].elements[32],'anchor1xx26','yyyy-MM-dd')" name="anchor1xx26" id="anchor1xx26"></td>
     </tr>
    <tr>
        <td align="center">Sump ID</td>
        <td align="center"><?=$conVacuumArray["sump_ID"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">Program-Zone</td>
        <td align="center"><?=$conVacuumArray["program_zone"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">Location LSD</td>
        <td align="center"><?=$conVacuumArray["location_LSD"]["Syncrude"][1]?></td>
    </tr>
    <tr>
        <td align="center">Start Date</td>
        <td align="center"><input type="text" name="StartDate[Syncrude][1]" id="StartDateSyncrude1" size="10" value="<?=($conVacuumArray["start_date"]["Syncrude"][1]!="0000-00-00")?$conVacuumArray["start_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx27.select(document.forms[0].elements[33],'anchor1xx27','yyyy-MM-dd')" name="anchor1xx27" id="anchor1xx27"></td>
    </tr>
    <tr>
        <td align="center">End Date</td>
        <td align="center"><input type="text" name="EndDate[Syncrude][1]" id="EndDateSyncrude1" size="10" value="<?=($conVacuumArray["end_date"]["Syncrude"][1]!="0000-00-00")?$conVacuumArray["end_date"]["Syncrude"][1]:""?>" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx28.select(document.forms[0].elements[34],'anchor1xx28','yyyy-MM-dd')" name="anchor1xx28" id="anchor1xx28"></td>
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