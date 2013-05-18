//-------------------- Sys thuc hien ------------------------------------------
/*
	Nguoi tao: Sys
	* Ngay tao: 10/06/2011
	* Y nghia: Ho tro go phim Enter submit lai trangn
*/
var x='';	
function handler(e){
	//IE return :event.keyCode; FF return : e.which;
	var  x=(window.event)?event.keyCode:e.which; 
	if (x==13){	//Enter	
		btn_enrollment_wait('');					
	}
}	
if (!document.all){
	window.captureEvents(Event.KEYPRESS);
	window.onkeypress=handler;
}else{
	document.onkeypress = handler;
} 
/**
 * @author :Sys
 * Date : 10/06/2011
 * @param p_url : Dia chi URL
 * @returns : Ham xu ly update Chuc vu vao he thong CSDL
 */
function btn_position_update(p_url){
	if (verify(document.forms[0])){	
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 		
	}	
}

/**
 * @author :Sys
 * Date : 17/06/2011
 * @param p_value : Gia tri Checkbox
 * @returns : Ham xu ly Check/Not Check khi NSD nhan vao tieu de cua checkbox tuong ung
 */
function btn_checkOrNotCheckbox(current_chk_obj){
	if (current_chk_obj.checked){
		current_chk_obj.checked = false;
	}else{
		current_chk_obj.checked = true;
	}
}
