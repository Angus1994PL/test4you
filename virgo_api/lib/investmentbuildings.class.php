<?php

/**
 * Class provides methods for managing pictures of investments buldings.
 * @author Jakub Konieczka
 *
 */
class InvestmentBuildings{

	/**
	 * Creates a investment bulding object on the basis of data from the database.
	 * @param array $row
	 * @return InvestmentBuilding
	 */
	protected static function BuildInvestmentBuilding($row){
		$bulding = new InvestmentBuilding($row['id_lng'],$row['id'],$row['name'],$row['symbol'],$row['description'],$row['investments_id'],$row['area'],$row['due_date'],$row['floors_no']);
		return $bulding;
	}

	/**
	 * Returns a investment bulding object from the database by ID.
	 * @param int $id
     * @param int $idLng
	 * @return InvestmentBuilding
	 */
	public static function GetInvestmentBuilding($id, $idLng = 1045){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#investments_buildings WHERE id=? AND id_lng=?", array((int) $id, (int) $idLng));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildInvestmentBuilding($row);
			else return null;
		}else return null;
	}

	/**
	 * Add given investment bulding object to database.
	 * @param InvestmentBuilding $bulding
	 */
	public static function AddInvestmentBuilding(InvestmentBuilding $bulding){
		$query = "INSERT INTO #S#investments_buildings (id, id_lng, investments_id, symbol, name, description, area, due_date, floors_no) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array($bulding->GetId(), $bulding->GetIdLng(), $bulding->GetInvestmentId(), $bulding->GetSymbol(), $bulding->GetName(), $bulding->GetDescription(), $bulding->GetArea(), $bulding->GetDueDate(), $bulding->GetFloorsNo());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Save given investment bulding object (buildingNew) to database. 
	 * @param InvestmentBuilding $buildingNew
	 */
	public static function EditInvestmentBuilding(InvestmentBuilding $buildingNew){
		$query = "UPDATE #S#investments_buildings SET id=?, investments_id=?, symbol=?, name=?, description=?, area=?, due_date=?, floors_no=? WHERE id=?;";
		$params = array($buildingNew->GetId(), $buildingNew->GetInvestmentId(), $buildingNew->GetSymbol(), $buildingNew->GetName(),
        $buildingNew->GetDescription(), $buildingNew->GetArea(), $buildingNew->GetDueDate(), $buildingNew->GetFloorsNo(), (int) $buildingNew->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

	/**
	 * Add or investment bulding if exists, given agent object.
	 * @param InvestmentBuilding $bulding
	 */
	public static function AddEditInvestmentBuilding(InvestmentBuilding $bulding){
		$f = self::GetInvestmentBuilding($bulding->GetId(), $bulding->GetIdLng());
		if($f == null){
			self::AddInvestmentBuilding($bulding);
		}else{
			self::EditInvestmentBuilding($bulding);
		}
	}

	/**
	 * Return an array of investment building for given investment.
	 * @param int $investmentId
     * @param int $idLng
	 * @return InvestmentBuilding[]
	 */
	public static function GetInvestmentBuildings($investmentId, $idLng){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#investments_buildings WHERE investments_id=? AND id_lng=? ORDER BY symbol ASC", array((int) $investmentId, (int) $idLng));
		$buldings = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$buldings[$ndx] = self::BuildInvestmentBuilding($row);
			$ndx++;			
		}
		return $buldings;
	}
	
	/**
	 * Delete investment building from database, clears info in offers, given by ID.
	 * @param int $id
	 */
	public static function DeleteInvestmentBuilding($id){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("UPDATE #S#offers SET investments_buildings_id=NULL WHERE investments_buildings_id=?", array((int) $id));
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments_buildings WHERE id=?", array((int) $id));
	}

	/**
	 * Delete all investment buildings for given investment.
	 * @param int $investmentId
	 */
	public static function DeleteInvestmentBuildings($investmentId){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("UPDATE #S#offers SET investments_buildings_id=NULL WHERE investments_buildings_id IN (SELECT id FROM #S#investments_buildings WHERE investments_id=?)", array((int) $investmentId));
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#investments_buildings WHERE investments_id=?", array((int) $investmentId));
	}

    /**
     * Add offers from xml to given investment building.
     * @param xmlnode $offersNode
     * @param InvestmentBuilding $bulding
     */
	public static function AddOffersToBuilding($offersNode, InvestmentBuilding $bulding){
		if($offersNode != null && $offersNode->children != null){
			foreach(@$offersNode->children() as $ofeNode){
				$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("UPDATE #S#offers SET investments_buildings_id=? WHERE id=?", array($bulding->GetId(), (int) $ofeNode['wartosc']));
			}
		}
	}
    
    /**
     * Remove offers from investment building.
     * @param xmlnode $offersNode
     * @param InvestmentBuilding $bulding
     */
	public static function RemoveOffersFromBuilding($offersIds){
		foreach($offersIds as $id){
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("UPDATE #S#offers SET investments_buildings_id=0 WHERE id=?", array($id));
        }
	}
	
}

?>