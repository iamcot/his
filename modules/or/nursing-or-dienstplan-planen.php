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
$lang_tables[]='or.php';
$lang_tables[]='departments.php';
define('LANG_FILE','nursing.php');
$local_user='ck_op_dienstplan_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/care_api_classes/class_access.php');
$access= & new Access();
if($_SESSION['sess_user_name']!='admin'){
    $role= $access->checkNameRole($_SESSION['sess_user_name']);
    if(strpos($role['role_name'], 'Trưởng khoa')=='' && strpos($role['role_name'], 'Điều dưỡng trưởng')==''){
        header("Location:../../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
        exit;
    }
}

$thisfile=basename(__FILE__);
$breakfile="nursing-or-dienstplan_1.php".URL_APPEND."&dept_nr=$dept_nr&ward_nr=$ward_nr&pmonth=$pmonth&pyear=$pyear&retpath=$retpath";
$_SESSION['sess_file_return']=$thisfile;

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
//gọi bảng care_dutyplen_oncall
$pers_obj->useDutyplanTable();

/************** resolve dept only *********************************/

if ($pmonth=='') $pmonth=date('n');
if ($pyear=='') $pyear=date('Y');

/* Establish db connection */
if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
if($dblink_ok)
{
    if($mode=='save')
    {
        $arr_1_txt=array();
        $arr_2_txt=array();
        $arr_1_pnr=array();
        $arr_2_pnr=array();

        for($i=0;$i<$maxelement;$i++)
        {
            $tdx="ha".$i;
            $ddx="hr".$i;
            $ax="a".$i;
            $rx="r".$i;

            if(!empty($$ax)) $arr_1_txt[$ax]=$$ax;
            if(!empty($$rx)) $arr_2_txt[$rx]=$$rx;
            if(!empty($$tdx)) $arr_1_pnr[$tdx]=$$tdx;
            if(!empty($$ddx)) $arr_2_pnr[$ddx]=$$ddx;

        }

        $ref_buffer=array();
        // Serialize the data
        $ref_buffer['duty_1_txt']=serialize($arr_1_txt);
        $ref_buffer['duty_2_txt']=serialize($arr_2_txt);
        $ref_buffer['duty_1_pnr']=serialize($arr_1_pnr);
        $ref_buffer['duty_2_pnr']=serialize($arr_2_pnr);

        $ref_buffer['dept_nr']=87;
        $ref_buffer['role_nr']=14; // 14 = oncall nurse (role person)
        $ref_buffer['year']=$pyear;
        $ref_buffer['month']=$pmonth;
        $ref_buffer['modify_id']=$_SESSION['sess_user_name'];

        if($dpoc_nr=$pers_obj->NOCDutyplanExists(87,ROLE_NR_NURSER,$pyear,$pmonth)){
            //echo $dpoc_nr;
            $ref_buffer['history']=$pers_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n");
            // Point to the internal data array
            $pers_obj->setDataArray($ref_buffer);

            if($pers_obj->updateDataFromInternalArray($dpoc_nr)){
                    # Remove the cache plan
                    if(date('Yn')=="$pyear$pmonth"){
                            $pers_obj->deleteDBCache('NOCS_'.date('Y-m-d'));
                    }
                    header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=87&ward_nr=63&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
            }else echo "<p>".$pers_obj->sql."<p>$LDDbNoSave"; 
        } // else create new entry
        else
        {
            $ref_buffer['history']="Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
            $ref_buffer['create_id']=$_SESSION['sess_user_name'];
            $ref_buffer['create_time']='NULL';
            // Point to the internal data array
            $pers_obj->setDataArray($ref_buffer);
            if($pers_obj->insertDataFromInternalArray()){
                            //echo $sql." new insert <br>";
                            # Remove the cache plan
                            if(date('Yn')=="$pyear$pmonth"){
                                    $pers_obj->deleteDBCache('NOCS_'.date('Y-m-d'));
                            }

                            header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=87&ward_nr=63&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
            }else{
                    echo "<p>".$pers_obj->sql."<p>$LDDbNoSave"; 
            }
        }//end of else
     }// end of if(mode==save)
    else
    {
        $dutyplan=&$pers_obj->getNOCDutyplan(87,ROLE_NR_NURSER,$pyear,$pmonth);
    }
}
else { echo "$LDDbNoLink<br>"; } 


$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));

$firstday=date("w",mktime(0,0,0,$pmonth,1,$pyear));

