<div id = 'search_drug'>
<? if ($params){
$path = GetTypePath($_DOC_PARAMS['docid']);?>
<form action="<?=$path?>search" id = 'search_drugs' method ="post">
<?foreach ($params as $alph) {?>
<?if ($alph != $_DOC_PARAMS['get_params'][0] || $_DOC_PARAMS['methods'] !='search') {?><a href = '<?=$path?>search/<?=urlencode($alph)?>'><?=$alph?></a> 
<?}else{?><?=$alph?>
<?}?>
&nbsp;
<?}?>
| 	<input name = 'search_name' type ="text" value = '<?=htmlspecialchars($_POST['search_name'])?>'>
	<input id = 'search_submit' name= 'search_submit' type = "submit" value = ' ' title = 'Найти'>
</form>
<?}?> 
</div>
