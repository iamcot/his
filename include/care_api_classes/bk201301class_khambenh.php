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
class Khambenh extends Core {
	/**
	* Database table for the encounter notes data.
	* @var string
	* @access private
	*/
	var $tb_khambenh='care_encounter_khambenh';
	/**
	* Database table for the notes types.
	* @var string
	* @access private
	*/
	
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
	var $tb_hb_nhi='care_hoibenh_nhi';
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
	var $fld_notes=array(

						'nr',
						'encounter_nr',
						'personell_name',
						'toanthan_notes',
                        'phu',
                        'da_notes',
                        'hach_notes',
                        'vu_notes',
						'tuanhoan_notes',
						'hohap_notes',
						'tieuhoa_notes',
						'thantietnieusinhduc_notes',
						'thankinh_notes',
						'coxuongkhop_notes',
						'taimuihong_notes',
						'ranghammat_notes',
						'mat_notes',
						'khac_notes',
						'tongquat_bp',
						'chuyenkhoa',
						'date',
						'time',
						'history',
						'modify_id',
						'modify_time',
						'create_id',
						'create_time',
                         'flag');
        
        //Huynh
        var $tb_khamngoai='care_encounter_khamngoai';
        var $list_khamngoai=array(
						'nr',
						'encounter_nr',
						'seopt',
                        'timthai',
						'hdtc',
						'tuthe',
						'chieucaotc',
						'vongbung',
						'cctc',
						'vu',
                        'date_khamngoai',
						'status',
						'history',						
						'create_id',
						'create_time',
                        'modify_id',
						'modify_time');
        var $tb_khamtrong='care_encounter_khamtrong';
        var $list_khamtrong=array(
						'nr',
						'encounter_nr',
						'bishop',
                        'amho',
						'amdao',
						'tangsinhmon',
						'TC',
						'phanphu',
						'oi',
                        'time_oivo',
                        'date_oivo',
						'oivo',
                        'msnuocoi',
                        'nuocoi',
                        'ngoi',
                        'the',
                        'kieuthe',
                        'dolot',
                         'dknhv',
						'status',
						'history',						
						'create_id',
						'create_time',
                        'modify_id',
						'modify_time');
        
        var $tb_khamkhac='care_encounter_khamkhac';
        var $list_khamkhac=array(
						'nr',
						'encounter_nr',
						'xetnghiem_notes',
                        'dept_nr',
						'phanbiet_notes',
						'tienluong_notes',
						'ppchinh_notes',
                         'date_khamngoai',
						'status',
						'history',						
						'create_id',
						'create_time',
                        'modify_id',
						'modify_time');
        var $tb_khamchuyenkhoa='care_encounter_khamchuyenkhoa';
        var $list_khamchuyenkhoa=array(
						'nr',
                        'pid',
                         'encounter_nr',
						'dauhieu_notes',
						'moilon_notes',
                        'moibe_notes',
						'amvat_notes',
                        'amho',
						'mangtrinh_notes',
                        'tangsinhmon',
                        'amdao',
                         'TC',
                        'thanhtc_notes',
                         'phanphu',
						'tuicung_notes',
                         'date',
						'status',
						'history',						
						'create_id',
						'create_time',
                         'modify_id',
						'modify_time');
        
        ///////////////////////
		
