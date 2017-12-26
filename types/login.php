<?php
//-------------------вход зарегистрированного пользователя----------------
/*$qry = mysql_query("SELECT md5(md5('456Tm0xSq')) mdpass FROM dual ",$conn);
$a = mysql_fetch_row($qry);
echo '"'.$a[0].'"';
*/
function check_login_params ($params) {
	global $conn;
if ((/*preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[login]) &&*/ 
		preg_match("/[^(\w)|(\@)|(\.)]/",$params[login])))  {
	return 'Ошибка! Вы ввели некорректный адрес email!';} 

if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[password])) {
	return 'Ошибка! Пароль может содержать только буквы, цифры, символ подчеркивания и точку.';} 

if(empty($params[login]) || (empty ($params[password])&& isset($_POST['btn_login']))) {
	return 'Ошибка! Поля не могут быть пустыми!';}
$sol = 'Tm0xSq';
$qry = mysql_query("SELECT md5(md5('".$params[password].$sol."')) mdpass, u.* FROM users u WHERE "./*name='".$params[login]."' or*/ "email = '".$params[login]."'",$conn);
if (mysql_error()) {
	return 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!';}

if (mysql_num_rows($qry)!= 1) {
	return 'Пользователь с таким именем не зарегистрирован в системе! Проверьте введенные данные или обратитесь в тех.поддержку сайта.';}

$userdata = mysql_fetch_assoc($qry); 
if ($userdata['is_active']!=1) {
	return 'Ваша учетная запись заблокирована или не утверждена! Если Вы не знаете, почему это произошло, обратитесь в тех.поддержку сайта.';}
	
if ($userdata[mdpass]!= $userdata[password] && ($userdata[mdpass]!= $userdata[tmppass] && $userdata[tmppass]!= '' )) {
	return 'Введен неверный пароль!';}

IF ($userdata[mdpass]== $userdata[tmppass]&& $userdata[tmppass]!=$userdata[password]) {
	$qry = mysql_query("update users set password = md5(md5('".$newpass."')) where id ='".$userdata[id]."'",$conn);}
if (mysql_error()){
	return 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!';}
session_start();
$_SESSION['user_id'] = $userdata['id'];
$_SESSION['last_name'] =$userdata['last_name']; 
$_SESSION['first_name'] =$userdata['first_name']; 
$_SESSION['second_name'] =$userdata['second_name']; 
$_SESSION['user_rights'] =$userdata['is_admin']; 
$_SESSION['user_email'] =$userdata['email']; 
$_SESSION['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR'];
$_SESSION['HTTP_USER_AGENT']=$_SERVER['HTTP_USER_AGENT'];
    					
$parseurl = parse_url($params[from_url]);
if ( !isset($params['from_url']) || 
  	//  strstr($parseurl['path'],$_SERVER[HTTP_HOST].'/login')
   	 preg_match('/(\/)?login(\/)?/i',$parseurl['path'])){
	header("Location: http://".$_SERVER['HTTP_HOST']);
  	exit;
} else {
	header("Location: http://".$_SERVER['HTTP_HOST'].$params[from_url]);
	exit;
}
return '';
}////////////////////////////////////////////////

function  check_remember_params($params) {
	global $conn;
if (preg_match("/[^(\w)|(\@)|(\.)]/",$params[login]) ) {
	return 'Ошибка! Необходимо ввести верный адрес электроннной почты!';}
	
$qry = mysql_query("SELECT * FROM users u WHERE "/*name = '".$params[login]."' or*/ ."email='".$params[login]."'",$conn);
if (mysql_error()) {
	return 'Ошибка получения данных! Обратитесь в тех.поддержку сайта!';} 
if (mysql_num_rows($qry)!= 1) {
	return 'Пользователь с таким именем не зарегистрирован в системе! Проверьте введенные данные или обратитесь в тех.поддержку сайта.';}

$userdata = mysql_fetch_assoc($qry);
// генерация нового пароля и запись его в базу
$sol = 'Tm0xSq';
$newpass = generate_password();
$qry = mysql_query("update users u set u.tmppass = md5(md5('".$newpass.$sol."')) where id =".$userdata[id],$conn);
if (mysql_error()){
	return 'Ошибка получения данных! Обратитесь в тех.поддержку сайта!';}
  			//
$to = $userdata[email];
$subject = 'Восстановление пароля на сайте '.$_SERVER['HTTP_HOST'];
$headers = "From: Восстановитель паролей <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
$message = "Здравствуйте, ".$userdata[first_name]." ".$userdata[second_name]."! \r\n\r\n".
			"Вам пришло это сообщение, потому что вы подали запрос на генерацию нового пароля на сайте http://".$_SERVER['HTTP_HOST']."\r\n".
  			"Для авторизации введите данные: \r\n".
  			"Email: ".$userdata[email]."\r\n".
  			"Пароль: ".$newpass." \r\n\r\n".
  			"Или пройдите по ссылке: http://".$_SERVER['HTTP_HOST']."/login/remember/".rawurlencode($userdata[email])."/".rawurlencode($newpass)."\r\n".
  			"Если же вы не запрашивали новый пароль или письмо пришло к вам случайно, просто удалите это сообщение. \r\n\r\n".
  			"С уважением, администрация сайта.";
mail($to, $subject, $message, $headers); // отправка почты
return  'На указанный адрес отправлено сообщение с новым паролем.';
}///////////////////////////////////////////////

unset($pararray);
if (isset($_POST['btn_login']) || (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('remember'))) {// войти
	if (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('remember')) { // если пароль - "вспомненный"
		$name=mysql_real_escape_string(trim(htmlspecialchars(substr($_DOC_PARAMS[get_params][1],0,30))));
		$pass=mysql_real_escape_string(trim(htmlspecialchars(substr($_DOC_PARAMS[get_params][2],0,20))));
	} else { // если пароль - "введенный"
		$name=mysql_real_escape_string(trim(substr($_POST['login'],0,30)));
		$pass=mysql_real_escape_string(trim(substr($_POST['password'],0,20)));
	}
	$pararray['params']['login']=$name;
	$pararray['params']['password']=$pass;
	$pararray['params']['from_url'] = rawurldecode($_POST['from_url']);
	$errormsg = check_login_params($pararray['params']);
	$pararray['errormsg'] = $errormsg;
	
///-------------------вспомнить пароль ------------------
}elseif (isset($_POST['btn_remember'])) {
	$name=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['login'],0,30))));
	$pararray['params']['login']=$name;
	$errormsg = check_remember_params($pararray['params']);
	$pararray['errormsg'] = $errormsg;
	
/// ------------------выход из системы -------------------
} elseif (isset($_POST['btn_logout']) || strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('logout')) {
	session_start();
	session_destroy();
	header("Location: http://".$_SERVER['HTTP_HOST']);
	exit;
}
$pararray['errormsg']=$errormsg;
?>