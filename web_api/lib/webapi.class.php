<?php

/**
 * Class providing acces to offers, like geting them to view on the page.
 * @author Jakub Konieczka
 *
 */
class WebAPI{

	const PARAM_SYNCHRONIZATION_TIME = "SYNCHRONIZATION_TIME";
    const PARAM_SYNCH_OFFERS_COUNT_TIME = "PARAM_SYNCH_OFFERS_COUNT_TIME";
    const PARAM_ENABLED_SYNCH_OFFERS_COUNT = "PARAM_ENABLED_SYNCH_OFFERS_COUNT";
	const PARAM_HAS_NEWSLETTER = "HAS_NEWSLETTER";
    const PARAM_ARCHIVE_SOLD = "ARCHIVE_SOLD";
    const PARAM_ARCHIVE_CONTRACT = "ARCHIVE_CONTRACT";
    const PARAM_ARCHIVE_SOLD_CONTRACT = "ARCHIVE_SOLD_CONTRACT";
    const PARAM_PAGE_VISIT_COUNT = "PAGE_VISIT_COUNT";
    const SITE_ELEMENT_SERWIS = 1;
    const SITE_ELEMENT_MIEJSCE_SERWISU = 2;
    const SITE_ELEMENT_MIEJSCE_MENU = 3;
    const SITE_ELEMENT_MIEJSCE_GRUPY = 4;
    const SITE_ELEMENT_MENU = 5;
    const SITE_ELEMENT_ARTYKUL = 6;
    const SITE_ELEMENT_ARKUSZ_CSS = 7;
    const SITE_ELEMENT_ARKUSZ_JS = 8;
    const SITE_ELEMENT_BANER = 9;
    const SITE_ELEMENT_GRAFIKA = 10;
    const SITE_ELEMENT_GALERIA = 11;
    const SITE_ELEMENT_GALERIA_POZYCJA = 12;
    const SITE_ELEMENT_OSOBA = 13;

    private static $_Params = null;

    /**
     * Checks if Api has CMS extensions
     * @return boolean
     */
    private function checkwebext(){
        if((isset(Config::$Version) && Config::$Version == Config::VERSION_EXTENDED) || (isset(Config::$Moduly["web_api"]) && Config::$Moduly["web_api"]==true)) return true;
        return false;
    }

    /**
     * Saves serwis params to database
     * @param string $key
     * @param string $value
     */
	public function SaveParam($key, $value){

		$db = DataBase::GetDbInstance();
		$query = "SELECT COUNT(*) AS cnt FROM #S#settings WHERE key_name=?";
		$result = $db->ExecuteQueryWithParams($query, array($key));
		if($result){

			$row = $db->FetchArray($result);
			if($row['cnt'] == 0){
				$query = "INSERT INTO #S#settings VALUES(?, ?)";
				$result = $db->ExecuteQueryWithParams($query, array($key, $value));
			}else{
				$query = "UPDATE #S#settings SET value=? WHERE key_name=?";
				$result = $db->ExecuteQueryWithParams($query, array($value, $key));
			}
		}
	}

    /**
     * Returns application address, eg: http://demo.galapp.net/Moduly/Virgo
     * @return string
     */
    protected function GetApplicationAddress(){
        $ndx = strrpos(Config::$WebServiceUrl."/Moduly/Virgo/virWsOfertyAPI.asmx", "/");
        return substr(Config::$WebServiceUrl."/Moduly/Virgo/virWsOfertyAPI.asmx", 0, $ndx);
    }

	/**
	 * Returns a agent object from the database by ID.
	 * @param $id
	 * @return Agent
	 */
	public function GetAgent($id){
		$ags = new Agents();
		return $ags->GetAgent($id);
	}

    /**
     * Gets serwis param from database
     * @param string $key
     * @param string $defValue
     */
	public function LoadParam($key, $defValue = null){
		$db = DataBase::GetDbInstance();
		$query = "SELECT value FROM #S#settings WHERE key_name=?";
		$result = $db->ExecuteQueryWithParams($query, array($key));
		if($result){
			$row = $db->FetchArray($result);
			return $row ? $row[0] : null;
		}else{
			return $defValue;
		}
	}

