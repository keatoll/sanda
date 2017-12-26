<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=cp1251; no-cache" />
<title>ООО "САНДА-ФАРМ"</title>
<meta name="keywords" content="Владивосток аптека опт купить препараты инсулин диабет лечебное питание" />
<meta name="description" content="Компания ООО 'САНДА-ФАРМ'. Оптовый отдел, аптечная сеть социаьных цен, кабинет терапевта во Владивостоке" />
<link href="/styles.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="/jscripts/scripts.js"></script>
<script type="text/javascript" src="/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	plugins : "advhr,advimage,advlink,fullscreen,insertdatetime,paste,pagebreak,preview,print,table,searchreplace",
	// Theme options
	language: "ru",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,iespell,advhr,|,print,|,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true
});
</script>
</head>
<body <? //onload="allClose()" //процедура для начального открытия левого меню?> >
<!-- header begins -->
<div id = 'body'>
<div id="logo" >
	<a href="http://<?=$_SERVER["HTTP_HOST"]?>">
	<h1>ООО "САНДА-ФАРМ"</h1>
	<p id = "logo_desc">Оптовое направление&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Аптеки социальных цен&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Медицинские услуги</p>
	</a>
</div>
<div id="menu">
	<table><tr>
	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'О КОМПАНИИ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/about_us/rus" >О компании<br/>About us</a>
		</td>
	<?} else {?><td>О компании<br/>About us</td> <?}?>

	<?/* if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'НОВОСТИ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);">
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/news">Новости</a>
		</td>
	<?} else {?><td>Новости</td> <?}*/?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'ОПТОВЫЕ ПРОДАЖИ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/opt">Оптовые продажи</a>
		</td>
	<?} else {?><td>Оптовые продажи</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'КАБИНЕТ ТЕРАПЕВТА') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/doctor_info">Кабинет терапевта</a>
		</td>
	<?} else {?><td>Кабинет терапевта</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'НАШИ ТОВАРЫ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/price_list">Наши товары</a>
		</td>
	<?} else {?><td>Наши товары</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'ДИАБЕТИЧЕСКИЕ ПРЕПАРАТЫ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/diabet">Диабетические препараты</a>
		</td>
	<?} else {?><td>Диабетические препараты</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'СОЦИАЛЬНЫЕ ЦЕНЫ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/social">Социальные цены</a>
		</td>
	<?} else {?><td>Социальные цены</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'ЛЕЧЕБНОЕ ПИТАНИЕ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/nutricia">Лечебное питание</a>
		</td>
	<?} else {?><td>Лечебное питание</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'ИНДИВИДУАЛЬНЫЙ ЗАКАЗ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/individual">Индивидуальный заказ</a>
		</td>
	<?} else {?><td>Индивидуальный заказ</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= 'КОНТАКТЫ') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/contacts">Контакты</a>
		</td>
	<?} else {?><td>Контакты</td> <?}?>

	</tr></table>
</div>
<div id="ierarchy"> <?// меню дерева посещения?>
 <div id = 'path'><a href = 'http://<?=$_SERVER['HTTP_HOST']?><?=$_DOC_PARAMS['state']=='admin'?"/admin":'';?>'>Главная страница</a>
  <? if ($_DOC_PARAMS['dtree']) {
  		foreach ($_DOC_PARAMS['dtree'] as $key=>$arr){ ?>
  		&rarr;<?if ($_DOC_PARAMS['dtree'][$key+1] || $_DOC_PARAMS['methods'] ) {?><a href = '<?=GetTypePath($arr,$_DOC_PARAMS['state'])?>'><?}?><?=dt_params($arr,'name')?><?if ($_DOC_PARAMS['dtree'][$key+1]|| $_DOC_PARAMS['methods']) {?></a><?}?>	
  <?	}
  }if ($_DOC_PARAMS['methods']){?>&rarr;<?=$_DOC_PARAMS['method_label'];}?>
 </div>
 <?if ($_SESSION['user_rights']==1) {?>
 <div id = "login_h1"><?if ($_DOC_PARAMS[state]=='admin') {?>
   <a href = '<?=GetTypePath($_DOC_PARAMS[docid])?>'>Просмотр документа</a>
 				<?}else {?>
   <a href = '<?=GetTypePath($_DOC_PARAMS[docid],1)?>'>Редактирование документа</a>
   			 <?}?>
 </div> <?}?>
 </div>
<!-- header ends -->
<!-- content begins -->