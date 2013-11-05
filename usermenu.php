<?
include("sessionCheck.php");
include("db.php");

session_start();
$accessLead = ($_SESSION['levelid']==2)?true:false;

list($DailySafety, $DailyInputs, $SupervisorsDaily, $ConDaily, $SurveyAccess, $DrillingGeotech, $ConPerWell, $ConstContr, $ConHydro, $ConVacuum, $ConWaterCross, $Water, $Vacuum, $WellConUpdate, $Report1, $Report2, $Report4, $Report5, $Report6, $Report7, $Report8, $Report9, $Report10, $Report11, $Report12, $Report) = $infosystem->Execute("SELECT `DailySafety`, `DailyInputs`, `SupervisorsDaily`, `ConDaily`, `SurveyAccess`, `DrillingGeotech`, `ConPerWell`, `ConstContr`, `ConHydro`, `ConVacuum`,`ConWaterCross`, `Water`, `Vacuum`, `WellConUpdate`, `Report1`, `Report2`, `Report4`, `Report5`, `Report6`, `Report7`, `Report8`, `Report9`, `Report10`, `Report11`, `Report12`, `Report` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
?>
<style>
td {
	white-space: nowrap;
	text-align: left;
	background: none;
}
</style>
<table border="0" style="text-align: left; margin: auto;" cellpadding="3" cellspacing="0">
	<tbody>
		<tr align="center">
			<th colspan="2" style="font-size: larger;">MAIN PAGE</th>
		</tr>
		<tr align="center">
			<th colspan="2">Use appropriate link for Data Input / Report</th>
		</tr>
		<tr valign="top">
			<td>
				<table border="1" cellpadding="3" cellspacing="0">
					<?
					if($DailySafety!="d") { ?>
					<tr>
						<td><a href="DailySafety<?=($accessLead)?"Lead":""?>.php" target="_top">Daily Safety</a></td>
						<td>Input time from 19:00-04:59</td>
					</tr>
					<?
					}
					if($SupervisorsDaily!="d") { ?>
					<tr>
						<td><a href="DailySupervisors.php" target="_top">Daily Supervisors</a></td>
						<td>Input time from 19:00-23:59</td>
					</tr>
					<?
					}
					if($DailyInputs!="d") { ?>
					<tr>
						<td><a href="DailyInputs.php" target="_top">Daily Inputs</a></td>
						<td>Input time from 19:00-23:59</td>
					</tr>
					<?
					}
					if($SurveyAccess!="d") { ?>
					<tr>
						<td><a href="SurveyModule.php" target="_top">Survey Module</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($DrillingGeotech!="d") { ?>
					<tr>
						<td><a href="DrillingGeotech.php" target="_top">Drilling Geotech Module</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($ConDaily!="d") { ?>
					<tr>
						<td><a href="ConDaily.php" target="_top">Construction Daily</a></td>
						<td>Input time from 20:00-19:50</td>
					</tr>
					<?
					}
					if($ConPerWell!="d") { ?>
					<tr>
						<td><a href="ConPerWell.php" target="_top">Construction Per Well</a></td>
						<td>Input time from 00:00-23:59</td>
					</tr>
					<?
					}
					if($ConstContr!="d") { ?>
					<tr>
						<td><a href="ConstContr.php" target="_top">Construction Contractor</a></td>
						<td>Input time from 00:00-23:59</td>
					</tr>
					<?
					}
					if($ConHydro!="d") { ?>
					<tr>
						<td><a href="ConHydro.php" target="_top">Construction Hydro</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($ConVacuum!="d") { ?>
					<tr>
						<td><a href="ConVacuum.php" target="_top">Construction Vacuum</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($ConWaterCross!="d") { ?>
					<tr>
						<td><a href="ConWaterCross.php" target="_top">Construction Water Cross</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($Water!="d") { ?>
					<tr>
						<td><a href="Water.php" target="_top">Water</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($MudProducts!="d") { ?>
					<tr>
						<td><a href="MudProducts.php" target="_top">Well and Mud Products</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($DailyMudModule!="d") { ?>
					<tr>
						<td><a href="DailyMudModule.php" target="_top">Daily Mud Module</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($TempLogDeck!="d") { ?>
					<tr>
						<td><a href="TempLogDeck.php" target="_top">Temp Log Deck</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($RigMatLocation!="d") { ?>
					<tr>
						<td><a href="RigMatLocation.php" target="_top">Rig Mat Location</a></td>
						<td>Input time from 00:00-23:45</td>
					</tr>
					<?
					}
					if($WellConUpdate!="d") { ?>
					<tr>
						<td><a href="WellConUpdate.php" target="_top">Main Board Data Import</a></td>
						<td>Select csv file for upload</td>
					</tr><?
					}
					?>
				</table>
			</td>
			<td>
				<table border="1" cellpadding="3" cellspacing="0">
					<?
					if($Report1!="d") { ?>
					<tr>
						<td><a href="Report1.php" target="_top">Report #1 - Main Construction Report</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report2!="d") { ?>
					<tr>
						<td><a href="Report2.php" target="_top">Report #2 - Water Usage Summary</a></td>
						<td>Click to submit</td>
					</tr>
					<?
					}
					if($Report4!="d") { ?>
					<tr>
						<td><a href="Report4.php" target="_top">Report #4 - Water Crossing Summary</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report5!="d") { ?>
					<tr>
						<td><a href="Report5.php" target="_top">Report #5 - Water Summary by Dates</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report6!="d") { ?>
					<tr>
						<td><a href="Report6.php" target="_top">Report #6 - Daily Water Summary</a></td>
						<td>Enter the date in the form</td>
					</tr><?
					}
					if($Report7!="d") { ?>
					<tr>
						<td><a href="Report7.php" target="_top">Report #7 - Sump Summary by Dates</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report8!="d") { ?>
					<tr>
						<td><a href="Report8.php" target="_top">Report #8 - Daily Sump Summary</a></td>
						<td>Enter the date in the form</td>
					</tr><?
					}
					if($Report9!="d") { ?>
					<tr>
						<td><a href="Report9.php" target="_top">Report #9 - Sump Usage Summary</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report10!="d") { ?>
					<tr>
						<td><a href="Report10.php" target="_top">Report #10 - Drilling Summary</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report11!="d") { ?>
					<tr>
						<td><a href="Report11.php" target="_top">Report #11 - Survey Summary</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report12!="d") { ?>
					<tr>
						<td><a href="Report12.php" target="_top">Report #12 - Construction Summary</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report13!="d") { ?>
					<tr>
						<td><a href="Report13.php" target="_top">Report #13 - DSR</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report14!="d") { ?>
					<tr>
						<td><a href="Report13.php" target="_top">Report #14 - Construction Requests</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report15!="d") { ?>
					<tr>
						<td><a href="Report15.php" target="_top">Report #15 - DSR Report</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report16!="d") { ?>
					<tr>
						<td><a href="Report16.php" target="_top">Report #16 - Mud Report per days</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report17!="d") { ?>
					<tr>
						<td><a href="Report17.php" target="_top">Report #17 - Mud Report per Wells</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report17!="d") { ?>
					<tr>
						<td><a href="Report17a.php" target="_top">Report #17 - Consumables for all the wells</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report18!="d") { ?>
					<tr>
						<td><a href="Report18.php" target="_top">Report #18 - Consumables for all the wells</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report19!="d") { ?>
					<tr>
						<td><a href="Report18a.php" target="_top">Report #18 - Consumables per wells</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report20!="d") { ?>
					<tr>
						<td><a href="Report20.php" target="_top">Report #20 - Temporary Log Deck</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report21!="d") { ?>
					<tr>
						<td><a href="Report21.php" target="_top">Report #21 - Rig Mat Location</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					if($Report!="d") { ?>
					<tr>
						<td><a href="DailyReports.php" target="_top">AFP Progress And Activity Report</a></td>
						<td>Click to submit</td>
					</tr><?
					}
					?>
				</table>
			</td>
		</tr>
	</tbody>
</table>