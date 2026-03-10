<?php

/**
 * Supports connection to WebService, synchronize database.
 * @author marcinw
 *
 */
class WebServiceWeb{


	protected static $_instance = null;
	protected $_sc;
	protected $_sid = "";
        protected $_DEBUG = false;
        const CFG_FILE = "config.cfg";

	/**
	 * Return the WebService object.
	 * @return WebService
	 */
	public static function WS(){
		if(self::$_instance == null){
			self::$_instance = new WebServiceWeb();
        }
        if(empty(self::$_instance->getSC())){
            echo 'web serwice nie odpowiada';
            return null;
        }

        return self::$_instance;
	}

	/**
	 * Create new WebService object.
	 */
	public function __construct(){

        //var_dump(phpinfo());die();

		$useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

		$url = Config::$WebServiceUrl."/Moduly/Virgo/virWsOfertyAPI.asmx?WSDL";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		$head = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);


        //var_dump($httpCode);
        //die();
		if($httpCode != 302 && $httpCode != 0){ //
			try{
				$this->_sc = new soapclient(Config::$WebServiceUrl."/Moduly/Virgo/virWsOfertyAPI.asmx?WSDL");

			}catch (Exception $ex) {
				Errors::LogError("WebService:__construct", $ex->getMessage());
			}
		} else {
            $this->_sc = null;
        }
	}

	/*
	 * Get private _sc
	 */

	public function getSC(){
		return $this->_sc;
	}

	/**
	 * WebService logging in, using a defined key.
	 */
	public function Login($preserveMultiLogin = false){
        //var_dump($preserveMultiLogin);
        //die();
		if(!$this->WS() || empty($this->WS()->getSC())) return null; //

		try{
			if($preserveMultiLogin && $this->_sid != "") return;
			$params = array('key'=>Config::$WebKey);
			$result = $this->WS()->getSC()->__soapCall("Login", array($params));
			if($result->LoginResult->Status == 0)
				$this->_sid = $result->LoginResult->Sid;
            else{
				Errors::LogError("WebService:Login", "Login response: " . $result->LoginResult->Message);
                //throw new Exception($result->LoginResult->Message);
			}
		}catch (Exception $ex) {
			Errors::LogError("WebService:Login", $ex->getMessage());
            //throw new Exception($ex->getMessage());
		}
	}

    /**
	 * WebService logging in, using a defined key.
	 */
	public function LoginEx($preserveMultiLogin = false){

        //var_dump($this->WS()); die();
        if(isset(Config::$LoginLocal) && Config::$LoginLocal == true) {
			$this->Login($preserveMultiLogin);
			return true;
		}


		if(!$this->WS()) return null;
		try{
			if($preserveMultiLogin && $this->_sid != "") return;
            $gal_app=preg_replace("/https?\:\/\//i", "", Config::$GalAppDomain);
			$params = array('key'=>Config::$WebKey, 'app'=>$gal_app);
			$result = $this->WS()->getSC()->__soapCall("LoginEx", array($params));
			if($result->LoginExResult->Status == 0)
				$this->_sid = $result->LoginExResult->Sid;
            else{
				Errors::LogError("WebService:Login", "LoginEx response: " . $result->LoginExResult->Message);
                //throw new Exception($result->LoginResult->Message);
			}
		}catch (Exception $ex) {
			Errors::LogError("WebService:LoginEx", $ex->getMessage());
            //throw new Exception($ex->getMessage());
		}
	}

	/**
	 * WebService logging out.
	 */
	public function Logout(){
		if(!$this->WS()) return null;
		try{
			if($this->_sid == "") return;
			$params = array('sid'=>$this->_sid);
			$result = $this->WS()->getSC()->__soapCall("Logout", array($params));
			$this->_sid = "";
		}catch (Exception $ex) {
			Errors::LogError("WebService:Logout", $ex->getMessage());
		}
	}

    /**
     * Check if this has definied any newsletters.
     * @return int
     */
    public function HasNewsLetter(){
       if(!$this->WS()) return null;
	   try{
			$params = array('sid'=>$this->_sid);
			$result = $this->WS()->getSC()->__soapCall("HasNewsLetter", array($params));
			if($result->HasNewsLetterResult->Message <> "OK"){
				Errors::LogError("WebService:HasNewsLetter", "Response: " . $result->HasNewsLetterResult->Message);
			}
			return $result->HasNewsLetterResult->Status;
		}catch (Exception $ex) {
			Errors::LogError("WebService:HasNewsLetter", $ex->getMessage());
		}
    }

    /**
     * Add given email to newsletter.
     * @param string $mail
     * @return string
     */
    public function AddMailToNewsLetter($mail){
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'email'=>$mail);
			$result = $this->WS()->getSC()->__soapCall("AddMailToNewsLetter", array($params));
			if($result->AddMailToNewsLetterResult->Status <> 0){
				Errors::LogError("WebService:AddMailToNewsLetter", "Response: " . $result->AddMailToNewsLetterResult->Message);
			}
			return $result->AddMailToNewsLetterResult->Message;
		}catch (Exception $ex) {
			Errors::LogError("WebService:AddMailToNewsLetter", $ex->getMessage());
		}
    }

    /**
     * Remove given email from newsletter.
     * @param string $mail
     * @return string
     */
    public function RemoveMailFromNewsLetter($mail){
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'email'=>$mail);
			$result = $this->WS()->getSC()->__soapCall("RemoveMailFromNewsLetter", array($params));
			if($result->RemoveMailFromNewsLetterResult->Status <> 0){
				Errors::LogError("WebService:RemoveMailFromNewsLetter", "Response: " . $result->RemoveMailFromNewsLetterResult->Message);
			}
			return $result->RemoveMailFromNewsLetterResult->Message;
		}catch (Exception $ex) {
			Errors::LogError("WebService:RemoveMailFromNewsLetter", $ex->getMessage());
		}
    }

    /**
     * Confirm given email to add to newsletter.
     * @param int $id
     * @param string $hash
     * @return string
     */
    public function ConfirmNewsLetterMail($id, $hash){
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'id'=>$id, 'hash'=>$hash);
			$result = $this->WS()->getSC()->__soapCall("ConfirmNewsLetterMail", array($params));
			if($result->ConfirmNewsLetterMailResult->Status <> 0){
				Errors::LogError("WebService:ConfirmNewsLetterMail", "Response: " . $result->ConfirmNewsLetterMailResult->Message);
			}
			return $result->ConfirmNewsLetterMailResult->Message;
		}catch (Exception $ex) {
			Errors::LogError("WebService:ConfirmNewsLetterMail", $ex->getMessage());
		}
    }

    /**
     * Get a list of serwis to be added or remove. Write the serwis to database.
     * @return null|int
     */
    public function GetService() {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid);
            $result = $this->WS()->getSC()->__soapCall("GetSerwis", array($params));

			if($result->GetSerwisResult->Status <> 0){
				Errors::LogError("WebService:GetSerwis", "Response: " . $result->GetSerwisResult->Message);
                return 0;
			}
            Serwisy::DeleteSerwis(0);
            $xml = simplexml_load_string($result->GetSerwisResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $child){
                if($child->getName() == "wersja"){
                    //read serwis
                    $node = $child;
                    $mieszkania = $node->Mieszkania == "True" ? 1 : 0;
                    $domy = $node->Domy == "True" ? 1 : 0;
                    $dzialki = $node->Dzialki == "True" ? 1 : 0;
                    $lokale = $node->Lokale == "True" ? 1 : 0;
                    $hale = $node->Hale == "True" ? 1 : 0;
                    $gospodarstwa = $node->Gospodarstwa == "True" ? 1 : 0;
                    $kamienice = $node->Kamienice == "True" ? 1 : 0;
                    $biurowce = $node->Biurowce == "True" ? 1 : 0;

                    $serwis = new Serwis($node["GID"], $node["jezyk"],  $node->NazwaFirmy, $node->AdresWWW, $node->EmailKontaktowy, $node->StartowyJezyk,
                        $node["oddzial"], $node["uzytkownik"], $mieszkania, $domy, $dzialki, $lokale, $hale, $gospodarstwa, $kamienice,
                        $biurowce, $node->RodzajeOfert, $node->TagTitle, $node->TagKeywords, $node->TagDescription, $node->Head, $node->Body, $node->Foot);
                    Serwisy::AddEditSerwis($serwis);
                    echo DataBase::GetDbInstance()->LastError();
                    $cnt++;
                }else if($child->getName() == "Oddzial"){
                    //read departments
                    $node = $child;
                    $dep = new Department($node["ID"], $node->Nazwa, $node->Nazwa2, $node->Adres, $node->Miasto, $node->Kod, $node->Nip, $node->Wojewodztwo, $node->Www, $node->Telefon, $node->Email, $node->Fax, $node->Uwagi, $node->Naglowek, $node->Stopka, $node->PlikLogo, $node->ZdjecieWWW, $node->Subdomena, $node->Firma);
                    Departments::AddEditDepartment($dep);
                    echo DataBase::GetDbInstance()->LastError();
                }else if($child->getName() == "Agent"){
                    //read agents
                    $node = $child;
                    $agent = new Agent($node["ID"], $node->Nazwa, $node->Telefon, $node->Komorka, $node->Email, $node->Oddzial, $node->JabberLogin, $node->NrLicencji, $node->OdpowiedzialnyNazwa, $node->OdpowiedzialnyNrLicencji, $node->Komunikator, $node->PlikFoto, $node->KodPracownika, $node->DzialFunkcja);
                    Agents::AddEditAgent($agent);
                    echo DataBase::GetDbInstance()->LastError();
                }else if($child->getName() == "parametry"){
                    $s = $xml->xpath("/Serwis/wersja[1]/@GID");
                    $sGID = (string)$s[0]['GID'];
                    $params = array();
                    foreach($child->children() as $param){
                        $key = $param['nazwa'];
                        $params["$key"] = $param;
                    }
                    Serwisy::SaveParams($sGID, $params);
                    echo DataBase::GetDbInstance()->LastError();
                }else if($child->getName() == "Konfiguracja"){
                    file_put_contents(WEB_API_DIR."/".self::CFG_FILE,utf8_decode(mb_convert_encoding(html_entity_decode($child), 'utf8', 'HTML-ENTITIES')));
                }
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetSerwis", $ex->getMessage());
            return 0;
		}
    }


    /**
     * Get a list of agents to be added or remove. Write the agent to database.
     * @param int $id
     * @return int
     */
    public function GetAgenci($id = 0){
        if(!$this->WS()) return null;
        try{
            $params = array('sid'=>$this->_sid, 'id'=>$id);
            $result = $this->WS()->getSC()->__soapCall("GetUserzy", array($params));
			if($result->GetUserzyResult->Status <> 0){
				Errors::LogError("WebService:GetUserzy", "Response: " . $result->GetUserzyResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetUserzyResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
                $kod_pracownika = 0;
                if(is_numeric((string) $node->KodPracownika)) $kod_pracownika = (int) $node->KodPracownika;
				$agent = new Agent($node["ID"], $node->Nazwa, $node->Telefon, $node->Komorka, $node->Email, $node->Oddzial, $node->JabberLogin, $node->NrLicencji, $node->OdpowiedzialnyNazwa, $node->OdpowiedzialnyNrLicencji, $node->Komunikator, $node->PlikFoto, $kod_pracownika, $node->DzialFunkcja);
                Agents::AddEditAgent($agent);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
            }
            return $cnt;
        }catch (Exception $ex) {
			Errors::LogError("WebService:GetUserzy", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get a list of departments to be added or remove. Write the department to database.
     * @param int $id
     * @return int
     */
    public function GetOddzialy($id = 0){
        if(!$this->WS()) return null;
        try{
            $params = array('sid'=>$this->_sid, 'id'=>$id);
            $result = $this->WS()->getSC()->__soapCall("GetOddzialy", array($params));
			if($result->GetOddzialyResult->Status <> 0){
				Errors::LogError("WebService:GetOddzialy", "Response: " . $result->GetOddzialyResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetOddzialyResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
				$dep = new Department($node["ID"], $node->Nazwa, $node->Nazwa2, $node->Adres, $node->Miasto, $node->Kod, $node->Nip, $node->Wojewodztwo, $node->Www, $node->Telefon, $node->Email, $node->Fax, $node->Uwagi, $node->Naglowek, $node->Stopka, $node->PlikLogo, $node->ZdjecieWWW, $node->Subdomena, $node->Firma);
                Departments::AddEditDepartment($dep);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
            }
            return $cnt;
        }catch (Exception $ex) {
			Errors::LogError("WebService:GetOddzialy", $ex->getMessage());
            return 0;
		}
    }

	/**
     * Get a list of osoba to be added or remove. Write the osoba to database.
     * @param int $id
     * @return int
     */
    public function GetOsoby($id=0){
        if(!$this->WS()) return null;
        try{
            $params = array('sid'=>$this->_sid, 'id'=>$id);
			$result = $this->WS()->getSC()->__soapCall("GetOsoby", array($params));
			if($result->GetOsobyResult->Status <> 0){
				Errors::LogError("WebService:GetOsoby", "Response: " . $result->GetOsobyResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetOsobyResult->XMLContent);
            $cnt = 0;

            foreach($xml->children() as $o){
                $id_usera = null;
                if (CheckNumeric($o['id_usera']) > 0 ){
                    $id_usera = CheckNumeric($o['id_usera']);
                }
                $osoba = new Osoba($o["id"], $o->Imie, $o->Nazwisko, $o->Email, $o['telefon'], $o->Login, $o->Haslo, $o['data_rejestracji'], $id_usera);
                Osoby::AddEditOsoba($osoba);
                $cnt++;
            }
            return $cnt;
        }catch (Exception $ex) {
			Errors::LogError("WebService:GetOsoby", $ex->getMessage());
            return 0;
		}

    }

    /**
     * Get a list of miejsca to be added or remove. Write the miejsca to database.
     * @param int $rodzaj
     * @param int $gid
     * @return null|int
     */
    public function GetMiejsca($rodzaj = 0, $gid = 0) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'rodzaj'=>$rodzaj, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetMiejsca", array($params));
			if($result->GetMiejscaResult->Status <> 0){
				Errors::LogError("WebService:GetMiejsca", "Response: " . $result->GetMiejscaResult->Message);
                return 0;
			}
			$xml = simplexml_load_string($result->GetMiejscaResult->XMLContent);
            $cnt = 0;
            foreach($xml->serwisu->children() as $child){
                $this->SaveMiejsce($child, Miejsca::MIEJSCE_RODZAJ_SERWISU);
                $cnt++;
            }
            foreach($xml->menu->children() as $child){
                $this->SaveMiejsce($child, Miejsca::MIEJSCE_RODZAJ_MENU);
                $cnt++;
            }
            foreach($xml->grupy->children() as $child){
                $this->SaveMiejsce($child, Miejsca::MIEJSCE_RODZAJ_GRUPY);
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetMiejsca", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Write the miejsca to database.
     * @param array $node
     * @param int $typ
     * @return null
     */
    private function SaveMiejsce($node, $typ){
        if(!$this->WS()) return null;
		$miejsce = new Miejsce($node["GID"], $node["jezyk"], $typ, $node["serwis"], $node["parent"], ($node["lp"] == null ? 0 : $node["lp"]), $node->NazwaGlowna, $node->Nazwa, $node->Grafika, $node->Link,
            $node->Inne, $node->Uwagi);
        Miejsca::AddEditMiejsce($miejsce);
        echo DataBase::GetDbInstance()->LastError();
    }

    /**
     * Get a list of menu items to be added or remove. Write the menu items to database.
     * @param int $gid
     * @return null|int
     */
    public function GetMenu($gid = 0) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetMenu", array($params));
			if($result->GetMenuResult->Status <> 0){
				Errors::LogError("WebService:GetMenu", "Response: " . $result->GetMenuResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetMenuResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
				$czyunawww = $node->UkryjNaWWW == "True" ? 1 : 0;
                $nofollow = $node->NoFollow == "True" ? 1 : 0;
                $menu = new Menu($node["GID"], $node["jezyk"], $node["serwis"], $node["miejsceMenu"], $node["grupaSerwisu"], $node->Lp, $czyunawww,
                    $node->NazwaGlowna, $node->Nazwa, $node->Grafika, $node->Grafika2, $node->Link, $node->Tooltip, $nofollow);
                Menus::AddEditMenu($menu);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetMenu", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get a list of artykul to be added or remove. Write the artykul to database.
     * @param int $gid
     * @return int
     */
    public function GetArtykuly($gid = 0) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetArtykuly", array($params));
			if($result->GetArtykulyResult->Status <> 0){
				Errors::LogError("WebService:GetArtykuly", "Response: " . $result->GetArtykulyResult->Message);
                return 0;
			}
            $xml = simplexml_load_string(str_replace("&#xC;","",$result->GetArtykulyResult->XMLContent));
            $cnt = 0;
            foreach($xml->children() as $node){
                $czywiad = $node["CzyWiadomosc"] == "True" ? 1 : 0;
                $czydef = $node["CzyDomyslny"] == "True" ? 1 : 0;
                $NoFollow = $node["NoFollow"] == "True" ? 1 : 0;
                $art = new Artykul($node["GID"], $node["jezyk"], $node["serwis"], $node["grupaSerwisu"], $node["miejsceSerwisu"], $node["menu"], $node["artykulNadrzedny"],
                    $node->Lp, $czywiad, $czydef, $node->Autor, $node->LiczbaOdslon, $node->SredniaOcena, $node->DataWiadomosci, $node->DataAktualizacji,
                    $node->Tytul, $node->Skrot, $node->SkrotGrafika, $node->Tresc, $node->Link, $node->NazwaWyswietlana, $node->TagTitle, $node->TagKeywords, $node->TagDescription, $node["galeria"], $node->Tagi, $node->DataRozpoczeciaPublikacji, $NoFollow);
                Artykuly::AddEditArtykul($art);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
                //parametry
                $paramsNode = $node->parametry;
                if($paramsNode != null){
                    ArtykulyParametry::DeleteArtykulParametr(0, $art->GetGID(), $art->GetIdJezyk());
                    foreach($paramsNode->children() as $pNode){
                        $par = new ArtykulParametr($pNode["GID"], $node["jezyk"], $node["GID"], $pNode->ParamNazwa, $pNode->Nazwa, $pNode->Naglowek, $pNode->Stopka);
                        ArtykulyParametry::AddEditArtykulParametr($par);
                        echo DataBase::GetDbInstance()->LastError();
                    }
                }
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetArtykuly", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get a list of css/js files to be added or remove. Write the css/js file to database.
     * @param int $rodzaj
     * @param int $gid
     * @return int
     */
    public function GetArkuszeSkrypty($rodzaj = 0, $gid = 0) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'rodzaj'=>$rodzaj, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetArkuszeSkrypty", array($params));
			if($result->GetArkuszeSkryptyResult->Status <> 0){
				Errors::LogError("WebService:GetArkuszeSkrypty", "Response: " . $result->GetArkuszeSkryptyResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetArkuszeSkryptyResult->XMLContent);
            $cnt = 0;
            foreach($xml->Arkusze->children() as $child){
                $this->SaveArkuszSkrypt($child, ArkuszeSkrypty::ARKUSZ_RODZAJ_CSS);
                $cnt++;
            }
            foreach($xml->Skrypty->children() as $child){
                $this->SaveArkuszSkrypt($child, ArkuszeSkrypty::ARKUSZ_RODZAJ_JS);
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetArkuszeSkrypty", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Write the css/js file to database.
     * @param array $node
     * @param int $rodzaj
     * @return int
     */
    private function SaveArkuszSkrypt($node, $rodzaj){
        if(!$this->WS()) return null;
		$ark = new ArkuszSkrypt($node["GID"], $node["serwis"], $rodzaj, $node->Opis, $node->Tresc, $node->RodzajArkusza);
        ArkuszeSkrypty::AddEditArkuszSkrypt($ark);
        echo DataBase::GetDbInstance()->LastError();
    }

    /**
     * Get a list of baner to be added or remove. Write the baner to database.
     * @param int $gid
     * @return int
     */
    public function GetBanery($gid = 0) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetBanery", array($params));
			if($result->GetBaneryResult->Status <> 0){
				Errors::LogError("WebService:GetBanery", "Response: " . $result->GetBaneryResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetBaneryResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
                $ban = new Baner($node["GID"], $node["jezyk"], $node["serwis"], $node["grupaSerwisu"], $node["miejsceSerwisu"], $node->Status, $node->DataDodania,
                    $node->DataWygasniecia, $node->DataEmisji, $node->UrlDocelowy, $node->GIDGrafiki, $node->Embed);
                Banery::AddEditBaner($ban);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetBanery", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get graphics (one or all) from WebService, and write it on local disk.
     * @param string $pfn
     * @return int
     */
    public function GetGrafiki($pfn = "") {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'fileName'=>$pfn);
			$result = $this->WS()->getSC()->__soapCall("GetGrafiki", array($params));
			if($result->GetGrafikiResult->Status <> 0){
				Errors::LogError("WebService:GetGrafiki", "Response: " . $result->GetGrafikiResult->Message);
                return 0;
			}
            $cnt = 0;
            $lst = array();
            if($pfn != ""){
                Grafiki::PobierzPlik($result->GetGrafikiResult->ListContent->FileDesc->Name, $result->GetGrafikiResult->ListContent->FileDesc->Size);
                $lst[] = basename($result->GetGrafikiResult->ListContent->FileDesc->Name);
                $cnt++;
            }else
                foreach ($result->GetGrafikiResult->ListContent->FileDesc as $file) {
                    Grafiki::PobierzPlik($file->Name, $file->Size);
                    $lst[] = basename($file->Name);
                    $cnt++;
                }
            if($pfn == "" && count($lst) > 1){
                Grafiki::UsunGrafiki($lst);
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetGrafiki", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get a list of options to be added or remove. Write the options to database.
     * @return int
     */
    public function GetOpcje() {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid);
			$result = $this->WS()->getSC()->__soapCall("GetOpcje", array($params));
			if($result->GetOpcjeResult->Status <> 0){
				Errors::LogError("WebService:GetOpcje", "Response: " . $result->GetOpcjeResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetOpcjeResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
                $opt = new Opcja($node["nazwa"], $node["wartosc"]);
                Opcje::AddEditOpcja($opt);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetOpcje", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get a list of language text to be added or remove. Write the language text to database.
     * @return int
     */
    public function GetJezyki() {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid);
			$result = $this->WS()->getSC()->__soapCall("GetJezyki", array($params));
			if($result->GetJezykiResult->Status <> 0){
				Errors::LogError("WebService:GetJezyki", "Response: " . $result->GetJezykiResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetJezykiResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
                $jt = new JezykTekst(strtolower($node["klucz"]), $node["jezyk"], $node->wartosc);
                JezykiTeksty::AddEditJezyk($jt);
                echo DataBase::GetDbInstance()->LastError();
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetJezyki", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get a list of galeria to be added or remove. Write the galeria to database.
     * @param int $gid
     * @return int
     */
    public function GetGalerie($gid = 0) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetGalerie", array($params));
			if($result->GetGalerieResult->Status <> 0){
				Errors::LogError("WebService:GetGalerie", "Response: " . $result->GetGalerieResult->Message);
                return 0;
			}
            $xml = simplexml_load_string($result->GetGalerieResult->XMLContent);
            $cnt = 0;
            foreach($xml->children() as $node){
                $gal = new Galeria((int)$node["GID"], (string)$node["jezyk"], (string)$node["serwis"], (int)$node["grupaSerwisu"], (int)$node["Lp"], (string)$node->Nazwa, (string)$node->Opis,
                    (string)$node->SlowaKLuczowe, (string)$node->Grafika, (string)$node->Rozmiar1, (string)$node->Rozmiar2, (string)$node->Rozmiar3);
                Galerie::AddEditGaleria($gal);

                echo DataBase::GetDbInstance()->LastError();

                $pozycje = $node->GaleriePozycje;
                if ($pozycje != null) {
                    foreach($pozycje->children() as $node2){
                        $galpoz = new GaleriaPozycja((int)$node2["GID"], (int)$node2["jezyk"], (string)$node2["serwis"], (int)$gal->GetGID(), (int)$node2["Lp"], (string)$node2->Plik, (string)$node2->Opis, (string)$node2->Tagi);
                        GaleriePozycje::AddEditGaleriaPozycja($galpoz);
                        echo DataBase::GetDbInstance()->LastError();
                    }
                    $this->GetGaleriePozycje($gal->GetGID(), $gal->GetIdJezyk());
                }
                $cnt++;
            }
            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetGalerie", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Get graphics for galeria from WebService, and write it on local disk.
     * @param int $gid
     * @param int $idLng
     * @return null|int
     */
    public function GetGaleriePozycje($gid, $idLng = 1045) {
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'gid'=>$gid);
			$result = $this->WS()->getSC()->__soapCall("GetGaleriePozycje", array($params));
            if($result->GetGaleriePozycjeResult->Status == 5){
                return 0;
            }else if($result->GetGaleriePozycjeResult->Status <> 0){
				Errors::LogError("WebService:GetGaleriePozycje", "Response: " . $result->GetGaleriePozycjeResult->Message);
                return 0;
			}

            $cnt = 0;

            foreach ($result->GetGaleriePozycjeResult->ListContent->FileDesc as $file) {
                if (isset($file->Name)) {
                    $name=str_replace("\\","/",$file->Name);
                    GaleriePozycje::PobierzPlik($gid, $name, $file->OrygName, $file->Size);
                    $cnt++;
                }
            }

            return $cnt;
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetGaleriePozycje", $ex->getMessage());
            return 0;
		}
    }

    /**
     * Returns photo file from given params.
     * $id - identyfier of photo,
     * $size - size of photo (100_70),
     * $photoType - type of photo
     * @param int $id
     * @param string $size
     * @param string $photoType
     * @return array
     */
    public function GetPhoto($id, $size, $photoType){
		if(!$this->WS()) return null;
		try{
			if($this->_sid == "") return;
			$params = array('sid'=>$this->_sid, 'id'=>$id, 'size'=>$size, 'photoType'=>$photoType);
			$result = $this->WS()->getSC()->__soapCall("GetPhoto", array($params));
			if($result->GetPhotoResult->Status == 0){
				return $result->GetPhotoResult->Image;
			}else{
				Errors::LogError("WebService:GetPhoto", "ID=$id, SIZE=$size, PHOTOTYPE=$photoType Response: " . $result->GetPhotoResult->Message);
			}
		}catch (Exception $ex) {
			Errors::LogError("WebService:GetPhoto", $ex->getMessage());
		}
	}
	/**
     * Returns PDF file from given url
     * @param string $url
     * @return array
     * @throws Exception
     */

    public function GetPdfFromUrl($url){
        if(!$this->WS()) return null;
        if($this->_sid == "") return;
        $params = array('sid'=>'', 'url'=>$url);
        $sc = new soapclient("https://pdf.galapp.net/html2pdf.asmx?WSDL");
        $result = $sc->__soapCall("GetPdfFromUrl", array($params));

        if($result->GetPdfFromUrlResult->Status == 0){
            return $result->GetPdfFromUrlResult->Image;
        }else{
            throw new Exception($result->GetPdfFromUrlResult->Message);
        }
	}

	/**
     * Check if this can send sms.
     * @return int
     */
    public function IsSmsGatewayActive(){
       if(!$this->WS()) return null;
	   try{
			$params = array('sid'=>$this->_sid);
			$result = $this->WS()->getSC()->__soapCall("IsSmsGatewayActive", array($params));
			if($result->IsSmsGatewayActiveResult->Message <> "OK"){
				Errors::LogError("WebService:IsSmsGatewayActive", "Response: " . $result->IsSmsGatewayActiveResult->Message);
			}
			return $result->IsSmsGatewayActiveResult->Status;
		}catch (Exception $ex) {
			Errors::LogError("WebService:IsSmsGatewayActive", $ex->getMessage());
		}
    }

    /**
     * Sends sms.
     * @param string $tresc
     * @param string $numer
     * @return null
     */
    public function SendSms($tresc, $numer){
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'tresc'=>$tresc, 'numer'=>$numer);
			$result = $this->WS()->getSC()->__soapCall("SendSms", array($params));
			if($result->SendSmsResult->Status <> 0){
				Errors::LogError("WebService:SendSms", "Response: " . $result->SendSmsResult->Message);
			}
			return $result->SendSmsResult->Message;
		}catch (Exception $ex) {
			Errors::LogError("WebService:SendSms", $ex->getMessage());
		}
    }

    /**
     * Sends email.
     * @param string $tresc
     * @param string $numer
     * @return null
     */
    public function SendEmail($email, $szablon){
        if(!$this->WS()) return null;
		try{
			$params = array('sid'=>$this->_sid, 'email'=>$email, 'szablon'=>$szablon);
			$result = $this->WS()->getSC()->__soapCall("SendEmail", array($params));
			if($result->SendEmailResult->Status <> 0){
				Errors::LogError("WebService:SendEmail", "Response: " . $result->SendEmailResult->Message);
			}
			return $result->SendEmailResult->Message;
		}catch (Exception $ex) {
			Errors::LogError("WebService:SendEmail", $ex->getMessage());
		}
    }


    /**
     * Upload reservation.
     * @return string
     */
    public function LoginUser($login, $pwd){
        try{
            $params = array('sid'=>$this->_sid, 'login'=>$login, 'pwd'=>$pwd);
            $result = $this->WS()->getSC()->__soapCall("LoginUzytkownik", array($params));
            //dump($result);

            if($result->LoginUzytkownikResult->Status <> 0){
                //Errors::LogError("WebServiceApi:LoginUzytkownik", "Response: " . $result->LoginUzytkownikResult->Message);
                return $result_array['status']=1;
                //$result_array['message']=$result->LoginUzytkownikResult->Message;
            }else{
                return $result_array['status']=0;
            }
        }catch (Exception $ex) {
            return $result_array['status']=1;
            //$result_array['message']=$ex->getMessage();
        }
    }
}

?>
