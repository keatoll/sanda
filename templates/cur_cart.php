<div id = 'cur_cart'>
<h2>Ваша корзина</h2>
<? if ($params['drug_amount'] >0) {?>
<p>У вас выбранных видов товаров: <b><?=sprintf("%d",$params['drug_amount'])?></b> <br/>
 на общую сумму <b><?=sprintf("%s",$params['drug_summ'])?></b> рублей. <br/>
<a href = "http://<?=$_SERVER['HTTP_HOST']?>/current_order"  target = 'blank'>Подробнее</a>
</p>
<?} else {?>
<p>Ваша корзина пуста.</p>
<?}?>
</div>