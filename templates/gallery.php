<? ob_start();
$res = mysql_query("select count(id) from poligon_gallery",$conn);
$count = mysql_fetch_row($res);
$count = $count [0];
if ( (int)$param[2] ){
  $res = mysql_query("select gdate from poligon_gallery where id=".(int)$param[2],$conn); 
  $c_date = mysql_fetch_row($res);
  $c_date =$c_date[0];
  $c_id = ($c_date !='')? (int)$param[2] : 0;
}
if ( !$c_id) {
  $res = mysql_query("select min(gdate) from poligon_gallery",$conn); 
  $c_date = mysql_fetch_row($res);
  $c_date = $c_date [0];
  $res = mysql_query("select id from poligon_gallery where gdate='$c_date' limit 1",$conn);
  $c_id = mysql_fetch_row($res);
  $c_id = $c_id [0];
}

$res = mysql_query("select count(id) from poligon_gallery where gdate < '$c_date'",$conn);
$l_count = mysql_fetch_row($res);
$l_count = $l_count [0];
$mn=min($l_count,10);
$res = mysql_query("select count(id) from poligon_gallery where gdate > '$c_date'",$conn);
$b_count = mysql_fetch_row($res);
$b_count = $b_count[0];
$lim = ($l_count-10<0)? 0: $l_count-10;
$st = $mn+11;
$res = mysql_query("select * from poligon_gallery order by gdate limit $lim, $st",$conn);
$i=0;
if ($l_count >10){ // добавить ссылку "Предыдущие"
	$stp = max($lim-5,0);
	$prev_res = mysql_query("select id from poligon_gallery order by gdate limit $stp, 1",$conn);
	$prev= mysql_fetch_row($prev_res);
	$prev = $prev[0];
}

if ($b_count >10){ // добавить ссылку "Следущие"
	$stn = $lim+$st+min(5,$count-$lim-$st)-1;
	$next_res = mysql_query("select id from poligon_gallery order by gdate limit $stn, 1",$conn);
	$next= mysql_fetch_row($next_res);
	$next = $next[0];
} 
ob_end_clean();

if ($lim){?>
<br/><br/>
<span class='page'><a href='../poligon/gallery/<?=$prev?>'>Предыдущие </a></span><br/><br/>
<?}?>

<? while ($galls = mysql_fetch_assoc($res)) {
    $i++;
    if ($i<$mn || $i>$mn+2  )  { ?>
    	<span id='gallery'><a href='../poligon/gallery/<?=$galls[id]?>' ><?=$galls[gdate]?> <?=$galls[gdescr]?></a></span><br/>
    <?} 
   else if  ($i==$mn || $i==$mn+2) { ?>
        <div id='gallery'><h2><?=$galls[gdate]?> <?=$galls[gdescr]?></h2><br/>
       <? $res_list = mysql_query("select * from `poligon_g_list` where gid = ".$galls[id],$conn); ?>
	<table><tr>
	<? $i=0;
	while ($list = mysql_fetch_assoc($res_list)) { ?>
	<td><a href='../poligon/images/gall/<?=$list[name]?>'><img id="<?=$list[id]?>" alt="" src="../poligon/images/gall/<?=$list[name]?>" /></a> <br/>
	 <?=$list[descr]?> </td> 
                <? $i++;
                    if ($i==3) { $i=0; ?></tr>
	<tr><?}?>

	<?}?></tr></table></div><br/><?
    } 
   else if  ( $i==$mn+1 || $c_id==$galls[id] ) 
    { ?>
    <div id='gallery'><h1><?=$galls[gdate]?> <?=$galls[gdescr]?></h1><br/>
   <? $res_list = mysql_query("select * from `poligon_g_list` where gid = ".$galls[id],$conn);?>
	<table> <tr>
	<? $i=0;
	while ($list = mysql_fetch_assoc($res_list)) { ?>
	<td><a href='../poligon/images/gall/<?=$list[name]?>'><img id="<?=$list[id]?>" alt="" src="../poligon/images/gall/<?=$list[name]?>" /></a> <br/>
	 <?=$list[descr]?> </td> 
                <?$i++;
                    if ($i==3) { $i=0; ?></tr>
	<tr><?}?>

	<?}?></tr> </table> </div> <br/>
<? } }
if ($b_count >10) { ?>
	<span class='page'><a href='../poligon/gallery/<?=$next?>'>Следующие</a></span><br/><br/>
<?}?>
<? mysql_close($conn); ?>