<?php
/**
* @package care_api
*/
/** */
require_once($root_path.'include/care_api_classes/class_core.php');

class ListGroup extends Core {

	#Define table
	var $tb_gp_phar='care_pharma_group';
	var $tb_gp_phar_sub='care_pharma_group_sub';
	var $tb_gp_med='care_med_products_main_sub';	
	var $tb_gp_chemical='care_chemical_group';	


	
	#Danh sach cac ham cua don vi -----------------------------------------------------------------------
	
	//Get Detail
	function getPharmaDetailGroup($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_gp_phar WHERE pharma_group_id='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getPharmaDetailGroupSub($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_gp_phar_sub WHERE nr='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}	
	function getMedDetailGroup($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_gp_med WHERE id='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getChemicalDetailGroup($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_gp_chemical WHERE chemical_group_id='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	//List
	function listPharmaGroup(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_gp_phar ORDER BY pharma_group_id";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	function listPharmaGroupSub(){
		global $db;
		$this->sql="SELECT gps.*, gp.pharma_group_name  
					FROM $this->tb_gp_phar AS gp, $this->tb_gp_phar_sub AS gps 
					WHERE gp.pharma_group_id=gps.pharma_group_id 
					ORDER BY gps.nr";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	function listMedGroup(){
		global $db;
		$this->sql="SELECT gp.*, type.type_name_of_med FROM $this->tb_gp_med AS gp, care_med_type_of_medicine AS type 
					WHERE gp.type_of_med=type.type_of_med 
					ORDER BY gp.type_of_med";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	function listMedType(){
		global $db;
		$this->sql="SELECT * FROM care_med_type_of_medicine ";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	function listChemicalGroup(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_gp_chemical ORDER BY chemical_group_name";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}

	//Create
	function newPharmaGroup($nr, $name) {
	    global $db;
		if($nr=='' || $name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_gp_phar 
						(pharma_group_id, 
						pharma_group_name, 
						history, 
						note, 
						create_id, 
						create_time
						)
					VALUES
						('$nr', 
						'$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."',  
						'', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}
	function newPharmaGroupSub($group_id, $group_id_sub, $name) {
	    global $db;
		if($group_id=='' || $group_id_sub=='' || $name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_gp_phar_sub 
						(pharma_group_id, 
						pharma_group_id_sub, 
						pharma_group_name_sub, 
						history, 
						note, 
						create_id, 
						create_time
						)
					VALUES
						('$group_id', 
						'$group_id_sub', 
						'$name', 
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."',  
						'', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}	
	function newMedGroup($type, $name) {
	    global $db;
		if($type=='' || $name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_gp_med 
						(type_of_med, 
						name_sub, 
						create_time
						)
						VALUES
						('$type', 
						'$name',  
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}	
	function newChemicalGroup($name) {
	    global $db;
		if($name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_gp_chemical 
					(chemical_group_name, 
					history, 
					note, 
					create_id, 
					create_time
					)
					VALUES
					('$name',  
					'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
					'',
					'".$_SESSION['sess_user_name']."', 
					'".date('Y-m-d H:i:s')."'
					)";
		return $this->Transact($this->sql);	
	}	
	
	//Update
	function updatePharmaGroup($oldnr, $nr, $name) {
	    global $db;
		if($oldnr=='' || $nr=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_gp_phar 
					SET
						pharma_group_id = '$nr' , 
						pharma_group_name = '$name' , 
						history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")."  					
					WHERE
						pharma_group_id = '$oldnr' ";		
		return $this->Transact($this->sql);	
	}
	function updatePharmaGroupSub($nr, $group_id, $group_id_sub, $name) {
	    global $db;
		if($nr=='' || $group_id=='' || $group_id_sub=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_gp_phar_sub 
					SET
						pharma_group_id = '$group_id' , 
						pharma_group_id_sub = '$group_id_sub' , 
						pharma_group_name_sub = '$name' , 
						history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 
					WHERE
						nr = '$nr' ";		
		return $this->Transact($this->sql);	
	}	
	function updateMedGroup($nr, $type, $name) {
	    global $db;
		if($nr=='' || $type=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_gp_med 
					SET type_of_med = '$type' , 
						name_sub = '$name' 
					WHERE
						id = '$nr' ";
		return $this->Transact($this->sql);	
	}	
	function updateChemicalGroup($nr, $name) {
	    global $db;
		if($nr=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_gp_chemical 
					SET	 
						chemical_group_name = '$name' , 
						history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 
					WHERE
						chemical_group_id = '$nr'  ";
		return $this->Transact($this->sql);	
	}
	
	//Delete
	function deletePharmaGroup($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_gp_phar  		
					WHERE pharma_group_id = '$nr' ";
		return $this->Transact($this->sql);	
	}
	function deletePharmaGroupSub($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_gp_phar_sub  		
					WHERE nr = '$nr' ";
		return $this->Transact($this->sql);	
	}	
	function deleteMedGroup($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_gp_med 		
					WHERE id = '$nr' ";
		return $this->Transact($this->sql);	
	}	
	function deleteChemicalGroup($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_gp_chemical 		
					WHERE chemical_group_id = '$nr' ";
		return $this->Transact($this->sql);	
	}

	
	
}
	
?>