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
class Issuepaper extends Core {
	/**#@+
	* @access private
	* @var string
	*/
		
	/** 10/10/2011  - Tuyen-------------------------------------------------------------------------------
	* Table name for issuepaper info 
	*/
	var $tb_phar_issue_info='care_pharma_issue_paper_info';
	/** 10/10/2011
	* Table name for issuepaper 
	*/
	var $tb_phar_issue='care_pharma_issue_paper';
	/** 10/10/2011
	* Table name for prescription info 
	*/
	var $tb_phar_pres_info='care_pharma_prescription_info';
	/** 10/10/2011
	* Table name for prescription 
	*/
	var $tb_phar_pres='care_pharma_prescription';
	
	//**********************************************************************
        //**********************************************************************
        //Hoa chat-Huynh
        var $tb_chemical_issue_info='care_chemical_issue_paper_info';
	var $tb_chemical_issue='care_chemical_issue_paper';
	var $tb_chemical_pres_info='care_chemical_prescription_info';
	var $tb_chemical_pres='care_chemical_prescription';
        //**********************************************************************
        //**********************************************************************
	
	/** @access private
	* Field names of care_pharma_issuepaper_info table
	* @var int
	*/
	var $tabpharfields=array('issue_paper_id',
							'dept_nr',
							'ward_nr',	
							'type',		
							'typeput',							
							'date_time_create',
							'nurse',
							'history',
							'note',	
							'modify_id',
							'status_finish',
							'issue_user',
							'issue_note',
							'receive_user');
	/**
	* Field names of care_pharma_issuepaper table
	* @var int
	*/
	var $tabpharfields_sub=array('nr',
							'issue_paper_id', 
							'product_encoder',
							'product_name',
							'units',
							'sumpres',
							'plus',
							'number_request',
							'number_receive',
							'note');						


	
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
	function IssuePaper(){
		$this->setTable($this->tb_phar_issue_info);
		$this->setRefArray($this->tabpharfields);
	}
	
	/**
	* Sets the core object to point  to either care_encounter_issuepaper or care_encounter_issuepaper_sub and field names.
	* Tuyen
	* The table is determined by the parameter content. 
	* @access public
	* @param string Determines the final table name 
	* @return boolean.
	*/
	function useIssuePaper($type){
		if($type=='issuepaper_info'){
			$this->setTable($this->tb_phar_issue_info);
			$this->setRefArray($this->tabpharfields);
		}elseif($type=='issuepaper'){
			$this->setTable($this->tb_phar_issue);
			$this->setRefArray($this->tabpharfields_sub);
		}else{return false;}
	}
	
