<?php
/**
* @package care_api
*/
/** */
require_once($root_path.'include/care_api_classes/class_core.php');
/**
*  Obstetrics methods. 
*  Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class Obstetrics extends Core {
	/**
	* Database table for the neonatal data.
	* @var string
	*/
	var $tb_neonatal='care_encounter_neonatal'; 
	/**
	* Database table for the person registration data.
	* @var string
	*/
	var $tb_person='care_person';
	/**
	* Database table for the encounter/admission data.
	* @var string
	*/
	var $tb_enc='care_encounter';
	/**
	* Database table for the disease categories
	* @var string
	*/
	var $tb_diseases='care_category_disease';
	/**
	* Database table for the outcome types
	* @var string
	*/
	var $tb_outcomes='care_type_outcome';
	/**
	* Database table for the delivery modes
	* @var string
	*/
	var $tb_dmodes='care_mode_delivery';
	/**
	* Database table for feeding types
	* @var string
	*/
	var $tb_feeds='care_type_feeding';
	/**
	* Database table for the pregnancy data
	* @var string
	*/
	var $tb_preg='care_encounter_pregnancy';
	/**
	* Database table for induction methods
	* @var string
	*/
	var $tb_indmethod='care_method_induction';
	/**
	* Database table for perineum types
	* @var string
	*/
	var $tb_perineum='care_type_perineum';
	/**
	* Database table for neonatal classifications
	* @var string
	*/
	var $tb_classif='care_classif_neonatal';
	/**
	* Database table for anesthesia types
	* @var string
	*/
	var $tb_anest='care_type_anaesthesia';
        /// edit 28/11-Huynh //////////
        var $tb_request_op='care_test_request_or';
        
        var $tb_history='care_encounter_history_obstetrics';
        
        var $tb_obstetrics='care_encounter_obstetrics';
        
        var $tb_placenta='care_encounter_placenta';
        
        var $tb_history_phu='care_encounter_obstetrics_question';
        
        var $result=array();
        ///////////////////////////////
	/**
	* Birth details buffer
	* @var adodb record object
	*/
	var $bd; # birth details holder
	//var $dead_stat="'deleted','hidden','inactive','void'";
	/**
	* Field names of care_neonatal table
	* @var array
	*/
	var $fld_neonatal=array(
									'nr',
									'pid',
									'parent_encounter_nr',
									'delivery_nr',
									'encounter_nr',
									'delivery_place',
									'delivery_mode',
									'delivery_date',
									'c_s_reason',
									'born_before_arrival',
									'face_presentation',
									'posterio_occipital_position',
									'delivery_rank',
									'apgar_1_min',
									'apgar_5_min',
									'apgar_10_min',
									'time_to_spont_resp',
									'condition',
									'weight',
									'length',
									'head_circumference',
									'scored_gestational_age',
									'feeding',
									'congenital_abnormality',
									'classification',
									'disease_category',
									'outcome',
									'status',
									'history',
									'modify_id',
									'modify_time',
									'create_id',
									'create_time');
	/**
	* Field names of care_encounter_pregnancy table
	* @var array
	*/
	var $fld_preg=array(
									'nr',
									'encounter_nr',
									'this_pregnancy_nr',
									'delivery_date',
									'delivery_time',
									'gravida',
									'para',
									'pregnancy_gestational_age',
									'nr_of_fetuses',
									'child_encounter_nr',
									'is_booked',
									'vdrl',
									'rh',
									'delivery_mode',
									'delivery_by',
									'bp_systolic_high',
									'bp_diastolic_high',
									'proteinuria',
									'labour_duration',
									'induction_method',
									'induction_indication',
									'is_epidural',
									'anaesth_type_nr',
									'complications',
									'perineum',
									'blood_loss',
									'blood_loss_unit',
									'is_retained_placenta',
									'post_labour_condition',
									'outcome',
									'status',
									'history',
									'modify_id',
									'modify_time',
									'create_id',
									'create_time');
	/**
	* Constructor
	* @param int Encounter number
	*/
	function Obstetrics($nr=0){
		if($nr) $this->enc_nr=$nr;
		$this->coretable=$this->tb_neonatal;
		$this->ref_array=$this->fld_neonatal;
	}
	/** 
	* Gets all birth data based on the PID number.
	*
	* The data returned have index keys as outlined at the <var>$fld_neonatal</var> array.
	* @access public
	* @param int PID number
	* @return mixed adodb record object or boolean
	*/
	function BirthDetails($pid){
		global $db;
		$this->sql="SELECT n.* FROM   $this->tb_neonatal AS n, $this->tb_person AS p
			WHERE p.pid=".$pid." AND p.pid=n.pid AND n.status NOT IN ($this->dead_stat)
			ORDER BY n.modify_time DESC";
        if($this->bd=$db->Execute($this->sql)) {
            if($this->rec_count=$this->bd->RecordCount()) {
				 return $this->bd;	 
			} else { return false; }
		} else { return false; }
	}
