<?
include("sessionCheck.php");
include("db.php");
include_once("adodb/toexport.inc.php");

$resourceType = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 'vacuum' : 'water';
$report = (isset($_GET['resourceType']) && $_GET['resourceType'] == 'vacuum') ? 7 : 5;
$cellLabel = array('water' => 'Water', 'vacuum' => 'Sump');

$todayShort = date("y-m-d", mktime());
$today = date("Y-m-d", mktime());
$dateOfReport = isset($_POST['txtDate'])?$_POST['txtDate']:$today;
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report {$report}', '{$_SESSION['username']}')");

$rsReport = $infosystem->Execute("SELECT wv.`input_date`, chv.`area`, chv.`cell_number`, wv.`volume` FROM `con_hydro_vac` chv, `water_vacuum` wv WHERE chv.`con_hydro_vac_id` = wv.`con_hydro_vac_id` AND chv.`type` = '{$resourceType}' ORDER BY wv.`input_date` DESC");
$rsArea = $infosystem->Execute("SELECT DISTINCT chv.`area` FROM `con_hydro_vac` chv, `water_vacuum` wv WHERE chv.`con_hydro_vac_id` = wv.`con_hydro_vac_id` AND `type` = '{$resourceType}' ORDER BY chv.`area`");
while(!$rsArea->EOF) {
	list($xArea) = $rsArea->fields;
	$rsCell[$xArea] = $infosystem->Execute("SELECT DISTINCT chv.`cell_number` FROM `con_hydro_vac` chv, `water_vacuum` wv WHERE chv.`con_hydro_vac_id` = wv.`con_hydro_vac_id` AND `type` = '{$resourceType}' AND `area` = '{$xArea}' ORDER BY chv.`cell_number`");
	$rsArea->MoveNext();
}
while(!$rsReport->EOF) {
	list($xInputDate, $xArea, $xCellNumber, $xVolume) = $rsReport->fields;
	$reportData[$xInputDate][$xArea][$xCellNumber] += $xVolume;
	$rsReport->MoveNext();
}

$reportURL = "reports/Report{$report}-{$todayShort}.csv";

$fp = fopen("{$reportURL}", "w");
if($fp) {
	rs2csvfile($rsReport, $fp);
	fclose($fp);
}
//mail("predragl@wmasters.ca", "WM Digital - Report #{$report}, {$today}", "Please find Report #{$report} created on {$today} on the following URL:\r\n\r\nhttp://wmasters.d-zine.ca/{$reportURL}", "From: WM Digital <admin@wellsitemasters.com>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>Report <?=$report?> - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
<script src="CalendarPopupCombined.js"></script>
</head>

<body>
<? include("header.inc"); ?>
<div id="mainForm" style="padding:20px;">
<table cellspacing="1" cellpadding="5" bgcolor="#CCCCCC">
    <tr>
        <th rowspan="2">Date /<br><?= $cellLabel[$resourceType] ?> Source</th>
	    <?
		$rsArea->MoveFirst();
	    while(!$rsArea->EOF) {
		    list($yArea) = $rsArea->fields;
	    ?>
        <th colspan="<?= $rsCell[$yArea]->RecordCount() ?>" align="center"><?= $yArea ?></th>
	    <?
		    $rsArea->MoveNext();
	    }
	    ?>
    </tr>
	<tr>
		<?
		$rsArea->MoveFirst();
		while(!$rsArea->EOF) {
			list($yArea) = $rsArea->fields;
			while(!$rsCell[$yArea]->EOF) {
				list($yCellNumber) = $rsCell[$yArea]->fields;
			?>
				<th align="center"><?= $yCellNumber ?></th>
			<?
				$rsCell[$yArea]->MoveNext();
			}
			$rsArea->MoveNext();
		}
		?>
	</tr>
	<?
	$sumVolume = array();
	foreach($reportData as $zInputDate => $value1) {
	?>
    <tr>
		<td><?= $zInputDate ?></td>
		<?
		$rsArea->MoveFirst();
		while(!$rsArea->EOF) {
			list($yArea) = $rsArea->fields;
			$rsCell[$yArea]->MoveFirst();
			while(!$rsCell[$yArea]->EOF) {
				list($yCellNumber) = $rsCell[$yArea]->fields;
				$zVolume = $reportData[$zInputDate][$yArea][$yCellNumber];
				$sumVolume[$yArea][$yCellNumber] += $zVolume;
				?>
				<td align="right"><?= ($zVolume != 0) ? number_format($reportData[$zInputDate][$yArea][$yCellNumber], 2) : "" ?></td>
				<?
				$rsCell[$yArea]->MoveNext();
			}
			$rsArea->MoveNext();
		}
		?>
	</tr>
	<?
    }
    ?>
	<tr>
		<th>Total</th>
		<?
		$rsArea->MoveFirst();
		while(!$rsArea->EOF) {
			list($yArea) = $rsArea->fields;
			$rsCell[$yArea]->MoveFirst();
			while(!$rsCell[$yArea]->EOF) {
				list($yCellNumber) = $rsCell[$yArea]->fields;
				?>
				<th align="right"><?= number_format($sumVolume[$yArea][$yCellNumber], 2) ?></th>
				<?
				$rsCell[$yArea]->MoveNext();
			}
			$rsArea->MoveNext();
		}
		?>
	</tr>
</table>
</div>

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

<script language="javascript" ID="jscal1xx1">
	var cal1xx1 = new CalendarPopup("testdiv1");
	cal1xx1.showNavigationDropdowns();
</script>

<? include ('footer.inc'); ?>
</body>
</html>