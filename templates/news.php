<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h1><?=$_DOC_PARAMS['properties']['name']?></h1>
<span  id = 'stddoc_value'>  
<img src = "/<?=$_DOC_PARAMS['properties']['img']?>" alt="" />&nbsp;</span>
<span  id = 'stddoc_value'>  
	<?=$_DOC_PARAMS['properties']['description']?>
	<?=$_DOC_PARAMS['properties']['text']?>
</span>
<span  id = 'stddoc_value'>  
<i><?=substr($_DOC_PARAMS['properties']['date'],8,2)?>.<?=substr($_DOC_PARAMS['properties']['date'],5,2)?>.<?=substr($_DOC_PARAMS['properties']['date'],0,4)?></i>
</span>

