<?php

/**
 * Class provides methods for managing rooms of offer.
 * @author Jakub Konieczka
 *
 */
class OfferRooms{
	
	/**
	 * Creates an offer room object on the basis of data from the database.
	 * @param array $row
	 * @return OfferRoom
	 */
	protected static function BuildRoom($row){
		$room = new OfferRoom($row['id'],$row['offers_id'],$row['offers_id_lng'],$row['kind'],$row['order'],$row['area'],$row['level'],$row['type'],
			$row['height'],$row['kitchen_type'],$row['number'],$row['glaze'],$row['window_view'],$row['description'],$row['floors_state'],$row['room_type']);
			
		//additional sets of properties
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT name, value FROM #S#offers_rooms_sets WHERE offers_rooms_id=?", array((int) $row['id']));
		$walls = array();
		$floors = array();
		$windowsExhibition = array();
		$equipment = array();
		while($row2 = DataBase::GetDbInstance()->FetchArray($result)){
			if($row2['name'] == 'Podlogi') $floors[count($floors)] = $row2['value'];
			else if($row2['name'] == 'Sciany') $walls[count($walls)] = $row2['value'];
			else if($row2['name'] == 'Wyposazenie') $equipment[count($equipment)] = $row2['value'];
			else if($row2['name'] == 'WystawaOkien') $windowsExhibition[count($windowsExhibition)] = $row2['value'];
		}
		$room->SetWalls($walls);
		$room->SetFloors($floors);
		$room->SetWindowsExhibition($windowsExhibition);
		$room->SetEquipment($equipment);
		
		return $room;
	}

    /**
	 * Save all values from $set as a collection of room. 
	 * @param array $set
	 * @param string $name
	 * @param int $roomId
	 */
	protected static function SaveSets($set, $name, $roomId){
		foreach($set as $key => $value){
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("INSERT INTO #S#offers_rooms_sets(offers_rooms_id, name, value) VALUES(?, ?, ?)", 
				array($roomId, $name, $value));
		}
	}
    
	/**
	 * Returns an offer room object from the database by ID.
	 * @param int $id
	 * @return OfferRoom
	 */
	public static function GetRoom($id){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_rooms WHERE id=?", array((int) $id));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildRoom($row);
			else return null;
		}else return null;
	}
	
	/**
	 * Add given offer room object to database.
	 * @param OfferRoom $room
	 */
	public static function AddRoom(OfferRoom $room){
		$query = "INSERT INTO #S#offers_rooms (offers_id, kind, `order`, area, level, `type`, height, kitchen_type, number, glaze, window_view
			, description, floors_state, room_type, offers_id_lng) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($room->GetOfferId(), $room->GetKind(), $room->GetOrder(), $room->GetArea(), $room->GetLevel(), $room->GetType(), $room->GetHeight(), 
			$room->GetKitchenType(), $room->GetNumber(), $room->GetGlaze(), $room->GetWindowView(), $room->GetDescription(), $room->GetFloorsState(), $room->GetRoomType(), $room->GetOfferLng());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		//echo $result;
		$room->SetId(DataBase::GetDbInstance()->LastInsertedId("offers_rooms"));
		self::SaveSets($room->GetFloors(), "Podlogi", $room->GetId());
		self::SaveSets($room->GetWalls(), "Sciany", $room->GetId());
		self::SaveSets($room->GetEquipment(), "Wyposazenie", $room->GetId());
		self::SaveSets($room->GetWindowsExhibition(), "WystawaOkien", $room->GetId());
	}
	
	/**
	 * Save given offer room object (roomNew) to database.
	 * @param OfferRoom $roomOld
	 * @param OfferRoom $roomNew
	 */
	public static function EditRoom(OfferRoom $roomOld, OfferRoom $roomNew){
		$query = "UPDATE #S#offers_rooms SET offers_id=?, kind=?, `order`=?, area=?, level=?, `type`=?, height=?, kitchen_type=?, number=?,
			glaze=?, window_view=?, description=?, floors_state=?, room_type=?, offers_id_lng=? WHERE id=?;";
		$params = array($roomNew->GetOfferId(), $roomNew->GetKind(), $roomNew->GetOrder(), $roomNew->GetArea(), $roomNew->GetLevel(), $roomNew->GetType(), $roomNew->GetHeight(), 
        $roomNew->GetKitchenType(), $roomNew->GetNumber(), $roomNew->GetGlaze(), $roomNew->GetWindowView(), $roomNew->GetDescription(), $roomNew->GetFloorsState(), $roomNew->GetRoomType(),
        $room->GetOfferLng(), (int) $roomOld->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

	/**
	 * Delete all rooms for given offer.
	 * @param int $offerId
     * @param int $offerLng
	 */
	public static function DeleteRooms($offerId, $offerLng){
        if($offerLng == null){
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE s FROM #S#offers_rooms_sets s INNER JOIN #S#offers_rooms r ON s.offers_rooms_id=r.id WHERE r.offers_id=?", array((int) $offerId));
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_rooms WHERE offers_id=?", array((int) $offerId));
        }else{
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE s FROM #S#offers_rooms_sets s INNER JOIN #S#offers_rooms r ON s.offers_rooms_id=r.id WHERE r.offers_id=? AND r.offers_id_lng=?", array((int) $offerId, (int) $offerLng));
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_rooms WHERE offers_id=? AND offers_id_lng=?", array((int) $offerId, (int) $offerLng));
        }
	}    
	
	/**
	 * Return an array of rooms for given offer.
	 * @param $offerId
     * @param $offerLng
	 * @return OfferRoom[]
	 */
	public static function GetRooms($offerId, $offerLng){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_rooms WHERE offers_id=? AND offers_id_lng=? ORDER BY `order` ASC", array((int) $offerId, (int) $offerLng));
		$rooms = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$rooms[$ndx] = self::BuildRoom($row);
			$ndx++;
		}
		return $rooms;
	}
		
}

?>