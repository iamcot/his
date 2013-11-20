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
    $lang_tables[]='emr.php';
    $lang_tables[]='nursing.php';
    require('./include/init_show.php');
    if(!isset($mode)){
            $mode='show';
    }
    if(empty($current_encounter)&&!empty($_SESSION['sess_en'])){
        $current_encounter=$_SESSION['sess_en'];
    }elseif($current_encounter) {
        $_SESSION['sess_en']=$current_encounter;
    }
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($encounter_nr);
    if(!isset($allow_update)) $allow_update=FALSE;

    if($obj=$enc_obj->loadEncounterData1($encounter_nr,1)){
        $obj=$enc_obj->loadEncounterData1($encounter_nr);
        $rows=$obj->RecordCount();
    }
    $page_title=$LDInfoGiayto.'::'.$LDConsultNotes1;
    $subtitle=$LDConsultNotes1;
    $_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&target=search";
    
    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

    /* Load GUI page */
    require('./gui_bridge/default/gui_notes.php');
?>
