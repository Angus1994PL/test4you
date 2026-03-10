<?php

if (!defined('WEB_API_DIR')) {
define('WEB_API_DIR', "web_api");
}
require_once WEB_API_DIR . '/web_api.php';

/**
 * Include all required files.
 */

if (!defined('VIRGO_API_DIR')) {
    define('VIRGO_API_DIR', "virgo_api");
}

class ApiAutoLoaderVirgo {
    static public function autoload($class_name) {
        $cwd = str_replace("\\", "/", getcwd());

        $prefix = (strrpos($cwd, VIRGO_API_DIR) === false) ? VIRGO_API_DIR . "/" : "";

        $reqString = $prefix . "lib/" . strtolower($class_name) . ".class.php";

        if($class_name == "WebServiceVirgo")
            $reqString = $prefix . "ws/webservice.class.php";

        if(file_exists($reqString)) require_once $reqString;
    }
}

spl_autoload_register('ApiAutoLoaderVirgo::autoload');

?>
