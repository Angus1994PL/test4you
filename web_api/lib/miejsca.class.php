<?php

/**
 * Description of miejsca
 *
 * @author Jakub Konieczka
 */
class Miejsca {

    const MIEJSCE_RODZAJ_SERWISU = 1;
    const MIEJSCE_RODZAJ_MENU = 2;
    const MIEJSCE_RODZAJ_GRUPY = 3;

    /**
     * Creates a Miejsce object on the basis of data from the database.
     * @param array $row
     * @return Miejsce
     */
    protected static function BuildMiejsce($row){
		$miejsce = new Miejsce($row['GID'],$row['IdJezyk'],$row['Rodzaj'],$row['serwisy_GID'],$row['Parent_GID'],$row['Lp'],$row['NazwaGlowna'],$row['Nazwa'],$row['Grafika'],
            $row['Link'],$row['Inne'],$row['Uwagi']);
		return $miejsce;
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
    protected static function prepareFilters($filters){
        if($filters == null) return $filters;
        $tab_with_numeric_value= array("GID", "IdJezyk", "Rodzaj", "IdRodzic", "Lp", "UkryjNaWWW");
		$newFilters = array();
    	foreach($filters as $key=>$value){
            if(!is_array($value)){ 
                if(array_search($key, $tab_with_numeric_value)){
                    if(is_numeric($value)) $newFilters[$key]= (int) trim($value,'\'"');
                }else{
                    $newFilters[$key] = trim($value,'\'"');
                }
            }else{ $newFilters[$key] = $value;}
    		switch($key){    			
    			case "Nazwa":
    			case "Grafika": $newFilters[$key] = $value."%"; break;
    			case "LikeNazwa": $newFilters[$key] = '%'.$value."%"; break;
    			default: $newFilters[$key] = $value;
    		}
    	}
    	return array_values($newFilters);
    }

    /**
	 * Returns an miejsce object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $rodzaj
     * @param int $idLng
	 * @return Miejsce
	 */
	public static function GetMiejsce($gid, $rodzaj, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#miejsca WHERE GID=? AND Rodzaj=? AND IdJezyk=?", array((int) $gid, (int) $rodzaj, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildMiejsce($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given miejsce object to database.
	 * @param Miejsce $miejsce
	 */
	public static function AddMiejsce(Miejsce $miejsce){
		$query = "INSERT INTO #S#miejsca (GID, IdJezyk, Rodzaj, serwisy_GID, Parent_GID, Lp, NazwaGlowna, Nazwa, Grafika, Link, Inne, Uwagi)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($miejsce->GetGID(),$miejsce->GetIdJezyk(),$miejsce->GetRodzaj(),$miejsce->Getserwisy_GID(),$miejsce->GetParent_GID(),$miejsce->GetLp(),
            $miejsce->GetNazwaGlowna(), $miejsce->GetNazwa(),$miejsce->GetGrafika(),$miejsce->GetLink(),$miejsce->GetLink(),$miejsce->GetUwagi());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
    
    /**
	 * Delete miejsce from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteMiejsce($gid, $rodzaj, $idLng = 1045){
        $query = "DELETE FROM #S#miejsca WHERE 1=1 ";
        $params = array();
        if($gid > 0){
            $query .= " AND GID=?";
            $params[] = (int) $gid;
        }
        if($rodzaj > 0){
            $query .= " AND Rodzaj=?";
            $params[] = (int) $rodzaj;
        }
        $query .= " AND IdJezyk=?";
        $params[] = (int) $idLng;
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
    
    /**
	 * Add or edit if exists, given miejsce object.
	 * @param Miejsce $miejsce
	 */
	public static function AddEditMiejsce(Miejsce $miejsce){
		if($miejsce != null){
            self::DeleteMiejsce($miejsce->GetGID(), $miejsce->GetRodzaj(), $miejsce->GetIdJezyk());
			self::AddMiejsce($miejsce);
		}
	}

    /**
     * Returns a collection of miejsce object from database for given parent object.
     * @param Miejsce $parent
     * @return Miejsce[]
     */
    public static function GetMiejsca(Miejsce $parent){
        $list = array();
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#miejsca WHERE Parent_GID=? AND Rodzaj=? AND IdJezyk=?",
                array((int) $parent->GetGID(), (int) $parent->GetRodzaj(), (int) $parent->GetIdJezyk()));
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildMiejsce($row);
		}
		return $list;
	}

