<?php

include_once('application/libraries/virgo_api/lib/offershelper.class.php');

if (!defined('WEB_API_DIR')) {
    define('WEB_API_DIR', "application/libraries/web_api");
}

Class ServiceConfig {
	private $_source;
	
	public function GenerateXML(){
		
			
		$this->_source = getcwd().'/'.WEB_API_DIR."/config.cfg";
		
		if(!file_exists($this->_source))
			return array();
		$fo = fopen($this->_source, 'r');
		if(filesize($this->_source)==0)
			return array();
		$xmlstring =  trim(fread($fo, filesize($this->_source)));
		if(strlen(str_replace(' ', '', $xmlstring))==0)
			return array();	
		
		$xml = simplexml_load_string($xmlstring);
		$return = $this->generujTablice($xml);
		
		return $return;
		
	}
	public function generujTablice($node, $array = false){
		
		$return = array();
		foreach($node->children() as $key => $value){
			if(isset($value['id']))
				$key = (string)$value['id'];
			if(count($value->children())==1){
				if($array){
					$return[][$key] = $this->generujTablice($value);
				}else{
					$return[$key] = $this->generujTablice($value);
				}
			}
			if(count($value->children())==0){ 
				$return[$key] = trim((string)$value);
			}
			
			if(count($value->children())>1){
				if($array)
					$return[][$key] = $this->generujTablice($value , true);
				else
					$return[$key] = $this->generujTablice($value , true);
			}
		}
		return $return;
	}
	
	public function GetServiceConfig(){
		return $this->GenerateXML();
	}
	
	public function __call($method, $args) {
		$GetServiceConfig = array();
		$method = strtolower($method);
		$method = str_replace('get','',$method);
		$GetServiceConfig = $this->GetServiceConfig();
		if(isset($GetServiceConfig[$method]))
			return $GetServiceConfig[$method];
		else
			return array();
		
	}
	

}