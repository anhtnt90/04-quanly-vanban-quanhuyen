// Ham nay duoc goi khi NSD nhan vao 1 trang trong danh sach cac trang o cuoi danh sach
function page_onclick(p_page_number){
	document.getElementById('hdn_page').value = p_page_number;
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	btn_save_onclick('DISPLAY_ALL_LIST');
}

// Ham nay duoc goi khi NSD nhan vao nut "Truy van du lieu"
function btn_query_data_onclick(p_hdn_tag_obj,p_hdn_value_obj, p_hdn_page_obj, pAction){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, false);		
	p_hdn_page_obj.value = 1;	
	actionUrl(pAction);
}

function set_input(){
	
}

/// Ham item_onclick duoc goi khi NSD click vao 1 dong trong danh sach
//  p_item_value: chua ID cua doi tuong can hieu chinh
function item_onclick(p_item_value){
	row_onclick(document.getElementById('hdn_id_project'), p_item_value,"view");
}
// Ham btn_save_list duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat 1 doi tuong
function btn_save_project(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML				
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 		
	}	
}
//THuc hien khi NSD nhan vao nut XOA
function btn_del_onclick(p_hdn_tag_obj, p_hdn_value_obj, p_chk_obj, p_hdn_id_list_obj, p_hdn_page_obj, p_fuseaction){
	_save_xml_tag_and_value_list(document.forms[0],p_hdn_tag_obj,p_hdn_value_obj,false);
	p_hdn_page_obj.value =1;
	//alert(checkbox_value_to_list(p_chk_obj,","));
	btn_delete_onclick(p_chk_obj,p_hdn_id_list_obj,p_fuseaction);
}
//Hien thi doi tuong
function show_row(p_row_id){
	try{
		var v_length = eval('document.all.' + p_row_id + '.length');
		if (v_length){
			for (var i=0;i<v_length;i++){
				eval('document.all.' + p_row_id + '[i].style.display="block"');
			}
		}else{
			eval('document.all.' + p_row_id + '.style.display="block"');
		} 
	}catch(e){;} 
}
//An doi tuong
function hide_row(p_row_id){
	try{
		var v_length = eval('document.all.' + p_row_id + '.length');
		if (v_length){
			for (var i=0;i<v_length;i++){
				eval('document.all.' + p_row_id + '[i].style.display="none"');
			}
		}else{
			eval('document.all.' + p_row_id + '.style.display="none"');
		} 
	}catch(e){;} 
}
function deleteFileAttach(p_id_file_attach){
	try{
		document.getElementById('tr_exist_image').style.display = "none";		
	}catch(e){;}
	document.getElementById('tr_preview_image').style.display = "none";
	document.getElementById('tr_delete_logo').style.display = "none";
	
	document.getElementById('hdn_deleted_exist_file_id_list').value = p_id_file_attach;
	
}
// Ham btn_staff_picture_onclick duoc goi khi NSD kich nut "Browse" de chon anh chan dung CBCC
function btn_staff_picture_onclick(p_file_obj) {
	try{
		document.getElementById('tr_exist_image').style.display = "none";		
	}catch(e){;}
	document.getElementById('tr_preview_image').style.display = "none";
	document.getElementById('tr_delete_logo').style.display = "none";	
	document.getElementById('preview_image').src = p_file_obj.value;		
}


// Thuc hien khi click vao nut 
function btn_url_onclick(p_checkbox_obj, p_hidden_obj, p_goto_url){
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co CAN BO nao duoc chon!");
	}	
	else{
		v_value = checkbox_value_to_list(p_checkbox_obj,",");		
		if (v_value.indexOf(',') > 0) {
			alert('Chi duoc CAP NHAT SO YEU LY LICH moi lan mot CAN BO!');return;
		}
		else{			
			p_hidden_obj.value = v_value;
			//window.location = _WEB_PATH + p_goto_url +'&'+ p_hidden_obj + '=' + v_value;//Thuc hien URL
			actionUrl(p_goto_url);
		}
	}	
}
function btn_printSearch_onclick(p_checkbox_obj, p_hidden_obj, p_url){		
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if (list_count_element(checkbox_value_to_list(p_checkbox_obj,","),',') == 1){
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			p_url = p_url + 'hdn_object_id/' + p_hidden_obj.value;
			window.open(p_url);
		}else{
			alert('Ban chi duoc chon MOT DU AN de IN!');
			return;
		}
	}
}
function btn_backProject(url)
{
	window.location.href = 'url';
}