/*	function DeliveryDate(){
		return $bd['delivery_date'];
	}
	function PregnancyNr(){
		return $bd['pregnancy_nr'];
	}
	function DeliveryPlace(){
		return $bd['delivery_place'];
	}
	function DeliveryMode(){
		return $bd['delivery_mode'];
	}
	function CSReason(){
		return $bd['c_s_reason'];
	}
	function BornBeforeArrival(){
		return $bd['born_before_arrival'];
	}
	function FacePresentation(){
		return $bd['face_presentation'];
	}
	function PostOcciPos(){
		return $bd['posterio_occipital_position'];
	}
	function DeliveryRank(){
		return $bd['delivery_rank'];
	}
	function Apgar1(){
		return $bd['apgar_1_min'];
	}
	function Apgar5(){
		return $bd['apgar_5_min'];
	}
	function Apgar10(){
		return $bd['apgar_10_min'];
	}
	function Condition(){
		return $bd['condition'];
	}
	function Weight(){
		return $bd['weight'];
	}
	function Length(){
		return $bd['length'];
	}
	function HeadCircumference(){
		return $bd['head_circumference'];
	}
	function ScoredGestAge(){
		return $bd['scored_gestational_age'];
	}
	function Feeding(){
		return $bd['feeding'];
	}
	function CongenAbnormality(){
		return $bd['congenital_abnormality'];
	}
	function Classification(){
		return $bd['classification'];
	}
	function DiseaseCategory(){
		return $bd['disease_category'];
	}
	function Outcome(){
		return $bd['outcome'];
	}
*/
	/**
	* Gets all disease categories.
	* @access public
	* @return mixed adodb record object or boolean
	*/ 
	function DiseaseCategories(){
		global $db;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_diseases WHERE group_nr=2 AND status NOT IN ($this->dead_stat) ORDER BY nr"; # group_nr = 2 is Neonatal
        if($this->res['_dcat']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_dcat']->RecordCount()) {
				 return $this->res['_dcat'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets all feeding types.
	* @access public
	* @return mixed adodb record object or boolean
	*/ 
	function FeedingTypes(){
		global $db;
		$this->sql="SELECT *, LD_var AS \"LD_var\" FROM   $this->tb_feeds WHERE group_nr=2 AND status NOT IN ($this->dead_stat) ORDER BY nr"; # group_nr = 2 is Neonatal
        if($this->res['_feed']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_feed']->RecordCount()) {
				return $this->res['_feed'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets all outcome types.
	* @access public
	* @return mixed adodb record object or boolean
	*/ 
	function Outcomes(){
		global $db;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_outcomes WHERE group_nr=2 AND status NOT IN ($this->dead_stat) ORDER BY nr"; # group_nr = 2 is Neonatal
        if($this->res['_outs']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_outs']->RecordCount()) {
				return $this->res['_outs'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets all delivery modes.
	* @access public
	* @return mixed adodb record object or boolean
	*/ 
	function DeliveryModes(){
		global $db;
		$this->sql="SELECT *, LD_var AS \"LD_var\" FROM   $this->tb_dmodes WHERE group_nr=2 AND status NOT IN ($this->dead_stat) ORDER BY nr"; # group_nr = 2 is Neonatal
        if($this->res['_dmodes']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_dmodes']->RecordCount()) {
				return $this->res['_dmodes'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Sets the status of a neonatal birth details record to "inactive".
	* @access public
	* @param int PID number
	* @return mixed adodb record object or boolean
	*/ 
	function deactivateBirthDetails($pid){
		global $_SESSION;
		$this->sql="UPDATE  $this->tb_neonatal SET status='inactive', history=".$this->ConcatHistory("Deactivated ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n")." WHERE pid=$pid"; 
		return $this->Transact();
	}
	/**
	* Gets  complete disease category information.
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getDiseaseCategory($nr){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT *, LD_var AS \"LD_var\" FROM   $this->tb_diseases WHERE group_nr=2 AND nr=$nr AND status NOT IN ($this->dead_stat)"; # group_nr = 2 is Neonatal
        if($this->res['_gdcat']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_gdcat']->RecordCount()) {
				 return $this->res['_gdcat']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets complete feeding type information. 
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getFeedingType($nr){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_feeds WHERE group_nr=2 AND nr=$nr AND status NOT IN ($this->dead_stat)"; # group_nr = 2 is Neonatal
        if($this->res['_gfeed']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_gfeed']->RecordCount()) {
				return $this->res['_gfeed']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets complete outcome type information. 
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getOutcome($nr){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_outcomes WHERE group_nr=2 AND nr=$nr AND status NOT IN ($this->dead_stat)"; # group_nr = 2 is Neonatal
        if($this->res['_gout']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_gout']->RecordCount()) {
				return $this->res['_gout']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets complete delivery mode information. 
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getDeliveryMode($nr){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_dmodes WHERE group_nr=2 AND nr=$nr AND status NOT IN ($this->dead_stat)"; # group_nr = 2 is Neonatal
        if($this->res['_gdmode']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_gdmode']->RecordCount()) {
				return $this->res['_gdmode']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets  pregnancy information based on either the pid number or encounter number.
	*
	* The data returned have index keys as outlined at the <var>$fld_prenancy</var> array.
	* @access public
	* @param int Primary record key number
	* @param string Flags the type of the first parameter. '_ENC' = encounter number, '_REG' = pid number.
	* @return mixed adodb record object or boolean
	*/ 
	function Pregnancies($nr,$nr_type='_ENC'){
            global $db;
            if($nr_type=='_ENC'){
                    $this->sql="SELECT n.* FROM   $this->tb_preg AS n
                            WHERE n.encounter_nr=".$nr." AND n.status NOT IN ($this->dead_stat)
                            ORDER BY n.modify_time DESC";
            }elseif($nr_type='_REG'){
                    $this->sql="SELECT n.* FROM $this->tb_person AS p, $this->tb_preg AS n, $this->tb_enc AS e
                            WHERE p.pid=".$nr." AND e.pid=p.pid AND e.encounter_nr=n.encounter_nr AND n.status NOT IN ($this->dead_stat)
                            ORDER BY n.modify_time DESC";
            }
//            echo $this->sql;
            if($this->res['_preg']=$db->Execute($this->sql)) {
                if($this->rec_count=$this->res['_preg']->RecordCount()) {
                                        return $this->res['_preg'];	 
                            } else { return false; }
                    } else { return false; }
	}
	/**
	* Sets the core object to  point to care_encounter_pregnancy table
	* @access public
	*/
	function usePregnancy(){
		$this->coretable=$this->tb_preg;
		$this->ref_array=$this->fld_preg;
	}
	/**
	* Sets the status of a pregnancy record to "inactive" based on the record's primary key number.
	* @access public
	* @param int Primary record key number
	* @return mixed adodb record object or boolean
	*/ 
	function deactivatePregnancy($nr){
		global $_SESSION;
		$this->sql="UPDATE  $this->tb_preg SET status='inactive', history=".$this->ConcatHistory("Deactivated ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n")."
							WHERE nr=$nr"; 
		return $this->Transact();
	}
	/**
	* Gets all induction methods.
	* @access public
	* @param int Group number
	* @return mixed adodb record object or boolean
	*/ 
	function InductionMethods($grp=1){
		global $db;
		# Group nr. 1 = pregnancy group
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_indmethod WHERE group_nr=$grp AND status NOT IN ($this->dead_stat) ORDER BY nr"; 
        if($this->res['_indm']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_indm']->RecordCount()) {
				return $this->res['_indm'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets complete induction method information.
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getInductionMethod($nr){
		global $db;
		if(!$nr) return false;
		# Group nr. 1 = pregnancy group
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_indmethod WHERE nr=$nr";
		
        if($this->res['_gin']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_gin']->RecordCount()) {
				return $this->res['_gin']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets all perineum types.
	* @access public
	* @return mixed adodb record object or boolean
	*/ 
	function Perineums(){
		global $db;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_perineum WHERE status NOT IN ($this->dead_stat) ORDER BY nr";
        if($this->res['_peri']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_peri']->RecordCount()) {
				return $this->res['_peri'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets a complete perineum information.
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getPerineum($nr=0){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_perineum WHERE nr=$nr";
		
        if($this->res['_gper']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_gper']->RecordCount()) {
				return $this->res['_gper']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets all classifications.
	* @access public
	* @return mixed adodb record object or boolean
	*/ 
	function Classifications(){
		global $db;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_classif WHERE status NOT IN ($this->dead_stat) ORDER BY nr";
        if($this->res['classif']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['classif']->RecordCount()) {
				return $this->res['classif'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets a complete neonatal classification information.
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getClassification($nr=0){
		global $db;
		$this->rec_count=0;
		if(!$nr) return false;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_classif WHERE nr=$nr";
        if($this->res['gclasif']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['gclasif']->RecordCount()) {
				return $this->res['gclasif']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets all anaesthesia  types.
	* @access public
	* @return mixed array or boolean
	*/ 
	function AnaesthesiaTypes(){
		global $db;
		$this->rec_count=0;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_anest WHERE status NOT IN ($this->dead_stat) ORDER BY nr";
        if($this->res['anat']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['anat']->RecordCount()) {
				return $this->res['anat'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Gets a complete anaesthesia information.
	* @access public
	* @param int Primary record key number
	* @return mixed array or boolean
	*/ 
	function getAnaesthesia($nr=0){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT *,LD_var AS \"LD_var\" FROM   $this->tb_anest WHERE nr=$nr";
		
        if($this->res['gana']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['gana']->RecordCount()) {
				return $this->res['gana']->FetchRow();
			} else { return false; }
		} else { return false; }
	}
	/**
	* Checks if the encounter has a pregnancy record. This is limited to the encounter only.
	* @access public
	* @param int Encounter number
	* @return mixed array or boolean
	*/
	function EncounterPregnancyExists($nr=0){
		global $db;
		if(!$nr) return false;
		$this->sql="SELECT nr FROM   $this->tb_preg WHERE encounter_nr=$nr AND status NOT IN ($this->dead_stat)"; 
		
        if($buf=$db->Execute($this->sql)) {
            if($this->rec_count=$buf->RecordCount()) {
				$buf2=$buf->FetchRow();
				return $buf2['nr'];
			} else { return false; }
		} else { return false; }
	}
	/**
	* Checks if the encounter number of a child is recorded at the parent's encounter record. 
	*
	* return:
	* - _CHILD_EXISTS = child encounter nr is recorded at parent
	* - _NO_CHILD = child encounter nr is not recorded at parent
	* - _NO_EXISTS = parent pregnancy record not exists
	* @access public
	* @param int = Encounter number of the child
	* @param int = Encounter number of the parent
	* @return string
	*/
	function ChildNrAtParent($child_nr=0,$parent_nr=0){
		global $db, $sql_LIKE;
		if(!$child_nr||!$parent_nr) return false;
		if($this->EncounterPregnancyExists($parent_nr)){
			$this->sql="SELECT encounter_nr FROM   $this->tb_preg WHERE encounter_nr=$parent_nr AND child_encounter_nr $sql_LIKE '%$child_nr%' AND status NOT IN ($this->dead_stat)";
		
			if($this->res['_cnbp']=$db->Execute($this->sql)) {
           		if($this->rec_count=$this->res['_cnbp']->RecordCount()) {
					return  '_CHILD_EXISTS';
				}else{return  '_NO_CHILD';}
			}else{ return  '_NO_EXISTS';}
		}else{
			return '_NO_EXISTS';
		}
	}
	/**
	* Adds the encounter number of a child to the parents encounter record. 
	*
	* The birth details are passed by reference with an associative array.
	* The data must be prepared having the following index keys:
	* - delivery_date = date of delivery (birth)
	* - delivery_nr = the number or this birth e.g. 3rd, 4th, etc.
	* - delivery_mode = the mode of delivery
	* - outcome = the outcome of the delivery
	* @access public
	* @param int = Encounter number of the child
	* @param int = Encounter number of the parent
	* @return boolean
	*/
	function AddChildNrToParent($child_nr=0,$parent_nr=0,&$birth){
		global $_SESSION;
		if(!$child_nr||!$parent_nr) return false;
		switch($this->ChildNrAtParent($child_nr,$parent_nr))
		{
			case '_NO_CHILD': # Update
				$this->sql="UPDATE $this->tb_preg 
								SET child_encounter_nr=".$this->ConcatFieldString('child_encounter_nr', '".$child_nr."').",
										history=".$this->ConcatHistory("Updated by child  ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n")."
								WHERE encounter_nr=$parent_nr AND status NOT IN ($this->dead_stat)";
				return $this->Transact();
				break; 
        	# If record not exists create it
			case '_NO_EXISTS':
				$this->sql="INSERT INTO $this->tb_preg 
									(delivery_date,
									para,
									encounter_nr,
									child_encounter_nr,
									delivery_mode,
									outcome,
									history,
									create_id,
									create_time
									) 
									VALUES 
									('".$birth['delivery_date']."',
									'".$birth['delivery_nr']."',
									'$parent_nr',
									'$child_nr',
									'".$birth['delivery_mode']."',
									'".$birth['outcome']."',
									'Created by child ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n',
									'".$_SESSION['sess_user_name']."',
									'".date('YmdHis')."')";
				return $this->Transact();
				break;
			default: return false;
		}
	}
        // add 28/11-Hu?nh //////////
        function ProfileSurgery($batch_nr=''){
            global $db;
            if(!$batch_nr) return false;
		$this->sql="SELECT person_surgery FROM $this->tb_request_op WHERE batch_nr=$batch_nr";
            if($this->result=$db->Execute($this->sql)) {
                if($this->rec_count=$this->result->RecordCount()) {
                    return $this->result->FetchRow();
                } else { return false; }
            } else { return false; }
        }
		
    function BirthDetails1($pid,$nr){
        global $db;
        $this->sql="SELECT n.* FROM   $this->tb_neonatal AS n, $this->tb_person AS p
                WHERE p.pid=".$pid." AND p.pid=n.pid AND n.status NOT IN ($this->dead_stat) AND n.nr=$nr
                ORDER BY n.modify_time DESC";
//        echo $this->sql;
        if($this->bd=$db->Execute($this->sql)) {
        if($this->rec_count=$this->bd->RecordCount()) {
                            return $this->bd;	 
                } else { return false; }
        } else { return false; }
    }
    
    function deleteBirthDetails1($pid, $enc_nr, $nr){
        global $_SESSION;
        $this->sql="DELETE FROM  $this->tb_neonatal WHERE pid=$pid AND nr=$nr AND parent_encounter_nr=$enc_nr"; 
//        echo $this->sql;
        return $this->Transact();
    }
    
    //HUYNH
    function history($nr,$nr_type='_ENC'){
        global $db;
        if($nr_type=='_ENC'){
                $this->sql="SELECT n.* FROM   $this->tb_history AS n
                        WHERE n.encounter_nr=".$nr." AND n.status NOT IN ($this->dead_stat)
                        ORDER BY n.modify_time DESC";
        }elseif($nr_type='_REG'){
                $this->sql="SELECT n.* FROM $this->tb_person AS p, $this->tb_history AS n, $this->tb_enc AS e
                        WHERE p.pid=".$nr." AND e.pid=p.pid AND e.encounter_nr=n.encounter_nr AND n.status NOT IN ($this->dead_stat)
                        ORDER BY n.modify_time DESC";
        }
//        echo $this->sql;
        if($this->res['_preg']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_preg']->RecordCount()) {
                                    return $this->res['_preg'];	 
                        } else { return false; }
                } else { return false; }
    }
    
    function deactivateHistory($nr){
        global $_SESSION;
        $this->sql="UPDATE  $this->tb_history SET status='inactive', history=".$this->ConcatHistory("Deactivated ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n")."
                                                WHERE nr=$nr"; 
        return $this->Transact();
    }
    
    function useHistory(){
        $this->coretable=$this->tb_history;
    }
    
    function useobstetrics(){
        $this->coretable=$this->tb_obstetrics;
    }
    
    function useplacenta(){
        $this->coretable=$this->tb_placenta;
    }
    
    function Obstetrics1($pid,$nr){
        global $db;
        $this->sql="SELECT n.* FROM $this->tb_obstetrics AS n, $this->tb_person AS p
                WHERE p.pid=".$pid." AND p.pid=n.pid AND n.status NOT IN ($this->dead_stat) AND n.encounter_nr=$nr
                ORDER BY n.modify_time DESC";
//        echo $this->sql;
        if($this->bd=$db->Execute($this->sql)) {
        if($this->rec_count=$this->bd->RecordCount()) {
                            return $this->bd;	 
                } else { return false; }
        } else { return false; }
    }
    
    function Obstetrics2($pid){
        global $db;
        $this->sql="SELECT n.* FROM $this->tb_obstetrics AS n, $this->tb_person AS p
                WHERE p.pid=".$pid." AND p.pid=n.pid AND n.status NOT IN ($this->dead_stat)
                ORDER BY n.modify_time DESC";
//        echo $this->sql;
        if($this->bd=$db->Execute($this->sql)) {
            if($this->rec_count=$this->bd->RecordCount()) {
                                return $this->bd;	 
                    } else { return false; }
            } else { return false; }
    }
    
    function Placenta1($pid,$nr){
        global $db;
        $this->sql="SELECT n.* FROM $this->tb_placenta AS n, $this->tb_person AS p
                WHERE p.pid=".$pid." AND p.pid=n.pid AND n.status NOT IN ($this->dead_stat) AND n.encounter_nr=$nr
                ORDER BY n.modify_time DESC";
        if($this->bd=$db->Execute($this->sql)) {
        if($this->rec_count=$this->bd->RecordCount()) {
                            return $this->bd;	 
                } else { return false; }
        } else { return false; }
    }
    
    function Placenta2($pid){
        global $db;
        $this->sql="SELECT n.* FROM $this->tb_placenta AS n, $this->tb_person AS p
                WHERE p.pid=".$pid." AND p.pid=n.pid AND n.status NOT IN ($this->dead_stat)
                ORDER BY n.modify_time DESC";
        if($this->bd=$db->Execute($this->sql)) {
            if($this->rec_count=$this->bd->RecordCount()) {
                                return $this->bd;	 
                    } else { return false; }
            } else { return false; }
    }
    
    function useHistory_Phu(){
        $this->coretable=$this->tb_history_phu;
    }
    
    function history_Phu($nr,$nr_type='_ENC',$status=''){
        global $db;
        if($nr_type=='_ENC'){
                $this->sql="SELECT n.* FROM   $this->tb_history_phu AS n
                        WHERE n.encounter_nr=".$nr." AND n.status NOT IN ($this->dead_stat)
                        ORDER BY n.modify_time DESC";
        }elseif($nr_type='_REG'){
                $this->sql="SELECT n.* FROM $this->tb_person AS p, $this->tb_history_phu AS n, $this->tb_enc AS e
                        WHERE p.pid=".$nr." AND e.pid=p.pid AND e.encounter_nr=n.encounter_nr AND n.status NOT IN ($this->dead_stat)
                        ORDER BY n.modify_time DESC";
        }
//        echo $this->sql;
        if($this->res['_preg']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['_preg']->RecordCount()) {
                    return $this->res['_preg'];
            } else { return false; }
        } else { return false; }
    }
    
    function Report($month, $year){
        global $db;
        $cond="AND MONTH(e.encounter_date)=$month AND YEAR(e.encounter_date)=$year AND e.current_dept_nr=7";
        $array='1,3,5,7,8,10';
        if(strpos($array,$month)!==false || $month=='12'){
            $date=$year.'-'.$month.'-31';
        }else if($month=='2'){
            $date=$year.'-'.$month.'-28';
        }else{
            $date=$year.'-'.$month.'-30';
        }
        $this->sql="SELECT
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.encounter_class_nr=1 $cond) NOITRU,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.encounter_class_nr=2 $cond) NGOAITRU,
                    (SELECT SUM(DATEDIFF(e.discharge_date,DATE(e.encounter_date))) FROM care_encounter AS e WHERE e.encounter_class_nr=1 AND e.is_discharged<>0 $cond) SONGAYDT_XV, 
                    (SELECT SUM(DATEDIFF('$date',DATE(e.encounter_date))) FROM care_encounter AS e WHERE e.encounter_class_nr=1 AND e.is_discharged=0 $cond) SONGAYDT_CXV,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.insurance_nr <> '' $cond) BHYT,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.insurance_nr LIKE '%GD%' $cond) BHYT_TN,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE (e.insurance_nr LIKE '%CN%' OR e.insurance_nr LIKE '%HN%') $cond) BHYT_HN,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.insurance_nr <> '' AND (e.insurance_nr NOT LIKE '%GD%' 
                                                                       AND e.insurance_nr NOT LIKE '%CN%' AND e.insurance_nr NOT LIKE '%HN%' 
                                                                       AND e.insurance_nr NOT LIKE '%TN%') $cond) BHXH,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.discharged_type=2 AND e.encounter_class_nr=1  $cond) CV,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.discharged_type=2 AND e.encounter_class_nr=2  $cond) CVTT,
                    (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.encounter_class_nr=2 $cond) BANT,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_diagnosis_code='O26' $cond) khamthai,
                    (SELECT COUNT(DISTINCT(e.referrer_diagnosis_code)) FROM care_encounter AS e,care_person AS p WHERE e.pid=p.pid 
                                                                        AND e.referrer_diagnosis_code='O26' AND p.tuoi>10 AND p.tuoi<18 $cond) khamthai_vtn,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='O80' OR e.referrer_diagnosis_code='O60' 
                                                                        OR e.referrer_diagnosis_code='O81' OR e.referrer_diagnosis_code='O82'
                                                                        OR e.referrer_diagnosis_code='O83') $cond) sode,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='O80' OR e.referrer_diagnosis_code='O60') $cond) dethuong,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_diagnosis_code='O81' $cond) dekho,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_diagnosis_code='O82' $cond) demo,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_diagnosis_code='O83' $cond) deconthu3,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='000' OR 
                                                                        LEFT(e.referrer_diagnosis_code,1)='N' AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79)) $cond) khamphukhoa,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (LEFT(e.referrer_diagnosis_code,1)='N' 
                                                                        AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79)) $cond) macphukhoa,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (LEFT(e.referrer_diagnosis_code,1)='N' 
                                                                        AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79))
                                                                        AND e.is_discharged<>0 $cond) chuaphukhoa,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Papmear' $cond) papmear,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_recom_therapy='Phá thai nội khoa'
                                                                        OR e.referrer_recom_therapy='Hút thai' OR e.referrer_recom_therapy='Nạo thai') $cond) phathai,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Nạo thai' $cond) naothai,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Hút thai' $cond) hutthai,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Phá thai nội khoa' $cond) phathainoikhoa,
                    (SELECT COUNT(DISTINCT(e.referrer_diagnosis_code)) FROM care_encounter AS e, care_person AS p WHERE e.pid=p.pid AND (e.referrer_recom_therapy='Phá thai nội khoa'
                                                                        OR e.referrer_recom_therapy='Hút thai' OR e.referrer_recom_therapy='Nạo thai')
                                                                        AND p.tuoi>10 AND p.tuoi<18 $cond) phathai_vtn,
                    (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='O04' OR e.referrer_diagnosis_code='O05') $cond) saythai,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.outcome='1') beduocsinh,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.outcome='1' AND baby.sex=2) begaiduocsinh,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.outcome='1' AND baby.weight<>'') beduoccan,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.outcome='1' AND baby.weight<2500) becanduoi2500g, 
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.feeding=1) becobusua, 
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.delivery_rank='Vitamin K1') betiemvitaminK1,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.delivery_rank='Viêm gan B') betiemviemganB,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.delivery_rank='BCG') betiemBCG,
                    (SELECT COUNT(baby.id) FROM care_encounter_neonatal AS baby, care_encounter AS e WHERE e.encounter_nr=baby.encounter_nr $cond
                                                                        AND baby.outcome<>'1') bechet,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Đặt vòng' $cond) datvong,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Tháo vòng' $cond) thaovong,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Thuốc uống' $cond) thuocuong,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Thuốc tiêm' $cond) thuoctiem,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Thuốc cấy' $cond) thuoccay,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Bao cao su' $cond) BCS,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Triệt sản' $cond) trietsan,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e, care_person AS p WHERE e.pid=p.pid $cond        
                                                                        AND e.referrer_recom_therapy='Triệt sản' AND p.sex='m') trietsannam,
                    (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e, care_test_request_dientim AS ycdt WHERE e.encounter_nr=ycdt.encounter_nr 
                                                                                                                AND ycdt.status='done' $cond) doDT,
                    (SELECT COUNT(ps.nr) FROM care_personell_assignment AS ps WHERE ps.location_nr=7 AND ps.STATUS<>'deleted') TongNS,
                    (SELECT COUNT(pn.nr) FROM care_personell_assignment AS ps, care_personell AS pn WHERE ps.location_nr=7 AND ps.status<>'deleted' 
                                                                                                    AND pn.nr=ps.personell_nr
                                                                                                    AND pn.contract_class='BC') BC,
                    (SELECT COUNT(pn.nr) FROM care_personell_assignment AS ps, care_personell AS pn WHERE ps.location_nr=7 AND ps.status<>'deleted' 
                                                                                                    AND pn.nr=ps.personell_nr
                                                                                                    AND pn.contract_class='HDNBC') HD,
                    (SELECT COUNT(ps.nr) FROM care_personell_assignment AS ps, care_personell AS pn WHERE ps.location_nr=7 AND ps.STATUS<>'deleted'
                                                                                AND pn.nr=ps.personell_nr
                                                                                AND pn.job_function_title BETWEEN(1) AND(6)) BS,
                    (SELECT COUNT(en.nr) FROM care_encounter AS e, care_encounter_notes AS en WHERE e.encounter_nr=en.encounter_nr AND en.type_nr=24 
                                                                                                AND en.notes LIKE 'Tai biến trong khi sanh') taibiensankhoa,
                    (SELECT COUNT(en.nr) FROM care_encounter AS e, care_encounter_notes AS en WHERE e.encounter_nr=en.encounter_nr AND en.type_nr=24 
                                                                                                AND en.notes LIKE 'Tai biến do phá thai') taibienphathai,
                    (SELECT COUNT(en.nr) FROM care_encounter AS e, care_encounter_notes AS en WHERE e.encounter_nr=en.encounter_nr AND en.type_nr=24 
                                                                                                AND en.notes LIKE 'Tai biến do sử dụng biện pháp tránh thai') taibientranhthai
                    FROM care_person AS p, care_encounter AS e
                    WHERE e.pid=p.pid AND e.current_dept_nr=7";
