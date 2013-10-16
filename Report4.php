<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
include_once("adodb/toexport.inc.php");
include_once("adodb/adodb.inc.php");
include_once("infosystem.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

$todayShort = date("y-m-d", mktime());
$today = date("Y/m/d", mktime());
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Report 4', '{$_SESSION['username']}')");

$rsReport = $infosystem->Execute("SELECT `source_id`, `description`, `location`, `permit_no`, `start_date`, `completed`, `reclaimed`, `area` FROM `water_crossings` ORDER BY `source_id`");

$report = 4;
$reportURL = "reports/Report{$report}-{$todayShort}.csv";

$fp = fopen("{$reportURL}", "w");
if($fp) {
	rs2csvfile($rsReport, $fp);
	fclose($fp);
}
//mail("predragl@wmasters.ca", "WM Digital - Report #{$report}, {$today}", "Please find Report #{$report} created on {$today} on the following URL:\r\n\r\nhttp://wmasters.d-zine.ca/{$reportURL}", "From: WM Digital <admin@wellsitemasters.com>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report <?=$report?> - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include ('header.inc');?>
<table cellspacing="1" cellpadding="3">
    <tr>
        <td>Permit #</td>
        <td>Source ID</td>
        <td>Description</td>
        <td>Location LSD</td>
        <td>Start Date</td>
        <td>Completed</td>
        <td>Reclaimed</td>
        <td>AREA</td>
    </tr><?
	$rsReport->MoveFirst();
    while(!$rsReport->EOF) {
    list($x_source_id, $x_description, $x_location, $x_permit_no, $x_start_date, $x_completed, $x_reclaimed, $x_area) = $rsReport->fields; ?>
    <tr>
		<td><?=$x_permit_no?></td>
        <td><?=$x_source_id?></td>
        <td><?=$x_description?></td>
        <td><?=$x_location?></td>
        <td><?=($x_start_date!="0000-00-00")?$x_start_date:""?></td>
        <td><?=($x_completed!="0000-00-00")?$x_completed:""?></td>
        <td><?=($x_reclaimed!="0000-00-00")?$x_reclaimed:""?></td>
		<td><?=$x_area?></td>
    </tr><?
	$rsReport->MoveNext();
	} ?>
</table>
<? include ('footer.inc'); ?>
</body>
</html>