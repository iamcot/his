<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//error_reporting(E_ALL);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* , elpidio@care2x.org
*
* See the file "copy_notice.txt" for the licence notice
*/
//$db->debug=true;
/**
* Funtion prepareTestElemenst() will process the POST vars containg the test elements
* and other variables: sampling day & sampling time
* return: 1= if  test element(s) set, (paramlist is not empty),
* return: 0 = if no test element set, (paramlist empty)
*/
$sql="select personell_nr,name from care_users  where login_id='".$_SESSION['sess_login_userid']."'";
//echo $sql;
$temp=$db->execute($sql);
if($temp->recordcount())
{
	if($result=$temp->fetchrow()){
		$pers_nr=$result['personell_nr'];
		$pers_name=$result['name'];
	}else{
		$pers_nr='';
		$pers_name='';
	}
}
function prepareTestElements()
{
    global $_POST, $paramlist, $sday, $sample_time;		
	$paramlist='';					   
	while(list($x,$v)=each($_POST)){
    	if((substr($x,0,1)=='_')&&($_POST[$x]==1)){
	    	if($paramlist==''){
				$paramlist=$x.'=1';
			}else{
				$paramlist.='&'.$x.'=1';
			}
		}
	}								
	
	if($paramlist!=''){
		/* Prepare the sampling minutes */
		for($i=15;$i<46;$i=$i+15){
			$hmin="min_".$i;
			if($_POST[$hmin]){
				$tmin=$i;
				break;
			}
		}
		if(!$tmin) $tmin=0;							
		/* Prepare the sampling ten hours */
		if($_POST['hrs_20']) $th=20;
			elseif($_POST['hrs_10']) $th=10;
		for($i=0;$i<10;$i++){
			$h1s='hrs_'.$i;
			if($_POST[$h1s]){
				$to=$i;
				break;
			}
		}
		if(!$to) $to=0;				
		for($i=0;$i<7;$i++){
			$tday="day_".$i;
			if($_POST[$tday]){
				$sday=$i;
				break;
			}
		}
		$sample_time=($th+$to).":".$tmin.":00";								
		return 1;
	}else{
		return 0;
	}
}

/* Start initializations */


$lang_tables[]='departments.php';

define('LANG_FILE','konsil.php');

/* We need to differentiate from where the user is coming:
*  $user_origin != lab ;  from patient charts folder
*  $user_origin == lab ;  from the laboratory
*  and set the user cookie name and break or return filename
*/

if($user_origin=='lab'){
  //$local_user='ck_lab_user';
  $local_user='aufnahme_user';
  $breakfile=$root_path."modules/registration_admission/show_appointment_1.php".URL_APPEND."&pid=".$_SESSION['sess_pid']."&target=search&type_nr=4";
}else{
  $local_user='ck_pflege_user';
  $breakfile=$root_path."modules/nursing/nursing-station-patientdaten.php".URL_APPEND."&edit=$edit&station=$station&pn=$pn";
}

require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
$thisfile='nursing-station-patientdaten-doconsil-blood.php';
require_once($root_path.'include/core/inc_date_format_functions.php');
$bgc1='#fff3f3'; /* The main background color of the form */
$abtname=get_meta_tags($root_path."global_conf/$lang/konsil_tag_dept.pid");
$edit_form=0;
$read_form=0;
$db_request_table=$target;
$db_request_table_sub=$target . "_sub";
$paramlist='';
$sday='';
$sample_time='';
$data=array();
$blood_array=array('WBC','LYM','MID','GRA','LY%','MI%','GR%','RBC','HGB','HCT','MCV','MCH','MCHC','RDWc','PLT','PCT','MPV','PDWc');
$formtitle=$LDBloodBank;
define('_BATCH_NR_INIT_',40000000);
/*
*  The following are  batch nr inits for each type of test request
*   chemlabor = 10000000; patho = 20000000; baclabor = 30000000; blood = 40000000; generic = 50000000;
*/
						
/* Here begins the real work */
include_once($root_path.'include/care_api_classes/class_lab.php');
$lab_obj = new Lab;

