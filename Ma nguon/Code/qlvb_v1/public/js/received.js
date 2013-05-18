//  p_item_value: chua ID cua doi tuong can hieu chinh
function item_onclick(p_item_value,p_url){
	row_onclick(document.getElementById('hdn_object_id'), p_item_value, p_url);
}

function btn_del_onclick(p_hdn_tag_obj, p_hdn_value_obj, p_chk_obj, p_hdn_id_list_obj, p_hdn_page_obj, p_fuseaction){
	_save_xml_tag_and_value_list(document.forms(0),p_hdn_tag_obj,p_hdn_value_obj,false);
	p_hdn_page_obj.value =1;
	//alert(checkbox_value_to_list(p_chk_obj,","));
	btn_delete_onclick(p_chk_obj,p_hdn_id_list_obj,p_fuseaction);
}

// Ham nay duoc goi khi NSD nhan vao nut "Truy van du lieu"
function btn_query_data_onclick(p_hdn_tag_obj,p_hdn_value_obj, p_hdn_page_obj, pAction){
	_save_xml_tag_and_value_list(document.forms(0), p_hdn_tag_obj, p_hdn_value_obj, false);		
	p_hdn_page_obj.value = 1;	
	actionUrl(pAction);
}

///////////////////////////////////////////////////////////////////////////////////////////
function onchange_submit(pAction){
	//document.forms(0).hdn_page.value =1;	
	_save_xml_tag_and_value_list(document.forms(0), document.forms(0).hdn_filter_xml_tag_list,document.forms(0).hdn_filter_xml_value_list, true);
	actionUrl('');
}
function set_input(){
	
}

/*
Y nghia : Tao ham hien thi thong tin chi tiet noi xu ly
*/
function show_modal_dialog_onclick_processing_place(p_url, p_obj, browerName){ 
	try{
		var url = _GET_HTTP_AND_HOST + p_url;			
		var sRtn;
		if(browerName == 'Safari'){
			dataitem = window.open(url,'','height=700px,width=400px,toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,modal=yes');
			targetitem = p_obj;
			dataitem.targetitem = targetitem;
		}else{		
			sRtn = showModalDialog(url,"","dialogWidth=700px;dialogHeight=370px;status=yes;scroll=yes;dialogCenter=yes,resizable=yes");			
		    if (sRtn!=""){
				p_obj.value = sRtn;
		    }
		}
	}catch(e){;}	
}

