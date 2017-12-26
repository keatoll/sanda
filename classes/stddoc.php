<?
class stddoc {
	var $min_rights;
	var $method_label;
	function stddoc () {
		$this->min_rights=0;
		$this->method_label ='Просмотр документа';
	}
	function adddoc () {
		$this->min_rights=2;
		$this->method_label ='Создание документа';
	}
	function editdoc () {
		$this->min_rights=2;
		$this->method_label ='Правка документа';
	}
	function deletedoc () {
		$this->min_rights=2;
		$this->method_label ='Удаление документа';
	}
}
?>