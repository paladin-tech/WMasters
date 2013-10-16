<?
require("adodb/adodb.inc.php");
require("infosystem.php");
//$infosystem->debug = true;

session_start();
$accessLead = ($_SESSION['levelid']==2)?true:false;

list($DailySafety, $DailyInputs, $SupervisorsDaily, $ConDaily, $SurveyAccess, $DrillingGeotech, $ConPerWell, $ConstContr, $ConHydro, $ConVacuum, $ConWaterCross, $Water, $Vacuum, $WellConUpdate, $Report1, $Report2, $Report4, $Report5, $Report6, $Report7, $Report8, $Report9, $Report10, $Report11, $Report12, $Report) = $infosystem->Execute("SELECT `DailySafety`, `DailyInputs`, `SupervisorsDaily`, `ConDaily`, `SurveyAccess`, `DrillingGeotech`, `ConPerWell`, `ConstContr`, `ConHydro`, `ConVacuum`,`ConWaterCross`, `Water`, `Vacuum`, `WellConUpdate`, `Report1`, `Report2`, `Report4`, `Report5`, `Report6`, `Report7`, `Report8`, `Report9`, `Report10`, `Report11`, `Report12`, `Report` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
?>
<table border="1" style="text-align: left; width: 460px; height: 208px; margin-left: auto; margin-right: auto;" cellpadding="2" cellspacing="2">
  <tbody>
    <tr align="center">
      <td colspan="2" rowspan="1" style="vertical-align: top;"><big><big>MAIN PAGE</big></big></td>
    </tr>
    <tr align="center">
      <td colspan="2" rowspan="1" style="vertical-align: top;">Use appropriate link for Data Input / Report<br>
      </td>
    </tr><?
	if($DailySafety!="d") { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><a href="DailySafety<?=($accessLead)?"Lead":""?>.php" target="_top">Daily Safety</a></td>
      <td style="vertical-align: top; text-align: center;">Input time from 19:00-04:59<br>
      </td>
    </tr><?
	}
	if($SupervisorsDaily!="d") { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><a href="DailySupervisors.php" target="_top">Daily Supervisors</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 19:00-23:59<br>
      </td>
    </tr><?
	}
	if($DailyInputs!="d") { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><a href="DailyInputs.php" target="_top">Daily Inputs</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 19:00-23:59<br>
      </td>
    </tr><?
	}
	if($SurveyAccess!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="SurveyModule.php" target="_top">Survey Module</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
   	}
	if($DrillingGeotech!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="DrillingGeotech.php" target="_top">Drilling Geotech Module</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
   	}
	if($ConDaily!="d") { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><a href="ConDaily.php" target="_top">Construction Daily</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 20:00-19:50<br>
      </td>
    </tr><?
	}
	if($ConPerWell!="d") { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><a href="ConPerWell.php" target="_top">Construction Per Well</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:59<br>
      </td>
    </tr><?
	}
		if($ConstContr!="d") { ?>
	    <tr>
	      <td style="vertical-align: top; text-align: center;"><a href="ConstContr.php" target="_top">Construction Contractor</a><br>
	      </td>
	      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:59<br>
	      </td>
	    </tr><?
	}
	if($ConHydro!="d") { ?>
   	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="ConHydro.php" target="_top">Construction Hydro</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
	}
	if($ConVacuum!="d") { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><a href="ConVacuum.php" target="_top">Construction Vacuum</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
	}
	if($ConWaterCross!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="ConWaterCross.php" target="_top">Construction Water Cross</a><br></td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
	}
	if($Water!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Water.php" target="_top">Water</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
	}
	if($Vacuum!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Vacuum.php" target="_top">Vacuum</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Input time from 00:00-23:45<br>
      </td>
    </tr><?
	}
		if($WellConUpdate!="d") { ?>
	<tr>
	  <td style="vertical-align: top; text-align: center;"><a href="WellConUpdate.php" target="_top">Main Board Data Import</a><br>
	  </td>
	  <td style="vertical-align: top; text-align: center;">Select csv file for upload<br>
	  </td>
	</tr><?
	}
	if($Report1!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report1.php" target="_top">Report #1 - Main Construction Report</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report2!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report2.php" target="_top">Report #2 - Water Usage Summary</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report4!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report4.php" target="_top">Report #4 - Water Crossing Summary</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report5!="d") { ?>
	<tr>
	  <td style="vertical-align: top; text-align: center;"><a href="Report5.php" target="_top">Report #5 - Water Summary by Dates</a><br>
	  </td>
	  <td style="vertical-align: top; text-align: center;">Click to submit<br>
	  </td>
	</tr><?
	}
	if($Report6!="d") { ?>
	<tr>
	  <td style="vertical-align: top; text-align: center;"><a href="Report6.php" target="_top">Report #6 - Daily Water Summary</a><br>
	  </td>
	  <td style="vertical-align: top; text-align: center;">Enter the date in the form<br>
	  </td>
	</tr><?
	}
	if($Report7!="d") { ?>
	<tr>
	  <td style="vertical-align: top; text-align: center;"><a href="Report7.php" target="_top">Report #7 - Sump Summary by Dates</a><br>
	  </td>
	  <td style="vertical-align: top; text-align: center;">Click to submit<br>
	  </td>
	</tr><?
	}
	if($Report8!="d") { ?>
	<tr>
	  <td style="vertical-align: top; text-align: center;"><a href="Report8.php" target="_top">Report #8 - Daily Sump Summary</a><br>
	  </td>
	  <td style="vertical-align: top; text-align: center;">Enter the date in the form<br>
	  </td>
	</tr><?
	}
	if($Report9!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report9.php" target="_top">Report #9 - Sump Usage Summary</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report10!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report10.php" target="_top">Report #10 - Drilling Summary</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report11!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report11.php" target="_top">Report #11 - Survey Summary</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report12!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="Report12.php" target="_top">Report #12 - Construction Summary</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	if($Report!="d") { ?>
	<tr>
      <td style="vertical-align: top; text-align: center;"><a href="DailyReports.php" target="_top">AFP Progress And Activity Report</a><br>
      </td>
      <td style="vertical-align: top; text-align: center;">Click to submit<br>
      </td>
    </tr><?
	}
	 ?>
  </tbody>
</table>