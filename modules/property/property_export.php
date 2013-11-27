<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
 * DFCK HIS
 * Author: CoT - thang102@gmail.com
 * Select & export property to PDF
 */
define('LANG_FILE','properties.php');
define('NO_2LEVEL_CHK',1);
if($_SESSION['sess_user_origin']=='personell_admin'){
    $local_user='ck_doctors_dienstplan_user';
	//$local_user='aufnahme_user';
    $breakfile=$root_path.'modules/property/property-admi-welcome.php'.URL_APPEND;
}else{
    $local_user='ck_doctors_dienstplan_user';
    if (!empty($_SESSION['sess_path_referer'])){
            $breakfile=$root_path.$_SESSION['sess_path_referer'].URL_APPEND;
    } else {
            /* default startpage */
            $breakfile = $root_path.'doctors.php'.URL_APPEND;
    }
}
require_once($root_path.'include/core/inc_front_chain_lang.php');
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');

$view_prop = "cot_prop_export";
#xu li
$selectfield = array('name_formal','unit','numprop');
if($_POST['viewpropreport']){ //view 
    foreach ($propfield as $field){
        if(isset($_POST[$field[0]]) && $field[2]==1) $selectfield[]= $field[0];
    }
    //var_dump($selectfield);
    global $db;
    $sql="select ";    

    foreach($selectfield as  $row){
        $sql .= "$row , ";
    }
    $sql = substr($sql,0,-2);
    $sql.= " from $view_prop where dept_mana=".$_POST['dept_mana']."";

    if(isset($_POST['importfromdate']) && ($_POST['importfromdate']!=''))
        $sql .= " and importdate  >= '".date("Y-m-d",strtotime($_POST['importfromdate']))."' ";
    if(isset($_POST['importtodate']) && ($_POST['importtodate']!=''))
        $sql .= " and importdate  <= '".date("Y-m-d",strtotime($_POST['importtodate']))."' ";
    //echo $sql;
    $rs = $db->Execute($sql);
    if($rs->RecordCount()){
        $tbcontent = "<table class='property'><tr class='gray'><td>STT</td>";
        foreach($propfield as $field){
            if(isset($_POST[($field[0])]) || ($field[3]=='true'))
                $tbcontent .= "<td>".$field[1]."</td>";
        }
        $tbcontent .="</tr>";
        $toggle=0;
        
        $i = 1;
        while($props = $rs->FetchRow())
        {
            if($toggle) $trc='#dedede';
            else $trc='#efefef';
            $toggle=!$toggle;
            $tbcontent .= "<tr bgcolor='$trc'><td>".$i++."</td>";
            foreach ($propfield as $field) {
                if(isset($_POST[($field[0])]) || ($field[3]=='true'))
                $tbcontent .= "<td>".$props[($field[0])]."</td>";
            }
            $tbcontent .="</tr>";

        }
        $tbcontent .="</table>";
    }
    else{
        $tbcontent = "Chưa có dữ liệu.";
    }
}
else if($_POST['exportpropreport']){ //xuat PDF
    foreach ($propfield as $field){
        if(isset($_POST[$field[0]]) && $field[2]==1) $selectfield[]= $field[0];
    }
    //var_dump($selectfield);
    global $db;
    $sql="select ";    

    foreach($selectfield as  $row){
        $sql .= "$row , ";
    }
    $sql = substr($sql,0,-2);
    $sql.= " from $view_prop where dept_mana=".$_POST['dept_mana']."";
    if(isset($_POST['importfromdate']) && ($_POST['importfromdate']!=''))
        $sql .= " and importdate  >= '".$_POST['importfromdate']."' ";
    if(isset($_POST['importtodate']) && ($_POST['importtodate']!=''))
        $sql .= " and importdate  <= '".$_POST['importtodate']."' ";
    $rs = $db->Execute($sql);
    if($rs->RecordCount()){
        $tbcontent = '<table border="1" cellspacing="0" cellpadding="3"><tr><td  width="4%" align="center"><b>STT</b></td>';
        foreach($propfield as $field){
            if(isset($_POST[($field[0])]) || ($field[3]=='true'))
                $tbcontent .= "<td><b>".$field[1]."</b></td>";
        }
        $tbcontent .="</tr>";
        $i = 1;
        while($props = $rs->FetchRow())
        {
            
            $tbcontent .= '<tr><td align="center">'.$i++.'</td>';
            foreach ($propfield as $field) {
                if(isset($_POST[($field[0])]) || ($field[3]=='true'))
                $tbcontent .= "<td>".$props[($field[0])]."</td>";
            }
            $tbcontent .="</tr>";
            
        }

        $tbcontent .="</table>";
        require($root_path."modules/pdfmaker/std_plates/Baocaothietbi.php");
        exportbctb($tbcontent);
    }
    else{
        $tbcontent = "Chưa có dữ liệu.";
    }
}


# Added for the common header top block

$smarty->assign('sToolbarTitle',$LDPropExportTitle);
$smarty->assign('sWindowTitle',$LDPropExportTitle);
$deptmanastr = "";
foreach($deptlist as $dept){
    if($dept[0]==$propinfo['dept_mana']) $select = "selected='true'"; else $select="";
    $deptmanastr .= '<option value="'.$dept[0].'" '.$select.'>'.$dept[1].'</option>';
}
$propSelectField = "";
foreach ($propfield as $field){
    if($field[3]=='true') $check = "checked DISABLED";
    else if(isset($_POST[$field[0]])) $check = "checked";
    else $check = "";
    $propSelectField .= "<div><input type='checkbox' name='".$field[0]."'  $check > ".$field[1]." </div>";
}

include_once($root_path.'include/core/inc_date_format_functions.php');
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
$smarty->assign('importfromdate','<input id="importfromdate" name="importfromdate" type="text" value="">');
$smarty->assign('importtodate','<input id="importtodate" name="importtodate" type="text" value="">');
$smarty->assign('LDDeptMana',$LDDeptMana);
$smarty->assign('tbcontent',$tbcontent);
$smarty->assign('deptmana',$deptmanastr);
$smarty->assign('LDPropSelect',$LDPropSelect);
$smarty->assign('PropSelectList',$propSelectField);
//render
$smarty->assign('sMainBlockIncludeFile','property/property_export.tpl');
?>
    <link type="text/css" rel="stylesheet" href="<?php echo  $root_path;?>js/cssjquery/jquery-ui-1.7.2.custom.css" />
    <script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
    <script src="<?php echo $root_path;?>js/jquery-ui-1.7.2.custom.min.js"></script>
    <script>
    $(function() {
        $("#importfromdate").datepicker({
            changeMonth: true,
            changeYear: true
        });
        $("#importtodate").datepicker({
            changeMonth: true,
            changeYear: true
        });
        $("#importfromdate").datepicker("option", "dateFormat","yy-mm-dd");
        $("#importtodate").datepicker("option", "dateFormat","yy-mm-dd");
    });

    </script>
<?
$smarty->display('common/mainframe.tpl');

