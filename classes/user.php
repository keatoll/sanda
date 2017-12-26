<?
//include_once stddoc.php;

class user /*extends stddoc */{
	function user(){
		$this->min_rights=0;	
		$this->method_label ='';
	}
	function login () {
		//null;	
		$this->method_label ='Авторизация пользователя';
	}
	function register () {
		//null;	
		$this->method_label ='Регистрация пользователя';
	}
	function edit_user () {
		$this->min_rights=1;
		$this->method_label ='Изменение профиля';
	}
	function myaccount () {
		$this->min_rights=1;
		$this->method_label ='Личный кабинет';
	}
	function current_order () {
		$this->min_rights=1;
		$this->method_label ='Просмотр и изменение текущего заказа';
	}
	function order_hist () {
		$this->min_rights=1;
		$this->method_label ='Архив заказов';
	}
}
?>