<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");}

ob_start();


require_once ('db.inc');

$company_name = $_POST["company_name"];
$dailysafety = $_POST["dailysafety"];
$dailyinputs = $_POST["dailyinputs"];
$supervisordaily = $_POST["supervisordaily"];
$condaily = $_POST["condaily"];
$conperwell = $_POST["conperwell"];
$conhydro = $_POST["conhydro"];
$conaccess = $_POST["conaccess"];
$conwatercross = $_POST["conwatercross"];
$water = $_POST["water"];
$vacuum = $_POST["vacuum"];
$report1 = $_POST["report1"];
$report2 = $_POST["report2"];
$report3 = $_POST["report3"];
$report4 = $_POST["report4"];
$report5 = $_POST["report5"];
$report = $_POST["report"];

$qr="UPDATE wm_company SET
DailySafety='$dailysafety',
DailyInputs='$dailyinputs',
SupervisorsDaily='$supervisordaily',
ConDaily='$condaily',
ConPerWell='$condaily',
ConHydro='$conhydro',
ConAccess='$conaccess',
ConWaterCross='$conwatercross',
Water='$water',
Vacuum='$vacuum',
Report1='$report1',
Report2='$report2',
Report3='$report3',
Report4='$report4',
Report5='$report5',
Report='$report'
WHERE name = '$company_name'
";
mysql_query($qr) or die;

Header("Location: confirm.html\n\n");
?>