function makefwdpath($path,$dpt,$mo,$yr,$saved)
{
    if ($path==1)
    {	
        $fwdpath='nursing-or-dienstplan.php?';
        if($saved!="1") 
        {  
                if ($mo==1) {$mo=12; $yr--;}
                        else $mo--;
        }
        return $fwdpath.'dept='.$dpt.'&pmonth='.$mo.'&pyear='.$yr;
    }
    else return "nursing-or-dienstplan-checkpoint.php";
}

# Prepare page title
$sTitle = "$LDMakeDutyPlan :: ";
$LDvar=$dept_obj->LDvar();
if(isset($$LDvar) && $$LDvar) $sTitle = $sTitle.$$LDvar.' :: '.$LDNursing;
else $sTitle = $sTitle.$dept_obj->FormalName();

# Start Smarty templating here
/**
* LOAD Smarty
*/

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in toolbar
$smarty->assign('sToolbarTitle',$sTitle);

# href for help button
$smarty->assign('pbHelp',"javascript:gethelp('op_duty.php','plan','$rows')");

# href for return button
$smarty->assign('pbBack','javascript:history.back();killchild();');

# href for close button
$smarty->assign('breakfile',$breakfile);

# Body onLoad javascript
//$smarty->assign('sOnLoadJs','onUnload="killchild()"');

# Window bar title
$smarty->assign('sWindowTitle',$sTitle);

# Collect extra javascript

ob_start();
?>

<style type="text/css">

div.a3 {font-family: arial; font-size: 14; margin-left: 3; margin-right:3; }

.infolayer {
    position:static;
    visibility: hide;
    left: 10;
    top: 10;
}

</style>

<script language="javascript">

  var urlholder;
  var infowinflag=0;

function popselect(elem,mode,nr_t,nr_d,nr_s,nr_s_t,nr_s_d)
{
    w=window.screen.width;
    h=window.screen.height;
    ww=700;
    wh=500;
    var tmonth=document.dienstplan.month.value;
    var tyear=document.dienstplan.jahr.value;
    //nếu thếm $sid sẽ bị lỗi URI quá dài
    urlholder="nursing-or-dienstplan-poppersonselect.php?elemid="+elem + "&dept_nr=87&ward_nr=63&month="+tmonth+"&year="+tyear+ "&mode=" + mode+ "&nr_t="+ nr_t + "&nr_d="+ nr_d + "&nr_s="+ nr_s + "&nr_s_t="+ nr_s_t + "&nr_s_d="+ nr_s_d + "&retpath=qview<?php echo "&lang=$lang"; ?>";
    popselectwin=window.open(urlholder,"pop","width=" + ww + ",height=" + wh + ",menubar=no,resizable=yes,scrollbars=yes,dependent=yes");
    window.popselectwin.moveTo((w/2)+80,(h/2)-(wh/2));
}

function killchild()
{
    if (window.popselectwin) if(!window.popselectwin.closed) window.popselectwin.close();
}

function cal_update()
{
    var filename="nursing-or-dienstplan-planen.php?<?php echo "sid=$sid&lang=$lang" ?>&retpath=<?php echo $retpath ?>&dept_nr=87&ward_nr=63&pmonth="+document.dienstplan.month.value+"&pyear="+document.dienstplan.jahr.value;
    window.location.replace(filename);
}
</script>
<?php 

$sTemp=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$smarty->assign('LDStandbyPerson',$LDStandbyPerson);
$smarty->assign('LDOnCall',$LDOnCall);

# Prepare the date selectors
$smarty->assign('LDMonth',$LDMonth);
$sBuffer = '<select name="month" size="1" onChange="cal_update()">';

for ($i=1;$i<13;$i++){
	 $sBuffer = $sBuffer.'<option  value="'.$i.'" ';
	 if (($pmonth)==$i)  $sBuffer = $sBuffer.'selected';
	  $sBuffer = $sBuffer.'>'.$monat[$i].'</option>';
	  $sBuffer = $sBuffer."\n";
}
$sBuffer = $sBuffer.'</select>';
$smarty->assign('sMonthSelect',$sBuffer);

$smarty->assign('LDYear',$LDYear);
$sBuffer = '<select name="jahr" size="1" onChange="cal_update()">';

for ($i=2000;$i<2016;$i++){
	 $sBuffer = $sBuffer.'<option  value="'.$i.'" ';
	 if ($pyear==$i) $sBuffer = $sBuffer.'selected';
	 $sBuffer = $sBuffer.'>'.$i.'</option>';
  	 $sBuffer = $sBuffer."\n";
}
$sBuffer = $sBuffer.'</select>';
$smarty->assign('sYearSelect',$sBuffer);

