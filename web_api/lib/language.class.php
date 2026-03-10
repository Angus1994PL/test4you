<?php

/**
 * Description of language.
 *
 * @author Jakub Konieczka
 */
class Language {
    
    private $_Id;
    private $_Name;
    private $_ShortName;
    
    public function GetId(){
		return $this->_Id;
	}

	public function SetId($value){
		$this->_Id = $value;
	}

    public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}
    
    public function GetShortName(){
		return $this->_ShortName;
	}

	public function SetShortName($value){
		$this->_ShortName = $value;
	}

    public function __construct($Id){
		$this->SetId($Id);
        switch ($Id) {
            case 1026: $this->SetName("Bulgarian"); $this->SetShortName("bg"); break;
            case 1029: $this->SetName("Czech"); $this->SetShortName("cs"); break;
            case 1030: $this->SetName("Danish"); $this->SetShortName("da"); break;
            case 1031: $this->SetName("German"); $this->SetShortName("de"); break;
            case 1032: $this->SetName("Greek"); $this->SetShortName("gr"); break;
            case 1034: $this->SetName("Spain"); $this->SetShortName("es"); break;
            case 1035: $this->SetName("Finnish"); $this->SetShortName("fi"); break;
            case 1036: $this->SetName("French"); $this->SetShortName("fr"); break;
            case 1037: $this->SetName("Hebrew"); $this->SetShortName("he"); break;
            case 1038: $this->SetName("Hungarian"); $this->SetShortName("hu"); break;
            case 1040: $this->SetName("Italian"); $this->SetShortName("it"); break;
            case 1041: $this->SetName("Japanese"); $this->SetShortName("ja"); break;
            case 1042: $this->SetName("Korean"); $this->SetShortName("ko"); break;
            case 1043: $this->SetName("Dutch - Netherlands"); $this->SetShortName("nl"); break;
            case 1044: $this->SetName("Norwegian"); $this->SetShortName("no"); break;
            case 1045: $this->SetName("Polski"); $this->SetShortName("pl"); break;
            case 1048: $this->SetName("Romanian"); $this->SetShortName("ro"); break;
            case 1049: $this->SetName("Russian"); $this->SetShortName("ru"); break;
            case 1050: $this->SetName("Croatian"); $this->SetShortName("hr"); break;
            case 1051: $this->SetName("Slovak"); $this->SetShortName("sk"); break;
            case 1053: $this->SetName("Swedish"); $this->SetShortName("sv"); break;
            case 1055: $this->SetName("Turkish"); $this->SetShortName("tr"); break;
            case 1058: $this->SetName("Ukrainian"); $this->SetShortName("uk"); break;
            case 1059: $this->SetName("Belarusian"); $this->SetShortName("be"); break;
            case 1060: $this->SetName("Slovenian"); $this->SetShortName("sl"); break;
            case 1061: $this->SetName("Estonian"); $this->SetShortName("et"); break;
            case 1062: $this->SetName("Latvian"); $this->SetShortName("lv"); break;
            case 1063: $this->SetName("Lithuanian"); $this->SetShortName("lt"); break;
            case 1066: $this->SetName("Vietnamese"); $this->SetShortName("vi"); break;
            case 1077: $this->SetName("Zulu"); $this->SetShortName("zu"); break;
            case 1106: $this->SetName("Welsh"); $this->SetShortName("cy"); break;
            case 1142: $this->SetName("Latin"); $this->SetShortName("la"); break;
            case 2047: $this->SetName("English"); $this->SetShortName("en"); break;
            case 2052: $this->SetName("Chinese"); $this->SetShortName("zh"); break;
            case 2070: $this->SetName("Portuguese"); $this->SetShortName("pt"); break;
            default: $this->SetName("nieznany"); $this->SetShortName("unk"); break;
        }
	}
    
}

