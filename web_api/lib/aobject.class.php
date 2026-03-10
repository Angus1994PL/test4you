<?php

/**
 * An sbstract class providing ID property.
 * @author Jakub Konieczka
 *
 */
abstract class AObject{
	
	private $_ID;
	
	public function GetId(){
		return $this->_ID;
	}
	
	public function SetId($value){
		$this->_ID = $value;
	}	
	
} 

?>