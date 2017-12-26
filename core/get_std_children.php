<?
//поиск всех дочерних элементов, если тип документа - папка 
if ($_DOC_PARAMS['properties']['isDir']== 1 ){ 
	$c_page = $_GET[page];
	$cur_page = (int)$c_page;
	if (!$cur_page) {$cur_page=1;}
	$res = mysql_query("select count(dt.`id`) from doctree dt where dt.`isActive` = 1 and dt.parent = ".$_DOC_PARAMS['docid'],$conn);
	if (!mysql_error() && mysql_num_rows($res)==1){
		$req_count = mysql_fetch_row($res);
	}else {$req_count[0]=0;}
	$limit=20;
	$maxpage = max(round($req_count[0]/$limit),1);
	if ($maxpage*$limit <$req_count[0]) {$maxpage=$maxpage+1;}
	$page=min($maxpage,$cur_page);
	$page= max(1,$page);
	$from=($page-1)*$limit;
	//$to=min($req_count[0],$from+$limit);
	$doc_child = mysql_query(" select * from doctree dt ". 
							 " where dt.`isActive` = 1 and dt.parent = {$_DOC_PARAMS['docid']} limit $from, $limit",$conn);
	if (!mysql_error()) {// нет ошибок и объект найден
		if (key_exists('img', $_DOC_PARAMS['properties'])){$is_img=1;}else {$is_img=0;}
		while ($row = mysql_fetch_assoc($doc_child)) {
			if ($is_img){
				$qry = mysql_query("select img from {$_DOC_PARAMS['type']} where id = {$row['id']}",$conn);
				$img_val = mysql_fetch_assoc($qry);
				$row['img']=$img_val['img'];
			}
			$pararray['child_list'][] = $row;
			
		}
		$pararray['child_list'][page]=$page;
		$pararray['child_list'][max_page]=$maxpage;
	}else{ $errormsg = 'Произошла ошибка при получении данных. Обратитесь в тех.поддержку сайта!'; }
}
$pararray['errormsg']=$errormsg;

?>