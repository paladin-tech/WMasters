<?
// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");

// Debugging info here can be useful if necessary
//$infosystem->debug = true;

if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;
	$password = mysql_real_escape_string($password);
	$encrypted_password=md5($password);
	$check = $infosystem->Execute("SELECT wu.`ID`, wu.`username`, wu.`companyID`, wu.`levelID`, wc.`name` FROM `wm_users` wu, `wm_company` wc WHERE wu.`companyID` = wc.`companyID` AND wu.`username` = '{$username}' AND wu.`password` = '{$encrypted_password}'");
	if($check->RecordCount()!=0) {
		list($ID, $username, $companyID, $levelID, $name) = $check->fields;
		session_start();
		$_SESSION['userID'] = $ID;
		$_SESSION['username'] = $username;
//		$_SESSION['password'] = $password;
		$_SESSION['companyid'] = $companyID;
		$_SESSION['companyname'] = $name;
		$_SESSION['levelid'] = $levelID;
		header("location:MainPage.php");
	} else {
		echo "Wrong Username and Password";
	}
}
?>
<html>
<head>
<title>Wellsite Masters</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

<div id="mainForm">




		<div id="formHeader">
				<p class="formInfo"></p>
				<h2 class="formInfo">-WM Digital</h2>
				<p class="formInfo"></p>
		</div>

<br>
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<td>
	<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
	<tr>
	<td colspan="3"><strong>Wellsite Masters Login</strong></td>
	</tr>
	<tr>
		<td width="78">Username</td>
		<td width="6">:</td>
		<td width="294"><input name="username" type="text" id="username"></td>
	</tr>
	<tr>
		<td>Password</td>
		<td>:</td>
		<td><input name="password" type="password" id="password"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Login"></td>
	</tr>
	</table>
	</td>
	</form>
	</tr>
</table>
<br>
</body>
</html>