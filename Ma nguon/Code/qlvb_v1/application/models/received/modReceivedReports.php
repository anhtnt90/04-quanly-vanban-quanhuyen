<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 17/11/2009
 * Y nghia: Class Xu ly Report DocReceived
 */	
class Received_modReceivedReports extends Sys_DB_Connection {
	
	/**
	 *  Lay ten file xml dung de bao cao
	 *
	 * @param unknown_type $arrList
	 * @param unknown_type $sListCode
	 * @return unknown
	 */
	
	public function getFileNameXml($arrList,$sListCode){
		
		// Load thu vien xml
		Zend_Loader::loadClass("Sys_Publib_Xml");
		
		// Neu khong co file nao thi lay mac dinh
		$sFileNameXml = "Tong_hop_VB_den_Boxd.xml"; 
		
		for ($i=0;$i<sizeof($arrList);$i++){			
			if ($arrList[$i]['C_CODE'] == $sListCode){
				$sFileNameXml =  Sys_Publib_Xml::_xmlGetXmlTagValue($arrList[$i]['C_XML_DATA'],'data_list','xml_file_name');
			}
		}
		return $sFileNameXml;		
	}
	
	/**
	 * Lay danh sach loai bao cao
	 *
	 * @param unknown_type $psReportListTypeCode
	 * @return unknown
	 */
	public function getAllReportByReportType($psReportListTypeCode){			
		$sql = "Exec f_GetAllReportByReporttype  '" . $psReportListTypeCode ."'";		
		// thuc hien cap nhat du lieu vao csdl
		try {			
			$arrResult = $this->adodbQueryDataInNameMode($sql) ; 			
			
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;	
		
	}
	
	/**
	 * Ham thuc hien lay tat ca cac thong tin Bao cao
	 *
	 * @param unknown_type $psFilterXmlString
	 * @param unknown_type $psXmlFileName
	 * @return unknown
	 */
	public function getAllReportDoc($psFilterXmlString,$psXmlFileName){
		//Tao doi tuong Sys_Library
		$objSysLib = new Sys_Library();
		
		//Tao doi tuong Sys_Publib_Xml
		Zend_Loader::loadClass('Sys_Publib_Xml');
		$objSysLibXml = new Sys_Publib_Xml();

		//Doc file XML
		$psXmlStringInFile = $objSysLib->_readFile($psXmlFileName);
		//echo '$psXmlStringInFile ='.$psXmlStringInFile ; exit;
		$psSqlString = $objSysLibXml->_xmlGetXmlTagValue($psXmlStringInFile,"report_sql","sql");
		//echo '<br> psSqlString = ' . $psSqlString . '<br>';	exit;		
		// Thay the gia tri trong file xml 
		$psSqlString = $objSysLibXml->_replaceTagXmlValueInSql($psSqlString, $psXmlStringInFile, 'filter_row', $psFilterXmlString);			
		//Thuc thi lenh SQL
		try{
			$arrResult = $this->adodbQueryDataInNameMode($psSqlString);	
			//echo 'psSqlString = '.$psSqlString; exit;
		}catch (Exception $e){
			echo $e->getMessage();
		}
		//var_dump($arrResult); exit;
		return $arrResult;
				 
	}
	
	/**
	 *  Lay danh sach bao cao 
	 *
	 * @param unknown_type $arrParam
	 * @param unknown_type $sChecked
	 * @return unknown
	 */
	public function showListReport($arrParam,$sChecked){
		
		//Tao doi tuong trong trong thu vien dung chung
		$objLib = new Sys_Library();
		//
		$sHtmlRes = '<table class="list_table2"  width="99%" cellpadding="0" cellspacing="0" align="right">
					<col width="5%"><col width="95%">'		;
			
		for ($i=0;$i<sizeof($arrParam);$i++) {
			$v_report_code = $arrParam[$i]['C_CODE'];
			$v_report_name =Sys_Publib_Library::_replaceBadChar($arrParam[$i]['C_NAME']);
			//$v_report_type = $arrParam[$i]['FK_REPORTTYPYE'];						
			if ($v_current_style_name == 'odd_row'){
				$v_current_style_name = 'round_row';
			}else{
				$v_current_style_name = 'odd_row';
			}				
										
			$v_report_checked = '';
			if ($sChecked!='' && $sChecked == $v_report_code)
			$v_report_checked = 'checked';
			// In danh sach
			$sHtmlRes = $sHtmlRes.'<tr class="'. $v_current_style_name.'">'.
								'<td ><input type="radio" message="Phai chon it nhat mot BAO CAO " id = "opt_reporttype_id" name="opt_reporttype_id" readonly="true"  value="'. $v_report_code.'"' . $v_report_checked.' onClick="btn_rad_onclick(this,document.getElementById(\'hdn_Report_id\'));document.forms[0].submit();"></td>
								<td colspan="10">' .$v_report_name.'</td></tr>';
			
		}	
		
		$sHtmlRes = $sHtmlRes.'</table>'	;				
		return $sHtmlRes;
		//var_dump($sHtmlRes);
	}


}
?>
