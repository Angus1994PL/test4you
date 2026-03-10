<?php

define("VIRGO_API_DIR", "virgo_api");

require_once(VIRGO_API_DIR . "/virgo_api.php");
require_once 'functions.php';

if(!isset($_GET['element'])) die("Error. No parameters.");
if(!isset($_GET['gid']) && !$_GET['element']=='11') die("Error. No parameters.");

$element = $_GET['element'];
$gid = 0;
if (isset($_GET['gid'])) $gid = $_GET['gid'];
$del = false;
if(isset($_GET['del']) && $_GET['del'] == 1) $del = true;
$force = false;
if(isset($_GET['force']) && $_GET['force'] == 1) $force = true;

$api = new VirgoAPI();
$ret = $api->SynchronizeSiteElement($element, $gid, $del, $force);
echo "Synchronizing site element completed:<br />$ret";

?>
