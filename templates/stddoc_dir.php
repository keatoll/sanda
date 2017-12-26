<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1><?=$_DOC_PARAMS['properties']['name']?></h1>
 <span  id = 'stddoc_value'>
	 <?if ($_DOC_PARAMS['properties']['img']){?><img src = "/<?=$_DOC_PARAMS['properties']['img']?>" alt=""/>&nbsp; <?}?>
	 <?=$_DOC_PARAMS['properties']['description']?></span><br/>
 <?/* if (isset($params['docparams'])){
foreach ($params['docparams'] as $key => $value) { ?>
<span  id = 'stddoc_value'>
	<?if ($value['field_type'] == 'date') { //дата?>
	<?=$value['value'][2]?>.<?=$value['value'][1]?>.<?=$value['value'][0]?> </span><br/>
	
	<? } elseif ($value['field_type'] != 'text') { //не текст ?>
		<?=$value['value']?>
		<?if ($value['input_type']=='file'){ ?>
			<img src = "/<?=$value['value']?>" alt=""/>&nbsp; </span>
		<?}?>
	<?} elseif ($value['field_type'] != 'hidden')  {?>
		</span><br/>
		<?=$value['value']?>
	<?}?>
 <?} }*/?>

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
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]-1){?>	...&nbsp;<?}?>
<?if (min($params['child_list'][max_page],$params['child_list'][page]+$border) < $params['child_list'][max_page]){?>
	<a href='<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&page=<?=$params['child_list'][max_page]?>' ><?=$params['child_list'][max_page]?></a>&nbsp;
<?}?>
</div>
<?}?>
 <? if (isset($params['child_list'][0])) { /*вывод всех дочерних документов*/?>
 <?$parent_url = GetTypePath($_DOC_PARAMS['docid']);?>
 <? foreach ($params['child_list'] as $key =>$item ) {
    if (is_numeric($key)){?> 
<div id = 'child_list'>
  	<h2><a href='<?=$parent_url.$item['url']?>'><?=$item['name']?></a></h2>
  	<span id = 'child_descr'><a href='<?=$parent_url.$item['url']?>'><?=substr(strip_tags($item['description']),0,128)?></a></span><br/>
</div>
 <?}}}?>
<? if (isset($params['child_list'][0]) && $params['child_list'][max_page]>1) {?> 
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