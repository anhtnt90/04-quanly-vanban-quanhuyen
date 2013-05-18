/*
Nguoi tao: Do viet Hai
Ngay tao: 27/07/2011
Y nghia: Ham xac dinh in lich duoi dang nao, WEB, WORL
*/
function btn_rad_onclick(p_rad_obj,p_hdn_obj){	
	p_hdn_obj.value = p_rad_obj.value;
	//alert(p_hdn_obj.value);
}
/*
Nguoi tao: Do viet Hai
Ngay tao: 27/07/2011
Y nghia: Ham gan cac gia tri an vao URL khi bat len cua so in lich
*/
function btn_print_schedule_onclick(p_url,v_week,v_year,v_date,v_type,v_exporttype,v_print_for_owner){	
	p_url = p_url + '?hdn_year=' + v_year + '&hdn_week=' + v_week + '&hdn_date=' + v_date + '&hdn_schedule_type=' + v_type + '&hdn_exporttype=' + v_exporttype + '&hdn_print_for_owner=' + v_print_for_owner;		
	//alert(p_url);
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");	
    //alert(sRtn);
	if (sRtn!=""){
		window.open(sRtn);
    }
}
/*
Nguoi tao: Do viet Hai
Ngay tao: 02/08/2011
Y nghia: Ham kiem tra gia tri truyen vao khi NSD click vao nut cap nhat
*/
function btn_save_schedule_unit(p_approve,p_url){			
		if((document.forms[0].C_WORK_NAME.value==null) || (document.forms[0].C_WORK_NAME.value == "") || isblank(document.forms[0].C_WORK_NAME.value)){
			alert('Phải nhập TÊN CÔNG VIỆC!');
			document.getElementById('C_WORK_NAME').focus();
			return false;
		}					
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 			
}
//	Ham item_onclick duoc goi khi NSD click vao 1 dong trong danh sach
//  p_item_value: chua ID cua doi tuong can hieu chinh
function item_onclick(p_item_value,p_url){		
	row_onclick(document.getElementById('hdn_object_id'), p_item_value, p_url);
}
/*
Nguoi tao: Do viet Hai
Ngay tao: 02/08/2011
Y nghia: Ham thuc hien luc bien check khi duyet ho so vao bien an
*/
function check_approve(chk_obj){	
	if(chk_obj.checked){
		document.forms[0].hdn_approve_schedule.value = '1';				
	}
	else{
		document.forms[0].hdn_approve_schedule.value = '0';		
	}
}
/*
Nguoi tao: Do viet Hai
Ngay tao: 02/08/2011
Y nghia: Ham kiem tra va luu cac ID truoc khi duyet ho so
*/
function btn_aprrove_onclick(p_checkbox_obj, p_hidden_obj, p_url){	
	//alert('OK');
	var Delimitor   = '!#~$|*';
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chưa có đối tượng nào được chọn");
	}
	else{
		if(confirm('Bạn thực sự muốn duyệt lịch đã chọn?')){
			value_list = checkbox_value_to_list(p_checkbox_obj,",");			
			p_hidden_obj.value = value_list; 
			actionUrl(p_url);
		}
	}
}
/*
Nguoi tao: Nguyen Duy Hieu
Ngay tao: 25/11/2009
Y nghia: Ham tu dong chuyen doi kieu gio phut giay
Tham so:
	+ obj_hour : document.all/document.forms(0)
*/
function hour_onkeyup(obj_hour,e){
	//alert(obj_hour.value);
	strtime=obj_hour.value;
	//keycode=window.event.keyCode;
	keycode = (window.event)?event.keyCode:e.which; 
	intlen=strtime.length;
	if (keycode==8 || (keycode>=37 && keycode<=40) || (keycode>=48 && keycode<=57) || (keycode>=96 && keycode<=105)){
		if (keycode != 8){
			if (intlen==1){
				if (strtime > 2 && strtime < 10){
					obj_hour.value="0"+strtime+":";
				}
			}else{
				if (intlen==2){
					if (strtime > 9 && strtime < 24){
						obj_hour.value=strtime+":";
					}else{
						if (strtime >= 0 && strtime <= 9){
							obj_hour.value=strtime+":";
						}else{
							alert("Thời gian không hợp lệ !");
							obj_hour.value=strtime.substr(0,1);
						}
					}
				}else{
					if (intlen==4){
						strtime4=strtime.substr(3,1);
						if (strtime4 == "6" || strtime4 == "7" || strtime4 == "8" || strtime4 == "9"){
							alert("Thời gian không hợp lệ !");
							obj_hour.value=strtime.substr(0,3);
						}
					}
				}
			}
		}
	}else{
		strtimef=strtime.substr(0,intlen-1)
		obj_hour.value=strtimef;
	}
}
function hour_onBlur(obj_hour){		
	strtime=obj_hour.value;
	intlen=strtime.length;
	v_time_start = document.forms['frmsheduleUnit'].C_START_TIME.value;
	v_time_finish = document.forms['frmsheduleUnit'].C_FINISH_TIME.value;	
	if(v_time_finish !=0 && v_time_finish  <= v_time_start){
				alert('Thời gian KẾT THÚC phải lớn hơn thời gian BẮT ĐẦU!');
				document.forms['frmsheduleUnit'].C_FINISH_TIME.value ='';
				document.forms['frmsheduleUnit'].C_FINISH_TIME.focus();
				return;
			}
	if (intlen==1){
		alert("Thời gian không hợp lệ!");
		obj_hour.value.focus();
	}else{
		if (intlen==3){
			obj_hour.value=strtime + "00";
		}else{
			if (intlen==4){
				obj_hour.value=strtime + "0";
			}
		}
	}
}