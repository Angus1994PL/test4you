<?php

/**
 * Description of serwisy
 *
 * @author Jakub Konieczka
 */
class Serwisy {

    /**
     * List of services
     * @var array
     */
    private static $serwisy;

    /**
     * Creates a Serwis object on the basis of data from the database.
     * @param array $row
     * @return Serwis
     */
    protected static function BuildSerwis($row){
		$serwis = new Serwis($row['GID'],$row['IdJezyk'],$row['NazwaFirmy'],$row['AdresWWW'],$row['EmailKontaktowy'],$row['StartowyJezyk'],$row['departments_id'],$row['agents_id'],
            $row['Mieszkania'],$row['Domy'],$row['Dzialki'],$row['Lokale'],$row['Hale'],$row['Gospodarstwa'],$row['Kamienice'],$row['Biurowce'],$row['RodzajeOfert'],$row['TagTitle'],
            $row['TagKeywords'],$row['TagDescription'],$row['Head'],$row['Body'],$row['Foot']);
		return $serwis;
	}

    /**
	 * Returns an serwis object from the database by GID and ID LNG.
	 * @param int $gid
     * @param int $idLng
	 * @return Serwis
	 */
	public static function GetSerwis($gid, $idLng = ""){
        if (empty($idLng)){
            if (count(static::$serwisy[$gid]) == 0){
                $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#serwisy WHERE GID=?", array($gid));
                if ($result){
                    while ($row = DataBase::GetDbInstance()->FetchArray($result)){
                        static::$serwisy[$gid][$row["IdJezyk"]] = self::BuildSerwis($row);
                    }
                    if (!empty(static::$serwisy[$gid][1045])){
                        $idLng = 1045;
                    } else {
                        $idLng = reset(static::$serwisy[$gid])->GetIdJezyk();
                    }
                } else {
                    return NULL;
                }
            } else {
                if (!empty(static::$serwisy[$gid][1045])){
                    $idLng = 1045;
                } else {
                    $idLng = reset(static::$serwisy[$gid])->GetIdJezyk();
                }
            }
        }
        if(empty(static::$serwisy[$gid][$idLng])) {
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#serwisy WHERE GID=? AND IdJezyk=?", array($gid, (int) $idLng));
            if($result){
                $row = DataBase::GetDbInstance()->FetchArray($result);
                if($row) {
                    static::$serwisy[$gid][$idLng] = self::BuildSerwis($row);
                    return static::$serwisy[$gid][$idLng];
                }
                else return null;
            }else return null;
        }
        else {
            return static::$serwisy[$gid][$idLng];
        }
	}

	/**
	 * Add given serwis object to database.
	 * @param Serwis $serwis
	 */
	public static function AddSerwis(Serwis $serwis){
		$query = "INSERT INTO #S#serwisy (GID, IdJezyk, NazwaFirmy, AdresWWW, EmailKontaktowy, StartowyJezyk, departments_id, agents_id, Mieszkania, Domy, Dzialki, Lokale, Hale,
            Gospodarstwa, Kamienice, Biurowce, RodzajeOfert, TagTitle, TagKeywords, TagDescription, Head, Body, Foot)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($serwis->GetGID(),$serwis->GetIdJezyk(),$serwis->GetNazwaFirmy(),$serwis->GetAdresWWW(), $serwis->GetEmailKontaktowy(), $serwis->GetStartowyJezyk(),
            $serwis->Getdepartments_id(), $serwis->Getagents_id(), $serwis->GetMieszkania(), $serwis->GetDomy(), $serwis->GetDzialki(), $serwis->GetLokale(), $serwis->GetHale(),
            $serwis->GetGospodarstwa(), $serwis->GetKamienice(), $serwis->GetBiurowce(), $serwis->GetRodzajeOfert(), $serwis->GetTagTitle(), $serwis->GetTagKeywords(),
            $serwis->GetTagDescription(), $serwis->GetHead(), $serwis->GetBody(), $serwis->GetFoot());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete serwis from database, given by GID. If idLng=0 then deletes all records with given GID.
	 * @param int $gid
     * @param int $idLng
	 */
	public static function DeleteSerwis($gid, $idLng = 1045){
        $query = "DELETE FROM #S#serwisy WHERE 1=1 ";
        $params = array();
        if($gid > 0){
            $query .= " AND GID=?";
            $params[] = $gid;
        }
        $query .= " AND IdJezyk=?";
        $params[] = (int) $idLng;
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if exists, given serwis object.
	 * @param Serwis $serwis
	 */
	public static function AddEditSerwis(Serwis $serwis){
		if($serwis != null){
            self::DeleteSerwis($serwis->GetGID(), $serwis->GetIdJezyk());
			self::AddSerwis($serwis);
		}
	}

    /**
     *
     * @param string $serwis
     * @param string $serwisGID
     * @param array $params
     */
    public static function SaveParams($serwisGID, array $params){
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#serwisy_parametry WHERE serwisy_GID=?", array($serwisGID));
        foreach ($params as $key => $value) {
            $query = "INSERT INTO #S#serwisy_parametry VALUES(?, ?, ?)";
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array($serwisGID, $key, $value));
        }
    }

    /**
     * Return list of languages in service.
     * @param string $serwisGID
     * @return Language[]
     */
    public static function GetAvailableLanguages($serwisGID = null){
        $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT DISTINCT(IdJezyk) FROM #S#serwisy " . ($serwisGID == null ? "" : "WHERE GID='$serwisGID'") . " ORDER BY IdJezyk ASC");
        $lngs = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$lngs[$ndx] = new Language($row['IdJezyk']);
			$ndx++;
		}
		return $lngs;
    }

    /**
     * Returns an default serwis object from the database by ID LNG.
     * @param int $idLng
     * @return Serwis
     */
    public static function PobierzSerwisJezykGID($idLng){
        return self::GetSerwis(Config::$WebGID, $idLng);
    }

    /**
     * Return list of languages in service.
     * @param string $serwisGID
     * @return Language[]
     */
    public static function PobierzJezykiSerwisow($serwisGID = null){
        if($serwisGID == null) $serwisGID = Config::$WebGID;
        return self::GetAvailableLanguages($serwisGID);
    }

    /**
     * Returns list of service params for given service.
     * @param Serwis $serwis
     * @return array
     */
    public static function GetSerwisParametry(Serwis $serwis){
        if (!($result = OffersHelper::getMethodResultCache($serwis))) {
            $list = array();
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#serwisy_parametry WHERE serwisy_GID=?", array($serwis->GetGID()));
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                $key = $row['Nazwa'];
                $value = $row['Wartosc'];
                $list[$key] = $value;
            }
            OffersHelper::setMethodResultCache($list, $serwis);
            return $list;
        }
        return $result;
	}

}
?>
