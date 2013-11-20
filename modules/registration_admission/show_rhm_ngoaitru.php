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
    require_once($root_path.'include/care_api_classes/class_khambenh.php');
    $obj=new Khambenh();
	$obj->KhambenhRHMNgoaitru();
    # Point the core data to pregnancy
     
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($encounter_nr);
    $enc_obj->loadEncounterData();
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
    if(!isset($allow_update)) $allow_update=FALSE;

    if(!isset($mode)){
            $mode='show';
    }elseif($mode=='newdata') {
	
	include_once($root_path.'include/core/inc_date_format_functions.php');
	$saved=FALSE;

	# Prepare additional info saving
	$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	$_POST['tiensubenh']=$_POST['tsb1'].'_'.$_POST['tsb2'].'_'.$_POST['tsb3'].'_'.$_POST['tsb4'].'_'.$_POST['tsb5'].'_'.$_POST['tsb6'].'_'.$_POST['tsb7']
							.'_'.$_POST['tsb8'].'_'.$_POST['tsb9'];
							//echo $_POST['tiensubenh'];
	$_POST['hamtren_trai']=$_POST['htt1'].'_'.$_POST['htt2'].'_'.$_POST['htt3'].'_'.$_POST['htt4']
							.'_'.$_POST['htt5'].'_'.$_POST['htt6'].'_'.$_POST['htt7'].'_'.$_POST['htt8'];
	$_POST['hamtren_phai']=$_POST['htp1'].'_'.$_POST['htp2'].'_'.$_POST['htp3'].'_'.$_POST['htp4']
							.'_'.$_POST['htp5'].'_'.$_POST['htp6'].'_'.$_POST['htp7'].'_'.$_POST['htp8'];
	$_POST['hamduoi_trai']=$_POST['hdt1'].'_'.$_POST['hdt2'].'_'.$_POST['hdt3'].'_'.$_POST['hdt4']
							.'_'.$_POST['hdt5'].'_'.$_POST['hdt6'].'_'.$_POST['hdt7'].'_'.$_POST['hdt8'];
	$_POST['hamduoi_phai']=$_POST['hdp1'].'_'.$_POST['hdp2'].'_'.$_POST['hdp3'].'_'.$_POST['hdp4']
							.'_'.$_POST['hdp5'].'_'.$_POST['hdp6'].'_'.$_POST['hdp7'].'_'.$_POST['hdp8'];
	$_POST['khdieutri']=$_POST['khdt1'].'_'.$_POST['khdt2'].'_'.$_POST['khdt3'].'_'.$_POST['khdt4'].'_'.$_POST['khdt5'].'_'.$_POST['khdt6']
						.'_'.$_POST['khdt7'].'_'.$_POST['khdt8'].'_'.$_POST['khdt9'].'_'.$_POST['khdt10'];
	//var_dump($_POST);
	if($allow_update){
            $_POST['modify_id']=$_SESSION['sess_user_name'];
            $_POST['modify_time']=date('YmdHis');
		$obj->setWhereCondition('encounter_nr='.$_POST['encounter_nr']);
		$obj->setDataArray($_POST);
//                foreach($_POST AS $k=>$v){
//                    echo $k.'->'.$v.'<br>';
//                }

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
    require('./include/init_show.php');
    if(empty($current_encounter)&&!empty($_SESSION['sess_en'])){
        $current_encounter=$_SESSION['sess_en'];
    }elseif($current_encounter) {
        $_SESSION['sess_en']=$current_encounter;
    }
    if($_SESSION['sess_en']){
        $pregs=&$obj->getKhambenhRHMNgoaitru($_SESSION['sess_en']);
    }
    if($pregs){
        $rows=$pregs->RecordCount();
    }    
  

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 
	$page_title="Khám Ngoại trú Răng - Hàm - Mặt";
    /* Load GUI page */
    require('./gui_bridge/default/gui_show.php');
?>