    /**
     * Gets serwis params from database
     * @param string $key
     * @param object $defValue
     * @return array[]
     */
    public static function Params($key, $defValue = null){
        if(self::$_Params == null){
            self::$_Params = array();
            $db = DataBase::GetDbInstance();
            $query = "SELECT key_name, value FROM #S#settings";
            $result = $db->ExecuteQuery($query);
            if($result){
                while($row = $db->FetchArray($result)){
                    self::$_Params[$row['key_name']] = $row['value'];
                }
            }
        }
        if(array_key_exists($key, self::$_Params)) return self::$_Params[$key];
        else return $defValue;
    }

    /**
     * Returns unique list of used languages in offers or definied in service.
     * @return Language[]
     */
    public function GetAvailableLanguages(){
        if (isset(Config::$Moduly) && isset(Config::$Moduly["web_api"]) && Config::$Moduly["web_api"] === true && $this->checkwebext()){
            return Serwisy::GetAvailableLanguages();
        } else {
            return Offers::GetAvailableLanguages();
        }
    }

    /**
     * Add given email to newsletter.
     * @param string $mail
     * @return string
     */
    public function AddMailToNewsLetter($mail){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->AddMailToNewsLetter($mail);
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:AddMailToNewsLetter", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Check if this has definied any newsletters.
     * @return int
     */
    public function HasNewsLetter(){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->HasNewsLetter();
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:HasNewsLetter", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Remove given email from newsletter.
     * @param string $mail
     * @return string
     */
    public function RemoveMailFromNewsLetter($mail){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->RemoveMailFromNewsLetter($mail);
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:RemoveMailFromNewsLetter", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Confirm given email to add to newsletter.
     * @param int $id
     * @param string $hash
     * @return string
     */
    public function ConfirmNewsLetterMail($id, $hash){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->ConfirmNewsLetterMail($id, $hash);
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:ConfirmNewsLetterMail", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Starts site synchronization process.
     * @return string
     */
    public function SynchronizeSite(){
        if(!$this->checkwebext()) return "Function unavailable in this version.";
		try{
			if(WebServiceWeb::WS())
			{
                echo"1";
				WebServiceWeb::WS()->LoginEx();
                echo"2";
                $ret1 = WebServiceWeb::WS()->GetService();
                echo"3";
				$ret2 = $ret3  = $ret4 = $ret5 = $ret6 = $ret7 = $ret8 = $ret9 = $ret10 = $ret11 = $ret12 = $ile = 0;

				if($ret1 != 0)
				{
					Miejsca::DeleteMiejsce(0, 0);
					$ret2 = WebServiceWeb::WS()->GetMiejsca();

					Menus::DeleteMenu(0);
					$ret3 = WebServiceWeb::WS()->GetMenu();

					Artykuly::DeleteArtykul(0);
					$ret4 = WebServiceWeb::WS()->GetArtykuly();

					ArkuszeSkrypty::DeleteArkuszSkrypt(0, 0);
					$ret5 = WebServiceWeb::WS()->GetArkuszeSkrypty();

					Banery::DeleteBaner(0);
					$ret6 = WebServiceWeb::WS()->GetBanery();

					Opcje::DeleteOpcja(null);
					$ret7 = WebServiceWeb::WS()->GetOpcje();

					JezykiTeksty::DeleteJezyk(null);
					$ret8 = WebServiceWeb::WS()->GetJezyki();

					$ret9 = WebServiceWeb::WS()->GetGalerie();

					Agents::DeleteAgent(0);
					$ret10= WebServiceWeb::WS()->GetAgenci();

					Departments::DeleteDepartment(0);
					$ret11= WebServiceWeb::WS()->GetOddzialy();
                    Osoby::DeleteOsoba(null);
                    $ret12 = WebServiceWeb::WS()->GetOsoby();

					$ile = WebServiceWeb::WS()->HasNewsLetter();
					$this->SaveParam(WebAPI::PARAM_HAS_NEWSLETTER, $ile > 0 ? 1 : 0);

                    GaleriePozycje::IndeksujGaleriePozycjeDlaArtykulow();

					if(Config::$UseOptionsDiskCache) $this->ClearOptionsCache();
					if(Config::$UseLanguageDiskCache) $this->ClearLanguageCache();

				}

				WebServiceWeb::WS()->Logout();
                $s = $this->GetSerwis(Config::$WebGID);
                if ($s->GetParametr('pokazujWaluty', false)!="" && $s->GetParametr('pokazujWaluty', false)!="false"){
                    $this->DownloadCurrency();
                }


				return "Serwisy: $ret1, Miejsca: $ret2, Menu: $ret3, Artykuly: $ret4, Arkusze/JS: $ret5, Banery: $ret6, Opcje: $ret7, Jezyki: $ret8, Galerie: $ret9, NewsL: $ile, Agenci: $ret10, Oddzialy: $ret11, Osoby: $ret12";
			}
			else return 'Error: WebServiceWeb not available';

		}catch(Exception $ex){
			Errors::LogError("WebAPI:SynchronizeSite", $ex->getMessage());
			return "ERROR";
		}
	}

    /**
     * Starts graphics synchronization process.
     * @return string
     */
    public function SynchronizeGraphics(){
        if(!$this->checkwebext()) return "Function unavailable in this version.";
		try{
			WebServiceWeb::WS()->LoginEx();

            $ret = WebServiceWeb::WS()->GetGrafiki();

            WebServiceWeb::WS()->Logout();
            return "Grafiki: $ret";
		}catch(Exception $ex){
			Errors::LogError("WebAPI:SynchronizeGraphics", $ex->getMessage());
			return "ERROR";
		}
	}

    /**
     * Starts galerie synchronization process.
     * @return string
     */
    public function SynchronizeGalerie(){
        if(!$this->checkwebext()) return "Function unavailable in this version.";
		try{
			WebServiceWeb::WS()->LoginEx();
            $gps = Galerie::PobierzGalerieJezyki();
            $ret = 0;

            foreach ($gps as $gp) {
                $ret += WebServiceWeb::WS()->GetGaleriePozycje($gp->GetGID());
            }

            WebServiceWeb::WS()->Logout();
            return "GaleriePozycje: $ret";
		}catch(Exception $ex){
			Errors::LogError("WebAPI:SynchronizeGalerie", $ex->getMessage());
			return "ERROR";
		}
	}

    /**
     * Synchronize passed element.
     * @param int $element
     * @param string $gid
     * @param boolean $del
     * @param boolean $force
     * @return string
     */
    public function SynchronizeSiteElement($element, $gid, $del, $force){
        if(!$this->checkwebext()) return "Function unavailable in this version.";
		try{
            if($element < 1 || $element > 12) return "Invalid element: $element";
            $ret = 0;
			WebServiceWeb::WS()->LoginEx();
            switch ($element) {
                case self::SITE_ELEMENT_SERWIS: if($del) Serwisy::DeleteSerwis($gid); else $ret = WebServiceWeb::WS()->GetService(); break;
                case self::SITE_ELEMENT_MIEJSCE_SERWISU: if($del) Miejsca::DeleteMiejsce($gid, Miejsca::MIEJSCE_RODZAJ_SERWISU); else $ret = WebServiceWeb::WS()->GetMiejsca(Miejsca::MIEJSCE_RODZAJ_SERWISU, $gid); break;
                case self::SITE_ELEMENT_MIEJSCE_MENU: if($del) Miejsca::DeleteMiejsce($gid, Miejsca::MIEJSCE_RODZAJ_MENU); else $ret = WebServiceWeb::WS()->GetMiejsca(Miejsca::MIEJSCE_RODZAJ_MENU, $gid); break;
                case self::SITE_ELEMENT_MIEJSCE_GRUPY: if($del) Miejsca::DeleteMiejsce($gid, Miejsca::MIEJSCE_RODZAJ_GRUPY); else $ret = WebServiceWeb::WS()->GetMiejsca(Miejsca::MIEJSCE_RODZAJ_GRUPY, $gid); break;
                case self::SITE_ELEMENT_MENU: if($del) Menus::DeleteMenu($gid); else $ret = WebServiceWeb::WS()->GetMenu($gid); break;
                case self::SITE_ELEMENT_ARTYKUL:
                    if($del) {
                        Artykuly::DeleteArtykul($gid);
                    } else {
                        $ret = WebServiceWeb::WS()->GetArtykuly($gid);
                        GaleriePozycje::IndeksujGaleriePozycjeDlaArtykulu($gid);
                    }
                    break;
                case self::SITE_ELEMENT_ARKUSZ_CSS: if($del) ArkuszeSkrypty::DeleteArkuszSkrypt($gid, ArkuszeSkrypty::ARKUSZ_RODZAJ_CSS); else $ret = WebServiceWeb::WS()->GetArkuszeSkrypty(ArkuszeSkrypty::ARKUSZ_RODZAJ_CSS, $gid); break;
                case self::SITE_ELEMENT_ARKUSZ_JS: if($del) ArkuszeSkrypty::DeleteArkuszSkrypt($gid, ArkuszeSkrypty::ARKUSZ_RODZAJ_JS); else $ret = WebServiceWeb::WS()->GetArkuszeSkrypty(ArkuszeSkrypty::ARKUSZ_RODZAJ_JS, $gid); break;
                case self::SITE_ELEMENT_BANER: if($del) Banery::DeleteBaner($gid); else $ret = WebServiceWeb::WS()->GetBanery($gid); break;
                case self::SITE_ELEMENT_GRAFIKA: $pfn = base64_decode($gid); if($del) Grafiki::DeleteGrafike($gid); else if($force) Grafiki::DeleteGrafike($gid); $ret = WebServiceWeb::WS()->GetGrafiki($gid); break;
                case self::SITE_ELEMENT_GALERIA:
                    if($del) {
                        Galerie::DeleteGaleria($gid);
                    } else {
                        $ret = WebServiceWeb::WS()->GetGalerie($gid);
                        GaleriePozycje::IndeksujGaleriePozycjeDlaGalerii($gid);
                    }
                    break;
                case self::SITE_ELEMENT_GALERIA_POZYCJA: if($del) GaleriePozycje::DeleteGaleriaPozycja($gid); break;
                case self::SITE_ELEMENT_OSOBA: if($del) Osoby::DeleteOsoba($gid); else $ret = WebServiceWeb::WS()->GetOsoby($gid); break;
                default: break;
            }
            WebServiceWeb::WS()->Logout();
            return "Element: $element, GID: $gid, ret: $ret";
		}catch(Exception $ex){
			Errors::LogError("WebAPI:SynchronizeSiteElement", $ex->getMessage());
			return "ERROR";
		}
	}

    /**
     * Download exchange rates from NBP.
     */
    public function DownloadCurrency(){
        Waluty::PobierzZNbp();
    }

    /**
     * Create cache file with all options.
     */
    public function ClearOptionsCache(){
        $buf = serialize(Opcje::GetOpcje());
        $cwd = getcwd();
        chdir(WEB_API_DIR);
        $h = fopen("opcje_cache.bin", "w");
        fwrite($h, $buf);
        fclose($h);
        chdir($cwd);
    }

    /**
     * Create cache file with all language text.
     */
    public function ClearLanguageCache(){
        $buf = serialize(JezykiTeksty::GetJezyki());
        $cwd = getcwd();
        chdir(WEB_API_DIR);
        $h = fopen("jezyki_cache.bin", "w");
        fwrite($h, $buf);
        fclose($h);
        chdir($cwd);
    }

    /**
     * Returns default service, from config.php ($WebGID).
     * @return Serwis
     */
    public function GetSerwis() {
        return $s = Serwisy::GetSerwis(Config::$WebGID);
    }

    /**
     * Return path to image with agent or department in given size.
     * @param int $objId
     * @param string $customSize
     * @param string $photoType
     * @param string $version
     * @return string
     */
    public function GetAgentDepartmentPhoto($objId, $customSize, $photoType, $version){
		//testing size params
        if(!preg_match("/0*[1-9][0-9]*_0*[1-9][0-9]*/", $customSize)) return "";

		$path = getcwd() . "/photos";
		if (!file_exists($path)) {mkdir($path);}
		$path = getcwd() . "/photos/other";
		if (!file_exists($path)) {mkdir($path);}

		$fileName = "/".$photoType."_".$objId."_".$customSize."_".$version."_.jpg";
		$path .= $fileName;
		if (!file_exists($path)){
			//get image from server
			WebServiceWeb::WS()->LoginEx(true);
            $buf = WebServiceWeb::WS()->GetPhoto($objId, $customSize, $photoType);
			if ($buf != null) {
				$file = fopen($path, "wb");
				fwrite($file, $buf);
				fclose($file);
			}
		}
		$path = Config::$AppPath . "/photos/other" . $fileName;
		return $path;
	}

    /**
     * Zwraca ściężkę do pliku grafiki, jak plik nie istnieje zwraca FALSE.
     * @param string $fileName
     * @return string
     */
    public function GetGrafikaPath($fileName){
        $dir = Grafiki::GetPath();
        $localFileName = $dir . "/" . $fileName;
        if(file_exists($localFileName))
            return $localFileName;
        else
            return false;
    }

    /**
     * Zwraca tablicę byte z plikiem PDF.
     * @param string $url
     * @return array byte
     */
    public function GetPdfFromUrl($url){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->GetPdfFromUrl($url);
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:GetPdfFromUrl", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Return page visit counter value
     * @return int
     */
    public function readPageVisitCounter(){
        $count=0;
        if($this->LoadParam(WebAPI::PARAM_PAGE_VISIT_COUNT,false)===false) $count = 0;
        else $count=$this->LoadParam(WebAPI::PARAM_PAGE_VISIT_COUNT);
        return (int) $count;
    }

    /**
     * Set page visit counter value
     * @param int $value
     */
    public function setPageVisitCounter($value=0){
        $this->SaveParam(WebAPI::PARAM_PAGE_VISIT_COUNT, $value);
    }

    /**
     * Increase page visit counter value by 1
     */
    public function increasePageVisitCounter(){
        if($this->LoadParam(WebAPI::PARAM_PAGE_VISIT_COUNT,false)!==false){
            $count = (int) $this->LoadParam(WebAPI::PARAM_PAGE_VISIT_COUNT);
            $count+=1;
            $this->SaveParam(WebAPI::PARAM_PAGE_VISIT_COUNT, $count);
        }
    }


	/**
     * Check if this has definied any newsletters.
     * @return int
     */
    public function IsSmsGatewayActive(){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->IsSmsGatewayActive();
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:IsSmsGatewayActive", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Sends sms.
     * @param string $tresc
     * @param string $numer
     * @return string
     */
    public function SendSms($tresc, $numer){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->SendSms($tresc, $numer);
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:SendSms", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Sends email.
     * @param string email
     * @param string szablon
     * @return string
     */
    public function SendEmail($email, $szablon){
        try{
			WebServiceWeb::WS()->LoginEx();
			$ret = WebServiceWeb::WS()->SendEmail($email, $szablon);
			WebServiceWeb::WS()->Logout();
			return $ret;
		}catch(Exception $ex){
			Errors::LogError("WebAPI:SendEmail", $ex->getMessage());
			return "ERROR";
		}
    }

    /**
     * Clear photos from given (by id) agent or department
     * @param int $idusr. Agents Id.
     * @param int $idodd. Departments Id.
     * @return string
     */
    public function ClearWebPhotos($idusr = 0, $idodd = 0) {
        try{
            $path = getcwd() . "/photos/other/";
            if($idusr > 0){
                $src = $path."3_".$idusr."_*.jpg";
            }elseif($idodd > 0 ){
                $src = $path."1_".$idodd."_*.jpg";
            }
            var_dump($src);
            foreach (glob($src) as $filename){
                unlink($filename);
            }
		}catch(Exception $ex){
			Errors::LogError("VirgoAPI:ClearPhotos", $ex->getMessage());
			return "ERROR";
		}
    }
}

?>
