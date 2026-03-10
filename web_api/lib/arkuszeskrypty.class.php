<?php

/**
 * Description of arkusze_skrypty
 *
 * @author Jakub Konieczka
 */
class ArkuszeSkrypty {

    const ARKUSZ_RODZAJ_CSS = 1;
    const ARKUSZ_RODZAJ_JS = 2;

    /**
     * Creates an ArkuszSkrypt object on the basis of data from the database.
     * @param array $row
     * @return ArkuszSkrypt 
     */
    protected static function BuildArkuszSkrypt($row){
		$ark = new ArkuszSkrypt($row['GID'],$row['serwisy_GID'],$row['Rodzaj'],$row['Opis'],$row['Tresc'],$row['RodzajArkusza']);
		return $ark;
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null){
        if($filters == null) return $filters;
        $tab_with_numeric_value= array("GID", "Typ");
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
		$orderbyArray = array('GID', 'Typ');
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
    protected static function GetQuery($select = "SELECT * ", $sorting = "", $filters = null){
        $query = $select . " FROM #S#arkusze_skrypty AS a ";
        $query .= " WHERE 1=1 ";
		if($filters != null){
            foreach ($filters as $key => $value){
				switch ($key) {
                    case "GID": $query .= " AND a.GID=?"; break;
                    case "GIDSerwis": $query .= " AND a.serwisy_GID = ?";break;
                    case "Rodzaj": $query .= " AND a.RodzajArkusza = ?";break;
                    case "Typ": $query .= " AND a.Rodzaj = ?";break;
                    case "Opis": $query .= " AND a.Opis LIKE ?";break;
					default: $query .= " AND a.$key=?"; break;	
                }
            }
        }
        if($sorting != ""){
			$query .= " ORDER BY a.".self::prepareSort($sorting);
		}
        return $query;
    }
    
    /**
     * Returns a list of ArkuszSkrypt, take into account the filtering.
     * @param int $rodzaj
     * @param array $ht
     * @return ArkuszSkrypt[]
     */
    protected static function PobierzListe($rodzaj, $ht = array()){
        $db = DataBase::GetDbInstance();
        $ht['Typ']=$rodzaj;
        $filters = self::PrepareFilters($ht);
        
        $query = self::GetQuery("SELECT a.*", "GID,asc", $ht);
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
        
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildArkuszSkrypt($row);
		}
		return $list;
    }

    /**
	 * Returns an arkusz/akrypt object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $rodzaj
	 * @return ArkuszSkrypt
	 */
	public static function GetArkuszSkrypt($gid, $rodzaj){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#arkusze_skrypty WHERE GID=? AND Rodzaj=?", array((int) $gid, (int) $rodzaj));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildArkuszSkrypt($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given arkusz/akrypt object to database.
	 * @param ArkuszSkrypt $ark
	 */
	public static function AddArkuszSkrypt(ArkuszSkrypt $ark){
		$query = "INSERT INTO #S#arkusze_skrypty (GID, serwisy_GID, Rodzaj, Opis, Tresc, RodzajArkusza) VALUES(?, ?, ?, ?, ?, ?);";
		$params = array($ark->GetGID(), $ark->Getserwisy_GID(), $ark->GetRodzaj(), $ark->GetOpis(), $ark->GetTresc(), $ark->GetRodzajArkusza());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete arkusz/akrypt from database, given by GID.
	 * @param int $gid
     * @param int $rodzaj
	 */
	public static function DeleteArkuszSkrypt($gid, $rodzaj){
        
		$params = array();
		$query = "DELETE FROM #S#arkusze_skrypty WHERE 1=1 ";
        
        if($gid > 0){
            $query .= " AND GID=?";
            $params[] = (int) $gid;
        }
        if($rodzaj > 0){
            $query .= " AND Rodzaj=?";
            $params[] = (int) $rodzaj;
        }
        
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if exists, given arkusz/akrypt object.
	 * @param ArkuszSkrypt $ark
	 */
	public static function AddEditArkuszSkrypt(ArkuszSkrypt $ark){
		if($ark != null){
            self::DeleteArkuszSkrypt($ark->GetGID(), $ark->GetRodzaj());
			self::AddArkuszSkrypt($ark);
		}
	}

    /**
     * Returns a list of sheets for given parameters.
     * @param array $ht
     * @return ArkuszSkrypt[]
     */
    public static function PobierzArkusze($ht = null){
        return self::PobierzListe(ArkuszeSkrypty::ARKUSZ_RODZAJ_CSS, $ht);
    }

    /**
     * Returns a list of scripts for given parameters.
     * @param array $ht
     * @return ArkuszSkrypt[]
     */
    public static function PobierzSkrypty($ht = null){
        return self::PobierzListe(ArkuszeSkrypty::ARKUSZ_RODZAJ_JS, $ht);
    }

}

?>
