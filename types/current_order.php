<? 
function check_send_params () {
global $conn,$_DOC_PARAMS;	
session_start();
if (isset ($_POST['cur_order_input'])){
	$summ_order = 0;
	$with_sert = (int)$_POST['with_sert'];
	$qry = mysql_query("insert into `order_headers` (`user_id`,`with_sert`) values (".$_SESSION[user_id].",$with_sert)",$conn);
	$qry = mysql_query('SELECT LAST_INSERT_ID() AS last_id FROM `order_headers`',$conn);
	$last_order = mysql_fetch_assoc($qry);
	foreach ($_POST['cur_order_input'] as $key => $value) { //���������, ���� �� ��������� � ������ ����� ��������� �� ���������
		$qry = mysql_query("select * from drugs d, doctree dt where dt.id= d.id and d.id= ".$key,$conn);
		if (mysql_error() || mysql_num_rows($qry)!=1 ){
			$qry = mysql_query("delete from `order_headers` where id = ".$last_order['last_id'],$conn);
			return '������ ��� ��������� ������! ���������� � ���.��������� �����.';}
		$drugs_data = mysql_fetch_assoc($qry);
		if ((int)$value > $drugs_data[amount]) {
			$errormsg = $drugs_data[amount];//������������� ���������� �� ������! �� ������ �������� ������ '.$drugs_amount[amount].' ����!';
			$value=$drugs_data[amount];
		}
		if ((int)$value<0){ $value = 0;} 
		$_SESSION[tocart][$key] = (int)$value;
		$qry = mysql_query("insert into `order_row` (`header_id`, `drug_id`, `drug_name`, `price`, `amount`) ".
							" values(".$last_order['last_id'].",".$key.", '".$drugs_data['name'].
							"',".$drugs_data['price'].",".$_SESSION[tocart][$key].")",$conn);
		$summ_order +=($_SESSION[tocart][$key]*$drugs_data['price']);
}
$qry = mysql_query("update `order_headers` set `summ` = $summ_order where id = ".$last_order['last_id'],$conn);
if (mysql_error() ){
	return '������ ��� ���������� ������! ���������� � ���.��������� �����.';}
unset($_SESSION[tocart]);
//if ((int)$errormsg) {return $errormsg;}
}
//�������� �������� �� ����
include_once 'core/globalvars.php';
$to = $admin_email;
$subject = '����� ����� �� ����� '.$_SERVER['HTTP_HOST'];
$headers = "From: ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
$message = "������������! \r\n\r\n".
			"�� ����� ".$_SERVER['HTTP_HOST']. " ��� ������ ����� �����. \r\n".
  			"��� �������������� ������ �������� �� ������: http://".$_SERVER['HTTP_HOST']."/admin/manage_orders/".$last_order['last_id']."\r\n".
  			"����� �����".$_SERVER['HTTP_HOST'];
mail($to, $subject, $message, $headers); // �������� �����

return '��� ����� ������. ����� ������ ������: <b>'.$last_order['last_id'].'</b>.<br/>�� �������� � ���� � ��������� ����� �� ��������� ���� ���������� ������.<br/> ��� ��������� ������ ����������� ��������� ���������� ����� ������ � ��� ���������!';
}

session_start();
//�������� �����
if (isset($_POST['cancel'])) {
	unset($_SESSION[tocart]);
	$errormsg = '';
	
//������� �����
}elseif (isset($_POST['send'])) {
	$errormsg = check_send_params();

//����������� �����
}elseif (isset($_POST['recount'])) {
	if (isset ($_POST['cur_order_input'])){
		foreach ($_POST['cur_order_input'] as $key => $value) {
			$qry = mysql_query("select amount from drugs where id= ".$key,$conn);
			if (mysql_error() || mysql_num_rows($qry)!=1 ){
				$errormsg = '������ ��� ��������� ������! ���������� � ���.��������� �����.';
			}else { 
				$drugs_amount = mysql_fetch_assoc($qry);
			if ((int)$value > $drugs_amount[amount]) {
				$errormsg = '������������� ���������� �� ������! �� ������ �������� ������ '.$drugs_amount[amount].' ����!';
				$value=$drugs_amount[amount];
			}
			if ((int)$value<0){ $value = 0;} 
			$_SESSION[tocart][$key] = (int)$value;
			}
		}
	}
	$_SESSION['with_sert'] = ($_POST['with_sert'])?1:0;
//������� ������� �� ������
}elseif (isset($_POST['cur_order_delete'])) {
	foreach($_POST['cur_order_delete'] as $key=>$value) {
		unset($_SESSION[tocart][$key]);
	}
	$errormsg = '';
// ���������� ������ ������
}elseif (isset ($_SESSION[tocart])){
	$summ_order = 0;
	foreach ($_SESSION[tocart] as $drug => $amount ) {
		$qry = mysql_query("select * from doctree dt, drugs d where d.id = dt.id and dt.id =".$drug,$conn);
		if (mysql_error() || mysql_num_rows($qry)!=1 ) {
			$errormsg = '��������� ������ �� ����� ��������� ������! ���������� � ���.��������� �����.';
		}else {
			$dr_data = mysql_fetch_assoc($qry);
			$pararray['params'][$drug]['amount']=$amount;
			$pararray['params'][$drug]['name']=$dr_data['name'];
			$pararray['params'][$drug]['price']=$dr_data['price'];
			$pararray['params'][$drug]['instock']=$dr_data['amount'];
			$pararray['params'][$drug]['summ']=$dr_data['price']*$amount;
			$summ_order += $pararray['params'][$drug]['summ'];
		}
	}
//��� �������� � ������
} else {
	$errormsg = '���� ������� �����.';
}
$pararray['summ_order']= $summ_order;
$pararray['errormsg']=$errormsg;
?>
