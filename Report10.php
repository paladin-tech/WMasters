<?
include("sessionCheck.php");
include("db.php");

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());

$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Drilling Report', '{$_SESSION['username']}')");

$rsDates = $infosystem->Execute("SELECT DISTINCT `datumi` FROM (
	SELECT `date_BASE` as `datumi` FROM `wells_construction`
  UNION
	SELECT `date_A` as `datumi` FROM `wells_construction`
  UNION
	SELECT `ready_for_drilling` as `datumi` FROM `wells_construction`
  UNION
	SELECT `approved_by_drilling` as `datumi` FROM `wells_construction`
  UNION
	SELECT `ready_roll_back` as `datumi` FROM `wells_construction`
  UNION
	SELECT `roll_back_ready` as `datumi` FROM `wells_construction`
  UNION
	SELECT `reclaimed_except_vegetation` as `datumi` FROM `wells_construction`
  UNION
	SELECT `date_B` as `datumi` FROM `wells_construction`
  UNION
	SELECT `date_C` as `datumi` FROM `wells_construction`
  UNION
	SELECT `date_D` as `datumi` FROM `wells_construction`
) RES
WHERE `datumi` <> '0000-00-00'
ORDER BY `datumi` DESC");
?>
<html><head>
<title>Drilling Report - WM Digital System</title>
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
     	<td align="center">Prestake<br/>Completed Date</td>
        <td align="center">Completed<br/>As Built Date</td>
    	<td align="center">Construction<br/>Completed</td>
    	<td align="center">Rig Ready</td>
    	<td align="center">Lease Release</td>
        <td align="center">Roll Back<br/>Ready</td>
        <td align="center">Rolled Back</td>
        <td align="center">Completed A</td>
        <td align="center">Completed B</td>
        <td align="center">Completed C</td>
    </tr>
    <?
    	while(!$rsDates->EOF) {
		$dateOfReport = $rsDates->Fields("datumi");
		$rsPrestake_Completed_Date = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_BASE` = '{$dateOfReport}'");
		$rsCompleted_As_Built_Date = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_A` = '{$dateOfReport}'");
		$rsConstruction_Completed = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `ready_for_drilling` = '{$dateOfReport}'");
		$rsRig_Ready = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `approved_by_drilling` = '{$dateOfReport}'");
		$rsLease_Release = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `ready_roll_back` = '{$dateOfReport}'");
		$rsRoll_Back_Ready = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `roll_back_ready` = '{$dateOfReport}'");
		$rsRolled_Back = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `reclaimed_except_vegetation` = '{$dateOfReport}'");
		$rsCompleted_A = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_B` = '{$dateOfReport}'");
		$rsCompleted_B = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_C` = '{$dateOfReport}'");
		$rsCompleted_C = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `date_D` = '{$dateOfReport}'");

		$maxdates = max($rsPrestake_Completed_Date->RecordCount(),$rsCompleted_As_Built_Date->RecordCount(),$rsConstruction_Completed->RecordCount(),$rsRig_Ready->RecordCount(),$rsLease_Release->RecordCount(),$rsRoll_Back_Ready->RecordCount(),$rsRolled_Back->RecordCount(),$rsCompleted_A->RecordCount(),$rsCompleted_B->RecordCount(),$rsCompleted_C->RecordCount());
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
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsCompleted_A->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsCompleted_A->Fields("well_id")?></p></td>
                </tr><?
				$rsCompleted_A->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsCompleted_B->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsCompleted_B->Fields("well_id")?></p></td>
                </tr><?
				$rsCompleted_B->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsCompleted_C->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsCompleted_C->Fields("well_id")?></p></td>
                </tr><?
				$rsCompleted_C->MoveNext();
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