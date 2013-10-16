<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");

// Debugging info here can be useful if necessary
// $infosystem->debug = true;

// Create Y-m-d format of current time
$today = date("Y-m-d", mktime());

$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Survey Report', '{$_SESSION['username']}')");

$rsDates = $infosystem->Execute("SELECT DISTINCT `datumi` FROM (
	SELECT `flag_requested` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_BASE` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_A` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_B` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_C` as `datumi` FROM `wells_construction`
  UNION
	SELECT `need_as_built_D` as `datumi` FROM `wells_construction`
) RES
WHERE `datumi` <> '0000-00-00'
ORDER BY `datumi` DESC");
?>
<html><head>
<title>Survey Report - WM Digital System</title>
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
    	<td align="center">Prestake<br/>Requested</td>
    	<td align="center">Requested<br/>As Built Date</td>
        <td align="center">Requested A</td>
        <td align="center">Requested B</td>
        <td align="center">Requested C</td>
    </tr>
    <?
    	while(!$rsDates->EOF) {
		$dateOfReport = $rsDates->Fields("datumi");
		$rsFlag_Requested = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `flag_requested` = '{$dateOfReport}'");
		$rsPrestake_Requested = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_BASE` = '{$dateOfReport}'");
		$rsRequested_As_Built_Date = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_A` = '{$dateOfReport}'");
		$rsRequested_A = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_B` = '{$dateOfReport}'");
		$rsRequested_B = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_C` = '{$dateOfReport}'");
		$rsRequested_C = $infosystem->Execute("SELECT `well_id` FROM `wells_construction` WHERE `need_as_built_D` = '{$dateOfReport}'");

		$maxdates = max($rsFlag_Requested->RecordCount(),$rsPrestake_Requested->RecordCount(),$rsRequested_As_Built_Date->RecordCount(),$rsRequested_A->RecordCount(),$rsRequested_B->RecordCount(),$rsRequested_C->RecordCount());
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
				while(!$rsRequested_A->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRequested_A->Fields("well_id")?></p></td>
                </tr><?
				$rsRequested_A->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRequested_B->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRequested_B->Fields("well_id")?></p></td>
                </tr><?
				$rsRequested_B->MoveNext();
				} ?>
            </table>
        </td>
    	<td align="center">
        	<table cellpadding="2" cellspacing="0" width="100%"><?
				while(!$rsRequested_C->EOF) { ?>
            	<tr>
                	<td><p style="color:<?=$fontcolor?>"><?=$rsRequested_C->Fields("well_id")?></p></td>
                </tr><?
				$rsRequested_C->MoveNext();
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