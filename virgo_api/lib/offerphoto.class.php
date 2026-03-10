<?php

/**
 * Class describing a picture of offer.
 * @author Jakub Konieczka
 *
 */
class OfferPhoto extends AObject {

	private $_OfferId;
	private $_InvestmentId;
	private $_Filename;
	private $_Description;
	private $_Order;
	private $_Type;
	private $_Intro;
    private $_FotoId;
    
    private $_LinkFilmYouTube;
    private $_LinkMiniaturkaYouTube;

    private $_OfferObj = null;
	private $_InvestmentObj = null;
	
	public function GetOfferId(){
		return $this->_OfferId;
	}

	public function SetOfferId($value){
		$this->_OfferId = $value;
	}
	
	public function GetInvestmentId(){
		return $this->_InvestmentId;
	}

	public function SetInvestmentId($value){
		$this->_InvestmentId = $value;
	}

	public function GetFilename(){
		return $this->_Filename;
	}

	public function SetFilename($value){
		$this->_Filename = $value;
	}

	public function GetDescription(){
		return $this->_Description;
	}

	public function SetDescription($value){
		$this->_Description = $value;
	}

	public function GetOrder(){
		return $this->_Order;
	}

	public function SetOrder($value){
		$this->_Order = $value;
	}

	public function GetType(){
		return $this->_Type;
	}

	public function SetType($value){
		$this->_Type = $value;
	}

	public function GetIntro(){
		return $this->_Intro;
	}

	public function SetIntro($value){
		$this->_Intro = $value;
	}

    public function GetFotoId(){
		return $this->_FotoId;
	}

	public function SetFotoId($value){
		$this->_FotoId = $value;
	}
    
    public function GetLinkFilmYouTube(){
		return $this->_LinkFilmYouTube;
	}

	public function SetLinkFilmYouTube($value){
		$this->_LinkFilmYouTube = $value;
	}
    
    public function GetLinkMiniaturkaYouTube(){
		return $this->_LinkMiniaturkaYouTube;
	}

	public function SetLinkMiniaturkaYouTube($value){
		$this->_LinkMiniaturkaYouTube = $value;
	}
	
	/**
	 * Return offer as object.
	 * @return Offer
	 */
	public function GetOfferObj(){
		if($this->_OfferObj == null){
			$ofs = new Offers();
			$this->_OfferObj = $ofs->GetOffer($this->GetOfferId());
		}
		return $this->_OfferObj;
	}

	/**
	 * Return investment as object.
	 * @return Investment
	 */
	public function GetInvestmentObj(){
		if($this->_InvestmentObj == null){
			$this->_InvestmentObj = Investments::GetInvestment($this->GetInvestmentId());
		}
		return $this->_InvestmentObj;
	}
	
	/**
	 * Return path to photo file, if file not exist, download it and save on disk.
	 * @param string $customSize Custom size, written as width_height, ex. 400_300
     * @param bool $basicWatermark
     * @param bool $additionalWatermark
     * @param bool $kadruj
	 * @return string
	 */
	public function GetImgSrc($customSize, $basicWatermark, $additionalWatermark, $kadruj = false){
		return OfferPhotos::GetImg($this, $customSize, $basicWatermark, $additionalWatermark, $kadruj);
	}

    /**
     * Return path to photo file, if file not exist, download it and save on disk.
     * @param string $customSize
     * @param bool $basicWatermark
     * @param bool $additionalWatermark
     * @param bool $kadruj
     * @return string
     */
    public function GetSwfImgSrc($customSize, $basicWatermark, $additionalWatermark, $kadruj = false){
        if($this->GetFotoId() == null || $this->GetFotoId() == 0) return Config::$AppPath . "/images/fla.png";
        $foto = OfferPhotos::GetPhoto($this->GetFotoId());
        if($foto == null) return Config::$AppPath . "/images/fla.png";
		return OfferPhotos::GetImg($foto, $customSize, $basicWatermark, $additionalWatermark, $kadruj);
	}
	
	/**
	 * Return path to SWF file, if file not exist, download it and save on disk.
	 * @return string
	 */
	public function GetSWFSrc(){
		return OfferPhotos::GetSWF($this);
	}
	
	/**
	 * Return URL to folder with swf files.
	 * @return string
	 */
	public function GetBaseLink(){
        $suf = $this->GetOfferId() < 100 ? $this->GetOfferId() : substr($this->GetOfferId(), 0, 2);
        $path = getcwd() . "/photos/ofs_" . $suf;
        if (!file_exists($path)) {mkdir($path);}
		return $_SERVER["SERVER_NAME"] . Config::$AppPath . "/photos/ofs_$suf/offer_" . $this->GetOfferId() . "/";
	}
	
	/**
	 * Download SWF file from server, if don't exists local.
	 */
	public function DownloadSWF(){
		$this->GetSWFSrc();
	}

	public function __construct($id, $OfferId, $InvestmentId, $Filename, $Description, $Order, $Type, $Intro, $FotoId, $LinkFilmYouTube, $LinkMiniaturkaYouTube){
		$this->SetId($id);
		$this->SetOfferId($OfferId);
		$this->SetInvestmentId($InvestmentId);
		$this->SetFilename($Filename);
		$this->SetDescription($Description);
		$this->SetOrder($Order);
		$this->SetType($Type);
		$this->SetIntro($Intro);
        $this->SetFotoId($FotoId);
        $this->SetLinkFilmYouTube($LinkFilmYouTube);
        $this->SetLinkMiniaturkaYouTube($LinkMiniaturkaYouTube);
	}

}

?>