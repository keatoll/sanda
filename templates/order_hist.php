<?if (!isset($params['child_list'][0])) {?>
<h1>Ваш заказ от <?=$params['order_date']?></h1>
<div id = 'errormsg' ><?=$params['errormsg']?></div>
<?if (isset ($params['params'])){ ?>
<table id = 'current_order' >
<tr><th>Товар</th>
	<th>Количество, шт</th>
	<th>Цена, руб</th>
	<th>Сумма</th>
	<th id='t5'>&nbsp;</th>
</tr>
	<?foreach ($params['params'] as $key =>$drug ) {?>
<tr>	
		<td id='t1'><?=$drug['drug_name']?>&nbsp;</td>
		<td id='t2' ><?=$drug['amount']?>&nbsp;</td>
		<td id='t3' ><?=$drug['price']?>&nbsp;</td>
		<td id='t4' ><?=$drug['amount']*$drug['price']?>&nbsp;</td>
		<td id='t5' >&nbsp;</td>
</tr>
<?	}?>
<tr><td colspan = "3" id='t1' ><b>Общая сумма заказа</b></td>
	<td  id='t4'><b><?=$params['summ_order']?>&nbsp;</b></td>
	<td id='t5' >&nbsp;</td>
</tr>
<tr><td id='t5' colspan = "5"><input type = "checkbox" name ='with_sert' <?if ($params['with_sert']) {?>checked <?}?>disabled>Вложить сертификаты, подтверждающие качество товара</td></tr>
<tr><td id='t5' colspan = "5" >&nbsp;</td></tr>

</table>
<?} ?>

<span><a href = "/order_hist">Вернуться в список заказов</a></span>
<?} else  { draw_template ('order_hist_dir',$params); }?>