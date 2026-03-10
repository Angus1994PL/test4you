<?php

/**
 * Description of waluty
 *
 * @author Jakub Konieczka
 */
class Waluty {

    /**
     *
     * @param array $row
     * @return Waluta
     */
    protected static function BuildWaluta($row){
		$waluta = new Waluta($row['waluta'],$row['kurs'],$row['opis']);
		return $waluta;
	}

    /**
	 * Returns an waluta object from the database by key.
	 * @param string $symbol
	 * @return Waluta
	 */
	public static function GetWaluta($symbol){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#waluty_kursy WHERE waluta=$1", array($symbol));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildWaluta($row);
			else return null;
		}else return null;
	}

    /**
	 * Add given waluta object to database.
	 * @param Waluta $waluta
	 */
	public static function AddWaluta(Waluta $waluta){
		$query = "INSERT INTO #S#waluty_kursy (waluta, kurs, opis) VALUES(?, ?, ?);";
		$params = array($waluta->GetSymbol(), $waluta->GetKurs(), $waluta->GetOpis());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
		//echo $result;
	}

    /**
	 * Delete waluta from database, given by symbol.
	 * @param string $symbol
	 */
	public static function DeleteWaluta($symbol){
        $query = "DELETE FROM #S#waluty_kursy WHERE 1=1";
         if($symbol != null){
            $query .= " AND waluta=?";
            $params[]=$symbol;
        }
        $result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

    /**
	 * Add or edit if waluta, given option object.
	 * @param Waluta $waluta
	 */
	public static function AddEditWaluta(Waluta $waluta){
		if($waluta != null){
            self::DeleteWaluta($waluta->GetSymbol());
			self::AddWaluta($waluta);
		}
	}

    /**
     * Download exchange rates from NBP.
     */
    public static function PobierzZNbp(){
        try {
            $handle = fopen("http://www.nbp.pl/Kursy/KursyA.html", "r");
            if (!$handle) {
                die("<p>Unable to open remote file.</p>");
            }
            $xmlFile = null;
            while (!feof ($handle)) {
                $line = fgets ($handle, 1024);
                /* This only works if the title and its tags are on one line */
                if (preg_match ("<a href=\"(\\S+).xml\".+>", $line, $out)) {
                    $xmlFile = $out[1];
                    break;
                }
            }
            fclose($handle);
            if ($xmlFile[0] === "/"){
                $xmlFile = substr($xmlFile,1);
            }
            $xml = simplexml_load_file("http://www.nbp.pl/".$xmlFile.".xml");
            foreach($xml->children() as $pozycja){
                if($pozycja->getName() == "pozycja"){
                    $symbol = $pozycja->kod_waluty;
                    $przelicznik = $pozycja->przelicznik;
                    $kurs = str_replace(",", ".", $pozycja->kurs_sredni);
                    $nazwa = $pozycja->nazwa_waluty;
                    $walutaKurs = new Waluta($symbol, $kurs, $nazwa);
                    self::AddEditWaluta($walutaKurs);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
?>
