<?php

/**
 * Description of baner
 *
 * @author Jakub Konieczka
 */
class Baner {

	private $_GID;
    private $_IdJezyk;
	private $_serwisy_GID;
	private $_miejsca_grupa_serwisu;
	private $_miejsca_miejsce_serwisu;
	private $_Status;
	private $_DataDodania;
	private $_DataWygasniecia;
	private $_DataEmisji;
	private $_UrlDocelowy;
	private $_GIDGrafiki;
	private $_Embed;

    private $_SerwisObj = null;
    private $_IsSerwisObjSet = false;
    private $_MiejsceSerwisuObj = null;
    private $_IsMiejsceSerwisuObjSet = false;
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

	public function Getmiejsca_miejsce_serwisu(){
		return $this->_miejsca_miejsce_serwisu;
	}

	public function Setmiejsca_miejsce_serwisu($value){
		$this->_miejsca_miejsce_serwisu = $value;
	}

	public function GetStatus(){
		return $this->_Status;
	}

	public function SetStatus($value){
		$this->_Status = $value;
	}

	public function GetDataDodania(){
		return $this->_DataDodania;
	}

	public function SetDataDodania($value){
		$this->_DataDodania = $value;
	}

	public function GetDataWygasniecia(){
		return $this->_DataWygasniecia;
	}

	public function SetDataWygasniecia($value){
		$this->_DataWygasniecia = $value;
	}

	public function GetDataEmisji(){
		return $this->_DataEmisji;
	}

	public function SetDataEmisji($value){
		$this->_DataEmisji = $value;
	}

	public function GetUrlDocelowy(){
		return $this->_UrlDocelowy;
	}

	public function SetUrlDocelowy($value){
		$this->_UrlDocelowy = $value;
	}

	public function GetGIDGrafiki(){
		return $this->_GIDGrafiki;
	}

	public function SetGIDGrafiki($value){
		$this->_GIDGrafiki = $value;
	}

	public function GetEmbed(){
		return $this->_Embed;
	}

	public function SetEmbed($value){
		$this->_Embed = $value;
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

	public function __construct($GID, $IdJezyk, $serwisy_GID, $miejsca_grupa_serwisu, $miejsca_miejsce_serwisu, $Status, $DataDodania, $DataWygasniecia, $DataEmisji,
            $UrlDocelowy, $GIDGrafiki, $Embed){
		$this->SetGID($GID);
        $this->SetIdJezyk($IdJezyk);
		$this->Setserwisy_GID($serwisy_GID);
		$this->Setmiejsca_grupa_serwisu($miejsca_grupa_serwisu);
		$this->Setmiejsca_miejsce_serwisu($miejsca_miejsce_serwisu);
		$this->SetStatus($Status);
		$this->SetDataDodania($DataDodania);
		$this->SetDataWygasniecia($DataWygasniecia);
		$this->SetDataEmisji($DataEmisji);
		$this->SetUrlDocelowy($UrlDocelowy);
		$this->SetGIDGrafiki($GIDGrafiki);
		$this->SetEmbed($Embed);
	}

}

?>
