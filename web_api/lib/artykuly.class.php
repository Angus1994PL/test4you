<?php

/**
 * Description of artykuly
 * @author Jakub Konieczka
 */
class Artykuly  {

    /**
     * Creates an Artykul object on the basis of data from the database.
     * @param array $row
     * @return Artykul 
     */
    protected static function BuildArtykul($row){
		$art = new Artykul($row['GID'],$row['IdJezyk'],$row['serwisy_GID'],$row['miejsca_grupa_serwisu'],$row['miejsca_miejsce_serwisu'],$row['menu_GID'],$row['Parent_GID'],$row['Lp'],
            $row['CzyWiadomosc'],$row['CzyDomyslny'],$row['Autor'],$row['LiczbaOdslon'],$row['SredniaOcena'],$row['DataWiadomosci'],$row['DataAktualizacji'],$row['Tytul'],
            $row['Skrot'],$row['SkrotGrafika'],$row['Tresc'],$row['Link'],$row['NazwaWyswietlana'],$row['TagTitle'],$row['TagKeywords'],$row['TagDescription'],$row['galerie_GID'],
            $row['Tagi'],$row['DataRozpoczeciaPublikacji'], $row['NoFollow']);
		return $art;
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
    protected static function prepareFilters($filters){
        if($filters == null) return $filters;
		$newFilters = array();
    	$tab_with_numeric_value = array("GID","IdJezyk","GIDMiejsceSerwisu","GIDGrupaSerwisu","GIDArtykulNadrzedny","RokWiadomosci","GIDParametrArtykul");
    	foreach ($filters as $key => $value) {
            if(array_search($key, $tab_with_numeric_value)){
                if(is_numeric($value)) $newFilters[$key]= (int) trim($value,'\'"');
            }else{
                $newFilters[$key] = trim($value,'\'"');
            }
            
            switch ($key) {
                case "ids": array_pop($newFilters); $newFilters = array_merge($newFilters, self::prepareStringToArray($value)); break;
                case "JestTytul":
                case "ArtykulNadrzednyIsNull": unset($newFilters[$key]);break;
				case "JestGrupaSerwisu": unset($newFilters[$key]);
                    break;
                case "TytulLubTresc": 
                        foreach($newFilters['TytulLubTresc'] as $value) {array_unshift($newFilters, '%'.$value.'%');array_unshift($newFilters, '%'.$value.'%');}
                        unset($newFilters['TytulLubTresc']);break;
            }
        }
    	
    	return array_values($newFilters);
    }
    
    /**
     * Prepare a part of query for given array of words
     * @param array $frazaArr
     * @return string
     */
	protected static function prepareLikeIn($frazaArr) {
        $zapytanieOr = "";

        foreach ($frazaArr as $key => $value) {
            $zapytanieOr = " OR a.Tresc LIKE ? OR a.Tytul LIKE ? " . $zapytanieOr;
        }
        $zapytanieOr = trim($zapytanieOr, ' OR ');
        $zapytanieOr = "(" . $zapytanieOr . ")";
        return $zapytanieOr;
    }
    
    /**
     * Creates SQL query, including sorting and filters.
     * @param string $select
     * @param string $sorting
     * @param array $filters
     * @return string
     */
    protected static function GetQuery($select = "SELECT a.* ", $sorting = "", $filters = null){
        $query = $select . " FROM #S#artykuly AS a WHERE 1=1  ";
        if($filters != null){
			foreach ($filters as $key => $value){
				switch ($key) {
                    case "GID": $query .= " AND a.GID=?"; break;
                    case "bezGID": $query .= " AND a.GID<>?"; break;
                    case "GIDMiejsceSerwisu": $query .= " AND a.miejsca_miejsce_serwisu=?"; break;
                    case "GIDGrupaSerwisu": $query .= " AND a.miejsca_grupa_serwisu=?"; break;
					case "JestGrupaSerwisu": if ($value == true) $query .= " AND a.miejsca_grupa_serwisu IS NULL";
                    case "GIDSerwis": $query .= " AND a.serwisy_GID=?"; break;
                    case "RokWiadomosci": $query .= " AND YEAR(a.DataWiadomosci)=?"; break;
                    case "IdJezyk": $query .= " AND a.IdJezyk=?"; break;
                    case "GIDParametrArtykul": $query .= " AND (SELECT COUNT(p.GID) FROM #S#artykuly_parametry p WHERE p.artykuly_GID=a.GID AND p.IdJezyk=a.IdJezyk AND p.GID=?)>0 "; break;
                    case "ParametrNazwa": $query .= " AND (SELECT COUNT(p.GID) FROM #S#artykuly_parametry p WHERE p.artykuly_GID=a.GID AND p.IdJezyk=a.IdJezyk AND p.ParamNazwa=?)>0 "; break;
                    case "CzyWiadomosc": $query .= " AND a.CzyWiadomosc=?"; break;
                    case "CzyDomyslny": $query .= " AND a.CzyDomyslny=?"; break;
                    case "JestTytul": $query .= " AND a.Tytul<>''";break;
                    case "TytulStr": $query .= " AND a.Tytul LIKE ?"; break;
					case "TytulLubTresc": $query .= " AND ".self::prepareLikeIn($value); break;
                    case "GIDArtykulNadrzedny": $query .= " AND a.Parent_GID=?"; break;
                    case "ArtykulNadrzednyIsNull": if($value == true) $query .= " AND a.Parent_GID IS NULL"; break;
                    case "NazwaWyswietlana": $query .= " AND a.NazwaWyswietlana=?"; break;
                    case "Tresc": $query .= 'AND a.Tresc LIKE ?'; break;
                    case "ids": $query .= " AND a.GID IN (".self::prepareStringToBind($value).")"; break;
                    default: $query .= " AND a.$key=?"; break;
                }
            }
        }
        if($sorting != ""){
			$query .= " ORDER BY a.$sorting";
		}
		return $query;
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
	 * Returns an artykul object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return Artykul Artykul
	 */
	public static function GetArtykul($gid, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#artykuly WHERE GID=? AND IdJezyk=?", array((int) $gid, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildArtykul($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given artykul object to database.
	 * @param Artykul $artykul
	 */
	public static function AddArtykul(Artykul $artykul){
		$query = "INSERT INTO #S#artykuly (GID, IdJezyk, serwisy_GID, miejsca_grupa_serwisu, miejsca_miejsce_serwisu, menu_GID, Parent_GID, Lp, CzyWiadomosc, CzyDomyslny, Autor,
            LiczbaOdslon, SredniaOcena, DataWiadomosci, DataAktualizacji, Tytul, Skrot, SkrotGrafika, Tresc, Link, NazwaWyswietlana, TagTitle, TagKeywords, TagDescription, galerie_GID,
            Tagi, DataRozpoczeciaPublikacji, NoFollow)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($artykul->GetGID(),$artykul->GetIdJezyk(),$artykul->Getserwisy_GID(),$artykul->Getmiejsca_grupa_serwisu(),$artykul->Getmiejsca_miejsce_serwisu(),
            $artykul->Getmenu_GID(), $artykul->GetParent_GID(), $artykul->GetLp(), $artykul->GetCzyWiadomosc(), $artykul->GetCzyDomyslny(), $artykul->GetAutor(), $artykul->GetLiczbaOdslon(),
            $artykul->GetSredniaOcena(), $artykul->GetDataWiadomosci(), $artykul->GetDataAktualizacji(), $artykul->GetTytul(), $artykul->GetSkrot(), $artykul->GetSkrotGrafika(),
            $artykul->GetTresc(), $artykul->GetLink(), $artykul->GetNazwaWyswietlana(), $artykul->GetTagTitle(), $artykul->GetTagKeywords(), $artykul->GetTagDescription(), $artykul->Getgalerie_GID(),
            $artykul->GetTagi(), $artykul->GetDataRozpoczeciaPublikacji(), $artykul->GetNoFollow());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		//echo $result;
	}

    /**
	 * Delete artykul from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteArtykul($gid, $idLng = 0){
        ArtykulyParametry::DeleteArtykulParametry($gid, $idLng);
        $params = array();
        $query = "DELETE FROM #S#artykuly WHERE 1=1 ";
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
	 * Add or edit if exists, given artykul object.
	 * @param Artykul $artykul
	 */
	public static function AddEditArtykul(Artykul $artykul){
		if($artykul != null){
            self::DeleteArtykul($artykul->GetGID(), $artykul->GetIdJezyk());
			self::AddArtykul($artykul);
		}
	}

    /**
     * Returns an article for given parameters.
     * @param int $gid_artykulu
     * @param int $id_jezyka
     * @param int $gid_miejsca_serwisu
     * @param int $gid_grupy_serwisu
     * @param string $str_parametr
     * @return Artykul
     */
    public static function PobierzArtykulJezyk($gid_artykulu, $id_jezyka = 1045, $gid_miejsca_serwisu = 0, $gid_grupy_serwisu = 0, $str_parametr = ""){
        $params = array((int) $id_jezyka);
    	$query = "SELECT * FROM #S#artykuly a WHERE CzyWiadomosc=0 AND IdJezyk=? AND serwisy_GID='".Config::$WebGID."' ";
        if($gid_artykulu > 0){ $query .= " AND GID=?"; $params[] = (int) $gid_artykulu; }
        if($gid_miejsca_serwisu > 0){ $query .= " AND miejsca_miejsce_serwisu=?"; $params[] = (int) $gid_miejsca_serwisu; }
        if($gid_grupy_serwisu > 0){ $query .= " AND miejsca_grupa_serwisu=?"; $params[] = (int) $gid_grupy_serwisu; }
        if($str_parametr != ""){ $query .= " AND (SELECT COUNT(p.GID) FROM #S#artykuly_parametry p WHERE p.artykuly_GID=a.GID AND p.IdJezyk=a.IdJezyk AND p.ParamNazwa=?)>0 "; $params[] = $str_parametr; }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildArtykul($row);
			else return null;
		}else return null;
    }

    /**
     * Returns an article for given title.
     * @param string $tytul
     * @param int $id_jezyka
     * @return Artykul
     */
    public static function PobierzArtykulPoTytule($tytul, $id_jezyka = 1045){
        $query = "SELECT * FROM #S#artykuly a WHERE CzyWiadomosc=0 AND IdJezyk=? AND serwisy_GID=? AND Tytul=?";
        $params = array((int) $id_jezyka, Config::$WebGID, $tytul);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildArtykul($row);
			else return null;
		}else return null;
    }
    
