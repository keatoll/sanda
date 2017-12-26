<?	$qry = mysql_query("select UPPER(substr(dt.name,1,1)) alph from  doctree dt where dt.isActive = 1 and dt.isDir = 0 and dt.parent = ".$_DOC_PARAMS[docid].
					   " group by UPPER(substr(dt.name,1,1))",$conn);
	if (!mysql_error() && mysql_num_rows($qry)>0) {
		while ($alph = mysql_fetch_assoc($qry)) {
			$pararray[]=$alph['alph'];
		}
	}
?>