<?
include("sessionCheck.php");
include("db.php");

// Filling combo's with data
$user = $_SESSION['username'];
$companyID = $_SESSION['companyid'];
$nowDate = date("Y-m-d", mktime());
$rsDailyInputs = $infosystem->Execute("SELECT wdi.`kearl1`, wdi.`kearl2`, wdi.`firebag1`, wdi.`firebag2`, wdi.`wapasu1`, wdi.`wapasu2`, wdi.`wapasu3`, wdi.`wapasu4`, wdi.`wapasu5`, wdi.`athabasca1`, wdi.`athabasca2`, wdi.`creeburn1`, wdi.`creeburn2`, wdi.`camp3_1`, wdi.`camp3_2`, wdi.`camp3_3`, wdi.`camp3_4`, wdi.`companyID`, wc.`name` FROM `wm_dailyinputs` wdi, `wm_company` wc WHERE wdi.`companyID` = wc.`companyID` AND wdi.`input_date` = '{$nowDate}' LIMIT 17");

$rsCompany = $infosystem->Execute("SELECT `companyID`, `name` FROM `wm_company`");
if($rsDailyInputs->RecordCount()==0) {
	while(!$rsCompany->EOF) {
		list($companyID) = $rsCompany->fields;
		$infosystem->Execute("INSERT INTO `wm_dailyinputs`(`user`, `companyID`, `input_date`) VALUES('{$user}', {$companyID}, '{$nowDate}')");
		$rsCompany->MoveNext();
	}
	$rsDailyInputs = $infosystem->Execute("SELECT wdi.`kearl1`, wdi.`kearl2`, wdi.`firebag1`, wdi.`firebag2`, wdi.`wapasu1`, wdi.`wapasu2`, wdi.`wapasu3`, wdi.`wapasu4`, wdi.`wapasu5`, wdi.`athabasca1`, wdi.`athabasca2`, wdi.`creeburn1`, wdi.`creeburn2`, wdi.`camp3_1`, wdi.`camp3_2`, wdi.`camp3_3`, wdi.`camp3_4`, wdi.`companyID`, wc.`name` FROM `wm_dailyinputs` wdi, `wm_company` wc WHERE wdi.`companyID` = wc.`companyID` AND wdi.`input_date` = '{$nowDate}' LIMIT 17");
}
// Submit execution procedure
if(isset($_POST['submit'])) {
	foreach($_POST as $key => $value) $$key = $value;

	$SQL = "UPDATE `wm_dailyinputs` SET ";
	foreach($DI[$companyID] as $key => $value) $SQL .= "`{$key}` = '{$value}', ";
	$SQL .= "`user` = '{$user}' WHERE `input_date` = '{$nowDate}' AND `companyID` = {$companyID}";

	$infosystem->Execute($SQL);
	$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Daily Inputs', '{$user}')");

include("confirm.html");
ob_end_flush();
}
else {
?>
<html><head>
<title>Daily Inputs - WM Digital System</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><link href="style.css" rel="stylesheet" type="text/css">
</head>
<? include("header.inc"); ?>
<div id="mainForm" style="padding:20px;">
<form name="frm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table cellspacing="1" cellpadding="3">
    <tr>
        <td rowspan="2">Company</td>
        <td colspan="3" align="center">Kearl</td>
        <td colspan="3" align="center">Firebag</td>
        <td colspan="6" align="center">Wapasu</td>
        <td colspan="3" align="center">Athabasca</td>
        <td colspan="3" align="center">Creeburn</td>
        <td colspan="5" align="center">Camp 3</td>
        <td align="center">Total</td>
    </tr>
    <tr>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">Sum</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">Sum</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">5</td>
        <td align="center">Sum</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">Sum</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">Sum</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">Sum</td>
        <td align="center">&nbsp;</td>
    </tr>
    <?
	$bigSum = 0; $columnSum = array();
	while(!$rsDailyInputs->EOF) {
	list($x_kearl1, $x_kearl2, $x_firebag1, $x_firebag2, $x_wapasu1, $x_wapasu2, $x_wapasu3, $x_wapasu4, $x_wapasu5, $x_athabasca1, $x_athabasca2, $x_creeburn1, $x_creeburn2, $x_camp3_1, $x_camp3_2, $x_camp3_3, $x_camp3_4, $x_companyID, $x_name) = $rsDailyInputs->fields;
	$readonly = ($x_companyID==$companyID)?"":"readonly = \"readonly\"";
	$x_kearl = $x_kearl1 + $x_kearl2;
	$columnSum["Kearl1"] += $x_kearl1;
	$columnSum["Kearl2"] += $x_kearl2;
	$columnSum["Kearl"] += $x_kearl;
	$x_firebag = $x_firebag1 + $x_firebag2;
	$columnSum["Firebag1"] += $x_firebag1;
	$columnSum["Firebag2"] += $x_firebag2;
	$columnSum["Firebag"] += $x_firebag;
	$x_wapasu = $x_wapasu1 + $x_wapasu2 + $x_wapasu3 + $x_wapasu4 + $x_wapasu5;
	$columnSum["Wapasu1"] += $x_wapasu1;
	$columnSum["Wapasu2"] += $x_wapasu2;
	$columnSum["Wapasu3"] += $x_wapasu3;
	$columnSum["Wapasu4"] += $x_wapasu4;
	$columnSum["Wapasu5"] += $x_wapasu5;
	$columnSum["Wapasu"] += $x_wapasu;
	$x_athabasca = $x_athabasca1 + $x_athabasca2;
	$columnSum["Athabasca1"] += $x_athabasca1;
	$columnSum["Athabasca2"] += $x_athabasca2;
	$columnSum["Athabasca"] += $x_athabasca;
	$x_creeburn = $x_creeburn1 + $x_creeburn2;
	$columnSum["Creeburn1"] += $x_creeburn1;
	$columnSum["Creeburn2"] += $x_creeburn2;
	$columnSum["Creeburn"] += $x_creeburn;
	$x_camp3 = $x_camp3_1 + $x_camp3_2 + $x_camp3_3 + $x_camp3_4;
	$columnSum["Camp31"] += $x_camp3_1;
	$columnSum["Camp32"] += $x_camp3_2;
	$columnSum["Camp33"] += $x_camp3_3;
	$columnSum["Camp34"] += $x_camp3_4;
	$columnSum["Camp3"] += $x_camp3;
	$rowSum = $x_kearl + $x_firebag + $x_wapasu + $x_athabasca + $x_creeburn + $x_camp3;
	$bigSum += $rowSum; ?>
    <tr<?=($x_companyID==$companyID)?" id=\"trCleared\"":""?>>
        <td><?=$x_name?></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][kearl1]" size="2" value="<?=$x_kearl1?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][kearl2]" size="2" value="<?=$x_kearl2?>"<?=$readonly?> /></td>
        <td><?=$x_kearl?></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][firebag1]" size="2" value="<?=$x_firebag1?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][firebag2]" size="2" value="<?=$x_firebag2?>"<?=$readonly?> /></td>
        <td><?=$x_firebag?></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][wapasu1]" size="2" value="<?=$x_wapasu1?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][wapasu2]" size="2" value="<?=$x_wapasu2?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][wapasu3]" size="2" value="<?=$x_wapasu3?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][wapasu4]" size="2" value="<?=$x_wapasu4?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][wapasu5]" size="2" value="<?=$x_wapasu5?>"<?=$readonly?> /></td>
        <td><?=$x_wapasu?></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][athabasca1]" size="2" value="<?=$x_athabasca1?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][athabasca2]" size="2" value="<?=$x_athabasca2?>"<?=$readonly?> /></td>
        <td><?=$x_athabasca?></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][creeburn1]" size="2" value="<?=$x_creeburn1?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][creeburn2]" size="2" value="<?=$x_creeburn2?>"<?=$readonly?> /></td>
        <td><?=$x_creeburn?></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][camp3_1]" size="2" value="<?=$x_camp3_1?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][camp3_2]" size="2" value="<?=$x_camp3_2?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][camp3_3]" size="2" value="<?=$x_camp3_3?>"<?=$readonly?> /></td>
        <td><input type="text" name="DI[<?=$x_companyID?>][camp3_4]" size="2" value="<?=$x_camp3_4?>"<?=$readonly?> /></td>
        <td><?=$x_camp3?></td>
        <td><?=$rowSum?></td>
    </tr>
    <?
	$rsDailyInputs->MoveNext();
	} ?>
    <tr>
    	<td>Total</td>
    	<td><?=$columnSum["Kearl1"]?></td>
    	<td><?=$columnSum["Kearl2"]?></td>
    	<td><?=$columnSum["Kearl"]?></td>
    	<td><?=$columnSum["Firebag1"]?></td>
    	<td><?=$columnSum["Firebag2"]?></td>
    	<td><?=$columnSum["Firebag"]?></td>
    	<td><?=$columnSum["Wapasu1"]?></td>
    	<td><?=$columnSum["Wapasu2"]?></td>
    	<td><?=$columnSum["Wapasu3"]?></td>
    	<td><?=$columnSum["Wapasu4"]?></td>
    	<td><?=$columnSum["Wapasu5"]?></td>
    	<td><?=$columnSum["Wapasu"]?></td>
    	<td><?=$columnSum["Athabasca1"]?></td>
    	<td><?=$columnSum["Athabasca2"]?></td>
    	<td><?=$columnSum["Athabasca"]?></td>
    	<td><?=$columnSum["Creeburn1"]?></td>
    	<td><?=$columnSum["Creeburn2"]?></td>
    	<td><?=$columnSum["Creeburn"]?></td>
    	<td><?=$columnSum["Camp31"]?></td>
    	<td><?=$columnSum["Camp32"]?></td>
    	<td><?=$columnSum["Camp33"]?></td>
    	<td><?=$columnSum["Camp34"]?></td>
    	<td><?=$columnSum["Camp3"]?></td>
        <td><?=$bigSum?></td>
    </tr>
    <tr>
    	<td><input type="submit" name="submit" value="Submit"></td>
        <td colspan="24">1 = Craft, 2 = Supervisory, 3 = Executives, 4 = Suites, 5 = Other</td>
    </tr>
  </table>
</form>
</div>
<? include("footer.inc"); ?>
</body>
</html><?
} ?>