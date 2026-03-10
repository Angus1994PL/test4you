<?php

error_reporting(0);
ini_set('display_errors',0);

$filepath = __DIR__ . $_SERVER['REQUEST_URI'];
$filepath = preg_replace('/(\?anticache\=\d+)/', '', $filepath);

if(file_exists($filepath) && is_file($filepath)) {
    $ext = explode('.', $filepath);
    if(end($ext) == 'xml') {
        header("Content-type: text/xml");
    } else if(end($ext) == 'json') {
        header("Content-type: application/json");
    }
    else {
        header("Content-Type: image/jpeg");
        header("Content-Length: " .(string)(filesize($filepath)) );
    }
    echo file_get_contents($filepath);
    die;
}

if (!defined('VIRGO_API_DIR')) {
	define("VIRGO_API_DIR", "virgo_api");
}

$url = $_SERVER['REQUEST_URI'];
$matches = [];

// ZDJĘCIE SCENY
if(preg_match('/^\/virtual\/ofs_([0-9]{1,2})\/offer_([0-9]+)\/image_([0-9]+)\.jpg$/', $url, $matches)) {
    require_once(VIRGO_API_DIR . "/virgo_api.php");
    WebServiceVirgo::WS()->LoginEx(true);
    $buf = WebServiceVirgo::WS()->GetVirtualTourScene($matches[3]);
    if ($buf != null) {
        $virtual = new OfferVirtual($matches[2]);
        $filepath = $virtual->setSceneImage($matches[3], $buf);
        header("Content-Type: image/jpeg");
        header("Content-Length: " .(string)(filesize($filepath)));
        echo file_get_contents($filepath);
        die;
    }
}
// ZDJECIE PUNKTU
else if (preg_match('/^\/virtual\/ofs_([0-9]{1,2})\/offer_([0-9]+)\/image_point_([0-9]+)\.jpg$/', $url, $matches)){
    require_once(VIRGO_API_DIR . "/virgo_api.php");
    WebServiceVirgo::WS()->LoginEx(true);
    $buf = WebServiceVirgo::WS()->GetVirtualTourScenePointImg($matches[3]);
    if ($buf != null) {
        $virtual = new OfferVirtual($matches[2]);
        $filepath = $virtual->setScenePointImage($matches[3], $buf);
        header("Content-Type: " . mime_content_type($filepath));
        header("Content-Length: " .(string)(filesize($filepath)));
        echo file_get_contents($filepath);
        die;
    }
}
// MOBILNE ZDJĘCIE SCENY
elseif(preg_match('/^\/virtual\/ofs_([0-9]{1,2})\/offer_([0-9]+)\/image_mobile_([0-9]+).jpg$/', $url, $matches)) {
    require_once(VIRGO_API_DIR . "/virgo_api.php");
    WebServiceVirgo::WS()->LoginEx(true);
    $buf = WebServiceVirgo::WS()->GetVirtualTourScene($matches[3]);
    if ($buf != null) {
        $virtual = new OfferVirtual($matches[2]);
        $filepath = $virtual->setSceneMobileImage($matches[3], $buf);
        header("Content-Type: image/jpeg");
        header("Content-Length: " .(string)(filesize($filepath)));
        echo file_get_contents($filepath);
        die;
    }
}
elseif(preg_match('/^\/virtual\/ofs_([0-9]{1,2})\/offer_([0-9]+)\/image_mini_([0-9]+)\.jpg$/', $url, $matches)) {
    require_once(VIRGO_API_DIR . "/virgo_api.php");
    WebServiceVirgo::WS()->LoginEx(true);
    $buf = WebServiceVirgo::WS()->GetVirtualTourScene($matches[3]);
    if ($buf != null) {
        $virtual = new OfferVirtual($matches[2]);
        $filepath = $virtual->setSceneMiniImage($matches[3], $buf);
        header("Content-Type: image/jpeg");
        header("Content-Length: " .(string)(filesize($filepath)) );
        echo file_get_contents($filepath);
        die;
    }
}
// INTRO WIRTUALNEJ WIZYTY (NP. NA LISTĘ OFERT)
else if(preg_match('/^\/virtual\/ofs_([0-9]{1,2})\/offer_([0-9]+)\/intro\/$/', $url, $matches)) {
    require_once(VIRGO_API_DIR . "/virgo_api.php");
    $virtual = new OfferVirtual($matches[2]);
    echo $virtual->getVirtualTourIntro();
    die;
}
// CAŁA WIRTUALNA WIZYTA
else if(preg_match('/^\/virtual\/ofs_([0-9]{1,2})\/offer_([0-9]+)\/(\?lang=(pl|en|de|ru))?$/', $url, $matches)) {
    require_once(VIRGO_API_DIR . "/virgo_api.php");
    $virtual = new OfferVirtual($matches[2]);
    echo $virtual->getVirtualTour($_GET["lang"]);
    die;
}

// empty img
$im = imagecreatetruecolor(1, 1);
header('Content-Type: image/jpeg');
imagejpeg($im);
imagedestroy($im);
