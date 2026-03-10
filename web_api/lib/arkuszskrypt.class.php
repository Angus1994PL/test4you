<?php

/**
 * Description of arkusz_skrypt
 * @author Jakub Konieczka
 */
class ArkuszSkrypt {

	private $_GID;
	private $_serwisy_GID;
	private $_Rodzaj;
	private $_Opis;
	private $_Tresc;
	private $_RodzajArkusza;

    private $_SerwisObj = null;
    private $_IsSerwisObjSet = false;
    
	public function GetGID(){
		return $this->_GID;
	}

	public function SetGID($value){
		$this->_GID = $value;
	}

	public function Getserwisy_GID(){
		return $this->_serwisy_GID;
	}

	public function Setserwisy_GID($value){
		$this->_serwisy_GID = $value;
	}

	public function GetRodzaj(){
		return $this->_Rodzaj;
	}

	public function SetRodzaj($value){
		$this->_Rodzaj = $value;
	}

	public function GetOpis(){
		return $this->_Opis;
	}

	public function SetOpis($value){
		$this->_Opis = $value;
	}

	public function GetTresc(){
		return $this->_Tresc;
	}

	public function SetTresc($value){
		$this->_Tresc = $value;
	}

	public function GetRodzajArkusza(){
		return $this->_RodzajArkusza;
	}

	public function SetRodzajArkusza($value){
		$this->_RodzajArkusza = $value;
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

	public function __construct($GID, $serwisy_GID, $Rodzaj, $Opis, $Tresc, $RodzajArkusza){
		$this->SetGID($GID);
		$this->Setserwisy_GID($serwisy_GID);
		$this->SetRodzaj($Rodzaj);
		$this->SetOpis($Opis);
		$this->SetTresc($Tresc);
		$this->SetRodzajArkusza($RodzajArkusza);

	}

}

?>
