

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

function btn_printview(id,showModalDialog,p_url){	
		//document.getElementById('chk_item_id').value = id;
		//document.getElementById('showModalDialog').value = showModalDialog;
		window.open(p_url+"?chk_item_id="+id+"&showModalDialog="+showModalDialog);
}
//Ham btn_add_received_onclick duoc goi khi NSD bam vao nut 'Lay VB den' hoac 'Lay VB di'
function btn_add_record_onclick(p_url, object_id, doc_relate_type,UrlAjax){
	//Duong dan xu ly In
	p_url = p_url + '?showModalDialog=1&hdn_object_id=' + object_id + '&hdn_docrelate_type=' + doc_relate_type;
	DialogCenter(p_url,'',800,400)
	//if(sRtn == 'CHON')
	//	document.getElementsByTagName('form')[0].submit();
}
//Ham btn_delete_record_onclick duoc goi khi NSD nhan vao nut xoa tren man hinh cap nhat van ban lien quan
function btn_delete_docrelate_onclick(UrlAjax,p_checkbox_obj, object_id){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co van ban nao duoc chon");
		return false;
	}else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?')){
			var key = 'RecordArchiveId=' + object_id + '&DocRelateIdList=' + v_value_list;
			postAJAXHTTPText(UrlAjax + '/Record/generatehtml/archives/deleterelatedoc.php',key,document.getElementById('file_name'), null);	
			var key = 'ListIdDoc=' + v_value_list + '&DocType=VB_HSLT&TableName=T_DOC_DOCUMENT_OTHER_RECORD&delimitor=!#~$|*';
			arrUrl = UrlAjax.split('/');
			postAJAXHTTPText('/' + arrUrl[3] + '/public/ajax/deleteAllFile.php',key,'', null);	
			setTimeout("getrelatedoc();",500);
		}
	}
}
//Ham btn_select_onclick duoc goi khi NSD bam vao nut 'chon'
function btn_select_onclick(UrlAjax,p_checkbox_obj,p_url,hdn_object_id_list,hdn_object_id){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co van ban nao duoc chon");
		return false;
	}else{
		//Luu cac gia tri duoc chon vao hdn_object_id_list
		document.getElementById('hdn_save').value ='CHON';
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
				DialogCenter(p_url,'',800,400)
			}
			if(p_type == 'VB_DI'){
				document.getElementById('hdn_object_id').value = v_value_list;
				var p_url = '../editsend/?showModalDialog=1&hdn_object_id=' + v_value_list + '&hdn_doc_id=' + p_doc_id +'&hdn_type=' + p_type;
				DialogCenter(p_url,'',800,400)
			}
		}
	}
}
//----------------
//Ham duoc goi khi NSD bam chon mot van ban tren modal dialog lay van ban den hoac di
function set_hidden_docrelate(obj,chk_obj,value){
	v_docrelate_id_list = document.getElementById('checkvalue').value;
	if(v_docrelate_id_list.indexOf(value) >= 0){
		alert("Van ban nay da ton tai trong ho so luu tru");
		return false;
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
function selectrow_docrelate(obj){
		v_docrelate_id_list = document.getElementById('checkvalue').value;
		if(obj.checked)
			if(v_docrelate_id_list.indexOf(obj.value) >= 0){
				alert('Van ban nay da ton tai trong ho so luu tru')
				obj.checked = false;
				$(obj).parent().parent().removeClass('selected');
				return false;
			}else{
				$(obj).parent().parent().addClass('selected');
			}
		else
			$(obj).parent().parent().removeClass('selected');
}
//Ham thuc hien truy van du lieu khi NSD bam nut tim kiem tren modal dialog
function getAllReceivedDocAjax(UrlAjax,ajaxFileName,sOwnerId){
	var key = 'fullTextSearch=' + document.getElementById('txtfullTextSearch').value + '&curentPage=1' + '&rowOnPage=' + document.getElementById('cboRowOnPage').value + '&doctype=' + document.getElementById('C_DOC_TYPE').value + '&doccate=' + document.getElementById('C_DOC_CATE').value + '&OwnerId=' + sOwnerId + '&year=' + document.getElementById('year').value;
	if(document.getElementById('txtfullTextSearch').value != '' || document.getElementById('C_DOC_TYPE').value != '' || document.getElementById('C_DOC_CATE').value != '')
		postAJAXHTTPText(UrlAjax + '/Record/generatehtml/archives/' + ajaxFileName + '.php',key, document.getElementById('divtable'), null);
}
//Ham thuc hien khi NSD nhan vao cac nut tren form cap nhat ho so luu tru
function btn_save_record(p_checkbox_obj, hdn_value_obj, hdn_option_obj, p_url){
	if (verify(document.forms[0])){	
		if(hdn_option_obj.value == 'KHAC'){
			v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
			hdn_value_obj.value = v_value_list;
		}
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}
//Ham btn_print_infor_onclick duoc goi khi NSD click vao nut "In"
function btn_print_record_onclick(p_url,object_id){
	//Duong dan xu ly In
	p_url = p_url + '?showModalDialog=1&hdn_object_id=' + object_id;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }	
}
// Ham checkbox_all_item_id_docrelate duoc goi khi nguoi su dung click vao nut checkall tren man hinh cap nhat van ban lien quan
function checkbox_all_item_id_docrelate(p_chk_obj){
	//remove class cua tat ca cac tr trong table
	v_docrelate_id_list = document.getElementById('checkvalue').value;
	$('tr').removeClass('selected');
	try{
		v_count = p_chk_obj.length;
		if(v_count){
			if(document.forms[0].chk_all_item_id.checked == true){
				for(i=0;i<=v_count;i++){
					if(p_chk_obj[i].disabled == false && v_docrelate_id_list.indexOf(p_chk_obj[i].value) < 0){
						p_chk_obj[i].checked = 'checked';
						$(p_chk_obj[i]).parent().parent().addClass('selected');
					}
				}
			}else{
				for(i=0;i<p_chk_obj.length;i++){
					p_chk_obj[i].checked = '';
				}		
			}
		}else{
			if(document.forms[0].chk_all_item_id.checked == true){
				if(p_chk_obj.disabled == false && v_docrelate_id_list.indexOf(p_chk_obj[i].value) < 0){
					p_chk_obj.checked = 'checked';
					$(p_chk_obj).parent().parent().addClass('selected');
				}
			}else{
				p_chk_obj.checked = '';
			}
		}
	}catch(e){;}
}
//Ham thuc hien khi NSD nhan vao cac nut tren form cap nhat van ban khac cho ho so luu tru
function btn_save_docother(hdn_option, p_url){
	//window.opener.document.getElementsByTagName('form')[0].submit();
	if(hdn_option.value != 'QUAY_LAI'){
		if (verify(document.forms[0])){	
			document.getElementsByTagName('form')[0].action = p_url;
			document.getElementsByTagName('form')[0].submit(); 
			window.opener.document.getElementsByTagName('form')[0].submit();
			if(hdn_option.value == 'GHI_QUAYLAI')
				window.close();
		}	
	}
	if(hdn_option.value == 'QUAY_LAI')
		window.close();
}
//ham luu loai van ban(VB_DEN, VB_DI) vao hidden khi NSD chon mot hang tren danh sach va danh dau mau hang duoc chon
function set_hidden_record(obj,chk_obj,value,type,hdn_type,hdn_doc_id){
	for(i = 0; i< chk_obj.length; i++){
		if(chk_obj[i].value == value){
			chk_obj[i].checked = true;
			rowid = "#" + obj.id;
			$('td').parent().removeClass('selected');
			$(obj).parent().addClass('selected');
			hdn_type.value = type;
			hdn_doc_id.value = value;
		}else{
			chk_obj[i].checked = false;
		}		
	}
}
//Ham thuc hien to mau row khi nguoi su dung bam vao nut checkbox tren danh sach van ban dien tu
//obj	Checkbox duoc chon
function selectrow_record(obj, type, hdn_type, docid, hdn_doc_id){
	if(obj.checked){
		$(obj).parent().parent().addClass('selected');
		$('td > p:.red').addClass('blue');
		hdn_type.value = type;
		hdn_doc_id.value = docid;
	}else
		$(obj).parent().parent().removeClass('selected');
}
//Ham thuc hien khi NSD click vao nut sua tren man hinh danh sach van ban lien quan den ho so luu tru
function btn_update_docother_onclick(p_checkbox_obj, p_url, object_id, doc_relate_type,doc_relate_id){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de sua")
			return;
		}else{
			if(doc_relate_type != 'VB_DI' && doc_relate_type != 'VB_DEN'){
				//Duong dan xu ly In
				p_url = p_url + '?showModalDialog=1&hdn_object_id=' + object_id + '&hdn_docrelate_type=' + doc_relate_type + '&hdn_doc_id=' + doc_relate_id;
				DialogCenter(p_url,'',800,300)
			}else if(doc_relate_type == 'VB_DI')
				alert('Không thể sửa văn bản đi')
			 else if(doc_relate_type == 'VB_DEN')
				alert('Không thể sửa văn bản đến')
		}
	}
}
// ham btn_delete_onclick() duoc goi khi NSD nhan chuot vao nut "Xoa"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_delete_record_onclick(UrlAjax, p_checkbox_obj, p_hidden_obj, p_url){
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?'))
		{
			v_value_list	   = checkbox_value_to_list(p_checkbox_obj,","); 
			p_hidden_obj.value = v_value_list;
			actionUrl(p_url);
		//	var key = 'RecordArchiveIdList=' + v_value_list;
		//	arrUrl = UrlAjax.split('/');
		//	postAJAXHTTPText(UrlAjax + '/Record/generatehtml/archives/deleterecord.php',key,document.getElementById('doc_id'), null);
			
			//checkvalue();
			//eval("postAJAXHTTPText(UrlAjax + '/Record/generatehtml/archives/delattachfileinrecord.php',key,document.getElementById('doc_id'), null);var key = 'fileNameList=' + document.getElementById('hdn_file_name_list').value;postAJAXHTTPText('/' + arrUrl[3] + '/public/ajax/deleteFileUpload.php', key, null, null);actionUrl(p_url1);");
			//setTimeout("var key = 'fileNameList=' + document.getElementById('hdn_file_name_list').value;postAJAXHTTPText('/' + arrUrl[3] + '/public/ajax/deleteFileUpload.php', key, null, null);", 500);
			//setTimeout("actionUrl(p_url1);",500);
		}
	}
}
//Ham btn_add_received_onclick duoc goi khi NSD bam vao nut 'Lay VB den' hoac 'Lay VB di'
function btn_add_docother_onclick(p_url, object_id, doc_relate_type){
	p_url = p_url + '?showModalDialog=1&hdn_object_id=' + object_id + '&hdn_docrelate_type=' + doc_relate_type;
	DialogCenter(p_url,'',800,300)
}
//Ham mo cua so o giua man hinh
function DialogCenter(pageURL, title,w,h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 

function postAJAXHTTPTextRecord(url, key, objLoading){
    var AJAXhttp;
    if (window.ActiveXObject)
    {
            AJAXhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else if (window.XMLHttpRequest)
    {
            AJAXhttp = new XMLHttpRequest();
    }
    if (!AJAXhttp)
    {
            alert("Không thể khởi tạo được AJAX object!!!");
            return;
    }
    try
    {
        if (objLoading != null)
        {
            objLoading.style.display = "block";
        }
        AJAXhttp.onreadystatechange = function(){
	        if (AJAXhttp.readyState == 4) 
	        { // Complete
                if (AJAXhttp.status == 200) 
                { 
                    if (objLoading != null)
                    {
                        objLoading.style.display = "none";
                    }
                }
                 else 
                 {
                    if (objLoading != null)
                    {
                        objLoading.style.display = "block";
                    }
                }
            }
        };
        AJAXhttp.open("POST", url);
        AJAXhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        AJAXhttp.send(key);

    }
    catch(e){
        if (objLoading != null){
            objLoading.style.display = "none";
        }
        alert("Lỗi: " + e.description + "\n" + url);
    }    
}
