<?
//include_once stddoc.php;

class admin /*extends stddoc */{
	var $min_rights;
	var $method_label;
	function manage_types () {
		$this->min_rights = 3;	
		$this->method_label ='Управление типами документов';
	}
	function manage_orders () {
		$this->min_rights = 2;
		$this->method_label ='Управление заказами клиентов';
	}
}
?>