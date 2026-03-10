<?php

/**
 * Class provides methods for managing the investments.
 * @author Jakub Konieczka
 *
 */
class Investments{
	
	/**
	 * Creates a investment object on the basis of data from the database.
	 * @param array $row
	 * @return Investment
	 */
	protected static function BuildInvestment($row){
		$inv = new Investment($row['id_lng'],$row['id'],$row['no'],$row['number'],$row['name'],$row['description'],$row['short_description'],$row['contact'],
			$row['map_marker'],$row['garage'],$row['pool'],$row['terrace'],$row['air_conditioning'],$row['house_project'],$row['special'],
			$row['creation_date'],$row['due_date'],$row['total_area'],$row['gross_volume'],$row['area_from'],$row['area_to'],$row['price_from'],
			$row['price_to'],$row['pricem2_from'],$row['pricem2_to'],$row['floor_from'],$row['floor_to'],$row['rooms_no_from'],$row['rooms_no_to'],$row['country'],
			$row['province'],$row['district'],$row['location'],$row['quarter'],$row['region'],$row['street'],$row['category'],$row['departments_id']);
		//buildings
		$inv->SetBuildings(InvestmentBuildings::GetInvestmentBuildings($row['id'], $row['id_lng']));
		return $inv;
	}
    
    /**
	 * Save all investment dynamic properties values to database.
	 * @param Investment $inv
     * @param bool $isNew
	 */
	protected static function SaveProperties(Investment $inv, $isNew){
		$props = new Properties();
        $lst = array();
        if(!$isNew){
            $query = "SELECT id, properties_id, value FROM #S#investments_properties WHERE investments_id=? AND investments_id_lng=?";
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array((int) $inv->GetId(), (int) $inv->GetIdLng()));
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                $lst[$row['properties_id']][] = array("id" => $row['id'], "value" => $row['value']);
            }
        }
		foreach($inv->data as $key => $value){
			$prop = Properties::GetWithAddProperty($key);
			Properties::SavePropertyValueForInv($prop, $inv, $value, $isNew, $lst);
		}
	}
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null){
		if($filters == null) return $filters;
        $tab_with_numeric_value= array("id", "IdLng", "no", "department", );
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
				case "categories":
				case "districts": 
				case "locations": 
                case "quarters": array_pop($newFilters); $newFilters = array_merge($newFilters, self::prepareStringToArray($value)); break;
                case "departments": array_pop($newFilters); $newFilters = array_merge($newFilters, self::prepareStringToArray($value, true)); break;
				case "specjalne": array_pop($newFilters); break;
                //Doubles params in query
                case "area":
                case "price":
                case "pricem2":
                case "floor":
                case "rooms":array_push($newFilters,$newFilters[$key]);break;
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
	protected static function prepareStringToArray($value, $ints = false){		
        if(is_array($value)) $arr=$value;
		else $arr = explode(',', $value);
		$newArr = array();
		foreach($arr as $value){
            if($ints) $newArr[] = (int) trim($value, "' ");
			else $newArr[] = trim($value, "' ");
		}
		return $newArr;
	}
    
    
	/**
	* Returns cleared value for SQL query.
	* @param String $value
	* @param bool $remove
	* @return $value
	*/
	protected static function prepareSort($value){
		$orderbyArray = array('IdLng', 'no', 'number', 'name', 'description', 'shortDescription', 'contact',  'country', 'province', 'district','location', 'quarter', 'region', 'street', 'category', 'creationDateFrom', 'creationDateTo', 'dueDateFrom', 'dueDateTo', 'totalAreaFrom', 'totalAreaTo', 'grossVolumeFrom', 'grossVolumeTo', 'area_from', 'area_to', 'price_from', 'price_to', 'pricem2', 'floor', 'rooms', 'districts', 'locations', 'quarters', 'categories', 'specjalne', 'department', 'departments', 'creation_date');
		$destArray = array('asc', 'desc');
	
		$exp = explode(' ', $value);
		$orderby = 'id';
		$dest = 'desc';
	
		if(isset($exp[0]) && in_array($exp[0], $orderbyArray)) $orderby = $exp[0];
		if(isset($exp[1]) && in_array($exp[1], $destArray)) $dest = $exp[1];
	
		return $orderby.' '.$dest;
	}
    
    /**
     * Prepares properties to GetQuery
     * @param array $value
     * @return string
     */
	protected static function preparePropertiesToQuery($value){
		$i = 1;
		$query = '';
		foreach($value as $propValues){
			$query .= " AND ip$i.value IN(".self::prepareStringToBind($propValues).")";
			$i++;
		}
		return $query;
	}
    
    /**
     * Creates query string from given params
     * @param string $select
     * @param string $sorting
     * @param mixed $filters
     * @return string
     */
    protected static function GetQuery($select = "SELECT * ", $sorting = "", $filters = null){
		$query = $select . " FROM #S#investments AS i ";        
        
        if(array_key_exists('properties', $filters)){
			$i = 1;
			foreach($filters['properties'] as $name=>$values){
				$query .= " INNER JOIN #S#investments_properties AS ip$i ON (i.id = ip$i.investments_id AND i.id_lng = ip$i.investments_id_lng) INNER JOIN #S#properties AS p$i ON (p$i.id = ip$i.properties_id AND p$i.id = ".OffersHelper::getProps($name).")";
				$i++;
			}	
		}
        
		$query .= " WHERE 1=1 ";
		if($filters != null){
			foreach ($filters as $key => $value){
				switch ($key) {
                    case "IdLng": $query .= " AND i.id_lng=?"; break;
					case "no": $query .= " AND i.no LIKE ?"; break;	
					case "number": $query .= " AND i.number LIKE ?"; break;
					case "name": $query .= " AND i.name LIKE ?"; break;
					case "description": $query .= " AND i.description LIKE ?"; break;
					case "shortDescription": $query .= " AND i.short_description LIKE ?"; break;
					case "contact": $query .= " AND i.contact LIKE ?"; break;
					case "country": $query .= " AND i.country LIKE ?"; break;
					case "province": $query .= " AND i.province LIKE ?"; break;
					case "district": $query .= " AND i.district LIKE ?"; break;
					case "location": $query .= " AND i.location LIKE ?"; break;
					case "quarter": $query .= " AND i.quarter LIKE ?"; break;
					case "region": $query .= " AND i.region LIKE ?"; break;
					case "street": $query .= " AND i.street LIKE ?"; break;
					case "category": $query .= " AND i.category LIKE ?"; break;
					case "creationDateFrom": $query .= " AND i.creation_date >= ?"; break;
					case "creationDateTo": $query .= " AND i.creation_date <= ?"; break;
					case "dueDateFrom": $query .= " AND i.due_date >= ?"; break;
					case "dueDateTo": $query .= " AND i.due_date <= ?"; break;
					case "totalAreaFrom": $query .= " AND i.total_area >= ?"; break;
					case "totalAreaTo": $query .= " AND i.total_area <= ?"; break;
					case "grossVolumeFrom": $query .= " AND i.gross_volume >= ?"; break;
					case "grossVolumeTo": $query .= " AND i.gross_volume <= ?"; break;					
					case "area": $query .= " AND i.area_from <= ?" . " AND i.area_to >= ?"; break;
					case "areaFrom": $query .= " AND i.area_from >= ?"; break;
					case "areaTo": $query .= " AND i.area_to <= ?"; break;
					case "price": $query .= " AND i.price_from <= ?" . " AND i.price_to >= ?"; break;
					case "priceFrom": $query .= " AND i.price_from >= ?"; break;
					case "priceTo": $query .= " AND i.price_to <= ?"; break;
					case "pricem2": $query .= " AND i.pricem2_from <= ?" . " AND i.pricem2_to >= ?"; break;
					case "floor": $query .= " AND i.floor_from <= ?" . " AND i.floor_to >= ?"; break;
					case "rooms": $query .= " AND i.rooms_no_from <= ?" . " AND i.rooms_no_to >= ?"; break;
					case "roomsNoFrom": $query .= " AND i.rooms_no_from >= ?"; break;
					case "roomsNoTo": $query .= " AND i.rooms_no_to <= ?"; break;
					case "districts": $query .= " AND i.district IN (" . self::prepareStringToBind($value) . ")"; break;
					case "locations": $query .= " AND i.location IN (" . self::prepareStringToBind($value) . ")"; break;
					case "quarters": $query .= " AND i.quarter IN (" . self::prepareStringToBind($value) . ")"; break;
					case "categories": $query .= " AND i.category IN (" . self::prepareStringToBind($value) . ")"; break;
					case "specjalne": $query .= " AND i.special=1";break;
					case "department": $query .=" AND i.departments_id = ?";break;
					case "departments": $query .=" AND i.departments_id IN (". self::prepareStringToBind($value) .")"; break;
                    case "properties": $query .= self::preparePropertiesToQuery($value); break;
					default: $query .= " AND i.$key=?"; break;					
				}
			}	
		}
		if($sorting != ""){
			$query .= " ORDER BY i.".self::prepareSort($sorting);
		}
		return $query;
	}

	/**
	 * Returns a investment object from the database by ID.
	 * @param int $id
     * @param int $idLng
	 * @return Investment
	 */
	public static function GetInvestment($id, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#investments WHERE id=? AND id_lng=?", array((int) $id, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildInvestment($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given investment object to database.
	 * @param Investment $inv
	 */
	public static function AddInvestment(Investment $inv){
		$query = "INSERT INTO #S#investments (id, no, number, name, description, short_description, contact, map_marker, garage, pool, terrace, air_conditioning, house_project,
			special, creation_date, due_date, total_area, gross_volume, area_from, area_to, price_from, price_to, pricem2_from, pricem2_to, floor_from, floor_to, rooms_no_from, rooms_no_to, country,
			province, district, location, quarter, region, street, category, departments_id, id_lng)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($inv->GetId(), $inv->GetNo(), $inv->GetNumber(), $inv->GetName(), $inv->GetDescription(), $inv->GetShortDescription(), $inv->GetContact(), 
			$inv->GetMapMarker(), $inv->GetGarage(), $inv->GetPool(), $inv->GetTerrace(), $inv->GetAirConditioning(), $inv->GetHouseProject(), $inv->GetSpecial(), 
			$inv->GetCreationDate(), $inv->GetDueDate(), $inv->GetTotalArea(), $inv->GetGrossVolume(), $inv->GetAreaFrom(), $inv->GetAreaTo(), 
			$inv->GetPriceFrom(), $inv->GetPriceTo(), $inv->GetPricem2From(), $inv->GetPricem2To(), $inv->GetFloorFrom(), $inv->GetFloorTo(), $inv->GetRoomsNoFrom(), 
			$inv->GetRoomsNoTo(), $inv->GetCountry(), $inv->GetProvince(), $inv->GetDistrict(), $inv->GetLocation(), $inv->GetQuarter(), $inv->GetRegion(), 
			$inv->GetStreet(), $inv->GetCategory(), $inv->GetDepartmentId(), $inv->GetIdLng());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        self::SaveProperties($inv, true);
	}
    
    /**
     * Delete relations for investments agents by given invenstemns id
     * @param int $inv_id 
     */
    public static function DelInvestmentsAgents($inv_id){
        $query = "DELETE FROM #S#investments_agents WHERE investments_id=?";
        $params = array((int) $inv_id);
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
    }
    
    /**
	 * Add a relation for given investment and agent objects.
	 * @param Investment $inv
     * @param Agent $agent
	 */
    public static function AddInvestmentsAgent(Investment $inv, Agent $agent){
        $query = "INSERT IGNORE INTO #S#investments_agents (investments_id, agents_id) VALUES(?,?)";
        $params = array($inv->GetId(), $agent->GetId());
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
    }
    
    /**
     * Returns a list of investments for given agent.
     * @param Agent $agent
     * @return Investment[]
     */
    public static function GetAgentsInvestments(Agent $agent){
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT inv.* FROM #S#investments inv INNER JOIN #S#investments_agents ia ON ia.investments_id=inv.id WHERE ia.agents_id=?", array($agent->GetId()));
		if($result){
            $list = array();
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                $list[count($list)] = self::BuildInvestment($row);
            }
		}
        return $list;
    }
    
    /**
     * Returns a list of agents for given investment.
     * @param Investment $inv
     * @return Agent[]
     */
    public static function GetInvestmentsAgents(Investment $inv){
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT a.id FROM #S#agents a INNER JOIN #S#investments_agents ia ON ia.agents_id=a.id WHERE ia.investments_id=?", array($inv->GetId()));
		if($result){
            $list = array();
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                $list[count($list)] = Agents::GetAgent($row[0]);
            }
		}
        return $list;
    }
	
	/**
	 * Save given investment object (invNew) to database.
	 * @param Investment $invNew
	 */
	public static function EditInvestment(Investment $invNew){
		$query = "UPDATE #S#investments SET no=?, number=?, name=?, description=?, short_description=?, contact=?, map_marker=?, garage=?, pool=?,
			terrace=?, air_conditioning=?, house_project=?, special=?, creation_date=?, due_date=?, total_area=?, gross_volume=?, area_from=?, area_to=?,
			price_from=?, price_to=?, pricem2_from=?, pricem2_to=?, floor_from=?, floor_to=?, rooms_no_from=?, rooms_no_to=?, country=?, province=?, district=?,
			location=?, quarter=?, region=?, street=?, category=?, departments_id=?, id_lng=?  WHERE id=? AND id_lng=?;";
		$params = array($invNew->GetNo(), $invNew->GetNumber(), $invNew->GetName(), $invNew->GetDescription(), $invNew->GetShortDescription(), $invNew->GetContact(),
			$invNew->GetMapMarker(), $invNew->GetGarage(), $invNew->GetPool(), $invNew->GetTerrace(), $invNew->GetAirConditioning(), $invNew->GetHouseProject(), $invNew->GetSpecial(), 
			$invNew->GetCreationDate(), $invNew->GetDueDate(), $invNew->GetTotalArea(), $invNew->GetGrossVolume(), $invNew->GetAreaFrom(), $invNew->GetAreaTo(), 
			$invNew->GetPriceFrom(), $invNew->GetPriceTo(), $invNew->GetPricem2From(), $invNew->GetPricem2To(), $invNew->GetFloorFrom(), $invNew->GetFloorTo(), $invNew->GetRoomsNoFrom(), 
			$invNew->GetRoomsNoTo(), $invNew->GetCountry(), $invNew->GetProvince(), $invNew->GetDistrict(), $invNew->GetLocation(), $invNew->GetQuarter(), $invNew->GetRegion(), 
			$invNew->GetStreet(), $invNew->GetCategory(), $invNew->GetDepartmentId(), $invNew->GetIdLng(), (int) $invNew->GetId(), (int) $invNew->GetIdLng());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
        self::SaveProperties($invNew, false);
	}
    
    /**
	 * Delete all unused investment properties values, that are no longer published.
	 * @param int $investmentId
	 * @param int $investmentLng
	 * @param int[] $propsIds
	 */
	public static function DeleteUnUseProperties($investmentId, $investmentLng, $propsIds){
		$params = array((int) $investmentId, (int) $investmentLng);
		
		if(count($propsIds) > 0){
			$inBind = implode(',', array_fill(0, count($propsIds), '?'));			
			$params = array_merge($params, $propsIds);
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments_properties WHERE investments_id=? AND investments_id_lng=? AND properties_id NOT IN ($inBind)", $params);
		}else{
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments_properties WHERE investments_id=? AND investments_id_lng=?", $params);
        }
	}
		
	/**
	 * Add or edit if exists, given investment object.
	 * @param Investment $inv
	 */
	public static function AddEditInvestment(Investment $inv){
		$i = self::GetInvestment($inv->GetId(), $inv->GetIdLng());
		if($i == null){
			self::AddInvestment($inv);
		}else{
			self::EditInvestment($inv);
		}
	}
	
	/**
	 * Delete investment from database, given by ID.
	 * @param int $id
	 */
	public static function DeleteInvestment($id){
		$investment = self::GetInvestment($id);
		if($investment != null){
			OfferPhotos::DeletePhotosInvestment($id);
			InvestmentBuildings::DeleteInvestmentBuildings($id);
                        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments_properties WHERE investments_id=?", array((int) $id));
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments WHERE id=?", array((int) $investment->GetId()));
		}	
	}
	
	/**
	 * Returns a list of investments in given language, take into account the filtering and sorting.
	 * @param RefreshEventArgs $args
     * @param int $idLng
	 * @return Investment[]
	 */
	public static function GetInvestments(RefreshEventArgs $args, $idLng){
		$db = DataBase::GetDbInstance();
                $args->Filters["IdLng"] = (int) $idLng;
		$filters = self::PrepareFilters($args->Filters);
		$query = self::GetQuery("SELECT COUNT(*)", "", $args->Filters);
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		$row = $db->FetchArray($result);
		$args->SetRowsCount($row[0]);	
		
		$query = "SELECT *  ";
		$query = self::GetQuery($query, $args->Sorting, $args->Filters);
		$args->SetLimit($query);
		$list = array();
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		while($row = $db->FetchArray($result)){
			$list[count($list)] = self::BuildInvestment($row);
		}
		return $list;
	}
    
    
    /**
	 * Returns a list of investments, take into account the filtering and sorting.
	 * @param Array $filters
     * @param String $sort
     * @param Integer $idLng
     * @return Investment[]
	 */
	public static function GetInvestmentsAlter($filters,$sort="", $idLng=1045){
		$db = DataBase::GetDbInstance();
        $filters["IdLng"] = (int) $idLng;
		$filters_new = self::PrepareFilters($filters);
		
		$query = self::GetQuery("SELECT *  ", $sort, $filters);
		$list = array();
		$result = $db->ExecuteQueryWithParams($query, array_values($filters_new));
		while($row = $db->FetchArray($result)){
			$list[count($list)] = self::BuildInvestment($row);
		}
		return $list;
	}
    
    	
	/**
	 * Returns a list of invest's ids, take into account the filtering and sorting.
	 * @param array $filters
	 * @param string $sort
	 * @return Investment[]
	 */
	public static function GetInvestmentId(RefreshEventArgs $args1, $sort=""){
		$db = DataBase::GetDbInstance();
		$filters = self::PrepareFilters($args1->Filters);
		$query = self::GetQuery("SELECT i.id ", $sort, $args1->Filters);
		$list = array();
		$result = $db->ExecuteQueryWithParams($query, array_values($filters));
		while($row = $db->FetchArray($result)){
            $list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
	 * Returns a unique list of countries
	 * @param int $idLng
     * @param bool $rent
     * @param mixed $object
	 * @return string[]
	 */
	public static function GetCountries($idLng = 1045){
		
		if(!($list = OffersHelper::getMethodResultCache())) {
		
			$db = DataBase::GetDbInstance();
			$params = array((int) $idLng);
			$query = "SELECT DISTINCT(country) FROM #S#investments WHERE country IS NOT NULL AND id_lng=?";
			$query .= " ORDER BY country ASC";
		
			$result = $db->ExecuteQueryWithParams($query, $params);
			$list = array();
			while($row = $db->FetchArray($result)){
				$list[count($list)] = $row[0];
			}
			
			OffersHelper::setMethodResultCache($list);
			
		}
		
		return $list;
	}
	
	/**
	 * Returns a unique list of provinces used in investments.
	 * @return string[]
	 */
	public static function GetProvinces($idLng = 1045){
		$db = DataBase::GetDbInstance();
		$query = "SELECT DISTINCT(i.province) FROM #S#investments i WHERE i.province IS NOT NULL AND i.id_lng = ? ORDER BY i.province ASC";
		$result = $db->ExecuteQueryWithParams($query, array((int) $idLng));
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
	 * Returns a unique list of provinces selected by country used id offers.
     * @param string $country
	 * @param int $idLng
	 * @return string[]
	 */
	public static function GetProvincesByCountry($country, $idLng = 1045){
		
		if(!($list = OffersHelper::getMethodResultCache())) {
			
			$db = DataBase::GetDbInstance();
			$params = array($country, (int) $idLng);
			$query = "SELECT DISTINCT(province) FROM #S#investments as i  WHERE i.province IS NOT NULL AND i.country LIKE ? AND id_lng=? ORDER BY province ASC";
			$result = $db->ExecuteQueryWithParams($query, $params);
			$list = array();
			while($row = $db->FetchArray($result)){
				$list[count($list)] = $row[0];
			}
			
			OffersHelper::setMethodResultCache($list);
		}
		return $list;
	}
	
	/**
	 * Returns a unique list of districts used id investments.
	 * @param mixed $province
	 * @return string[]
	 */
	public static function GetDistricts($province = null, $idLng = 1045){
		$db = DataBase::GetDbInstance();
		$query = "SELECT DISTINCT(i.district) FROM #S#investments i WHERE i.district IS NOT NULL ";
		if($province != null) $query .= " AND i.province=?";
        $query .= " AND i.id_lng = ?";
		$query .= " ORDER BY district ASC";
		if($province != null)
			$result = $db->ExecuteQueryWithParams($query, array($province,(int) $idLng));
		else
			$result = $db->ExecuteQuery($query);
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
	 * Returns a unique list of locations used id investments.
	 * @param mixed $districts
	 * @return string[]
	 */
	public static function GetLocations($districts = null, $idLng = 1045){
		$db = DataBase::GetDbInstance();
		
		$query = "SELECT DISTINCT(i.location) FROM #S#investments i WHERE i.location IS NOT NULL ";
		$params = array();
		
		if($districts != null) {
			$inBind = implode(',', array_fill(0, count($districts), '?'));
			$query .= " AND i.district IN (".$inBind.") ";
			$params = $districts;		
		}
        
        $query .= " AND i.id_lng = ? ";
        $params[] = (int) $idLng;
        
		$query .= " ORDER BY i.location ASC";
		$result = $db->ExecuteQueryWithParams($query, $params);
		
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
	 * Returns a unique list of quarters used in investments.
	 * @param mixed $locations
	 * @return string[]
	 */
	public static function GetQuarters($locations = null, $idLng = 1045){
		$db = DataBase::GetDbInstance();
		
		$query = "SELECT DISTINCT(i.quarter) FROM #S#investments i WHERE i.quarter IS NOT NULL ";
		$params = array();
		
		if($locations != null) {
			$inBind = implode(',', array_fill(0, count($locations), '?'));
			$query .= " AND i.location IN (".$inBind.") ";
			$params = $locations;			
		}
        
        $query .= " AND i.id_lng = ? ";
        $params[] = (int) $idLng;
        
		$query .= " ORDER BY i.quarter ASC";
		$result = $db->ExecuteQueryWithParams($query, $params);
		
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
	 * Returns a unique list of regions used in investments.
	 * @param mixed $quarters
	 * @return string[]
	 */
	public static function GetRegions($quarters = null, $idLng = 1045){
		$db = DataBase::GetDbInstance();
		
		$query = "SELECT DISTINCT(i.region) FROM #S#investments i WHERE i.region IS NOT NULL ";
		$params = array();
		
		if($quarters != null) {
			$inBind = implode(',', array_fill(0, count($quarters), '?'));
			$query .= " AND i.quarter IN (".$inBind.") ";
			$params = $quarters;			
		}
        
		$query .= " AND i.id_lng = ? ";
        $params[] = (int) $idLng;
        
		$query .= " ORDER BY i.region ASC";
		$result = $db->ExecuteQueryWithParams($query, $params);
		
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
     * Returns an array with offers minimum and maximum field area in given language.
     * @param RefreshEventArgs $args
     * @param int $idLng
     * @return array(min, max)
     */
    public static function getDistrictsAndLocationsAndQuartersAndRegionsAndStreets($districts = null, $province = null, $idLng = 1045)
    {

        if (!($list = OffersHelper::getMethodResultCache())) {

            $db = DataBase::GetDbInstance();	

            $query = "SELECT DISTINCT i.district, i.location, i.quarter, i.region, i.street FROM #S#investments i WHERE i.location IS NOT NULL AND i.id_lng=? ";

            if ($province != null)
                $query .= " AND i.province=? ";

            $i = 1;
            if ($province)
                $filters = array((int) $idLng, $province);
            else
                $filters = array((int) $idLng);

            if ($districts != null) {
                $inBind = implode(',', array_fill(0, count($districts), '?'));
                $query .= " AND i.district IN (" . $inBind . ") ";
                $filters = array_merge($filters, $districts);
            }
			
            $query .= " ORDER BY i.location ASC";

            if ($filters != null)
                $result = $db->ExecuteQueryWithParams($query, $filters);

            $list = array();
            $i = 0;

            while ($row = $db->FetchArray($result)) {
                $list[$row[0]][$row[1]][$row[2]][$row[3]][$row[4]] = 1;
            }

            OffersHelper::setMethodResultCache($list);
        }

        return $list;
    }
	
	/**
	 * Returns a unique list of categories used in investments.
	 * @return string[]
	 */
	public static function GetCategories($idLng = 1045){
		$db = DataBase::GetDbInstance();
		$query = "SELECT DISTINCT(i.category) FROM #S#investments i WHERE i.category IS NOT NULL AND i.category <> '' AND i.id_lng = ? ORDER BY i.category ASC";
		$result = $db->ExecuteQueryWithParams($query, array((int) $idLng));
		$list = array();
		while($row = $db->FetchArray($result)){
			$list[count($list)] = $row[0];
		}
		return $list;
	}
	
	/**
	 * Returns a offers count in given investment.
	 * @param int $investmentId
     * @param int $lngId
	 * @return int
	 */
	public static function GetOffersCount($investmentId, $lngId = 1045){
		$db = DataBase::GetDbInstance();
		$params = array((int) $investmentId, (int) $lngId);
		$query = "SELECT COUNT(*) FROM #S#offers o INNER JOIN #S#investments_buildings b ON o.investments_buildings_id=b.id WHERE b.investments_id=? AND o.id_lng=?";
		$result = $db->ExecuteQueryWithParams($query, $params);
		$row = $db->FetchArray($result);
		return $row[0];
	}
	
	/**
	 * Returns array of offers investment.
	 * @param int $investmentId
	 * @param int $lngId
	 * @return array
	 */
	public static function GetOffers($investmentId, $lngId = 1045){
		$db = DataBase::GetDbInstance();
		$params = array((int) $investmentId, (int) $lngId);
		$query = "SELECT o.id FROM #S#offers o INNER JOIN #S#investments_buildings b ON o.investments_buildings_id=b.id WHERE b.investments_id=? AND o.id_lng=?";
		$result = $db->ExecuteQueryWithParams($query, $params);
		
		$offers = array();
		
		while($row = $db->FetchArray($result)) {
			
			$offers[] = Offers::getOffer($row[0], $lngId);
			
		}
		
		return $offers;
		
	}
	
}

?>