<?
unset($pararray); 
$sqry = mysql_query("SELECT id,name,parent, url FROM doctree ".
			"WHERE parent= 0 and type >0 ORDER BY name", $conn);
if (!mysql_error()){
	$result = '';
	while ( $row = mysql_fetch_assoc($sqry) ) {
		$pararray[] = $row; 
	}
}
?>