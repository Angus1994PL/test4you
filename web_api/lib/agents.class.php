<?php

/**
 * Class provides methods for managing agents.
 * @author Jakub Konieczka
 */
class Agents{
	
	/**
	 * Creates an agent object on the basis of data from the database.
	 * @param array $row
	 * @return Agent
	 */
	protected static function BuildAgent($row){
		$ag = new Agent($row['id'],$row['name'],$row['phone'],$row['cell'],$row['email'], $row['departments_id'], $row['jabber_login'],$row['licence_no'],
			$row['responsible_name'],$row['responsible_licence_no'],$row['comunicators'],$row['photo_file'], $row['agents_code'], $row['section']);
		return $ag;
	}

    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null){
        $tab_with_numeric_value= array("id", "departments_id");
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
            switch ($key) {	
                case "departments_ids": array_pop($newFilters); $newFilters = array_merge($newFilters, self::prepareStringToArray($value)); break;
            }
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
		$orderbyArray = array('id', 'agents_code', 'name');
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
        $query = $select . " FROM #S#agents AS a ";
        $query .= " WHERE 1=1 ";
		if($filters != null){
            foreach ($filters as $key => $value){
				switch ($key) {
                    case "id": $query .= " AND a.id=?"; break;
                    case "departments_id": $query .= " AND a.departments_id = ?";break;
                    case "departments_ids": $query .= " AND a.departments_id IN (".self::prepareStringToBind($value).")";break;
                    case "agents_code": $query .= " AND a.agents_code = ?";break;
                    case "not_agents_code": $query .= " AND a.agents_code <> ?";break;
					case "name": $query .= " AND a.name LIKE ?"; break;
                    default: $query .= " AND a.$key=?"; break;	
                }
            }
        }
        if($sorting != ""){
			$query .= " ORDER BY a.".self::prepareSort($sorting);
		}
        return $query;
    }
    
    /**
	 * Delete all redundant agents, that are no longer published.
	 * @param int $agentsIds
	 */
    public static function DeleteRedundantAgents($agentsIds){
        if(count($agentsIds) > 0){
            $inBind = implode(',', array_fill(0, count($agentsIds), '?'));
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#agents WHERE id NOT IN ($inBind)", array_values($agentsIds));
        }else{
			$result = DataBase::GetDbInstance()->ExecuteQuery("DELETE FROM #S#agents");
        }
    }
    
	/**
	 * Returns an agent object from the database by ID.
	 * @param int $id
	 * @return Agent
	 */
	public static function GetAgent($id){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#agents WHERE id=?", array((int) $id));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildAgent($row);
			else return null;
		}else return null;
	}
	
	public static function GetMLSAgentId() {
		
		$filters = array('name' => "%MLS%");
		$args = new RefreshEventArgs(1, 0, $filters);
		
		$agents = self::GetAgents($args);
		return isset($agents[0]) ? $agents[0]->GetId() : 0;
		
	}

	/**
	 * Add given agent object to database.
	 * @param Agent $ag
	 */
	public static function AddAgent(Agent $ag){
		$query = "INSERT INTO #S#agents (id, name, phone, cell, email, jabber_login, licence_no, responsible_name, responsible_licence_no, departments_id, comunicators, photo_file, agents_code, section)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($ag->GetId(), $ag->GetName(), $ag->GetPhone(), $ag->GetCell(), $ag->GetEmail(), $ag->GetJabberLogin(), $ag->GetLicenceNo(),
			$ag->GetResponsibleName(), $ag->GetResponsibleLicenceNo(), $ag->GetDepartmentId(), $ag->GetComunicators(), $ag->GetPhotoFile(), $ag->GetAgentsCode(), $ag->GetSection());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Save given agent object (agNew) to database. 
	 * @param Agent $agNew
	 */
	public static function EditAgent(Agent $agNew){
		$query = "UPDATE #S#agents SET name=?, phone=?, cell=?, email=?, jabber_login=?, licence_no=?,  responsible_name=?, responsible_licence_no=?, departments_id=?, comunicators=?, photo_file=?, agents_code=?, section=? WHERE id=?;";
		$params = array($agNew->GetName(), $agNew->GetPhone(), $agNew->GetCell(), $agNew->GetEmail(), $agNew->GetJabberLogin(), 
			$agNew->GetLicenceNo(), $agNew->GetResponsibleName(), $agNew->GetResponsibleLicenceNo(), $agNew->GetDepartmentId(), $agNew->GetComunicators(), $agNew->GetPhotoFile(), $agNew->GetAgentsCode(), $agNew->GetSection(), (int) $agNew->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Add or edit if exists, given agent object.
	 * @param Agent $ag
	 */
	public static function AddEditAgent(Agent $ag){
		$a = self::GetAgent($ag->GetId());
		if($a == null){
			self::AddAgent($ag);
		}else{
			self::EditAgent($ag);
		}
	}
    
    /**
	 * Delete agent from database, given by ID.
	 * @param int $id
	 */
	public static function DeleteAgent($id=0){
        $params = array();
        $query = "DELETE FROM #S#agents WHERE 1=1 ";
        if($id > 0){
            $query .= " AND id=?";
            $params[] = $id;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
    
    /**
	 * Returns a list of agents, take into account the filtering and sorting.
	 * @param RefreshEventArgs $args
	 * @return Agent[]
	 */
	public static function GetAgents(RefreshEventArgs $args){
		$db = DataBase::GetDbInstance();
        $filters = self::PrepareFilters($args->Filters);
		$query = self::GetQuery("SELECT COUNT(*)", "", $args->Filters);
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		$row = $db->FetchArray($result);
		$args->SetRowsCount($row[0]);
		
		$query = "SELECT a.id, a.name, a.phone, a.cell, a.email, a.departments_id, a.jabber_login, a.licence_no, a.responsible_name, a.responsible_licence_no, a.comunicators, a.photo_file, a.agents_code, a.section ";
		$query = self::GetQuery($query, $args->Sorting, $args->Filters);
		$args->SetLimit($query);
		$list = array();
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		while($row = $db->FetchArray($result)){
			$list[count($list)] = self::BuildAgent($row);
		}
		return $list;
	}
	
}

?>