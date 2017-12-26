<? 
function check_send_params () {
global $conn,$_DOC_PARAMS;	
if (!isset ($_POST['cur_order_input'])){
	return '';}
$errormsg = '';
$summ_order = 0;
$order_id = (int)$_DOC_PARAMS['get_params'][0];
$qry = mysql_query("select * from `order_headers` h where h.state != 1 and h.`id` = ".$order_id,$conn);
if (mysql_error() || mysql_numrows($qry)!=1 ){
	return 'Ошибка параметров! Попробуйте выполнить операцию еще раз.';}
$last_order = mysql_fetch_assoc($qry);
foreach ($_POST['cur_order_input'] as $key => $value) { //проверяем, были ли изменения в заказе перед отправкой на обработку
	$str_to_import = '';
	$qry = mysql_query("select * from order_row r where r.drug_id= ".$key." and r.header_id = ".$order_id,$conn);
	if (mysql_error() || mysql_numrows($qry)!=1 ){
		return 'Ошибка при получении данных! Обратитесь в тех.поддержку сайта.';}
	$row_data = mysql_fetch_assoc($qry);
	
	$qry = mysql_query("select * from drugs d where d.id= ".$key,$conn);
	if (mysql_error() || mysql_numrows($qry)!=1 ){
		return 'Ошибка при получении данных! Обратитесь в тех.поддержку сайта.';}
	$drugs_data = mysql_fetch_assoc($qry);
	if ($value != $row_data['amount']) { //если нужно изменить кол-во
		if ($value && !(int)$value) {
			return 'Ошибка! Указано неверное количество товаров.';}
		if ((int)$value<0){ $value = 0;} 
		if ((int)$value > $drugs_data['amount']) {
			$errormsg = 'Недостаточное количество на складе! Вы можете добавить только '.$drugs_amount[amount].' штук!';
			$value=$drugs_data[amount];
		}
		if ($value >=1 ) {
			$qry = mysql_query("update `order_row` r set r.amount = ".$value.
							  " where r.drug_id = ".$key." and r.header_id = ".$order_id, $conn);
 			// TODO: str to import 
			$str_to_import = $drugs_data['id_1c']."\r".$last_order['name']."\r".$drugs_data['factory']."\r".
							 $drugs_data['description']."\r".$value."\r".$drugs_data['price']."\r".
							 $drugs_data['farm_group']."\r";
		} else {
			$qry = mysql_query("delete from `order_row` r where r.drug_id = ".$key." and r.header_id = ".$order_id,$conn);
		}
		if (mysql_error() ){
			return 'Ошибка при сохранении данных! Обратитесь в тех.поддержку сайта.';}
		
 	} else {
 		// TODO: str to import 
			$str_to_import = $drugs_data['id_1c']."\r".$last_order['name']."\r".$drugs_data['factory']."\r".
							 $drugs_data['description']."\r".$drugs_data['amount']."\r".$drugs_data['price']."\r".
							 $drugs_data['farm_group']."\r";
 	}
	$summ_order =$summ_order+($value*$drugs_data['price']);

	//импорт резервирования
	/* 0=`id_1c`  * 1=`name`   * 2=`description`   * 3=`amount`   * 4=`price`   * 5=`f_group` */
	if ($str_to_import){
		// создаем файл на локальной машине
		$hanle = fopen("uploads/export_".$last_order['id'].".txt", "a") ;
		fputs($handle,$str_to_import);// or return "Не могу произвести запись в файл";
		fclose($handle);
			
		//копируем файл на 
		include_once 'core/globalvars.php';
		$conn_id = ftp_connect($ftp_url);// or return 'Не могу открыть файл'; 
		ftp_login($conn_id,$ftp_user , $ftp_pass );// or return "Не удалось войти под именем user\n";
		ftp_pasv($conn_id, true);
		$upload = ftp_put($conn_id, 'price.txt', 'uploads/price.txt', FTP_ASCII);// or return 'Не удалось закачать файл'; 
		ftp_close($conn_id);
		
		unlink("uploads/export_".$last_order['id'].".txt");
	}
}
if ($last_order['summ'] != $summ_order) {
	$qry = mysql_query("update `order_headers` set `summ` = $summ_order, `state` = 1 where id = ".$order_id,$conn);
	if (mysql_error()){
		return 'Ошибка при сохранении данных! Обратитесь в тех.поддержку сайта.';}
}

if (!$errormsg) {
	$errormsg = 'Данный заказ успешно обработан!';}

return $errormsg;
}

