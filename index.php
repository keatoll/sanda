<? error_reporting(0);
setlocale(LC_ALL, 'ru_RU.CP1251');
//подключаемся
include_once ('core/core.php');
$conn = get_connection(3);

// получаем данные о странице: адрес, параметры
$_DOC_PARAMS = get_type();
//print_r($_DOC_PARAMS);
unset ($type_params);
$is_index = 0;
// ------------вызываем методы---------------------- 
if (checkURLrights()==1) {
	if ($_DOC_PARAMS['methods']){
		$type_params = call_type($_DOC_PARAMS['methods']);
	} elseif ($_DOC_PARAMS['type']){
		if ($_DOC_PARAMS['state']=='admin' ) { // если мы в админке
				$type_params = call_type('editdoc');
		} else {// если мы в юзер-моде
			$type_params = call_type($_DOC_PARAMS['type']);
		}
	} elseif ($_DOC_PARAMS['state'] == 'admin') { //ни то, ни се. в админке получаем параметры главной страницы
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

// ----------выводим страницу----------------------
// ------рисуем шапку-------------------------
draw_template('header');
// ------рисуем правое и/или левое меню
if ($_DOC_PARAMS['state']=='admin'){ 
	draw_template ('admin_left');
}
draw_template ('right',$type_params);

// ------рисуем основное окно----------------
if ($_DOC_PARAMS['state']=='admin'){ 
	echo '<div id="main_admin">';
} else {
	echo '<div id="main">';
}

if (checkURLrights()==1) {
// ---вызываем метод
	if (isset($_DOC_PARAMS['methods']) && 
		file_exists ('templates/'.$_DOC_PARAMS['methods'].'.php')) {
		draw_template($_DOC_PARAMS['methods'],$type_params);
// ---если есть тип документа
	}elseif ($_DOC_PARAMS['type']){
		if ($_DOC_PARAMS['state']!='admin') {// если не админка
			//если директория
			if ($_DOC_PARAMS['properties']['isDir']) {
				if (file_exists ('templates/'.$_DOC_PARAMS['type'].'_dir.php')) {//спец шаблон
					draw_template($_DOC_PARAMS['type'].'_dir',$type_params);
				} else { // стандартный шаблон вывода директории
	   				draw_template ('stddoc_dir',$type_params);
				}
			// если объект
			}else {
				if (file_exists ('templates/'.$_DOC_PARAMS['type'].'.php')) {//спец шаблон
					draw_template($_DOC_PARAMS['type'],$type_params);
				} else {// стандартный шаблон вывода данных
		   			draw_template ('stddoc',$type_params);
				}
			}
		} else {// если админка
			draw_template('editdoc',$type_params);
		}
// получаем параметры для главной страницы админки
	} elseif ($_DOC_PARAMS['state'] == 'admin') {
			draw_template ($_DOC_PARAMS['state'].'_index',$type_params);
// выводим главную основного сайта
	} elseif ($is_index==1) {
		draw_template ($_DOC_PARAMS['state'].'_index',$type_params);
	}
// если прав недостаточно
} else {
	draw_template('login',$type_params);
}
echo '</div>';
// -----------выводим низ окна-----------------------
draw_template('footer');  
?>