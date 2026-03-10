<?php

define("VIRGO_API_DIR", ".");

include("virgo_api.php");

$api = new VirgoAPI();
$count = $api->SynchronizeDB();
echo "Synchronizing database completed ($count).";

?>