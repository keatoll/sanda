<h1>��� ������� �����</h1>
<div id = 'errormsg' ><?=$params['errormsg']?></div>
<?if (isset ($params['params'])){ ?>
<p>�������� ��������! ���� �� ������ � �� ������� �� ������ ����� ���������� �� ������ �� �����!</p>
<form id = 'cur_order_form' method="POST" action="">
<table id = 'current_order' >
<tr><th>�����</th>
	<th>����������, ��</th>
	<th>����, ���</th>
	<th>�����</th>
	<th id='t5'>&nbsp;</th>
</tr>
	<?foreach ($params['params'] as $key =>$drug ) {?>
<tr>	
		<td id='t1'><?=$drug['name']?>&nbsp;</td>
		<td id='t2'><input type = "text" name = 'cur_order_input[<?=$key?>]' value ="<?=$drug['amount']?>" <?//onChange="change_s()"?>></td>
		<td id='t3'><?=$drug['price']?>&nbsp;</td>
		<td id='t4'><?=$drug['summ']?>&nbsp;</td>
		<td id='t5'><input id = "delete_file_btn_small"  type ="submit" name = 'cur_order_delete[<?=$key?>]' value="&nbsp;&nbsp;" alt="�������" title = "�������" ></td>
</tr>
<?	}?>
<tr><td id='t1' colspan = "3"><b>����� ����� ������</b></td>
	<td  id='t4'><b><?=$params['summ_order']?></b></td>
	<td  id='t5' ><input type ="submit" name = 'recount' value = '�����������'></td></tr>
<tr><td id='t5' colspan = "5"><input type = "checkbox" name ='with_sert' <?if ($_SESSION['with_sert']){?>checked<?}?>>������� �����������, �������������� �������� ������*</td></tr>
<tr><td id='t5' colspan = "4" align="right"><input type ="submit" name = 'cancel' value = '������������'> 
						<input type ="submit" name = 'send' value = '����������'>
	</td><td id='t5'>&nbsp;</td></tr>
</table>
</form>
<p>* �������� ���, ��� ������ ���� ������� ������ � �����������, ��������������� �� ����� ������������� �������� �������, � �� �������� �� � ����� ������.</p>
<?} ?>
