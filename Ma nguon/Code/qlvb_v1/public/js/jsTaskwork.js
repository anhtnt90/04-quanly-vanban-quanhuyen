// Ham duoc goi khi NSD nhan vao nut "Cap Nhat"
function btn_save_task_work(p_url){
		if((document.forms[0].C_TITLE.value==null) || (document.forms[0].C_TITLE.value == "") || isblank(document.forms[0].C_TITLE.value)){
			alert('Phải nhập TIÊU ĐỀ!');
			document.getElementById('C_TITLE').focus();
			return false;
		}
		if(document.forms[0].hdn_option_natr.value=='GIAO_VIEC'){
			if((document.forms[0].C_STAFF_ID.value==null) || (document.forms[0].C_STAFF_ID.value == "") || isblank(document.forms[0].C_STAFF_ID.value)){
				alert('Phải nhập NGƯỜI XỬ LÝ!');
				document.getElementById('C_STAFF_ID').focus();
				return false;
			}
		}else{
			if((document.forms[0].C_STAFF_ID_LIST.value==null) || (document.forms[0].C_STAFF_ID_LIST.value == "") || isblank(document.forms[0].C_STAFF_ID_LIST.value)){
				alert('Phải nhập NGƯỜI NHẬN!');
				document.getElementById('C_STAFF_ID_LIST').focus();
				return false;
			}
		}
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
}
/**
 * 
 * @param p_date
 * @returns {Boolean}
 */
function btn_save_task_work_process(){
	try{
		if(document.getElementById('C_RESULT').value == ''){
			alert('Phải nhập NỘI DUNG!');
			document.getElementById('C_RESULT').focus();
			return false;		
		}
		if(document.forms[0].chk_status.checked){
			document.forms[0].hdn_approved_status.value = '1';
		}else{
			document.forms[0].hdn_approved_status.value = '2';
		}
	}catch(e){;}
	document.getElementsByTagName('form')[0].action = '';
	document.getElementsByTagName('form')[0].submit(); 
}
/**
 * 
 * @returns {Boolean}
 */
function set_check(svalue){
	document.forms[0].hdn_object_check_id.value = svalue;
	document.getElementsByTagName('form')[0].action = '../check/';
	document.getElementsByTagName('form')[0].submit(); 
}
/**
 * 
 * @returns {Boolean}
 */
function searchcheck(svalue){
	document.forms[0].hdn_search_check.value = svalue;
	document.getElementsByTagName('form')[0].action = '';
	document.getElementsByTagName('form')[0].submit(); 
}
/**
 * 
 * @returns {Boolean}
 */
function searchfile(svalue){
	document.forms[0].hdn_search_file.value = svalue;
	document.getElementsByTagName('form')[0].action = '';
	document.getElementsByTagName('form')[0].submit(); 
}