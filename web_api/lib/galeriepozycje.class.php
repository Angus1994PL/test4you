<?php

/**
 * Description of galerie pozycje
 *
 * @author Jakub Tyniecki
 */
class GaleriePozycje {

    /**
     * Creates an Galeria object on the basis of data from the database.
     * @param array $row
     * @return GaleriaPozycja 
     */
    protected static function BuildGaleriaPozycja($row){
		$galeriapozycja = new GaleriaPozycja($row['GID'],$row['IdJezyk'],$row['serwisy_GID'],$row['galerie_GID'],$row['Lp'],$row['Plik'],$row['Opis'],$row['Tagi']);
		return $galeriapozycja;
	}
    
    /**
     * Zwraca ścieżkę do folderu z galeriami.
     * @return string
     */
    public static function GetPath(){
        //przygotowanie folderu
        $dir = getcwd() . "/galerie";
		if (!file_exists($dir)) {mkdir($dir);}
        chmod($dir, 0755);
        return $dir;
    }

    /**
	 * Returns an galeria pozycja object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return GaleriaPozycja GaleriaPozycja
	 */
	public static function GetGaleriaPozycja($gid, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#galeriepozycje WHERE GID=? AND IdJezyk=?", array((int) $gid, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildGaleriaPozycja($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given galeria pozycja object to database.
	 * @param GaleriaPozycja $galeriapozycja
	 */
	public static function AddGaleriaPozycja(GaleriaPozycja $galeriapozycja){
		$query = "INSERT INTO #S#galeriepozycje (GID, IdJezyk, serwisy_GID, galerie_GID, Lp, Plik, Opis, Tagi)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($galeriapozycja->GetGID(),$galeriapozycja->GetIdJezyk(),$galeriapozycja->Getserwisy_GID(),$galeriapozycja->Getgalerie_GID(),$galeriapozycja->GetLp(),
            $galeriapozycja->GetPlik(),$galeriapozycja->GetOpis(), $galeriapozycja->GetTagi());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		//echo $result;
	}

    /**
	 * Delete galeria pozycja from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteGaleriaPozycja($gid, $idLng = 0){
        $dir = self::GetPath();
        $gp = self::GetGaleriaPozycja($gid, 1045);
        if ($gp != null) {
            $gid_g = $gp->Getgalerie_GID();
            $getPlik = explode(".", $gp->GetPlik());

            $localFileName = $dir . "/" . "gal" . $gid_g . "/" . $gp->GetGID() . "%s" . "." . end($getPlik);

            if (file_exists(sprintf($localFileName, ""))) unlink(sprintf($localFileName, ""));
			
            if ($gp->GetGaleria()!==null) {
				if ($gp->GetGaleria()->GetRozmiar1() !== false && file_exists(sprintf($localFileName, "_" . $gp->GetGaleria()->GetRozmiar1()))) unlink(sprintf($localFileName, "_" . $gp->GetGaleria()->GetRozmiar1()));
				if ($gp->GetGaleria()->GetRozmiar2() !== false && file_exists(sprintf($localFileName, "_" . $gp->GetGaleria()->GetRozmiar2()))) unlink(sprintf($localFileName, "_" . $gp->GetGaleria()->GetRozmiar2()));
				if ($gp->GetGaleria()->GetRozmiar3() !== false && file_exists(sprintf($localFileName, "_" . $gp->GetGaleria()->GetRozmiar3()))) unlink(sprintf($localFileName, "_" . $gp->GetGaleria()->GetRozmiar3()));
			}
        }
        
        $params = array();
        $query = "DELETE FROM #S#galeriepozycje WHERE 1=1 ";
        if($gid > 0){
            $query .= " AND GID=?";
            $params[] = (int) $gid;
        }
        if($idLng > 0){
            $query .= " AND IdJezyk=?";
            $params[] = (int) $idLng;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if exists, given galeria pozycja object.
	 * @param Baner $baner
	 */
	public static function AddEditGaleriaPozycja(GaleriaPozycja $galeriapozycja){
		if($galeriapozycja != null){
            self::DeleteGaleriaPozycja($galeriapozycja->GetGID(), $galeriapozycja->GetIdJezyk());
			self::AddGaleriaPozycja($galeriapozycja);
		}
	}

