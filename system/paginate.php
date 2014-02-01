<?php
class paginateButton {

	public $active = false;
	public $pagenumber = 0;
	public $disabled = false;
	public $direction = 0;
	
	public function __construct($currentPage,$pag, $dis = false, $dir = 0) {
	
		if($pag!=="-") {
			if($currentPage==$pag) {
				$this->active = true;
			}
		}
		
		$this->pagenumber = $pag;
		
		if($dis) {
			$this->disabled = true;
		}	
		
		if($dir==="left") {
			$this->direction = "left";
		}
		
		if($dir==="right") {
			$this->direction = "right";
		}
	}
}

function getPaginateArray($currentPage, $objectsPerPage, $maxObjects) {

	$output = array();

	if($currentPage!="last") {
		if($currentPage==NULL || $currentPage<=0) {
			$currentPage=1;
		}
	}
	$maxPages = floor($maxObjects/$objectsPerPage);
	
	
	
	if($maxObjects%$objectsPerPage!=0) {
		$maxPages+= 1;
	}
	
	if($currentPage>$maxPages) {
		$currentPage=$maxPages;
	}
	
	if($currentPage=="last") {
		$currentPage = $maxPages;
	}
	
	$left_dis=false;
	
	if($currentPage<=1) {
		$left_dis=true;
	}
	
	array_push($output, new paginateButton($currentPage,"&laquo;",$left_dis,"left"));
	
	if($maxPages>=8) {
		
		if($currentPage>=4) {
		
			array_push($output,new paginateButton($currentPage,1));
			
			if($currentPage>=5) {
				array_push($output, new paginateButton($currentPage,"-",true));
			}
		}
		
		$sub=0;
		
		if($currentPage-2>=1) {
			$sub=2;
		}
		
		elseif($currentPage-1>=1) {
			$sub=1;
		}
		
		elseif($currentPage==1)
		{
			$sub=0;
		}
		
		$max=0;
		
		if($currentPage+2==$maxPages) {
			$max=1;
		}
		
		elseif($currentPage+1==$maxPages) {
			$max=2;
		}
		
		elseif($currentPage==$maxPages) {
			$max=3;
		}
		
		for($i=$currentPage-$sub-$max;$i<=$currentPage-$sub-$max+4;$i++) {
			array_push($output,new paginateButton($currentPage,$i));
		}
		
		if($currentPage<=$maxPages) {
			if($currentPage<=$maxPages-4) {
				array_push($output, new paginateButton($currentPage,"-",true));
			}
			array_push($output,new paginateButton($currentPage,$maxPages));
		}
	}
	else {
		for($i=1;$i<=$maxPages;$i++) {
			array_push($output, new paginateButton($currentPage,$i));
		}
	}
	
	$right_dis=false;
	
	if($currentPage>=$maxPages) {
		$right_dis=true;
	}
	
	array_push($output, new paginateButton($currentPage,"&raquo;",$right_dis,"right"));
	
	return $output;
}