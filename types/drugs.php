<? require_once 'stddoc.php';

session_start();
// загрузка заказов для админа для добавления позиций в заказы
if ($_SESSION['user_rights']==1){
	$qry = mysql_query("select  o.id oid, 
			concat(u.last_name,' ',u.first_name,' ',u.second_name) nm 
			from order_headers o, users u 
			where u.id = o.user_id and o.state = 0",$conn);
	if (!mysql_error() && mysql_num_rows($qry)>0) {
		while ($orders = mysql_fetch_assoc($qry)) {
			$pararray['admin_orders'][$orders['oid']]=$orders['oid'].'-'.$orders['nm'];
		}
	}
}
$pararray['alph_search'] = call_type('search_panel');

?>