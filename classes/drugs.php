<?
include_once ('stddoc.php');

class drugs extends stddoc {
	function addtocart () {
		$this->min_rights = 1;	
		$this->method_label ='Добавление в корзину';
	}
	function search () {
		//null;
		$this->method_label ='Поиск товара';
	}
}
?>
