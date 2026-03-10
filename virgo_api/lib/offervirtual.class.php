<?php

/**
 * Wirtualna wizyta dla oferty
 */
class OfferVirtual {

    /**
     * ID oferty
     */
    private $offerID;

    /**
     * schemat.xml
     */
    private $xml;

    /**
     * schemat.json
     */
    private $json;

    /**
     * Ścieżka do folderu przechowującego wszystkie wirtualne wizyty
     */
    private $virtualPath;

    /**
     * Adres folder przechowującego wszystkie wirtualne wizyty
     */
    private $virtualUrl;


    public function __construct($offerID)
    {
        $this->offerID = $offerID;
        $this->virtualPath = $_SERVER['DOCUMENT_ROOT'] . '/virtual/';
        if(!file_exists($this->virtualPath)) {
            mkdir($this->virtualPath);
        }
        $this->virtualUrl = '/virtual/';
    }


    /**
     * Dodaje pojedynczą scenę (wraz z punktami) do wynikowego pliku XML (schemat.xml)
     *
     * @param stdClass $xmlNode
     */
	public function addScene($xmlNode)
    {
        if((int)$xmlNode->Typ != 1) {
            return false;
        }

        if(!$this->xml) {
            $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" standalone="yes"?><prezentacja/>');
            $mapa = $this->xml->addChild('mapa');
            $node = dom_import_simplexml($mapa);
            $no   = $node->ownerDocument;
            $node->appendChild($no->createCDATASection('brak'));
        }

        $pano = $this->xml->addChild('pano');
        $pano->addAttribute('id', (int)$xmlNode['Id']);
        $pano->addAttribute('nazwa', isset($xmlNode->Nazwa) ? (string)$xmlNode->Nazwa : '');
        $pano->addAttribute('link', $this->getSceneImageUrl((int)$xmlNode['Id']));
        $pano->addAttribute('min', $this->getSceneMiniImageUrl((int)$xmlNode['Id']));
        $pano->addAttribute('mx', '0');
        $pano->addAttribute('my', '0');
        $pano->addAttribute('lp', (int)$xmlNode->Lp);

        if (isset(Config::$Moduly) && isset(Config::$Moduly["web_api"]) && Config::$Moduly["web_api"] === true) {
            $ss = new Serwisy();
            $s = $ss->GetSerwis(Config::$WebGID);

            if (!empty($s)) {
                $dep = $s->GetOddzial();
                if (!empty($dep)) {
                    $logos = $pano->addChild('logo');
                    $logos->addAttribute('link', isset($dep) ? (string)$dep->GetLogoImageSrc('250_150') : '');
                    $logos->addAttribute('nazwa', isset($dep) ? (string)$dep->GetName() : '');
                }
            }
        }

        $points = (string)$xmlNode->Punkty;
        if($points = json_decode($points, true)) {
            if(!empty($points)) {
                foreach($points as $item) {
                    if(isset($item['typ']) && (int)$item['typ'] == 1) {
                        $pano->addAttribute('slat', (string)$item['px']);
                        $pano->addAttribute('slon', (string)$item['py']);
                    }
                    else {
                        $point = $pano->addChild('punkt');
                        $point->addAttribute('id', (int)$item['id']);
                        $point->addAttribute('nazwa', isset($item['nazwa']) ? (string)$item['nazwa'] : '');
                        $point->addAttribute('link', $this->getSceneImageUrl((int)$item['id']));
                        $point->addAttribute('px', (string)$item['px']);
                        $point->addAttribute('py', (string)$item['py']);
                        $point->addAttribute('pz', (string)$item['pz']);
                        $point->addAttribute('typ', (string)$item['link'] ? '1' : '2');
                    }
                }
            }
        }
    }


    /**
     * Zwraca ID pierwszej sceny z pliku XML
     * @return int
     */
    public function getFirstSceneId()
    {
        if($this->getXML()) {
            //$xml = simplexml_load_file($this->getXML());
            $xml = simplexml_load_file($this->getVirtualFolderPath() . '/schemat.xml');

            $min = null;
            $id = (int)$xml->pano[0]['id'];
            foreach($xml->pano as $pano) {
                if($min === null || $pano['lp'] < $min) {
                    $min = (int)$pano['lp'];
                    $id = (int)$pano['id'];
                }
            }

            return $id;
        }
        return 0;
    }


