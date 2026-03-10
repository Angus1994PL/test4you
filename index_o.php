<?php

session_start();

define("VIRGO_API_DIR", "virgo_api");

require_once("smarty/Smarty.class.php");
require_once(VIRGO_API_DIR . "/virgo_api.php");
require("Sajax.php");
require(VIRGO_API_DIR ."/ajaxfunctions.php");

sajax_init();
sajax_export("AJAXSynchronizeDB");
sajax_export("AJAXGetDistricts");
sajax_export("AJAXGetLocations");
sajax_export("AJAXGetQuarters");
sajax_handle_client_request();

$smarty = new Smarty();
$api = new VirgoAPI();

function ShowSearchForm(VirgoAPI $api, Smarty $smarty, $idLng){
	$smarty->assign("ShowSearchForm", true);
	$smarty->assign("objects", $api->GetObjects());
	$smarty->assign("provinces", $api->GetProvinces($idLng));
	$smarty->assign("flatTypes", $api->GetBuildingTypes($idLng));
	$smarty->assign("houseTypes", $api->GetHouseTypes($idLng));
	$smarty->assign("fieldDestiny", $api->GetFieldDestiny($idLng));
	$smarty->assign("localDestiny", $api->GetPremisesDestiny($idLng));
	$smarty->assign("post", $_POST);
}
function GetSelectedItem($array){
	$sel = array();
	foreach($array as $item) $sel[$item] = true;
	return $sel;
}

