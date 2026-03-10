<?php

require_once 'web_api/config.php';
require_once 'web_api/db/idatabase.interface.php';
require_once 'web_api/db/mysql.class.php';
require_once 'web_api/db/db.class.php';
require_once 'web_api/lib/errors.class.php';

echo "<h3>Installing the database</h3>";

$err = false;
echo "Extension php_mysql_libmysql.dll: " . CheckExtension("mysql", $err) . "<br />";
echo "Extension php_soap.dll: " . CheckExtension("soap", $err) . "<br />";
echo "Extension xml: " . CheckExtension("xml", $err) . "<br />";
echo "Extension php_zip.dll: " . CheckExtension("zip", $err) . "<br />";
if($err){
	echo "<h4>Installing database failed. Check php.ini and try again. If the problem persists, please contact with your administrator.</h4>";
	exit();
}
echo "<br />";

function CheckExtension($extension, $err){
	if(extension_loaded($extension)) return "ENABLED";
	else {$err = true; return "DISABLED";}
}

try{
	Config::$SaveErrorToDataBase = false;
	$db = DataBase::GetDbInstance();
	$result = $db->ExecuteQuery("SELECT now()");
	$err = $db->LastError();
	if($err != "") throw new Exception("Connection failed:<br />" . $db->LastError());
	else echo "Connection parameters OK.<br />";

	$path = "../photos";
	if (!file_exists($path)) {mkdir($path);}

}catch (Exception $ex){
	echo $ex->getMessage();
	echo "<h4>Installing database failed. Check configuration (config.php file) and try again. If the problem persists, please contact with us.</h4>";
	exit("");
}

$api_version = 0;

try{
    $sql = "CREATE TABLE IF NOT EXISTS `settings` (
	`key_name` VARCHAR( 100 ) NOT NULL PRIMARY KEY ,
	`value` VARCHAR( 200 ) NOT NULL ,
	INDEX ( `key_name` )
	) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

	$result = $db->ExecuteQuery($sql);
	echo "Creating table settings: OK<br />";
	if($db->LastError() != '') throw new Exception("table settings:" . $db->LastError());

    $sql = "SELECT s.value FROM settings s WHERE s.key_name = 'virgo_api_version'";
    $result = $db->ExecuteQuery($sql);
    if($db->FetchArray($result)){
        while($row = $db->FetchArray($result)){
            if((int) $row['value'] < 5){
                $api_version = 5;
                $sql = "INSERT INTO settings (key_name ,value) VALUES ('virgo_api_version','5')";
                $db->ExecuteQuery($sql);
                $sql = "DROP TABLE IF EXISTS offers_multi_properties; DROP TABLE IF EXISTS offers_properties; DROP TABLE IF EXISTS investments_properties;";
                $db->ExecuteQuery($sql);
                break;
            }
        }
    }else{
        $api_version = 5;
        $sql = "INSERT INTO settings (key_name ,value) VALUES ('virgo_api_version','5')";
        $db->ExecuteQuery($sql);
        $sql = "DROP TABLE IF EXISTS offers_multi_properties; DROP TABLE IF EXISTS offers_properties; DROP TABLE IF EXISTS investments_properties;";
        $db->ExecuteQuery($sql);
    }

    $sql = "DROP TABLE IF EXISTS errors";
    $result = $db->ExecuteQuery($sql);
	if($db->LastError() != '') throw new Exception("table errors:" . $db->LastError());

    $sql = "CREATE TABLE IF NOT EXISTS `departments` (
	`id` INT NOT NULL PRIMARY KEY ,
	`name` VARCHAR( 255 ) NOT NULL ,
    `name2` VARCHAR( 255 ) NULL ,
	`address` VARCHAR( 150 ) NULL ,
	`city` VARCHAR( 100 ) NULL ,
	`postcode` VARCHAR( 6 ) NULL ,
	`nip` VARCHAR( 20 ) NULL ,
	`province` VARCHAR( 50 ) NOT NULL ,
	`www` VARCHAR( 150 ) NULL ,
	`phone` VARCHAR( 150 ) NULL ,
	`email` VARCHAR( 150 ) NULL ,
	`fax` VARCHAR( 150 ) NULL ,
	`remarks` TEXT NULL ,
    `header` TEXT NULL ,
    `footer` TEXT NULL ,
    `logo_file` VARCHAR( 50 ) NULL ,
    `photo_file` VARCHAR( 50 ) NULL ,
	`subdomena` VARCHAR( 30 ) NULL ,
	`organization_id` INT NULL,
    INDEX ( `organization_id` )
	) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

	$result = $db->ExecuteQuery($sql);
	echo "Creating table departments: OK<br />";
	if($db->LastError() != '') throw new Exception("table departments:" . $db->LastError());

	$sql = "CREATE TABLE IF NOT EXISTS `agents` (
	`id` INT NOT NULL PRIMARY KEY ,
	`name` VARCHAR( 50 ) NOT NULL ,
	`phone` VARCHAR( 50 ) NULL ,
	`cell` VARCHAR( 50 ) NULL ,
	`email` VARCHAR( 50 ) NULL ,
	`departments_id` INT NOT NULL REFERENCES departments(id) ,
	`jabber_login` VARCHAR( 20 ) NULL ,
	`licence_no` VARCHAR( 50 ) NULL ,
	`responsible_name` VARCHAR( 50 ) NULL ,
	`responsible_licence_no` VARCHAR( 50 ) NULL ,
    `comunicators` VARCHAR( 500 ) NULL ,
    `photo_file` VARCHAR( 50 ) NULL,
    `agents_code` INT NULL,
    `section` VARCHAR( 255 ) NULL,
	INDEX ( `departments_id` )
	) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

	$result = $db->ExecuteQuery($sql);
	echo "Creating table agents: OK<br />";
	if($db->LastError() != '') throw new Exception("table agents:" . $db->LastError());

    if(Config::$Framework == Config::FRAMEWORK_CODEIGNITER){
		$sql = "CREATE TABLE IF NOT EXISTS  `ci_sessions` (
			session_id varchar(40) DEFAULT '0' NOT NULL,
			ip_address varchar(16) DEFAULT '0' NOT NULL,
			user_agent varchar(255) NOT NULL,
			last_activity int(10) unsigned DEFAULT 0 NOT NULL,
			user_data text DEFAULT ('') NOT NULL,
			PRIMARY KEY (session_id)
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
		$result = $db->ExecuteQuery($sql);
        $result = $db->ExecuteQuery('ALTER TABLE ci_sessions MODIFY user_data text CHARACTER SET utf8;');
		echo "Creating table ci_sessions: OK<br />";
		if($db->LastError() != '') throw new Exception("table ci_sessions:" . $db->LastError());
	}

    if (isset(Config::$Moduly)){
        if ($api_version < 5 && (!isset(Config::$Moduly["web_api"]) || Config::$Moduly["web_api"] !== true)) require_once "web_api/install_my.php";
        foreach (Config::$Moduly as $modul=>$status) {
            if ($status === true) {
                require_once $modul."/install_my.php";
            }
        }
    }

    echo "<h4>Installing database complete.</h4>";
}catch (Exception $ex){
	echo "Execution error:<br />" . $ex->getMessage();
	echo "<h4>Installing database failed. Check configuration (config.php file) and try again. If the problem persists, please contact with us.</h4>";
}