    /**
     * Zwraca adres do obrazu ze sceną
     * @param int $id       ID sceny
     * @return string
     */
    public function getSceneImageUrl($id)
    {
        return $this->getVirtualFolderUrl() . 'image_'.$id.'.jpg';
    }


    /**
     * Zwraca adres do obrazu ze sceną
     * @param int $id       ID sceny
     * @return string
     */
    public function getSceneMiniImageUrl($id)
    {
        return $this->getVirtualFolderUrl() . 'image_mini_'.$id.'.jpg';
    }


    /**
     * Zwraca adres do obrazu ze sceną
     * @param int $id       ID sceny
     * @return string
     */
    public function getSceneMobileImageUrl($id)
    {
        return $this->getVirtualFolderUrl() . 'image_mobile_'.$id.'.jpg';
    }


    /**
     * Zwraca ścieżkę na serwerze do obrazu ze sceną
     * @param int $id       ID sceny
     * @return string
     */
    public function getSceneImagePath($id)
    {
        return $this->getVirtualFolderPath() . '/image_'.$id.'.jpg';
    }


    /**
     * Zwraca ścieżkę na serwerze do obrazu z punktem
     * @param int $id       ID punktu
     * @return string
     */
    public function getScenePointImagePath($id)
    {
        return $this->getVirtualFolderPath() . '/image_point_'.$id.'.jpg';
    }


    /**
     * Zwraca ścieżkę na serwerze do obrazu z miniaturą sceny
     * @param int $id       ID sceny
     * @return string
     */
    public function getSceneMiniImagePath($id)
    {
        return $this->getVirtualFolderPath() . '/image_mini_'.$id.'.jpg';
    }


    /**
     * Zwraca ścieżkę na serwerze do obrazu ze sceną dla urządzeń mobilnych
     * @param int $id       ID sceny
     * @return string
     */
    public function getSceneMobileImagePath($id)
    {
        return $this->getVirtualFolderPath() . '/image_mobile_'.$id.'.jpg';
    }


    /**
     * Zapisuje pojedynczy obraz sceny
     * @param int $id       ID sceny
     * @param string $buf   Obraz
     */
    public function setSceneImage($id, $buf)
    {
        $filepath = $this->getSceneImagePath($id);
        $file = fopen($filepath, "wb");
        fwrite($file, $buf);
        fclose($file);
        return $filepath;
    }


    /**
     * Zapisuje pojedynczy obraz punktu
     * @param int $id       ID punktu
     * @param string $buf   Obraz
     */
    public function setScenePointImage($id, $buf)
    {
        $filepath = $this->getScenePointImagePath($id);
        $file = fopen($filepath, "wb");
        fwrite($file, $buf);
        fclose($file);
        return $filepath;
    }


    /**
     * Zapisuje miniature pojedynczego obrazu sceny na potrzeby urządzeń mobilnych
     * @param int $id       ID sceny
     * @param string $buf   Obraz
     */
    public function setSceneMobileImage($id, $buf)
    {
        $filepath = $this->getSceneMobileImagePath($id);
        $file = fopen($filepath, "wb");
        fwrite($file, $buf);
        fclose($file);

        // resize
        list($width, $height) = getimagesize($filepath);
        $nWidth = 1024;
        $nHeight = round($nWidth * $height / $width);
        $image = @imagecreatefromjpeg($filepath);

        $nImage = imagecreatetruecolor($nWidth, $nHeight);
        imagecopyresampled($nImage, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);

        imagejpeg($nImage, $filepath, 100);

        return $filepath;
    }


