function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

//скрытие/отображение подпунктов меню
function Showlefttree(id) {
var menu = document.getElementById(id);
var cookie_data = "";
var openlist = '';
var id_to_write = str_replace('ltree_','',id);

//if ((menu != null) && (navigator.cookieEnabled )) { //если куки включены
if (menu.style.display == 'none') {// отобразить меню
	menu.style.display = 'block';
/*	cookie_data = getCookie('lmenu');
	openlist = cookie_data.split('.');

	if (cookie_data ==''){
		setCookie('lmenu','.'+id_to_write+'.',10);  // если куки нет, ставим
		cookie_data = getCookie('lmenu');
	}
	if (!(id_to_write in openlist) ){
		
		setCookie('lmenu',cookie_data+id_to_write+'.',10);  // добавляем пункт
	}
*/
} else { 				// скрыть меню
	menu.style.display = 'none';
/*	cookie_data = getCookie('lmenu');
	if (cookie_data ==''){
		setCookie('lmenu','.'+id_to_write+'.',10);  // если куки нет, ставим
		cookie_data = getCookie('lmenu');
	}
	cookie_data = str_replace('.'+id_to_write+'.','.',cookie_data);
	setCookie('lmenu',cookie_data,10);  // добавляем пункт
*/}
/*} else { // если куки выключены, то отображаем все
	menu.style.display = 'block';
} */
}

//скрытие подпунктов меню при загрузке страницы
function allClose() {
/*var arr = [];
var ul_id = '';
var cookie_data = '';//getCookie('lmenu');
var openlist = cookie_data.split('.');
var list = document.getElementById('ltree_').getElementsByTagName('ul');
cookie_data = getCookie('lmenu');
alert('allClose');
for(var i=0;i<list.length;i++){
	ul_id = str_replace('ltree_','',list[i].id);
	alert(' + '+ul_id);
	if (!(ul_id in arr)){
		alert('no repeat '+ul_id);
		arr.push(ul_id);
		if ( ul_id in openlist) {list[i].style.display = "block"; alert('block '+ul_id);
		} else 					{list[i].style.display = "none"; alert('none '+ul_id);}
	}
}*/
}

// получить значение cookie
function getCookie(name) { 
    var dc = document.cookie;
	var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return false;
    } else {
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}

//записать значение cookie
function setCookie(cookieName,cookieValue,nDays) { 
	var today = new Date();
	var expire = new Date();
	if (nDays==null || nDays==0) nDays=1;
	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue) + "; path=/; expires="+expire.toGMTString();
} 

function change_s() {
	//for(var i=0;i<list2.length;i++){
		//alert(list2[i].value);
		//ul_id = str_replace('ltree_','',list[i].id);
	//}	
	
}
