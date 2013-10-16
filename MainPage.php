<?
session_start();
if (!isset($_SESSION["username"])) header("location:index.php");

//require_once('db.inc');
require("adodb/adodb.inc.php");
require("infosystem.php");
?>

<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<link href="style.css" rel="stylesheet" type="text/css">
	<title>Main Page</title>
</head>
<body onLoad="collapseAll()">
<div id="mainForm">
	<div id="formHeader">
		<p class="formInfo"></p>

		<h2 class="formInfo">WM Digital</h2>

		<p class="formInfo"></p>
	</div>
	<br>
	<br>
	<?
	// Check user level access
	$username = $_SESSION["username"];
	//die("select levelID from wm_users where username='$username'");
	$result = $infosystem->Execute("SELECT `levelID` FROM `wm_users` WHERE `username` = '{$username}'");
//	$result = mysql_query("select levelID from wm_users where username='$username'");
	list($levelID) = $result->fields;
//	list($levelID) = mysql_fetch_row($result);
	if ($levelID == 1) {
		include('adminmenu.php');
	}

	if ($levelID == 2) {
		include('usermenu.php');
//	include ('leadmenu.php');
	}

	if ($levelID == 3) {
		include('usermenu.php');
	}
	?>

	<br>&nbsp <a href="logout.php" target="_top">Log out</a><br>
	<br>
</div>
</body>
</html>