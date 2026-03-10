<?php

/**
 * Description of menus
 * @author Jakub Konieczka
 */
class Menus {

    /**
     * Creates a Menu object on the basis of data from the database.
     * @param array $row
     * @return Menu
     */
    protected static function BuildMenu($row){
		$menu = new Menu($row['GID'],$row['IdJezyk'],$row['serwisy_GID'],$row['miejsca_miejsce_menu'],$row['miejsca_grupa_serwisu'],$row['Lp'],$row['UkryjNaWWW'],$row['NazwaGlowna'],
            $row['Nazwa'], $row['Grafika'],$row['Grafika2'],$row['Link'],$row['Tooltip'],$row['NoFollow']);
		return $menu;
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
    protected function prepareFilters($filters){
        if($filters == null) return $filters;
        $tab_with_numeric_value= array("GID", "IdJezyk", "GIDMiejsceMenu", "GIDGrupaSerwisu", "Lp", "UkryjNaWWW");
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
				case 'NazwaMenu': 
				case "Nazwa":
				case "Grafika":
				case "Link": $newFilters[$key] = $value."%"; break;
				default: $newFilters[$key] = $value;
			}
		}
		return array_values($newFilters);
	}

    /**
	 * Returns an menu item object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return Menu
	 */
	public static function GetMenu($gid, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#menu WHERE GID=? AND IdJezyk=?", array((int) $gid, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildMenu($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given menu item object to database.
	 * @param Menu $menu
	 */
	public static function AddMenu(Menu $menu){
		$query = "INSERT INTO #S#menu (GID, IdJezyk, serwisy_GID, miejsca_miejsce_menu, miejsca_grupa_serwisu, Lp, UkryjNaWWW, NazwaGlowna, Nazwa, Grafika, Grafika2, Link, Tooltip, NoFollow)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($menu->GetGID(),$menu->GetIdJezyk(),$menu->Getserwisy_GID(),$menu->Getmiejsca_miejsce_menu(),$menu->Getmiejsca_grupa_serwisu(),$menu->GetLp(),
            $menu->GetUkryjNaWWW(),$menu->GetNazwaGlowna(),$menu->GetNazwa(),$menu->GetGrafika(),$menu->GetGrafika2(),$menu->GetLink(),$menu->GetTooltip(),$menu->GetNoFollow());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete menu item from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteMenu($gid, $idLng = 0){
        $query = "DELETE FROM #S#menu WHERE 1=1 ";
        $params = array();
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
	 * Add or edit if exists, given menu item object.
	 * @param Menu $menu
	 */
	public static function AddEditMenu(Menu $menu){
		if($menu != null){
            self::DeleteMenu($menu->GetGID(), $menu->GetIdJezyk());
			self::AddMenu($menu);
		}
	}

    /**
     * Returns a list of menu items object from database selected by given filters.
     * @param array $ht
     * @param bool $czy_tylko_na_www
     * @param string $sort
     * @return Menu[]
     */
    public static function PobierzMenusyJezyki($ht = array(), $czy_tylko_na_www = FALSE, $sort = "Lp,ASC"){
    	$query = "SELECT m.* FROM #S#menu AS m WHERE 1=1";
        if($czy_tylko_na_www) $query .= " AND UkryjNaWWW=0 ";
        if($ht){
			foreach ($ht as $key => $value){
				switch ($key) {
                    case "GID": $query .= " AND m.GID=?"; break;
                    case "GIDSerwis": $query .= " AND m.serwisy_GID=?"; break;
                    case "GIDMiejsceMenu": $query .= " AND m.miejsca_miejsce_menu=?"; break;
                    case "GIDGrupaSerwisu": $query .= " AND m.miejsca_grupa_serwisu=?"; break;
                    case "IdJezyk": $query .= " AND m.IdJezyk=?"; break;
                    case "NazwaMenu": $query .= " AND m.NazwaGlowna LIKE ?"; break;
                    case "Nazwa": $query .= " AND m.Nazwa LIKE ?"; break;
                    case "Grafika": $query .= " AND m.Grafika LIKE ?"; break;
                    case "Link": $query .= " AND m.Link LIKE ?"; break;
                    default: $query .= " AND m.$key=?"; break;
                }
            }
        }
        
        if($sort != ""){
            $sort = explode(",", $sort);
            $query.=" ORDER BY m.".$sort[0]." ".$sort[1];
        }
        
        $list = array();
        $params = self::prepareFilters($ht);
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildMenu($row);
		}
		return $list;
    }

    /**
     * Returns a list of menu items object from database selected by given filters.
     * @param int $gid_miejsca_menu
     * @param int $id_jezyka
     * @param int $gid_grupy_serwisu
     * @param bool $czy_tylko_na_www
     * @return Menu[]
     */
    public static function PobierzMenusyJezyki2($gid_miejsca_menu, $id_jezyka = 1045, $gid_grupy_serwisu = 0, $czy_tylko_na_www = FALSE){
        $ht = array("GIDMiejsceMenu" => $gid_miejsca_menu, "IdJezyk" => (int) $id_jezyka, "GIDSerwis" => Config::$WebGID);
        if($gid_grupy_serwisu > 0) $ht["GIDGrupaSerwisu"] = (int) $gid_grupy_serwisu;
        return self::PobierzMenusyJezyki($ht, $czy_tylko_na_www);
    }
    
	/**
     * Returns distinct values of 'GID' (in miejsca table) in array
     * Indexes of return array = miejsca.Nazwa
     * @return Array
     */    
    public static function PobierzMiejscaMenu($gid = false){    	
    	if(!$gid) $gid = Config::$WebGID;
    	$db = DataBase::GetDbInstance();
    	
    	$query = "SELECT DISTINCT miejsca.Nazwa, miejsca.GID FROM menu, miejsca WHERE menu.miejsca_miejsce_menu = miejsca.GID AND menu.serwisy_GID = ?";
    	$result = $db->ExecuteQueryWithParams($query, array((int) $gid));
    	
    	$returnArray = array();
    	while($row = $db->FetchArray($result)){
    		$returnArray[$row['GID']] = $row['Nazwa'];
    	}
    	return $returnArray;
    }
}

?>
