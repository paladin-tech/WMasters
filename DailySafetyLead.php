<?
session_start();
if(!isset($_SESSION["username"])||$_SESSION['levelid']!=2) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");

$rsDailySafety = $infosystem->Execute("SELECT `recordID`, `user`, `entrydate`, `reviewed` FROM `wm_dailysafety` ORDER BY `entrydate` DESC LIMIT 10");
?>
<html><head>
<title>Daily Safety - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<? include("header.inc"); ?>
<div id="mainForm" style="padding:20px;">
<table border="0" cellspacing="1" cellpadding="5">
	<tr>
        <th>User</td>
        <th align="center">Entry Date</td>
        <th>&nbsp;</td>
    </tr><?
	while(!$rsDailySafety->EOF) {
	list($x_recordID, $x_user, $x_entrydate, $x_reviewed) = $rsDailySafety->fields; ?>
	<tr>
        <td><?=$x_user?></td>
        <td align="center"><?=$x_entrydate?></td>
        <td align="center"><a href="DailySafety.php?recordID=<?=$x_recordID?>"><?=($x_reviewed==1)?"View":"Review"?></a></td>
    </tr><?
	$rsDailySafety->MoveNext();
	} ?>
</table>
</div><? include("footer.inc"); ?>
</body>
</html>