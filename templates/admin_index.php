<h1>Разделы</h1>
<p>Вы находитесь в режиме администрирования сайта. Перейдите на интересующий раздел</p>
<? if (isset ($params[params])) {
	foreach ($params[params] as $param) { ?>
	<h2><a href = '/admin/<?=$param['url']?>'><?=$param['name']?></a></h2>	
<?	}
}?>
<h1>Функции</h1>
<p>Дополнительные функции администрирования</p>
<?/*
<a href = '/admin/adddoc/' >Создать новый раздел</a> <br/>
<a href = "/admin/manage_types"> Управление типами документов</a><br/>
</p>*/?>
<h2><a href = "/admin/manage_orders"> Управление заказами клиентов</a></h2>


