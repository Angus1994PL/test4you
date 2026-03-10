<?php

/**
 * Class describing a property of offers.
 * @author Jakub Konieczka
 *
 */
class Property {

	private $_ID;
	private $_Name;
	private $_Date;

	public function GetID(){
		return $this->_ID;
	}

	public function SetID($value){
		$this->_ID = $value;
	}

	public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}

	public function GetDate(){
		return $this->_Date;
	}

	public function SetDate($value){
		$this->_Date = $value;
	}

	public function __construct($ID, $Name, $Date){		
		$this->SetID($ID);
		$this->SetName($Name);
		$this->SetDate($Date);
	}

}

?>