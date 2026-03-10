<?php

/**
 * Description of opcje
 *
 * @author Jakub Konieczka
 */
class Opcje {

    private static $_cache = null;    

    /**
     * Creates an Opcja object on the basis of data from the database.
     * @param array $row
     * @return Opcja
     */
    protected static function BuildOpcja($row){
		$opcja = new Opcja($row['klucz'],$row['wartosc']);
		return $opcja;
	}

    /**
	 * Returns an option object from the database by key.
	 * @param string $key
	 * @return Opcja
	 */
	public static function GetOpcja($key){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#opcje WHERE klucz=?", array($key));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildOpcja($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given option object to database.
	 * @param Opcja $opcja
	 */
	public static function AddOpcja(Opcja $opcja){
		$query = "INSERT INTO #S#opcje (klucz, wartosc) VALUES(?, ?);";
		$params = array($opcja->GetKlucz(),$opcja->GetWartosc());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Delete option from database, given by key.
	 * @param atring $key
	 */
	public static function DeleteOpcja($key){
        $query = "DELETE FROM #S#opcje WHERE 1=1";
        $params = array();
         if($key != null){
            $query .= " AND klucz=?";
            $params[] = $key;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if exists, given option object.
	 * @param Opcja $opcja
	 */
	public static function AddEditOpcja(Opcja $opcja){
		if($opcja != null){
            self::DeleteOpcja($opcja->GetKlucz());
			self::AddOpcja($opcja);
		}
	}       
    
    private static function LoadOpcje(){
        //var_dump(self::$_cache);
        if(self::$_cache == null){
            if(Config::$UseOptionsDiskCache && file_exists(WEB_API_DIR."/opcje_cache.bin")){
                $h = fopen(WEB_API_DIR."/opcje_cache.bin", "r");
                $buf = fread($h, filesize(WEB_API_DIR."/opcje_cache.bin"));
                fclose($h);
                self::$_cache = unserialize($buf);
            }else{
                $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT * FROM #S#opcje");
                self::$_cache = array();
                while($row = DataBase::GetDbInstance()->FetchArray($result)){
                    $op = self::BuildOpcja($row);
                    self::$_cache[$op->GetKlucz()] = $op->GetWartosc();
                }
            }
        }
    }

    /**
     * Returns the option object.
     * @param string $key
     * @return Opcja
     */
    private static function FindOpcja($key){
        if(array_key_exists($key, self::$_cache)) {
            return self::$_cache[$key];
        }
        return null;
    }

    /**
     * Returns the value of the specified option as bool.
     * @param string $key
     * @return bool
     */
    public static function OpcjaBool($key){
        self::LoadOpcje();
        $o = self::FindOpcja($key);
        if($o == null) return false;
        else{
            if($o == "True") return true;
        }
        return false;
    }

    /**
     * Returns the value of the specified option as string
     * @param string $key
     * @return string
     */
    public static function OpcjaString($key){
        self::LoadOpcje();
        $o = self::FindOpcja($key);
        if($o == null) return "";
        else return $o;
    }

    /**
     * Returns the value of the specified option as integer
     * @param string $key
     * @return int
     */
    public static function OpcjaInt($key){
        self::LoadOpcje();
        $o = self::FindOpcja($key);
        if($o == null) return 0;
        else{
            if(is_numeric($o)) return intval($o);
        }
        return 0;
    }

    /**
     * Returns list of options ready to serialize.
     * @return array
     */
    public static function GetOpcje(){
        $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT * FROM #S#opcje");
        $arr = array();
        while($row = DataBase::GetDbInstance()->FetchArray($result)){
            $arr[$row["klucz"]] = $row["wartosc"];
        }
        return $arr;
    }
}

?>
