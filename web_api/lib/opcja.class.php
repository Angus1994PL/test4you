<?php

/**
 * Description of opcja
 *
 * @author Jakub Konieczka
 */
class Opcja {

    private $_Klucz;
    private $_Wartosc;

    public function GetKlucz(){
		return $this->_Klucz;
	}

	public function SetKlucz($value){
		$this->_Klucz = $value;
	}

    public function GetWartosc(){
		return $this->_Wartosc;
	}

	public function SetWartosc($value){
		$this->_Wartosc = $value;
	}

    public function __construct($klucz, $wartosc){
		$this->SetKlucz($klucz);
        $this->SetWartosc($wartosc);
	}

}
?>
