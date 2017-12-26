<?
function check_edit_params($params){
	global $conn;
	//session_start();
if (empty ($params[last_name]) || empty ($params[first_name]) ||empty ($params[second_name]) ||empty ($params[email]) ){
	return '������! ����, ���������� ���������� (*) ������ ���� ���������!';}
if ($params['birth_date'][1]&&!checkdate($params['birth_date'][1], $params['birth_date'][0], $params['birth_date'][2])){
	return '������! ���� �������� �� �������!';}
if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[name])) {
	return '������! ����� ������ ��������� ������ �����, �����, ����� ��� ������ �������������!';}
if ( preg_match("/[^(\w)|(\s)| ]/",$params[last_name])) {
	return '������! ������� ������ ��������� ������ �����!';}
if ( preg_match("/[^(\w)|(\s)| ]/",$params[first_name])) {
	return '������! ��� ������ ��������� ������ �����!';}
if ( preg_match("/[^(\w)|(\s)| ]/",$params[second_name])) {
	return '������! �������� ������ ��������� ������ �����!';}
if ( !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.
'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i',$params[email])){
	return '������! ����� ����������� ����� �������� ������������ ������� ��� ����� �������� ������!';}
if ( !preg_match("/^(\+?\d+)?\s*(\(\d+\))?[\s-]*([\d-]*)$/",$params[phone]) && !empty ($params[phone])) {
	return '������! �� ������� ���������� ����� ��������!';}
if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[password])) {
	return '������! ������ ������ ��������� ������ �����, �����, ����� ��� ������ �������������!';}

if ($params[user_type] ==2) { //���� ��, ������ ���. ���������
	if (empty ($params[org_name]) || empty ($params[bank_name])||empty ($params[inn]) || 
		empty ($params[kpp])||empty ($params[user_acct]) || empty ($params[bank_acct])||
		empty ($params[bik])){
		return '������! ����, ���������� ���������� (*) ������ ���� ���������!';}
	if ( preg_match("/[(\D)*]/",$params[inn])) {
		return '������! ��� ����������� ������� ������ �� ����!';}
	if ( preg_match("/[(\D)*]/",$params[kpp])) {
		return '������! ��� ����������� ������� ������ �� ����!';}
	if ( preg_match("/[(\D)*]/",$params[user_acct])) {
		return '������! ���� ����������� ������� ������ �� ����!';}
	if ( preg_match("/[(\D)*]/",$params[bank_acct])) {
		return '������! ���� ����� ������� ������ �� ����!';}
	if ( preg_match("/[(\D)*]/",$params[bik])) {
		return '������! ��� ����� ������� ������ �� ����!';}
	if (strlen($params[user_acct])!=20 || strlen($params[bank_acct])!=20 ) {
		return '������! ����� ����� ������ �������!';
	}
} 
if ($params[password]!==$params[confirm_p]) {
	return '������! ������ �� ��������� � �������������� ������. ��������� ��������� ������';}

if (strlen($params[password])<6 && !empty($params[password])) {
	return '������ ������ ���� �� ������ ����� ��������';}

$qry = mysql_query("select * from users where email = '".$params[email]."' and id != ".$_SESSION['user_id'],$conn);
if (mysql_error()) {
	return '������ ��������� ������! ���������� � ���.��������� �����!'; }
if (mysql_num_rows($qry) >0 ) {
	return '������! ������������ � ����� ������� ����������� ����� ��� ����������!';}

$qry = mysql_query("select * from `users` where id = ".$_SESSION['user_id'],$conn);
if (mysql_error()) {
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!'; } 
if (mysql_num_rows($qry) != 1 ) {
	return '������! ������ �� ������� � ����!';}
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
if (!$sEdit) {//������ �� �������� 
	return ''; }
$sEdit = substr($sEdit, 0, strlen($sEdit)-1);

// ������ ������ � ��
$qry = mysql_query("update `users` set ".$sEdit.' where id = '.$_SESSION['user_id'],$conn);
if (mysql_error()) {
	return '��������� ������ ���������� ������! ���������� � ���.��������� �����!'.mysql_error(); } 

if ($bMailChanged == 1) { // ���� ���������� ���� 
	//�������� ��������������� ���� �� ����� ����
$to = $params[email];
$subject = '������������� ������ ����������� ����� �� ����� '.$_SERVER['HTTP_HOST'];
$headers = "From: ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
$message = "������������, ".$params[name]."! \r\n\r\n".
			"�� ������� ���� ����� � �������� ������ ������ ������ ����������� ����� �� ����� ".$_SERVER['HTTP_HOST']. "\r\n".
  			"��� ������������� ����� ������ �������� �� ������: http://".$_SERVER['HTTP_HOST']."/edit_user/confirm/".$_SESSION['user_id']."/".rawurlencode($params[email])."\r\n".
  			"���� �� ������ ������ � ��� ��������, ������ ������� ��� ���������. \r\n\r\n".
  			"� ���������, ������������� ����� ".$_SERVER['HTTP_HOST'];
mail($to, $subject, $message, $headers); // �������� �����
return '1';
}
session_start();
$_SESSION['last_name']=$params[last_name];

return '';
}////////////////////////////////////////////////////////////////

