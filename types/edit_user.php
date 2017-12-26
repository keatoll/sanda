<?
function check_edit_params($params){
	global $conn;
	//session_start();
if (empty ($params[last_name]) || empty ($params[first_name]) ||empty ($params[second_name]) ||empty ($params[email]) ){
	return 'Ошибка! Поля, отмеченные звездочкой (*) должны быть заполнены!';}
if ($params['birth_date'][1]&&!checkdate($params['birth_date'][1], $params['birth_date'][0], $params['birth_date'][2])){
	return 'Ошибка! Дата рождения не указана!';}
if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[name])) {
	return 'Ошибка! Логин должен содержать только буквы, цифры, точку или символ подчеркивания!';}
if ( preg_match("/[^(\w)|(\s)| ]/",$params[last_name])) {
	return 'Ошибка! Фамилия должна содержать только буквы!';}
if ( preg_match("/[^(\w)|(\s)| ]/",$params[first_name])) {
	return 'Ошибка! Имя должно содержать только буквы!';}
if ( preg_match("/[^(\w)|(\s)| ]/",$params[second_name])) {
	return 'Ошибка! Отчество должно содержать только буквы!';}
if ( !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.
'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i',$params[email])){
	return 'Ошибка! Адрес электронной почты содержит недопустимые символы или имеет неверный формат!';}
if ( !preg_match("/^(\+?\d+)?\s*(\(\d+\))?[\s-]*([\d-]*)$/",$params[phone]) && !empty ($params[phone])) {
	return 'Ошибка! Не удалось распознать номер телефона!';}
if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[password])) {
	return 'Ошибка! Пароль должен содержать только буквы, цифры, точку или символ подчеркивания!';}

if ($params[user_type] ==2) { //если ЮЛ, чекаем доп. параметры
	if (empty ($params[org_name]) || empty ($params[bank_name])||empty ($params[inn]) || 
		empty ($params[kpp])||empty ($params[user_acct]) || empty ($params[bank_acct])||
		empty ($params[bik])){
		return 'Ошибка! Поля, отмеченные звездочкой (*) должны быть заполнены!';}
	if ( preg_match("/[(\D)*]/",$params[inn])) {
		return 'Ошибка! ИНН организации состоит только из цифр!';}
	if ( preg_match("/[(\D)*]/",$params[kpp])) {
		return 'Ошибка! КПП организации состоит только из цифр!';}
	if ( preg_match("/[(\D)*]/",$params[user_acct])) {
		return 'Ошибка! счет организации состоит только из цифр!';}
	if ( preg_match("/[(\D)*]/",$params[bank_acct])) {
		return 'Ошибка! счет банка состоит только из цифр!';}
	if ( preg_match("/[(\D)*]/",$params[bik])) {
		return 'Ошибка! БИК банка состоит только из цифр!';}
	if (strlen($params[user_acct])!=20 || strlen($params[bank_acct])!=20 ) {
		return 'Ошибка! Номер счета введен неверно!';
	}
} 
if ($params[password]!==$params[confirm_p]) {
	return 'Ошибка! Пароль не совпадает с подтверждением пароля. Проверьте введенные данные';}

if (strlen($params[password])<6 && !empty($params[password])) {
	return 'Пароль должен быть не короче шести символов';}

$qry = mysql_query("select * from users where email = '".$params[email]."' and id != ".$_SESSION['user_id'],$conn);
if (mysql_error()) {
	return 'Ошибка получения данных! Обратитесь в тех.поддержку сайта!'; }
if (mysql_num_rows($qry) >0 ) {
	return 'Ошибка! Пользователь с таким адресом электронной почты уже существует!';}

$qry = mysql_query("select * from `users` where id = ".$_SESSION['user_id'],$conn);
if (mysql_error()) {
	return 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!'; } 
if (mysql_num_rows($qry) != 1 ) {
	return 'Ошибка! Данные не найдены в базе!';}
$cur_data = mysql_fetch_assoc($qry);
$sEdit = '';
$sol = 'Tm0xSq';
$bMailChanged = 0;
if ($params['birth_date'][1].'-'.$params['birth_date'][0].'-'.$params['birth_date'][2] !=$cur_data['birth_date'] ) {
	$sEdit = "birth_date='".$params['birth_date'][2].'-'.$params['birth_date'][1].'-'.$params['birth_date'][0]."',";
}
foreach ($cur_data as $key => $value ) {
	if ($params[$key] != $value) {
		if ($key=='is_active'||$key=='is_admin'||$key=='id'||$key=='birth_date') {
			continue;
		}elseif ($key=='password' || $key=='tmppass'){
			if(!empty($params[password])) {
				$sEdit.= "password=md5(md5('".$params[password].$sol."')),tmppass=md5(md5('".$params[password].$sol."')),";}
		}elseif ($key=='email') {
			$bMailChanged = 1;
			$sEdit.= "tmpmail='".$params[email]."',";
		}else {
			$sEdit.= $key."='".$params[$key]."',";
		}
	}
}
//die ($sEdit);	/////!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if (!$sEdit) {//данные не менялись 
	return ''; }
