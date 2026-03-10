<?php

echo "<h3>Installing virgo_api database</h3>";
echo "<br />";

//create a tabel of investments
$sql = "CREATE TABLE IF NOT EXISTS `investments` (
`id` INT NOT NULL ,
`id_lng` INT NOT NULL ,
`no` INT NOT NULL ,
`number` VARCHAR( 50 ) NOT NULL ,
`name` VARCHAR( 200 ) NOT NULL ,
`description` TEXT NULL ,
`short_description` TEXT NULL ,
`contact` TEXT NULL ,
`map_marker` VARCHAR( 500 ) NULL ,
`garage` BOOL NOT NULL ,
`pool` BOOL NULL ,
`terrace` BOOL NULL ,
`air_conditioning` BOOL NULL ,
`house_project` BOOL NULL ,
`special` BOOL NULL ,
`creation_date` DATE NULL ,
`due_date` DATE NULL ,
`total_area` DECIMAL( 18, 2 ) NOT NULL ,
`gross_volume` DECIMAL( 18, 2 ) NULL ,
`area_from` DECIMAL( 18, 2 ) NULL ,
`area_to` DECIMAL( 18, 2 ) NULL ,
`price_from` DECIMAL( 18, 2 ) NULL ,
`price_to` DECIMAL( 18, 2 ) NULL ,
`pricem2_from` DECIMAL( 18, 2 ) NULL ,
`pricem2_to` DECIMAL( 18, 2 ) NULL ,
`floor_from` INT NULL ,
`floor_to` INT NULL ,
`rooms_no_from` INT NULL ,
`rooms_no_to` INT NULL ,
`country` VARCHAR( 50 ) NULL ,
`province` VARCHAR( 50 ) NULL ,
`district` VARCHAR( 50 ) NULL ,
`location` VARCHAR( 50 ) NULL ,
`quarter` VARCHAR( 50 ) NULL ,
`region` VARCHAR( 50 ) NULL ,
`street` VARCHAR( 50 ) NULL ,
`category` VARCHAR( 100 ) NULL ,
`departments_id` INT NOT NULL REFERENCES departments(id) ,
INDEX ( `id` ),
PRIMARY KEY ( `id` , `id_lng` ),
INDEX ( `departments_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table investments: OK<br />";
if($db->LastError() != '') throw new Exception("table investments:" . $db->LastError());

//create a tabel of investments buildings
$sql = "CREATE TABLE IF NOT EXISTS `investments_buildings` (
`id` INT NOT NULL ,
`id_lng` INT NOT NULL ,
`investments_id` INT NOT NULL REFERENCES investments(id) ,
`symbol` VARCHAR( 50 ) NOT NULL ,
`name` VARCHAR( 200 ) NOT NULL ,
`due_date` DATE NULL ,
`description` VARCHAR( 1000 ) NULL ,
`floors_no` INT NULL ,
`area` DECIMAL( 18, 2 ) NULL ,
INDEX ( `id` ),
PRIMARY KEY ( `id` , `id_lng` ),
INDEX ( `investments_id` , `id_lng` ),
INDEX ( `investments_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table investments_buildings: OK<br />";
if($db->LastError() != '') throw new Exception("table investments_buildings:" . $db->LastError());


//create a tabel of offers
$sql = "CREATE TABLE IF NOT EXISTS `offers` (
`id` INT NOT NULL ,
`id_lng` INT NOT NULL ,
`status` VARCHAR( 20 ) NOT NULL ,
`object` VARCHAR( 20 ) NOT NULL ,
`rent` BOOL NOT NULL ,
`symbol` VARCHAR( 20 ) NOT NULL ,
`original` BOOL NOT NULL ,
`province` VARCHAR( 50 ) NULL ,
`district` VARCHAR( 50 ) NULL ,
`location` VARCHAR( 50 ) NULL ,
`quarter` VARCHAR( 50 ) NULL ,
`region` VARCHAR( 50 ) NULL ,
`street` VARCHAR( 50 ) NULL ,
`floor` VARCHAR( 200 ) NULL ,
`price` DECIMAL( 18, 2 ) NOT NULL ,
`price_square` DECIMAL( 18, 2 ) NOT NULL ,
`rooms_no` INT NOT NULL ,
`area` DECIMAL( 18, 2 ) NOT NULL ,
`latitude` DECIMAL( 18, 6 ) NULL ,
`longitude` DECIMAL( 18, 6 ) NULL ,
`building_technology` VARCHAR( 200 ) NULL ,
`construction_material` VARCHAR( 200 ) NULL ,
`construction_status` VARCHAR( 200 ) NULL ,
`building_type` VARCHAR( 200 ) NULL ,
`agents_id` INT NOT NULL REFERENCES agents(id) ,
`investments_buildings_id` INT NULL REFERENCES investments_buildings(id) ,
`creation_date` DATE NOT NULL ,
`modification_date` DATE NOT NULL ,
`country` VARCHAR( 50 ) NULL ,
`floor_no` INT NOT NULL ,
`year_of_construction` INT NOT NULL ,
`house_type` VARCHAR( 200 ) NULL ,
`first_page` BOOL NOT NULL ,
`object_type` VARCHAR( 200 ) NULL ,
`contract_type` VARCHAR( 100 ) NULL ,
`visits_no` INT NOT NULL ,
`legal_status` VARCHAR( 100 ) NULL ,
`ownership_status` VARCHAR( 100 ) NULL ,
`furnishings` VARCHAR( 200 ) NULL ,
`field_area` DECIMAL( 18, 2 ) NOT NULL ,
`change_status` BOOL NOT NULL ,
`notices` TEXT NULL ,
`notices_property` TEXT NULL ,
`video_link` VARCHAR( 200 ) NULL ,
`no_commission` BOOL NOT NULL ,
`description_synonim` TEXT NULL,
`display_number` INT NOT NULL DEFAULT 0,
`expiration_date` DATE NULL,
`has_swfs` BOOL NOT NULL DEFAULT 0,
`has_movs` BOOL NOT NULL DEFAULT 0,
`has_photos` BOOL NOT NULL DEFAULT 0,
`has_pans` BOOL NOT NULL DEFAULT 0,
`has_maps` BOOL NOT NULL DEFAULT 0,
`has_proj` BOOL NOT NULL DEFAULT 0,
`loc_as_commune` BOOL NOT NULL DEFAULT 0,
INDEX ( `id` ),
PRIMARY KEY ( `id` , `id_lng` ),
INDEX ( `agents_id` ),
INDEX ( `investments_buildings_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table offers: OK<br />";
if($db->LastError() != '') throw new Exception("table offers:" . $db->LastError());

//create a tabel of properties (for offers or investments)
$sql = "CREATE TABLE IF NOT EXISTS `properties` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 100 ) NOT NULL ,
`date` DATE NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table properties: OK<br />";
if($db->LastError() != '') throw new Exception("table properties:" . $db->LastError());

//create a tabel of offers properties
$sql = "CREATE TABLE IF NOT EXISTS `offers_properties` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`offers_id` INT NOT NULL REFERENCES offers(id) ,
`offers_id_lng` INT NULL REFERENCES offers(id_lng) ,
`properties_id` INT NOT NULL REFERENCES properties(id) ,
`value` TEXT NOT NULL ,
`set` BOOL NOT NULL ,
INDEX ( `offers_id` ) ,
INDEX ( `offers_id` , `offers_id_lng` ) ,
INDEX ( `offers_id` , `offers_id_lng` , `properties_id` ),
UNIQUE KEY `offers_id_uniq` (`offers_id`,`offers_id_lng`,`properties_id`,`value`(32))
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table offers_properties: OK<br />";
if($db->LastError() != '') throw new Exception("table offers_properties:" . $db->LastError());

//create a tabel of offers photos
$sql = "CREATE TABLE IF NOT EXISTS `offers_photos` (
`id` INT NOT NULL PRIMARY KEY,
`offers_id` INT NULL REFERENCES offers(id) ,
`investments_id` INT NULL REFERENCES investments(id) ,
`filename` VARCHAR( 50 ) NOT NULL ,
`description` VARCHAR( 50 ) NOT NULL ,
`order` INT NOT NULL ,
`type` VARCHAR( 20 ) NOT NULL ,
`intro` BOOL NULL ,
`foto_id` INT NULL ,
`LinkFilmYouTube` VARCHAR( 500 ),
`LinkMiniaturkaYouTube` VARCHAR( 500 ),
INDEX ( `id` ),
INDEX ( `offers_id` ),
INDEX ( `investments_id` ),
INDEX ( `foto_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table offers_photos: OK<br />";
if($db->LastError() != '') throw new Exception("tables offers_photos:" . $db->LastError());

//create a tabel for offers rooms
$sql = "CREATE TABLE IF NOT EXISTS `offers_rooms` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`offers_id` INT NULL REFERENCES offers(id) ,
`offers_id_lng` INT NULL REFERENCES offers(id_lng) ,
`kind` VARCHAR( 25 ) NOT NULL ,
`order` INT NULL ,
`area` VARCHAR( 200 ) NULL ,
`level` VARCHAR( 20 ) NULL ,
`type` VARCHAR( 100 ) NULL ,
`height` DECIMAL( 18, 2 ) NULL ,
`kitchen_type` VARCHAR( 100 ) NULL ,
`number` INT NULL ,
`glaze` VARCHAR( 100 ) NULL ,
`window_view` VARCHAR( 100 ) NULL ,
`description` VARCHAR( 1000 ) NULL ,
`floors_state` VARCHAR( 100 ) NULL DEFAULT '',
`room_type` VARCHAR( 100 ) NULL DEFAULT '',
INDEX ( `offers_id` , `offers_id_lng` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table offers_rooms: OK<br />";
if($db->LastError() != '') throw new Exception("table offers_rooms:" . $db->LastError());


//create a tabel of offers rooms properties
$sql = "CREATE TABLE IF NOT EXISTS `offers_rooms_sets` (
`offers_rooms_id` INT NOT NULL ,
`name` VARCHAR( 20 ) NOT NULL ,
`value` VARCHAR( 200 ) NOT NULL ,
INDEX ( `offers_rooms_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table offers_rooms_sets: OK<br />";
if($db->LastError() != '') throw new Exception("table offers_rooms_sets:" . $db->LastError());


//create a tabel of investments properties
$sql = "CREATE TABLE IF NOT EXISTS `investments_properties` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`investments_id` INT NOT NULL REFERENCES investments(id) ,
`investments_id_lng` INT NULL REFERENCES investments(id_lng) ,
`properties_id` INT NOT NULL REFERENCES properties(id) ,
`value` TEXT NOT NULL ,
`set` BOOL NOT NULL ,
`hash` CHAR(32) NOT NULL ,
INDEX ( `investments_id` ) ,
INDEX ( `investments_id` , `investments_id_lng` ) ,
INDEX ( `investments_id` , `investments_id_lng` , `properties_id` ),
UNIQUE KEY `offers_id_uniq` (`investments_id`,`investments_id_lng`,`properties_id`,`hash`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table investments_properties: OK<br />";
if($db->LastError() != '') throw new Exception("table investments_properties:" . $db->LastError());


//create a tabel of lists
$sql = "CREATE TABLE IF NOT EXISTS `listy` (
  `id` int(11) NOT NULL,
  `enum_id` int(11) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enum_id` (`enum_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table listy: OK<br />";
if($db->LastError() != '') throw new Exception("table listy:" . $db->LastError());


//create a tabel of locations
$sql = "CREATE TABLE IF NOT EXISTS `lokalizacje` (
      `id` int(11) NOT NULL,
      `nazwa` varchar(256) NOT NULL,
      `powiat_id` int(11) NOT NULL,
      `wojewodztwo_id` int(11) NOT NULL,
      `gmina` tinyint(1) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `powiat_id` (`powiat_id`),
      KEY `wojewodztwo_id` (`wojewodztwo_id`),
      KEY `gmina` (`gmina`)
    ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table lokalizacja: OK<br />";
if($db->LastError() != '') throw new Exception("table lokalizacje:" . $db->LastError());

//create a tabel of provinces
$sql = "CREATE TABLE IF NOT EXISTS `powiaty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `wojewodztwo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wojewodztwo_id` (`wojewodztwo_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table powiaty: OK<br />";
if($db->LastError() != '') throw new Exception("table powiaty:" . $db->LastError());


//create a tabel of districts
$sql = "CREATE TABLE IF NOT EXISTS `dzielnice` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `lokalizacja_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lokalizacja_id` (`lokalizacja_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table dzielnice: OK<br />";
if($db->LastError() != '') throw new Exception("table dzielnice:" . $db->LastError());


//create a tabel of regions
$sql = "CREATE TABLE IF NOT EXISTS `rejony` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `dzielnica_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dzielnica_id` (`dzielnica_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table rejony: OK<br />";
if($db->LastError() != '') throw new Exception("table rejony:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(50) NOT NULL,
  `rent` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `quartersOrStreet` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `priceFrom` decimal(18,2) DEFAULT NULL,
  `priceTo` decimal(18,2) DEFAULT NULL,
  `areaFrom` decimal(18,2) DEFAULT NULL,
  `areaTo` decimal(18,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `date` (`date`),
  KEY `object` (`object`(6),`rent`,`location`(6),`date`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table statistics: OK<br />";
if($db->LastError() != '') throw new Exception("table statistics:" . $db->LastError());

include_once 'update_db.php';
