<?
include("sessionCheck.php");
include("db.php");

$rsCompany = $infosystem->Execute("SELECT `companyID`, `name` FROM `wm_company` ORDER BY `name`");

if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	if($companyId == '') {
		$infosystem->Execute("INSERT INTO `wm_company` SET `name` = '{$txtName}', `description` = '{$txtDescription}', `address` = '{$txtAddress}', `DailySafety` = '{$selDailySafety}', `DailyInputs` = '{$selDailyInputs}', `SupervisorsDaily` = '{$selSupervisorsDaily}', `DrillingGeotech` = '{$selDrillingGeotech}', `ConDaily` = '{$selConDaily}', `ConPerWell` = '{$selConPerWell}', `ConstContr` = '{$selConstContr}', `ConHydro` = '{$selConHydro}', `ConVacuum` = '{$selConVacuum}', `SurveyAccess` = '{$selSurveyAccess}', `ConWaterCross` = '{$selConWaterCross}', `Water` = '{$selWater}', `Vacuum` = '{$selVacuum}', `WellConUpdate` = '{$selWellConUpdate}', `DailyMudModule` = '{$selDailyMudModule}', `MudProducts` = '{$selMudProducts}', `MudProductList` = '{$selMudProductList}', `SurveyModule` = '{$selSurveyModule}', `RigMatLocation` = '{$selRigMatLocation}', `TempLogDeck` = '{$selTempLogDeck}', `Wells` = '{$selWells}', `Report1` = '{$selReport1}', `Report2` = '{$selReport2}', `Report3` = '{$selReport3}', `Report4` = '{$selReport4}', `Report5` = '{$selReport5}', `Report6` = '{$selReport6}', `Report7` = '{$selReport7}', `Report8` = '{$selReport8}', `Report9` = '{$selReport9}', `Report10` = '{$selReport10}', `Report11` = '{$selReport11}', `Report12` = '{$selReport12}', `Report13` = '{$selReport13}', `Report14` = '{$selReport14}', `Report15` = '{$selReport15}', `Report16` = '{$selReport16}', `Report17` = '{$selReport17}', `Report18` = '{$selReport18}', `Report19` = '{$selReport19}', `Report20` = '{$selReport20}', `Report21` = '{$selReport21}', `Report` = '{$selReport}'");
		$companyId = $infosystem->Insert_ID();
	}
	else
	{
		$infosystem->Execute("UPDATE `wm_company` SET `name` = '{$txtName}', `description` = '{$txtDescription}', `address` = '{$txtAddress}', `DailySafety` = '{$selDailySafety}', `DailyInputs` = '{$selDailyInputs}', `SupervisorsDaily` = '{$selSupervisorsDaily}', `DrillingGeotech` = '{$selDrillingGeotech}', `ConDaily` = '{$selConDaily}', `ConPerWell` = '{$selConPerWell}', `ConstContr` = '{$selConstContr}', `ConHydro` = '{$selConHydro}', `ConVacuum` = '{$selConVacuum}', `SurveyAccess` = '{$selSurveyAccess}', `ConWaterCross` = '{$selConWaterCross}', `Water` = '{$selWater}', `Vacuum` = '{$selVacuum}', `WellConUpdate` = '{$selWellConUpdate}', `DailyMudModule` = '{$selDailyMudModule}', `MudProducts` = '{$selMudProducts}', `MudProductList` = '{$selMudProductList}', `SurveyModule` = '{$selSurveyModule}', `RigMatLocation` = '{$selRigMatLocation}', `TempLogDeck` = '{$selTempLogDeck}', `Wells` = '{$selWells}', `Report1` = '{$selReport1}', `Report2` = '{$selReport2}', `Report3` = '{$selReport3}', `Report4` = '{$selReport4}', `Report5` = '{$selReport5}', `Report6` = '{$selReport6}', `Report7` = '{$selReport7}', `Report8` = '{$selReport8}', `Report9` = '{$selReport9}', `Report10` = '{$selReport10}', `Report11` = '{$selReport11}', `Report12` = '{$selReport12}', `Report13` = '{$selReport13}', `Report14` = '{$selReport14}', `Report15` = '{$selReport15}', `Report16` = '{$selReport16}', `Report17` = '{$selReport17}', `Report18` = '{$selReport18}', `Report19` = '{$selReport19}', `Report20` = '{$selReport20}', `Report21` = '{$selReport21}', `Report` = '{$selReport}' WHERE `companyID` = '{$companyId}'");
	}
}

