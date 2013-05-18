function btn_print_rop_onclick(p_url,sLeaderPositionName,iUnitId,sStatus,sDocType,fromDate,toDate,sfullTextSearch,sadvandeSearch){
	p_url = p_url + '?hdn_leader_name=' + sLeaderPositionName + '&hdn_unit_id=' + iUnitId + '&hdn_status=' + sStatus;
	p_url = p_url + '&hdn_doc_type=' + sDocType + '&hdn_from_date=' + fromDate + '&hdn_to_date=' + toDate + '&hdn_full_textSearch=' + sfullTextSearch;
	p_url = p_url + '&hdn_advand_search=' + sadvandeSearch;
	//alert(p_url);
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }
}
function btn_print_rop_work_onclick(p_url,sLeaderPositionName,iUnitId,sStatus,fromDate,toDate,sfullTextSearch,sadvandeSearch){
	p_url = p_url + '?hdn_leader_name=' + sLeaderPositionName + '&hdn_unit_id=' + iUnitId + '&hdn_status=' + sStatus;
	p_url = p_url + '&hdn_from_date=' + fromDate + '&hdn_to_date=' + toDate + '&hdn_full_textSearch=' + sfullTextSearch;
	p_url = p_url + '&hdn_advand_search=' + sadvandeSearch;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }
}
function btn_printresult_rop_onclick(p_url,iTongSoVb,iSumDangXuLy,iSumDaXuLyDungHan,iSumDaXuLyQuaHan,iSumQuaHanChuaXuLy,fromDate,toDate){
	p_url = p_url + '?hdn_tongsovb=' + iTongSoVb + '&hdn_dangxuly=' + iSumDangXuLy + '&hdn_daxulydunghan=' + iSumDaXuLyDungHan;
	p_url = p_url + '&hdn_daxulyquahan=' + iSumDaXuLyQuaHan + '&hdn_quahanchuaxuly=' + iSumQuaHanChuaXuLy + '&hdn_from_date=' + fromDate + '&hdn_to_date=' + toDate;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }
}
function btn_printresultunit_rop_onclick(p_url,sStatus,fromDate,toDate,iUnitId,sfullTextSearch){
	p_url = p_url + '?hdn_status=' + sStatus + '&hdn_from_date=' + fromDate + '&hdn_to_date=' + toDate + '&hdn_unit_id=' + iUnitId + '&txt_fullTextSearch=' + sfullTextSearch;
	sRtn = showModalDialog(p_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
    if (sRtn!=""){
		window.open(sRtn);
    }
}
function item_onclick_rop(p_item_value,p_url){	
		
	row_onclick(document.getElementById('hdn_object_id'), p_item_value, p_url);
}