<div id = 'errormsg' ><?=$params['errormsg']?></div>
<?if (!isset($params['child_list'][0])) {?>
<h1>����� � <?=$params['order']['id']?> �� <?=$params['order']['date']?></h1>
<?if (isset ($params['params'])){ ?>
<form id = 'manage_orders_form' method="POST" action="">
<table id = 'current_order' >
<tr><th>�����</th>
	<th>����������, ��</th>
	<th>����, ���</th>
	<th>�����</th>
	<th>&nbsp;</th>
	
</tr>
	<?foreach ($params['params'] as $key =>$drug ) {?>
<tr>	
		<td id='t1'><?=$drug['drug_name']?>&nbsp;</td>
		<td id='t2' ><input type = "text" name = 'cur_order_input[<?=$drug['drug_id']?>]' value ="<?=$drug['amount']?>" <?//onChange="change_s()"?>></td>
		<td id='t3' ><?=$drug['price']?>&nbsp;</td>
		<td id='t4' ><?=$drug['amount']*$drug['price']?>&nbsp;</td>
		<td id='t5'><input id = "delete_file_btn_small"  type ="submit" name = 'cur_order_delete[<?=$drug['drug_id']?>]' value="&nbsp;&nbsp;" alt="�������" title = "�������" ></td>
</tr>
<?	}?>
<tr><td colspan = "3"><b>����� ����� ������</b></td>
	<td  id='t4'><b><?=$params['summ_order']?>&nbsp;</b></td>
	<td  id='t5' ><input type ="submit" name = 'recount' value = '�����������'></td></tr>
<tr><td colspan = "5"><input type ="submit" name = 'cancel' value = '������������'> 
						<input type ="submit" name = 'send' value = '����������'>
	</td></tr>
</table>
</form>
<?} ?>

<span><a href = "/order_hist">��������� � ������ �������</a></span>
<?} else  { draw_template ('manage_orders_dir',$params); }?>