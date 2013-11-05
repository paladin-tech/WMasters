<?
include("sessionCheck.php");
include("db.php");

$user = $_SESSION['username'];
$now = mktime();
$nowDate = date("Y-m-d", $now);
$nowTime = date("Y-m-d H:i:s", $now);

// We need to check if record for today already exists
$dailySupervisor = $infosystem->Execute("SELECT `weather_high`, `weather_low`, `weather_precipitation`, `weather_conditions`, `remarks` FROM `daily_supervisors` WHERE `input_date` = '{$nowDate}'");
$updateForm = ($dailySupervisor->RecordCount()==0)?false:true;

// Filling combo's with data
$rsAfpProjectSummaryItems = $infosystem->Execute("SELECT `item_id`, `item_description`, `total_w` FROM `afp_project_summary_items` WHERE `Active` = 1 order by `item_id`");
$rsRigs = $infosystem->Execute("SELECT `rig_id`, `rig_code` FROM `rigs`");
$rsWells = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

if($updateForm) {
	list($x_weather_high, $x_weather_low, $x_weather_precipitation, $x_weather_conditions, $x_remarks) = $dailySupervisor->fields;
	$rsDailySupAFPSumm = $infosystem->Execute("SELECT `afp_item_id`, `current` FROM `daily_supervisors_afp_summ` WHERE `input_date` = '{$nowDate}'");
	while(!$rsDailySupAFPSumm->EOF) {
		$rsDailySupAFPSummArr[$rsDailySupAFPSumm->Fields("afp_item_id")] = $rsDailySupAFPSumm->Fields("current");
		$rsDailySupAFPSumm->MoveNext();
	}
	$rsDailySupDrill24h = $infosystem->Execute("SELECT `rig_id`, `completed`, `on`, `next_well` FROM `daily_supervisors_drill24hrs_summ` WHERE `input_date` = '{$nowDate}'");
	while(!$rsDailySupDrill24h->EOF) {
		$rsDailySupDrill24hArr[$rsDailySupDrill24h->Fields("rig_id")]["completed"] = $rsDailySupDrill24h->Fields("completed");
		$rsDailySupDrill24hArr[$rsDailySupDrill24h->Fields("rig_id")]["on"] = $rsDailySupDrill24h->Fields("on");
		$rsDailySupDrill24hArr[$rsDailySupDrill24h->Fields("rig_id")]["next_well"] = $rsDailySupDrill24h->Fields("next_well");
		$rsDailySupDrill24h->MoveNext();
	}
} else {
	$lastUpdated = $infosystem->Execute("SELECT Max(`input_date`) FROM `daily_supervisors`");
	if ($lastUpdated->RecordCount() > 0) {
		list($lastUpdatedDate) = $lastUpdated->fields;
		$dailySupervisorCopyPrevious4 = $infosystem->Execute("SELECT `remarks` FROM `daily_supervisors` WHERE  `input_date` =  '{$lastUpdatedDate}'");
		list($x_remarks) = $dailySupervisorCopyPrevious4->fields;
	}
}

