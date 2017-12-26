<?
// проверка прав
$errormsg = ''; 

function checknsave_new_data () {
	global $conn,$_DOC_PARAMS;	

$insert_str_key = '';
$insert_str_value = '';
$insert_str_key_dt = '';
$insert_str_value_dt = '';
$url_param = '';
$surl = '';
$arr_no_update = array('id','edit_obj','MAX_FILE_SIZE','delete_file');

$qry = mysql_query("select dt.* from doctype dt where dt.name= '{$_DOC_PARAMS[type]}'",$conn);
if (mysql_error()) {
	return 'Произошла ошибка во время получения данных! Обратитесь в тех.поддержку сайта.'; }
if (mysql_num_rows($qry)!=1) { // нет ошибок и объект найден
	return 'Произошла ошибка во время получения данных. Переданы неверные параметры'; }
	
$doctree = mysql_fetch_assoc($qry); // получаем общие свойства объекта
	
//--------properties------- //
// изменяем остальные свойства объекта
foreach ($_POST as $key => $value) {
	if (in_array($key, $arr_no_update)){ continue;}
	$qry = mysql_query("select * from params where doctype in (0,{$doctree['id']}) 
											   and field_name = '{$key}' ",$conn);
	if (mysql_error()) {
		return 'Произошла ошибка во время получения данных. Переданы неверные параметры';}
	$res_params = mysql_fetch_assoc($qry);
	if ($key =='url' && $_DOC_PARAMS[properties][url]<> $value) {
		$surl =  '1';
	}
		
	// вытаскиваем типы параметров 
	$param_value = '';
	switch ($res_params['field_type']){
		//case 'id'	  : $param_value = (int)$value;	break;
		case 'yn'	  : $param_value = ((int)$value==1)?1:0;break;
		case 'text'   : if (!$value && $res_params['field_name']=='url' && !$res_params['doctype']){
						   $value = $docid;}
						$param_value = mysql_real_escape_string($value); break;
		case 'date'	  : $param_value = (checkdate(substr($value[1],0,2), substr($value[0],0,2), substr($value[2],0,4)))? 
			 				substr($value[2],0,4).'-'.substr($value[1],0,2).'-'.substr($value[0],0,2):date("Y").'-'.date("m").'-'.date("d");
							break;
		case 'int'	  : $param_value = (int)$value;		break;
		case 'num'	  : $param_value = (float)$value;	break;
		// varchar+ : 
		default: if ($res_params['doctype']){
					$qry_params = mysql_query("select {$res_params['field_name']} from {$doctree['tablename']} where {$res_params['field_name']} IS NOT null limit 1",$conn);
				 $param_value = mysql_real_escape_string(substr($value,0,mysql_field_len($qry_params,0)));	
				}else {
					$qry_params = mysql_query("select {$res_params['field_name']} from doctree where {$res_params['field_name']} IS NOT null limit 1",$conn);
				 $param_value = mysql_real_escape_string(substr($value,0,mysql_field_len($qry_params,0)));	
				}
				break;
	}
	if ($key == 'url') {
		$url_param = $param_value;
		if (!ereg("^([\.\-_A-Za-z0-9])*[\.\-_A-Za-z]([\.\-_A-Za-z0-9])*$", $url_param)){
			$url_param = '';
		}
	}
	if ($url_param || $key != 'url') {
		if ($res_params['doctype']!='0'){
			$insert_str_key.=(($insert_str_key)?", ":""). "$key";
			$insert_str_value.=(($insert_str_value)?", ":""). "'$param_value'";
		
		}else {
			$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "$key";
			$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'$param_value'";
		}
	}
}

//если папка
if (strtolower($_DOC_PARAMS['get_params'][0])=='folder'){
	$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "isDir";
	$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'1'";
}	

//ПОИСК РОДИТЕЛЯ
/* по принципу: если добавляем новый в категории, то добавляется дочерний, 
 * если добавляем в объекте-листе, то родитель = родителю
 * */ 
if ($_DOC_PARAMS['properties']['isDir']==1){
	$parent_dir =$_DOC_PARAMS['docid']; 
} else {
	$parent_dir = $_DOC_PARAMS['properties']['parent'];
}
if (!$parent_dir) {$parent_dir = 0;}

$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "parent";
$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'{$parent_dir}'";

//ЗАПИСЬ ТИПА
$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "type";
$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'{$_DOC_PARAMS['properties']['type']}'";

//----------properties----- \\
	
	
// вносим запись в doctree:
if (!$insert_str_key_dt) { return '';} //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

mysql_query("insert into doctree (".$insert_str_key_dt. ") values(".$insert_str_value_dt. ") ",$conn); 
$qry_insert = mysql_query("SELECT LAST_INSERT_ID() AS last_id FROM doctree ",$conn);
// получаем id нового документа
$new_id = mysql_fetch_row($qry_insert);
if (mysql_error()) {
	return 'Произошла ошибка во время обновления данных. Обратитесь в тех.поддержку сайта!'; } 
// проверяем, что поле url заполнено. если нет, то ставим поле = id
if (!$url_param){
	mysql_query("update doctree set `url`=".$new_id[0]." where id = ".$new_id[0],$conn);
}

//----------files---------- //
foreach ($_FILES as $key => $value) {
	if ($value['error']==4) {
		continue;
	}else if ($value['error']!=0){
		return "Ошибка загрузки файла" ;
	}
	if (!is_uploaded_file($value['tmp_name'])) {
		return "Ошибка загрузки файла" ;
	}
	
	if($value["size"] > 1024*2*1024) {
    	return "Размер файла превышает два мегабайта";}
	//вытаскиваем тип файла из параметров
	$qry = mysql_query("select * from params where doctype in (0,{$doctree['id']}) 
										   and field_name = '{$key}' ",$conn);
	if (mysql_error()) {
		$errormsg = 'Произошла ошибка во время получения данных. Обратитесь в тех.поддержку сайта!';}
	$res = mysql_fetch_assoc($qry);
	// вытаскиваем типы параметров 
	$f_true = '';
	switch ($res['field_type']){
		case 'img'	  : $f_true = getimagesize($value['tmp_name']); break;
		case 'file'	  : $f_true = filesize($value['tmp_name']) ;	break;
		default		  : $f_true = filesize($value['tmp_name']) ;	break;
	}
	if (!$f_true || !get_image_type($value['tmp_name'])) {
		return 'Произошла ошибка во время загрузки файла';} 
	$ext = end(explode('.',$value['name']));
	if (!file_exists("uploads/{$doctree['tablename']}/") ) {
		mkdir("uploads/{$doctree['tablename']}/");
	}
	$fname = "uploads/{$doctree['tablename']}/".$new_id[0].'.'.$ext;
	//if (!move_uploaded_file($value["tmp_name"],$fname)){
	if (!resizeimg($value["tmp_name"],$fname,250,0,$_SERVER["HTTP_HOST"])){
		$errormsg = 'Произошла ошибка во время сохранения файла';
		break;
	}
	if ($res['doctype']!='0'){
		$insert_str_key.=(($insert_str_key)?", ":""). "$key";
		$insert_str_value.=(($insert_str_value)?", ":""). "'$fname'";
	}else {
		$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "$key";
		$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'$fname'";
	}
} //----------files---------- \\
	
if (!$insert_str_key ) {return '';}//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//if (!$parent_dir) {
mysql_query("insert into {$doctree['tablename']} (id,".$insert_str_key. ") values($new_id[0],".$insert_str_value. ") ",$conn);
if (mysql_error()) {
	return  'Произошла ошибка во время обновления данных. Обратитесь в тех.поддержку сайта!';}
//}
header("Location: ".GetTypePath($new_id[0],1));
exit;
}//end checknsave_new_data
	
if (isset ($_POST['edit_obj'])) {
//------------если нужно сохранить данные ------------------------------------------------//
$errormsg = checknsave_new_data();
//----если нужно сохранить данные
}

$is_dir= strtolower($_DOC_PARAMS['get_params'][0])=='folder'?1:0;
//вытаскиваем общие свойства объекта из doctree
$qry = mysql_query("select dt.* from doctype dt where dt.name= '{$_DOC_PARAMS[type]}'",$conn);
if (mysql_error()) {
		$errormsg = 'Произошла ошибка во время получения данных. Обратитесь в тех.поддержку сайта!'; 
}
if (mysql_num_rows($qry)==1) { // нет ошибок и объект найден
	$doctype = mysql_fetch_assoc($qry); // получаем общие свойства объекта

	$qry = mysql_query("select * from params  
		where doctype in (0,{$doctype['id']}) order by doctype ",$conn);
	if (mysql_error()) {
		$errormsg = 'Произошла ошибка во время получения данных. Обратитесь в тех.поддержку сайта!'; 
	} else {
	unset($pararray);
	while ($res = mysql_fetch_assoc($qry)) {
	  if (!$is_dir || !$res['doctype']){ //если не папка, то все параметры, если папка, то только основные
				// вытаскиваем типы параметров 
		switch ($res['field_type']){
			case 'id'	  : $res['input_type'] = 'hidden';	break;
			case 'yn'	  : $res['input_type'] = 'checkbox';break;
			case 'img'	  : $res['input_type'] = 'file';	break;
			case 'text'   : $res['input_type'] = 'textarea';break;
			case 'date'	  : $res['input_type'] = 'text';	break;
			case 'varchar': $res['input_type'] = 'text';	break;
			case 'int'	  : $res['input_type'] = 'text';	break;
			case 'num'	  : $res['input_type'] = 'text';	break;
			default		  : $res['input_type'] = 'text';	break;
		}
		 if($res['field_type']== 'date'){ 
			$res['value'][0] = date("Y");
			$res['value'][1] = date("m");
			$res['value'][2] = date("d"); // значение параметра
		}
		$pararray ['docparams'][] = $res;
	  }}
	}
} else {
	$errormsg = 'Произошла ошибка во время получения данных. Обратитесь в тех.поддержку сайта!'; 
}
$pararray['errormsg']=$errormsg;
?>