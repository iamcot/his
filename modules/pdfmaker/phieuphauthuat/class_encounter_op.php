<?php
    /**
    * @package care_api
    */
    /**
    */
    # Define to TRUE if you want to show the ward id with full name  on the selection box
    define('SHOW_COMBINE_WARDIDNAME',1);
    # Define to TRUE if you want to show the full name of  wards on the selection box
    define('SHOW_FULL_WARDNAME',FALSE);
    /**
    */
    require_once($root_path.'include/care_api_classes/class_core.php');
    class OPEncounter extends Core{
        /**
	* Database table room data
	* @var string
	* @access private
	*/
        var $tb='care_encounter_op';
        var $tb_test='care_test_request_or';
        //add by vy
        var $tb_medoc_op='care_op_med_doc';
        
        function getInfo($enc_nr='',$status){
            global $db;
            $this->sql="SELECT * FROM care_encounter_op WHERE encounter_nr='$enc_nr' AND status='$status'";
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return $this->res['oi'];
            }else{return false;}
        }

        function updateInfo($enc_nr,$dept_nr,$operator,$diagnosis){
            global $db;
            $this->sql="INSERT INTO $this->tb (year,
                                          dept_nr,
                                          op_date,
                                          op_src_date,
                                          encounter_nr,
                                          diagnosis,
                                          op_therapy,
                                          encoding,
                                          doc_date,
                                          doc_time,
                                          status,
                                          history,
                                          create_id,
                                          create_time)
                                  VALUES ('".date(Y)."',
                                          '$dept_nr',
                                          '".date('Y-m-d')."',
                                          '".date('Ymd')."',
                                          '$enc_nr',
                                          '$operator',
                                          '$diagnosis',
                                          ' ~e=".$_SESSION['sess_user_name']."&d=".date("d-m-Y")."&t=".date("H:i:s")."\n',
                                          '".date("d-m-Y")."', '".date("H:i")."',
                                          'pending',
                                          ".$this->ConcatHistory('Create: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name']."\n").",
                                          '".$_SESSION['sess_user_name']."', '".date('Y-m-d H:i:s')."')";
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return $this->res['oi']->FetchRow();
            }else{return false;}
        }

        function getInfoTest(){
            global $db;
            $this->sql="SELECT  batch_nr,encounter_nr,send_date,dept_nr
                        FROM $this->tb_test;
			WHERE status='pending' OR status='received' ORDER BY  send_date DESC";
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return $this->res['oi'];
            }else{return false;}
        }
        
        function checkRequestOP($batch_nr){
            global $db;
            if(empty($batch_nr)) $batch_nr='0';
            $this->sql="SELECT * FROM $this->tb WHERE batch_nr='$batch_nr' AND (status='pending' OR status='received')";
            echo $this->sql;
        }
        
        //add by vy
        function getInfoMedoc($enc){
                global $db;
                if(empty($enc)) $enc='0';
                $this->sql="SELECT * FROM $this->tb_medoc_op WHERE encounter_nr='$enc' ";
                if($this->res['gim']=$db->Execute($this->sql)){
                        if ($this->res['gim']->RecordCount()) {
                return $this->res['gim']->FetchRow();
                        }else{return FALSE;}
                }else{return FALSE;}
        }





    }
?>