function check_recount_params () {
	global $conn,$_DOC_PARAMS;	
$errormsg = '';	
if (!isset ($_POST['cur_order_input'])){
	return '';}
$order_id = (int)$_DOC_PARAMS['get_params'][0];
foreach ($_POST['cur_order_input'] as $key => $value) {
	$qry = mysql_query("select amount from drugs where id= ".$key,$conn);
	if (mysql_error() || mysql_numrows($qry)!=1 ){
		return 'Ошибка при получении данных! Обратитесь в тех.поддержку сайта.';}
	
	$drugs_amount = mysql_fetch_assoc($qry);
	if ($value && !(int)$value) {
		return 'Ошибка! Указано неверное количество товаров.';}
		
	if ((int)$value > $drugs_amount[amount]) {
		$errormsg = 'Недостаточное количество на складе! Вы можете добавить только '.$drugs_amount[amount].' штук!';
		$value=$drugs_amount[amount];
	}
	
	if ((int)$value<0){ $value = 0;} 
	if ($value >=1 ) {
		$qry = mysql_query("update `order_row` r set r.amount = ".$value.
					  " where r.drug_id = ".$key." and r.header_id = ".$order_id , $conn);
	} else {
		$qry = mysql_query("delete from `order_row` r where r.drug_id = ".$key." and r.header_id = ".$order_id ,$conn);
	}
	if (mysql_error() ){
		return 'Ошибка при сохранении данных! Обратитесь в тех.поддержку сайта.';}
}
$qry = mysql_query("select sum(r.amount*r.price) h_sum from `order_row` r where r.header_id= $order_id",$conn);
if (mysql_error() || mysql_numrows($qry)!=1 ){
	return 'Ошибка при получении данных! Обратитесь в тех.поддержку сайта.';}
$new_sum = mysql_fetch_assoc($qry);
$qry = mysql_query("update `order_headers` r set r.summ = ".$new_sum['h_sum'].
			  " where r.id = ".$order_id , $conn);
if (mysql_error() ){
	return 'Ошибка при сохранении данных! Обратитесь в тех.поддержку сайта.';}
return $errormsg;
}
	
function check_cancel_params () {
	global $conn,$_DOC_PARAMS;	
	$order_id = (int)$_DOC_PARAMS['get_params'][0];
	$qry = mysql_query("delete from `order_row` where `header_id` = ".$order_id,$conn);
	if (mysql_error()){
		return 'Ошибка при изменении данных! Обратитесь в тех.поддержку сайта.'.mysql_error();}

	$qry = mysql_query("delete from `order_headers` where `state` != 1 and `id` = ".$order_id,$conn);
	if (mysql_error()){
		return 'Ошибка при изменении данных! Обратитесь в тех.поддержку сайта.';}
	header("Location: http://".$_SERVER["HTTP_HOST"].'/admin/manage_orders/');
	exit;
return '';
}

//отменить заказ
if (isset($_POST['cancel'])) {
	$errormsg = check_cancel_params();
		
//принять заказ
}elseif (isset($_POST['send'])) {
	$errormsg = check_send_params();

//пересчитать сумму
}elseif (isset($_POST['recount'])) {
	$errormsg = check_recount_params();
	
//удалить позицию из заказа
}elseif (isset($_POST['cur_order_delete'])) {
	$order_id = (int)$_DOC_PARAMS['get_params'][0];
	foreach($_POST['cur_order_delete'] as $key=>$value) {
	//	unset($_SESSION[tocart][$key]);
		$qry = mysql_query("delete from `order_row` r where r.drug_id = ".$key." and r.header_id = ".$order_id ,$conn);
		if (mysql_error() ){
			return 'Ошибка при сохранении данных! Обратитесь в тех.поддержку сайта.';}
	}
	$errormsg = '';
}

// отобразить список заказа
if ((int)$_DOC_PARAMS['get_params'][0]>0){ //если показываем параметры заказа
$order_id = (int)$_DOC_PARAMS['get_params'][0];
// отобразить список заказа
$qry = mysql_query("select * from order_row r where r.header_id = ".$order_id,$conn);
if (mysql_error() || mysql_numrows($qry)<1 ) {
	$errormsg = 'Произошла ошибка во время получения данных! Обратитесь в тех.поддержку сайта.';
}else {
	//!!!!!!!!!!!! if (state <>0) {die ('ошибка!'); } //добавить!!!!
	$summ_order = 0;
	while ($hrow = mysql_fetch_assoc($qry)) {
		$pararray['params'][] = $hrow;
		$summ_order += $hrow['price']*$hrow['amount'];
	}
	$qry = mysql_query('select * from order_headers h where h.id = '.$order_id,$conn);
	if (mysql_error() || mysql_numrows($qry)<1 ) {
		$errormsg = 'Произошла ошибка во время получения данных! Обратитесь в тех.поддержку сайта.';
	}else {
		$h_params = mysql_fetch_assoc($qry);
		$pararray['order'] = $h_params; 
		$pararray['order']['date'] = substr($h_params['date'],0,10);
	}
	
}
$pararray['summ_order']= $summ_order;

//поиск всех дочерних элементов, если тип документа - папка 
} elseif (!$_DOC_PARAMS['get_params'][0]/* && (int)$_SESSION[user_id]>0 */){ 
	$c_page = $_GET[page];
	$cur_page = (int)$c_page;
	if (!$cur_page) {$cur_page=1;}
	$res = mysql_query("select count(id) from order_headers h where h.state = 0",$conn);
	$req_count = mysql_fetch_row($res);
	$limit=10;
	$maxpage = max(round($req_count[0]/$limit),1);
	if ($maxpage*$limit <$req_count[0]) {$maxpage=$maxpage+1;}
	$page=min($maxpage,$cur_page);
	$page= max(1,$page);
	$from=($page-1)*$limit;
	//$to=min($req_count[0],$from+$limit);
  	
	$doc_child = mysql_query(" select * from order_headers h where h.state = 0 ".
							 " limit $from, $limit",$conn);
	if (!mysql_error()) {// нет ошибок и объект найден
		while ($row = mysql_fetch_assoc($doc_child)) {
			$pararray['child_list'][] = $row;
		}
		$pararray['child_list'][page]=$page;
		$pararray['child_list'][max_page]=$maxpage;
	}else{ $errormsg = 'Произошла ошибка при получении данных. Обратитесь в тех.поддержку сайта!'; }
 }
$pararray['errormsg']=$errormsg;
?>