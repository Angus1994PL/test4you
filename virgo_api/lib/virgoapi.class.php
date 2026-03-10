<?php

/**
 * Class providing acces to offers, like geting them to view on the page.
 * @author Jakub Konieczka
 *
 */
class VirgoAPI extends WebAPI{

	/**
	 * Returns list of offers in given language, using filter, sorting and paging given in args object.
	 * @param RefreshEventArgs $args Lists of arguments used in query.
     * @param int $idLng Language Id. Default 1045.
	 * @return Offer[]
	 */
	public function GetOffers(RefreshEventArgs $args, $idLng = 1045){
		return Offers::GetOffers($args, $idLng);
	}

	/**
	 * Returns list of special offers (for first page) in given language, using filter, sorting and paging given in args object
	 * @param RefreshEventArgs $args Lists of arguments used in query.
     * @param int $idLng Language Id. Default 1045.
	 * @return Offer[]
	 */
	public function GetOffersForFirstPage(RefreshEventArgs $args, $idLng = 1045){
		$args->Filters["PierwszaStrona"] = 1;
		return Offers::GetOffers($args, $idLng);
	}

    /**
     * Get most popular offers
     * @param int $count Number of returned offers.
     * @param int $idLng Language Id. Default 1045.
     * @param array $filters Array with filters values used in query.
     * @return array
     */
    public function GetMostPopularOffers($count, $idLng = 1045, $filters = null){
        $args = new RefreshEventArgs($count, 0, $filters, "id_lng ASC, op.Value DESC");
		$args->Filters["IloscOdslonWWWSort"] = "True";
		return Offers::GetOffers($args, $idLng);
	}

	/**
	 * Returns a offer object from the database by ID.
	 * @param int $id Offer id.
     * @param int $idLng Language Id. Default 1045.
	 * @return Offer
	 */
	public function GetOffer($id, $idLng = 1045){
		return Offers::GetOffer($id, $idLng);
	}

	/**
	 * Returns a photo object from the database by ID.
	 * @param int $id OfferPhoto id.
	 * @return OfferPhoto
	 */
	public function GetOfferPhoto($id){
		return OfferPhotos::GetPhoto($id);
	}

	/**
	 * Returns a unique list of offer object.
	 * @return string[]
	 */
	public function GetObjects(){
		return Offers::GetObjects();
	}

	/**
	 * Returns a unique list of provinces used id offers.
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetProvinces($idLng = 1045){
		return Offers::GetProvinces($idLng);
	}

	/**
	 * Returns a unique list of districts used id offers.
	 * @param string $province Name of the province from which districts will be given. Default null.
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetDistricts($province = null, $idLng = 1045){
		return Offers::GetDistricts($province, $idLng);
	}

	/**
	 * Returns a unique list of locations used id offers.
	 * @param string[] $districts Array of districts to look for locations. Default null.
     * @param string $province Name of the province from which locations will be given. Default null.
     * @param int $idLng Language Id. Default 1045.
     * @param string $object Object's type from which locations will be given. Default null.
     * @param int $rent Offer's type of transaction from which locations will be given. Acceptable values 0/1. Default null.
	 * @return string[]
	 */
	public function GetLocations($districts = null, $province = null, $idLng = 1045, $object = null, $rent = null){
		return Offers::GetLocations($districts, $province, $idLng, $object, $rent);
	}

	/**
	 * Returns a unique list of quarters used in offers.
	 * @param string[] $locations Array of locations to look for quarters. Default null.
     * @param int $idLng Language Id. Default 1045.
     * @param string $object Type of object from which quarters will be given. Default null.
     * @param int $rent Offer's type of transaction from which quarters will be given. Acceptable values 0/1. Default null.
     * @param string[] $building_types Building's type from which quarters will be given. Acceptable values string[] or string with ',' delimiter
	 * @return string[]
	 */
	public function GetQuarters($locations = null, $idLng = 1045, $object = null, $rent = null, $building_types = null){
		return Offers::GetQuarters($locations, $idLng, $object, $rent, $building_types);
	}

