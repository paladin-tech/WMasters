<?
include("sessionCheck.php");
include("db.php");

$today = date("Y-m-d", mktime());
$user = $_SESSION['username'];
$accessLead = ($_SESSION['levelid']==2)?true:false;

if(isset($_GET['final'])&&$accessLead) {
	$infosystem->Execute("UPDATE `wm_dailysafety` SET `reviewed` = 1 WHERE `recordID` = ".$_GET['final']);
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Daily Safety', '{$user}')");
	include("confirm.html");
	ob_end_flush();
}
else {
	$chk = $infosystem->Execute("SELECT `recordID`, `reviewed`, `entrydate` FROM `wm_dailysafety` ORDER BY `entrydate` DESC LIMIT 1");
	list($recordID, $checkReviewed, $entrydate) = $chk->fields;

	if(($checkReviewed==1||$chk->RecordCount()==0)&&$entrydate!=$today) {
		$infosystem->Execute("INSERT INTO `wm_dailysafety`(`user`, `topics`, `remarks`, `JSA`, `NearMiss`, `TotalHrs`, `other`, `reviewed`, `entrydate`) VALUES('{$user}', '', '', 0, 0, 0,'', 0, '{$today}')");

		$recordID = $infosystem->Insert_ID();
	}

	$recordID = (isset($_GET['recordID']))?$_GET['recordID']:$recordID;
	list($x_user, $x_topics, $x_remarks, $x_JSA, $x_NearMiss, $x_TotalHrs, $x_other, $x_reviewed) = $infosystem->Execute("SELECT `user`, `topics`, `remarks`, `JSA`, `NearMiss`, `TotalHrs`, `other`, `reviewed` FROM `wm_dailysafety` WHERE `recordID` = {$recordID}")->fields;

	list($DailySafetyLvl) = $infosystem->Execute("SELECT `DailySafety` FROM `wm_company` WHERE `companyID` = ".$_SESSION['companyid'])->fields;
	$accessLevelDesc = array("o"=>"Read Only", "x"=>"Read/Write", "w"=>"Read/Write, Read Other");
	$readOnly = ($DailySafetyLvl=="o"||$x_reviewed==1)?" readonly=\"readonly\"":"";
	$btnSubmitDisabled = ($DailySafetyLvl=="o"||$x_reviewed==1)?" disabled=\"disabled\"":"";
	$btnFinalSaveDisabled = ($x_reviewed==1)?" disabled=\"disabled\"":"";

	// Create Y-m-d format of current time
		$nowDate = mktime();

	// Submit execution procedure
	$dbFields = array(); $dbFieldValues = array();
	if(isset($_POST['submit'])) {
		$user = $_SESSION['username'];
		foreach($_POST as $key => $value) {
			if($key!="submit") array_push($dbFields, "`{$key}`");
			if($value!="Submit") array_push($dbFieldValues, "'{$value}'");
		}

	// We need to remove the Submit element values from dynamic SQL
		$SQL = "UPDATE `wm_dailysafety` SET ";
		foreach($dbFields as $f => $v) $SQL .= "{$v} = ".$dbFieldValues[$f].", ";
		$SQL = substr($SQL, 0, -2);
		$SQL .= " WHERE `recordID` = '{$recordID}'";
		$infosystem->Execute($SQL);
		$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Daily Safety', '{$user}')");

	include("confirm.html");
	ob_end_flush();
	}
	else {
	?>
		<html><head>
		<title>Daily Safety - WM Digital System</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
		</head>
		<body>
		<? include("header.inc"); ?>
		<div id="mainForm" style="padding:20px;">
		<p>Access Level: <?=$accessLevelDesc[$DailySafetyLvl]?>
		<br/><!-- begin form -->
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="frm" id="frm">
		<input type="hidden" name="reviewed" id="reviewed" value="0" />
		<table border="0" cellspacing="1" cellpadding="3">
			<tr>
				<td class="mainForm" id="fieldBox_4"><label class="formFieldQuestion">Daily Safety Topics:</label></td><td><input class="mainForm" type=text name="topics" id="topics" size="50" value="<?=$x_topics?>"<?=$readOnly?> /></td>
			</tr>
			<tr>
				<td class="mainForm" id="fieldBox_5"><label class="formFieldQuestion">REMARKS </label></td><td><textarea class="mainForm"  name="remarks" id="remarks" rows="10" cols="50"<?=$readOnly?>><?=$x_remarks?></textarea></td>
			</tr>
		</table>
		<br>
		<table border="0" cellspacing="1" cellpadding="3">
			<tr>
				<td label class="formFieldQuestion">JSA</td>
				<td label class="formFieldQuestion">Near Miss</td>
				<td label class="formFieldQuestion">Total Hours</td>
				<td label class="formFieldQuestion">Other</td>
			</tr>
			<tr>
				<td><input class="mainForm" type=text name="JSA" id="JSA" size="20" value="<?=$x_JSA?>"<?=$readOnly?> /></td>
				<td><input class="mainForm" type=text name="NearMiss" id="NearMiss" size="20" value="<?=$x_NearMiss?>"<?=$readOnly?> /></td>
				<td><input class="mainForm" type=text name="TotalHrs" id="TotalHrs" size="20" value="<?=$x_TotalHrs?>"<?=$readOnly?> /></td>
				<td><input class="mainForm" type=text name="other" id="other" size="20" value="<?=$x_other?>"<?=$readOnly?> /></td>
			</tr>
		 </table>
		<br>
		<input name="submit" type="submit" value="Save"<?=$btnSubmitDisabled?> /><? if($accessLead) { ?>&nbsp;<input type="button" value="Final Save" onClick="FinalSave()"<?=$btnFinalSaveDisabled?> /><? } ?>
		</form>
		<!-- end of form -->

		<!-- end of this page -->
		<!-- close the display stuff for this page -->
		</div>
		<? include ('footer.inc');?>
		<script language="javascript">
		function FinalSave() {
			if(confirm('Are you sure you want to do the Final Save and lock the record? Further changes will not be possible!')) {
				document.getElementById('reviewed').value = 1;
				window.location = 'DailySafety.php?final=<?=$recordID?>';
		//		document.forms['frm'].submit();
			}
		}
		</script>
		</body>
		</html><?
	}
}?>