if(isset($_GET['companyId'])) {
	$companyId = $_GET['companyId'];
	list($name, $description, $address, $DailySafety, $DailyInputs, $SupervisorsDaily, $DrillingGeotech, $ConDaily, $ConPerWell, $ConstContr, $ConHydro, $ConVacuum, $SurveyAccess, $ConWaterCross, $Water, $Vacuum, $WellConUpdate, $DailyMudModule, $MudProducts, $MudProductList, $SurveyModule, $RigMatLocation, $TempLogDeck, $Wells, $Report1, $Report2, $Report3, $Report4, $Report5, $Report6, $Report7, $Report8, $Report9, $Report10, $Report11, $Report12, $Report13, $Report14, $Report15, $Report16, $Report17, $Report18, $Report19, $Report20, $Report21, $Report) = $infosystem->Execute("SELECT `name`, `description`, `address`, `DailySafety`, `DailyInputs`, `SupervisorsDaily`, `DrillingGeotech`, `ConDaily`, `ConPerWell`, `ConstContr`, `ConHydro`, `ConVacuum`, `SurveyAccess`, `ConWaterCross`, `Water`, `Vacuum`, `WellConUpdate`, `DailyMudModule`, `MudProducts`, `MudProductList`, `SurveyModule`, `RigMatLocation`, `TempLogDeck`, `Wells`, `Report1`, `Report2`, `Report3`, `Report4`, `Report5`, `Report6`, `Report7`, `Report8`, `Report9`, `Report10`, `Report11`, `Report12`, `Report13`, `Report14`, `Report15`, `Report16`, `Report17`, `Report18`, `Report19`, `Report20`, `Report21`, `Report` FROM `wm_company` WHERE `companyID` = '{$companyId}'")->fields;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Edit user access - WM Digital System</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#selCompany').change(function() {
				window.location.href = ($(this).val() == '') ? '<?= $_SERVER["PHP_SELF"] ?>' : '<?= $_SERVER["PHP_SELF"] ?>?companyId='+$(this).val();
			});
			$('#frm').submit(function() {
				if(!confirm('Are you sure you want to make changes?')) return false;
			});
		});
	</script>
	<style type="text/css">
		table.modules td {
			width: 25%;
		}
	</style>
</head>