function check_confirm_params ($params) {
	global $conn;
if( empty($params[email]) || empty($params[user_id])) {
	return '������ � ������ �������������! ���������, ��� �����, �������� ����, ��������� � ������� � ������ ��� ���������� � ���.��������� �����.';}

$qry = mysql_query("SELECT u.* FROM users u WHERE id='".$params[user_id]."'",$conn);
if (mysql_error()) {
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!';}

if (mysql_num_rows($qry)!= 1) {
	return '������������ � ����� ������ �� ��������������� � �������! ��������� ��������� ������ ��� ���������� � ���.��������� �����.';}

$userdata = mysql_fetch_assoc($qry); 
IF (!$userdata['tmpmail']) {
	return '� ��� ��� �������� �������� �� ��������� ������ ����������� �����.';}
	
if (strtolower($userdata['tmpmail']) != strtolower($params[email])) {
	return '������ � ������ �������������! ���������, ��� �����, �������� ����, ��������� � ������� � ������ ��� ���������� � ���.��������� �����.';}

$qry = mysql_query("update users set tmpmail = '' , email = '".$userdata['tmpmail']."' where id ='".$params[user_id]."'",$conn);
if (mysql_error()){
	return '��������� ������ �� ����� ��������� ������! ���������� � ���.��������� �����!';}
session_start();
$_SESSION['user_email']=$params[email];

return '';
}////////////////////////////////////////////////

session_start();
unset($pararray);
//---------------------------�������� ������-----------------------------------------
if (isset($_POST['edit'])) {// ����������
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
	//---------------------------�����������--------------------------------------------
}elseif (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('confirm')) {
	if (strtoupper($_DOC_PARAMS[get_params][1])===strtoupper('cancel')) { 
		//�������� ������������� ����
		if (!$_SESSION['user_id']) {
			$errormsg = '������ ����������� ������������! ������������� �������� � ��������� ��������.';
		} else {
			$qry = mysql_query("select * from `users` where id = ".$_SESSION['user_id'],$conn);
			if (mysql_error()) {
				$errormsg= '��������� ������ ��������� ������! ���������� � ���.��������� �����!'; 
			}elseif (mysql_num_rows($qry) != 1 ) {
				$errormsg= '������! ������ �� ������� � ����!';
			} else {
				$pararray['params'] = mysql_fetch_assoc($qry);
				$qry = mysql_query("update users set tmpmail = '' where id = ".$_SESSION['user_id'], $conn);
				if (mysql_error()) {
					$errormsg= '��������� ������ ��������� ������! ���������� � ���.��������� �����!'; 
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
	} else { // ����������� ����
		$pararray['params'][user_id] = (integer) $_DOC_PARAMS[get_params][1];
		$pararray['params'][email]=mysql_real_escape_string(trim(substr(rawurldecode($_DOC_PARAMS[get_params][2]),0,30)));
		$errormsg = check_confirm_params($pararray['params']);
		if (!$errormsg ) {$errormsg ='��� ����� ����������� ����� ������� �������';}
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
}// else { //����� ������ ��� ���������
if (!isset($pararray['params']['last_name'])) {	
$qry = mysql_query("select * from `users` where id = ".$_SESSION['user_id'],$conn);
	if (mysql_error()) {
		$errormsg= '��������� ������ ��������� ������! ���������� � ���.��������� �����!'; 
	}elseif (mysql_num_rows($qry) != 1 ) {
		$errormsg= '������! ������ �� ������� � ����!';
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