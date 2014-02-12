<?php
class PostList {
	public $tagFilter;
	public $resultsPerPage;
	public $currentPage;
	public $maximumPosts;
	public $maximumPages;
	public $order;
	public $posts = array();
	public $pagination;
	
	public function __construct($resultsPerPage, $currentPage, $order = "asc", $tagfilter = false) {
		if($tagfilter !== false) {
			$tagfilter = strtolower($tagfilter);
		}

		$this->resultsPerPage = $resultsPerPage;
		$this->currentPage = $currentPage;
		$this->order = $order;
		$this->tagFilter = $tagfilter;

		if($this->resultsPerPage<=0) {
			$this->resultsPerPage = 1;
		}
		
		if($this->currentPage !== "last") {
			if($this->currentPage === null || $this->currentPage <= 0) {
				$this->currentPage = 1;
			}
		}
		
		$this->maximumPosts = Database::getNumberOfPosts(false, $tagfilter);
		$this->maximumPages = floor($this->maximumPosts / $this->resultsPerPage);
		
		if($this->maximumPosts % $this->resultsPerPage !=0) {
			$this->maximumPages += 1;
		}
		
		if($this->currentPage > $this->maximumPages || $this->currentPage == "last") {
			$this->currentPage = $this->maximumPages;
		}
		
		if($this->order !== strtolower("desc")) {
			$this->order = "asc";
		}
		else {
			$this->order = "desc";
		}
		$this->loadPosts();
		$this->loadPagination();
	}
	
	public function loadPosts() {
		$offset = ($this->currentPage-1) * $this->resultsPerPage;
		for($i=1;$i<$this->resultsPerPage+1;$i++) {
			if($i+ $offset <= $this->maximumPosts) {
				$x = array();
				$x = Database::readPostByIndex(false, $i + $offset, $this->order, $this->tagFilter);
				array_push($this->posts, $x);
				unset($x);
			}
			else {
				break;
			}
		}
		if($this->posts === false) {
			return false;
		}
		return true;
	}	
	
	public function loadPagination() {
		$this->pagination = getPaginateArray($this->currentPage, $this->resultsPerPage, $this->maximumPosts);
	}
	
}