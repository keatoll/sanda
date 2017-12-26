<?//require_once 'stddoc.php';?>
<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1><?=$_DOC_PARAMS['properties']['name']?></h1>
<div id = 'doc_params'>
<table>
<tr>
	<td id ='t1'>  
		<img src = "/<?=$_DOC_PARAMS['properties']['img']?>" alt="" />&nbsp;
	</td>
	<td id ='t2'>
		<div id = 'stddoc_value'><b id = 'stddoc_field'>Цена </b><?=$_DOC_PARAMS['properties']['price']?> рублей.</div>
		<div id = 'stddoc_value'><b id = 'stddoc_field'>Остаток </b><?if ($_DOC_PARAMS['properties']['amount']>10){?>есть в наличии<?}else{?><?=$_DOC_PARAMS['properties']['amount']?><?}?></div><br/>
  		<div id = 'child_button'>
  		<input style = "width:30px;" type="text" maxlength="5" id='drug' name='drug' value="1" />
  		<?if (isset($params['admin_orders'])) {?>
  		<select id = "admin_order">
  		<? foreach ($params['admin_orders'] as $key_order => $value_order ) {?>
  		<option value = '<?=$key_order?>'><?=$value_order?></option>
  		<?}?> 
  		</select>
  		<?}?>
  		<input id = 'to_cart' type="button" name = 'add[<?=$key?>]' title="В корзину" value=" " onclick = " location.href='<?=GetTypePath($_DOC_PARAMS['docid'])?>/addtocart/'+document.getElementById('drug').value+'/'+document.getElementById('admin_order').value;"/>
  		</div>
	</td>
</tr>
<tr>
	<td colspan=2>
		<div id = 'stddoc_value'><b id = 'stddoc_field'>Изготовитель </b><?=$_DOC_PARAMS['properties']['factory']?>, <?=$_DOC_PARAMS['properties']['country']?></div>
		<div id = 'stddoc_value'><?=$_DOC_PARAMS['properties']['description']?></div>
	</td>
</tr>
</table>
</div>