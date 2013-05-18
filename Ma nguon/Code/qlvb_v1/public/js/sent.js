// Ham btn_save_list duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat 1 doi tuong
function btn_save_period(p_hdn_tag_obj,p_hdn_value_obj,p_url){	
	 if(!isnum(document.getElementById('so_ban').value ) && document.getElementById('so_ban').value != ''){
		alert('SO BAN phai la so nguyen duong!');
		return;
	 }
	 if(!isnum(document.getElementById('so_trang').value) && document.getElementById('so_trang').value != '' ){
		alert('SO TRANG phai la so nguyen duong!');
		return;
	 } 
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML			
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;	
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}

function btn_del_onclick(p_hdn_tag_obj, p_hdn_value_obj, p_chk_obj, p_hdn_id_list_obj, p_hdn_page_obj, p_fuseaction){
	_save_xml_tag_and_value_list(document.forms[0],p_hdn_tag_obj,p_hdn_value_obj,false);
	p_hdn_page_obj.value =1;
	//alert(checkbox_value_to_list(p_chk_obj,","));
	btn_sent_delete_onclick(p_chk_obj,p_hdn_id_list_obj,p_fuseaction);
}

// Ham nay duoc goi khi NSD nhan vao nut "Truy van du lieu"
function btn_query_data_onclick(p_hdn_tag_obj,p_hdn_value_obj, p_hdn_page_obj, pAction){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, false);		
	p_hdn_page_obj.value = 1;	
	actionUrl(pAction);
}

///////////////////////////////////////////////////////////////////////////////////////////
function onchange_submit(pAction){
	//document.forms(0).hdn_page.value =1;	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	actionUrl('');
}
function set_input(){
	
}

// ham btn_sent_delete_onclick() duoc goi khi NSD nhan chuot vao nut "Xoa"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_sent_delete_onclick(p_checkbox_obj, p_hidden_obj, p_url){
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?')){
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			actionUrl(p_url);
		}
	}
}

// ham btn_printReceipt_onclick() duoc goi khi NSD nhan chuot vao nut "In giay bien nhan"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_printReceipt_onclick(p_checkbox_obj, p_hidden_obj, p_url){		
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if (list_count_element(checkbox_value_to_list(p_checkbox_obj,","),',') == 1){
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			p_url = p_url + 'hdn_object_id/' + p_hidden_obj.value;
			window.open(p_url);
		}else{
			alert('Ban chi duoc chon MOT HO SO de IN GIAY BIEN NHAN!');
			return;
		}
	}
}

// ham btn_moveHandleUnit_onclick() duoc goi khi NSD nhan chuot vao nut "Ban giao ho so"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_moveHandleUnit_onclick(p_checkbox_obj, p_hidden_obj, p_url){		
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{		
		p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,_SUB_LIST_DELIMITOR); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj		
		actionUrl(p_url);
	}
}

// ham btn_UpdatemoveHandleUnit_onclick() duoc goi khi NSD nhan chuot vao nut Update "Ban giao ho so"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_UpdatemoveHandleUnit_onclick(p_url){		
	if (verify(document.forms[0])){	
		actionUrl(p_url);
	}
}

// ham btn_printReceipt_onclick() duoc goi khi NSD nhan chuot vao nut "In giay bien nhan"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_appendRecord_onclick(p_checkbox_obj, p_hidden_obj, p_url){		
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if (list_count_element(checkbox_value_to_list(p_checkbox_obj,","),',') == 1){
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			//p_url = p_url + 'hdn_object_id/' + p_hidden_obj.value;
			actionUrl(p_url);			
		}else{
			alert('Ban chi duoc chon MOT HO SO de thu hien!');
			return;
		}
	}
}

