<?php

/**
 * Class describing the offer.
 * @author Marcin Welc
 *
 */
class Offer extends AObject {

    private $_IdLng;
    private $_Status;
	private $_Object;
	private $_Rent;
	private $_Symbol;
	private $_Original;
	private $_Province;
	private $_District;
	private $_Location;
	private $_Quarter;
	private $_Region;
	private $_Street;
	private $_Floor;
	private $_Price;
	private $_PriceSquare;
	private $_RoomsNo;
	private $_Area;
	private $_Latitude;
	private $_Longitude;
	private $_BuildingTechnology;
	private $_ConstructionMaterial;
	private $_ConstructionStatus;
	private $_BuildingType;
	private $_AgentId;
	private $_InvestmentBuildingId;
	private $_CreationDate;
	private $_ModificationDate;
    private $_DescriptionSynonim;
    private $_Kraj;
    private $_IloscPieter;
    private $_RokBudowy;
    private $_PierwszaStrona;
    private $_RodzajDomu;
    private $_RodzajObiektu;
    private $_SposobPrzyjecia;
    private $_IloscOdslonWWW;
    private $_StatusWlasnosci;
    private $_StanPrawny;
    private $_UmeblowanieLista;
    private $_PowierzchniaDzialki;
    private $_Zamiana;
    private $_UwagiOpis;
    private $_UwagiNieruchomosc;
    private $_VideoLink;
    private $_NoCommission;
    private $_ExpirationDate;
	private $_TagTitle;
	private $_TagKeywords;
	private $_TagDescription;
	
	private $_AgentObj = null;
	private $_InvestmentBuldingObj = null;
	private $_Photos = array();
	private $_SWFs = array();
	private $_Rooms = array();
    
    private $_HasSwfs;
    private $_HasMovs;
    private $_HasPhotos;
    private $_HasPans;
    private $_HasMaps;
    private $_HasProj;
    private $_HasVirtual;
    private $_LocAsCommune;
    
    private $dataLoaded = false;
    private $attributesLoaded = false;
    
	public $data = array();
	public $attributes = array();

    public function GetIdLng(){
		return $this->_IdLng;
	}

	public function SetIdLng($value){
		$this->_IdLng = $value;
	}

    public function GetStatus(){
		return $this->_Status;
	}

	public function SetStatus($value){
		$this->_Status = $value;
	}

	public function GetObject(){
		return $this->_Object;
	}

	public function SetObject($value){
		$this->_Object = $value;
	}

	public function GetRent(){
		return $this->_Rent;
	}

	public function SetRent($value){
		$this->_Rent = $value;
	}

	public function GetSymbol(){
		return $this->_Symbol;
	}

	public function SetSymbol($value){
		$this->_Symbol = $value;
	}

	public function GetOriginal(){
		return $this->_Original;
	}

