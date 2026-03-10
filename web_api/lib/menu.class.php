<?php

/**
 * Description of menu
 *
 * @author Jakub Konieczka
 */
class Menu {

	private $_GID;
	private $_IdJezyk;
	private $_serwisy_GID;
	private $_miejsca_miejsce_menu;
	private $_miejsca_grupa_serwisu;
	private $_Lp;
	private $_UkryjNaWWW;
	private $_NazwaGlowna;
	private $_Nazwa;
	private $_Grafika;
	private $_Grafika2;
	private $_Link;
	private $_Tooltip;
    private $_NoFollow;

    private $_SerwisObj = null;
    private $_IsSerwisObjSet = false;
    private $_MiejsceMenuObj = null;
    private $_IsMiejsceMenuObjSet = false;
    private $_GrupaSerwisuObj = null;
    private $_IsGrupaSerwisuObjSet = false;
    
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

	public function Getmiejsca_miejsce_menu(){
		return $this->_miejsca_miejsce_menu;
	}

	public function Setmiejsca_miejsce_menu($value){
		$this->_miejsca_miejsce_menu = $value;
	}

	public function Getmiejsca_grupa_serwisu(){
		return $this->_miejsca_grupa_serwisu;
	}

	public function Setmiejsca_grupa_serwisu($value){
		$this->_miejsca_grupa_serwisu = $value;
	}

	public function GetLp(){
		return $this->_Lp;
	}

	public function SetLp($value){
		$this->_Lp = $value;
	}

	public function GetUkryjNaWWW(){
		return $this->_UkryjNaWWW;
	}

	public function SetUkryjNaWWW($value){
		$this->_UkryjNaWWW = $value;
	}

    public function GetNazwaGlowna(){
		return $this->_NazwaGlowna;
	}

	public function SetNazwaGlowna($value){
		$this->_NazwaGlowna = $value;
	}

	public function GetNazwa(){
		return $this->_Nazwa;
	}

	public function SetNazwa($value){
		$this->_Nazwa = $value;
	}

	public function GetGrafika(){
		return $this->_Grafika;
	}

	public function SetGrafika($value){
		$this->_Grafika = $value;
	}

	public function GetGrafika2(){
		return $this->_Grafika2;
	}

	public function SetGrafika2($value){
		$this->_Grafika2 = $value;
	}

	public function GetLink(){
		return $this->_Link;
	}

	public function SetLink($value){
		$this->_Link = $value;
	}

	public function GetTooltip(){
		return $this->_Tooltip;
	}

	public function SetTooltip($value){
		$this->_Tooltip = $value;
	}

    public function GetNoFollow(){
		return $this->_NoFollow;
	}

	public function SetNoFollow($value){
		$this->_NoFollow = $value;
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
	 * Return miejsce menu object.
	 * @return Miejsce
	 */
	public function GetMiejsceMenu(){
		if($this->_IsMiejsceMenuObjSet == false){
			$this->_MiejsceMenuObj = Miejsca::GetMiejsce($this->Getmiejsca_miejsce_menu(), Miejsca::MIEJSCE_RODZAJ_MENU, $this->GetIdJezyk());
            $this->_IsMiejsceMenuObjSet = true;
		}
		return $this->_MiejsceMenuObj;
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

	public function __construct($GID, $IdJezyk, $serwisy_GID, $miejsca_miejsce_menu, $miejsca_grupa_serwisu, $Lp, $UkryjNaWWW, $NazwaGlowna, $Nazwa, $Grafika, $Grafika2, $Link, $Tooltip, $NoFollow){
		$this->SetGID($GID);
		$this->SetIdJezyk($IdJezyk);
		$this->Setserwisy_GID($serwisy_GID);
		$this->Setmiejsca_miejsce_menu($miejsca_miejsce_menu);
		$this->Setmiejsca_grupa_serwisu($miejsca_grupa_serwisu);
		$this->SetLp($Lp);
		$this->SetUkryjNaWWW($UkryjNaWWW);
		$this->SetNazwaGlowna($NazwaGlowna);
		$this->SetNazwa($Nazwa);
		$this->SetGrafika($Grafika);
		$this->SetGrafika2($Grafika2);
		$this->SetLink($Link);
		$this->SetTooltip($Tooltip);
        $this->SetNoFollow($NoFollow);
	}
}

?>