	/**
	 * Returns a unique list of regions used in offers.
	 * @param string[] $quarters Array of quarters to look for regions
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetRegions($quarters = null, $idLng = 1045){
		return Offers::GetRegions($quarters, $idLng);
	}

	/**
	 * Returns a unique list of building types (used in flats).
     * @param int $idLng Language Id. Default 1045.
     * @param string $object Type of object from which building types will be given. Default null.
	 * @return string[]
	 */
	public function GetBuildingTypes($idLng = 1045, $object=null){
		return Offers::GetBuildingTypes($idLng, $object);
	}

	/**
	 * Returns a unique list of house types (used in houses).
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetHouseTypes($idLng = 1045){
		return Offers::GetHouseTypes($idLng);
	}

	/**
	* Returns a unique list of object types (used in objects).
	* @param int $idLng Language Id. Default 1045.
	* @return string[]
	*/
	public function GetObjectTypes($idLng = 1045){
		return Offers::GetObjectTypes($idLng);
	}

	/**
	 * Returns a unique list of parcels destiny (used in parcels).
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetFieldDestiny($idLng = 1045){
		return Offers::GetFieldDestiny($idLng);
	}

	/**
	* Returns a unique list of hall destiny (used in halls).
	* @param int $idLng Language Id. Default 1045.
	* @return string[]
	*/
	public function GetHallDestiny($idLng = 1045){
		return Offers::GetHallDestiny($idLng);
	}

	/**
	 * Returns a unique list of premises destiny (used in premises).
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetPremisesDestiny($idLng = 1045){
		return Offers::GetPremisesDestiny($idLng);
	}

    /**
	 * Returns a unique list of ownerships status.
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetOwnershipsStatus($idLng = 1045){
		return Offers::GetOwnershipsStatus($idLng);
	}

    /**
	 * Returns a unique list of legal status.
     * @param int $idLng Language Id. Default 1045.
	 * @return string[]
	 */
	public function GetLegalStatus($idLng = 1045){
		return Offers::GetLegalStatus($idLng);
	}

	/**
	 * Return list of investments in given language, using filter, sorting and paging given in args object.
	 * @param RefreshEventArgs $args Lists of arguments used in query.
     * @param $lng Language Id. Default 1045.
	 * @return Investment[]
	 */
	public function GetInvestments(RefreshEventArgs $args, $lng = 1045){
		return Investments::GetInvestments($args, $lng);
	}

	/**
	 * Returns a investment object from the database by ID.
	 * @param $id Investments Id.
	 * @return Investment
	 */
	public function GetInvestment($id){
		return Investments::GetInvestment($id);
	}

	/**
	 * Returns a investment building object from the database by ID.
	 * @param $id InvestmentsBuilding Id.
	 * @return InvestmentBuilding
	 */
	public function GetInvestmentBuilding($id){
		return InvestmentBuildings::GetInvestmentBuilding($id);
	}

	/**
	 * Returns a unique list of provinces used id investments.
	 * @return string[]
	 */
	public function GetInvestmentsProvinces($idLng = 1045){
		return Investments::GetProvinces($idLng);
	}

	/**
	 * Returns a unique list of districts used id investments.
	 * @param string $province Name of province to look for districts. Default null.
	 * @return string[]
	 */
	public function GetInvestmentsDistricts($province = null, $idLng = 1045){
		return Investments::GetDistricts($province, $idLng);
	}

	/**
	 * Returns a unique list of locations used id investments.
	 * @param $districts Array of districts to look for locations. Default null.
	 * @return string[]
	 */
	public function GetInvestmentsLocations($districts = null, $idLng = 1045){
		return Investments::GetLocations($districts, $idLng);
	}

	/**
	 * Returns a unique list of quarters used in investments.
	 * @param $locations Array of locations to look for quarters. Default null.
	 * @return string[]
	 */
	public function GetInvestmentsQuarters($locations = null, $idLng = 1045){
		return Investments::GetQuarters($locations, $idLng);
	}

	/**
	 * Returns a unique list of regions used in investments.
	 * @param $quarters Array of quarters to look for regions. Default null.
	 * @return string[]
	 */
	public function GetInvestmentsRegions($quarters = null, $idLng = 1045){
		return Investments::GetRegions($quarters, $idLng);
	}

