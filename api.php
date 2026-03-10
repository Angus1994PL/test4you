<?php
require_once 'web_api/config.php';
require_once 'functions.php';

foreach(Config::$Moduly as $mod=>$val){
    if($val===true && $mod!="web_api") require_once $mod."/api.php";
}