		//kham chuyen khoa mat
		var $tb_chuyenkhoa_mat='care_chuyenkhoa_mat';
		var $mat_array=array(
							'nr',
							'encounter_nr',
							'thiluc_khongkinh_trai',
							'thiluc_khongkinh_phai',
							'thiluc_cokinh_trai',
							'thiluc_cokinh_phai',
							'nhanap_trai',
							'nhanap_phai',
							'thitruong_trai',
							'thitruong_phai',
							'ledao_trai',
							'ledao_phai',
							'mimat_trai',
							'mimat_phai',
							'ketmac_trai',
							'ketmac_phai',
							'mathot_trai',
							'mathot_phai',
							'giacmac_trai',
							'giacmac_phai',
							'cungmac_trai',
							'cungmac_phai',
							'tienphong_trai',
							'tienphong_phai',
							'mongmat_trai',
							'mongmat_phai',
							'dongtu_trai',
							'dongtu_phai',
							'thuytinhthe_trai',
							'thuytinhthe_phai',
							'thuytinhdich_trai',
							'thuytinhdich_phai',
							'anhdongtu_trai',
							'anhdongtu_phai',
							'nhancau_trai',
							'nhancau_phai',
							'hocmat_trai',
							'hocmat_phai',
							'daymat_trai',
							'daymat_phai',
							'doctor_nr',
							'doctor_name',
							'date',
							'history',
							'create_id',
							'create_time',
							'modify_id',
							'modify_time'
							);
        var $tb_yhct_ngoaitru='care_benhan_ngoaitru_yhct';
		var $yhct_ngoaitru_array=array(
								'nr',
								'encounter_nr',
								'date',
								'vongchan',
								'vanchan',
								'van_chan',
								'thietchan',
								'benhdanh',
								'batcuong',
								'tangphu',
								'nguyennhan',
								'phepchua',
								'phuongthuoc',
								'phuonghuyet',
								'xoabop',
								'chedoan',
								'chedoholy',
								'tienluong',
								'doctor_nr',
								'doctor_name',
								'history',
								'create_id',
								'create_time',
								'modify_id',
								'modify_time'
								);
								
