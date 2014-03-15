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
        var $tb_medoc_op='care_op_med_doc';
        var $tb_personell_op='care_personell_op';
        var $tb_personell='care_personell';
        var $tb_person='care_person';
        var $tb_encounter='care_encounter';
        var $tb_user='care_users';
	
        var $result;
        var $record_count;
        var $personell_data;
        
        function getInfo($batch_nr='',$status){
            global $db;
            if($status==''){
                $this->sql="SELECT * FROM $this->tb WHERE batch_nr='$batch_nr'";
            }else{
                $this->sql="SELECT * FROM $this->tb WHERE batch_nr='$batch_nr' AND status='$status'";
            }
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return $this->res['oi'];
            }else{return false;}
        }

        function InsertInfo($saal,$batch_nr,$time){
            global $db;
            $this->sql="INSERT INTO $this->tb (year,
                                          op_room,
                                          batch_nr,
                                          encoding,
                                          doc_time,
                                          status,
                                          history,
                                          create_id)
                                  VALUES ('".date(Y)."',
                                          '$saal',
                                          '$batch_nr',
                                          ' ~e=".$_SESSION['sess_user_name']."&d=".date("d-m-Y")."&t=".date("H:i:s")."\n',
                                          '".$time."',
                                          'pending',
                                          ".$this->ConcatHistory('Create: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'].'\n').",
                                          '".$_SESSION['sess_user_name']."')";
