<?
include("sessionCheck.php");
include("db.php");

$rsDailySafety = $infosystem->Execute("SELECT `entrydate` FROM `wm_dailysafety` WHERE `reviewed` = 1 ORDER BY `entrydate` DESC LIMIT 20");
?>
<html><head>
<title>Daily Reports - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<? include("header.inc"); ?>
<div id="mainForm" style="padding:20px;">
<table border="0" cellspacing="1" cellpadding="5">
	<tr>
        <th align="center">Entry Date</th>
    </tr><?
	while(!$rsDailySafety->EOF) {
	list($x_entrydate) = $rsDailySafety->fields; ?>
	<tr>
        <td align="center"><a href="report.php?fordate=<?=$x_entrydate?>"><?=$x_entrydate?></a></td>
    </tr><?
	$rsDailySafety->MoveNext();
	} ?>
</table>
</div><? include("footer.inc"); ?>
</body>
</html>