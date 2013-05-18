function selectrow_work(obj,status){
	if(obj.checked){
		$(obj).parent().parent().addClass('selected');
		$('td > p:.red').addClass('blue');
		document.getElementById('hdn_status').value = status;
	}
	else
		$(obj).parent().parent().removeClass('selected');
}
function set_hidden_work(obj,chk_obj,hdn_obj,value){
	hdn_obj.value ="";
	var valueOne = new Array();
	for(i = 0; i< chk_obj.length; i++){
		valueOne = chk_obj[i].value.split(':')
		if(valueOne[0] == value && chk_obj[i].disabled == false){
			chk_obj[i].checked = true;
			hdn_obj.value = value;
			rowid = "#" + obj.id;
			$('td').parent().removeClass('selected');
			$(obj).parent().addClass('selected');
		}else{
			chk_obj[i].checked = false;
		}		
	}
}
function btn_delete_work_onclick(p_checkbox_obj, p_hidden_obj, p_url, UrlAjax, DocType, TableName){
	var Delimitor   = '!#~$|*';
	var value_list_to_del = '';
	var value = new Array();
	var value_arr = new Array();
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?')){
			value_list = checkbox_value_to_list(p_checkbox_obj,",");
			value_arr  = value_list.split(',');
			var isfail = 0;
			var ok;
			for(i = 0; i < value_arr.length; i++){
				value = value_arr[i].split(':');
				//if(value[1]  != 'DA_XU_LY'){
					value_list_to_del += value[0] + ',';
				//}else	isfail += 1;
			}
			value_list_to_del = value_list_to_del.substr(0,value_list_to_del.length -1);
/*			if(isfail > 0){
				ok = confirm('Có '+ isfail + ' công việc sẽ không được xóa vì đã xử lý, Bạn có muốn tiếp tục?');
				if(ok == true){
					var key = 'ListIdDoc=' + value_list_to_del + '&DocType='+ DocType +'&TableName='+ TableName +'&delimitor=' + Delimitor;
					arrUrl = UrlAjax.split('/');
					postAJAXHTTPText('/' + arrUrl[3] + '/public/ajax/deleteAllFile.php',key,'', null);	
					p_hidden_obj.value = value_list_to_del; //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
					actionUrl(p_url);		
				}
			}else{*/
				var key = 'ListIdDoc=' + value_list_to_del + '&DocType='+ DocType +'&TableName='+ TableName +'&delimitor=' + Delimitor;
				arrUrl = UrlAjax.split('/');
				postAJAXHTTPText('/' + arrUrl[3] + '/public/ajax/deleteAllFile.php',key,'', null);	
				p_hidden_obj.value = value_list_to_del; //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
				actionUrl(p_url);	
//			}
			
		}
	}
}
// Ham btn_save_invitation duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat thong tin CAP NHAT GIAY MOI
function btn_save_work(p_hdn_tag_obj,p_hdn_value_obj,p_url,p_dateNow,obj_date,message){
	try{
		if(date_compare(p_dateNow,obj_date.value) > 0){
			alert(message);
			obj_date.focus();
			return false;
		}
	}catch(e){;}
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML			
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;	
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}
// Ham duoc goi khi NSD cap nhat thong tin xu ly cong viec
function btn_save_process_work(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	try{
		if (test_date(document.getElementById('C_WORK_DATE').value)==false){
			alert('NGAY THUC HIEN khong dung dinh dang ngay/thang/nam!');
			document.getElementById('C_WORK_DATE').focus();	
			return false;	
		}
/*		if(date_compare(p_date,document.getElementById('C_WORK_DATE').value) > 0){
			alert('NGAY THUC HIEN phai nho hon hoac bang ngay hien tai!');
			document.getElementById('C_WORK_DATE').focus();
			return false;
		}
*/
		if(document.getElementById('C_RESULTS').value == ''){
			alert('Phai xac dinh KET QUA XU LY!');
			document.getElementById('C_RESULTS').focus();
			return false;		
		}
	}catch(e){;}
	document.getElementsByTagName('form')[0].action = '';
	document.getElementsByTagName('form')[0].submit(); 
}
function btn_update_work_onclick(p_checkbox_obj){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de sua")
			return;
		}else{
			value = new Array();
			value = arr_value[0].split(':')
			if(value[1] == 'DANG_XU_LY'){
				var ok = confirm('Công việc hiện tại đang được xử lý, Bạn có muốn tiếp tục?')
				if(ok == true){
					p_url = '../../edit';
					item_onclick(value[0],p_url);		
				}
			}else{
				p_url = '../../edit';
				item_onclick(value[0],p_url);
			}
		}
	}
}
function btn_update_work_assign_onclick(p_checkbox_obj,left_modul){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de phan cong xu ly")
			return;
		}else{
			value = new Array();
			value = arr_value[0].split(':')
			if(left_modul == 'CHO_PHAN_CONG')
				p_url = '../../assign/';
			else
				p_url = '../../editassign/';
			item_onclick(value[0],p_url);
		}
	}
}
function btn_update_work_process_onclick(p_checkbox_obj,left_modul,p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot cong viec de xu ly")
			return;
		}else{
			value = new Array();
			value = arr_value[0].split(':')
			if(left_modul == 'DA_XU_LY')
					document.getElementById('status').value = 'DA_XU_LY';
			else	document.getElementById('status').value = 'DANG_XU_LY';
			item_onclick(value[0],p_url);
		}
	}
}
function btn_print_infor_onclick(p_hidden_obj, p_url){
	//Duong dan xu ly In
	p_url = p_url + '/?hdn_object_id=' + p_hidden_obj.value;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }	
}
function btn_print_processGetAll(p_url,fromDate,toDate,iLeaderId,status,sfullTextSearch){
	p_url = p_url + '?hdn_from_date=' + fromDate + '&hdn_to_date=' + toDate;
	p_url = p_url + '&hdn_leader_id=' + iLeaderId + '&hdn_status=' + status + '&hdn_full_textSearch=' + sfullTextSearch;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }
}