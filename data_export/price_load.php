<? 
//������������� �����

include_once '../core/globalvars.php';

$zip = new ZipArchive;
$f1="ftp://".$ftp_user.":".$ftp_pass."@".$ftp_url."/price1.zip";
$f2='../uploads/price1.zip';
copy($f1, $f2);
if ($zip->open($f2) === TRUE) {
	$zip->extractTo("../uploads/");
} else {
	$to = $admin_email;
	$subject = '������ �������� ������ �� ����� '.$_SERVER['HTTP_HOST'];
	$headers = "From: ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
	$message = "������������! \r\n\r\n".
			"�� ����� ".$_SERVER['HTTP_HOST']. " ��� �������� ������ ��������� ������: \r\n".
  			" �� ������� ��������������� ������ \r\n".
  			"����� �����".$_SERVER['HTTP_HOST'];
	mail($to, $subject, $message, $headers); // �������� �����
	
	die ('�� ������� ��������������� ������');
}

$conn = mysql_connect('localhost',$db_user,$db_password) or die (mysql_error());
mysql_select_db($db_base,$conn);

//��������� ������� ������� �� �����
$handle = fopen("../uploads/price.txt", "r");
    $buffer = fgets($handle);
    $param_name = explode(";",$buffer);
    /* id_1c; name;factory; country; amount;price;group; description */
    foreach ($param_name as $key => $value) {
    	$par_name_flip[$value] = $key;
    } 
    unset($param_name);

// �������� ���������� ��� ���� ������� ������
if (!feof($handle)){
    $qry = mysql_query('update `drugs` d set d.`amount`=0 ',$conn);//where not exists (select dt.`id` from `doctree` dt where dt.`id` = d.`id` and dt.`parent` in (4,6))');
    $qry = mysql_query('update `doctree` dt set dt.`isActive`=0 where dt.type = 3 and dt.`isDir` = 0 ',$conn);
}

while (!feof($handle)) {
    $buffer = fgets($handle);
    $tmp_load = explode(";",$buffer);
    /* id_1c; name;factory; country; amount;price;group; description */
        if (!$tmp_load[$par_name_flip['id_1c']] || !(int)$tmp_load[$par_name_flip['amount']] || !(float)$tmp_load[$par_name_flip['price']]) {
    	$msg_error=$msg_error."\n"."��������� ������ ������! ��������� ������������ ������!".$tmp_load[$par_name_flip['id_1c']]."\n";
    	continue;
    }
	// ��������� �� ������� ������ ������
	$res= mysql_query("select d.`id` from `drugs` d where d.`id_1c`=".$tmp_load[$par_name_flip['id_1c']],$conn);
	if (mysql_num_rows($res)==0){ //���� ���� ����� �������
		/* ���������� ������ � doctree */ 
		$qry = mysql_query("insert into `doctree` (`name`,`description`,`parent`,`type`,`url`) values('".
					mysql_real_escape_string($tmp_load[$par_name_flip['name']])."','".mysql_real_escape_string($tmp_load[$par_name_flip['description']])."',3,3,'')",$conn); 
		if (mysql_error()) {
			$msg_error= $msg_error."\n".'��������� ������ �� ����� ���������� ������ � ������ '.$tmp_load[$par_name_flip['name']].'. ���������� � ���.��������� �����!'; 
		}else {
			$qry_insert = mysql_query("SELECT LAST_INSERT_ID() AS last_id FROM `doctree`",$conn);
			// �������� id ������ ���������
			$new_id = mysql_fetch_row($qry_insert);
			$new_id = $new_id[0];
			// ���������, ��� ���� url ���������. ���� ���, �� ������ ���� = id
			$qry = mysql_query("update doctree set `url`=".$new_id." where id = ".$new_id,$conn);
			//���������� ������ � drugs 
			$qry = mysql_query("insert into `drugs` (`id`,`id_1c`,`group`,`amount`,`price`,`factory`,`country`) values(".$new_id.",'".
				$tmp_load[$par_name_flip['id_1c']]."','".mysql_real_escape_string($tmp_load[$par_name_flip['group']])."','".
				$tmp_load[$par_name_flip['amount']]."','".$tmp_load[$par_name_flip['price']]."','".
				mysql_real_escape_string($tmp_load[$par_name_flip['factory']])."','".
				mysql_real_escape_string($tmp_load[$par_name_flip['country']])."')",$conn); 
			if (mysql_error()) {
				die(mysql_error());
				$msg_error= $msg_error."\n".'��������� ������ �� ����� ���������� ������. ���������� � ���.��������� �����!'; 
			}
		} 
	}else { //���� ����� ����, �� ������ ��������� ���-�� � ���� � ������
		$drug_data = mysql_fetch_assoc($res);
		mysql_query("update `drugs` set `amount`=".$tmp_load[$par_name_flip['amount']].",`price`=".$tmp_load[$par_name_flip['price']].
					" where id = ".$drug_data['id'],$conn);
		if (mysql_error()) {$msg_error=$msg_error."\n".mysql_error();}
	}
}

// �������� ���������� ��� ���� ������� ������
$qry = mysql_query('update `doctree` dt set dt.`isActive`=1 where dt.`isDir` = 0 and '.
  					' dt.id in (select d.id from drugs d where d.amount > 0) ',$conn);

fclose($handle);
unlink("../uploads/price.txt");
unlink("../uploads/price1.zip");

if ($msg_error) {
		$to = $admin_email;
	$subject = '������ �������� ������ �� ����� '.$_SERVER['HTTP_HOST'];
	$headers = "From: ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">\r\n";
	$message = "������������! \r\n\r\n".
			"�� ����� ".$_SERVER['HTTP_HOST']. " ��� �������� ������ ��������� ������: \r\n".
  			$msg_error." \r\n".
  			"����� �����".$_SERVER['HTTP_HOST'];
	mail($to, $subject, $message, $headers); // �������� �����
	
	
}
echo $msg_error."\n".'������ ��������';
?>