	/**
	 * Returns a unique list of categories used in investments.
	 * @return string[]
	 */
	public function GetInvestmentsCategories($idLng = 1045){
		return Investments::GetCategories($idLng);
	}

	/**
	 * Returns JavaScript code that start the synchronization of the database, if UseSajaxToSynchronize option is enabled.
	 * If not, immediately proceeds the synchronization of database.
	 * @return string
	 */
	public function GetSynchronizeJS(){
		$date = $this->LoadParam(VirgoAPI::PARAM_SYNCHRONIZATION_TIME, time());
		if(time() - $date > Config::$DataSynchronizationInterval){
			if(Config::$UseSajaxToSynchronize){
				$str = '<script type="text/javascript">SynchronizeDB();</script>';
				return $str;
			}else{
				$this->SynchronizeDB();
				return "";
			}
		}
	}

    public function GetSynchronizeOffersCount(){
        $date = $this->LoadParam(VirgoAPI::PARAM_SYNCH_OFFERS_COUNT_TIME, time());
        if(time() - $date > Config::$DataSyncOffersCountInterval){
            if(Config::$UseSajaxToSynchronize){
				$str = '<script type="text/javascript">SynchronizeOffersCount();</script>';
				return $str;
			}else{
				$this->SynchronizeOffersCount();
				return "";
			}
        }
    }

    public function SynchronizeOffersCount($skipTimeStamp = false){
        $xmlstr="";
        if($this->LoadParam(VirgoAPI::PARAM_ENABLED_SYNCH_OFFERS_COUNT)=="1"){
            $date = $this->LoadParam(VirgoAPI::PARAM_SYNCH_OFFERS_COUNT_TIME, time());
            if((time() - $date > Config::$DataSyncOffersCountInterval) || $skipTimeStamp){
                $this->SaveParam(VirgoAPI::PARAM_SYNCH_OFFERS_COUNT_TIME, time());

                $oferty = Offers::GetCountedOffers();
                $xmlstr="<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?><dane>";
                foreach($oferty as $key=>$val){
                    $xmlstr.="<province name=\"".$key."\">";
                    foreach($val as $key2=>$val2){
                        if($key2=="0"){$xmlstr.="<sell>";}else{$xmlstr.="<rent>";}
                        foreach($val2 as $key3=>$val3){
                            $xmlstr.="<".$key3.">".$val3."</".$key3.">";
                        }
                        if($key2=="0"){$xmlstr.="</sell>";}else{$xmlstr.="</rent>";}
                    }
                    $xmlstr.="</province>";
                }
                $xmlstr.="</dane>";
                $fh = fopen("./offers_count.xml", "w+");
                fputs ($fh, $xmlstr);
                fclose ($fh);
            }
        }
        return $xmlstr;
    }

