<?php

/**
 * Class describing the error that occurred in the system, and was saved.
 * @author Jakub Konieczka
 *
 */
class Error {

	private $_Id;
	private $_Date;
	private $_Method;
	private $_Message;

	public function GetId(){
		return $this->_Id;
	}

	public function SetId($value){
		$this->_Id = $value;
	}

	public function GetDate(){
		return $this->_Date;
	}

	public function SetDate($value){
		$this->_Date = $value;
	}

	public function GetMethod(){
		return $this->_Method;
	}

	public function SetMethod($value){
		$this->_Method = $value;
	}

	public function GetMessage(){
		return $this->_Message;
	}

	public function SetMessage($value){
		$this->_Message = $value;
	}


	public function __construct($Id, $Date, $Method, $Message){		
		$this->SetId($Id);
		$this->SetDate($Date);
		$this->SetMethod($Method);
		$this->SetMessage($Message);

	}
	
}

?>