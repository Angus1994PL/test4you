<?php

define("VIRGO_API_DIR", "virgo_api");

require_once("smarty/Smarty.class.php");
require_once(VIRGO_API_DIR . "/virgo_api.php");
require("Sajax.php");
require(VIRGO_API_DIR ."/ajaxfunctions.php");

sajax_init(); 
sajax_export("AJAXSynchronizeDB"); 
sajax_export("AJAXGetInvestmentsDistricts");
sajax_export("AJAXGetInvestmentsLocations");
sajax_export("AJAXGetInvestmentsQuarters"); 
sajax_handle_client_request();

$smarty = new Smarty();
$api = new VirgoAPI();

function ShowSearchForm(VirgoAPI $api, Smarty $smarty, $lng){
	$smarty->assign("ShowSearchForm", true);
	$smarty->assign("provinces", $api->GetInvestmentsProvinces($lng));
	$smarty->assign("categories", $api->GetInvestmentsCategories($lng));
	$smarty->assign("post", $_POST);
}

function GetSelectedItem($array){
	$sel = array();
	foreach($array as $item) $sel[$item] = true;
	return $sel;
}

$lng = 1045;

if(isset($_GET['lng'])){
    $_SESSION['lng'] = $_GET['lng'];
}
if(isset($_SESSION['lng'])){
    $lng = $_SESSION['lng'];    
}
$smarty->assign("Lng", $lng);

if(isset($_POST['hidAction'])){
	switch ($_POST['hidAction']){
		case "sort" :
		case "page" :		
		case "search" :{
			ShowSearchForm($api, $smarty, $lng);
			$smarty->assign("ShowInvestmentsList", true);
			$filters = array();			
			if($_POST['txtNumber'] != "") $filters["number"] = $_POST['txtNumber'];
			if($_POST['txtName'] != "") $filters["name"] = $_POST['txtName'];
			if($_POST['txtArea'] != "") $filters["area"] = $_POST['txtArea'];
			if($_POST['txtPrice'] != "") $filters["price"] = $_POST['txtPrice'];
			if($_POST['txtRoom'] != "") $filters["rooms"] = $_POST['txtRoom'];
			if($_POST['txtFloor'] != "") $filters["floor"] = $_POST['txtFloor'];
			if($_POST['cmbProvince'] != -1) {
				$filters["province"] = $_POST['cmbProvince'];
				$smarty->assign("districts", $api->GetInvestmentsDistricts($_POST['cmbProvince']));			
			}
			if(isset($_POST['cmbDistrict'])) {
				$smarty->assign("districtsSelected", GetSelectedItem($_POST['cmbDistrict']));
				$items = "";
				foreach($_POST['cmbDistrict'] as $item) $items .= "'$item',";
				$filters["districts"] = substr($items, 0, strlen($items) - 1);
				$smarty->assign("locations", $api->GetInvestmentsLocations($_POST['cmbDistrict']));
			}
			if(isset($_POST['cmbLocation'])) {
				$smarty->assign("locationsSelected", GetSelectedItem($_POST['cmbLocation']));
				$items = "";
				foreach($_POST['cmbLocation'] as $item) $items .= "'$item',";
				$filters["locations"] = substr($items, 0, strlen($items) - 1);
				$smarty->assign("quarters", $api->GetInvestmentsQuarters($_POST['cmbLocation']));
			}
			if(isset($_POST['cmbQuarter'])) {
				$smarty->assign("quartersSelected", GetSelectedItem($_POST['cmbQuarter']));
				$items = "";
				foreach($_POST['cmbQuarter'] as $item) $items .= "'$item',";
				$filters["quarters"] = substr($items, 0, strlen($items) - 1);
			}
			if(isset($_POST['cmbCategories'])){
				$smarty->assign("categorySelected", GetSelectedItem($_POST['cmbCategories']));					
				$items = "";
				foreach($_POST['cmbCategories'] as $item) $items .= "'$item',";
				$filters["categories"] = substr($items, 0, strlen($items) - 1);
			}
			//print_r($filters);
			$sort = "id DESC";
			if(isset($_POST["hidSort"])){
				switch ($_POST['hidSort']){
					case "L1" : $sort = "location ASC"; break;
					case "L2" : $sort = "location DESC"; break;
					case "P1" : $sort = "price_from ASC, price_to ASC"; break;
					case "P2" : $sort = "price_from DESC, price_to DESC"; break;
					case "A1" : $sort = "area_from ASC, area_to ASC"; break;
					case "A2" : $sort = "area_from DESC, area_to DESC"; break;
				}
				$smarty->assign("sort", $_POST['hidSort']);
			}
			$page = 0;
			if(isset($_POST["hidPage"])) $page = $_POST["hidPage"];
			$args = new RefreshEventArgs(5, $page, $filters, $sort);
			$smarty->assign("investments", $api->GetInvestments($args, $lng));
			$smarty->assign("args", $args);
			$smarty->assign("page", $page);
		}break;		
	}	
}else if(isset($_GET['action'])){
	if(isset($_GET['id']) && !is_numeric($_GET['id'])) die("Acces denied! " . $_GET['id']);
	switch ($_GET['action']){
		case "invest":	
			$smarty->assign("ShowInvestmentDetails", true);
			$investment = $api->GetInvestment($_GET['id']);
			$smarty->assign("invest", $investment);	
		break;
		case "photo":
			$smarty->assign("ShowPhoto", true);
			$photo = $api->GetOfferPhoto($_GET['id']);
			$smarty->assign("photo", $photo);
		break;
	}
}else{
	//main page
	ShowSearchForm($api, $smarty, $lng);
}

//load available languages
$smarty->assign("Languages", $api->GetAvailableLanguages());

sajax_handle_client_request();
$smarty->assign("synchronizeDB", $api->GetSynchronizeJS());
$smarty->assign("ajax", sajax_show_javascript());
$smarty->display("index_i.tpl");

?> 