    /**
     * Returns an article for given title.
     * @param string $tytul
     * @param int $id_jezyka
     * @return Artykul
     */
    public static function PobierzArtykulPoLinku($link){
        $query = "SELECT * FROM #S#artykuly a WHERE CzyWiadomosc=0 AND serwisy_GID=? AND Link=?";
        $params = array(Config::$WebGID, $link);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        if($result){
            $row = DataBase::GetDbInstance()->FetchArray($result);
            if($row) return self::BuildArtykul($row);
            else return null;
        }else return null;
    }

    /**
     * Returns an default article.
     * @param int $id_jezyka
     * @return Artykul
     */
    public static function PobierzArtykulDomyslny($id_jezyka){
        $query = "SELECT * FROM #S#artykuly a WHERE CzyWiadomosc=0 AND CzyDomyslny=1 AND IdJezyk=? AND serwisy_GID=?";
        $params = array((int) $id_jezyka, Config::$WebGID);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildArtykul($row);
			else return null;
		}else return null;
    }

    /**
     * Returns a list of articles for given parameters.
     * @param int $gid_grupy_serwisu
     * @param int $id_jezyka
     * @return Artykul[]
     */
    public static function PobierzArtykulyJezykiWDolGrupySerwisu($gid_grupy_serwisu, $id_jezyka = 1045){
    	
    	$params = array((int) $id_jezyka, Config::$WebGID);
    	$query = "SELECT * FROM #S#artykuly a WHERE CzyWiadomosc=0 AND IdJezyk=? AND serwisy_GID=?";
    	
        if(isset($gid_grupy_serwisu) && $gid_grupy_serwisu > 0){
            $ideki = Miejsca::GetChildGIDs($gid_grupy_serwisu, Miejsca::MIEJSCE_RODZAJ_GRUPY, $id_jezyka);
            if(strrpos($ideki, ",") === strlen($ideki)-strlen(",")) $ideki = substr ($ideki, 0, strlen($ideki)-1);
            $query .= " AND miejsca_grupa_serwisu IN($ideki)";
        }
        
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildArtykul($row);
		}
		return $list;
    }
    
    /**
     * Returns distinct values of 'GID' (in miejsca table) in array
     * Indexes of return array = miejsca.Nazwa
     * @return Array
     */    
    public static function PobierzMiejscaSerwisu($gid = false){    	
    	if(!$gid) $gid = Config::$WebGID;
    	$db = DataBase::GetDbInstance();
    	    	
    	$query = "SELECT DISTINCT miejsca.Nazwa, miejsca.GID FROM artykuly, miejsca WHERE artykuly.miejsca_miejsce_serwisu = miejsca.GID AND miejsca.Nazwa != 'Bannery' AND artykuly.serwisy_GID =?";
    	$result = $db->ExecuteQueryWithParams($query, array($gid));
    	
    	$returnArray = array();
    	while($row = $db->FetchArray($result)){
    		$returnArray[$row['GID']] = $row['Nazwa'];
    	}
    	
    	return $returnArray;
    }

    /**
     * Returns a list of articles for given parameters.
     * @param array $ht
     * @param int $strona
     * @param int $ile_na_strone
     * @param string $sort
     * @return Artykul[]
     */
    public static function PobierzArtykulyJezyki($ht = null, $strona = 0, $ile_na_strone = 0, $sort = ""){
        $query = self::GetQuery("SELECT a.* ", $sort, $ht);
        $params = self::prepareFilters($ht);  
        if($strona >= 0 && $ile_na_strone > 0) $query .= " LIMIT " . ($strona * $ile_na_strone) . ", $ile_na_strone";
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildArtykul($row);
		}
		return $list;
    }

    /**
     * Returns a list of articles for the parameters.
     * @param int $gid_miejsca_serwisu
     * @param int $id_jezyka
     * @param int $id_parametr
     * @param int $bez_gid
     * @param int $gid_grupy_serwisu
     * @return Artykul[]
     */
    public static function PobierzArtykulyJezyki2($gid_miejsca_serwisu, $id_jezyka = 1045, $parametr = 0, $bez_gid = 0, $gid_grupy_serwisu = 0, $sort = ""){
        
    	$params = array((int) $id_jezyka, Config::$WebGID);
    	$query = "SELECT * FROM #S#artykuly a WHERE a.CzyWiadomosc=0 AND a.IdJezyk=? AND a.serwisy_GID=?";
        
        if(is_numeric($parametr) && $parametr > 0){
        	$params[] = (int) $parametr;
        	$query .= " AND (SELECT COUNT(p.GID) FROM #S#artykuly_parametry p WHERE p.artykuly_GID=a.GID AND p.IdJezyk=a.IdJezyk AND p.GID=?)>0 ";
        }elseif($parametr !== 0){
        	$params = array_merge($params, array($parametr, (int) $id_jezyka));
        	$query .= " AND (SELECT COUNT(p.GID) FROM #S#artykuly_parametry p WHERE p.ParamNazwa=? AND p.IdJezyk=? AND p.artykuly_GID=a.GID)>0 ";
        }
        
        if($gid_miejsca_serwisu > 0){
        	$params[] = (int) $gid_miejsca_serwisu;
        	$query .= " AND miejsca_miejsce_serwisu=? ";
        }
        
        if($gid_grupy_serwisu > 0){
        	$params[] = (int) $gid_grupy_serwisu;
        	$query .= " AND miejsca_grupa_serwisu=? ";
        }
        
        if($bez_gid > 0){
        	$params[] = (int) $bez_gid;
        	$query .= " AND a.GID<>? ";
        }
        
        if($sort != ""){
            $sort = explode(",", $sort);
            $query.=" ORDER BY a.".$sort[0]." ".$sort[1];
        }
                       
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildArtykul($row);
		}
		
		return $list;
    }

    /**
     * Returns the number of all articles for given parameters.
     * @param array $ht
     * @return int
     */
    public static function PoliczArtykulyJezyki($ht = array()){
    	$params = self::prepareFilters($ht);
    	$query = self::GetQuery("SELECT COUNT(a.GID) ", "", $ht);
        
    	$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		$row = DataBase::GetDbInstance()->FetchArray($result);
		return $row[0];
    }
}

?>
