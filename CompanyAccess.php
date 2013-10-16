<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

ob_start();


require_once ('db.inc');



 //This code runs if the form has been submitted



ob_end_flush();

?>

<html><head>
		<title>Edit user access - WM Digital System</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">


</head>
<body onLoad="collapseAll()">

<div id="mainForm">


<? include ('header.inc');?>



<?
	$username=$_SESSION["username"];
	$result= mysql_query ("select companyID from wm_users where username='$username'");
	list($companyID) = mysql_fetch_row($result);
	$result= mysql_query ("select name from wm_company where companyID='$companyID'");
	list($company_name) = mysql_fetch_row($result);
	session_start ();
	$_SESSION['company_name'] = $company_name;

	$qr="select name from wm_company";
	$res=mysql_query($qr);
?>


		<form action="saveuseraccess.php" method="post"><ul class=mainForm id="mainForm_1">
		<table border 1>
		<tr>
		<td>Company name</td>
		<td>Daily Safety</td>
		<td>Daily Inputs</td>
		<td>Superisors Daily</td>
		<td>Con Daily</td>
		<td>Con PER Well</td>
		<td>Con Hydro</td>
		<td>Con Access</td>
		<td>Con Water Cross</td>
		<td>Water</td>
		<td>Vacuum</td>
		<td>Report1</td>
		<td>Report2</td>
		<td>Report3</td>
		<td>Report4</td>
		<td>Report5</td>
		<td>Report</td>
		</tr>


		<tr>
		<td>
		<select name=company_name>
		<?
		while($r=mysql_fetch_row($res))
		{
			echo "<option value=\"$r[0]\">$r[0]</option>";
		}
		?>
		</select>
		</td>
		<td>
		<select name="dailysafety">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="dailyinputs">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="supervisordaily">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="condaily">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="conperwell">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="conhydro">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="conaccess">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="conwatercross">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="water">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="vacuum">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="report1">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="report2">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="report3">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="report4">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="report5">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		<td>
		<select name="report">
		<option value="d">d</option>
		<option value="x">x</option>
		<option value="o">o</option>
		<option value="w">w</option>
		</select>
		</td>
		</tr>
		</table>
		<input id="submit" name="submit" type="submit" value="Submit">
		</form>


		<!-- end of this page -->
		<!-- close the display stuff for this page -->
		</div>

<? include ('footer.inc');?>
</body></html>
