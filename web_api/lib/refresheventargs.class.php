<?php

/**
 * Class providing refresh arguments, like sorting, filters, page size.
 * @author Jakub Konieczka
 *
 */
class RefreshEventArgs{
	public $PageSize;
	public $ActualPage;
	public $Filters;
	public $Sorting;	
	
	public $PageCount;	
	public $RowsCount;	
	
	/**
	 * Create new instance of RefreshEventArgs class, with given parameters. 
	 * @param $pageSize
	 * @param $actualPage
	 * @param $filters
	 * @param $sort
	 */
	public function __construct($pageSize, $actualPage, $filters = null, $sort = ""){
		$this->PageSize = $pageSize;
		$this->ActualPage = $actualPage;
		$this->Filters = $filters;
		$this->Sorting = $sort;
	}

	/**
	 * Returns the offset for SQL queries to set on a good record to retrieve proper page with results.
	 * @return int
	 */
	public function GetOffset(){
		//echo "$this->PageNumber - $this->PageSize - $this->PageCount<br>";
		$of = $this->ActualPage * $this->PageSize;
		if($of > $this->RowsCount) {
			$of = 0;
			$this->ActualPage = 0;		
		}
		return $of; 
	}
	
	/**
	 * Zwraca numer pierwszej strony, do nawigacji miedzy stronami.
	 */
	public function GetFirst(){
		return 0;
	}
	
	/**
	 * Zwraca numer poprzedniej strony, do nawigacji miedzy stronami.
	 */
	public function GetPrev(){
		if($this->ActualPage - 1 < 0) return 0;
		else return $this->ActualPage - 1;		
	}
	
	/**
	 * Zwraca numer nastepnej strony, do nawigacji miedzy stronami.
	 */
	public function GetNext(){
		if($this->ActualPage + 1 >= $this->PageCount) return $this->PageCount - 1;
		else return $this->ActualPage + 1;		
	}
	
	/**
	 * Zwraca numer ostaniej strony, do nawigacji miedzy stronami.
	 */
	public function GetLast(){		
		return $this->PageCount - 1;
	}
	
	/**
	 * Zwraca licznik z aktualnie wyswietlanymi rekordami z wszystkich.
	 */
	public function getActPage(){
		$cnt = $this->ActualPage * $this->PageSize;
		$apc = $cnt + $this->PageSize;
		if($apc > $this->RowsCount) $apc = $this->RowsCount;
		if($cnt + 1 <= $this->RowsCount) $cnt++;
		return "$cnt - $apc z ".$this->RowsCount;
	}

	public function ActPageSizeStyle($pgs){
		if($this->PageSize == $pgs) 
			return 'style="color: red;"';
		else
			return "";
	}

	/**
	 * Zwraca tablice stron do pokazania na paginatorze. Np. 3,4,5,6,7
	 *
	 * @param int $range
	 * @return array of int
	 */
	public function GetPagesNumbers($range = 0){
		if($range == 0) $range = Config::$PaginatorRange;
		$list = array();
		$j = 0;
		$ile = ($range * 2) + 1;
		$start = $this->ActualPage - $range;
		if($start < 0) $start = 0;		
		if($this->ActualPage + $range > $this->PageCount - 1 && $start >= $range) {$start = $start + $this->PageCount - $this->ActualPage - 1 - $range;}
		if($start + $ile > $this->PageCount ) {$ile = $this->PageCount; $start = 0;}
		for($i = $start; $i < $start + $ile; $i++){					
			$list[$j] = $i;	
			$j++;
		}
		if(count($list) == 0)
		{
			$list[0] = 0;
		}
		return $list;
	}
	
	/**
	 * Zwraca ostatnia strone do wyswietlenia, o ile trzeba. 
	 *
	 * @param unknown_type $range
	 * @return unknown
	 */
	public function ShowLastPage($range = 0){
		if($range == 0) $range = Config::$PaginatorRange;
		$lst  = $this->GetPagesNumbers($range);	
		if($lst[count($lst) - 1] < $this->PageCount - 1) return $this->PageCount - 1;
		return 0;
	}

	public function SetRowsCount($count){
		$this->RowsCount = $count;
		$pc = 0;
		if(($count % $this->PageSize) == 0) $pc = $count / $this->PageSize;
		else $pc = floor($count / $this->PageSize) + 1;
		$this->PageCount = $pc;
	}
	
	public function SetLimit(&$query){
		if(Config::$Driver == Config::DRIVER_MYSQL){
			$query .= " LIMIT " . $this->GetOffset() . ", " . $this->PageSize;	
		}else if(Config::$Driver == Config::DRIVER_POSTGRESQL){
			$query .= " LIMIT " . $this->PageSize . " OFFSET " . $this->GetOffset();
		}
	}
	
}

?>