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
class CabinetMedipot extends Core {
	/**#@+
	* @access private
	* @var string
	*/
		
	/** 15/01/2012  - Tuyen-------------------------------------------------------------------------------
	* Table name for destroy 
	*/
	var $tb_med_destroy_info='care_med_dept_destroymed_info';
	var $tb_med_destroy='care_med_dept_destroymed';
	
	/** 15/01/2012
	* Table name for return 
	*/
	var $tb_med_return_info='care_med_dept_returnmed_info';
	var $tb_med_return='care_med_dept_returnmed';
	
	/** 15/01/2012
	* Table name for archive 
	*/
	var $tb_med_archive='care_med_department_archive';
	var $tb_med_avai_dept='care_med_available_department';

	var $tb_med_use_info='care_med_dept_used_info';
	var $tb_med_use='care_med_dept_used';
	
	/** @access private
	* Field names of care_med_destroy_info table
	* @var int
	*/
	var $tab_des_fields=array('destroy_id',
							'dept_nr',
							'ward_nr',
							'typeput',
							'date_time_create',
							'doctor',
							'history',
							'note',
							'modify_id',
							'status_finish',
							'user_accept');
	/**
	* Field names of care_med_destroy table
	* @var int
	*/
	var $tab_des_fields_sub=array('nr',
								'destroy_id',
								'product_encoder',
								'product_lot_id',
								'exp_date',
								'cost',
								'number',
								'units',
								'note');						

	/** @access private
	* Field names of care_med_return_info table
	* @var int
	*/
	var $tab_re_fields=array('return_id',
							'dept_nr',
							'ward_nr',
							'typeput',
							'date_time_create',
							'doctor',
							'history',
							'note',
							'modify_id',
							'status_finish',
							'user_accept');
	/**
	* Field names of care_med_return table
	* @var int
	*/
	var $tab_re_fields_sub=array('nr',
								'destroy_id',
								'product_encoder',
								'product_lot_id',
								'cost',
								'number',
								'units',
								'note');
	/**
	* Field names of care_pharma_use_info table
	* @var int
	*/
	var $tab_use_fields=array('use_id', 
							'dept_nr', 
							'ward_nr', 
							'date_time_use', 
							'nurse', 
							'history', 
							'note', 
							'modify_id',
							'status_finish',
							'user_accept');
	/**
	* Field names of care_pharma_use table
	* @var int
	*/
	var $tab_use_fields_sub=array('nr', 
								'use_id', 
								'product_encoder', 
								'product_lot_id', 
								'cost', 
								'number', 
								'units', 
								'note');
	/**
	* Field names of care_med_department_archive table
	* @var int
	*/
	var $tab_archive_fields=array('nr',
								'dept_nr',
								'ward_nr',
								'typeput',
								'product_encoder',
								'product_lot_id',
								'get_use',
								'number',
								'issuepaper_id',
								'pres_id',
								'use_id',
								'return_id',
								'destroy_id',
								'at_date_time',
								'user');								
	
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
	function CabinetMedipot(){
		//$this->setTable($this->tb_med_destroy_info);
		//$this->setRefArray($this->tab_des_fields);
	}
	
	//-----------------------------------Destroy & Return-------------------------------
	
	/**
	* Sets the core object to point  to either care_encounter_issuepaper or care_encounter_issuepaper_sub and field names.
	* Tuyen
	* The table is determined by the parameter content. 
	* @access public
	* @param string Determines the final table name 
	* @return boolean.
	*/
	function useCabinetDestroy($type){
		if($type=='des_info'){
			$this->setTable($this->tb_med_destroy_info);
			$this->setRefArray($this->tab_des_fields);
		}elseif($type=='des'){
			$this->setTable($this->tb_med_destroy);
			$this->setRefArray($this->tab_des_fields_sub);
		}else{return false;}
	}
	
	function useCabinetReturn($type){
		if($type=='re_info'){
			$this->setTable($this->tb_med_return_info);
			$this->setRefArray($this->tab_re_fields);
		}elseif($type=='re'){
			$this->setTable($this->tb_med_return);
			$this->setRefArray($this->tab_re_fields_sub);
		}else{return false;}
	}

