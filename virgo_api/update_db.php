<?php

echo "<h3>Updating virgo_api database</h3>";
echo "<br />";

try{
	$db = DataBase::GetDbInstance();
    
    AddColumn($db, "offers_photos", "foto_id", "INT");
	AddColumn($db, "offers", "description_synonim", "TEXT NULL");
    AddIndex($db, "properties", "name", '`name`');
	AddIndex($db, "offers_properties", "value", "`value` ( 100 )");
    DropColumn($db, "offers_rooms_sets", "id");
    AddColumn($db, "offers", "display_number", "INT NOT", "DEFAULT 0");
    
    DropColumn($db, "offers", "field_destiny");
    DropColumn($db, "offers", "local_destiny");
    DropColumn($db, "offers", "hall_destiny");
    DropColumn($db, "offers", "category");
    
    AddColumn($db, "offers", "expiration_date", "DATE NULL");
	AddColumn($db, "offers_photos", "LinkFilmYouTube", "VARCHAR( 500 )");
    AddColumn($db, "offers_photos", "LinkMiniaturkaYouTube", "VARCHAR( 500 )");
    AddColumn($db, "offers", "loc_as_commune", "BOOL NOT", "DEFAULT 0");
	
	AddColumn($db, "offers", "tag_title", "TEXT NULL");
	AddColumn($db, "offers", "tag_keywords", "TEXT NULL");
	AddColumn($db, "offers", "tag_description", "TEXT NULL");
    
	AddColumn($db, "offers", "has_virtual", "TINYINT(1) NOT", 'DEFAULT 0 AFTER `has_proj`');
    
    //optymalizacja dodanie kolumn flagowych do oferty
    $s2_result = $db->ExecuteQuery("SHOW columns FROM offers WHERE field='has_swfs'");
    $s2_row = $db->FetchArray($s2_result);
    $s2_add = $s2_row == null;
    //$s2_add=true;
    if($s2_add){
        //dodanie flag na istnienie zdjec, swf, dokumentow, panoram, rzutow 
        AddColumn($db, "offers", "has_swfs", "BOOL NOT", "DEFAULT 0");
        AddColumn($db, "offers", "has_movs", "BOOL NOT", "DEFAULT 0");
        AddColumn($db, "offers", "has_photos", "BOOL NOT", "DEFAULT 0");
        AddColumn($db, "offers", "has_pans", "BOOL NOT", "DEFAULT 0");
        AddColumn($db, "offers", "has_maps", "BOOL NOT", "DEFAULT 0");
        AddColumn($db, "offers", "has_proj", "BOOL NOT", "DEFAULT 0");
        AddColumn($db, "offers", "loc_as_commune", "BOOL NOT", "DEFAULT 0");
        
        //ustawienie flag dla poszczegolnych ofert
        $result_ofers = $db->ExecuteQuery("SELECT o.id, max(op.type='Filmy') as movs, max(op.type='Zdjecie') as zdjecia, max(op.type='Rzut') as projs, max(op.type='SWF') as swfs, max(op.type='Panorama') as pans, max(op.type='Mapa') as maps FROM offers o inner JOIN offers_photos op ON op.offers_id = o.id GROUP BY o.id");
        while($row_o = $db->FetchArray($result_ofers)){
            $result_mprop_upd = $db->ExecuteQueryWithParams("UPDATE offers SET has_swfs=?, has_movs=?, has_photos=?, has_maps=?, has_proj=?, has_pans=? WHERE id=?", array($row_o['swfs'],$row_o['movs'], $row_o['zdjecia'],$row_o['maps'],$row_o['projs'],$row_o['pans'], $row_o['id']));
        }
    }
    $sql = "CREATE TABLE IF NOT EXISTS `investments_agents` (
				`investments_id` INT NOT NULL ,
				`agents_id` INT NOT NULL ,
				INDEX ( `investments_id` ),
				INDEX ( `agents_id` ),
				PRIMARY KEY ( `investments_id` , `agents_id` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
	$result = $db->ExecuteQuery($sql);
        
    $sql = "CREATE TABLE IF NOT EXISTS `offers_documents` ( `id` INT NOT NULL PRIMARY KEY, `offers_id` INT NULL , `investments_id` INT NULL , `filename` VARCHAR(50) NOT NULL, `description` VARCHAR(250) NULL, `type` VARCHAR(20), `foto_id` INT NULL ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
    $result = $db->ExecuteQuery($sql);
    
        echo "Creating table investments_agents: OK<br />"; 
	if($db->LastError() != '') throw new Exception("table investments_agents:" . $db->LastError());

	echo "<h4>Updating virgo_api database complete.</h4>";
    
}catch (Exception $ex){
	echo "Execution error:<br />" . $ex->getMessage();
	echo "<h4>Installing database failed. Check configuration (config.php file) and try again. If the problem persists, please contact with us.</h4>";	
}

	