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
# Default value for the maximum nr of rows per block displayed, define this to the value you wish
# In normal cases this value is derived from the db table "care_config_global" using the "pagin_insurance_list_max_block_rows" element.
define('MAX_BLOCK_ROWS',30); 

$lang_tables=array('personell.php');
define('LANG_FILE','or.php');
$local_user='ck_opdoku_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

///$db->debug=true;

# If a forwarded nr is available, convert it to searchkey and set mode to "search"
if(isset($fwd_nr) && $fwd_nr){
	$searchkey=$fwd_nr;
	$mode='search';
}else{
	# Translate *? wildcards	
	$searchkey=strtr($searchkey,'*?','%_');
}
$thisfile=basename(__FILE__);
$toggle=0;
if($_COOKIE['ck_login_logged'.$sid]) $breakfile=$root_path.'main/spediens.php'.URL_APPEND;
	else $breakfile='spediens-bdienst-zeit-erfassung.php'.URL_APPEND.'retpath=op&encoder=';
 /* Set color values for the search mask */
$searchmask_bgcolor='#f3f3f3';
$searchprompt=$LDEnterEmployeeSearchKey;
$entry_block_bgcolor='#fff3f3';
$entry_border_bgcolor='#6666ee';
$entry_body_bgcolor='#ffffff';

if(!isset($searchkey)) $searchkey='';
if(!isset($mode)) $mode='';


# Initialize page´s control variables
if($mode=='paginate'){
	$searchkey=$_SESSION['sess_searchkey'];
}else{
	# Reset paginator variables
	$pgx=0;
	$totalcount=0;
	$odir='';
	$oitem='';
}
#Load and create paginator object
require_once($root_path.'include/care_api_classes/class_paginator.php');
$pagen=new Paginator($pgx,$thisfile,$_SESSION['sess_searchkey'],$root_path);

if(isset($mode)&&($mode=='search'||$mode=='paginate')&&isset($searchkey)&&($searchkey)){
	
	include_once($root_path.'include/core/inc_date_format_functions.php');

	if($mode!='paginate'){
		$_SESSION['sess_searchkey']=$searchkey;
	}	
		# convert * and ? to % and &
		$searchkey=strtr($searchkey,'*?','%_');
            
		$GLOBAL_CONFIG=array();
			
		include_once($root_path.'include/care_api_classes/class_globalconfig.php');
		$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
		$glob_obj->getConfig('personell_nr_adder');
		
		# Get the max nr of rows from global config
		$glob_obj->getConfig('pagin_personell_search_max_block_rows');
		if(empty($GLOBAL_CONFIG['pagin_personell_search_max_block_rows'])) $pagen->setMaxCount(MAX_BLOCK_ROWS); # Last resort, use the default defined at the start of this page
			else $pagen->setMaxCount($GLOBAL_CONFIG['pagin_personell_search_max_block_rows']);		
		
	   	$searchkey=trim($searchkey);
		$suchwort=$searchkey;
		
		if(is_numeric($suchwort)) {
            $suchwort=(int) $suchwort;
			//$numeric=1;
			if($suchwort<$GLOBAL_CONFIG['personell_nr_adderr']){
				   $suchbuffer=(int) ($suchwort + $GLOBAL_CONFIG['personell_nr_adder']) ; 
			}
			
			if(empty($oitem)) $oitem='date';			
			if(empty($odir)) $odir='DESC'; # default, latest pid at top
			
			$sql2=" WHERE ( date $sql_LIKE '$suchwort%' OR date $sql_LIKE '%$suchwort%' ";
			
	    } else {
			# Try to detect if searchkey is composite of first name + last name
			
			$searchkey=strtr($searchkey,',',' ');
			
			
			if(empty($oitem)) $oitem='date';
			
			# Check the size of the comp
			$DOB=formatDate2STD($suchwort,$date_format);
				$sql2=" WHERE (standby_name $sql_LIKE '%$suchwort%' OR standby_name $sql_LIKE '$suchwort%' OR oncall_name $sql_LIKE '%$suchwort%' OR oncall_name $sql_LIKE '$suchwort%' ";
				if($DOB) $sql2.=" OR date $sql_LIKE '$DOB' OR date $sql_LIKE '%$DOB'";
					else $sql2.=')';
				if(empty($odir)) $odir='ASC'; # default, ascending alphabetic
			
		}

			$sql2.=" AND status NOT IN ('void','hidden','deleted','inactive')
						 )";
			# Filter if it is personnel nr
			if($oitem=='date') $sql3.='ORDER BY '.$oitem.' '.$odir;
				else $sql3 ='ORDER BY '.$oitem.' '.$odir;

			$dbtable='FROM care_standby_duty_report ';

			$sql='SELECT * '.$dbtable.$sql2.$sql3;
			//echo $sql;

			if($ergebnis=$db->SelectLimit($sql,$pagen->MaxCount(),$pagen->BlockStartIndex()))
       		{
				if ($linecount=$ergebnis->RecordCount()) 
				{ 
					
					# Set the object to actual nr of rows
					$pagen->setTotalBlockCount($linecount);
					
					# If more than one count all available
					if(isset($totalcount) && $totalcount){
						$pagen->setTotalDataCount($totalcount);
					}else{

						# Count total available data
						$sql='SELECT COUNT(date) AS count '.$dbtable.$sql2;
						
						if($result=$db->Execute($sql)){
							if ($result->RecordCount()) {
								$rescount=$result->FetchRow();
    								$totalcount=$rescount['count'];
    						}
						}
						$pagen->setTotalDataCount($totalcount);
					}
					# Set the sort parameters
					$pagen->setSortItem($oitem);
					$pagen->setSortDirection($odir);
					//echo $sql;
				}
			}
			 else {echo "<p>".$sql."<p>$LDDbNoRead";};
} else { 
    $mode='';
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"$LDOnCallDuty :: $LDScheduler :: $LDSearch");

 # hide return button
 $smarty->assign('pbBack',FALSE);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('employee_search.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDOnCallDuty :: $LDScheduler :: $LDSearch");

 # Body onLoad Javascript code
 $smarty->assign('sOnLoadJs','onLoad="document.searchform.searchkey.select()"');

# Colllect javascript code

ob_start();

?>


<ul>
	 <table border=0 cellpadding=10 bgcolor="<?php echo $entry_border_bgcolor ?>">
     <tr>
       <td>
	   <?php

            include($root_path.'include/core/inc_patient_searchmask.php');
       
	   ?>
</td>
     </tr>
   </table>

<p>
<a href="<?php  echo $breakfile; ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a>
<p>

<?php
if($mode=='search'||$mode=='paginate'){

	if ($linecount) echo '<hr width=80% align=left>'.str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
		else echo str_replace('~nr~','0',$LDSearchFound); 
		  
	if ($linecount) { 

	# Load the common icons
	$img_options=createComIcon($root_path,'statbel2.gif','0','',TRUE);
	$img_male=createComIcon($root_path,'spm.gif','0','',TRUE);
	$img_female=createComIcon($root_path,'spf.gif','0','',TRUE);

	echo '
			<table border=0 cellpadding=2 cellspacing=1> <tr class="wardlisttitlerow">';
			
?>

      <td><b>
	  <?php 
	  	if($oitem=='date') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDDate,'date',$odir,$flag); 
			 ?></b></td>
      <td><b>
	  <?php 
	  	if($oitem=='standby_name') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDStandbyName,'standby_name',$odir,$flag); 
			 ?></b></td>
     
      <td><b>
	  <?php 
	  	if($oitem=='standby_start') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDStandByStart,'standby_start',$odir,$flag); 
			 ?></b></td>
      <td><b>
	  <?php 
	  	if($oitem=='standby_end') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDStandByEnd,'standby_end',$odir,$flag); 
			 ?></b></td>
	   <td><b>
	  <?php 
	  	if($oitem=='oncall_name') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDOncallName,'oncall_name',$odir,$flag); 
			 ?></b></td>
      <td align='center'><b>
	  <?php 
	  	if($oitem=='oncall_start') $flag=TRUE;
			else $flag=FALSE;
		 echo $pagen->SortLink($LDOncallStart,'oncall_start',$odir,$flag); 
		 	
		?></b></td>
		 <td><b>
	  <?php 
	  	if($oitem=='oncall_end') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDOncallEnd,'oncall_end',$odir,$flag); 
			 ?></b></td>
		
    <td background="<?php echo createBgSkin($root_path,'tableHeaderbg.gif'); ?>"><font color="#ffffff"><b><?php echo $LDOptions; ?></td>

