<?php
 //local_user do file init_show.php lấy ra
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require_once($root_path.'include/core/inc_environment_global.php');
    ///$db->debug = true;
    //set quyền admin là true
    define('NO_2LEVEL_CHK',1);
    $thisfile=basename(__FILE__);
    if(!isset($type_nr)||!$type_nr) $type_nr=1; //* 1 = history physical notes

    require_once($root_path.'include/care_api_classes/class_notes.php');//load csdl tu code cua duong dan nay
    $obj=new Notes;
    $types=$obj->getAllTypesSort('name');
    $this_type=$obj->getType($type_nr);//la 1 mang chua cac gia tri nr,type,name,LD_var trong bang care_type_notes
    //Xet dieu kien tao moi/thay doi/luu benh nhan

    if(!isset($mode)){
            $mode='show';
    } elseif($mode=='create'||$mode=='update') {
            include_once($root_path.'include/core/inc_date_format_functions.php');
            # Set the date, default is today
            if(empty($_POST['date'])) $_POST['date']=date('Y-m-d');
                    else $_POST['date']=@formatDate2STD($_POST['date'],$date_format);
            $_POST['time']=date('H:i:s');
            //luu lai nhung gia tri trong form nhap vao csdl
            include('./include/save_admission_data.inc.php');
    }
    # Load the emr language table
    $lang_tables=array('emr.php');
    require('./include/init_show.php');
    //$current_encounter <=> pid
    //$sess_en để truyền mã bệnh nhân cho những nút trong template này
    if(isset($current_encounter) && $current_encounter) {
            $parent_admit=true;
            $is_discharged=false;
            $_SESSION['sess_en'] = $current_encounter;
    }
//    $page_title.=" :: $LDNotes $LDAndSym $LDReports";

    ////////////////////////// edit 10/11 //////////////////////////////////////////
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $obj1=new Encounter;
    $status=$obj1->AllStatus($_SESSION['sess_en']);
    ////////////////////////////////////////////////////////////////////////////////

    //e.encounter_nr=".$_SESSION['sess_en']." de ktra co ton tai trong csdl ko
    if($parent_admit){
	$sql="SELECT n.nr,n.notes,n.short_notes, n.encounter_nr,n.date,n.personell_nr,n.personell_name,e.encounter_class_nr
		FROM care_encounter AS e,
			care_person AS p,
			care_encounter_notes AS n
		WHERE p.pid=".$_SESSION['sess_pid']."
			AND p.pid=e.pid
			AND e.encounter_nr=".$_SESSION['sess_full_en']."
			AND e.encounter_nr=n.encounter_nr
		ORDER BY n.date DESC";
    }else{
            $sql="SELECT n.nr,n.notes,n.short_notes, n.encounter_nr,n.date,n.personell_nr,n.personell_name,e.encounter_class_nr
                    FROM care_encounter AS e,
                            care_person AS p,
                            care_encounter_notes AS n
                    WHERE	p.pid=".$_SESSION['sess_pid']."
                            AND	p.pid=e.pid
                            AND e.encounter_nr=n.encounter_nr
                    ORDER BY n.date DESC";
    }
    if($result=$db->Execute($sql)){
	$rows=$result->RecordCount();
        
    }else{
            echo $sql;
    }
    //LD_var có trong bảng care_type_notes o day lay ra de lam tieu de cho form
    if(isset($$this_type['LD_var'])&&!empty($$this_type['LD_var'])) {
            $subtitle=$$this_type['LD_var'];
    }else{
            $subtitle=$this_type['name'];
    }

    # Tag for help file
    $notestype='notes';

    $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
    $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer);

    /* Hide tabs */
    $notabs=true;
    ////////////edit 10/11-Huỳnh ///////////////////
    switch ($this_type['nr']){
//        case 4:
//            //hội chẩn
//            require('./gui_bridge/default/gui_show_notes_op_1.php');
//            break;
//        case 5:
//            //phẫu thuật
//            echo "test";
//            //require('./gui_bridge/default/gui_show_notes_2.php');
//            break;
//        case 19:
//            echo "test";
//            //require('./gui_bridge/default/gui_show_notes_1.php');
//            break;
        default :
            require('./gui_bridge/default/gui_show_notes_op_1.php');
            break;
    }
////////////////////////////////////////////////////////////

?>
