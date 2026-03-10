<?php

/**
 * Description of artykul
 * @author Jakub Konieczka
 */
class Artykul {

	private $_GID;
	private $_IdJezyk;
	private $_serwisy_GID;
	private $_miejsca_grupa_serwisu;
	private $_miejsca_miejsce_serwisu;
	private $_menu_GID;
	private $_Parent_GID;
    private $_galerie_GID;
	private $_Lp;
	private $_CzyWiadomosc;
	private $_CzyDomyslny;
    private $_NoFollow;
	private $_Autor;
	private $_LiczbaOdslon;
	private $_SredniaOcena;
	private $_DataWiadomosci;
	private $_DataAktualizacji;
	private $_Tytul;
	private $_Skrot;
	private $_SkrotGrafika;
	private $_Tresc;
	private $_Link;
	private $_NazwaWyswietlana;
	private $_TagTitle;
	private $_TagKeywords;
	private $_TagDescription;
    private $_Tagi;
    private $_DataRozpoczeciaPublikacji;

    private $_SerwisObj = null;
    private $_IsSerwisObjSet = false;
    private $_MiejsceSerwisuObj = null;
    private $_IsMiejsceSerwisuObjSet = false;
    private $_GrupaSerwisuObj = null;
    private $_IsGrupaSerwisuObjSet = false;
    private $_MenuObj = null;
    private $_IsMenuObjSet = false;
    private $_ParentObj = null;
    private $_IsParentObjSet = false;
    private $_GaleriaObj = null;
    private $_IsGaleriaObjSet = false;
    private $_ParametryObj = null;
    private $_IsParametryObjSet = false;

    private $_GaleriePozycjeObj = null;
    private $_IsGaleriePozycjeObjSet = false;

	public function GetGID(){
		return $this->_GID;
	}

	public function SetGID($value){
		$this->_GID = $value;
	}

	public function GetIdJezyk(){
		return $this->_IdJezyk;
	}

	public function SetIdJezyk($value){
		$this->_IdJezyk = $value;
	}

	public function Getserwisy_GID(){
		return $this->_serwisy_GID;
	}

	public function Setserwisy_GID($value){
		$this->_serwisy_GID = $value;
	}

	public function Getmiejsca_grupa_serwisu(){
		return $this->_miejsca_grupa_serwisu;
	}

	public function Setmiejsca_grupa_serwisu($value){
		$this->_miejsca_grupa_serwisu = $value;
	}

	public function Getmiejsca_miejsce_serwisu(){
		return $this->_miejsca_miejsce_serwisu;
	}

	public function Setmiejsca_miejsce_serwisu($value){
		$this->_miejsca_miejsce_serwisu = $value;
	}

	public function Getmenu_GID(){
		return $this->_menu_GID;
	}

	public function Setmenu_GID($value){
		$this->_menu_GID = $value;
	}

	public function GetParent_GID(){
		return $this->_Parent_GID;
	}

	public function SetParent_GID($value){
		$this->_Parent_GID = $value;
	}

    public function Getgalerie_GID(){
		return $this->_galerie_GID;
	}

	public function Setgalerie_GID($value){
		$this->_galerie_GID = $value;
	}

	public function GetLp(){
		return $this->_Lp;
	}

	public function SetLp($value){
		$this->_Lp = $value;
	}

	public function GetCzyWiadomosc(){
		return $this->_CzyWiadomosc;
	}

	public function SetCzyWiadomosc($value){
		$this->_CzyWiadomosc = $value;
	}

    public function GetCzyDomyslny(){
		return $this->_CzyDomyslny;
	}

	public function SetCzyDomyslny($value){
		$this->_CzyDomyslny = $value;
	}

    public function GetNoFollow(){
		return $this->_NoFollow;
	}

	public function SetNoFollow($value){
		$this->_NoFollow = $value;
	}

	public function GetAutor(){
		return $this->_Autor;
	}

	public function SetAutor($value){
		$this->_Autor = $value;
	}

	public function GetLiczbaOdslon(){
		return $this->_LiczbaOdslon;
	}