//Ham kiem tra ngay hen nop nghia vu tai chinh
function f_appointed_date(){	
	if(!isnum(document.getElementById('so_ngay_giai_quyet').value)){
		alert('SO NGAY GIAI QUYET phai la so nguyen!');
		document.getElementById('so_ngay_giai_quyet').focus();
		return;
	}
	var count = document.getElementById('so_ngay_giai_quyet').value;
	//Lay thong tin Nam hien thoi
	var d = new Date();		
	var p_year = d.getFullYear();
	var v_list_day_off_of_year = _LIST_DAY_OFF_OF_YEAR.split(",");
	var v_list_day = _LIST_WORK_DAY_OF_WEEK;
	
	var v_list_luner_date="";
	var v_increase_and_decrease_day = parseInt(_INCREASE_AND_DECREASE_DAY);
	var v_date,v_temp_date;	
	var v_next_date = "";	
	//Ngay nop ho so
	var v_input_date = document.getElementById('C_RECEIVE_DATE').value;
	if (!isdate(v_input_date)){
		return;
	}else{	
		var arr_input_date = v_input_date.split("/");
		v_input_date = arr_input_date[0]*1 + "/" + arr_input_date[1]*1 + "/" + arr_input_date[2]*1; 
		
	}
	for (var i=0;i<v_list_day_off_of_year.length;i++){
		v_date = v_list_day_off_of_year[i].split("/");
		if (v_date[0]=="-"){
			v_list_luner_date = list_append(v_list_luner_date,v_date[1]+"/" + v_date[2] + "/" + p_year,",");
		}else{
			v_temp_date = Solar2Lunar(v_date[1]+ "/" + v_date[2] + "/" + p_year);
			v_list_luner_date = list_append(v_list_luner_date,v_temp_date,",");
		}
	}	
	var i=0;
	v_next_date = v_input_date;
	while ((i<count - v_increase_and_decrease_day)){ //Tinh du tong so ngay tru ngay duoc nghi ra
		if ((list_have_date(v_list_luner_date,Solar2Lunar(v_next_date),",")!=1)&&(Solar2DayofWeek(v_next_date)!=7)&&(Solar2DayofWeek(v_next_date)!=8)){
			i++;			
			v_next_date = Next_Date(v_next_date);
		}else{
			v_next_date = Next_Date(v_next_date);		
		}
	}
	//Neu den han duoc lay roi ma gap ngay khong tiep thi phai cho 
	while ((list_have_element(v_list_day,Solar2DayofWeek(v_next_date),",")<0)){
		v_next_date = Next_Date(v_next_date);	
	}
	document.getElementById('C_APPOINTED_DATE').value = v_next_date;
}
//Lay ngay tiep theo cua ngay trong elTerget.value
function Next_Date(p_date) {
	if(isdate(p_date)){
		var theDate,strSeparator,arr,day,month,year;
		strSeparator = "";
		theDate = p_date;
		if (theDate.indexOf("/")!=-1) strSeparator = "/";
		if (theDate.indexOf("-")!=-1) strSeparator = "-";
		if (theDate.indexOf(".")!=-1) strSeparator = ".";
		if (strSeparator != "") {
			arr=theDate.split(strSeparator);
			day=new Number(arr[0])+1;
			month=new Number(arr[1]);
			year=new Number(arr[2]);
			if(day > 28){
				if (((month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12) && (day > 31))
				|| ((month == 4 || month == 6 || month == 9 || month == 11) && (day > 30))||(month == 2 && year % 4 !=0)||(month == 2 && year % 4 ==0 && day > 29)) 
				{
					day = 1;
					month = month+1;
				}
				if (month > 12 ){
					year = year +1;
					month = 1;
				}			
			}
			return day + strSeparator + month + strSeparator + year;
		}
    }
}
function getStatus(hrefID,hdnStatus,link){
 	hdnStatus.value = hrefID.getAttribute('value');
 	alert(hdnStatus.value);
 	window.location.href= link;
 }
// Hien thi cua so ModalDialog de chon DON VI 
// p_url: duong dan URL toi thu muc chua unit.phtml
// p_obj: ten doi tuong form nhan gia tri ngay
function show_modal_dialog_onclick_update_unit_all(p_url, p_obj, browerName){ 
	var url = _GET_HTTP_AND_HOST + p_url;	
	var sRtn;			
	sRtn = showModalDialog(url,"","dialogWidth=700px;dialogHeight=400px;status=no;scroll=no;dialogCenter=yes");		
    if (sRtn != ""){
    	if (p_obj.value != ""){
			p_obj.value = p_obj.value + ";" + sRtn;
    	}else{
    		p_obj.value = sRtn;
    	}	
    }	
}
// Ham btn_sent_invitation_unit_list duoc goi khi NSD nhan vao nut "Chon" tren ModalDialog cap nhat thong tin DON VI nhan GIAY MOI
function btn_sent_invitation_unit_list(p_obj){
	var IDList =  document.getElementsByName('chk_multiple_checkbox');
	var nameReturn = '';
	for(i =0; i< IDList.length; i++){
		if(IDList[i].checked){
			var UnitName = IDList[i].getAttribute('nameUnit');
			nameReturn = nameReturn + UnitName + '; ';
		}
	}
	if (p_obj == "unit" ){
		document.getElementById('infor_received').value = nameReturn;
	}	
	window.close();
}
function btn_edit_onclick(value,p_url){	
		item_onclick(value,p_url);
}