<?php
					echo"</tr>";

					while($zeile=$ergebnis->FetchRow())
					{
						
						echo "
							<tr class=";
						if($toggle) { echo "wardlistrow2>"; $toggle=0;} else {echo "wardlistrow1>"; $toggle=1;};
						echo"<td>";
                       // echo '&nbsp;'.($zeile['nr']+$GLOBAL_CONFIG['personell_nr_adder']);
                         echo '&nbsp;'.formatDate2Local($zeile['date'],$date_format);
                       echo "</td>";	
					   
					
					   
						echo"<td>";
						echo "&nbsp;".ucfirst($zeile['standby_name']);
                        echo "</td>";
						echo"<td>";
						echo "&nbsp;".$zeile['standby_start'];
                        echo "</td>";		
						echo"<td>";
						echo "&nbsp;".$zeile['standby_end'];
                        echo "</td>";
						echo"<td>";
						echo "&nbsp;".ucfirst($zeile['oncall_name']);
                        echo "</td>";
						
						echo"<td>";
						echo "&nbsp;".$zeile['oncall_start'];
                        echo "</td>";	
						echo"<td>";
						echo "&nbsp;".$zeile['oncall_end'];
                        echo "</td>";	
                        echo '</td>
					   ';	

					 

					}
					echo '
						<tr><td colspan=6>'.$pagen->makePrevLink($LDPrevious).'</td>
						<td align=right>'.$pagen->makeNextLink($LDNext).'</td>
						</tr>
						</table>';
					if($linecount>$pagen->MaxCount())
					{
					    /* Set the appending nr for the searchform */
					    $searchform_count=2;
					?>
			<p>
		 <table border=0 cellpadding=10 bgcolor="<?php echo $entry_border_bgcolor ?>">
     <tr>
       <td>
	   <?php
            include($root_path.'include/core/inc_patient_searchmask.php');
	   ?>
</td>
     </tr>
   </table>
					<?php
					}
	}
}
?>

</ul>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
