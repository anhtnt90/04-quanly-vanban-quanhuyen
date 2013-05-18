function btn_add_staff_from_ldap_user(){
	document.all.hdn_list_item_id.value = checkbox_value_to_list(document.all.chk_item_id,_LIST_DELIMITOR);
	returnValue = document.all.hdn_list_item_id.value;
	window.close();
}
function btn_select_staff_from_LDAP(){
	var v_count = document.all.rad_item_id.length;
	var v_current_radio_id = 0;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (document.all.rad_item_id[i].checked){
				v_current_radio_id = document.all.rad_item_id[i].value;
				break;
			}
		}
	}else{
		if (document.all.rad_item_id.checked){
			v_current_radio_id = document.all.rad_item_id.value;
		}
	}
	if (v_current_radio_id>0){
		v_url = _DSP_MODAL_DIALOG_URL_PATH;
		v_url = v_url + "?goto_url=org/index.php" + "&fuseaction=DISPLAY_ALL_LDAP_USER" + "&modal_dialog_mode=1" 
		sRtn = showModalDialog(v_url,"","dialogWidth=400PT"+";dialogHeight=300PT"+";dialogTop=80pt;status=no;scroll=no;");
		if (!sRtn) return;
		document.all.hdn_ldap_user_list.value = sRtn;
		document.all.hdn_unit_id.value = v_current_radio_id;
		document.all.fuseaction.value = "ADD_STAFF_FROM_LDAP_USER";
		document.forms[0].submit();
	}else{
		alert("phải xác định một đơn vị trước khi lấy NSD từ LDAP");	
		return;
	}
}

function btn_save_staff_onclick(p_fuseaction){
	if (!verify(document.forms[0])){
		return;
	}
	v_birthday = document.forms[0].txt_birthday.value;
	v_date = new Date(ddmmyyyy_to_mmddyyyy(v_birthday));
	v_year = v_date.getFullYear()*1;
	if (v_year==0 || v_year<=1900){
		alert("Năm sinh không hợp lệ");
		return;
	}
	if (date_compare("1/1/1900",v_birthday)<0){
		alert("Ngày sinh phải sau ngày 01/01/1900");
		return;
	}
	if (document.forms[0].txt_unit_name.value==""){
		alert("Phải xác định phòng  ban");
		return;
	}
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}

//**********************************************************************************************************************
// Ham select_parent_unit(): hien thi cua so modal dialog de chon don vi cha
//**********************************************************************************************************************
function select_parent_unit(){
	f = document.forms[0];
	v_parent_id = f.hdn_unit_id.value; // Khong hien thi don vi nay
	v_height = "280pt";
	v_width = "450pt";
	v_allow_editing_in_modal_dialog = 0; // Khong cho phep Them/sua/xoa dia ban tren cua so CHON dia ban
	v_allow_select = 1;	// Hien thi nut "Chon"
	show_modal_dialog_treeview_onclick('org/index/','DISPLAY_ALL_UNIT',f.txt_parent_name, f.hdn_parent_code, f.hdn_parent_id,v_parent_id,v_height,v_width,v_allow_editing_in_modal_dialog,v_allow_select);
}
//**********************************************************************************************************************
// Ham set_root_node_to_open(): dat che do "block" cho doi tuong goc
//**********************************************************************************************************************
function set_root_node_to_open(p_img_url){
	v_count = document.getElementsByName('str_obj').length;
	tr_obj = document.getElementsByName('str_obj');
	img = document.getElementsByName('img');
	i=0;
	while(i<v_count){
		if (tr_obj[i].getAttribute('parent_id')=="" && tr_obj[i].getAttribute('type')=="0"){
			img[i].src = p_img_url;
			tr_obj[i].style.display="block";
		}
		i++;
	}
}

