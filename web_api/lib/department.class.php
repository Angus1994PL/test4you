<?php

/**
 * Class describing a department.
 * @author Jakub Konieczka
 *
 */
class Department extends AObject{
	
	private $_Name;
	private $_Name2;
	private $_Address;
	private $_City;
	private $_PostCode;
	private $_Nip;
	private $_Province;
	private $_Www;
	private $_Phone;	
	private $_Email;
	private $_Fax;
	private $_Remarks;
    private $_Header;
    private $_Footer;
    private $_LogoFile;
    private $_PhotoFile;
    Private $_Subdomena;
    Private $_OrganizationID;
	
	public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}

    public function GetName2(){
		return $this->_Name2;
	}

	public function SetName2($value){
		$this->_Name2 = $value;
	}

	public function GetAddress(){
		return $this->_Address;
	}

	public function SetAddress($value){
		$this->_Address = $value;
	}

	public function GetCity(){
		return $this->_City;
	}

	public function SetCity($value){
		$this->_City = $value;
	}

	public function GetPostCode(){
		return $this->_PostCode;
	}

	public function SetPostCode($value){
		$this->_PostCode = $value;
	}

	public function GetNip(){
		return $this->_Nip;
	}

	public function SetNip($value){
		$this->_Nip = $value;
	}
	
	public function GetProvince(){
		return $this->_Province;
	}

	public function SetProvince($value){
		$this->_Province = $value;
	}
	
	public function GetWww(){
		return $this->_Www;
	}

	public function SetWww($value){
		$this->_Www = $value;
	}
	
	public function GetPhone(){
		return $this->_Phone;
	}

	public function SetPhone($value){
		$this->_Phone = $value;
	}

	public function GetEmail(){
		return $this->_Email;
	}

	public function SetEmail($value){
		$this->_Email = $value;
	}

	public function GetFax(){
		return $this->_Fax;
	}

	public function SetFax($value){
		$this->_Fax = $value;
	}
	
	public function GetRemarks(){
		return $this->_Remarks;
	}

	public function SetRemarks($value){
		$this->_Remarks = $value;
	}

    public function GetHeader(){
		return $this->_Header;
	}

	public function SetHeader($value){
		$this->_Header = $value;
	}
    
    public function GetFooter(){
		return $this->_Footer;
	}

	public function SetFooter($value){
		$this->_Footer = $value;
	}
    
    public function GetLogoFile(){
		return $this->_LogoFile;
	}

	public function SetLogoFile($value){
		$this->_LogoFile = $value;
	}
    
    public function GetPhotoFile(){
		return $this->_PhotoFile;
	}

	public function SetPhotoFile($value){
		$this->_PhotoFile = $value;
	}

    public function GetSubdomena(){
		return $this->_Subdomena;
	}

	public function SetSubdomena($value){
		$this->_Subdomena = $value;
	}
        
        public function GetOrganizationID(){
		return $this->_OrganizationID;
	}

	public function SetOrganizationID($value){
		$this->_OrganizationID = $value;
	}

    /**
	 * Return path to logo file, if file not exist, download it and save on disk.
	 * @param string $customSize Custom size, written as width_height, ex. 400_300
	 * @return string
	 */
	public function GetLogoImageSrc($customSize){
        if($this->GetLogoFile() == "") return "";
		$api = new VirgoAPI();
		return $api->GetAgentDepartmentPhoto($this->GetId(), $customSize, 1, str_replace(array('-', ':', ' '), array(''), $this->GetLogoFile()));
	}

    /**
	 * Return path to department photo file, if file not exist, download it and save on disk.
	 * @param string $customSize Custom size, written as width_height, ex. 400_300
	 * @return string
	 */
	public function GetPhotoImageSrc($customSize){
        if($this->GetPhotoFile() == "") return "";
		$api = new VirgoAPI();
		return $api->GetAgentDepartmentPhoto($this->GetId(), $customSize, 2, str_replace(array('-', ':', ' '), array(''), $this->GetPhotoFile()));
	}
    

	public function __construct($id, $name, $name2, $addres, $city, $postCode, $nip, $province, $www, $phone, $email, $fax, $remarks, $header, $footer, $logoFile, $photoFile, $subdomena, $organizationId){
		$this->SetId($id);
		$this->SetName($name);
        $this->SetName2($name2);
		$this->SetAddress($addres);
		$this->SetCity($city);
		$this->SetPostCode($postCode);
		$this->SetNip($nip);
		$this->SetProvince($province);
		$this->SetWww($www);
		$this->SetPhone($phone);		
		$this->SetEmail($email);
		$this->SetFax($fax);
		$this->SetRemarks($remarks);
		$this->SetHeader($header);
		$this->SetFooter($footer);
		$this->SetLogoFile($logoFile);
		$this->SetPhotoFile($photoFile);
        $this->SetSubdomena($subdomena);
        $this->SetOrganizationID($organizationId);
	}
	
}

?>