<? error_reporting(0);
setlocale(LC_ALL, 'ru_RU.CP1251');
//������������
include_once ('core/core.php');
$conn = get_connection(3);

// �������� ������ � ��������: �����, ���������
$_DOC_PARAMS = get_type();
//print_r($_DOC_PARAMS);
unset ($type_params);
$is_index = 0;
// ------------�������� ������---------------------- 
if (checkURLrights()==1) {
	if ($_DOC_PARAMS['methods']){
		$type_params = call_type($_DOC_PARAMS['methods']);
	} elseif ($_DOC_PARAMS['type']){
		if ($_DOC_PARAMS['state']=='admin' ) { // ���� �� � �������
				$type_params = call_type('editdoc');
		} else {// ���� �� � ����-����
			$type_params = call_type($_DOC_PARAMS['type']);
		}
	} elseif ($_DOC_PARAMS['state'] == 'admin') { //�� ��, �� ��. � ������� �������� ��������� ������� ��������
		$type_params['params']= call_type('admin_index'); 
	} else {
		$is_index = 1;
	}
	$type_params['login'] = call_type('login');
} else {
	$type_params = call_type('login');
	$type_params['login'] = $type_params;
}
$type_params['cur_cart'] = call_type('cur_cart');

// ----------������� ��������----------------------
// ------������ �����-------------------------
draw_template('header');
// ------������ ������ �/��� ����� ����
if ($_DOC_PARAMS['state']=='admin'){ 
	draw_template ('admin_left');
}
draw_template ('right',$type_params);

// ------������ �������� ����----------------
if ($_DOC_PARAMS['state']=='admin'){ 
	echo '<div id="main_admin">';
} else {
	echo '<div id="main">';
}

if (checkURLrights()==1) {
// ---�������� �����
	if (isset($_DOC_PARAMS['methods']) && 
		file_exists ('templates/'.$_DOC_PARAMS['methods'].'.php')) {
		draw_template($_DOC_PARAMS['methods'],$type_params);
// ---���� ���� ��� ���������
	}elseif ($_DOC_PARAMS['type']){
		if ($_DOC_PARAMS['state']!='admin') {// ���� �� �������
			//���� ����������
			if ($_DOC_PARAMS['properties']['isDir']) {
				if (file_exists ('templates/'.$_DOC_PARAMS['type'].'_dir.php')) {//���� ������
					draw_template($_DOC_PARAMS['type'].'_dir',$type_params);
				} else { // ����������� ������ ������ ����������
	   				draw_template ('stddoc_dir',$type_params);
				}
			// ���� ������
			}else {
				if (file_exists ('templates/'.$_DOC_PARAMS['type'].'.php')) {//���� ������
					draw_template($_DOC_PARAMS['type'],$type_params);
				} else {// ����������� ������ ������ ������
		   			draw_template ('stddoc',$type_params);
				}
			}
		} else {// ���� �������
			draw_template('editdoc',$type_params);
		}
// �������� ��������� ��� ������� �������� �������
	} elseif ($_DOC_PARAMS['state'] == 'admin') {
			draw_template ($_DOC_PARAMS['state'].'_index',$type_params);
// ������� ������� ��������� �����
	} elseif ($is_index==1) {
		draw_template ($_DOC_PARAMS['state'].'_index',$type_params);
	}
// ���� ���� ������������
} else {
	draw_template('login',$type_params);
}
echo '</div>';
// -----------������� ��� ����-----------------------
draw_template('footer');  
?>