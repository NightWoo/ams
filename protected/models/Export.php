<?php
class Export
{
	private $fileName;
	private $content;
	
	public function __construct($fileName , $content) {
		$this->fileName = $fileName;
		$this->content = $content;
	}


	public function toCSV() {
		$bom = "\xEF\xBB\xBF";

		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: inline; filename=\"" . $this->fileName . ".csv\"");
		
		echo $bom.$this->content;
	}
}
