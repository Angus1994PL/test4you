<?php

define("VIRGO_API_DIR", "virgo_api");

require_once("smarty/Smarty.class.php");
require_once(VIRGO_API_DIR . "/virgo_api.php");
require("Sajax.php");
require(VIRGO_API_DIR . "/ajaxfunctions.php");

sajax_init(); 
sajax_export("AJAXSynchronizeDB"); 
sajax_handle_client_request();

$smarty = new Smarty();
$api = new VirgoAPI();

sajax_handle_client_request();
$smarty->assign("synchronizeDB", $api->GetSynchronizeJS());
$smarty->assign("ajax", sajax_show_javascript());
$smarty->display("index.tpl");

?>