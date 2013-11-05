<?
include("sessionCheck.php");
include("db.php");

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());
$dateOfReport = isset($_POST['date'])?$_POST['date']:$today;
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Construction Daily', '{$_SESSION['username']}')");

$rsSurveyed = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `flagged` = '{$dateOfReport}'");
$rsConstructed = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `ready_for_drilling` = '{$dateOfReport}'");
$rsAsBuilt = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `as_built` = '{$dateOfReport}'");
$rsNeedAsBuilt = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_BASE` = '{$dateOfReport}'");
$rsRR = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `rr` = '{$dateOfReport}'");
$rsAbandonment = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `abandonment` = '{$dateOfReport}'");
$rsRollBack = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `reclaimed_except_vegetation` = '{$dateOfReport}'");
?>
<html><head>
<title>Construction Daily - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<? include("header.inc"); ?>
<div id="mainForm" style="padding:20px;">
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<table cellspacing="1" cellpadding="3">
	<tr>
		<td colspan="3">Make sure that all Daily Inputs are completed first!!</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td align="center">Well Completed:</td>
    	<td><input type="text" name="date" value="<?=$dateOfReport?>" /></td>
        <td><input type="submit" name="submit" value="Create Report" /></td>
    </tr>
</table>
<table cellspacing="1" cellpadding="5"><?
if($rsSurveyed->RecordCount()==0&&$rsConstructed->RecordCount()==0&&$rsAsBuilt->RecordCount()==0&&$rsNeedAsBuilt->RecordCount()==0&&$rsRR->RecordCount()==0&&$rsAbandonment->RecordCount()==0&&$rsRollBack->RecordCount()==0) { ?>
	<tr>
    	<td colspan="3">No records for this report.</td>
    </tr><?
} else { ?>
	<tr>
    	<td align="center">Surveyed</td>
        <td align="center">Ready for Drilling<br />(constructed)</td>
        <td align="center">Base As Built</td>
        <td align="center">Need as Built</td>
        <td align="center">Last RR</td>
        <td align="center">Last Abandonment</td>
        <td align="center">Roll-back</td>
    </tr>
    <tr valign="top">
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsSurveyed->EOF) { ?>
            	<tr>
                	<td><?=$rsSurveyed->Fields("well_id")?></td>
                </tr><?
				$rsSurveyed->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsConstructed->EOF) { ?>
            	<tr>
                	<td><?=$rsConstructed->Fields("well_id")?></td>
                </tr><?
				$rsConstructed->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsAsBuilt->EOF) { ?>
            	<tr>
                	<td><?=$rsAsBuilt->Fields("well_id")?></td>
                </tr><?
				$rsAsBuilt->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsNeedAsBuilt->EOF) { ?>
            	<tr>
                	<td><?=$rsNeedAsBuilt->Fields("well_id")?></td>
                </tr><?
				$rsNeedAsBuilt->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRR->EOF) { ?>
            	<tr>
                	<td><?=$rsRR->Fields("well_id")?></td>
                </tr><?
				$rsRR->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsAbandonment->EOF) { ?>
            	<tr>
                	<td><?=$rsAbandonment->Fields("well_id")?></td>
                </tr><?
				$rsAbandonment->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRollBack->EOF) { ?>
            	<tr>
                	<td><?=$rsRollBack->Fields("well_id")?></td>
                </tr><?
				$rsRollBack->MoveNext();
				} ?>
            </table>
        </td>
    </tr><?
} ?>
</table>
</form>
</div>
<? include("footer.inc"); ?>
</body>
</html>