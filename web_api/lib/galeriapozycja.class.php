<?php

/**
 * Description of galeria pozycja
 * @author Jakub Tyniecki
 */
class GaleriaPozycja {

	private $_GID;
    private $_IdJezyk;
	private $_serwisy_GID;
	private $_galerie_GID;
	private $_Lp;
	private $_Plik;
	private $_PlikRozmiar1;
	private $_PlikRozmiar2;
	private $_PlikRozmiar3;
	private $_Opis;
    private $_Tagi;

    private $_SerwisObj = null;
    private $_IsSerwisObjSet = false;
    private $_GaleriaObj = null;
    private $_IsGaleriaObjSet = false;
    
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

	public function GetPlik(){
		return $this->_Plik;
	}

	public function SetPlik($value){
		$this->_Plik = $value;
	}

	public function GetOpis(){
		return $this->_Opis;
	}
	
	public function GetPlikRozmiar($no){
		$valName = "_PlikRozmiar".$no;
		return $this->$valName;
	}

	public function SetOpis($value){
		$this->_Opis = $value;
	}

    public function GetTagi(){
		return $this->_Tagi;
	}

	public function SetTagi($value){
		$this->_Tagi = $value;
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
	 * Return Galeria object.
	 * @return Galeria
	 */
	public function GetGaleria(){
		if($this->_IsGaleriaObjSet == false){
			$this->_GaleriaObj = Galerie::GetGaleria($this->Getgalerie_GID(), $this->GetIdJezyk());
            $this->_IsGaleriaObjSet = true;
		}
		return $this->_GaleriaObj;
	}

	public function __construct($GID, $IdJezyk, $serwisy_GID, $galerie_GID, $Lp, $Plik, $Opis, $Tagi){
		$this->SetGID($GID);
        $this->SetIdJezyk($IdJezyk);
		$this->Setserwisy_GID($serwisy_GID);
		$this->Setgalerie_GID($galerie_GID);
		$this->SetLp($Lp);
		$this->SetPlik($Plik);
		$this->SetTagi($Tagi);
		
		$this->_PlikRozmiar1 = $this->GetGaleria()->GetRozmiar1() ? "galerie/gal".$this->_galerie_GID."/".$this->GetGID()."_".$this->GetGaleria()->GetRozmiar1().".jpg" : "";
		$this->_PlikRozmiar2 = $this->GetGaleria()->GetRozmiar2() ? "galerie/gal".$this->_galerie_GID."/".$this->GetGID()."_".$this->GetGaleria()->GetRozmiar2().".jpg" : "";
		$this->_PlikRozmiar3 = $this->GetGaleria()->GetRozmiar3() ? "galerie/gal".$this->_galerie_GID."/".$this->GetGID()."_".$this->GetGaleria()->GetRozmiar3().".jpg" : "";
		
		$this->SetOpis($Opis);
	}

}

?>
