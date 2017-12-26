<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1><?=$_DOC_PARAMS['properties']['name']?></h1>
<? if (isset($params['docparams'])){
	foreach ($params['docparams'] as $key => $value) { ?>
<span  id = 'stddoc_value'> <b><?=$value['field_label']?>: &nbsp;</b>
	<?	if ($value['field_type'] == 'date') { /*дата*/?>
		<?=$value['value'][2]?>.<?=$value['value'][1]?>.<?=$value['value'][0]?></span><br/> 
	<? 	} elseif ($value['field_type'] == 'img' ) { /*не текст */?>
			<img src = "/<?=$value['value']?>" alt="" />&nbsp;</span> 
	<?	} elseif ($value['field_type'] != 'hidden')  {?>
		<?=$value['value']?></span><br/>
	<?	}?>
		
 <?	}
}?>
