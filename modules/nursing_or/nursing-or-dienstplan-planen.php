<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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
define('LANG_FILE','doctors.php');
$local_user='ck_op_dienstplan_user';
if ($local_user='ck_op_dienstplan_user') define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

if(!isset($dept_nr)||!$dept_nr){
    header('Location:nursing-or-select-dept.php'.URL_REDIRECT_APPEND.'&retpath='.$retpath);
    exit;
}

$thisfile=basename(__FILE__);
$breakfile="nursing-or-dienstplan.php".URL_APPEND."&dept_nr=$dept_nr&pmonth=$pmonth&pyear=$pyear&retpath=$retpath";
$_SESSION['sess_file_return']=$thisfile;

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$pers_obj->useDutyplanTable();
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
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
					$arr_1=array();
					$arr_1_j=array();
					$arr_2=array();
					$arr_2_j=array();
					$arr_2_pnr=array();
					//add by vy
					$arr_3_txt=array();
					$arr_3=array();
					$arr_3_pnr=array();
					$arr_3_j=array();
        for($i=0;$i<$maxelement;$i++)
        {
           $tdx="ha".$i;//Mã những người trực suốt
						$ddx="hr".$i;//Mã những người trực sẵn sàng
						//add by vy
						$todx="hao".$i;					
						$ax="a".$i;//trực suốt					
						$rx="r".$i;//trực sẵn sàng
						//add by vy
						$axo="ao".$i;					
						for($j=0;$j<5;$j++){
							$axj="ha".$i."_".$j;
							$aj="a".$i."_".$j;
							$dxj="hr".$i."_".$j;
							$dj="r".$i."_".$j;
							$toxj="hao".$i."_".$j;
							$toj="ao".$i."_".$j;
							//echo $_POST[$axj];
							if(!empty($$axj)) $arr_1[$i][$axj]=$_POST[$axj];
							if(!empty($$aj)) $arr_1_j[$i][$aj]=$_POST[$aj];
//var_dump($arr_1[$axj]);
							if(!empty($$dxj)) $arr_2[$i][$dxj]=$_POST[$dxj];
							if(!empty($$dj)) $arr_2_j[$i][$dj]=$_POST[$dj];
							if(!empty($$toxj)) $arr_3[$i][$toxj]=$_POST[$toxj];
							if(!empty($$toj)) $arr_3_j[$i][$toj]=$_POST[$toj];
							//echo $temp;
							
						}
						
					if(!empty($arr_1[$i]))	$temp[$i]= serialize($arr_1[$i]);
					if(!empty($arr_1_j[$i]))	$temp1[$i]= serialize($arr_1_j[$i]);
					if(!empty($arr_2[$i]))	$temp2[$i]= serialize($arr_2[$i]);
					if(!empty($arr_2_j[$i]))	$temp3[$i]= serialize($arr_2_j[$i]);
					if(!empty($arr_3[$i]))	$temp4[$i]= serialize($arr_3[$i]);
					if(!empty($arr_3_j[$i]))	$temp5[$i]= serialize($arr_3_j[$i]);
						//echo $temp[$i];
						$arr_1_pnr[$tdx]= $temp[$i];
					
						 $arr_1_txt[$ax]=$temp1[$i];
						
						 $arr_2_txt[$rx]=$temp3[$i];
						//add by vy
						$arr_3_txt[$axo]=$temp5[$i];
						
						//end
//echo $tdx;
					
						//var_dump($arr_1_pnr[$tdx]);
						 $arr_2_pnr[$ddx]=$temp2[$i];
						//add by vy
						$arr_3_pnr[$todx]=$temp4[$i];
						
        }

        $ref_buffer=array();
        // Serialize the data
       $ref_buffer['duty_1_txt']=serialize($arr_1_txt);
					$ref_buffer['duty_2_txt']=serialize($arr_2_txt);
					$ref_buffer['duty_1_pnr']=serialize($arr_1_pnr);
				
					$ref_buffer['duty_2_pnr']=serialize($arr_2_pnr);
					//add by vy
					$ref_buffer['duty_3_txt']=serialize($arr_3_txt);
					
					$ref_buffer['duty_3_pnr']=serialize($arr_3_pnr);

        $ref_buffer['dept_nr']=$dept_nr;
        $ref_buffer['role_nr']=14; // 14 = oncall nurse (role person)
        $ref_buffer['year']=$pyear;
        $ref_buffer['month']=$pmonth;
        $ref_buffer['modify_id']=$_SESSION['sess_user_name'];

        if($dpoc_nr=$pers_obj->NOCDutyplanExists($dept_nr,$pyear,$pmonth)){
            //echo $dpoc_nr;
            $ref_buffer['history']=$pers_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n");
            // Point to the internal data array
            $pers_obj->setDataArray($ref_buffer);

            if($pers_obj->updateDataFromInternalArray($dpoc_nr)){
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, str_replace("\"","\'",$pers_obj->getLastQuery()), date('Y-m-d H:i:s'));		
                    # Remove the cache plan
                    if(date('Yn')=="$pyear$pmonth"){
                            $pers_obj->deleteDBCache('NOCS_'.date('Y-m-d'));
                    }
                    header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=$dept_nr&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
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
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, str_replace("\"","\'",$pers_obj->getLastQuery()), date('Y-m-d H:i:s'));		
                            //echo $sql." new insert <br>";
                            # Remove the cache plan
                            if(date('Yn')=="$pyear$pmonth"){
                                    $pers_obj->deleteDBCache('NOCS_'.date('Y-m-d'));
                            }

                            header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=$dept_nr&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
            }else{
                    echo "<p>".$pers_obj->sql."<p>$LDDbNoSave"; 
            }
        }//end of else
     }// end of if(mode==save)
    else
    {
      if($dutyplan=&$pers_obj->getNOCDutyplan($dept_nr,$pyear,$pmonth)){
			
				$aelems=unserialize($dutyplan['duty_1_txt']);
				//var_dump($aelems);
				$relems=unserialize($dutyplan['duty_2_txt']);
				$a_pnr=unserialize($dutyplan['duty_1_pnr']);
				$r_pnr=unserialize($dutyplan['duty_2_pnr']);
				
				//add by vy
				$aoelems=unserialize($dutyplan['duty_3_txt']);
				
				$ao_pnr=unserialize($dutyplan['duty_3_pnr']);
				
			}
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
if(isset($$LDvar) && $$LDvar) $sTitle = $sTitle.$$LDvar;
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

function popselect(elem,mode,nr_t,nr_d,nr_s,nr_s_t,nr_s_d,nr_o,nr_s_o)
{
    w=window.screen.width;
    h=window.screen.height;
    ww=700;
    wh=500;
    var tmonth=document.dienstplan.month.value;
    var tyear=document.dienstplan.jahr.value;
    //nếu thếm $sid sẽ bị lỗi URI quá dài
    urlholder="nursing-or-dienstplan-poppersonselect.php?elemid="+elem+"&dept_nr=<?php echo $dept_nr ?>&month="+tmonth+"&year="+tyear+"&mode="+mode+"&nr_t="+nr_t+"&nr_d="+nr_d+"&nr_s="+nr_s+"&nr_s_t="+nr_s_t+"&nr_s_d="+nr_s_d+"&nr_o="+nr_o+"&nr_s_o="+nr_s_o+"&retpath=qview<?php echo "&lang=$lang"; ?>";
    popselectwin=window.open(urlholder,"pop","width=" + ww + ",height=" + wh + ",menubar=no,resizable=yes,scrollbars=yes,dependent=yes");
    window.popselectwin.moveTo((w/2)+80,(h/2)-(wh/2));
}
function insertRow(mode,nr,num)
{               var tbl = document.getElementById("mytest"+mode+nr);
            var lastRow = tbl.tBodies[0].rows.length;
	if (window.XMLHttpRequest)	  {
	  xmlhttp=new XMLHttpRequest();
	  }
	else  {
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		                            var rowadd =tbl.tBodies[0].insertRow(-1);
                            rowadd.innerHTML = xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","getrow.php?mode="+mode+"&i="+nr+"&num="+num,true);
	xmlhttp.send();


}
function delRow(mode,i,num)
{
  var tbl = document.getElementById("mytest"+mode+i);
  //alert(tbl);
  var lastRow = tbl.tBodies[0].rows.length;
  //alert(lastRow);
 
if (lastRow >= num) tbl.deleteRow(num-1);
document.getElementById("h"+mode+i+"_"+(num-1))="";
}
function killchild()
{
    if (window.popselectwin) if(!window.popselectwin.closed) window.popselectwin.close();
}

function cal_update()
{
    var filename="nursing-or-dienstplan-planen.php?<?php echo "sid=$sid&lang=$lang" ?>&retpath=<?php echo $retpath ?>&dept_nr=<?php echo $dept_nr; ?>&pmonth="+document.dienstplan.month.value+"&pyear="+document.dienstplan.jahr.value;
    window.location.replace(filename);
}
</script>
<?php 

$sTemp=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$smarty->assign('LDStandbyPerson',$LDStandbyPerson);
$smarty->assign('LDOnCall',$LDOnCall);
 $smarty->assign('LDNgoaigio1',$LDNgoaigio1);
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
<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
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

  $smarty->assign('sIcon1','<table cellspacing="1" cellpadding="0" border="0" id="mytesta'.$i.'">
		<tbody>
		
			');
		$aj_pnr=unserialize($a_pnr['ha'.$n]);	
		$ajelems=unserialize($aelems['a'.$n]);
		//echo sizeof($aj_pnr)."cfvg";
		if(sizeof($aj_pnr)>1){
		$temp='';
		 for($j=0;$j<sizeof($aj_pnr);$j++){
		 if($j==0){
		  $temp.='<tr><td><nobr><input type="hidden" name="ha'.$n.'_'.$j.'" value="'.$aj_pnr['ha'.$n.'_'.$j].'">               
			<input type="text" name="a'.$n.'_'.$j.'" value="'.$ajelems['a'.$n.'_'.$j].'">
				<a href="javascript:popselect(\''.$n.'_'.$j.'\',\'a\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'a\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						
						</nobr></td></tr>';
		 }else{
		 $temp.='<tr><td><nobr><input type="hidden" name="ha'.$n.'_'.$j.'" value="'.$aj_pnr['ha'.$n.'_'.$j].'">               
			<input type="text" name="a'.$n.'_'.$j.'" value="'.$ajelems['a'.$n.'_'.$j].'">
				<a href="javascript:popselect(\''.$n.'_'.$j.'\',\'a\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'a\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						<a onclick="delRow(\'a\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[-]</a>
						</nobr></td></tr>';
						
		 }
		 }
		 $temp.='
					
					</tbody>
					</table>';
		 $smarty->assign('sInput1',$temp);
		}else{
			//echo $a_pnr['ha'.$n];
        $smarty->assign('sInput1','<tr><td><nobr><input type="hidden" name="ha'.$n.'_0" value="'.$aj_pnr['ha'.$n.'_0'].'">                
				<input type="text" name="a'.$n.'_0" value="'.$ajelems['a'.$n.'_0'].'">
				<a href="javascript:popselect(\''.$n.'_0\',\'a\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'a\',\''.$i.'\',\'1\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						</nobr></td>
					</tr>
					</tbody>
					</table>');
					}
					//end trực sáng
    
//add by vy
	//start trực ngoài giờ
		$smarty->assign('sIcon3','<table cellspacing="1" cellpadding="0" border="0" id="mytestao'.$i.'">
		<tbody>
		
			');
		$aoj_pnr=unserialize($ao_pnr['hao'.$n]);	
		$aojelems=unserialize($aoelems['ao'.$n]);
		if(sizeof($aoj_pnr)>1){
		$temp='';
		 for($j=0;$j<sizeof($aoj_pnr);$j++){
		 if($j==0){
		  $temp.='<tr><td><nobr><input type="hidden" name="hao'.$n.'_'.$j.'" value="'.$aoj_pnr['hao'.$n.'_'.$j].'">               
			<input type="text" name="ao'.$n.'_'.$j.'" value="'.$aojelems['ao'.$n.'_'.$j].'">
				<a href="javascript:popselect(\''.$n.'_'.$j.'\',\'ao\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'ao\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						
						</nobr></td></tr>';
		 }else{
		 $temp.='<tr><td><nobr><input type="hidden" name="hao'.$n.'_'.$j.'" value="'.$aoj_pnr['hao'.$n.'_'.$j].'">               
			<input type="text" name="ao'.$n.'_'.$j.'" value="'.$aojelems['ao'.$n.'_'.$j].'">
				<a href="javascript:popselect(\''.$n.'_'.$j.'\',\'ao\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'ao\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						<a onclick="delRow(\'ao\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[-]</a>
						</nobr></td></tr>';
						
		 }
		 }
		 $temp.='
					
					</tbody>
					</table>';
		 $smarty->assign('sInputO1',$temp);
		}else{
			//echo $a_pnr['ha'.$n];
        $smarty->assign('sInputO1','<tr><td><nobr><input type="hidden" name="hao'.$n.'_0" value="'.$aoj_pnr['hao'.$n.'_0'].'">                
				<input type="text" name="ao'.$n.'_0" value="'.$aojelems['ao'.$n.'_0'].'">
				<a href="javascript:popselect(\''.$n.'_0\',\'ao\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'ao\',\''.$i.'\',\'1\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						</nobr></td>
					</tr>
					</tbody>
					</table>');
					}
     // end trực ngoài giờ  
	$smarty->assign('sIcon2','<table cellspacing="1" cellpadding="0" border="0" id="mytestr'.$i.'">
		<tbody>
		
			');
		$rj_pnr=unserialize($r_pnr['hr'.$n]);
		
		$rjelems=unserialize($relems['r'.$n]);
		if(sizeof($rj_pnr)>1){
		$temp='';
		 for($j=0;$j<sizeof($rj_pnr);$j++){
		 if($j==0){
		  $temp.='<tr><td><nobr><input type="hidden" name="hr'.$n.'_'.$j.'" value="'.$rj_pnr['hr'.$n.'_'.$j].'">               
			<input type="text" name="r'.$n.'_'.$j.'" value="'.$rjelems['r'.$n.'_'.$j].'">
				<a href="javascript:popselect(\''.$n.'_'.$j.'\',\'r\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'r\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						
						</nobr></td></tr>';
		 }else{
		 $temp.='<tr><td><nobr><input type="hidden" name="hr'.$n.'_'.$j.'" value="'.$rj_pnr['hr'.$n.'_'.$j].'">               
			<input type="text" name="r'.$n.'_'.$j.'" value="'.$rjelems['r'.$n.'_'.$j].'">
				<a href="javascript:popselect(\''.$n.'_'.$j.'\',\'r\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'r\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						<a onclick="delRow(\'r\',\''.$i.'\',\''.($j+1).'\');" href="javascript:;" alt="Thêm người">&nbsp;[-]</a>
						</nobr></td></tr>';
						
		 }
		 }
		 $temp.='
					
					</tbody>
					</table>';
		 $smarty->assign('sInput2',$temp);
		}else{
			//echo $a_pnr['ha'.$n];
        $smarty->assign('sInput2','<tr><td><nobr><input type="hidden" name="hr'.$n.'_0" value="'.$rj_pnr['hr'.$n.'_0'].'">                
				<input type="text" name="r'.$n.'_0" value="'.$rjelems['r'.$n.'_0'].'">
				<a href="javascript:popselect(\''.$n.'_0\',\'r\')">
                        <img src="../../gui/img/common/default/search_radio.jpg"></a>
						<a onclick="insertRow(\'r\',\''.$i.'\',\'1\');" href="javascript:;" alt="Thêm người">&nbsp;[+]</a>
						</nobr></td>
					</tr>
					</tbody>
					</table>');
					}

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