/* Check for the patietn number = $pn. If available get the patients data, otherwise set edit to 0 */
if(isset($pn) && $pn) {
    include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	
	if($enc_obj->loadEncounterData($pn)){
		$edit=true;
		$full_en=$pn;
		$_SESSION['sess_en']=$pn;
		$_SESSION['sess_full_en']=$full_en;
		
		include_once($root_path.'include/care_api_classes/class_diagnostics.php');
		$diag_obj=new Diagnostics;
		$diag_obj->useBloodLabRequestTable();
		$diag_obj_sub = new Diagnostics;
		$diag_obj_sub->useBloodLabRequestSubTable();
		
	}else{
    	$edit=0;
	  	$mode='';
	  	$pn='';
   }
}

	if(!isset($mode)) $mode='';
	
	switch($mode){
		case 'save':
				  if(prepareTestElements())  {
					$data['batch_nr']=$batch_nr;
					$data['encounter_nr']=$pn;
					$data['room_nr']=$room_nr;
					$data['dept_nr']=$dept_nr;
					//$data['parameters']=$paramlist;
					$data['doctor_sign']=$doctor_sign;
					$data['highrisk']=$_highrisk_;
					$data['notes']=$notes;
					$data['send_date']=formatDate2STD($date,$date_format)." ".$time;
					$data['send_doctor']=$send_doctor;
					$data['send_doctor_nr']=$send_doctor_nr;
					$data['sample_time']=$sample_time;
					$data['sample_weekday']=$sday;
					$data['status']=$status;
					$data['urgent']=$urgent;
					$data['history']="Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
					$data['modify_id']=$_SESSION['sess_user_name'];
					$data['create_id']=$_SESSION['sess_user_name'];
					$data['create_time']='NULL';
					$diag_obj->setDataArray($data);
				    if($diag_obj->insertDataFromInternalArray()){
				    	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $diag_obj->getLastQuery(), date('Y-m-d H:i:s'));
				    	//sub values management
				    	//$diag_obj->useChemLabRequestSubTable();
				    	$singleParam = explode("&",$paramlist);
				    	foreach( $singleParam as $key => $value) {
				    		$tmpParam = explode("=",$value);
				    		$parsedParamList['batch_nr']=$batch_nr;
				    		$parsedParamList['encounter_nr']=$pn;
				    		$parsedParamList['paramater_name']=$tmpParam[0];
				    		$parsedParamList['parameter_value']=$tmpParam[1];
					    	$diag_obj_sub->setDataArray($parsedParamList);
					    	$diag_obj_sub->insertDataFromInternalArray();
				    	}
				    	//$eComBill->createBillItem($parsedParamList['encounter_nr'], $temp['bill_item_nr'],$temp1['item_unit_cost'], 1, $temp1['item_unit_cost'],date("Y-m-d G:i:s") );
					  	// Load the visual signalling functions
						include_once($root_path.'include/core/inc_visual_signalling_fx.php');
						// Set the visual signal
						setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REQUEST);
						//echo $sql;
						header("location:".$root_path."modules/laboratory/labor_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&noresize=$noresize&batch_nr=$batch_nr");
						exit;
					}else{
					     echo "<p>$sql<p>$LDDbNoSave";
						 $mode='';
					}
	            } //end of prepareTestElements()
					
				break; // end of case 'save'
							
			case 'update':
				if(prepareTestElements()){
					$data['room_nr']=$room_nr;
					$data['dept_nr']=$dept_nr;
					//$data['parameters']=$paramlist;
					$data['doctor_sign']=$doctor_sign;
					$data['highrisk']=$_highrisk_;
					$data['notes']=$notes;
					$data['send_date']=formatDate2STD($date,$date_format)." ".$time;
					$data['send_doctor']=$send_doctor;
					$data['send_doctor_nr']=$send_doctor_nr;
					$data['sample_time']=$sample_time;
					$data['sample_weekday']=$sday;
					$data['status']=$status;
					$data['urgent']=$urgent;
					$data['history']="CONCAT(history,'Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n')";
					$data['modify_id']=$_SESSION['sess_user_name'];
					$diag_obj->setDataArray($data);
					$diag_obj->setWhereCond(" batch_nr=$batch_nr");
					if($diag_obj->updateDataFromInternalArray($batch_nr)){
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $diag_obj->getLastQuery(), date('Y-m-d H:i:s'));
						//sub values management
				    	//$diag_obj->useChemLabRequestSubTable();
				    	//first i delete the old request values
				    	//then i insert the new ones.
				    	$diag_obj_sub->deleteOldValues($batch_nr,$pn);
				    	$singleParam = explode("&",$paramlist);
				    	foreach( $singleParam as $key => $value) {
				    		$tmpParam = explode("=",$value);
				    		$parsedParamList['batch_nr']=$batch_nr;
				    		$parsedParamList['encounter_nr']=$pn;
				    		$parsedParamList['paramater_name']=$tmpParam[0];
				    		$parsedParamList['parameter_value']=$tmpParam[1];
					    	$diag_obj_sub->setDataArray($parsedParamList);
					    	$diag_obj_sub->insertDataFromInternalArray();
				    	}						
						// Load the visual signalling functions
						include_once($root_path.'include/core/inc_visual_signalling_fx.php');
						// Set the visual signal
						setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REQUEST);
					 	header("location:".$root_path."modules/laboratory/labor_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=$edit&saved=update&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&batch_nr=$batch_nr&noresize=$noresize");
					 	exit;
					} else {
						echo "<p>$sql<p>$LDDbNoSave";
						$mode="";
					}
				} //end of prepareTestElements()
				
				break; // end of case 'update'
								
								
	        /* If mode is edit, get the stored test request when its status is either "pending" or "draft"
			*  otherwise it is not editable anymore which happens when the lab has already processed the request,
			*  or when it is discarded, hidden, locked, or otherwise.
			*
			*  If the "parameter" element is not empty, parse it to the $stored_param variable
			*/
			case 'edit':
						//echo $batch_nr;
		    //$sql="SELECT * FROM care_test_request_".$db_request_table."  WHERE batch_nr='".$batch_nr."' AND (status='pending' OR status='draft' OR status='')";
		    
		    $sql  = "SELECT * FROM care_test_request_".$db_request_table." ";
			$sql .= "INNER JOIN care_test_request_".$db_request_table_sub." ON ";
			$sql .= "( care_test_request_".$db_request_table.".batch_nr = care_test_request_".$db_request_table_sub.".batch_nr) ";
			$sql .= "WHERE care_test_request_".$db_request_table.".batch_nr='".$batch_nr."' ";
			$sql .= "AND (status='pending' OR status='draft' OR status='')";
		               // echo $sql;
						if($ergebnis=$db->Execute($sql)) {
						$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
				            if($editable_rows=$ergebnis->RecordCount()) {
							    while ( !$ergebnis->EOF ) {
									$stored_param[$ergebnis->fields['paramater_name']] = $ergebnis->fields['parameter_value'];
									$stored_request=$ergebnis->GetRowAssoc($toUpper=false);
									$ergebnis->MoveNext();
								}				            	
							    $edit_form=1;
					         }
			             }
						 break; ///* End of case 'edit': */
			
			 default: $mode="";
						   
		  }// end of switch($mode)
			
          if(!$mode) /* Get a new batch number */
		  {
		                $sql="SELECT batch_nr FROM care_test_request_".$db_request_table."  ORDER BY batch_nr DESC";
		                if($ergebnis=$db->SelectLimit($sql,1))
       		            {
				            if($batchrows=$ergebnis->RecordCount())
					        {
						       $bnr=$ergebnis->FetchRow();
							   $batch_nr=$bnr['batch_nr'];
							   if(!$batch_nr) $batch_nr=_BATCH_NR_INIT_; else $batch_nr++;
					         }
					         else
					         {
					            $batch_nr=_BATCH_NR_INIT_;
					          }
			             }
			               else {echo "<p>$sql<p>$LDDbNoRead"; exit;}
						 $mode="save";
		   }

