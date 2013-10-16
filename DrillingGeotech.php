<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");
include("xajax_f.php");

// Debugging info here can be useful if necessary
// $infosystem->debug = true;

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");

$user = $_SESSION['username'];

list($DrillingGeotechLvl) = $infosystem->Execute("SELECT `DrillingGeotech` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($DrillingGeotechLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($DrillingGeotechLvl=="o")?" disabled=\"disabled\"":"";

// Dynamic SQL creation and execution
$dbFields = array(); $dbFieldValues = array();
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) {
		if($key!="submit") array_push($dbFields, "`{$key}`");
		if($value!="Submit") array_push($dbFieldValues, "'{$value}'");
	}
	array_push($dbFields, "`last_changed_by`");
	array_push($dbFieldValues, "'{$user}'");

	$SQL = "UPDATE `wells_construction` SET ";
	foreach($dbFields as $f => $v) $SQL .= "{$v} = ".$dbFieldValues[$f].", ";
	$SQL = substr($SQL, 0, -2);
	$SQL .= " WHERE `well_id` = '{$well_id}'";

	$infosystem->Execute($SQL);
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Drilling Geotech', '{$user}')");

	include("confirm.html");
	ob_end_flush();
} else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Drilling Geotech Module - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
<? $xajax->printJavascript(); ?>
<script src="CalendarPopupCombined.js"></script>
<script language="javascript">
function setSelectedIndex(s1, v) {
	s = document.getElementById(s1);
    for ( var i = 0; i < s.options.length; i++ ) {
        if ( s.options[i].value == v ) {
            s.options[i].selected = true;
            return;
        }
    }
}
</script>
</head>

