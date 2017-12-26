<?
function check_add_to_cart () {
	global $conn,$_DOC_PARAMS;	

if (!CheckUserRights()){ 
	return 'Ошибка! Для добавления товаров в корзину вам необходимо авторизоваться или зарегистрироваться!';}
if ($_DOC_PARAMS['properties']['isDir']==1) {
	return 'Ошибка! Этот товар нельзя добавить в корзину! При повторении ошибки обратитесь в тех.поддержку сайта.';}
if (!isset($_DOC_PARAMS['get_params'][0]) || (int)$_DOC_PARAMS['get_params'][0]<1 ) { //если не указано кол-во, то кол-во = 1 
	$_DOC_PARAMS['get_params'][1] = 0;}	

$qry = mysql_query("select amount from drugs where id= ".$_DOC_PARAMS['docid'],$conn);
if (mysql_error() || mysql_num_rows($qry)!=1 ){
	return 'Ошибка при получении данных! Обратитесь в тех.поддержку сайта.';}
	
$drugs_amount = mysql_fetch_assoc($qry);
session_start();
if (!$_SESSION['tocart'][$_DOC_PARAMS['docid']]) {
	$_SESSION['tocart'][$_DOC_PARAMS['docid']] = 0;
}
//проверка на добавляемое значение
if ((int)$_DOC_PARAMS['get_params'][0]<1 || !(int)$_DOC_PARAMS['get_params'][0]) {
	$value = 0;
}else {
	$value = (int)$_DOC_PARAMS['get_params'][0];
}
	//die($value.'+'.$_DOC_PARAMS['get_params'][0]);
//проверка на кол-во, чтобы не больше, чем на складе
if ($value+$_SESSION['tocart'][$_DOC_PARAMS['docid']] > $drugs_amount[amount]) {
	//return 'Недостаточное количество на складе! Вы можете добавить только '.$drugs_amount[amount].' штук!';
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
	return 'Ошибка! Для добавления товаров в корзину вам необходимо авторизоваться или зарегистрироваться!';}
if ($_DOC_PARAMS['properties']['isDir']==1) {
	return 'Ошибка! Этот товар нельзя добавить в корзину! При повторении ошибки обратитесь в тех.поддержку сайта.';}

$order_id = (int)$_DOC_PARAMS['get_params'][1];
if (!$order_id) {
	return 'Ошибка параметров! Попробуйте добавить товар в корзину еще раз.';}

$qry = mysql_query("select u.id from users u, order_headers h where h.user_id = u.id and h.id= ".$order_id,$conn);
if (mysql_error() || mysql_num_rows($qry)!=1 ){
	return 'Ошибка параметров! Попробуйте добавить товар в корзину еще раз.';}
$user_id = mysql_fetch_assoc($qry);	

$qry = mysql_query("select * from drugs d, doctree dt where dt.id= d.id and d.id= ".$_DOC_PARAMS['docid'],$conn);
if (mysql_error() || mysql_num_rows($qry)!=1 ){
	return 'Ошибка при получении данных! Обратитесь в тех.поддержку сайта.';}
$drugs_data = mysql_fetch_assoc($qry);

$drug_amount = (int)$_DOC_PARAMS['get_params'][0];
//если не указано кол-во, то кол-во = 1 
if ($drug_amount<1 || !$drug_amount) {$drug_amount = 0;}	

$qry = mysql_query("select * from `order_row` where drug_id = ".$_DOC_PARAMS['docid']." and header_id = $order_id",$conn);
if (mysql_num_rows($qry)==0) {
	$qry = mysql_query("insert into `order_row` (`header_id`, `drug_id`, `drug_name`, `price`, `amount`) ".
					" values(".$order_id.",".$_DOC_PARAMS['docid'].", '".$drugs_data['name'].
					"',".$drugs_data['price'].",".$drug_amount.")",$conn);
	if (mysql_error()){
		return 'Ошибка при добавлении данных! Обратитесь в тех.поддержку сайта.';}	
}elseif(mysql_num_rows($qry)==1){
	$drug_cur_data = mysql_fetch_assoc($qry);
	$drug_amount+=$drug_cur_data['amount'];
	$qry = mysql_query("update `order_row` set `amount` = $drug_amount where drug_id = "
						.$_DOC_PARAMS['docid']." and header_id = $order_id" ,$conn);
	if (mysql_error()){
		return 'Ошибка при добавлении данных! Обратитесь в тех.поддержку сайта.';}	
}else {
	return "Ошибка при получении данных! Обратитесь в тех.поддержку сайта.";
}

$qry = mysql_query('select sum(`price`*`amount`) docsum from `order_row` where header_id = '.$order_id,$conn);
if (mysql_error()){
	return 'Ошибка при добавлении данных! Обратитесь в тех.поддержку сайта.';}	
$summ_order = mysql_fetch_assoc($qry);

$qry = mysql_query("update `order_headers` set `summ` = ".$summ_order[docsum]." where id = ".$order_id,$conn);
if (mysql_error()){
	return 'Ошибка при добавлении данных! Обратитесь в тех.поддержку сайта.';}	

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