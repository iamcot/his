<?php
class Property extends Core {
	/**
	* Table name for property data
	* @var string
	*/
    var $tb_property='care_property';
	/**
	* Table name for property data
	* @var string
	*/
    var $tb_property_use='care_property_use';
    var $tb_property_repair='dfck_property_repair';
	var $tb_property_return = 'care_property_return';
	var $tb_property_liquidation = 'care_property_liquidation';
	/**
	* Table name for property data
	* @var string
	*/
    var $tb_property_operation='care_property_operation';
	/**
	* Ward number buffer
	* @var int
	*/
	var $ward_nr;
	/**
	* Department number buffer
	* @var int
	*/
	var $dept_nr;
	var $fld_property_repair = array('nr','damaged_date','request_person','damaged_detail','repair_date','repair_detail','repair_person','prop_nr');
	var $fld_property = array('nr','model','name_formal','name_short','LD_var','description','propfunction','factorer','vender','warranty','manual','note','image','status','history','modify_id','modify_time','create_id','create_time','importdate','useddate', 'productiondate', 'importstatus', 'current_dept','price','source','usepercent','dept_mana','serie','unit','power','country','volta','proptype');
	var $fld_property_use = array('nr','dept','ward','room','manager','im_date','im_status','use_date','function','current_status','pre_use','prop_nr','is_active','history','modify_id','modify_time','create_id','create_time','reason');
	var $fld_property_operation = array('nr','use_nr','operation','reason','manager','time','operator','result','before_status','after_status','is_active','status','history','modify_id','modify_time','create_id','create_time');
	var $fld_property_return = array('nr', 'prop_nr', 'manager', 'reason', 'return_date', 'history','modify_id','modify_time','create_id','create_time');
	var $fld_property_liquidation = array('nr', 'prop_nr', 'manager', 'decision_nr', 'reason', 'buyer', 'price', 'liquidation_date', 'history','modify_id','modify_time','create_id','create_time');
	