function btn_printview(id,showModalDialog,p_url){	
		//document.getElementById('chk_item_id').value = id;
		//document.getElementById('showModalDialog').value = showModalDialog;
		window.open(p_url+"?chk_item_id="+id+"&showModalDialog="+showModalDialog);
}
//Ham btn_print_infor_onclick duoc goi khi NSD click vao nut "In"
function btn_print_infor_onclick(p_hidden_obj, p_url){
	//Duong dan xu ly In
	p_url = p_url + '/hdn_object_id/' + p_hidden_obj.value;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }
}
//Ham btn_add_received_onclick duoc goi khi NSD bam vao nut 'Lay VB den' hoac 'Lay VB di'
function btn_sentrelate_onclick(p_url,id){
	//Duong dan xu ly In
	p_url = p_url + '?showModalDialog=1&sentID=' +id ;
	sRtn = showModalDialog(p_url,"","dialogWidth=800px;dialogHeight=450px;status=no;scroll=no;dialogCenter=yes;resizable=no");	
	//checkvalue();
}

//Ham btn_sent_onclick duoc goi khi NSD bam vao nut 'gui'
function btn_sent_onclick(p_checkbox_obj,p_url,hdn_object_id_list){
	if (!v_value_list){
		alert("Chua co van ban nao duoc chon");
		return false;
	}
		//Luu cac gia tri duoc chon vao hdn_object_id_list
		document.getElementById('hdn_save').value ='GUI';
		hdn_object_id_list.value = v_value_list;
		document.getElementsByTagName('form')[0].action = p_url;
		
		document.getElementsByTagName('form')[0].submit();
		window.close();
}

function checkrelate(arrId){
	var listId ='';
	var Id ='';
	try{
		var coutnchk = arrId.length;
		for(i = 0; i< coutnchk; i++){
			Id = arrId[i].value;
			if(arrId[i].checked){
				document.getElementById(Id).style.display = "none";		
			}else{
				if(listId == ""){
					listId =document.getElementsByName('chk_item_id')[i].value;
				}else{
					listId += "," + document.getElementsByName('chk_item_id')[i].value;
				}
			}		
		}	
		document.getElementById('hdn_list_id').value = listId;
		//alert(listId);
	}catch(e){;}
}
//Ham duoc goi khi NSD bam chon mot van ban tren modal dialog lay van ban den hoac di
function set_hidden_dialog(obj,chk_obj,oldobj,value){
	document.getElementById('hdn_doc_id').value =oldobj.value;
	oldobj.value ='';
	for(i = 0; i< chk_obj.length; i++){
		
		if(chk_obj[i].value == value){
			chk_obj[i].checked = true;
			rowid = "#" + obj.id;
			$('td').parent().removeClass('selected');
			$(obj).parent().addClass('selected');
		}else{
			chk_obj[i].checked = false;
		}		
	}
}
function item_onclick1(p_item_value,p_url){		
	row_onclick(document.getElementById('hdn_work_id'), p_item_value, p_url);
}
//Ham btn_update_onclick duoc goi khi NSD bam nut "Sua"
//- p_checkbox_obj		Ten cua checkbox
//- p_url				Dia chi URL de thuc thi
function process_update_onclick(p_checkbox_obj,p_url){
	document.getElementById('C_RESULT').optional = true ;
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de sua")
			return;
		}
		else
			item_onclick1(v_value_list,p_url);
	}
}
function submitorder_update_onclick(p_checkbox_obj,p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de sua")
			return;
		}
		else
			item_onclick1(v_value_list,p_url);
	}
}

