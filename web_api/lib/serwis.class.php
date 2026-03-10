<?php

/**
 * Class describing the agent.
 * @author Jakub Konieczka
 */
class Serwis {

	private $_GID;
	private $_IdJezyk;
	private $_NazwaFirmy;
	private $_AdresWWW;
	private $_EmailKontaktowy;
	private $_StartowyJezyk;
	private $_departments_id;
	private $_agents_id;
	private $_Mieszkania;
	private $_Domy;
	private $_Dzialki;
	private $_Lokale;
	private $_Hale;
	private $_Gospodarstwa;
	private $_Kamienice;
	private $_Biurowce;
	private $_RodzajeOfert;
	private $_TagTitle;
	private $_TagKeywords;
	private $_TagDescription;
	private $_Head;
	private $_Body;
	private $_Foot;

    private $_AgentObj = null;
    private $_IsAgentObjSet = false;
    private $_DepartmentObj = null;
    private $_IsDepartmentObjSet = false;
    private $_Parametry = null;
    private $_IsParametrySet = false;

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

	public function GetNazwaFirmy(){
		return $this->_NazwaFirmy;
	}

	public function SetNazwaFirmy($value){
		$this->_NazwaFirmy = $value;
	}

	public function GetAdresWWW(){
		return $this->_AdresWWW;
	}

	public function SetAdresWWW($value){
		$this->_AdresWWW = $value;
	}

	public function GetEmailKontaktowy(){
		return $this->_EmailKontaktowy;
	}

	public function SetEmailKontaktowy($value){
		$this->_EmailKontaktowy = $value;
	}

	public function GetStartowyJezyk(){
		return $this->_StartowyJezyk;
	}

	public function SetStartowyJezyk($value){
		$this->_StartowyJezyk = $value;
	}

	public function Getdepartments_id(){
		return $this->_departments_id;
	}

	public function Setdepartments_id($value){
		$this->_departments_id = $value;
	}

	public function Getagents_id(){
		return $this->_agents_id;
	}

	public function Setagents_id($value){
		$this->_agents_id = $value;
	}

	public function GetMieszkania(){
		return $this->_Mieszkania;
	}

	public function SetMieszkania($value){
		$this->_Mieszkania = $value;
	}

	public function GetDomy(){
		return $this->_Domy;
	}

	public function SetDomy($value){
		$this->_Domy = $value;
	}

	public function GetDzialki(){
		return $this->_Dzialki;
	}

	public function SetDzialki($value){
		$this->_Dzialki = $value;
	}

	public function GetLokale(){
		return $this->_Lokale;
	}

	public function SetLokale($value){
		$this->_Lokale = $value;
	}

	public function GetHale(){
		return $this->_Hale;
	}

	public function SetHale($value){
		$this->_Hale = $value;
	}

	public function GetGospodarstwa(){
		return $this->_Gospodarstwa;
	}

	public function SetGospodarstwa($value){
		$this->_Gospodarstwa = $value;
	}

	public function GetKamienice(){
		return $this->_Kamienice;
	}

	public function SetKamienice($value){
		$this->_Kamienice = $value;
	}

	public function GetBiurowce(){
		return $this->_Biurowce;
	}

	public function SetBiurowce($value){
		$this->_Biurowce = $value;
	}

	public function GetRodzajeOfert(){
		return $this->_RodzajeOfert;
	}

	public function SetRodzajeOfert($value){
		$this->_RodzajeOfert = $value;
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

	public function GetHead(){
		return $this->_Head;
	}

	public function SetHead($value){
		$this->_Head = $value;
	}

	public function GetBody(){
		return $this->_Body;
	}

	public function SetBody($value){
		$this->_Body = $value;
	}

	public function GetFoot(){
		return $this->_Foot;
	}

	public function SetFoot($value){
		$this->_Foot = $value;
	}

    /**
	 * Return agent as object.
	 * @return Agent
	 */
	public function GetUzytkownik(){
		if($this->_IsAgentObjSet == false){
			$this->_AgentObj = Agents::GetAgent($this->Getagents_id());
            $this->_IsAgentObjSet = true;
		}
		return $this->_AgentObj;
	}

    /**
	 * Return department as object.
	 * @return Department
	 */
	public function GetOddzial(){
		if($this->_IsDepartmentObjSet == false){
			$this->_DepartmentObj = Departments::GetDepartment($this->Getdepartments_id());
            $this->_IsDepartmentObjSet = true;
		}
		return $this->_DepartmentObj;
	}

    /**
     * Returns list of service params for this service.
     * @return array
     */
    public function GetParametry(){
		if($this->_IsParametrySet == false){
			$ss = new Serwisy();
			$this->_Parametry = $ss->GetSerwisParametry($this);
            $this->_IsParametrySet = true;
		}
		return $this->_Parametry;
	}

    /**
     * Returns value of given parameter name.
     * @param string $nazwa
     * @param bool $throwExceptionIfNotExists
     * @return string
     */
    public function GetParametr($nazwa, $throwExceptionIfNotExists = true){
        $arr = $this->GetParametry();
        if(array_key_exists($nazwa, $arr))
            return $arr[$nazwa];
        else
            if($throwExceptionIfNotExists)
                throw new Exception("Podany parametr nie istnieje.", 0, null);
            else
                return "";
    }


	public function __construct($GID, $IdJezyk, $NazwaFirmy, $AdresWWW, $EmailKontaktowy, $StartowyJezyk, $departments_id, $agents_id, $Mieszkania, $Domy, $Dzialki, $Lokale,
            $Hale, $Gospodarstwa, $Kamienice, $Biurowce, $RodzajeOfert, $TagTitle, $TagKeywords, $TagDescription, $Head, $Body, $Foot){
		$this->SetGID($GID);
		$this->SetIdJezyk($IdJezyk);
		$this->SetNazwaFirmy($NazwaFirmy);
		$this->SetAdresWWW($AdresWWW);
		$this->SetEmailKontaktowy($EmailKontaktowy);
		$this->SetStartowyJezyk($StartowyJezyk);
		$this->Setdepartments_id($departments_id);
		$this->Setagents_id($agents_id);
		$this->SetMieszkania($Mieszkania);
		$this->SetDomy($Domy);
		$this->SetDzialki($Dzialki);
		$this->SetLokale($Lokale);
		$this->SetHale($Hale);
		$this->SetGospodarstwa($Gospodarstwa);
		$this->SetKamienice($Kamienice);
		$this->SetBiurowce($Biurowce);
		$this->SetRodzajeOfert($RodzajeOfert);
		$this->SetTagTitle($TagTitle);
		$this->SetTagKeywords($TagKeywords);
		$this->SetTagDescription($TagDescription);
		$this->SetHead($Head);
		$this->SetBody($Body);
		$this->SetFoot($Foot);
	}

}

?>
