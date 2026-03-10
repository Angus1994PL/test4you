<?php

/**
 * Description of artykul_parametr
 *
 * @author Jakub Konieczka
 */
class ArtykulParametr {

	private $_GID;
	private $_IdJezyk;
	private $_artykuly_GID;
	private $_ParamNazwa;
	private $_Nazwa;
	private $_Naglowek;
	private $_Stopka;

    private $_ArtykulObj = null;
    private $_IsArtykulObjSet = false;

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

	public function Getartykuly_GID(){
		return $this->_artykuly_GID;
	}

	public function Setartykuly_GID($value){
		$this->_artykuly_GID = $value;
	}

	public function GetParamNazwa(){
		return $this->_ParamNazwa;
	}

	public function SetParamNazwa($value){
		$this->_ParamNazwa = $value;
	}

	public function GetNazwa(){
		return $this->_Nazwa;
	}

	public function SetNazwa($value){
		$this->_Nazwa = $value;
	}

	public function GetNaglowek(){
		return $this->_Naglowek;
	}

	public function SetNaglowek($value){
		$this->_Naglowek = $value;
	}

	public function GetStopka(){
		return $this->_Stopka;
	}

	public function SetStopka($value){
		$this->_Stopka = $value;
	}

    /**
	 * Return artykul object.
	 * @return Artykul
	 */
	public function GetArtykul(){
		if($this->_IsArtykulObjSet == false){
			$this->_ArtykulObj = Artykuly::GetArtykul($this->Getartykuly_GID(), $this->GetIdJezyk());
            $this->_IsArtykulObjSet = true;
		}
		return $this->_ArtykulObj;
	}    

	public function __construct($GID, $IdJezyk, $artykuly_GID, $ParamNazwa, $Nazwa, $Naglowek, $Stopka){
		$this->SetGID($GID);
		$this->SetIdJezyk($IdJezyk);
		$this->Setartykuly_GID($artykuly_GID);
		$this->SetParamNazwa($ParamNazwa);
		$this->SetNazwa($Nazwa);
		$this->SetNaglowek($Naglowek);
		$this->SetStopka($Stopka);
	}

}

?>
