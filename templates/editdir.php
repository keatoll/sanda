<form id = "editclass" method = "POST" enctype="multipart/form-data" action = '<?/*=GetTypePath($_DOC_PARAMS[docid])*/?>'>
 <input type="hidden" name="MAX_FILE_SIZE" value="<?=1024*1024*2?>" />
 <? if (isset($params)){
foreach ($params as $key => $value) { ?>
	<div id= 'admin_label'><?=$value['field_label']?></div>&nbsp;<br/>
<div id = 'admin_value'>
	<?if ($value['field_type'] == 'date') { /*дата*/?>
	день<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>[0]" value="<?=$value['value'][2]?>" size =2 MAXLENGTH =2>
	мес<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>[1]" value="<?=$value['value'][1]?>" size =2 MAXLENGTH =2>
	год<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>[2]" value="<?=$value['value'][0]?>" size =4 MAXLENGTH =4>
	<br/>
	<? } elseif ($value['field_type'] != 'text') { /*не текст */?>
	<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>" value="<?=$value['value']?>"><br/>

		<?if ($value['input_type']=='file'){ ?>
			<img src = "<?=$value['value']?>" alt=""/>&nbsp; 
			<input type = "button" name = "delete_file[<?=$value['value']?>]" value="Удалить" onclick = "if (confirm('Удалить?')){ location.href='<?=GetTypePath($params[0]['value'])."?did=".$params[0]['value']."&d_file=".$value['field_name']?>';} ">
		<?}?>
		<br/>
	<?} else {?>
		<textarea name="<?=$value['field_name']?>" cols ="80" rows="30"> <?=$value['value']?></textarea><br/>
	<?}?>
</div>
 <?} }?>
<input type = "submit" name = "edit_obj" value="Сохранить">
</form>
