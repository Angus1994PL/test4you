<?php

/**
 * Class provides methods for recording bugs.
 * @author Jakub Konieczka
 *
 */
class Errors {
	
	/**
	 * Writes to the database information about the error.
	 * @param string $methodName
	 * @param string $message
	 */
	public static function LogError($methodName, $message){
		if(Config::$ShowErrors){
			echo "method: $methodName, message: $message<br />";
		}
        error_log("method: $methodName, message: $message");
	}
	
	/**
	 * Writes to the log.txt file given message with datetime stamp.
	 * @param string $message
	 */
	public static function LogError2File($message){
		try{
			$handle = fopen("log.txt", "a");
			fwrite($handle, date('Y-m-d H:i') . " - " . $message . "\n");
			fclose($handle);
		}catch(Exception $ex){
			self::LogError("LogError2File", $ex->getMessage);
		}
	}
	
	/**
	 * Writes step info to the stepslog.txt
	 * 
	 * @param string message
	 */
	public static function LogSynchroStep($message){
		if(Config::$ShowErrors){
			try{
				$handle = fopen("synchrostep.txt", "a");
				fwrite($handle, date('Y-m-d H:i') . " - " . $message . "\n");
				fclose($handle);
			}catch(Exception $ex){
				self::LogError("LogSynchroStep", $ex->getMessage);
			}
		}
	}
	
}

?>