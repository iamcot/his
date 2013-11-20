<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    require_once($root_path.'global_conf/inc_global_address.php');
    $lang='vi';
    define('LANG_FILE','pharma_put_in.php');
    $user_origin=='ck_prod_order_user';
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    include_once($root_path.'include/care_api_classes/class_pharma.php');

    if(!isset($Pharma)) $Pharma = new Pharma;
		
    $thisfile= basename(__FILE__);
    $breakfile=$root_path.'modules/pharmacy/allocation.php'.URL_APPEND;

    $bgc1='#ffffff'; /* The main background color of the form */
    $edit_form=0; /* Set form to non-editable*/
    $read_form=1; /* Set form to read */
    $edit=0; /* Set script mode to no edit*/

    if(!isset($mode)) $mode='';
    
    /* Get pending putin */
    if ($search==''){
            $condition=" WHERE status_finish='0' ORDER BY create_time";
    }
    else{
        if (strrpos($search,'/') || strrpos($search,'-')){
                $search = formatDate2STD($search,'dd/mm/yyyy');
                $condition=" WHERE status_finish='0' AND create_time LIKE '".$search."%' ORDER BY create_time DESC";
        }
        else
                $condition=" WHERE status_finish='0' AND put_in_id LIKE '%".$search."%' ORDER BY put_in_id";
        $breakfile = $thisfile.URL_APPEND;
    }
    $list_report = $Pharma->listAllPutInChemical($condition);
    if(is_object($list_report)){
    $batchrows = $list_report->RecordCount();		

    if($batchrows && (!isset($report_id) || !$report_id)){ 			// Check for the report_id = $put_in_id. If available get the patients data to show 
            $report_show = $list_report->FetchRow();		
            $report_id = $report_show['put_in_id'];
    }

    }else{
?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php
    echo "$LDReportNotFound<br>";
    echo '<center><a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'back2.gif','0').'></a></center>';
    exit;
    }


    /* Check for the report id = $put_in_id. If available get the patients data */
    if($batchrows && $report_id){

    //GET DATA PRESCRIPTION (get all medicine in this reportpaper)

    if($chemical_in_report = $Pharma->getDetailPutInChemicalInfo($report_id)){
            if($chemical_count = $chemical_in_report->RecordCount()){
                    $edit_form=1;
            }		
    }else{
            $mode='';
            $report_id='';
    }
    }

    # Prepare title
    $sTitle = $LDPendingPutInRequest;
    if($batchrows) $sTitle = $sTitle." (".$LDIssueId.': '.$report_id.")";


    # Start Smarty templating here
    /**
    * LOAD Smarty
    */

    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('nursing');

    # Title in toolbar
    $smarty->assign('sToolbarTitle',$sTitle);

    # hide back button
    $smarty->assign('pbBack',FALSE);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('pending_radio.php')");

    # href for close button
    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('sWindowTitle',$sTitle);

    $smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();"');

    # Collect extra javascript code

    ob_start();
?>

