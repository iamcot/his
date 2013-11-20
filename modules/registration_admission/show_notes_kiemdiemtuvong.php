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
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
    $user = & new Access();
    $obj=new Encounter($encounter_nr);
    $obj->Kiemtuvong();

    if(!isset($allow_update)) $allow_update=FALSE;

    if(!isset($mode)){
            $mode='show';
    }elseif($mode=='newdata') {
	
	include_once($root_path.'include/core/inc_date_format_functions.php');
	$saved=FALSE;
	# Prepare additional info saving	
        if(empty($_POST['date_tuvong'])) $_POST['date_tuvong']=date('Y-m-d');
		else $_POST['date_tuvong']=@formatDate2STD($_POST['date_tuvong'],$date_format);
        if(empty($_POST['date_kiemtuvong'])) $_POST['date_kiemtuvong']=date('Y-m-d');
		else $_POST['date_kiemtuvong']=@formatDate2STD($_POST['date_kiemtuvong'],$date_format);
        if(empty($_POST['time_tuvong'])) $_POST['time_tuvong']=date('H:i:s');
		else $_POST['time_tuvong']=@convertTimeToStandard($_POST['time_tuvong']);
        if(empty($_POST['time_kiemtuvong'])) $_POST['time_kiemtuvong']=date('H:i:s');
		else $_POST['time_kiemtuvong']=@convertTimeToStandard($_POST['time_kiemtuvong']);
        $_POST['thanhvien_notes']=trim($_POST['thanhvien_notes']);
	if($allow_update){
            $_POST['modify_id']=$_SESSION['sess_user_name'];
            $_POST['modify_time']=date('YmdHis');
		$obj->setWhereCondition('encounter_nr='.$_POST['encounter_nr'].' AND nr='.$nr);
		$obj->setDataArray($_POST);
                if($obj->updateDataFromInternalArray($_POST['encounter_nr'])) {
                    $saved=true;
                }else{
                    echo $obj->getLastQuery();
                    echo "<br>$LDDbNoUpdate";
                }
	}else{
		$_POST['history']="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
                $_POST['create_id']=$_SESSION['sess_user_name'];
		$_POST['create_time']=date('YmdHis'); # Create own timestamp for cross db compatibility           
//                foreach ($_POST AS $k=>$v)
//                    echo $k.'=>'.$v.'<br>';
		$obj->setDataArray($_POST);
                if($obj->insertDataFromInternalArray()) {
                        $saved=true;
                }else{
                        echo $obj->getLastQuery()."<br>$LDDbNoSave";
                }		
	}
        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
	if($saved){
		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']."&time=".strtotime(date("h:i:s")));
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
        $pregs=&$obj->getKiemtuvong($_SESSION['sess_en'],'_ENC', "$nr");
    }else{
        $pregs=&$obj->getKiemtuvong($_SESSION['sess_pid'],'_REG', "$nr");
    }
    $rows=$obj->LastRecordCount();   

    $page_title=$LDOther1;
    $subtitle=$LDOther1;
    $_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&encounter_nr=".$_SESSION['sess_en']."&target=search";
    
    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_notes.php');
?>
