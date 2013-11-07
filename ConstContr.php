<?
include("sessionCheck.php");
include("db.php");
include("xajax_f.php");

// Gathering data for combo's
$rsWellLicence = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `archive` = 0  ORDER BY `mainboard`");

$user = $_SESSION['username'];

list($ConPerWellLvl) = $infosystem->Execute("SELECT `ConstContr` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$readOnly = ($ConPerWellLvl=="o")?" readonly=\"readonly\"":"";
$btnSubmitDisabled = ($ConPerWellLvl=="o")?" disabled=\"disabled\"":"";

// Dynamic SQL creation and execution
$dbFields = array(); $dbFieldValues = array();
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) {
		if($key!="submit") array_push($dbFields, "`{$key}`");
		if($value!="Submit") array_push($dbFieldValues, "'{$value}'");
	}
	array_push($dbFields, "`last_changed_by`");
	array_push($dbFieldValues, "'{$user}'");

	$check = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `well_id` = '{$well_id}'");

	if($check->RecordCount()==0) {
		$dbFields = str_replace(", `submit`", "", implode(", ", $dbFields));
		$dbFieldValues = str_replace(", 'Submit'", "", implode(", ", $dbFieldValues));
		$SQL = "INSERT INTO `wells_construction`({$dbFields}) VALUES({$dbFieldValues})";
	} else {
		$SQL = "UPDATE `wells_construction` SET ";
		foreach($dbFields as $f => $v) $SQL .= "{$v} = ".$dbFieldValues[$f].", ";
		$SQL = substr($SQL, 0, -2);
		$SQL .= " WHERE `well_id` = '{$well_id}'";
	}
	$infosystem->Execute($SQL);
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Construction Per Well', '{$user}')");
	include("confirm.html");
	ob_end_flush();
} else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Construction Contractor - WM Digital System</title>
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
    <td rowspan="5">&nbsp;</td>
    <td>Well ID (Lease ID)</td>
    <td>
      <select name="well_id" id="well_id" onchange="xajax_GetWellInfoConstContr(this.value)"<?=$readOnly?>>
      	<option value=""></option><?