$smarty->assign('sFormAction','action="nursing-or-dienstplan-planen.php"');

 # collect hidden inputs

 ob_start();

?>

<input type="hidden" name="mode" value="save">
<input type="hidden" name="dept" value="<?php echo $dept_obj->ID(); ?>">
<input type="hidden" name="dept_nr" value="87">
<input type="hidden" name="ward_nr" value="63">
<input type="hidden" name="pmonth" value="<?php echo $pmonth; ?>">
<input type="hidden" name="pyear" value="<?php echo $pyear; ?>">
<input type="hidden" name="planid" value="<?php echo $ck_plan; ?>">
<input type="hidden" name="maxelement" value="<?php echo $maxdays; ?>">
<input type="hidden" name="encoder" value="<?php echo $ck_op_dienstplan_user; ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="sid" value="<?php echo $sid; ?>">

<?php

$sTemp=ob_get_contents();
ob_end_clean();
$smarty->assign('sHiddenInputs',$sTemp);

if($saved) $sBuffer = createLDImgSrc($root_path,'close2.gif','0');
else $sBuffer = createLDImgSrc($root_path,'cancel.gif','0');

 # Assign control links
$smarty->assign('sSave','<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'"></a>');
$smarty->assign('sClose',"<a href=\"$breakfile\" onUnload=\"killchild()\"><img ".$sBuffer." alt=\"$LDClosePlan\"></a>");

$aelems=unserialize($dutyplan['duty_1_txt']);
$relems=unserialize($dutyplan['duty_2_txt']);
$a_pnr=unserialize($dutyplan['duty_1_pnr']);
$r_pnr=unserialize($dutyplan['duty_2_pnr']);

$sTemp='';

for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
{
    switch ($wd){
        case 6: $smarty->assign('sRowClass','class="saturday"');break;
        case 0: $smarty->assign('sRowClass','class="sunday"');break;
        default: $smarty->assign('sRowClass','class="weekday"');
    }

    $smarty->assign('iDayNr',$i);
    $smarty->assign('LDShortDay',$LDShortDay[$wd]);

    if ($aelems['a'.$n]=="") $smarty->assign('sIcon1','<img '.createComIcon($root_path,'warn.gif','0').'>');
    else $smarty->assign('sIcon1','<img '.createComIcon($root_path,'mans-gr.gif','0').'>');
    $smarty->assign('sInput1','<input type="hidden" name="ha'.$n.'" value="'.$a_pnr['ha'.$n].'">
            <input type="text" name="a'.$n.'" size="30" onFocus=this.select() value="'.$aelems['a'.$n].'">');

    //$n là mã của các ngày trong tháng từ 0-31
	$smarty->assign('sPopWin1','<a href="javascript:popselect(\''.$n.'\',\'a\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\',\''.$r_pnr['hr'.$n].'\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\')">
                <button onclick="javascript:popselect(\''.$n.'\',\'a\',\''.$n.'\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\',\''.$r_pnr['hr'.$n].'\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\')"><img '.createComIcon($root_path,'patdata.gif','0').' alt="'.$LDClk2Plan.'"></button></a>');
        
	if ($relems['r'.$n]=="") $smarty->assign('sIcon2','<img '.createComIcon($root_path,'warn.gif','0').'>');
		else $smarty->assign('sIcon2','<img '.createComIcon($root_path,'mans-red.gif','0').'>');
	$smarty->assign('sInput2','<input type="hidden" name="hr'.$n.'" value="'.$r_pnr['hr'.$n].'">
                <input type="text" name="r'.$n.'" onFocus=this.select() value="'.$relems['r'.$n].'">');
	$smarty->assign('sPopWin2','<a href="javascript:popselect(\''.$n.'\',\'r\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\',\''.$a_pnr['ha'.$n].'\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\')">
                <button onclick="javascript:popselect(\''.$n.'\',\'r\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\',\''.$a_pnr['ha'.$n].'\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\')"><img '.createComIcon($root_path,'patdata.gif','0').' alt="'.$LDClk2Plan.'"></button></a>');
    if($wd==6) $wd=-1;

    # Buffer each row and collect to a string

    ob_start();
            $smarty->display('common/duty_plan_entry_row.tpl');
            $sTemp = $sTemp.ob_get_contents();
    ob_end_clean();
}

# Assign the duty entry rows to the subframe template

$smarty->assign('sDutyRows',$sTemp);


$smarty->assign('sMainBlockIncludeFile','common/duty_plan_entry_frame.tpl');
 /**
 * show Template
 */
$smarty->display('common/mainframe.tpl');

?>