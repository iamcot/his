<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    $lang_tables=array('departments.php');
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    $thisfile= basename(__FILE__);
    $breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
    $fileforward='../include/inc_destroy_chemical_save.php'.URL_APPEND;

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

    if (!isset($target))
        $target='new';
		
    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common');

    # Title in the toolbar
    $smarty->assign('sToolbarTitle',$LDDestroyChemical.' :: '.$LDNewReportTab);

    # href for help button
//    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('Name',$LDDestroyChemical);

    # Onload Javascript code
    $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

    # Hide the return button
    $smarty->assign('pbBack',FALSE);

    ob_start();
?>
<style type="text/css">
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
.v12 { font-family:verdana,arial;font-size:12; }
.v13 { font-family:verdana,arial;font-size:13; }
.v10 { font-family:verdana,arial;font-size:10; }
#hint ul {
	list-style-type: none;
	font-family: verdana, arial, sans-serif;
	font-size: 10px;
	margin: 0 0 0 -28px;
}
#hint li {
	list-style-type: none;
	border: 1px dotted #C0C0C0;
	margin: 0 0 0 -10px;
	cursor: default;
	color: black;
	text-align:left;
}
#hint {
	background:#fff;
	border: 0px;
}
#hint > li:hover {
	background: #ffc;
}
.sx {
	text-align:left;
	font-size: 12px;
	font-variant: small-caps;
	color: blue;
}
li.selected {
	background: #FFE4E1;
}
.nav:hover {
	background:#FFFF99;
}
.together { border-left:thick solid #0000FF; }
.input1 {
            width:10%; height:100%;
            border-bottom: solid 1px #C3C3C3;
            border-top: solid 1px #fff; 
            border-left: solid 1px #fff;
            border-right: solid 1px #fff;
    }
</style>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/effects.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/controls.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/builder.js"></script>
<script src="<?php echo $root_path; ?>js/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script  language="javascript">
<!--
    function chkform(d) {
	document.newdestroyform.action="../include/inc_destroy_chemical_save.php";
	document.newdestroyform.submit();
    }

    function insertRow()
    {
        var tbl = document.getElementById('tblMedicine');
        var lastRow = tbl.tBodies[0].rows.length;

        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }
        else {// code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200){
                        //var tbl = document.getElementById('tblMedicine');
                        var rowadd =tbl.tBodies[0].insertRow(-1);
                        //table = table.substr(0, table.length-8);
                        rowadd.innerHTML = xmlhttp.responseText;
                        //document.getElementById('tblMedicine').innerHTML = table;

                        var tblstt = document.getElementById('tblSTT');
                        var laststt = tblstt.tBodies[0].rows.length;
                        var row=tblstt.tBodies[0].insertRow(-1);
                        row.innerHTML = '<tr><td bgColor="#ffffff" align="center"><a href="javascript:;" onclick="deleteRow('+laststt+')">[x]</a></td><td align="center" bgColor="#ffffff"><input name="stt1" type="text" size=1 value="'+laststt+'" style="text-align:center;border-color:white;border-style:solid;" readonly></td></tr>';

                }
        }
        var maxid = document.getElementById('maxid');
        maxid.value = maxid.value*1+1;
        var idnum=maxid.value;

        xmlhttp.open("GET","../include/inc_destroymed_addchemical.php?i="+idnum,true);
        xmlhttp.send();

    }
    function deleteRow(i)
    {
        var tbl = document.getElementById('tblMedicine');
        var lastRow = tbl.tBodies[0].rows.length;
        i=i-1+2;
        if (lastRow > i)
            tbl.tBodies[0].deleteRow(i);

        var tblstt = document.getElementById('tblSTT');
        var laststt = tblstt.tBodies[0].rows.length;
        tblstt.tBodies[0].deleteRow(laststt-1);
    }
    function deleteIssue()
    {
	var r=confirm("<?php echo $LDWouldDeleteIssue; ?>");
	if (r==true) {
		document.newdestroyform.action="<?php echo $fileforward;?>&isdelete=delete";
		document.newdestroyform.submit();
	}
    }
    function startCalc(x){
        interval = setInterval("calc("+x+")",1);
    }
    function calc(x){
        //sum1 * cost1 = totalcost1;
        a = document.newdestroyform['sumpres'+x].value;
        b = document.newdestroyform['plus'+x].value; 
        document.newdestroyform['sum'+x].value = Number(a)+Number(b);

    }
    function stopCalc(){
        clearInterval(interval);
    }

    function searchChemical(id_number)
    {
	var win = 'cabinet_search_chemical.php?id_number='+id_number+'&dept_nr=<?php echo $dept_nr;?>&ward_nr=<?php echo $ward_nr;?>';
	window.open(win,'popuppage','width=850,toolbar=1,resizable=1,scrollbars=yes,height=600,top=100,left=100');
	//myWindow.focus();
    }
    function Chemical_AutoComplete(i){
        var name_chemical='chemical'+i;
        var ward1 = "<?php echo $LDWard ?>";
        var all = "<?php echo $LDAll?>";
        var includeScript = "../include/inc_pharma_cabinet_autocomplete_chemical.php?mode=auto&dept_nr=<?php echo $dept_nr;?>&k="+i+"&ward1="+ward1+"&all="+all;
        new Ajax.Autocompleter(name_chemical,"hint",includeScript, {
                method:'get',
                paramName:'search',
                afterUpdateElement : setSelectionId
            }
        );
    }
		
    function setSelectionId(div,li) {
        var a=li.id;
        var temp_id=a.split('@#');
        var k=temp_id[0];
        document.getElementById('encoder'+k).value = temp_id[1];

        var text=div.value; 
        var temp_value=text.split('-- ');
        document.getElementById('chemical'+k).value = temp_value[0];
        document.getElementById('lotid'+k).value = temp_value[1];
        document.getElementById('exp'+k).value = temp_value[2];
        document.getElementById('number'+k).value = temp_value[4];
        var b=temp_value[3]; 
        var temp_cost=b.split(' vnd/');
        document.getElementById('cost'+k).value = temp_cost[0];
        document.getElementById('unit'+k).value = temp_cost[1];
        document.getElementById('totalcost'+k).value = temp_value[4]*temp_cost[0];
        
        document.getElementById('ward_nr'+k).value = temp_value[5].substr(temp_value[5].indexOf("-")+1);
        
        CheckDuplicateChemical();
    }

    function Fill_Data(i)
    {
        var process_file='../include/inc_pharma_cabinet_autocomplete_chemical.php?mode=filldata&dept_nr=<?php echo $dept_nr;?>&ward_nr=<?php echo $ward_nr;?>';
        var name_chemical='chemical'+i;

        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var a = xmlhttp.responseText.split("@#");
                document.getElementById('encoder'+i).value = a[0];
                document.getElementById('number'+i).value = a[1]; 			
                document.getElementById('unit'+i).value = a[2]; 
                document.getElementById('cost'+i).value = a[3]; 
                document.getElementById('lotid'+i).value = a[4];
                document.getElementById('totalcost'+i).value = a[1]*a[3];
                document.getElementById('ward_nr'+i).value = a[6];

                CheckDuplicateChemical();
            }
        }
        xmlhttp.open("GET",process_file+"&lotid="+document.getElementById('lotid'+i).value+"&search="+document.getElementById('chemical'+i).value,true);
        xmlhttp.send();
    }
    
    function CheckDuplicateChemical(){
        var n = document.getElementById('maxid').value;		
        var enco_j, enco_k;
        for (j=1; j<=n; j++){
            enco_j = document.getElementById("encoder"+j);
            enco_j.style.backgroundColor="white";
        }
        for (j=1; j<=n; j++){	
            enco_j = document.getElementById("encoder"+j);
            if (enco_j.value!='') {
                for (k=j; k<=n; k++){
                    enco_k = document.getElementById("encoder"+k);
                    if (k!=j && enco_k.value!='')
                        if (enco_j.value==enco_k.value){
                                enco_j.style.backgroundColor="gold";
                                enco_k.style.backgroundColor="gold";
                        }
                }
            }
        }
    }

    function CalCost(i){
        var a = document.getElementById('number'+i).value;
        var b = document.getElementById('cost'+i).value;
        document.getElementById('totalcost'+i).value = a*b;
    }
    
    function alertselected(selectobj){        
        var number_use=selectobj.selectedIndex;
        d=document.newdestroyform;
        d.typeput.value=number_use;
    }
