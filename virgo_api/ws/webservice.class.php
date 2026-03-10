<?php

/**
 * Supports connection to WebService, synchronize database.
 * @author Marcin Welc
 *
 */
class WebServiceVirgo extends WebServiceWeb {

    protected static $_instance_virgo = null;

    const TMP_ZIP_FILE = "tmp.zip";
    const TMP_ZIP_ARCH_FILE = "tmp_arch.zip";
    const TMP_LISTS_ZIP_FILE = "tmp_list.zip";
    const TMP_LOCATIONSALL_ZIP_FILE = "tmp_locationsall.zip";
    const TMP_XML_OFE_FILE = "tmp.xml";
    const TMP_XML_OFE_ARCH_FILE = "tmp_arch.xml";
    const TMP_XML_INV_FILE = "tmpi.xml";
    const TMP_XML_OFELIST_FILE = "oferrs.xml";
    const TMP_XML_LISTS_FILE = "lists.xml";
    const TMP_XML_LOCATIONSALL_FILE = "locationsall.xml";

    /**
     * Return the WebService object.
     * @return WebService
     */
    public static function WS() {
        if (self::$_instance_virgo == null)
            self::$_instance_virgo = new WebServiceVirgo();
        return self::$_instance_virgo;
    }

    /**
     * Get a list of offers to be added or remove. Write the offers to the database.
     * @param string &$log
     * @return int
     */
    public function GetOffers(&$log, $lngId = 1045) {
        if (!$this->WS())
            return null;
        $log .= "START\n";
        $log .= "working dir=" . getcwd() . "\n";
        $time_start = microtime_float();
        $count_arr = array("suma" => 0, "dodane" => 0, "zmodyfikowane" => 0, "usuniete" => 0);
        $deleted_ids = $this->GetOffersPartial(false, $log, $count_arr);

        Errors::LogSynchroStep('WebServiceVirgo - GetOffers() - step 1');
        //delete offers
        foreach ($deleted_ids as $idd) {
            $ret = Offers::DeleteOffer($idd, $lngId);
            if ($ret == "D") {
                $count_arr["suma"] ++;
                $count_arr["usuniete"] ++;
            }
            echo DataBase::GetDbInstance()->LastError();
        }

        Errors::LogSynchroStep('WebServiceVirgo - GetOffers() - step 4');
        Errors::LogError2File("Offers synchronization results: added=" . $count_arr["dodane"] . ", modified=" . $count_arr["zmodyfikowane"] . ", deleted=" . $count_arr["usuniete"]);

        $log .= "KONIEC\n";
        $time_end = microtime_float();
        $time = $time_end - $time_start;

        if ($this->_DEBUG)
            echo "<b>Execution time: $time seconds</b><br>";
        if ($this->_DEBUG)
            echo "Queries count: " . DataBase::$QUERY_COUNT . "<br>";
        OffersHelper::clearCache();
        return $count_arr["suma"];
    }

