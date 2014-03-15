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
    require_once($root_path.'include/care_api_classes/class_khambenh_yhct.php');
    $obj=new KhambenhYHCT;
	require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
    # Point the core data to pregnancy
     
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($encounter_nr);
    $enc_obj->loadEncounterData();

    if(!isset($allow_update)) $allow_update=FALSE;

    if(!isset($mode)){
            $mode='show';
    }elseif($mode=='newdata') {
	
	include_once($root_path.'include/core/inc_date_format_functions.php');
	$saved=FALSE;
//echo $mode;
	# Prepare additional info saving
	$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	$_POST['hinhthai']=$_POST['hinhthai1'].'_'.$_POST['hinhthai2'].'_'.$_POST['hinhthai3'].'_'.$_POST['hinhthai4'].'_'.$_POST['hinhthai5'].'_'.$_POST['hinhthai6'];	
	$_POST['thansac']=$_POST['sac1'].'_'.$_POST['sac2'].'_'.$_POST['sac3'].'_'.$_POST['sac4'].'_'.$_POST['sac5']
	.'_'.$_POST['sac6'].'_'.$_POST['sac7'].'_'.$_POST['trach1'].'_'.$_POST['trach2'].'_'.$_POST['trach3'];
	$_POST['luoi_notes']=$_POST['chatluoi_notes'].'_'.$_POST['sacluoi_notes'].'_'.$_POST['reuluoi_notes'];
	$_POST['amthanh_notes']=$_POST['tiengnoi_notes'].'_'.$_POST['hoitho_notes'].'_'.$_POST['ho_notes'].'_'.$_POST['onac_notes'];
	$_POST['mui_notes']=$_POST['chatthai_notes'].'_'.$_POST['hoinguoi_notes'];
	$_POST['hannhiet_notes']=$_POST['hannhietbl_notes'].'_'.$_POST['benhtd_notes'];
	$_POST['kn_sd_notes']=$_POST['kn_notes'].'_'.$_POST['sd_notes'];
	$_POST['luoi']=$_POST['chatluoi1'].'_'.$_POST['chatluoi2'].'_'.$_POST['chatluoi3'].'_'.$_POST['chatluoi4'].'_'.$_POST['chatluoi5']
	.'_'.$_POST['chatluoi6'].'_'.$_POST['chatluoi7'].'_'.$_POST['chatluoi8'].'_'.$_POST['chatluoi9']
	.'_'.$_POST['sacluoi1'].'_'.$_POST['sacluoi2'].'_'.$_POST['sacluoi3'].'_'.$_POST['sacluoi4']
	.'_'.$_POST['sacluoi5'].'_'.$_POST['sacluoi6'].'_'.$_POST['sacluoi7'].'_'.$_POST['sacluoi8']
	.'_'.$_POST['sacluoi9'].'_'.$_POST['reuluoi1'].'_'.$_POST['reuluoi2'].'_'.$_POST['reuluoi3']
	.'_'.$_POST['reuluoi4'].'_'.$_POST['reuluoi5'].'_'.$_POST['reuluoi6'].'_'.$_POST['reuluoi7']
	.'_'.$_POST['reuluoi8'].'_'.$_POST['reuluoi9'].'_'.$_POST['reuluoi10'].'_'.$_POST['reuluoi11'].'_'.$_POST['reuluoi12'];
	$_POST['amthanh']=$_POST['tiengnoi1'].'_'.$_POST['tiengnoi2'].'_'.$_POST['tiengnoi3'].'_'.$_POST['tiengnoi4'].'_'.$_POST['tiengnoi5']
	.'_'.$_POST['tiengnoi6'].'_'.$_POST['tiengnoi7'].'_'.$_POST['tiengnoi8'].'_'.$_POST['tiengnoi9'].'_'.$_POST['hoitho1']
	.'_'.$_POST['hoitho2'].'_'.$_POST['hoitho3'].'_'.$_POST['hoitho4'].'_'.$_POST['hoitho5'].'_'.$_POST['hoitho6'].'_'.$_POST['hoitho7'].'_'.$_POST['hoitho8']
	.'_'.$_POST['hoitho9'].'_'.$_POST['hoitho10'].'_'.$_POST['ho1'].'_'.$_POST['ho2'].'_'.$_POST['ho3'].'_'.$_POST['ho4'].'_'.$_POST['ho5'].'_'.$_POST['ho6'].'_'.$_POST['ho7'];
	$_POST['mui']=$_POST['mui1'].'_'.$_POST['mui2'].'_'.$_POST['mui3'].'_'.$_POST['mui4'].'_'.$_POST['mui5'].'_'.$_POST['mui6'].'_'.$_POST['mui7'].'_'.$_POST['hoinguoi1']
	.'_'.$_POST['hoinguoi2'].'_'.$_POST['hoinguoi3'].'_'.$_POST['hoinguoi4'].'_'.$_POST['hoinguoi5'].'_'.$_POST['hoinguoi6'];
	$_POST['hannhiet']=$_POST['hannhietbl1'].'_'.$_POST['hannhietbl2'].'_'.$_POST['hannhietbl3'].'_'.$_POST['hannhietbl4'].'_'.$_POST['hannhietbl5'].'_'.$_POST['hannhietbl6'].'_'.$_POST['hannhietbl7'].'_'.$_POST['hannhietbl8'].'_'.$_POST['hannhietbl9'];
	$_POST['mohoi']=$_POST['mohoi1'].'_'.$_POST['mohoi2'].'_'.$_POST['mohoi3'].'_'.$_POST['mohoi4'].'_'.$_POST['mohoi5']
	.'_'.$_POST['mohoi6'].'_'.$_POST['mohoi7'];
	$_POST['daumat']=$_POST['daudau1'].'_'.$_POST['daudau2'].'_'.$_POST['daudau3'].'_'.$_POST['daudau4'].'_'.$_POST['daudau5']
	.'_'.$_POST['daudau6'].'_'.$_POST['daudau7'].'_'.$_POST['daudau8'].'_'.$_POST['daudau9'].'_'.$_POST['daudau10'].'_'.$_POST['daudau11']
	.'_'.$_POST['daudau12'].'_'.$_POST['daudau13'].'_'.$_POST['daudau14'].'_'.$_POST['daudau15'].'_'.$_POST['daudau16'].'_'.$_POST['daudau17']
	.'_'.$_POST['daudau18'].'_'.$_POST['daudau19'].'_'.$_POST['daudau20'].'_'.$_POST['daudau21'].'_'.$_POST['daudau22'].'_'.$_POST['daudau23']
	.'_'.$_POST['daudau24'].'_'.$_POST['daudau25'];
	$_POST['bungnguc']=$_POST['bungnguc1'].'_'.$_POST['bungnguc2'].'_'.$_POST['bungnguc3'].'_'.$_POST['bungnguc4'].'_'.$_POST['bungnguc5']
	.'_'.$_POST['bungnguc6'].'_'.$_POST['bungnguc7'].'_'.$_POST['bungnguc8'].'_'.$_POST['bungnguc9'].'_'.$_POST['bungnguc10'];
	$_POST['an']=$_POST['an1'].'_'.$_POST['an2'].'_'.$_POST['an3'].'_'.$_POST['an4'].'_'.$_POST['an5']
	.'_'.$_POST['an6'].'_'.$_POST['an7'].'_'.$_POST['an8'].'_'.$_POST['an9'].'_'.$_POST['an10'].'_'.$_POST['an11'];
	$_POST['uong']=$_POST['uong1'].'_'.$_POST['uong2'].'_'.$_POST['uong3'].'_'.$_POST['uong4'].'_'.$_POST['uong5']
	.'_'.$_POST['uong6'].'_'.$_POST['uong7'];
	$_POST['ngu']=$_POST['ngu1'].'_'.$_POST['ngu2'].'_'.$_POST['ngu3'].'_'.$_POST['ngu4'].'_'.$_POST['ngu5'];
	$_POST['daitt']=$_POST['tieutien1'].'_'.$_POST['tieutien2'].'_'.$_POST['tieutien3'].'_'.$_POST['tieutien4'].'_'.$_POST['tieutien5']
	.'_'.$_POST['tieutien6'].'_'.$_POST['tieutien7'].'_'.$_POST['tieutien8'].'_'.$_POST['tieutien9'].'_'.$_POST['tieutien10']
	.'_'.$_POST['daitien1'].'_'.$_POST['daitien2'].'_'.$_POST['daitien3'].'_'.$_POST['daitien4'].'_'.$_POST['daitien5']
	.'_'.$_POST['daitien6'].'_'.$_POST['daitien7'].'_'.$_POST['daitien8'].'_'.$_POST['daitien9'].'_'.$_POST['daitien10'].'_'.$_POST['daitien11'];
	$_POST['kn_sd']=$_POST['roiloankn1'].'_'.$_POST['roiloankn2'].'_'.$_POST['roiloankn3'].'_'.$_POST['roiloankn4'].'_'.$_POST['roiloankn5']
	.'_'.$_POST['roiloankn6'].'_'.$_POST['roiloankn7'].'_'.$_POST['roiloankn8'].'_'.$_POST['roiloankn9'].'_'.$_POST['doiha1']
	.'_'.$_POST['doiha2'].'_'.$_POST['doiha3'].'_'.$_POST['doiha4'].'_'.$_POST['doiha5']
	.'_'.$_POST['doiha6'].'_'.$_POST['doiha7'].'_'.$_POST['doiha8'].'_'.$_POST['doiha9'].'_'.$_POST['doiha10'].'_'.$_POST['nam_sd1']
	.'_'.$_POST['nam_sd2'].'_'.$_POST['nam_sd3'].'_'.$_POST['nam_sd4'].'_'.$_POST['nam_sd5'].'_'.$_POST['nu_sd1'].'_'.$_POST['nu_sd2']
	.'_'.$_POST['nu_sd3'].'_'.$_POST['nu_sd4'];
	$_POST['dkxh']=$_POST['dkxuathien1'].'_'.$_POST['dkxuathien2'].'_'.$_POST['dkxuathien3'];
	$_POST['xucchan']=$_POST['xucchan1'].'_'.$_POST['xucchan2'].'_'.$_POST['xucchan3'].'_'.$_POST['xucchan4'].'_'.$_POST['xucchan5'].'_'.$_POST['xucchan6'].'_'.$_POST['xucchan7'].'_'.$_POST['xucchan8']
	.'_'.$_POST['xucchan9'].'_'.$_POST['xucchan10'].'_'.$_POST['xucchan11'].'_'.$_POST['mohoi1'].'_'.$_POST['mohoi2'].'_'.$_POST['mohoi3'].'_'.$_POST['mohoi4'].'_'.$_POST['conhuc1'].'_'.$_POST['conhuc2'].'_'.$_POST['conhuc3'].'_'.$_POST['conhuc4'].'_'.$_POST['conhuc5']
	.'_'.$_POST['conhuc6'].'_'.$_POST['conhuc7'].'_'.$_POST['bung1'].'_'.$_POST['bung2'].'_'.$_POST['bung3'].'_'.$_POST['bung4'].'_'.$_POST['bung5'].'_'.$_POST['bung6'].'_'.$_POST['bung7'];
	$_POST['machchan']=$_POST['machtaytrai1'].'_'.$_POST['machtaytrai2'].'_'.$_POST['machtaytrai3'].'_'.$_POST['machtaytrai4'].'_'.$_POST['machtaytrai5'].'_'.$_POST['machtaytrai6'].'_'.$_POST['machtaytrai7']
	.'_'.$_POST['machtaytrai8'].'_'.$_POST['machtaytrai9']	
	.'_'.$_POST['machtayphai1'].'_'.$_POST['machtayphai2'].'_'.$_POST['machtayphai3'].'_'.$_POST['machtayphai4'].'_'.$_POST['machtayphai5'].'_'.$_POST['machtayphai6']
	.'_'.$_POST['machtayphai7'].'_'.$_POST['machtayphai8'].'_'.$_POST['machtayphai9'];
	$_POST['chedoan']=$_POST['chedoan1'].'_'.$_POST['chedoan2'].'_'.$_POST['chedoan3'].'_'.$_POST['chedoan4'].'_'.
	$_POST['chedoan5'].'_'.$_POST['chedoan6'];
	$_POST['chedochamsoc']=$_POST['chedochamsoc1'].'_'.$_POST['chedochamsoc2'].'_'.$_POST['chedochamsoc3'];
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
    $lang_tables[]='obstetrics.php';
    require('./include/init_show.php');
    if(empty($current_encounter)&&!empty($_SESSION['sess_en'])){
        $current_encounter=$_SESSION['sess_en'];
    }elseif($current_encounter) {
        $_SESSION['sess_en']=$current_encounter;
    }
    if($_SESSION['sess_en']){
        $pregs=&$obj->_getKhambenh('encounter_nr='.$_SESSION['sess_en'].'','');
    }
    if($pregs){
        $rows=$pregs->RecordCount();
    }    
  

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_show.php');
?>
