<?
class stddoc {
	var $min_rights;
	var $method_label;
	function stddoc () {
		$this->min_rights=0;
		$this->method_label ='�������� ���������';
	}
	function adddoc () {
		$this->min_rights=2;
		$this->method_label ='�������� ���������';
	}
	function editdoc () {
		$this->min_rights=2;
		$this->method_label ='������ ���������';
	}
	function deletedoc () {
		$this->min_rights=2;
		$this->method_label ='�������� ���������';
	}
}
?>