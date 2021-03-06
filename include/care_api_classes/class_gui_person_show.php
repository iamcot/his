<?php
/**
* @package care_api
*/

/**
*  GUI person registration data show methods.
* Dependencies:
* assumes the following files are in the given path
*
*  Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/

require_once($root_path.'include/care_api_classes/class_core.php');
$thisfile = basename($_SERVER['PHP_SELF']);

class GuiPersonShow {
	# Language files to be loaded
	var $langfiles=array('prompt.php','person.php','aufnahme.php');

	# Filename of script to run in fallback state (when something goes wrong)
	var $fallbackfile = '';
	
	# Default path for fotos. Make sure that this directory exists!
	var $default_photo_path='uploads/photos/registration';
	var $photo_filename='nopic';
	
	# Filename of file running this gui
	var $thisfile = '';

	# The PID
	var $pid=0;
	
	# The text holder in front of output block
	var $pretext='';

	# The text holder after the output block
	var $posttext='';

	# Current encounter number
	var $current_encounter=0;
	
	# Person data in array
	var $data = array();
	
	# Person data object
	var $data_obj;
	
	# Person object
	var $person_obj;
	
	# Flag if data is loaded
	var $is_loaded;
	
	# Flag if the data is to be returned only as string
	var $bReturnOnly = FALSE;
	
	# Internal smarty object
	var $smarty;
	
	/**
	* Constructor
	*/
	function GuiPersonShow($pid=0, $filename='', $fallbackfile=''){
		global $thisfile, $root_path;
		if(empty($filename)) $this->thisfile = $thisfile;
			else $this->thisfile = $filename;
		if(!empty($fallbackfile)) $this->fallbackfile = $fallbackfile;
//		if(!empty($this->default_photo_path)) $this->default_photo_path = $root_path.$this->default_photo_path;

		include_once($root_path.'include/care_api_classes/class_person.php');
		$this->person_obj=& new Person($pid);
		
		if($pid){
			$this->pid =$pid;
			return $this->_load();
		}
	}
	/**
	* Sets the file name of the script to run when something goes wrong
	*/
	function setFallbackFile($target){
		$this->fallbackfile = $target;
	}
	/**
	* Sets the PID value
	*/
	function setPID($nr){
		$this->pid = $nr;
		$this->person_obj->setPID($nr);
		return $this->_load();
	}
	/**
	* Returns the death data
	*/
	function DeathDate(){
		if (isset($this->data['death_date'])) {
			if($this->data['death_date']) {
				return $this->data['death_date'];
			} else {
				return $this->person_obj->DeathDate();
			}
		} else {
			return FALSE;
		}
	}
	/**
	*  Gets the encounter number if person is currently admitted
	*/
	function CurrentEncounter(){
		global $root_path;
		if($this->current_encounter){
			return $this->current_encounter;
		}else{
			return $this->current_encounter=$this->person_obj->CurrentEncounter($this->pid);
		}
	}
	/**
	* (pre)Loads the person registration data.
	*
	* Can be checked if data is loaded with the $this->is_loaded variable
	* @access private
	*/
	function _load(){
		if($this->data_obj= $this->person_obj->getAllInfoObject()){
				$this->data=$this->data_obj->FetchRow();
			return $this->is_loaded = TRUE;
		}else{
			return $this->is_loaded = FALSE;
		}
	}
	/**
	* Function to generate demographic data dynamically depending on the global config
	*/
	function createTR($ld_text, $input_val, $colspan = 1, $vidata = FALSE){

		ob_start();
			if($vidata) $input_val="<div class=\"vi_data\">$input_val</div>";
			$sBuffer=$ld_text;
			$this->smarty->assign('sItem',$sBuffer);
			$this->smarty->assign('sColSpan2',"colspan=$colspan");
			$this->smarty->assign('sInput',$input_val);
			$this->smarty->display('registration_admission/reg_row.tpl');
			$sBuffer = ob_get_contents();
		ob_end_clean();

		//$this->toggle=!$this->toggle;

		return $sBuffer;
/*
		echo '<tr>
				<td bgColor="#eeeeee" ><FONT SIZE=-1  FACE="Arial,verdana,sans serif">'.$ld_text.':
				</td>
				<td colspan='.$colspan.' bgcolor="#ffffee"><FONT SIZE=-1  FACE="Arial,verdana,sans serif">'.$input_val.'
				</td>
			</tr>';*/
	}

	/**
	* Displays the GUI showing the data
	*/
	function display($pid=0){
		global $_SESSION, $root_path, $dbf_nodate, $newdata, $kb_other_his_array, $lang;

		$validdata = TRUE;

		if(!empty($pid)) $this->pid=$pid;

		# Load the language tables
		$lang_tables =$this->langfiles;
		include($root_path.'include/core/inc_load_lang_tables.php');

		# Load the other hospitals array
		include_once($root_path.'global_conf/other_hospitals.php');

		include_once($root_path.'include/core/inc_date_format_functions.php');

		include_once($root_path.'include/care_api_classes/class_insurance.php');
		$pinsure_obj=new PersonInsurance($this->pid);
$insurance_obj=new Insurance;
		# Get the global config for registration form of the person 
		include_once($root_path.'include/care_api_classes/class_globalconfig.php');

		$GLOBAL_CONFIG = array();

		$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
		$glob_obj->getConfig('person_%');

		//extract($GLOBAL_CONFIG);

		if(empty($this->pid)) {
			
			$validdata = FALSE;
		
		}else{

			//if($data_obj=&$this->person_obj->getAllInfoObject()){
			//	$this->data=$data_obj->FetchRow();
			if($this->is_loaded){
				extract($this->data);

				# Get related insurance data
				/*$p_insurance=&$pinsure_obj->getPersonInsuranceObject($this->pid);

				if($p_insurance==FALSE) {
					$insurance_show=true;
				} else {
					if(!$p_insurance->RecordCount()) {
						$insurance_show=true;
					} elseif ($p_insurance->RecordCount()==1){
						$buffer= $p_insurance->FetchRow();
						extract($buffer);

						$insurance_show=true;
						# Get insurace firm name
						$insurance_firm_name=$pinsure_obj->getFirmName($insurance_firm_id);
					} else {
						$insurance_show=FALSE;
					}
				}*/
				if (isset($insurance_class_nr)) {
					//TODO $insurance_class_nr might not be set and brings to an undefined situation for insurances. 
					$insurance_class=$pinsure_obj->getInsuranceClassInfo($insurance_class_nr);
				}
					$insurance_firm_name=$insurance_obj->getFirmName($insurance_firm_id);
				# Check if person is currently admitted
				$this->current_encounter=$this->person_obj->CurrentEncounter($this->pid);

				# update history of the record
				if(empty($newdata)) @$this->person_obj->setHistorySeen($_SESSION['sess_user_name']);
			
				# Check whether config foto path exists, else use default path
				$photo_path = (is_dir($root_path.$GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $this->default_photo_path;

			}else{
				$validdata = FALSE; 
			}

		}
		
		include_once($root_path.'gui/smarty_template/smarty_care.class.php');
		$this->smarty = new smarty_care('common',FALSE);
		
		if($validdata){

		include_once($root_path.'include/core/inc_photo_filename_resolve.php');

		############ Here starts the GUI output ##################

		# Start Smarty templating here
		# Create smarty object without initiliazing the GUI (2nd param = FALSE)


		
		# Set from width
		$this->smarty->assign('sFormWidth','width="100%"');

		$img_male=createComIcon($root_path,'spm.gif','0');
		$img_female=createComIcon($root_path,'spf.gif','0');

		if(!empty($this->pretext)) $this->smarty->assign('pretext',$this->pretext);

		$this->smarty->assign('LDRegistryNr',$LDRegistryNr);
		$this->smarty->assign('pid',$pid);

		if(file_exists($root_path.'cache/barcodes/pn_'.$pid.'.png')){
			$this->smarty->assign('sBarcodeImg','<img src="'.$root_path.'cache/barcodes/pn_'.$pid.'.png" border=0 width=180 height=35>');
		}else{
			$this->smarty->assign('sBarcodeImg',"<img src='".$root_path."classes/barcode/image.php?code=".$pid."&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=pn' border=0 width=0 height=0>");
			$this->smarty->assign('sBarcodeImg',"<img src='".$root_path."classes/barcode/image.php?code=".$pid."&style=68&type=I25&width=180&height=50&xres=2&font=5' border=0 width=180  height=35>");
		}

		$this->smarty->assign('img_source',$img_source);
		
		//gjergji
		if ($photo_filename=='' || $photo_filename=='nopic' || !file_exists($root_path.$photo_path.'/'.$photo_filename)){
			$pfile = (isset($pfile)) ? $pfile : '';
			$this->smarty->assign('sFileBrowserInput','<input name="photo_filename" type="file" size="15"   onChange="showpic(this)" value="'.$pfile.'">');
		}		
		//end : gjergji
		
		# iRowSpanCount counts the rows on the left of the photo image. Begin with 5 because there are 5 static rows.
		$iRowSpanCount = 5;

		$this->smarty->assign('LDRegDate',$LDRegDate);
		$this->smarty->assign('sRegDate',@date("d/m/Y",strtotime($date_reg)));

		//$iRowSpanCount++;
		$this->smarty->assign('LDRegTime',$LDRegTime);
		$this->smarty->assign('sRegTime',convertTimeToLocal(@formatDate2Local($date_reg,$date_format,0,1)));
	/*
		if (!$GLOBAL_CONFIG['person_title_hide']){
			$this->smarty->assign('LDTitle',$LDTitle);
			$this->smarty->assign('sPersonTitle','<font class="vi_data">'.$title.'</font>');
			$iRowSpanCount++;
		}
		*/
		$this->smarty->assign('LDFullName',$LDFullName);
		$this->smarty->assign('sNameLast','<font class="vi_data">'.$name_last.'</font>');
		//$iRowSpanCount++;

		# If person is dead show a black cross
		if($death_date&&$death_date!=$dbf_nodate) $sCross = '&nbsp;<img '.createComIcon($root_path,'blackcross_sm.gif','0','',TRUE).'>';
			else $sCross ='';
		//$this->smarty->assign('LDFirstName',$LDFirstName);
		$this->smarty->assign('sNameFirst','<font class="vi_data">'.$name_first.'</font>'.$sCross);

		if (!$GLOBAL_CONFIG['person_name_2_hide']&&$name_2){
			$this->smarty->assign('sName2',$this->createTR($LDName2,$name_2));
			$iRowSpanCount++;
		}
		if (!$GLOBAL_CONFIG['person_name_3_hide']&&$name_3){
			$this->smarty->assign('sName3',$this->createTR($LDName3,$name_3));
			$iRowSpanCount++;
		}
		if (!$GLOBAL_CONFIG['person_name_middle_hide']&&$name_middle){
			$this->smarty->assign('sNameMiddle',$this->createTR($LDNameMid,$name_middle));
			$iRowSpanCount++;
		}
		if (!$GLOBAL_CONFIG['person_name_maiden_hide']&&$name_maiden){
			$this->smarty->assign('sNameMaiden',$this->createTR($LDNameMaiden,$name_maiden));
			$iRowSpanCount++;
		}
		if (!$GLOBAL_CONFIG['person_name_others_hide']&&$name_others){
			$this->smarty->assign('sNameOthers',$this->createTR($LDNameOthers,$name_others));
			$iRowSpanCount++;
		}
		
		# Set the rowspan value for the photo image <td>
		$this->smarty->assign('sPicTdRowSpan',"rowspan=$iRowSpanCount");

		$this->smarty->assign('LDBday',"$LDBday:");

		if($death_date&&$death_date!=$dbf_nodate){
			$this->smarty->assign('sCrossImg',$sCross);
			$this->smarty->assign('sDeathDate','<font color="#000000">'.date("d/m/Y",strtotime($death_date)).'</font>');
		}
		
		$this->smarty->assign('sBdayInput','<font class="vi_data">'.formatDate2Local($date_birth,$date_format).'</font>');
		if(!empty($thang)){
			$this->smarty->assign('sInputTuoi','Tháng : '.$thang);
		}else{
		//$this->smarty->assign('LDTuoi',"$LDTuoi :");
			$this->smarty->assign('sInputTuoi',$LDTuoi.' : '.$tuoi);
		}
		$this->smarty->assign('LDSex', "$LDSex:");

		if($sex=="m") $this->smarty->assign('sSex','<font class="vi_data">'.$LDMale.'</font>');
			elseif($sex=="f") $this->smarty->assign('sSex','<font class="vi_data">'.$LDFemale.'</font>');
			 elseif($sex=="0") $this->smarty->assign('sSex','<font class="vi_data">Không rõ</font>');

		if (!$GLOBAL_CONFIG['person_bloodgroup_hide'] && trim($blood_group)) {
			// KB: make blood group hideable
			$this->smarty->assign('LDBloodGroup',"$LDBloodGroup");
			$buf='LD'.trim($blood_group);
			$this->smarty->assign('sBGInput',$$buf);
		}

		if (!$GLOBAL_CONFIG['person_civilstatus_hide'] && trim($civil_status)) {
			$this->smarty->assign('LDCivilStatus',"$LDCivilStatus");

			if($civil_status=="single") $sCSBuffer =  $LDSingle;
				elseif($civil_status=="married") $sCSBuffer =  $LDMarried;
					elseif($civil_status=="divorced") $sCSBuffer =  $LDDivorced;
						elseif($civil_status=="widowed") $sCSBuffer = $LDWidowed;
							elseif($civil_status=="separated") $sCSBuffer =  $LDSeparated;
							elseif($civil_status=="không rõ") $sCSBuffer =  $LDKhongRo;

			$this->smarty->assign('sCSInput',$sCSBuffer);
		}
		
		$this->smarty->assign('LDAddress',"$LDAddress");

		$this->smarty->assign('LDStreet',"$LDStreet");

		$this->smarty->assign('sStreetInput',$addr_str);

		$this->smarty->assign('LDStreetNr',"$LDStreetNr");

		$this->smarty->assign('sStreetNrInput',$addr_str_nr);

		$this->smarty->assign('LDTownCity',"$LDTownCity");
		$this->smarty->assign('sTownCityInput',$addr_citytown_name);

		$this->smarty->assign('LDZipCode',"$LDZipCode ");
		$this->smarty->assign('sZipCodeInput',$addr_zip);
		//huyen xa add by vy
		$this->smarty->assign('LDHuyenxa',"$LDHuyenxa ");
		$this->smarty->assign('sHuyenxaInput',$addr_quanhuyen_name);
		$this->smarty->assign('LDThonPhuong',"$LDThonPhuong ");
		$this->smarty->assign('sThonPhuongInput',$addr_phuongxa_name);

		if (!$GLOBAL_CONFIG['person_insurance_hide']) {
			if (!$GLOBAL_CONFIG['person_insurance_1_nr_hide']&&$insurance_show&&$insurance_nr){
				
				$this->smarty->assign('bShowInsurance',TRUE);

				$this->smarty->assign('sInsuranceNr',$this->createTR($LDInsuranceNr,$insurance_nr,2));

				if (isset($insurance_class_info)) {
					$buffer=$insurance_class_info['LD_var'];
					// check if buffer exists, then take the variable name as value and show it
					if(isset($$buffer)&&!empty($$buffer)) $this->smarty->append('sInsClasses',$$buffer);
    					else $this->smarty->append('sInsClasses',$insurance_class_info['name']);
				}
				
				$this->smarty->assign('LDInsuranceCo',$LDInsuranceCo);
				$this->smarty->assign('sInsCoNameInput',$insurance_firm_name);

				$this->createTR($LDInsuranceCo.' 1',$insurance_firm_name,2);
			}
		}
		
		//chuyen tu tiep nhan sang dk
		$this->smarty->assign('LDBillType',$LDBillType);
		$this->smarty->assign('LDMaDKKCB',$LDMaDKKCB);
		$this->smarty->assign('madk_kcbbd',$madkbd);
		$this->smarty->assign('LDTinhtrang',$LDTinhtrang);
		if($is_traituyen=='0') $this->smarty->assign('sTinhtrangInput','Không rõ');
		elseif($is_traituyen=='1') $this->smarty->assign('sTinhtrangInput','Đúng tuyến');
		elseif($is_traituyen=='2') $this->smarty->assign('sTinhtrangInput','Trái tuyến');
		if (isset($$insurance_class['LD_var'])&&!empty($$insurance_class['LD_var'])) $this->marty->assign('sBillTypeInput',$$insurance_class['LD_var']);
			else $this->smarty->assign('sBillTypeInput',$insurance_class['name']); 
			
		$this->smarty->assign('LDInsuranceNr',$LDInsuranceNr);
		if(isset($insurance_nr) && $insurance_nr) $this->smarty->assign('insurance_nr',$insurance_nr);
		if(isset($insurance_nr) && $insurance_nr) {$this->smarty->assign('is_bh',1);}else{ $this->smarty->assign('is_bh',0);}
		//add 9-11
		$this->smarty->assign('LDInsuranceStart',$LDInsuranceStart);
		if($insurance_start==DBF_NODATE){
		$this->smarty->assign('sInsStartDayInput','');
		}else{
		$this->smarty->assign('sInsStartDayInput',@formatDate2Local($insurance_start,$date_format));
		}
		$this->smarty->assign('LDInsuranceExp',$LDInsuranceExp);
		if($insurance_exp==DBF_NODATE){
		$this->smarty->assign('sInsExpDayInput','');
		}else{
		$this->smarty->assign('sInsExpDayInput',@formatDate2Local($insurance_exp,$date_format));
		if((substr($insurance_exp,0,4))< (date("Y"))){
		$this->smarty->assign('error',True);		
		}elseif(((substr($insurance_exp,0,4))==(date("Y")))){
			if((substr($insurance_exp,5,2))<(date("m"))){
			$this->smarty->assign('error',True);
			}elseif((substr($insurance_exp,5,2))==(date("m"))){
				if((substr($insurance_exp,8,2))<(date("d"))){
				$this->smarty->assign('error',True);
				}else{
				$this->smarty->assign('error',false);	
				}
			}else{
			$this->smarty->assign('error',false);	
			}
		}else{
		$this->smarty->assign('error',false);	
		}
		}
		$this->smarty->assign('LDexp','Bảo hiểm đã hết hạn vui lòng cập nhật');
		$this->smarty->assign('LDNoicap',$LDNoicap);
		$this->smarty->assign('insurance_loca',$insurance_local);
		
		$this->smarty->assign('LDInsuranceCo',$LDInsuranceCo);
		$this->smarty->assign('insurance_firm_name',$insurance_firm_name);
		//
		$this->smarty->assign('LDPhone1',$LDPhone);
		$this->smarty->assign('sPhone1',$phone_1_nr);
		$this->smarty->assign('LDCellPhone1',$LDCellPhone);
		$this->smarty->assign('sCellPhone1',$cellphone_1_nr);
		$this->smarty->assign('LDFax',$LDFax);
		$this->smarty->assign('sFax',$fax);
		$this->smarty->assign('LDEmail',$LDEmail);
		$this->smarty->assign('sEmail',"<a href=\"mailto:$email\">$email</a>");
		$this->smarty->assign('LDCitizenship',$LDCitizenship);
		$this->smarty->assign('sCitizenship',$citizenship);
		$this->smarty->assign('LDSSSNr',$LDSSSNr);
		$this->smarty->assign('sSSSNr',$sss_nr);
		$this->smarty->assign('LDNatIdNr',"$LDNatIdNr ");
		$this->smarty->assign('sNatIdNr',$nat_id_nr);
		$this->smarty->assign('LDReligion',$LDReligion);
		$this->smarty->assign('sReligion',$religion);
		
		if (!$GLOBAL_CONFIG['person_ethnic_orig_hide']&&$ethnic_orig){
			$this->smarty->assign('LDEthnicOrig',$LDEthnicOrigin);
			$this->smarty->assign('sEthnicOrigInput',$ethnic_orig_txt);
		}

		if (!$GLOBAL_CONFIG['person_other_his_nr_hide']){
			$other_hosp_list = $this->person_obj->OtherHospNrList();
			$iHospCount = sizeof($other_hosp_list);
			
			if($iHospCount) {
				$this->smarty->assign('bShowOtherHospNr',TRUE);

				$this->smarty->assign('LDOtherHospitalNr',$LDOtherHospitalNr);

				$sOtherNrBuffer='';
				if(is_array($other_hosp_list) && $iHospCount){

					foreach( $other_hosp_list as $k=>$v ){
						$sOtherNrBuffer.="<b>".$kb_other_his_array[$k].":</b> ".$v."<br />\n";
					}
				}
				$this->smarty->assign('sOtherNr',$sOtherNrBuffer);
			}
		}
		//nghe nghiep
		$this->smarty->assign('LDnghenghiep',$LDnghenghiep);
		$this->smarty->assign('sNghenghiep',$nghenghiep);
		$this->smarty->assign('LDnoilamviec',$LDnoilamviec);
		$this->smarty->assign('sNoilamviec',$noilamviec);
		$this->smarty->assign('LDhotenbaotin',$LDhotenbaotin);
		$this->smarty->assign('sHotenbaotin',$hotenbaotin);
		$this->smarty->assign('LDdtbaotin',$LDdtbaotin);
		$this->smarty->assign('sDTbaotin',$dtbaotin);
		$this->smarty->assign('LDTSBenhCN',$LDTSBenhCN);
		$this->smarty->assign('sTSBenhCN',$tiensubenhcanhan);
		$this->smarty->assign('LDTSBenhGD',$LDTSBenhGD);
		$this->smarty->assign('sTSBenhGD',$tiensubenhgiadinh);
		$this->smarty->assign('LDRegBy',$LDRegBy);
		if(empty($modify_id)) $buffer=$create_id; else $buffer=$modify_id;

		$this->smarty->assign('sRegByInput',$buffer);

		}else{
			$this->smarty->assign('pretext','Invalid PID number or the data is not available from the databank! Please report this to <a  href=\'mailto:info@care2x.org\'>info@care2x.org</a>. Thank you.');
		}
		

		# If data is to be returned only, buffer output, get the buffer contents, end and clean buffer and return contents.
		if($this->bReturnOnly){
			ob_start();
				$this->smarty->display('registration_admission/reg_form_show.tpl');
			$sTemp = ob_get_contents();
			ob_end_clean();
			return $sTemp;
		}else{
			$this->smarty->display('registration_admission/reg_form_show.tpl');
			return TRUE;
		}
	} // end of function
	
	/**
	* Creates the  data but returns it as a string instead of outputting it
	*/
	function create(){
		$this->bReturnOnly=TRUE;
		return $this->display();
	}
} // end of class
?>