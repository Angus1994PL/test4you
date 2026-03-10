<?php

/**
 * Description of galerie
 * @author Jakub Tyniecki
 */
class Galerie {

    /**
     * Creates an Galeria object on the basis of data from the database.
     * @param array $row
     * @return Galeria 
     */
    protected static function BuildGaleria($row){
		$galeria = new Galeria($row['GID'],$row['IdJezyk'],$row['serwisy_GID'],$row['miejsca_grupa_serwisu'],$row['Lp'],$row['Nazwa'],
            $row['Opis'],$row['SlowaKluczowe'],$row['Grafika'],$row['Rozmiar1'],$row['Rozmiar2'],$row['Rozmiar3']);
		return $galeria;
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null){
        if($filters == null) return $filters;
        $tab_with_numeric_value= array("GID", "IdJezyk","GIDGrupaSerwisu","Lp");
		$newFilters = array();
		foreach ($filters as $key => $value){
            if(!is_array($value)){ 
                if(array_search($key, $tab_with_numeric_value)){
                    if(is_numeric($value)) $newFilters[$key]= (int) trim($value,'\'"');
                }else{
                    $newFilters[$key] = trim($value,'\'"');
                }
            }else{ $newFilters[$key] = $value;}
        }
        return $newFilters;
    }
    
    /**
     * Converts array or string to GetQuery
     * @param mixed $value
     * @return string
     */
	protected static function prepareStringToBind($value){
        if(is_array($value)) $arr = $value;
        else $arr = explode(',', $value);
		return implode(',', array_fill(0, count($arr), '?')); 
	}
    
	/**
     * Converts array or string to GetQuery
     * @param mixed $value
     * @return string[]
     */
	protected static function prepareStringToArray($value){		
        if(is_array($value)) $arr=$value;
		else $arr = explode(',', $value);
		$newArr = array();
		foreach($arr as $value){
			$newArr[] = trim($value, "' ");
		}
		return $newArr;
	}
    
    /**
	* Returns cleared value for SQL query.
	* @param string $value
	* @return string
	*/
	protected static function prepareSort($sort){	
		$sort = strtolower($sort);
		$orderbyArray = array('GID', 'Lp');
		$destArray = array('asc', 'desc');
		
		$exp_sort = explode(',', $sort);
		
		$return = '';
		foreach($exp_sort as $value){
            $exp = explode(' ', trim($value));
            $orderby = 'GID';
            $dest = 'desc';
            if(isset($exp[0]) && in_array($exp[0], $orderbyArray)) $orderby = $exp[0];
            if(isset($exp[1]) && in_array(strtolower($exp[1]), $destArray)) $dest = $exp[1];
            $return .= $orderby.' '.$dest.',';
		}
		return trim($return, ',');		
	}
    
    /**
     * Creates query string from given params
     * @param string $select
     * @param string $sorting
     * @param array $filters
     * @return string 
     */
    protected static function GetQuery($select = "SELECT g.* ", $sorting = "", $filters = null){
        $query = $select . " FROM #S#galerie AS g ";
        $query .= " WHERE 1=1 ";
		if($filters != null){
            foreach ($filters as $key => $value){
				switch ($key) {
                    case "GID": $query .= " AND g.GID=?"; break;
                    case "GIDSerwis": $query .= " AND g.serwisy_GID = ?";break;
                    case "IdJezyk": $query .= " AND g.IdJezyk = ?";break;
                    case "GIDGrupaSerwisu": $query .= " AND g.miejsca_grupa_serwisu = ?";break;
					default: $query .= " AND g.$key=?"; break;	
                }
            }
        }
        if($sorting != ""){
			$query .= " ORDER BY a.".self::prepareSort($sorting);
		}
        return $query;
    }

    /**
	 * Returns galeria object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return Galeria
	 */
	public static function GetGaleria($gid, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#galerie WHERE GID=? AND IdJezyk=?", array((int) $gid, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildGaleria($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given galeria object to database.
	 * @param Baner $baner
	 */
	public static function AddGaleria(Galeria $galeria){
		$query = "INSERT INTO #S#galerie (GID, IdJezyk, serwisy_GID, miejsca_grupa_serwisu, Lp, Nazwa, Opis, SlowaKluczowe, Grafika, Rozmiar1, Rozmiar2, Rozmiar3)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($galeria->GetGID(),$galeria->GetIdJezyk(),$galeria->Getserwisy_GID(),$galeria->Getmiejsca_grupa_serwisu(),$galeria->GetLp(),
            $galeria->GetNazwa(),$galeria->GetOpis(),$galeria->GetSlowaKluczowe(),$galeria->GetGrafika(), $galeria->GetRozmiar1(), $galeria->GetRozmiar2(), $galeria->GetRozmiar3());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete galeria from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteGaleria($gid, $idLng = 0){
        $gp = GaleriePozycje::PobierzGaleriePozycjeJezyki($gid, $idLng);
        if ($gp != null && sizeof($gp)>0) {
            foreach ($gp as $p) GaleriePozycje::DeleteGaleriaPozycja($p->GetGID(), $idLng);
        }
        
        $params = array();
        $query = "DELETE FROM #S#galerie WHERE 1=1 ";
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
	 * Add or edit if exists, given galeria object.
	 * @param Galeria $galeria
	 */
	public static function AddEditGaleria(Galeria $galeria){
		if($galeria != null){
            self::DeleteGaleria($galeria->GetGID(), $galeria->GetIdJezyk());
			self::AddGaleria($galeria);
		}
	}

    /**
     * Returns list of Galeria objects for a given filters.
     * @param int $gid_grupy_serwisu
     * @param int $id_jezyka
     * @param string $sort
     * @return Galeria[]
     */
    public static function PobierzGalerieJezyki($gid_grupy_serwisu = 0, $id_jezyka = 1045, $sort=""){
        $filters = array();
        if(is_int($gid_grupy_serwisu) && $gid_grupy_serwisu > 0) $filters['GIDGrupaSerwisu']=(int) $gid_grupy_serwisu;
        $filters['IdJezyk']=(int) $id_jezyka;
        $filters['GIDSerwis']=Config::$WebGID;
        $newfilters = self::PrepareFilters($filters);
        $query = self::GetQuery("SELECT g.* ",$sort, $filters);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array_values($newfilters));
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildGaleria($row);
		}
		return $list;
    }
    
    /**
     * Returns list of Galeria objects for a given filters.
     * @param array $filters
     * @param int $id_jezyka
     * @param string $sort
     * @return Galeria[]
     */
    public static function PobierzGalerie($filters = array(), $id_jezyka = 1045, $sort=""){
        $filters['IdJezyk']=(int) $id_jezyka;
        $newfilters = self::PrepareFilters($filters);
        $query = self::GetQuery("SELECT g.* ",$sort, $filters);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array_values($newfilters));
        $list = array();
    	while($row = DataBase::GetDbInstance()->FetchArray($result)){
    		$list[count($list)] = self::BuildGaleria($row);
    	}
    	return $list;
    }

}

?>
