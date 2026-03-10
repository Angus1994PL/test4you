<?php

define('VIRGO_API_DIR', "virgo_api");
require_once VIRGO_API_DIR . '/virgo_api.php';

require_once '/Sajax.php';
require_once 'ajaxfunctions.php';

class Api extends VirgoAPI {
    public $synchronizeDB = '';
    public $synchronizeOffersCount = '';
    public $ajax = '';

    public function __construct(){
        sajax_init();
        sajax_export("AJAXSynchronizeDB");
        sajax_export("AJAXSynchronizeOffersCount");
        sajax_handle_client_request();

        $this->synchronizeDB = $this->GetSynchronizeJS();
        $this->synchronizeOffersCount = $this->GetSynchronizeOffersCount();
        $this->ajax = sajax_show_javascript();
    }
}

?>