	/**
	 * Starts synchronization process of the database.
     * @param bool $skipTimeStamp Disables time stamp validation.
	 * @return string
	 */
	public function SynchronizeDB($skipTimeStamp = false){
		if(WebServiceVirgo::WS()){
			try{
				Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - SYNCHRONIZATION START...');
				$date = $this->LoadParam(VirgoAPI::PARAM_SYNCHRONIZATION_TIME, time());
				if((time() - $date > Config::$DataSynchronizationInterval) || $skipTimeStamp){
					$this->SaveParam(VirgoAPI::PARAM_SYNCHRONIZATION_TIME, time());
					$cwd = getcwd();
					chdir(VIRGO_API_DIR);
					WebServiceVirgo::WS()->LoginEx();
					//wyslanie odslon ofert

					WebServiceVirgo::WS()->NoteOffersViews(Offers::GetOffersViews());

                    //$this->ClearOffersVirtualCache();

					$log = "";
					if (isset(Config::$Moduly) && isset(Config::$Moduly["web_api"]) && Config::$Moduly["web_api"] === true) {
						$serwis = $this->GetSerwis();
						if ($serwis){
							$lngId = $serwis->GetIdJezyk();
						} else {
							$lngId = 1045;
						}
					} else {
						$lngId = Config::$defaultLanguageId;
					}

					$count = WebServiceVirgo::WS()->GetOffers($log, $lngId);
					Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - step 1');

					$count2 = WebServiceVirgo::WS()->GetInvestments();
					Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - step 2');

					//pobranie listy aktualnych ofert
					//weryfikacja ofert czy istnieja jesli nie to usuwa z bazy
					$xmlOffersLoaded = $this->GetOffersList(true);
					if($xmlOffersLoaded !== true) return 'VirgoAPI:SynchronizeDB: XML Error - VirgoAPI::GetOffersList(true)';

					Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - step 3');

					$missingIds = $xmlOffersLoaded ? Offers::VerifyOffers() : array();
					Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - step 4');

					if(count($missingIds) > 0){
						if(!file_exists("logs")) mkdir ("logs");
						$d = date("Y_m_d_H_i_s");
						$logFileHandle = fopen("logs/".$d."_log.txt", "w");
						fwrite($logFileHandle, $log);
						fclose($logFileHandle);
						copy("tmp.xml", "logs/".$d."_tmp.xml");
						WebServiceVirgo::WS()->SetMissingOffers($missingIds);
					}
					Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - step 5');

					if(Config::$UsePropertiesDiskCache) $this->ClearPropertiesCache();

					WebServiceVirgo::WS()->Logout();
					chdir($cwd);
					return "Ofs: $count, Invs: $count2";
				}else
					return "-";
			}catch(Exception $ex){
				Errors::LogError("VirgoAPI:SynchronizeDB", $ex->getMessage());
				return "ERROR";
			}

			Errors::LogSynchroStep('VirgoApi - SynchronizeDB() - SYNCHRONIZATION DONE!');
		}
		else return 'Error: WebService not available';
	}


    /**
     * Create cache file with all properties.
     */
    public function ClearPropertiesCache(){
        $buf = serialize(Properties::GetProperties());
        $cwd = getcwd();
        $h = fopen("properties_cache.bin", "w");
        fwrite($h, $buf);
        fclose($h);
        chdir($cwd);
    }

    /**
     * Clear offers virtual cache file
     */
    public function ClearOffersVirtualCache(){
        $cwd = getcwd();
        $h = fopen("offers_virtual.bin", "w");
        fwrite($h, 0);
        fclose($h);
        chdir($cwd);
    }

	/**
	 * Reset offer on VIRGO server.
	 * @return string
	 */
	public function Reset(){
		try{
			WebServiceVirgo::WS()->LoginEx();
			$ret = WebServiceVirgo::WS()->Reset();
			WebServiceVirgo::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("VirgoAPI:Reset", $ex->getMessage());
			return "ERROR";
		}
	}

	/**
	 * Get list of actual offer on web site.
     * @param bool $skipLogin Skips WS login procedure.
	 * @return string
	 */
	public function GetOffersList($skipLogin = false){
		try{
			if(!$skipLogin) WebServiceVirgo::WS()->LoginEx();
			$ret = WebServiceVirgo::WS()->GetOffersList();
			if(!$skipLogin) WebServiceVirgo::WS()->Logout();

			Errors::LogSynchroStep('VirgoApi - GetOffersList() - done');

			return $ret;
		}catch(Exception $ex){
			Errors::LogError("VirgoAPI:GetOfferList", $ex->getMessage());
			return "ERROR";
		}
	}

    /**
     * Return full address of contact form.
     * @return string
     */
    public function GetContactFormAddress(){
        return $this->GetApplicationAddress() . "/virFormKontakt.aspx?key=" . Config::$WebKey;
    }

    /**
     * Return full address of new offer form.
     * @return string
     */
    public function GetNewOfferFormAddress(){
        return $this->GetApplicationAddress() . "/virFormZglos.aspx?key=" . Config::$WebKey;
    }

    /**
     * Return full address of new search form.
     * @return string
     */
    public function GetNewSearchFormAddress(){
        return $this->GetApplicationAddress() . "/virFormPowiadom.aspx?key=" . Config::$WebKey;
    }

