<?
function check_edit_params($params){
	global $conn;

if (empty ($params['last_name']) || empty ($params['first_name']) || empty ($params['second_name']) || empty ($params[password]) || empty ($params[confirm_p])|| empty ($params[email]) ){
	return '������! ����, ���������� ���������� (*) ������ ���� ���������!';}
if (!checkdate($params['birth_date'][1], $params['birth_date'][0], $params['birth_date'][2])){
	return '������! ���� �������� �� �������!';}
/*if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[login])) {
	return '������! ����� ������ ��������� ������ �����, �����, ����� ��� ������ �������������!';}
*/if ( preg_match("/[^(\w)|(\s)| ]/",$params[last_name])) {
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

if (strlen($params[password])<6) {
	return '������ ������ ���� �� ������ ����� ��������';}
	
/*$qry = mysql_query("select * from users where name = '$params[login]'",$conn);
if (mysql_error()) {
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!'; }
if (mysql_num_rows($qry) >0 ) {
	return '������! ������������ � ����� ������� ��� ����������!';}
*/ 
$qry = mysql_query("select * from users where email = '".$params[email]."'",$conn);
if (mysql_error()) {
	return '������ ��������� ������! ���������� � ���.��������� �����!'; }
if (mysql_num_rows($qry) >0 ) {
	return '������! ������������ � ����� ������� ����������� ����� ��� ����������!';}

// ������ ������ � ��
$sol = 'Tm0xSq';
if ($params[user_type] ==2) { //���� ��, ������ ���. ���������
$qry = mysql_query("INSERT into `users` (`password`,`last_name`,`first_name`,`second_name`,".
							"`email`,`phone`,`tmppass`,`birth_date`,'org_name','bank_name','inn',".
							"'kpp','user_acct','bank_acct','bik','user_type') ".
					" values (md5(md5('".$params[password].$sol."')),'".
					$params[last_name]."','".$params[first_name]."','".$params[second_name].
					"','".$params[email]."','".$params[phone]."',md5(md5('".$params[password].$sol."')), '".
					$params['birth_date'][2]."-".$params['birth_date'][1]."-".$params['birth_date'][0].
					"','".$params[org_name]."','".$params[bank_name]."','".$params[inn]."','".$params[kpp].
					"','".$params[user_acct]."','".$params[bank_acct]."','".$params[bik]."','".$params[user_type].
					"')",$conn);
} else {
$qry = mysql_query("INSERT into `users` (`password`,`last_name`,`first_name`,`second_name`,".
							"`email`,`phone`,`tmppass`,`birth_date`) ".
					" values (md5(md5('".$params[password].$sol."')),'".
					$params[last_name]."','".$params[first_name]."','".$params[second_name].
					"','".$params[email]."','".$params[phone]."',md5(md5('".$params[password].$sol."')), '".
					$params['birth_date'][2]."-".$params['birth_date'][1]."-".$params['birth_date'][0].
					"')",$conn);
}
if (mysql_error()) {
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!'; } 
// �������� id ������ ���������
$qry_insert = mysql_query("SELECT LAST_INSERT_ID() FROM doctree AS last_id",$conn);
$new_id = mysql_fetch_row($qry_insert);

//�������� ��������������� ���� �� ����
$to = $params[email];
$subject = '������������� ����������� �� ����� '.$_SERVER['HTTP_HOST'];
$headers = "From: ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
$message = "������������, ".$params[last_name]." ".$params[first_name]." ".$params[second_name]."! \r\n\r\n".
			"�� ������� ������������������ �� ����� ".$_SERVER['HTTP_HOST']. "\r\n".
  			"��� ������������� ����������� �������� �� ������: http://".$_SERVER['HTTP_HOST']."/register/confirm/".$new_id[0]."/".rawurlencode($params['email'])."/?from_url=".rawurlencode($_SERVER[REQUEST_URI])."\r\n".
  			"���� �� ������ ������ � ��� ��������, ������ ������� ��� ���������. \r\n\r\n".
  			"� ���������, ������������� ����� ".$_SERVER['HTTP_HOST'];
mail($to, $subject, $message, $headers); // �������� �����
return '';
}////////////////////////////////////////////////////////////////

function check_confirm_params ($params) {
	global $conn;
if (!preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.
'(([a-z0-9-])*([a-z0-9]))+'.'(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i',$params['email'])) {
	return '������! �������� ������ ������ ��������� ������ ����� email!';} 
if(empty ($params['email']) || empty ($params['user_id'])) {
	return '������ � ������ �������������! ���������, ��� �����, �������� ����, ��������� � ������� � ������ ��� ���������� � ���.��������� �����.';}

$qry = mysql_query("SELECT u.* FROM `users` u WHERE u.`email`='".$params['email']."'",$conn);
if (mysql_error()) {
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!';}

if (mysql_num_rows($qry)!= 1) {
	return '������ � ������ �������������! ���������, ��� �����, �������� ����, ��������� � ������� � ������ ��� ���������� � ���.��������� �����.11';}
	
$userdata = mysql_fetch_assoc($qry); 
if ($userdata['is_active']>1) {
	$errormsg = '���� ������� ������ �������������! ���� �� �� ������, ������ ��� ���������, ���������� � ���.��������� �����.';}

IF ($userdata['is_active']==0) {
	$qry = mysql_query("update users set is_active = 1 where id ='".$params['user_id']."'",$conn);}
if (mysql_error()){
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!';}
		
session_start();
$_SESSION['user_id'] = $userdata['id'];
$_SESSION['last_name'] =$userdata['last_name']; 
$_SESSION['first_name'] =$userdata['first_name']; 
$_SESSION['second_name'] =$userdata['second_name']; 
$_SESSION['user_rights'] =$userdata['is_admin']; 
$_SESSION['user_email'] =$userdata['email']; 
$_SESSION['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR'];
$_SESSION['HTTP_USER_AGENT']=$_SERVER['HTTP_USER_AGENT'];

$parseurl = parse_url($params['from_url']);
if ( !isset($params['from_url']) || 
  	//  strstr($parseurl['path'],$_SERVER[HTTP_HOST].'/register')
  	preg_match('/(\/)?register(\/)?/i',$parseurl['path'])
   ) {
   	header("Location: http://".$_SERVER['HTTP_HOST']);
  	exit;
} else {
	header("Location: http://".$_SERVER['HTTP_HOST'].$params['from_url']);
	exit;
}
return '';
}////////////////////////////////////////////////

unset($pararray);
//---------------------------����������-------------------------------------------------
if (isset($_POST['register'])) {// ����������
	$pararray['params']['login']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['login'],0,30))));
	$pararray['params']['last_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['last_name'],0,15))));
	$pararray['params']['first_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['first_name'],0,15))));
	$pararray['params']['second_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['second_name'],0,15))));
	$pararray['params']['email']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['email'],0,20))));
	$pararray['params']['phone']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['phone'],0,20))));
	$pararray['params']['password']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['password'],0,20))));
	$pararray['params']['confirm_p']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['confirm_p'],0,20))));
	$pararray['params']['birth_date'][0] = (integer)(substr($_POST['birth_date'][0],0,2)) ;
	$pararray['params']['birth_date'][1] = (integer)(substr($_POST['birth_date'][1],0,2)) ;
	$pararray['params']['birth_date'][2] = (integer)(substr($_POST['birth_date'][2],0,4)) ;
	$pararray['params']['org_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['org_name'],0,128))));
	$pararray['params']['inn']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['inn'],0,15))));
	$pararray['params']['kpp']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['kpp'],0,15))));
	$pararray['params']['user_acct']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['user_acct'],0,20))));
	$pararray['params']['bank_name']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['bank_name'],0,128))));
	$pararray['params']['bik']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['bik'],0,15))));
	$pararray['params']['user_type']=(int)$_POST['user_type'];
	$pararray['params']['bank_acct']=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['bank_acct'],0,20))));
	$errormsg = check_edit_params($pararray['params']);
	$pararray['errormsg'] = $errormsg;
	if (!$errormsg) {$pararray['check_email'] = 1;}
//---------------------------�����������--------------------------------------------
}elseif (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('confirm')) {
	$pararray['params']['email']=mysql_real_escape_string(trim(substr(rawurldecode($_DOC_PARAMS[get_params][2]),0,30)));
	$pararray['params']['user_id'] = (integer) $_DOC_PARAMS[get_params][1];
	$pararray['params']['from_url'] = rawurldecode($_DOC_PARAMS[get_params][from_url]);
	$errormsg = check_confirm_params($pararray['params']);
	$pararray['errormsg'] = $errormsg;
	$pararray['check_email'] = 1;
}

?>