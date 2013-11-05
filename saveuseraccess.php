<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

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
$dailymudmodule = $_POST["dailymudmodule"];
$mudproducts = $_POST["mudproducts"];
$mudproductlist = $_POST["mudproductlist"];
$surveymodule = $_POST["surveymodule"];
$rigmatlocation = $_POST["rigmatlocation"];
$templogdeck = $_POST["templogdeck"];
$wells = $_POST["wells"];
$report1 = $_POST["report1"];
$report2 = $_POST["report2"];
$report3 = $_POST["report3"];
$report4 = $_POST["report4"];
$report5 = $_POST["report5"];
$report6 = $_POST["report6"];
$report7 = $_POST["report7"];
$report8 = $_POST["report8"];
$report9 = $_POST["report9"];
$report10 = $_POST["report10"];
$report11 = $_POST["report11"];
$report12 = $_POST["report12"];
$report13 = $_POST["report13"];
$report14 = $_POST["report14"];
$report15 = $_POST["report15"];
$report16 = $_POST["report16"];
$report17 = $_POST["report17"];
$report18 = $_POST["report18"];
$report19 = $_POST["report19"];
$report20 = $_POST["report20"];
$report21 = $_POST["report21"];
$report = $_POST["report"];

$qr="UPDATE wm_company SET
DailySafety = '$dailysafety',
DailyInputs = '$dailyinputs',
SupervisorsDaily = '$supervisordaily',
ConDaily = '$condaily',
ConPerWell = '$condaily',
ConHydro = '$conhydro',
ConAccess = '$conaccess',
ConWaterCross = '$conwatercross',
Water = '$water',
Vacuum = '$vacuum',
DailyMudModule = '$dailymudmodule',
MudProducts = '$mudproducts',
MudProductList = '$mudproductlist',
SurveyModule = '$surveymodule',
RigMatLocation = '$rigmatlocation',
TempLogDeck = '$templogdeck',
Wells = '$wells',
Report1 = '$report1',
Report2 = '$report2',
Report3 = '$report3',
Report4 = '$report4',
Report5 = '$report5',
Report6 = '$report6',
Report7 = '$report7',
Report8 = '$report8',
Report9 = '$report9',
Report10 = '$report10',
Report11 = '$report11',
Report12 = '$report12',
Report13 = '$report13',
Report14 = '$report14',
Report15 = '$report15',
Report16 = '$report16',
Report17 = '$report17',
Report18 = '$report18',
Report19 = '$report19',
Report20 = '$report20',
Report21 = '$report21',
Report = '$report'
WHERE name = '$company_name'
";
mysql_query($qr) or die;

Header("Location: confirm.html\n\n");
?>
