

function btn_del_onclick(p_hdn_tag_obj, p_hdn_value_obj, p_chk_obj, p_hdn_id_list_obj, p_hdn_page_obj, p_fuseaction){
	_save_xml_tag_and_value_list(document.forms[0],p_hdn_tag_obj,p_hdn_value_obj,false);
	p_hdn_page_obj.value =1;
	//alert(checkbox_value_to_list(p_chk_obj,","));
	btn_delete_onclick(p_chk_obj,p_hdn_id_list_obj,p_fuseaction);
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

// ham btn_delete_onclick() duoc goi khi NSD nhan chuot vao nut "Xoa"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
/*function btn_delete_onclick(p_checkbox_obj, p_hidden_obj, p_url){
	alert('hlo')
	//_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
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
*/
// Ham btn_save_invitation duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat thong tin CAP NHAT GIAY MOI
function btn_save_sendreceived(p_hdn_tag_obj,p_hdn_value_obj,p_url,p_dateNow){
	try{
		if(date_compare(p_dateNow,document.getElementById('C_RELEASE_DATE').value) > 0){
			alert('NGAY PHAT HANH phai nho hon hoac bang ngay hien tai');
			document.getElementById('C_RELEASE_DATE').focus();
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
			nameReturn = nameReturn + nameStaff + ';';
		}
	}	
	
	for(i =0; i< IDList.length; i++){
		if(IDList[i].checked){
			var UnitName = IDList[i].getAttribute('nameUnit');
			var UnitId = IDList[i].value;
			UnitIdReturn = UnitIdReturn + UnitId + ',';
			nameReturn = nameReturn + UnitName + ';';
		}
	}
	if (p_obj == "unit" ){
		InforReceived = nameReturn +'!@~!'+ StaffIdReturn +'!@~!'+ UnitIdReturn;
		document.getElementById('infor_received').value = InforReceived;
		document.getElementById('kinh_gui').value = nameReturn; 
		document.getElementById('can_bo').value = StaffIdReturn;
		document.getElementById('don_vi_nhan').value = UnitIdReturn;
	}
	if (p_obj == "staff" ){
		InforReceived = nameReturn +'!@~!'+ StaffIdReturn +'!@~!'+ UnitIdReturn;
		document.getElementById('infor_invite').value = InforReceived;
		document.getElementById('kinh_moi').value = nameReturn; 
	}
	if (p_obj == "sign" ){
		InforReceived = nameReturn +'!@~!'+ StaffIdReturn +'!@~!'+ UnitIdReturn;
		document.getElementById('infor_sign').value = InforReceived;
		document.getElementById('nguoi_ky').value = nameReturn; 
	}		
	window.close();
}
//Ham btn_invitation_infor_onclick duoc goi khi NSD click vao nut "Theo doi thong tin"; "Xem giay moi"
function btn_invitation_infor_onclick(p_checkbox_obj, p_hidden_obj, p_url){
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co GIAY MOI nao duoc chon!");
	}
	else{		
		p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
		actionUrl(p_url);
			
	}
}
function btn_printview(id,showModalDialog,p_url){	
		//document.getElementById('chk_item_id').value = id;
		//document.getElementById('showModalDialog').value = showModalDialog;
		window.open(p_url+"?chk_item_id="+id+"&showModalDialog="+showModalDialog);
}
//Ham btn_print_infor_onclick duoc goi khi NSD click vao nut "In"
function btn_print_infor_onclick(p_hidden_obj, p_docid, p_type, p_url){
	//Duong dan xu ly In
	p_url = p_url + '/?hdn_object_id=' + p_hidden_obj.value + '&hdn_doc_id=' + p_docid + '&hdn_type=' + p_type;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }	
}
//Ham btn_add_received_onclick duoc goi khi NSD bam vao nut 'Lay VB den' hoac 'Lay VB di'
function btn_add_sendreceived_onclick(p_url){
	//Duong dan xu ly In
	p_url = p_url + '?showModalDialog=1/';
	DialogCenter(p_url,'',800,560);
}
//Ham btn_sent_onclick duoc goi khi NSD bam vao nut 'gui'
function btn_sent_onclick(p_checkbox_obj,p_url,hdn_object_id_list){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co van ban nao duoc chon");
		return false;
	}
	if(document.getElementById('C_STAFF_ID_LIST').value =='' && document.getElementById('C_UNIT_ID_LIST').value =='' ){
		alert("Phai xac dinh thong tin noi nhan");
		return false;
	}else{
		//Luu cac gia tri duoc chon vao hdn_object_id_list
		document.getElementById('hdn_save').value ='GUI';
		hdn_object_id_list.value = v_value_list;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit();
		window.opener.document.getElementsByTagName('form')[0].submit();
		window.close();
	}
}
//Ham btn_sent_later_onclick duoc goi khi NSD bam vao nut 'ghi tam'
function btn_sent_later_onclick(p_checkbox_obj,p_url,hdn_object_id_list){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co van ban nao duoc chon");
		return false;
	}else{
		//Luu cac gia tri duoc chon vao hdn_object_id_list
		document.getElementById('hdn_save').value ='GHI_TAM';
		hdn_object_id_list.value = v_value_list;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit();
		window.opener.document.getElementsByTagName('form')[0].submit();
		window.close();
	}	
}
//Ham btn_update_onclick duoc goi khi NSD bam nut "Sua" tren man hinh danh sach gui nhan van ban
//- p_checkbox_obj		Ten cua checkbox
//- p_url				Dia chi URL de thuc thi
function btn_update_send_received_onclick(p_checkbox_obj,p_type,p_doc_id){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de sua")
			return;
		}else{
			if(p_type == 'VB_DEN'){
				document.getElementById('hdn_object_id').value = v_value_list;
				var p_url = '../editreceived/?showModalDialog=1&hdn_object_id=' + v_value_list + '&hdn_doc_id=' + p_doc_id +'&hdn_type=' + p_type;
				DialogCenter(p_url,'',800,560);
			}
			if(p_type == 'VB_DI'){
				document.getElementById('hdn_object_id').value = v_value_list;
				var p_url = '../editsend/?showModalDialog=1&hdn_object_id=' + v_value_list + '&hdn_doc_id=' + p_doc_id +'&hdn_type=' + p_type;
				DialogCenter(p_url,'',800,560);
			}
			if(p_type == ''){
				p_url = '../edit';
				item_onclick(v_value_list,p_url);
			}	
		}
	}
}
//----------------
//ham luu loai van ban(VB_DEN, VB_DI) vao hidden khi NSD chon mot hang tren danh sach va danh dau mau hang duoc chon
function set_hidden_send_receidved(obj,chk_obj,hdn_obj,value,type,hdn_type,docid,hdn_doc_id){
	hdn_obj.value ="";
	for(i = 0; i< chk_obj.length; i++){
		if(chk_obj[i].value == value){
			chk_obj[i].checked = true;
			hdn_obj.value = value;
			rowid = "#" + obj.id;
			$('td').parent().removeClass('selected');
			$(obj).parent().addClass('selected');
			hdn_type.value = type;
			hdn_doc_id.value = docid;
		}else{
			chk_obj[i].checked = false;
		}		
	}
}
//Ham duoc goi khi NSD bam chon mot van ban tren modal dialog lay van ban den hoac di
function set_hidden_dialog(obj,chk_obj,value,oldobj){
	if(value != oldobj.value && oldobj.value !=""){
		var r=confirm("Ban muon thay the van ban dang cho gui?");
		if(r == true){
			document.getElementById('hdn_doc_id_old').value =oldobj.value;
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
		else{
			return false;
		}
	}else{
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
}
//Ham thuc hien to mau row khi nguoi su dung bam vao nut checkbox tren modal dialog lay van ban den hoac di
//obj	Checkbox duoc chon
function selectrow_dialog(obj, docid, oldobj){
	try{
		if(document.getElementById('hdn_doc_id_old').value == ''){
			var r=confirm("Ban muon thay the van ban dang cho gui?");
			if(r == true){
				document.getElementById('hdn_doc_id_old').value =oldobj.value;
				oldobj.value ='';
				$(obj).parent().parent().removeClass('selected');
			}else{
				obj.checked = true;
				$(obj).parent().parent().addClass('selected');
				$('td > p:.red').addClass('blue');
			}
		}else{
			if(obj.checked){
			$(obj).parent().parent().addClass('selected');
			$('td > p:.red').addClass('blue');
			}else
				$(obj).parent().parent().removeClass('selected');
		}
	}catch(e){
		selectrow(obj);
	}
}
//Ham thuc hien to mau row khi nguoi su dung bam vao nut checkbox tren danh sach van ban dien tu
//obj	Checkbox duoc chon
function selectrow_send_received(obj, type, hdn_type, docid, hdn_doc_id, ojid, hdn_oj_id){
	if(obj.checked){
		$(obj).parent().parent().addClass('selected');
		$('td > p:.red').addClass('blue');
		hdn_type.value = type;
		hdn_doc_id.value = docid;
		hdn_oj_id.value = ojid;
	}else
		$(obj).parent().parent().removeClass('selected');
}
//Ham thuc hien truy van du lieu khi NSD bam nut tim kiem tren modal dialog
function getAllReceivedDocAjax(UrlAjax,ajaxFileName,sOwnerId){
	var listId = '';
	var docId  =''; //id van ban duoc chon trong truong hop sua van ban
	try{
		var coutnchk = document.getElementsByName('chk_item_id').length;
		for(i = 0; i< (coutnchk); i++)
			if(document.getElementsByName('chk_item_id')[i].checked)
				listId +=document.getElementsByName('chk_item_id')[i].value + ',';
	}catch(e){;}
	docId = document.getElementById('hdn_doc_id').value;
	var key = 'fullTextSearch=' + document.getElementById('txtfullTextSearch').value + '&curentPage=1' + '&rowOnPage=' + document.getElementById('cboRowOnPage').value + '&listID=' + listId + '&OwnerId=' + sOwnerId + '&docId=' + docId;
	if(document.getElementById('txtfullTextSearch').value != '')
		postAJAXHTTPText(UrlAjax + '/sendReceived/generatehtml/documents/' + ajaxFileName + '.php',key, document.getElementById('divtable'), null);
	
}
//Ham thuc hien khi NSD bam vao nut theo doi nhan VB
function btn_ropreceived_onclick(p_checkbox_obj, p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de theo doi")
			return;
		}else{
			actionUrl(p_url);
		}
	}
}
