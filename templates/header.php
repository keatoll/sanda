<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=cp1251; no-cache" />
<title>��� "�����-����"</title>
<meta name="keywords" content="����������� ������ ��� ������ ��������� ������� ������ �������� �������" />
<meta name="description" content="�������� ��� '�����-����'. ������� �����, �������� ���� ��������� ���, ������� ��������� �� ������������" />
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
<body <? //onload="allClose()" //��������� ��� ���������� �������� ������ ����?> >
<!-- header begins -->
<div id = 'body'>
<div id="logo" >
	<a href="http://<?=$_SERVER["HTTP_HOST"]?>">
	<h1>��� "�����-����"</h1>
	<p id = "logo_desc">������� �����������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ ���������� ���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������� ������</p>
	</a>
</div>
<div id="menu">
	<table><tr>
	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '� ��������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/about_us/rus" >� ��������<br/>About us</a>
		</td>
	<?} else {?><td>� ��������<br/>About us</td> <?}?>

	<?/* if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '�������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);">
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/news">�������</a>
		</td>
	<?} else {?><td>�������</td> <?}*/?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '������� �������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/opt">������� �������</a>
		</td>
	<?} else {?><td>������� �������</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '������� ���������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/doctor_info">������� ���������</a>
		</td>
	<?} else {?><td>������� ���������</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '���� ������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/price_list">���� ������</a>
		</td>
	<?} else {?><td>���� ������</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '������������� ���������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/diabet">������������� ���������</a>
		</td>
	<?} else {?><td>������������� ���������</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '���������� ����') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/social">���������� ����</a>
		</td>
	<?} else {?><td>���������� ����</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '�������� �������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/nutricia">�������� �������</a>
		</td>
	<?} else {?><td>�������� �������</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '�������������� �����') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/individual">�������������� �����</a>
		</td>
	<?} else {?><td>�������������� �����</td> <?}?>

	<? if (strtoupper(dt_params($_DOC_PARAMS['dtree'][0],'name'))!= '��������') {?>
		<td style = "background-color:rgb(115,204,243<?//255,234,0?>);"
			onmouseover="style.backgroundColor = 'rgb(226,244,251)';" 
			onmouseout="style.backgroundColor = 'rgb(115,204,243)';"
			>
			<a href="http://<?=$_SERVER["HTTP_HOST"]?>/contacts">��������</a>
		</td>
	<?} else {?><td>��������</td> <?}?>

	</tr></table>
</div>
<div id="ierarchy"> <?// ���� ������ ���������?>
 <div id = 'path'><a href = 'http://<?=$_SERVER['HTTP_HOST']?><?=$_DOC_PARAMS['state']=='admin'?"/admin":'';?>'>������� ��������</a>
  <? if ($_DOC_PARAMS['dtree']) {
  		foreach ($_DOC_PARAMS['dtree'] as $key=>$arr){ ?>
  		&rarr;<?if ($_DOC_PARAMS['dtree'][$key+1] || $_DOC_PARAMS['methods'] ) {?><a href = '<?=GetTypePath($arr,$_DOC_PARAMS['state'])?>'><?}?><?=dt_params($arr,'name')?><?if ($_DOC_PARAMS['dtree'][$key+1]|| $_DOC_PARAMS['methods']) {?></a><?}?>	
  <?	}
  }if ($_DOC_PARAMS['methods']){?>&rarr;<?=$_DOC_PARAMS['method_label'];}?>
 </div>
 <?if ($_SESSION['user_rights']==1) {?>
 <div id = "login_h1"><?if ($_DOC_PARAMS[state]=='admin') {?>
   <a href = '<?=GetTypePath($_DOC_PARAMS[docid])?>'>�������� ���������</a>
 				<?}else {?>
   <a href = '<?=GetTypePath($_DOC_PARAMS[docid],1)?>'>�������������� ���������</a>
   			 <?}?>
 </div> <?}?>
 </div>
<!-- header ends -->
<!-- content begins -->