//**********************************************************************************************************************
// Ham set_parent_node_to_open()
//**********************************************************************************************************************
function set_node_to_open(p_current_parent_id, p_current_id,p_img_url){
	v_current_parent_id = p_current_parent_id;	
	v_count = document.getElementsByName('str_obj').length;
	tr_obj = document.getElementsByName('str_obj');
	rad_item_id = document.getElementsByName('rad_item_id');
	div_obj = document.getElementsByName('div_obj');
	img = document.getElementsByName('img');
	i=0;
	while(i<v_count){
		if (tr_obj[i].getAttribute('item_id')==p_current_id && tr_obj[i].getAttribute('type')=="0"){
			img[i].src = p_img_url;
			div_obj[i].style.display="block";
			rad_item_id[i].checked = true;
		}
		
		// Hien thi cac node cha, ong, cu, ...
		if (tr_obj[i].getAttribute('item_id')==v_current_parent_id && tr_obj[i].getAttribute('type')=="0"){
			img[i].src = p_img_url;
			div_obj[i].style.display="block";
			
			v_current_parent_id = tr_obj[i].getAttribute('parent_id');
			i=0;
		}else{
			i++;
		}		
	}
}
//**********************************************************************************************************************
// Ham node_image_onclick()
// Y nghia: 
// - Xy ly khi NSD nhan vao nut "dong/mo" trong CAY
//**********************************************************************************************************************
function node_image_onclick(node,show_control,img_open_container_str,img_close_container_str,hdn_parent_item_id_obj,p_url) {
	//alert(node.parent_id);
	if (_MODAL_DIALOG_MODE==1)
		document.forms[0].action = "index.php?modal_dialog_mode=1";
	else
		document.forms[0].action = "index.php";
	//Neu nut (anh) la mot nut dang leaf_object thi khong co tuong tac
	if (node.type=='1') {return;}
	var nextDIV = node.nextSibling;
	//alert(nextDIV.style.display);
	while(nextDIV.nodeName != "DIV"){
		nextDIV = nextDIV.nextSibling;
	}
	//alert(nextDIV.style.display);
	//alert(nextDIV.style.display);
	if (nextDIV.style.display == 'block') {
		if (node.childNodes.length > 0) {
			if(document.all){//ie
				if(node.childNodes.item(0).nodeName == "IMG"){
					node.childNodes.item(0).src = img_open_container_str;
					try{
						select_parent_radio(document.forms[0].rad_item_id,document.forms[0].chk_item_id,node.getAttribute('id'));
					}catch(e){;}
				}
			} else{//ff
				if(node.childNodes.item(1).nodeName == "IMG"){
					node.childNodes.item(1).src = img_close_container_str;
					try{
						select_parent_radio(document.forms[0].rad_item_id,document.forms[0].chk_item_id,node.getAttribute('id'));
					}catch(e){;}
				}
			}			
		}
		//Kiem tra neu van nhan vao cung mot nut anh thi khong phai SUBMIT, nhan nut anh khac moi SUBMIT
		if (document.forms[0].hdn_item_id.value==node.getAttribute('id')){
			//Mo nut hien tai dong thoi them id cua nut do vao chuoi id can lay
			nextDIV.style.display = 'none';
			return;
		}else{
			document.forms[0].hdn_item_id.value=node.getAttribute('id');
			document.forms[0].hdn_current_position.value=node.getAttribute('level') + '_' + node.getAttribute('id');
			document.forms[0].hdn_parent_item_id.value=node.getAttribute('parent_id');	
			document.getElementsByTagName('form')[0].action = p_url;
			document.getElementsByTagName('form')[0].submit(); 
		//}
		}
	} else {
		//alert("vao day");
		if (node.childNodes.length > 0) {
			if(document.all){//ie
				if(node.childNodes.item(0).nodeName == "IMG"){
					node.childNodes.item(0).src = img_close_container_str;
					try{
						select_parent_radio(document.forms[0].rad_item_id,document.forms[0].chk_item_id,node.getAttribute('id'));
					}catch(e){;}
				}
			} else{//ff
				if(node.childNodes.item(1).nodeName == "IMG"){
					node.childNodes.item(1).src = img_open_container_str;
					try{
						select_parent_radio(document.forms[0].rad_item_id,document.forms[0].chk_item_id,node.getAttribute('id'));
					}catch(e){;}
				}
			}					
		}
		//Neu dong nut do lai thi bo id khoi chuoi
		nextDIV.style.display = 'block';
	}
}
//**********************************************************************************************************************
// Ham node_name_onclick()
// Thuc hien chuc nang hieu chinh thong tin cua mot doi tuong tren mot node cua cay
//	Input
//		1. id: Khoa chinh cua doi tuong
//		2. value: Ma viet tat cua doi tuong
//		3. text: Ten doi tuong
//		4. type: Loai doi tuong: 0-Doi tuong la phong ban; 1-Doi tuong la can bo
//**********************************************************************************************************************
function node_name_onclick(node,select_parent,v_url,v_url_edit_staff){
	if(_MODAL_DIALOG_MODE==1){
		return_value = node.id + _LIST_DELIMITOR + node.value + _LIST_DELIMITOR + node.innerText;
		//alert(return_value);
		window.returnValue = return_value;
		window.close();
	}//alert(node.getAttribute("level"));
	if (node.getAttribute("level")=="0") {
		document.getElementsByTagName('form')[0].action = v_url+'?sUnitId='+node.id;
		document.getElementsByTagName('form')[0].submit(); 	
	}else{
		document.getElementsByTagName('form')[0].action = v_url_edit_staff+'?sUnitId='+node.id;
		document.getElementsByTagName('form')[0].submit(); 	
	}
	
}

