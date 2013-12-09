<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");
$userID = $_SESSION['userID'];
?>