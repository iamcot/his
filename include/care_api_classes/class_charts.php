<?php
/**
* @package care_api
*/

/**
*/
require_once($root_path.'include/care_api_classes/class_core.php');
require_once($root_path.'include/care_api_classes/class_notes.php');
require_once($root_path.'include/care_api_classes/class_notes_nursing.php');

/**
*  Charts methods.
*  Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance.
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class Charts extends NursingNotes {
	/**
	* Table name for encounter measurement data
	* @var string
	*/
	var $tb_measure='care_encounter_measurement';
        /**
	* Huynh
        * Table name for encounter the born chart data
	* @var string
	*/
	var $tb_bdcd='care_encounter_bdcd';
	/**
	* Table name for encounter prescription data
	* @var string
	*/
	var $tb_prescription='care_encounter_prescription';
	/**
	* Table name for encounter prescription data
	* @var string
	*/
	var $tb_prescription_sub='care_encounter_prescription_sub';
	/**
	* Table name for prescription notes data
	* @var string
	*/
	//var $tb_presc_notes='care_enc_prescnotes';
        var $tb_presc_notes='care_encounter_prescription_notes';
        /**
	* Field names of care_encounter_measurement table
	* @var array
	*/
	var $fld_measure=array(
								'nr',
								'msr_date',
								'msr_time',
								'encounter_nr',
								'msr_type_nr',
								'value',
								'unit_nr',
								'unit_type_nr',
								'notes',
								'measured_by',
								'status',
								'history',
								'modify_id',
								'modify_time',
								'create_id',
								'create_time');
        /**
	* Field names of care_encounter_bdcd table
	* @var array
	*/
	var $fld_bdcd=array(
								'nr',
								'msr_date',
								'msr_time',
								'encounter_nr',
								'nhiptim',
								'nuocoi',
								'dochongkhop',
								'cotucung',
								'dolot',
                                                                'soconco',
                                                                'sogiay',
                                                                'oxytocin',
                                                                'sogiot',
                                                                'thuoc',
                                                                'mach',
                                                                'huyetap',
                                                                'nhietdo',
                                                                'dam',
                                                                'acetone',
                                                                'luong',
                                                                'ghichu',
                                                                'lanmangthai',
                                                                'lansinh',
                                                                'giooivo',
								'bdcd_by',
								'history',
								'modify_id',
								'modify_time',
								'create_id',
								'create_time');
	/**
	* Field names of care_encounter_prescription table
	* @var array
	*/
	var $fld_prescription=array(
								'nr',
								'encounter_nr',
								'prescription_type_nr',
								'article',
								'drug_class',
								'order_nr',
								'dosage',
								'application_type_nr',
								'notes',
								'prescribe_date',
								'prescriber',
								'color_marker',
								'is_current',
								'status',
								'history',
								'modify_id',
								'modify_time',
								'create_id',
								'create_time');
	/**
	* Field names of care_encounter_prescription_sub table
	* @var int
	*/
	var $fld_prescription_sub=array('nr', 
								'prescription_nr',
								'prescription_type_nr',
								'bestellnum',
								'article',
								'drug_class',
								'dosage',
								'admin_time',
								'quantity',
								'application_type_nr',
								'sub_speed',
								'notes_sub',
								'color_marker',
								'is_stopped',
								'stop_date',
								'status',
								'companion',);										
	/**
	* Field names of care_encounter_prescription_notes table
	* @var array
	*/
	var $fld_presc_notes=array(
								'nr',
								'date',
								'prescription_nr',
								'notes',
								'short_notes',
								'status',
								'history',
								'modify_id',
								'modify_time',
								'create_id',
								'create_time');
	/**
	* Constructor
	*/			
	function Charts(){
		$this->NursingNotes();
	}
	/**
	* Sets the core table to the prescription table
	* @access private
	*/			
	function _usePrescriptionTable(){
		$this->coretable=$this->tb_prescription;
		$this->ref_array=$this->fld_prescription;
	}
	function _useNotesTable(){
		$this->coretable=$this->tb_notes;
		$this->ref_array=$this->fld_notes;	
	}
	/**
	* Gets the diagnosis text based on the encounter_nr key.
	* The result is sorted ascending by date and time. 
	* @access public
	* @param int Encounter number
	* @return mixed adodb record object or boolean
	*/			
	function getDiagnosis($enr){
		if($this->_getNotes("encounter_nr=$enr AND type_nr=12","ORDER BY date,time")){
			return $this->result;
		}else{return false;}
	}
	/**
	* Saves a diagnosis text data into the database.
	* The data to be stored are contained in an associative array  and passed by reference.
	* @access public
	* @param int Encounter number
	* @return boolean
	*/			
	function saveDiagnosisFromArray(&$data_array){
		$this->_useNotesTable();
		$this->data_array=$data_array;
		if($this->_insertNotesFromInternalArray(12)){ // 12 = diagnosis text note type
			return true;
		}else{
			return false;
		}
	}
	/**
	* Gets chart notes data based on the type_nr key.
	* The result is sorting ascending by data and time.
	* @access public
	* @param int Encounter number
	* @param int Type number of notes
	* @return mixed adodb record object or boolean
	*/			
	function getChartNotes($enr,$type_nr){
		if($this->_getNotes("encounter_nr=$enr AND type_nr=$type_nr","ORDER BY date,time")){
			return $this->result;
		}else{return false;}
	}
	/**
	* Saves chart notes data  of the type_nr type key into the database.
	* The data to be stored are contained in an associative array  and passed by reference.
	* @access public
	* @param array Chart notes data in associative array
	* @param int Notes type number
	* @return boolean
	*/			
	function saveChartNotesFromArray(&$data_array,$type_nr){
		//$this->setTable($this->tb_notes);
		$this->_useNotesTable();
		$this->data_array=$data_array;
		//$this->setRefArray($this->fld_notes);
		if($this->_insertNotesFromInternalArray($type_nr)){ 
			return true;
		}else{
			return false;
		}
	}	
	/**
	* Gets chart notes data based on the type_nr key and date key.
	* The result is sorting ascending by data and time.
	* @access public
	* @param int Encounter number
	* @param int Type number of notes
	* @param string Date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getDayChartNotes($enr,$type_nr,$date){
		if($this->_getNotes("encounter_nr=$enr AND type_nr=$type_nr AND date='$date'","ORDER BY create_time")){
			return $this->result;
		}else{return false;}
	}
	function getChartNotesByNr($nr){
		if($this->_getNotes(" nr=$nr ","")){
			return $this->result->FetchRow();
		}else{return false;}
	}
	function updateChartNotes(&$data_array,$nr){
		$this->_useNotesTable();
		$this->data_array=$data_array;
		if($this->_updateNotesFromInternalArray($nr)){
			return true;
		}else{return false;}
	}
	function deleteChartNotes($nr){
		global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM ". $this->tb_notes ." WHERE nr=".$nr;
		return $this->Transact($this->sql);
	}
	/**
	* Prepares the  measurement data for storing into the database.
	* The data are transferred to an associative array, the core table is set to measurement table 
	* and the reference array is set to the measurementtable's field names.
	* @access private
	*/			
	function _prepareSaveMeasurement(){
		global $_SESSION;
		$this->coretable=$this->tb_measure;
		$this->ref_array=$this->fld_measure;
		$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_id']=$_SESSION['sess_user_name'];
		$this->data_array['measured_by']=$_SESSION['sess_user_name'];
		$this->data_array['create_time']=date('YmdHis');
		$this->data_array['history']="Create: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
	}
	/* ---------- Blood pressure ------------- */		
	function saveBPFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveMeasurement();
		$this->data_array['msr_type_nr']=8;	 // 8 = bp composite type of measurement
		$this->data_array['unit_type_nr']=4;	 // 4 = pressure type of unit measurement
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}		
	function getDayBP($enr,$date){
		global $db;
		$this->sql="SELECT * FROM $this->tb_measure WHERE encounter_nr='$enr' AND msr_type_nr=8 AND msr_date='$date' ORDER BY msr_time";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}
	/* ---------- Nhiet do ------------- */
	function saveTemperatureFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveMeasurement();
		$this->data_array['msr_type_nr']=3;	 // 3 = temperature type of measurement
		$this->data_array['unit_type_nr']=5;	 // 5 = temperature type of unit measurement
		if($this->data_array['unit_nr']=='')
			$this->data_array['unit_nr']=15;	// do C
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}			
	function getDayTemperature($enr,$date){
		global $db;
		$this->sql="SELECT * FROM $this->tb_measure WHERE encounter_nr='$enr' AND msr_type_nr=3 AND msr_date='$date' ORDER BY msr_time";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}
	
	/* ---------- Mach ------------- */
	function saveMachFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveMeasurement();
		$this->data_array['msr_type_nr']=2;	 
		$this->data_array['unit_type_nr']=6;	 
		if($this->data_array['unit_nr']=='')
			$this->data_array['unit_nr']=19;	// L/ph
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}	
	function getDayMach($enr,$date){
		global $db;
		$this->sql="SELECT * FROM $this->tb_measure WHERE encounter_nr='$enr' AND msr_type_nr=2 AND msr_date='$date' ORDER BY msr_time";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}	
	/* ---------- Huyet ap ------------- */
	function saveHuyetApFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveMeasurement();
		$this->data_array['msr_type_nr']=1;	 
		$this->data_array['unit_type_nr']=4;	 
		if($this->data_array['unit_nr']=='')
			$this->data_array['unit_nr']=14;	// mmHg
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}	
	/* ---------- Can nang ------------- */
	function saveCanNangFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveMeasurement();
		$this->data_array['msr_type_nr']=6;	 
		$this->data_array['unit_type_nr']=2;	 
		if($this->data_array['unit_nr']=='')
			$this->data_array['unit_nr']=6;	// kg
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}
	/* ---------- Nhip tho ------------- */
	function saveNhipThoFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveMeasurement();
		$this->data_array['msr_type_nr']=10;	 
		$this->data_array['unit_type_nr']=6;	 
		if($this->data_array['unit_nr']=='')
			$this->data_array['unit_nr']=19;	// L/ph
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}	
	/**
	* Get measurement data by nr...
	* @access private
	*/
	function getMeasureByNr($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_measure WHERE nr='$nr'";
		if($this->result=$db->Execute($this->sql)){
			return $this->result->FetchRow();
		}else{return false;}
	}
	function getDayMeasure($enr,$type,$date){
		global $db;
		$this->sql="SELECT * FROM $this->tb_measure 
					WHERE encounter_nr='$enr' AND msr_type_nr='$type' AND msr_date='$date' ORDER BY msr_time";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}	
	function getManyDaysMeasureByType($pn,$type,$start,$end){
		global $db;
		$this->sql="SELECT * FROM $this->tb_measure 
					WHERE encounter_nr='$pn' AND msr_type_nr='$type' AND (msr_date>='$start') AND (msr_date<='$end') ORDER BY msr_date";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}
	function getManyDaysNursing($pn,$start,$end){
		global $db;
		$this->sql="SELECT msr_date,msr_time,measured_by FROM $this->tb_measure 
					WHERE encounter_nr='$pn' AND (msr_date>='$start') AND (msr_date<='$end') GROUP BY msr_date,measured_by";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}
	function getManyDaysNursingWithBlockTime($pn,$start,$end){
		global $db;
		$this->sql="SELECT msr_date,msr_time,measured_by FROM $this->tb_measure 
					WHERE encounter_nr='$pn' AND (msr_date>='$start') AND (msr_date<='$end') AND (HOUR(msr_time)<12) 
					GROUP BY msr_date,measured_by
					UNION
					SELECT msr_date,msr_time,measured_by FROM $this->tb_measure 
					WHERE encounter_nr='$pn' AND (msr_date>='$start') AND (msr_date<='$end') AND (HOUR(msr_time)>=12)
					GROUP BY msr_date,measured_by";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{return false;}
	}	
	/**
	* Saves prescription data.
	* Data must be contained in an associative array and passed by reference.
	* @param array Data contained in an associative array. Reference pass.
	* @return boolean
	*/			
	function savePrescriptionFromArray(&$data){
		global $_SESSION;
		$this->_usePrescriptionTable();
		$this->data_array=$data;
		$this->data_array['prescribe_date']=date('Y-m-d');
		$this->data_array['modify_id']='';
		$this->data_array['create_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_time']=date('YmdHis');
		$this->data_array['history']="Create: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}
	/**
	* Updates prescription data based on the nr key.
	* Data must be contained in an associative array and passed by reference.
	* @param int Record number key
	* @param array Data contained in an associative array. Reference pass.
	* @return boolean
	*/			
	function updatePrescriptionFromArray($nr,&$data){
		global $_SESSION;
		$this->_usePrescriptionTable();
		$this->data_array=$data;
		if(isset($this->data_array['prescribe_date'])) unset($this->data_array['prescribe_date']);
		$this->data_array['modify_id']=$_SESSION['sess_user_name'];
        $this->data_array['modify_time']=date('YmdHis');
		$this->data_array['history']=$this->ConcatHistory("Update: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n");
		if($this->updateDataFromInternalArray($nr)){
			return true;
		}else{return false;}
	}
	/**
	* Stops or marks the end of prescription data based on the nr key.
	* @param int Record number key
	* @return boolean
	*/
	function EndPrescription($nr){
		global $_SESSION;
		$this->data_array=NULL;
		$this->_usePrescriptionTable();
		$this->data_array['is_stopped']=1;
		$this->data_array['stop_date']=date('Y-m-d');
		$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['history']=$this->ConcatHistory("Ended: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n");
		if($this->updateDataFromInternalArray($nr)){
			return true;
		}else{return false;}
	}
	/**
	* Gets all current prescription data based on the encounter_nr key.
	* changed by gjergj sheldija
	* to work with the new way of managing prescriptions
	* @param int Encounter number
	* @return mixed adodb record object or boolean
	*/
	function getAllCurrentPrescription($enr){
		global $db;
		$this->sql="SELECT $this->tb_prescription.*,
					$this->tb_prescription_sub.*, 
					$this->tb_prescription.nr as id 
				FROM $this->tb_prescription_sub 
					INNER JOIN $this->tb_prescription ON ( $this->tb_prescription.nr = $this->tb_prescription_sub.prescription_nr )  
				WHERE $this->tb_prescription.encounter_nr=$enr 
					AND $this->tb_prescription_sub.is_stopped IN ('',0) 
				ORDER BY $this->tb_prescription.nr desc,  
					$this->tb_prescription_sub.companion desc,
					$this->tb_prescription.prescribe_date desc";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{
			return false;
		}
	}
		
	/**
	* Gets prescription data of a day based on the encounter_nr and date keys.
	* @param int Encounter number
	* @param string Prescription date
	* @return mixed adodb record object or boolean
	*/			
	function getDayPrescriptionNotes($enr,$date){
		global $db;
		$this->sql="SELECT $this->tb_prescription_sub.*,
       						$this->tb_presc_notes.nr AS notes_nr,
      		 				$this->tb_presc_notes.short_notes AS day_notes,
       						$this->tb_prescription.prescribe_date
					FROM $this->tb_prescription INNER JOIN $this->tb_prescription_sub ON $this->tb_prescription.nr = $this->tb_prescription_sub.prescription_nr
     				LEFT JOIN $this->tb_presc_notes ON
      					$this->tb_prescription.nr =
       					$this->tb_presc_notes.prescription_nr AND
        				$this->tb_presc_notes.date = '$date'
					WHERE $this->tb_prescription.encounter_nr = $enr AND
      					$this->tb_prescription_sub.is_stopped IN ('', 0)";
		//echo $this->sql;
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{
			return false;
		}
	}
	/**
	* Saves prescription notes.
	* Data must be contained in an associative array and passed by reference.
	* @param array Data contained in an associative array. Reference pass.
	* @return boolean
	*/
	function savePrescriptionNotesFromArray(&$data){
		global $_SESSION;
		$this->data_array=$data;
		$this->coretable=$this->tb_presc_notes;
		$this->ref_array=$this->fld_presc_notes;
		//$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_time']=date('YmdHis');
		$this->data_array['history']="Create: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}
	/**
	* Saves prescription notes based on the primary record key "nr".
	* Data must be contained in an associative array and passed by reference.
	* @param int Record number
	* @param array Data contained in an associative array. Reference pass.
	* @return boolean
	*/			
	function updatePrescriptionNotesFromArray($nr,&$data){
		global $_SESSION;
		$this->data_array=$data;
		$this->coretable=$this->tb_presc_notes;
		$this->ref_array=$this->fld_presc_notes;
		$this->data_array['modify_id']=$_SESSION['sess_user_name'];
        $this->data_array['modify_time']=date('YmdHis');
		$this->data_array['history']=$this->ConcatHistory("Update: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n");
		if($this->updateDataFromInternalArray($nr)){
			return true;
		}else{return false;}
	}
	/**
	* Gets table field's data of a given date range.
	* The resulting data is buffered in the $result variable.
	* @param int Encounter number
	* @param int Field name of the table to be fetched
	* @param int Type number of the notes
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return boolean
	*/			
	function _getChartDailyData($enr,$notes_id,$type_nr,$start,$end){
		global $db;
		$this->Notes(); # call Notes constructor to set the table and field names
		$this->sql="SELECT date,time,$notes_id, personell_name, aux_notes FROM $this->tb_notes WHERE encounter_nr=$enr AND type_nr=$type_nr AND date >= '$start' AND date <= '$end' ORDER BY create_time DESC";
		if($this->result=$db->Execute($this->sql)){
			if($this->result->RecordCount()){
				return true;
			}else{return false;}
		}else{return false;}
	}
	/**
	* Gets diet plan of a given date range.
	* @param int Encounter number
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getChartDailyDietPlans($enr,$start,$end){
		if($this->_getChartDailyData($enr,'short_notes',23,$start,$end)){
			return $this->result;
		}else{return false;}
	}
	/**
	* Gets main notes data of a given date range.
	* @param int Encounter number
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getChartDailyMainNotes($enr,$start,$end){
		if($this->_getChartDailyData($enr,'notes',7,$start,$end)){
			return $this->result;
		}else{return false;}
	}
	function getChartDailyNotes_5($enr,$start,$end){
		if($this->_getChartDailyData($enr,'notes',41,$start,$end)){
			return $this->result;
		}else{return false;}
	}
	/**
	* Gets supplementary notes data of a given date range.
	* @param int Encounter number
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getChartDailyEtcNotes($enr,$start,$end){
		if($this->_getChartDailyData($enr,'notes',8,$start,$end)){
			return $this->result;
		}else{return false;}
	}
	/**
	* Gets anticoaguants  data of a given date range.
	* @param int Encounter number
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getChartDailyAnticoagNotes($enr,$start,$end){
		if($this->_getChartDailyData($enr,'short_notes',10,$start,$end)){
			return $this->result;
		}else{return false;}
	}
	/**
	* Gets IV notes  data of a given date range.
	* @param int Encounter number
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getChartDailyIVNotes($enr,$start,$end){
		if($this->_getChartDailyData($enr,'short_notes',9,$start,$end)){
			return $this->result;
		}else{return false;}
	}
	/**
	* Gets prescription notes  data of a given date range.
	* @param int Encounter number
	* @param string Starting date in yyyy-mm-dd format
	* @param string Ending date in yyyy-mm-dd format
	* @return mixed adodb record object or boolean
	*/			
	function getChartDailyPrescriptionNotes($enr,$start,$end){
		global $db;

        $this->sql="SELECT p.nr,n.date,n.short_notes 
                        FROM $this->tb_prescription AS p
                            LEFT JOIN $this->tb_presc_notes AS n  ON p.nr=n.prescription_nr
                         WHERE p.encounter_nr=$enr  
                         ORDER BY p.nr";
        echo $sql;
        if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{ //echo $this->sql;
			return false;
		}
	}
        
        /**
	* Prepares the born chart data for storing into the database.
	* The data are transferred to an associative array, the core table is set to born chart 
	* and the reference array is set to the born chart's field names.
	* @access private
	*/			
	function _prepareSaveBDCD(){
		global $_SESSION;
		$this->coretable=$this->tb_bdcd;
		$this->ref_array=$this->fld_bdcd;
		$this->data_array['create_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_time']=date('YmdHis');
		$this->data_array['history']="Create: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
	}
        /* ---------- save bdcd ------------- */
	function saveBdcdFromArray(&$data){
		$this->data_array=$data;
		$this->_prepareSaveBDCD();	 
		if($this->insertDataFromInternalArray()){
			return true;
		}else{return false;}
	}
        /* ----------- Get Info BDCD -------------*/
        function getManyDaysInfo($pn,$start,$end,$cond=''){
		global $db;
		$this->sql="SELECT * FROM $this->tb_bdcd 
                                    WHERE encounter_nr='$pn' AND (msr_date>='$start') AND (msr_date<='$end') $cond GROUP BY msr_date,msr_time";
		if($this->result=$db->Execute($this->sql)){
                    return $this->result;
		}else{return false;}
	}
        /*------------ Update lang mang thai/lan sinh/thoi gian vo oi -------------------------------*/
        function updatebdcd(&$data,$nr){
		$this->data_array=$data;
		$this->_prepareSaveBDCD();
		if($this->_updateNotesFromInternalArray($nr)){
			return true;
		}else{return false;}
	}
        /* ----------- Get Info BDCD -------------*/
        function getInfoCondition($getinfo,$pn,$start='',$end='',$cond=''){
		global $db;
                if($start!='' && $end!=''){
                    $cond=" encounter_nr='$pn' AND (msr_date>='$start') AND (msr_date<='$end') ".$cond;
                }
		$this->sql="SELECT $getinfo FROM $this->tb_bdcd 
                                    WHERE $cond GROUP BY msr_date,msr_time";
		if($this->result=$db->Execute($this->sql)){
                    return $this->result;
		}else{return false;}
	}
        /* ---------- Delete info bdcd --------------*/
        function DeleteInfoBDCD($nr){
                global $db; 
                $this->sql="DELETE FROM $this->tb_bdcd WHERE nr=$nr";
                echo $this->sql;
                if($this->result=$db->Execute($this->sql)){
                    return true;
                }else{return false;}
        }
}
?>