/* Ham btn_delete_of_tree_onclick lam nhiem vu xoa mot doi tuong trong danh sach trong form dsp_single*/
function delete_node_of_tree(p_radio_obj, p_checkbox_obj, p_hdn_list_item_id){
	//Xac dinh Radio dang chon
	var v_count;
	var v_current_radio_id = "";
	var v_checkId="";
	var v_parentid="";
	v_count = p_radio_obj.length;
	if(confirm('Bạn thực sự muốn xóa đối tượng đã chọn ?')){
		if (v_count){
			for(i=0;i<v_count;i++){
				if (p_radio_obj[i].checked){
					v_current_radio_id = p_radio_obj[i].getAttribute('value');
					document.forms[0].hdn_parent_item_id.value = p_radio_obj[i].getAttribute('parent_id');
					document.forms[0].hdn_item_id.value = p_radio_obj[i].getAttribute('value');
					v_parentid=p_radio_obj[i].getAttribute('parent_id');
					break;
				}
			}
		}else{
			if (p_radio_obj.checked){
				v_current_radio_id = p_radio_obj.getAttribute('value');
				document.forms[0].hdn_parent_item_id.value = p_radio_obj.getAttribute('parent_id');
				document.forms[0].hdn_item_id.value = p_radio_obj.getAttribute('value');
				v_parentid= p_radio_obj.getAttribute('parent_id');
			}
		}
		v_count = p_checkbox_obj.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				v_parentid=p_checkbox_obj[0].getAttribute('parent_id');
				if (p_checkbox_obj[i].checked){
					if(v_checkId!=""){
						v_checkId=v_checkId+",";
					}
					v_checkId=v_checkId+p_checkbox_obj[i].getAttribute('value');
				}
			}
		}else{
			if (p_checkbox_obj.checked){			
				v_checkId=v_checkId+p_checkbox_obj.getAttribute('value');
				v_parentid=p_checkbox_obj.getAttribute('parent_id');
			}
		}
		v_empty_staff=true;
		try{
			//Kiem tra cac staff co trong unit
			var v_count;
			v_count = p_checkbox_obj.length;
			if (v_count){
				for(i=0;i<v_count;i++){
					if (p_checkbox_obj[i].getAttribute('parent_id')==v_current_radio_id){
						v_empty_staff=false;
						break;
					}
				}
			}else{
				if (p_checkbox_obj.getAttribute('parent_id')==v_current_radio_id){
					v_empty_staff=false;
				}
			}
		}catch(e){;}
	
		if (v_empty_staff){
			//Xoa Unit hien thoi
			btn_delete_unit_staff('../deleteunit/?sUnit='+document.forms[0].hdn_item_id.value+'& parentId='+v_parentid);
		}else{
			//Xoa cac staff
			btn_delete_unit_staff('../deleteStaff/?sUnit='+v_checkId+'& parentId='+v_parentid);
		}	
	}
}

