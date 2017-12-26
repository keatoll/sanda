<? 
session_start();
if ((int)$_DOC_PARAMS['get_params'][0]>0){ //если показываем параметры заказа
$order_id = (int)$_DOC_PARAMS['get_params'][0];
// отобразить список заказа
$qry = mysql_query("select * from order_row r where r.header_id = ".$order_id,$conn);
if (mysql_error() || mysql_num_rows($qry)<1 ) {
	$errormsg = 'Произошла ошибка во время получения данных! Обратитесь в тех.поддержку сайта.';
}else {
	$summ_order = 0;
	while ($hrow = mysql_fetch_assoc($qry)) {
		$pararray['params'][] = $hrow;
		$summ_order += $hrow['price']*$hrow['amount'];
	}
	$qry = mysql_query('select * from order_headers h where h.id = '.$order_id,$conn);
	if (mysql_error() || mysql_num_rows($qry)<1 ) {
		$errormsg = 'Произошла ошибка во время получения данных! Обратитесь в тех.поддержку сайта.';
	}else {
		$h_params = mysql_fetch_assoc($qry);
		$pararray['order_date'] = substr($h_params['date'],0,10);
		$pararray['with_sert'] = (int)$h_params['with_sert'];
	}
	
}
$pararray['summ_order']= $summ_order;

//поиск всех дочерних элементов, если тип документа - папка 
} elseif (!$_DOC_PARAMS['get_params'][0] && (int)$_SESSION[user_id]>0 ){ 
	$c_page = $_GET[page];
	$cur_page = (int)$c_page;
	if (!$cur_page) {$cur_page=1;}
	$res = mysql_query("select count(id) from order_headers h where h.user_id = ".$_SESSION[user_id],$conn);
	$req_count = mysql_fetch_row($res);
	$limit=10;
	$maxpage = max(round($req_count[0]/$limit),1);
	if ($maxpage*$limit <$req_count[0]) {$maxpage=$maxpage+1;}
	$page=min($maxpage,$cur_page);
	$page= max(1,$page);
	$from=($page-1)*$limit;
	//$to=min($req_count[0],$from+$limit);
  	
	$doc_child = mysql_query(" select * from order_headers h  ". 
							 " where h.user_id = ".$_SESSION[user_id].
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