    /**
     * Zapisuje miniature pojedynczego obrazu sceny
     * @param int $id       ID sceny
     * @param string $buf   Obraz
     */
    public function setSceneMiniImage($id, $buf)
    {
        $filepath = $this->getSceneMiniImagePath($id);
        $file = fopen($filepath, "wb");
        fwrite($file, $buf);
        fclose($file);

        // resize
        list($width, $height) = getimagesize($filepath);
        $nHeight = 100;
        $nWidth = round($width * $nHeight / $height);
        $image = @imagecreatefromjpeg($filepath);

        $nImage = imagecreatetruecolor($nWidth, $nHeight);
        imagecopyresampled($nImage, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);

        imagejpeg($nImage, $filepath, 100);

        return $filepath;
    }


    /**
     * Zwraca ścieżkę na serwerze do folderu z obrazami/xml-em dla danej oferty
     * @return string
     */
    private function getVirtualFolderPath()
    {
        $suf = $this->offerID < 100 ? $this->offerID : substr($this->offerID, 0, 2);
        return $this->virtualPath . 'ofs_' . $suf . '/offer_' . $this->offerID;
    }


    /**
     * Zwraca url do folderu z obrazami/xml-em dla danej oferty
     * @return string
     */
    private function getVirtualFolderUrl()
    {
        $suf = $this->offerID < 100 ? $this->offerID : substr($this->offerID, 0, 2);
        return $this->virtualUrl . 'ofs_' . $suf . '/offer_' . $this->offerID . '/';
    }