$showSpecial = true;
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
			$smarty->assign("ShowOffersList", true);
			$filters = array();
			$filters["rent"] = $_POST['cmbType'];
			if($_POST['cmbObject'] != -1) $filters["object"] = $_POST['cmbObject'];
			if($_POST['txtPriceFrom'] != "") $filters["priceFrom"] = $_POST['txtPriceFrom'];
			if($_POST['txtPriceTo'] != "") $filters["priceTo"] = $_POST['txtPriceTo'];
			if($_POST['txtAreaFrom'] != "") $filters["areaFrom"] = $_POST['txtAreaFrom'];
			if($_POST['txtAreaTo'] != "") $filters["areaTo"] = $_POST['txtAreaTo'];
			if($_POST['txtRoomsFrom'] != "") $filters["rooms_noFrom"] = $_POST['txtRoomsFrom'];
			if($_POST['txtRoomsTo'] != "") $filters["rooms_noTo"] = $_POST['txtRoomsTo'];
			if(isset($_POST['cbxSWF']) && $_POST['cbxSWF'] == 1) $filters["virtual_visit"] = true;
			if($_POST['cmbProvince'] != -1) {
				$filters["province"] = $_POST['cmbProvince'];
				$smarty->assign("districts", $api->GetDistricts($_POST['cmbProvince']));
			}
			if(isset($_POST['cmbDistrict'])) {
				$smarty->assign("districtsSelected", GetSelectedItem($_POST['cmbDistrict']));
				$items = "";
				foreach($_POST['cmbDistrict'] as $item) $items .= "'$item',";
				$filters["districts"] = substr($items, 0, strlen($items) - 1);
				$smarty->assign("locations", $api->GetLocations($_POST['cmbDistrict']));
			}
			if(isset($_POST['cmbLocation'])) {
				$smarty->assign("locationsSelected", GetSelectedItem($_POST['cmbLocation']));
				$items = "";
				foreach($_POST['cmbLocation'] as $item) $items .= "'$item',";
				$filters["locations"] = substr($items, 0, strlen($items) - 1);
				$smarty->assign("quarters", $api->GetQuarters($_POST['cmbLocation']));
			}
			if(isset($_POST['cmbQuarter'])) {
				$smarty->assign("quartersSelected", GetSelectedItem($_POST['cmbQuarter']));
				$items = "";
				foreach($_POST['cmbQuarter'] as $item) $items .= "'$item',";
				$filters["quarters"] = substr($items, 0, strlen($items) - 1);
			}
			if($_POST['cmbObject'] == "Mieszkanie"){
				$smarty->assign("showFlatTypes", true);
				if(isset($_POST['cmbFlatType'])) {
					$smarty->assign("flatTypesSelected", GetSelectedItem($_POST['cmbFlatType']));
					$items = "";
					foreach($_POST['cmbFlatType'] as $item) $items .= "'$item',";
					$filters["building_types"] = substr($items, 0, strlen($items) - 1);
				}
			}
			if($_POST['cmbObject'] == "Dom"){
				$smarty->assign("showHouseTypes", true);
				if(isset($_POST['cmbHouseType'])) {
					$smarty->assign("houseTypesSelected", GetSelectedItem($_POST['cmbHouseType']));
					$items = "";
					foreach($_POST['cmbHouseType'] as $item) $items .= "'$item',";
					$filters["house_types"] = substr($items, 0, strlen($items) - 1);
				}
			}
			if($_POST['cmbObject'] == "Dzialka"){
				$smarty->assign("showFieldDestiny", true);
				if(isset($_POST['cmbFieldDestiny'])) {
					$smarty->assign("fieldDestinySelected", GetSelectedItem($_POST['cmbFieldDestiny']));
					$items = "";
					if (array_key_exists('properties',$filters)){
						$filters['properties'] = array();
					}
					$filters['properties']['PrzeznaczenieDzialkiSet'] =  GetSetAsText($_POST['cmbFieldDestiny']); //substr($items, 0, strlen($items) - 1);
				}
			}
			if($_POST['cmbObject'] == "Lokal"){
				$smarty->assign("showLocalDestiny", true);
				if(isset($_POST['cmbLocalDestiny'])){
					$smarty->assign("localDestinySelected", GetSelectedItem($_POST['cmbLocalDestiny']));
					$items = "";
					if (array_key_exists('properties',$filters)){
						$filters['properties'] = array();
					}
					$filters['properties']['PrzeznaczenieLokalu'] =  GetSetAsText($_POST['cmbLocalDestiny']);
				}
			}
			//print_r($filters);
			$sort = "id DESC";
			if(isset($_POST["hidSort"])){
				switch ($_POST['hidSort']){
					case "L1" : $sort = "location ASC"; break;
					case "L2" : $sort = "location DESC"; break;
					case "P1" : $sort = "price ASC"; break;
					case "P2" : $sort = "price DESC"; break;
					case "A1" : $sort = "area ASC"; break;
					case "A2" : $sort = "area DESC"; break;
				}
				$smarty->assign("sort", $_POST['hidSort']);
			}
			$page = 0;
			if(isset($_POST["hidPage"])) $page = $_POST["hidPage"];
			$args = new RefreshEventArgs(5, $page, $filters, $sort);
			$smarty->assign("offers", $api->GetOffers($args, $lng));
			$smarty->assign("args", $args);
			$smarty->assign("page", $page);
		}break;
        case "newsLetterAdd":
        case "newsLetterDel":{
            ShowSearchForm($api, $smarty, $lng);
            $email = $_POST['nlEmail'];
            $msg = "";
            if(trim($email) != ""){
                if($_POST['hidAction'] == "newsLetterAdd"){
                    $msg = $api->AddMailToNewsLetter($email);
                    if($msg == "OK") $msg = "Na adres $email został wysłany link aktywacyjny. Prosimy o kliknięcie.";
                }else{
                    $msg = $api->RemoveMailFromNewsLetter($email);
                    if($msg == "OK") $msg = "E-mail: $email został pomyślnie usunięty z listy newsletter'a.";
                }
            }else $msg = "Nie podano adresu e-mail.";
            $smarty->assign("infoMsg", $msg);
        }break;
	}
}else if(isset($_GET['action'])){
	if(isset($_GET['id']) && !is_numeric($_GET['id'])) die("Acces denied! " . $_GET['id']);
	switch ($_GET['action']){
		case "offer":{
			$smarty->assign("ShowOfferDetails", true);
			$offer = $api->GetOffer($_GET['id'], $_GET['lng']);
            if($offer == null) die("Oferta nie istnieje.");
			$offer->NoteOfferView();

			$OfferVirtual = new OfferVirtual($offer->GetId());
			$offer->Virtual = $OfferVirtual->getUrl();
			if ($offer->Virtual){
				switch ($_GET['lng']){
					case 2047:
						$offer->Virtual .= "?lang=en";
						break;
					case 1031:
						$offer->Virtual .= "?lang=de";
						break;
					case 1049:
						$offer->Virtual .= "?lang=ru";
						break;
					case 1045:
					default:
						$offer->Virtual .= "?lang=pl";
						break;
				}
			}

			// var_dump($virtual);exit;
			// var_dump($offer);exit;

			$smarty->assign("offer", $offer);
			$showSpecial = false;
		}break;
		case "photo":{
			$smarty->assign("ShowPhoto", true);
			$photo = $api->GetOfferPhoto($_GET['id']);
            if($photo == null) die("Zdjęcie nie istnieje.");
			$smarty->assign("photo", $photo);
			$showSpecial = false;
		}break;
		case "swf":{
			$smarty->assign("ShowSWF", true);
			$photo = $api->GetOfferPhoto($_GET['id']);
            if($photo == null) die("Zdjęcie nie istnieje.");
			$smarty->assign("photo", $photo);
			$showSpecial = false;
		}break;
	}
}else{
	//main page
	ShowSearchForm($api, $smarty, $lng);
}

//load available languages
$smarty->assign("Languages", $api->GetAvailableLanguages());

if($showSpecial){
	$argsSpec = new RefreshEventArgs(5, 0, array(), "id DESC");
	$smarty->assign("ShowSpecialOffers", true);
	$smarty->assign("specialOffers", $api->GetOffersForFirstPage($argsSpec, $lng));
}

sajax_handle_client_request();
$smarty->assign("synchronizeDB", $api->GetSynchronizeJS());
$smarty->assign("ajax", sajax_show_javascript());
$smarty->assign("ApiObj", $api);
$smarty->display("index_o.tpl");


	function GetSetAsText($set){
		$str = "";
		if($set != null && is_array($set)){
			foreach($set as $val)
				$str .= $val . ", ";
		}
		$str = trim($str);
		return substr($str, 0, strlen($str) - 1);
	}

?>
