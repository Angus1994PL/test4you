<?php

echo "<h3>Updating web_api database</h3>";
echo "<br />";

try{
    $db = DataBase::GetDbInstance();
    AddColumn($db, "menu", "NoFollow", "TINYINT( 1 )");
    AddColumn($db, "departments", "header", "TEXT");
    AddColumn($db, "departments", "footer", "TEXT");
    AddColumn($db, "departments", "logo_file", "VARCHAR( 50 )");
    AddColumn($db, "departments", "photo_file", "VARCHAR( 50 )");
    AddColumn($db, "departments", "subdomena", "VARCHAR( 30 )");
    AddColumn($db, "departments", "organization_id", "INT NULL");
	AddColumn($db, "departments", "name2", "VARCHAR( 255 )");
    AddColumn($db, "agents", "comunicators", "VARCHAR( 500 )");
    AddColumn($db, "agents", "photo_file", "VARCHAR( 50 )");
    AddColumn($db, "agents", "agents_code", "INT");
    AddColumn($db, "agents", "section", "VARCHAR( 50 )");

    AddColumn($db, "artykuly", "galerie_GID", "INT NULL");
    AddIndex($db, "artykuly", "galerie_GID", '`galerie_GID`');

    AddColumn($db, "galerie", "Rozmiar1", "VARCHAR( 9 )");
    AddColumn($db, "galerie", "Rozmiar2", "VARCHAR( 9 )");
    AddColumn($db, "galerie", "Rozmiar3", "VARCHAR( 9 )");

	AddColumn($db, "artykuly", "Tagi", "VARCHAR( 500 )");
    AddColumn($db, "artykuly", "DataRozpoczeciaPublikacji", "VARCHAR( 500 )");

    AddColumn($db, "galeriepozycje", "Tagi", "VARCHAR( 500 )");

    AddColumn($db, "artykuly", "NoFollow", "TINYINT( 1 )", 'DEFAULT 0');

    if(ColumnExist($db, 'offers_properties', 'hash')) {
        DropIndex($db, 'offers_properties', 'offers_id_uniq');
        AddIndex($db, 'offers_properties', 'offers_id_uniq', '`offers_id`,`offers_id_lng`,`properties_id`,`value`(32)', true);
        DropIndex($db, 'offers_properties', 'hash');
        DropIndex($db, 'offers_properties', 'offers_id_2');
        DropColumn($db, "offers_properties", "hash");
    }


    $sql = "CREATE TABLE IF NOT EXISTS `osoby` (
            `id` INT NOT NULL ,
            `name` VARCHAR(50) NULL  ,
            `last_name` VARCHAR(50) NULL  ,
            `email` VARCHAR(50) NULL  ,
            `phone` VARCHAR(20) NULL  ,
            `login` VARCHAR(100) NULL  ,
            `pwd` VARCHAR(100) NULL  ,
            `registration_date` DATE NULL ,
            `user_id` INT NULL ,
            PRIMARY KEY ( `id`)
            ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

    $result = $db->ExecuteQuery($sql);
    echo "Creating table osoby: OK<br />";
    if($db->LastError() != '') throw new Exception("table osoby:" . $db->LastError());
	echo "<h4>Updating web_api database complete.</h4>";

}catch (Exception $ex){
	echo "Execution error:<br />" . $ex->getMessage();
	echo "<h4>Installing database failed. Check configuration (config.php file) and try again. If the problem persists, please contact with us.</h4>";
}
