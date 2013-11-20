<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    $thisfile= basename(__FILE__).URL_APPEND;
    $breakfile='../report_khole.php'.URL_APPEND;


    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common');

    # Title in the toolbar
    $smarty->assign('sToolbarTitle',$LDKhoLe.' :: '.$LDReportUse15Day);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LD15DayReport')");

    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('Name',$LDReportUse15Day);

    # Onload Javascript code
    $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

    # Hide the return button
    $smarty->assign('pbBack',FALSE);

    ob_start();
?>
<script  language="javascript">
    <!--
    function chkform(d) {
            document.reportform.action='<?php echo $thisfile; ?>';
            document.reportform.submit();
    }
function printOut(fromdate,todate,select_type)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khole_thongke15ngaydung.php<?php echo URL_APPEND; ?>&type=chemical&fromdate="+fromdate+"&todate="+todate+"&select_type="+select_type;
	testprintpdf=window.open(urlholder,"ThongKe15NgayDung","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
function selectTypeMed() {
	var temp_i = document.getElementById("type_med").selectedIndex;
	document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
	document.reportform.action='<?php echo $thisfile; ?>';
	document.reportform.submit();
}
</script>
<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp); 

    $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

    //***********************************NOI DUNG TRANG********************************
    $smarty->assign('LDSumAllDept',$LDSumAllDept);
    $smarty->assign('titleForm',$LD15DayReport);
    $smarty->assign('LDFromDate',$LDFromDate);
    $smarty->assign('LDToDate',$LDToDate);
    $smarty->assign('LDSTT',$LDSTT);
    $smarty->assign('LDPresName',$LDChemicalName1);
    $smarty->assign('LDUnit',$LDUnit);
    $smarty->assign('LDStandard',$LDStandard);
    $smarty->assign('LDDay',$LDDay);
    $smarty->assign('LDTotalNumber',$LDTotalNumber);
    $smarty->assign('LDNote',$LDNote);

    //Test format fromday
    if (isset($fromdate) && $fromdate!='' && strpos($fromdate,'-')<3) {
            list($f_day,$f_month,$f_year) = explode("-",$fromdate);
            $fromdate=$f_year.'-'.$f_month.'-'.$f_day;
    }
    else 
            list($f_year,$f_month,$f_day) = explode("-",$fromdate);
    //Test format today
    if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
            list($t_day,$t_month,$t_year) = explode("-",$todate);
            $todate=$t_year.'-'.$t_month.'-'.$t_day;
    }
    else 
            list($t_year,$t_month,$t_day) = explode("-",$todate);

    $smarty->assign('monthreport',$LDMonth.': '.$f_month);

    //Calendar
    require_once ($root_path.'js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
    $calendar->load_files();
    $date_format='dd-mm-yyyy';
    $smarty->assign('calendarfrom',$calendar->show_calendar($calendar,$date_format,'fromdate',$fromdate));
    $smarty->assign('calendarto',$calendar->show_calendar($calendar,$date_format,'todate',$todate));

	//Report in
	if(!isset($select_type) || $select_type=='')
		$select_type=0;
	$s0=''; $s1=''; $s2=''; $s3='';
	switch($select_type){
		case 0: $s0='selected'; break;
		case 1: $s1='selected'; break;
		case 2: $s2='selected'; break;
		case 3: $s3='selected'; break;
		default: $s0='selected';
	}
	$temp='<select id="type_med" name="type_med" onchange="selectTypeMed()">
				<option value="0" '.$s0.' >'.$LDChemical.'</option>
				<option value="1" '.$s1.' >'.$LDChemical_KP.'</option>
				<option value="2" '.$s2.' >'.$LDChemical_BH.'</option>
				<option value="3" '.$s3.' >'.$LDChemical_CBTC.'</option>
			</select>';
	$smarty->assign('inputby',$temp);

    ob_start();
    echo '<tr bgColor="#E1E1E1" align="center">';
    if ($f_day=='')
            $f_day=1;
    for ($i=$f_day;$i<$f_day+16;$i++){
            if($i<=31){
                    $temp=str_pad((int) $i,2,"0",STR_PAD_LEFT);
                    echo '<td>'.$temp.'</td>';
            } else echo '<td>&nbsp;&nbsp;&nbsp;</td>';

    }
    echo '</tr>';
    $sTempDay = ob_get_contents();				
    ob_end_clean();
    $smarty->assign('divDay',$sTempDay);
	


    //Search item from date
    $j=(int)$f_day; $total=0;
    $i=0;
    if ($f_day>16)
            $end_day=31;
    else
            $end_day=$f_day+15;

    if ($t_day>$end_day)
            $todate=$t_year.'-'.$t_month.'-'.$end_day;


    require_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
    $CabinetPharma = new CabinetPharma;
	switch($select_type){	
	case 0: $cond_typeput = ''; break;
	case 1: $cond_typeput = ' AND arc.typeput=1 '; break;
	case 2: $cond_typeput = ' AND arc.typeput=0 '; break;
	case 3: $cond_typeput = ' AND arc.typeput=2 '; break;
	default: $cond_typeput = '';
}	
    $listReport = $CabinetPharma->reportChemical15Day('','',$fromdate,$todate,$cond_typeput);
    if(is_object($listReport)){
            ob_start();	
            while($rowReport = $listReport->FetchRow())	{
                    if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder'])) {
                            if (isset($old_encode)){
                                    for ($j;$j<=$end_day;$j++)
                                            echo '<td></td>';
                                    echo	'<th>'.$total.'</th>';	//Tong cong		
                                    echo	'<td></td>	</tr>';		//Note
                            }
                            $old_encode=$rowReport['product_encoder'];
                            $j=(int)$f_day;
                            $flag=1; $total=0;
                            $i++;
                    }else $flag=0;
		
			
		if ($flag){
			echo '<tr bgColor="#ffffff" >';
			echo	'<td align="center">'.$i.'</td>'; //STT
			echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_chemical'].'</td>';  //Don vi
			echo	'<td> </td>';	//Quy cach?		
		}
		for($j;$j<=(int)$rowReport['at_day'];$j++) {
			if ($j==(int)$rowReport['at_day']){
				echo '<td>'.$rowReport['total'].'</td>';  //Ngay
				$total+=$rowReport['total'];
				$j++;
				break;
			}
			else
				echo '<td></td>';
		}
							
	}
	for ($j;$j<=$end_day;$j++)
		echo '<td></td>';
	echo	'<th>'.$total.'</th>';	//Tong cong		
	echo	'<td></td>	</tr>';		//Note
			
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
    } else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="22">'.$LDItemNotFound.'</td></tr>';
 
    $smarty->assign('divItem',$sTempDiv);



    //sHiddenInputs
    $sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
                    <input type="hidden" name="lang" value="'.$lang.'">
					<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">';

    $smarty->assign('sHiddenInputs',$sTempHidden);

    //*********************************************************************************

    $smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
    $smarty->assign('pbPrint','<a href="javascript:window.printOut(\''.$fromdate.'\',\''.$todate.'\',\''.$select_type.'\');"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');
    $smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

    # Assign the page template to mainframe block
    $smarty->assign('sMainBlockIncludeFile','pharmacy/khole_baocao15ngay.tpl');

    # Show main frame
    $smarty->display('common/mainframe.tpl');

?>