//**********************************************************************************************************************
// Ham btn_add_node_of_treeview()
// Chuc nang: Them moi mot Node trong cay.
// Tham so truyen vao:
//	1. p_radio_obj : la mot doi tuong Radio button.
//	2. p_hdn_item_id_obj: la mot doi tuong chua id cua Cha doi node moi them 
//	3. p_fuseaction: bien fuseaction.
//**********************************************************************************************************************

function btn_add_node_of_treeview(p_show_control,p_fuseaction){

	var v_count;
	var v_current_radio_id = "0";
	var v_parent_radio_id = "0";
	
	if (p_show_control == "true") {
		v_count = document.all.rad_item_id.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (document.all.rad_item_id[i].checked){
					v_current_radio_id = document.all.rad_item_id[i].value;
					v_parent_radio_id =  document.all.rad_item_id[i].parent_id;
					break;
				}
			}
		}else{
			if (document.all.rad_item_id.checked){
				v_current_radio_id = document.all.rad_item_id.value;
				v_parent_radio_id =  document.all.rad_item_id.parent_id;
			}
		}
	}
	document.forms[0].hdn_parent_item_id.value = v_current_radio_id;
	document.forms[0].hdn_item_id.value = '0';
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}
function btn_org_update(p_url,hdn_is_update){
	if (verify(document.forms[0])){	
		document.forms[0].hdn_is_update.value=hdn_is_update;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 		
	}	
}
/*
 * ham dung de xu ly khi thay doi gia tri trong selectbox
 * obj: doi tuong thay doi
 * sSelectValue: gia tri mac dinh cho selectbox
 * sSelectboxName: ten cua selectbox can thay doi
 * sColumnChek: ten cot can kiem tra
 * sCode: cot add vao selectbox
 * sName: ten add vao seclectbox
 * arrProfession: mang java
 */
