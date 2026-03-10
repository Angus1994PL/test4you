<?php

/**
 * Description of banery
 *
 * @author Jakub Konieczka
 */
class Banery {

    /**
     * Creates a Baner object on the basis of data from the database.
     * @param array $row
     * @return Baner 
     */
    protected static function BuildBaner($row){
		$baner = new Baner($row['GID'],$row['IdJezyk'],$row['serwisy_GID'],$row['miejsca_grupa_serwisu'],$row['miejsca_miejsce_serwisu'],$row['Status'],$row['DataDodania'],
            $row['DataWygasniecia'],$row['DataEmisji'],$row['UrlDocelowy'],$row['GIDGrafiki'],$row['Embed']);
		return $baner;
	}

    /**
	 * Returns an baner object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return Baner Baner
	 */
	public static function GetBaner($gid, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#banery WHERE GID=? AND IdJezyk=?", array((int) $gid, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildBaner($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given baner object to database.
	 * @param Baner $baner
	 */
	public static function AddBaner(Baner $baner){
		$query = "INSERT INTO #S#banery (GID, IdJezyk, serwisy_GID, miejsca_grupa_serwisu, miejsca_miejsce_serwisu, Status, DataDodania, DataWygasniecia, DataEmisji,
            UrlDocelowy, GIDGrafiki, Embed)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($baner->GetGID(),$baner->GetIdJezyk(),$baner->Getserwisy_GID(),$baner->Getmiejsca_grupa_serwisu(),$baner->Getmiejsca_miejsce_serwisu(),$baner->GetStatus(),
            $baner->GetDataDodania(),$baner->GetDataWygasniecia(),$baner->GetDataEmisji(),$baner->GetUrlDocelowy(),$baner->GetGIDGrafiki(),$baner->GetEmbed());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete baner from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteBaner($gid, $idLng = 0){
        $query = "DELETE FROM #S#banery WHERE 1=1 ";
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
	 * Add or edit if exists, given baner object.
	 * @param Baner $baner
	 */
	public static function AddEditBaner(Baner $baner){
		if($baner != null){
            self::DeleteBaner($baner->GetGID(), $baner->GetIdJezyk());
			self::AddBaner($baner);
		}
	}

    /**
     * Returns list of Baner objects for a given filters.
     * @param int $gid_grupy_serwisu
     * @param int $gid_miejsca_serwisu
     * @param int $id_jezyka
     * @return Baner[]
     */
    public static function PobierzAktywneBaneryReklamoweJezyki($gid_grupy_serwisu = 0, $gid_miejsca_serwisu = 0, $id_jezyka = 1045, $sort=""){
        $params = array("Aktywny", (int) $id_jezyka, Config::$WebGID);
        $query = "SELECT * FROM #S#banery WHERE Status=? AND IdJezyk=? AND serwisy_GID=? ";
        if($gid_grupy_serwisu > 0) { $query .= " AND miejsca_grupa_serwisu=? "; $params[] = (int) $gid_grupy_serwisu; }
        if($gid_miejsca_serwisu > 0) { $query .= " AND miejsca_miejsce_serwisu=? "; $params[] = (int) $gid_miejsca_serwisu; } 
        if($sort != "") $query.="ORDER BY ".$sort;
                
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        $list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$list[count($list)] = self::BuildBaner($row);
		}
		return $list;
    }

}

?>
