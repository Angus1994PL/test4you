<?php

/**
 * Class describing the investment building.
 * @author Jakub Konieczka
 *
 */
class InvestmentBuilding extends AObject{

    private $_IdLng;
	private $_Name;
	private $_Symbol;
	private $_Description;
	private $_InvestmentId;
	private $_Area;
	private $_DueDate;
	private $_FloorsNo;

	private $_InvestmentObj = null; 
	private $_Offers = array();

    public function GetIdLng(){
		return $this->_IdLng;
	}

	public function SetIdLng($value){
		$this->_IdLng = $value;
	}

	public function GetName(){
		return $this->_Name;
	}

	public function SetName($value){
		$this->_Name = $value;
	}

	public function GetSymbol(){
		return $this->_Symbol;
	}

	public function SetSymbol($value){
		$this->_Symbol = $value;
	}

	public function GetDescription(){
		return $this->_Description;
	}

	public function SetDescription($value){
		$this->_Description = $value;
	}

	public function GetInvestmentId(){
		return $this->_InvestmentId;
	}

	public function SetInvestmentId($value){
		$this->_InvestmentId = $value;
	}

	public function GetArea(){
		return $this->_Area;
	}

	public function SetArea($value){
		$this->_Area = $value;
	}

	public function GetDueDate(){
		return $this->_DueDate;
	}

	public function SetDueDate($value){
		$this->_DueDate = $value;
	}

	public function GetFloorsNo(){
		return $this->_FloorsNo;
	}

	public function SetFloorsNo($value){
		$this->_FloorsNo = $value;
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
	 * Return array of offers in building.
	 * @return Offer[]
	 */
	public function GetOffers(){
		if($this->_Offers == null){
			$this->_Offers = Offers::GetOffersInvestmentBuilding($this->GetId(), $this->GetIdLng());
		}
		return $this->_Offers;
	}
	
	public function __construct($idLng, $id, $name, $symbol, $description, $investmentId, $area, $dueDate, $floorsNo){
		$this->SetId($id);
        $this->SetIdLng($idLng == null ? 1045 : $idLng);
		$this->SetName($name);
		$this->SetSymbol($symbol);
		$this->SetDescription($description);
		$this->SetInvestmentId($investmentId);
		$this->SetArea($area);
		$this->SetDueDate($dueDate);
		$this->SetFloorsNo($floorsNo);
	}

}

?>