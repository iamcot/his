<?php

require_once($root_path.'include/care_api_classes/class_core.php');

class HealthStation extends Core {

	#Define table
	var $tb_health='care_pharma_health_station';
	var $tb_type='care_pharma_type_health_station';
	
	
	//Get Detail
	function getDetailHealth($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_health WHERE health_station='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getDetailType($nr){
		global $db;
		$this->sql="SELECT * FROM $this->tb_type WHERE nr='$nr'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	
	//List
	function listHealth(){
		global $db;
		$this->sql="SELECT ht.*, tp.*  
					FROM $this->tb_health AS ht, $this->tb_type AS tp 
					WHERE ht.type=tp.nr 
					ORDER BY type,village";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	function listType(){
		global $db;
		$this->sql="SELECT * FROM $this->tb_type ORDER BY typename";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	//Create
	function newHealth($name, $type, $address, $tel_number, $note) {
	    global $db;
		if($name=='' || $type=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_health 
						(village, 
						type, 
						address, 
						tel_number, 
						note, 
						history, 
						create_id, 
						create_time
						)
					VALUES
						('$name', 
						'$type',
						'$address', 
						'$tel_number', 
						'$note', 						
						'Create by: ".$_SESSION['sess_user_name']." ".date('Y-m-d H:i:s')."', 
						'".$_SESSION['sess_user_name']."', 
						'".date('Y-m-d H:i:s')."'
						)";
		return $this->Transact($this->sql);	
	}
	function newType($name) {
	    global $db;
		if($name=='') return FALSE;
		$this->sql="INSERT INTO $this->tb_type  
						(typename, 
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
	function updateHealth($nr, $name, $type, $address, $tel_number, $note) {
	    global $db;
		if($type=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_health
					SET
						village = '$name' , 
						type = '$type' , 
						address = '$address' , 
						tel_number = '$tel_number' , 
						note = '$note' ,  
						history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 
					WHERE
						health_station = '$nr' ";		
		return $this->Transact($this->sql);	
	}
	function updateType($nr, $name) {
	    global $db;
		if($nr=='' || $name=='') return FALSE;
		$this->sql="UPDATE $this->tb_type
					SET
					typename = '$name',   
					history = ".$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r")." 
					WHERE
					nr = '$nr' ";		
		return $this->Transact($this->sql);	
	}	
	
	//Delete
	function deleteHealth($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_health  		
					WHERE health_station = '$nr' ";		
		return $this->Transact($this->sql);	
	}
	function deleteType($nr) {
	    global $db;
		if($nr=='') return FALSE;
		$this->sql="DELETE FROM $this->tb_type  		
					WHERE nr = '$nr' ";
		return $this->Transact($this->sql);	
	}	
}



?>