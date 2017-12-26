 <div id = "right"> <?if ($_DOC_PARAMS[methods]!='login') { ?>
  <?draw_template('login',$params[login]);?> 
 <?}?>
<?=draw_template('cur_cart',$params['cur_cart']);?>  
</div> 