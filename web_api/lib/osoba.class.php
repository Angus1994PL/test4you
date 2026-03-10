<?php

/**
 * Class describing the Osoba
 * @author marcinw
 */
class Osoba extends AObject {
    
    private $_Name;
    private $_LastName;
    private $_Email;
    private $_Phone;
    private $_Login;
    private $_Pwd;
    private $_RegistrationDate;
    private $_UserId;
    
    public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}
    
    public function GetLastName(){
		return $this->_Name;
	}

	public function SetLastName($value){
		$this->_LastName = $value;
	}
    
    public function GetEmail(){
		return $this->_Email;
	}

	public function SetEmail($value){
		$this->_Email = $value;
	}
    
    public function GetPhone(){
		return $this->_Phone;
	}

	public function SetPhone($value){
		$this->_Phone = $value;
	}
    
    public function GetLogin(){
		return $this->_Login;
	}

	public function SetLogin($value){
		$this->_Login = $value;
	}
    
    public function GetPwd(){
		return $this->_Pwd;
	}

	public function SetPwd($value){
		$this->_Pwd = $value;
	}
    
    public function GetRegistrationDate(){
		return $this->_RegistrationDate;
	}

	public function SetRegistrationDate($value){
		$this->_RegistrationDate = $value;
	}
    
    public function GetUserId(){
		return $this->_UserId;
	}

	public function SetUserId($value){
		$this->_UserId = $value;
	}
    
    public function __construct($id, $name, $last_name, $email, $phone, $login, $pwd, $reg_date, $user_id){
		$this->SetId($id);
		$this->SetName($name);
        $this->SetLastName($last_name);
        $this->SetEmail($email);
		$this->SetPhone($phone);
        $this->SetLogin($login);
        $this->SetPwd($pwd);
        $this->SetRegistrationDate($reg_date);
        $this->SetUserId($user_id);
	}
    
}
