<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1>Создание <?if (strtolower($_DOC_PARAMS['get_params'][0])=='folder'){?>группы объектов <?}else {?>объекта <?}?><?=$_DOC_PARAMS['properties']['name']?></h1>
<form id = "editform" method = "POST" enctype="multipart/form-data" action = '<?/*=GetTypePath($_DOC_PARAMS[docid])*/?>'>
 <input type="hidden" name="MAX_FILE_SIZE" value="<?=1024*1024*2?>" />
 <table id ='editclass'>
 <? if (isset($params['docparams'])){
foreach ($params['docparams'] as $key => $value) { ?>
	<tr>
	<td id= 'admin_label'><?=$value['field_label']?> &nbsp;</td>
<td  id = 'admin_value'>
	<?if ($value['field_type'] == 'date') { /*дата*/?>
	день<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>[0]" value="<?=$value['value'][2]?>" size =2 MAXLENGTH =2>
	мес<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>[1]" value="<?=$value['value'][1]?>" size =2 MAXLENGTH =2>
	год<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>[2]" value="<?=$value['value'][0]?>" size =4 MAXLENGTH =4>
	
	<? } elseif ($value['field_type'] != 'text') { /*не текст */?>
	<input type = "<?=$value['input_type']?>" name = "<?=$value['field_name']?>" value="<?=$value['value']?>">

		<?if ($value['input_type']=='file'){ ?>
			<img src = "/<?=$value['value']?>" alt=""/>&nbsp; 
			<input type = "button" id ="delete_file_btn" name = "delete_file[<?=$value['value']?>]" alt="Удалить изображение" title = "Удалить изображение" value = "&nbsp;&nbsp;" onclick = "if (confirm('Удалить изображение?')){ location.href='<?=GetTypePath($params['docparams'][0]['value'],1)."?did=".$params['docparams'][0]['value']."&d_file=".$value['field_name']?>';} ">
		<?}?>
		
	<?} else {?>
		</td></tr><tr><td colspan=2 >
		<textarea name="<?=$value['field_name']?>" cols ="80" rows="30"> <?=$value['value']?></textarea>
	<?}?>
</td> </tr>
 <?} }?>
 </table>
<div id = 'line'>&nbsp;</div> 
<div id = 'doc_methods'>
  <table id ='editclass'>
    <tr>
     <?/* <td id = 't1'><input type = "button" name = 'delete_child[<?=$_DOC_PARAMS['docid']?>]' id = "delete_file_btn" value="&nbsp;&nbsp;" alt="Удалить документ" title = "Удалить документ" onclick = "if (confirm('При удалении этого документа также будут удалены все дочерние документы. Вы настаиваете на продолжении?')){ location.href='<?=GetTypePath($_DOC_PARAMS['docid'],1)."/deletedoc"?>';} "></td>
      <td id = 't1'><input type = "button" name = "add_child" id = "new_file_btn" title="Добавить дочерний" value="&nbsp;&nbsp;" onclick = " location.href='<?=GetTypePath($_DOC_PARAMS['docid'],1)."/adddoc"?>'; "></td>
      */?><td align = 'right'><input type = "submit" id = "save_file_btn" name = "edit_obj" value="&nbsp;&nbsp;" alt="Сохранить документ" title = "Сохранить документ"></td>
    </tr> 
  </table>
</div>
</form>
 
