<?
function check_add_to_cart () {
	global $conn,$_DOC_PARAMS;	

if (!CheckUserRights()){ 
	return '������! ��� ���������� ������� � ������� ��� ���������� �������������� ��� ������������������!';}
if ($_DOC_PARAMS['properties']['isDir']==1) {
	return '������! ���� ����� ������ �������� � �������! ��� ���������� ������ ���������� � ���.��������� �����.';}
if (!isset($_DOC_PARAMS['get_params'][0]) || (int)$_DOC_PARAMS['get_params'][0]<1 ) { //���� �� ������� ���-��, �� ���-�� = 1 
	$_DOC_PARAMS['get_params'][1] = 0;}	

$qry = mysql_query("select amount from drugs where id= ".$_DOC_PARAMS['docid'],$conn);
if (mysql_error() || mysql_num_rows($qry)!=1 ){
	return '������ ��� ��������� ������! ���������� � ���.��������� �����.';}
	
$drugs_amount = mysql_fetch_assoc($qry);
session_start();
if (!$_SESSION['tocart'][$_DOC_PARAMS['docid']]) {
	$_SESSION['tocart'][$_DOC_PARAMS['docid']] = 0;
}
//�������� �� ����������� ��������
if ((int)$_DOC_PARAMS['get_params'][0]<1 || !(int)$_DOC_PARAMS['get_params'][0]) {
	$value = 0;
}else {
	$value = (int)$_DOC_PARAMS['get_params'][0];
}
	//die($value.'+'.$_DOC_PARAMS['get_params'][0]);
//�������� �� ���-��, ����� �� ������, ��� �� ������
if ($value+$_SESSION['tocart'][$_DOC_PARAMS['docid']] > $drugs_amount[amount]) {
	//return '������������� ���������� �� ������! �� ������ �������� ������ '.$drugs_amount[amount].' ����!';
	$_SESSION['tocart'][$_DOC_PARAMS['docid']] = $drugs_amount[amount];
}else {
	$_SESSION['tocart'][$_DOC_PARAMS['docid']] = $_SESSION['tocart'][$_DOC_PARAMS['docid']] + $value;
}	
	 
header("Location: ".GetTypePath($_DOC_PARAMS['properties']['parent']));
exit;
}

function check_add_to_cart_admin () {
	global $conn,$_DOC_PARAMS;
if (!CheckUserRights()){ 
	return '������! ��� ���������� ������� � ������� ��� ���������� �������������� ��� ������������������!';}
if ($_DOC_PARAMS['properties']['isDir']==1) {
	return '������! ���� ����� ������ �������� � �������! ��� ���������� ������ ���������� � ���.��������� �����.';}

$order_id = (int)$_DOC_PARAMS['get_params'][1];
if (!$order_id) {
	return '������ ����������! ���������� �������� ����� � ������� ��� ���.';}

$qry = mysql_query("select u.id from users u, order_headers h where h.user_id = u.id and h.id= ".$order_id,$conn);
if (mysql_error() || mysql_num_rows($qry)!=1 ){
	return '������ ����������! ���������� �������� ����� � ������� ��� ���.';}
$user_id = mysql_fetch_assoc($qry);	

$qry = mysql_query("select * from drugs d, doctree dt where dt.id= d.id and d.id= ".$_DOC_PARAMS['docid'],$conn);
if (mysql_error() || mysql_num_rows($qry)!=1 ){
	return '������ ��� ��������� ������! ���������� � ���.��������� �����.';}
$drugs_data = mysql_fetch_assoc($qry);

$drug_amount = (int)$_DOC_PARAMS['get_params'][0];
//���� �� ������� ���-��, �� ���-�� = 1 
if ($drug_amount<1 || !$drug_amount) {$drug_amount = 0;}	

$qry = mysql_query("select * from `order_row` where drug_id = ".$_DOC_PARAMS['docid']." and header_id = $order_id",$conn);
if (mysql_num_rows($qry)==0) {
	$qry = mysql_query("insert into `order_row` (`header_id`, `drug_id`, `drug_name`, `price`, `amount`) ".
					" values(".$order_id.",".$_DOC_PARAMS['docid'].", '".$drugs_data['name'].
					"',".$drugs_data['price'].",".$drug_amount.")",$conn);
	if (mysql_error()){
		return '������ ��� ���������� ������! ���������� � ���.��������� �����.';}	
}elseif(mysql_num_rows($qry)==1){
	$drug_cur_data = mysql_fetch_assoc($qry);
	$drug_amount+=$drug_cur_data['amount'];
	$qry = mysql_query("update `order_row` set `amount` = $drug_amount where drug_id = "
						.$_DOC_PARAMS['docid']." and header_id = $order_id" ,$conn);
	if (mysql_error()){
		return '������ ��� ���������� ������! ���������� � ���.��������� �����.';}	
}else {
	return "������ ��� ��������� ������! ���������� � ���.��������� �����.";
}

$qry = mysql_query('select sum(`price`*`amount`) docsum from `order_row` where header_id = '.$order_id,$conn);
if (mysql_error()){
	return '������ ��� ���������� ������! ���������� � ���.��������� �����.';}	
$summ_order = mysql_fetch_assoc($qry);

$qry = mysql_query("update `order_headers` set `summ` = ".$summ_order[docsum]." where id = ".$order_id,$conn);
if (mysql_error()){
	return '������ ��� ���������� ������! ���������� � ���.��������� �����.';}	

header("Location: ".GetTypePath($_DOC_PARAMS['properties']['parent']));
exit;
}

if ((int)$_DOC_PARAMS['get_params'][1]) {
	$errormsg = check_add_to_cart_admin();
}else {
	$errormsg = check_add_to_cart();
}
$pararray['errormsg']=$errormsg;
?>