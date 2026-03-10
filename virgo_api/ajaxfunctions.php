<?php
require_once VIRGO_API_DIR.'/../functions.php';

function AJAXGetDistricts($province, $idLng=1045){
	$api = new VirgoAPI();
	return $api->GetDistricts($province, $idLng);
}

function AJAXGetLocations($districts, $idLng = 1045){
	$api = new VirgoAPI();
	$arr = explode(",", $districts);	
	return $api->GetLocations($arr, null, $idLng);
}

function AJAXGetQuarters($locations, $idLng = 1045){
	$api = new VirgoAPI();
	$arr = explode(",", $locations);	
	return $api->GetQuarters($arr, $idLng);
}

function AJAXGetInvestmentsDistricts($province, $idLng = 1045){
	$api = new VirgoAPI();
	return $api->GetInvestmentsDistricts($province, $idLng);
}

function AJAXGetInvestmentsLocations($districts, $idLng = 1045){
	$api = new VirgoAPI();
	$arr = explode(",", $districts);	
	return $api->GetInvestmentsLocations($arr, $idLng);
}

function AJAXGetInvestmentsQuarters($locations, $idLng = 1045){
	$api = new VirgoAPI();
	$arr = explode(",", $locations);	
	return $api->GetInvestmentsQuarters($arr, $idLng);
}

function AJAXSynchronizeDB(){
	$api = new VirgoAPI();
	return $api->SynchronizeDB();
}

function AJAXSynchronizeOffersCount(){
    $api = new VirgoAPI();
    return $api->SynchronizeOffersCount();
}

?>