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
/*
	Ham thuc hien thay the gia tri 
*/

function replace_char(str,str_search,str_replace){	
	result = '';	
	result = str.replace(new RegExp(str_search,"g" ),str_replace);	
	return  result;
}
/*
	Ham thuc hien click vao nut bao cao
*/
function btn_show_report(p_hdn_obj,p_hdn_tag_obj,p_hdn_value_obj, hdn_unitName, p_fuseaction){
	
	var v_url = '';
	var v_exporttype = document.getElementById('hdn_exporttype').value;		
	var v_report_xml_file = document.getElementById('hdn_xml_file').value;	 	
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);	
	if (verify(document.forms[0])){		
		//alert(p_hdn_value_obj.value);return;
		var tagValue = replace_char(p_hdn_value_obj.value,"/","-");	
		v_url =  p_fuseaction + '/';
		v_url = v_url + 'hdn_exporttype/' + v_exporttype;		
		//v_url = v_url + '/hdn_xml_file/' + v_report_xml_file;
		v_url = v_url + '/hdn_Report_id/' + p_hdn_obj.value;
		v_url = v_url + '/hdn_filter_xml_tag_list/' + p_hdn_tag_obj.value;
		v_url = v_url + '/hdn_filter_xml_value_list/' + tagValue ;	
		v_url = v_url + '/unitName/' + hdn_unitName.value ;	
		//alert(v_url);
		open(v_url);
	}
}


function btn_rad_onclick(p_rad_obj,p_hdn_obj){
	p_hdn_obj.value = p_rad_obj.value;	
}
function onChange_submit(obj_sel_type){
	document.getElementById('hdn_filter_xml_tag_list').value = 'report_type';
	document.getElementById('hdn_filter_xml_value_list').value = obj_sel_type.value;
	document.getElementById('fuseaction').value = "DISPLAY_SINGLE_CONDITIONS";
	document.forms[0].submit();
}
function check_value_in_form(){
	
}
function set_input(){
	
}
function save_hidden_list_item_id(p_hdn_list,p_chk_obj){
	if (checkbox_value_to_list(p_chk_obj,",")!=""){
		p_hdn_list.value = checkbox_value_to_list(p_chk_obj,",");
	}
}
///Luu lai danh sach cac the va cac gia tri co trong form
function save_list_onclick(f,hdn_obj_tag,hdn_obj_value){
	var list_tag = "";
	var list_value = "";
	var v_temp = "";
	var v_value = "";
	for (i=0;i<f.length;i++){
		var e=f.elements[i];
		if (e.value==""||e.value==null){
			v_value=" ";
		}else{
			v_value=e.value;
		}
		if (e.xml_tag_in_db &&(e.type!='radio' && e.type!='checkbox')){
			list_tag = list_append(list_tag,e.xml_tag_in_db,_SUB_LIST_DELIMITOR);
			list_value = list_append(list_value,v_value,_SUB_LIST_DELIMITOR);
		}
		if (e.xml_tag_in_db &&(e.type=='radio' || e.type=='checkbox')){
			list_tag = list_append(list_tag,e.xml_tag_in_db,_SUB_LIST_DELIMITOR);
			if (e.checked==true){
				v_temp="true";
			}else{
				v_temp="false";
			}
			list_value = list_append(list_value,v_temp,_SUB_LIST_DELIMITOR);
		}
	}
	if (hdn_obj_tag.value!=null && hdn_obj_value.value!=null){
		hdn_obj_tag.value = list_tag;
		hdn_obj_value.value = list_value;
	}
	//alert(list_value);return;
}
//Chuyen toi url
function goto_url(p_url,p_open_new_win)
{
	if (p_open_new_win==3)
		open_me(v_url, 0, 1, 1,0, 1, 0, 0, 0, 0, 800, 1280, 0, 0);
	else{
		//document.parentWindow.location = p_url;	
		//alert (document.location);
		if (p_open_new_win==2)
			window.top.location = p_url;
		else{
			window.location = p_url;
		}
	}
}
//==============================================================================================================
// open new window with some value
function open_me(url, vStatus, vResizeable, vScrollbars, vToolbar,vMenubar, vLocation, vFullscreen, vTitlebar, vCentered, vHeight, vWidth, vTop, vLeft)	 
{
	winDef = '';
	winDef = winDef.concat('status=').concat((vStatus) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('resizable=').concat((vResizeable) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('scrollbars=').concat((vScrollbars) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('toolbar=').concat((vToolbar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('menubar=').concat((vMenubar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('location=').concat((vLocation) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('fullscreen=').concat((vFullscreen) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('titlebar=').concat((vTitlebar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('height=').concat(vHeight).concat(',');
	winDef = winDef.concat('width=').concat(vWidth).concat(',');

	if (vCentered)	{
		winDef = winDef.concat('top=').concat((screen.height - vHeight)/2).concat(',');
		winDef = winDef.concat('left=').concat((screen.width - vWidth)/2);
	}
	else	{
		winDef = winDef.concat('top=').concat(vTop).concat(',');
		winDef = winDef.concat('left=').concat(vLeft);
	}
	open(url, '_blank', winDef);
}

function show_hide_report(p_rad_obj,p_rad_obj_list){
	//alert(p_rad_obj);
	arr_list_object = p_rad_obj_list.split(",");	
	//alert(p_rad_obj_list);
	v_count = arr_list_object.length;
	for(i=0;i<v_count;i++){
		if(p_rad_obj){
			if(arr_list_object[i] == p_rad_obj){	
				show_row(arr_list_object[i]);
			}
			else{
				hide_row(arr_list_object[i]);
			}
		}
		else{
			show_row(arr_list_object[i]);
		}
	}
}


function onchange_land_district_selectbox(obj_parent){		
	try{
	
		var v_land_district = document.all.phong_ban.value;	
		display_childselectbox_by_fk(obj_parent.value,document.all.phong_ban);
		set_selected(document.all.phong_ban,v_land_district);
	}catch(e){;}
}
