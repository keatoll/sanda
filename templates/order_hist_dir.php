<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1>История заказов</h1>
 <?  if (($params['child_list'][0]) && $params['child_list'][max_page]>1) { //  pager  ?>
<div class='pager'><b>Страницы:</b> &nbsp;  
<? $border= 4;
if (max(1,$params['child_list'][page]-$border) > 1){?>
	<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=1' >1</a>&nbsp;
<?}?>
<?if (max(1,$params['child_list'][page]-$border) > 2){?>	...&nbsp;<?}?>
<? for ($i=max(1,$params['child_list'][page]-$border); $i<=min($params['child_list'][max_page],$params['child_list'][page]+$border); $i++ )
	{
		if ($i != $params['child_list'][page]){?>
		<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=<?=$i?>' ><?=$i?></a>&nbsp;
		<?}	else { ?>
		    <?=$i?> &nbsp;
		<?}
	}?>
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]-1){?>	...&nbsp;<?}?>
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]){?>
	<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=<?=$params['child_list'][max_page]?>' ><?=$params['child_list'][max_page]?></a>&nbsp;
<?}?>
</div>
<?}?>

 <? if (isset($params['child_list'][0])) { /*вывод всех дочерних документов*/?>
 <? foreach ($params['child_list'] as $key =>$item ) {
    if (is_numeric($key)){?> 
<div id = 'child_list'>
  	<span id = 'child_name'><a href='/order_hist/<?=$item['id']?>'>Заказ №<?=$item['id']?> от <?=substr($item['date'],0,10)?> на сумму <?=$item['summ']?></a></span><?if (!$item['state']){?><span id ='order_state'>&nbsp;В сборке</span><?}?><br/>
</div>
 <?}}}?>
 <?  if (($params['child_list'][0]) && $params['child_list'][max_page]>1) { //  pager  ?>
<div class='pager'><b>Страницы:</b> &nbsp;  
<? $border= 4;
if (max(1,$params['child_list'][page]-$border) > 1){?>
	<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=1' >1</a>&nbsp;
<?}?>
<?if (max(1,$params['child_list'][page]-$border) > 2){?> ...&nbsp;<?}?>
<? for ($i=max(1,$params['child_list'][page]-$border); $i<=min($params['child_list'][max_page],$params['child_list'][page]+$border); $i++ )
	{
		if ($i != $params['child_list'][page]){?>
		<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=<?=$i?>' ><?=$i?></a>&nbsp;
		<?}	else { ?>
		    <?=$i?> &nbsp;
		<?}
	}?>
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]-1){?>	...&nbsp;<?}?>
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]){?>
	<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=<?=$params['child_list'][max_page]?>' ><?=$params['child_list'][max_page]?></a>&nbsp;
<?}?>
</div>
<?}?>