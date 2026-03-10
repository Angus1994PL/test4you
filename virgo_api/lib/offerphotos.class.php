<?php

/**
 * Class provides methods for managing pictures of offer.
 * @author Jakub Konieczka
 *
 */
class OfferPhotos{

	/**
	 * Creates a photo object on the basis of data from the database.
	 * @param array $row
	 * @return OfferPhoto
	 */
	protected static function BuildPhoto($row){
		$photo = new OfferPhoto($row['id'],$row['offers_id'],$row['investments_id'],$row['filename'],$row['description'],$row['order'],$row['type'],$row['intro'],$row['foto_id'],$row['LinkFilmYouTube'],$row['LinkMiniaturkaYouTube']);
		return $photo;
	}

    /**
     * Get list of directories for given path
     * @param string $directory
     * @param string $pattern
     * @param string $filename
     * @return type
     */
    protected static function GetDirectoryList ($directory, $pattern = "", $filename = ""){
        $results = array();
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..") {
                if($pattern == "" && $filename == "") $results[] = $file;
                else if(strtolower(substr($file, 0, strlen($pattern))) == $pattern){
                    $results[] = $file;
                }else if(strtolower(substr($file, 0, strlen($filename))) == $filename){
                    $results[] = $file;
                }
            }
        }
        closedir($handler);
        return $results;
    }
    
    /**
     * Prepare filters to GetQuery
     * @param mixed $filters
     * @return array
     */
	protected static function PrepareFilters($filters = null){
		if($filters == null) return $filters;
        $tab_with_numeric_value= array("id","offers_id","investments_id","order","intro","foto_id");
		$newFilters = array();
		foreach ($filters as $key => $value){
            if(!is_array($value)){ 
                if(array_search($key, $tab_with_numeric_value)){
                    if(is_numeric($value)) $newFilters[$key]= (int) trim($value,'\'"');
                }else{
                    $newFilters[$key] = trim($value,'\'"');
                }
            }else{ $newFilters[$key] = $value;}
            
			switch ($key) {	
                case "not_ids": array_pop($newFilters); $newFilters = array_merge($newFilters, self::prepareStringToArray($value, true)); break;
			}
		}
		return $newFilters;
	}
    
    /**
     * Converts array or string to GetQuery
     * @param mixed $value
     * @return string
     */
	protected static function prepareStringToBind($value){
        if(is_array($value)) $arr = $value;
        else $arr = explode(',', $value);
		return implode(',', array_fill(0, count($arr), '?')); 
	}
    
	/**
     * Converts array or string to GetQuery
     * @param mixed $value
     * @return string[]
     */
	protected static function prepareStringToArray($value, $ints = false){		
        if(is_array($value)) $arr=$value;
		else $arr = explode(',', $value);
		$newArr = array();
		foreach($arr as $value){
            if($ints) $newArr[] = (int) trim($value, "' ");
			else $newArr[] = trim($value, "' ");
		}
		return $newArr;
	}
    
    
	/**
	* Returns cleared value for SQL query.
	* @param String $value
	* @param bool $remove
	* @return $value
	*/
	protected static function prepareSort($value){
		$orderbyArray = array('order');
		$destArray = array('asc', 'desc');
	
		$exp = explode(' ', $value);
		$orderby = 'id';
		$dest = 'desc';
	
		if(isset($exp[0]) && in_array($exp[0], $orderbyArray)) $orderby = $exp[0];
		if(isset($exp[1]) && in_array($exp[1], $destArray)) $dest = $exp[1];
	
		return $orderby.' '.$dest;
	}
    
    /**
     * Creates query string from given params
     * @param string $select
     * @param string $sorting
     * @param mixed $filters
     * @return string
     */
    protected static function GetQuery($select = "SELECT * ", $sorting = "", $filters = null){
		$query = $select . " FROM #S#offers_photos op WHERE 1=1 ";
		if($filters != null){
			foreach ($filters as $key => $value){
				switch ($key) {
                    case "id": $query .= " AND op.id=?"; break;
                    case "not_ids": $query .= " AND op.id NOT IN (" . self::prepareStringToBind($value) . ")"; break;
					default: $query .= " AND op.$key=?"; break;					
				}
			}	
		}
		if($sorting != ""){
			$query .= " ORDER BY op.".self::prepareSort($sorting);
		}
		return $query;
	}
    
	/**
	 * Returns a photo object from the database by ID.
	 * @param int $id
	 * @return OfferPhoto
	 */
	public static function GetPhoto($id){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_photos WHERE id=?", array((int) $id));
		if($result){
			$row = DataBase::GetDbInstance()->FetchArray($result);
			if($row) return self::BuildPhoto($row);
			else return null;
		}else return null;
	}
	
	/**
	 * Add given photo object to database.
	 * @param OfferPhoto $photo
	 */
	public static function AddPhoto(OfferPhoto $photo){
		$query = "INSERT INTO #S#offers_photos (id, offers_id, investments_id, filename, description, `order`, `type`, intro, foto_id, LinkFilmYouTube, LinkMiniaturkaYouTube) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		
        is_numeric($photo->GetId())?$get_id=$photo->GetId():$get_id=$photo->GetId();
        is_numeric($photo->GetOfferId())?$get_offer_id= $photo->GetOfferId():$get_offer_id=$photo->GetOfferId();
        is_numeric($photo->GetInvestmentId())?$get_investment_id=$photo->GetInvestmentId():$get_investment_id=$photo->GetInvestmentId();
        is_numeric($photo->GetFotoId())?$get_foto_id=$photo->GetFotoId():$get_foto_id=$photo->GetFotoId();
        
        $params = array($get_id, $get_offer_id, $get_investment_id, $photo->GetFilename(), $photo->GetDescription(), $photo->GetOrder(), $photo->GetType(), (int) $photo->GetIntro(), (int) $get_foto_id, $photo->GetLinkFilmYouTube(), $photo->GetLinkMiniaturkaYouTube());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}
	
	/**
	 * Save given photo object (photoNew) to database. 
	 * @param $photoNew
	 */
	public static function EditPhoto(OfferPhoto $photoNew){
		$query = "UPDATE #S#offers_photos SET offers_id=?, filename=?, description=?, `order`=?, `type`=?, investments_id=?, intro=?, foto_id=?, LinkFilmYouTube=?, LinkMiniaturkaYouTube=? WHERE id=?;";
		$params = array($photoNew->GetOfferId(), $photoNew->GetFilename(),
			$photoNew->GetDescription(), $photoNew->GetOrder(), $photoNew->GetType(), $photoNew->GetInvestmentId(), $photoNew->GetIntro(), $photoNew->GetFotoId(), $photoNew->GetLinkFilmYouTube(), $photoNew->GetLinkMiniaturkaYouTube(), (int) $photoNew->GetId());
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, $params);
	}

	/**
	 * Add or photo if exists, given agent object.
	 * @param OfferPhoto $photo
	 */
	public static function AddEditPhoto(OfferPhoto $photo){	 
		$f = self::GetPhoto($photo->GetId());
		if($f == null){
			self::AddPhoto($photo);
		}else{
			self::EditPhoto($photo);
		}
	}

	/**
	 * Return an array of photos for given offer.
	 * @param int $offerId
	 * @return OfferPhoto[]
	 */
	public static function GetPhotos($offerId){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_photos WHERE offers_id=? AND type<>'SWF' AND type<>'Filmy' ORDER BY `order` ASC", array((int) $offerId));
		$photos = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$photos[$ndx] = self::BuildPhoto($row);
			$ndx++;			
		}
		return $photos;
	}
	
	/**
	 * Return an array of photos for given offer and type of photo.
	 * @param int $offerId
	 * @param string $type
	 * @return OfferPhoto[]
	 */
	public static function GetPhotosByType($offerId, $type = 'Zdjecie'){
		//var_dump($type);
		$allowedTypes = array('Zdjecie', 'Panorama', 'Rzut', 'Filmy');
		if(!in_array($type, $allowedTypes)) return false;
		
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_photos WHERE offers_id=? AND type=? ORDER BY `order` ASC", array((int) $offerId, $type));
		$photos = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$photos[$ndx] = self::BuildPhoto($row);
			$ndx++;
		}
		return $photos;
	}
	
	/**
	 * Return an array of photos for given offer.
	 * @param int $offerId
     * @param bool $bezintra
	 * @return OfferPhoto[]
	 */
	public static function GetSWFs($offerId, $bezintra = false){
        $query = "SELECT * FROM #S#offers_photos WHERE offers_id=? AND type='SWF' ";
        if($bezintra) $query.=" AND intro=0 ";
        $query.=" ORDER BY `order` ASC";
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams($query, array((int) $offerId));
		$photos = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$photos[$ndx] = self::BuildPhoto($row);
			$ndx++;			
		}
		return $photos;
	}

	/**
	 * Return an array of photos for given investment.
	 * @param int $investmentId
	 * @return OfferPhoto[]
	 */
	public static function GetPhotosInvestment($investmentId){
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_photos WHERE investments_id=? ORDER BY `order` ASC", array((int) $investmentId));
		$photos = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$photos[$ndx] = self::BuildPhoto($row);
			$ndx++;			
		}
		return $photos;
	}
	
	/**
	* Return an array of photos for given investment.
	* @param int $investmentId
	* @return OfferPhoto[]
	*/
	public static function GetPhotosInvestmentByType($investmentId, $type = 'Zdjecie'){
		
		$allowedTypes = array('Zdjecie', 'Rzut', 'Mapa');
		if(!in_array($type, $allowedTypes)) return false;
		
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT * FROM #S#offers_photos WHERE investments_id=? AND type=? ORDER BY `order` ASC", array((int) $investmentId, $type));
		$photos = array();
		$ndx = 0;
		while($row = DataBase::GetDbInstance()->FetchArray($result)){
			$photos[$ndx] = self::BuildPhoto($row);
			$ndx++;
		}
		return $photos;
	}
	
    /**
     * Delete photos from disk
     * @param int $idPhoto
     * @param int $idOffer
     * @param int $idInvestment
     * @param string $filename
     */
    public static function DeletePhotoFromDisk($idPhoto, $idOffer, $idInvestment = 0, $filename=""){
        $path = '';
        $suf = $idOffer < 100 ? $idOffer : substr($idOffer, 0, 2);
        
        if($idOffer != 0) $path = $_SERVER['DOCUMENT_ROOT'].Config::$AppPath. "/photos/ofs_$suf/offer_" . $idOffer;
        if($idInvestment != 0) $path = $_SERVER['DOCUMENT_ROOT'].Config::$AppPath. "/photos/investment_" . $idInvestment;
        if (file_exists($path)) {
            $files = self::GetDirectoryList($path, $idPhoto == 0 ? "" : $idPhoto."_", $filename);
            foreach($files as $file){
                unlink($path."/".$file);
            }
            $files = self::GetDirectoryList($path, "");
            if(count($files) == 0) rmdir ($path);
        }
    }

	/**
	 * Delete offer photo from database and disk, given by ID.
	 * @param int $id
	 */
	public static function DeletePhoto($id){
		//first delete from disk
        $ofeId = DataBase::GetDbInstance()->UniqueResult("SELECT offers_id FROM #S#offers_photos WHERE id=$id");
        self::DeletePhotoFromDisk($id, $ofeId);
        //now from database
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_photos WHERE id=?", array((int) $id));
	}

	/**
	 * Delete all phots for given offer.
	 * @param int $idOffer
	 */
	public static function DeletePhotos($idOffer){        
        if(is_numeric($idOffer)) $idOffer = (int) $idOffer;
		//first delete from disk
        self::DeletePhotoFromDisk(0, $idOffer);
        //now from database
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_photos WHERE offers_id=?", array((int) $idOffer));
	}

	/**
	 * Delete all photos for given investment.
	 * @param int $idInvestment
	 */
	public static function DeletePhotosInvestment($idInvestment){
		//first delete from disk
        self::DeletePhotoFromDisk(0, 0, $idInvestment);
        //now from database
		$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_photos WHERE investments_id=?", array((int) $idInvestment));
	}
	
	/**
	 * Delete all unused photos, that are no longer published.
	 * @param int $offerId
	 * @param array $Ids
     * @param mixed $investmentId
	 */
	public static function DeleteUnUsePhotos($offerId = false, $Ids = array(), $investmentId = false){
		if(count($Ids) > 0 && $offerId){
            $params = array();
			$params[] = (int) $offerId;
            $params['not_ids'] = $Ids;
            $filters=self::PrepareFilters($params);
			$inBind = implode(',', array_fill(0, count($Ids), '?'));
            //first delete from disk
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT id, filename FROM #S#offers_photos WHERE offers_id=? AND id NOT IN ($inBind)", $filters);
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                self::DeletePhotoFromDisk($row[0], $offerId,0, $row[1]);
            }
			//now from database
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_photos WHERE offers_id=? AND id NOT IN ($inBind)", $filters);
		}else self::DeletePhotoFromDisk(0, $offerId);

		if(count($Ids) > 0 && $investmentId){
			$params = array();
			$params[] = (int) $investmentId;
            $params['not_ids'] = $Ids;
            $filters=self::PrepareFilters($params);
			$inBind = implode(',', array_fill(0, count($Ids), '?'));

            //first delete from disk
            $result = DataBase::GetDbInstance()->ExecuteQueryWithParams("SELECT id FROM #S#offers_photos WHERE investments_id=? AND id NOT IN ($inBind)", $filters);
            while($row = DataBase::GetDbInstance()->FetchArray($result)){
                self::DeletePhotoFromDisk($row[0], $offerId);
            }

            //now from database
			$result = DataBase::GetDbInstance()->ExecuteQueryWithParams("DELETE FROM #S#offers_photos WHERE investments_id=? AND id NOT IN ($inBind)", $filters);
		}else self::DeletePhotoFromDisk(0, 0, $investmentId);
	}

	/**
	 * Return path to photo file, if file not exist, download it.
	 * @param OfferPhoto $photo
	 * @param string $customSize Custom size, written as width_height, ex. 400_300
     * @param bool $basicWatermark
     * @param bool $additionalWatermark
	 * @return string
	 */  
	public static function GetImg(OfferPhoto $photo, $customSize, $basicWatermark, $additionalWatermark, $kadruj){
		//testing size params
        if(!preg_match("/0*[1-9][0-9]*_0*[1-9][0-9]*/", $customSize)) return "";
				
		$path = getcwd() . "/photos";
		if (!file_exists($path)) {mkdir($path);chmod($path, 0775);}

        $suf = $photo->GetOfferId() < 100 ? $photo->GetOfferId() : substr($photo->GetOfferId(), 0, 2);
        $path = getcwd() . "/photos/ofs_" . $suf;
        if (!file_exists($path)) {mkdir($path);chmod($path, 0775);}
	
		$path = getcwd() . "/photos/ofs_$suf/offer_" . $photo->GetOfferId();
		if($photo->GetInvestmentId() > 0)
			$path = getcwd() . "/photos/investment_" . $photo->GetInvestmentId();
		if (!file_exists($path)) {mkdir($path);chmod($path, 0775);}
				
		$fileName = "/" . $photo->GetId() . "_$customSize" . "_" . ($basicWatermark ? "1" : "0") . "_" . ($additionalWatermark ? "1" : "0") . "_" . ($kadruj ? "1" : "0") . ".jpg";
		$path .= $fileName;        

		if (!file_exists($path)){
			//get image from server
			WebServiceVirgo::WS()->LoginEx(true);
            $buf = WebServiceVirgo::WS()->GetImage($photo->GetId(), $customSize, $basicWatermark, $additionalWatermark, $kadruj);
			if ($buf != null) {
				$file = fopen($path, "wb");
				fwrite($file, $buf);
				fclose($file);
			}
		}
		$path = Config::$AppPath . "/photos/ofs_$suf/offer_" . $photo->GetOfferId() . $fileName;
		if($photo->GetInvestmentId() > 0)
			$path = Config::$AppPath . "/photos/investment_" . $photo->GetInvestmentId() . $fileName;
		return $path;
	}
	
	/**
	 * Return path to SWF file, if file not exist, download it.
	 * @param OfferPhoto $photo
	 * @return string
	 */
	public static function GetSWF(OfferPhoto $photo){
		$path = getcwd() . "/photos";
		if (!file_exists($path)) {mkdir($path);chmod($path, 0775);}

        $suf = $photo->GetOfferId() < 100 ? $photo->GetOfferId() : substr($photo->GetOfferId(), 0, 2);
        $path = getcwd() . "/photos/ofs_" . $suf;
        if (!file_exists($path)) {mkdir($path);chmod($path, 0775);}

		$path = getcwd() . "/photos/ofs_$suf/offer_" . $photo->GetOfferId();
		if($photo->GetInvestmentId() > 0)
			$path = getcwd() . "/photos/investment_" . $photo->GetInvestmentId();
		if (!file_exists($path)) {mkdir($path);chmod($path, 0775);}
		
		$fileName = "/" . $photo->GetFilename();
		$path .= $fileName;
		if (!file_exists($path)){
			//get image from server
			WebServiceVirgo::WS()->LoginEx(true);
			$buf = WebServiceVirgo::WS()->GetSWF($photo->GetId());
			if ($buf != null) {
				$file = fopen($path, "wb");
				fwrite($file, $buf);
				fclose($file);
			}
		}
		$path = Config::$AppPath . "/photos/ofs_$suf/offer_" . $photo->GetOfferId() . $fileName;
		if($photo->GetInvestmentId() > 0)
			$path = Config::$AppPath . "/photos/investment_" . $photo->GetInvestmentId() . $fileName;
		return $path;
	}
	
}

?>