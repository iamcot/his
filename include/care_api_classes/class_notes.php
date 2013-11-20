<?php
/**
* @package care_api
*/
/**
*/
require_once($root_path.'include/care_api_classes/class_core.php');

/**
*  Notes methods.
*  Note: this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance.
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class Notes extends Core {
	/**
	* Database table for the encounter notes data.
	* @var string
	* @access private
	*/
	var $tb_notes='care_encounter_notes';
	/**
	* Database table for the notes types.
	* @var string
	* @access private
	*/
	var $tb_types='care_type_notes';
	/**
	* Database table for the encounter data.
	* @var string
	* @access private
	*/
	var $tb_enc='care_encounter';
	/**
	* Holder for sql query results.
	* @var object adodb record object
	* @access private
	*/
        var $tb_person='care_person';
        var $tb_dept='care_department';

	var $result;
	/**
	* Holder for preloaded department data.
	* @var object adodb record object
	* @access private
	*/
	var $preload_dept;
	/**
	* Preloaded flag
	* @var boolean
	* @access private
	*/
	var $is_preloaded=false;
	/**
	* Field names of care_encounter_notes table
	* @var array
	* @access private
	*/
	var $fld_notes=array('nr',
									'encounter_nr',
									'type_nr',
									'notes',
									'short_notes',
									'aux_notes',
									'ref_notes_nr',
									'personell_nr',
									'personell_name',
									'send_to_pid',
									'send_to_name',
									'date',
									'time',
									'location_type',
									'location_type_nr',
									'location_nr',
									'location_id',
									'ack_short_id',
									'date_ack',
									'date_checked',
									'date_printed',
									'send_by_mail',
									'send_by_email',
									'send_by_fax',
									'status',
									'history',
									'modify_id',
									'modify_time',
									'create_id',
									'create_time',
									'morenote',
									'aux_morenote',
									'next_treatment',
									'person_decision',
									'list_member');
	/**
	* Constructor
	*/			
	function Notes(){
		$this->setTable($this->tb_notes);
		$this->setRefArray($this->fld_notes);
	}
	/**
	* Checks if a certain notes record of a certain type exists in the database.
	* @access private
	* @param int Encounter number
	* @param int Notes type number
	* @return boolean
	*/			
	function _Exists($enr,$type_nr){
		if($this->_RecordExists("type_nr=$type_nr AND encounter_nr=$enr")){
			return true;
		}else{return false;}
	}
	/**
	* Gets all types of notes record. Sorted result.
	* @access public
	* @param string Sort item
	* @return mixed 2 dimensional array or boolean
	*/			
	function getAllTypesSort($sort=''){
	    global $db;
	
		if(empty($sort)) $sort=" ORDER BY nr";
			else $sort=" ORDER BY $sort";
	    if ($this->result=$db->Execute("SELECT nr,type,name,LD_var AS \"LD_var\" FROM $this->tb_types WHERE status NOT IN ($this->dead_stat) $sort")) {
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
	* Gets all types of notes record. Unsorted result.
	* @access public
	* @param string Sort item
	* @return mixed 2 dimensional array or boolean
	*/			
	function getAllTypes(){
		return $this->getAllTypesSort();
	}
	/**
	* Gets notes type information based on the type number (nr key).
	*
	* The returned array has 4 elements:
	* - nr  = The type number (integer).
	* - type  = The optional type id (alphanumeric).
	* - name = The name of the notes type.
	* - LD_var  = The name of the language dependent variable containing the foreign name of the notes type.
	*
	* @access public
	* @return mixed 1 dimensional array or boolean
	*/			
	function getType($nr=1){
	    global $db;
	    if ($this->res['gt']=$db->Execute("SELECT nr,type,name,LD_var AS \"LD_var\" FROM $this->tb_types WHERE nr=$nr")) {
		    if ($this->res['gt']->RecordCount()) {
		        return $this->res['gt']->FetchRow();
			} else {
				return false;
			}
		}
		else {
		    return false;
		}
	}
	/**
	* Gets a notes record data based on a passed condition.
	* @access private
	* @param string Condition foRecordCount()r the WHERE sql part. Query constraint.
	* @param string Sort directive in complete syntax e.g. "ORDER BY date DESC"
	* @return mixed adodb record object or boolean
	*/			
	function _getNotes($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_notes WHERE $cond $order";
		//echo $this->sql;
		//echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        //return true;
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/**
	* Save a notes data of a given type number.
	*
	* The data to be saved comes from an internal buffer array that is populated by other methods.
	* @access private
	* @param string Type number of the notes data to be saved.
	* @return boolean
	*/
	function _insertNotesFromInternalArray($type_nr=''){
		global $_SESSION;
		if(empty($type_nr)) return false;
		if(empty($this->data_array['date'])) $this->data_array['date']=date('Y-m-d');
		if(empty($this->data_array['time'])) $this->data_array['time']=date('H:i:s');
		$this->data_array['type_nr']=$type_nr;
		//$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_id']=$_SESSION['sess_user_name'];
		$this->data_array['create_time']=date('YmdHis');
		$this->data_array['history']="Create: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r";	
        	return $this->insertDataFromInternalArray();
	}
	/**
	* Updates a notes data record based on the primary record key "nr".
	*
	* The data to be saved comes from an internal buffer array that is populated by other methods.
	* @access private
	* @param int Record number of the notes record to be updated.
	* @return boolean
	*/			
	function _updateNotesFromInternalArray($nr){
		global $_SESSION;
		$this->data_array['modify_id']=$_SESSION['sess_user_name'];
		$this->data_array['modify_time']=date('YmdHis');
		$this->data_array['history']=$this->ConcatHistory("Update: ".date('Y-m-d H-i-s')." ".$_SESSION['sess_user_name']."\n\r");
		return $this->updateDataFromInternalArray($nr);
		/*
		if($this->updateDataFromInternalArray($nr)){
			return true;
		}else{ return false; }
		*/
	}
	/**
	* Gets the date range of a certain notes type that fits to a given condition.
	*
	* The resulting adodb record object is stored in the internal buffer $result.
	* @access private
	* @param int Encounter number
	* @param int Notes type number
	* @param string Condition string. Query constraint.
	* @return boolean
	*/			
	function _getNotesDateRange($enr='',$type_nr=0,$cond=''){
		global $db;
		if(empty($enr)){
			return false;
		}else{
			if(empty($cond) && $type_nr){
				$cond="encounter_nr=$enr AND type_nr=$type_nr";
			}
			$this->sql="SELECT MIN(date) AS fe_date, MAX(date) AS le_date FROM $this->tb_notes WHERE $cond";
			if($this->result=$db->Execute($this->sql)){
				if($this->result->RecordCount()){
					return true;
				}else{return false;}
			}else{return false;}
		}
	}
	/**
	*Gets all notes of a given record number.
	* @access public
	* @param int Record number
	* @return mixed adodb record object or boolean
	*/
	function getEncounterNotes($nr){
		return $this->_getNotes("nr=$nr AND status NOT IN ($this->dead_stat)",'');
	}
	
	#----- Tong ket benh an /begin----------------------------------------------------------------------------
	function insertTongKetBenhAnItem($enc, $type_nr, $notes, $short_notes, $personell_name, $date){
		global $db;	
		$sql = "INSERT INTO $this->tb_notes (encounter_nr, type_nr, notes, short_notes, personell_name, date, time, history, create_id, create_time)
			VALUES ('".$enc."', '".$type_nr."', '".$notes."', '".$short_notes."', '".$personell_name."', '".$date."', '".date('H:i:s')."', 'Create: ".$personell_name." ".date('Y-m-d H:i:s')."', '".$personell_name."', '".date('H:i:s')."'); ";			
		return $this->Transact($sql);		
	}
	//Danh rieng cho phau thuat, thu thuat 
	function insertTongKetBenhAnArray($enc, $type_nr, $array_notes, $type_medoc, $personell_name, $date){
		global $db;		
		$flag=0;
		$nr = count($array_notes);		
		for ($i=0; $i<$nr; $i++){
			if(is_array($array_notes[$i])){
				$sql="INSERT INTO $this->tb_notes (encounter_nr, 
				type_nr, 
				notes, 
				short_notes, 
				aux_notes, 
				personell_name, 
				date, time, 
				history, create_id, create_time, 
				morenote, 
				aux_morenote)
				VALUES ('".$enc."', 
				'".$type_nr."', 
				'".($array_notes[$i]['pppt'])."', 
				'".$type_medoc."', 
				'".($array_notes[$i]['bspt'])."',
				'".$personell_name."', 
				'".($array_notes[$i]['date'])."', '".($array_notes[$i]['time'])."', 
				'Create: ".$personell_name." ".date('Y-m-d H:i:s')."', '".$personell_name."', '".date('H:i:s')."', 
				'".($array_notes[$i]['cb_pt'].','.$array_notes[$i]['cb_tt'])."', 
				'".($array_notes[$i]['bsgm'])."');";
				
				if ($this->Transact($sql))
					$flag=1;
			}
		}
		//echo $sql;
		return 	$flag;	
	}
	
	function insertTongKetBenhAn($enc, $date, $type_medoc, $lydovaovien, $quatrinhbenhly, $tomtatkqxn, $ketquagiaiphau, $chandoanvaovien, $phaptri, $thoigiantri, $ketquadieutri, $chandoanravien, $ppdieutri, $array_phauthuat, $ttravien, $huongdieutri ){
	
		global $_SESSION;
		$user = $_SESSION['sess_user_name'];
		$sql=''; $flag=0;
		
		//Ly do vao vien: type=8
		if ($this->insertTongKetBenhAnItem($enc, 8, $lydovaovien, $type_medoc, $user, $date))
			$flag=1;		
		
		//Qua trinh benh ly: type=9
		if ($this->insertTongKetBenhAnItem($enc, 9, $quatrinhbenhly, $type_medoc, $user, $date))
			$flag=1;
			
		//Tom tat ket qua CLS: type=10
		if ($this->insertTongKetBenhAnItem($enc, 10, $tomtatkqxn, $type_medoc, $user, $date))
			$flag=1;
			
		//Ket qua giai phau: type=11
		if ($this->insertTongKetBenhAnItem($enc, 11, $ketquagiaiphau, $type_medoc, $user, $date))
			$flag=1;			
			
		//Chan doan vao vien: type=13
		if ($this->insertTongKetBenhAnItem($enc, 13, $chandoanvaovien, $type_medoc, $user, $date))
			$flag=1;			

		//Phap tri: type=14
		if ($this->insertTongKetBenhAnItem($enc, 14, $phaptri, $type_medoc, $user, $date))
			$flag=1;		

		//Thoi gian dieu tri: type=22
		if ($this->insertTongKetBenhAnItem($enc, 22, $thoigiantri, $type_medoc, $user, $date))
			$flag=1;			
					
		//Ket qua dieu tri: type=23
		if ($this->insertTongKetBenhAnItem($enc, 23, $ketquadieutri, $type_medoc, $user, $date))
			$flag=1;

		//Chan doan ra vien: type=36
		if ($this->insertTongKetBenhAnItem($enc, 36, $chandoanravien, $type_medoc, $user, $date))
			$flag=1;

		//PP dieu tri: type=37
		if ($this->insertTongKetBenhAnItem($enc, 37, $ppdieutri, $type_medoc, $user, $date))
			$flag=1;

		//Phau thuat, thu thuat: type=38
		if ($this->insertTongKetBenhAnArray($enc, 38, $array_phauthuat, $type_medoc, $user, $date))
			$flag=1;			
			
		//Tinh trang ra vien: type=39
		if ($this->insertTongKetBenhAnItem($enc, 39, $ttravien, $type_medoc, $user, $date))
			$flag=1;				
			
		//Huong dieu tri: type=40
		if ($this->insertTongKetBenhAnItem($enc, 40, $huongdieutri, $type_medoc, $user, $date))
			$flag=1;	
			
		return $flag;
	}
	
	function updateTongKetBenhAnItem($enc, $type_nr, $notes, $short_notes, $personell_name, $date){
		global $db;	
		$sql = "UPDATE $this->tb_notes 
					SET notes = '".$notes."' ,  
						short_notes= '".$short_notes."' , 
						personell_name = '".$personell_name."' , 
						date = '".$date."' , 
						history = ".$this->ConcatHistory("Update: ".date('Y-m-d H:i:s')." ".$personell_name."\n\r")." , 
						modify_id = '".$personell_name."' , 
						modify_time = '".date('H:i:s')."'
					WHERE encounter_nr = '".$enc."' AND type_nr='".$type_nr."' ;";
		return $this->Transact($sql);
	}
	//Danh rieng cho phau thuat, thu thuat 
	function updateTongKetBenhAnArray($enc, $type_nr, $array_notes, $short_notes, $personell_name, $date){
		global $db;		
		$sql = "DELETE FROM $this->tb_notes WHERE encounter_nr='".$enc."' AND type_nr='".$type_nr."'";
		$this->Transact($sql);
		return $this->insertTongKetBenhAnArray($enc, $type_nr, $array_notes, $short_notes, $personell_name, $date);	
	}	
	function updateTongKetBenhAn($enc, $date, $type_medoc, $lydovaovien, $quatrinhbenhly, $tomtatkqxn, $ketquagiaiphau, $chandoanvaovien, $phaptri, $thoigiantri, $ketquadieutri, $chandoanravien, $ppdieutri, $array_phauthuat, $ttravien, $huongdieutri ){
	
		global $_SESSION;
		$user = $_SESSION['sess_user_name'];
		$sql=''; $flag=0;
		
		//Ly do vao vien: type=8
		if ($this->updateTongKetBenhAnItem($enc, 8, $lydovaovien, $type_medoc, $user, $date))
			$flag=1;		
		
		//Qua trinh benh ly: type=9
		if ($this->updateTongKetBenhAnItem($enc, 9, $quatrinhbenhly, $type_medoc, $user, $date))
			$flag=1;
			
		//Tom tat ket qua CLS: type=10
		if ($this->updateTongKetBenhAnItem($enc, 10, $tomtatkqxn, $type_medoc, $user, $date))
			$flag=1;
			
		//Ket qua giai phau: type=11
		if ($this->updateTongKetBenhAnItem($enc, 11, $ketquagiaiphau, $type_medoc, $user, $date))
			$flag=1;			
			
		//Chan doan vao vien: type=13
		if ($this->updateTongKetBenhAnItem($enc, 13, $chandoanvaovien, $type_medoc, $user, $date))
			$flag=1;			

		//Phap tri: type=14
		if ($this->updateTongKetBenhAnItem($enc, 14, $phaptri, $type_medoc, $user, $date))
			$flag=1;		

		//Thoi gian dieu tri: type=22
		if ($this->updateTongKetBenhAnItem($enc, 22, $thoigiantri, $type_medoc, $user, $date))
			$flag=1;			
					
		//Ket qua dieu tri: type=23
		if ($this->updateTongKetBenhAnItem($enc, 23, $ketquadieutri, $type_medoc, $user, $date))
			$flag=1;

		//Chan doan ra vien: type=36
		if ($this->updateTongKetBenhAnItem($enc, 36, $chandoanravien, $type_medoc, $user, $date))
			$flag=1;

		//PP dieu tri: type=37
		if ($this->updateTongKetBenhAnItem($enc, 37, $ppdieutri, $type_medoc, $user, $date))
			$flag=1;

		//Phau thuat, thu thuat: type=38
		if ($this->updateTongKetBenhAnArray($enc, 38, $array_phauthuat, $type_medoc, $user, $date))
			$flag=1;			
			
		//Tinh trang ra vien: type=39
		if ($this->updateTongKetBenhAnItem($enc, 39, $ttravien, $type_medoc, $user, $date))
			$flag=1;				
			
		//Huong dieu tri: type=40
		if ($this->updateTongKetBenhAnItem($enc, 40, $huongdieutri, $type_medoc, $user, $date))
			$flag=1;	
			
		return $flag;
	}
	
	#----- Tong ket benh an /end----------------------------------------------------------------------------
	
	function getValueNotes($enc,$type_nr){
	  global $db;
	  $this->sql="select notes from $this->tb_notes where encounter_nr=$enc and type_nr=$type_nr";
	  if($buf=$db->Execute($this->sql)){
		    if($buf->RecordCount()) {
				$buf2=$buf->FetchRow();
				return $buf2['notes'];
			}else{return FALSE;}
		}else{return FALSE;}
	}
	
	#------ So ket 15 ngay dieu tri /begin ------------------------------------
	function getlanSoKetsaucung($enc){
	  global $db;
	  $this->sql="select short_notes from $this->tb_notes where encounter_nr='$enc' and type_nr='42' ORDER BY short_notes DESC ";
	  if($buf=$db->Execute($this->sql)){
		    if($buf->RecordCount()) {
				$buf2=$buf->FetchRow();
				return $buf2['short_notes'];
			}else{return 0;}
		}else{return 0;}		
	}
	function insertSoKet15Ngay($enc, $date, $dienbienlamsang, $xetnghiemcls, $quatrinhdieutri, $danhgiakq, $huongdieutri ){
	
		global $_SESSION;
		$user = $_SESSION['sess_user_name'];
		$flag=0;
		$lansoket = $this->getlanSoKetsaucung($enc);
		$lansoket++;
		if ($this->insertTongKetBenhAnItem($enc, 42, $dienbienlamsang, $lansoket, $user, $date))
			$flag=1;		
		
		if ($this->insertTongKetBenhAnItem($enc, 43, $xetnghiemcls, $lansoket, $user, $date))
			$flag=1;
			
		if ($this->insertTongKetBenhAnItem($enc, 44, $quatrinhdieutri, $lansoket, $user, $date))
			$flag=1;
			
		if ($this->insertTongKetBenhAnItem($enc, 45, $danhgiakq, $lansoket, $user, $date))
			$flag=1;			
			
		if ($this->insertTongKetBenhAnItem($enc, 46, $huongdieutri, $lansoket, $user, $date))
			$flag=1;			
			
		return $flag;
	}
	function updateSoKetItem($enc, $type_nr, $lansoket, $notes, $personell_name, $date){
		global $db;	
		$sql = "UPDATE $this->tb_notes 
					SET notes = '".$notes."' ,  
						personell_name = '".$personell_name."' , 
						date = '".$date."' , 
						history = ".$this->ConcatHistory("Update: ".date('Y-m-d H:i:s')." ".$personell_name."\n\r")." , 
						modify_id = '".$personell_name."' , 
						modify_time = '".date('H:i:s')."'
					WHERE encounter_nr = '".$enc."' AND type_nr='".$type_nr."' AND short_notes='".$lansoket."';";
		return $this->Transact($sql);
	}
	function updateSoKet15Ngay($enc, $date, $lansoket, $dienbienlamsang, $xetnghiemcls, $quatrinhdieutri, $danhgiakq, $huongdieutri ){
	
		global $_SESSION;
		$personell_name = $_SESSION['sess_user_name'];
		$sql=''; $flag=0;
		
		if ($this->updateSoKetItem($enc, 42, $lansoket, $dienbienlamsang, $personell_name, $date))
			$flag=1;		
		if ($this->updateSoKetItem($enc, 43, $lansoket, $xetnghiemcls, $personell_name, $date))
			$flag=1;	
		if ($this->updateSoKetItem($enc, 44, $lansoket, $quatrinhdieutri, $personell_name, $date))
			$flag=1;	
		if ($this->updateSoKetItem($enc, 45, $lansoket, $danhgiakq, $personell_name, $date))
			$flag=1;	
		if ($this->updateSoKetItem($enc, 46, $lansoket, $huongdieutri, $personell_name, $date))
			$flag=1;				
			
		return $flag;
	}
        //cac giay to khac        function _getNotesKhac($cond,$order='ORDER BY notes.date,notes.time DESC'){	    global $db;            $this->sql="SELECT notes.* FROM $this->tb_notes AS notes, $this->tb_types AS types WHERE $cond $order";//            echo $this->sql;	    if ($this->result=$db->Execute($this->sql)) {		    if ($this->result->RecordCount()) {		        //return true;		        return $this->result;			}else{return false;}		}else{return false;}	}}
?>
