<?
// �������� ����
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
	return '��������� ������ �� ����� ��������� ������! ���������� � ���.��������� �����.'; }
if (mysql_num_rows($qry)!=1) { // ��� ������ � ������ ������
	return '��������� ������ �� ����� ��������� ������. �������� �������� ���������'; }
	
$doctree = mysql_fetch_assoc($qry); // �������� ����� �������� �������
	
//--------properties------- //
// �������� ��������� �������� �������
foreach ($_POST as $key => $value) {
	if (in_array($key, $arr_no_update)){ continue;}
	$qry = mysql_query("select * from params where doctype in (0,{$doctree['id']}) 
											   and field_name = '{$key}' ",$conn);
	if (mysql_error()) {
		return '��������� ������ �� ����� ��������� ������. �������� �������� ���������';}
	$res_params = mysql_fetch_assoc($qry);
	if ($key =='url' && $_DOC_PARAMS[properties][url]<> $value) {
		$surl =  '1';
	}
		
	// ����������� ���� ���������� 
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

//���� �����
if (strtolower($_DOC_PARAMS['get_params'][0])=='folder'){
	$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "isDir";
	$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'1'";
}	

//����� ��������
/* �� ��������: ���� ��������� ����� � ���������, �� ����������� ��������, 
 * ���� ��������� � �������-�����, �� �������� = ��������
 * */ 
if ($_DOC_PARAMS['properties']['isDir']==1){
	$parent_dir =$_DOC_PARAMS['docid']; 
} else {
	$parent_dir = $_DOC_PARAMS['properties']['parent'];
}
if (!$parent_dir) {$parent_dir = 0;}

$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "parent";
$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'{$parent_dir}'";

//������ ����
$insert_str_key_dt.=(($insert_str_key_dt)?", ":""). "type";
$insert_str_value_dt.=(($insert_str_value_dt)?", ":""). "'{$_DOC_PARAMS['properties']['type']}'";

//----------properties----- \\
	
	
// ������ ������ � doctree:
if (!$insert_str_key_dt) { return '';} //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

mysql_query("insert into doctree (".$insert_str_key_dt. ") values(".$insert_str_value_dt. ") ",$conn); 
$qry_insert = mysql_query("SELECT LAST_INSERT_ID() AS last_id FROM doctree ",$conn);
// �������� id ������ ���������
$new_id = mysql_fetch_row($qry_insert);
if (mysql_error()) {
	return '��������� ������ �� ����� ���������� ������. ���������� � ���.��������� �����!'; } 
// ���������, ��� ���� url ���������. ���� ���, �� ������ ���� = id
if (!$url_param){
	mysql_query("update doctree set `url`=".$new_id[0]." where id = ".$new_id[0],$conn);
}

//----------files---------- //
foreach ($_FILES as $key => $value) {
	if ($value['error']==4) {
		continue;
	}else if ($value['error']!=0){
		return "������ �������� �����" ;
	}
	if (!is_uploaded_file($value['tmp_name'])) {
		return "������ �������� �����" ;
	}
	
	if($value["size"] > 1024*2*1024) {
    	return "������ ����� ��������� ��� ���������";}
	//����������� ��� ����� �� ����������
	$qry = mysql_query("select * from params where doctype in (0,{$doctree['id']}) 
										   and field_name = '{$key}' ",$conn);
	if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!';}
	$res = mysql_fetch_assoc($qry);
	// ����������� ���� ���������� 
	$f_true = '';
	switch ($res['field_type']){
		case 'img'	  : $f_true = getimagesize($value['tmp_name']); break;
		case 'file'	  : $f_true = filesize($value['tmp_name']) ;	break;
		default		  : $f_true = filesize($value['tmp_name']) ;	break;
	}
	if (!$f_true || !get_image_type($value['tmp_name'])) {
		return '��������� ������ �� ����� �������� �����';} 
	$ext = end(explode('.',$value['name']));
	if (!file_exists("uploads/{$doctree['tablename']}/") ) {
		mkdir("uploads/{$doctree['tablename']}/");
	}
	$fname = "uploads/{$doctree['tablename']}/".$new_id[0].'.'.$ext;
	//if (!move_uploaded_file($value["tmp_name"],$fname)){
	if (!resizeimg($value["tmp_name"],$fname,250,0,$_SERVER["HTTP_HOST"])){
		$errormsg = '��������� ������ �� ����� ���������� �����';
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
	return  '��������� ������ �� ����� ���������� ������. ���������� � ���.��������� �����!';}
//}
header("Location: ".GetTypePath($new_id[0],1));
exit;
}//end checknsave_new_data
	
if (isset ($_POST['edit_obj'])) {
//------------���� ����� ��������� ������ ------------------------------------------------//
$errormsg = checknsave_new_data();
//----���� ����� ��������� ������
}

$is_dir= strtolower($_DOC_PARAMS['get_params'][0])=='folder'?1:0;
//����������� ����� �������� ������� �� doctree
$qry = mysql_query("select dt.* from doctype dt where dt.name= '{$_DOC_PARAMS[type]}'",$conn);
if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
}
if (mysql_num_rows($qry)==1) { // ��� ������ � ������ ������
	$doctype = mysql_fetch_assoc($qry); // �������� ����� �������� �������

	$qry = mysql_query("select * from params  
		where doctype in (0,{$doctype['id']}) order by doctype ",$conn);
	if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
	} else {
	unset($pararray);
	while ($res = mysql_fetch_assoc($qry)) {
	  if (!$is_dir || !$res['doctype']){ //���� �� �����, �� ��� ���������, ���� �����, �� ������ ��������
				// ����������� ���� ���������� 
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
			$res['value'][2] = date("d"); // �������� ���������
		}
		$pararray ['docparams'][] = $res;
	  }}
	}
} else {
	$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
}
$pararray['errormsg']=$errormsg;
?>