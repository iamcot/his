<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
    * GNU General Public License
    * Copyright 2002,2003,2004,2005 Elpidio Latorilla
    * elpidio@care2x.org, 
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    $thisfile=basename(__FILE__);
    require('./include/init_show.php');

    $page_title=$question_Obstetrics;

    # Load the entire encounter data
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($encounter_nr);
    $enc_obj->loadEncounterData();
    # Get encounter class
    $enc_class=$enc_obj->EncounterClass();
    
    $_SESSION['sess_full_en']=$encounter_nr;

    if(empty($encounter_nr)&&!empty($_SESSION['sess_en'])){
            $encounter_nr=$_SESSION['sess_en'];
    }elseif($encounter_nr) {
            $_SESSION['sess_en']=$encounter_nr;
    }
	
    $subtitle=$question_Obstetrics;

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 
    $_SESSION['sess_file_return']=$thisfile;
    
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $encounter_obj=new Encounter();
    $class=&$encounter_obj-> loadEncounterData($_SESSION['sess_en']);
    $encounter_class_nr=&$encounter_obj->EncounterClass();
    
    # Set break file
    require('include/inc_breakfile.php');

    if($mode=='show') $glob_obj->getConfig('admit_%');
    /* Load GUI page */
    require('./gui_bridge/default/gui_show_obstetrics_general.php');
?>
