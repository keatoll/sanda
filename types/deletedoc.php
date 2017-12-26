<? 	
// проверка прав
$errormsg = ''; 
$qry = mysql_query("select id from doctree where parent = ".$_DOC_PARAMS[docid]." limit 0,1",$conn);
if (mysql_num_rows($qry)>0) {
	$errormsg = 'У удаляемого объекта есть зависимые. Удаление запрещено.';
} else { //'no children'

//------------сначала удалить картинки --------------------------------------------------//
	//получаем id документа
//doc_delete_img;
$qry = mysql_query("SELECT p.*, dt.tablename  FROM params p, doctype dt ".
					"WHERE p.`field_type` = 'img' ".
       				"and p.doctype in (dt.id,0) ".
       				"and dt.name = '".$_DOC_PARAMS[type]."'",$conn);
if (mysql_error()) {
		$errormsg = 'Произошла ошибка во время получения данных'; 
} else if (mysql_num_rows($qry) >0 ) { // нет ошибок и объект найден
	while ($doc_imgs = mysql_fetch_assoc($qry)) { // получаем список параметров-изображений удаляемого объекта 
		$qry = mysql_query("select {$doc_imgs['field_name']} from {$doc_imgs['tablename']} where id = {$_DOC_PARAMS[docid]}",$conn);
		if (mysql_error()) {
			$errormsg = 'Произошла ошибка во время получения данных'; 
		} else {
			$indx = mysql_fetch_row($qry);
			if (file_exists ($indx[0])) {
				unlink($indx[0]); 
			}
		}
	}
} //----сначала удалить картинки 

//------------затем удалить документ ------------------------------------------------//
//вытаскиваем общие свойства объекта из doctree
$qry = mysql_query("select dt.tablename dttbl, d.id, d.parent".
				" from doctree d, doctype dt".
				" where d.id = '{$_DOC_PARAMS[docid]}' and d.type = dt.id ",$conn);
if (mysql_error()||mysql_num_rows($qry)!=1) {
		$errormsg = 'Произошла ошибка во время получения данных'; 
} else {
	$delete_params = mysql_fetch_assoc($qry);
		$qry_del = mysql_query('delete from `'.$delete_params['dttbl'].'` where id='.$_DOC_PARAMS[docid],$conn);
		if( mysql_error()) {
			$errormsg = 'Произошла ошибка во время удаления данных документа'; 
		} else {
			$qry_del = mysql_query('delete from `doctree` where id='.$_DOC_PARAMS[docid],$conn);
			if ($delete_params['id']==$_DOC_PARAMS[docid]) {
				header("Location: ".GetTypePath($delete_params['parent'],1));
				exit;
			}
			if(mysql_error()) {
				$errormsg = 'Произошла ошибка во время удаления документа'; 
			}
		}
	}
} 
//---если нужно удалить документ
$pararray['errormsg']=$errormsg;

?>