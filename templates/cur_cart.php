<div id = 'cur_cart'>
<h2>���� �������</h2>
<? if ($params['drug_amount'] >0) {?>
<p>� ��� ��������� ����� �������: <b><?=sprintf("%d",$params['drug_amount'])?></b> <br/>
 �� ����� ����� <b><?=sprintf("%s",$params['drug_summ'])?></b> ������. <br/>
<a href = "http://<?=$_SERVER['HTTP_HOST']?>/current_order"  target = 'blank'>���������</a>
</p>
<?} else {?>
<p>���� ������� �����.</p>
<?}?>
</div>