list($DailySupervisorsLvl) = $infosystem->Execute("SELECT `SupervisorsDaily` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($DailySupervisorsLvl=="o"||$checkReviewed==1)?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($DailySupervisorsLvl=="o"||$checkReviewed==1)?" disabled=\"disabled\"":"";

// Submit execution procedure
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	if($updateForm) {
		$infosystem->Execute("UPDATE `daily_supervisors` SET `weather_high` = '{$weather_high}', `weather_low` = '{$weather_low}', `weather_precipitation` = '{$weather_precipitation}', `weather_conditions` = '{$weather_conditions}', `remarks` = '{$aremarks}' WHERE `input_date` = '{$nowDate}'");
		foreach($AFP as $key=>$value) $infosystem->Execute("UPDATE `daily_supervisors_afp_summ` SET `current` = '{$value}' WHERE `input_date` = '{$nowDate}' AND `afp_item_id` = '{$key}'");
		foreach($RigCompleted24 as $rig_id=>$value1) {
			$value2 = $RigON[$rig_id];
			$value3 = $RigNextWell[$rig_id];
			$infosystem->Execute("UPDATE `daily_supervisors_drill24hrs_summ` SET `completed` = '{$value1}', `on` = '{$value2}', `next_well` = '{$value3}' WHERE `input_date` = '{$nowDate}' AND `rig_id` = '{$rig_id}'");
		}
		$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Daily Supervisors', '{$user}')");
	} else {
		$infosystem->Execute("INSERT INTO `daily_supervisors`(`input_date`, `weather_high`, `weather_low`, `weather_precipitation`, `weather_conditions`, `remarks`, `user`) VALUES('{$nowDate}', '{$weather_high}', '{$weather_low}', '{$weather_precipitation}', '{$weather_conditions}', '{$aremarks}', '{$user}')");
		foreach($AFP as $key=>$value) $infosystem->Execute("INSERT INTO `daily_supervisors_afp_summ`(`input_date`, `afp_item_id`, `current`) VALUES('{$nowDate}', '{$key}', '{$value}')");
		foreach($RigCompleted24 as $rig_id=>$value1) {
			$value2 = $RigON[$rig_id];
			$value3 = $RigNextWell[$rig_id];
			$infosystem->Execute("INSERT INTO `daily_supervisors_drill24hrs_summ`(`input_date`, `rig_id`, `completed`, `on`, `next_well`) VALUES('{$nowDate}', '{$rig_id}', '{$value1}', '{$value2}', '{$value3}')");
		}
		$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Daily Supervisors', '{$user}')");
	}

include("confirm.html");
ob_end_flush();
}
else {
?>
<html><head>
<title>Daily Supervisors - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<? include("header.inc"); ?>
<div id="mainForm" style="padding:20px;">
<form name="frm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table border="1" cellspacing="1" cellpadding="3">
    <caption>WEATHER</caption>
    <tr>
        <th scope="row">High (&deg;C)</th>
        <td><input type="text" name="weather_high" id="weather_high" value="<?=($updateForm)?$x_weather_high:""?>"<?=$readOnly?> /></td>
    </tr>
    <tr>
        <th scope="row">Low (&deg;C)</th>
        <td><input type="text" name="weather_low" id="weather_low" value="<?=($updateForm)?$x_weather_low:""?>"<?=$readOnly?> /></td>
    </tr>
    <tr>
        <th scope="row">Precipitation (%)</th>
        <td><input type="text" name="weather_precipitation" id="weather_precipitation" value="<?=($updateForm)?$x_weather_precipitation:""?>"<?=$readOnly?> /></td>
    </tr>
    <tr>
        <th scope="row">Conditions</th>
        <td><input type="text" name="weather_conditions" id="weather_conditions" value="<?=($updateForm)?$x_weather_conditions:""?>"<?=$readOnly?> /></td>
    </tr>
</table>
<br />
<table border="1" cellspacing="1" cellpadding="3">
    <caption>AFP Project Summary</caption>
    <tr>
        <th scope="row">Entries</th>
        <td>Current</td>
        <td>Total(w)</td>
        <!--td>TOTAL%</td-->
    </tr><?
    while(!$rsAfpProjectSummaryItems->EOF) {
    list($x_item_id, $x_item_description, $x_total_w) = $rsAfpProjectSummaryItems->fields; ?>
    <tr>
        <th scope="row"><?=$x_item_description?></th>
        <td><input type="text" name="AFP[<?=$x_item_id?>]" id="AFP<?=$x_item_id?>" value="<?=($updateForm)?$rsDailySupAFPSummArr[$x_item_id]:""?>" size="4"<?=$readOnly?> /></td>
        <td><input type="text" name="TotalW[<?=$x_item_id?>]" id="TotalW<?=$x_item_id?>" value="<?=$x_total_w?>" size="4" readonly></td>
        <!--td><input type="text" name="Total[<?//=$x_item_id?>]" id="Total<?//=$x_item_id?>" value="<?//=($updateForm)?number_format(($rsDailySupAFPSummArr[$x_item_id]*100/$x_total_w), 1):0?>" size="4"<?//=$readOnly?> /></td-->
    </tr><?
    $rsAfpProjectSummaryItems->MoveNext();
    } ?>
</table>
<br />
<table border="1" cellspacing="1" cellpadding="3">
    <caption>Drilling 24 Hrs Activity Summary</caption>
    <tr>
        <th scope="row">Rigs</th>
        <td>Completed Wells in 24 Hrs</td>
        <td>ON</td>
        <td>Next Well</td>
    </tr><?
  while(!$rsRigs->EOF) {
  list($x_rig_id, $x_rig_code) = $rsRigs->fields; ?>
  <tr>
    <th scope="row"><?=$x_rig_code?></th>
    <td><textarea name="RigCompleted24[<?=$x_rig_id?>]" id="RigCompleted24<?=$x_rig_id?>"<?=$readOnly?>><?=$rsDailySupDrill24hArr[$x_rig_id]["completed"]?></textarea></td>
    <td>
        <select name="RigON[<?=$x_rig_id?>]" id="RigON<?=$x_rig_id?>">
			<option value=""></option><?
			$rsWells->MoveFirst();
			while(!$rsWells->EOF) {
			list($x_well_id) = $rsWells->fields; ?>
			<option value="<?=$x_well_id?>"<?=($updateForm&&($rsDailySupDrill24hArr[$x_rig_id]["on"]==$x_well_id))?" selected":""?>><?=$x_well_id?></option><?
			$rsWells->MoveNext();
			} ?>
        </select>
    </td>
    <td>
        <select name="RigNextWell[<?=$x_rig_id?>]" id="RigNextWell<?=$x_rig_id?>">
			<option value=""></option><?
			$rsWells->MoveFirst();
			while(!$rsWells->EOF) {
			list($x_well_id) = $rsWells->fields; ?>
			<option value="<?=$x_well_id?>"<?=($updateForm&&($rsDailySupDrill24hArr[$x_rig_id]["next_well"]==$x_well_id))?" selected":""?>><?=$x_well_id?></option><?
			$rsWells->MoveNext();
			} ?>
        </select>
    </td>
  </tr><?
  $rsRigs->MoveNext();
  } ?>
</table>
<br />
<p>
    Remarks<br />
    <textarea name="aremarks" id="aremarks" style="width: 400px; height: 100px;"<?=$readOnly?>><?=$x_remarks?></textarea>
</p>
<p>
    <input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> />
</p>
</form>
</div>
<? include("footer.inc"); ?>
</body>
</html><?
}
?>