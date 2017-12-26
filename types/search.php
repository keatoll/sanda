<?
//require_once 'stddoc.php';
//поиск всех дочерних элементов, если тип документа - папка 
if ($_DOC_PARAMS['properties']['isDir']== 1 ){ 
	$c_page = $_GET[page];
	$cur_page = (int)$c_page;
	if (!$cur_page) {$cur_page=1;}
	
	// параметры поиска
	$search_param = '';
	if (isset ($_POST['search_submit'])){
		$pararray['query_str']=urldecode($_POST['search_name']);
		$sParam = trim(mysql_real_escape_string(htmlspecialchars($_POST['search_name'])));
		$arrParam = explode(' ',$sParam);
		if ($arrParam){
		foreach ($arrParam as $sSubstr) {
			$search_param = ($search_param)?$search_param." and upper(dt.name) like upper('%$sSubstr%') ":
										" and (upper(dt.name) like upper('%$sSubstr%') ";
		} 
		$search_param = ($search_param)?$search_param.")":$search_param;
		} elseif($sParam) { 
			$search_param = " and (upper(dt.name) like upper('%$sParam%') )";
		}
		unset($arrParam);
	} elseif (strlen($_DOC_PARAMS['get_params'][0])>1) {
		$pararray['query_str']=urldecode($_DOC_PARAMS['get_params'][0]);
		$sParam = trim(mysql_real_escape_string(urldecode($_DOC_PARAMS['get_params'][0])));
		$arrParam = explode(' ',$sParam);
		if ($arrParam){
		foreach ($arrParam as $sSubstr) {
			$search_param = ($search_param)?$search_param." and upper(dt.name) like upper('%$sSubstr%') ":
										" and (upper(dt.name) like upper('%$sSubstr%') ";
		} 
		$search_param = ($search_param)?$search_param.")":$search_param;
		} elseif($sParam) { 
			$search_param = " and (upper(dt.name) like upper('%$sParam%') )";
		}
		unset($arrParam);
	} else {
		$pararray['query_str']=urldecode($_DOC_PARAMS['get_params'][0]);
		$sParam = substr(urldecode($_DOC_PARAMS['get_params'][0]),0,1);
		$search_param = " and upper(substr(dt.name,1,1)) = upper('$sParam') "; 
	}
	$pararray['sparam'] = $sParam;
	
	//здесь мы учитываем, что поиск у нас только по активным документам
	$res = mysql_query("select count(id) from doctree dt where dt.isActive = 1 and dt.parent = ".$_DOC_PARAMS['docid']
						.$search_param,$conn);
	$req_count = mysql_fetch_row($res);
	$limit=10;
	$maxpage = max(round($req_count[0]/$limit),1);
	if ($maxpage*$limit <$req_count[0]) {$maxpage=$maxpage+1;}
	$page=min($maxpage,$cur_page);
	$page= max(1,$page);
	$from=($page-1)*$limit;
	//$to=min($req_count[0],$from+$limit);
	
	$doc_child = mysql_query(" select * from doctree dt ". 
							 " where dt.isActive = 1 and dt.parent = {$_DOC_PARAMS['docid']} ".$search_param." limit $from, $limit",$conn);
	if (!mysql_error()) {// нет ошибок и объект найден
		while ($row = mysql_fetch_assoc($doc_child)) {
			$pararray['child_list'][] = $row;
		}
		$pararray['child_list'][page]=$page;
		$pararray['child_list'][max_page]=$maxpage;
	}else{ $errormsg = 'Произошла ошибка при получении данных. Обратитесь в тех.поддержку сайта!'; }
  }
$pararray['errormsg']=$errormsg;

//
session_start();
if ($_SESSION['user_rights']==1){
	$qry = mysql_query("select  o.id oid, 
			concat(u.last_name,' ',u.first_name,' ',u.second_name) nm 
			from order_headers o, users u 
			where u.id = o.user_id and o.state = 0",$conn);
	if (!mysql_error() && mysql_num_rows($qry)>0) {
		while ($orders = mysql_fetch_assoc($qry)) {
			$pararray['admin_orders'][$orders['oid']]=$orders['oid'].'-'.$orders['nm'];
		}
	}
	$pararray['alph_search'] = call_type('search_panel');
	}
?>