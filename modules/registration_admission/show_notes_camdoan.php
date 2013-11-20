<?php
    //error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
    * GNU General Public License
    * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
    * elpidio@care2x.org, 
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    $thisfile=basename(__FILE__);
    require_once($root_path.'include/care_api_classes/class_notes.php');	
    $obj=new Notes();
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter;    
    require_once($root_path.'include/core/inc_date_format_functions.php');
	//Ghi log
    require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
    $user = & new Access();
	////////////////////////
	
    if(!isset($allow_update)) $allow_update=FALSE;
    $saved=FALSE;
    if(!isset($mode)){
            $mode='show';
    }elseif($mode=='newdata') {
        if($allow_update){
                $date=(empty($x_date))?date('Y-m-d'):formatDate2STD($x_date,$date_format);
		$time=(empty($x_time))?date('H:i:s'):convertTimeToStandard($x_time);
                $data_array=" encounter_nr=$encounter_nr ,";
                $data_array.=" date='$date' ,";
                $data_array.=" time='$time' ,";
                $data_array.=" history='Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n"."' ,";
                $data_array.=" modify_id='".$_SESSION['sess_user_name']."' ,";
                $data_array.=" modify_time='".date('YmdHis')."' ,";
                if($_POST['nguoicamdoan']){
                    $data_array.=" notes='".$_POST['nguoicamdoan']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,$nr);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                if($_POST['tuoinguoicamdoan']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['tuoinguoicamdoan']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+1));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                if($_POST['quanhevoinguoibenh']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['quanhevoinguoibenh']."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+2));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                if($_POST['text_camdoan']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['text_camdoan']."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+3));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                $saved=1;
	}else{
                $date=(empty($x_date))?date('Y-m-d'):formatDate2STD($x_date,$date_format);
		$time=(empty($x_time))?date('H:i:s'):convertTimeToStandard($x_time);
                $data_array['encounter_nr']=$encounter_nr;
                $data_array['date']=$date;
                $data_array['time']=$time;
                $data_array['create_id']=$_SESSION['sess_user_name'];
                $date_array['history']="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
                $date_array['create_time']=date('YmdHis');
                if($_POST['nguoicamdoan']){
                    $data_array['notes']=$_POST['nguoicamdoan'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,29);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                if($_POST['tuoinguoicamdoan']){
                    $data_array['notes']=$_POST['tuoinguoicamdoan'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,30);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                if($_POST['quanhevoinguoibenh']){
                    $data_array['notes']=$_POST['quanhevoinguoibenh'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,31);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                if($_POST['text_camdoan']){
                    $data_array['notes']=$_POST['text_camdoan'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,32);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }else{
                    echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }
                $saved=1;
	}	
	///////////////////////
	if($saved){
		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']."&pn=".$_SESSION['sess_en']."&time=".strtotime(date("h:i:s")));
		exit;
	}
    } 
    
    $lang_tables[]='emr.php';
    require('./include/init_show.php');
    if(empty($current_encounter)&&!empty($_SESSION['sess_en'])){
        $current_encounter=$_SESSION['sess_en'];
    }elseif($current_encounter) {
        $_SESSION['sess_en']=$current_encounter;
    }
    # Get all birth details data of the person
    if($current_encounter){
        $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$current_encounter' AND notes.type_nr=types.nr AND types.sort_nr=29", "ORDER BY nr ASC");
        if($pregs){
            $rows=$pregs->RecordCount();
        }
    }      

    $page_title=$LDInfoGiayto.'::'.$LDGiayCamdoan;
    $subtitle=$LDGiayCamdoan;
    $_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&encounter_nr=".$_SESSION['sess_en']."&target=search";
    
    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_notes.php');
?>
