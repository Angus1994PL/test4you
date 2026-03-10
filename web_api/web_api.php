<?php
date_default_timezone_set("Europe/Warsaw");

/**
 * Include all required files.
 */

if (!defined('WEB_API_DIR')) {
    define('WEB_API_DIR', "web_api");
}

require_once WEB_API_DIR . '/config.php';

require_once WEB_API_DIR . '/db/idatabase.interface.php';
require_once WEB_API_DIR . '/db/mysql.class.php';
require_once WEB_API_DIR . '/db/postgresql.class.php';
require_once WEB_API_DIR . '/db/db.class.php';

class ApiAutoLoader {

    static public function autoload($class_name) {
        $cwd = str_replace("\\", "/", getcwd());

        $prefix = (strrpos($cwd, WEB_API_DIR) === false) ? WEB_API_DIR . "/" : "";

        $reqString = $prefix . "lib/" . strtolower($class_name) . ".class.php";

        if($class_name == "WebServiceWeb" )
            $reqString = $prefix . "ws/webservice.class.php";

        if(strpos($cwd,"libra_api")!==false){
            $cwd=str_replace("libra_api","web_api",$cwd);
            $reqString = $cwd."/lib/" . strtolower($class_name) . ".class.php";
        }
        if(strpos($cwd,"virgo_api")!==false){
            $cwd =  str_replace("virgo_api","web_api",$cwd);
            $reqString = $cwd."/lib/" . strtolower($class_name) . ".class.php";
        }
        if(strpos($cwd,"magellan_api")!==false){
            $cwd =  str_replace("magellan_api","web_api",$cwd);
            $reqString = $cwd."/lib/" . strtolower($class_name) . ".class.php";
        }
        if(strpos($cwd,"pegasus_api")!==false){
            $cwd =  str_replace("pegasus_api","web_api",$cwd);
            $reqString = $cwd."/lib/" . strtolower($class_name) . ".class.php";
        }

        if(file_exists($reqString)) {require_once $reqString;}
    }
}

spl_autoload_register('ApiAutoLoader::autoload');

function convert($size)
{
    $unit=array('B','KB','MB','GB','TB','PB');
    return "Memory usage: " . round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>
