<?php
/**
 * Description of grafiki
 * @author kubak
 */

class Grafiki {

    /**
     * Zwraca ścieżkę do folderu z grafikami.
     * @return string
     */
    public static function GetPath(){
        //przygotowanie folderu
        $dir = getcwd() . "/grafika";
		if (!file_exists($dir)) {mkdir($dir);}
        return $dir;
    }

    /**
     * Usuwa z dysku pliki których nie ma w przekazanej tablicy.
     * @param string[] $files
     */
    public static function UsunGrafiki($files){
        $dir = getcwd() . "/grafika";
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if(!in_array($file, $files)){
                        unlink($dir . "/" .$file);
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * Usuwa podany plik z dysku.
     * @param string $filename
     */
    public static function DeleteGrafike($filename){
        //przygotowanie folderu
        $dir = self::GetPath();
        $localFileName = $dir . "/" . $filename;
        if(file_exists($localFileName)){
            unlink($localFileName);
        }
    }

    /**
     * Pobiera wskazany plik i go zapisuje o ile już nie istnieje lokalna wersja tego pliku.
     * @param string $filename
     * @param int $filesize
     */
    public static function PobierzPlik($filename, $filesize){
        //przygotowanie folderu
        $dir = self::GetPath();

        //wyciagnac sama nazwe pliku
        $localFileName = basename($filename);
        $localFileName = $dir . "/" . $localFileName;

        $pobierzPlik = false;
        //najpierw sprawdzic czy plik istnieje
        if(file_exists($localFileName)){
            //jak plik jest to sie upewnic
            $sizeLocal = filesize($localFileName);
            if($sizeLocal <> $filesize) $pobierzPlik = true;
        }else{
            //brak pliku, nalezy go sciagnac
            $pobierzPlik = true;
        }

        //fizyczne pobranie pliku
        if($pobierzPlik){
            $handle = fopen($filename.(startsWith($filename,"http") ? '?v='.time() : ""), "r");
            if (!$handle) {
                echo "Unable to open remote file.<br />";
                exit;
            }
            $contents = '';
            while (!feof($handle)) {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);

            //teraz zapis pobranego pliku na dysk
            $handle = fopen($localFileName, "w");
            fwrite($handle, $contents);
            fclose($handle);
			
			chmod($localFileName, 0755);
        }
    }

}

?>
