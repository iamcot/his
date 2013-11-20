<?php
$returnfile=$_SESSION['sess_file_return'];

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

if($parent_admit) $sTitleNr= ($_SESSION['sess_full_en']);
	else $sTitleNr = ($_SESSION['sess_full_pid']);

# Title in the toolbar
 $smarty->assign('sToolbarTitle',"$page_title $encounter_nr");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPatientRegister')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',"$page_title $encounter_nr");

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('medocs_entry.php')");

  # href for return button
 $smarty->assign('pbBack',$returnfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=show&type_nr='.$type_nr);


# Buffer extra javascript code

ob_start();

?>

<script  language="javascript">
<!-- 

<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

function popRecordHistory(table,pid) {
	urlholder="./record_history.php<?php echo URL_REDIRECT_APPEND; ?>&table="+table+"&pid="+pid;
	HISTWIN<?php echo $sid ?>=window.open(urlholder,"histwin<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
}

-->
</script>
<?php 

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

require('./gui_bridge/default/gui_tabs_medocs.php');

if($enc_obj->Is_Discharged()){

	$smarty->assign('is_discharged',TRUE);
	$smarty->assign('sWarnIcon',"<img ".createComIcon($root_path,'warn.gif','0','absmiddle').">");
	$smarty->assign('sDischarged',$LDPatientIsDischarged);

}

# Set the table columns´ classes
$smarty->assign('sClassItem','class="adm_item"');
$smarty->assign('sClassInput','class="adm_input"');

$smarty->assign('LDCaseNr',$LDAdmitNr);

$smarty->assign('sEncNrPID',$_SESSION['sess_en']);

$smarty->assign('img_source',"<img $img_source>");

$smarty->assign('LDTitle',$LDTitle);
$smarty->assign('title',$title);
$smarty->assign('LDLastName',$LDLastName);
$smarty->assign('name_last',$name_last);
$smarty->assign('LDFirstName',$LDFirstName);
$smarty->assign('name_first',$name_first);

# If person is dead show a black cross and assign death date

if($death_date && $death_date != DBF_NODATE){
	$smarty->assign('sCrossImg','<img '.createComIcon($root_path,'blackcross_sm.gif','0').'>');
	$smarty->assign('sDeathDate',@formatDate2Local($death_date,$date_format));
}

	# Set a row span counter, initialize with 7
	$iRowSpan = 7;

	if($GLOBAL_CONFIG['patient_name_2_show']&&$name_2){
		$smarty->assign('LDName2',$LDName2);
		$smarty->assign('name_2',$name_2);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_3_show']&&$name_3){
		$smarty->assign('LDName3',$LDName3);
		$smarty->assign('name_3',$name_3);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_middle_show']&&$name_middle){
		$smarty->assign('LDNameMid',$LDNameMid);
		$smarty->assign('name_middle',$name_middle);
		$iRowSpan++;
	}

$smarty->assign('sRowSpan',"rowspan=\"$iRowSpan\"");

$smarty->assign('LDBday',$LDBday);
$smarty->assign('sBdayDate',@formatDate2Local($date_birth,$date_format));
$smarty->assign('LDTuoi',$LDTuoi);
$smarty->assign('tuoi',$tuoi);
$smarty->assign('LDSex',$LDSex);
if($sex=='m') $smarty->assign('sSexType',$LDMale);
	elseif($sex=='f') $smarty->assign('sSexType',$LDFemale);

$smarty->assign('LDBloodGroup',$LDBloodGroup);
if($blood_group){
	$buf='LD'.$blood_group;
	$smarty->assign('blood_group',$$buf);
}


//Cac title can thiet
$smarty->assign('LDTongKetBenhAn',$LDMedocs1);
$smarty->assign('LDDate',$LDDate);
$smarty->assign('LDTTRaVien',$LDTtNguoiBenhRaVien);
$smarty->assign('LDTherapy',$LDTherapy);
$smarty->assign('LDDetails',$LDDetails);
$smarty->assign('LDBy',$LDBy);
$smarty->assign('LDLyDoVaoVien',$LDLyDoVaoVien);
$smarty->assign('LDKetQuaGPB',$LDKetQuaGPB);
$smarty->assign('LDChanDoanVaoVien',$LDChanDoanVaoVien);
$smarty->assign('LDPhapTri',$LDPhapTri);
$smarty->assign('LDThoiGianDieuTri',$LDThoiGianDieuTri);
$smarty->assign('LDKetQuaDieuTri',$LDKetQuaDieuTri);
$smarty->assign('LDNgayGio',$LDNgayGio);
$smarty->assign('LDPhauThuatVoCam',$LDPhauThuatVoCam);
$smarty->assign('LDBacSyPT',$LDBacSyPT);
$smarty->assign('LDBacSyGM',$LDBacSyGM);
$smarty->assign('LDPhauThuat',$LDPhauThuat);
$smarty->assign('LDThuThuat',$LDThuThuat);
$smarty->assign('LDQuaTrinhBenhLy',$LDQuaTrinhBenhLyVaDBLS);
$smarty->assign('LDTomTatKQXN',$LDTomTatKQXN);
$smarty->assign('LDHuongDieuTri',$LDHuongDieuTriTT);
$smarty->assign('LDChanDoanRaVien',$LDChanDoanRaVien);

if(file_exists($root_path.'gui/img/common/default/post_discussion.gif')){
	$TP_iconPost = '<img '.createComIcon($root_path,'comments.gif','0').'>';
}else{
	$TP_iconPost = '';
}
$menuoption ="<FONT color='#cc0000'><b>".$LDThongTinTongKet."</b><br>
<TABLE cellSpacing=0 cellPadding=0 class=\"submenu_frame\" border=0>
    <TBODY><TR><TD>
                <TABLE cellSpacing=1 cellPadding=2 border=0>
                    <TBODY>
                        <TR class=\"submenu\"><td align=center>".$TP_iconPost."</td>
                            <TD vAlign=top > <nobr>
                                <a href=\"show_medocs.php".URL_APPEND."&pid=$pid&encounter_nr=".$encounter_nr."&target=$target\">$LDMedocs1</a>
                            </nobr> </TD>
                        </TR>                     
                        <TR class=\"submenu\"><td align=center>".$TP_iconPost."</td>
                            <TD vAlign=top > <nobr>
                                <a href=\"show_soket.php".URL_APPEND."&pid=$pid&encounter_nr=".$encounter_nr."&target=$target\">$LDSoket</a>
                            </nobr> </TD>
                        </TR>
                    </TBODY>
                </TABLE>
    </TD></TR></TBODY>
</TABLE>";
$smarty->assign('sOptionsMenu',$menuoption);

//$smarty->assign('LDNo',$LDNo);

if($mode=='show'){
	if($rows){

		# Set the document list template file
		
		$smarty->assign('sDocsBlockIncludeFile','medocs/docslist_frame.tpl');
		
		$sTemp = ''; $sTemp_sur=''; $sTemp1='';
		$toggle=0; $i=0; $text_sSurgery='';
		$result->MoveFirst();
		while($row=$result->FetchRow()){
			if($toggle) $smarty->assign('sRowClass','class="wardlistrow2"');
				else $smarty->assign('sRowClass','class="wardlistrow1"');
			$toggle=!$toggle;
			if($i==0){
				$sType=$row['short_notes'];
				$smarty->assign('sDate',@formatDate2Local($row['date'],$date_format));
				$smarty->assign('sAuthor',$row['personell_name']);
				$i++;
			}		
			
			$smarty->assign('sdocItem',nl2br($row['notes']));
			$smarty->assign('bShow',true);
			
			switch($row['type_nr']){					
				case 9:	$smarty->assign('sdocTitle',$LDQuatrinhbenhly);	
						break;	
						
				case 10:	$smarty->assign('sdocTitle',$LDTomTatKQXN);								
						break;
							
				case 8: case 11: case 13: case 14: case 22: case 23:	
						if($sType=='yhct'){	
							switch($row['type_nr']){
								case 8:		$smarty->assign('sdocTitle',$LDLyDoVaoVien); break;
								case 11:	$smarty->assign('sdocTitle',$LDKetQuaGPB); break;	
								case 13:	$smarty->assign('sdocTitle',$LDChanDoanVaoVien); break;
								case 14:	$smarty->assign('sdocTitle',$LDPhapTri); break;
								case 22:	$smarty->assign('sdocTitle',$LDThoiGianDieuTri); break;
								case 23:	$smarty->assign('sdocTitle',$LDKetQuaDieuTri); break;
							}	
						}else {
							$smarty->assign('bShow',false);
							$toggle=!$toggle;
						}
						break;
							
				case 36: if($sType=='ngoaitru' || $sType=='yhct')	
								$smarty->assign('sdocTitle',$LDChanDoanRaVien); 
						else {
							$smarty->assign('bShow',false);
							$toggle=!$toggle;
						}
						break;
						
				case 37:
				case 39: 
						if($sType=='noitru' || $sType=='ngoaitru' || $sType=='khac'){	
							if($row['type_nr']==37)
								$smarty->assign('sdocTitle',$LDTherapy);
							else 
								$smarty->assign('sdocTitle',$LDTtNguoiBenhRaVien); 
						}else {
							$smarty->assign('bShow',false);
							$toggle=!$toggle;
						}
						break;
					
				case 38: if($sType=='khac'){
							$text_sSurgery .= '<tr bgcolor="#ffffff"><td>'.@formatDate2Local($row['date'],$date_format).' '.$row['time'].'</td>
													<td>'.$row['notes'].'</td>
													<td>'.$row['aux_notes'].'</td>
													<td>'.$row['aux_morenote'].'</td>
												</tr>';											
							$temp_cb = explode(',',$row['morenote']);
							if ($temp_cb[0]) $cb_pt=' checked ';
							if ($temp_cb[1]) $cb_tt=' checked ';
							
						}else {
							$smarty->assign('bShow',false);
							$toggle=!$toggle;
						}	
						break;												
						
				case 40: $smarty->assign('sdocTitle',$LDHuongDieuTriTT); 								
						break;	
						
				default: $smarty->assign('sdocTitle',$LDQuatrinhbenhly); 						
						break;
			}
			 
			ob_start();
				if($row['type_nr']<38){
					$smarty->display('medocs/docslist_row.tpl');
					$sTemp = $sTemp.ob_get_contents();
				}elseif($row['type_nr']>38){
					$smarty->display('medocs/docslist_row.tpl');
					$sTemp1 = $sTemp1.ob_get_contents();
				}
			ob_end_clean();
		}	
		
		$smarty->assign('sDocsListRows',$sTemp);
		$smarty->assign('sDocsListRows1',$sTemp1);
		
		if($text_sSurgery!=''){
			$smarty->assign('bShow',true);
			$smarty->assign('sSurgery',$text_sSurgery);
			$smarty->assign('cb_pt',$cb_pt);
			$smarty->assign('cb_tt',$cb_tt);
			ob_start();			
			$smarty->display('medocs/docslist_row2.tpl');
			$sTemp_sur = $sTemp_sur.ob_get_contents();
			ob_end_clean();			
			$smarty->assign('sDocsListRows_sur',$sTemp_sur);
		}
		
	}else{
	
		# Show no record prompt

		$smarty->assign('bShowNoRecord',TRUE);

		$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','absmiddle').'>');
		$smarty->assign('norecordyet',$norecordyet);

	}
}elseif($mode=='update'){

	# Show the record details

	# Set the include file
	$result->MoveFirst();
	$i=0; $text_sSurgery='';
	$maxelements=0;
	while($row=$result->FetchRow()){
		if($i==0){
			$sDate = $row['date'];
			$sType = $row['short_notes'];
 			$i++;
		}
		switch($row['type_nr']){
				case 8:	$smarty->assign('sLyDoVaoVien',$row['notes']); 		
						break;				
				case 9:	$smarty->assign('sQuaTrinhBenhLy',$row['notes']); 		
						break;	
				case 10:	$smarty->assign('sTomTatKQXN',$row['notes']);	
						break;
				case 11:	$smarty->assign('sKetQuaGPB',$row['notes']);	
						break;		
				case 13:	$smarty->assign('sChanDoanVaoVien',$row['notes']);	
						break;		
				case 14:	$smarty->assign('sPhapTri',$row['notes']);	
						break;		
				case 22:	$smarty->assign('sThoiGianDieuTri',$row['notes']);	
						break;		
				case 23:	$smarty->assign('sKetQuaDieuTri',$row['notes']);	
						break;		
				case 36:	$smarty->assign('sChanDoanRaVien',$row['notes']);	
						break;		
				case 37:	$smarty->assign('sTherapy',$row['notes']);	
						break;	
				case 38:	//Phau thuat, thu thuat
							$text_sSurgery .= '<tr bgcolor="#ffffff">
													<td><input type="hidden" name="pt_ngaygio'.$maxelements.'" value="'.@formatDate2Local($row['date'],$date_format).' '.$row['time'].'">'.@formatDate2Local($row['date'],$date_format).' '.$row['time'].'</td>
													<td><input type="hidden" name="pt_phuongphap'.$maxelements.'" value="'.$row['notes'].'">'.$row['notes'].'</td>
													<td><input type="hidden" name="pt_bspt'.$maxelements.'" value="'.$row['aux_notes'].'">'.$row['aux_notes'].'</td>
													<td><input type="hidden" name="pt_bsgm'.$maxelements.'" value="'.$row['aux_morenote'].'">'.$row['aux_morenote'].'</td>
													<td><a href="javascript:getInfoSurgery('.$maxelements.')"><img '.createComIcon($root_path,'info3.gif','0','absmiddle').'  title="'.$LDOptions.'"  ></a></td>
												</tr>';
											
							$temp_cb = explode(',',$row['morenote']);
							if ($temp_cb[0]) $cb_pt=' checked ';
							if ($temp_cb[1]) $cb_tt=' checked ';
							$maxelements++;
						break;	
				case 39:	$smarty->assign('sTTRaVien',$row['notes']);	
						break;	
				case 40:	$smarty->assign('sHuongDieuTri',$row['notes']);	
						break;							

						
				default: $smarty->assign('sQuaTrinhBenhLy',$row['notes']);
						break;
		}
				
	}
	
	if($text_sSurgery==''){
		$sql="SELECT yc.*, tb.* , hs.* 	
			FROM care_op_med_doc AS hs
			LEFT JOIN care_encounter_op AS tb ON tb.nr=hs.encounter_op_nr
			LEFT JOIN care_test_request_or AS yc ON yc.batch_nr=tb.batch_nr
			WHERE yc.encounter_nr='".$encounter_nr."' AND hs.result='done'";
			if($listsur=$db->Execute($sql)){
				$maxelements=$listsur->RecordCount();
				for($j=0;$j<$maxelements;$j++){
					$row_sur=$listsur->FetchRow();
					$text_sSurgery .= '<tr><td><input type="hidden" name="pt_ngaygio'.$j.'" value="'.@formatDate2Local($row_sur['date_request'],$date_format).' '.$row_sur['op_start'].'">'.@formatDate2Local($row_sur['date_request'],$date_format).' '.$row_sur['op_start'].'</td>
											<td><input type="hidden" name="pt_phuongphap'.$j.'" value="'.$row_sur['test_request'].'/'.$row_sur['method_op'].'">'.$row_sur['test_request'].'/'.$row_sur['method_op'].'</td>
											<td><input type="hidden" name="pt_bspt'.$j.'" value="'.$row_sur['person_surgery'].'">'.$row_sur['person_surgery'].'</td>
											<td><input type="text" name="pt_bsgm'.$j.'" value=""></td>
											<td><a href="javascript:getInfoSurgery('.$j.')"><img '.createComIcon($root_path,'info3.gif','0','absmiddle').'  title="'.$LDOptions.'"  ></a></td>
										</tr>';
				}
			}
			else $maxelements=0;		
	}

	$smarty->assign('cb_pt',$cb_pt);
	$smarty->assign('cb_tt',$cb_tt);	
	$smarty->assign('sSurgery',$text_sSurgery);
	
	$smarty->assign('sDocsBlockIncludeFile','medocs/form.tpl');
	

}else { # Create a new form for data entry

	# Create some default text...
	if($type_medoc=='yhct'){
		$smarty->assign('sChanDoanVaoVien',$LDtextYHCT1);
		$smarty->assign('sChanDoanRaVien',$LDtextYHCT1);
		$smarty->assign('sPhapTri',$LDtextYHCT1);
	}
	elseif($type_medoc=='ngoaitru'){	
		$smarty->assign('sChanDoanRaVien',$LDtextChanDoanRaVien);
	}
	elseif($type_medoc=='khac'){
		$text_sSurgery='';
		$sql="SELECT yc.*, tb.*, hs.* 	
			FROM care_op_med_doc AS hs
			LEFT JOIN care_encounter_op AS tb ON tb.nr=hs.encounter_op_nr
			LEFT JOIN care_test_request_or AS yc ON yc.batch_nr=tb.batch_nr
			WHERE yc.encounter_nr='".$encounter_nr."' AND hs.result='done' ";
			if($listsur=$db->Execute($sql)){
				$maxelements=$listsur->RecordCount();
				for($j=0;$j<$maxelements;$j++){
					$row_sur=$listsur->FetchRow();
					$text_sSurgery .= '<tr><td><input type="hidden" name="pt_ngaygio'.$j.'" value="'.@formatDate2Local($row_sur['date_request'],$date_format).' '.$row_sur['op_start'].'">'.@formatDate2Local($row_sur['date_request'],$date_format).' '.$row_sur['op_start'].'</td>
											<td><input type="hidden" name="pt_phuongphap'.$j.'" value="'.$row_sur['test_request'].'/'.$row_sur['method_op'].'" >'.$row_sur['test_request'].'/'.$row_sur['method_op'].'</td>
											<td><input type="hidden" name="pt_bspt'.$j.'" value="'.$row_sur['person_surgery'].'" >'.$row_sur['person_surgery'].'</td>
											<td><input type="text" name="pt_bsgm'.$j.'" ></td>
											<td><a href="javascript:getInfoSurgery('.$j.')"><img '.createComIcon($root_path,'info3.gif','0','absmiddle').'  title="'.$LDOptions.'"  ></a></td>
										</tr>';
				}
			}
			else $maxelements=0;
		//$maxelements
		$smarty->assign('sSurgery',$text_sSurgery);
	}
	
	# Set the include file	
	
	$smarty->assign('sDocsBlockIncludeFile','medocs/form.tpl');
	
	# Set form table as active form
	$smarty->assign('bSetAsForm',TRUE);

	# Collect extra javascript
}

if($mode!='show'){
	
	//Khi create & update voi cac dang ho so khac nhau
	if($type_medoc!='')
		$sType = $type_medoc;
	switch($sType){
		case 'noitru':	$smarty->assign('bNoiTru',TRUE); break;
		case 'ngoaitru':	$smarty->assign('bNgoaiTru',TRUE); break;
		case 'khac':	$smarty->assign('bKhac',TRUE); break;
		case 'yhct':	$smarty->assign('bYHCT',TRUE); break;
		
		default: 	$sType='noitru';	
					$smarty->assign('bNoiTru',TRUE); break;
	}


	//Xem thong tin them
	$smarty->assign('sXemQTBL','<a href="javascript:popInfo(9)"><img '.createLDImgSrc($root_path,'see_more.gif','0').'  title="'.$LDShowDetails.'"  ></a>');
	$smarty->assign('sXemTTKQXN','<a href="javascript:popInfo(10)"><img '.createLDImgSrc($root_path,'see_more.gif','0').'  title="'.$LDShowDetails.'"  ></a>');
	$smarty->assign('sXemPPDT','<a href="javascript:popInfo(37)"><img '.createLDImgSrc($root_path,'see_more.gif','0').'  title="'.$LDShowDetails.'"  ></a>');	
	
ob_start();
?>
<script language="javascript">
<!-- Script Begin
function chkForm(d) {			//Khong co rang buoc ve noi dung may doan text 
	if(d.date.value==""){
		alert("<?php echo $LDPlsEnterDate ?>");
		d.date.focus();
		return false;
	}else{
		return true;
	}

}
function ChangeTypeMedoc(){
	var cbx = document.getElementById('type_medoc');
	var mode = document.getElementById('mode');
	if(cbx && mode){
		window.location = "<?php echo $thisfile.URL_REDIRECT_APPEND; ?>&encounter_nr=<?php echo $encounter_nr; ?>&mode="+mode.value+"&type_medoc="+cbx.value;
	}
}
function popInfo(type_nr){
	if(type_nr==9){
		//Xem qua trinh benh ly 
		urlholder="../pdfmaker/dieuduong/phieuchamsoc.php"+"<?php echo URL_APPEND."&pn=".$encounter_nr; ?>";
		window.open(urlholder,"Detail","width=800,height=400,menubar=no,resizable=yes,scrollbars=yes");		
	}
	else if(type_nr==10){
		//Tom tat xet nghiem CLS
		urlholder="../nursing/nursing-station-patientdaten-nolabreport.php"+"<?php echo URL_APPEND."&pn=".$encounter_nr."&nodoc=labor"; ?>";
		window.open(urlholder,"Detail","width=800,height=250,menubar=no,resizable=yes,scrollbars=yes");
	}
	else if(type_nr==37){
		//Phuong phap dieu tri
		urlholder="../pdfmaker/prescription/todieutri.php"+"<?php echo URL_APPEND."&enc=".$encounter_nr; ?>";
		window.open(urlholder,"Detail","width=800,height=400,menubar=no,resizable=yes,scrollbars=yes");		
	}
}
function getInfoSurgery(i){
	
}
//  Script End -->
</script>

<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('sDocsJavaScript',$sTemp);


	//gjergji : new calendar
	require_once ('../../js/jscalendar/calendar.php');
	$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
	$calendar->load_files();
	if($mode=='update')
		$smarty->assign('sDateMiniCalendar',$calendar->show_calendar($calendar,$date_format,'date',$sDate));
	else
		$smarty->assign('sDateMiniCalendar',$calendar->show_calendar($calendar,$date_format,'date',date('Y-m-d')));
	//end gjergji
	
	$smarty->assign('TP_user_name',$_SESSION['sess_user_name']);

	# Collect hidden inputs
	
	ob_start();

?>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" id="mode" value="<? echo $mode ?>">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="edit" value="<?php echo $edit; ?>">
<input type="hidden" name="is_discharged" value="<?php echo $is_discharged; ?>">
<input type="hidden" name="maxelements" id="maxelements" value="<?php echo $maxelements; ?>">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('sHiddenInputs',$sTemp);

	//Combobox Type Medoc
	$selectnt=''; $selectngt=''; $selectkhac=''; $selectyhct='';
	
	if($sType=='')
		$sType = $type_medoc;
		
	if($sType=='noitru') $selectnt=' selected ';			
	else if($sType=='ngoaitru') $selectngt=' selected ';
	else if($sType=='khac') $selectkhac=' selected ';
	else if($sType=='yhct') $selectyhct=' selected ';
	$cbx = '<select id="type_medoc" name="type_medoc" onChange="ChangeTypeMedoc()">
				<option value="noitru" '.$selectnt.' >'.$LDBenhAnNoiTru.'</option>
				<option value="ngoaitru" '.$selectngt.' >'.$LDBenhAnNgoaiTru.'</option>
				<option value="khac" '.$selectkhac.' >'.$LDBenhAnKhac.'</option>
				<option value="yhct" '.$selectyhct.' >'.$LDBenhAnYHCT.'</option>
			</select>';		
		
	$smarty->assign('cbxTypeMedoc',$cbx);		

	
} 


if(($mode=='show')&&!$enc_obj->Is_Discharged()&&$result->RecordCount()){
	//Cap nhat
	$smarty->assign('sNewLinkIcon','<img '.createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle').'>');
	$smarty->assign('sNewRecLink','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$encounter_nr.'&target='.$target.'&mode=update&type_nr='.$type_nr.'">'.$LDUpdateRecord.'</a>');
	//In an
	$smarty->assign('sPdfLinkIcon','<img '.createComIcon($root_path,'icon_acro.gif','0','absmiddle').'>');
	$smarty->assign('sMakePdfLink','<a href="'.$root_path."modules/pdfmaker/medocs/tongketbenhan.php".URL_APPEND."&enc=".$encounter_nr."&mnr=".$nr.'&target='.$target.'&type_nr='.$type_nr.'">'.$LDPrintPDFDoc.'</a>');
	
} else if($mode=='update'){		
	
	$smarty->assign('pbBottomBack','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$encounter_nr.'&target='.$target.'&mode=show&type_nr='.$type_nr.'&type_medoc='.$type_medoc.'"><img '.createLDImgSrc($root_path,'back2.gif','0').'  title="'.$LDBack2TongKet.'"  ></a>&nbsp;');

} else {	//create new
	if($mode=='show' &&!$enc_obj->Is_Discharged() ){
		$smarty->assign('sNewLinkIcon','<img '.createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle').'>');
		$smarty->assign('sNewRecLink','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$encounter_nr.'&target='.$target.'&mode=create&type_nr='.$type_nr.'">'.$LDEnterNewRecord.'</a>');
	}else {
		$smarty->assign('pbBottomBack','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$encounter_nr.'&target='.$target.'&mode=show&type_nr='.$type_nr.'"><img '.createLDImgSrc($root_path,'back2.gif','0').'  title="'.$LDBack2TongKet.'"  ></a>&nbsp;');	
	}
}

$smarty->assign('pbBottomClose','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'close2.gif','0').'  title="'.$LDCancelClose.'"  ></a>');

$smarty->assign('sMainBlockIncludeFile','medocs/main_soket.tpl');

$smarty->display('common/mainframe.tpl');

?>
