<?
//include_once stddoc.php;

class user /*extends stddoc */{
	function user(){
		$this->min_rights=0;	
		$this->method_label ='';
	}
	function login () {
		//null;	
		$this->method_label ='����������� ������������';
	}
	function register () {
		//null;	
		$this->method_label ='����������� ������������';
	}
	function edit_user () {
		$this->min_rights=1;
		$this->method_label ='��������� �������';
	}
	function myaccount () {
		$this->min_rights=1;
		$this->method_label ='������ �������';
	}
	function current_order () {
		$this->min_rights=1;
		$this->method_label ='�������� � ��������� �������� ������';
	}
	function order_hist () {
		$this->min_rights=1;
		$this->method_label ='����� �������';
	}
}
?>