<body><? include ('header.inc');?>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">

  <tr>
    <td colspan="2">&nbsp;</td>
    <td rowspan="12">&nbsp;</td>
    <td></td>
    <td>Prestake Completed</td>
    <td>As Built</td>
    <td>A</td>
    <td>B</td>
    <td>C</td>
    <td>D</td>
    <td>O1</td>
    <td>O2</td>
  </tr>

  <tr>
    <td>Well ID (Lease ID)</td>
    <td>
      <select name="well_id" id="well_id" onchange="xajax_GetWellInfoDrillingGeotech(this.value)"<?=$readOnly?>>
      	<option value=""></option><?
		while(!$rsWellLicence->EOF) {
		list($y_well_id) = $rsWellLicence->fields; ?>
        <option value="<?=$y_well_id?>"<?=($updateForm&&$x_well_id==$x_well_id)?" selected":""?>><?=$y_well_id?></option><?
		$rsWellLicence->MoveNext();
		} ?>
      </select></td>
    <td>Survey Request</td>
	<script language="javascript" ID="jscal1xx1">
		var cal1xx1 = new CalendarPopup("testdiv1");
		cal1xx1.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_BASE" id="need_as_built_BASE" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx1.select(document.forms[0].need_as_built_BASE,'anchor1xx1','yyyy-MM-dd')" name="anchor1xx1" id="anchor1xx1"></td>
	<script language="javascript" ID="jscal1xx2">
		var cal1xx2 = new CalendarPopup("testdiv1");
		cal1xx2.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_A" id="need_as_built_A" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx2.select(document.forms[0].need_as_built_A,'anchor1xx2','yyyy-MM-dd')" name="anchor1xx2" id="anchor1xx2"></td>
  	<script language="javascript" ID="jscal1xx3">
		var cal1xx3 = new CalendarPopup("testdiv1");
		cal1xx3.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_B" id="need_as_built_B" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx3.select(document.forms[0].need_as_built_B,'anchor1xx3','yyyy-MM-dd')" name="anchor1xx3" id="anchor1xx3"></td>
  	<script language="javascript" ID="jscal1xx4">
		var cal1xx4 = new CalendarPopup("testdiv1");
		cal1xx4.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_C" id="need_as_built_C" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx4.select(document.forms[0].need_as_built_C,'anchor1xx4','yyyy-MM-dd')" name="anchor1xx4" id="anchor1xx4"></td>
  	<script language="javascript" ID="jscal1xx5">
		var cal1xx5 = new CalendarPopup("testdiv1");
		cal1xx5.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_D" id="need_as_built_D" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx5.select(document.forms[0].need_as_built_D,'anchor1xx5','yyyy-MM-dd')" name="anchor1xx5" id="anchor1xx5"></td>
  	<script language="javascript" ID="jscal1xx6">
		var cal1xx6 = new CalendarPopup("testdiv1");
		cal1xx6.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_O1" id="need_as_built_O1" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx6.select(document.forms[0].need_as_built_O1,'anchor1xx6','yyyy-MM-dd')" name="anchor1xx6" id="anchor1xx6"></td>
  	<script language="javascript" ID="jscal1xx7">
		var cal1xx7 = new CalendarPopup("testdiv1");
		cal1xx7.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_O2" id="need_as_built_O2" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx7.select(document.forms[0].need_as_built_O2,'anchor1xx7','yyyy-MM-dd')" name="anchor1xx7" id="anchor1xx7"></td>
  	<script language="javascript" ID="jscal1xx8">
		var cal1xx8 = new CalendarPopup("testdiv1");
		cal1xx8.showNavigationDropdowns();
    </script>
    <td><input type="text" name="need_as_built_O3" id="need_as_built_O3" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx8.select(document.forms[0].need_as_built_O3,'anchor1xx8','yyyy-MM-dd')" name="anchor1xx8" id="anchor1xx8"></td>
  </tr>

  <tr>
    <td>Construction Completed</td>
    <td id="tdReady_for_drilling">&nbsp;</td>
    <td>As Built Date</td>
    <td id="tdDate_BASE">&nbsp;</td>
    <td id="tdDate_A">&nbsp;</td>
    <td id="tdDate_B">&nbsp;</td>
    <td id="tdDate_C">&nbsp;</td>
    <td id="tdDate_D">&nbsp;</td>
    <td id="tdDate_O1">&nbsp;</td>
    <td id="tdDate_O2">&nbsp;</td>
    <td id="tdDate_O3">&nbsp;</td>
  </tr>

  <tr>
    <td>Rig Ready</td>
 	<script language="javascript" ID="jscal1xx9">
		var cal1xx9 = new CalendarPopup("testdiv1");
		cal1xx9.showNavigationDropdowns();
    </script>
    <td><input type="text" name="approved_by_drilling" id="approved_by_drilling" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx9.select(document.forms[0].approved_by_drilling,'anchor1xx9','yyyy-MM-dd')" name="anchor1xx9" id="anchor1xx9"></td>
    <td>As Built GPS N</td>
    <td id="tdGps_north_BASE">&nbsp;</td>
    <td id="tdGps_north_A">&nbsp;</td>
    <td id="tdGps_north_B">&nbsp;</td>
    <td id="tdGps_north_C">&nbsp;</td>
    <td id="tdGps_north_D">&nbsp;</td>
    <td id="tdGps_north_O1">&nbsp;</td>
    <td id="tdGps_north_O2">&nbsp;</td>
    <td id="tdGps_north_O3">&nbsp;</td>
  </tr>

  <tr>
    <td>Last Spud</td>
    <td id="tdSpud">&nbsp;</td>
    <td>As Built GPS E</td>
    <td id="tdGps_east_BASE">&nbsp;</td>
    <td id="tdGps_east_A">&nbsp;</td>
    <td id="tdGps_east_B">&nbsp;</td>
    <td id="tdGps_east_C">&nbsp;</td>
    <td id="tdGps_east_D">&nbsp;</td>
    <td id="tdGps_east_O1">&nbsp;</td>
    <td id="tdGps_east_O2">&nbsp;</td>
    <td id="tdGps_east_O3">&nbsp;</td>
  </tr>

  <tr>
    <td>Last RR</td>
    <td id="tdRR">&nbsp;</td>
    <td>EL</td>
    <td id="tdEL_BASE">&nbsp;</td>
    <td id="tdEL_A">&nbsp;</td>
    <td id="tdEL_B">&nbsp;</td>
    <td id="tdEL_C">&nbsp;</td>
    <td id="tdEL_D">&nbsp;</td>
    <td id="tdEL_O1">&nbsp;</td>
    <td id="tdEL_O2">&nbsp;</td>
    <td id="tdEL_O3">&nbsp;</td>
  </tr>

  <tr>
    <td>Last Logged</td>
    <td id="tdLogged">&nbsp;</td>
    <td>GL</td>
    <td id="tdGL_BASE">&nbsp;</td>
    <td id="tdGL_A">&nbsp;</td>
    <td id="tdGL_B">&nbsp;</td>
    <td id="tdGL_C">&nbsp;</td>
    <td id="tdGL_D">&nbsp;</td>
    <td id="tdGL_O1">&nbsp;</td>
    <td id="tdGL_O2">&nbsp;</td>
    <td id="tdGL_O3">&nbsp;</td>
  </tr>

  <tr>
    <td>No Abandonment Required</td>
    <td id="tdNoAbandonmentRequired">&nbsp;</td>
    <td>Other Description</td>
    <td colspan="6">&nbsp;</td>
    <td><textarea name="desc_O2" id="desc_O2"<?=$readOnly?>><?=($updateForm)?$x_desc_O2:""?></textarea></td>
    <td><textarea name="desc_O3" id="desc_O3"<?=$readOnly?>><?=($updateForm)?$x_desc_O3:""?></textarea></td>
  </tr>

  <tr>
    <td>Permanent Installation</td>
    <td id="tdPermanentInstallation">&nbsp;</td>
    <td colspan="9" rowspan="3">&nbsp;</td>
  </tr>

  <tr>
    <td>Last Abandonment</td>
    <td id="tdAbandonment">&nbsp;</td>
  </tr>

  <tr>
    <td>Lease Release</td>
 	<script language="javascript" ID="jscal1xx10">
		var cal1xx10 = new CalendarPopup("testdiv1");
		cal1xx10.showNavigationDropdowns();
    </script>
    <td><input type="text" name="ready_roll_back" id="ready_roll_back" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx10.select(document.forms[0].ready_roll_back,'anchor1xx10','yyyy-MM-dd')" name="anchor1xx10" id="anchor1xx10"></td>
  </tr>

  <tr>
    <td>Rolled Back</td>
    <td id="tdRollBack">&nbsp;</td>
    <td colspan="8">&nbsp;</td>
    <td><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></td>
  </tr>
