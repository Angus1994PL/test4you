<?php

/**
 * Class provides methods for managing departments.
 * @author Jakub Konieczka
 *
 */
class Departments{

	/**
	 * Creates an Department object on the basis of data from the database.
	 * @param array $row
	 * @return Department
	 */
	protected static function BuildDepartment($row){
		$dep = new Department($row['id'],$row['name'],$row['name2'],$row['address'],$row['city'],$row['postcode'],$row['nip'],$row['province'],
			$row['www'],$row['phone'],$row['email'],$row['fax'],$row['remarks'],$row['header'],$row['footer'],$row['logo_file'],$row['photo_file'],
                        $row['subdomena'], $row['organization_id']);
		return $dep;
	}

    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null)
    {
        if ($filters == null)
        {
            return $filters;
        }
            
        $tab_with_numeric_value = array("Id", "bezId", "IdOrganizacji");
        $array_values = array('Ids');
        $newFilters = array();
        foreach ($filters as $key => $value) {
            if (!is_array($value)) {
                if (array_search($key, $tab_with_numeric_value) !== false) {
                    if (is_numeric($value)) {
                        $newFilters[$key] = (int) trim($value, '\'"');
                    }
                } elseif (array_search($key, $array_values) !== false) {
                    array_pop($newFilters);
                    $newFilters = array_merge($newFilters, self::prepareStringToArray($value, true));
                } else {
                    $newFilters[$key] = trim($value, '\'"');
                }
            } else {
                array_pop($newFilters);
                $newFilters = array_merge($newFilters, self::prepareStringToArray($value, true));
            }
            switch ($key) {
                case "uniqueOrganization":array_pop($newFilters);
                    break;
            }
        }
        return $newFilters;
    }

    /**
	* Returns cleared value for SQL query.
	* @param string $value
	* @return string
	*/
	protected static function prepareSort($sort){	
		$sort = strtolower($sort);
		$orderbyArray = array('Id', 'Nazwa', 'Miasto', 'Licencja');
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
	 * Creates SQL query, including sorting and filters.
	 * @param string $select
	 * @param string $sorting
	 * @param array $filters
	 * @return string
	 */
	protected static function GetQuery($select = "SELECT d.* ", $sorting = "", $filters = null){
		$query = $select . " FROM #S#departments AS d ";
        
        if ($filters != null && ((array_key_exists("Licencja", $filters) || (array_key_exists("AgentNazwa", $filters))))) { 
            $query .= " INNER JOIN #S#agents AS a ON a.departments_id=d.id ";
        } 
        $query .= " WHERE 1=1 ";
        
		if($filters != null){
			foreach ($filters as $key => $value){
				switch ($key) {
					case "Id": $query .= " AND d.id=?"; break;
                    case "Ids": $query .= " AND d.id IN (".self::prepareStringToBind($value).")"; break;
					case "bezId": $query .= " AND d.id<>?"; break;                    
					case "Nazwa": $query .= " AND d.name LIKE ?"; break;
					case "Miasto": $query .= " AND d.city LIKE ?"; break;
                    case "IdOrganizacji": $query .= " AND d.organization_id=?"; break;
                    case "uniqueOrganization": $query.= " AND d.id in (SELECT MIN(d2.id) FROM #S#departments AS d2 GROUP BY d2.organization_id) ";break;
                    case "Licencja": $query.=" AND a.licence_no=?"; break;
                    case "AgentNazwa": $query.=" AND a.name LIKE ?"; break;
					default: $query .= " AND d.$key=?"; break;
				}
			}
		}
		if($sorting != ""){
			$query .= " ORDER BY d.".self::prepareSort($sorting);
		}
		return $query;
	}
    
    /**
	 * Delete all redundant departments, that are no longer published.
	 * @param int $departmentIds
	 */
    public static function DeleteRedundantDepartments($departmentsIds){
        if(count($departmentsIds) > 0){
            $inBind = implode(',', array_fill(0, count($departmentsIds), '?'));			
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#departments WHERE id NOT IN ($inBind)", array_values($departmentsIds));
        }else{
			$result = DataBase::GetDbInstance()->ExecuteQuery("DELETE FROM #S#departments");
        }
    }
    
	/**
	 * Returns an department object from the database by ID.
	 * @param int $id
	 * @return Department
	 */
	public static function GetDepartment($id){		
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#departments WHERE id=?", array((int) $id));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildDepartment($row);
			else return null;
		}else return null;
	}
	
	/**
	 * Returns a list of departments for given parameters.
	 * @param array $filters
	 * @param int $strona
	 * @param int $ile_na_strone
	 * @param string $sort
	 * @return Department[]
	 */
	public static function GetDepartments($filters = null, $strona = 0, $ile_na_strone = 0, $sort = ""){
        if($filters==null) $filters = array();
		$newfilters = self::PrepareFilters($filters);
        
        $query = self::GetQuery("SELECT d.* ", $sort, $filters);
		if($strona >= 0 && $ile_na_strone > 0) $query .= " LIMIT " . ($strona * $ile_na_strone) . ", $ile_na_strone";
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array_values($newfilters));
        
		$list = array();
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
            $list[count($list)] = self::BuildDepartment($row);
		}
		return $list;
	}
    
	/**
	 * Returns a unique list of locations used in departments.
	 * @return string[]
	 */
	public static function GetLocations(){
		$db = DataBase::GetDbInstance();
		$query = "SELECT DISTINCT(city) FROM #S#departments WHERE 1=1 AND city<>'' ";
		$query .= " ORDER BY city ASC";
                
        $result = $db->ExecuteQuery($query);
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
        
	/**
	 * Returns a unique list of provinces used in departments.
	 * @return string[]
	 */
	public static function GetProvinces(){
		$db = DataBase::GetDbInstance();
		$query = "SELECT DISTINCT(province) FROM #S#departments WHERE 1=1 AND province<>'' ";
		$query .= " ORDER BY province ASC";
                
        $result = $db->ExecuteQuery($query);
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
        
	/**
	 * Returns a department's province.
	 * @param int $iddept 
	 * @return string[]
	 */
	public static function GetDepartmentProvince($iddept){
		
		$db = DataBase::GetDbInstance();
		$query = "SELECT DISTINCT(province) FROM #S#departments AS d WHERE 1=1 AND d.province<>'' ";
		$params = array();		
		
		if(is_numeric($iddept) && $iddept>0) { 
			$query.=" AND d.id=?"; 
			$params[] = (int) $iddept;
		}
                
		$query .= " ORDER BY d.province ASC";
		$result = $db->ExecuteQueryWithParams($query, $params);
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
        
	/**
	 * Add given department object to database.
	 * @param Department $dep
	 */
	public static function AddDepartment(Department $dep){
		$query = "INSERT INTO #S#departments (id, name, name2, address, city, postcode, nip, province, www, phone, email, fax, remarks, header, footer, logo_file, photo_file, subdomena, organization_id)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($dep->GetId(), $dep->GetName(), $dep->GetName2(), $dep->GetAddress(), $dep->GetCity(), $dep->GetPostCode(), $dep->GetNip(), $dep->GetProvince(), $dep->GetWww(),
                        $dep->GetPhone(), $dep->GetEmail(), $dep->GetFax(), $dep->GetRemarks(), $dep->GetHeader(), $dep->GetFooter(), $dep->GetLogoFile(), $dep->GetPhotoFile(), $dep->GetSubdomena(),
                        $dep->GetOrganizationID());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Save given department object (depNew) to database. 
	 * @param Department $depNew
	 */
	public static function EditDepartment(Department $depNew){
		$query = "UPDATE #S#departments SET name=?, address=?, city=?, postcode=?, nip=?, province=?, www=?, phone=?, email=?, fax=?, remarks=?, header=?, footer=?, logo_file=?, photo_file=?, name2=?, subdomena=?, organization_id=? WHERE id=?;";
		$params = array($depNew->GetName(), $depNew->GetAddress(), $depNew->GetCity(), $depNew->GetPostCode(), $depNew->GetNip(), $depNew->GetProvince(), $depNew->GetWww(),
                        $depNew->GetPhone(), $depNew->GetEmail(), $depNew->GetFax(), $depNew->GetRemarks(), $depNew->GetHeader(), $depNew->GetFooter(), $depNew->GetLogoFile(), $depNew->GetPhotoFile(),
                        $depNew->GetName2(), $depNew->GetSubdomena(), $depNew->GetOrganizationID(), (int) $depNew->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Add or edit if exists, given department object.
	 * @param Department $dep
	 */
	public static function AddEditDepartment(Department $dep){
		$d = self::GetDepartment($dep->GetId());
		if($d == null){
			self::AddDepartment($dep);
		}else{
			self::EditDepartment($dep);
		}
	}
    
    /**
	 * Delete department from database, given by ID.
	 * @param int $id
	 */
	public static function DeleteDepartment($id=0){
        $params = array();
        $query = "DELETE FROM #S#departments WHERE 1=1 ";
        if($id > 0){
            $query .= " AND id=?";
            $params[] = $id;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
    
    
    /**
     * Converts array or string to GetQuery
     * @param mixed $value
     * @return string[]
     */
	protected static function prepareStringToArray($value, $numeric=false){		
        if(is_array($value)) $arr=$value;
		else $arr = explode(',', $value);
		$newArr = array();
		foreach($arr as $value){
			if($numeric && is_int(trim($value, "' "))) $newArr[] = (int) trim($value, "' ");
            else $newArr[] = trim($value, "' ");
		}
		return $newArr;
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
    

}

?>