	public function SetLiczbaOdslon($value){
		$this->_LiczbaOdslon = $value;
	}

	public function GetSredniaOcena(){
		return $this->_SredniaOcena;
	}

	public function SetSredniaOcena($value){
		$this->_SredniaOcena = $value;
	}

	public function GetDataWiadomosci(){
		return $this->_DataWiadomosci;
	}

	public function SetDataWiadomosci($value){
		$this->_DataWiadomosci = $value;
	}

	public function GetDataAktualizacji(){
		return $this->_DataAktualizacji;
	}

	public function SetDataAktualizacji($value){
		$this->_DataAktualizacji = $value;
	}

	public function GetTytul(){
		return $this->_Tytul;
	}

	public function SetTytul($value){
		$this->_Tytul = $value;
	}

	public function GetSkrot(){
		return $this->_Skrot;
	}

	public function SetSkrot($value){
		$this->_Skrot = $value;
	}

	public function GetSkrotGrafika(){
		return $this->_SkrotGrafika;
	}

	public function SetSkrotGrafika($value){
		$this->_SkrotGrafika = $value;
	}

	public function GetTresc(){
		return $this->_Tresc;
	}

	public function SetTresc($value){
		$this->_Tresc = $value;
	}

	public function GetLink(){
		return $this->_Link;
	}

	public function SetLink($value){
		$this->_Link = $value;
	}

	public function GetNazwaWyswietlana(){
		return $this->_NazwaWyswietlana;
	}

	public function SetNazwaWyswietlana($value){
		$this->_NazwaWyswietlana = $value;
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

    public function GetTagi(){
		return $this->_Tagi;
	}

	public function SetTagi($value){
		$this->_Tagi = $value;
	}

    public function GetDataRozpoczeciaPublikacji(){
		return $this->_DataRozpoczeciaPublikacji;
	}

	public function SetDataRozpoczeciaPublikacji($value){
		$this->_DataRozpoczeciaPublikacji = $value;
	}

    /**
	 * Return serwis object.
	 * @return Serwis
	 */
	public function GetSerwis(){
		if($this->_IsSerwisObjSet == false){
			$this->_SerwisObj = Serwisy::GetSerwis($this->Getserwisy_GID(), $this->GetIdJezyk());
            $this->_IsSerwisObjSet = true;
		}
		return $this->_SerwisObj;
	}

    /**
	 * Return miejsce serwisu object.
	 * @return Miejsce
	 */
	public function GetMiejsceSerwisu(){
		if($this->_IsMiejsceSerwisuObjSet == false){
			$this->_MiejsceSerwisuObj = Miejsca::GetMiejsce($this->Getmiejsca_miejsce_serwisu(), Miejsca::MIEJSCE_RODZAJ_SERWISU, $this->GetIdJezyk());
            $this->_IsMiejsceSerwisuObjSet = true;
		}
		return $this->_MiejsceSerwisuObj;
	}

    /**
	 * Return grupa serwisu object.
	 * @return Miejsce
	 */
	public function GetGrupaSerwisu(){
		if($this->_IsGrupaSerwisuObjSet == false){
			$this->_GrupaSerwisuObj = Miejsca::GetMiejsce($this->Getmiejsca_grupa_serwisu(), Miejsca::MIEJSCE_RODZAJ_GRUPY, $this->GetIdJezyk());
            $this->_IsGrupaSerwisuObjSet = true;
		}
		return $this->_GrupaSerwisuObj;
	}

    /**
	 * Return menu object.
	 * @return Menu
	 */
	public function GetMenu(){
		if($this->_IsMenuObjSet == false){
			$this->_MenuObj = Menus::GetMenu($this->Getmenu_GID(), $this->GetIdJezyk());
            $this->_IsMenuObjSet = true;
		}
		return $this->_MenuObj;
	}

    /**
	 * Return grupa serwisu object.
	 * @return Artykul
	 */
	public function GetParent(){
		if($this->_IsParentObjSet == false){
			$this->_ParentObj = Artykuly::GetArtykul($this->GetParent_GID(), $this->GetIdJezyk());
            $this->_IsParentObjSet = true;
		}
		return $this->_ParentObj;
	}

    /**
	 * Returngaleria object.
	 * @return Galeria
	 */
	public function GetGaleria(){
		if($this->_IsGaleriaObjSet == false){
			$this->_GaleriaObj = Galerie::GetGaleria($this->Getgalerie_GID(), $this->GetIdJezyk());
            $this->_IsGaleriaObjSet = true;
		}
		return $this->_GaleriaObj;
	}

    /**
	 * Return parameter list object.
	 * @return ArtykulParametr[]
	 */
	public function GetParametry(){
		if($this->_IsParametryObjSet == false){
			$this->_ParametryObj = ArtykulyParametry::GetArtykulParametry($this);
            $this->_IsParametryObjSet = true;
		}
		return $this->_ParametryObj;
	}

    /**
	 * Return parameter list object.
	 * @return ArtykulParametr[]
	 */
	public function GetGaleriePozycje(){
		if($this->_IsGaleriePozycjeObjSet == false){
			$this->_GaleriePozycjeObj = GaleriePozycje::PobierzGaleriePozycjeDlaArtykulu($this->GetGID(), $this->GetIdJezyk());
            $this->_IsGaleriePozycjeObjSet = true;
		}
		return $this->_GaleriePozycjeObj;
	}

    /**
     *
     * @param int $gid
     * @return bool
     */
    public function MaParametr($param){
    	if(is_numeric($param)) $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT COUNT(GID) FROM #S#artykuly_parametry WHERE GID=? AND artykuly_GID=? AND IdJezyk=?", array((int) $param, (int) $this->_GID, (int) $this->_IdJezyk));
    	else $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT COUNT(GID) FROM #S#artykuly_parametry WHERE ParamNazwa=? AND artykuly_GID=? AND IdJezyk=?", array($param, (int) $this->_GID, (int) $this->_IdJezyk));

        if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			return $row && $row[0] > 0;
		}else return false;
    }