-->
</script>
<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp); 

    # Load and display the tab
    $new_report_url=$thisfile;
    $list_report_url='chemical_destroy_chemical_list.php';
    ob_start();
    require('../include/inc_medicine_report_tab.php');
    $smarty->display('nursing/manage_medicine_tab.tpl');
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->assign('sTab',$sTemp);

    $smarty->assign('sRegForm','<form name="newdestroyform" method="POST"  onSubmit="return chkform(this)">');

    //***********************************NOI DUNG TRANG********************************
    $smarty->assign('deptname',$LDDept.': '.$deptname);
    $smarty->assign('ward',$LDWard.': '.$wardname);
    $smarty->assign('titleForm',$LDDestroyChemical);
    $smarty->assign('LDTYPE',$LDTypePutIn1);
    $smarty->assign('LDSTT',$LDSTT);
    $smarty->assign('LDPresID',$LDChemicalID);
    $smarty->assign('LDPresName',$LDChemicalName1);
    $smarty->assign('LDUnit',$LDUnit);
    $smarty->assign('LDNumberOf',$LDNumberOf);
    $smarty->assign('LDLotID',$LDLotID);
    $smarty->assign('LDExpDate',$LDExpDate);
    $smarty->assign('LDNote',$LDNote);
    $smarty->assign('LDCost',$LDCost);
    $smarty->assign('LDDestroyRequest',$LDDestroyRequest);
    $smarty->assign('LDTotalCost',$LDTotalCost);

    if(!isset($target) || ($target=='new') || ($target=='create')){
        $target='new'; 

        if(!isset($maxid) || $maxid==''){
            $maxid=5; $flag=0;
            $rowReport='';
        } else {
            $listid = explode('_',$itemid);
            $flag=1;
            require_once($root_path.'include/care_api_classes/class_product.php');
            $Product = new Product;
        }
			
        $create_id = $_SESSION['sess_user_name'];		
        for ($i=1;$i<=$maxid;$i++){
            $list_info = explode('.',$listid[$i]);
            if($flag) {
                $condition = " AND tatcakhoa.available_product_id='".$list_info[0]."' ";
                if($listReport = $Product->SearchExpCabinetChemical($dept_nr, $list_info[1], $condition)){
                    $rowReport = $listReport->FetchRow();
                    $rowReport['exp_date'] = formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy');
                    $totalcost = $rowReport['cost']*$rowReport['number'];
                }
            }
            ob_start();
                $smarty->assign('sTypePut','<select name="typeput" class="input1" onChange="alertselected(this)"><option value="0">'.$LDBH.'</option><option value="1" selected>'.$LDNoBH.'</option><option value="2">'.$LDCBTC.'</option></select>');
	        require('../include/inc_destroymed_addchemical.php');
                $sTempDiv = $sTempDiv.ob_get_contents();
            ob_end_clean();

            $sTempDivStt = $sTempDivStt.'<tr bgColor="#ffffff">
                                            <td align="center"><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
                                            <td align="center"><input name="stt'.$i.'" type="text" size=1 value="'.$i.'" style="text-align:center;border-color:white;border-style:solid;" readonly /></td>
                                    </tr>';
        }		
        $smarty->assign('divMedicine',$sTempDiv);
        $smarty->assign('divSTT',$sTempDivStt);
		
    }else{	//target=update
	
        $smarty->assign('IssueId',$LDIssueId.': '.'<input type="text" size=8 name="IssueId" value="'.$des_id.'" style="text-align:center;border-color:white;border-style:solid;" readonly />');
		
        require_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
        $CabinetPharma = new CabinetPharma;
		
        $listReport = $CabinetPharma->getDetailDestroyChemicalInfo($des_id);
        if(is_object($listReport)){
            $sTempDivStt='';$sTempDiv='';
            $maxid=$listReport->RecordCount();
            for ($i=1;$i<=$maxid;$i++){
                $rowReport = $listReport->FetchRow();
				$flag1=''; $flag2=''; $flag3='';
				switch ($rowReport['typeput']){
						case 0:	//BHYT
							$flag1='selected="selected"';
							break;
						case 1:	//Su nghiep
							$flag2='selected="selected"';
							break;
						case 2: //CBTC
							$flag3='selected="selected"';
							break;	
							
						default:
							$flag2='selected="selected"';
							break;
				}
                $rowReport['exp_date'] = formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy');
                ob_start();
                $smarty->assign('sTypePut','<select name="typeput" class="input1" onChange="alertselected(this)"><option value="0" '.$flag1.'>'.$LDBH.'</option><option value="1" '.$flag2.'>'.$LDNoBH.'</option><option value="2" '.$flag3.'>'.$LDCBTC.'</option></select>');
                require('../include/inc_destroymed_addchemical.php');
                $sTempDiv = $sTempDiv.ob_get_contents();				
                ob_end_clean();	

                $sTempDivStt.='<tr bgColor="#ffffff">
                                    <td align="center"><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
                                    <td align="center"><input name="stt'.$i.'" type="text" size=1 value="'.$i.'" style="text-align:center;border-color:white;border-style:solid;" readonly /></td>
                                </tr>';							
            }	
            $create_id= $rowReport ['doctor'];
            $date_time= $rowReport ['date_time_create'];
            $sTempDiv = '<div id="myDiv">'.$sTempDiv.'</div>';
        }

            $smarty->assign('divMedicine',$sTempDiv);
            $smarty->assign('divSTT',$sTempDivStt);
            $smarty->assign('pbDelete','<img '.createLDImgSrc($root_path,'delete.gif','0','middle').' title="'.$LDDelete.'" align="middle" OnClick="deleteIssue()" />');		
    }
    //sHiddenInputs
    $sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'" />
                    <input type="hidden" name="lang" value="'.$lang.'" />
                    <input type="hidden" id="maxid" name="maxid" value="'.$maxid.'" />
                    <input type="hidden" name="type" value="'.$type.'" />
                    <input type="hidden" name="target" value="'.$target.'" />
                    <input type="hidden" name="date_time" value="'.$date_time.'" />
                    <input type="hidden" name="des_id" value="'.$des_id.'" />
                    <input type="hidden" name="create_id" value="'.$create_id.'" />
                    <input type="hidden" name="dept_nr" value="'.$dept_nr.'" />';

    $smarty->assign('sHiddenInputs',$sTempHidden);

    //*********************************************************************************

    $smarty->assign('AddRow','<a href="javascript:;" onclick="insertRow();">&nbsp;[+]&nbsp;'.$LDAddRowChemical.'</a>');
    $smarty->assign('UserName',$LDUserIssue.': '.$create_id);
    $smarty->assign('NoteOfCreator',$LDNoteOfCreator.':<br> <textarea name="notecreator" cols="50" rows="2" wrap="physical" >'.$rowReport['generalnote'].'</textarea>');

    $smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
    $smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

    # Assign the page template to mainframe block
    $smarty->assign('sMainBlockIncludeFile','nursing/medicine_destroymed_1.tpl');

    # Show main frame
    $smarty->display('common/mainframe.tpl');

?>