    /**
     * Returns string containig all child GID's.
     * @param int $gid
     * @param int $rodzaj
     * @param int $idLng
     * @return string
     */
    public static function GetChildGIDs($gid, $rodzaj, $idLng){
        $ret = "$gid,";
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT GID FROM #S#miejsca WHERE Parent_GID=? AND Rodzaj=? AND IdJezyk=?", array((int) $gid, (int) $rodzaj, (int) $idLng));
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
            $ret .= self::GetChildGIDs($row[0], $rodzaj, $idLng);
		}
        return $ret;
    }

    /**
     * Get GrupaSerwisu object by GID and ID LNG.
     * @param int $gid_grupy_serwisu
     * @param int $idLng
     * @return Miejsce
     */
    public static function PobierzGrupaSerwisuGID($gid, $idLng = 1045){
        return self::GetMiejsce($gid, Miejsca::MIEJSCE_RODZAJ_GRUPY, $idLng);
    }

    /**
     * Get GrupaSerwisu object by name and ID LNG.
     * @param string $str_nazwa
     * @param int $idLng
     * @return Miejsce
     */
    public static function PobierzGrupaSerwisu($str_nazwa, $idLng = 1045){
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#miejsca WHERE NazwaGlowna=? AND Rodzaj=? AND IdJezyk=? AND serwisy_GID=?",
                array($str_nazwa, (int) Miejsca::MIEJSCE_RODZAJ_GRUPY, (int) $idLng, Config::$WebGID));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildMiejsce($row);
			else return null;
		}else return null;
    }

    /**
     * Get MiejsceSerwisu object by GID and ID LNG.
     * @param int $gid_miejsca_serwisu
     * @param int $idLng
     * @return Miejsce
     */
    public static function PobierzMiejsceSerwisuGid($gid, $idLng = 1045){
        return self::GetMiejsce($gid, Miejsca::MIEJSCE_RODZAJ_SERWISU, $idLng);
    }

    /**
     * Return list of MiejscaSerwisu objects selected with given filters.
     * @param array $ht
     * @param int $idLng
     * @return Miejsce[]
     */
    public static function PobierzMiejscaSerwisu($ht, $idLng = 1045){
        $query = "SELECT m.* FROM #S#miejsca AS m WHERE m.Rodzaj=".Miejsca::MIEJSCE_RODZAJ_SERWISU." AND IdJezyk=? ";
        
        if($ht != null){
			foreach ($ht as $key => $value){
				switch ($key) {
                    case "GID": $query .= " AND m.GID=?"; break;
                    case "IdRodzic": if($value == "null") $query .= " AND m.Parent_GID IS NULL"; else $query .= " AND m.Parent_GID=?"; break;
                    case "GIDSerwis": $query .= " AND m.serwisy_GID=?"; break;
                    case "Nazwa": $query .= " AND m.NazwaGlowna LIKE ?"; break;
                    case "Grafika": $query .= " AND m.Grafika LIKE ?"; break;
                    case "LikeNazwa": $query .= " AND m.NazwaGlowna LIKE ?"; break;
                    default: $query .= " AND m.$key=?"; break;
                }
            }
        }
        
        $list = array();
        $params = array_merge(array((int) $idLng), self::prepareFilters($ht));
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildMiejsce($row);
		}
		return $list;
    }

    /**
     * Return list of MiejscaMenu objects selected with given filters.
     * @param array $ht
     * @param int $idLng
     * @return Miejsce[]
     */
    public static function PobierzMiejscaMenu($ht, $idLng = 1045){
        $query = "SELECT m.* FROM #S#miejsca AS m WHERE m.Rodzaj=".Miejsca::MIEJSCE_RODZAJ_MENU." AND IdJezyk=? ";
        if($ht != null){
			foreach ($ht as $key => $value){
				switch ($key) {
                    case "GID": $query .= " AND m.GID=?"; break;
                    case "IdRodzic": if($value == "null") $query .= " AND m.Parent_GID IS NULL"; else $query .= " AND m.Parent_GID=?"; break;
                    case "GIDSerwis": $query .= " AND m.serwisy_GID=?"; break;
                    case "Lp": $query .= " AND m.Lp=?"; break;
                    case "Nazwa": $query .= " AND m.NazwaGlowna LIKE ?"; break;
                    default: $query .= " AND m.$key=?"; break;
                }
            }
        }
        
        $list = array();
		$params = array_merge(array((int) $idLng), self::prepareFilters($ht));
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildMiejsce($row);
		}
		return $list;
    }

    /**
     * Get MiejsceMenu object by GID and ID LNG.
     * @param int $gid_miejsca_menu
     * @param int $idLng
     * @return Miejsce
     */
    public static function PobierzMiejsceMenuJezykGid($gid_miejsca_menu, $idLng = 1045){
        return self::GetMiejsce($gid_miejsca_menu, Miejsca::MIEJSCE_RODZAJ_MENU, $idLng);
    }

}
?>
