<?php

/**
 * Class describing the Investment.
 * @author Marcin Welc
 *
 */
class Investment extends AObject{

    private $_IdLng;
	private $_No;
	private $_Number;
	private $_Name;
	private $_Description;
	private $_ShortDescription;
	private $_Contact;
	private $_MapMarker	;
	private $_Garage;
	private $_Pool;
	private $_Terrace;
	private $_AirConditioning;
	private $_HouseProject;
	private $_Special;
	private $_CreationDate;
	private $_DueDate;
	private $_TotalArea;
	private $_GrossVolume;
	private $_AreaFrom;
	private $_AreaTo;
	private $_PriceFrom;
	private $_PriceTo;
	private $_Pricem2From;
	private $_Pricem2To;
	private $_FloorFrom;
	private $_FloorTo;
	private $_RoomsNoFrom;
	private $_RoomsNoTo;
	private $_Country;
	private $_Province;
	private $_District;
	private $_Location;
	private $_Quarter;
	private $_Region;
	private $_Street;
	private $_Category;
	private $_DepartmentId;

	private $_DepartmentObj = null; 
	private $_Photos = array();
	private $_Buildings = array();
	
    public function GetIdLng(){
		return $this->_IdLng;
	}

	public function SetIdLng($value){
		$this->_IdLng = $value;
	}

	public function GetNo(){
		return $this->_No;
	}

	public function SetNo($value){
		$this->_No = $value;
	}

	public function GetNumber(){
		return $this->_Number;
	}

	public function SetNumber($value){
		$this->_Number = $value;
	}

