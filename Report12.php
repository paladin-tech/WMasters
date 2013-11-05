<?
include("sessionCheck.php");
include("db.php");

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());

$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Construction Report', '{$_SESSION['username']}')");

$rsDates = $infosystem->Execute("SELECT DISTINCT `datumi` FROM (
	SELECT `flag_requested` as `datumi` FROM `wells_construction`
  UNION
	SELECT `flagged` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_BASE` as `datumi` FROM `wells_construction`
  UNION
	SELECT `date_BASE` as `datumi` FROM `wells_construction`
  UNION
	SELECT `date_A` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_A` as `datumi` FROM `wells_construction`
  UNION
	SELECT `ready_for_drilling` as `datumi` FROM `wells_construction`
  UNION
	SELECT `approved_by_drilling` as `datumi` FROM `wells_construction`
  UNION
	SELECT DATE(`rr`) as `datumi` FROM `wells_construction`
  UNION
	SELECT DATE(`abandonment`) as `datumi` FROM `wells_construction`
  UNION
	SELECT `ready_roll_back` as `datumi` FROM `wells_construction`
  UNION
	SELECT `roll_back_ready` as `datumi` FROM `wells_construction`
  UNION
	SELECT `reclaimed_except_vegetation` as `datumi` FROM `wells_construction`
) RES
WHERE `datumi` <> '0000-00-00'
ORDER BY `datumi` DESC");
?>
<html><head>
<title>Construction Report - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<? include("header.inc"); ?>
<table cellspacing="1" cellpadding="5"><?
if($rsDates->RecordCount()==0) { ?>
	<tr>
    	<td colspan="3">No records for this report!</td>
    </tr><?
} else { ?>
	<tr>
    	<td align="center">Date</td>
    	<td align="center">Flag Requested</td>
    	<td align="center">Flag Done</td>
    	<td align="center">Prestake<br/>Requested</td>
    	<td align="center">Prestake<br/>Completed Date</td>
    	<td align="center">Requested<br/>As Built Date</td>
    	<td align="center">Completed<br/>As Built Date</td>
    	<td align="center">Construction<br/>Completed</td>
    	<td align="center">Rig Ready</td>
    	<td align="center">Last RR</td>
    	<td align="center">Last<br/>Abandonment</td>
    	<td align="center">Lease Release</td>
        <td align="center">Roll Back<br/>Ready</td>
        <td align="center">Rolled Back</td>
    </tr>
    <?
    	while(!$rsDates->EOF) {
		$dateOfReport = $rsDates->Fields("datumi");
		$rsFlag_Requested = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `flag_requested` = '{$dateOfReport}'");
		$rsFlag_Done = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `flagged` = '{$dateOfReport}'");
		$rsPrestake_Requested = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_BASE` = '{$dateOfReport}'");
		$rsPrestake_Completed_Date = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_BASE` = '{$dateOfReport}'");
		$rsRequested_As_Built_Date = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_A` = '{$dateOfReport}'");
		$rsCompleted_As_Built_Date = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_A` = '{$dateOfReport}'");
		$rsConstruction_Completed = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `ready_for_drilling` = '{$dateOfReport}'");
		$rsRig_Ready = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `approved_by_drilling` = '{$dateOfReport}'");
		$rsLast_RR = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE DATE(`rr`) = '{$dateOfReport}'");
		$rsLast_Abandonment = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE DATE(`abandonment`) = '{$dateOfReport}'");
		$rsLease_Release = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `ready_roll_back` = '{$dateOfReport}'");
		$rsRoll_Back_Ready = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `roll_back_ready` = '{$dateOfReport}'");
		$rsRolled_Back = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `reclaimed_except_vegetation` = '{$dateOfReport}'");

		$maxdates = max($rsFlag_Requested->RecordCount(),$rsFlag_Done->RecordCount(),$rsPrestake_Requested->RecordCount(),$rsPrestake_Completed_Date->RecordCount(),$rsRequested_As_Built_Date->RecordCount(),$rsCompleted_As_Built_Date->RecordCount(),$rsConstruction_Completed->RecordCount(),$rsRig_Ready->RecordCount(),$rsLast_RR->RecordCount(),$rsLast_Abandonment->RecordCount(),$rsLease_Release->RecordCount(),$rsRoll_Back_Ready->RecordCount(),$rsRolled_Back->RecordCount());
    	$rsDates->MoveNext();
    	if($dateOfReport==$today) {$fontcolor = "red";} else {$fontcolor = "black";}
    ?>
      <tr valign="top">
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%">
				<?
				for( $i=0; $i<$maxdates; $i++ )
				{ ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$dateOfReport?></p></td>
                </tr>
				<?
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsFlag_Requested->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsFlag_Requested->Fields("well_id")?></p></td>
                </tr><?
				$rsFlag_Requested->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsFlag_Done->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsFlag_Done->Fields("well_id")?></p></td>
                </tr><?
				$rsFlag_Done->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsPrestake_Requested->EOF) { ?>
            	<tr>
					<td><p style="color:<?=$fontcolor?>"><?=$rsPrestake_Requested->Fields("well_id")?></p></td>
                </tr><?
				$rsPrestake_Requested->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsPrestake_Completed_Date->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsPrestake_Completed_Date->Fields("well_id")?></p></td>
                </tr><?
				$rsPrestake_Completed_Date->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRequested_As_Built_Date->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRequested_As_Built_Date->Fields("well_id")?></p></td>
                </tr><?
				$rsRequested_As_Built_Date->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsCompleted_As_Built_Date->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsCompleted_As_Built_Date->Fields("well_id")?></p></td>
                </tr><?
				$rsCompleted_As_Built_Date->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsConstruction_Completed->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsConstruction_Completed->Fields("well_id")?></p></td>
                </tr><?
				$rsConstruction_Completed->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRig_Ready->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRig_Ready->Fields("well_id")?></p></td>
                </tr><?
				$rsRig_Ready->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsLast_RR->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsLast_RR->Fields("well_id")?></p></td>
                </tr><?
				$rsLast_RR->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsLast_Abandonment->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsLast_Abandonment->Fields("well_id")?></p></td>
                </tr><?
				$rsLast_Abandonment->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsLease_Release->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsLease_Release->Fields("well_id")?></p></td>
                </tr><?
				$rsLease_Release->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRoll_Back_Ready->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRoll_Back_Ready->Fields("well_id")?></p></td>
                </tr><?
				$rsRoll_Back_Ready->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRolled_Back->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRolled_Back->Fields("well_id")?></p></td>
                </tr><?
				$rsRolled_Back->MoveNext();
				} ?>
            </table>
        </td>
    </tr>
    <?
	}
} ?>
</table>
</form>
</div>
<? include("footer.inc"); ?>
</body>
</html>