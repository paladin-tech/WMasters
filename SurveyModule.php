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

list($SurveyAccessLvl) = $infosystem->Execute("SELECT `SurveyAccess` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($SurveyAccessLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($SurveyAccessLvl=="o")?" disabled=\"disabled\"":"";

// Dynamic SQL creation and execution
$dbFields = array();
$dbFieldValues = array();
if(isset($_POST['submit'])) {
    foreach($_POST as $key => $value) {
        if($key!="submit") array_push($dbFields, "`{$key}`");
	if($value!="Submit") array_push($dbFieldValues, "'{$value}'");
    }
    array_push($dbFields, "`last_changed_by`");
    array_push($dbFieldValues, "'{$user}'");

    list($x_dateBase, $x_northBase, $x_eastBase) = $infosystem->Execute("SELECT `date_BASE`, `gps_north_BASE`, `gps_east_BASE` FROM `wells_construction` WHERE `well_id` = '{$well_id}'")->fields;

	$SQL = "UPDATE `wells_construction` SET ";
	foreach($dbFields as $f => $v) $SQL .= "{$v} = ".$dbFieldValues[$f].", ";
	$SQL = substr($SQL, 0, -2);
	$SQL .= " WHERE `well_id` = '{$well_id}'";
	$infosystem->Execute($SQL);
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Survey Module', '{$user}')");
	include("confirm.html");

    ob_end_flush();
} else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Survey Module - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
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

	function UpdateDelta(p1, p2, p3) {
		cell_source = 'tdDesigned' + p1;
	    desN = document.getElementById(cell_source).innerHTML;
	    cell = 'tdDelta' + p1 + p2;
	    delta = Math.abs(desN-p3);
	    if(delta>10) delta +=  ' <font color="red"><b>!!</b></font>';
	    document.getElementById(cell).innerHTML = delta;
	}
	</script>
	<script type="text/javascript">
	<!--
	function popup(mylink, windowname) {
		if (! window.focus)return true;
		var href;
		if (typeof(mylink) == 'string')
		   href=mylink;
		else
		   href=mylink.href;
		window.open(href, windowname, 'width=400,height=200,scrollbars=yes');
		return false;
	}
	//-->
	</script>
</head>

<body><? include ('header.inc');?>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<table cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">

<tr>
  <td colspan="2">&nbsp;</td>
  <td rowspan="10">&nbsp;</td>
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
    <select name="well_id" id="well_id" onchange="xajax_GetWellInfoSurvey(this.value)"<?=$readOnly?>>
    <option value=""></option><?
    while(!$rsWellLicence->EOF) {
      list($y_well_id) = $rsWellLicence->fields; ?>
      <option value="<?=$y_well_id?>"<?=($updateForm&&$x_well_id==$x_well_id)?" selected":""?>><?=$y_well_id?></option><?
      $rsWellLicence->MoveNext();
    } ?>
    </select></td>
  <td>Survey Request</td>
  <td id="tdNeed_as_built_BASE">&nbsp;</td>
  <td id="tdNeed_as_built_A">&nbsp;</td>
  <td id="tdNeed_as_built_B">&nbsp;</td>
  <td id="tdNeed_as_built_C">&nbsp;</td>
  <td id="tdNeed_as_built_D">&nbsp;</td>
  <td id="tdNeed_as_built_O1">&nbsp;</td>
  <td id="tdNeed_as_built_O2">&nbsp;</td>
  <td id="tdNeed_as_built_O3">&nbsp;</td>
</tr>

<tr>
  <td>Flag Requested</td>
  <td id="tdFlagRequested">&nbsp;</td>
  <td>As Built Date</td>
  <script language="javascript" ID="jscal1xx1">
      var cal1xx1 = new CalendarPopup("testdiv1");
	  cal1xx1.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_BASE" id="date_BASE" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx1.select(document.forms[0].date_BASE,'anchor1xx1','yyyy-MM-dd')" name="anchor1xx1" id="anchor1xx1"></td>
  <script language="javascript" ID="jscal1xx2">
      var cal1xx2 = new CalendarPopup("testdiv1");
	  cal1xx2.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_A" id="date_A" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx2.select(document.forms[0].date_A,'anchor1xx2','yyyy-MM-dd')" name="anchor1xx2" id="anchor1xx2"></td>
  <script language="javascript" ID="jscal1xx3">
      var cal1xx3 = new CalendarPopup("testdiv1");
	  cal1xx3.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_B" id="date_B" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx3.select(document.forms[0].date_B,'anchor1xx3','yyyy-MM-dd')" name="anchor1xx3" id="anchor1xx3"></td>
  <script language="javascript" ID="jscal1xx4">
      var cal1xx4 = new CalendarPopup("testdiv1");
	  cal1xx4.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_C" id="date_C" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx4.select(document.forms[0].date_C,'anchor1xx4','yyyy-MM-dd')" name="anchor1xx4" id="anchor1xx4"></td>
  <script language="javascript" ID="jscal1xx5">
      var cal1xx5 = new CalendarPopup("testdiv1");
	  cal1xx5.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_D" id="date_D" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx5.select(document.forms[0].date_D,'anchor1xx5','yyyy-MM-dd')" name="anchor1xx5" id="anchor1xx5"></td>
  <script language="javascript" ID="jscal1xx6">
      var cal1xx6 = new CalendarPopup("testdiv1");
	  cal1xx6.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_O1" id="date_O1" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx6.select(document.forms[0].date_O1,'anchor1xx6','yyyy-MM-dd')" name="anchor1xx6" id="anchor1xx6"></td>
  <script language="javascript" ID="jscal1xx7">
      var cal1xx7 = new CalendarPopup("testdiv1");
	  cal1xx7.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_O2" id="date_O2" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx7.select(document.forms[0].date_O2,'anchor1xx7','yyyy-MM-dd')" name="anchor1xx7" id="anchor1xx7"></td>
  <script language="javascript" ID="jscal1xx8">
      var cal1xx8 = new CalendarPopup("testdiv1");
	  cal1xx8.showNavigationDropdowns();
  </script>
  <td><input type="text" name="date_O3" id="date_O3" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx8.select(document.forms[0].date_O3,'anchor1xx8','yyyy-MM-dd')" name="anchor1xx8" id="anchor1xx8"></td>
</tr>

<tr>
  <td>Flag Done</td>
  <script language="javascript" ID="jscal1xx9">
      var cal1xx9 = new CalendarPopup("testdiv1");
	  cal1xx9.showNavigationDropdowns();
  </script>
  <td><input type="text" name="flagged" id="flagged" size="12" readonly="readonly" /> <img src="Calendar-icon.png" id="display_or_not" style="cursor:pointer" onClick="cal1xx9.select(document.forms[0].flagged,'anchor1xx9','yyyy-MM-dd')" name="anchor1xx9" id="anchor1xx9"></td>
  <td>As Built GPS N</td>
  <td><input type="text" name="gps_north_BASE" id="gps_north_BASE" value="<?=($updateForm)?$x_gps_north_BASE:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'Base', this.value)" /></td>
  <td><input type="text" name="gps_north_A" id="gps_north_A" value="<?=($updateForm)?$x_gps_north_A:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'A', this.value)" /></td>
  <td><input type="text" name="gps_north_B" id="gps_north_B" value="<?=($updateForm)?$x_gps_north_B:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'B', this.value)" /></td>
  <td><input type="text" name="gps_north_C" id="gps_north_C" value="<?=($updateForm)?$x_gps_north_C:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'C', this.value)" /></td>
  <td><input type="text" name="gps_north_D" id="gps_north_D" value="<?=($updateForm)?$x_gps_north_D:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'D', this.value)" /></td>
  <td><input type="text" name="gps_north_O1" id="gps_north_O1" value="<?=($updateForm)?$x_gps_north_O1:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'O1', this.value)" /></td>
  <td><input type="text" name="gps_north_O2" id="gps_north_O2" value="<?=($updateForm)?$x_gps_north_O2:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'O2', this.value)" /></td>
  <td><input type="text" name="gps_north_O3" id="gps_north_O3" value="<?=($updateForm)?$x_gps_north_O3:""?>"<?=$readOnly?> onchange="UpdateDelta('North', 'O3', this.value)" /></td>
</tr>

<tr>
  <td colspan="2" rowspan="3">&nbsp;</td>
  <td>As Built GPS E</td>
  <td><input type="text" name="gps_east_BASE" id="gps_east_BASE" value="<?=($updateForm)?$x_gps_east_BASE:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'Base', this.value)" /></td>
  <td><input type="text" name="gps_east_A" id="gps_east_A" value="<?=($updateForm)?$x_gps_east_A:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'A', this.value)" /></td>
  <td><input type="text" name="gps_east_B" id="gps_east_B" value="<?=($updateForm)?$x_gps_east_B:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'B', this.value)" /></td>
  <td><input type="text" name="gps_east_C" id="gps_east_C" value="<?=($updateForm)?$x_gps_east_C:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'C', this.value)" /></td>
  <td><input type="text" name="gps_east_D" id="gps_east_D" value="<?=($updateForm)?$x_gps_east_D:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'D', this.value)" /></td>
  <td><input type="text" name="gps_east_O1" id="gps_east_O1" value="<?=($updateForm)?$x_gps_east_O1:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'O1', this.value)" /></td>
  <td><input type="text" name="gps_east_O2" id="gps_east_O2" value="<?=($updateForm)?$x_gps_east_O2:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'O2', this.value)" /></td>
  <td><input type="text" name="gps_east_O3" id="gps_east_O3" value="<?=($updateForm)?$x_gps_east_O3:""?>"<?=$readOnly?> onchange="UpdateDelta('East', 'O3', this.value)" /></td>
</tr>

<tr>
  <td>EL</td>
  <td><input type="text" name="EL_BASE" id="EL_BASE" value="<?=($updateForm)?$x_EL_BASE:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_A" id="EL_A" value="<?=($updateForm)?$x_EL_A:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_B" id="EL_B" value="<?=($updateForm)?$x_EL_B:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_C" id="EL_C" value="<?=($updateForm)?$x_EL_C:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_D" id="EL_D" value="<?=($updateForm)?$x_EL_D:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_O1" id="EL_O1" value="<?=($updateForm)?$x_EL_O1:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_O2" id="EL_O2" value="<?=($updateForm)?$x_EL_O2:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="EL_O3" id="EL_O3" value="<?=($updateForm)?$x_EL_O3:""?>"<?=$readOnly?> /></td>
</tr>

<tr>
  <td>GL</td>
  <td><input type="text" name="GL_BASE" id="GL_BASE" value="<?=($updateForm)?$x_GL_BASE:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_A" id="GL_A" value="<?=($updateForm)?$x_GL_A:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_B" id="GL_B" value="<?=($updateForm)?$x_GL_B:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_C" id="GL_C" value="<?=($updateForm)?$x_GL_C:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_D" id="GL_D" value="<?=($updateForm)?$x_GL_D:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_O1" id="GL_O1" value="<?=($updateForm)?$x_GL_O1:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_O2" id="GL_O2" value="<?=($updateForm)?$x_GL_O2:""?>"<?=$readOnly?> /></td>
  <td><input type="text" name="GL_O3" id="GL_O3" value="<?=($updateForm)?$x_GL_O3:""?>"<?=$readOnly?> /></td>
</tr>

<tr>
  <td>Designed North</td>
  <td id="tdDesignedNorth">&nbsp;</td>
  <td>Delta North</td>
  <td id="tdDeltaNorthBase"></td>
  <td id="tdDeltaNorthA"></td>
  <td id="tdDeltaNorthB"></td>
  <td id="tdDeltaNorthC"></td>
  <td id="tdDeltaNorthD"></td>
  <td id="tdDeltaNorthO1"></td>
  <td id="tdDeltaNorthO2"></td>
  <td id="tdDeltaNorthO3"></td>
</tr>

<tr>
  <td>Designed East</td>
  <td id="tdDesignedEast">&nbsp;</td>
  <td>Delta East</td>
  <td id="tdDeltaEastBase"></td>
  <td id="tdDeltaEastA"></td>
  <td id="tdDeltaEastB"></td>
  <td id="tdDeltaEastC"></td>
  <td id="tdDeltaEastD"></td>
  <td id="tdDeltaEastO1"></td>
  <td id="tdDeltaEastO2"></td>
  <td id="tdDeltaEastO3"></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
  <td>Other Description</td>
  <td colspan="6">&nbsp;</td>
  <td id="tdDesc02">&nbsp;</td>
  <td id="tdDesc03">&nbsp;</td>
</tr>

<tr>
  <td colspan="12">Note: Make sure that your delta is +/- 10m</td>
</tr>

<tr>
  <td colspan="10">&nbsp;</td>
  <td><!--<input type="submit" value="Check Delta"/>--><A
   HREF="SurveyModuleHelp.html"
   onClick="return popup(this, 'notes')">HELP</A></td>
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