function AddColumn($db, $tableName, $columnName, $columnType, $default = ""){
    $add = false;
    $result = $db->ExecuteQuery("SHOW columns FROM $tableName WHERE field='$columnName'");
    $row = $db->FetchArray($result);
    $add = $row == null;
    if($add){
        $sql = "ALTER TABLE `$tableName` ADD COLUMN `$columnName` $columnType NULL $default;";
        $result = $db->ExecuteQuery($sql);
        echo "Updating table $tableName: OK, column added: $columnName<br />";
        if($db->LastError() != '') throw new Exception("table $tableName:" . $db->LastError());
    }
}

function AddIndex($db, $tableName, $indexName, $columns, $unique = false){
    $add = false;
    $result = $db->ExecuteQuery("SHOW index FROM $tableName WHERE Key_name='$indexName'");
    $row = $db->FetchArray($result);
    $add = $row == null;
    if($add){
        if($unique) {
            $sql = "ALTER TABLE `$tableName` ADD UNIQUE `$indexName` ($columns);";
        }
        else {
            $sql = "ALTER TABLE `$tableName` ADD INDEX `$indexName` ($columns);";
        }
        $result = $db->ExecuteQuery($sql);
        echo "Updating table $tableName: OK, index added: $indexName<br />";
        if($db->LastError() != '') throw new Exception("table $tableName:" . $db->LastError());
    }
}

function DropIndex($db, $tableName, $indexName)
{
    $result = $db->ExecuteQuery("SHOW index FROM $tableName WHERE Key_name='$indexName'");
    $row = $db->FetchArray($result);
    $drop = $row != null;
    if ($drop) {
        $sql = "ALTER TABLE `$tableName` DROP INDEX `$indexName`;";
        $result = $db->ExecuteQuery($sql);
        echo "Updating table $tableName: OK, index dropped: $indexName<br />";
        if ($db->LastError() != '')
            throw new Exception("table $tableName:" . $db->LastError());
    }
}

function DropColumn($db, $tableName, $columnName){
    $drop = false;
    $result = $db->ExecuteQuery("SHOW columns FROM $tableName WHERE field='$columnName'");
    $row = $db->FetchArray($result);
    $drop = $row != null;
    if($drop){
        $sql = "ALTER TABLE `$tableName` DROP `$columnName`;";
        $result = $db->ExecuteQuery($sql);
        echo "Updating table $tableName: OK, column dropped: $columnName<br />";
        if($db->LastError() != '') throw new Exception("table $tableName:" . $db->LastError());
    }
}

function ColumnExist($db, $tableName, $columnName)
{
    $result = $db->ExecuteQuery("SHOW columns FROM $tableName WHERE field='$columnName'");
    $row = $db->FetchArray($result);

    return $row != null;
}