	//kham chuyen khoa tmh
	var $tb_tmh='care_ck_tmh';
	var $tmh_array=array(
					'nr',
					'encounter_nr',
					'date',
					'notes',
					'thanhquan_notes',
					'hong_notes',
					'conghiengtrai',
					'conghiengphai',
					'tai_notes',
					'mui_notes',
					'doctor_nr',
					'doctor_name',
					'history',
					'create_id',
					'create_time',
					'modify_id',
					'modify_time'
					);
	var $tb_rhm='care_ck_rhm';
	var $rhm_array=array(
					'nr',
					'encounter_nr',
					'date',
					'toanthan_notes',
					'ck_notes',
					'phai_notes',
					'thang_notes',
					'trai_notes',
					'hamtrenhong_notes',
					'hamduoi_notes',
					'kheho_notes',
					'doctor_nr',
					'doctor_name',
					'history',
					'create_id',
					'create_time',
					'modify_id',
					'modify_time'
					);
	var $hb_nhi_array=array(
					'nr',
					'encounter_nr',
					'conthu',
					'tienthai',
					'tinhtrangkhisinh',
					'cannang',
					'ditatbamsinh',
					'ditatnotes',
					'pttinhthan',
					'ptvandong',
					'benhkhac',
					'nuoiduong',
					'thangcaisua',
					'chamsoc',
					'tiemchung',
					'tiemchungkhac',
					'date',
					'doctor_name',
					'doctor_nr',
					'history',
					'create_id',
					'create_time',
					'modify_id',
					'modify_time'
					
	);
	var $tb_tcm='care_khambenh_tcm';
	var $tcm_array=array(
						 'nr',
						 'encounter_nr',
						 'tim',
						 'sp02',
						 'trigiac',
						 'th_tiengtim',
						 'time_maomach',
						 'amthoi_notes',
						 'th_khac',
						 'hohap',
						 'hohap_khac',
						 'th_ganto',
						 'th_ganto_cm',
						 'th_ganto_dd',
						 'th_khac',
						 'than_tn_sd',
						 'tk_dongtu',
						 'tk_pxas',
						 'thankinh',
						 'tk_khac',
						 'co_xuong_khop',
						 'tmh_rhm_m_khac',
						 'date',
						 'doctor_nr',
						 'doctor_name',
						 'history',
						 'create_id',
						 'create_time',
						 'modify_id',
						 'modify_time'
	);
	/**
	* Constructor
	*/			
	function Khambenh(){
		$this->setTable($this->tb_khambenh);
		$this->setRefArray($this->fld_notes);
	}
	
	
	
	
	function _getKhambenh($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_khambenh WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        //return true;
		        return $this->result;
			}else{return false;}
		}else{return false;}
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
	function _getKhambenhDateRange($enr='',$type_nr=0,$cond=''){
		global $db;
		if(empty($enr)){
			return false;
		}else{
			if(empty($cond)){
				$cond="encounter_nr=$enr";
			}
			$this->sql="SELECT MIN(date) AS fe_date, MAX(date) AS le_date FROM $this->tb_khambenh WHERE $cond";
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
	function getEncounterKhambenh($nr){
		return $this->_getKhambenh("nr=$nr AND status NOT IN ($this->dead_stat)",'');
	}
        //Huynh-khoa san 
        
        function KhamNgoai(){
            $this->setTable($this->tb_khamngoai);
            $this->setRefArray($this->list_khamngoai);
	}
        
        function getEncounterKhambenh1($nr,$flag){
            return $this->_getKhambenh("encounter_nr=$nr AND flag=$flag" ,'');
	}
        
        function getEncounterKhambenh_ngoai($nr){
            return $this->_getKhambenh_ngoai("encounter_nr=$nr AND status NOT IN ($this->dead_stat)" ,'');
	}
        
        function _getKhambenh_ngoai($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_khamngoai WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function KhamTrong(){
            $this->setTable($this->tb_khamtrong);
            $this->setRefArray($this->list_khamtrong);
	}
        
        function getEncounterKhambenh_trong($nr){
            return $this->_getKhambenh_trong("encounter_nr=$nr AND status NOT IN ($this->dead_stat)" ,'');
	}
        
        function _getKhambenh_trong($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_khamtrong WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function KhamKhac(){
            $this->setTable($this->tb_khamkhac);
            $this->setRefArray($this->list_khamkhac);
	}
        
        function getEncounterKhambenh_khac($nr){
            return $this->_getKhambenh_khac("encounter_nr=$nr AND status NOT IN ($this->dead_stat)" ,'');
	}
        
        function _getKhambenh_khac($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_khamkhac WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function getEncounterKhambenh_Phu($nr,$flag){
            return $this->_getKhambenh("encounter_nr=$nr AND flag=$flag" ,'');
	}
        
        function KhamChuyenkhoa(){
            $this->setTable($this->tb_khamchuyenkhoa);
            $this->setRefArray($this->list_khamchuyenkhoa);
	}
        
        function getEncounterKhamChuyenkhoa($nr){
            return $this->_getKhamChuyenkhoa("encounter_nr=$nr AND status NOT IN ($this->dead_stat)" ,'');
	}
        
        function _getKhamChuyenkhoa($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_khamchuyenkhoa WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}    

//kham chuyen khoa mat
		function KhamChuyenKhoaMat(){
			$this->setTable($this->tb_chuyenkhoa_mat);
			$this->setRefArray($this->mat_array);
		}
		function getKhamChuyenKhoaMat($nr){
		global $db;
		 $this->sql="select * from $this->tb_chuyenkhoa_mat where encounter_nr=$nr";
		// echo $this->sql;
		  if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        //return true;
		        return $this->result;
			}else{return false;}
		}else{return false;}
		}
//yhct ngoai tru benh an
		function KhamYHCTNgoaiTru(){
			$this->setTable($this->tb_yhct_ngoaitru);
			$this->setRefArray($this->yhct_ngoaitru_array);
		}
		
		function KhamRHM(){
			$this->setTable($this->tb_rhm);
			$this->setRefArray($this->rhm_array);
		}
		function KhamTMH(){
			$this->setTable($this->tb_tmh);
			$this->setRefArray($this->tmh_array);
		}
		function getKhamRHM($nr){
			 return $this->_getKhamRHM("encounter_nr=$nr" ,'');
		}
		 function _getKhamRHM($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_rhm WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	//hoi benh nhi	
		function HoibenhNhi(){
			$this->setTable($this->tb_hb_nhi);
			$this->setRefArray($this->hb_nhi_array);
		}
		 function getNhihoibenh($nr){
            return $this->_getNhihoibenh("encounter_nr=$nr" ,'');
	}
        
        function _getNhihoibenh($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_hb_nhi WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	//kham benh tcm
	function KhambenhTCM(){
			$this->setTable($this->tb_tcm);
			$this->setRefArray($this->tcm_array);
		}
	function getKhambenhTCM($nr){
            return $this->_getKhambenhTCM("encounter_nr=$nr" ,'');
	}
        
        function _getKhambenhTCM($cond,$order='ORDER BY date,time DESC'){
	    global $db;
		$this->sql="SELECT * FROM $this->tb_tcm WHERE $cond $order";
//		echo $this->sql;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
}
?>