	/**
	* Gets type returned in a 1 dimensional array.
	*
	* The resulting data have the following index keys:
	* - type = issuepaper type
	*
	* @access public
	* @return mixed array or boolean
	*/
	function getIssuePaperType($nr){
	    global $db;
		$this->sql="SELECT type, typeput FROM $this->tb_phar_issue_info WHERE issue_paper_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getEncoder($nr){
	    global $db;
		$this->sql="SELECT product_encoder FROM $this->tb_phar_issue WHERE nr='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getDeptWard($issue_id){
	    global $db;
		$this->sql="SELECT dept_nr, ward_nr FROM $this->tb_phar_issue_info WHERE issue_paper_id='".$issue_id."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getSumPres($issue_id, $encoder){
	    global $db;
		$this->sql="SELECT sumpres 
					FROM $this->tb_phar_issue 
					WHERE issue_paper_id='".$issue_id."' AND product_encoder='".$encoder."' ";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	/** 18/10/2011
	 * Get all info of a issuepaper, based on the issuepaper id
	 * Tuyen
	 * @param int issuepaper number
	 * @return table result or boolean
	 */
	function getDetailIssuePaperInfo($nr){
	    global $db;
		$this->sql="SELECT issinfo.*,iss.*,issinfo.note AS generalnote  
					FROM $this->tb_phar_issue_info AS issinfo, $this->tb_phar_issue AS iss  
					WHERE issinfo.issue_paper_id='".$nr."' AND iss.issue_paper_id=issinfo.issue_paper_id";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}

	}
	
	/** 18/10/2011
	 * Get general info of a issuepaper, based on the issuepaper id
	 * Tuyen
	 * @param int issuepaper number
	 * @return mixed array or boolean
	 */
	function getIssuePaperInfo($nr){
	    global $db;
		$this->sql="SELECT iss.*, dept.name_formal  
					FROM $this->tb_phar_issue_info AS iss
					LEFT JOIN care_department AS dept 
					ON dept.nr=iss.dept_nr 
					WHERE iss.issue_paper_id='".$nr."' ";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get all medicine in a issuepaper, based on the issuepaper id
	 * Tuyen
	 * @param int issuepaper number
	 * @return table result or boolean
	 */
	function getAllMedicineInIssuePaper($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_issue 
					WHERE issue_paper_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/** 18/10/2011
	 * Get ID of last issuepaper
	 * Tuyen
	 * @return mixed array or boolean
	 */
	function getLastIDIssue(){
		global $db;
		$this->sql="SELECT MAX(issue_paper_id) AS issue_paper_id FROM $this->tb_phar_issue_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	
	/** 18/10/2011
	 * Updates the status finish of a issuepaper, based on the issuepaper id
	 * @param int issuepaper id
	 * @param string new status
	 * @return boolean
	 */
	function setIssueStatusFinish($issuepaperId,$status) {
	    global $db;
		if(!$issuepaperId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_issue_info
						SET status_finish='$status'
						WHERE issue_paper_id=$issuepaperId";
		return $this->Transact($this->sql);	
	}
	
	/** 18/10/2011
	 * Updates the status finish of a issuepaper, based on the issuepaper id
	 * @param int issuepaper id
	 * @param string new status
	 * @return boolean
	 */
	function deleteAllMedicineInIssue($issuepaperId) {
	    global $db;
		if(!$issuepaperId) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_issue
					WHERE issue_paper_id=$issuepaperId";
		return $this->Transact($this->sql);	
	}	
	
	function deleteIssue($issuepaperId) {
	    global $db;
		if(!$issuepaperId) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_issue_info
					WHERE issue_paper_id=$issuepaperId";
		return $this->Transact($this->sql);	
	}	
	/** 28/11/2011
	 * List all issuepaper, with condition
	 * Tuyen
	 * @param string condition
	 * @return table result or boolean
	 */
	function listAllIssuePaper($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_issue_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 28/11/2011
	 * List some issuepaper, with condition
	 * Tuyen
	 * @param string condition, int current_page, int number_items_per_pag
	 * @return table result or boolean
	 */
	function listSomeIssuePaperSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_phar_issue_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function countIssuePaperItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_phar_issue_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	/** 5/12/2011
	 * Updates the number of receive medicine in issuepaper, based on the medicine_nr and number receive
	 * @param int medicine_nr
	 * @param int number_receive
	 * @return boolean
	 */
	function setReceiveMedicineInIssue($medicine_nr,$receive_number) {
	    global $db;
		if(!$medicine_nr) return FALSE;

		$this->sql="UPDATE $this->tb_phar_issue
						SET number_receive='".$receive_number."' 
						WHERE nr='".$medicine_nr."' ";

		return $this->Transact($this->sql);	
	}

	function setMedicineReceiveOfPresInIssue($issue_Id, $encoder, $number) {
	    global $db;
		if(!$issue_Id) return FALSE;
		$sql="	SELECT prsinfo.prescription_id, prs.sum_number 
				FROM care_pharma_prescription_info AS prsinfo, care_pharma_prescription AS prs  
				WHERE prsinfo.in_issuepaper='".$issue_Id."' AND prsinfo.prescription_id= prs.prescription_id 
					AND prs.product_encoder='".$encoder."'";
		if($tmp_result=$db->Execute($sql)){
			while($item = $tmp_result->FetchRow()){
				//echo $number.' '.$item['sum_number'];
				if($number>$item['sum_number']){
					$sql1="UPDATE care_pharma_prescription 
						SET number_receive=sum_number 
						WHERE prescription_id='".$item['prescription_id']."' AND product_encoder='".$encoder."'";
					$db->Execute($sql1);		
					$number = $number - $item['sum_number'];
				}else{
					$sql1="UPDATE care_pharma_prescription 
						SET number_receive=".$item['sum_number']." 
						WHERE prescription_id='".$item['prescription_id']."' AND product_encoder='".$encoder."'";
					$db->Execute($sql1);
					//echo $sql1;					
					break;
				}
				//echo $item['sum_number'];
			}
		}
			
	}	
	/** 5/12/2011
	 * Updates the issue_user, issue_note, receive_user of a issuepaper, based on the issuepaper id
	 * @param int issuepaper id
	 * @param string issue_user, issue_note, receive_user
	 * @return boolean
	 */
	function setInfoPersonWhenIssue($issuepaperId,$issue_user,$issue_note,$receive_user) {
	    global $db;
		if(!$issuepaperId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_issue_info
						SET issue_user='$issue_user', issue_note='$issue_note', receive_user='$receive_user'  
						WHERE issue_paper_id=$issuepaperId";
		return $this->Transact($this->sql);	
	}

	function setPresStatusFinish($issuepaperId,$status) {
	    global $db;
		if(!$issuepaperId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE care_pharma_prescription_info
						SET status_finish='$status'
						WHERE in_issuepaper=$issuepaperId";
		return $this->Transact($this->sql);	
	}
	
	
	//Tim thuoc tuong ung trong kho le
	function searchMedicineInAvaiProduct($encoder, $typeput){
	    global $db;
		$this->sql="SELECT sum(available_number) AS total 
					FROM care_pharma_available_product 
					WHERE product_encoder='$encoder' AND typeput='$typeput'
					GROUP BY product_encoder";
            if($this->result=$db->Execute($this->sql)) {
                    if($this->result->RecordCount()){
                        $buf = $this->result->FetchRow();
						return $buf['total'];
                    } else { return 0; } 
            } else { return 0; } 
	}
	
        //**********************************************************************
        //**********************************************************************
        //Hoa Chat
        //**********************************************************************
        //**********************************************************************
        function listAllIssuePaperChemical($condition){
	    global $db;
            $this->sql="SELECT * 
                        FROM $this->tb_chemical_issue_info ".$condition;
//            echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function getDeptWardChemical($issue_id){
	    global $db;
		$this->sql="SELECT dept_nr, ward_nr FROM $this->tb_chemical_issue_info WHERE issue_paper_id='".$issue_id."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
        
        function getAllChemicalInIssuePaper($nr){
	    global $db;
		$this->sql="SELECT * 
                            FROM $this->tb_chemical_issue 
                            WHERE issue_paper_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function getChemicalIssuePaperInfo($nr){
	    global $db;
            $this->sql="SELECT * 
                        FROM $this->tb_chemical_issue_info 
                        WHERE issue_paper_id='".$nr."'";
//            echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
        
        function setReceiveChemicalInIssue($chemical_nr,$receive_number) {
	    global $db;
            if(!$chemical_nr) return FALSE;

            $this->sql="UPDATE $this->tb_chemical_issue
                        SET number_receive='".$receive_number."' 
                        WHERE nr='".$chemical_nr."' ";
//            echo $this->sql;
            return $this->Transact($this->sql);	
	}
        
        function getIssuePaperChemicalType($nr){
	    global $db;
		$this->sql="SELECT type, typeput FROM $this->tb_chemical_issue_info WHERE issue_paper_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
                if ($this->result->RecordCount()) {
                    return $this->result->FetchRow();
                    }else{return false;}
            }else{return false;}
	}
        
        function getSumPresChemical($issue_id, $encoder){
	    global $db;
		$this->sql="SELECT sumpres 
                            FROM $this->tb_chemical_issue
                            WHERE issue_paper_id='".$issue_id."' AND product_encoder='".$encoder."' ";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
        
        function setInfoPersonWhenIssueChemical($issuepaperId,$issue_user,$issue_note,$receive_user) {
	    global $db;
		if(!$issuepaperId) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_issue_info
                            SET issue_user='$issue_user', issue_note='$issue_note', receive_user='$receive_user'  
                            WHERE issue_paper_id=$issuepaperId";
		return $this->Transact($this->sql);	
	}
        
        function setIssueChemicalStatusFinish($issuepaperId,$status) {
	    global $db;
            if(!$issuepaperId) return FALSE;
            //prescriprion_info
            $this->sql="UPDATE $this->tb_chemical_issue_info
                        SET status_finish='$status'
                        WHERE issue_paper_id=$issuepaperId";
            return $this->Transact($this->sql);	
	}
        
        function getDetailChemicalIssuePaperInfo($nr){
	    global $db;
		$this->sql="SELECT issinfo.*,iss.*,issinfo.note AS generalnote  
                            FROM $this->tb_chemical_issue_info AS issinfo, $this->tb_chemical_issue AS iss  
                            WHERE issinfo.issue_paper_id='".$nr."' AND iss.issue_paper_id=issinfo.issue_paper_id";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}

	}
        
        function getLastIDIssueChemical(){
            global $db;
            $this->sql="SELECT MAX(issue_paper_id) AS issue_paper_id FROM $this->tb_chemical_issue_info";
            if ($this->result=$db->Execute($this->sql)) {
                if ($this->result->RecordCount()) {
                    return $this->result->FetchRow();
                    }else{return false;}
            }else{return false;}		
	}
        
        function useIssuePaperChemical($type){
		if($type=='issuepaper_info'){
			$this->setTable($this->tb_chemical_issue_info);
			$this->setRefArray($this->tabpharfields);
		}elseif($type=='issuepaper'){
			$this->setTable($this->tb_chemical_issue);
			$this->setRefArray($this->tabpharfields_sub);
		}else{return false;}
	}
        
        function deleteAllChemicalInIssue($issuepaperId) {
	    global $db;
		if(!$issuepaperId) return FALSE;

		$this->sql="DELETE FROM $this->tb_chemical_issue
                            WHERE issue_paper_id=$issuepaperId";
		return $this->Transact($this->sql);	
	}
        
        function deleteIssueChemical($issuepaperId) {
	    global $db;
            if(!$issuepaperId) return FALSE;

            $this->sql="DELETE FROM $this->tb_chemical_issue_info
                        WHERE issue_paper_id=$issuepaperId";
            return $this->Transact($this->sql);	
        }
        
        function countIssuePaperChemicalItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_chemical_issue_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
        
        function listSomeIssuePaperChemicalSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
            $current_page=intval($current_page);
            $number_items_per_page=intval($number_items_per_page);

            $start_from =($current_page-1)*$number_items_per_page; 

            $this->sql="SELECT * 
                        FROM $this->tb_chemical_issue_info ".$condition."  
                        LIMIT $start_from,$number_items_per_page";

            if ($this->result=$db->Execute($this->sql)) {
                if ($this->result->RecordCount()) {
                    return $this->result;
                    }else{return false;}
            }else{return false;}
	}
        
        function getEncoderChemical($nr){
	    global $db;
		$this->sql="SELECT product_encoder FROM $this->tb_chemical_issue WHERE nr='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	//Tim thuoc tuong ung trong kho le
	function searchChemicalInAvaiProduct($encoder, $typeput){
	    global $db;
		$this->sql="SELECT sum(available_number) AS total 
					FROM care_chemical_available_product 
					WHERE product_encoder='$encoder' AND typeput='$typeput'
					GROUP BY product_encoder";
            if($this->result=$db->Execute($this->sql)) {
                    if($this->result->RecordCount()){
                        $buf = $this->result->FetchRow();
						return $buf['total'];
                    } else { return 0; } 
            } else { return 0; } 
	}	
	function searchChemicalInMainSub($encoder, $typeput){
	    global $db;
		$this->sql="SELECT sum(number) AS total 
					FROM care_chemical_products_main_sub  
					WHERE product_encoder='$encoder' AND typeput='$typeput'
					GROUP BY product_encoder";
            if($this->result=$db->Execute($this->sql)) {
                    if($this->result->RecordCount()){
                        $buf = $this->result->FetchRow();
						return $buf['total'];
                    } else { return 0; } 
            } else { return 0; } 
	}	
}
?>