$sEdit = substr($sEdit, 0, strlen($sEdit)-1);

// запись данных в БД
$qry = mysql_query("update `users` set ".$sEdit.' where id = '.$_SESSION['user_id'],$conn);
if (mysql_error()) {
	return 'Произошла ошибка обновления данных! Обратитесь в тех.поддержку сайта!'.mysql_error(); } 

if ($bMailChanged == 1) { // если изменилось мыло 
	//отправка подтверждающего кода на новое мыло
$to = $params[email];
$subject = 'Подтверждение адреса электронной почты на сайте '.$_SERVER['HTTP_HOST'];
$headers = "From: ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
$message = "Здравствуйте, ".$params[name]."! \r\n\r\n".
			"Вы указали этот адрес в качестве своего нового адреса электронной почты на сайте ".$_SERVER['HTTP_HOST']. "\r\n".
  			"Для подтверждения этого адреса пройдите по ссылке: http://".$_SERVER['HTTP_HOST']."/edit_user/confirm/".$_SESSION['user_id']."/".rawurlencode($params[email])."\r\n".
  			"Если же письмо пришло к вам случайно, просто удалите это сообщение. \r\n\r\n".
  			"С уважением, администрация сайта ".$_SERVER['HTTP_HOST'];
mail($to, $subject, $message, $headers); // отправка почты
return '1';
}
session_start();
$_SESSION['last_name']=$params[last_name];

return '';
}////////////////////////////////////////////////////////////////