<body>
<? include ('header.inc');?>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?><?= (isset($companyId)) ? "?companyId={$companyId}" : "" ?>" method="post">
	<input type="hidden" name="companyId" value="<?= (isset($companyId)) ? $companyId : 0 ?>">
	<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
		<tr valign="top">
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
					<tr>
						<td>Company</td>
					</tr>
					<tr>
						<td>
							<select name="selCompany" id="selCompany">
								<option value="[New Company]"></option>
								<?
								while(!$rsCompany->EOF) {
									list($xCompanyId, $xCompanyName) = $rsCompany->fields;
									?>
									<option value="<?= $xCompanyId ?>"<?= (isset($companyId) && $companyId == $xCompanyId) ? " selected" : ""?>><?= $xCompanyName ?></option>
									<?
									$rsCompany->MoveNext();
								}
								?>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td width="100%"></td>
		</tr>
		<tr>
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%">
					<tr valign="top">
						<td>
							Company Name<br>
							<input type="text" name="txtName" value="<?= (isset($companyId)) ? $name : "" ?>">
						</td>
						<td>
							Description<br>
							<textarea rows="3" cols="40" name="txtDescription"><?= (isset($companyId)) ? $description : "" ?></textarea>
						</td>
						<td>
							Address<br>
							<textarea rows="3" cols="40" name="txtAddress"><?= (isset($companyId)) ? $address : "" ?></textarea>
						</td>
					</tr>
				</table>
			</td>
			<td width="100%"></td>
		</tr>
		<tr>
			<td>
				<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" width="100%" class="modules">
					<tr>
						<td>Daily Safety</td>
						<td>
							<select name="selDailySafety">
								<option value="d"<?= (isset($companyId) && $DailySafety == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $DailySafety == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $DailySafety == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $DailySafety == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 1</td>
						<td>
							<select name="selReport1">
								<option value="d"<?= (isset($companyId) && $Report1 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report1 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report1 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report1 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Daily Inputs</td>
						<td>
							<select name="selDailyInputs">
								<option value="d"<?= (isset($companyId) && $DailyInputs == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $DailyInputs == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $DailyInputs == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $DailyInputs == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 2</td>
						<td>
							<select name="selReport2">
								<option value="d"<?= (isset($companyId) && $Report2 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report2 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report2 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report2 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Supervisors Daily</td>
						<td>
							<select name="selSupervisorsDaily">
								<option value="d"<?= (isset($companyId) && $SupervisorsDaily == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $SupervisorsDaily == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $SupervisorsDaily == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $SupervisorsDaily == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 3</td>
						<td>
							<select name="selReport3">
								<option value="d"<?= (isset($companyId) && $Report3 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report3 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report3 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report3 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Drilling Geotech</td>
						<td>
							<select name="selDrillingGeotech">
								<option value="d"<?= (isset($companyId) && $DrillingGeotech == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $DrillingGeotech == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $DrillingGeotech == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $DrillingGeotech == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 4</td>
						<td>
							<select name="selReport4">
								<option value="d"<?= (isset($companyId) && $Report4 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report4 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report4 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report4 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Con Daily</td>
						<td>
							<select name="selConDaily">
								<option value="d"<?= (isset($companyId) && $ConDaily == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $ConDaily == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $ConDaily == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $ConDaily == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 5</td>
						<td>
							<select name="selReport5">
								<option value="d"<?= (isset($companyId) && $Report5 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report5 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report5 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report5 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Con Per Well</td>
						<td>
							<select name="selConPerWell">
								<option value="d"<?= (isset($companyId) && $ConPerWell == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $ConPerWell == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $ConPerWell == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $ConPerWell == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 6</td>
						<td>
							<select name="selReport6">
								<option value="d"<?= (isset($companyId) && $Report6 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report6 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report6 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report6 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Const Contr</td>
						<td>
							<select name="selConstContr">
								<option value="d"<?= (isset($companyId) && $ConstContr == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $ConstContr == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $ConstContr == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $ConstContr == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 7</td>
						<td>
							<select name="selReport7">
								<option value="d"<?= (isset($companyId) && $Report7 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report7 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report7 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report7 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Con Hydro</td>
						<td>
							<select name="selConHydro">
								<option value="d"<?= (isset($companyId) && $ConHydro == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $ConHydro == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $ConHydro == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $ConHydro == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 8</td>
						<td>
							<select name="selReport8">
								<option value="d"<?= (isset($companyId) && $Report8 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report8 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report8 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report8 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Con Vacuum</td>
						<td>
							<select name="selConVacuum">
								<option value="d"<?= (isset($companyId) && $ConVacuum == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $ConVacuum == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $ConVacuum == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $ConVacuum == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 9</td>
						<td>
							<select name="selReport9">
								<option value="d"<?= (isset($companyId) && $Report9 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report9 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report9 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report9 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Survey Access</td>
						<td>
							<select name="selSurveyAccess">
								<option value="d"<?= (isset($companyId) && $SurveyAccess == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $SurveyAccess == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $SurveyAccess == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $SurveyAccess == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 10</td>
						<td>
							<select name="selReport10">
								<option value="d"<?= (isset($companyId) && $Report10 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report10 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report10 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report10 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Con Water Cross</td>
						<td>
							<select name="selConWaterCross">
								<option value="d"<?= (isset($companyId) && $ConWaterCross == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $ConWaterCross == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $ConWaterCross == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $ConWaterCross == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 11</td>
						<td>
							<select name="selReport11">
								<option value="d"<?= (isset($companyId) && $Report11 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report11 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report11 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report11 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Water</td>
						<td>
							<select name="selWater">
								<option value="d"<?= (isset($companyId) && $Water == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Water == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Water == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Water == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 12</td>
						<td>
							<select name="selReport12">
								<option value="d"<?= (isset($companyId) && $Report12 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report12 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report12 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report12 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Vacuum</td>
						<td>
							<select name="selVacuum">
								<option value="d"<?= (isset($companyId) && $Vacuum == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Vacuum == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Vacuum == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Vacuum == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 13</td>
						<td>
							<select name="selReport13">
								<option value="d"<?= (isset($companyId) && $Report13 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report13 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report13 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report13 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Well Con Update</td>
						<td>
							<select name="selWellConUpdate">
								<option value="d"<?= (isset($companyId) && $WellConUpdate == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $WellConUpdate == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $WellConUpdate == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $WellConUpdate == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 14</td>
						<td>
							<select name="selReport14">
								<option value="d"<?= (isset($companyId) && $Report14 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report14 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report14 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report14 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Daily Mud Module</td>
						<td>
							<select name="selDailyMudModule">
								<option value="d"<?= (isset($companyId) && $DailyMudModule == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $DailyMudModule == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $DailyMudModule == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $DailyMudModule == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 15</td>
						<td>
							<select name="selReport15">
								<option value="d"<?= (isset($companyId) && $Report15 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report15 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report15 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report15 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Mud Products</td>
						<td>
							<select name="selMudProducts">
								<option value="d"<?= (isset($companyId) && $MudProducts == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $MudProducts == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $MudProducts == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $MudProducts == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 16</td>
						<td>
							<select name="selReport16">
								<option value="d"<?= (isset($companyId) && $Report16 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report16 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report16 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report16 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Mud Product List</td>
						<td>
							<select name="selMudProductList">
								<option value="d"<?= (isset($companyId) && $MudProductList == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $MudProductList == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $MudProductList == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $MudProductList == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 17</td>
						<td>
							<select name="selReport17">
								<option value="d"<?= (isset($companyId) && $Report17 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report17 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report17 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report17 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Survey Module</td>
						<td>
							<select name="selSurveyModule">
								<option value="d"<?= (isset($companyId) && $SurveyModule == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $SurveyModule == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $SurveyModule == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $SurveyModule == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 18</td>
						<td>
							<select name="selReport18">
								<option value="d"<?= (isset($companyId) && $Report18 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report18 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report18 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report18 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Rig Mat Location</td>
						<td>
							<select name="selRigMatLocation">
								<option value="d"<?= (isset($companyId) && $RigMatLocation == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $RigMatLocation == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $RigMatLocation == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $RigMatLocation == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 19</td>
						<td>
							<select name="selReport19">
								<option value="d"<?= (isset($companyId) && $Report19 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report19 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report19 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report19 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Temp Log Deck</td>
						<td>
							<select name="selTempLogDeck">
								<option value="d"<?= (isset($companyId) && $TempLogDeck == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $TempLogDeck == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $TempLogDeck == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $TempLogDeck == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 20</td>
						<td>
							<select name="selReport20">
								<option value="d"<?= (isset($companyId) && $Report20 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report20 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report20 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report20 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Wells</td>
						<td>
							<select name="selWells">
								<option value="d"<?= (isset($companyId) && $Wells == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Wells == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Wells == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Wells == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
						<td>Report 21</td>
						<td>
							<select name="selReport21">
								<option value="d"<?= (isset($companyId) && $Report21 == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report21 == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report21 == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report21 == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>Report</td>
						<td>
							<select name="selReport">
								<option value="d"<?= (isset($companyId) && $Report == "d") ? " selected" : "" ?>>d</option>
								<option value="x"<?= (isset($companyId) && $Report == "x") ? " selected" : "" ?>>x</option>
								<option value="o"<?= (isset($companyId) && $Report == "o") ? " selected" : "" ?>>o</option>
								<option value="w"<?= (isset($companyId) && $Report == "w") ? " selected" : "" ?>>w</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td width="100%"></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Save"></td>
			<td width="100%"></td>
		</tr>
	</table>
</form>
<? include ('footer.inc'); ?>
</body>
</html>