	function useCabinetUse($type){
		if($type=='use_info'){
			$this->setTable($this->tb_med_use_info);
			$this->setRefArray($this->tab_use_fields);
		}elseif($type=='use'){
			$this->setTable($this->tb_med_use);
			$this->setRefArray($this->tab_use_fields_sub);
		}else{return false;}
	}	
	/** 18/10/2011
	 * Get all info of a CabinetPharma, based on the CabinetPharma id
	 * Tuyen
	 * @param int CabinetPharma number
	 * @return table result or boolean
	 */
	function getDetailDestroyInfo($nr){
	    global $db;
		$this->sql="SELECT desinfo.*,dest.*,desinfo.note AS generalnote, 
						khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine  
					FROM $this->tb_med_destroy_info AS desinfo, $this->tb_med_destroy AS dest, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi  
					WHERE desinfo.destroy_id='".$nr."' AND dest.destroy_id=desinfo.destroy_id 
					AND khochan.product_encoder=dest.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getDetailReturnInfo($nr){
	    global $db;
		$this->sql="SELECT desinfo.*,dest.*,desinfo.note AS generalnote, khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine  
					FROM $this->tb_med_return_info AS desinfo, $this->tb_med_return AS dest, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi  
					WHERE desinfo.return_id='".$nr."' AND dest.return_id=desinfo.return_id 
					AND khochan.product_encoder=dest.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getDetailUseInfo($nr){
	    global $db;
		$this->sql="SELECT desinfo.*,dest.*,desinfo.note AS generalnote, khochan.product_name,khochan.sodangky, donvi.unit_name_of_medicine  
					FROM $this->tb_med_use_info AS desinfo, $this->tb_med_use AS dest, care_med_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
					WHERE desinfo.use_id='".$nr."' AND dest.use_id=desinfo.use_id 
					AND khochan.product_encoder=dest.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	/** 18/10/2011
	 * Get general info of a CabinetPharma, based on the CabinetPharma id
	 * Tuyen
	 * @param int CabinetPharma number
	 * @return mixed array or boolean
	 */
	function getDestroyInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_destroy_info 
					WHERE destroy_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getReturnInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_return_info 
					WHERE return_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function getUseInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_use_info 
					WHERE use_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}	
	/** 18/10/2011
	 * Get all medicine in a CabinetPharma, based on the CabinetPharma id
	 * Tuyen
	 * @param int CabinetPharma number
	 * @return table result or boolean
	 */
	function getAllMedicineInDestroy($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_destroy 
					WHERE destroy_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getAllMedicineInReturn($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_return 
					WHERE return_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get ID of last CabinetPharma
	 * Tuyen
	 * @return mixed array or boolean
	 */
	function getLastID(){
		global $db;
		$this->sql="SELECT MAX(destroy_id) AS destroy_id FROM $this->tb_med_destroy_info ";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	function getLastReturnID(){
		global $db;
		$this->sql="SELECT MAX(return_id) AS return_id FROM $this->tb_med_return_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getLastUseID(){
		global $db;
		$this->sql="SELECT MAX(use_id) AS use_id FROM $this->tb_med_use_info ";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}	
	function getMedicineInDestroy($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_med_destroy WHERE nr='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}
	
	function getMedicineInReturn($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_med_return WHERE nr='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}
	/** 18/10/2011
	 * Updates the status finish of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string new status
	 * @return boolean
	 */
	function setDestroyStatusFinish($destroy_id,$status) {
	    global $db;
		if(!$destroy_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_med_destroy_info
						SET status_finish='$status'
						WHERE destroy_id=$destroy_id";
		return $this->Transact($this->sql);	
	}
	
	function setReturnStatusFinish($return_id,$status) {
	    global $db;
		if(!$return_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_med_return_info
						SET status_finish='$status'
						WHERE return_id=$return_id";
		return $this->Transact($this->sql);	
	}
	/** 18/10/2011
	 * Updates the status finish of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string new status
	 * @return boolean
	 */
	function deleteAllMedipotInDestroy($destroy_id) {
	    global $db;
		if(!$destroy_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_destroy
					WHERE destroy_id=$destroy_id";
		return $this->Transact($this->sql);	
	}	
	
	function deleteDestroy($destroy_id) {
	    global $db;
		if(!$destroy_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_destroy_info
					WHERE destroy_id=$destroy_id";
		return $this->Transact($this->sql);	
	}	
	
	function deleteAllMedipotInReturn($return_id) {
	    global $db;
		if(!$return_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_return
					WHERE return_id=$return_id";
		return $this->Transact($this->sql);	
	}	
	
	function deleteReturn($return_id) {
	    global $db;
		if(!$return_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_return_info
					WHERE return_id=$return_id";
		return $this->Transact($this->sql);	
	}	
	function deleteAllMedicineInUse($use_id) {
	    global $db;
		if(!$return_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_use 
					WHERE use_id='$use_id'";
		return $this->Transact($this->sql);	
	}	
	
	function deleteUse($use_id) {
	    global $db;
		if(!$return_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_use_info
					WHERE use_id='$use_id'";
		return $this->Transact($this->sql);	
	}	
	/** 28/11/2011
	 * List all CabinetPharma, with condition
	 * Tuyen
	 * @param string condition
	 * @return table result or boolean
	 */
	function listAllDestroy($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_destroy_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
		
	function listAllReturn($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_return_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function listAllUse($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_use_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}		
	/** 28/11/2011
	 * List some CabinetPharma, with condition
	 * Tuyen
	 * @param string condition, int current_page, int number_items_per_pag
	 * @return table result or boolean
	 */
	function listSomeDestroySplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_med_destroy_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function countDestroyItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_med_destroy_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	function listSomeReturnSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_med_return_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function countReturnItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_med_return_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}	
	function countUseItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_use_return_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}	
	function listSomeUseSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_med_use_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}		

	/** 5/12/2011
	 * Updates the issue_user, issue_note, receive_user of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string issue_user, issue_note, receive_user
	 * @return boolean
	 */
	function setInfoPersonWhenDestroy($destroy_id,$user_accept) {
	    global $db;
		if(!$destroy_id) return FALSE;
		$this->sql="UPDATE $this->tb_med_destroy_info
						SET user_accept='$user_accept' 
						WHERE destroy_id=$destroy_id";
		return $this->Transact($this->sql);	
	}
	
	function setInfoPersonWhenReturn($return_id,$user_accept) {
	    global $db;
		if(!$return_id) return FALSE;
		$this->sql="UPDATE $this->tb_med_return_info
						SET user_accept='$user_accept' 
						WHERE return_id=$return_id";
		return $this->Transact($this->sql);	
	}
	
	//--------------------------------------------Archive-----------------------------------------
	function useCabinetArchive(){
		$this->setTable($this->tb_med_archive);
		$this->setRefArray($this->tab_archive_fields);
	}
	
	function report15Day($dept_nr,$ward_nr,$fromday,$today){
		global $db;	
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		else 
			list($f_year,$f_month,$f_day) = explode("-",$fromday);
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}
		else 
			list($t_year,$t_month,$t_day) = explode("-",$today);
			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
			
		//Test dept, ward
		$dept_ward='';
		if($dept_nr!='') $dept_ward=$dept_ward.' AND arc.dept_nr='.$dept_nr.' ';
		if($ward_nr!='') $dept_ward=$dept_ward.' AND arc.ward_nr='.$ward_nr.' ';
			
		$this->sql="SELECT arc.*, main.product_name, unit.unit_name_of_medicine, DAY(at_date_time) AS at_day, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					 ".$dept_ward." 
					AND (arc.pres_id>0 OR arc.use_id>0)
					AND (YEAR(at_date_time)=YEAR('$today')) AND (MONTH(at_date_time)=MONTH('$today')) 
					AND (DAY(at_date_time) <= DAY('$today')) AND (DAY(at_date_time) >= DAY('$fromday')) 
					GROUP BY arc.product_encoder, DAY(at_date_time)   
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function reportMedipot15Day($fromday,$today, $condition=''){
		global $db;	
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		else 
			list($f_year,$f_month,$f_day) = explode("-",$fromday);
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}
		else 
			list($t_year,$t_month,$t_day) = explode("-",$today);
			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
			
		$this->sql="SELECT arc.*, main.product_name, unit.unit_name_of_medicine, DAY(at_date_time) AS at_day, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					AND (arc.pres_id>0 OR arc.use_id>0) 
					AND arc.get_use=0 ".$condition." 
					AND (YEAR(at_date_time)=YEAR('$today')) AND (MONTH(at_date_time)=MONTH('$today')) 
					AND (DAY(at_date_time) <= DAY('$today')) AND (DAY(at_date_time) >= DAY('$fromday')) 
					GROUP BY arc.product_encoder, DAY(at_date_time)   
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function reportMonth($dept_nr,$ward_nr,$month,$year){
		global $db;	
		
		//Test dept, ward
		$dept_ward='';
		if($dept_nr!='') $dept_ward=$dept_ward.' AND arc.dept_nr='.$dept_nr.' ';
		if($ward_nr!='') $dept_ward=$dept_ward.' AND arc.ward_nr='.$ward_nr.' ';
		
		$this->sql="SELECT arc.*, main.product_name, main.price, unit.unit_name_of_medicine, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					 ".$dept_ward." 
					AND ((arc.pres_id>0) OR (arc.use_id>0) OR (arc.destroy_id>0))
					AND (YEAR(arc.at_date_time)='$year') AND (MONTH(arc.at_date_time)='$month') 
					GROUP BY arc.product_encoder, MONTH(arc.at_date_time), (arc.pres_id>0), (arc.use_id>0), (arc.destroy_id>0) 
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function reportMedipotMonth($month,$year){
		global $db;	
		
		$this->sql="SELECT arc.*, main.product_name, main.price, unit.unit_name_of_medicine, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					AND ((arc.pres_id>0) OR (arc.use_id>0) OR (arc.destroy_id>0))
					AND arc.get_use=0 
					AND (YEAR(arc.at_date_time)='$year') AND (MONTH(arc.at_date_time)='$month') 
					GROUP BY arc.product_encoder, MONTH(arc.at_date_time), arc.pres_id, (arc.use_id>0), (arc.destroy_id>0) 
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function reportMedipotBHMonth($month,$year){
		global $db;	
		
		$this->sql="SELECT arc.*, main.product_name, main.price, unit.unit_name_of_medicine, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					AND ((arc.pres_id>0) OR (arc.use_id>0) OR (arc.destroy_id>0))
					AND arc.get_use=0 AND arc.typeput=0
					AND (YEAR(arc.at_date_time)='$year') AND (MONTH(arc.at_date_time)='$month') 
					GROUP BY arc.product_encoder, MONTH(arc.at_date_time), arc.pres_id, (arc.use_id>0), (arc.destroy_id>0) 
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function reportMedipotKPMonth($month,$year){
		global $db;	
		
		$this->sql="SELECT arc.*, main.product_name, main.price, unit.unit_name_of_medicine, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					AND ((arc.pres_id>0) OR (arc.use_id>0) OR (arc.destroy_id>0))
					AND arc.get_use=0 AND arc.typeput=1
					AND (YEAR(arc.at_date_time)='$year') AND (MONTH(arc.at_date_time)='$month') 
					GROUP BY arc.product_encoder, MONTH(arc.at_date_time), arc.pres_id, (arc.use_id>0), (arc.destroy_id>0) 
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function reportMedipotCBTCMonth($month,$year){
		global $db;	
		
		$this->sql="SELECT arc.*, main.product_name, main.price, unit.unit_name_of_medicine, SUM(arc.number) AS total 
					FROM $this->tb_med_archive AS arc, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					AND ((arc.pres_id>0) OR (arc.use_id>0) OR (arc.destroy_id>0))
					AND arc.get_use=0 AND arc.typeput=2 
					AND (YEAR(arc.at_date_time)='$year') AND (MONTH(arc.at_date_time)='$month') 
					GROUP BY arc.product_encoder, MONTH(arc.at_date_time), arc.pres_id, (arc.use_id>0), (arc.destroy_id>0) 
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function getTypePres($nr){
	    global $db;
		$this->sql="SELECT typepres.group_pres, typepres.prescription_type_name , pres.prescription_type 
					FROM care_med_prescription_info AS pres, care_med_type_of_prescription AS typepres
					WHERE pres.prescription_id='".$nr."'
					AND pres.prescription_type=typepres.prescription_type";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	

	
	function insertArchiveUseListPres($dept, $ward, $encoder, $lotid, $issue_id, $user, $typeput){
		global $db;	
		$this->sql="SELECT pres.prescription_id AS id, pres.sum_number AS number 
					FROM care_med_prescription_info AS presinfo, care_med_prescription AS pres  
					WHERE presinfo.prescription_id=pres.prescription_id  
					AND presinfo.in_issuepaper='$issue_id'  
					AND pres.product_encoder='$encoder' ";
		
		if ($results=$db->Execute($this->sql)){
			
			while($i<=$results->RecordCount() || $i<=count($lotid))
			{
				if(!isset($a) && !isset($b)){
					$a=$results->FetchRow();
					list($key, $value) = each($lotid);  //$b=each($lotid);
				}
	
				if($a['number']==0)
					$a=$results->FetchRow();
				if($value==0)
					list($key, $value) = each($lotid);
				
				if($value < $a['number']){
					$this->insertArchive($dept, $ward, $encoder, $key, '0', $value, 0, $a['id'], 0, 0, 0, $user, $typeput);
					$a['number']=$a['number']-$value;
					$value=0;
				} else {
					$this->insertArchive($dept, $ward, $encoder, $key, '0', $a['number'], 0, $a['id'], 0, 0, 0, $user, $typeput);
					$value=$value-$a['number'];
					$a['number']=0;
				}	
					
				$i++;
			}
		}
		
		return $list_pres_lotid;	
	}
	

		
	//--------------------------------------------Available Department-----------------------------------------
	/** 5/02/2012
	 * Updates care_med_available_department
	 * @param $encoder, $lotid, $number_use, $cal='+','-'
	 * @process: if not exist=> insert, else update
	 * @return boolean
	 */
	 function checkExistMedicineInAvaiDept($dept,$ward,$encoder,$lotid,$typeput){
		global $db;
		$this->sql="SELECT *
					FROM $this->tb_med_avai_dept AS dept, care_med_available_product AS pro 
					WHERE dept.available_product_id = pro.available_product_id 
					AND dept.department='$dept' AND dept.ward_nr='$ward' AND dept.typeput='$typeput'
					AND pro.product_encoder='$encoder' AND pro.product_lot_id='$lotid'";		
		if($this->result=$db->Execute($this->sql)) {
			if($this->result->RecordCount()){
				return $this->result->FetchRow();
			} else { return false; } 
		} else { return false; }       
	} 
	 
	function updateMedicineAvaiDept($encoder, $lotid, $dept, $ward, $number, $cal, $typeput) {
	    global $db;
		if($encoder=='') return FALSE;
		$this->sql= "UPDATE $this->tb_med_avai_dept AS dept, care_med_available_product AS pro
					SET dept.available_number=dept.available_number".$cal."'$number' 
					WHERE dept.available_product_id = pro.available_product_id 
					AND pro.product_encoder='$encoder' AND pro.product_lot_id='$lotid' 
					AND dept.department='$dept' AND dept.ward_nr='$ward' AND dept.typeput='$typeput'";
		return $this->Transact($this->sql);		
	}
	
	function insertMedicineAvaiDept($encoder, $lotid, $dept, $ward, $number, $typeput){
	    global $db;
		if($encoder=='') return FALSE;
		$this->sql="SELECT available_product_id FROM care_med_available_product WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='$typeput'";
		if($result1=$db->Execute($this->sql)){
			//Get id from khole
			$avai_id=$result1->FetchRow();
						
			$this->sql="INSERT INTO $this->tb_med_avai_dept (available_product_id,department,ward_nr,available_number,typeput) VALUES ('".$avai_id['available_product_id']."','$dept','$ward','$number','$typeput')";
			return $this->Transact($this->sql);
		} else {return false;}
	}
	
	/** 5/02/2012
	 * Updates care_med_available_department
	 * @param $encoder, $number_use
	 * @process: priority to use first_lotid
	 * @return boolean
	 */
	function useMedicineAvaiDept($encoder, $dept, $ward, $number_use, $typeput){
		global $db;
		$this->sql="SELECT pro.product_lot_id, dept.available_number 
					FROM $this->tb_med_avai_dept AS dept, care_med_available_product AS pro   
					WHERE dept.available_product_id = pro.available_product_id 
					AND pro.product_encoder='$encoder' 
					AND dept.available_number>0 
					AND dept.department='$dept' AND dept.ward_nr='$ward' AND dept.typeput='$typeput' 
					ORDER BY pro.available_product_id ";
		if ($this->result=$db->Execute($this->sql)) {
			$n=$this->result->RecordCount();
		    if ($n) {
				for ($i=0;$i<$n;$i++){
					$lotid = $this->result->FetchRow();
					if ($lotid['available_number']<$number_use) {
						$list_lotid = array($lotid['product_lot_id'] => $lotid['available_number']);
						$this->updateMedicineAvaiDept($encoder, $lotid['product_lot_id'], $dept, $ward, $lotid['available_number'], '-', $typeput);
						$number_use = $number_use - $lotid['available_number'];
					} else {
						$list_lotid = array($lotid['product_lot_id'] => $number_use);
						$this->updateMedicineAvaiDept($encoder, $lotid['product_lot_id'], $dept, $ward, $number_use, '-', $typeput);
						break;
					}
				}
		        return $list_lotid;
			}else{return false;}
		}else{return false;}	
	}	
	
	/** 24/02/2012
	 * Functions for distribute dept into wards
	 */
	function ShowDistributeCabinet($dept_nr, $current_page, $number_items_per_page)
    {
        global $db;
		$dept_ward .= "AND (ward_nr='0' OR ward_nr='') ";
		if ($dept_nr!='')
			$dept_ward = " AND taikhoa.department='".$dept_nr."' ";
		if ($current_page!='' && $number_items_per_page!='') {		
			$start_from =($current_page-1)*$number_items_per_page; 
			$limit_number ='LIMIT '.$start_from.', '.$number_items_per_page;
		}			
			
		$this->sql="SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, tatcakhoa.product_lot_id, tatcakhoa.exp_date, taikhoa.*    
                FROM $this->tb_med_avai_dept AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medicine AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					".$dept_ward." 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
                    AND taikhoa.available_number>0 	
                ORDER BY khochan.product_name   
				".$limit_number;
		if($this->result=$db->Execute($this->sql)) {
			if($this->result->RecordCount()) {
				 return $this->result;	 
			} else { return false; }
		} else { return false; }                   
    }
		
	function SearchDistributeCabinet($dept_nr, $condition)
	{
		global $db;
		$dept_ward = "AND (ward_nr='0' OR ward_nr='') ";
		if ($dept_nr!='')
			$dept_ward .= " AND taikhoa.department='".$dept_nr."' ";
				
		$this->sql="SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, tatcakhoa.product_lot_id, tatcakhoa.exp_date , taikhoa.*   
                FROM $this->tb_med_avai_dept AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medicine AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					".$dept_ward." 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
                    AND taikhoa.available_number>0 	
					".$condition." 
                ORDER BY khochan.product_name ";
	
		if($this->result=$db->Execute($this->sql)) {
			if($this->result->RecordCount()) {
				 return $this->result;	 
			} else { return false; }
		} else { return false; }      
	}
	
	function getInfoMedInAvaiDept($IDmed){
		global $db;
		$this->sql="SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, tatcakhoa.product_lot_id, tatcakhoa.exp_date , taikhoa.*   
                FROM $this->tb_med_avai_dept AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medicine AS donvi 
                WHERE taikhoa.ID='$IDmed' 
				AND taikhoa.available_product_id=tatcakhoa.available_product_id 
                AND khochan.product_encoder=tatcakhoa.product_encoder 
                AND donvi.unit_of_medicine=khochan.unit_of_medicine	";	

	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}    
	}
	
	function getMedAvaiDeptIfExist($avai_pro_id, $dept, $ward){
		global $db;
		$this->sql="SELECT * FROM $this->tb_med_avai_dept 
					WHERE available_product_id='$avai_pro_id' AND department='$dept' AND ward_nr='$ward' ";	

	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}    	
	}
	
	function deleteMedAvaiDept($ID) {
	    global $db;
		if(!$ID) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_avai_dept 
					WHERE ID='$ID'";
		return $this->Transact($this->sql);	
	}
	
	function updateMedAvaiDept($id, $value){
	    global $db;	
		$this->sql="UPDATE $this->tb_med_avai_dept 
					SET available_number=available_number+'$value' 
					WHERE ID='$id' ";
		return $this->Transact($this->sql);
	}
	
	function insertMedAvaiDept($avai_pro_id, $dept, $ward, $value){
	    global $db;	
		$this->sql="INSERT INTO $this->tb_med_avai_dept(ID,available_product_id,department,ward_nr,available_number,init_number)
                    VALUES (0,'$avai_pro_id','$dept','$ward',$value,'0')";
		return $this->Transact($this->sql);
	}
	
	function insertArchive($dept, $ward, $product_encoder, $product_lotid, $get_use, $number, $issuepaper_id=0, $pres_id=0, $use_id=0, $return_id=0, $destroy_id=0, $user, $typeput){
		global $db;	
		
		$this->sql="INSERT INTO $this->tb_med_archive(nr,dept_nr,ward_nr,typeput,product_encoder,product_lot_id,get_use,number,issuepaper_id,pres_id, use_id,return_id,destroy_id,at_date_time,user)
                    VALUES (0, '$dept', '$ward', '$typeput', '$product_encoder', '$product_lotid', '$get_use', '$number', '$issuepaper_id', '$pres_id', '$use_id', '$return_id', '$destroy_id', CURRENT_TIMESTAMP, '$user')";
		//echo $this->sql;
		return $this->Transact($this->sql);	
	}

}
?>