	public function SetOriginal($value){
		$this->_Original = $value;
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

	public function GetFloor(){
		return $this->_Floor;
	}

	public function SetFloor($value){
		$this->_Floor = $value;
	}
	
	public function GetPrice(){
		return $this->_Price;
	}

	public function SetPrice($value){
		$this->_Price = $value;
	}

	public function GetPriceSquare(){
		return $this->_PriceSquare;
	}

	public function SetPriceSquare($value){
		$this->_PriceSquare = $value;
	}

	public function GetRoomsNo(){
		return $this->_RoomsNo;
	}

	public function SetRoomsNo($value){
		$this->_RoomsNo = $value;
	}

	public function GetArea(){
		return $this->_Area;
	}

	public function SetArea($value){
		$this->_Area = $value;
	}

	public function GetLatitude(){
		return $this->_Latitude;
	}

	public function SetLatitude($value){
		$this->_Latitude = $value;
	}

	public function GetLongitude(){
		return $this->_Longitude;
	}

	public function SetLongitude($value){
		$this->_Longitude = $value;
	}

	public function GetBuildingTechnology(){
		return $this->_BuildingTechnology;
	}

	public function SetBuildingTechnology($value){
		$this->_BuildingTechnology = $value;
	}

	public function GetConstructionMaterial(){
		return $this->_ConstructionMaterial;
	}

	public function SetConstructionMaterial($value){
		$this->_ConstructionMaterial = $value;
	}

	public function GetConstructionStatus(){
		return $this->_ConstructionStatus;
	}

	public function SetConstructionStatus($value){
		$this->_ConstructionStatus = $value;
	}

	public function GetBuildingType(){
		return $this->_BuildingType;
	}

	public function SetBuildingType($value){
		$this->_BuildingType = $value;
	}

	public function GetAgentId(){
		return $this->_AgentId;
	}

	public function SetAgentId($value){
		$this->_AgentId = $value;
	}

	public function GetInvestmentBuildingId(){
		return $this->_InvestmentBuildingId;
	}

	public function SetInvestmentBuildingId($value){
		$this->_InvestmentBuildingId = $value;
	}
	
	public function GetCreationDate(){
		return $this->_CreationDate;
	}

	public function SetCreationDate($value){
		$this->_CreationDate = $value;
	}
	
	public function GetModificationDate(){
		return $this->_ModificationDate;
	}

	public function SetModificationDate($value){
		$this->_ModificationDate = $value;
	}
        
    public function GetDescriptionSynonim(){
		return $this->_DescriptionSynonim;
	}

	public function SetDescriptionSynonim($value){
		$this->_DescriptionSynonim = $value;
	}
	
    public function GetKraj(){
		return $this->_Kraj;
	}

	public function SetKraj($value){
		$this->_Kraj = $value;
	}
    
    public function GetIloscPieter(){
		return $this->_IloscPieter;
	}

	public function SetIloscPieter($value){
		$this->_IloscPieter = $value;
	}
    
    public function GetRokBudowy(){
		return $this->_RokBudowy;
	}

	public function SetRokBudowy($value){
		$this->_RokBudowy = $value;
	}
    
    public function GetPierwszaStrona(){
		return $this->_PierwszaStrona;
	}

	public function SetPierwszaStrona($value){
		$this->_PierwszaStrona = $value;
	}
    
    public function GetRodzajDomu(){
		return $this->_RodzajDomu;
	}

	public function SetRodzajDomu($value){
		$this->_RodzajDomu = $value;
	}
    
    public function GetRodzajObiektu(){
		return $this->_RodzajObiektu;
	}

	public function SetRodzajObiektu($value){
		$this->_RodzajObiektu = $value;
	}
    
    public function GetSposobPrzyjecia(){
		return $this->_SposobPrzyjecia;
	}

	public function SetSposobPrzyjecia($value){
		$this->_SposobPrzyjecia = $value;
	}
    
    public function GetIloscOdslonWWW(){
		return $this->_IloscOdslonWWW;
	}

	public function SetIloscOdslonWWW($value){
		$this->_IloscOdslonWWW = $value;
	}
    
    public function GetStatusWlasnosci(){
		return $this->_StatusWlasnosci;
	}

	public function SetStatusWlasnosci($value){
		$this->_StatusWlasnosci = $value;
	}
    
    public function GetStanPrawny(){
		return $this->_StanPrawny;
	}

	public function SetStanPrawny($value){
		$this->_StanPrawny = $value;
	}
    
    public function GetUmeblowanieLista(){
		return $this->_UmeblowanieLista;
	}

	public function SetUmeblowanieLista($value){
		$this->_UmeblowanieLista = $value;
	}
    
    public function GetPowierzchniaDzialki(){
		return $this->_PowierzchniaDzialki;
	}

	public function SetPowierzchniaDzialki($value){
		$this->_PowierzchniaDzialki = $value;
	}
    
    public function GetZamiana(){
		return $this->_Zamiana;
	}

	public function SetZamiana($value){
		$this->_Zamiana = $value;
	}
    
    public function GetUwagiOpis(){
		return $this->_UwagiOpis;
	}

	public function SetUwagiOpis($value){
		$this->_UwagiOpis = $value;
	}
    
    public function GetUwagiNieruchomosc(){
		return $this->_UwagiNieruchomosc;
	}

	public function SetUwagiNieruchomosc($value){
		$this->_UwagiNieruchomosc = $value;
	}
    
    public function GetVideoLink(){
		return $this->_VideoLink;
	}

	public function SetVideoLink($value){
		$this->_VideoLink = $value;
	}
    
    public function GetNoCommission(){
		return $this->_NoCommission;
	}

	public function SetNoCommission($value){
		$this->_NoCommission = $value;
	}
    
    public function GetExpirationDate(){
		return $this->_ExpirationDate;
	}

	public function SetExpirationDate($value){
		$this->_ExpirationDate = $value;
	}
	
	public function GetTagTitle(){
		return $this->_TagTitle;
	}

	public function SetTagTitle($value){
		$this->_TagTitle = $value;
	}
	
	public function GetTagKeywords(){
		return $this->_TagKeywords;
	}

	public function SetTagKeywords($value){
		$this->_TagKeywords = $value;
	}
	
	public function GetTagDescription(){
		return $this->_TagDescription;
	}

	public function SetTagDescription($value){
		$this->_TagDescription = $value;
	}
    
    public function GetHasSwfs(){
		return $this->_HasSwfs;
	}

	public function SetHasSwfs($value){
		$this->_HasSwfs = $value;
	}
    
    public function GetHasMovs(){
		return $this->_HasMovs;
	}

	public function SetHasMovs($value){
		$this->_HasMovs = $value;
	}
    
    public function GetHasPhotos(){
		return $this->_HasPhotos;
	}

	public function SetHasPhotos($value){
		$this->_HasPhotos = $value;
	}
    
    public function GetHasPans(){
		return $this->_HasPans;
	}

	public function SetHasPans($value){
		$this->_HasPans = $value;
	}
    
    public function GetHasMaps(){
		return $this->_HasMaps;
	}

	public function SetHasMaps($value){
		$this->_HasMaps = $value;
	}
    
    public function GetHasProj(){
		return $this->_HasProj;
	}

	public function SetHasProj($value){
		$this->_HasProj = $value;
	}
    
    public function GetHasVirtual(){
		return $this->_HasVirtual;
	}

	public function SetHasVirtual($value){
		$this->_HasVirtual = $value;
	}
    
    public function GetLocAsCommune(){
        return  $this->_LocAsCommune;
    }
    
    public function SetLocAsCommune($value){
        $this->_LocAsCommune = $value;
    }
    
	/**
	 * Return agent as object.
	 * @return Agent
	 */
	public function GetAgentObj(){
		if($this->_AgentObj == null){
			$ags = new Agents();
			$this->_AgentObj = $ags->GetAgent($this->GetAgentId());
		}
		return $this->_AgentObj;
	}
    
	/**
	 * Return array of offer photos.
	 * @return OfferPhoto[]
	 */
	public function GetPhotos(){
        if($this->_Photos == null){
            $this->SetPhotos(OfferPhotos::GetPhotos($this->GetId()));
        }
		return $this->_Photos;
	}
	
	/**
	 * Return array of offer photos by Type.
     * @pram string $type
	 * @return OfferPhoto[]
	 */
	public function GetPhotosByType($type = 'Zdjecie'){	
        return OfferPhotos::GetPhotosByType($this->GetId(), $type);		
	}
	
	/**
	 * Set array of photos to offer.
	 * @param array $value
	 */
	public function SetPhotos($value){
		$this->_Photos = $value;
	}
    
    /**
	 * Return array of offer SWF.
	 * @return OfferPhoto[]
	 */
	public function GetSWFs(){
        if($this->_SWFs == null){
            $this->SetSWFs(OfferPhotos::GetSWFs($this->GetId()));
        }
		return $this->_SWFs;
	}
	
	/**
     * Return offers SWF by given id
     * @param int $id
     * @return OfferPhoto
     */	
	public function GetSwfById($id){		
		$swfs = $this->GetSWFs();		
		$returnSWF = false;
		foreach($swfs as $swf){			
			if($swf->GetId() == $id){
				$returnSWF = $swf;			
				break;
			}
		}
		return $returnSWF;
	}
	
	/**
	 * Set array of SWF to offer.
	 * @param array $value
	 */
	public function SetSWFs($value){
		$this->_SWFs = $value;
	}
	
    /**
	 * Return array of offer rooms.
	 * @return OfferRoom[]
	 */
	public function GetRooms(){
        if($this->_Rooms == null){
            $this->SetRooms(OfferRooms::GetRooms($this->GetId(), $this->GetIdLng()));
        }
		return $this->_Rooms;
	}
	
    /**
	 * Set array of rooms to offer.
	 * @param array $value
	 */
	public function SetRooms($value){
		$this->_Rooms = $value;
	}

	/**
	 * Returns object name and rent as string.
	 * @return string
	 */
	public function GetShortDescription(){
		$str = $this->GetObject();
		if($str == "Dzialka") $str = "Działka";
		$str .= " " . ($this->GetRent() ? "Wynajem" : "Sprzedaż"); 
		return $str;
	} 
	
    /**
	 * Returns content of given set (array) as string.
	 * @param array $set
	 * @return string
	 */
	public function GetSetAsText($set){
		$str = "";
		if($set != null && is_array($set)){
			foreach($set as $val)
				$str .= $val . ", ";
		}
		$str = trim($str);
		return substr($str, 0, strlen($str) - 1);
	}
	
    /**
     * Returns image tag with thumbnail (first photo, if exists).
     * @param int $width
     * @param int $height
     * @param bool $kadruj
     * @return string
     */
	public function GetThumbnail($width = 100, $height = 75, $kadruj = false){
		if($this->GetPhotos() != null && count($this->GetPhotos()) > 0){
			$photos = $this->GetPhotos();
			$photo = $photos[0];
			return "<img src='".$photo->GetImgSrc($width."_".$height, false, false, $kadruj)."' />";
		}else
			return "<img src='" . Config::$NoPhotoPath . "' />";		
	}

    /**
     * Returns image address, in given width and height.
     * @param int $photoNo
     * @param int $width
     * @param int $height
     * @param bool $kadruj
     * @param bool $additionalWatermark
     * @param string $type
     * @return type 
     */
    public function GetResizedFotoSrc($photoNo, $width, $height, $kadruj = false, $additionalWatermark = false, $type = false){        
    	if($this->GetPhotos() != null && count($this->GetPhotos()) > $photoNo){
            if($type) $photos = $this->GetPhotosByType($type);
            else $photos = $this->GetPhotos();
            if(isset($photos[$photoNo])) $photo = $photos[$photoNo];
            else return Config::$NoPhotoPath;
            
            return $photo->GetImgSrc($width . "_" . $height, false, $additionalWatermark, $kadruj);
        }else{
            return Config::$NoPhotoPath;
        }
    }
    
	/**
	 * Check if offer has Photos.
	 * @return bool
	 */
	public function hasPhotos(){
		return $this->GetHasPhotos()==1;
	}
    
    /**
	 * Check if offer has Projection.
	 * @return bool
	 */
	public function hasProj(){
		return $this->GetHasProj()==1;
	}
	
	/**
     * Returns object tag with intro (intro SWF, if exists).
     * @param int $width
     * @param int $height
     * @param bool $transparent
     * @return string
     */
	public function GetSWFIntro($width = 110, $height = 80, $transparent = false){
		if($this->GetSWFs() != null && count($this->GetSWFs()) > 0){
			foreach($this->GetSWFs() as $swf){
				if($swf->GetIntro()){
					$s = '<object type="application/x-shockwave-flash" data="'.$swf->GetSWFSrc().'" style="width:100%; height:100%;" height="100%" width="100%">
							<param name="movie" value="'.$swf->GetSWFSrc().'" />';
					if($transparent) $s .= '<param name="wmode" value="transparent" />';
                    $s .= '</object>';
					return $s;
				}
			}
		}
		return "<img src='" . Config::$NoPhotoPath . "' />";	
	}

	/**
	 * Check if offer has SWF files. 
	 * @return bool
	 */
	public function HasSWF(){
		return $this->GetHasSwfs()==1;
	}
                
        /**
	 * Check if offer has SWFIntro files. 
	 * @return bool
	 */
	public function HasSWFIntro(){
        if($this->GetHasSwfs()==1){
            foreach($this->GetSWFs() as $swf){
                if($swf->GetIntro()) return true;
            }
        }
        return false;
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
	 * Return investment building as object.
	 * @return Investment
	 */
	public function GetInvestmentBuilding(){
		if($this->_InvestmentBuldingObj == null){
			$this->_InvestmentBuldingObj = InvestmentBuildings::GetInvestmentBuilding($this->GetInvestmentBuildingId());
		}
		return $this->_InvestmentBuldingObj;
	}

    /**
     * Note offer view on page.
     */
    public function NoteOfferView(){
        $query = "UPDATE #S#offers SET display_number=display_number+1 WHERE id=?";
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array((int) $this->GetId()));
    }
	

	public function __construct($idLng, $id, $Status, $Object, $Rent, $Symbol, $Original, $Province, $District, 
                                $Location, $Quarter, $Region, $Street, $Floor, $Price, $PriceSquare, $RoomsNo, 
                                $Area, $Latitude, $Longitude, $BuildingTechnology, $ConstructionMaterial, $ConstructionStatus, 
                                $BuildingType, $AgentId, $CreateDate, $ModDate, $InvestBuilding, $Kraj, $IloscPieter, $RokBudowy, 
                                $RodzajDomu, $PierwszaStrona, $RodzajObiektu, $SposobPrzyjecia, $IloscOdslonWWW, $StanPrawny, $StatusWlasnosci, 
                                $Umeblowanie, $PowierzchniaDzialki, $Zamiana, $UwagiOpis, $UwagiNieruchomosc, $VideoLink, $NoCommission, $ExpirationDate,
                                $HasSwfs, $HasMovs, $HasPhotos, $HasPans, $HasMaps, $HasProj, $HasVirtual, $loc_as_commune, $TagTitle, $TagKeywords, $TagDescription){
        $this->SetId($id);
		$this->SetIdLng($idLng == null ? 1045 : $idLng);
        $this->SetStatus($Status);
		$this->SetObject($Object);
		$this->SetRent($Rent);
		$this->SetSymbol($Symbol);
		$this->SetOriginal($Original);
		$this->SetProvince($Province);
		$this->SetDistrict($District);
		$this->SetLocation($Location);
		$this->SetQuarter($Quarter);
		$this->SetRegion($Region);
		$this->SetStreet($Street);
		$this->SetFloor($Floor);
		$this->SetPrice($Price);
		$this->SetPriceSquare($PriceSquare);
		$this->SetRoomsNo($RoomsNo);
		$this->SetArea($Area);
		$this->SetLatitude($Latitude);
		$this->SetLongitude($Longitude);
		$this->SetBuildingTechnology($BuildingTechnology);
		$this->SetConstructionMaterial($ConstructionMaterial);
		$this->SetConstructionStatus($ConstructionStatus);
		$this->SetBuildingType($BuildingType);
		$this->SetAgentId($AgentId);
		$this->SetCreationDate($CreateDate);
		$this->SetModificationDate($ModDate);
		$this->SetInvestmentBuildingId($InvestBuilding);
        $this->SetKraj($Kraj); 
        
        $this->SetIloscPieter($IloscPieter); 
        $this->SetRokBudowy($RokBudowy); 
        $this->SetRodzajDomu($RodzajDomu); 
        $this->SetPierwszaStrona($PierwszaStrona);
        $this->SetRodzajObiektu($RodzajObiektu); 
        $this->SetSposobPrzyjecia($SposobPrzyjecia);
        $this->SetIloscOdslonWWW($IloscOdslonWWW);
        $this->SetStanPrawny($StanPrawny);
        $this->SetStatusWlasnosci($StatusWlasnosci);
        $this->SetUmeblowanieLista($Umeblowanie);
        $this->SetPowierzchniaDzialki($PowierzchniaDzialki);
        $this->SetZamiana($Zamiana);
        $this->SetUwagiOpis($UwagiOpis);
        $this->SetUwagiNieruchomosc($UwagiNieruchomosc);
        $this->SetVideoLink($VideoLink);
        $this->SetNoCommission($NoCommission);
        $this->SetExpirationDate($ExpirationDate);
		$this->SetTagTitle($TagTitle);
		$this->SetTagKeywords($TagKeywords);
		$this->SetTagDescription($TagDescription);
        
        $this->SetHasSwfs($HasSwfs);
        $this->SetHasMovs($HasMovs);
        $this->SetHasPhotos($HasPhotos);
        $this->SetHasPans($HasPans);
        $this->SetHasMaps($HasMaps);
        $this->SetHasProj($HasProj);
        $this->SetHasVirtual($HasVirtual);
        $this->SetLocAsCommune($loc_as_commune);
	}
    
    private function LoadProperties(){
        //dynamic properties
        if($this->dataLoaded) return;
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT p.name, op.value, op.set FROM #S#offers_properties AS op INNER JOIN #S#properties AS p ON p.id = op.properties_id WHERE op.offers_id=? AND op.offers_id_lng=?", 
            array((int) $this->GetId(), (int) $this->GetIdLng()));
		
		while($row2 = DataBase::GetDbInstance()->FetchArray($result)){
			if($row2['set'] == 1){
                $set = null;
                if (array_key_exists($row2['name'], $this->data)) {
                    $set = $this->data[$row2['name']];
                }
				$set[count($set ?? [])] = $row2['value'];
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
        if(in_array($name,OffersHelper::$props_arr)!=false){
            if($this->data == null) $this->LoadProperties();
            $name="Get".$name;
            return $this->$name();
        }else{
            if($this->data == null) $this->LoadProperties();
            if (array_key_exists($name, $this->data)) {
                return $this->data[$name];
            }else false;
        }
	}

    /**
     * Returns given attribute value if exists. If not returns empty string.
     * @param string $name
     * @return string
     */
    public function Atrybut($name){
        $attr_arr = array("Link","ZeroProwizji");
        if(in_array($name,$attr_arr)!=false){
            switch($name){
                case "Link": return $this->GetVideoLink();
                case "ZeroProwizji": return $this->GetNoCommission();
            }
        }else{
            if(!$this->attributesLoaded){
                if($this->Atrybuty != null){

                    foreach ($this->Atrybuty as $value) {
                        $pos = strrpos($value, "#|#");
                        if (!($pos === false)){
                            $this->attributes[substr($value, 0, $pos)] = substr($value, $pos + 3);
                        }
                    }
                }else $this->attributes = array(); 
                $this->attributesLoaded = true;
            }
            if(array_key_exists($name, $this->attributes)){
                return $this->attributes[$name];
            }
        }
        return "";
    }

}

?>