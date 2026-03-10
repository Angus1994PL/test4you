<?php

/**
 * Description of miejsce
 *
 * @author Jakub Konieczka
 */
class Miejsce {

	private $_GID;
	private $_IdJezyk;
	private $_Rodzaj;
	private $_serwisy_GID;
	private $_Parent_GID;
	private $_Lp;
    private $_NazwaGlowna;
	private $_Nazwa;
	private $_Grafika;
	private $_Link;
	private $_Inne;
	private $_Uwagi;

    private $_SerwisObj = null;
    private $_IsSerwisObjSet = false;
    private $_ParentObj = null;
    private $_IsParentObjSet = false;
    private $_Podrzedne = null;
    private $_IsPodrzedneSet = false;

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

	public function GetRodzaj(){
		return $this->_Rodzaj;
	}

	public function SetRodzaj($value){
		$this->_Rodzaj = $value;
	}

	public function Getserwisy_GID(){
		return $this->_serwisy_GID;
	}

	public function Setserwisy_GID($value){
		$this->_serwisy_GID = $value;
	}

	public function GetParent_GID(){
		return $this->_Parent_GID;
	}

	public function SetParent_GID($value){
		$this->_Parent_GID = $value;
	}

	public function GetLp(){
		return $this->_Lp;
	}

	public function SetLp($value){
		$this->_Lp = $value;
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

	public function GetLink(){
		return $this->_Link;
	}

	public function SetLink($value){
		$this->_Link = $value;
	}

	public function GetInne(){
		return $this->_Inne;
	}

	public function SetInne($value){
		$this->_Inne = $value;
	}

	public function GetUwagi(){
		return $this->_Uwagi;
	}

	public function SetUwagi($value){
		$this->_Uwagi = $value;
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
	 * Return parent object.
	 * @return Miejsce
	 */
	public function GetParent(){
		if($this->_IsParentObjSet == false){
			$this->_ParentObj = Miejsca::GetMiejsce($this->GetParent_GID(), $this->GetRodzaj(), $this->GetIdJezyk());
            $this->_IsParentObjSet = true;
		}
		return $this->_ParentObj;
	}

    /**
	 * Return children collection.
	 * @return Miejsce[]
	 */
	public function GetPodrzedne(){
		if($this->_IsPodrzedneSet == false){
			$this->_Podrzedne = Miejsca::GetMiejsca($this);
            $this->_IsPodrzedneSet = true;
		}
		return $this->_Podrzedne;
	}

	public function __construct($GID, $IdJezyk, $Rodzaj, $serwisy_GID, $Parent_GID, $Lp, $NazwaGlowna, $Nazwa, $Grafika, $Link, $Inne, $Uwagi){
		$this->SetGID($GID);
		$this->SetIdJezyk($IdJezyk);
		$this->SetRodzaj($Rodzaj);
		$this->Setserwisy_GID($serwisy_GID);
		$this->SetParent_GID($Parent_GID);
		$this->SetLp($Lp);
		$this->SetNazwaGlowna($NazwaGlowna);
		$this->SetNazwa($Nazwa);
		$this->SetGrafika($Grafika);
		$this->SetLink($Link);
		$this->SetInne($Inne);
		$this->SetUwagi($Uwagi);
	}

}

?>