</table>
</form>
<div id="testdiv1" style="position: absolute; visibility: hidden; background-color: white; left: 42px; top: 859px;"><table class="cpBorder" borderwidth="1" border="1" cellpadding="1" cellspacing="0" width="144">
<tbody><tr><td align="CENTER">
<center>
<table borderwidth="0" border="0" cellpadding="0" cellspacing="0" width="144"><tbody><tr>
<td class="cpMonthNavigation" colspan="3" width="78"><select class="cpMonthNavigation" name="cpMonth" onmouseup="CP_stop(event)" onChange="CP_refreshCalendar(2,this.options[this.selectedIndex].value-0,2010);"><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11" selected="selected">November</option><option value="12">December</option></select></td><td class="cpMonthNavigation" width="10">&nbsp;</td><td class="cpYearNavigation" colspan="3" width="56"><select class="cpYearNavigation" name="cpYear" onmouseup="CP_stop(event)" onChange="CP_refreshCalendar(2,11,this.options[this.selectedIndex].value-0);"><option value="2008">2008</option><option value="2009">2009</option><option value="2010" selected="selected">2010</option><option value="2011">2011</option><option value="2012">2012</option></select></td></tr></tbody></table>
<table align="CENTER" border="0" cellpadding="1" cellspacing="0" width="120">
<tbody><tr>
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
</tr><tr>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,7);CP_hideCalendar('2');" class="cpCurrentMonthDate">7</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,8);CP_hideCalendar('2');" class="cpCurrentMonthDate">8</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,9);CP_hideCalendar('2');" class="cpCurrentMonthDate">9</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,10);CP_hideCalendar('2');" class="cpCurrentMonthDate">10</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,11);CP_hideCalendar('2');" class="cpCurrentMonthDate">11</a></td>
	<td class="cpCurrentDate"><a href="javascript:CP_tmpReturnFunction(2010,11,12);CP_hideCalendar('2');" class="cpCurrentDate">12</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,13);CP_hideCalendar('2');" class="cpCurrentMonthDate">13</a></td>
