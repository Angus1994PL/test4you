<?php

/**
 * Description of jezyk
 *
 * @author Jakub Konieczka
 */
class JezykTekst {

    private $_Klucz;
    private $_IdJezyk;
    private $_Wartosc;

    public function GetKlucz(){
		return $this->_Klucz;
	}

	public function SetKlucz($value){
		$this->_Klucz = $value;
	}

    public function GetIdJezyk(){
		return $this->_IdJezyk;
	}

	public function SetIdJezyk($value){
		$this->_IdJezyk = $value;
	}

    public function GetWartosc(){
		return $this->_Wartosc;
	}

	public function SetWartosc($value){
		$this->_Wartosc = $value;
	}

    public function __construct($klucz, $IdJezyk, $wartosc){
		$this->SetKlucz($klucz);
        $this->SetIdJezyk($IdJezyk);
        $this->SetWartosc($wartosc);
    }

}
?>
