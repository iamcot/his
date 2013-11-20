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
    $obj->useplacenta();// gán tên bảng là care_encounter_pregnancy
    # Create measurement object
    require_once($root_path.'include/care_api_classes/class_measurement.php');
    $msr=new Measurement;
    //ghi log
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
	
        if($_POST['date']) $_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	if($_POST['time']) $_POST['time']=@convertTimeToStandard($_POST['time']);
        if(empty($_POST['raucuonco']))
            $_POST['raucuonco']=0;
        if(empty($_POST['kstc']))
            $_POST['kstc']=0;
        if(empty($_POST['chaymau']))
            $_POST['chaymau']=0;
	//if(empty($_POST['blood_loss'])) $_POST['blood_loss_unit']=0;
	if($allow_update){
            $_POST['modify_id']=$_SESSION['sess_user_name'];
            $_POST['modify_time']=date('YmdHis');
		$obj->setWhereCondition('nr='.$_POST['rec_nr']);
                $array=array('nr','pid','encounter_nr','rau','date', 'time', 'cachsorau', 'matmang', 'matmui', 'banhrau', 'cannang', 'raucuonco', 'kstc', 'crdai', 'chaymau', 'matmau', 'donvimau', 'xuly', 'status', 'history', 'modify_id', 'modify_time', 'create_id', 'create_time');
                $obj->setRefArray($array);
		$obj->setDataArray($_POST);
                if($obj->updateDataFromInternalArray($_POST['rec_nr'])) {
                    $saved=true;
                }else{
                    echo $obj->getLastQuery();
                    echo "<br>$LDDbNoUpdate";
                }	
                $result=&$obj->BirthDetails($_SESSION['sess_pid']);
                $rows=$obj->LastRecordCount();
                $i=$_POST['nr_of_fetuses']+1;
                while($i<=$rows){
                   $obj->deleteBirthDetails1($_SESSION['sess_pid'],$_SESSION['sess_en'],$i);
                   $i++; 
                }
	}else{
		# Deactivate the old record first if exists
		if(isset($rec_nr) && $rec_nr){
			$obj->deactivatePregnancy($_POST['rec_nr']);
		}
		$_POST['history']="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
		$_POST['create_id']=$_SESSION['sess_user_name'];
		$_POST['create_time']=date('YmdHis'); # Create own timestamp for cross db compatibility
                $array=array('nr','pid','encounter_nr','rau','date', 'time', 'cachsorau', 'matmang', 'matmui', 'banhrau', 'cannang', 'raucuonco', 'kstc', 'crdai', 'chaymau', 'matmau', 'donvimau', 'xuly', 'status', 'history', 'modify_id', 'modify_time', 'create_id', 'create_time');
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
    
    if(isset($current_encounter) && $current_encounter) { 
	$parent_admit=true; 
	$is_discharged=false;
	$_SESSION['sess_en'] = $current_encounter;
    }
    # Get all birth details data of the person
    if($current_encounter){
        $pregs=&$obj->Placenta1($_SESSION['sess_pid'], $current_encounter);
    }else{
        $pregs=&$obj->Placenta2($_SESSION['sess_pid']);
    }

    $rows=$obj->LastRecordCount();

    $page_title=$SorauInfo;
    $subtitle=$SorauInfo;
    $_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&encounter_nr=".$_SESSION['sess_en']."&target=search";

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_show.php');
?>