    /**
     * Returns list of GaleriaPozycja objects for a given filters.
     * @param int $gid_galerii
     * @param int $id_jezyka
     * @return GaleriaPozycja[]
     */
    public static function PobierzGaleriePozycjeJezyki($gid_galerii = 0, $id_jezyka = 1045, $sort=""){
        $query = "SELECT * FROM #S#galeriepozycje WHERE IdJezyk=? AND serwisy_GID=? ";
        $params = array((int) $id_jezyka, Config::$WebGID);
        if(is_int($gid_galerii) && $gid_galerii > 0) {
            $query .= " AND galerie_GID=? ";
            $params[] = (int) $gid_galerii;
        }
        
        if($sort != "") $query.="ORDER BY ".$sort;
        else $query .= "ORDER BY Lp";
        
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildGaleriaPozycja($row);
		}
		return $list;
    }

    /**
     * Get graphics for galeria from WebService, and write it on local disk.
     * @param string $filename
     * @param int $filesize
     */
    public static function PobierzPlik($gid, $filename, $orygname, $filesize){
        //przygotowanie folderu
        $dir = self::GetPath();

        //wyciagnac sama nazwe pliku
        $localFileName = basename($orygname);
        $localFileName = $dir . "/" . "gal" . $gid . "/" . $localFileName;

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
            $handle = fopen(str_replace("sized//","sized/",$filename).(startswith($filename,"http") ? '?v='.time() : ""), "r");
            if (!$handle) {
                echo "Unable to open remote file.<br />";
                exit;
            }
            $contents = '';
            while (!feof($handle)) {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);
            
            if(!file_exists($dir . "/" . "gal" . $gid)){
                mkdir($dir . "/" . "gal" . $gid);
                chmod($dir . "/" . "gal" . $gid, 0755);
            }

            //teraz zapis pobranego pliku na dysk
            $handle = fopen($localFileName, "w");
            fwrite($handle, $contents);
            fclose($handle);
			
			chmod($localFileName, 0755);
        }
    }
    
    /**
     * Returns list of GaleriaPozycja objects for a given filters.
     * @param int $artykul_gid
     * @return GaleriaPozycja[]
     */
    public static function PobierzGaleriePozycjeDlaArtykulu($artykul_gid, $artykul_jezyk, $top=20){
        $query = "SELECT * FROM #S#galeriepozycje WHERE GID IN (SELECT galeriepozycje_GID FROM #S#artykuly_galeriepozycje WHERE artykuly_GID = ?) AND IdJezyk=? ORDER BY Lp LIMIT $top";
        $params = array((int) $artykul_gid, (int) $artykul_jezyk);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildGaleriaPozycja($row);
		}
		return $list;
    }
    
    public static function IndeksujGaleriePozycjeDlaArtykulow() {
        $del_query = "DELETE FROM #S#artykuly_galeriepozycje";
        
        $del_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($del_query, array());
        
        $query = "SELECT GID, Tagi FROM #S#artykuly WHERE Tagi <> '' AND IdJezyk=1045";
        
        $sub_query = "SELECT GID FROM #S#galeriepozycje WHERE Tagi <> '' AND Tagi LIKE ? AND IdJezyk=1045";
        
        $ins_query = "INSERT INTO #S#artykuly_galeriepozycje (artykuly_GID, galeriepozycje_GID) VALUES(?, ?);";
        
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array());

        while($row = DataBase::GetDbInstance()->FetchArray($result)) {
			$tagi = explode(",", $row['Tagi']);
            $unique_tags=array();
            foreach($tagi as $tag) {
                if (!isset($unique_tags[$tag])) {
                    $sub_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($sub_query, array('%'.$tag.'%'));

                    while($sub_row = DataBase::GetDbInstance()->FetchArray($sub_result)) {
                        $ins_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($ins_query, array($row['GID'], $sub_row['GID']));
                    }
                    $unique_tags[$tag]=true;
                }
            }
		}
    }
    
    public static function IndeksujGaleriePozycjeDlaArtykulu($artykul_gid) {
        $del_query = "DELETE FROM #S#artykuly_galeriepozycje WHERE artykuly_GID=?";
        
        $del_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($del_query, array((int) $artykul_gid));
        
        $a = Artykuly::PobierzArtykulJezyk($artykul_gid);
        
        if ($a->GetTagi()<>'') {
            $sub_query = "SELECT GID FROM #S#galeriepozycje WHERE Tagi <> '' AND Tagi LIKE ? AND IdJezyk=1045";
        
            $ins_query = "INSERT INTO #S#artykuly_galeriepozycje (artykuly_GID, galeriepozycje_GID) VALUES(?, ?);";
        
            $tagi = explode(",", $a->GetTagi());
            $unique_tags=array();
            foreach($tagi as $tag) {
                if (!isset($unique_tags[$tag])) {
                    $sub_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($sub_query, array('%'.$tag.'%'));
                    while($sub_row = DataBase::GetDbInstance()->FetchArray($sub_result)) {
                        $ins_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($ins_query, array($a->GetGID(), $sub_row['GID']));
                    }
                    $unique_tags[$tag]=true;
                }
            }
        }
    }
    
    public static function IndeksujGaleriePozycjeDlaPozycji($galeriapozycja_gid) {
        $del_query = "DELETE FROM #S#artykuly_galeriepozycje WHERE galeriepozycje_GID=?";
        
        $del_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($del_query, array((int) $galeriapozycja_gid));
        $query = "SELECT GID, Tagi FROM #S#artykuly WHERE Tagi <> '' AND IdJezyk=1045 AND";
        
        $gp = self::GetGaleriaPozycja($galeriapozycja_gid, 1045);
        $gp_tagi = explode(",",$gp->GetTagi());
        
        if ($gp->GetTagi()<>'') {
            $ins_query = "INSERT INTO #S#artykuly_galeriepozycje (artykuly_GID, galeriepozycje_GID) VALUES(?, ?);";

            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array());

            while($row = DataBase::GetDbInstance()->FetchArray($result)) {
                $tagi = explode(",", $row['Tagi']);
                $unique_tags=array_intersect($tagi, $gp_tagi);
                if (sizeof($unique_tags)>0) {
                    $ins_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($ins_query, array($row['GID'], $gp->GetGID()));
                }
            }
        }
    }
    
    public static function IndeksujGaleriePozycjeDlaGalerii($galeria_gid) {
        $del_query = "DELETE a_gp FROM #S#artykuly_galeriepozycje AS a_gp INNER JOIN #S#galeriepozycje AS gp ON a_gp.galeriepozycje_GID=gp.GID WHERE gp.galerie_GID=?";
        
        $del_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($del_query, array((int) $galeria_gid));
        
        $query = "SELECT GID, Tagi FROM #S#artykuly WHERE Tagi <> '' AND IdJezyk=1045";
        
        $sub_query = "SELECT GID FROM #S#galeriepozycje WHERE Tagi <> '' AND Tagi LIKE ? AND IdJezyk=1045 AND galerie_GID=?";
        
        $ins_query = "INSERT INTO #S#artykuly_galeriepozycje (artykuly_GID, galeriepozycje_GID) VALUES(?, ?);";
        
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array());

        while($row = DataBase::GetDbInstance()->FetchArray($result)) {
			$tagi = explode(",", $row['Tagi']);
            $unique_tags=array();
            foreach($tagi as $tag) {
                if (!isset($unique_tags[$tag])) {
                    $sub_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($sub_query, array('%'.$tag.'%', (int) $galeria_gid));

                    while($sub_row = DataBase::GetDbInstance()->FetchArray($sub_result)) {
                        $ins_result = DataBase::GetDbInstance()->ExecuteQueryWithParams($ins_query, array($row['GID'], $sub_row['GID']));
                    }
                    $unique_tags[$tag]=true;
                }
            }
		}
    }
}

?>