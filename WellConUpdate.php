<?
include("sessionCheck.php");
include("db.php");

$user = $_SESSION['username'];

list($WellConUpdateLvl) = $infosystem->Execute("SELECT `WellConUpdate` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
$btnSubmitDisabled = ($WellConUpdateLvl=="o")?" disabled=\"disabled\"":"";

$submitted = false;

function checkDateFormat($date)
{
	if (strlen($date)>19)
		return false;
	else {
		if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", substr($date,0,10), $parts))
		{
			if(checkdate($parts[2],$parts[3],$parts[1])) {
				$colonCount = 0;
				$value = substr($date,11-strlen($date));
				for ($i=0; $i<strlen($value); $i++) {
					$ch = substr($value,$i,1);
					if ($ch == ':') $colonCount++;
					else if (($ch < '0')||($ch > '9')) return false;
				}
				if (($colonCount < 1) || ($colonCount > 2)) return false;
				$hh = substr($value,0, strpos($value,":"));
				if ((floatval($hh) < 0) || (floatval($hh) > 23)) return false;
				$mm = substr($value,strpos($value,":")+1, 2);
				if ((floatval($mm) < 0) || (floatval($mm) > 59)) return false;
				if ($colonCount == 2) {
				$ss = substr($value,strpos($value,":",strpos($value,":")+1)+1, 2);
				} else {
				$ss = "00";
				}
				if ((floatval($ss) < 0) || (floatval($ss) > 59)) return false;
				return true;
			}
			else
				return false;
		}
		else
			return false;
	}
}

if(isset($_POST['submit'])) {
	$submitted = true;
	$csvFile = $_FILES['filCSV']['tmp_name'];
	$handle = fopen($csvFile, "r");
	$sadrzaj = fread($handle, filesize($csvFile));
	$record = explode(chr(13).chr(10), $sadrzaj);
	fclose($handle);

	$rsWellID = $infosystem->Execute("SELECT `well_id` FROM `wells_construction`");
	$wellList = array();
	$msgError = array(); $msgOK = array();
	$updateCount = $CSVRecords = 0;

	while(!$rsWellID->EOF) {
		array_push($wellList, $rsWellID->Fields("well_id"));
		$rsWellID->MoveNext();
	}

	foreach($record as $ind=>$r) {
		if($ind>0) {
			$r = str_replace('"', '', $r);
			list($wellID, $spud, $rr, $logged, $abandonment) = explode(",", $r);
			if($wellID!="") {
				$checkStatus = true;
				$CSVRecords++;
				if(!in_array($wellID, $wellList)) {
					array_push($msgError, "{$wellID}: Well ID does not exist in database");
					$checkStatus = false;
				}
				if($spud!="") {
					if(checkDateFormat($spud)===false) {
						array_push($msgError, "{$wellID}: SPUD date/time format invalid");
						$checkStatus = false;
					}
				}
				if($rr!="") {
					if(checkDateFormat($rr)===false) {
						array_push($msgError, "{$wellID}: RR date/time format invalid");
						$checkStatus = false;
					}
				}
				if($logged!="") {
					if(checkDateFormat($logged)===false) {
						array_push($msgError, "{$wellID}: LOGGED date/time format invalid");
						$checkStatus = false;
					}
				}
				if($abandonment!="") {
					if(checkDateFormat($abandonment)===false) {
						array_push($msgError, "{$wellID}: Abandonment date/time format invalid");
						$checkStatus = false;
					}
				}
				if($checkStatus) {
					$updateCount++;
					array_push($msgOK, "<tr><td nowrap=\"nowrap\">{$wellID}</td><td nowrap=\"nowrap\">{$spud}</td><td nowrap=\"nowrap\">{$rr}</td><td nowrap=\"nowrap\">{$logged}</td><td nowrap=\"nowrap\">{$abandonment}</td></tr>");
					$infosystem->Execute("UPDATE `wells_construction` SET `spud` = '{$spud}', `rr` = '{$rr}', `logged` = '{$logged}', `abandonment` = '{$abandonment}', `csv_import_date_time` = NOW() WHERE `well_id` = '{$wellID}'");
				}
			}
		}
	}
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) $valueS('Main Board Data Import', '{$user}')");
}

list($updateTime) = $infosystem->Execute("SELECT MAX(`csv_import_date_time`) FROM `wells_construction`")->fields;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Main Board Data Import - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>

<body><? include ('header.inc');?>
<div style="background:#EEEEEE">
<br />
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" onsubmit="return CheckFile()">
<table cellspacing="1" cellpadding="10" align="left" width="400px"><?
	if($submitted) { ?>
    <tr>
        <td><?
			echo "{$updateCount}/{$CSVRecords} records successfully loaded.<br />";
        	if(sizeof($msgError)>0) { ?>
                <br /><br /><span style="color:red">Following wells' records failed to load:</span><br /><?
				foreach($msgError as $m) {
					echo "<br />{$m}";
				}
			} ?><br /><br /><br /><?
            if(sizeof($msgOK)>0) { ?>
    	        The following information was updated:<br />
				<table cellpadding="3" cellspacing="1" border="1">
					<tr><td nowrap="nowrap">Well ID</td><td nowrap="nowrap">SPUD</td><td nowrap="nowrap">RR</td><td nowrap="nowrap">LOGGED</td><td nowrap="nowrap">Abandonment</td></tr><?
				foreach($msgOK as $m) {
					echo $m;
				} ?>
                </table><?
			} ?><br /><br />
        </td>
    </tr><?
	} ?>
    <tr>
        <td>Choose a CSV file to upload:</td>
    </tr>
    <tr>
        <td><input type="file" name="filCSV" id="filCSV" /></td>
    </tr>
    <tr>
        <td>Last Update:<br /><?=$updateTime?></td>
    </tr>
    <tr>
        <td><input type="submit" name="submit" $value="Update Database"<?=$btnSubmitDisabled?> /></td>
    </tr>
</table>
</form>
<br style="clear:both" /><br />
</div>
<? include ('footer.inc'); ?>
</body>
<script language="javascript">
function CheckFile()
{
	var fileName = document.getElementById('filCSV').$value;
	parts = fileName.split('.');
	ext = parts[(parts.length-1)];
	if(fileName=='') {
		alert('No file chosen for upload. Please select a CSV file and click on Update Database button.');
		return false;
	}
	if(ext!='csv'&&ext!='CSV') {
		alert('Not a CSV file format.');
		return false;
	}
}
</script>
</html>