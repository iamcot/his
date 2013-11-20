<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    $lang_tables=array('departments.php');
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    # Init
    $thisfile= basename(__FILE__);
    $breakfile='nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
    $fileforward='nursing-issuepaper-chemical-depot.php'.URL_APPEND.'&target=sum';
    if (!isset($target) || !$target) $target='pres';

    //Get info of current department, ward
    if ($ward_nr!=''){
            require_once($root_path.'include/care_api_classes/class_ward.php');
            $Ward = new Ward;
            if($wardinfo = $Ward->getWardInfo($ward_nr)) {
                    $wardname = $wardinfo['name'];
                    $deptname = ($$wardinfo['LD_var']);
                    $dept_nr = $wardinfo['dept_nr'];
            }
    } elseif ($dept_nr!=''){
            require_once($root_path.'include/care_api_classes/class_department.php');
            $Dept = new Department;
            if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
                    $deptname = ($$deptinfo['LD_var']);
                    $wardname = $LDAllWard;
            }
    }

    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common');

    # Title in the toolbar
    $smarty->assign('sToolbarTitle',$LDIssuePaperChemical.' :: '.$LDPrescriptionTab);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaperChemical')");

    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('Name',$LDIssuePaperChemical);

    $smarty->assign('sPresForm','<form name="selectpresform" method="POST" action="" onSubmit="return submitform(this)">');

    $smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
    $smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');


    # Onload Javascript code
    $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

    # Hide the return button
    $smarty->assign('pbBack',FALSE);

    # Collect additional javascript code
    ob_start();
?>

<script  language="javascript">
<!--
function submitform() {
	var sel = document.selectpresform.elements.length;
	var temp;
	var tempstr;
	var counter;
	//str = document.selectpresform.hidden.value;
	querystr = "<?php echo $fileforward; ?>&";

	counter = 1;
	for (i=0;i<sel;i++) {	
		//temp = str.indexOf("#");
		if(document.selectpresform.elements[i].type=="checkbox" && document.selectpresform.elements[i].id!='') {
			temp=document.selectpresform.elements[i].id;
			tempstr = temp.substring(4,temp.length);
			//str=str.substring(temp+1,str.length);		
			
			if(document.selectpresform.elements[i].checked==true) 	
			{
				querystr=querystr+"itemcode"+counter+"="+tempstr+"&";
				counter = counter + 1;		
			}			
		}		
	}
	document.selectpresform.action = querystr;
	document.selectpresform.submit();
}

function checkPres(checkAllState,room)
{
	var x = document.getElementsByName(room);
	if(x.length > 0)
	{
		// Loop through the array
		for (i = 0; i < x.length; i++)
		{
			x[i].checked = checkAllState.checked;
		}
	}
}
-->
</script>

<?php

    $sTemp = ob_get_contents();
    ob_end_clean();

    # Append the extra javascript to JavaScript block
    $smarty->append('JavaScript',$sTemp);


    # Load and display the tab
    ob_start();
            require('./include/inc_issuepaper_chemical_tabs.php');
            $smarty->display('nursing/issuepaper_tab.tpl');
            $sTemp = ob_get_contents();
    ob_end_clean();

    $smarty->assign('sTab',$sTemp);
    $smarty->assign('deptname',$LDDept.': '.$deptname);
    $smarty->assign('ward',$LDWard.': '.$wardname);