if(!isset($edit)) $edit=FALSE;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle','Xét nghiệm :: Công thức máu');

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('request_chemlab.php','$pn')");

 # hide return  button
 $smarty->assign('pbBack',FALSE);

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle','Xét nghiệm :: Công thức máu');

 # Prepare new form start button
 if($user_origin=='lab' && $pn){
 	$smarty->assign('gifAux1',createLDImgSrc($root_path,'newpat2.gif','0'));
	$smarty->assign('pbAux1',$thisfile.URL_APPEND."&station=$station&user_origin=$user_origin&status=$status&target=$target&noresize=$noresize");
}

# Prepare Body onLoad javascript code
$sTemp = 'onLoad="if (window.focus) window.focus(); loadM(\'form_test_request\');';
if($pn=="") $sTemp = $sTemp .'document.searchform.searchkey.focus();';

$smarty->assign('sOnLoadJs',$sTemp .'"');

 # collect extra javascript code
 ob_start();
 require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
?>

<style type="text/css">
.lab {font-family: arial; font-size: 12; color:purple;}
.lmargin {margin-left: 5;}
</style>

<script language="javascript">
<!--

function chkForm(d){
   return true
}

function loadM(fn){
	mBlank=new Image();
	mBlank.src="b.gif";
	mFilled=new Image();
	mFilled.src="f.gif";
	
	form_name=fn;
}

