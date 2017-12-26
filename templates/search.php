<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1>Поиск в разделе <?=$_DOC_PARAMS['properties']['name']?> </h1>
<?draw_template('search_panel',$params['alph_search']);?>
 <p  id = 'stddoc_value'>
	По запросу <b><?=$params['query_str']?></b> найдено: </p><br/>
 <?  if (($params['child_list'][0]) && $params['child_list'][max_page]>1) {?> 
<div class='pager'><b>Страницы:</b> &nbsp; <?/*  pager  */?>
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
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]-1){?>... &nbsp;<?}?>
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]){?>
	<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=<?=$params['child_list'][max_page]?>' ><?=$params['child_list'][max_page]?></a>&nbsp;
<?}?>
</div>
<?}?>

 <? if (isset($params['child_list'][0])) { /*вывод всех дочерних документов*/?>
 <? foreach ($params['child_list'] as $key =>$item ) {
    if (is_numeric($key)){
       	$parent_url = GetTypePath($_DOC_PARAMS['docid']);?>
<div id = 'child_list'>
  	<span id = 'child_name'><a href='<?=$parent_url.$item['url']?>'><?=$item['name']?></a></span><br/>
  	<span id = 'child_descr'><a href='<?=$parent_url.$item['url']?>'>
  	<?if ($_DOC_PARAMS['type']=='drugs'){?>
  		<?$drugs_dt = get_doc_params($item['id'],'factory,country','drugs');?>
       	<?=$drugs_dt['factory']?>, <?=$drugs_dt['country']?><br/>
    <?}?>
       	<?=substr(strip_tags($item['description']),0,128)?><?if (strip_tags($item['description'])>128){?>...<?}?></a>
    </span><br/>
  	<span id = 'child_button'>
  		<input  type="text" maxlength="5" id='drug_<?=$key?>' value="1" />
  		<?if (isset($params['admin_orders'])) {?>
  		<select id = "admin_order_<?=$key?>">
  		<? foreach ($params['admin_orders'] as $key_order => $value_order ) {?>
  		<option value = '<?=$key_order?>'><?=$value_order?></option>
  		<?}?> 
  		</select>
  		<?}?>
  		<input style = "width:80px;" type="button" name = 'add[<?=$key?>]' value="В корзину" onclick = " location.href='<?=$item['url']?>/addtocart/'+document.getElementById('drug_<?=$key?>').value+'/'+document.getElementById('admin_order_<?=$key?>').value;"/>
  	</span>
</div>
 <?}}}?>

 <?  if (($params['child_list'][0]) && $params['child_list'][max_page]>1) {?> 
<div class='pager'><b>Страницы:</b> &nbsp;  <?/*  pager  */?>
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