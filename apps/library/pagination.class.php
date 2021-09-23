<?php
/*
 * Created on Mar 29, 2015 - 9:00:19 AM
* @Author : Jacques Jocelyn
*          Modify or copy this file requires author's agreement.
* @purpose :
* - Calculate the number of pages and define the start
* - Generate the pagination for each page
*/
class Pagination{
	var $data;
	var $lang;
	var $totalRecord;
	function __construct($iniObj,$totalRecord=0){
		global $db,$sqlData,$iniObj;
		$this->db = $db;$this->sqlData=$sqlData;
		$this->lang = $_SESSION['LANG'];
		$this->ini = $iniObj;
		$this->textNav = false;
		$this->totalRecord = $totalRecord;
		$this->range        = 5;
		$this->calculatePages();
	}

	function getEndLimit($currentPage){
		$this->lastItem= ($this->firstItem + $this->ini->nbResultPerPage);
		if($this->lastItem > $this->totalRecord) $this->lastItem  = $this->totalRecord;
		return $this->lastItem;
	}

	function getStartLimit($currentPage){
		$this->firstItem = ($currentPage-1)*$this->ini->nbResultPerPage; // First item to retrieve
		if($this->firstItem >$this->totalRecord)  {
			$this->firstItem  = ($this->totalRecord - $this->ini->nbResultPerPage);
		}
		return $this->firstItem;
	}

	function calculatePages(){
		$this->totalPage=0;
		if($this->totalRecord > $this->ini->nbResultPerPage){
			$this->totalPage = ceil($this->totalRecord / $this->ini->nbResultPerPage);
		}
		else $this->totalPage = 1;
	}

	function paginate($url,$currentPage){
		if ($this->totalPage > 1 ) {
			if(((int)$currentPage - (int)$this->range ) <=0) {
				$startRange=1;
			}
			else  {
				$startRange = (int)$currentPage - (int)$this->range;
			}

			if(((int)$currentPage + (int)$this->range) > $this->totalPage) {
				$endRange = $this->totalPage;
			}
			else {
				$endRange = (int)$currentPage + (int)$this->range;
			}

			if((int)$currentPage > 1){

				echo "<li><a href='/$url/p_".($currentPage-1)."'> <i class='fa fa-angle-left'> Préc.</i></a></li>"; // Goto 1st page
			}

			for ($p=$startRange; $p<=$endRange; $p++) {
				if($currentPage==$p){
					echo "<li class='active'><a href='#'>$p</a></li>";
				}
				else {
					echo "<li><a href='/$url/p_$p'>".$p."</a></li>";
				}
			}
			if($currentPage < $this->totalPage){
				echo "<li><a href='/$url/p_".($currentPage+1)."'> Suiv. <i class='fa fa-angle-right'> </i></a></li>"; // Goto next page
			}
		}
	}

}
