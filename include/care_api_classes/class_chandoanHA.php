<?php
/**
* @package care_api
*/
/** */
require_once($root_path.'include/care_api_classes/class_core.php');
/**
* Prescription methods. 
*
* Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
* @author Tuyen
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class ChanDoanHA extends Core {
	/**#@+
	* @access private
	* @var string
	*/
		
	
	
	/**
	* SQL query result buffer
	* @var adodb record object
	*/
	var $result;

	/**
	* Preloaded data flag
	* @var boolean
	*/
	var $is_preloaded=false;

						
	/**
	* Constructor
	*/
	function ChanDoanHA(){
		//$this->setTable($this->tb_phar_destroy_info);
		//$this->setRefArray($this->tab_des_fields);
	}
	
	function countResultsXQItems($condition)	//xquang
	{
		global $db;
		$this->sql="SELECT count(*) AS sum_item  
				FROM care_encounter_diagnostics_report AS re, care_test_request_radio AS test 
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE 'XQ%'  ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	function listResultsXQSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT per.name_first, per.name_last, per.tuoi, per.sex ,per.pid, re.*
				FROM care_encounter_diagnostics_report AS re, care_test_request_radio AS test, care_person AS per, care_encounter AS en
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE 'XQ%'  
					AND en.pid=per.pid AND en.encounter_nr=re.encounter_nr ".$condition."
				ORDER BY re.report_date,re.report_time 	
				LIMIT $start_from,$number_items_per_page";
					
		//echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function listResultsXQ($condition){
	    global $db;
		
		$this->sql="SELECT per.name_first, per.name_last, per.tuoi, per.sex, per.pid, re.*
				FROM care_encounter_diagnostics_report AS re, care_test_request_radio AS test, care_person AS per, care_encounter AS en
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE 'XQ%'  
					AND en.pid=per.pid AND en.encounter_nr=re.encounter_nr ".$condition." 
				ORDER BY re.report_date, re.report_time";
					
		//echo  $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	function countResultsSAItems($condition)	//sieu am
	{
		global $db;
		$this->sql="SELECT count(*) AS sum_item  
				FROM care_encounter_diagnostics_report AS re, care_test_request_radio AS test 
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE 'Si%'  ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	function listResultsSASplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT per.name_first, per.name_last, per.tuoi, per.sex ,per.pid, re.*
				FROM care_encounter_diagnostics_report AS re, care_test_request_radio AS test, care_person AS per, care_encounter AS en
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE 'Si%'  
					AND en.pid=per.pid AND en.encounter_nr=re.encounter_nr ".$condition."
				ORDER BY re.report_date,re.report_time 	
				LIMIT $start_from,$number_items_per_page";
					
		//echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function listResultsSA($condition){
	    global $db;
		
		$this->sql="SELECT per.name_first, per.name_last, per.tuoi, per.pid, per.sex , re.*
				FROM care_encounter_diagnostics_report AS re, care_test_request_radio AS test, care_person AS per, care_encounter AS en
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE 'Si%'  
					AND en.pid=per.pid AND en.encounter_nr=re.encounter_nr ".$condition." 
				ORDER BY re.report_date, re.report_time";
					
		//echo  $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}		
	
	function countResultsDTItems($condition)	//dien tim
	{
		global $db;
		$this->sql="SELECT count(*) AS sum_item  
				FROM care_encounter_diagnostics_report AS re, care_test_request_dientim AS test 
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE '%n Tim'  ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	function listResultsDTSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT per.name_first, per.name_last, per.tuoi, per.sex ,per.pid,  re.*
				FROM care_encounter_diagnostics_report AS re, care_test_request_dientim AS test, care_person AS per, care_encounter AS en
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE '%n Tim'  
					AND en.pid=per.pid AND en.encounter_nr=re.encounter_nr ".$condition."
				ORDER BY re.report_date,re.report_time 	
				LIMIT $start_from,$number_items_per_page";
					
		//echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function listResultsDT($condition){
	    global $db;
		
		$this->sql="SELECT per.name_first, per.name_last, per.tuoi, per.sex, per.pid, re.*
				FROM care_encounter_diagnostics_report AS re, care_test_request_dientim AS test, care_person AS per, care_encounter AS en
				WHERE re.report_nr=test.batch_nr AND re.reporting_dept LIKE '%n Tim'  
					AND en.pid=per.pid AND en.encounter_nr=re.encounter_nr ".$condition." 
				ORDER BY re.report_date, re.report_time";
					
		//echo  $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}	
}

?>