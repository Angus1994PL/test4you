<?php

/**
 * Class describing lists 
 */

class Lists {
	
	public static function getList($enum_id){
		$db = DataBase::GetDbInstance();
		$query = "SELECT * FROM listy WHERE enum_id = ?";
		$params = array((int) $enum_id);
		
		$return = array();
		$result = $db->ExecuteQueryWithParams($query, $params);
		while($row = $db->FetchArray($result)){
			$return[] = $row['value'];
		}
			
		return $return;
	}
	
}