function process_save_period(p_work_date,p_sent_date,p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if(date_compare(p_sent_date,p_work_date.value) > 0){
		alert('NGAY THUC HIEN phai nho hon hoac bang ngay hien tai');
		return ;
	}
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML			
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;	
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}
function submitorder_save_period(p_work_date,p_sent_date,p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if(date_compare(p_sent_date,p_work_date.value) > 0){
		alert('NGAY TRINH phai nho hon hoac bang ngay hien tai');
		return ;
	}
	var j = 0
	var arrStatus = document.getElementsByName('C_STATUS');
	for(i =0; i < arrStatus.length; i ++){
		if(arrStatus[i].checked == true){
			j = 1;
		}
	}
	if(j == 0){
		alert('Phai xac dinh LANH DAO trinh ky');
		return ;
	}	
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML			
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;	
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}
function 	getLeader(code,value){
	try{
		if(code.value == 'TRINH_LDUB' && value == 1){
			document.getElementById('ld_pb').style.display = "none"; 
			document.getElementById('ld_ub').style.display = "block";
			document.getElementById('C_PB_NAME').value ='';
			document.getElementById('C_UB_NAME').setAttribute("option","false");
			document.getElementById('C_UB_NAME').setAttribute("optional","");
			document.getElementById('C_PB_NAME').setAttribute("option","");
			document.getElementById('C_PB_NAME').setAttribute("optional","true");
		}
	}catch(e){;}	
	try{
		if(code.value == 'TRINH_LDPB' && value == 1){
			document.getElementById('ld_pb').style.display = "block"; 
			document.getElementById('ld_ub').style.display = "none"; 
			document.getElementById('C_UB_NAME').value ='';
			document.getElementById('C_PB_NAME').setAttribute("option","false");
			document.getElementById('C_PB_NAME').setAttribute("optional","");
			document.getElementById('C_UB_NAME').setAttribute("option","");
			document.getElementById('C_UB_NAME').setAttribute("optional","true");
		}
	}catch(e){;}
	try{
		if(code.value == 'TRINH_LDUB' && value == 2){
			document.getElementById('ld_vp').style.display = "none"; 
			document.getElementById('ld_ub').style.display = "block";
			document.getElementById('C_VP_NAME').value ='';
			document.getElementById('C_UB_NAME').setAttribute("option","false");
			document.getElementById('C_UB_NAME').setAttribute("optional","");
			document.getElementById('C_VP_NAME').setAttribute("option","");
			document.getElementById('C_VP_NAME').setAttribute("optional","true");
		}
	}catch(e){;}	
	try{
		if(code.value == 'CHUYEN_LDVP' && value == 2){
			document.getElementById('ld_vp').style.display = "block"; 
			document.getElementById('ld_ub').style.display = "none"; 
			document.getElementById('C_UB_NAME').value ='';
			document.getElementById('C_VP_NAME').setAttribute("option","false");
			document.getElementById('C_VP_NAME').setAttribute("optional","");
			document.getElementById('C_UB_NAME').setAttribute("option","");
			document.getElementById('C_UB_NAME').setAttribute("optional","true");
		}
	}catch(e){;}
	try{
		if(code.value != 'CHUYEN_LDVP' && code.value != 'TRINH_LDUB' && value == 2){
			document.getElementById('ld_vp').style.display = "none"; 
			document.getElementById('ld_ub').style.display = "none"; 
			document.getElementById('C_UB_NAME').value ='';
			document.getElementById('C_VP_NAME').value ='';
			document.getElementById('C_VP_NAME').setAttribute("option","");
			document.getElementById('C_VP_NAME').setAttribute("optional","true");
			document.getElementById('C_UB_NAME').setAttribute("option","");
			document.getElementById('C_UB_NAME').setAttribute("optional","true");
		}
	}catch(e){;}
	try{
		if(code.value == 'TRINH_LDUB' && value == 3){
			document.getElementById('ld_ub').style.display = "block";
			//document.getElementById('C_VP_NAME').value ='';
			document.getElementById('C_UB_NAME').setAttribute("option","false");
			document.getElementById('C_UB_NAME').setAttribute("optional","");
		}
	}catch(e){;}	
	try{
		if(code.value == 'LDVP_TRALAI' || code.value == 'CHO_DANG_KY' && value == 3){
			document.getElementById('ld_ub').style.display = "none";
			document.getElementById('C_UB_NAME').value ='';
			document.getElementById('C_UB_NAME').setAttribute("option","");
			document.getElementById('C_UB_NAME').setAttribute("optional","true");
		}
	}catch(e){;}	
	try{
		if(code.value == 'TRINH_LDPX'){
				document.getElementById('ld_px').style.display = "block";
			document.getElementById('C_PX_NAME').setAttribute("option","false");
			document.getElementById('C_PX_NAME').setAttribute("optional","")
		}
	}catch(e){;}	
}	

function btn_print_transferred_onclick(p_checkbox_obj,p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co VAN BAN nao duoc chon!");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot VAN BAN de in phieu  xu ly!")
			return;
		}
		else{
			//Duong dan xu ly In
			p_url = p_url + '/hdn_object_id/' + v_value_list;
			sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
		    if (sRtn!=""){
				window.open(sRtn);
		    }
		}	
	}
}
function btn_rad_onclick(p_rad_obj,p_hdn_obj){
	p_hdn_obj.value = p_rad_obj.value;	
}

//bat phim tat
shortcut.add("Ctrl+M",function() {
	document.getElementById('hdh_option').value='GHI_THEMMOI';
	btn_save_period(document.getElementById('hdn_xml_tag_list'),document.getElementById('hdn_xml_value_list'),'');
});

shortcut.add("Ctrl+E",function() {
	document.getElementById('hdh_option').value='GHI_THEMTIEP';
	btn_save_period(document.getElementById('hdn_xml_tag_list'),document.getElementById('hdn_xml_value_list'),'');
});
shortcut.add("Ctrl+Q",function() {
	document.getElementById('hdh_option').value='GHI_QUAYLAI';
	btn_save_period(document.getElementById('hdn_xml_tag_list'),document.getElementById('hdn_xml_value_list'),'');
});

shortcut.add("Ctrl+G",function() {
	document.getElementById('hdh_option').value='GHI_TAM';
	btn_save_period(document.getElementById('hdn_xml_tag_list'),document.getElementById('hdn_xml_value_list'),'');
});
