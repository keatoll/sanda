<? session_start();
//unset($pararray[tocart]);
$pararray['drug_amount'] = count($_SESSION['tocart']);
$pararray['drug_summ'] = 0;
if ($pararray['drug_amount']){
	foreach ($_SESSION['tocart'] as $key => $value) {
		$res = mysql_query("select `price` from `drugs` where `id` =".$key,$conn);
		if (!mysql_error() && mysql_num_rows($res)==1) {
			$drug_price = mysql_fetch_assoc($res);
			$pararray['drug_summ'] = $pararray['drug_summ'] + $drug_price['price']*$value;
		}
	}
}
?>