<?
include_once ('stddoc.php');

class drugs extends stddoc {
	function addtocart () {
		$this->min_rights = 1;	
		$this->method_label ='���������� � �������';
	}
	function search () {
		//null;
		$this->method_label ='����� ������';
	}
}
?>
