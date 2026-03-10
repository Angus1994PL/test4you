<?php
/**
 * Class assisting offers mangment
 * @author Marcin Welc
 */

class OffersHelper {

    const CACHE_DIR = 'cache/virgo/';
	public static $props_arr = array("Kraj","IloscPieter","RokBudowy","RodzajDomu","PierwszaStrona","RodzajObiektu","SposobPrzyjecia","IloscOdslonWWW","StanPrawny","StatusWlasnosci","UmeblowanieLista","PowierzchniaDzialki","Zamiana","UwagiOpis","UwagiNieruchomosc");

    public static function getProps($val, $byId = false){
        if(!$byId) {
            $p=Properties::GetPropertyName($val);
            return isset($p)?$p->GetID():0;
        }
        $p=Properties::GetProperty($val);
        return isset($p)?$p->GetName():0;
    }

    public static function setMethodResultCache($data, $customTail = '') {

    	$callers = debug_backtrace();

    	$cacheDir = $_SERVER['DOCUMENT_ROOT'] . Config::$AppPath . '/' . self::CACHE_DIR . strtolower($callers[1]['class']) . '/';
    	if(!file_exists($cacheDir)) mkdir($cacheDir, 0755, true);
    	$cacheFileName = md5(serialize($callers[1]) . serialize($customTail)) . '.cache';
    	$cacheFilePath = $cacheDir . $cacheFileName;

    	if(!file_exists($cacheFilePath)) file_put_contents($cacheFilePath, serialize($data));

    }

    public static function getMethodResultCache($customTail = '') {
    	$callers=debug_backtrace();

    	$cacheDir = $_SERVER['DOCUMENT_ROOT'] . Config::$AppPath . '/' . self::CACHE_DIR . strtolower($callers[1]['class']) . '/';
    	$cacheFileName = md5(serialize($callers[1]) . serialize($customTail)) . '.cache';
    	$cacheFilePath = $cacheDir . $cacheFileName;

    	if(file_exists($cacheFilePath)) return unserialize(file_get_contents($cacheFilePath));
    	else return false;

    }

    public static function clearCache() {
    	if(file_exists($_SERVER['DOCUMENT_ROOT'] . Config::$AppPath . '/' . self::CACHE_DIR)) {
            self::deleteDir($_SERVER['DOCUMENT_ROOT'] . Config::$AppPath . '/' . self::CACHE_DIR);
        }
    }

    private static function deleteDir($path)
    {
    	$class_func = array(__CLASS__, __FUNCTION__);
    	return is_file($path) ?
    	@unlink($path) :
    	array_map($class_func, glob($path.'/*')) == @rmdir($path);
    }

}

?>
