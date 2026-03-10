<?php

/**
 * Description of galeria
 * @author Jakub Tyniecki
 */
class Galeria {

	private $_GID;
    private $_IdJezyk;
	private $_serwisy_GID;
	private $_miejsca_grupa_serwisu;
	private $_Lp;
	private $_Nazwa;
	private $_Opis;
	private $_SlowaKluczowe;
	private $_Grafika;
    
    private $_Rozmiar1;
    private $_Rozmiar2;
    private $_Rozmiar3;

    private $_SerwisObj = null;
    private $_GaleriePozycjeList = null;
    private $_IsSerwisObjSet = false;
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

	public function GetNazwa(){
		return $this->_Nazwa;
	}

	public function SetNazwa($value){
		$this->_Nazwa = $value;
	}

	public function GetOpis(){
		return $this->_Opis;
	}

	public function SetOpis($value){
		$this->_Opis = $value;
	}

	public function GetSlowaKluczowe(){
		return $this->_SlowaKluczowe;
	}

	public function SetSlowaKluczowe($value){
		$this->_SlowaKluczowe = $value;
	}

	public function GetGrafika(){
		return $this->_Grafika;
	}

	public function SetGrafika($value){
		$this->_Grafika = $value;
	}
    
    public function GetRozmiar1(){
		return $this->_Rozmiar1;
	}

	public function SetRozmiar1($value){
		$this->_Rozmiar1 = $value;
	}

    public function GetRozmiar2(){
		return $this->_Rozmiar2;
	}

	public function SetRozmiar2($value){
		$this->_Rozmiar2 = $value;
	}
    
    public function GetRozmiar3(){
		return $this->_Rozmiar3;
	}

	public function SetRozmiar3($value){
		$this->_Rozmiar3 = $value;
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
	* Return galeriepozycje list.
	* @return ArrayObject
	*/
	public function GetPozycje(){
		if($this->_GaleriePozycjeList == null){
			$this->_GaleriePozycjeList = GaleriePozycje::PobierzGaleriePozycjeJezyki($this->_GID, $this->_IdJezyk);
		}		
		return $this->_GaleriePozycjeList;
	}

	public function __construct($GID, $IdJezyk, $serwisy_GID, $miejsca_grupa_serwisu, $Lp, $Nazwa, $Opis, $SlowaKluczowe, $Grafika, $Rozmiar1, $Rozmiar2, $Rozmiar3){
		$this->SetGID($GID);
        $this->SetIdJezyk($IdJezyk);
		$this->Setserwisy_GID($serwisy_GID);
		$this->Setmiejsca_grupa_serwisu($miejsca_grupa_serwisu);
		$this->SetLp($Lp);
		$this->SetNazwa($Nazwa);
		$this->SetOpis($Opis);
		$this->SetSlowaKluczowe($SlowaKluczowe);
		$this->SetGrafika($Grafika);
        $this->SetRozmiar1($Rozmiar1);
        $this->SetRozmiar2($Rozmiar2);
        $this->SetRozmiar3($Rozmiar3);
	}

}

?>
