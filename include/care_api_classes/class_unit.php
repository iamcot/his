<?php
/**
* @package care_api
*/
/** */
require_once($root_path.'include/care_api_classes/class_core.php');

class Units extends Core {

	#Define table
	var $tb_unit_phar='care_pharma_unit_of_medicine';
	var $tb_unit_med='care_med_unit_of_medipot';	
	var $tb_unit_chemical='care_chemical_unit_of_medicine';	
	
	var $tb_type_phar='care_pharma_type_of_medicine';
	var $tb_use_phar='care_pharma_use_of_medicine';
	
	#Danh sach cac ham cua don vi -----------------------------------------------------------------------
	
	//Get Detail
	function getPharmaDetailUnit($unit){
		global $db;
		$this->sql="SELECT * FROM $this->tb_unit_phar WHERE unit_of_medicine='$unit'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getMedDetailUnit($unit){
		global $db;
		$this->sql="SELECT * FROM $this->tb_unit_med WHERE unit_of_medicine='$unit'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getChemicalDetailUnit($unit){
		global $db;
		$this->sql="SELECT * FROM $this->tb_unit_chemical WHERE unit_of_chemical='$unit'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	//List
	function listPharmaUnit(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_unit_phar ORDER BY unit_name_of_medicine";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	function listMedUnit(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_unit_med ORDER BY unit_name_of_medicine";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	function listChemicalUnit(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_unit_chemical ORDER BY unit_name_of_chemical";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}

	//Create
	function newPharmaUnit($unit, $name) {
	    global $db;
		if($unit=='' || $name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_unit_phar 
						(unit_of_medicine, 
						unit_name_of_medicine, 
						history, 
						create_id, 
						create_time
						)
					VALUES
						('$unit', 
						'$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}
	function newMedUnit($unit, $name) {
	    global $db;
		if($unit=='' || $name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_unit_med 
						(unit_of_medicine, 
						unit_name_of_medicine, 
						history, 
						create_id, 
						create_time
						)
					VALUES
						('$unit', 
						'$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}	
	function newChemicalUnit($unit, $name) {
	    global $db;
		if($unit=='' || $name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_unit_chemical 
						(unit_of_chemical, 
						unit_name_of_chemical, 
						history, 
						create_id, 
						create_time
						)
					VALUES
						('$unit', 
						'$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}	
	
	//Update
	function updatePharmaUnit($oldunit, $unit_of_medicine, $unit_name_of_medicine) {
	    global $db;
		if($oldunit=='' || $unit_of_medicine=='') return FALSE;
		$this->sql="UPDATE $this->tb_unit_phar  
					SET
					unit_of_medicine = '$unit_of_medicine' , 
					unit_name_of_medicine = '$unit_name_of_medicine' , 
					history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 		
					WHERE
					unit_of_medicine = '$oldunit' ";		
		return $this->Transact($this->sql);	
	}
	function updateMedUnit($oldunit, $unit_of_medicine, $unit_name_of_medicine) {
	    global $db;
		if($oldunit=='' || $unit_of_medicine=='') return FALSE;
		$this->sql="UPDATE $this->tb_unit_med 
					SET
					unit_of_medicine = '$unit_of_medicine' , 
					unit_name_of_medicine = '$unit_name_of_medicine' , 
					history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 		
					WHERE
					unit_of_medicine = '$oldunit' ";
		return $this->Transact($this->sql);	
	}	
	function updateChemicalUnit($oldunit, $unit_of_chemical, $unit_name_of_chemical) {
	    global $db;
		if($oldunit=='' || $unit_of_chemical=='') return FALSE;
		$this->sql="UPDATE $this->tb_unit_chemical 
					SET
					unit_of_chemical = '$unit_of_chemical' , 
					unit_name_of_chemical = '$unit_name_of_chemical' ,  
					history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")."  		
					WHERE
					unit_of_chemical = '$oldunit' ";
		return $this->Transact($this->sql);	
	}
	
	//Delete
	function deletePharmaUnit($unit) {
	    global $db;
		if($unit=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_unit_phar  		
					WHERE unit_of_medicine = '$unit' ";
		return $this->Transact($this->sql);	
	}
	function deleteMedUnit($unit) {
	    global $db;
		if($unit=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_unit_med 		
					WHERE unit_of_medicine = '$unit' ";
		return $this->Transact($this->sql);	
	}	
	function deleteChemicalUnit($unit) {
	    global $db;
		if($unit=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_unit_chemical 		
					WHERE unit_of_chemical = '$unit' ";
		return $this->Transact($this->sql);	
	}

	
	

	#Danh sach cac ham cua dang thuoc, duong dung -----------------------------------------------------------------------
	
	//Get Detail
	function getPharmaDetailType($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_type_phar WHERE type_of_medicine='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getPharmaDetailUse($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_use_phar WHERE use_of_medicine='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	//List
	function listPharmaType(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_type_phar ORDER BY type_name_of_medicine";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	function listPharmaUse(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_use_phar ORDER BY name_use";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	//Create
	function newPharmaType($name) {
	    global $db;
		if($name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_type_phar 
						(type_name_of_medicine, 
						history, 
						create_id, 
						create_time
						)
					VALUES
						('$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}
	function newPharmaUse($name) {
	    global $db;
		if($name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_use_phar 
						(name_use, 
						history, 
						create_id, 
						create_time
						)
					VALUES
						('$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}
	
	//Update
	function updatePharmaType($nr, $name) {
	    global $db;
		if($nr=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_type_phar
					SET 
					type_name_of_medicine = '$name' , 
					history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 
					WHERE
					type_of_medicine = '$nr' ";		
		return $this->Transact($this->sql);	
	}
	function updatePharmaUse($nr, $name) {
	    global $db;
		if($nr=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_use_phar
					SET 
					name_use = '$name' , 
					history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 
					WHERE
					use_of_medicine = '$nr' ";		
		return $this->Transact($this->sql);	
	}	
	
	//Delete
	function deletePharmaType($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_type_phar  		
					WHERE type_of_medicine = '$nr' ";
		return $this->Transact($this->sql);	
	}
	function deletePharmaUse($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_use_phar  		
					WHERE use_of_medicine = '$nr' ";
		return $this->Transact($this->sql);	
	}	
}

?>