    /**
     * Return full address of contact to offer form.
     * @param int $offerId Offers Id.
     * @return string
     */
    public function GetContactPerOfferFormAddress($offerId){
        return $this->GetApplicationAddress() . "/virFormPodOferta.aspx?key=" . Config::$WebKey . "&ofeid=" . $offerId;
    }

    /**
     * Starts site synchronization process.
     * @return string
     */
    public function SynchronizeSite(){
		$msg = parent::SynchronizeSite();
        return $msg;
	}

    /**
     * Clear photos from given (by id) offer
     * @param int $ideofe. Offers Id.
     * @return string
     */
    public function ClearPhotos($ideofe) {
        try{
            $folder = "";
            if ($ideofe > 0) {
                $suf = $ideofe < 100 ? $ideofe : substr($ideofe, 0, 2);
                $folder .= "/ofs_".$suf."/offer_".$ideofe;
            }
            $path = getcwd() . "/photos".$folder;
            if(file_exists($path)){
                $this->rrmdir($path);
            }
		}catch(Exception $ex){
			Errors::LogError("VirgoAPI:ClearPhotos", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Adds search.
     * @param array $data Array with atributes of added search.
     * @return string
     */
    public function AddSearch($data){
    	try{
    		WebServiceVirgo::WS()->LoginEx();
    		$ret = WebServiceVirgo::WS()->AddSearch($data);
    		WebServiceVirgo::WS()->Logout();
    		return $ret;
    	}catch(Exception $ex){
    		Errors::LogError("VirgoAPI:AddSearch", $ex->getMessage());
    		return "ERROR";
    	}
    }

	/**
     * Adds offer
     * @param array $data Array with offer atributes.
     * @return string
     */
    public function AddOffer($data){
    	try{
    		WebServiceVirgo::WS()->LoginEx();
    		$ret = WebServiceVirgo::WS()->AddOffer($data);
    		WebServiceVirgo::WS()->Logout();
    		return $ret;
    	}catch(Exception $ex){
    		Errors::LogError("VirgoAPI:AddOffer", $ex->getMessage());
    		return "ERROR";
    	}
    }

    /**
     * Gets lists
     * @param type $idsArr Array of lists id.
     * @return string
     */
    public function GetLists($idsArr = null){
    	try{
    		WebServiceVirgo::WS()->LoginEx();
    		$ret = WebServiceVirgo::WS()->GetLists($idsArr);
    		WebServiceVirgo::WS()->Logout();
    		return $ret;
    	}catch(Exception $ex){
    		Errors::LogError("VirgoAPI:GetLists", $ex->getMessage());
    		return "ERROR";
    	}
    }

    /**
     * Add/edit seller.
     * @param array $dataArray. Array with seller atributes.
     * @return string
     */
    public function AddEditSeller(array $dataArray){
    	try{
    		WebServiceVirgo::WS()->LoginEx();
    		$ret = WebServiceVirgo::WS()->AddEditSeller($dataArray);
    		WebServiceVirgo::WS()->Logout();
    		return $ret;
    	}catch(Exception $ex){
    		Errors::LogError("VirgoAPI:AddEditSeller", $ex->getMessage());
    		return "ERROR";
    	}
    }

    /**
     * Import locations
     * @param int $type. Type of returned locations. Acceptable values 1,2,3,4. Default false.
     * @return string
     */
    public function GetLocationsAll($type = false){
    	try{
    		WebServiceVirgo::WS()->LoginEx();
    		$ret = WebServiceVirgo::WS()->GetLocationsAll($type);
    		WebServiceVirgo::WS()->Logout();
    		return $ret;
    	}catch(Exception $ex){
    		Errors::LogError("VirgoAPI:GetLocationsAll", $ex->getMessage());
    		return "ERROR";
    	}
    }

    /**
     * Cascade delete files and directories
     * @param string $dir Directory path.
     */
    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Returns an array of search Statistics with given params
     * @param type $filters
     * @return array
     */
    public function getStatisticsGrouped($filters){
        return Statistics::getStatisticsGrouped($filters);
    }

    public function deleteOldStatistics(){
        return Statistics::deleteOldStatistics();
    }

}
