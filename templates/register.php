<div id = register>
<H1>����������� ������ ������������.</H1>
<div id = 'errormsg' ><?=$params['errormsg']?></div>
<?if (!$_DOC_PARAMS[get_params][0] && !$_POST['submit']) {?><p>���������, ����������, ����.</p> <?}?>
<form id = 'auth_form' action="" method="post">
<? if (!$params['check_email']) {?>
    <div id = 'edit_user_field'>�������*</div>
    <div id = "edit_user_value"><input type="text" name="last_name" maxlength = '15' value = '<?=$params[params]['last_name']?>'/></div>
    <div id = 'edit_user_field'>���*<br/></div>
    <div id = "edit_user_value"><input type="text" name="first_name" maxlength = '15' value = '<?=$params[params]['first_name']?>'/></div>
    <div id = 'edit_user_field'>��������*<br/></div>
    <div id = "edit_user_value"><input type="text" name="second_name" maxlength = '15' value = '<?=$params[params]['second_name']?>'/></div>
    <div id = 'edit_user_field'>���� ��������</div>
    <div id = "edit_user_value">	����<input type = "text" name = "birth_date[0]" value="<?=$params[params]['birth_date'][0]?>" size =2 MAXLENGTH =2>
							���<input type = "text" name = "birth_date[1]" value="<?=$params[params]['birth_date'][1]?>" size =2 MAXLENGTH =2>
							���<input type = "text" name = "birth_date[2]" value="<?=$params[params]['birth_date'][2]?>" size =4 MAXLENGTH =4>
	</div>
	<div id = "edit_user_value">
		���������� ����<input type="radio" name="user_type" value = '1' <?if ($params[params]['user_type']!=2){?>checked<?}?> onchange="document.getElementById('edit_user_UL').style.display='none';">
		����������� ����<input type="radio" name="user_type" value = '2' <?if ($params[params]['user_type']==2){?>checked<?}?> onchange="document.getElementById('edit_user_UL').style.display='block';">
	</div>
    
	<div id = 'edit_user_UL' style="display:<?if ($params[params]['user_type']!=2){?>none<?}else {?>block<?}?>">
	    <div id = 'edit_user_field'>�������� �����������*</div>
	    <div id = "edit_user_value"><input type="text" name="org_name" maxlength = '128' value = '<?=$params[params]['org_name']?>'/></div>
	    <div id = 'edit_user_field'>���*</div>
	    <div id = "edit_user_value"><input type="text" name="inn" maxlength = '15' value = '<?=$params[params]['inn']?>'/></div>
	    <div id = 'edit_user_field'>���*</div>
	    <div id = "edit_user_value"><input type="text" name="kpp" maxlength = '10' value = '<?=$params[params]['kpp']?>'/></div>
	    <div id = 'edit_user_field'>��������� ����*</div>
	    <div id = "edit_user_value"><input type="text" name="user_acct" maxlength = '20' value = '<?=$params[params]['user_acct']?>'/></div>
	    <div id = 'edit_user_field'>� �����*</div>
	    <div id = "edit_user_value"><input type="text" name="bank_name" maxlength = '128' value = '<?=$params[params]['bank_name']?>'/></div>
	    <div id = 'edit_user_field'>��� �����*</div>
	    <div id = "edit_user_value"><input type="text" name="bik" maxlength = '10' value = '<?=$params[params]['bik']?>'/></div>
	    <div id = 'edit_user_field'>����������������� ���� �����*</div>
	    <div id = "edit_user_value"><input type="text" name="bank_acct" maxlength = '20' value = '<?=$params[params]['bank_acct']?>'/></div>
	</div>
	
	<div id = 'edit_user_field'>email*</div>
	<div id = 'edit_user_explain'>����� ������ ���� ������������. �� ����� �������������� ��� �������� ������������� � �����������.</div>
    <div id = "edit_user_value"><input type="text" name="email" maxlength = '20' value = '<?=$params[params]['email']?>'/></div>
    <div id = 'edit_user_field'>�������</div>
    <div id = "edit_user_value"><input type="text" name="phone" maxlength = '20' value = '<?=$params[params]['phone']?>'/></div>
    <div id = 'edit_user_field'>����� ������*</div>
    <div id = 'edit_user_explain'>����� ������ ���� �� 6 �� 20 ��������. ����� ��������� ������ �����, �����, ����� � ���� �������������</div>
    <div id = "edit_user_value"><input type="password" name="password" maxlength = '30' value = '<?=$params[params]['password']?>'/></div>
    <div id = 'edit_user_field'>������������� ������*</div>
    <div id = "edit_user_value"><input type="password" name="confirm_p" maxlength = '30' value = '<?=$params[params]['confirm_p']?>'/></div>
    <div id = "edit_user_value"><input type="submit" name='register' value="������������������" /></div>
<?} elseif ($params['errormsg']) {?>
    <div id = 'edit_user_field'>&nbsp;</div>
		
<?} else  {?>
            <div id = 'edit_user_field'>�� ������� ������������������. �� ��������� ���� ����� ����������� ����� ������ ������ �� ������������� ����������.<br/>
            ����� ������������� ����������� �� ������ �������������� ��������������.
            <?$type_params = call_type('login'); 
            draw_template('login',$type_params);?>
            </div>
<?}?>
</form>
</div>