/*
function changeSelectbox(obj,sSelectValue,sSelectboxName){
		selector = document.getElementById(sSelectboxName);
		var selectedValue = selector.value;
		if (!selectedValue)
			selectedValue = sSelectValue;
		removeAllChild(selector);
		option = document.createElement('option');
		option.value = '';
		option.appendChild(document.createTextNode('--Chọn--'));
		selector.appendChild(option);
		trainingLevel = obj.value;
		if(trainingLevel != '')
			for(i = 0; i < arrProfession.length; i++){
				if(arrProfession[i][2].search(trainingLevel) >= 0){
					option = document.createElement('option');
					option.value = arrProfession[i][0];
					option.appendChild(document.createTextNode(arrProfession[i][1]));
					selector.appendChild(option);
				}
		}
		selector.value = selectedValue;
	}
	//xoa cac phan tu trong selectbox
	function removeAllChild(obj){
		while (obj.hasChildNodes()) {
			obj.removeChild(obj.firstChild);
		}
	}
	*/
	function changeSelectbox(obj,sSelectValue,sSelectboxName){
	selector = document.getElementById(sSelectboxName);
	var selectedValue = selector.value;
	var number=0;
	if (!selectedValue)
		selectedValue = sSelectValue;
	removeAllChild(selector);
	option = document.createElement('option');
	option.value = '';
	option.appendChild(document.createTextNode('--Chọn--'));
	selector.appendChild(option);
	trainingLevel = obj.value;
	if(trainingLevel != '')
		for(i = 0; i < arrProfession.length; i++){
			if(arrProfession[i][2].search(trainingLevel) >= 0){
				//tim thay lan dau tien
				if(number==0){
					number=(arrProfession[i][3]).length;
				}
				var space='';
				for(j=0;j<((arrProfession[i][3]).length-number);j++){
					space=space+"\u2002";
				}
				
				nodevalue=space+arrProfession[i][1];
				//alert(nodevalue);
				option = document.createElement('option');
				option.value = arrProfession[i][0];
				option.appendChild(document.createTextNode(nodevalue));
				selector.appendChild(option);
			}
	}
	selector.value = selectedValue;
	}
	//xoa cac phan tu trong selectbox
	function removeAllChild(obj){
			while (obj.hasChildNodes()) {
				obj.removeChild(obj.firstChild);
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
/**
 * @author :KHOIVN
 * Date : 23/06/2011
 * @param obj : ten option 
 * @param index :vi tri
 * @returns : Ham xu ly Check/Not Check khi NSD nhan vao tieu de cua option tuong ung
 */
function btn_checkOrNotOption(obj,index){
	//alert('OK');
	document.getElementsByName(obj)[index].checked=true;
}

function ResetSearch(){
	document.getElementById('hdn_current_page').value = "1";
}
function checkvalue(){
	if(document.getElementById('txtfullTextSearch').value != "" || document.getElementById('C_TRAINING_LEVEL_INDEX').value != "" || document.getElementById('C_ENROLLMENT_YEAR_INDEX').value != "" || document.getElementById('C_ENROLLMENT_TIME_INDEX').value != ""){
		actionUrl('');
	}
}
function btn_them_click(){
	if(document.getElementById('hdn_item_id').value == ""){
		alert('Phải xác định đơn vị cần thêm');
		return;
	}
	else{
		actionUrl('../add/');
	}
}
function btn_them_can_bo_click(){
	if(document.getElementById('hdn_item_id').value == ""){
		alert('Phải chọn đơn vị cần thêm cán bộ');
		return;
	}
	else{
		actionUrl('../addstaff/');
	}
}
function btn_search(objOwner,objUnit,objFulltextsearch){
	if(objOwner.value == "" & objFulltextsearch.value==""){
		alert('Nhập điều kiện tìm kiếm');
		return;
	}
	else{
		document.getElementById('hdn_owner').value =objOwner.value;
		document.getElementById('hdn_unitid').value =objUnit.value;
		document.getElementById('hdn_fulltextsearch').value = objFulltextsearch.value;
		document.getElementById('hdn_option').value ='1';
		actionUrl('../search/');
	}
}
function btn_delete_unit_staff($sUrl){
	actionUrl($sUrl);
}
function changeSelectbox_resetcode(){
	document.getElementById('txt_parent_code').value="";
	document.getElementById('txt_parent_name').value="";
}
function change_text_selectbox(obj){
	var selected=obj.value;
  	removeAllChild(obj);			
	for(i = 0; i < arrProfession.length; i++){
		if(arrProfession[i][0]== selected){												
			option = document.createElement('option');
			option.value = arrProfession[i][0];
			option.appendChild(document.createTextNode(arrProfession[i][1]));
			selector.appendChild(option);
	}			
	selector.value = selected;
  }	  
}
/*
Nguoi tao: Do viet Hai
Ngay tao: 04/11/2011
Y nghia: Ham gan cac gia tri an vao URL khi bat len cua so in danh sach NSD
*/
function btn_print_onclick(p_url,v_exporttype,v_unit,v_full_text_search,v_department){	
	//alert('OK');
	p_url = p_url + '?hdn_exporttype=' + v_exporttype + '&hdn_unit=' + v_unit + '&hdn_fullTextSearch='+ v_full_text_search + '&hnd_department=' + v_department;				
	sRtn = showModalDialog(p_url,"","dialogWidth=auto;dialogHeight=auto;status=no;scroll=no;dialogCenter=yes");	
	//alert(sRtn);
	if (sRtn!=""){
    	window.open(sRtn);
    }
}
function check_VN_text(obj,p_url){
	$.ajax({
		  url: p_url,type: 'POST',dataType: 'html',
		  data:'value=' + obj.value,
		  success: function(data){
			  if(data!=''){
				  alert(data);
				  obj.value='';
				  obj.focus();
			  }			
	      }
	 });
}	  
