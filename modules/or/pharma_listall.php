<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('MAX_BLOCK_ROWS',30);
    $lang_tables=array('or.php');
    define('LANG_FILE','aufnahme.php');

    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    require_once($root_path.'include/care_api_classes/class_access.php');
    $access= & new Access();
    if($_SESSION['sess_login_username']!='admin'){
        $role= $access->checkNameRole($_SESSION['sess_login_username']);
        if(strpos($role['dept_nr'], '"'.$dept_nr.'"')==0 && $role['location_nr']!=$dept_nr){
            header("Location:../../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
            exit;
        }
    }
    require_once($root_path.'include/core/inc_date_format_functions.php');

    //$db->debug=true;

    if($_COOKIE['ck_login_logged'.$sid]) $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
            else $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;

    $thisfile=basename(__FILE__);
    if(empty($oitem)) $oitem='product_name';
    # Initialize page�s control variables
    if($mode!='paginate'){
            # Reset paginator variables
            $pgx=0;
            $totalcount=0;
            $odir='';
            $oitem='';
    }
    #Load and create paginator object
    require_once($root_path.'include/care_api_classes/class_paginator.php');
    $pagen=new Paginator($pgx,$thisfile,$_SESSION['sess_searchkey'],$root_path);

    require_once($root_path.'include/care_api_classes/class_pharma_dept.php');
    $pharma=new Pharma_Dept;

    $GLOBAL_CONFIG=array();

    # Get the max nr of rows from global config
    require_once($root_path.'include/care_api_classes/class_globalconfig.php');
    $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
    //Mặc định ứng với type 'pagin_personell_list_max_block_rows' là 20 record
    $glob_obj->getConfig('pagin_personell_list_max_block_rows');
    if(empty($GLOBAL_CONFIG['pagin_personell_list_max_block_rows'])) $pagen->setMaxCount(MAX_BLOCK_ROWS); # Last resort, use the default defined at the start of this page
            else $pagen->setMaxCount($GLOBAL_CONFIG['pagin_personell_list_max_block_rows']);


    if(empty($odir)) $odir='ASC'; # default, ascending alphabetic
    # Set the sort parameters
    $pagen->setSortItem($oitem);
    $pagen->setSortDirection($odir);
    $toggle=0;

    $product_encoder=$pharma->getAllPharmaInDept($dept_nr,$ward_nr,$odir);
    //phân trang		  
    if($ergebnis=$db->SelectLimit($product_encoder,$pagen->MaxCount(),$pagen->BlockStartIndex())){
            if ($linecount=$ergebnis->RecordCount()){ 
                if(($linecount==1) && $numeric){
                    $zeile=$ergebnis->FetchRow();
                    header("location:personell_register_show.php".URL_REDIRECT_APPEND."&from=such&target=personell_listall&personell_nr=".$zeile['nr']."&sem=".(!$zeile['is_discharged']));
                    exit;
                }
            }
            $pagen->setTotalBlockCount($linecount);

            //Đếm số dòng để xuất dữ liệu
            if(isset($totalcount) && $totalcount){
                    $pagen->setTotalDataCount($totalcount);
            }else{
                    if ($count=$pharma->countnumberPharma($dept_nr,$ward_nr)) {
                        $totalcount=$count;
                    }
                    $pagen->setTotalDataCount($totalcount);
            }
    }else{
            echo "<p>$sql<p>$LDDbNoRead";
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
    $smarty->assign('sToolbarTitle', "$LDManagePharma :: $LDListPharma ");

    # hide return button
    $smarty->assign('pbBack',FALSE);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('employee_all.php')");

    # href for close button
    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('sWindowTitle',"$LDManagePharma :: $LDListPharma");

    # Colllect javascript code

    ob_start();

?>
<table width=100% border=0 cellspacing="0" cellpadding=0>

<!-- Load tabs -->
<?php

$target='personell_listall';
 include('./gui_bridge/default/gui_tabs_personell_reg.php') 

?>

</table>
<ul>

<?php

if ($linecount) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
	else echo str_replace('~nr~','0',$LDSearchFound); 
	if ($linecount) { 
	echo '<table border=0 cellpadding=2 cellspacing=1> <tr class="wardlisttitlerow">';

?>
      <td align="center" width="40%">
          <b>
	  <?php 
                echo $LDNamePharma;
	 ?>
          </b>
      </td>
      <td  align="center" width="22%">
          <b>
	  <?php 
		echo $LDNumber; 
          ?>
          </b>
      </td>
      <td align="center" width="10%">
          <b>
	  <?php 
		echo $LDUnit; 
	  ?>
          </b>
      </td>

</tr>
<?php
	while($zeile=$ergebnis->FetchRow()){
                echo setCharSet();
                echo '
                        <tr class=';
                if($toggle) { echo "wardlistrow2>"; $toggle=0;} else {echo "wardlistrow1>"; $toggle=1;};
                $encoder=$pharma->getPharmaInfo($zeile['product_encoder'],$dept_nr,$ward_nr);
		echo '<td >';
                echo '&nbsp;'.ucfirst($encoder['product_name']).'</td>';
                $number=$pharma->getnumberPharma($zeile['product_encoder'],$dept_nr,$ward_nr);
		echo '<td align="center">';
                echo '&nbsp;'.ucfirst($number['number']).'</td>';
                $unit=$pharma->getunitPharma($zeile['product_encoder'],$dept_nr,$ward_nr);
		echo '<td align="center">';
			echo '&nbsp;'.ucfirst($unit['unit_name_of_medicine']);
			echo '</td></tr>';

		}
		echo '<tr>
                        <td colspan="2">'.$pagen->makePrevLink($LDPrevious).'</td>
			<td align=right>'.$pagen->makeNextLink($LDNext).'</td>
                    </tr>
                    </table>';
	}
?>
<form name="managepharma" action="<?php echo $root_path.'modules/nursing/nursing-manage-medicine.php'?>">
    <input type="submit" value="<?php echo $LDManageActivePharma ?>" />
    <input type="hidden" name="ntid" value="false" />
    <input type="hidden" name="lang" value="<?php echo $lang ?>" />
    <input type="hidden" name="target" value="pres" />    
    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr?>" />
    <input type="hidden" name="ward_nr" value="<?php echo $ward_nr?>" />
</form>
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
