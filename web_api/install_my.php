<?php

echo "<h3>Installing web_api database</h3>";
echo "<br />";

$db = DataBase::GetDbInstance();
$sql = "CREATE TABLE IF NOT EXISTS `serwisy` (
`GID` VARCHAR(8) NOT NULL ,
`IdJezyk` INT NOT NULL ,
`NazwaFirmy` VARCHAR(100) NOT NULL ,
`AdresWWW` VARCHAR(100) NOT NULL ,
`EmailKontaktowy` VARCHAR(100) NULL ,
`StartowyJezyk` INT NULL ,
`departments_id` INT NOT NULL REFERENCES departments(id) ,
`agents_id` INT NOT NULL REFERENCES agents(id) ,
`Mieszkania` BOOL NULL ,
`Domy` BOOL NULL ,
`Dzialki` BOOL NULL ,
`Lokale` BOOL NULL ,
`Hale` BOOL NULL ,
`Gospodarstwa` BOOL NULL ,
`Kamienice` BOOL NULL ,
`Biurowce` BOOL NULL ,
`RodzajeOfert` VARCHAR(20) NULL ,
`TagTitle` VARCHAR(300) NULL ,
`TagKeywords` VARCHAR(500) NULL ,
`TagDescription` VARCHAR(500) NULL ,
`Head` TEXT NULL ,
`Body` TEXT NULL ,
`Foot` TEXT NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` ),
INDEX ( `departments_id` ),
INDEX ( `agents_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table serwisy: OK<br />";
if($db->LastError() != '') throw new Exception("table serwisy:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `serwisy_parametry` (
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`Nazwa` VARCHAR(100) NOT NULL ,
`Wartosc` TEXT NULL ,
PRIMARY KEY ( `serwisy_GID` , `Nazwa` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table serwisy_parametry: OK<br />";
if($db->LastError() != '') throw new Exception("table serwisy_parametry:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `miejsca` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`Rodzaj` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`Parent_GID` INT NULL REFERENCES miejsca(GID) ,
`Lp` INT NULL ,
`NazwaGlowna` VARCHAR(100) NULL ,
`Nazwa` VARCHAR(100) NULL ,
`Grafika` VARCHAR(100) NULL ,
`Link` VARCHAR(200) NULL ,
`Inne` VARCHAR(100) NULL ,
`Uwagi` TEXT NULL ,
PRIMARY KEY ( `GID` , `IdJezyk`, `Rodzaj` ),
INDEX ( `serwisy_GID` ),
INDEX ( `Parent_GID` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table miejsca: OK<br />";
if($db->LastError() != '') throw new Exception("table miejsca:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `menu` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`miejsca_miejsce_menu` INT NULL REFERENCES miejsca(GID) ,
`miejsca_grupa_serwisu` INT NULL REFERENCES miejsca(GID) ,
`Lp` INT NULL ,
`UkryjNaWWW` BOOL NULL ,
`NazwaGlowna` VARCHAR(50) NULL ,
`Nazwa` VARCHAR(100) NULL ,
`Grafika` VARCHAR(100) NULL ,
`Grafika2` VARCHAR(100) NULL ,
`Link` VARCHAR(250) NULL ,
`Tooltip` VARCHAR(250) NULL ,
`NoFollow` TINYINT( 1 ) NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` ),
INDEX ( `serwisy_GID` ),
INDEX ( `miejsca_miejsce_menu` ),
INDEX ( `miejsca_grupa_serwisu` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table menu: OK<br />";
if($db->LastError() != '') throw new Exception("table menu:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `artykuly` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`miejsca_grupa_serwisu` INT NULL REFERENCES miejsca(GID) ,
`miejsca_miejsce_serwisu` INT NULL REFERENCES miejsca(GID) ,
`menu_GID` INT NULL REFERENCES menu(GID) ,
`Parent_GID` INT NULL REFERENCES artykuly(GID) ,
`Lp` INT NULL ,
`CzyWiadomosc` BOOL NOT NULL ,
`CzyDomyslny` BOOL NOT NULL ,
`Autor` VARCHAR(50) NULL ,
`LiczbaOdslon` INT NULL ,
`SredniaOcena` DECIMAL(18, 2) NULL ,
`DataWiadomosci` DATE NULL ,
`DataAktualizacji` DATE NULL ,
`Tytul` VARCHAR(200) NULL ,
`Skrot` TEXT NULL ,
`SkrotGrafika` VARCHAR(100) NULL ,
`Tresc` TEXT NULL ,
`Link` VARCHAR(200) NULL ,
`NazwaWyswietlana` VARCHAR(200) NULL ,
`TagTitle` VARCHAR(300) NULL ,
`TagKeywords` VARCHAR(500) NULL ,
`TagDescription` VARCHAR(500) NULL ,
`galerie_GID` INT NULL ,
`Tagi` VARCHAR(500) NULL ,
`DataRozpoczeciaPublikacji` VARCHAR(500) NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` ),
INDEX ( `serwisy_GID` ),
INDEX ( `miejsca_grupa_serwisu` ),
INDEX ( `miejsca_miejsce_serwisu` ),
INDEX ( `menu_GID` ),
INDEX ( `Parent_GID` ),
INDEX ( `galerie_GID` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table artykuly: OK<br />";
if($db->LastError() != '') throw new Exception("table artykuly:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `artykuly_parametry` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`artykuly_GID` INT NOT NULL REFERENCES artykuly(GID) ,
`ParamNazwa` VARCHAR(50) NOT NULL ,
`Nazwa` VARCHAR(100) NULL ,
`Naglowek` VARCHAR(100) NULL ,
`Stopka` VARCHAR(100) NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` , `artykuly_GID`),
INDEX ( `artykuly_GID` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table artykuly_parametry: OK<br />";
if($db->LastError() != '') throw new Exception("table artykuly_parametry:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `arkusze_skrypty` (
`GID` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`Rodzaj` INT NOT NULL ,
`Opis` VARCHAR(150) NULL ,
`Tresc` TEXT NULL ,
`RodzajArkusza` VARCHAR(15) NULL ,
PRIMARY KEY ( `GID` ),
INDEX ( `serwisy_GID` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table arkusze_skrypty: OK<br />";
if($db->LastError() != '') throw new Exception("table arkusze_skrypty:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `banery` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`miejsca_grupa_serwisu` INT NULL REFERENCES miejsca(GID) ,
`miejsca_miejsce_serwisu` INT NULL REFERENCES miejsca(GID) ,
`Status` VARCHAR(20) NOT NULL ,
`DataDodania` DATE NOT NULL ,
`DataWygasniecia` DATE NULL ,
`DataEmisji` DATE NULL ,
`UrlDocelowy` VARCHAR(150) NULL ,
`GIDGrafiki` INT NULL ,
`Embed` TEXT NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` ),
INDEX ( `serwisy_GID` ),
INDEX ( `miejsca_grupa_serwisu` ),
INDEX ( `miejsca_miejsce_serwisu` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table banery: OK<br />";
if($db->LastError() != '') throw new Exception("table banery:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `opcje` (
`klucz` VARCHAR(50) NOT NULL ,
`wartosc` TEXT NULL ,
PRIMARY KEY ( `klucz` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table opcje: OK<br />";
if($db->LastError() != '') throw new Exception("table opcje:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `jezyki` (
`klucz` VARCHAR(100) NOT NULL ,
`IdJezyk` INT NOT NULL ,
`wartosc` VARCHAR(1000) NULL ,
PRIMARY KEY ( `klucz` , `IdJezyk` ),
INDEX ( `klucz` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table jezyki: OK<br />";
if($db->LastError() != '') throw new Exception("table jezyki:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `waluty_kursy` (
`waluta` VARCHAR(3) NOT NULL ,
`kurs` DECIMAL(18, 4) NOT NULL ,
`opis` VARCHAR(200) NULL ,
PRIMARY KEY ( `waluta` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table waluty_kursy: OK<br />";
if($db->LastError() != '') throw new Exception("table waluty_kursy:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `galerie` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`miejsca_grupa_serwisu` INT NULL REFERENCES miejsca(GID) ,
`Lp` INT NOT NULL ,
`Nazwa` VARCHAR(350) NULL ,
`Opis` TEXT NULL ,
`SlowaKluczowe` TEXT NULL ,
`Grafika` VARCHAR(150) NULL ,
`Rozmiar1` VARCHAR(9) NULL ,
`Rozmiar2` VARCHAR(9) NULL ,
`Rozmiar3` VARCHAR(9) NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` ),
INDEX ( `serwisy_GID` ),
INDEX ( `miejsca_grupa_serwisu` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table galerie: OK<br />";
if($db->LastError() != '') throw new Exception("table galerie:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `galeriepozycje` (
`GID` INT NOT NULL ,
`IdJezyk` INT NOT NULL ,
`serwisy_GID` VARCHAR(8) NOT NULL REFERENCES serwisy(GID) ,
`galerie_GID` INT NULL REFERENCES galerie(GID) ,
`Lp` INT NOT NULL ,
`Plik` VARCHAR(350) NULL ,
`Opis` TEXT NULL ,
`Tagi` VARCHAR( 500 ) NULL ,
PRIMARY KEY ( `GID` , `IdJezyk` ),
INDEX ( `serwisy_GID` ),
INDEX ( `galerie_GID` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table galeriepozycje: OK<br />";
if($db->LastError() != '') throw new Exception("table galeriepozycje:" . $db->LastError());

$sql = "CREATE TABLE IF NOT EXISTS `artykuly_galeriepozycje` (
`artykuly_GID` INT NOT NULL REFERENCES artykuly(GID) ,
`galeriepozycje_GID` INT NOT NULL REFERENCES galeriepozycje(GID) ,
PRIMARY KEY ( `artykuly_GID`, `galeriepozycje_GID` ),
INDEX ( `artykuly_GID`, `galeriepozycje_GID` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$result = $db->ExecuteQuery($sql);
echo "Creating table artykuly_galeriepozycje: OK<br />";
if($db->LastError() != '') throw new Exception("table artykuly_galeriepozycje:" . $db->LastError());

include_once 'update_db.php';
