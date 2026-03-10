<?php

/**
 * Class provides methods for managing the properties of offers.
 * @author Jakub Konieczka
 *
 */
class Properties{
	
	private static $_PropertiesIds = null;
	private static $_PropertiesNames = null; 
	
	/**
	 * Creates an property object on the basis of data from the database.
	 * @param array $row
	 * @return Property
	 */
	protected static function BuildProperty($row){
		$prop = new Property($row['id'],$row['name'],$row['date']);
		return $prop;
	}
    
    /**
	 * Load properties to cache.
	 */
	protected static function LoadProperties(){
        self::$_PropertiesIds = array();
		self::$_PropertiesNames = array();
        if(Config::$UsePropertiesDiskCache && file_exists(VIRGO_API_DIR."/properties_cache.bin")){
            $h = fopen(VIRGO_API_DIR."/properties_cache.bin", "r");
            $buf = fread($h, filesize(VIRGO_API_DIR."/properties_cache.bin"));
            fclose($h);
            $arr = unserialize($buf);
            foreach($arr as $id=>$vals){
                $row = array('id' => $id, 'name' => $vals[0], 'date' => $vals[1]);
                $prop = self::BuildProperty($row);
                self::$_PropertiesIds[$prop->GetID()] = $prop;
                self::$_PropertiesNames[$prop->GetName()] = $prop;
            }
        }else{
            $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT * FROM #S#properties");
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                $prop = self::BuildProperty($row);
                self::$_PropertiesIds[$prop->GetID()] = $prop;
                self::$_PropertiesNames[$prop->GetName()] = $prop;
            }
        }
	}

	/**
	 * Returns an property object from the database by ID.
	 * @param int $id
	 * @return Property
	 */
	public static function GetProperty($id){
        if(self::$_PropertiesIds == null) self::LoadProperties();
		if(array_key_exists($id, self::$_PropertiesIds))
			return self::$_PropertiesIds[$id];
		else{
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#properties WHERE id=?", array((int) $id));
			if($result){
				$row = DataBase::GetDbInstance()->FetchArray($result);
				if($row) return self::BuildProperty($row);
				else return null;
			}else return null;
		}
	}
    
    /**
     * Returns list of properties ready to serialize.
     * @return array
     */
    public static function GetProperties(){
        $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT * FROM #S#properties");
        $arr = array();
        while($row = DataBase::GetDbInstance()->FetchArray($result)){
            $prs = self::BuildProperty($row);
            $arr[$prs->GetID()]=array($prs->GetName(), $prs->GetDate());
        }
        return $arr;
    }
	
	/**
	 * Returns an property object from the database by NAME.
	 * @param string $name
	 * @return Property
	 */
	public static function GetPropertyName($name){
        if(self::$_PropertiesNames == null) self::LoadProperties();
		if(array_key_exists($name, self::$_PropertiesNames))
			return self::$_PropertiesNames[$name];
		else{
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#properties WHERE name=?", array($name));
			if($result){
				$row = DataBase::GetDbInstance()->FetchArray($result);
				if($row) return self::BuildProperty($row);
				else return null;
			}else return null;
		}
	}

	/**
	 * Add given property object to database.
	 * @param Property $prop
	 */
	public static function AddProperty(Property $prop){
		$query = "INSERT INTO #S#properties (name, date) VALUES(?, now());";
		$params = array($prop->GetName());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		self::LoadProperties();
	}
	
	/**
	 * Save given property object (propNew) to database.
	 * @param Property $propOld
	 * @param Property $propNew
	 */
	public static function EditProperty(Property $propOld, Property $propNew){
		$query = "UPDATE #S#properties SET name=?, date=? WHERE id=?;";
		$params = array($propNew->GetName(), $propNew->GetDate(), (int) $propOld->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		self::LoadProperties();
	}

	/**
	 * Returns an property object if it exists, if not it adds it.
	 * @param string $name
	 * @return Property
	 */
	public static function GetWithAddProperty($name){
		$prop = self::GetPropertyName($name);
		if($prop == null){
			self::AddProperty(new Property(0, $name, date("Y-m-d")));
			return self::GetWithAddProperty($name);
		}else{
			return $prop;
		}
	} 
	
	/**
	 * Save value of given property in given offer.
	 * @param Property $prop
	 * @param Offer $offer
	 * @param string $value
     * @param bool $isNew
     * @param array $dbValuesList
	 */
	public static function SavePropertyValue(Property $prop, Offer $offer, $value, $isNew, $dbValuesList){
        is_numeric($offer->GetId())?$get_id=(int) $offer->GetId():$get_id=$offer->GetId();
        is_numeric($offer->GetIdLng())?$get_lng_id=(int) $offer->GetIdLng():$get_lng_id=$offer->GetIdLng();
        is_numeric($prop->GetID())?$get_prop_id=(int) $prop->GetID():$get_prop_id=$prop->GetID();
            
        if($isNew){
            //if offer is new, then directly make insert, instead of checking if record exist in db
            if(is_array($value)){
                foreach($value as $val){
                    $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("INSERT INTO #S#offers_properties (offers_id, offers_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, true)", array($get_id, $get_lng_id, $get_prop_id, $val));
                }
            }else{
                $query = "INSERT INTO #S#offers_properties (offers_id, offers_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, false)";
                $params = array($get_id, $get_lng_id, $get_prop_id, $value);
                $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
            }
        }else{            
            //if offer exist, check if value exist in db
            if(is_array($value)){
                $dbvalues = array();
                if(array_key_exists($prop->GetID(), $dbValuesList)){
                    foreach ($dbValuesList[$prop->GetID()] as $dbval) {
                        if(in_array($dbval["value"], $value)){
                            $dbvalues[count($dbvalues)] = $dbval["value"];
                        }else{
                            //delete from database
                            $result2 = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_properties WHERE id=?", array((int) $dbval['id']));
                        }
                    }
                }
                foreach($value as $val){
                    if(!in_array($val, $dbvalues)){
                        //insert to database
                        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("INSERT INTO #S#offers_properties (offers_id, offers_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, true)", array($get_id, $get_lng_id, $get_prop_id, $val));
                    }
                }
            }else{
                $row = null; ;
                if(array_key_exists($prop->GetID(), $dbValuesList)) $row = $dbValuesList[$prop->GetID()][0];
                $query = "";
                if($row == null){
                    $query = "INSERT INTO #S#offers_properties (offers_id, offers_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, false)";
                    $params = array($get_id, $get_lng_id, $get_prop_id, $value);
                }
                else if($row['value'] != $value){
                    $query = "UPDATE #S#offers_properties SET value=? WHERE offers_id=? AND offers_id_lng=? AND properties_id=?";
                    $params = array($value, (int) $get_id, (int) $get_lng_id, (int) $get_prop_id);
                }
                if($query != ""){
                    $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
                }
            }
        }
	}
    
    /**
	 * Save value of given property in given investment.
	 * @param Property $prop
	 * @param Investment $inv
	 * @param string $value
     * @param bool $isNew
     * @param array $dbValuesList
	 */
	public static function SavePropertyValueForInv(Property $prop, Investment $inv, $value, $isNew, $dbValuesList){
        is_numeric($inv->GetId())?$get_id=(int) $inv->GetId():$get_id=$inv->GetId();
        is_numeric($inv->GetIdLng())?$get_lng_id=(int) $inv->GetIdLng():$get_lng_id=$inv->GetIdLng();
        is_numeric($prop->GetID())?$get_prop_id=(int) $prop->GetID():$get_prop_id=$prop->GetID();
            
        if($isNew){
            //if offer is new, then directly make insert, instead of checking if record exist in db
            if(is_array($value)){
                foreach($value as $val){
                    $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("INSERT INTO #S#investments_properties (investments_id, investments_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, true)", array($get_id, $get_lng_id, $get_prop_id, $val));
                }
            }else{
                $query = "INSERT INTO #S#investments_properties (investments_id, investments_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, false)";
                $params = array($get_id, $get_lng_id, $get_prop_id, $value);
                $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
            }
        }else{            
            //if offer exist, check if value exist in db
            if(is_array($value)){
                $dbvalues = array();
                if(array_key_exists($prop->GetID(), $dbValuesList)){
                    foreach ($dbValuesList[$prop->GetID()] as $dbval) {
                        if(in_array($dbval["value"], $value)){
                            $dbvalues[count($dbvalues)] = $dbval["value"];
                        }else{
                            //delete from database
                            $result2 = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments_properties WHERE id=?", array((int) $dbval['id']));
                        }
                    }
                }
                foreach($value as $val){
                    if(!in_array($val, $dbvalues)){
                        //insert to database
                        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("INSERT INTO #S#investments_properties (investments_id, investments_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, true)", array($get_id, $get_lng_id, $get_prop_id, $val));
                    }
                }
            }else{
                $row = null;
                if(array_key_exists($prop->GetID(), $dbValuesList)) $row = $dbValuesList[$prop->GetID()][0];
                $query = "";
                if($row == null){
                    $query = "INSERT INTO #S#investments_properties (investments_id, investments_id_lng, properties_id, value, `set`) VALUES(?, ?, ?, ?, false)";
                    $params = array($get_id, $get_lng_id, $get_prop_id, $value);
                }else if($row['value'] != $value){
                    $query = "UPDATE #S#investments_properties SET value=? WHERE investments_id=? AND investments_id_lng=? AND properties_id=?";
                    $params = array($value, (int) $get_id, (int) $get_lng_id, (int) $get_prop_id);
                }
                if($query != ""){
                    $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
                }
            }
        }
	}
	
}

?>