	public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}

	public function GetDescription(){
		return $this->_Description;
	}

	public function SetDescription($value){
		$this->_Description = $value;
	}

	public function GetShortDescription(){
		return $this->_ShortDescription;
	}

	public function SetShortDescription($value){
		$this->_ShortDescription = $value;
	}

	public function GetContact(){
		return $this->_Contact;
	}

	public function SetContact($value){
		$this->_Contact = $value;
	}

	public function GetMapMarker(){
		return $this->_MapMarker;
	}
	
	public function GetLongitude(){	
		if($this->GetMapMarker())
		{
			list($latitude, $longitude) = explode(',', $this->GetMapMarker());
			return trim($longitude, ')| ');
		}
		else return false;
	}
	
	public function GetLatitude(){
		if($this->GetMapMarker())
		{
			list($latitude, $longitude) = explode(',', $this->GetMapMarker());
			return trim($latitude, '( ');
		}
		else return false;
	}

	public function SetMapMarker($value){
		$this->_MapMarker = $value;
	}

	public function GetGarage(){
		return $this->_Garage;
	}

	public function SetGarage($value){
		$this->_Garage = $value;
	}

	public function GetPool(){
		return $this->_Pool;
	}

	public function SetPool($value){
		$this->_Pool = $value;
	}

	public function GetTerrace(){
		return $this->_Terrace;
	}

	public function SetTerrace($value){
		$this->_Terrace = $value;
	}

	public function GetAirConditioning(){
		return $this->_AirConditioning;
	}

	public function SetAirConditioning($value){
		$this->_AirConditioning = $value;
	}

	public function GetHouseProject(){
		return $this->_HouseProject;
	}

	public function SetHouseProject($value){
		$this->_HouseProject = $value;
	}

	public function GetSpecial(){
		return $this->_Special;
	}

	public function SetSpecial($value){
		$this->_Special = $value;
	}

	public function GetCreationDate(){
		return $this->_CreationDate;
	}

	public function SetCreationDate($value){
		$this->_CreationDate = $value;
	}

	public function GetDueDate(){
		return $this->_DueDate;
	}

	public function SetDueDate($value){
		$this->_DueDate = $value;
	}

	public function GetTotalArea(){
		return $this->_TotalArea;
	}

	public function SetTotalArea($value){
		$this->_TotalArea = $value;
	}

	public function GetGrossVolume(){
		return $this->_GrossVolume;
	}

	public function SetGrossVolume($value){
		$this->_GrossVolume = $value;
	}

	public function GetAreaFrom(){
		return $this->_AreaFrom;
	}

	public function SetAreaFrom($value){
		$this->_AreaFrom = $value;
	}

	public function GetAreaTo(){
		return $this->_AreaTo;
	}

	public function SetAreaTo($value){
		$this->_AreaTo = $value;
	}

	public function GetPriceFrom(){
		return $this->_PriceFrom;
	}

	public function SetPriceFrom($value){
		$this->_PriceFrom = $value;
	}

	public function GetPriceTo(){
		return $this->_PriceTo;
	}

	public function SetPriceTo($value){
		$this->_PriceTo = $value;
	}

	public function GetPricem2From(){
		return $this->_Pricem2From;
	}

	public function SetPricem2From($value){
		$this->_Pricem2From = $value;
	}

	public function GetPricem2To(){
		return $this->_Pricem2To;
	}

	public function SetPricem2To($value){
		$this->_Pricem2To = $value;
	}

	public function GetFloorFrom(){
		return $this->_FloorFrom;
	}

	public function SetFloorFrom($value){
		$this->_FloorFrom = $value;
	}

	public function GetFloorTo(){
		return $this->_FloorTo;
	}

	public function SetFloorTo($value){
		$this->_FloorTo = $value;
	}

	public function GetRoomsNoFrom(){
		return $this->_RoomsNoFrom;
	}

	public function SetRoomsNoFrom($value){
		$this->_RoomsNoFrom = $value;
	}

	public function GetRoomsNoTo(){
		return $this->_RoomsNoTo;
	}

	public function SetRoomsNoTo($value){
		$this->_RoomsNoTo = $value;
	}

	public function GetCountry(){
		return $this->_Country;
	}

	public function SetCountry($value){
		$this->_Country = $value;
	}

	public function GetProvince(){
		return $this->_Province;
	}

	public function SetProvince($value){
		$this->_Province = $value;
	}

	public function GetDistrict(){
		return $this->_District;
	}

	public function SetDistrict($value){
		$this->_District = $value;
	}

	public function GetLocation(){
		return $this->_Location;
	}

	public function SetLocation($value){
		$this->_Location = $value;
	}

	public function GetQuarter(){
		return $this->_Quarter;
	}

	public function SetQuarter($value){
		$this->_Quarter = $value;
	}

	public function GetRegion(){
		return $this->_Region;
	}

	public function SetRegion($value){
		$this->_Region = $value;
	}

	public function GetStreet(){
		return $this->_Street;
	}

	public function SetStreet($value){
		$this->_Street = $value;
	}

	public function GetCategory(){
		return $this->_Category;
	}

	public function SetCategory($value){
		$this->_Category = $value;
	}

	public function GetDepartmentId(){
		return $this->_DepartmentId;
	}

	public function SetDepartmentId($value){
		$this->_DepartmentId = $value;
	}	

	/**
	 * Return department as object.
	 * @return Department
	 */
	public function GetDepartmentObj(){
		if($this->_DepartmentObj == null){
			$deps = new Departments();
			$this->_DepartmentObj = $deps->GetDepartment($this->GetDepartmentId());
		}
		return $this->_DepartmentObj;
	}
	
	/**
	 * Return array of offer photos.
	 * @return OfferPhoto[]
	 */
	public function GetPhotos(){
        if($this->_Photos == null){
            $this->SetPhotos(OfferPhotos::GetPhotosInvestment($this->GetId()));
        }
		return $this->_Photos;
	}
	
	/**
	 * Return array of offer photos.
     * @param string $type
	 * @return OfferPhoto[]
	 */
	public function GetPhotosByType($type = 'Zdjecie'){
		return OfferPhotos::GetPhotosInvestmentByType($this->GetId(), $type);
	}
	
	/**
	 * Set array of photos to offer.
	 * @param $value
	 */
	public function SetPhotos($value){
		$this->_Photos = $value;
	}
	
	/**
	 * Check if offer has Photos.
	 * @return bool
	 */
	public function hasPhotos(){
		return $this->GetPhotos() != null && count($this->GetPhotos()) > 0;
	}
	
	/**
	 * Return array of investment buildings.
	 * @return InvestmentBuilding[]
	 */
	public function GetBuildings(){
        if($this->_Buildings == null){
            $this->SetBuildings(InvestmentBuildings::GetInvestmentBuildings($this->GetId(), $this->GetIdLng()));
        }
		return $this->_Buildings;
	}
	/**
	 * Set array of buildings to investment.
	 * @param $value
	 */
	public function SetBuildings($value){
		$this->_Buildings = $value;
	}
	
	/**
	 * Returns a building count in investment.
	 * @return int
	 */
	public function GetBuildingsCount(){		
		return count($this->GetBuildings());
	}
	
	/**
	 * Returns a offers count in investment.
	 * @return int
	 */
	public function GetOffersCount($lngId = 1045){
		return Investments::GetOffersCount($this->GetId(), $lngId);
	}
	
	/**
	 * Returns array of offers in investment.
	 * @return array
	 */
	public function GetOffers($lngId = 1045){
		return Investments::GetOffers($this->GetId(), $lngId);
	}
	
	/**
	 * Returns image tag with thumbnail (first photo, if exists). 
	 * @return string
	 */
	public function GetThumbnail(){
		if($this->GetPhotos() != null && count($this->GetPhotos()) > 0){
			$photos = $this->GetPhotos();
			$photo = $photos[0];  
			return "<img src='".$photo->GetImgSrc("100_75", false, false)."' />";
		}else
			return "<img src='" . Config::$NoPhotoPath . "' />";		
	}
	
	/**
     * Returns image address, in given width and height.
     * @param int $photoNo
     * @param int $width
     * @param int $height
     * @return string
     */
	function GetResizedFotoSrc($photoNo, $width, $height, $kadruj = false, $basicWatermark = false, $additionalWatermark = false){
        if($this->GetPhotos() != null && count($this->GetPhotos()) > $photoNo){
            $photos = $this->GetPhotos();
            $photo = $photos[$photoNo];
            return $photo->GetImgSrc($width . "_" . $height, $basicWatermark, $additionalWatermark, $kadruj);
        }else
            return Config::$NoPhotoPath;
    }

    /**
     * Return string with all location information path.
     * @return string
     */
	public function GetAllLocation(){
		$str = "";
		if($this->GetProvince() != null) $str = $this->GetProvince() . ", ";
		if($this->GetDistrict() != null) $str .= $this->GetDistrict() . ", ";
		if($this->GetLocation() != null) $str .= $this->GetLocation() . ", ";
		if($this->GetQuarter()!= null) $str .= $this->GetQuarter() . ", ";
		if($this->GetRegion()!= null) $str .= $this->GetRegion() . ", ";
		if(strlen($str) > 2) $str = substr($str, 0, strlen($str) - 2);
		return $str;
	}
    
    /**
     * Return an array of Agents
     * @return Agents[]
     */
    public function GetAgents(){
        return Investments::GetInvestmentsAgents($this);
    }
	
	
	public function __construct($idLng, $id, $no, $number, $name, $description, $shortDescription, $contact, $mapMarker, $garage, $pool, $terrace, $airConditioning, $houseProject, $special, $creationDate,
			$dueDate, $totalArea, $grossVolume, $areaFrom, $areaTo, $priceFrom, $priceTo, $pricem2From, $pricem2To, $floorFrom, $floorTo, $roomsNoFrom, $roomsNoTo, $country, $province, $district, $location, 
			$quarter, $region, $street, $category, $departmentId){
		$this->SetId($id);
        $this->SetIdLng($idLng == null ? 1045 : $idLng);
		$this->SetNo($no);
		$this->SetNumber($number);
		$this->SetName($name);
		$this->SetDescription($description);
		$this->SetShortDescription($shortDescription);
		$this->SetContact($contact);
		$this->SetMapMarker($mapMarker);
		$this->SetGarage($garage);
		$this->SetPool($pool);
		$this->SetTerrace($terrace);
		$this->SetAirConditioning($airConditioning);
		$this->SetHouseProject($houseProject);
		$this->SetSpecial($special);
		$this->SetCreationDate($creationDate);
		$this->SetDueDate($dueDate);
		$this->SetTotalArea($totalArea);
		$this->SetGrossVolume($grossVolume);
		$this->SetAreaFrom($areaFrom);
		$this->SetAreaTo($areaTo);
		$this->SetPriceFrom($priceFrom);
		$this->SetPriceTo($priceTo);
		$this->SetPricem2From($pricem2From);
		$this->SetPricem2To($pricem2To);
		$this->SetFloorFrom($floorFrom);
		$this->SetFloorTo($floorTo);
		$this->SetRoomsNoFrom($roomsNoFrom);
		$this->SetRoomsNoTo($roomsNoTo);
		$this->SetCountry($country);
		$this->SetProvince($province);
		$this->SetDistrict($district);
		$this->SetLocation($location);
		$this->SetQuarter($quarter);
		$this->SetRegion($region);
		$this->SetStreet($street);
		$this->SetCategory($category);
		$this->SetDepartmentId($departmentId);
	}
    
    private $dataLoaded = false;
    public $data = array();
    
    private function LoadProperties(){
        //dynamic properties
        if($this->dataLoaded) return;
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT p.name, op.value, op.set FROM #S#investments_properties AS op INNER JOIN #S#properties AS p ON p.id = op.properties_id WHERE op.investments_id=? AND op.investments_id_lng=?", 
            array((int) $this->GetId(), (int) $this->GetIdLng()));
		
		while($row2 = DataBase::GetDbInstance()->FetchArray($result)){
			if($row2['set'] == 1){
                $set = null;
                if (array_key_exists($row2['name'], $this->data)) {
                    $set = $this->data[$row2['name']];
                }
				$set[count($set)] = $row2['value'];
                $this->data[$row2['name']] = $set;
			}else
                $this->data[$row2['name']] = $row2['value'];
		}
        $this->dataLoaded = true;
    }
    
    /**
	 * Universal method to set dynamic property.
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value) {
        if($this->data == null) $this->LoadProperties();
        $this->data[$name] = $value;
    }
    
	/**
	 * Universal methos to get value of dynamic property.
	 * If property not exists, nothing will be return. 
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
        if($this->data == null) $this->LoadProperties();
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }else ;//throw new Exception("brak wlasciwosci");
	}
}

?>
