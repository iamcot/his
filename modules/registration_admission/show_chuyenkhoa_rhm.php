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
    $obj=new Khambenh;
	$obj->KhamRHM();
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
//echo $mode;
	# Prepare additional info saving
	$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	
	//echo $_POST['tienthai1'];
	//echo $_POST['ditatbamsinh'];
	//echo $_POST['date'];
	if($allow_update){
            $_POST['modify_id']=$_SESSION['sess_user_name'];
            $_POST['modify_time']=date('YmdHis');
		$obj->setWhereCondition('encounter_nr='.$_POST['encounter_nr']);
		$obj->setDataArray($_POST);
//                foreach($_POST AS $k=>$v){
//                    echo $k.'->'.$v.'<br>';
//                }
//var_dump($_POST);
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
        $pregs=&$obj->getKhamRHM($_SESSION['sess_en']);
    }
    if($pregs){
        $rows=$pregs->RecordCount();
    }    
  

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 
	$page_title="Khám Răng - Hàm - Mặt";
    /* Load GUI page */
    require('./gui_bridge/default/gui_show.php');
?>