</tr><tr>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,14);CP_hideCalendar('2');" class="cpCurrentMonthDate">14</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,15);CP_hideCalendar('2');" class="cpCurrentMonthDate">15</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,16);CP_hideCalendar('2');" class="cpCurrentMonthDate">16</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,17);CP_hideCalendar('2');" class="cpCurrentMonthDate">17</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,18);CP_hideCalendar('2');" class="cpCurrentMonthDate">18</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,19);CP_hideCalendar('2');" class="cpCurrentMonthDate">19</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,20);CP_hideCalendar('2');" class="cpCurrentMonthDate">20</a></td>
</tr><tr>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,21);CP_hideCalendar('2');" class="cpCurrentMonthDate">21</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,22);CP_hideCalendar('2');" class="cpCurrentMonthDate">22</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,23);CP_hideCalendar('2');" class="cpCurrentMonthDate">23</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,24);CP_hideCalendar('2');" class="cpCurrentMonthDate">24</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,25);CP_hideCalendar('2');" class="cpCurrentMonthDate">25</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,26);CP_hideCalendar('2');" class="cpCurrentMonthDate">26</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,27);CP_hideCalendar('2');" class="cpCurrentMonthDate">27</a></td>
</tr><tr>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,28);CP_hideCalendar('2');" class="cpCurrentMonthDate">28</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,29);CP_hideCalendar('2');" class="cpCurrentMonthDate">29</a></td>
	<td class="cpCurrentMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,11,30);CP_hideCalendar('2');" class="cpCurrentMonthDate">30</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,1);CP_hideCalendar('2');" class="cpOtherMonthDate">1</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,2);CP_hideCalendar('2');" class="cpOtherMonthDate">2</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,3);CP_hideCalendar('2');" class="cpOtherMonthDate">3</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,4);CP_hideCalendar('2');" class="cpOtherMonthDate">4</a></td>
</tr><tr>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,5);CP_hideCalendar('2');" class="cpOtherMonthDate">5</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,6);CP_hideCalendar('2');" class="cpOtherMonthDate">6</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,7);CP_hideCalendar('2');" class="cpOtherMonthDate">7</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,8);CP_hideCalendar('2');" class="cpOtherMonthDate">8</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,9);CP_hideCalendar('2');" class="cpOtherMonthDate">9</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,10);CP_hideCalendar('2');" class="cpOtherMonthDate">10</a></td>
	<td class="cpOtherMonthDate"><a href="javascript:CP_tmpReturnFunction(2010,12,11);CP_hideCalendar('2');" class="cpOtherMonthDate">11</a></td>
</tr><tr>
	<td colspan="7" class="cpTodayText" align="CENTER">
		<a class="cpTodayText" href="javascript:CP_tmpReturnFunction('2010','11','12');CP_hideCalendar('2');">Today</a>
		<br>
	</td></tr></tbody></table></center></td></tr></tbody></table>
</div>

<? include ('footer.inc'); ?>
</body>
</html><?
} ?>