// Filling combo with data
		while(!$rsWellLicence->EOF) {
		list($y_well_id) = $rsWellLicence->fields; ?>
        <option value="<?=$y_well_id?>"<?=($updateForm&&$x_well_id==$x_well_id)?" selected":""?>><?=$y_well_id?></option><?
		$rsWellLicence->MoveNext();
		} ?>
      </select></td>
    <td>Well Suffix</td>
    <td>As Built Date</td>
    <td>As Built GPS N</td>
    <td>As Built GPS E</td>
  </tr>
  <tr>
    <td>Well Licence</td>
    <td><input type="text" name="well_licence" id="well_licence"<?=$readOnly?> /></td>
    <td>Prestake Completed</td>
    <td id="tdDate_BASE">&nbsp;</td>
    <td id="tdGPSNBASE">&nbsp;</td>
    <td id="tdGPSEBASE">&nbsp;</td>
  </tr>
  <tr>
    <td>Permit</td>
    <td><input type="text" name="permit" id="permit"<?=$readOnly?> /></td>
    <td>As Built</td>
    <td id="tdDate_A">&nbsp;</td>
    <td id="tdGPSNA">&nbsp;</td>
    <td id="tdGPSEA">&nbsp;</td>
  </tr>
  <tr>
    <td>Lease Activity</td>
    <td id="tdLeaseActivity">&nbsp;</td>
    <td>A</td>
    <td id="tdDate_B">&nbsp;</td>
    <td id="tdGPSNB">&nbsp;</td>
    <td id="tdGPSEB">&nbsp;</td>
  </tr>
  <tr>
    <td>Location LSD</td>
    <td id="tdLocationLSD">&nbsp;</td>
    <td>B</td>
    <td id="tdDate_C">&nbsp;</td>
    <td id="tdGPSNC">&nbsp;</td>
    <td id="tdGPSEC">&nbsp;</td>
  </tr>

  <tr>
	<td rowspan="2">New Cut Access</td>
    <td>Length [m]</td>
    <td id="tdLengthNCA">&nbsp;</td>
    <td>C</td>
    <td id="tdDate_D">&nbsp;</td>
    <td id="tdGPSND">&nbsp;</td>
    <td id="tdGPSED">&nbsp;</td>

  </tr>

  <tr>
    <td>Width [m]</td>
    <td id="tdWidthNCA">&nbsp;</td>
    <td>D</td>
    <td id="tdDate_O1">&nbsp;</td>
    <td id="tdGPSNO1">&nbsp;</td>
    <td id="tdGPSEO1">&nbsp;</td>

  </tr>

  <tr>
    <td rowspan="2">Existing Access</td>
    <td>Length [m]</td>
    <td id="tdLengthEA">&nbsp;</td>
    <td>O1</td>
    <td id="tdDate_O2">&nbsp;</td>
    <td id="tdGPSNO2">&nbsp;</td>
    <td id="tdGPSEO2">&nbsp;</td>
  </tr>

  <tr>
    <td>Width [m]</td>
    <td id="tdWidthEA">&nbsp;</td>
    <td>O2</td>
    <td id="tdDate_O3">&nbsp;</td>
    <td id="tdGPSNO3">&nbsp;</td>
    <td id="tdGPSEO3">&nbsp;</td>
  </tr>

  <tr>
	<td colspan="7" style="padding: 10px; font-weight:bold; text-align: center; background: #DDDDDD;">Status</td>
  </tr>

  <tr>
    <td rowspan="17">&nbsp;</td>
    <td>Letter of Authority</td>
    <td id="tdLetterOfAuthority">&nbsp;</td>
    <td rowspan="17" colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>Start Date of Entry</td>
    <td id="tdStartDateOfEntry">&nbsp;</td>
  </tr>
  <tr>
    <td>Final Date of Entry</td>
    <td id="tdFinalDateOfEntry">&nbsp;</td>
  </tr>
  <tr>
    <td>Flag Requested</td>
    <td id="tdFlagRequested">&nbsp;</td>
  </tr>
  <tr>
  <tr>
    <td>Flag Done</td>
    <td id="tdFlagged">&nbsp;</td>
  </tr>
  <tr>
	<script language="javascript" ID="jscal1xx1">
		var cal1xx1 = new CalendarPopup("testdiv1");
		cal1xx1.showNavigationDropdowns();
    </script>
    <td>Salvaged</td>
    <td><input type="text" name="salvaged" id="salvaged" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx1.select(document.forms[0].salvaged,'anchor1xx1','yyyy-MM-dd')" name="anchor1xx1" id="anchor1xx1"></td>
  </tr>
  <tr>
	<script language="javascript" ID="jscal1xx2">
		var cal1xx2 = new CalendarPopup("testdiv1");
		cal1xx2.showNavigationDropdowns();
    </script>
    <td>Mulched</td>
    <td><input type="text" name="mulched" id="mulched" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx2.select(document.forms[0].mulched,'anchor1xx2','yyyy-MM-dd')" name="anchor1xx2" id="anchor1xx2"></td>
  </tr>
  <tr>
	<script language="javascript" ID="jscal1xx3">
		var cal1xx3 = new CalendarPopup("testdiv1");
		cal1xx3.showNavigationDropdowns();
    </script>
    <td>Bladed</td>
    <td><input type="text" name="bladed" id="bladed" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx3.select(document.forms[0].bladed,'anchor1xx3','yyyy-MM-dd')" name="anchor1xx3" id="anchor1xx3"></td>
  </tr>
  <tr>
	<script language="javascript" ID="jscal1xx4">
		var cal1xx4 = new CalendarPopup("testdiv1");
		cal1xx4.showNavigationDropdowns();
    </script>
    <td>Construction Completed</td>
    <td><input type="text" name="ready_for_drilling" id="ready_for_drilling" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx4.select(document.forms[0].ready_for_drilling,'anchor1xx4','yyyy-MM-dd')" name="anchor1xx4" id="anchor1xx4"></td>
  </tr>
  <tr>
    <td>Rig Ready</td>
    <td id="tdApprovedByDrilling">&nbsp;</td>
  </tr>
  <tr>
    <td>As Built Date</td>
    <td id="tdAsBuilt">&nbsp;</td>
  </tr>
  <tr>
     <td>Lease Release</td>
     <td id="tdLeaseRelease">&nbsp;</td>
  </tr>
  <tr>
     <td>Roll Back Ready</td>
     <td id="tdRollBackReady">&nbsp;</td>
  </tr>
  <tr>
	<script language="javascript" ID="jscal1xx5">
		var cal1xx5 = new CalendarPopup("testdiv1");
		cal1xx5.showNavigationDropdowns();
    </script>
    <td>Rolled Back</td>
    <td><input type="text" name="reclaimed_except_vegetation" id="reclaimed_except_vegetation" size="12" readonly="readonly" /> <img src="Calendar-icon.png" style="cursor:pointer" onClick="cal1xx5.select(document.forms[0].reclaimed_except_vegetation,'anchor1xx5','yyyy-MM-dd')" name="anchor1xx5" id="anchor1xx5"></td>
  </tr>
  <tr>
     <td>Constructed - Not Drilled</td>
     <td id="tdConstructedNotDrilled">&nbsp;</td>
  </tr>
  <tr>
     <td>Nil Entry - Not Built</td>
     <td id="tdNilEntryNotBuilt">&nbsp;</td>
  </tr>
  <tr>
	<td colspan="7" style="padding: 10px; font-weight:bold; text-align: center; background: #DDDDDD;">Lease</td>
    </tr>
  <tr>
  	<td rowspan="2">Size</td>
    <td>Length [m]</td>
    <td><input type="text" name="lease_length" id="lease_length"<?=$readOnly?> /></td>
    <td rowspan="5" colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>Width [m]</td>
    <td><input type="text" name="lease_width" id="lease_width"<?=$readOnly?> /></td>
  </tr>
  <tr>
    <td rowspan="3">&nbsp;</td>
    <td>Salvaged</td>
    <td>
    	<select name="lease_salvaged" id="lease_salvaged"<?=$readOnly?>>
            <option value="N">No</option>
            <option value="Y">Yes</option>
        </select>
    </td>
  </tr>
  <tr>
    <td>Remote Sump</td>
    <td><input type="text" name="lease_remote_sump" id="lease_remote_sump"<?=$readOnly?> /></td>
  </tr>
  <tr>
    <td>Snow Fill / Water Crossing</td>
    <td>
        <select name="lease_snow_fill" id="lease_snow_fill"<?=$readOnly?>>
        	<option value=""></option>
            <option value="SF">SF</option>
            <option value="WC">WC</option>
        </select>
    </td>
  </tr>
  <tr>
    <td align="CENTER" colspan="7"><input type="submit" name="submit" id="submit" value="Submit"<?=$btnSubmitDisabled?> /></td>
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