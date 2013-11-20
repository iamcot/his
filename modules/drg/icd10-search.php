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
$lang_tables[]='aufnahme.php';
define('LANG_FILE','drg.php');define('NO_2LEVEL_CHK',1);
//require_once('drg_inc_local_user.php');
define('MAX_BLOCK_ROWS',30); 
require_once($root_path.'include/core/inc_front_chain_lang.php');
//if (!isset($opnr) || !$opnr) {header("Location:".$root_path."language/".$lang."/lang_".$lang."_invalid-access-warning.php"); exit;};

# Create drg object
require_once($root_path.'include/care_api_classes/class_drg.php');
$drg=& new DRG;

///$db->debug=true;

$toggle=0;
$thisfile='icd10-search.php';
if(!isset($mode)) $mode='';

// Initialize page's control variables
if($mode=='paginate'){
	$searchkey=$_SESSION['sess_searchkey'];
}else{
	# Reset paginator variables
	$pgx=0;
	$totalcount=0;
	$odir='';
	$oitem='';
	
}
require_once($root_path.'include/care_api_classes/class_paginator.php');
$pagen=new Paginator($pgx,$thisfile,$_SESSION['sess_searchkey'],$root_path);

if(isset($mode)&&($mode=='search'||$mode=='paginate')&&isset($searchkey)&&($searchkey)){
	
if($mode!='paginate'){
		$_SESSION['sess_searchkey']=$searchkey;
	}	
	$searchkey=trim($searchkey);
	$GLOBAL_CONFIG=array();
		include_once($root_path.'include/care_api_classes/class_globalconfig.php');
		$glob_obj=new GlobalConfig($GLOBAL_CONFIG);

		# Get the max nr of rows from global config
		$glob_obj->getConfig('pagin_patient_search_max_block_rows');
		if(empty($GLOBAL_CONFIG['pagin_patient_search_max_block_rows'])) $pagen->setMaxCount(MAX_BLOCK_ROWS); # Last resort, use the default defined at the start of this page
			else $pagen->setMaxCount($GLOBAL_CONFIG['pagin_patient_search_max_block_rows']);
	if(!empty($searchkey)){
	 
		$fielddata='diagnosis_code,description,sub_level,inclusive,exclusive,notes,remarks,extra_subclass,extra_codes,std_code';
		
		# Search routine starts here

		if(strlen($searchkey)<3){
		
			# Added the special case of Bosnian (or Serbian) version with the latin description in the "description" field
			#and the actual local language version in the "note" field
			if($lang=="bs" || $lang == "sr") $sAddWhere= "OR notes $sql_LIKE '$searchkey%'";
				else $sAddWhere ='';

			$sql="SELECT $fielddata FROM $drg->tb_diag_codes WHERE (diagnosis_code $sql_LIKE '%$searchkey%' OR description $sql_LIKE '$searchkey%' OR description $sql_LIKE '%$searchkey%' $sAddWhere) AND type <> 'table' ORDER BY diagnosis_code";

			}else{

			# Added the special case of Bosnian (or Serbian) version with the latin description in the "description" field
			#and the actual local language version in the "note" field
			if($lang=="bs" || $lang == "sr") $sAddWhere= "OR notes $sql_LIKE '%$searchkey%'";
				else $sAddWhere ='';

				$sql="SELECT $fielddata FROM $drg->tb_diag_codes WHERE (diagnosis_code $sql_LIKE '%$searchkey%' OR description $sql_LIKE '%$searchkey%' $sAddWhere) AND type <> 'table' ORDER BY diagnosis_code";
			
			}
//echo $sql;
		$ergebnis=$db->SelectLimit($sql,$pagen->MaxCount(),$pagen->BlockStartIndex());
		if($ergebnis){
			$linecount=0;
			if ($linecount=$ergebnis->RecordCount()){
				$pagen->setTotalBlockCount($linecount);
					
					# If more than one count all available
					if(isset($totalcount) && $totalcount){
						$pagen->setTotalDataCount($totalcount);
					}else{
						# Count total available data
						if($dbtype=='mysql' ){
							$sql="SELECT COUNT(diagnosis_code) AS 'count' FROM $drg->tb_diag_codes WHERE (diagnosis_code $sql_LIKE '%$searchkey%' OR description $sql_LIKE '%$searchkey%' $sAddWhere) ORDER BY diagnosis_code ";
							echo $sql;
						}else{
							$sql='SELECT * FROM'.$drg->tb_diag_codes;
						}

						if($result=$db->Execute($sql)){
							if ($totalcount=$result->RecordCount()) {
								if($dbtype=='mysql'){
									$rescount=$result->FetchRow();
    									$totalcount=$rescount['count'];
									//	echo $totalcount;
								}
    							}
						}
						$pagen->setTotalDataCount($totalcount);
					}
					# Set the sort parameters
					$pagen->setSortItem($oitem);
					$pagen->setSortDirection($odir);
			}
		}else {echo "<p>".$sql."<p>$LDDbNoRead"; };
	}
}

/* Load the icon images */


?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
 <TITLE><?php echo $LDIcd10Search ?></TITLE>
 <script language="javascript" src="<?php echo $root_path; ?>js/showhide-div.js">
</script>
  <script language="javascript">
<!-- 
function prufdiag(d)
{
	if((d.searchkey.value=="")||(d.searchkey.value==" ")) return false;
}

// -->
</script>
 
<?php 
require($root_path.'include/core/inc_css_a_hilitebu.php');
?>
 
</HEAD>

<BODY  >

<FONT    SIZE=-1  FACE="Arial">
<ul>
<FORM method="post" name="searchdatadiag" onSubmit="return prufdiag(this)">
<a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> align="right"></a>

<FONT    SIZE=3  FACE="verdana,Arial" color="#0000aa"><b><?php echo $LDIcd10 ?></b>&nbsp;
</font>
<font size=3><INPUT type="text" name="searchkey" size="50" maxlength="60" onfocus="this.select()" value="<?php echo $searchkey ?>"></font> 
<br>
<INPUT type="submit" name="versand" value="<?php echo $LDSearch ?>">
<input type="hidden" value="search" name="target">
<input type="hidden" name="sid" value="<?php echo $sid; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="pn" value="<?php echo $pn; ?>">
<input type="hidden" name="mode" value="search">
</FORM>
<p>

<table border=0 cellpadding=1 cellspacing=1 width='100%'> 
<tr bgcolor=#0000aa>
<td><font size=2 color=#ffffff>&nbsp;<b><?php echo $LDIcd10 ?></b>&nbsp;</td>
<td colspan=7><font size=2 color=#ffffff>&nbsp;<b><?php echo $LDDescription ?></b>
</td>
		
</tr>

<?php
if($mode=='search'||$mode=='paginate'){
			if ($linecount>0) 
				{ 
					
					while($zeile=$ergebnis->FetchRow())
					{ 
						echo '<tr class="wardlistrow1">
						<td>
							'.$zeile['diagnosis_code'].'
						</td>
						<td>
							'.$zeile['description'].'
						</td>
						</tr>
						';
					}
					echo '
						<tr>
							<td>
								'.$pagen->makePrevLink($LDPrevious).'
							</td>
							<td align=right>
								'.$pagen->makeNextLink($LDNext).'
							</td>
						</tr>
					';
				}
}				
?>

</table>


</ul>
&nbsp;
</FONT>


</FONT>


</BODY>
</HTML>