// Ham btn_save_doc_received duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat VB
function btn_save_doc_received(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML			
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;	
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}
//Ham btn_doc_distribution_assign_onclick duoc goi khi NSD click vao nut "Phan phoi,Phan cong" xu ly van ban
function btn_doc_distribution_assign_onclick(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	var IDList = document.getElementsByName('chk_multiple');
	var IdeaList = document.getElementsByName('txt_multiple');
	var ideaReturn = '';
	var idReturn = '';
	for(i =0; i< IDList.length; i++){
		if(IDList[i].checked){
			ideaReturn = ideaReturn + IdeaList[i].value + '!#~$|*';
			idReturn = idReturn + IDList[i].value + ',';
		}
	}
	document.getElementById('ds_lanh_dao').value = idReturn;
	document.getElementById('ds_y_kien').value = ideaReturn;
	if(document.getElementById('ds_lanh_dao').value ==""){
		alert("Phai chon LANH DAO nhan van ban!");	
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
// Ham btn_save_doc_received_distribution duoc goi khi NSD nhan vao nut "Ghi" tren form cap nhat thong tin PHAN PHOI VB
function btn_save_doc_received_distribution(p_hdn_tag_obj,p_hdn_value_obj,p_url,p_date){
	try{
		if(date_compare(p_date,document.getElementById('C_DISTRIBUTION_DATE').value) > 0){
			alert('NGAY THUC HIEN phai nho hon hoac bang ngay hien tai!');
			document.getElementById('C_DISTRIBUTION_DATE').focus();
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
// Ham btn_save_doc_received_assign duoc goi khi NSD nhan vao nut "Ghi" tren form cap nhat thong tin PHAN CONG XU LY VAN BAN (Cap DON VI)
function btn_save_doc_received_assign(p_hdn_tag_obj,p_hdn_value_obj,p_url,p_date){
	try{
		if(date_compare(p_date,document.getElementById('C_ASSIGNED_DATE').value) > 0){
			alert('NGAY THUC HIEN phai nho hon hoac bang ngay hien tai!');
			document.getElementById('C_ASSIGNED_DATE').focus();
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
// Ham btn_save_doc_received_unit_assign duoc goi khi NSD nhan vao nut "Ghi" tren form cap nhat thong tin PHAN CONG XU LY VAN BAN (Cap PHONG BAN)
function btn_save_doc_received_unit_assign(p_hdn_tag_obj,p_hdn_value_obj,p_url,p_date,p_dateleader){
	try{
		if(date_compare(p_date,document.getElementById('C_SENT_DATE').value) > 0){
			alert('NGAY THUC HIEN phai nho hon hoac bang ngay hien tai!');
			document.getElementById('C_SENT_DATE').focus();
			return false;
		}
		if(date_compare(p_dateleader,document.getElementById('C_APPOINTED_DATE').value) > 0){
			alert('HAN XU LY khong duoc dat gia tri lon hon han cua lanh dao don vi!');
			document.getElementById('C_APPOINTED_DATE').focus();
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
// Ham btn_save_doc_received_process duoc goi khi NSD nhan vao nut "Ghi" tren form cap nhat KET QUA XU LY VAN BAN
function btn_save_doc_received_process(p_date){
	//alert(document.getElementById('C_WORK_DATE').value)
	try{
		if (test_date(document.getElementById('C_WORK_DATE').value)==false){
			alert('NGAY THUC HIEN khong dung dinh dang ngay/thang/nam!');
			document.getElementById('C_WORK_DATE').focus();	
			return false;	
		}
		if(date_compare(p_date,document.getElementById('C_WORK_DATE').value) > 0){
			alert('NGAY THUC HIEN phai nho hon hoac bang ngay hien tai!');
			document.getElementById('C_WORK_DATE').focus();
			return false;
		}
		if(document.getElementById('C_RESULT').value == ''){
			alert('Phai xac dinh KET QUA XU LY!');
			document.getElementById('C_RESULT').focus();
			return false;		
		}
	}catch(e){;}
	document.getElementsByTagName('form')[0].action = '';
	document.getElementsByTagName('form')[0].submit(); 
}

//Viet ham an/hien thong tin phan phoi, phan cong xu ly
function showHideDistribution(chkObject, idObject){
	if (chkObject.checked){
		document.getElementById(idObject).style.display = 'block';
	}else{
		document.getElementById(idObject).style.display = 'none';
	}
}
function _getInvitation(subType, invitation){
	if(subType.value == "GM"){
		invitation.style.display = "block";
	}else{
		invitation.style.display = "none";
	}
}

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
	var StaffList =  document.getElementsByName('chk_staff_id'); 
	var nameReturn = '';
	var StaffIdReturn = '';
	var UnitIdReturn = '';
	var InforReceived = '';
	for(i =0; i< StaffList.length; i++){
		if(StaffList[i].checked){
			var nameStaff = StaffList[i].getAttribute('staffName');
			var StaffId = StaffList[i].value;
			StaffIdReturn = StaffIdReturn + StaffId + ',';
			nameReturn = nameReturn + nameStaff + '; ';
		}
	}
	try{		
		document.getElementById('infor_sign').value = nameReturn;
	}catch(e){;}
	try{
		document.getElementById('infor_received').value = nameReturn;
	}catch(e){;}
	window.close();
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
//Ham btn_print_transferred_onclick duoc goi khi NSD click vao nut "In phieu xu ly"
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
//Ham xu ly an hien cap nhat thong tin phan cong xu ly VB (Cap DON VI)
function showHideProcessReceivedPlace(p_value){	
	//alert(p_value.value);
	try{	
		//Truong hop VB nhan de biet
		if (p_value.value == "NHAN_DE_BIET"){	
			document.getElementById('AssignType').style.display = "none"; 
			document.getElementById('LeaderProcess').style.display = "none"; 
			document.getElementById('UnitProcess').style.display = "none"; 	
			document.getElementById('StaffReceived').style.display = "none";
			document.getElementById('UnitReceived').style.display = "none";
			document.getElementById('AppointedDate').style.display = "none";
					
			document.getElementById('C_TYPE_ASSIGN').setAttribute("option","");
			document.getElementById('C_TYPE_ASSIGN').setAttribute("optional","true");
			 	
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("option","");
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("optional","true"); 
			
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("optional","true");
			
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("optional","true");
			
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("optional","true");
		}
		//Truong hop sao luc VB
		if (p_value.value == "SAO_LUC"){
			document.getElementById('AssignType').style.display = "none"; 
			document.getElementById('LeaderProcess').style.display = "none"; 
			document.getElementById('UnitProcess').style.display = "none"; 
			document.getElementById('AppointedDate').style.display = "none"; 
			
			document.getElementById('StaffReceived').style.display = "block"; 
			document.getElementById('UnitReceived').style.display = "block"; 
			
			document.getElementById('C_TYPE_ASSIGN').setAttribute("option","");
			document.getElementById('C_TYPE_ASSIGN').setAttribute("optional","true");
			 	
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("option","");
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("optional","true"); 
			
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("optional","true");
			
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("option","false");
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("optional","");
			
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("option","false");
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("optional","");		
			
		}
		//Truong hop phai xu ly VB
		if (p_value.value == "VB_PHAI_XU_LY"){
			document.getElementById('AssignType').style.display = "block"; 
			document.getElementById('AppointedDate').style.display = "block"; 
				
			document.getElementById('StaffReceived').style.display = "none";
			document.getElementById('UnitReceived').style.display = "none";
			
			document.getElementById('C_TYPE_ASSIGN').setAttribute("option","false");
			document.getElementById('C_TYPE_ASSIGN').setAttribute("optional","");
			
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("optional","true");
			
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("optional","true");
			
			//Truong hop chuyen VB --> PCT
			if(document.getElementById('C_TYPE_ASSIGN').value == 'CHUYEN_PCT'){
				document.getElementById('LeaderProcess').style.display = "block"; 
				document.getElementById('UnitProcess').style.display = "none"; 
				
				document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("option","false");
				document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("optional","");
				
				document.getElementById('C_UNIT_NAME_LIST').setAttribute("option","");
				document.getElementById('C_UNIT_NAME_LIST').setAttribute("optional","true");
					
			}
			//Truong hop chuyen VB --> DON VI, PHONG BAN
			if(document.getElementById('C_TYPE_ASSIGN').value == 'CHUYEN_DONVI_PHONGBAN'){
				document.getElementById('LeaderProcess').style.display = "none"; 
				document.getElementById('UnitProcess').style.display = "block"; 
				
				document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("option","");
				document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("optional","true");
				
				document.getElementById('C_UNIT_NAME_LIST').setAttribute("option","false");
				document.getElementById('C_UNIT_NAME_LIST').setAttribute("optional","");
					
			}		
		}
		
		//Truong hop chuyen VB --> PCT
		if(p_value.value == 'CHUYEN_PCT'){
			document.getElementById('AssignType').style.display = "block"; 
			document.getElementById('AppointedDate').style.display = "block"; 
				
			document.getElementById('StaffReceived').style.display = "none";
			document.getElementById('UnitReceived').style.display = "none";
			
			document.getElementById('C_TYPE_ASSIGN').setAttribute("option","false");
			document.getElementById('C_TYPE_ASSIGN').setAttribute("optional","");
			
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("optional","true");
			
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("optional","true");
			
			document.getElementById('LeaderProcess').style.display = "block"; 
			document.getElementById('UnitProcess').style.display = "none"; 
			
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("option","false");
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("optional","");
			
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("optional","true");
				
		}
		//Truong hop chuyen VB --> DON VI, PHONG BAN
		if(p_value.value == 'CHUYEN_DONVI_PHONGBAN'){
			document.getElementById('AssignType').style.display = "block"; 
			document.getElementById('AppointedDate').style.display = "block"; 
				
			document.getElementById('StaffReceived').style.display = "none";
			document.getElementById('UnitReceived').style.display = "none";
			
			document.getElementById('C_TYPE_ASSIGN').setAttribute("option","false");
			document.getElementById('C_TYPE_ASSIGN').setAttribute("optional","");
			
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_STAFF_RECEIVED_LIST').setAttribute("optional","true");
			
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("option","");
			document.getElementById('C_UNIT_RECEIVED_LIST').setAttribute("optional","true");
			
			document.getElementById('LeaderProcess').style.display = "none"; 
			document.getElementById('UnitProcess').style.display = "block"; 
			
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("option","");
			document.getElementById('C_LEADER_POSITION_NAME_LIST').setAttribute("optional","true");
			
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("option","false");
			document.getElementById('C_UNIT_NAME_LIST').setAttribute("optional","");
				
		}
		
	}catch(e){;}
}
//Ham xu ly an hien cap nhat thong tin phan cong xu ly VB (Cap PHONG BAN)
function showHideProcessReceivedUnitPlace(p_value){	
	//alert(p_value.value);
	try{
		//Truong hop VB nhan de biet
		if (p_value.value == "VB_PHAI_XU_LY"){			
			document.getElementById('StaffMainProcess').style.display = "block"; 
			document.getElementById('StaffCoordinateProcess').style.display = "block"; 
			document.getElementById('AppointedDate').style.display = "block";
			
			
			document.getElementById('C_STAFF_PROCESS_MAIN_NAME_LIST').setAttribute("option","false");
			document.getElementById('C_STAFF_PROCESS_MAIN_NAME_LIST').setAttribute("optional","");
			
		}
		//Truong hop VB nhan de biet
		if (p_value.value == "NHAN_DE_BIET"){			
			document.getElementById('StaffMainProcess').style.display = "none"; 
			document.getElementById('StaffCoordinateProcess').style.display = "none"; 
			document.getElementById('AppointedDate').style.display = "none";
			
			document.getElementById('C_STAFF_PROCESS_MAIN_NAME_LIST').setAttribute("option","");
			document.getElementById('C_STAFF_PROCESS_MAIN_NAME_LIST').setAttribute("optional","true");
			
		}
			
	}catch(e){;}
}
//Ham btn_update_process_onclick duoc goi khi NSD bam nut "Ket qua xu ly"
//- p_checkbox_obj		Ten cua checkbox
//- p_url				Dia chi URL de thuc thi
function btn_update_process_onclick(p_checkbox_obj,p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co VAN BAN nao duoc chon!");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot VAN BAN de xu ly!")
			return;
		}
		else
			item_onclick(v_value_list,p_url);
	}
}
//Ham btn_distribution_assign_onclick duoc goi khi NSD bam nut "Phan phoi, phan cong"
//- p_checkbox_obj		Ten cua checkbox
//- p_url				Dia chi URL de thuc thi
function btn_distribution_assign_onclick(p_checkbox_obj,p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co VAN BAN nao duoc chon!");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot VAN BAN de thuc hien phan phoi, phan cong xu ly!")
			return;
		}
		else
			item_onclick(v_value_list,p_url);
	}
}