	public function __construct($GID, $IdJezyk, $serwisy_GID, $miejsca_grupa_serwisu, $miejsca_miejsce_serwisu, $menu_GID, $Parent_GID, $Lp, $CzyWiadomosc, $CzyDef, $Autor, $LiczbaOdslon,
            $SredniaOcena, $DataWiadomosci, $DataAktualizacji, $Tytul, $Skrot, $SkrotGrafika, $Tresc, $Link, $NazwaWyswietlana, $TagTitle, $TagKeywords, $TagDescription, $galerie_GID,
            $Tagi, $DataRozpoczeciaPublikacji, $NoFollow){
		$this->SetGID($GID);
		$this->SetIdJezyk($IdJezyk);
		$this->Setserwisy_GID($serwisy_GID);
		$this->Setmiejsca_grupa_serwisu($miejsca_grupa_serwisu);
		$this->Setmiejsca_miejsce_serwisu($miejsca_miejsce_serwisu);
		$this->Setmenu_GID($menu_GID);
		$this->SetParent_GID($Parent_GID);
        $this->Setgalerie_GID($galerie_GID);
		$this->SetLp($Lp);
		$this->SetCzyWiadomosc($CzyWiadomosc);
        $this->SetCzyDomyslny($CzyDef);
		$this->SetAutor($Autor);
		$this->SetLiczbaOdslon($LiczbaOdslon);
		$this->SetSredniaOcena($SredniaOcena);
		$this->SetDataWiadomosci($DataWiadomosci);
		$this->SetDataAktualizacji($DataAktualizacji);
		$this->SetTytul($Tytul);
		$this->SetSkrot($Skrot);
		$this->SetSkrotGrafika($SkrotGrafika);
		$this->SetTresc($Tresc);
		$this->SetLink($Link);
		$this->SetNazwaWyswietlana($NazwaWyswietlana);
		$this->SetTagTitle($TagTitle);
		$this->SetTagKeywords($TagKeywords);
		$this->SetTagDescription($TagDescription);
        $this->SetTagi($Tagi);
        $this->SetDataRozpoczeciaPublikacji($DataRozpoczeciaPublikacji);
        $this->SetNoFollow($NoFollow);
	}

}

?>
