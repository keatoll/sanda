<?
function checknsave_edit_data () {
	global $conn,$_DOC_PARAMS;	
$update_str = '';
$update_str_dt = '';
$surl = '';
$arr_no_update = array('id','edit_obj','MAX_FILE_SIZE','delete_file');

//�������� id ���������
$docid = mysql_real_escape_string((integer)$_POST['id']);
$errormsg = ''; 
$qry = mysql_query("select dt.tablename dttbl, d.type, d.id ".
				" from doctree d, doctype dt".
				" where d.id = '$docid' and d.type = dt.id ",$conn);
if (mysql_error()) {
	return '��������� ������ �� ����� ��������� ������! ���������� � ���.��������� �����.'; }
if (mysql_num_rows($qry)!=1) { // ��� ������ � ������ ������
	return '��������� ������ �� ����� ��������� ������. �������� �������� ���������'; }
	
$doctree = mysql_fetch_assoc($qry); // �������� ����� �������� �������
$qry = mysql_query("select * from {$doctree['dttbl']} where id = {$docid} ",$conn);
if (mysql_error()) {
	return  '��������� ������ �� ����� ��������� ������! ���������� � ���.��������� �����.'; }
$obj_values = mysql_fetch_assoc($qry); // ����������� �� ������ ������� (�� ����) ��� ��������� ���������

//--------properties------- //
// �������� ��������� �������� �������
foreach ($_POST as $key => $value) {
	if (in_array($key, $arr_no_update)){ continue;}
	
	$qry = mysql_query("select * from params where doctype in (0,{$doctree['type']}) 
												   and field_name = '{$key}' ",$conn);
	if (mysql_error()) {
		return '��������� ������ �� ����� ��������� ������. �������� �������� ���������';}
	$res_params = mysql_fetch_assoc($qry);
	if ($key =='url' && $_DOC_PARAMS[properties][url]<> $value) {
		$surl =  '1';}
		
	// ����������� ���� ���������� 
	$param_value = '';
	switch ($res_params['field_type']){
		//case 'id'	  : $param_value = (int)$value;	break;
		case 'yn'	  : $param_value = ((int)$value==1)?1:0; break;
		case 'text'   : if (!$value && $res_params['field_name']=='url' && !$res_params['doctype']){
							$value = $docid;
						} 
						$param_value = mysql_real_escape_string(stripcslashes($value)); break;
		case 'date'	  : $param_value = (checkdate(substr($value[1],0,2), substr($value[0],0,2), substr($value[2],0,4)))? 
			 				substr($value[2],0,4).'-'.substr($value[1],0,2).'-'.substr($value[0],0,2):date("Y").'-'.date("m").'-'.date("d");
							break;
		case 'int'	  : $param_value = (int)$value;		break;
		case 'num'	  : $param_value = (float)$value;	break;
		// varchar+ : 
		default: if ($res_params['doctype']){
					$qry_params = mysql_query("select {$res_params['field_name']} from {$doctree['dttbl']} where {$res_params['field_name']} IS NOT null limit 1 ",$conn);
				 $param_value = mysql_real_escape_string(substr($value,0,mysql_field_len($qry_params,0)));	
				}else {
					$qry_params = mysql_query("select {$res_params['field_name']} from doctree where {$res_params['field_name']} IS NOT null limit 1 ",$conn);
				 $param_value = mysql_real_escape_string(substr($value,0,mysql_field_len($qry_params,0)));	
				}
				break;
	}
	if ($res_params['not_blank']==1 && !$param_value) {
		return '������! ���� '.$res_params['field_label'].' �� ��������� ��� ����� �������� ������!';	}
	if ($res_params['doctype']!='0'){
		$update_str.=(($update_str)?", ":""). "`$key` = '$param_value'";
	}else {
		$update_str_dt.= (($update_str_dt)?", ":""). "`$key` = '$param_value'";
	}
}
//----------properties----- \\

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
	$qry = mysql_query("select * from params where doctype in (0,{$doctree['type']}) 
										   and field_name = '{$key}' ",$conn);
	if (mysql_error()) {
		return '��������� ������ �� ����� ��������� ������! ���������� � ���.��������� �����.'; }
	
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
	if (!file_exists("uploads/{$doctree['dttbl']}/") ) {
		mkdir("uploads/{$doctree['dttbl']}/");
	}
	$fname = "uploads/{$doctree['dttbl']}/".$doctree['id'].'.'.$ext;
	if (!resizeimg($value["tmp_name"],$fname,250,0,$_SERVER["HTTP_HOST"])){/*move_uploaded_file($fname_1,$fname)*/
		return '��������� ������ �� ����� ���������� �����';}
	if ($res['doctype']){
		$update_str.= (($update_str)?", ":""). "`$key` = '$fname'";
	}else {
		$update_str_dt.= (($update_str_dt)?", ":""). "`$key` = '$fname'";
	}
}
//----------files---------- \\
	
// ���������� ��� ���������:
if ($update_str) {
	$update_str = "update {$doctree['dttbl']} set ".$update_str. " where `id` = {$doctree['id']} "; 
	$qry_edit = mysql_query($update_str,$conn);
	if (mysql_error()) {
		return '��������� ������ �� ����� ���������� ������. ���������� � ���.��������� �����.'; }
}
if ($update_str_dt) {
	$update_str_dt = "update doctree set ".$update_str_dt. " where `id` = {$doctree['id']} "; 
	$qry_edit = mysql_query($update_str_dt,$conn);
	if (mysql_error()) {
		return '��������� ������ �� ����� ���������� ������. ���������� � ���.��������� �����.';}
}
if ($surl) {
	header("Location: ".GetTypePath($doctree['id'],1));
	exit;
}
return '';	
}// end checknsave_edit_data


$errormsg = ''; 
if (isset ($_GET['d_file'])){
//------------���� ����� ������� �������� --------------------------------------------------//
	//�������� id ���������
//doc_delete_img;
	$docid = mysql_real_escape_string((integer)$_GET['did']);
$errormsg = ''; 
$qry = mysql_query("select dt.tablename dttbl, d.type, d.id ".
				" from doctree d, doctype dt".
				" where d.id = '$docid' and d.type = dt.id ",$conn);
if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
}
if (mysql_num_rows($qry)==1) { // ��� ������ � ������ ������
	$doctree = mysql_fetch_assoc($qry); // �������� ����� �������� �������
	$qry = mysql_query("select * from {$doctree['dttbl']}  
		where id = {$doctree['id']} ",$conn);
	$obj_values = mysql_fetch_assoc($qry); // ����������� �� ������ ������� (�� ����) ��� ��������� ���������
	
	if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
	} else {  
		$indx = htmlspecialchars(substr($_GET['d_file'],0,20));
		$qry = mysql_query("update {$doctree['dttbl']} set `".
						mysql_real_escape_string(htmlspecialchars(substr($_GET['d_file'],0,20))).
						"` = '' where id = {$doctree['id']} ",$conn);
		if (mysql_error()) {
			$errormsg = '��������� ������ ��� �������� �����. ���������� � ���.��������� �����!';
		} else {
			if (file_exists ($obj_values[$indx])) {unlink($obj_values[$indx]);}
		}
	}
	header("Location: ".GetTypePath($_DOC_PARAMS[docid],1));
	exit;
	
} else {
	$errormsg ='�� ������ ������'; 
}//----���� ����� ������� ��������


} elseif ($_POST['edit_obj'] ) {
	$errormsg = checknsave_edit_data ();
}
//------------���� ����� ������� ������  ------------------------------------------------//
$Cid = (integer)$_DOC_PARAMS[docid];
//����������� ����� �������� ������� �� doctree
$qry = mysql_query("select dt.tablename dttbl, dt.id  dtid, d.* ".
				" from doctree d, doctype dt".
				" where d.id = '$Cid' and d.type = dt.id ",$conn);