//        echo $this->sql;
        if($result=$db->Execute($this->sql)) {
            return $result;
        } else { return false; }
    }
    
    function Report_tuan($datefrom, $dateto){
        global $db;
        $cond="AND DATE(e.encounter_date) BETWEEN('$datefrom') AND('$dateto') AND e.current_dept_nr=7";
        $this->sql="SELECT
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='O80' OR e.referrer_diagnosis_code='O60' 
                                                                        OR e.referrer_diagnosis_code='O81' OR e.referrer_diagnosis_code='O82'
                                                                        OR e.referrer_diagnosis_code='O83') $cond) sode,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_diagnosis_code='O26' $cond) khamthai,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='000' OR 
                                                                        LEFT(e.referrer_diagnosis_code,1)='N' AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79)) $cond) khamphukhoa,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Đặt vòng' $cond) datvong,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Tháo vòng' $cond) thaovong,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Thuốc uống' $cond) thuocuong,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Thuốc tiêm' $cond) thuoctiem,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Thuốc cấy' $cond) thuoccay,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Bao cao su' $cond) BCS,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Triệt sản' $cond) trietsan,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='O04' OR e.referrer_diagnosis_code='O05') $cond) saythai,
                (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.discharged_type=2 AND e.encounter_class_nr=1 $cond) CV,
                (SELECT COUNT(e.insurance_nr) FROM care_encounter AS e WHERE e.discharged_type=2 AND e.encounter_class_nr=2 $cond) CVTT,
                (SELECT COUNT(e.encounter_nr) FROM care_encounter AS e, care_test_request_dientim AS ycdt WHERE e.encounter_nr=ycdt.encounter_nr 
                                                                                                            AND ycdt.status='done' $cond) doDT
                FROM care_person AS p, care_encounter AS e
                WHERE e.pid=p.pid AND e.current_dept_nr=7";