//            echo $this->sql;
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return true;
            }else{return false;}
        }
        
        function updateInfo($batch_nr,$time){
            global $db;
            $this->sql="UPDATE $this->tb SET 
                                          encoding=' ~e=".$_SESSION['sess_user_name']."&d=".date("d-m-Y")."&t=".date("H:i:s")."\n',
                                          doc_time='".$time."',
                                          history=".$this->ConcatHistory('Update: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'].'\n').",
                                          create_id='".$_SESSION['sess_user_name']."'
                                WHERE batch_nr='$batch_nr' AND year='".date(Y)."'";
//            echo $this->sql;
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return true;
            }else{return false;}
        }

        function getInfoTest($batch_nr='',$status='pending'){
            global $db;
            if($status!=''){
                $this->sql="SELECT encounter_nr,date_request,level_method FROM $this->tb_test
				WHERE batch_nr=$batch_nr AND status='$status' OR status='move' ORDER BY  date_request DESC";
            }else{
                $this->sql="SELECT encounter_nr,date_request,level_method FROM $this->tb_test
				WHERE batch_nr=$batch_nr ORDER BY  send_date DESC";
            }
            
            if ($this->res['oi']=$db->Execute($this->sql)) {
                return $this->res['oi'];
            }else{
                return false;
            }
        }
		
        function getInfoMedoc($enc_op_nr='',$nr=''){
                global $db;
                if(empty($enc_op_nr)){ 
                    $enc='0';
                    $this->sql="SELECT * FROM $this->tb_medoc_op WHERE nr='$nr'";
                }else{
                    $this->sql="SELECT * FROM $this->tb_medoc_op WHERE encounter_op_nr='$enc_op_nr'";
                }
                if($this->res['gim']=$db->Execute($this->sql)){
                    if ($this->res['gim']->RecordCount()) {
                        return $this->res['gim']->FetchRow();
                    }else{return FALSE;}
                }else{return FALSE;}

        }
        
        function getNamePersonell($batch_nr, $function){
            global $db;
            $this->sql="SELECT $function FROM $this->tb WHERE batch_nr='$batch_nr'";
            if($this->res['oi']=$db->Execute($this->sql)){
                if($result=$this->res['oi']->FetchRow()){
                    $dbuf=explode("~",trim($result[$function]));
                    $entrycount=sizeof($dbuf);
                    $elems=array();
                    $t=array();
                    for($i=0;$i<$entrycount;$i++)
                    {
                        if(trim($dbuf[$i])=="") continue;
                        parse_str(trim($dbuf[$i]),$elems);
                        $t[$i]= $elems[n];
                    }
                    return $t;
                }else{return FALSE;}
            }else{return FALSE;}
        }
        //cập nhật lại phòng mổ của ê-kíp
	function updateRoom($saal='',$batch_nr=''){
            global $db;
            $this->sql="UPDATE $this->tb SET op_room='$saal',history=".$this->ConcatHistory('Update: '.date('Y-m-d H:i:s').' room\n')." WHERE batch_nr=$batch_nr";
            if($this->result=$db->Execute($this->sql)){                
                return $this->result->FetchRow();
            }else{return FALSE;}
        }
        
        function searchPersonell($encounter_op_nr='',$job_function_title='',$status=''){
            global $db;            
            $this->sql="SELECT pr.name_last,pr.name_first,tb.personell_nr
                        FROM $this->tb_personell_op AS tb,
                             $this->tb_personell AS ps,
                             $this->tb_person AS pr
                        WHERE tb.encounter_op_nr='$encounter_op_nr'
                              AND tb.status='$status'
                              AND tb.personell_nr=ps.nr
                              AND ps.job_function_title='$job_function_title'
                              AND ps.pid=pr.pid";
            if($this->result=$db->Execute($this->sql)){
                $personell_name=array();
                $i=0;
                while($this->res['gim']=$this->result->FetchRow()){
                    $personell_name[$i]=$this->res['gim']['name_last'].' '.$this->res['gim']['name_first'].'\x';
                    $i++;
                } 
                return $personell_name;
            }else return FALSE;
        }
        
        function checkNr($encounter_op_nr='',$job_function_title='',$status=''){
            global $db;            
            $this->sql="SELECT tb.personell_nr
                        FROM $this->tb_personell_op AS tb,
                             $this->tb_personell AS ps,
                             $this->tb_person AS pr
                        WHERE tb.encounter_op_nr='$encounter_op_nr'
                              AND tb.status='$status'
                              AND tb.personell_nr=ps.nr
                              AND ps.job_function_title='$job_function_title'
                              AND ps.pid=pr.pid";
            if($this->result=$db->Execute($this->sql)){
                $personell_nr=array();
                $i=0;
                while($this->res['gim']=$this->result->FetchRow()){                   
                    $personell_nr[$i]=$this->res['gim']['personell_nr'].'\x';
                    $i++;
                } 
                return $personell_nr;
            }else return FALSE;
        }
        
        function insertPersonell($operator='',$encounter_op_nr='',$flag=''){
            global $db;  
            if($flag!=''){
                $name=explode(',', $operator);
                $this->sql="SELECT ps.nr 
                            FROM $this->tb_person AS pr, $this->tb_personell AS ps 
                            WHERE pr.name_last='$name[0]' AND pr.name_first='$name[1]' AND pr.pid=ps.pid";
                if($this->res['gim']=$db->Execute($this->sql)){
                    $this->result=$this->res['gim']->FetchRow();
                    $this->sql="INSERT INTO $this->tb_personell_op (personell_nr,encounter_op_nr,status,history) 
                                    VALUES ('$result[nr]','$encounter_op_nr','chosed','Create: ".date('Y-m-d h:i:s')." by $_SESSION[sess_user_name]\n')";
                    if($query=$db->Execute($this->sql)){
                        return true;
                    }else return false;
                }else return false;
            }else{
                $this->sql="INSERT INTO $this->tb_personell_op (personell_nr,encounter_op_nr,status,history) 
                                VALUES ('$operator','$encounter_op_nr','chosed','Create: ".date('Y-m-d h:i:s')." by $_SESSION[sess_user_name]\n')";
                if($query=$db->Execute($this->sql)){
                    return true;
                }else return false;
            }
            
        }
        
        function getStatus($personell_nr='',$date_chose='',$time='',$op_time='',$nr=''){
            global $db;
            $this->result='';
            $this->personell_data='';
            if($time){
                if($nr!=''){
                    $this->sql="SELECT DISTINCT(res.date_request) AS date_request
                                FROM $this->tb AS tb, $this->tb_test AS res, $this->tb_personell_op AS per 
                                WHERE per.personell_nr='$personell_nr' AND per.encounter_op_nr=tb.nr AND tb.batch_nr=res.batch_nr";
                    if($this->result=$db->Execute($this->sql)) {
                        $this->personell_data=$this->result->FetchRow();
                        $date=$this->personell_data[date_request];
                    }else return false;
                }
                $this->sql="SELECT MAX(tb.doc_time) AS doc_time FROM $this->tb AS tb,$this->tb_test AS res
                            WHERE res.date_request='$date' AND res.batch_nr=tb.batch_nr";                
                if($this->result=$db->Execute($this->sql)) {
                    if($count=$this->result->RecordCount()){
                        $this->personell_data=$this->result->FetchRow();
                        return $this->personell_data;
                    }
                } else {return FALSE;}
            }else{
                $this->sql="SELECT status FROM $this->tb_personell_op WHERE personell_nr=$personell_nr AND encounter_op_nr=$nr";
                if($this->result=$db->Execute($this->sql)) {
                    if($status=$this->result->FetchRow()){
                        return $status;                        
                    }else {return FALSE;}
                } else {return FALSE;}
            }            
        }
        
        function checkRoom($batch_nr='',$room='',$date='',$time=''){
            global $db;
            $this->sql="SELECT tb.doc_time AS time FROM $this->tb AS tb, $this->tb_test AS res WHERE tb.op_room=$room AND tb.batch_nr=res.batch_nr AND res.date_request='$date'";
//            echo $this->sql;
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        
        function test_ekip($batch_nr=''){
            global $db;
            $this->result='';
            if(!$batch_nr){
                $this->sql="SELECT tr.batch_nr,tr.encounter_nr,tr.send_date,tr.date_request 
                            FROM $this->tb_test AS tr, $this->tb AS en
                            WHERE (tr.status='draff' OR tr.status='received') AND en.status='pending' AND tr.batch_nr=en.batch_nr ORDER BY  date_request DESC";
            }else{
                $this->sql="SELECT tr.batch_nr,tr.encounter_nr,tr.send_date,tr.date_request 
                            FROM $this->tb_test AS tr, $this->tb AS en
                            WHERE tr.status='draff' AND tr.batch_nr=$batch_nr AND tr.batch_nr=en.batch_nr AND en.status='pending' ORDER BY  send_date DESC";
            }
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        
        function serch_pid($enc_nr){
            global $db;
            $this->sql="SELECT pid FROM $this->tb_encounter WHERE encounter_nr=$enc_nr";
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        
        function list_request(){
            global $db;
            $this->sql="SELECT request.batch_nr,request.encounter_nr,request.send_date,request.date_request 
                            FROM $this->tb_test as request, $this->tb_encounter AS encounter
                            WHERE (request.status='pending' OR request.status='received')
                                    AND request.encounter_nr=encounter.encounter_nr
                                    AND encounter.discharged_type=0
                            ORDER BY  date_request DESC";
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        
        function personell_nr($user){
            global $db;
            $this->sql="SELECT personell_nr FROM $this->tb_user WHERE name='$user'";
            if($this->result=$db->Execute($this->sql)) {
                $select=$this->result->FetchRow();
                return $select;
            }else return false;
        }
        
        function info_for_personell($nr,$date){
            global $db;
            if($date==""){
                $this->sql="SELECT tb.op_room, tb.doc_time, tb.batch_nr, tr.date_request
                        FROM $this->tb AS tb, $this->tb_test AS tr 
                        WHERE tb.nr='$nr' AND tb.batch_nr=tr.batch_nr";
            }else{
                $this->sql="SELECT tb.op_room, tb.doc_time, tb.batch_nr, tr.date_request
                        FROM $this->tb AS tb, $this->tb_test AS tr 
                        WHERE tb.nr='$nr' AND tb.batch_nr=tr.batch_nr AND tr.date_request='$date'";
            }            
            if($this->result=$db->Execute($this->sql)) {
                $select=$this->result->FetchRow();
                return $select;
            }else return false;
        }
        
        function get_personell_op($x,$sonews){
            global $db;
            if($x=="" && $sonews==""){
                $this->sql="SELECT DISTINCT(pno.personell_nr) AS personell_nr
                    FROM $this->tb_personell_op AS pno
                    LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                    LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                    LEFT JOIN $this->tb_personell AS pn ON pn.nr=pno.personell_nr 
                    LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                    ";
            }else{
                $this->sql="SELECT DISTINCT(pno.personell_nr) AS personell_nr
                    FROM $this->tb_personell_op AS pno
                    LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                    LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                    LEFT JOIN $this->tb_personell AS pn ON pn.nr=pno.personell_nr 
                    LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                    LIMIT $x,$sonews";
            }
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        
        function get_info($personell_nr){
            global $db;
            $this->sql="SELECT ps.name_last,ps.name_first,pn.job_function_title
                    FROM $this->tb_personell AS pn 
                    LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                    WHERE pn.nr='$personell_nr'";
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        } 
        //lấy nhân viên tham gia mổ(chính/phụ) theo tháng/năm
        function list_doctor_op($personell_nr,$level,$date){
            global $db;
            if($date==""){
                $this->sql="SELECT yc.date_request,yc.level_method,pno.personell_nr
                    FROM $this->tb_personell_op AS pno
                    LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                    LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                    LEFT JOIN $this->tb_personell AS pn ON pn.nr=pno.personell_nr 
                    LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                    WHERE pno.personell_nr='$personell_nr'";
            }else{
            //Đếm số lần mổ trong ngày
                $this->sql="SELECT pno.personell_nr
                    FROM $this->tb_personell_op AS pno
                    LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                    LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                    WHERE pno.personell_nr='$personell_nr' AND yc.date_request='$date' AND yc.level_method='$level'
                    ";
            }                                   
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        function list_doctor_op_flag($personell_nr,$level){
            global $db;
            if($level==""){
                //Đếm tất cả các ca mổ trong tháng
                $this->sql="SELECT yc.date_request,yc.level_method,pno.personell_nr
                    FROM $this->tb_personell_op AS pno
                    LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                    LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                    LEFT JOIN $this->tb_personell AS pn ON pn.nr=pno.personell_nr 
                    LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                    WHERE pno.personell_nr='$personell_nr'";
            }else{
                $this->sql="SELECT COUNT(yc.level_method) AS level_method
                    FROM $this->tb_personell_op AS pno
                    LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                    LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                    LEFT JOIN $this->tb_personell AS pn ON pn.nr=pno.personell_nr 
                    LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                    WHERE pno.personell_nr='$personell_nr' AND yc.level_method='$level'";
            }
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }        
        
        //phân trang chấm công
        function list_doctor_op_page($x,$sonews){
            global $db;
            $this->sql="SELECT pno.personell_nr,yc.date_request,yc.level_method,pn.job_function_title,ps.name_last,ps.name_first
                        FROM $this->tb_personell_op AS pno
                        LEFT JOIN $this->tb AS tb ON tb.nr=pno.encounter_op_nr
                        LEFT JOIN $this->tb_test AS yc ON yc.batch_nr=tb.batch_nr
                        LEFT JOIN $this->tb_personell AS pn ON pn.nr=pno.personell_nr 
                        LEFT JOIN $this->tb_person AS ps ON ps.pid=pn.pid
                        LIMIT $x,$sonews";
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
        
        function UpdateTestRequest($batch_nr=''){
            global $db;
            $this->sql="UPDATE care_test_request_or
                            SET status = 'draff',
                                history=".$this->ConcatHistory('Done: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'].'\n').",
                                modify_id = '" . $_SESSION['sess_user_name'] . "'
                            WHERE batch_nr = '" . $batch_nr . "'";
            if($this->result=$db->Execute($this->sql)) {
                return $this->result;
            }else return false;
        }
    }
?>