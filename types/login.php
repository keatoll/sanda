<?php
//-------------------���� ������������������� ������������----------------
/*$qry = mysql_query("SELECT md5(md5('456Tm0xSq')) mdpass FROM dual ",$conn);
$a = mysql_fetch_row($qry);
echo '"'.$a[0].'"';
*/
function check_login_params ($params) {
	global $conn;
if ((/*preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[login]) &&*/ 
		preg_match("/[^(\w)|(\@)|(\.)]/",$params[login])))  {
	return '������! �� ����� ������������ ����� email!';} 

if ( preg_match("/[^(\w)|(\x7F-\xFF)|(\s)| ]/",$params[password])) {
	return '������! ������ ����� ��������� ������ �����, �����, ������ ������������� � �����.';} 

if(empty($params[login]) || (empty ($params[password])&& isset($_POST['btn_login']))) {
	return '������! ���� �� ����� ���� �������!';}
$sol = 'Tm0xSq';
$qry = mysql_query("SELECT md5(md5('".$params[password].$sol."')) mdpass, u.* FROM users u WHERE "./*name='".$params[login]."' or*/ "email = '".$params[login]."'",$conn);
if (mysql_error()) {
	return '��������� ������ ��������� ������! ���������� � ���.��������� �����!';}

if (mysql_num_rows($qry)!= 1) {
	return '������������ � ����� ������ �� ��������������� � �������! ��������� ��������� ������ ��� ���������� � ���.��������� �����.';}

$userdata = mysql_fetch_assoc($qry); 
if ($userdata['is_active']!=1) {
	return '���� ������� ������ ������������� ��� �� ����������! ���� �� �� ������, ������ ��� ���������, ���������� � ���.��������� �����.';}
	
if ($userdata[mdpass]!= $userdata[password] && ($userdata[mdpass]!= $userdata[tmppass] && $userdata[tmppass]!= '' )) {
	return '������ �������� ������!';}

IF ($userdata[mdpass]== $userdata[tmppass]&& $userdata[tmppass]!=$userdata[password]) {
	$qry = mysql_query("update users set password = md5(md5('".$newpass."')) where id ='".$userdata[id]."'",$conn);}
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
	return '������! ���������� ������ ������ ����� ������������ �����!';}
	
$qry = mysql_query("SELECT * FROM users u WHERE "/*name = '".$params[login]."' or*/ ."email='".$params[login]."'",$conn);
if (mysql_error()) {
	return '������ ��������� ������! ���������� � ���.��������� �����!';} 
if (mysql_num_rows($qry)!= 1) {
	return '������������ � ����� ������ �� ��������������� � �������! ��������� ��������� ������ ��� ���������� � ���.��������� �����.';}

$userdata = mysql_fetch_assoc($qry);
// ��������� ������ ������ � ������ ��� � ����
$sol = 'Tm0xSq';
$newpass = generate_password();
$qry = mysql_query("update users u set u.tmppass = md5(md5('".$newpass.$sol."')) where id =".$userdata[id],$conn);
if (mysql_error()){
	return '������ ��������� ������! ���������� � ���.��������� �����!';}
  			//
$to = $userdata[email];
$subject = '�������������� ������ �� ����� '.$_SERVER['HTTP_HOST'];
$headers = "From: �������������� ������� <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
$message = "������������, ".$userdata[first_name]." ".$userdata[second_name]."! \r\n\r\n".
			"��� ������ ��� ���������, ������ ��� �� ������ ������ �� ��������� ������ ������ �� ����� http://".$_SERVER['HTTP_HOST']."\r\n".
  			"��� ����������� ������� ������: \r\n".
  			"Email: ".$userdata[email]."\r\n".
  			"������: ".$newpass." \r\n\r\n".
  			"��� �������� �� ������: http://".$_SERVER['HTTP_HOST']."/login/remember/".rawurlencode($userdata[email])."/".rawurlencode($newpass)."\r\n".
  			"���� �� �� �� ����������� ����� ������ ��� ������ ������ � ��� ��������, ������ ������� ��� ���������. \r\n\r\n".
  			"� ���������, ������������� �����.";
mail($to, $subject, $message, $headers); // �������� �����
return  '�� ��������� ����� ���������� ��������� � ����� �������.';
}///////////////////////////////////////////////

unset($pararray);
if (isset($_POST['btn_login']) || (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('remember'))) {// �����
	if (strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('remember')) { // ���� ������ - "�����������"
		$name=mysql_real_escape_string(trim(htmlspecialchars(substr($_DOC_PARAMS[get_params][1],0,30))));
		$pass=mysql_real_escape_string(trim(htmlspecialchars(substr($_DOC_PARAMS[get_params][2],0,20))));
	} else { // ���� ������ - "���������"
		$name=mysql_real_escape_string(trim(substr($_POST['login'],0,30)));
		$pass=mysql_real_escape_string(trim(substr($_POST['password'],0,20)));
	}
	$pararray['params']['login']=$name;
	$pararray['params']['password']=$pass;
	$pararray['params']['from_url'] = rawurldecode($_POST['from_url']);
	$errormsg = check_login_params($pararray['params']);
	$pararray['errormsg'] = $errormsg;
	
///-------------------��������� ������ ------------------
}elseif (isset($_POST['btn_remember'])) {
	$name=mysql_real_escape_string(trim(htmlspecialchars(substr($_POST['login'],0,30))));
	$pararray['params']['login']=$name;
	$errormsg = check_remember_params($pararray['params']);
	$pararray['errormsg'] = $errormsg;
	
/// ------------------����� �� ������� -------------------
} elseif (isset($_POST['btn_logout']) || strtoupper($_DOC_PARAMS[get_params][0])===strtoupper('logout')) {
	session_start();
	session_destroy();
	header("Location: http://".$_SERVER['HTTP_HOST']);
	exit;
}
$pararray['errormsg']=$errormsg;
?>