//***********************************NOI DUNG TRANG********************************
    if($target=='pres') 
    {
            include_once($root_path.'include/core/inc_date_format_functions.php');
            $smarty->assign('LDPresID',$LDPresID);	
            $smarty->assign('LDEncounterID',$LDEncounterID);	
            $smarty->assign('LDEncounterSex',$LDEncounterSex);	
            $smarty->assign('LDEncounterName',$LDEncounterName);	
            $smarty->assign('LDEncounterBirth',$LDPresDate);

            $condition='';
            if ($ward_nr!='')
                    $condition.=' AND prs.ward_nr='.$ward_nr.' ';
            if ($dept_nr!='')
                    $condition.=' AND prs.dept_nr='.$dept_nr.' ';

            $sql="SELECT prs.prescription_id, prs.encounter_nr, prs.date_time_create, enc.current_room_nr, per.name_first, per.name_last, per.sex  
                    FROM care_chemical_prescription_info AS prs, care_chemical_type_of_prescription AS tp, care_encounter AS enc, care_person AS per 
                    WHERE prs.status_finish='0' 
                            AND prs.in_issuepaper='0' 
                            AND prs.encounter_nr=enc.encounter_nr 
                            ".$condition." 
                            AND prs.prescription_type = tp.prescription_type
							AND tp.group_pres='1' AND prs.total_cost>0 
                            AND per.pid=enc.pid 
                            ORDER BY enc.current_room_nr, prs.prescription_id ";

            if($listpres=$db->Execute($sql))
            {
                    $count = $listpres->RecordCount();
                    if($count){
                            $flag_g=0;	//flag check show group name, type name
                            for($i=0;$i<$count;$i++) {
                                    $item=$listpres->FetchRow();
                                    $itemcode="";
                                    $smarty->assign('roomGroup','room'.$item['current_room_nr']);	
                                    $smarty->assign('itemID','pres'.$item['prescription_id']);		//checkbox: id=nounits1,2,3...
                                    $smarty->assign('itemPresID',$item['prescription_id']);
                                    $smarty->assign('itemEncID',$item['encounter_nr']);
                                    $smarty->assign('itemEncSex',$item['sex']);
                                    $smarty->assign('itemEncName',$item['name_last'].' '.$item['name_first']);
                                    //$sepChars=array('-','.','/',':',',');
                                    $date_pres = formatDate2Local($item['date_time_create'],$date_format,false,false,$sepChars);
                                    $smarty->assign('itemEncBirthday',$date_pres);

                                    $itemcode=$item['prescription_id'];
                                    $itemcode1=$itemcode1.$itemcode;
                                    $itemcode1=$itemcode1."#";			

                                    //show group, type name
                                    $flagtpl_g=false;
                                    $room_nr=$item['current_room_nr'];
                                    if ($flag_g!=$room_nr)
                                    {
                                            $flagtpl_g=true;
                                            $smarty->assign('checkgroup','<input type="checkbox" name="checkall'.$room_nr.'" onclick="checkPres(this,\'room'.$room_nr.'\');">');
                                            $smarty->assign('roomName',$LDRoom.': '.$room_nr);
                                            $flag_g=$room_nr;
                                    }	
                                    $smarty->assign('roomflag',$flagtpl_g);	

                                    ob_start();
                                    $smarty->display('nursing/issuepaper_selectpres_item.tpl');
                                    $sListRows = $sListRows.ob_get_contents();
                                    ob_end_clean();		
                            }
                    }
                    else{
                            $sListRows='<tr bgColor="#eeeeee"><td colspan="6">'.$LDNoPres.'</td></tr>';
                    }
                    $itemcode=$itemcode1;
                    $smarty->assign('ItemLine',$sListRows);
            }

    }
//*********************************************************************************
//$smarty->assign('test',$target.' '.$ward_nr);



//sHiddenInputs
	$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="hidden" value="'. $itemcode .'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">
		<input type="hidden" name="dept_nr" value="'.$dept_nr.'">';

	$smarty->assign('sHiddenInputs',$sTempHidden);

$sCancel="<a href=";
if($_COOKIE['ck_login_logged'.$sid]) $sCancel.=$breakfile;
	else $sCancel.='aufnahme_pass.php';
$sCancel.='><img '.createLDImgSrc($root_path,'close2.gif','0').' alt="'.$LDCancelClose.'"></a>';

$smarty->assign('pbCancel',$sCancel);

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/issuepaper_selectpres.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');


?>