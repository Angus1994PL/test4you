<?php

/**
 * 
 * @author Jakub Konieczka
 * 
 */
class DataBase {
	
	private static $_instance = null;
	private $_db = null;
    public static $QUERY_COUNT = 0;
	
	function __construct(){	
		if(Config::$Driver == Config::DRIVER_MYSQL)
			$this->_db = new MySql();
		else if(Config::$Driver == Config::DRIVER_POSTGRESQL)
			$this->_db = new PostgreSql();
		else
			die("Not supported database: " . Config::$Driver);
		$this->_db->Connect(Config::$Server, Config::$Port, Config::$DbName, Config::$Schema, Config::$UserName, Config::$Password);
	}
	
	function __destruct(){
		if($this->_db != null)
			$this->_db->Disconnect();
	}
	
	/**
	 * Returns instance of IDataBase interface object.  
	 * @return IDataBase
	 */
	public static function GetDbInstance(){
		if(self::$_instance == null) 
			self::$_instance = new DataBase();
		return self::$_instance->_db;
	}
	
	
}

?>