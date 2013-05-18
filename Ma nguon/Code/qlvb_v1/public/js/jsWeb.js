// Ham duoc goi khi NSD nhan vao nut "Cap Nhat"
function btn_save_menu(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	//if (verify(document.forms[0])){
		if((document.forms[0].C_NAME.value==null) || (document.forms[0].C_NAME.value == "") || isblank(document.forms[0].C_NAME.value)){
			alert('Phải nhập TÊN CHUYÊN MỤC!');
			document.getElementById('C_NAME').focus();
			return false;
		}
		if((document.forms[0].C_LEVEL.value != 0)&&(document.forms[0].PK_WEB_MENU.value == '')){
			alert('Phải chọn CHUYÊN MỤC GỐC!');
			document.getElementById('PK_WEB_MENU').focus();
			return false;
		}
		if((document.forms[0].C_LEVEL.value == 0)&&(document.forms[0].C_POSTISION.value == '')){
			alert('Phải chọn VỊ TRÍ HIỂN THỊ!');
			document.getElementById('C_POSTISION').focus();
			return false;
		}
		if(document.forms[0].chk_status.checked){
			document.forms[0].hdn_status.value = '1';
		}else{
			document.forms[0].hdn_status.value = '0';
		}
		if(document.forms[0].chk_view_on_web.checked){
			document.forms[0].hdn_display_web.value = '1';
		}else{
			document.forms[0].hdn_display_web.value = '0';
		}
		if(document.forms[0].chk_open_win.checked){
			document.forms[0].hdn_open_win.value = '1';
		}else{
			document.forms[0].hdn_open_win.value = '0';
		}
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	//}	
}
//Ham duoc goi khi NSD nhan vao nut "Cap Nhat"
function btn_save_article(p_url){
	if (verify(document.forms[0])){
		if(document.forms[0].chk_status.checked){
			document.forms[0].hdn_approved_status.value = '1';
		}else{
			document.forms[0].hdn_approved_status.value = '2';
		}
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}
/**
 * 
 * @param p_goto_url
 * @param p_fuseaction
 * @param p_select_obj
 * @param p_text_obj
 * @param p_hdn_obj
 */
function show_article_modal_dialog_onclick(p_url, p_text_obj, p_hdn_obj){
	v_url = p_url + '?showModalDialog=1&' + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth=900pt;dialogHeight=650pt;dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	arr_value = sRtn.split('|!~!|');
	p_text_obj.value = arr_value[1];
	p_hdn_obj.value = arr_value[0];
	document.getElementById('deletearticle').style.display = "";
}
/*
 * 
 */
function btn_select_article_onclick(p_checkbox_obj){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,"|!~!~!|");
	//alert(v_value_list);
	if (!v_value_list){
		alert("Chưa có tin bài nào được chọn");
	}else{
		arr_value = v_value_list.split("|!~!~!|");
		if (arr_value.length > 1){
			alert("Chỉ được chọn một tin bài")
			return;
		}
		window.returnValue = v_value_list;
		window.close();
	}
}
/*
 * Hieu chinh HAIDV
 * Resize kich co anh trong khung
 */
function resizeImageWithoutLink(max_width,max_height, objImage) {
	
	if (objImage.width > max_width && objImage.height > max_height)	{
		objImage.width = max_width;
		objImage.height = max_height;
	}
	if (objImage.width < max_width && objImage.height < max_height)	{
		objImage.width = max_width;
		objImage.height = max_height;
	}
	if (objImage.width > max_width && objImage.height < max_height)	{
		objImage.width = max_width;
		objImage.height = max_height;
	}
	if (objImage.width < max_width && objImage.height > max_height)	{
		objImage.width = max_width;
		objImage.height = max_height;
	}
}
/*
 * 
 */
function btn_approved_article_onclick11(p_checkbox_obj,p_permis, arrappromenu, p_hidden_obj, p_url){
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chưa có tin bài nào được chọn");
	}else{
		if (!p_checkbox_obj.length)
		{
			permis = p_permis.value;
			if ((p_checkbox_obj.checked)&&(list_have_element(arrappromenu,permis,',')<0))
			{
				alert("Bạn không có quyền duyệt tin bài này");
				return;
			}
		}
		else
		{
			for(j=0;j<p_checkbox_obj.length;j++)
			{
				permis = p_permis[j].value;
				if ((p_checkbox_obj[j].checked)&&(list_have_element(arrappromenu,permis,',')<0))
				{
					alert("Danh sách lựa chọn có chứa tin bài bạn không có quyền duyệt");
					return;
				}
			}
		}
		value_list = checkbox_value_to_list(p_checkbox_obj,",");
		p_hidden_obj.value = value_list;
		actionUrl(p_url);
	}
}
