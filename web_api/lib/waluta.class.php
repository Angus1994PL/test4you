<?php

/**
 * Description of waluta
 *
 * @author Jakub Konieczka
 */
class Waluta {
    
    private $_Symbol;
    private $_Kurs;
    private $_Opis;

    public function GetSymbol(){
		return $this->_Symbol;
	}

	public function SetSymbol($value){
		$this->_Symbol = $value;
	}

    public function GetKurs(){
		return $this->_Kurs;
	}

	public function SetKurs($value){
		$this->_Kurs = $value;
	}

    public function GetOpis(){
		return $this->_Opis;
	}

	public function SetOpis($value){
		$this->_Opis = $value;
	}

    public function __construct($symbol, $kurs, $opis){
		$this->SetSymbol($symbol);
        $this->SetKurs($kurs);
        $this->SetOpis($opis);
	}

}
?>