function check_confirm_params ($params) {
	global $conn;
if( empty($params[email]) || empty($params[user_id])) {
	return 'Ошибка в строке подтверждения! Проверьте, что адрес, вводимый вами, совпадает с адресом в письме или обратитесь в тех.поддержку сайта.';}

$qry = mysql_query("SELECT u.* FROM users u WHERE id='".$params[user_id]."'",$conn);
if (mysql_error()) {
	return 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!';}

if (mysql_num_rows($qry)!= 1) {
	return 'Пользователь с таким именем не зарегистрирован в системе! Проверьте введенные данные или обратитесь в тех.поддержку сайта.';}

$userdata = mysql_fetch_assoc($qry); 
IF (!$userdata['tmpmail']) {
	return 'У вас нет активных запросов на изменение адреса электронной почты.';}
	
if (strtolower($userdata['tmpmail']) != strtolower($params[email])) {
	return 'Ошибка в строке подтверждения! Проверьте, что адрес, вводимый вами, совпадает с адресом в письме или обратитесь в тех.поддержку сайта.';}

$qry = mysql_query("update users set tmpmail = '' , email = '".$userdata['tmpmail']."' where id ='".$params[user_id]."'",$conn);
if (mysql_error()){
	return 'Произошла ошибка во время изменения данных! Обратитесь в тех.поддержку сайта!';}
session_start();
$_SESSION['user_email']=$params[email];

return '';
}////////////////////////////////////////////////

session_start();
unset($pararray);
//---------------------------изменить данные-----------------------------------------
if (isset($_POST['edit'])) {// зарегаться
	//$pararray['params']['name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['login'],0,30))));
	$pararray['params']['last_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['last_name'],0,15))));
	$pararray['params']['first_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['first_name'],0,15))));
	$pararray['params']['second_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['second_name'],0,15))));
	$pararray['params']['email']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['email'],0,20))));
	$pararray['params']['phone']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['phone'],0,20))));
	$pararray['params']['password']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['password'],0,20))));
	$pararray['params']['confirm_p']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['confirm_p'],0,20))));
	$pararray['params']['birth_date'][0] = (substr($_POST['birth_date'][0],0,2)) ;
	$pararray['params']['birth_date'][1] = (substr($_POST['birth_date'][1],0,2)) ;
	$pararray['params']['birth_date'][2] = (substr($_POST['birth_date'][2],0,4)) ;
	$pararray['params']['org_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['org_name'],0,128))));
	$pararray['params']['inn']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['inn'],0,15))));
	$pararray['params']['kpp']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['kpp'],0,15))));
	$pararray['params']['user_acct']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['user_acct'],0,20))));
	$pararray['params']['bank_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['bank_name'],0,128))));
	$pararray['params']['bik']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['bik'],0,15))));
	$pararray['params']['user_type']=(int)$_POST['user_type'];
	$pararray['params']['bank_acct']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['bank_acct'],0,20))));
	$errormsg = check_edit_params($pararray['params']);
	$pararray['params']['email']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['email'],0,20))));
	if ($errormsg=='1') {
		$pararray['check_email'] = 1;
		$errormsg = '';  
	}
	$pararray['errormsg'] = $errormsg;
	//---------------------------подтвердить--------------------------------------------
}elseif (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('confirm')) {
	if (strtoupper($_DOC_PARAMS[get_params][1])===strtoupper('cancel')) { 
		//отменить подтверждение мыла
		if (!$_SESSION['user_id']) {
			$errormsg = 'Ошибка авторизации пользователя! Авторизуйтесь повторно и повторите действие.';
		} else {
			$qry = mysql_query("select * from `users` where id = ".$_SESSION['user_id'],$conn);
			if (mysql_error()) {
				$errormsg= 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!'; 
			}elseif (mysql_num_rows($qry) != 1 ) {
				$errormsg= 'Ошибка! Данные не найдены в базе!';
			} else {
				$pararray['params'] = mysql_fetch_assoc($qry);
				$qry = mysql_query("update users set tmpmail = '' where id = ".$_SESSION['user_id'], $conn);
				if (mysql_error()) {
					$errormsg= 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!'; 
				} else {
					$pararray['params']['tmpmail'] = '';
					$pararray['params']['password']='';
					$pararray['params']['tmppass']='';
					$date = $pararray['params']['birth_date'];
					unset($pararray['params']['birth_date']);
					$pararray['params']['birth_date'][0] = substr($date,8,2) ;
					$pararray['params']['birth_date'][1] = substr($date,5,2) ;
					$pararray['params']['birth_date'][2] = substr($date,0,4) ;
				}
			}
		}
	} else { // подтвердить мыло
		$pararray['params'][user_id] = (integer) $_DOC_PARAMS[get_params][1];
		$pararray['params'][email]=mysql_real_escape_string(trim(substr(rawurldecode($_DOC_PARAMS[get_params][2]),0,30)));
		$errormsg = check_confirm_params($pararray['params']);
		if (!$errormsg ) {$errormsg ='Ваш адрес электронной почты успешно изменен';}
		$pararray['errormsg'] = $errormsg;
		$pararray['check_email'] = 1;
		$pararray['params']['password']='';
		$pararray['params']['tmppass']='';
		$date = $pararray['params']['birth_date'];
		unset($pararray['params']['birth_date']);
		$pararray['params']['birth_date'][0] = substr($date,8,2) ;
		$pararray['params']['birth_date'][1] = substr($date,5,2) ;
		$pararray['params']['birth_date'][2] = substr($date,0,4) ;
	}
}// else { //вывод данных для изменения
if (!isset($pararray['params']['last_name'])) {	
$qry = mysql_query("select * from `users` where id = ".$_SESSION['user_id'],$conn);
	if (mysql_error()) {
		$errormsg= 'Произошла ошибка получения данных! Обратитесь в тех.поддержку сайта!'; 
	}elseif (mysql_num_rows($qry) != 1 ) {
		$errormsg= 'Ошибка! Данные не найдены в базе!';
	} else {
		$pararray['params'] = mysql_fetch_assoc($qry);
		$pararray['params']['password']='';
		$pararray['params']['tmppass']='';
		$date = $pararray['params']['birth_date'];
		unset($pararray['params']['birth_date']);
		$pararray['params']['birth_date'][0] = substr($date,8,2) ;
		$pararray['params']['birth_date'][1] = substr($date,5,2) ;
		$pararray['params']['birth_date'][2] = substr($date,0,4) ;
	}
	$pararray['errormsg'] = $errormsg;
}
?>