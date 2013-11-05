<?
include("sessionCheck.php");

require_once ('db.inc');
?>

<html>
<head>
	<title>Admin page</title>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<?
 //This code runs if the form has been submitted
 if (isset($_POST['submit'])) { 
 
 //This makes sure they did not leave any fields blank
 if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2'] | !$_POST['name'] | !$_POST['surname'] | !$_POST['levelID'] ) {
 		die('You did not complete all of the required fields');
 	}
 
 // checks if the username is in use
 	if (!get_magic_quotes_gpc()) {
 		$_POST['user'] = addslashes($_POST['user']);
 	}
 $usercheck = $_POST['username'];
 $check = mysql_query("SELECT username FROM wm_users WHERE username = '$usercheck'") 
 or die(mysql_error());
 $check2 = mysql_num_rows($check);
 
 //if the name exists it gives an error
 if ($check2 != 0) {
 		die('Sorry, the username '.$_POST['username'].' is already in use.');
 				}
 // this makes sure both passwords entered match
 	if ($_POST['pass'] != $_POST['pass2']) {
 		die('Your passwords did not match. ');
 	}
 
 	// Encrypt the password and add slashes if needed
 	$_POST['pass'] = md5($_POST['pass']);
 	if (!get_magic_quotes_gpc()) {
 		$_POST['pass'] = addslashes($_POST['pass']);
 		$_POST['user'] = addslashes($_POST['user']);
 			}
 
 // Insert data into the database
 	$insert = "INSERT INTO wm_users (username, password, name, surname, email, levelID, companyID, datetime)
 			VALUES ('".$_POST['username']."', '".$_POST['pass']."', '".$_POST['name']."', '".$_POST['surname']."', '".$_POST['email']."', '".$_POST['levelID']."', '".$_POST['companyID']."', NOW())";
 	$add_member = mysql_query($insert);
 	?>
 <div id="mainForm">
		<div id="formHeader">
				<p class="formInfo"></p>
				<h2 class="formInfo">WM Digital</h2>
				<p class="formInfo"></p>
		</div>
		<br>
 <h1>Registered</h1>
 <p>Thank you, you have registered new user</a>.</p>
 
 <?php } 
 else {	 ?>
 
 <div id="mainForm">
		<div id="formHeader">
				<p class="formInfo"></p>
				<h2 class="formInfo">WM Digital</h2>
				<p class="formInfo"></p>
		</div>
		<br>
 
 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
 <table border="0">
 <tr><td>Username:</td><td>
 <input type="text" name="username" maxlength="60">
 </td></tr>
 <tr><td>Password:</td><td>
 <input type="password" name="pass" maxlength="10">
 </td></tr>
 <tr><td>Confirm Password:</td><td>
 <input type="password" name="pass2" maxlength="10">
 </td></tr>
  <tr><td>Name:</td><td>
 <input type="tekst" name="name" maxlength="20">
 </td></tr>
  <tr><td>Surname:</td><td>
 <input type="tekst" name="surname" maxlength="20">
 </td></tr>
  <tr><td>Email:</td><td>
 <input type="tekst" name="email" maxlength="20">
 </td></tr>
  <tr><td>Level:</td><td>
 <select type="text" name="levelID" maxlength="1">
 <? 
 $level= mysql_query("SELECT * FROM wm_levels") or die("error".mysql_error());
 while ($row = mysql_fetch_array($level)) {
	echo "<option value='".$row[0]."'>".$row[1]."</option>";
 }
 ?>
 </select>
 </td></tr>
  <tr><td>Company:</td><td>
 <select type="text" name="companyID" maxlength="1">
 <? 
 $level= mysql_query("SELECT * FROM wm_company")or die("error".mysql_error());
 while ($row1 = mysql_fetch_array($level)) {
	echo "<option value='".($row1[0])."'>".($row1[1])."</option>";

 }
 ?>
 </select>
 </td></tr>
 <tr><th colspan=2><input type="submit" name="submit" 
value="Add user"></th></tr> </table>
 </form>
 <?php
 } ?> 
 </body>
 </html>