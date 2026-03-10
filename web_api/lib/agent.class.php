<?php

/**
 * Class describing the agent.
 * @author Jakub Konieczka
 *
 */
class Agent extends AObject{

	private $_Name;
	private $_Phone;
	private $_Cell;
	private $_Email;
	private $_DepartmentId;
	private $_JabberLogin;
	private $_LicenceNo;
	private $_ResponsibleName;
	private $_ResponsibleLicenceNo;
    private $_Comunicators;
    private $_PhotoFile;
    private $_AgentsCode;
    private $_Section;

	private $_DepartmentObj = null; 
	
	public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}

	public function GetPhone(){
		return $this->_Phone;
	}

	public function SetPhone($value){
		$this->_Phone = $value;
	}

	public function GetCell(){
		return $this->_Cell;
	}

	public function SetCell($value){
		$this->_Cell = $value;
	}

	public function GetEmail(){
		return $this->_Email;
	}

	public function SetEmail($value){
		$this->_Email = $value;
	}

	public function GetDepartmentId(){
		return $this->_DepartmentId;
	}

	public function SetDepartmentId($value){
		$this->_DepartmentId = $value;
	}

	public function GetJabberLogin(){
		return $this->_JabberLogin;
	}

	public function SetJabberLogin($value){
		$this->_JabberLogin = $value;
	}

	public function GetLicenceNo(){
		return $this->_LicenceNo;
	}

	public function SetLicenceNo($value){
		$this->_LicenceNo = $value;
	}

	public function GetResponsibleName(){
		return $this->_ResponsibleName;
	}

	public function SetResponsibleName($value){
		$this->_ResponsibleName = $value;
	}

	public function GetResponsibleLicenceNo(){
		return $this->_ResponsibleLicenceNo;
	}

	public function SetResponsibleLicenceNo($value){
		$this->_ResponsibleLicenceNo = $value;
	}

    public function GetComunicators(){
		return $this->_Comunicators;
	}

	public function SetComunicators($value){
		$this->_Comunicators = $value;
	}
    
    public function GetPhotoFile(){
		return $this->_PhotoFile;
	}

	public function SetPhotoFile($value){
		$this->_PhotoFile = $value;
	}
    
    public function GetAgentsCode(){
		return $this->_AgentsCode;
	}

	public function SetAgentsCode($value){
		$this->_AgentsCode = $value;
	}
    
    public function GetSection(){
		return $this->_Section;
	}

	public function SetSection($value){
		$this->_Section = $value;
	}

	/**
	 * Return department as object.
	 * @return Department
	 */
	public function GetDepartmentObj(){
		if($this->_DepartmentObj == null){
			$this->_DepartmentObj = Departments::GetDepartment($this->GetDepartmentId());
		}
		return $this->_DepartmentObj;
	}

    /**
	 * Return path to agent photo file, if file not exist, download it and save on disk.
	 * @param string $customSize Custom size, written as width_height, ex. 400_300
	 * @return string
	 */
	public function GetPhotoImageSrc($customSize){
        if($this->GetPhotoFile() == "") return "";
		$api = new WebAPI();
		return $api->GetAgentDepartmentPhoto($this->GetId(), $customSize, 3, str_replace(array('-', ':', ' ','.jpg'), array(''), $this->GetPhotoFile()));
	}

	
	public function __construct($id, $name, $phone, $cell, $email, $departmentId, $jabberLogin, $licenseNo, $responsibleName, $responsibleLicenceNo, $comunicators, $photoFile, $agentsCode, $section){
		$this->SetId($id);
		$this->SetName($name);
		$this->SetPhone($phone);
		$this->SetCell($cell);
		$this->SetEmail($email);
		$this->SetDepartmentId($departmentId);
		$this->SetJabberLogin($jabberLogin);
		$this->SetLicenceNo($licenseNo);
		$this->SetResponsibleName($responsibleName);
		$this->SetResponsibleLicenceNo($responsibleLicenceNo);
        $this->SetComunicators($comunicators);
        $this->SetPhotoFile($photoFile);
        $this->SetAgentsCode($agentsCode);
        $this->SetSection($section);
	}

}

?>