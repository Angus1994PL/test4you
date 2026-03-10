<?php

/**
 * Class describing the rooms of offer.
 * @author Jakub Konieczka
 *
 */
class OfferRoom {

	private $_Id;
	private $_OfferId;
	private $_OfferLng;
	private $_Kind;
	private $_Order;
	private $_Area;
	private $_Level;
	private $_Type;
	private $_Height;
	private $_KitchenType;
	private $_Number;
	private $_Glaze;
	private $_WindowView;
	private $_Description;
	private $_FloorsState;
	private $_RoomType;
	
	private $_Walls = array();
	private $_Floors = array();
	private $_WindowsExhibition = array();
	private $_Equipment = array();
	
	private $_OfferObj = null; 
	
	public function GetId(){
		return $this->_Id;
	}

	public function SetId($value){
		$this->_Id = $value;
	}
		
	public function GetOfferId(){
		return $this->_OfferId;
	}

	public function SetOfferId($value){
		$this->_OfferId = $value;
	}

    public function GetOfferLng(){
		return $this->_OfferLng;
	}

	public function SetOfferLng($value){
		$this->_OfferLng = $value;
	}

	public function GetKind(){
		return $this->_Kind;
	}

	public function SetKind($value){
		$this->_Kind = $value;
	}

	public function GetOrder(){
		return $this->_Order;
	}

	public function SetOrder($value){
		$this->_Order = $value;
	}

	public function GetArea(){
		return $this->_Area;
	}

	public function SetArea($value){
		$this->_Area = $value;
	}

	public function GetLevel(){
		return $this->_Level;
	}

	public function SetLevel($value){
		$this->_Level = $value;
	}

	public function GetType(){
		return $this->_Type;
	}

	public function SetType($value){
		$this->_Type = $value;
	}

	public function GetHeight(){
		return $this->_Height;
	}

	public function SetHeight($value){
		$this->_Height = $value;
	}

	public function GetKitchenType(){
		return $this->_KitchenType;
	}

	public function SetKitchenType($value){
		$this->_KitchenType = $value;
	}

	public function GetNumber(){
		return $this->_Number;
	}

	public function SetNumber($value){
		$this->_Number = $value;
	}

	public function GetGlaze(){
		return $this->_Glaze;
	}

	public function SetGlaze($value){
		$this->_Glaze = $value;
	}

	public function GetWindowView(){
		return $this->_WindowView;
	}

	public function SetWindowView($value){
		$this->_WindowView = $value;
	}

	public function GetDescription(){
		return $this->_Description;
	}

	public function SetDescription($value){
		$this->_Description = $value;
	}

	public function GetFloorsState(){
		return $this->_FloorsState;
	}

	public function SetFloorsState($value){
		$this->_FloorsState = $value;
	}

	public function GetRoomType(){
		return $this->_RoomType;
	}

	public function SetRoomType($value){
		$this->_RoomType = $value;
	}

	public function GetWalls(){
		return $this->_Walls;
	}

	public function SetWalls($value){
		$this->_Walls = $value;
	}

	public function GetFloors(){
		return $this->_Floors;
	}

	public function SetFloors($value){
		$this->_Floors = $value;
	}

	public function GetWindowsExhibition(){
		return $this->_WindowsExhibition;
	}

	public function SetWindowsExhibition($value){
		$this->_WindowsExhibition = $value;
	}

	public function GetEquipment(){
		return $this->_Equipment;
	}

	public function SetEquipment($value){
		$this->_Equipment = $value;
	}
	
	/**
	 * Return offer as object.
	 * @return Offer
	 */
	public function GetOfferObj(){
		if($this->_OfferObj == null){
			$this->_OfferObj = Offers::GetOffer($this->GetOfferId(), $this->GetOfferLng());
		}
		return $this->_OfferObj;
	}
	
	public function __construct($Id, $OfferId, $OfferLng, $Kind, $Order, $Area, $Level, $Type, $Height, $KitchenType, $Number, $Glaze, $WindowView, $Description, $FloorsState, $RoomType){
		$this->SetId($Id);
		$this->SetOfferId($OfferId);
		$this->SetOfferLng($OfferLng);
		$this->SetKind($Kind);
		$this->SetOrder($Order);
		$this->SetArea($Area);
		$this->SetLevel($Level);
		$this->SetType($Type);
		$this->SetHeight($Height);
		$this->SetKitchenType($KitchenType);
		$this->SetNumber($Number);
		$this->SetGlaze($Glaze);
		$this->SetWindowView($WindowView);
		$this->SetDescription($Description);
		$this->SetFloorsState($FloorsState);
		$this->SetRoomType($RoomType);
	}

}

?>