<?php
function encode_ip($dotquad_ip) {
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function get_connection($user=0) {
	$users_params = array(0=>array('u_guest', 'guest'),
						  1=>array('u_client', 'c'),
					 	  2=>array('u_datas', 'd'),
					 	  3=>array('sandafar_smpuser', 'JCLkUwil8'));
	$conn = mysql_connect('localhost',$users_params[$user][0],$users_params[$user][1]) or die (mysql_error());
	mysql_select_db('sandafar_vlad_db',$conn);
return $conn; 
}

function get_params() {
	$getparam=explode('?',$_GET[addr],3);
	if (substr($getparam[0],0,5)!='admin') {
   		$param = explode('/',htmlspecialchars('user/'.$getparam[0]));	
	}else{
		$param = explode('/',htmlspecialchars($getparam[0]));	
	}
	unset ($_GET['addr']);
	parse_str($getparam[1],$url_params);
	$getparam = array_merge($param,$url_params,$_GET);
	return $getparam; 
	//$a=array_shift($param);
}

function get_type() {
/*'type' - �������� ���� �������, 
 * 'dtree' - ����� �� ����� �� �������, 
 * 'get_params' - ������ ���������� Get,
 * 'state' - �����=1/����=0, 
 * 'docid' - id �������, 
 * 'properties' - ������ ���� ������� �������
 * 'methods' - ���������� ������ ������� 
 * min_rights - min ����� ���� ������� � �������
 * method_label - ���������������� �������� �������
 * 
 */
	global $conn;
	$params = get_params();
	$state = array_shift($params);
	$docid = $methods = $classtype = $dtree = $classparams = $props = $classname = '';
	$search_doc = 1;
	if (!isset($params)){return '';}
	foreach ($params as $key => $curparam ){
		if ($curparam ){
		$par = strtolower(mysql_real_escape_string(substr($curparam,0,20)));
		$parparent =strtolower(mysql_real_escape_string(substr($params[$key-1],0,20))); 
		$res = mysql_query("select dt.name tp, dt.classname dtclass, d.id from doctree d, doctype dt 
                       		where lower(d.url) = '$par' and d.type = dt.id and ($key = 0 or 
                            exists (select id from doctree d1 
                                    where d.parent = d1.id and lower(d1.url) = '$parparent'
                                   )
							)",$conn);
		$document = null;
		if (!mysql_error()){
			$document = mysql_fetch_assoc($res);
		}
			
		if (mysql_num_rows($res)>0){ //���� ������ ������ � ������ ��������
			$classname = $document['dtclass'];
			$classtype = $document['tp']; 		
			$docdata = $document['id']; 		
			$dtree[] = $document['id'];
			//$res = mysql_query("select * from doctree d where lower(d.url) = '$par'",$conn);
			//$props = mysql_fetch_assoc($res);
		} else { //���� �� ������� ����� ����������
			//require_once 'methods.php'; // ������� ���� �����
			if (!$classname){$classname = $state;}//'user';}
			if (file_exists('classes/'.$classname.'.php')&& 
			    (!$curdoc || !is_a($curdoc, $classname))){
				require_once ('classes/'.$classname.'.php');
				eval('$curdoc = new '.$classname.';');
			} 
			if  (method_exists($curdoc, $par)) {
				$methods = $par;
				eval('$curdoc->'.$par.'();');
			} else { // �� �������� � �� ����� => �������� 
				if ($nkey=(integer)$key){
					$classparams[] = mysql_real_escape_string(htmlspecialchars($curparam));
				}else{
					$classparams[$key] = mysql_real_escape_string(htmlspecialchars($curparam));
				}
			}
		}
		}
	}
	//�������� ���������
	if ($classtype && $docs_arrays[$classtype] != $state){//'user') {
		$qry = mysql_query("select dt.tablename dttbl from doctype dt where dt.name = '$classtype' ",$conn);
		if (!mysql_error() && mysql_num_rows($qry)==1 ) { // ��� ������ � ������ ������
			$res_dtype = mysql_fetch_assoc($qry); // �������� ����� �������� �������
			if($res_dtype['dttbl']) {
				$qry = mysql_query("select * from doctree dt ".
							 " left join ".$res_dtype['dttbl']." dtp on dt.id = dtp.id ".
							" where dt.id = ".$docdata,$conn) ;
				if (!mysql_error() && mysql_num_rows($qry)==1 ) { // ��� ������ � ������ ������
					$props = mysql_fetch_assoc($qry); // ����������� �� ������ ������� (�� ����) ��� ��������� ���������
				} 
			}
		}
	}
	if ($methods && !$docs_arrays[$classtype]) {$docs_arrays[$classtype] = $state;}//'user';}
	$res1 = array('type'=>$classtype, 'dtree'=>$dtree, 'get_params'=>$classparams,
				  'state'=>$state, 'docid'=>$docdata,  'properties'=>$props, 'methods'=>$methods);
	if ($methods && isset($curdoc)) { //���� ���������� �����, �� ��������� �������� ��� ���� �� ���� ����� 
		$res1['min_rights'] = $curdoc->min_rights;
		$res1['method_label'] = $curdoc->method_label;
	}
	return $res1;
}

function draw_template ($tplname, $params = null) {
	global $_DOC_PARAMS,$conn;
	require('templates/'.$tplname.'.php');
}

function call_type ($tpname) {
	global $_DOC_PARAMS, $conn;
	$pararray ='';
	if (file_exists ('types/'.$tpname.'.php')) {//���� ������
		require('types/'.$tpname.'.php');
	} else {
		require('types/stddoc.php');
	}
	//require('types/'.$tpname.'.php');
	return $pararray;	
}

//����� ������� ������ ������ ������. � ���������� ����� �� ������ ���� headers
function show_module ($tpname) {
	global $_DOC_PARAMS, $conn;
	$type_params = call_type($tpname);
	draw_template($tpname,$type_params);
}

// �������� ������ �� ��������� ������� ������
function data_is_string ($str, $length=0) {
	return 0;
}

//��� �������� �� ��������
function image_resize($img_file, $target_file, $width, $height){ 
      if(!file_exists($img_file)) return false;
      if(!$source_im_info = @getimagesize($img_file)) return false;
      $valid_im_types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
      if(!array_key_exists($source_im_info[2], $valid_im_types)) return false;
      $img_open_func = 'imagecreatefrom'.$valid_im_types[$source_im_info[2]];
      $source_im = $img_open_func($img_file);
      $result_im = imagecreatetruecolor($width, $height);
      if(!@imagecopyresampled($result_im, $source_im, 0, 0, 0, 0, $width, $height, $source_im_info[0], $source_im_info[1])) return false;
      $img_close_func = 'image'.$valid_im_types[$source_im_info[2]];
      if(!$img_close_func($result_im, $target_file)) return false;
      imagedestroy($source_im);
      imagedestroy($result_im);
      return true;
}

function get_image_type($file) {
	if (!$f = fopen($file, 'rb')) {
		return false;
	}
	$data = fread($f, 8);
	fclose($f);
	if (@array_pop(unpack('H12', $data)) == '474946383961' ||
		@array_pop(unpack('H12', $data)) == '474946383761'	) {
		return 'GIF';
	} else if (@array_pop(unpack('H4', $data)) == 'ffd8') {
		return 'JPEG';
	} else if (@array_pop(unpack('H16', $data)) == '89504e470d0a1a0a' ) {
		return 'PNG';
	} else if (@array_pop(unpack('H4', $data)) == '424d') {
		return 'BMP';
	}
	return false;
}

//--------�������� ����: ���� �� ������, � ���� ��, �� ����� ��. ������ �� �������� ������ �����!
// ���� ����������� ����� = 1, ���� �� ����������� ��� ������ = 0
function CheckUserRights () {
	global $conn;
	if (isset($_REQUEST[session_name()])) {
		session_cache_limiter("private_no_expire");
		session_start();		
		if ($_SESSION['REMOTE_ADDR']!=$_SERVER['REMOTE_ADDR']) {session_destroy();return 0;}
		if ($_SESSION['HTTP_USER_AGENT']!=$_SERVER['HTTP_USER_AGENT']) {session_destroy();return 0;}
		$nId = (integer)$_SESSION['user_id'];
		$smail = mysql_real_escape_string(substr($_SESSION['user_email'],0,30));
		if (!$nId ) {session_destroy(); return 0;}
		$sQry = mysql_query(" select * from users where id = $nId and email = '$smail'",$conn);
		if (mysql_num_rows($sQry)!=1 || mysql_error())	{ session_destroy(); return 0; }
		$userdata = mysql_fetch_assoc($sQry);
		if ($userdata['is_active']!=1) {session_destroy(); return 0; }
		if ($_SESSION['user_id'] != $userdata['id'] || $_SESSION['last_name'] !=$userdata['last_name'] || 
    		$_SESSION['user_rights'] !=$userdata['is_admin'] || $_SESSION['user_email'] !=$userdata['email']) {
    		session_destroy(); return 0; 	
		}else {
			return 1;
		}
	} else { //������ ������ ���, ���� �� �����������
		return 0;
	}
}

// ---------�������� ����: ����� �� ������ ���� �������� ������ ������
// -- 1 = �����, 0 = �� �����
function checkURLrights (){
	global $conn,$_DOC_PARAMS;

$url_rights = 3;
//���� �� �������� �����, �� ������ ����� ������
if (isset($_DOC_PARAMS['min_rights'])) {
	$url_rights = $_DOC_PARAMS['min_rights'];
// ����� ������ ����� ������� � doctree
} elseif(isset($_DOC_PARAMS['properties']['min_rights'])){
	$url_rights = $_DOC_PARAMS['properties']['min_rights'];
}else {
	$url_rights = 0;
}
if ($_DOC_PARAMS['state']=='admin' && $url_rights < 2) { // ���� �� � �������
	$url_rights = 2;
}
// ��������� ����� �����
//session_start();
$user_rights = 0;
if (CheckUserRights()==1) {
	if ($_SESSION['user_rights']>1) {
		$user_rights = $_SESSION['user_rights']; // ���� �����
	} elseif ($_SESSION['user_rights']==1) {
		$user_rights = 2; // ����� ������
	}else {
		$user_rights = 1; // ���������� ����
	}
} else {
	$user_rights = 0; // ����� ����
}

// ���������� ����� � ������ ������
$is_allowed = 0;
if ($user_rights >= $url_rights) {
	$is_allowed = 1;
}
return $is_allowed;
}

//���������� ���������� ������� �� ID �� ������� doctree
function dt_params ($docid, $paramname) {
	global $conn;
	$qry = mysql_query("select `$paramname` from doctree d where d.id = $docid ",$conn);
	if (!mysql_error()){
		$result = mysql_fetch_row($qry);
		return $result[0];
	}else {return '';}
}

//��������� ������ ��� ��� �������
function GetTypePath($docid,$state = 0){
	global $conn;
	$parentid = 1; // ��������� ����� ������� ��� �������������
	$patharr='';
	$curid = $docid;
	while ($parentid) {
		$res = mysql_query("select d.url, d.parent from doctree d where d.id = $curid ",$conn);
		if (!mysql_error() && mysql_num_rows($res)==1){
			$row1 = mysql_fetch_assoc($res);
			$parentid = $row1['parent'];
			$patharr = $row1['url']."/".$patharr;
		} else {
			$parentid = '';
		}
		$curid = $parentid;
	};
	
	if ($state == 1 ||strtolower($state)=='admin' ){
		return "http://".$_SERVER['HTTP_HOST']."/admin/".$patharr;
	} else {
		return "http://".$_SERVER['HTTP_HOST']."/".$patharr;
	}
}

//���������� ��������������� ������ �������� �����
function ShowTree($ParentID, $docid = 0,$state = 0) { 
global $_DOC_PARAMS,$conn;
$max_leafs = 10;
$sSQL="SELECT id,name,parent, url FROM doctree WHERE parent=".$ParentID. 
 /*and type >0 */" ORDER BY name limit 0,$max_leafs";
$sqry = mysql_query($sSQL, $conn);
$sSQL ="SELECT count(*) cnt FROM `doctree` WHERE parent= $ParentID";// and type >0 ";
$sqry2 = mysql_query($sSQL, $conn);
$sRes2 = mysql_fetch_assoc($sqry2);
$num_leafs = $sRes2[cnt];
$result = '';
if ($num_leafs > 0) {
	if ($ParentID==0) {
		$result.= "<UL id = 'ltree_'>";
	}else {
		if (in_array($ParentID, $_DOC_PARAMS[dtree])){
			$result.= "<UL id = 'ltree_$ParentID' style='display:block' >";
		} else {
			$result.= "<UL id = 'ltree_$ParentID' style='display:none' >";
		}
	}
	while ( $row = mysql_fetch_assoc($sqry) ) {
		$ID1 = $row["id"];
		$result.= "<li onclick = \"Showlefttree('ltree_{$row["id"]}');\"> <a href = '". GetTypePath($row["id"],$state)."'>". $row["name"]."</a></li>";//<br/>";
		$result.=ShowTree($ID1, $lvl+1,$state);
		//$result.='</li>'; 
	}
	if ($num_leafs > $max_leafs) {
		$result.= "<li> ..."."</li>";
	}
$result.="</UL>";
}

return $result;
}

//��������� ������ ��������� �������. ����� ��-����� = 7
function generate_password($lngt=7){
$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
$size=StrLen($chars)-1; // ���������� ���������� �������� � $chars
$password=null; // ���������� ������ ����������, � ������� � ����� ���������� �������.
while($lngt--) { // ������ ������.
	$password.=$chars[rand(0,$size)];
}
return $password;
}

/*������ ������� ����������� */
function resizeimg($filename, $smallimage, $w=0, $h=0, $str='',$quality=100){
    // ������� ������� ��������� ����������� � ���������
    $size_img = getimagesize($filename);
	// ������� ����������� ������ ��������� �����������
    $src_ratio=$size_img[0]/$size_img[1];
    
    //���� ������ ������ ���� ����������, �������� ����������� ���������������
    if (!(int)$w && !(int)$h) {
    	//�� ������ ��������� => �����, ������� ��� ��������
    	return false;
    }elseif ((int)$w<=0){
   		$w = $h*$src_ratio;
   		$ratio = $src_ratio;
    }elseif ((int)$h<=0){
    	$h=$w/$src_ratio;
   		$ratio = $src_ratio;
    }elseif((int)$h && (int)$w){
    	// ��������� ����������� ������ �����������, ������� ����� ��������
		$ratio = $w/$h;
    	// ����� ��������� ������� ����������� �����, ����� ��� ��������������� ����������� 
		// ��������� ��������� �����������
		if ($ratio<$src_ratio){
    		$h = $w/$src_ratio;
    	}else {
		   	$w = $h*$src_ratio;
    	}
    }
    
    // ���� ������� ������, �� ��������������� �� �����
    if (($size_img[0]<$w) && ($size_img[1]<$h)) {return true;}
    // �������� ������ ����������� �� �������� �������� 
    $dest_img = imagecreatetruecolor($w, $h);  
    $white = imagecolorallocate($dest_img, 255, 255, 255);       
    if ($size_img[2]==2)  {$src_img = imagecreatefromjpeg($filename);}                      
    else if ($size_img[2]==1) {$src_img = imagecreatefromgif($filename);}                      
    else if ($size_img[2]==3) {$src_img = imagecreatefrompng($filename);} 
 
    // ������������ �����������     �������� imagecopyresampled()
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);        

/*��������� ����� ���� ���������� �������������. ��� �������� ������������ ������� �� 
������� ��������. ������, ����������� ������. */
if ($str){
    // ���������� ���������� ������ ������ 
    $size = 2; // ������ ������ 
    $x_text = $w-imagefontwidth($size)*strlen($str)-3; 
    $y_text = $h-imagefontheight($size)-3; 

    // ���������� ����� ������ �� ����� ���� �������� ����� 
    $white = imagecolorallocate($dest_img, 255, 255, 255); 
    $black = imagecolorallocate($dest_img, 0, 0, 0); 
    $gray = imagecolorallocate($dest_img, 127, 127, 127); 
    if (imagecolorat($dest_img,$x_text,$y_text)>$gray) {$color = $black;}
    if (imagecolorat($dest_img,$x_text,$y_text)<$gray) {$color = $white;} 

    // ������� ����� 
    imagestring($dest_img, $size, $x_text-1, $y_text-1, $str,$white-$color); 
    imagestring($dest_img, $size, $x_text+1, $y_text+1, $str,$white-$color); 
    imagestring($dest_img, $size, $x_text+1, $y_text-1, $str,$white-$color); 
    imagestring($dest_img, $size, $x_text-1, $y_text+1, $str,$white-$color); 

    imagestring($dest_img, $size, $x_text-1, $y_text,   $str,$white-$color); 
    imagestring($dest_img, $size, $x_text+1, $y_text,   $str,$white-$color); 
    imagestring($dest_img, $size, $x_text,   $y_text-1, $str,$white-$color); 
    imagestring($dest_img, $size, $x_text,   $y_text+1, $str,$white-$color); 

    imagestring($dest_img, $size, $x_text,   $y_text,   $str,$color); 
}
        // ��������� ����������� ����� � ���� 
    if ($size_img[2]==2)  {imagejpeg($dest_img, $smallimage,$quality);}                  
    else if ($size_img[2]==1) {imagegif($dest_img, $smallimage,$quality);}                     
    else if ($size_img[2]==3) {imagepng($dest_img, $smallimage,$quality);} 
    // ������ ������ �� ��������� �����������
    imagedestroy($dest_img);
    imagedestroy($src_img);
    return true;         
}
?>