if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
}

if (mysql_num_rows($qry)==1 ) { // ��� ������ � ������ ������
$doctree = mysql_fetch_assoc($qry); // �������� ����� �������� �������
	if ($doctree['isDir']!= 1 ) { //���� ������ - �� ����� � �������� �������� �� ��. ������
	$qry = mysql_query("select * from {$doctree['dttbl']}  
		where id = {$doctree['id']} ",$conn);
	$obj_values = mysql_fetch_assoc($qry); // ����������� �� ������ ������� (�� ����) ��� ��������� ���������
  }
	$qry = mysql_query("select * from params  
		where doctype in (0,{$doctree['type']}) order by doctype ",$conn);
	if (mysql_error()) {
		$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
	}
	unset($pararray);
	while ($res = mysql_fetch_assoc($qry)) {
	  if (!$doctree['isDir'] || !$res['doctype']){ //���� �� �����, �� ��� ���������, ���� �����, �� ������ ��������
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
		if ($res['doctype']==='0') {
			($res['field_type']== 'date')? $res['value'] = explode('-',substr($doctree[$res['field_name']],0,10)):
									   $res['value'] = $doctree[$res['field_name']]; // �������� ���������
		} else { 
			($res['field_type']== 'date')? $res['value'] = explode('-',substr($obj_values[$res['field_name']],0,10)):
									   $res['value'] = $obj_values[$res['field_name']]; // �������� ���������
		}
		$pararray ['docparams'][] = $res;
	  }
	}
	
  //����� ���� �������� ���������, ���� ��� ��������� - ����� 
  if ($doctree['isDir']== 1 ){ 
  	$c_page = $_GET[page];
  	$cur_page = (int)$c_page;
  	if (!$cur_page) {$cur_page=1;}
 	$res = mysql_query("select count(id) from doctree dt where dt.parent = ".$doctree['id'],$conn);
	$req_count = mysql_fetch_row($res);
  	$limit=10;
	$page=min(round($req_count[0]/$limit),$cur_page);
	$page= max(1,$page);
	$from=($page-1)*$limit;
	//$to=min($req_count[0],$from+$limit);
  	$maxpage = max(round($req_count[0]/$limit),1);
  	
  	$doc_child = mysql_query(" select * from doctree dt ". 
							 " where dt.parent = {$doctree['id']} limit $from, $limit",$conn);
	if (!mysql_error()) {// ��� ������ � ������ ������
		while ($row = mysql_fetch_assoc($doc_child)) {
			$pararray['child_list'][] = $row;
		}
		$pararray['child_list'][page]=$page;
		$pararray['child_list'][max_page]=$maxpage;
	}else{ $errormsg = '��������� ������ ��� ��������� ������. ���������� � ���.��������� �����!'; }
  }
} else {
	$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
}
	unset ($obj_values);
$pararray['errormsg']=$errormsg;
?>