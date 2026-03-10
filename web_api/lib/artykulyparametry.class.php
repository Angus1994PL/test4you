<?php

/**
 * Description of artykuly_parametry
 * @author Jakub Konieczka
 */
class ArtykulyParametry {

    /**
     * Creates an ArtykulParametr object on the basis of data from the database.
     * @param array $row
     * @return ArtykulParametr 
     */
    protected static function BuildArtykulParametr($row){
		$par = new ArtykulParametr($row['GID'],$row['IdJezyk'],$row['artykuly_GID'],$row['ParamNazwa'],$row['Nazwa'],$row['Naglowek'],$row['Stopka']);
		return $par;
	}

    /**
	 * Returns an artykul parametr object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return ArtykulParametr
	 */
	public static function GetArtykulParametr($gid, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#artykuly_parametry WHERE GID=? AND IdJezyk=?", array((int) $gid, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildArtykulParametr($row);
			else return null;
		}else return null;
	}

    /**
     * Returns list of article params for given article.
     * @param Artykul $artykul
     * @return ArtykulParametr[]
     */
    public static function GetArtykulParametry(Artykul $artykul){		
        $list = array();
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#artykuly_parametry WHERE artykuly_GID=? AND IdJezyk=?", array((int) $artykul->GetGID(), (int) $artykul->GetIdJezyk()));
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildArtykulParametr($row);
		}
		return $list;
	}

	/**
	 * Add given artykul parametr object to database.
	 * @param ArtykulParametr $artparam
	 */
	public static function AddArtykulParametr(ArtykulParametr $artparam){
		$query = "INSERT INTO #S#artykuly_parametry (GID, IdJezyk, artykuly_GID, ParamNazwa, Nazwa, Naglowek, Stopka) VALUES(?, ?, ?, ?, ?, ?, ?);";
		$params = array($artparam->GetGID(), $artparam->GetIdJezyk(), $artparam->Getartykuly_GID(), $artparam->GetParamNazwa(), $artparam->GetNazwa(), $artparam->GetNaglowek(), $artparam->GetStopka());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete artykul parametr from database, given by GID.
	 * @param int $gid
	 * @param int $artGid
     * @param int $idLng
	 */
	public static function DeleteArtykulParametr($gid, $artGid, $idLng = 0){
        $query = "DELETE FROM #S#artykuly_parametry WHERE 1=1";
        $params = array();
        if($gid > 0){
            $query .= " AND GID=?";
            $params[] = (int) $gid;
        }
        if($artGid > 0){
            $query .= " AND artykuly_GID=?";
            $params[] = (int) $artGid;
        }
        if($idLng > 0){
            $query .= " AND IdJezyk=?";
            $params[] = (int) $idLng;
        }

        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete artykul parametrs from database, given by ARTYKUL GID.
	 * @param int $gidArtykul
     * @param int $idLng
	 */
	public static function DeleteArtykulParametry($gidArtykul, $idLng = 0){
        $query = "DELETE FROM #S#artykuly_parametry WHERE 1=1";
        $params = array();
        if($gidArtykul > 0){
            $query .= " AND artykuly_GID=?";
            $params[] = (int) $gidArtykul;
        }
        if($idLng > 0){
            $query .= " AND IdJezyk=?";
            $params[] = (int) $idLng;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if exists, given arkusz/akrypt object.
	 * @param ArtykulParametr $artparam
	 */
	public static function AddEditArtykulParametr(ArtykulParametr $artparam){
		if($artparam != null){
			self::AddArtykulParametr($artparam);
		}
	}

}

?>
