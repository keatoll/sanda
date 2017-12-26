<? session_start();
	if(!$_SESSION['user_id']){
	header("Location: http://".$_SERVER['HTTP_HOST']);
	exit;
}?>