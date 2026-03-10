<?php

if (!defined('VIRGO_API_DIR')) {
	define("VIRGO_API_DIR", "virgo_api");
}

require_once(VIRGO_API_DIR . "/virgo_api.php");
require_once 'functions.php';

$api = new VirgoAPI();
if(isset($_GET['offers'])){
    $count = $api->SynchronizeDB(true);
    echo "Synchronizing database completed ($count).";

    /* ---- Pobranie loga na potrzeby umieszczenia w WW zamiast statywu ---- */
    if (isset(Config::$Moduly) && isset(Config::$Moduly["web_api"]) && Config::$Moduly["web_api"] === true) {
        $serwisy = new Serwisy();
        $serwis = $serwisy->GetSerwis(Config::$WebGID);
        $department = $serwis->GetOddzial();
        if ($department != NULL && $department != ''){
            $department->GetLogoImageSrc('250_150');
        }
    }
    /* ---- KONIEC ---- */

}else if(isset($_GET['graphics'])){
    $ret = $api->SynchronizeGraphics();
    echo "Synchronizing graphics completed:<br />$ret";
}else if(isset($_GET['clearphotos'])){
    $idofe = $idusr = $idodd = 0;
    if (isset($_GET['idofe'])) $idofe = $_GET['idofe'];
    if (isset($_GET['idusr'])) $idusr = $_GET['idusr'];
    if (isset($_GET['idodd'])) $idodd = $_GET['idodd'];
    if($idofe>0){
        $ret = $api->ClearPhotos($idofe);
    }elseif($idusr > 0){
        $ret = $api->ClearWebPhotos($idusr);
    }elseif($idodd > 0){
        $ret = $api->ClearWebPhotos(0,$idodd);
    }

    echo "Deleteing photos completed:<br />$ret";
}else if(isset($_GET['index'])){
    $gp = new GaleriePozycje();
    $gp->IndeksujGaleriePozycjeDlaArtykulow();
}else{
    $ret = $api->SynchronizeSite();
	OffersHelper::clearCache();
    //Arkusze i skrypty do poprawki (zapis przy update strony)
    //if(!strpos("Arkusze/JS: 0",$ret)){
        arkuszeCSS("screen");
        arkuszeCSS("print");
        arkuszeCSS("screen","notatnik");
        arkuszeCSS("screen","wedrowka");
        scriptsJS();
    //}
    echo "Synchronizing site completed:<br />$ret";
}

rebuildRobotsFile();

function rebuildRobotsFile() {
    if(file_exists('robots.txt')) {
        $robotsFileContents = file_get_contents('robots.txt');
        $robotsFileContents = preg_replace('/Sitemap: ([a-zA-Z0-9\s\-\:\/\.]+)\r/', 'Sitemap: '.Config::$AppDomain."/sitemap.xml\r\n", $robotsFileContents);
        file_put_contents('robots.txt', $robotsFileContents);
    }
}

function arkuszeCSS($tryb='screen',$dla_strony=""){
        $return_string = "";
        $as = new ArkuszeSkrypty();
        $ss = new Serwisy();
        $serwis = $ss->GetSerwis(Config::$WebGID);

        $hta = array();
        $hta['GIDSerwis']=$serwis->GetGID();
        switch($dla_strony){
            case "notatnik":
                $hta['Rodzaj']="Podstawowy";
                $hta['Opis']="wydruk_notatnik.aspx";
                break;
            case "wedrowka":
                $hta['Rodzaj']="Podstawowy";
                $hta['Opis']="wedrowka3dNaOfercie";
                break;
            default:
                switch($tryb){
                    case "screen":$hta['Rodzaj']="Podstawowy";break;
                    case "print":$hta['Rodzaj']="DoDruku";break;
                }
        }
        $arks = $as->PobierzArkusze($hta);

        foreach($arks as $ar){
            $return_string.=$ar->GetTresc();
        }
        //zamiana sciezki do grafiki
        $return_string = str_replace("grafika/", "../grafika/", $return_string);
        $return_string = str_replace("Grafika/", "../grafika/", $return_string);
        //zamiana handlerow webi
        $return_string = str_replace("webi.ashx?", "../grafika/", $return_string);

        switch($dla_strony){
            case "notatnik":
                $fh = fopen("css/notatnik_wydruk.css", "w");
                break;
            case "wedrowka":
                $fh = fopen("css/wedrowka3d_outer.css", "w");
                break;
            default:
                $fh = fopen("css/outer_".$tryb.".css", "w");
        }

        fwrite($fh,$return_string);
        fclose($fh);
        //echo $return_string;
    }

    function scriptsJS(){
        $return_string = "";
        $as = new ArkuszeSkrypty();
        $ss = new Serwisy();
        $serwis = $ss->GetSerwis(Config::$WebGID);

        $hta = array();
        $hta['GIDSerwis']=$serwis->GetGID();

        $arks = $as->PobierzSkrypty($hta);

        foreach($arks as $ar){
            $return_string.=$ar->GetTresc();
        }

        $fh = fopen($_SERVER['DOCUMENT_ROOT'].Config::$AppPath."/js/outer.js", "w");
        fwrite($fh,$return_string);
        fclose($fh);
    }
?>
