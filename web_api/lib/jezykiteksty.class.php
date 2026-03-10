<?php

/**
 * Description of jezyki_teksty
 * @author Jakub Konieczka
 */
class JezykiTeksty {

    private static $_cache = null;

    /**
     * Creates a Jezyk object on the basis of data from the database.
     * @param array $row
     * @return JezykTekst
     */
    protected static function BuildJezyk($row){
		$jezyk = new JezykTekst($row['klucz'], $row['IdJezyk'], $row['wartosc']);
		return $jezyk;
	}
    
    protected static function LoadJezyki(){
        if(self::$_cache == null){
            if(Config::$UseLanguageDiskCache && file_exists(WEB_API_DIR."/jezyki_cache.bin")){
                $h = fopen(WEB_API_DIR."/jezyki_cache.bin", "r");
                $buf = fread($h, filesize(WEB_API_DIR."/jezyki_cache.bin"));
                fclose($h);
                self::$_cache = unserialize($buf);
            }else{
                $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT * FROM #S#jezyki");
                self::$_cache = array();
                
                while($row = DataBase::GetDbInstance()->FetchArray($result)){
                    $jt = self::BuildJezyk($row);
                    if(!array_key_exists($jt->GetKlucz(), self::$_cache))
                        self::$_cache[$jt->GetKlucz()] = array();
                    self::$_cache[$jt->GetKlucz()][$jt->GetIdJezyk()] = $jt->GetWartosc();
                }
                $ServiceConfig = new ServiceConfig();
                $custmLanguages = $ServiceConfig->GetLanguages();
                $shortCustmLanguages = [];
                if (count($custmLanguages)>1){
					foreach ($custmLanguages as $key => $lng){
						foreach ($lng as $key1 => $lng1){
                            $shortCustmLanguages[$key1] = $lng1;
						}
					}
				} else {
					$shortCustmLanguages = $custmLanguages;
				}
                foreach($shortCustmLanguages as $key => $lang){
                    if (!array_key_exists($key,self::$_cache)){
                        self::$_cache[$key] = array();
                    }
                    foreach ($lang as $country => $value){
                        $langNumber = self::LangStringToNumer($country);
                        if ($langNumber != 0){
                            self::$_cache[$key][$langNumber] = $value;
                        }
                    }
                }
            }
        }
    }
    
    protected static function LangStringToNumer($string){
        $langNumber = 0;
        switch ($string) {
            case 'pl':
                $langNumber = 1045;
                break;
            case 'en':
                $langNumber = 2047;
                break;
            case 'ru':
                $langNumber = 1049;
                break;
            case 'de':
                $langNumber = 1031;
                break;
            case 'es':
                $langNumber = 1034;
                break;
        }
        
        return $langNumber;
    }
    
    /**
     *
     * @param string $key
     * @param int $igLng
     * @return JezykTekst
     */
    protected static function FindJezyk($key, $igLng = 1045){
        if(array_key_exists($key, self::$_cache)) {
            if(array_key_exists($igLng, self::$_cache[$key])) {
                return self::$_cache[$key][$igLng];
            }
        }
        return null;
    }

    /**
	 * Returns an language text object from the database by key.
	 * @param string $key
     * @param int $idLng
	 * @return JezykTekst
	 */
	public static function GetJezyk($key, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#jezyki WHERE klucz=? AND IdJezyk=?", array($key, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildJezyk($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given language text object to database.
	 * @param JezykTekst $jezyk
	 */
	public static function AddJezyk(JezykTekst $jezyk){
		$query = "INSERT INTO #S#jezyki (klucz, IdJezyk, wartosc) VALUES(?, ?, ?);";
        $params = array($jezyk->GetKlucz(), $jezyk->GetIdJezyk(), $jezyk->GetWartosc());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		//echo $result;
	}

    /**
	 * Delete language text from database, given by key.
	 * @param string $key
     * @param int $idLng
	 */
	public static function DeleteJezyk($key, $idLng = 0){
        $query = "DELETE FROM #S#jezyki WHERE 1=1";
        $params = array();
         if($key != null){
            $query .= " AND klucz=?";
            $params[] = $key;
        }
        if($idLng > 0){
            $query .= " AND IdJezyk=?";
            $params[] = (int) $idLng;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if exists, given language text object.
	 * @param JezykTekst $jezyk
	 */
	public static function AddEditJezyk(JezykTekst $jezyk){
		if($jezyk != null){
            self::DeleteJezyk($jezyk->GetKlucz(), $jezyk->GetIdJezyk());
			self::AddJezyk($jezyk);
		}
	}    

    /**
     * Translates the given key if it exists.
     * @param string $key
     * @param int $igLng 
     */
    public static function Lng($key, $igLng = 1045){
        self::LoadJezyki();
        //var_dump(self::$_cache);
        $jt = self::FindJezyk($key, $igLng);
        if($jt == null) {
            if($igLng <> 1045) $jt = self::FindJezyk($key, 1045);
        }
        if($jt == null) return $key;
        return $jt;
    }

    /**
     * Returns list of language text ready to serialize.
     * @return array
     */
    public static function GetJezyki(){
        $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT * FROM #S#jezyki");
        $arr = array();
        while($row = DataBase::GetDbInstance()->FetchArray($result)){
            $jt = self::BuildJezyk($row);
            //$arr[count($arr)] = $jt;
            if(!array_key_exists($jt->GetKlucz(), $arr))
                $arr[$jt->GetKlucz()] = array();
            $arr[$jt->GetKlucz()][$jt->GetIdJezyk()] = $jt->GetWartosc();
        }
        return $arr;
    }
    
}

?>
