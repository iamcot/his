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
    require_once($root_path.'include/care_api_classes/class_obstetrics.php');
    $obj=new Obstetrics;
    # Point the core data to pregnancy
    $obj->useHistory_Phu();// gán tên bảng là care_encounter_pregnancy
    # Create measurement object
    require_once($root_path.'include/care_api_classes/class_measurement.php');
    $msr=new Measurement;
    //log
    require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
    $user = & new Access();

    if(!isset($allow_update)) $allow_update=FALSE;

    if(!isset($mode)){
            $mode='show';
    }elseif($mode=='newdata') {
	
	include_once($root_path.'include/core/inc_date_format_functions.php');
	$saved=FALSE;

	# Prepare additional info saving
	# Create own timestamp for cross db compatibility
	if(empty($_POST['kinhcuoitu'])) $_POST['kinhcuoitu']=date('Y-m-d');
		else $_POST['kinhcuoitu']=@formatDate2STD($_POST['kinhcuoitu'],$date_format);
        if(empty($_POST['daubung']))
            $_POST['daubung']=0;
	if($allow_update){
                $_POST['modify_id']=$_SESSION['sess_user_name'];
                $_POST['modify_time']=date('YmdHis'); 
		$obj->setWhereCondition('nr='.$_POST['rec_nr']);
                $array=array('nr','pid','encounter_nr','batdauthaykinh','tuoithaykinh','tinhchatkinh','luongkinh','chuki','songaykinh','kinhcuoitu','daubung','time','namlaychong','tuoilaychong','namhetkinh','tuoihetkinh','benhphukhoa', 'tienthai','status','history', 'create_id', 'create_time', 'modify_id', 'modify_time');
                $obj->setRefArray($array);
		$obj->setDataArray($_POST);
//                foreach($_POST AS $k=>$v){
//                    echo $k.'=>'.$v.'<br>';
//                }
                if($obj->updateDataFromInternalArray($_POST['rec_nr'])) {
                    $saved=true;
                }else{
                    echo $obj->getLastQuery();
                    echo "<br>$LDDbNoUpdate";
                }
	}else{
		# Deactivate the old record first if exists
		if(isset($rec_nr) && $rec_nr){
			$obj->deactivateHistory($_POST['rec_nr']);
		}
		$_POST['history']="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
		$_POST['create_id']=$_SESSION['sess_user_name'];
		$_POST['create_time']=date('YmdHis'); # Create own timestamp for cross db compatibility
                $array=array('nr','pid','encounter_nr','batdauthaykinh','tuoithaykinh','tinhchatkinh','luongkinh','chuki','songaykinh','kinhcuoitu','daubung','time','namlaychong','tuoilaychong','namhetkinh','tuoihetkinh','benhphukhoa', 'tienthai','status','history', 'create_id', 'create_time', 'modify_id', 'modify_time');
                $obj->setRefArray($array);
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
    $lang_tables[]='obstetrics.php';
    require('./include/init_show.php');

    if($parent_admit){
	# Get the pregnancy data of this encounter
	$pregs=&$obj->history_Phu($_SESSION['sess_en'],'_ENC');
    }else{
	# Get all pregnancies  of this person
	$pregs=&$obj->history_Phu($_SESSION['sess_pid'],'_REG');        
    }
//    echo $obj->getLastQuery();
    $rows=$obj->LastRecordCount();

    $page_title=$LDPregQuestion;
    $subtitle=$LDPregQuestion;
    $_SESSION['sess_file_return']="show_obstetrics_general.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&encounter_nr=".$_SESSION['sess_en']."&target=search";

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_show.php');
?>