<style type="text/css">
    div.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10;}
    div.fa2_ml10 {font-family: arial; font-size: 12; margin-left: 10;}
    div.fva2_ml3 {font-family: verdana; font-size: 12; margin-left: 3; }
    div.fa2_ml3 {font-family: arial; font-size: 12; margin-left: 3; }
    .fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva2b_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva0_ml10 {font-family: verdana,arial; font-size: 10; margin-left: 10; color:#000000;}
</style>

<script language="javascript">
<!-- 
    function FinishPres(report_id)
    { 
        if(report_id=='')
        {
                alert('<?php echo $LDReportNotFound; ?>');
                return false;
        }

        var r=confirm("<?php echo $LDGiveChemicalReport; ?>");
        if (r==true) {
                document.form_test_request.action="includes/inc_khochan_chemical_putin_statusfinish.php?report_id="+report_id+"&user_origin=<?php echo $user_origin; ?>";
                document.form_test_request.submit();
        } else
                return false;
    }

    function printOut()
    {
            window.print();
    }

    function CalCost(t){
//        var a = document.getElementById('receive'+t).value;
//        var b = document.getElementById('cost'+t).value;
//        document.getElementById('totalcost'+t).value = a*b;
        
        var tbl = document.getElementById("my_table_1");
        var trs = tbl.getElementsByTagName("tr");
        
        var n = document.getElementById('maxid').value;
        var total=0;
//        for (i = 1; i <= n; i++)
//        {
//            if(document.getElementById('totalcost'+i)!=null)
//                total = total + document.getElementById('totalcost'+i).value*1;
              for(j = 2; j< trs.length; j++){
                  var a = document.getElementById('receive'+(j-1)).value;
                    var b = document.getElementById('cost'+(j-1)).value;
                    document.getElementById('totalcost'+(j-1)).value = a*b;
//                    if(j == t){
//                        total=document.getElementById('totalcost'+(j-1)).value*1;
//                    }else{
//                        total = total + document.getElementById('totalcost'+(j-1)).value*1;
//                    }
                    total = total + document.getElementById('totalcost'+(j-1)).value*1;
//                    alert(total);
                }
//        }        
        document.getElementById('total_money').value = total;
    }
    function search(){
	var search = document.getElementById('search').value;
	document.form_test_request.action="<?php echo $thisfile.URL_APPEND;?>&search="+search;
	document.form_test_request.submit();
}
//-->
</script>
<?php

    $sTemp = ob_get_contents();

    ob_end_clean();

    $smarty->append('JavaScript',$sTemp);


    ob_start();

    if($batchrows){

?>

<!-- Table for the list index and the form -->
<table border=0>
    <tr valign="top">
        <td> <!-- ***************      LOAD MENU DANH SACH PHIEU LINH THUOC      ***************    -->
            <?php 
                require('includes/inc_khochan_putin_request_lister_fx.php');

            ?>
        </td> <!-- ************************************************************************    -->

        <td>
            <form name="form_test_request" method="post" >
		<table border="0" width="850"> <!-- ***************     SEARCH      ***************    -->
                    <tr>
                        <td><font size="3" color="#5f88be"><b><?php echo $LDKhoChan.': '.$TypePutInChemical; ?></b></td>
                        <td align="right"><input type="text" id="search" name="search" value=""></td>
                        <td><a href="javascript:search()"><input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE); ?> ></a></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="image" <?php echo createLDImgSrc($root_path,'abschic.gif','0') ?>  title="<?php echo $LDFinishEntry; ?>" OnClick="FinishPres(<?php echo $report_id; ?>)">
                            <a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> title="<?php echo $LDPrintOut; ?>"></a>
                        </td>				
                        <td align="right" colspan="2"><FONT size=1><?php echo $LDSearchIssueGuide; ?></td>
                    </tr>
		</table> 			<!-- ******************************    -->
                <br/>
			   <!--  outermost table creating form border -->
                <table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
                    <tr>
                        <td>
                            <table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0 width="850">
                                <tr>	<!-- ***************      HIEN THI NOI DUNG (MEDICINE) TRONG PHIEU LINH THUOC      ***************    -->
                                    <td align="center">
                                            <?php if(($edit || $read_form) && $chemical_count){ 
                                                    if($report_show = $Pharma->getPutInChemicalInfo($report_id))
                                                        require('includes/inc_khochan_putin_chemical_in_list.php');
                                            } ?>
                                    </td>
                                </tr>    <!-- *************************************************************************************    -->
                                <tr>
                                    <td>&nbsp;<br></td>
                                </tr>
                            </table>	
                        </td>
                    </tr>
                </table>
                <br/>
		
                <input type="image" <?php echo createLDImgSrc($root_path,'abschic.gif','0') ?>  title="<?php echo $LDFinishEntry; ?>" OnClick="FinishPres(<?php echo $report_id; ?>)">
                <a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> title="<?php echo $LDPrintOut; ?>"></a>

                <!--   ***************     HIDDEN  INPUT   ***************    -->
                <input type="hidden" name="sid" value="<?php echo $sid ?>">
                <input type="hidden" name="lang" value="<?php echo $lang ?>">
                <input type="hidden" name="pn" value="<?php echo $pn ?>">
                <input type="hidden" name="edit" value="<?php echo $edit ?>">
                <input type="hidden" id="maxid" name="maxid" value="<?php echo $batchrows ?>">
                <input type="hidden" id="tracker" name="tracker" value="<?php echo $tracker ?>">
                <input type="hidden" name="mode" id="mode" value="<?php if($mode=="edit") echo "update"; else echo $mode ?>">		
			
            </form>
	</td> 
    </tr>
</table>

<?php
    }
    else
    {
?>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom'); ?> align="absmiddle">
<font size=3 face="verdana,arial" color="#990000"><b><?php echo $LDNoPendingRequest; ?></b></font>
<p>
<a href="<?php echo $breakfile; ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0'); ?>></a>
<?php
    }

    $sTemp = ob_get_contents();
    ob_end_clean();

    # Assign to page template object
    $smarty->assign('sMainFrameBlockData',$sTemp);

    /**
    * show Template
    */
    $smarty->display('common/mainframe.tpl');


 ?>