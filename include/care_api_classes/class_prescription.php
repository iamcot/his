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
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class Prescription extends Core {
	/**#@+
	* @access private
	* @var string
	*/
	/**
	* Table name for prescription data
	*/
	var $tb='care_encounter_prescription';
	/**
	* Table name for prescription_sub data
	*/
	var $tb_sub='care_encounter_prescription_sub';
	/**
	* Table name for application types
	*/
	var $tb_app_types='care_type_application';
	/**
	* Table name for prescription types
	*/
	var $tb_pres_types='care_type_prescription';
	
	
	
	/** 10/10/2011  - Tuyen-------------------------------------------------------------------------------
	* Table name for prescription info 
	*/
	var $tb_phar_pres_info='care_pharma_prescription_info';
	/** 10/10/2011
	* Table name for prescription 
	*/
	var $tb_phar_pres='care_pharma_prescription';
	/** 10/10/2011 
	* Table name for prescription type 
	*/
	var $tb_phar_pres_type='care_pharma_type_of_prescription';
	
	var $tb_phar_pres_unit='care_pharma_unit_of_medicine';
	

        
        //********************************
        //********************************
    var $tb_chemical_pres_info='care_chemical_prescription_info';
	
	var $tb_chemical_pres='care_chemical_prescription';
	
	var $tb_chemical_pres_type='care_chemical_type_of_prescription';
        //********************************
        //********************************

	/** @access private
	* Field names of care_pharma_prescription_info table
	* @var int
	*/		
	var $tabfields=array('prescription_id',
							'prescription_type',
							'dept_nr',
							'ward_nr',
							'date_time_create',
							'symptoms',
							'diagnosis',
							'note',
							'history',
							'doctor',	
							'encounter_nr',
							'sum_date',
							'modify_id',
							'status_bill',
							'status_finish',
							'total_cost',
							'in_issuepaper',
							'issue_user',
							'issue_note',
							'receive_user',
							'phieutheodoi');

	/**
	* Field names of care_pharma_prescription table
	* @var int
	*/
	var $tabfields_sub=array('nr',
							'prescription_id', 
							'product_encoder',
							'product_name',
							'lotid',
							'sum_number',
							'number_receive',
							'number_of_unit',
							'type_use',
							'desciption',
							'note',
							'cost',
							'time_use',
							'morenote',
							'speed');						
	/** @access private
	* Field names of care_chemical_prescription_info table
	* @var int
	*/	
	var $tabchemicalfields=array('prescription_id',
							'prescription_type',
							'dept_nr',
							'ward_nr',
							'date_time_create',
							'symptoms',
							'diagnosis',
							'note',
							'history',
							'doctor',	
							'encounter_nr',
							'sum_date',
							'modify_id',
							'status_bill',
							'status_finish',
							'total_cost',
							'in_issuepaper',
							'issue_user',
							'issue_note',
							'receive_user');
	/**
	* Field names of care_chemical_prescription table
	* @var int
	*/
	var $tabchemicalfields_sub=array('nr',
							'prescription_id', 
							'product_encoder',
							'product_name',
							'sum_number',
							'number_receive',
							'number_of_unit',
							'desciption',
							'note',
							'cost',
							'time_use',
							'morenote');							
	//-------------------------------------------------------------------------------------------------------------
	
	
	 
	
	/**
	* SQL query result buffer
	* @var adodb record object
	*/
	var $result;
	/**
	* Preloaded department data
	* @var adodb record object
	*/
	var $preload_dept;
	/**
	* Preloaded data flag
	* @var boolean
	*/
	var $is_preloaded=false;
	/**
	* Number of departments
	* @var int
	*/
	var $dept_count;
	/**
	* Field names of care_encounter_prescription table
	* @var int
	*/
	var $tabfields1=array('nr',
						'encounter_nr',
						'prescribe_date',
						'prescriber',
						'notes',						
						'status',
						'history',
						'modify_id',
						'modify_time',
						'create_id',
						'create_time');
	/**
	* Field names of care_encounter_prescription table
	* @var int
	*/
	var $tabfields_sub1=array('nr', 
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
							'companion');
							
							
	/**#@-*/
						
	/**
	* Constructor
	*/
	function Prescription(){
		$this->setTable($this->tb_phar_pres_info);
		$this->setRefArray($this->tabpharfields);
	}
	
	/**
	* Sets the core object to point  to either care_encounter_prescription or care_encounter_prescription_sub and field names.
	*
	* The table is determined by the parameter content. 
	* @access public
	* @param string Determines the final table name 
	* @return boolean.
	*/
	function usePrescription($type){
		if($type=='prescription_info'){
			$this->setTable($this->tb_phar_pres_info);
			$this->setRefArray($this->tabpharfields);
		}elseif($type=='prescription'){
			$this->setTable($this->tb_phar_pres);
			$this->setRefArray($this->tabpharfields_sub);
		}else{return false;}
	}
		
	/**
	* Gets all prescription types returned in a 2 dimensional array.
	*
	* The resulting data have the following index keys:
	* - nr = the primary key number
	* - type = prescription type
	* - name = type default name
	* - LD_var = variable's name for the foreign laguange version of type's name
	*
	* @access public
	* @return mixed array or boolean
	*/
	function getPrescriptionTypes($noingoaitru=''){
	    global $db;
	   if($noingoaitru!=''){
			if($noingoaitru==1)			//noi tru
				$this->sql="SELECT * FROM $this->tb_phar_pres_type WHERE group_pres='1'";
			else
				$this->sql="SELECT * FROM $this->tb_phar_pres_type WHERE group_pres='0'";
		}
		else
			$this->sql="SELECT * FROM $this->tb_phar_pres_type";
		
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/**
	* Gets all application types returned in a 2 dimensional array.
	*
	* The resulting data have the following index keys:
	* - nr = the primary key number
	* - group_nr = the group number
	* - type = application type
	* - name = application's default name
	* - LD_var = variable's name for the foreign laguange version of application's name
	* - description =  description
	*
	* @access public
	* @return mixed array or boolean
	*/
	function getAppTypes(){
	    global $db;
	
	    if ($this->result=$db->Execute("SELECT nr,group_nr,type,name,LD_var AS \"LD_var\" ,description FROM $this->tb_app_types")) {
		    if ($this->result->RecordCount()) {
		        return $this->result->GetArray();
			} else {
				return false;
			}
		}
		else {
		    return false;
		}
	}
	function getUnitTypes(){
	    global $db;
	
	    if ($this->result=$db->Execute("SELECT * FROM $this->tb_phar_pres_unit")) {
		    if ($this->result->RecordCount()) {
		        return $this->result->GetArray();
			} else {
				return false;
			}
		}
		else {
		    return false;
		}
	}
	/**
	* Gets the information of an application type based on its type number key.
	*
	* The resulting data have the following index keys:
	* - type = application type
	* - group_nr = the group number
	* - name = application's default name
	* - LD_var = variable's name for the foreign laguange version of application's name
	* - description =  description
	*
	* @access public
	* @param int Type number
	* @return mixed array or boolean
	*/
	function getAppTypeInfo($type_nr){
	    global $db;
	
	    if ($this->result=$db->Execute("SELECT type,group_nr,name,LD_var AS \"LD_var\" ,description FROM $this->tb_app_types WHERE nr=$type_nr")) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			} else {
				return false;
			}
		}
		else {
		    return false;
		}
	}
	/**
	* Gets the information of a prescription type based on its type number key.
	*
	* The resulting data have the following index keys:
	* - type = application type
	* - name = application's default name
	* - LD_var = variable's name for the foreign laguange version of application's name
	* - description =  description
	*
	* @access public
	* @param int Type number
	* @return mixed array or boolean
	*/
	function getPrescriptionTypeInfo($type_nr){
	    global $db;
	
	    if ($this->result=$db->Execute("SELECT type,name,LD_var  AS \"LD_var\",description FROM $this->tb_pres_types WHERE nr=$type_nr")) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			} else {
				return false;
			}
		}
		else {
		    return false;
		}
	}
	/**
	* Gets all current prescription data based on the primary key.
	* Gjergj Sheldija
	* changed by gjergj sheldija
	* to work with the new way of managing prescriptions
	* @param int Encounter number
	* @return mixed adodb record object or boolean
	*/
	function getAllPrescriptionById($nr){
		global $db;
		$this->sql="SELECT $this->tb_sub.* 
			FROM $this->tb_sub 
			WHERE $this->tb_sub.prescription_nr=$nr 
				AND $this->tb_sub.is_stopped IN ('',0) ORDER BY $this->tb_sub.prescription_nr";
		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		}else{
			return false;
		}
	}
	/**
	 * Updates the status of a prescription, based on the encounter nr
	 * Gjergj Sheldija
	 * @param int prescription number
	 * @param string new status
	 * @return boolean
	 */
	function setPrescriptionStatus($prescriptionNr,$status) {
	    global $db;
		if(!$prescriptionNr) return FALSE;
		//prescription
		$this->sql="UPDATE $this->tb 
						SET status='$status'
						WHERE nr=$prescriptionNr";
		//echo $this->sql;
		$this->Transact($this->sql);
		//prescriprion_sub
		$this->sql="UPDATE $this->tb_sub 
						SET status='$status'
						WHERE prescription_nr=$prescriptionNr";
		return $this->Transact($this->sql);	
		//echo $this->sql;
	}
	
	
	/** 18/10/2011 -Tuyen-----------------------------------------------------------------------------
	 * Get all info of a prescription, based on the prescription id
	 * Tuyen
	 * @param int prescription number
	 * @return table result or boolean
	 */
	function getDetailPrescriptionInfo($nr){
	    global $db;
		$this->sql="SELECT pr.*,prs.*, pr.note AS generalnote 
					FROM $this->tb_phar_pres_info AS pr, $this->tb_phar_pres AS prs  
					WHERE pr.prescription_id='".$nr."' AND pr.prescription_id=prs.prescription_id";
		//echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get general info of a prescription, based on the prescription id
	 * Tuyen
	 * @param int prescription number
	 * @return mixed array or boolean
	 */
	function getPrescriptionInfo($nr){
	    global $db;
		$this->sql="SELECT pr.*,t.prescription_type_name AS type_name, t.typeput, dept.name_formal  
					FROM $this->tb_phar_pres_type AS t, $this->tb_phar_pres_info AS pr
					LEFT JOIN care_department AS dept 
					ON dept.nr=pr.dept_nr 
					WHERE pr.prescription_id='".$nr."' 
					AND pr.prescription_type=t.prescription_type";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}

	function findInventoryKhoLe($product_encoder,$typeput){
	    global $db;
		$this->sql="SELECT sum(available_number) AS sum 
					FROM care_pharma_available_product 
					WHERE product_encoder='$product_encoder' AND typeput='$typeput'";
					//echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        $buf = $this->result->FetchRow();
				return $buf['sum'];
			}else{return 0;}
		}else{return 0;}
	}	
	function findInventoryKhoLe_ToaThuoc($product_encoder,$typeput){
	    global $db;
		$this->sql="SELECT *, sum(available_number) AS sum 
					FROM care_pharma_available_product 
					WHERE product_encoder='$product_encoder' AND typeput='$typeput' AND available_number>0
					GROUP BY price";
					//echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;				
			}else{return 0;}
		}else{return 0;}
	}	
	/** 18/10/2011
	 * Get all medicine in a prescription, based on the prescription id
	 * Tuyen
	 * @param int prescription number
	 * @return table result or boolean
	 */
	function getAllMedicineInPres($nr){
	    global $db;
		$this->sql="SELECT prs.* 
					FROM $this->tb_phar_pres AS prs  
					WHERE prs.prescription_id='".$nr."'
					ORDER BY nr";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/** 18/10/2011
	 * Get ID of last prescription
	 * Tuyen
	 * @return mixed array or boolean
	 */
	function getLastIDPrescription(){
		global $db;
		$this->sql="SELECT MAX(prescription_id) AS prescription_id FROM $this->tb_phar_pres_info ";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	function getEncoder($nr){
	    global $db;
		$this->sql="SELECT product_encoder FROM $this->tb_phar_pres WHERE nr='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getDeptWard($pres_id){
	    global $db;
		$this->sql="SELECT dept_nr, ward_nr FROM $this->tb_phar_pres_info WHERE prescription_id='".$pres_id."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function getTypePut($pres_id){
	    global $db;
		$this->sql="SELECT type.*  
					FROM $this->tb_phar_pres_info AS info, care_pharma_type_of_prescription AS type 
					WHERE info.prescription_type=type.prescription_type 
					AND info.prescription_id='".$pres_id."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}	
	function getOldPrescriptionInfo($enc_nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_phar_pres_info WHERE encounter_nr='".$enc_nr."' AND total_cost>0 ORDER BY prescription_id DESC";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	/** 18/10/2011
	 * Updates the status bill of a prescription, based on the prescription id
	 * @param int prescription id
	 * @param string new status
	 * @return boolean
	 */
	function setPresStatusBill($prescriptionId,$status) {
	    global $db;
		if(!$prescriptionId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres_info
						SET status_bill='$status'
						WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}
	
	/** 18/10/2011
	 * Updates the status finish of a prescription, based on the prescription id
	 * @param int prescription id
	 * @param string new status
	 * @return boolean
	 */
	function setPresStatusFinish($prescriptionId,$status) {
	    global $db;
		if(!$prescriptionId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres_info
						SET status_finish='$status'
						WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}

	/** 18/10/2011
	 * Updates the status finish of a prescription, based on the prescription id
	 * @param int prescription id
	 * @param string new status
	 * @return boolean
	 */
	function deleteAllMedicineInPres($prescriptionId) {
	    global $db;
		if(!$prescriptionId) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_pres
					WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}	
	
	function deletePres($prescriptionId) {
	    global $db;
		if(!$prescriptionId) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_pres_info
					WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}	
	
	/** 01/11/2011
	 * Get all pres of encounter_nr, with status_bill=id_bill of billing
	 * Tuyen
	 * @param int encounter number, status=0 (not pay) or >=1 (paid)
	 * @return table result or boolean
	 */ 
	 //edit vy add p.product_name
  	function getAllPresOfEncounterByBillId($encounterId, $status_bill){
	    global $db;
		$this->sql="SELECT prs.*, t.prescription_type_name AS type_name 
					FROM $this->tb_phar_pres_info AS prs, $this->tb_phar_pres_type AS t 
					WHERE prs.encounter_nr='".$encounterId."' 
					AND prs.status_bill='".$status_bill."' 
					AND prs.total_cost>0
					AND prs.prescription_type=t.prescription_type";
			//echo $sql;		
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}

	function getAllPresOfEncounter($encounter_nr, $dept_nr='', $ward_nr='', $phieutheodoi=''){
	    global $db;
		$dept_ward='';
		if($dept_nr!='' && $dept_nr!='0') $dept_ward .=" AND prs.dept_nr='".$dept_nr."' ";
		if($ward_nr!='' && $ward_nr!='0') $dept_ward .=" AND prs.ward_nr='".$ward_nr."' ";
		if($phieutheodoi!='' && $phieutheodoi!='0') 
			$phieutheodoi_cmd=" AND prs.phieutheodoi='".$phieutheodoi."' ";
		else $phieutheodoi_cmd="";
				//edit 0810 cot
		$this->sql="SELECT prs.*, pr.*, prs.note AS totalnote, t.prescription_type_name AS type_name, dm.product_name 
			  FROM $this->tb_phar_pres_info AS prs, $this->tb_phar_pres AS pr, $this->tb_phar_pres_type AS t, 
					care_pharma_products_main AS dm 
			  WHERE prs.encounter_nr='".$encounter_nr."' 
			  ".$dept_ward." ".$phieutheodoi_cmd."
				AND prs.prescription_id = pr.prescription_id  
				AND pr.product_encoder = dm.product_encoder 
				AND prs.prescription_type = t.prescription_type 
			ORDER BY prs.date_time_create";
			//echo $sql;		
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getAllTreatmentOfEncounter($encounter_nr, $group_pres){
		global $db;
		if ($group_pres=='') $group_pres=0;
		$this->sql="SELECT pr.*, grp.group_pres 
			  FROM care_encounter AS e, 
				   care_person AS p, 
				   care_pharma_prescription_info AS pr, 
				   care_pharma_type_of_prescription AS grp  
			WHERE p.pid=e.pid 
				AND e.encounter_nr='".$encounter_nr."'
				AND e.encounter_nr=pr.encounter_nr 
				AND pr.phieutheodoi='0' 
				AND pr.prescription_type = grp.prescription_type 
				AND grp.group_pres = '".$group_pres."' 
			ORDER BY pr.date_time_create";
			
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	/** 15/11/2011
	 * Get pending in/out-patient prescription (by stastus_finish)
	 * Tuyen
	 * @param string "allpatient", "inpatient" or "outpatient" 
	 * @param int status=0 (not get medicine) or >=1 (got)
	 * @return table result or boolean
	 */
	function getAllPresByTypePatient($in_out_patient, $status_finish, $in_issuepaper){
	    global $db;
		$if_type='';
		switch($in_out_patient){
			case 'Allpatient':
			case 'allpatient':{		
						$if_type='';
						break;}
						
			case 'Inpatient':
			case 'inpatient':{		
						$if_type=" AND (prs.prescription_type='0397' OR prs.prescription_type='0398') ";
						break;}
						
			case 'Outpatient':			
			case 'outpatient':{		
						$if_type=" AND (prs.prescription_type='0399' OR prs.prescription_type='0400') ";
						break;}
						
			default: $if_type='';
		}
		
		$this->sql="SELECT prs.*, t.prescription_type_name AS type_name 
					FROM $this->tb_phar_pres_info AS prs, $this->tb_phar_pres_type AS t 
					WHERE prs.status_finish='".$status_finish."' 
					AND prs.prescription_type=t.prescription_type ".$if_type." 
					AND prs.in_issuepaper= ".$in_issuepaper." 
					AND prs.sum_date>0
					ORDER BY prs.date_time_create";
					
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	
	/** 28/11/2011
	 * Group all medicine in list prescription to create issuepaper
	 * Tuyen
	 * @param array "list_pres_id"
	 * @return table result or boolean
	 */
	function sumMedicineByListPresId($list_pres_id){
	    global $db;
		if(is_array($list_pres_id)){
			$list_id = "WHERE prescription_id ='".$list_pres_id[0]."' ";
			for ($i = 1; $i < count($list_pres_id); $i++) {
			  $list_id = $list_id." OR prescription_id ='".$list_pres_id[$i]."' ";
			}
		}
		else
			return false;
		
		$this->sql="SELECT  product_encoder, product_name, SUM(sum_number) AS sumpres, note AS units
					FROM $this->tb_phar_pres ".$list_id." GROUP BY product_encoder, note";
					
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/** 18/10/2011
	 * Updates the status in_issuepaper of a prescription, based on the prescription id
	 * @param int prescription id
	 * @param string new status
	 * @return boolean
	 */
	function setPresStatusInIssue($prescriptionId,$status) {
	    global $db;
		if(!$prescriptionId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres_info
						SET in_issuepaper='$status'
						WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}
	
	/** 1/12/2011
	 * Updates the number of receive medicine in prescription, based on the prescription_id and product_name
	 * @param int prescription id
	 * @param string new status
	 * @return boolean
	 */
	function setReceiveMedicineInPres($medicine_nr,$receive_number) {
	    global $db;
		if(!$medicine_nr) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres 
						SET number_receive='".$receive_number."' 
						WHERE nr='".$medicine_nr."' ";

		return $this->Transact($this->sql);	
	}
	function setReceiveMedicineInPres_ToaThuoc($medicine_nr, $receive_number, $cost) {
	    global $db;
		if(!$medicine_nr) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres 
						SET number_receive='".$receive_number."', cost='".$cost."' 
						WHERE nr='".$medicine_nr."' ";

		return $this->Transact($this->sql);	
	}
	/** 2014/03/26
     * Update cost for 1 medicine in pres
     * */
    function updateCostOneMedicine($medicine_nr,$cost,$recv){
        global $db;
        $this->sql="UPDATE ".$this->tb_phar_pres."
                        SET cost = '$cost', number_receive = '$recv'
                        WHERE nr = $medicine_nr";
        return $this->Transact($this->sql);
    }
	/** 5/12/2011
	 * Updates the cost all medicine in prescription, based on the prescription_id and product_name
	 * @param int prescription id
	 * @return boolean
	 */
	function updateCostAllMedicineInPres($pres_id) {
	    global $db;
		if(!$pres_id) return FALSE;

		$this->sql="UPDATE $this->tb_phar_pres INNER JOIN care_pharma_products_main
					ON $this->tb_phar_pres.product_encoder = care_pharma_products_main.product_encoder 
					SET $this->tb_phar_pres.cost = care_pharma_products_main.price
					WHERE $this->tb_phar_pres.prescription_id='".$pres_id."'";

		return $this->Transact($this->sql);
	}
	
	/** 5/12/2011
	 * Updates total cost of prescription, 
	 * first updates the cost all medicine in prescription, then update total cost
	 * @param int prescription id
	 * @return boolean
	 */
	function updateCostPres($pres_id){
	    global $db;
		if(!$pres_id) return FALSE;
		
		if($this->updateCostAllMedicineInPres($pres_id)) {
			$this->sql="UPDATE $this->tb_phar_pres_info
						SET total_cost=(SELECT CASE WHEN SUM(cost*number_receive)>0 THEN SUM(cost*number_receive) 
												ELSE SUM(cost*sum_number) END  
										FROM $this->tb_phar_pres 
										WHERE prescription_id='$pres_id') 
						WHERE prescription_id='$pres_id'";
			return $this->Transact($this->sql);	
			
		}else{ return false;}
	}
	
	/** 5/12/2011
	 * Updates the issue_user, issue_note, receive_user of a issuepaper, based on the issuepaper id
	 * @param int pres id
	 * @param string issue_user, issue_note, receive_user
	 * @return boolean
	 */
	function setInfoPersonWhenIssuePres($presId,$issue_user,$issue_note,$receive_user) {
	    global $db;
		if(!$presId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres_info
						SET issue_user='$issue_user', issue_note='$issue_note', receive_user='$receive_user'  
						WHERE prescription_id=$presId";
		return $this->Transact($this->sql);	
	}
	
	/** 5/12/2011
	 * Updates the final cost of pres after issue (for status_bill=0)
	 * @param int pres id
	 * @param string issue_user, issue_note, receive_user
	 * @return boolean
	 */
	function setCostPres($pres_id, $totalpres) {
	    global $db;
		if(!$pres_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres_info
						SET total_cost='$totalpres' 
						WHERE prescription_id=$pres_id";
		return $this->Transact($this->sql);	
	}
	
	function IssueMedicineForPatient($enc_nr, $date_issue, $encoder, $number, $pres_id) {
	    global $db;
		global $_SESSION;
		if($encoder=='') return FALSE;
		$this->sql="INSERT INTO care_pharma_prescription_issue 
					(enc_nr, date_issue, product_encoder, number, pres_id, create_id)
					VALUES
					('".$enc_nr."', '".$date_issue."', '".$encoder."', '".$number."', '".$pres_id."', '".$_SESSION['sess_user_name']."');";
		return $this->Transact($this->sql);	
	}	
	function getMedicineIssue($pres_id, $product_encoder){
		global $db;
		$this->sql="SELECT *, sum(number) AS sum FROM care_pharma_prescription_issue
					WHERE pres_id='".$pres_id."' AND product_encoder='".$product_encoder."'
					GROUP BY product_encoder";
			
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	function listMedicineIssueByEncounter($encounter_nr){
		global $db;
		$this->sql="SELECT *, sum(number) AS sum FROM care_pharma_prescription_issue
					WHERE enc_nr='".$encounter_nr."' 
					GROUP BY product_encoder, date_issue
					ORDER BY product_encoder, date_issue";
			
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}	
	}	
	function listMedicineIssueByPresId($pres_id){
		global $db;
		$this->sql="SELECT iss.*,main.product_name, SUM(iss.number) AS SUM FROM care_pharma_prescription_issue AS iss, care_pharma_products_main AS main
					WHERE iss.pres_id='".$pres_id."' AND iss.product_encoder=main.product_encoder 
					GROUP BY iss.product_encoder, iss.date_issue
					ORDER BY iss.product_encoder, iss.date_issue";
			
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}	
	}	
	function listMedicineIssueByEncNr($enc_nr){
		global $db;
		$this->sql="SELECT iss.*,main.product_name, SUM(iss.number) AS SUM FROM care_pharma_prescription_issue AS iss, care_pharma_products_main AS main
					WHERE iss.enc_nr='".$enc_nr."' AND iss.product_encoder=main.product_encoder 
					GROUP BY iss.product_encoder, iss.date_issue
					ORDER BY iss.product_encoder, iss.date_issue";
			
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}	
	}	
	function setDongPhatThuoc($pres_id) {
	    global $db;
		if(!$pres_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_pres_info
						SET dongphatthuoc='1' 
						WHERE prescription_id='$pres_id'";
		return $this->Transact($this->sql);	
	}	
	
        //**********************************************************************
        //**********************************************************************
        //Hoa chat
        //**********************************************************************
        //**********************************************************************
        //cho t?t c? c�c lo?i b?nh nh�n
        function getAllChemicalPresByTypePatient($in_out_patient, $status_finish, $in_issuepaper){
	    global $db;
            $if_type='';
            switch($in_out_patient){
                    case 'Allpatient':
                    case 'allpatient':{		
                                            $if_type='';
                                            break;}

                    case 'Inpatient':
                    case 'inpatient':{		
                                            $if_type=" AND (prs.prescription_type='0397' OR prs.prescription_type='0398') ";
                                            break;}

                    case 'Outpatient':			
                    case 'outpatient':{		
                                            $if_type=" AND (prs.prescription_type='0399' OR prs.prescription_type='0400') ";
                                            break;}

                    default: $if_type='';
            }
		
            $this->sql="SELECT prs.*, t.prescription_type_name AS type_name 
                        FROM $this->tb_chemical_pres_info AS prs, $this->tb_chemical_pres_type AS t 
                        WHERE prs.status_finish='".$status_finish."' 
                        AND prs.prescription_type=t.prescription_type ".$if_type." 
                        AND prs.in_issuepaper= ".$in_issuepaper." 
                        ORDER BY prs.date_time_create";
					
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function getAllChemicalOfEncounterByBillId($encounterId, $status_bill){
	    global $db;
		$this->sql="SELECT prs.*, t.prescription_type_name AS type_name 
					FROM $this->tb_chemical_pres_info AS prs, $this->tb_chemical_pres_type AS t 
					WHERE prs.encounter_nr='".$encounterId."' 
					AND prs.status_bill='".$status_bill."' 
					AND prs.prescription_type=t.prescription_type";
			//echo $sql;		
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}       
    function getAllChemicalInPres($nr){
	    global $db;
		$this->sql="SELECT prs.* 
                            FROM $this->tb_chemical_pres AS prs  
                            WHERE prs.prescription_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
    function getPrescriptionChemicalInfo($nr){
	    global $db;
            $this->sql="SELECT pr.*,t.prescription_type_name AS type_name,t.typeput, dept.name_formal 
                        FROM $this->tb_chemical_pres_type AS t, $this->tb_chemical_pres_info AS pr
						LEFT JOIN care_department AS dept 
						ON dept.nr=pr.dept_nr 						
                        WHERE pr.prescription_id='".$nr."' 
                        AND pr.prescription_type=t.prescription_type";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
        
        function sumChemicalByListPresId($list_pres_id){
	    global $db;
		if(is_array($list_pres_id)){
			$list_id = "WHERE prescription_id ='".$list_pres_id[0]."' ";
			for ($i = 1; $i < count($list_pres_id); $i++) {
			  $list_id = $list_id." OR prescription_id ='".$list_pres_id[$i]."' ";
			}
		}
		else
			return false;
		
		$this->sql="SELECT  product_encoder, product_name, SUM(sum_number) AS sumpres, note AS units
					FROM $this->tb_chemical_pres ".$list_id." GROUP BY product_encoder, note";
					
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function setPresChemicalStatusInIssue($prescriptionId,$status) {
	    global $db;
            if(!$prescriptionId) return FALSE;
            //prescriprion_info
            $this->sql="UPDATE $this->tb_chemical_pres_info
                        SET in_issuepaper='$status'
                        WHERE prescription_id=$prescriptionId";
            return $this->Transact($this->sql);	
	}
	
	function getLastIDChemicalPres(){
		global $db;
		$this->sql="SELECT MAX(prescription_id) AS prescription_id FROM $this->tb_chemical_pres_info ";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	function usePrescriptionChemical($type){
		if($type=='prescription_info'){
			$this->setTable($this->tb_chemical_pres_info);
			$this->setRefArray($this->tabchemicalfields);
		}elseif($type=='prescription'){
			$this->setTable($this->tb_chemical_pres);
			$this->setRefArray($this->tabchemicalfields_sub);
		}else{return false;}
	}
	
	function deleteAllChemicalInPres($prescriptionId) {
	    global $db;
		if(!$prescriptionId) return FALSE;

		$this->sql="DELETE FROM $this->tb_chemical_pres
					WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}	
	function deleteChemicalPres($prescriptionId) {
	    global $db;
		if(!$prescriptionId) return FALSE;

		$this->sql="DELETE FROM $this->tb_chemical_pres_info
					WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}	

	function updateCostAllChemicalInPres($pres_id) {
	    global $db;
		if(!$pres_id) return FALSE;

		$this->sql="UPDATE $this->tb_chemical_pres INNER JOIN care_chemical_products_main
					ON $this->tb_chemical_pres.product_encoder = care_chemical_products_main.product_encoder 
					SET $this->tb_chemical_pres.cost = care_chemical_products_main.price
					WHERE $this->tb_chemical_pres.prescription_id='".$pres_id."'";

		return $this->Transact($this->sql);
	}
	
	function updateCostChemical($pres_id){
	    global $db;
		if(!$pres_id) return FALSE;
		
		if($this->updateCostAllMedicineInPres($pres_id)) {
			$this->sql="UPDATE $this->tb_chemical_pres_info
						SET total_cost=(SELECT CASE WHEN SUM(cost*number_receive)>0 THEN SUM(cost*number_receive) 
												ELSE SUM(cost*sum_number) END  
										FROM $this->tb_chemical_pres 
										WHERE prescription_id='$pres_id') 
						WHERE prescription_id='$pres_id'";
			return $this->Transact($this->sql);	
			
		}else{ return false;}
	}
	function setChemicalStatusBill($prescriptionId,$status) {
	    global $db;
		if(!$prescriptionId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_pres_info
						SET status_bill='$status'
						WHERE prescription_id=$prescriptionId";
		return $this->Transact($this->sql);	
	}
	
	/** 18/10/2011
	 * Updates the status finish of a prescription, based on the prescription id
	 * @param int prescription id
	 * @param string new status
	 * @return boolean
	 */
	function setChemicalStatusFinish($prescriptionId,$status) {
	    global $db;
		if(!$prescriptionId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_pres_info 
						SET status_finish='$status' 
						WHERE prescription_id=$prescriptionId"; 
		return $this->Transact($this->sql);	
	}
	function setInfoPersonWhenIssueChemical($presId,$issue_user,$issue_note,$receive_user) {
	    global $db;
		if(!$presId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_pres_info
						SET issue_user='$issue_user', issue_note='$issue_note', receive_user='$receive_user'  
						WHERE prescription_id=$presId";
		return $this->Transact($this->sql);	
	}
	
	function setCostChemical($pres_id, $totalpres) {
	    global $db;
		if(!$pres_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_pres_info
						SET total_cost='$totalpres' 
						WHERE prescription_id=$pres_id";
		return $this->Transact($this->sql);	
	}
	
	function getPrescriptionChemicalTypes($noingoaitru=''){
	    global $db;
	    if($noingoaitru!=''){
			if($noingoaitru==1)			//noi tru
				$this->sql="SELECT * FROM $this->tb_chemical_pres_type WHERE group_pres='1'";
			else
				$this->sql="SELECT * FROM $this->tb_chemical_pres_type WHERE group_pres='0'";
		}
		else
			$this->sql="SELECT * FROM $this->tb_chemical_pres_type";		
		
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function getDetailPrescriptionInfo1($enc_nr){
	    global $db;
		$this->sql="SELECT pr.*,prs.* 
                            FROM $this->tb_phar_pres_info AS pr, $this->tb_phar_pres AS prs  
                            WHERE pr.encounter_nr='".$enc_nr."' AND pr.prescription_id=prs.prescription_id
                            GROUP BY prs.product_encoder";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		        return $this->sql;
		}else{return false;}
	}
	function getChemicalTypePut($pres_id){
	    global $db;
		$this->sql="SELECT type.*  
					FROM $this->tb_chemical_pres_info AS info, care_chemical_type_of_prescription AS type 
					WHERE info.prescription_type=type.prescription_type 
					AND info.prescription_id='".$pres_id."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function getChemicalDeptWard($pres_id){
	    global $db;
		$this->sql="SELECT dept_nr, ward_nr FROM $this->tb_chemical_pres_info WHERE prescription_id='".$pres_id."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function setReceiveChemicalInPres($medicine_nr,$receive_number) {
	    global $db;
		if(!$medicine_nr) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_pres 
						SET number_receive='".$receive_number."' 
						WHERE nr='".$medicine_nr."' ";

		return $this->Transact($this->sql);	
	}
	function getEncoderChemical($nr){
	    global $db;
		$this->sql="SELECT product_encoder FROM $this->tb_chemical_pres WHERE nr='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}

	function findInventoryKhoChan_HoaChat($product_encoder,$typeput){
	    global $db;
		$this->sql="SELECT sum(number) AS sum 
					FROM care_chemical_products_main_sub 
					WHERE product_encoder='$product_encoder' AND typeput='$typeput'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        $buf = $this->result->FetchRow();
				return $buf['sum'];
			}else{return 0;}
		}else{return 0;}
	}
	function updateAvaiPro_Sub($avai_pro, $number) {
	    global $db;
		$this->sql="UPDATE care_pharma_available_product  
						SET available_number=available_number - '".$number."' 
						WHERE available_product_id='".$avai_pro."' ";
		"UPDATE care_pharma_available_product AS avai, care_pharma_prescription AS pr
		SET avai.available_number = avai.available_number + pr.sum_number
		WHERE avai.available_product_id = pr.avai_pro_id 
		AND pr.prescription_id='$id' ";
	
		return $this->Transact($this->sql);	
	}
	function updateAvaiPro_Plus($pres_id) {
	    global $db;
		$this->sql="UPDATE care_pharma_available_product AS avai, care_pharma_prescription AS pr
					SET avai.available_number = avai.available_number + pr.sum_number
					WHERE avai.available_product_id = pr.avai_pro_id 
					AND pr.prescription_id='$pres_id' ";
	
		return $this->Transact($this->sql);	
	}	
	//Xu ly tam ton
	function updateAvaiPro_TamTon_Sub($avai_pro, $number) {
	    global $db;
		$this->sql="UPDATE care_pharma_available_product  
						SET allocation_temp=allocation_temp - '".$number."' 
						WHERE available_product_id='".$avai_pro."' ";
		"UPDATE care_pharma_available_product AS avai, care_pharma_prescription AS pr
		SET avai.allocation_temp = avai.allocation_temp + pr.sum_number
		WHERE avai.available_product_id = pr.avai_pro_id 
		AND pr.prescription_id='$id' ";
	
		return $this->Transact($this->sql);	
	}
	function updateAvaiPro_TamTon_Plus($pres_id) {
	    global $db;
		$this->sql="UPDATE care_pharma_available_product AS avai, care_pharma_prescription AS pr
					SET avai.allocation_temp = avai.allocation_temp + pr.sum_number
					WHERE avai.available_product_id = pr.avai_pro_id 
					AND pr.prescription_id='$pres_id' ";
	
		return $this->Transact($this->sql);	
	}	
	
//	function getAllPresOfEncounter_1($encounter_nr, $phieutheodoi=''){
//	    global $db;
//		if($phieutheodoi!='' && $phieutheodoi!='0')
//			$phieutheodoi_cmd=" AND prs.phieutheodoi='".$phieutheodoi."' ";
//		else $phieutheodoi_cmd="";
//        //thu
//        $this->sql="SELECT prs.*, pr.*, prs.note AS totalnote, t.prescription_type_name AS type_name, dm.product_name
//			  FROM $this->tb_phar_pres_info AS prs, $this->tb_phar_pres AS pr, $this->tb_phar_pres_type AS t,
//					care_pharma_products_main AS dm
//			  WHERE prs.encounter_nr='".$encounter_nr."'
//			   ".$phieutheodoi_cmd."
//				AND prs.prescription_id = pr.prescription_id
//				AND pr.product_encoder = dm.product_encoder
//				AND prs.prescription_type = t.prescription_type
//			ORDER BY prs.date_time_create";
//			//echo $sql;
//	    if ($this->result=$db->Execute($this->sql)) {
//		    if ($this->result->RecordCount()) {
//		        return $this->result;
//			}else{return false;}
//		}else{return false;}
//	}
}
?>
