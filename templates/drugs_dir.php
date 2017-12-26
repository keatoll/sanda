<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1><?=$_DOC_PARAMS['properties']['name']?></h1>
 <span  id = 'stddoc_value'>
	 <?if ($_DOC_PARAMS['properties']['img']){?><img src = "/<?=$_DOC_PARAMS['properties']['img']?>" alt=""/>&nbsp; <?}?>
	 <?=$_DOC_PARAMS['properties']['description']?></span><br/>
<? draw_template('search_panel',$params['alph_search']);?>

 <? if (($params['child_list'][0]) && $params['child_list'][max_page]>1) {?> 
<div class='pager'><b>Страницы:</b> &nbsp;  <?/*  pager  */?>
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

 <?/*вывод всех дочерних документов*/ ?>
 <? if (($params['child_list'][0])) {?> 
 <?$parent_url = GetTypePath($_DOC_PARAMS['docid']);?>
 <? foreach ($params['child_list'] as $key =>$item ) {
    if (is_numeric($key)){?> 
<div id = 'child_list'>
<table >
<tr> <td id='t3'>
  	<div id = 'child_name'><a href='<?=$parent_url.$item['url']?>'><?=$item['name']?></a></div>
  	<?if($item['img']){?><br/><div id = 'child_img'><a href='<?=$parent_url.$item['url']?>'><img src = "/<?=$item['img']?>" alt=""/></a></div><?}?>
</td><td id='t4'>
  	<?if($item['description']){?><br/><div id = 'child_descr'><a href='<?=$parent_url.$item['url']?>'>
  		<?$drugs_dt = get_doc_params($item['id'],'factory,country','drugs');?>
       	<?=$drugs_dt['factory']?>, <?=$drugs_dt['country']?><br/>
  		<?=substr(strip_tags($item['description']),0,128)?><?if (strip_tags($item['description'])>128){?>...<?}?>
	</a></div>	<?}?>
  	<div id = 'child_button'>
  		<input  type="text" maxlength="5" id='drug_<?=$key?>' name='drug_<?=$key?>' value="1" />
  		<?if ($params['admin_orders']) {?>
  		<select id = "admin_order_<?=$key?>">
  		<? foreach ($params['admin_orders'] as $key_order => $value_order ) {?>
  		<option value = '<?=$key_order?>'><?=$value_order?></option>
  		<?}?> 
  		</select>
  		<?}?>
  		<input type="button" id = 'to_cart' name = 'add[<?=$key?>]' value="   " title='В корзину' onclick = "location.href='<?=$parent_url.$item['url']?>/addtocart/'+document.getElementById('drug_<?=$key?>').value+'/'<?if ($params['admin_orders']) {?>+document.getElementById('admin_order_<?=$key?>').value<?}?>;"/>
  	</div>
 </td>
 </tr></table>
</div>
 <?}}}?>

<? if (($params['child_list'][0]) && $params['child_list'][max_page]>1) {?> 
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