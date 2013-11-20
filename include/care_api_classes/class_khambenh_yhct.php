<?php
/**
* @package care_api
*/
/**
*/
require_once($root_path.'include/care_api_classes/class_core.php');

/**
*  Notes methods.
*  Note: this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance.
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class KhamBenhYHCT extends Core {
	/**
	* Database table for the encounter notes data.
	* @var string
	* @access private
	*/
	var $tb_khambenh='care_encounter_khambenh_yhct';
	var $tb_khambenh_sub='care_encounter_khambenh_yhct_sub';
	/**
	* Database table for the notes types.
	* @var string
	* @access private
	*/
	var $tb_type_muckham ='care_type_yhct';
	var $tb_type_chitietmuc= 'care_type_yhct_sub';
	/**
	* Database table for the encounter data.
	* @var string
	* @access private
	*/
	var $tb_enc='care_encounter';
	/**
	* Holder for sql query results.
	* @var object adodb record object
	* @access private
	*/
	var $result;
	/**
	* Holder for preloaded department data.
	* @var object adodb record object
	* @access private
	*/
	var $preload_dept;
	/**
	* Preloaded flag
	* @var boolean
	* @access private
	*/
	var $is_preloaded=false;
	/**
	* Field names of care_encounter_notes table
	* @var array
	* @access private
	*/
	var $fld_notes=array('nr', 
						'encounter_nr', 
						'chandoan', 
						'doctor', 
						'date', 
						'time', 
						'history', 
						'modify_id', 
						'modify_time', 
						'create_id', 
						'create_time'
						);
    var $fld_notes_sub=array('nr', 
							'makham_nr', 
							'detail_nr', 
							'check_number', 
							'check_yesno', 
							'description'
							);
	var $tb_yhct="care_khambenh_yhct";
	var $yhct_array=array(
			'nr',
			'encounter_nr',
			'hinhthai',
			'hinhthai_notes',
			'tinhtao_radio',
			'thansac',
			'thansac_notes',
			'luoi',
			'luoi_notes' ,
			'bophan_notes',
			'amthanh' ,
			'amthanh_notes' ,
			'ho_radio',
			'onac_radio',
			'mui_radio',
			'mui',
			'mui_notes',
			'hoinguoi_radio',
			'hannhietbl_radio',
			'hannhiet',
			'hannhiet_notes' ,
			'benhtd_radio',
			'mohoi',
			'mohoi_notes',
			'daumat_radio',
			'daumat',
			'daumat_notes',
			'lung_radio',
			'lung_notes',
			'bungnguc_radio',
			'bungnguc',
			'bungnguc_notes',
			'chantay_radio',
			'chantay_notes',
			'an_notes',
			'an',
			'an_radio',
			'uong_radio',
			'uong',
			'uong_notes',
			'daitt_radio',
			'daitt' ,
			'daitt_notes',
			'ngu_radio',
			'ngu',
			'ngu_notes',
			'kn_sd_radio',
			'kn_sd' ,
			'doiha_radio',
			'kn_sd_notes',
			'sd_radio',
			'dkxh',
			'dkxh_notes',
			'xucchan',
			'xucchan_notes',
			'machchan',
			'tongkhan',
			'machchan_notes',
			'bienchung',
			'chandoan',
			'dieutri_radio',
			'dieutri',
			'chedoan',
			'chedochamsoc',
			'date',
			'doctor_nr',
			'doctor_name',
			'history',
			'create_id',
			'create_time',
			'modify_id',
			'modify_time'
	);
	/**
	* Constructor
	*/			
	function KhambenhYHCT(){
		$this->setTable($this->tb_yhct);
		$this->setRefArray($this->yhct_array);
	}
	

	function _getKhambenh($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_yhct WHERE $cond $order";

	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/**
	* Save a notes data of a given type number.
	*
	* The data to be saved comes from an internal buffer array that is populated by other methods.
	* @access private
	* @param string Type number of the notes data to be saved.
	* @return boolean
	*/
	function _insertNotesFromInternalArray($type_nr=''){
		global $_SESSION;
		if(empty($type_nr)) return false;
		if(empty($this->data_array['date'])) $this->data_array['date']=date('Y-m-d');
		if(empty($this->data_array['time'])) $this->data_array['time']=date('H:i:s');
		$this->data_array['type_nr']=$type_nr;
		//$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_time']=date('YmdHis');
		$this->data_array['history']="Create: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r";	
        	return $this->insertDataFromInternalArray();
	}
	/**
	* Updates a notes data record based on the primary record key "nr".
	*
	* The data to be saved comes from an internal buffer array that is populated by other methods.
	* @access private
	* @param int Record number of the notes record to be updated.
	* @return boolean
	*/			
	function _updateNotesFromInternalArray($nr){
		global $_SESSION;
		$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['modify_time']=date('YmdHis');
		$this->data_array['history']=$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r");
		return $this->updateDataFromInternalArray($nr);
	}
	/**
	* Gets the date range of a certain notes type that fits to a given condition.
	*
	* The resulting adodb record object is stored in the internal buffer $result.
	* @access private
	* @param int Encounter number
	* @param int Notes type number
	* @param string Condition string. Query constraint.
	* @return boolean
	*/			
	function _getKhambenhDateRange($enr='',$type_nr=0,$cond=''){
		global $db;
		if(empty($enr)){
			return false;
		}else{
			if(empty($cond)){
				$cond="encounter_nr=$enr";
			}
			$this->sql="SELECT MIN(date) AS fe_date, MAX(date) AS le_date FROM $this->tb_khambenh WHERE $cond";
			if($this->result=$db->Execute($this->sql)){
				if($this->result->RecordCount()){
					return true;
				}else{return false;}
			}else{return false;}
		}
	}

	# Tuyen - YHCT
	function listLanKham($enc){
		$cond=" encounter_nr='".$enc."' ";
		return $this->_getKhambenh($cond);		
	}
	
	function detailLanKham($nr){
		global $db;
		if($nr=='')
			return false;
		$this->sql="SELECT kb.*, type.nameI, typesub.*, kbsub.*    
					FROM $this->tb_khambenh AS kb, $this->tb_khambenh_sub AS kbsub, 
						$this->tb_type_muckham AS type, $this->tb_type_chitietmuc AS typesub
					WHERE kb.nr='".$nr."' 
					AND kbsub.makham_nr=kb.nr AND kbsub.detail_nr=typesub.detail_nr
					AND typesub.typeI=type.typeI 
					ORDER BY typesub.typeI, typesub.type1, typesub.order";
		
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
    }

	function showallMucKham(){
		global $db;
		$this->sql="SELECT type.nameI, typesub.*    
					FROM $this->tb_type_muckham AS type, $this->tb_type_chitietmuc AS typesub
					WHERE typesub.typeI=type.typeI 
					ORDER BY typesub.typeI, typesub.type1, typesub.order";
		
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function savelankham($encounter_nr, $bienchung, $chandoan, $doctor, $date){
		global $db;
		$this->sql="INSERT INTO $this->tb_khambenh 
					(encounter_nr, bienchung, chandoan, doctor, 
					date, time, history, 
					modify_id, modify_time, create_id, create_time)
					VALUES
					('$encounter_nr', '$bienchung', '$chandoan', '$doctor', 
					'$date', '".date('H:i:s')."', 'Create: $doctor ".date('Y-m-d H:i:s')."', 
					'', '', '$doctor', '".date('H:i:s')."');";
					
		return $this->Transact($this->sql);	
	}
	function savemuckham($makham_nr, $detail_nr, $check_number, $check_yesno, $description){
		global $db;
		$this->sql="INSERT INTO $this->tb_khambenh_sub 
					(makham_nr, detail_nr, check_number, check_yesno, description)
					VALUES 
					('$makham_nr', '$detail_nr', '$check_number', '$check_yesno', '$description');";
			//echo $this->sql;		
		return $this->Transact($this->sql);
	}
	function updatelankham($nr, $bienchung, $chandoan, $doctor, $date){
		global $db;
		$this->sql="UPDATE $this->tb_khambenh 
					SET 
					bienchung = '$bienchung' , 
					chandoan = '$chandoan' , 
					doctor = '$doctor' , 
					date = '$date' , 
					time = '".date('H:i:s')."' , 
					history =  ".$this->ConcatHistory("Update: ".date('Y-m-d H:i:s')." ".$doctor."\n\r")." , 
					modify_id = '$doctor' , 
					modify_time = '".date('Y-m-d H:i:s')."' 				
					WHERE
					nr = '$nr' ";
					
		return $this->Transact($this->sql);	
	}
	function updatemuckham($makham_nr, $detail_nr, $check_number, $check_yesno, $description){
		global $db;
		$this->sql="UPDATE $this->tb_khambenh_sub  
					SET  
					check_number = '$check_number' , 
					check_yesno = '$check_yesno' , 
					description = '$description'				
					WHERE
					makham_nr = '$makham_nr' AND detail_nr = '$detail_nr';";
					
		return $this->Transact($this->sql);
	}	
}
?>