function selectAllParams(group_id) {
	var r = document.getElementById('table_param');
	var rA = r.getElementsByTagName('*');
	var x,i = 0;
	while(x = rA[i++]){
		if(a = x.id) {
			param = a.substr(-(group_id.length),group_id.length);
			if( param == group_id) {
				setM(a);
			}
		}
	}
}

function setM(m){
	marker = document.images[m];
	element = document.forms[form_name][m];
    //eval("marker=document.images."+m);
	//eval("element=document."+form_name+"."+m);
	
    if(marker.src!=mFilled.src)	{
	   marker.src=mFilled.src;
	   element.value='1';
	}else{
	    marker.src=mBlank.src;
		element.value='0';
	 }
}

function setThis(prep,elem,begin,end,step){
  for(i=begin;i<end;i=i+step)  {
     x=prep + i;
     if(elem!=i)     {
       marker = document.images[x];
	   if(marker.src==mFilled.src)  setM(x);
     }
  }
  setM(prep+elem);
}
function popDocPer(target,obj_val,obj_name){
			urlholder="<?php echo $root_path; ?>modules/laboratory/personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
function sendLater(){
   document.form_test_request.status.value="draft";
   if(chkForm(document.form_test_request)) document.form_test_request.submit();
}

function printOut(){
	urlholder="<?php echo $root_path; ?>modules/laboratory/labor_test_request_printpop.php?sid=<?php echo $sid ?>&lang=<?php echo $lang ?>&user_origin=<?php echo $user_origin ?>&subtarget=<?php echo $target ?>&batch_nr=<?php echo $batch_nr ?>&pn=<?php echo $pn ?>&local_user=<?php echo $local_user?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    testprintout<?php echo $sid ?>.print();
}
$(function(){
$("#f-calendar-field-1").mask("99/99/9999");
$("#time").mask("99:99");
});
<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

//-->
</script>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

# Show list and actual form

 if(!$noresize){

?>

<script>
      window.moveTo(0,0);
	 window.resizeTo(1000,740);
</script>

<?php
}
?>

<ul>
<?php

if($edit){

	?>
	<form name="form_test_request" method="post" action="<?php echo $thisfile ?>">
	<?php
	
	/* If in edit mode display the control buttons */
	
	$controls_table_width=745;
	
	require($root_path.'modules/laboratory/includes/inc_test_request_controls.php');

}elseif(!$read_form && !$no_proc_assist){

?>

<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','absmiddle') ?>></td>
    <td class="prompt"><?php echo $LDPlsSelectPatientFirst ?></td>
    <td valign="bottom"><img <?php echo createComIcon($root_path,'angle_down_r.gif','0','',TRUE) ?>></td>
  </tr>
</table>
<?php
}
?>
   

<!-- outermost table for the form -->
<table border=0 cellpadding=1 cellspacing=0 bgcolor="#606060">
  <tr>
    <td>
	
	<!-- table for the form simulating the border -->
	<table border=0 cellspacing=0 cellpadding=0 bgcolor="white">
   <tr>
     <td>
	 
	 <!-- Here begins the table for the form  -->
	 
		<table   cellpadding=0 cellspacing=0 border=0 width=745>
	<tr  valign="top">

      <td bgcolor="<?php echo $bgc1 ?>">
	  <div class="lmargin">
	  <font size=3 color="#990000" face="arial">
       <?php echo $LDHospitalName ?><br>
       <?php echo $LDCentralLab ?><p><font size=2>
	   <?php echo $LDRoomNr ?>
	   <?php if($edit)
	   {
	   ?>
	    <input type="text" name="room_nr" size=10 maxlength=10
	    value="<?php
	                    if($edit_form||$read_form) echo stripslashes($stored_request['room_nr']);
						 else   echo $_COOKIE['ck_thispc_room']
				   ?>">
		<?php
		}
		else
		{
		   if($edit_form||$read_form) echo stripslashes($stored_request['room_nr']);
		}
		?>
	   <p>
	    <!--  Table for the day and month code -->
   <table border=0 cellspacing=0 cellpadding=0>
   <!-- Sampling time, day, minutes row -->
   <tr align="center">
   <td colspan=4><font size=1 face="arial" color= "purple"><?php echo $LDSamplingTime ?></td>
   <td colspan=3><font size=1 face="arial" color= "purple"><?php echo $LDDay ?></td>
   <td bgcolor= "#990000"><img src="p.gif" width=1 height=1></td>
   <td colspan=3><font size=1 face="arial" color= "purple"><?php echo $LDMinutes ?></td>

   </tr>
   <!-- Day row  -->
   <tr align="center">
   <?php
	for($i=1;$i<8;$i++)
	   echo 	 "<td><font size=1 face=\"verdana,arial\" color= \"#990000\">".$LDShortDay[$i]."</td>";
	?>
   <td bgcolor= "#990000"><img src="p.gif" width=1 height=1></td>
   <td><font size=1 face="verdana,arial" color= "#990000">15</td>
   <td><font size=1 face="verdana,arial" color= "#990000">30</td>
   <td><font size=1 face="verdana,arial" color= "#990000">45</td>

   </tr>

   <tr align="center">
   <?php
 
    if(($edit_form||$read_form))  $day_names=(int)$stored_request['sample_weekday'];
      else   $day_names=(int)date('w');
	  
    if(!$day_names) $day_names=7;
	
	for($i=1;$i<8;$i++)
	{
	   echo 	'
	   <td>';
	   if($edit) echo '<a href="javascript:setThis(\'day_\',\''.$i.'\',1,8,1)">';
	   if($day_names==$i)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6 id="day_'.$i.'">';
	   if($edit) echo '</a><input type="hidden" name="day_'.$i.'" value="'.$v.'">';
	   echo '</td>';
	}
	/* Divide line */
	echo  ' <td bgcolor= "#990000"><img src="p.gif" width=1 height=1></td>';
	
   if(($edit_form||$read_form) && $stored_request['sample_time'])
   {
      list($hour,$quarter_mins)=explode(":",$stored_request['sample_time']);
    }

	/* Get the quarter minutes*/
    if(!$edit_form&&!$read_form)
	{
	  $quarter_mins=(int)date('i');
	}
	 
   if($quarter_mins>44)
   {
     $quarter_mins=45;
   }
   elseif($quarter_mins>29)
   {
     $quarter_mins=30;
   }
   elseif($quarter_mins>14)
   {
     $quarter_mins=15;
   }
   else $quarter_mins=0;

	/* For the 10's */
	
      echo 	'<td>';
      if($edit) echo '<a href="javascript:setThis(\'min_\',\'15\',15,46,15)">';
	  if($quarter_mins==15)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6 id="min_15">';
	   if($edit) echo '</a><input type="hidden" name="min_15" value="'.$v.'">';
	   echo '</td>';

	   
	/* For the 30's */

	   echo 	'<td>';
	   if($edit) echo '<a href="javascript:setThis(\'min_\',\'30\',15,46,15)">';
	   if($quarter_mins==30)
      {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }

	   echo ' border=0 width=18 height=6 id="min_30">';
	   if($edit) echo '</a><input type="hidden" name="min_30" value="'.$v.'">';
	   echo '</td>';
	   
	/* For the 45's */

	   echo 	'<td>';
	   if($edit) echo '<a href="javascript:setThis(\'min_\',\'45\',15,46,15)">';
	   if($quarter_mins==45)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6 id="min_45">';
	   if($edit) echo '</a><input type="hidden" name="min_45" value="'.$v.'">';
	   echo '</td>';
	?>
   </tr>
   <!-- 10, 20 Time row -->
      <tr align="center">
   <td ><font size=1 face="arial" >&nbsp;</td>
   <td ><font size=1 face="verdana,arial" color= "#990000">10</td>
   <td><font size=1 face="verdana,arial" color= "#990000">20</td>
   <td colspan=8><font size=1 face="arial" color= "purple">&nbsp;</td>
   </tr>
   <!-- Input blocks for 10, 20 Time row -->
      <tr align="center">
   <td ><font size=1 face="arial" color= "purple"></td>
   <?php
   //ECHO $max_row;
   $hour_tens=0;
   $hour_ones=0;

    if(!$edit_form&&!$read_form)
	{
       $hour=(int)date('H');
	}
	
   if($hour>19)
   {
     $hour_tens=20;
	 $hour_ones=$hour-$hour_tens;
   }
   elseif($hour>9)
   {
     $hour_tens=10;
	 $hour_ones=$hour-$hour_tens;
   }
   else
   {
    $hour_ones=$hour;
   }
	   echo '
	   <td>';
	   if($edit) echo '<a href="javascript:setThis(\'hrs_\',\'10\',10,21,10)">';
	   if($hour_tens==10)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6 id="hrs_10">';
	   if($edit) echo '</a><input type="hidden" name="hrs_10" value="'.$v.'">';
	   echo '</td>';

	   echo '
	   <td>';
	   if($edit) echo '<a href="javascript:setThis(\'hrs_\',\'20\',10,21,10)">';
	   if($hour_tens==20)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6 id="hrs_20">';
	   if($edit) echo '</a><input type="hidden" name="hrs_20" value="'.$v.'">';
	   echo '</td>';
   ?>
   <td colspan=8><font size=1 face="arial" color= "purple"></td>

   </tr>
   
   <tr align="center">
   <?php
	for($i=0;$i<7;$i++)
	   echo 	 "<td><font size=1 face=\"verdana,arial\" color= \"#990000\">".$i."</td>";
	?>
   <td></td>
   <?php
	for($i=7;$i<10;$i++)
	   echo 	 "<td><font size=1 face=\"verdana,arial\" color= \"#990000\">".$i."</td>";
	?>
   </tr>
   <tr>
	<?php
   
	for($i=0;$i<7;$i++)
	{
	   echo 	'
	   <td>';
	   if($edit) echo '<a href="javascript:setThis(\'hrs_\',\''.$i.'\',0,10,1)">';
	   if($hour_ones==$i)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6  id="hrs_'.$i.'">';
	   if($edit) echo '</a><input type="hidden" name="hrs_'.$i.'" value="'.$v.'">';
	   echo '</td>';
	}
	?>
   <td></td>
	<?php
	for($i=7;$i<10;$i++)
	{
	   echo 	'
	   <td>';
	   if($edit) echo '<a href="javascript:setThis(\'hrs_\',\''.$i.'\',0,10,1)">';
	   if($hour_ones==$i)
	   {
	     echo '<img src="f.gif"';
		 $v="1";
	   }
	     else
	   {
	  	  echo  '<img src="b.gif"';
		  $v="0";
	    }
	   echo ' border=0 width=18 height=6 id="hrs_'.$i.'">';
	   if($edit) echo '</a><input type="hidden" name="hrs_'.$i.'" value="'.$v.'">';
	   echo '</td>';
	}
	?>
   </tr>
   <!-- urgjente -->
<tr align="center"  colspan=4>
   <td ><font size=1 face="arial" >&nbsp;</td>
   <td colspan="3"><font size=1 face="verdana,arial" color= "#990000">Khẩn cấp</td>
   <td><font size=1 face="arial" color= "purple">&nbsp;</td>
   <td ><font size=1 face="arial" color= "purple"></td>
   <?php
			echo '
			          <td '.$tdbgcolor.'>';
			if($edit) echo '<a href="javascript:setM(\'urgent\')">';
			if($edit_form||$read_form)
			{
			   if($stored_request['urgent'])
			   {
			      echo '<img src="f.gif"';
				  $inp_v=1;
				}
				else
				{
				  echo '<img src="b.gif"';
				}
			}
			else
			{
			   echo '<img src="b.gif"';
			}
			
			echo ' border=0 width=18 height=6 id="urgent">';
			if($edit) echo '</a><input type="hidden" name="urgent" value="'.$stored_request['urgent'].'">';
			'</td>';

   ?>
   <td colspan=8><font size=1 face="arial" color= "purple"></td>
 </tr>
<!-- end urgjente   -->
 </table>
 </div>
</td>

<!-- Middle block of first row -->
      <td bgcolor="<?php echo $bgc1 ?>">
		 <table border=0 cellpadding=10 bgcolor="#ee6666">
     <tr>
       <td>
   
<?php

      if($edit)
        {
		    echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
		}
        elseif(empty($pn))
		{
		    $searchmask_bgcolor='white';
            include($root_path.'modules/laboratory/includes/inc_test_request_searchmask.php');
        }
?>
</td>
     </tr>
   </table>
</td>


         <td  bgcolor="<?php echo $bgc1 ?>"  align="right">
<!--  Block for the casenumber codes -->
 <table border=0 cellspacing=0 cellpadding=0>
<?php

for($n=0;$n<8;$n++)
{

	if($n==2)
	{
	   echo '<tr><td colspan=10><img src="p.gif" width=1 height=2></td></tr>
	           <tr><td bgcolor="#ffcccc" colspan=10><img src="p.gif" width=1 height=1></td></tr>';
	 }
?>
   <tr align="center">
   <?php
	for($i=0;$i<10;$i++)
	   echo 	 "<td><font size=1 face=\"verdana,arial\" color= \"#990000\">".$i."</td>";
	?>
   </tr>

   
   <tr>
	<?php
	
	for($i=0;$i<10;$i++)
	{
	   echo 	'<td>';
	   if(substr($full_en,$n,1)==$i) echo '<img src="f.gif"';
	     else echo  '<img src="b.gif"';
	   echo ' border=0 width=18 height=6 align="absmiddle"></td>';
	}
	?>
   </tr>
<?php
}
?>

  <tr>
    <td colspan=10 align="right">
	<?php
    
	/* Barcode for the batch nr */
	
		    echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
    /**
	*  The barcode image is first searched in the cache. If present, it will be displayed.
	*  Otherwise an image will be generated, stored in the cache and displayed.
	*/
	$in_cache=1;
	
	if(!file_exists('../cache/barcodes/form_'.$batch_nr.'.png'))
	{
          echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5&label=1&form_file=1' border=0 width=0 height=0>";
	      if(!file_exists($root_path.'cache/barcodes/form_'.$batch_nr.'.png'))
	     {
             echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
			 $in_cache=0;
		 }
	}

    if($in_cache)   echo '<img src="'.$root_path.'cache/barcodes/form_'.$batch_nr.'.png"  border=0>';
	
	/* Prepare the narrow batch nr barcode for specimen labels */
	if(!file_exists('../cache/barcodes/lab_'.$batch_nr.'.png'))
	{
          echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=60&xres=1&font=5&label=1&form_file=lab' border=0 width=0 height=0>";
	}
?>
	</td>
  </tr>

 </table>

    </td>

	</tr>
<!--  The  row for batch number -->
	<tr bgcolor="<?php echo $bgc1 ?>">
	<td align="right"  colspan=3>
	<font size=1 color="purple" face="verdana,arial"><?php echo $LDBatchNumber ?><font color="#000000" size=2> <?php echo $batch_nr ?>
	<?php
	for($i=0;$i<30;$i++)
	{
	   if(substr($result['patnum'],$n,1)==$i) echo '<img src="f.gif"';
	     else echo  '<img src="b.gif"';
	   echo ' border=0 width=18 height=6 align="absmiddle">';
	}
	?>
    </td>

	</tr>
	
	</table>
	
<!--  The test parameters begin  -->
	
<table border=0 cellpadding=0 cellspacing=0 width=745 id=table_param bgcolor="<?php echo $bgc1 ?>">
 <?php

ob_start();
for($i=0;$i<=19;$i++) {
	echo '<tr class="lab">';
	for($j=0;$j<=1;$j++) {
     
			if($LD_Elements[$j][$i]['type']=='top') {
				//echo $LD_Elements[$j][$i]['value'];
				echo '<td bgcolor="#ee6666" colspan="2" onclick="selectAllParams(\''.$LD_Elements[$j][$i]['id'].'\');"><font color="white" style="cursor : pointer;">&nbsp;<b>'.$parametergruppe[$LD_Elements[$j][$i]['value']].'</b></font></td>';
				
			} else {
				if($LD_Elements[$j][$i]['value']) {
				
					echo '<td>';
					if($edit) {
						if( isset($stored_param[$LD_Elements[$j][$i]['id']]) && !empty($stored_param[$LD_Elements[$j][$i]['id']])) {
							echo '<input type="hidden" name="'.$LD_Elements[$j][$i]['id'].'" value="1">
							<a href="javascript:setM(\''.$LD_Elements[$j][$i]['id'].'\')">';
						} else {
							echo '<input type="hidden" name="'.$LD_Elements[$j][$i]['id'].'" value="0">
							<a href="javascript:setM(\''.$LD_Elements[$j][$i]['id'].'\')">';							
						}
					}				
					if( isset($stored_param[$LD_Elements[$j][$i]['id']]) && !empty($stored_param[$LD_Elements[$j][$i]['id']])) {
						echo '<img src="f.gif" border=0 width=18 height=6 id="'.$LD_Elements[$j][$i]['id'].'">';
					} else {
						echo '<img src="b.gif" border=0 width=18 height=6 id="'.$LD_Elements[$j][$i]['id'].'">';
					} if($edit) {
						echo '</a>';
					}
					echo '</td><td>';
					if($edit) echo '<a href="javascript:setM(\''.$LD_Elements[$j][$i]['id'].'\')">'.$LD_Elements[$j][$i]['value'].'</a>';
					else echo $LD_Elements[$j][$i]['value'];
					echo '</td>';
				} else {
					echo '<td colspan=2>&nbsp;</td>';
				}
			}
	}
	echo '</tr><tr>';
	if($i<$max_row) {
  		for($k=0;$k<=$column;$k++) {
  			echo '<td bgcolor="#ffcccc" colspan=2><img src="p.gif"  width=1 height=1></td>';
  	}
  	echo '</tr>';
	}
}

//$sTemp=ob_get_contents();
ob_end_flush();
//echo $sTemp;
?>
  <td colspan="10" align="left"><div class=fva2_ml10><font color="#000099">
			 <?php echo "Ngày gởi: ";

							//gjergji : new calendar
			
			//end : gjergji
			if ($stored_request['send_date']=="")
				$dateshow=date("Y-m-d G:i:s");
			else $dateshow=$stored_request['send_date'];
			
			echo $calendar->show_calendar($calendar,$date_format,'date',$dateshow);
			if(isset($stored_request['send_date']))
			{echo '<input type="text" size="5" id="time" name="time" value="'.@convertTimeToLocal(formatDate2Local($stored_request['send_date'],$date_format,0,1)).'">';
			}else{
			echo '<input type="text" size="5" id="time" name="time" value="'.date("H:i").'">';
			}
			//end gjergji ?>
		
			<?php echo 'BS gửi yêu cầu' ?>:
			<input type="text" name="send_doctor" size=37 maxlength=40 value="<?php if($edit_form || $read_form) echo $stored_request['send_doctor'];else echo $pers_name;?>">
			<input type="hidden" name="send_doctor_nr" value="<?php if(!empty( $stored_request['send_doctor_nr'])) echo $stored_request['send_doctor_nr'];else echo $pers_nr; ?>"> <a href="javascript:popDocPer('doctor_nr','send_doctor_nr','send_doctor')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>
			</div><br>
		</td>
  <tr>
    <td colspan=10><font size=2 face="verdana,arial" color="purple">&nbsp;<?php echo $LDEmergencyProgram.' &nbsp;&nbsp;&nbsp;<img '.createComIcon($root_path,'violet_phone.gif','0','absmiddle',TRUE).'> '.$LDPhoneOrder ?></td>
  </tr>

</table><!-- End of the main table holding the form -->
 
 	 </td>
   </tr>
 </table><!-- End of table simulating the border -->
 
	</td>
  </tr>
</table><!--  End of the outermost table bordering the form -->
<p>

<?php
if($edit)
{

/* If in edit mode display the control buttons */
require($root_path.'modules/laboratory/includes/inc_test_request_controls.php');

require($root_path.'modules/laboratory/includes/inc_test_request_hiddenvars.php');

?>

</form>

<?php
}
?>

</ul>

<?php

$sTemp = ob_get_contents();
 ob_end_clean();

# Assign the page output to main frame template

 $smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>