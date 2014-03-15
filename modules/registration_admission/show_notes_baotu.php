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
                if($_POST['cmnd']){
                    $data_array.=" notes='".$_POST['cmnd']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,$nr);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ngaycap']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".@formatDate2STD($_POST['ngaycap'],$date_format)."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+1));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['noicap']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['noicap']."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+2));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ngaytv'] && $_POST['giotv']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".@formatDate2STD($_POST['ngaytv'],$date_format).' '.convertTimeToStandard($_POST['giotv'])."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+3));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['nguyennhan']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['nguyennhan']."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+4));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['gui']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['gui']."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+5));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['noitv'] || $_POST['bvtv']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['noitv'].'@'.$_POST['bvtv']."' ";           
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+6));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
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
                if($_POST['cmnd']){
                    $data_array['notes']=$_POST['cmnd'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,50);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ngaycap']){
                    $data_array['notes']=@formatDate2STD($_POST['ngaycap'],$date_format);
                    $enc_obj->saveDischargeNotesFromArray1($data_array,51);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['noicap']){
                    $data_array['notes']=$_POST['noicap'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,52);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ngaytv'] && $_POST['giotv']){
                    $data_array['notes']=@formatDate2STD($_POST['ngaytv'],$date_format).' '.convertTimeToStandard($_POST['giotv']);
                    $enc_obj->saveDischargeNotesFromArray1($data_array,53);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['nguyennhan']){
                    $data_array['notes']=$_POST['nguyennhan'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,55);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['gui']){
                    $data_array['notes']=$_POST['gui'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,56);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['noitv'] || $_POST['bvtv']){
                    $data_array['notes']=$_POST['noitv'].'@'.$_POST['bvtv'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,57);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                $saved=1;
	}	
	///////////////////////
	if($saved){
		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']."&pn=".$_SESSION['sess_en']."&time=".date("h:i:s"));
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
        $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$current_encounter' AND notes.type_nr=types.nr AND types.sort_nr=34", "ORDER BY nr ASC");
        if($pregs){
            $rows=$pregs->RecordCount();
        }
    }      

    $page_title=$LDInfoGiayto.'::Giấy Báo Tử';
    $subtitle='Giấy Báo Tử';
    $_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&encounter_nr=".$_SESSION['sess_en']."&target=search";
    
    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_notes.php');
?>