    /**
     * Clear invalid xml chars
     * @param string $value
     * @return string
     */
    function stripInvalidXmlChars($value) {
        $invalid = array('&#x8;');

        $value = str_replace($invalid, '', $value);

        $ret = "";
        $current = "";
        if (empty($value))
            return $ret;
        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $current = ord($value[$i]);
            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF))) {

                $ret .= chr($current);
            } else {
                $ret .= " ";
            }
        }
        return $ret;
    }

    /**
     * Get a list of offers to be added or remove. Write the offers to the database.
     * @param boolean $archive
     * @param string &$log
     * @param array &$count_arr
     * @return null|int|array
     * @throws Exception
     */
    private function GetOffersPartial($archive, &$log, &$count_arr) {

        Errors::LogSynchroStep('WebServiceVirgo - GetOffersPartial() start...');

        if (!$this->WS()) {
            Errors::LogSynchroStep('WebServiceVirgo - NO WEBSERVICE');
            return null;
        }
        try {
            $log .= "archive=" . (int) $archive . "\n";
            $time_start = microtime_float();
            if ($this->_sid == "")
                return;
            $params = array('sid' => $this->_sid);

            if ($archive) {
                $result = $this->WS()->getSC()->__soapCall("GetOffersArchive", array($params));
                $buf = $result->GetOffersArchiveResult->OffersZip;
                $status = $result->GetOffersArchiveResult->Status;
                $msg = $result->GetOffersArchiveResult->Message;
            } else {
                $result = $this->WS()->getSC()->__soapCall("GetOffers", array($params));
                $buf = $result->GetOffersResult->OffersZip;
                $status = $result->GetOffersResult->Status;
                $msg = $result->GetOffersResult->Message;
            }
            $time_end = microtime_float();
            $time = $time_end - $time_start;
            if ($this->_DEBUG)
                echo "SOAP Call execution time: $time seconds<br>";

            if ($status <> 0) {
                throw new Exception($msg);
                die;
            }

            $zip_file = $archive ? self::TMP_ZIP_ARCH_FILE : self::TMP_ZIP_FILE;
            $f = fopen($zip_file, "w");
            fwrite($f, $buf);
            fclose($f);

            $time_end2 = microtime_float();
            $time = $time_end2 - $time_end;
            if ($this->_DEBUG)
                echo "Save ZIP execution time: $time seconds<br>";

            //unzip XML file with offers
            $contents = "";
            $zip = new ZipArchive();
            if ($zip->open($zip_file)) {
                $fp = $zip->getStream('xml.xml');
                if (!$fp)
                    exit("failed reading xml file (" . getcwd() . "), probably invalid permissions to folder\n");
                $contents = '';
                while (!feof($fp)) {
                    $contents .= fread($fp, 1024);
                }
                fclose($fp);
                $zip->close();
                $xml_file = $archive ? self::TMP_XML_OFE_ARCH_FILE : self::TMP_XML_OFE_FILE;
                file_put_contents($xml_file, $contents);
                if (file_exists($zip_file))
                    unlink($zip_file);
            }

            $time_end3 = microtime_float();
            $time = $time_end3 - $time_end2;
            if ($this->_DEBUG)
                echo "Save XML execution time: $time seconds<br>";

            $times = array("read_props" => 0, "save" => 0, "del_props" => 0, "rooms" => 0, "del_offers" => 0, "rooms_del" => 0, "photos" => 0, "virtual" => 0);
            $prevOfferId = "0";

            $content = file_get_contents($xml_file);
            //$content = preg_replace("/<UwagiOpis>([^\<\>]*)\<\/UwagiOpis>/m", "<UwagiOpis><![CDATA[$1]]></UwagiOpis>", $content);
            //$content = preg_replace("/<UwagiNieruchomosc>([^\<\>]*)\<\/UwagiNieruchomosc>/m", "<UwagiNieruchomosc><![CDATA[$1]]></UwagiNieruchomosc>", $content);
            $fp = fopen($xml_file, 'w');
            fwrite($fp, $content);
            fclose($fp);

            //open and read XML file
            $xml2 = new XMLReader();
            $xml2->open($xml_file);
            $domdoc = new DOMDocument;
            $ids_do_usuniecia_dodania = array();
            $all_agents_ids = array();
            $all_dept_ids = array();
            $blokuj_agentow = true;
            $blokuj_oddzialy = true;

            $time_end4 = microtime_float();
            $time = $time_end4 - $time_end3;
            if ($this->_DEBUG)
                echo "Load XML execution time: $time seconds<br>";

            $xml2->read();
            while ($xml2->name) {
                //Departments
                if ($xml2->name == "Oddzial") {
                    if ($blokuj_oddzialy)
                        $blokuj_oddzialy = false;
                    $node = simplexml_import_dom($domdoc->importNode($xml2->expand(), true));
                    if (count($node) > 0) {
                        $log .= "oddzial=" . $node["ID"] . " - " . $node->Nazwa . "\n";
                        $dep = new Department($node["ID"], $node->Nazwa, $node->Nazwa2, $node->Adres, $node->Miasto, $node->Kod, $node->Nip, $node->Wojewodztwo, $node->Www, $node->Telefon, $node->Email,
                                            $node->Fax, $node->Uwagi, $node->Naglowek, $node->Stopka, $node->PlikLogo, $node->ZdjecieWWW, $node->Subdomena, $node->Firma);
                        array_push($all_dept_ids, (int) $node["ID"]);
                        Departments::AddEditDepartment($dep);
                        echo DataBase::GetDbInstance()->LastError();
                    }
                }

                //Agents
                if ($xml2->name == "Agent") {
                    if ($blokuj_agentow)
                        $blokuj_agentow = false;
                    $node = simplexml_import_dom($domdoc->importNode($xml2->expand(), true));
                    if (count($node) > 0) {
                        $log .= "agent=" . $node["ID"] . " - " . $node->Nazwa . "\n";
                        $kod_pracownika = 0;
                        if(is_numeric((string) $node->KodPracownika))
                            $kod_pracownika = (int) $node->KodPracownika;
                        $agent = new Agent($node["ID"], $node->Nazwa, $node->Telefon, $node->Komorka, $node->Email, $node->Oddzial, $node->JabberLogin, $node->NrLicencji, $node->OdpowiedzialnyNazwa,
                                        $node->OdpowiedzialnyNrLicencji, $node->Komunikator, $node->PlikFoto, $kod_pracownika, $node->DzialFunkcja);
                        array_push($all_agents_ids, (int) $node["ID"]);
                        Agents::AddEditAgent($agent);
                        echo DataBase::GetDbInstance()->LastError();
                    }
                }


                //Offers
                if ($xml2->name == "Oferty") {
                    $node = simplexml_import_dom($domdoc->importNode($xml2->expand(), true));
                    if (count($node) > 0) {
                        foreach ($node->children() as $nodeOferta) {
                            $count_arr["suma"] ++;
                            $log .= "oferta=" . $nodeOferta["ID"] . " - " . $nodeOferta["Symbol"] . "\n";
                            //read major properties
                            $rent = strtolower($nodeOferta["Wynajem"]) == "true" ? 1 : 0;
                            $orig = strtolower($nodeOferta["Pierwotny"]) == "true" ? 1 : 0;
                            $przedmiot = $nodeOferta["Przedmiot"];
                            if ($przedmiot == "Biurowiec")
                                $przedmiot = "Obiekt";
                            $first_page = strtolower($nodeOferta->PierwszaStrona) == "true" ? 1 : 0;
                            $zamiana = $nodeOferta->Zamiana ? 1 : 0;
                            $loc_as_commune = strtolower($nodeOferta["LokalizacjaJakoGmina"] ?? "") == "true" ? 1 : 0;

                            $has_swfs = 0;
                            $has_movs = 0;
                            $has_maps = 0;
                            $has_projs = 0;
                            $has_pans = 0;
                            $has_photos = 0;
                            $has_virtual = 0;
                            if (isset($nodeOferta->Zdjecia)) {
                                foreach ($nodeOferta->Zdjecia->children() as $zd) {
                                    switch ($zd->typ) {
                                        case "Zdjecie":$has_photos = 1;
                                            break;
                                        case "Rzut":$has_projs = 1;
                                            break;
                                        case "Mapa":$has_maps = 1;
                                            break;
                                        case "SWF":$has_swfs = 1;
                                            break;
                                        case "Filmy":$has_movs = 1;
                                            break;
                                        case "Panorama":$has_pans = 1;
                                            break;
                                    }
                                }
                            }
                            /*if (isset($nodeOferta->Dokumenty)) {
                                //echo 'dokument';
                            }*/
                            $attr_arr = array("Link" => null, "ZeroProwizji" => 0);
                            if (isset($nodeOferta->Atrybuty)) {
                                foreach ($nodeOferta->Atrybuty->children() as $at) {
                                    if ($at["opis"] == "Link")
                                        $attr_arr["Link"] = (string) $at;
                                    if ($at["opis"] == "ZeroProwizji")
                                        $attr_arr["ZeroProwizji"] = (string) $at;
                                }
                            }
                            $offer = new Offer($nodeOferta["Jezyk"], CheckNumeric($nodeOferta["ID"]), $nodeOferta["Status"], $przedmiot, $rent, $nodeOferta["Symbol"], $orig, $nodeOferta["Wojewodztwo"],
                                            $nodeOferta["Powiat"], $nodeOferta["Lokalizacja"], $nodeOferta["Dzielnica"], $nodeOferta["Rejon"], $nodeOferta["Ulica"], CheckNumeric($nodeOferta["Pietro"]), CheckNumeric($nodeOferta["Cena"]),
                                            CheckNumeric($nodeOferta["CenaM2"]), $nodeOferta["IloscPokoi"], CheckNumeric($nodeOferta["PowierzchniaCalkowita"]), CheckNumeric($nodeOferta["MapSzerokoscGeogr"]),
                                            CheckNumeric($nodeOferta["MapDlugoscGeogr"]), $nodeOferta["TechnologiaBudowlana"], $nodeOferta["MaterialKonstrukcyjny"], $nodeOferta["StanWybudowania"], $nodeOferta["RodzajBudynku"],
                                            $nodeOferta["Agent"], $nodeOferta["DataWprowadzenia"], $nodeOferta["DataWprowadzenia"], 0, empty_to_null($nodeOferta->Kraj), CheckNumeric($nodeOferta->IloscPieter), CheckNumeric($nodeOferta->RokBudowy),
                                            empty_to_null($nodeOferta->RodzajDomu), $first_page, empty_to_null($nodeOferta->RodzajObiektu), empty_to_null($nodeOferta->SposobPrzyjecia), (int)$nodeOferta->IloscOdslonWWW, null,
                                            empty_to_null($nodeOferta->StatusWlasnosci), empty_to_null($nodeOferta->UmeblowanieLista), CheckNumeric($nodeOferta->PowierzchniaDzialki), $zamiana,
                                            empty_to_null(html_entity_decode($nodeOferta->UwagiOpis)), empty_to_null(html_entity_decode($nodeOferta->UwagiNieruchomosc)), empty_to_null($attr_arr["Link"]),
                                            $attr_arr["ZeroProwizji"], $nodeOferta["DataWaznosci"], $has_swfs, $has_movs, $has_photos, $has_pans, $has_maps, $has_projs, $has_virtual, $loc_as_commune, $nodeOferta->TagTitle, $nodeOferta->TagKeywords, $nodeOferta->TagDescription);

                            // Jeśli przynajmniej jedna oferta ma wirtualną wizytę
                            if($attr_arr["Link"] || $has_swfs) {
                                Offers::setHasVirtual();
                            }

                            $photosNode = null;
                            $virtualNode = null;
                            $virtualNodeNew = null;
                            $roomsNode = null;
                            $modDate = null;
                            $attributesNode = null;

                            $ts = microtime_float();

                            //properties that are in offers directly
                            $pomin = OffersHelper::$props_arr;

                            //atributes that are in offers directly
                            $pomin_attr = array("Link", "ZeroProwizji");

                            //read other properties
                            foreach ($nodeOferta->children() as $propNode) {
                                $pname = $propNode->getName();
                                //echo $pname.'<br>';
                                if ($przedmiot == 'Dzialka') {
                                    if ($pname == "StanPrawnyGruntu") {
                                        $offer->setStanPrawny($propNode);
                                    }
                                } else {
                                    if ($pname == "StanPrawnyDom" || $pname == "StanPrawnyLokal" || $pname == "StanPrawnyLokalLista") {
                                        $offer->setStanPrawny($propNode);
                                    }
                                }
                                if (in_array($pname, $pomin) === false) {
                                    if ($pname == "Dokumenty"){
                                        $documentsNode = $propNode;
                                    }

                                    if ($pname == "Zdjecia") {
                                        $photosNode = $propNode;
                                    } else if ($pname == "WirtualnaWizyta") {
                                        $virtualNode = $propNode;
                                    } else if ($pname == "WirtualnaWizyta_v2") {
                                        $virtualNodeNew = $propNode;
                                    } else if ($pname == "DataAktualizacji") {
                                        $modDate = $propNode;
                                    } else if ($pname == "Pomieszczenia") {
                                        $roomsNode = $propNode;
                                    } else if($pname == "WirtualnaWizytaLink"){
                                        $virtualLink = $propNode;
                                    } else if ($pname == "Atrybuty") {
                                        $attributesNode = $propNode;
                                        $set = array();
                                        foreach ($propNode->children() as $listNode) {
                                            if (array_search($listNode['opis'], $pomin_attr) === false)
                                                $set[count($set)] = $listNode['opis'] . "#|#" . $listNode;
                                        }
                                        $offer->__set($pname, $set);
                                    }else if ($propNode['iset'] == true) {
                                        $set = array();
                                        foreach ($propNode->children() as $listNode)
                                            $set[count($set)] = $listNode;
                                        $offer->__set($pname, $set);
                                    }else {
                                        $offer->__set($pname, $propNode);
                                    }
                                }
                            }
                            if ($nodeOferta['NrLokalu']) {
                                $offer->__set('NrLokalu', $nodeOferta['NrLokalu']);
                            }
                            $times["read_props"] += microtime_float() - $ts;
                            $ts = microtime_float();

                            //save offer object to database
                            if ($modDate != null)
                                $offer->SetModificationDate($modDate);
                            $ret = Offers::AddEditOffer($offer);
                            if ($ret == "A")
                                $count_arr["dodane"] ++;
                            else if ($ret == "E")
                                $count_arr["zmodyfikowane"] ++;
                            echo DataBase::GetDbInstance()->LastError();

                            $times["save"] += microtime_float() - $ts;
                            $ts = microtime_float();

                            //delete unuse properties from offer
                            $addedProperties = array();
                            foreach ($nodeOferta->children() as $propNode) {
                                $pname = $propNode->getName();
                                if ($pname == "StanPrawnyDom" || $pname == "StanPrawnyGruntu" || $pname == "StanPrawnyLokal" || $pname == "StanPrawnyLokalLista")
                                    $pname = "StanPrawny";
                                if (in_array($pname, $pomin) === false) {
                                    if (($pname != "Zdjecia" && $pname != "Pomieszczenia" && $pname != "Atrybuty" && $pname != "DataAktualizacji") || $propNode['iset'] == true) {
                                        $prop = Properties::GetPropertyName($pname);
                                        if ($prop != null)
                                            $addedProperties[count($addedProperties)] = $prop->GetID();
                                    }
                                }
                            }
                            if ($nodeOferta['NrLokalu']) {
                                $addedProperties[] = Properties::GetPropertyName('NrLokalu')->GetID();
                            }
                            if ($attributesNode != null) {
                                $addedProperties[] = Properties::GetPropertyName($attributesNode->getName())->GetID();
                            }
                            Offers::DeleteUnUseProperties($offer->GetId(), $offer->GetIdLng(), $addedProperties);

                            $times["del_props"] += microtime_float() - $ts;
                            $ts = microtime_float();

                            //photos
                            $addedPhotos = array();
                            if ($photosNode != null) {
                                foreach ($photosNode->children() as $photoNode) {
                                    $intro = strtolower($photoNode->intro) == "true" ? 1 : 0;
                                    $photo = new OfferPhoto($photoNode['ID'], $offer->GetId(), null, $photoNode->plik, $photoNode->opis, $photoNode->lp, $photoNode->typ, $intro, $photoNode['fotoID'],
                                                            (string) $photoNode->LinkFilmYouTube, (string) $photoNode->LinkMiniaturkaYouTube);
                                    OfferPhotos::AddEditPhoto($photo);
                                    echo DataBase::GetDbInstance()->LastError();
                                    $addedPhotos[count($addedPhotos)] = $photo->GetId();
                                }
                            }
                            OfferPhotos::DeleteUnUsePhotos($offer->GetId(), $addedPhotos, 0);

                            //documents
                            $addedDocuments = array();
                            if (!empty($documentsNode) && $documentsNode != null) {
                                foreach ($documentsNode->children() as $documentNode) {
                                    $photo = new OfferDocument($documentNode['Id'], $offer->GetId(), null, $documentNode->Plik, $documentNode->Uwagi, '', $documentNode['fotoID']);
                                    OfferDocuments::AddEditPhoto($photo);
                                    echo DataBase::GetDbInstance()->LastError();
                                    $addedPhotos[count($addedPhotos)] = $photo->GetId();
                                }
                            }
//                            OfferPhotos::DeleteUnUsePhotos($offer->GetId(), $addedPhotos, 0);

                            $times["photos"] += microtime_float() - $ts;
                            $ts = microtime_float();

                            //wirtualne wizyty
                            if ($virtualNode != null) {
                                $virtual = new OfferVirtual($offer->GetId());
                                $virtual->clearPhotos();

                                if(isset($virtualNode->Scena->Typ) && (int)$virtualNode->Scena->Typ===3 && !empty((string)$virtualNode->Scena->Link)) {
                                    $offer->__set('VirtualLink', (string)$virtualNode->Scena->Link);
                                    $offer->SetHasVirtual(1);
                                    Offers::AddEditOffer($offer);
                                    Offers::setHasVirtual();
                                }
                                else {
                                    foreach($virtualNode->Scena as $scena) {
                                        $virtual->addScene($scena);
                                    }
                                    $virtual->saveXML($offer->GetIdLng());
                                }
                            } else {
                                $virtual = new OfferVirtual($offer->GetId());
                                $virtual->clearPhotos(true);
                            }

                            if ($virtualNodeNew != null && $virtualNode != null) {
                                $json = $virtualNodeNew->json;
                                foreach($virtualNodeNew->images as $images) {
                                    foreach($images as $image) {
                                        $attr = $image->attributes();
                                        $imageHash = $attr['hash'][0];
                                        $imageHash2 = str_replace('#','',$imageHash);
                                        $imageId = str_replace("scene_", "", $imageHash2);
                                        $imageLink = $virtual->getSceneImageUrl($imageId);
                                        $json = str_replace($imageHash, $imageLink, $json);
                                    }
                                }

                                $jsonObj = json_decode($json, true);
								foreach($jsonObj["scenes"] as $key => $scene){
									$expl = explode("/",$scene["image"]);
									$image = end($expl);
									$file = explode(".", $image);
									$id = str_replace("image_", "" ,$file[0]);
									$jsonObj["scenes"][$key]["imageMobile"] = $virtual->getSceneMobileImageUrl($id);
								}
								$json = json_encode($jsonObj);

                                $virtual->setJson($json);
                                $virtual->saveJson($offer->GetIdLng());
                            }

                            //zewnetrzne wirtualne wizyty
                            if(!empty($virtualLink) && $virtualLink != null) {
                                if (!empty($virtualLink->Url) && $virtualLink->Url != null && $virtualLink->Url != ''){
                                    $offer->__set('VirtualLink', (string)$virtualLink->Url);
                                    $offer->SetHasVirtual(1);
                                    Offers::AddEditOffer($offer);
                                    // Offers::setHasVirtual();
                                }
                            }

                            $times["virtual"] += microtime_float() - $ts;
                            $ts = microtime_float();

                            //rooms
                            if ($roomsNode != null) {
                                if ($prevOfferId != $offer->GetId() . "")
                                    OfferRooms::DeleteRooms($offer->GetId(), null);

                                $times["rooms_del"] += microtime_float() - $ts;

                                foreach ($roomsNode->children() as $roomNode) {
                                    $room = new OfferRoom(0, $offer->GetId(), $offer->GetIdLng(), $roomNode['Rodzaj'], $roomNode->Lp, $roomNode->Powierzchnia, $roomNode->Poziom, $roomNode->Typ,
                                                        CheckNumeric($roomNode->Wysokosc), $roomNode->RodzajKuchni, CheckNumeric($roomNode->Ilosc), $roomNode->Glazura, $roomNode->WidokZOkna, $roomNode->Opis,
                                                        $roomNode->StanPodlogi, $roomNode->RodzajPomieszczenia);
                                    //sets of properties
                                    $_floors = array();
                                    if ($roomNode->Podlogi)
                                        foreach ($roomNode->Podlogi->children() as $listNode)
                                            $_floors[count($_floors)] = $listNode;
                                    $room->SetFloors($_floors);

                                    $_windowsExhibition = array();
                                    if ($roomNode->WystawaOkien)
                                        foreach ($roomNode->WystawaOkien->children() as $listNode)
                                            $_windowsExhibition[count($_windowsExhibition)] = $listNode;
                                    $room->SetWindowsExhibition($_windowsExhibition);

                                    $_walls = array();
                                    if ($roomNode->Sciany)
                                        foreach ($roomNode->Sciany->children() as $listNode)
                                            $_walls[count($_walls)] = $listNode;
                                    $room->SetWalls($_walls);

                                    $_equipment = array();
                                    if ($roomNode->Wyposazenie)
                                        foreach ($roomNode->Wyposazenie->children() as $listNode)
                                            $_equipment[count($_equipment)] = $listNode;
                                    $room->SetEquipment($_equipment);

                                    OfferRooms::AddRoom($room);
                                    echo DataBase::GetDbInstance()->LastError();
                                }
                            }

                            $times["rooms"] += microtime_float() - $ts;
                            $prevOfferId = $offer->GetId() . "";
                        }
                    }
                }

                //Deleted offers
                if ($xml2->name == "Usuniete") {
                    $node = simplexml_import_dom($domdoc->importNode($xml2->expand(), true));
                    foreach ($node->children() as $doUsuniecia) {
                        array_push($ids_do_usuniecia_dodania, (int) $doUsuniecia["ID"]);
                    }
                }

                $xml2->read();
            }

            //Delete redundant departments
            $redundantOffersDepartments = array();
            if (!$blokuj_oddzialy) {
                Departments::DeleteRedundantDepartments($all_dept_ids);
                $redundantOffersDepartments = Offers::getRedundantOffersIds($all_dept_ids);
            }

            //Delete redundant agents
            $redundantOffersAgents = array();
            if (!$blokuj_agentow) {
                $redundantOffersAgents = Offers::getRedundantOffersIds(false,$all_agents_ids);
                Agents::DeleteRedundantAgents($all_agents_ids);
            }

            $ts = microtime_float();

            $time_end5 = microtime_float();
            $time = $time_end5 - $time_end4;
            $times["del_offers"] += microtime_float() - $ts;
            if ($this->_DEBUG) {
                echo "Saving data to db execution time: $time seconds<br>";
                var_dump($times);
            }

            $xml2->close();
            Errors::LogSynchroStep('WebServiceVirgo - GetOffersPartial() done');
            return  array_unique(array_merge($ids_do_usuniecia_dodania, $redundantOffersDepartments, $redundantOffersAgents));
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:GetOffers", $ex->getMessage() . "; " . $ex->getTraceAsString());
            return 0;
        }
    }

    /**
     * Gets an image by given params.
     * @param int $id
     * @param string $size
     * @param boolean $basicWatermark
     * @param boolean $additionalWatermark
     * @param boolean $kadruj
     * @return array byte
     */
    public function GetImage($id, $size, $basicWatermark, $additionalWatermark, $kadruj) {
        if (!$this->WS())
            return null;
        try {
            if ($this->_sid == "")
                return;
            $params = array('sid' => $this->_sid, 'id' => $id, 'size' => $size, 'basicWatermark' => $basicWatermark, 'additionalWatermark' => $additionalWatermark);
            $result = null;
            if ($kadruj) {
                $res = $this->WS()->getSC()->__soapCall("GetImageNewKadr", array($params));
                $result = $res->GetImageNewKadrResult;
            } else {
                $res = $this->WS()->getSC()->__soapCall("GetImageNew", array($params));
                $result = $res->GetImageNewResult;
            }
            if ($result->Status == 0) {
                return $result->Image;
            } else {
                Errors::LogError("WebService:GetImage", "ID=$id, SIZE=$size, basicWatermark=$basicWatermark, additionalWatermark=$additionalWatermark, kadruj=$kadruj Response: " . $result->Message);
            }
        } catch (Exception $ex) {
            Errors::LogError("WebService:GetImage", $ex->getMessage());
        }
    }

    /**
     * Gets virtual's tour scene image
     *
     * @param int $id
     */
    public function GetVirtualTourScene($id) {
        if (!$this->WS())
            return null;
        try {
            if ($this->_sid == "")
                return;
            $params = array('sid' => $this->_sid, 'id' => $id);
            $result = null;
            $res = $this->WS()->getSC()->__soapCall("GetVirtualTourScene", array($params));

            $result = $res->GetVirtualTourSceneResult;
            if ($result->Status == 0) {
                return $result->Image;
            } else {
                Errors::LogError("WebService:GetVirtualTourScene", "ID=$id, Response: " . $result->Message);
            }
        } catch (Exception $ex) {
            Errors::LogError("WebService:GetVirtualTourScene", $ex->getMessage());
        }
    }

    /**
     * Gets virtual's tour scene point image
     *
     * @param int $id
     */
    public function GetVirtualTourScenePointImg($id)
    {
        if (!$this->WS()){
            return NULL;
        }

        try {
            if ($this->_sid == ""){
                return;
            }
            $params = ["sid" => $this->_sid, "id" => $id];
            $result = NULL;
            $res = $this->WS()->getSC()->__soapCall("GetVirtualTourScenePointImg", [$params]);

            $result = $res->GetVirtualTourScenePointImgResult;
            if ($result->Status == 0){
                return $result->Image;
            } else {
                Errors::LogError("WebService:GetVirtualTourScenePointImg", "ID=$id, Response: " . $result->Message);
            }
        } catch (Exception $ex) {
            Errors::LogError("WebService:GetVirtualTourScenePointImg", $ex->getMessage());
        }
    }

    /**
     * Gets a swf file
     * @param int $id OfferPhoto Id.
     * @return array byte
     */
    public function GetSWF($id) {
        if (!$this->WS())
            return null;
        try {
            if ($this->_sid == "")
                return;
            $params = array('sid' => $this->_sid, 'id' => $id);
            $result = $this->WS()->getSC()->__soapCall("GetSWF", array($params));
            if ($result->GetSWFResult->Status == 0) {
                return $result->GetSWFResult->Image;
            } else {
                Errors::LogError("WebServiceVirgo:GetSWF", "ID=$id, Response: " . $result->GetSWFResult->Message);
            }
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:GetSWF", $ex->getMessage());
        }
    }

    /**
     * Get a list of investments to be added or remove. Write the investments to the database.
     */
    public function GetInvestments() {
        //echo "start<br>";
        if (!$this->WS())
            return null;
        try {
            if ($this->_sid == "")
                return;
            $params = array('sid' => $this->_sid);
            $result = $this->WS()->getSC()->__soapCall("GetInvestments", array($params));
            if ($result->GetInvestmentsResult->Status != 0) {
                return $result->GetInvestmentsResult->Message;
            }

            $buf = $result->GetInvestmentsResult->OffersZip;

            $f = fopen(self::TMP_ZIP_FILE, "w");
            fwrite($f, $buf);
            fclose($f);

            //unzip XML file with offers
            $zip = new ZipArchive();
            if ($zip->open(self::TMP_ZIP_FILE)) {
                $fp = $zip->getStream('xml.xml');
                if (!$fp)
                    exit("failed reading xml file (" . getcwd() . ")\n");
                $contents = '';
                while (!feof($fp)) {
                    $contents .= fread($fp, 2);
                }
                fclose($fp);
                $zip->close();

                file_put_contents(self::TMP_XML_INV_FILE, $contents);
                if (file_exists(self::TMP_ZIP_FILE))
                    unlink(self::TMP_ZIP_FILE);
            }

            //open and read XML file
            $xml = simplexml_load_file(self::TMP_XML_INV_FILE);
            $investmentsNode = null;

            //read main nodes
            foreach ($xml->children() as $child) {
                //echo "<br />" . $child->getName() . ": " . $child;
                if ($child->getName() == "Inwestycje")
                    $investmentsNode = $child;
            }

            //read investments
            $importedInv = "";
            $count = 0;
            foreach ($investmentsNode->children() as $node) {
                $count++;
                $importedInv .= $node["ID"] . ",";

                //read major properties
                $garage = $node->Garaz == "True" ? 1 : 0;
                $pool = $node->Basen == "True" ? 1 : 0;
                $ter = $node->Taras == "True" ? 1 : 0;
                $ac = $node->Klimatyzacja == "True" ? 1 : 0;
                $spec = $node->Specjalna == "True" ? 1 : 0;
                $proj = $node->Projekt == "True" ? 1 : 0;
                $investment = new Investment($node["Jezyk"], $node["ID"], CheckNumeric($node["Lp"]), $node->Numer, $node->Nazwa, $node->Opis, $node->OpisSkrot, $node->DaneKontaktowe,
                                                $node->MapMar, $garage, $pool, $ter, $ac, $proj, $spec, $node->DataUtworzenia, (string)$node->TerminOddania ? $node->TerminOddania : null, CheckNumeric($node->PowierzchniaCalkowita),
                                                CheckNumeric($node->KubaturaBrutto), CheckNumeric($node->MetrazOd), CheckNumeric($node->MetrazDo), CheckNumeric($node->CenaOd), CheckNumeric($node->CenaDo),
                                                CheckNumeric($node->CenaM2Od), CheckNumeric($node->CenaM2Do), CheckNumeric($node->PietroOd), CheckNumeric($node->PietroDo), CheckNumeric($node->PokojeOd),
                                                CheckNumeric($node->PokojeDo), $node->Kraj == "" ? null : $node->Kraj, $node->Wojewodztwo == "" ? null : $node->Wojewodztwo, $node->Powiat == "" ? null : $node->Powiat,
                                                $node->Lokalizacja == "" ? null : $node->Lokalizacja, $node->Dzielnica == "" ? null : $node->Dzielnica, $node->Rejon == "" ? null : $node->Rejon, $node->Ulica,
                                                $node->Kategoria, $node["Oddzial"]);

                $photosNode = null;
                $lngsNode = null;
                $buildingsNode = null;
                $agentsNode = null;

                //read properties
                foreach ($node->children() as $propNode) {
                    if ($propNode->getName() == "Dokumenty"){
                        $documentsNode = $propNode;
                    }
                    if ($propNode->getName() == "Zdjecia")
                        $photosNode = $propNode;
                    else if ($propNode->getName() == "Jezyki")
                        $lngsNode = $propNode;
                    else if ($propNode->getName() == "Budynki")
                        $buildingsNode = $propNode;
                    else if ($propNode->getName() == "Agenci")
                        $agentsNode = $propNode;
                    else if ($propNode->getName() == "PolaDynamiczne") {
                        //delete unuse properties from offer
                        $addedProperties = array();
                        foreach ($propNode->children() as $listNode) {
                            $pname = (string) $listNode["Nazwa"];
                            $investment->__set($pname, $listNode);
                        }
                    }
                }
                //save investment object to database
                Investments::AddEditInvestment($investment);
                echo DataBase::GetDbInstance()->LastError();

                //delete unuse properties from offer
                $addedProperties = array();
                foreach ($node->children() as $propNode) {
                    $pname = $propNode->getName();
                    if ($pname == "PolaDynamiczne") {
                        foreach ($propNode->children() as $listNode) {
                            $pname = (string) $listNode["Nazwa"];
                            $prop = Properties::GetPropertyName($pname);
                            if ($prop != null)
                                $addedProperties[count($addedProperties)] = $prop->GetID();
                        }
                    }
                }
                Investments::DeleteUnUseProperties($investment->GetId(), $investment->GetIdLng(), $addedProperties);

                //photos
                if ($photosNode != null) {
                    $addedPhotos = array();
                    foreach ($photosNode->children() as $photoNode) {
                        $intro = $photoNode->intro == "True" ? 1 : 0;
                        $photo = new OfferPhoto($photoNode['ID'], null, $investment->GetId(), $photoNode->plik, $photoNode->opis, $photoNode['lp'], $photoNode['typ'], $intro, null, (string) $photoNode->LinkFilmYouTube,
                                                (string) $photoNode->LinkMiniaturkaYouTube);
                        OfferPhotos::AddEditPhoto($photo);
                        echo DataBase::GetDbInstance()->LastError();
                        $addedPhotos[count($addedPhotos)] = $photo->GetId();
                    }
                    OfferPhotos::DeleteUnUsePhotos(0, $addedPhotos, $investment->GetId());
                }

                //buildings
                if ($buildingsNode != null) {
                    $importedBlds = "";
                    foreach ($buildingsNode->children() as $buildingNode) {
                        $importedBlds .= $buildingNode["ID"] . ",";
                        $building = new InvestmentBuilding($investment->GetIdLng(), $buildingNode['ID'], $buildingNode->Nazwa, $buildingNode['Symbol'], $buildingNode->Opis, $investment->GetId(), CheckNumeric($buildingNode['Metraz']),
                        (string)$buildingNode->TerminOddania ? $buildingNode->TerminOddania : null, CheckNumeric($buildingNode['LiczbaPieter']));
                        Investmentbuildings::AddEditInvestmentBuilding($building);
                        echo DataBase::GetDbInstance()->LastError();

                        if(!empty($buildingNode->Oferty)) {
                            $offersNode = $buildingNode->Oferty;

                            // actual offers - in database
                            $actualOffers = array_map(function($item){
                                return $item->GetId();
                            }, $building->GetOffers());

                            // real offers - in xml
                            $realOffers = [];
                            foreach($offersNode->children() as $offer) {
                                $realOffers[] = (int)$offer['wartosc'];
                            }

                            // delete conection between building and offer if there's a difference
                            if($actualOffers != $realOffers) {
                                Investmentbuildings::RemoveOffersFromBuilding($actualOffers, $building);
                            }

                            //add offers to building
                            if(!empty($offersNode)) {
                                Investmentbuildings::AddOffersToBuilding($offersNode, $building);
                            }
                        }
                    }
                    //delete buildings
                    $importedBlds = substr($importedBlds, 0, strlen($importedBlds) - 1);
                    if ($importedBlds != "") {
                        $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT id FROM #S#investments_buildings WHERE investments_id=" . $investment->GetId() . " AND id NOT IN($importedBlds)");
                        while ($row = DataBase::GetDbInstance()->FetchArray($result)) {
                            Investmentbuildings::DeleteInvestmentBuilding($row[0]);
                            echo DataBase::GetDbInstance()->LastError();
                        }
                    }
                }

                //agenci
                //usuniecie starych
                Investments::DelInvestmentsAgents($investment->GetId());
                //dodanie nowych
                if ($agentsNode != null) {
                    foreach ($agentsNode->children() as $aNode) {
                        $a = Agents::GetAgent($aNode['wartosc']);
                        if ($a != null) {
                            Investments::AddInvestmentsAgent($investment, $a);
                        }
                    }
                }
            }

            //delete investments
            $importedInv = substr($importedInv, 0, strlen($importedInv) - 1);
            if ($importedInv == "")
                $importedInv = "-1";
            $result = DataBase::GetDbInstance()->ExecuteQuery("SELECT id FROM #S#investments WHERE id NOT IN($importedInv)");
            while ($row = DataBase::GetDbInstance()->FetchArray($result)) {
                Investments::DeleteInvestment($row[0]);
                //delete agents_investments relation
                Investments::DelInvestmentsAgents($row[0]);
                echo DataBase::GetDbInstance()->LastError();
            }

            return $count;
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:GetInvestments", $ex->getMessage() . "; " . $ex->getTraceAsString());
            return 0;
        }
    }

    /**
     * Reset offer on VIRGO server.
     */
    public function Reset() {
        if (!$this->WS())
            return null;
        try {
            $params = array('sid' => $this->_sid);
            $result = $this->WS()->getSC()->__soapCall("Reset", array($params));
            if ($result->ResetResult->Status <> 0) {
                Errors::LogError("WebService:Reset", "Response: " . $result->ResetResult->Message);
            }
            return $result->ResetResult->Message;
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:Reset", $ex->getMessage());
        }
    }

    /**
     * Get list of actual offer on web site.
     */
    public function GetOffersList() {
        if (!$this->WS())
            return null;
        try {
            $params = array('sid' => $this->_sid);
            $result = $this->WS()->getSC()->__soapCall("GetOfferList", array($params));
            if ($result->GetOfferListResult->Status <> 0) {
                Errors::LogError("WebServiceVirgo:GetOfferList", "Response: " . $result->GetOfferListResult->Message);
                return $result->GetOfferListResult->Message;
            } else {
                $buf = $result->GetOfferListResult->OffersZip;

                $f = fopen(self::TMP_ZIP_FILE, "w");
                fwrite($f, $buf);
                fclose($f);

                //unzip XML file with offers
                $zip = new ZipArchive();
                if ($zip->open(self::TMP_ZIP_FILE)) {
                    $fp = $zip->getStream('xml.xml');
                    if (!$fp)
                        exit("failed reading xml file\n");
                    $contents = '';
                    while (!feof($fp)) {
                        $contents .= fread($fp, 1024);
                    }
                    fclose($fp);
                    $zip->close();

                    file_put_contents(self::TMP_XML_OFELIST_FILE, $contents);
                    if (file_exists(self::TMP_ZIP_FILE))
                        unlink(self::TMP_ZIP_FILE);
                    $xml = simplexml_load_file(self::TMP_XML_OFELIST_FILE);
                    return true;
                }
            }
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:GetOfferList", $ex->getMessage());
            return $ex->getMessage();
        }
    }

    /**
     * Sets missing offers on pages
     * @param type $missingIds
     * @return null
     */
    public function SetMissingOffers($missingIds) {
        if (!$this->WS())
            return null;
        try {
            $params = array('sid' => $this->_sid, 'mids' => $missingIds);
            $result = $this->WS()->getSC()->__soapCall("SetMissingOffers", array($params));
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:SetMissingOffers", $ex->getMessage());
            return $ex->getMessage();
        }
    }

    /**
     * Note offers views on page.
     * @param int $id
     * @return string
     */
    public function NoteOffersViews($ofsCnt) {
        if (!$this->WS())
            return null;
        try {
            $params = array('sid' => $this->_sid, 'ofsCnt' => $ofsCnt);
            $result = $this->WS()->getSC()->__soapCall("NoteOffersViews", array($params));
            if ($result->NoteOffersViewsResult->Status <> 0) {
                Errors::LogError("WebServiceVirgo:NoteOffersViews", "Response: " . $result->NoteOffersViewsResult->Message);
            }
            return $result->NoteOffersViewsResult->Message;
        } catch (Exception $ex) {
            Errors::LogError("WebServiceVirgo:NoteOffersViews", $ex->getMessage());
        }
    }

    /**
     * Add search
     * @param array $data
     * @return string
     */
    public function AddSearch($data) {
        if (!$this->WS())
            return null;

        $allowedData = array('sid', 'powiadomienia', 'imie', 'nazwisko', 'telefon', 'komorka', 'email', 'uwagi', 'wojewodztwo', 'powiat', 'lokalizacja', 'dzielnica', 'rejon', 'ulica', 'przedmiot', 'uwagi',
                            'wynajem', 'rynek', 'cena_od', 'cena_do', 'cena_m2_od', 'cena_m2_do', 'pow_od', 'pow_do', 'il_pok_od', 'il_pok_do', 'pietro_od', 'pietro_do', 'il_pieter_od', 'il_pieter_do', 'rok_bodowy_od',
                            'rok_budowy_do');
        $allowedInt = array('il_pok_od', 'il_pok_do');
        $allowedFloat = array('pow_od', 'pow_do', 'cena_od', 'cena_do');


        try {
            $params = array('sid' => $this->_sid);
            foreach ($allowedData as $key) {
                if (isset($data[$key])) {

                    if (in_array($key, $allowedInt))
                        $params[$key] = (int) $data[$key];
                    elseif (in_array($key, $allowedFloat))
                        $params[$key] = (float) str_replace(',', '.', $data[$key]);
                    else
                        $params[$key] = $data[$key];
                }
            }

            $result = $this->WS()->getSC()->__soapCall("AddSearch", array($params));
            return $result->AddSearchResult;
        } catch (Exception $ex) {
            Errors::LogError("WebService:AddSearch", $ex->getMessage());
        }
    }

    /**
     *  Add an offer
     * @param array $dataArray
     * @return string
     */
    public function AddOffer(array $dataArray) {
        if (!$this->WS())
            return null;
        try {
            $clientMapArr = array('imie' => 'Imie',
                'nazwisko' => 'ImieNazwisko',
                'telefon' => 'TelDom',
                'komorka' => 'TelKomorka',
                'uwagi' => 'Uwagi',
                'email' => 'Email',);

            $offerMapArr = array('uwagi' => 'Uwagi',
                'wojewodztwo' => 'Wojewodztwo',
                'lokalizacja' => 'Lokalizacja',
                'dzielnica' => 'Dzielnica',
                'przedmiot' => 'Przedmiot', //(Mieszkanie, Dom, Dzialka, Lokal, Hala, Biurowiec)
                'wynajem' => 'Wynajem',
                'cena' => 'Cena',
                'pow' => 'PowierzchniaCalkowita',
                'il_pok' => 'IloscPokoi',
                'zamiana' => 'Zamiana',
                's_przyjecia' => 'SposobPrzyjecia',);

            $floatValues = array('pow', 'cena');
            $offerNode = new SimpleXMLElement("<Oferta></Oferta>");
            $clientNode = $offerNode->addChild("Klient");

            if (isset($dataArray['wynajem'])) {
                $offerNode->addAttribute('Wynajem', (int) $dataArray['wynajem']);
                unset($dataArray['wynajem']);
            }

            if (isset($dataArray['zamiana'])) {
                $offerNode->addAttribute('Zamiana', $dataArray['zamiana']);
                unset($dataArray['zamiana']);
            }

            //override input array
            foreach ($dataArray as $name => $value) {
                if($value){
                    if (in_array($name, $floatValues))
                        $value = str_replace('.', ',', $value);
                    if (array_key_exists($name, $clientMapArr)) {
                        $clientNode->addChild($clientMapArr[$name], $value);
                    } else if (array_key_exists($name, $offerMapArr)) {
                        $offerNode->addChild($offerMapArr[$name], $value);
                    }
                }
            }

            $xmlString = $offerNode->saveXML();
            $tempFileName = md5(time() + $_SERVER['HTTP_USER_AGENT'] + $_SERVER['REMOTE_ADDR']) . '.zip';

            $zip = new ZipArchive;
            $zip->open($tempFileName, ZipArchive::CREATE);
            $zip->addFromString('oferta.xml', $xmlString);
            $zip->close();

            $xmlZip = file_get_contents($tempFileName);
            unlink($tempFileName);

            $params = array('sid' => $this->_sid,
                'xmlZip' => $xmlZip);

            $result = $this->WS()->getSC()->__soapCall("AddOfferNew", array($params));
            return $result;
        } catch (Exception $ex) {
            Errors::LogError("WebService:AddOffer", $ex->getMessage());
        }
    }

    /**
     * Get lists
     * @param mix $idsArray
     * @return string
     */
    public function GetLists($idsArray = null) {

        if (!$this->WS())
            return null;

        try {
            if ($idsArray == null)
                $idsArray = array_values(get_class_vars("ListsEnums"));

            $params = array(
                'sid' => $this->_sid,
                'intTab' => $idsArray
            );

            $result = $this->WS()->getSC()->__soapCall("GetLists", array($params));
            $buf = $result->GetListsResult->ByteZip;

            $f = fopen(self::TMP_LISTS_ZIP_FILE, "w");
            fwrite($f, $buf);
            fclose($f);

            //unzip XML file with lists
            $zip = new ZipArchive();
            if ($zip->open(self::TMP_LISTS_ZIP_FILE)) {
                $fp = $zip->getStream('listy.xml');
                if (!$fp)
                    exit("failed reading xml file (" . getcwd() . ")\n");
                $contents = '';
                while (!feof($fp)) {
                    $contents .= fread($fp, 2);
                }
                fclose($fp);
                $zip->close();

                file_put_contents(self::TMP_XML_LISTS_FILE, $contents);
                if (file_exists(self::TMP_LISTS_ZIP_FILE))
                    unlink(self::TMP_LISTS_ZIP_FILE);

                $db = DataBase::GetDbInstance();

                $query = "DELETE FROM listy WHERE enum_id IN (" . implode(',', array_fill(0, count($idsArray), '?')) . ")";
                $params = array_values($idsArray);
                $db->ExecuteQueryWithParams($query, $params);

                $xml = simplexml_load_file(self::TMP_XML_LISTS_FILE);
                $listsNode = null;

                //read main nodes
                foreach ($xml->children() as $child) {
                    $query = "INSERT INTO listy VALUES(?, ?, ?)";
                    $params = array((int) $child['Id'], (int) $child['Rodzaj'], (string) $child->Tresc);

                    $r = $db->ExecuteQueryWithParams($query, $params);
                }
            }
            return true;
        } catch (Exception $ex) {
            Errors::LogError("WebService:GetLists", $ex->getMessage());
        }
    }

    /**
     * add/edit seller
     * @param array $dataArray
     * @return string
     */
    public function AddEditSeller(array $dataArray) {

        if (!$this->WS())
            return null;

        try {
            $xml = new SimpleXMLElement("<Seller></Seller>");
            if (isset($dataArray['Id'])) {
                $xml->addAttribute('Id', $dataArray['Id']);
                unset($dataArray['Id']);
            }

            foreach ($dataArray as $name => $value) {
                $xml->addChild($name, $value);
            }

            $xmlString = $xml->saveXML();
            dump($xmlString);
            $tempFileName = md5(time() + $_SERVER['HTTP_USER_AGENT'] + $_SERVER['REMOTE_ADDR']) . '.zip';

            $zip = new ZipArchive;
            $res = $zip->open($tempFileName, ZipArchive::CREATE);

            $zip->addFromString('seller.xml', $xmlString);
            $zip->close();

            $xmlZip = file_get_contents($tempFileName);
            unlink($tempFileName);

            $params = array(
                'sid' => $this->_sid,
                'xmlZip' => $xmlZip
            );

            $result = $this->WS()->getSC()->__soapCall("AddEditSeller", array($params));
            return $result;
        } catch (Exception $ex) {
            Errors::LogError("WebService:AddEditSeller", $ex->getMessage());
        }
    }

    /**
     * Import locations
     * @param mix $type
     * @return null
     */
    public function GetLocationsAll($type = false) {
        if (!$this->WS())
            return null;

        try {
            $params = array(
                'sid' => $this->_sid
            );

            $result = $this->WS()->getSC()->__soapCall("GetLocationsAll", array($params));
            $buf = $result->GetLocationsAllResult->ByteZip;

            $f = fopen(self::TMP_LOCATIONSALL_ZIP_FILE, "w");
            fwrite($f, $buf);
            fclose($f);

            //unzip XML file with lists
            $zip = new ZipArchive();
            if ($zip->open(self::TMP_LOCATIONSALL_ZIP_FILE)) {
                $fp = $zip->getStream('locationsall.xml');
                if (!$fp)
                    exit("failed reading xml file (" . getcwd() . ")\n");
                $contents = '';
                while (!feof($fp)) {
                    $contents .= fread($fp, 2);
                }
                fclose($fp);
                $zip->close();

                file_put_contents(self::TMP_XML_LOCATIONSALL_FILE, $contents);
                if (file_exists(self::TMP_LOCATIONSALL_ZIP_FILE))
                    unlink(self::TMP_LOCATIONSALL_ZIP_FILE);

                $db = DataBase::GetDbInstance();

                $xml = simplexml_load_file(self::TMP_XML_LOCATIONSALL_FILE);
                $listsNode = null;

                //read main nodes
                if ($type === false || $type == 1) {
                    $query = "DELETE FROM powiaty";
                    $db->ExecuteQuery($query);

                    foreach ($xml->Powiaty->children() as $child) {
                        $query = "INSERT INTO powiaty VALUES(?, ?, ?)";
                        $params = array((int) $child['Id'], $child['Nazwa'], (int) $child['WojewodztwoId']);
                        $r = $db->ExecuteQueryWithParams($query, $params);
                    }
                }

                if ($type === false || $type == 2) {
                    $query = "DELETE FROM lokalizacje";
                    $db->ExecuteQuery($query);

                    foreach ($xml->Lokalizacje->children() as $child) {
                        $query = "INSERT INTO lokalizacje VALUES(?, ?, ?, ?, ?)";
                        $params = array((int) $child['Id'], $child['Nazwa'], (int) $child['PowiatId'], (int) $child['WojewodztwoId'], ($child['Gmina'] == 'True' ? 1 : 0));
                        $r = $db->ExecuteQueryWithParams($query, $params);
                    }
                }

                if ($type === false || $type == 3) {
                    $query = "DELETE FROM dzielnice";
                    $db->ExecuteQuery($query);

                    foreach ($xml->Dzielnice->children() as $child) {
                        $query = "INSERT INTO dzielnice VALUES(?, ?, ?)";
                        $params = array((int) $child['Id'], $child['Nazwa'], (int) $child['LokalizacjaId']);
                        $r = $db->ExecuteQueryWithParams($query, $params);
                    }
                }

                if ($type === false || $type == 4) {
                    $query = "DELETE FROM rejony";
                    $db->ExecuteQuery($query);

                    foreach ($xml->Regiony->children() as $child) {
                        $query = "INSERT INTO rejony VALUES(?, ?, ?)";
                        $params = array((int) $child['Id'], $child['Nazwa'], (int) $child['DzielnicaId']);
                        $r = $db->ExecuteQueryWithParams($query, $params);
                    }
                }

                if (file_exists(self::TMP_XML_LOCATIONSALL_FILE))
                    unlink(self::TMP_XML_LOCATIONSALL_FILE);
            }
        } catch (Exception $ex) {
            Errors::LogError("WebService:GetLists", $ex->getMessage());
        }
    }

}

?>
