<?php

/**
 * Class describing lists 
 */

class LocationsAll {
	
    /**
     * Returns districts for given province id
     * @param mixed $wojewodztwoId
     * @return stdClass[]
     */
	public static function getDistricts($wojewodztwoId = null){
	
		$query = "SELECT * FROM powiaty WHERE 1=1";
		$params = array();
	
		if($wojewodztwoId !== null){
			$query .= " AND wojewodztwo_id = ?";
			$params[] = (int) $wojewodztwoId;
		}
	
	
		$db = DataBase::GetDbInstance();
		$result = $db->ExecuteQueryWithParams($query, $params);
	
		$return = array();
	
		while($row = $db->FetchArray($result)){
			$district = new stdClass();
			$district->id = $row['id'];
			$district->Nazwa = $row['nazwa'];
			$district->wojewodztwoId = $row['wojewodztwo_id'];
			$return[] = $district;
		}
		return $return;
	}
	
    /**
     * Returns locations for given province id, district id
     * @param mixed $wojewodztwoId
     * @param mixed $powiatId
     * @param mixed $gmina
     * @return stdClass[]
     */
	public static function getLocations($wojewodztwoId = null, $powiatId = null, $gmina = null){
		
		$query = "SELECT * FROM lokalizacje WHERE 1=1";
		$params = array();
		
		if($wojewodztwoId !== null){
			$query .= " AND wojewodztwo_id = ?";
			$params[] = (int) $wojewodztwoId;
		}
		
		if($powiatId !== null){
			$query .= " AND powiat_id = ?";
			$params[] = (int) $powiatId;
		}
		
		if($gmina !== null){
			$query .= " AND gmina = ?";
			$params[] = (int) $gmina;
		}
		
		$db = DataBase::GetDbInstance();
		$result = $db->ExecuteQueryWithParams($query, $params);
		
		$return = array();

		while($row = $db->FetchArray($result)){
			$location = new stdClass();
			$location->id = $row['id'];
			$location->Nazwa = $row['nazwa'];
			$location->powiatId = $row['powiat_id'];
			$location->wojewodztwoId = $row['wojewodztwo_id'];
			$location->gmina = $row['gmina'];
			$return[] = $location;
		}
		return $return;		
	}
	
    /**
     * Returns quarters for given location id
     * @param mixed $lokalizacjaId
     * @return stdClass[]
     */
	public static function getQuarters($lokalizacjaId = null){
	
		$query = "SELECT * FROM dzielnice WHERE 1=1";
		$params = array();
	
		if($lokalizacjaId !== null){
			$query .= " AND lokalizacja_id = ?";
			$params[] = (int) $lokalizacjaId;
		}	
		
	
		$db = DataBase::GetDbInstance();
		$result = $db->ExecuteQueryWithParams($query, $params);
	
		$return = array();
	
		while($row = $db->FetchArray($result)){
			$quarter = new stdClass();
			$quarter->id = $row['id'];
			$quarter->Nazwa = $row['nazwa'];
			$quarter->lokalizacjaId = $row['lokalizacja_id'];	
			$return[] = $quarter;
		}
		return $return;
	}
	
    /**
     * Returns regions for given quarter id
     * @param mixed $dzielnicaId
     * @return stdClass[]
     */
	public static function getRegions($dzielnicaId = null){
	
		$query = "SELECT * FROM rejony WHERE 1=1";
		$params = array();
	
		if($dzielnicaId !== null){
			$query .= " AND dzielnica_id = ?";
			$params[] = (int) $dzielnicaId;
		}
	
		$db = DataBase::GetDbInstance();
		$result = $db->ExecuteQueryWithParams($query, $params);
	
		$return = array();
	
		while($row = $db->FetchArray($result)){
			$region = new stdClass();
			$region->id = $row['id'];
			$region->Nazwa = $row['nazwa'];
			$region->dzielnicaId = $row['dzielnica_id'];
			$return[] = $region;
		}
	
		return $return;
	}
	
}