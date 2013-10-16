<?
session_start();
if(!isset($_SESSION["username"])) header("location:index.php");

// Including ADODB class and DB connection config
require("adodb/adodb.inc.php");
require("infosystem.php");

$fordate=$_GET["fordate"];

// Debugging info here can be useful if necessary
// $infosystem->debug = true;
$infosystem->Execute("INSERT INTO `tx_hist`(`FormName`, `user`) VALUES('Daily Reports', '{$_SESSION['username']}')");

//Get names from wm_company
$count=0;
$wm_companyQ=mysql_query("select name from wm_company where companyID<18");
while($wm_company = mysql_fetch_array($wm_companyQ))
 {
$name[$count]=$wm_company[name];
$count++;
}

//Get locations from wm_locations
$count=0;
$wm_locationsQ=mysql_query("select name from wm_locations where Active ='1'");
while($wm_locations = mysql_fetch_array($wm_locationsQ))
 {
$locations[$count]=$wm_locations[name];
$count++;
}

//Get item_description from afp_project_summary_items
$count=0;
$afp_project_summary_itemsQ=mysql_query("select * from afp_project_summary_items where Active ='1' order by item_id");
while($afp_project_summary_items = mysql_fetch_array($afp_project_summary_itemsQ))
 {
$item_description[$count]=$afp_project_summary_items[item_description];
$total_w[$count]=$afp_project_summary_items[total_w];
$count++;
}

//Get rig_code from rigs
$count=0;
$rigsQ=mysql_query("select rig_code from rigs where Active ='1' order by rig_id");
while($rigs = mysql_fetch_array($rigsQ))
 {
$rig_code[$count]=$rigs[rig_code];
$count++;
}

//Get all from wm_dailysafety
$wm_dailysafetyQ=mysql_query("select * from wm_dailysafety where entrydate='$fordate' limit 1");
$wm_dailysafety = mysql_fetch_array($wm_dailysafetyQ);

//Get all from wm_dailyinputs
$count=0;
$wm_dailyinputsQ =mysql_query("select * from wm_dailyinputs where input_date='$fordate' and companyID<18");
while($wm_dailyinputs = mysql_fetch_array($wm_dailyinputsQ))
 {
$wm_dailyinputs_kearl[$count]=$wm_dailyinputs[kearl1]+$wm_dailyinputs[kearl2];
$wm_dailyinputs_firebag[$count]=$wm_dailyinputs[firebag1]+$wm_dailyinputs[firebag2];
$wm_dailyinputs_wapasu[$count]=$wm_dailyinputs[wapasu1]+$wm_dailyinputs[wapasu2]+$wm_dailyinputs[wapasu3]+$wm_dailyinputs[wapasu4]+$wm_dailyinputs[wapasu5];
$wm_dailyinputs_athabasca[$count]=$wm_dailyinputs[athabasca1]+$wm_dailyinputs[athabasca2];
$wm_dailyinputs_creeburn[$count]=$wm_dailyinputs[creeburn1]+$wm_dailyinputs[creeburn2];
$wm_dailyinputs_camp[$count]=$wm_dailyinputs[camp3_1]+$wm_dailyinputs[camp3_2]+$wm_dailyinputs[camp3_3]+$wm_dailyinputs[camp3_4];

$wm_dailyinputs_company_total[$count]=$wm_dailyinputs_kearl[$count]+$wm_dailyinputs_firebag[$count]+$wm_dailyinputs_wapasu[$count]+$wm_dailyinputs_athabasca[$count]+$wm_dailyinputs_creeburn[$count]+$wm_dailyinputs_camp[$count];
$count++;
  }

//Sum calculate for for wm_dailyinputs
$wm_dailyinputs_kearl_total=array_sum($wm_dailyinputs_kearl);
$wm_dailyinputs_firebag_total=array_sum($wm_dailyinputs_firebag);
$wm_dailyinputs_wapasu_total=array_sum($wm_dailyinputs_wapasu);
$wm_dailyinputs_athabasca_total=array_sum($wm_dailyinputs_athabasca);
$wm_dailyinputs_creeburn_total=array_sum($wm_dailyinputs_creeburn);
$wm_dailyinputs_camp_total=array_sum($wm_dailyinputs_camp);
$wm_dailyinputs_all_total=array_sum($wm_dailyinputs_company_total);

//Get all from daily_supervisors
$daily_supervisorsQ=mysql_query("select * from daily_supervisors where input_date='$fordate' limit 1");
$daily_supervisors = mysql_fetch_array($daily_supervisorsQ);

//Get all from daily_supervisors_afp_summ
$count=0;
$daily_supervisors_afp_summQ =mysql_query("select * from daily_supervisors_afp_summ where input_date='$fordate' and afp_item_id<8");
while($daily_supervisors_afp_summ = mysql_fetch_array($daily_supervisors_afp_summQ))
 {
$current[$count]=$daily_supervisors_afp_summ[current];
$count++;
}

//Get all from daily_supervisors_drill24hrs_summ
$count=0;
$daily_supervisors_drill24hrs_summQ =mysql_query("select * from daily_supervisors_drill24hrs_summ where input_date='$fordate' and rig_id<12");
while($daily_supervisors_drill24hrs_summ = mysql_fetch_array($daily_supervisors_drill24hrs_summQ))
 {
$completed[$count]=$daily_supervisors_drill24hrs_summ[completed];
$on[$count]=$daily_supervisors_drill24hrs_summ[on];
$next_well[$count]=$daily_supervisors_drill24hrs_summ[next_well];

$count++;
}

include "reporttemplate.html";
?>