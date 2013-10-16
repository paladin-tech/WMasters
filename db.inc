<?
$db_host="wmastersca2.db.11749675.hostedresource.com";    // Host name 
$db_username="wmastersca2"; // Mysql username 
$db_password="Athlon!3200"; // Mysql password 
$db_name="wmastersca2";  // Database name 

 // Connects to Database 
 mysql_connect("$db_host", "$db_username", "$db_password") or die(mysql_error()); 
 mysql_select_db("$db_name") or die(mysql_error());
 ?>
 
 