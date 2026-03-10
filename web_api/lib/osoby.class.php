<?php

/**
 * Class provides methods for managing osoba.
 * @author marcinw
 */

class Osoby {
    
    /**
	 * Creates an Osoba object on the basis of data from the database.
	 * @param array $row
	 * @return Osoba
	 */
	protected static function BuildOsoba($row){
		$o = new Agent($row['id'],$row['name'],$row['last_name'],$row['email'],$row['phone'], $row['login'], $row['pwd'], $row['registration_date'], $row['user_id']);
		return $o;
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null){
        $tab_with_numeric_value= array("id", "user_id");
        if($filters == null) return $filters;
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
		$orderbyArray = array('id', 'name', 'last_name', 'email', 'login');
		$destArray = array('asc', 'desc');
		
		$exp_sort = explode(',', $sort);
		
		$return = '';
		foreach($exp_sort as $value){
            $exp = explode(' ', trim($value));
            $orderby = 'id';
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
        $query = $select . " FROM #S#osoby AS o ";
        $query .= " WHERE 1=1 ";
		if($filters != null){
            foreach ($filters as $key => $value){
				switch ($key) {
                    case "id": $query .= " AND o.id=?"; break;
                    case "user_id": $query .= " AND o.user_id=?"; break;
					case "name": $query .= " AND o.name LIKE ?"; break;
                    case "last_name": $query .= " AND o.last_name LIKE ?"; break;
                    case "email": $query .= " AND o.email = ?"; break;
                    case "pwd": $query .= " AND o.name = ?"; break;
                    case "login": $query .= " AND o.login = ?"; break;
                }
            }
        }
        if($sorting != ""){
			$query .= " ORDER BY o.".self::prepareSort($sorting);
		}
        return $query;
    }
    
    /**
	 * Returns an osoba object from the database by ID.
	 * @param int $id
	 * @return Osoba
	 */
	public static function GetOsoba($id){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#osoby WHERE id=?", array((int) $id));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildOsoba($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given osoba object to database.
	 * @param Osoba $o
	 */
	public static function AddOsoba(Osoba $o){
		$query = "INSERT INTO #S#osoby (id, name, last_name, email, phone, login, pwd, registration_date, user_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($o->GetId(), $o->GetName(), $o->GetLastName(), $o->GetEmail(), $o->GetPhone(), $o->GetLogin(), $o->GetPwd(), $o->GetRegistrationDate(), $o->GetUserId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
    /**
	 * Delete osoba from database, given by id.
	 * @param int $id
	 */
	public static function DeleteOsoba($id){
        $params = array();
        $query = "DELETE FROM #S#osoby WHERE 1=1 ";
        if($id > 0){
            $query .= " AND id=?";
            $params[] = (int) $id;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
    
	/**
	 * Save given osoba object (oNew) to database. 
	 * @param Osoba $oNew
	 */
	public static function EditOsoba(Osoba $oNew){
		$query = "UPDATE #S#osoby SET name=?, last_name=?, email=?, phone=?, login=?, pwd=?, registration_date=?, user_id=? WHERE id=?;";
		$params = array($oNew->GetName(), $oNew->GetLastName(), $oNew->GetEmail(), $oNew->GetPhone(), $oNew->GetLogin(), $oNew->GetPwd(),$oNew->GetRegistrationDate(), $oNew->GetUserId(), (int) $oNew->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Add or edit if exists, given osoba object.
	 * @param Osoba $o
	 */
	public static function AddEditOsoba(Osoba $o){
		$a = self::GetOsoba($o->GetId());
		if($a == null){
			self::AddOsoba($o);
		}else{
			self::EditOsoba($o);
		}
	}
    
    /**
	 * Returns a list of osoba, take into account the filtering and sorting.
	 * @param RefreshEventArgs $args
	 * @return Osoba[]
	 */
	public static function GetOsoby(RefreshEventArgs $args){
		$db = DataBase::GetDbInstance();
        $filters = self::PrepareFilters($args->Filters);
		$query = self::GetQuery("SELECT COUNT(*)", "", $args->Filters);
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		$row = $db->FetchArray($result);
		$args->SetRowsCount($row[0]);
		
		$query = "SELECT o.id, o.name, o.last_name, o.email, o.phone, o.login, o.pwd, o.registration_date, o.user_id ";
		$query = self::GetQuery($query, $args->Sorting, $args->Filters);
		$args->SetLimit($query);
		$list = array();
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		while($row = $db->FetchArray($result)){
			$list[count($list)] = self::BuildOsoba($row);
		}
		return $list;
	}
}