//        echo $this->sql;
        if($result=$db->Execute($this->sql)) {
            return $result;
        }else { return false; } 
    }
    
    function Report_BM07($month, $year){
        global $db;
        switch ($month){
            case 3:
                $datefrom=$year.'-01-01';
                $dateto=$year.'-03-31';
                break;
            case 6:
                $datefrom=$year.'-01-01';
                $dateto=$year.'-06-30';
                break;
            case 9:
                $datefrom=$year.'-01-01';
                $dateto=$year.'-09-30';
                break;
            default:
                $datefrom=$year.'-01-01';
                $dateto=$year.'-12-31';
                break;
        }
        $cond="AND DATE(e.encounter_date) BETWEEN('$datefrom') AND('$dateto') AND e.current_dept_nr=7";
        $this->sql="SELECT
                (SELECT COUNT(DISTINCT(e.encounter_date)) FROM care_encounter AS e, care_person AS p WHERE p.pid=e.pid
                                                                            AND (LEFT(e.referrer_diagnosis_code,1)='N' AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79) 
                                                                            OR e.referrer_diagnosis_code='000'
                                                                            OR e.referrer_recom_therapy='Nạo thai' OR e.referrer_recom_therapy='Hút thai'
                                                                            OR e.referrer_recom_therapy='Phá thai nội khoa')
                                                                            AND p.sex='f' AND p.tuoi>=15 $cond) tongphunu,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (e.referrer_diagnosis_code='000' OR 
                                                                        LEFT(e.referrer_diagnosis_code,1)='N' AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79) $cond)) khamphukhoa,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE (LEFT(e.referrer_diagnosis_code,1)='N' 
                                                                        AND RIGHT(e.referrer_diagnosis_code,2) BETWEEN(70) AND(79))
                                                                        AND e.is_discharged<>0 $cond) chuaphukhoa,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Nạo thai' $cond) naothai,
                (SELECT COUNT(e.referrer_diagnosis_code) FROM care_encounter AS e WHERE e.referrer_recom_therapy='Hút thai' $cond) hutthai,
                (SELECT COUNT(DISTINCT(e.referrer_diagnosis_code)) FROM care_encounter AS e, care_person AS p WHERE p.pid=e.pid AND (e.referrer_recom_therapy='Phá thai nội khoa'
                                                                        OR e.referrer_recom_therapy='Hút thai' OR e.referrer_recom_therapy='Nạo thai') 
                                                                        AND p.tuoi<18 $cond) trevtnphathai,
                (SELECT COUNT(en.nr) FROM care_encounter AS e, care_encounter_notes AS en WHERE e.encounter_nr=en.encounter_nr AND en.type_nr=24 
                                                                                            AND en.notes LIKE 'Tai biến do phá thai' AND e.discharged_type<>6) mactaibien,
                (SELECT COUNT(en.nr) FROM care_encounter AS e, care_encounter_notes AS en WHERE e.encounter_nr=en.encounter_nr AND en.type_nr=24 
                                                                                            AND en.notes LIKE 'Tai biến do phá thai' AND e.discharged_type=6) chettaibien
                FROM care_encounter AS e
                WHERE e.current_dept_nr=7";
//        echo $this->sql;
        if($result=$db->Execute($this->sql)) {
            return $result;
        }else { return false; }
    }
}
?>