	function countResultRows($query){
		global $db;
		$this->sql = $query;
		if($this->res['capo']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['capo']->RecordCount()) {
				$row = $this->res['capo']->FetchRow();
				return $row[0] ;	 
			} else { return false; }
		} else { return false; }
	}
	
	function getPropSourceTypeList($nr){
		global $db;
		$this->sql="SELECT nr, type, source FROM care_type_property_source ";
		if($nr != NULL) $this->sql .= " where nr=$nr";
		if($this->res['gpstl']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['gpstl']->RecordCount()) {
				 return $this->res['gpstl'];	 
			} else { return false; }
		} else { return false; }
	}
	
	function  getPropertyItemsObject($query, $start, $rowperpage){
		global $db;
		$this->sql = $query;
		if(isset($start) && isset($rowperpage)) $this->sql.=" limit $start, $rowperpage ";
		if($this->res['gpio']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['gpio']->RecordCount()) {
				 return $this->res['gpio'];	 
			} else { return false; }
		} else { return false; }
	}
	
	function addNewProperty($property){
            
            
		if(isset($property)&&!empty($property)){
			$this->coretable=$this->tb_property;
			$this->ref_array=$this->fld_property;
			$this->data_array=$property;
			$this->data_array['create_id'] = $_SESSION['sess_user_name'];
                        //var_dump($this->data_array);
			if($this->checkPropertyExist($this->data_array['serie'])) return false;
			return $this->insertDataFromInternalArray();
		} else return false;
             
	}
	
	function addNewPropertyTranSmitting($transmittingdata){
		if(isset($transmittingdata)&&!empty($transmittingdata)){
			$current_dept = $transmittingdata['dept'];
			$prop_nr = $transmittingdata['prop_nr'];
			$this->coretable=$this->tb_property_use;
			$this->ref_array=$this->fld_property_use;
			$this->data_array=$transmittingdata;
			if($this->insertDataFromInternalArray()) {
				$this->coretable=$this->tb_property;
				$this->ref_array=$this->fld_property;
				$propdata = array("current_dept"=>$current_dept,"status"=>1);
				//$propdata = array("status"=>1); //dang dung
				$this->data_array=$propdata;
				return $this->updateDataFromInternalArray($prop_nr);
			}
			else return false;
		} else return false;
	}
	
	function getInfomationOfProp($items, $prop_nr){
		global $db;
		$tmp = "";
		$this->sql="SELECT  ";
		if(isset($items)&&!empty($items)){
			while (list($key, $val) = each($items)) {
				$tmp .= $val . ", ";
			}
		}
	    $this->sql.=substr($tmp,0,-2);
		$this->sql.=" ,(select name_formal from care_department where id= p.dept_mana) dept_mana_name FROM $this->tb_property p WHERE nr=$prop_nr";
                //echo $this->sql;
		if($this->res['giop']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['giop']->RecordCount()) {                    
				 return $this->res['giop']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
        function getInfomationOfPropFromModel($items, $series){
		global $db;
		$tmp = "";
		$this->sql="SELECT  ";
		if(isset($items)&&!empty($items)){
			while (list($key, $val) = each($items)) {
				$tmp .= $val . ", ";
			}
		}
	    $this->sql.=substr($tmp,0,-2);
		$this->sql.=" ,(select name_formal from care_department where id= p.dept_mana) dept_mana_name FROM $this->tb_property p WHERE model='$series'";
                //echo $this->sql;
		if($this->res['giop']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['giop']->RecordCount()) {                    
				 return $this->res['giop']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
	
	function updatePropertyInfo($prop_nr, $oldshortname, $propdata){
		global $db;
		if(!$prop_nr) return false;
		if(!$propdata) return false;
		$this->coretable=$this->tb_property;
		$this->ref_array=$this->fld_property;
		$this->data_array=$propdata;
		if($this->data_array['name_short'] != $oldshortname) {
			if($this->checkPropertyExist($this->data_array['serie'])) return false;
		}
		return $this->updateDataFromInternalArray($prop_nr);
	}
	
	function updatePropertyUseInfo($using_nr, $propdata){
		global $db;
		if(!$using_nr) return false;
		if(!$propdata) return false;
		$current_dept = $propdata['dept'];
		$prop_nr = $propdata['prop_nr'];
		$this->coretable=$this->tb_property_use;
		$this->ref_array=$this->fld_property_use;
		$this->data_array=$propdata;
		if($this->updateDataFromInternalArray($using_nr)){
			$this->coretable=$this->tb_property;
			$this->ref_array=$this->fld_property;
			$propdata = array("current_dept"=>$current_dept);
			$this->data_array=$propdata;
			return $this->updateDataFromInternalArray($prop_nr);
		} else return false;
	}
	
	function getLastestTransmitting($prop_nr){
		global $db;
		$this->sql="SELECT nr, dept, ward, room, manager, function, current_status, im_date ";
		$this->sql.="FROM $this->tb_property_use ";
		$this->sql.="WHERE (prop_nr=$prop_nr) ";
		$this->sql.="AND (im_date=(SELECT MAX(im_date) FROM $this->tb_property_use WHERE prop_nr=$prop_nr  ";
		$this->sql.="AND nr=(SELECT MAX(nr) FROM $this->tb_property_use WHERE prop_nr=$prop_nr)))";
		if($this->res['glt']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['glt']->RecordCount()) {
				 return $this->res['glt']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
	
	function getTransmittingInfo($using_nr){
		global $db;
		$this->sql="SELECT nr, dept, ward, room, manager, function, im_status, current_status, im_date, use_date, reason ";
		$this->sql.="FROM $this->tb_property_use ";
		$this->sql.="WHERE (nr=$using_nr) ";
		if($this->res['glt']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['glt']->RecordCount()) {
				 return $this->res['glt']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
	
	function addNewOperation($op_data){
		if(isset($op_data)&&!empty($op_data)){
			$this->coretable=$this->tb_property_operation;
			$this->ref_array=$this->fld_property_operation;
			$this->data_array=$op_data;
			return $this->insertDataFromInternalArray();
		} else return false;
	}
	
	function updateOperation($op_nr, $op_data){
		global $db;
		if(!$op_nr) return false;
		if(!$op_data) return false;
		$this->coretable=$this->tb_property_operation;
		$this->ref_array=$this->fld_property_operation;
		$this->data_array=$op_data;
		return $this->updateDataFromInternalArray($op_nr);
	}
	
	function getOperationInfo($op_nr){
		global $db;
		$this->sql="SELECT nr, use_nr, operation, reason, manager, time, operator, result, before_status, after_status ";
		$this->sql.="FROM $this->tb_property_operation ";
		$this->sql.="WHERE (nr=$op_nr) ";
		if($this->res['goi']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['goi']->RecordCount()) {
				 return $this->res['goi']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
	
	function checkPropertyExist($serie){
		global $db;
		$this->sql="SELECT nr ";
		$this->sql.="FROM $this->tb_property ";
		$this->sql.="WHERE serie = '$serie' ";
		if($this->res['cpe']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['cpe']->RecordCount()) {
				 return true;	 
			} else { return false; }
		} else { return false; }
	}
	
	function getPropReturnInfo($prop_nr){
		global $db;
		$this->sql="SELECT nr, manager, reason, return_date ";
		$this->sql.="FROM $this->tb_property_return ";
		$this->sql.="WHERE (prop_nr=$prop_nr) ";
		if($this->res['gpri']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['gpri']->RecordCount()) {
				 return $this->res['gpri']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
	
	function getPropLiquiInfo($prop_nr){
		global $db;
		$this->sql="SELECT nr, manager, decision_nr, reason, buyer, price, liquidation_date ";
		$this->sql.="FROM $this->tb_property_liquidation ";
		$this->sql.="WHERE (prop_nr=$prop_nr) ";
		if($this->res['gpli']=$db->Execute($this->sql)) {
            if($this->rec_count=$this->res['gpli']->RecordCount()) {
				 return $this->res['gpli']->FetchRow();	 
			} else { return false; }
		} else { return false; }
	}
	
	function insertReturnInfo($returndata){
		if(isset($returndata)&&!empty($returndata)){
			$this->coretable=$this->tb_property_return;
			$this->ref_array=$this->fld_property_return;
			$this->data_array=$returndata;
			return $this->insertDataFromInternalArray();
		} else return false;
	}
	function insertRepairInfo($returndata){
		if(isset($returndata)&&!empty($returndata)){
			$this->coretable=$this->tb_property_repair;
			$this->ref_array=$this->fld_property_repair;
			$this->data_array=$returndata;
			return $this->insertDataFromInternalArray();
		} else return false;
	}
	function updateReturnInfo($return_nr, $returndata){
		if(!$return_nr) return false;
		if(isset($returndata)&&!empty($returndata)){
			$this->coretable=$this->tb_property_return;
			$this->ref_array=$this->fld_property_return;
			$this->data_array=$returndata;
			return $this->updateDataFromInternalArray($return_nr);
		} else return false;
	}
	
	function insertLiquiInfo($liquidata){
		if(isset($liquidata)&&!empty($liquidata)){
			$this->coretable=$this->tb_property_liquidation;
			$this->ref_array=$this->fld_property_liquidation;
			$this->data_array=$liquidata;
			return $this->insertDataFromInternalArray();
		} else return false;
	}
	
	function updateLiquiInfo($liqui_nr, $liquidata){
		if(!$liqui_nr) return false;
		if(isset($liquidata)&&!empty($liquidata)){
			$this->coretable=$this->tb_property_liquidation;
			$this->ref_array=$this->fld_property_liquidation;
			$this->data_array=$liquidata;
			return $this->updateDataFromInternalArray($liqui_nr);
		} else return false;
	}
	
	function updatePropReturnStatus($prop_nr){
		global $db;
		if(!$prop_nr) return false;
		$this->coretable=$this->tb_property;
		$this->ref_array=$this->fld_property;
		$this->data_array['status']=0;//update lai ve trang thai chua phan bo
		$this->data_array['current_dept']='NULL';
		return $this->updateDataFromInternalArray($prop_nr);
	}
	function updatePropLiquiStatus($prop_nr){
		global $db;
		if(!$prop_nr) return false;
		$this->coretable=$this->tb_property;
		$this->ref_array=$this->fld_property;
		$this->data_array['status']=2;//update ve trang thai thanh ly
		$this->data_array['current_dept']='NULL';
		return $this->updateDataFromInternalArray($prop_nr);
	}
        function getInfo_Equip(){
            global $db;
            $this->sql="SELECT tb.nr,tb.serie,tb.name_formal,tb.factorer,tb.unit,tb.productiondate,tb.model,tb.importstatus,tb.note,tb.useddate,tb.power 
                        FROM $this->tb_property AS tb
                        GROUP BY model";
//            echo $this->sql;
            if($execute=$db->Execute($this->sql)){
                if($result=$execute->RecordCount()){
                    return $execute;
                }else{
                    return FALSE;
                }                    
            }else{
                return FALSE;
            }
        }
        function Count_Equip($serie){
            global $db;
            $this->sql="SELECT count(tb.serie) as count_serie
                        FROM $this->tb_property AS tb
                        WHERE tb.serie=$serie";
//            echo $this->sql.'<br>';
            if($execute=$db->Execute($this->sql)){
                if($result=$execute->FetchRow()){
                    return $result;
                }else{
                    return FALSE;
                }                    
            }else{
                return FALSE;
            }
        }
        function Count_EquipUse($nr){
            global $db;
            $this->sql="SELECT count(tb.prop_nr) as count_use
                        FROM $this->tb_property_use AS tb
                        WHERE tb.prop_nr=$nr";
//            echo $this->sql.'<br>';
            if($execute=$db->Execute($this->sql)){
                if($result=$execute->FetchRow()){
                    return $result;
                }else{
                    return FALSE;
                }                    
            }else{
                return FALSE;
            }
        }
}
?>