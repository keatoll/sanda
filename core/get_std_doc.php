<?
$qry = mysql_query("select * from params  
		where doctype in (0, ".
						"(select id from doctype where name = '{$_DOC_PARAMS['type']}')".
						") and in_view = 1 order by field_type  ",$conn);
if (mysql_error()) {
	$errormsg = '��������� ������ �� ����� ��������� ������. ���������� � ���.��������� �����!'; 
}
unset($pararray);
while ($res = mysql_fetch_assoc($qry)) {
  if (!$_DOC_PARAMS['properties']['isDir'] || !$res['doctype']){ //���� �� �����, �� ��� ���������, ���� �����, �� ������ ��������
	// ����������� ���� ���������� 
	switch ($res['field_type']){
		case 'id'	  : $res['input_type'] = 'hidden';	break;
		case 'yn'	  : $res['input_type'] = 'checkbox';break;
		case 'img'	  : $res['input_type'] = 'file';	break;
		case 'text'   : $res['input_type'] = 'textarea';break;
	/*	case 'date'	  : $res['input_type'] = 'text';	break;
		case 'varchar': $res['input_type'] = 'text';	break;
		case 'int'	  : $res['input_type'] = 'text';	break;
		case 'num'	  : $res['input_type'] = 'text';	break;
		*/default		  : $res['input_type'] = 'text';	break;
	}
	if ($res['field_type']== 'date') {
		$res['value'] = explode('-',substr($_DOC_PARAMS['properties'][$res['field_name']],0,10));
		if (!checkdate($res['value'][1], $res['value'][2], $res['value'][0])){
			unset($res['value']);
		}
		$pararray ['docparams'][] = $res;
	}else {
		$res['value'] = $_DOC_PARAMS['properties'][$res['field_name']]; // �������� ���������
		if (trim($res['value']) !='' && $res['field_name'] !='name' ){	
			$pararray ['docparams'][] = $res;
		}
	}
  }
}
?>
