<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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
                $data_array=" encounter_nr=$encounter_nr ,";
                $data_array.=" date='$date' ,";
                $data_array.=" time='$time' ,";
                $data_array.=" history='Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n"."' ,";
                $data_array.=" modify_id='".$_SESSION['sess_user_name']."' ,";
                $data_array.=" modify_time='".date('YmdHis')."' ,";
                if($_POST['hinhthuc'] || $_POST['hinhthuc_text']){
                    $data_array.=" notes='".$_POST['hinhthuc'].'@'.$_POST['hinhthuc_text']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,$nr);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['vanchuyen'] && $_POST['soxe']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['vanchuyen'].'@'.$_POST['soxe']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+1));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['nguoiduaden']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['nguoiduaden']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+2));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['tuoindden']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['tuoindden']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+3));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }  
                if(isset($_POST['gioitinhndden']) || $_POST['gioitinhndden']!=''){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['gioitinhndden']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+4));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['diachindden']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['diachindden']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+5));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['lienhendden']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['lienhendden']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+6));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ppdttruoc']){;
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['ppdttruoc']."@".$_POST['tinhtrang']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+7));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['giotv']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".convertTimeToStandard($_POST['giotv'])."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+8));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['trigiac']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['trigiac']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+9));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['daniem']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['daniem']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+10));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['dongtu']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['dongtu']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+11));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['timmach']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['timmach']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+12));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['hohap']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['hohap']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+13));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['benhchinh']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['benhchinh']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+14));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['capcuu']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['capcuu']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+15));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['canthiep']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['canthiep']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+16));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['nguoixin']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['nguoixin']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+17));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['tuoinxin']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['tuoinxin']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+18));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if(isset($_POST['gioitinhnxin'])){
                    $data_array['notes']=$_POST['gioitinhnxin'];
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+19));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['lienhenxin']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['lienhenxin']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+20));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['benhsu']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".$_POST['benhsu']."@".$_POST['taisan']."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+21));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ngaytv']){
                    $array=explode("notes=",$data_array);
                    $data_array=$array[0]." notes='".@formatDate2STD($_POST['ngaytv'],$date_format)."' ";
                    $enc_obj->UpdateDischargeNotesFromArray($data_array,($nr+22));
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                $saved=1;
	}else{
                $data_array['encounter_nr']=$encounter_nr;
                $data_array['date']=$date;
                $data_array['time']=$time;
                $data_array['create_id']=$_SESSION['sess_user_name'];
                $date_array['history']="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
                $date_array['create_time']=date('YmdHis');
                if($_POST['hinhthuc'] || $_POST['hinhthuc_text']){
                    $data_array['notes']=$_POST['hinhthuc'].'@'.$_POST['hinhthuc_text'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,68);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['vanchuyen'] && $_POST['soxe']){
                    $data_array['notes']=$_POST['vanchuyen'].'@'.$_POST['soxe'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,69);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['nguoiduaden']){
                    $data_array['notes']=$_POST['nguoiduaden'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,70);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['tuoindden']){
                    $data_array['notes']=$_POST['tuoindden'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,71);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if(isset($_POST['gioitinhndden'])){
                    $data_array['notes']=$_POST['gioitinhndden'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,72);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['diachindden']){
                    $data_array['notes']=$_POST['diachindden'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,73);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['lienhendden']){
                    $data_array['notes']=$_POST['lienhendden'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,74);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['ppdttruoc']){
                    $data_array['notes']=$_POST['ppdttruoc'].'@'.$_POST['tinhtrang'];
                }else{
                        $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,75);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['giotv']){
                    $data_array['notes']=convertTimeToStandard($_POST['giotv']);
                    $enc_obj->saveDischargeNotesFromArray1($data_array,76);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['trigiac']){
                    $data_array['notes']=$_POST['trigiac'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,77);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['daniem']){
                    $data_array['notes']=$_POST['daniem'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,78);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['dongtu']){
                    $data_array['notes']=$_POST['dongtu'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,79);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['timmach']){
                    $data_array['notes']=$_POST['timmach'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,80);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['hohap']){
                    $data_array['notes']=$_POST['hohap'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,81);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['benhchinh']){
                    $data_array['notes']=$_POST['benhchinh'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,82);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['capcuu']){
                    $data_array['notes']=$_POST['capcuu'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,83);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['canthiep']){
                    $data_array['notes']=$_POST['canthiep'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,84);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['nguoixin']){
                    $data_array['notes']=$_POST['nguoixin'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,85);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['tuoinxin']){
                    $data_array['notes']=$_POST['tuoinxin'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,86);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if(isset($_POST['gioitinhnxin'])){
                    $data_array['notes']=$_POST['gioitinhnxin'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,87);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['lienhenxin']){
                    $data_array['notes']=$_POST['lienhenxin'];
                }else{
                    $data_array['notes']='';
                }
                $enc_obj->saveDischargeNotesFromArray1($data_array,88);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                if($_POST['benhsu']){
                    $data_array['notes']=$_POST['benhsu'].'@'.$_POST['taisan'];
                    $enc_obj->saveDischargeNotesFromArray1($data_array,89);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                }
                if($_POST['ngaytv']){
                    $data_array['notes']=@formatDate2STD($_POST['ngaytv'],$date_format);
                    $enc_obj->saveDischargeNotesFromArray1($data_array,90);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
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
        $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$current_encounter' AND notes.type_nr=types.nr AND types.sort_nr=33", "ORDER BY nr ASC");
        if($pregs){
            $rows=$pregs->RecordCount();
        }
    }      

    $page_title=$LDInfoGiayto.'::'.$LDTuvongtruoc;
    $subtitle=$LDTuvongtruoc;
    $_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&encounter_nr=".$_SESSION['sess_en']."&target=search";
    
    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_notes.php');
?>