    /**
     * Czyści folder ze zdjęć
     */
    public function clearPhotos($folder = false)
    {
        $path = $this->getVirtualFolderPath();
        if(file_exists($path)) {
            foreach(scandir($path) as $file) {
                //if(is_file($path . '/' . $file) && !in_array($file, ['.', '..', 'schemat.xml'])) {
				if(is_file($path . '/' . $file)) {
                    unlink($path . '/' . $file);
                }
                if ($folder) $this->rrmdir($path);
            }
        }
    }

    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }


    /**
     * Usuwa całkowicie folder z wirtualką
     */
    public function deleteVirtual()
    {
        $path = $this->getVirtualFolderPath();
        if(file_exists($path)) {
            foreach(scandir($path) as $file) {
                if(is_file($path . '/' . $file) && !in_array($file, ['.', '..'])) {
                    unlink($path . '/' . $file);
                }
            }
            rmdir($path);
        }
    }


    /**
     * Zapisuje plik schemat.xml w folderze oferty
     * @return int
     */
    public function saveXML($idLng = 1045)
    {
        if($this->xml) {
            $path = $this->getVirtualFolderPath();

            if(!file_exists($path)) {
                $subpath = implode('/', array_slice(explode('/', $path), 0, -1));
                if(!file_exists($subpath)) {
                    mkdir($subpath);
                }
                mkdir($path);
            }

            $offer = Offers::GetOffer($this->offerID, $idLng);
            $offer->SetHasVirtual(1);
            Offers::AddEditOffer($offer);
            Offers::setHasVirtual();

            return file_put_contents($path . '/schemat.xml', $this->xml->asXML());
        }
        return 0;
    }

    public function setJson($json){
        $this->json = $json;
    }

    public function saveJson($idLng = 1045)
    {
        if($this->json) {
            $path = $this->getVirtualFolderPath();

            if(!file_exists($path)) {
                $subpath = implode('/', array_slice(explode('/', $path), 0, -1));
                if(!file_exists($subpath)) {
                    mkdir($subpath);
                }
                mkdir($path);
            }

            $offer = Offers::GetOffer($this->offerID, $idLng);
            $offer->SetHasVirtual(1);
            Offers::AddEditOffer($offer);
            Offers::setHasVirtual();

            return file_put_contents($path . '/schemat2.json', $this->json);
        }
        return 0;
    }


    /**
     * Zwraca adres pliku schemat.xml
     * @return boolean|string
     */
    private function getXML()
    {
        $path = $this->getVirtualFolderPath() . '/schemat.xml';
        if(file_exists($path)) {
            return $this->getVirtualFolderUrl() . 'schemat.xml';
        }
        return false;
    }

    public function getJson()
    {
        $path = $this->getVirtualFolderPath() . '/schemat2.json';
        if(file_exists($path)) {
            return $this->getVirtualFolderUrl() . 'schemat2.json';
        }
        return false;
    }


    /**
     * Zwraca adres pliku schemat.xml
     * @return boolean|string
     */
    private function getMobileXML()
    {
        $path = $this->getVirtualFolderPath() . '/schemat_mobile.xml';
        if(file_exists($path)) {
            return $this->getVirtualFolderUrl() . 'schemat_mobile.xml';
        }
        else {
            $oryginalPath = $this->getVirtualFolderPath() . '/schemat.xml';
            if(file_exists($oryginalPath)) {
                $xml = file_get_contents($oryginalPath);
                $xml = preg_replace_callback('/link="([^ ]*)"/', function($matches){
                    if(isset($matches[1])) {
                        return 'link="' . str_replace('.jpg', '_mobile.jpg', $matches[1]) . '"';
                    }
                }, $xml);
                file_put_contents($path, $xml);
                return $this->getVirtualFolderUrl() . 'schemat_mobile.xml';
            }
        }
        return false;
    }


    /**
     * Zwraca URL do wirtualnej wizyty (jeśli istnieje)
     * @return boolean|string
     */
    public function getUrl()
    {
        if ($this->getXML() || $this->getJson()){
            return $this->getVirtualFolderUrl();
        }
        return false;
    }


    /**
     * Zwraca URL do prezentacji wirtualnej wizyty (jeśli istnieje)
     * @return boolean|string
     */
    public function getIntroUrl()
    {
        if($this->getXML()) {
            return $this->getVirtualFolderUrl() . 'intro/';
        }
        return false;
    }


    /**
     * Wyświetla wirtualną wizytę (w wersji do przeglądania)
     * @return boolean|string
     */
    public function getVirtualTourNew()
    {
        //var_dump('test');die();
        $isMobile = $this->isMobileDevice();

        if($isMobile) {
            $xml = $this->getMobileXML();
        }
        else {
            $xml = $this->getXML();
        }

        if($xml) {
            $html ='<!doctype html>
                <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <title>Odtwarzacz wirtualnych wizyt</title>
                    <base href="/">

                    <link rel="stylesheet" type="text/css" href="~/Content/lib/pano/player.pano.css" media="screen" />

                    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
                    <script src="/js/core.pano.js" type="text/javascript"></script>
                    <script src="/js/player.pano.js" type="text/javascript"></script>

                </head>
                <body>
                    <div id="points" style="display: none"></div>
                    <div id="container1" style="position: absolute;width:100%;height:100%; overflow: hidden"> </div>
                    <script>
                            $(function () {
                                var settings = {
                                    container: "container1",
                                    url: "'.$this->getJson().'",
                                    sceneId: '.$this->getFirstSceneId().',
                                    pointContainer: "points",
                                    showScenes: true,
                                    showMenu: true
                                };
                                $().panoPlayer(settings);
                            });
                    </script>
                </body>
                </html>';
            return $html;
        }
        return false;
    }

    public function getVirtualTour($lang = NULL)
    {
        $json = $this->getJson();

        if ($json){
            switch ($lang){
                case "en":
                    $langId = 2047;
                    break;
                case "de":
                    $langId = 1031;
                    break;
                case "ru":
                    $langId = 1049;
                    break;
                case "pl":
                default:
                    $langId = 1045;
            }
            $langs = [
                "vir_web_vv_fullscreen" => JezykiTeksty::Lng(strtolower("vir_web_vv_fullscreen"),$langId),
                "vir_web_vv_autorotate_on" => JezykiTeksty::Lng(strtolower("vir_web_vv_autorotate_on"),$langId),
                "vir_web_vv_autorotate_off" => JezykiTeksty::Lng(strtolower("vir_web_vv_autorotate_off"),$langId),
                "vir_web_vv_zoom_in" => JezykiTeksty::Lng(strtolower("vir_web_vv_zoom_in"),$langId),
                "vir_web_vv_zoom_out" => JezykiTeksty::Lng(strtolower("vir_web_vv_zoom_out"),$langId),
                "vir_web_vv_mode_vr" => JezykiTeksty::Lng(strtolower("vir_web_vv_mode_vr"),$langId)
            ];

            $html ='<!DOCTYPE html>'."\n";
            $html.='<html lang="en">'."\n";
            $html.='<head>';
                $html.='<meta charset="utf-8"><meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">';
                $html.='<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>';
                $html.='<link rel="stylesheet" type="text/css" href="' . Config::$AppPath . '/js/vv/player.pano.css" media="screen" />';
                $html.='<script type="text/javascript">
                            var langs = ' . json_encode($langs) . ';
                            window.getTranslation = function(key){
                                return langs[key];
                            }
                        </script>';
                $html.='<script src="' . Config::$AppPath . '/js/vv/core.pano.js" type="text/javascript"></script>';
                $html.='<script src="' . Config::$AppPath . '/js/vv/player.pano.js" type="text/javascript"></script>';
            $html.='</head>';
            $html.='<body class="pano_main">';
                $html.='<div id="title_pano_360" class="title_pano"></div>';
                $html.='<div id="points" style="display: none"></div>';
                $html.='<div id="virtualCont" style="position: absolute;width:100%;height:100%; overflow: hidden"></div>';
                $html.='
                    <script>
                    $(window).load(function(){
                        var apiTmp = $().panoPlayer({
                            container: \'virtualCont\',
                            url: \'' . $json . '\',
                            sceneId: ' . $this->getFirstSceneId() . ',
                            pointContainer: \'points\',
                            showScenes: true,
                            showMenu: true
                        });
                    });
                    </script>
                ';
            $html.='</body>';
            $html.= '</html>';
            return $html;
        } else {
            $isMobile = $this->isMobileDevice();
            if ($isMobile === TRUE){
                $xml = $this->getMobileXML();
            } else {
                $xml = $this->getXML();
            }
            if ($xml === FALSE){
                return FALSE;
            }
            $html ='<!DOCTYPE html>'."\n";
            $html.='<html lang="en">'."\n";
            $html.='<head>';
                $html.='<meta charset="utf-8"><meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">';
                $html.='<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>';
                $html.='<script src="/js/jquery-migrate-1.2.1.min.js"></script>';
                $html.='<link rel="stylesheet" type="text/css" href="/js/vv/player.pano.css" media="screen" />';
                $html.='<script src="/js/three.min.js?v=1"></script>';
                $html.='<script src="/js/html5pano.js?v=1"></script>';
                $html.='<style>
                            html,body{margin:0;padding:0;width:100%;height:100%;}
                            #ww_hr{
                            margin: 1px 0 1px 0;
                            border: 0;
                            height: 2px;
                            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0));
                            }
                            #ww_bottomDiv{
                            fontcolor:white;
                            font-Family:Khand,sans-serif;
                            background-image: linear-gradient(to top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2));
                            }
                            #ww_min{
                            fontcolor:white;
                            font-Family:Khand,sans-serif;
                            background-image: linear-gradient(to top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.1));
                            }
                            #ww_inf{
                            fontcolor:white;
                            font-Family:Khand,sans-serif;
                            background-image: linear-gradient(to top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.1));
                            }
                            #ww_tooltip{
                            color:#FFFFFF;
                            font-Family:Khand,sans-serif;
                            font-size : 14px;
                            background-color : rgba(0, 0, 0, 0.6);
                            border-radius : 3px;
                            padding : 5px;
                            text-align : center;
                            }
                            #ww_title{
                            font-Family :Khand,sans-serif;
                            font-size : 14px;
                            color:white;
                            font-weight : 400;
                            text-shadow : 1px 1px 10px #000000,1px 1px 10px #000000,1px 1px 10px #000000;
                            }
                            #ww_title_NO{
                            font-Family :Khand,sans-serif;
                            font-size : 12px;
                            color:white;
                            font-weight : 400;
                            background-color : rgba(0, 0, 0, 0.6);
                            border-radius : 4px;
                            padding : 5px;
                            padding-right : 5px;
                            }
                            #virtualCont {width:100%;height:100%;}
                        </style>';
            $html.='</head>';
            $html.='<body>';
                $html.='<div id="points" style="display: none"></div>';
                $html.='<div id="virtualCont"></div>';
                $html.='
                    <script>
                    $(window).load(function(){
                        $("#virtualCont").html5pano({
                            \'typ\': \'pano\',
                            \'xml\': \''.$xml.'\',
                            \'IDxml\': '.$this->getFirstSceneId().',
                            \'tryb\': 0,
                            \'lowSpec\': '.($isMobile?'true':'false').'
                        });
                    });
                    </script>
                ';
            $html.='</body>';
            $html.= '</html>';
            return $html;
        }
        return false;
    }


    /**
     * Wyświetla wirtualną wizytę (w wersji prezentacyjnej)
     * @return boolean|string
     */
    public function getVirtualTourIntro()
    {
        $isMobile = $this->isMobileDevice();

        if($isMobile) {
            $xml = $this->getMobileXML();
        }
        else {
            $xml = $this->getXML();
        }

        if($xml) {
            $html ='<!DOCTYPE html>'."\n";
            $html.='<html lang="en">'."\n";
            $html.='<head>';
                $html.='<meta charset="utf-8"><meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">';
                $html.='<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>';
                $html.='<script src="/js/jquery-migrate-1.2.1.min.js"></script>';
                $html.='<script src="/js/three.min.js?v=1"></script>';
                $html.='<script src="/js/html5pano.js?v=1"></script>';
                $html.='<style>
                            html,body{margin:0;padding:0;width:100%;height:100%;}
                            #ww_hr{
                            margin: 1px 0 1px 0;
                            border: 0;
                            height: 2px;
                            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0));
                            }
                            #ww_bottomDiv{
                            fontcolor:white;
                            font-Family:Khand,sans-serif;
                            background-image: linear-gradient(to top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2));
                            }
                            #ww_min{
                            fontcolor:white;
                            font-Family:Khand,sans-serif;
                            background-image: linear-gradient(to top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.1));
                            }
                            #ww_inf{
                            fontcolor:white;
                            font-Family:Khand,sans-serif;
                            background-image: linear-gradient(to top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.1));
                            }
                            #ww_tooltip{
                            color:#FFFFFF;
                            font-Family:Khand,sans-serif;
                            font-size : 14px;
                            background-color : rgba(0, 0, 0, 0.6);
                            border-radius : 3px;
                            padding : 5px;
                            text-align : center;
                            }
                            #ww_title{
                            font-Family :Khand,sans-serif;
                            font-size : 14px;
                            color:white;
                            font-weight : 400;
                            text-shadow : 1px 1px 10px #000000,1px 1px 10px #000000,1px 1px 10px #000000;
                            }
                            #ww_title_NO{
                            font-Family :Khand,sans-serif;
                            font-size : 12px;
                            color:white;
                            font-weight : 400;
                            background-color : rgba(0, 0, 0, 0.6);
                            border-radius : 4px;
                            padding : 5px;
                            padding-right : 5px;
                            }
                            #virtualCont {width:100%;height:100%;}
                        </style>';
            $html.='</head>';
            $html.='<body>';
                $html.='<div id="virtualCont"></div>';
                $html.='
                    <script>
                    $(window).load(function(){
                        $("#virtualCont").html5pano({
                            \'typ\': \'pano\',
                            \'xml\': \''.$xml.'\',
                            \'IDxml\': '.$this->getFirstSceneId().',
                            \'tryb\': 3,
                            \'lowSpec\': '.($isMobile?'true':'false').'
                        });
                    });
                    </script>
                ';
            $html.='</body>';
            $html.= '</html>';
            return $html;
        }
        return false;
    }


    /**
     * Czy wyświetlane na urządzeniu mobilnym
     *
     * @return boolean
     */
    private function isMobileDevice()
    {
        define('BASEPATH', 'BASEPATH');
        include($_SERVER['DOCUMENT_ROOT'].'/application/config/user_agents.php');

        $useragent=$_SERVER['HTTP_USER_AGENT'];

        foreach ($mobiles as $key => $val) {
            if ((strpos(strtolower($useragent), $key)) !== FALSE) {
                return true;
            }
        }
        return false;
    }

}
