<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('LANG_FILE','pharma_pay_out.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_pharma.php');
	require_once($root_path.'include/care_api_classes/class_health_station.php');
    $Pharma = new Pharma;

    $thisfile= basename(__FILE__);
    $breakfile=$root_path.'modules/pharmacy/apotheke.php'.URL_APPEND;
    $fileforward='includes/khochan_payout_chemical_save.php'.URL_APPEND;

    if (!isset($target))
            $target='new';
    if (!isset($select) || $select=='')
            $select=0;
		
    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common');

    # Title in the toolbar
    $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$TypepayoutChemical);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDNewReportTab')");

    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('Name',$TypepayoutChemical);

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
	font-family: verdana,
 	arial, sans-serif;
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

    .title1 {
	font-size:12px; 
	font-family:Tahoma; 
	border-left: solid 1px #C3C3C3;
	border-bottom: solid 1px #C3C3C3;
    }
    .input1 {
	width:100%; height:100%;
	border-bottom: solid 1px #C3C3C3;
	border-top: solid 1px #fff; 
	border-left: solid 1px #fff;
	border-right: solid 1px #fff;
    }
    .input2 { width:100%;height:100%;border:none; }
    .input3 { width:91%;height:100%;border:none; }
    .cell1 {
	border-bottom: solid 1px #C3C3C3;
	border-left: solid 1px #C3C3C3;
    }
    khole {
	font-weight:bold;
    }
</style>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/effects.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/controls.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/builder.js"></script>
<script src="<?php echo $root_path; ?>js/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script  language="javascript">
<!--
    function pay_out(){
	var d=document.newform;
	if(d.voucher_id.value==""){
		alert("<?php echo $LDPlsEnterVoucher; ?>");
		d.voucher_id.focus();
		return false;
	}else if(d.typeput.value==""){
		alert("<?php echo $LDPlsChoseType; ?>");
		d.supplier_input.focus();
		return false;
	}else if(d.total_money.value=="" || (d.total_money.value!= parseInt(d.total_money.value))){
		alert("<?php echo $LDPlsEnterTotalCost; ?>");
		d.total_money.focus();
		return false;
	}else if(d.payout_person.value==""){
		alert("<?php echo $LDPlsEnterpayoutPerson; ?>");
		d.payout_person.focus();
		return false;
	}
	document.newform.action="<? echo $fileforward; ?>";
	document.newform.submit();
    }

    function insertRow()
    {
	var maxid = document.getElementById('maxid');
	maxid.value = maxid.value*1+1;
	var idnum=maxid.value;
	
	var tbl = document.getElementById("my_table");
	var lastRow = tbl.tBodies[0].rows.length;
	var laststt=lastRow;
 
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var rowadd =tbl.tBodies[0].insertRow(-1);
			rowadd.innerHTML = xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET","includes/khochan_putin_addchemical.php?i="+idnum+'&stt_nr='+laststt,true);
	xmlhttp.send();
  
    }
    function deleteRow(i)
    {
        var tbl = document.getElementById("my_table");
        var lastRow = tbl.tBodies[0].rows.length;
        var j;
        for (j=i*1+1; j<lastRow; j++)
        {
            var row=tbl.rows[j];
            var cell0=row.cells[0];
            var cell1=row.cells[1];
            var k=j-1;
            cell1.innerHTML=k-1;
            cell0.innerHTML='<a href="javascript:;" onclick="deleteRow('+k+')">[x]</a>';
        }
     if (lastRow > i)
            tbl.tBodies[0].deleteRow(i);
    }
    function deleteIssue()
    {
	var r=confirm("<?php echo $LDWouldDeletePayOut; ?>");
	if (r==true) {
		document.newform.action="<?php echo $fileforward;?>&isdelete=delete";
		document.newform.submit();
	}
    }
    function CalCost(i){
        var a = document.getElementById('number'+i).value;
        var b = document.getElementById('cost'+i).value;
        document.getElementById('totalcost'+i).value = a*b;

        var n = document.getElementById('maxid').value;
        var total=0;
        for (i = 1; i <= n; i++)
        {
            if(document.getElementById('totalcost'+i)!=null)
                    total = total + document.getElementById('totalcost'+i).value*1;
        }
        document.getElementById('total_money').value = total;
    }
    function searchChemical(id_number)
    {
        var typeput = document.getElementById('typeput').value;
		var win = 'includes/khochan_search_chemical_payout.php?id_number='+id_number+'&typeput='+typeput;
        window.open(win,'popuppage','width=850,toolbar=1,resizable=1,scrollbars=yes,height=600,top=100,left=100');
    }
    function Chemical_AutoComplete(i){
        var name_chemical='chemical'+i;
        var includeScript = "includes/khochan_payout_autocomplete_chemical.php?mode=auto&k="+i;
        new Ajax.Autocompleter(name_chemical,"hint",includeScript, {
                method: 'get',
                paramName: 'search',
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
        var b=temp_value[3]; 
        var temp_cost=b.split(' vnd/');
        document.getElementById('cost'+k).value = temp_cost[0];
        document.getElementById('unit'+k).value = temp_cost[1];		

        CheckDuplicateChemical();
			
    }

    function Fill_Data(i,lotid)
    {
        
        var process_file='includes/khochan_payout_autocomplete_chemical.php?mode=filldata';
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
                    //document.getElementById('encoder'+i).value = a[0]; 	
                    document.getElementById('cost'+i).value = a[1];			
                    document.getElementById('unit'+i).value = a[2];
                    document.getElementById('lotid'+i).value = a[4];
                    document.getElementById('exp'+i).value = a[5];			

                    CheckDuplicateMedicine();
                }
            }
            xmlhttp.open("GET",process_file+"&encoder="+document.getElementById('encoder'+i).value+"&search="+document.getElementById('chemical'+i).value+"&lotid="+lotid,true);
        xmlhttp.send();

    }
    //Kiem tra thuoc trung 
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


-->
</script>
<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp); 

    # Load and display the tab
    $new_report_url=$thisfile;
    $list_report_url='payout_list_chemical.php';
    ob_start();
    require('includes/khochan_putin_tab.php');
    $smarty->display('pharmacy/khochan_putin_tab.tpl');
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->assign('sTab',$sTemp);

    $smarty->assign('sRegForm','<form name="newform" method="POST" >');

#function 'load health_station'
function load_health_station($select,$default)	
{
	$_obj= new HealthStation();
	
	$selected_box='<select id="health_station" name="health_station" style="width:100%; height:100%;border-bottom: solid 1px #C3C3C3;border-top: solid 1px #fff; border-left: solid 1px #fff;border-right: solid 1px #fff;">';
    
         
		$type_info = $_obj->listHealth();		
		if($select==0)		//Kho Le
			$selected_box=$selected_box.'<option SELECTED value="0">'.$default.'</option>';
		else 
			$selected_box=$selected_box.'<option value="0">'.$default.'</option>';
			
		$cbx='';
        if (is_object($type_info))
        {
			$group==''; 
			while($item = $type_info->FetchRow())
			{
				if ($group=='' || $group!=$item['type']){
					$cbx .=  '<optgroup label="'.$item['typename'].'">';
					$group=$item['type'];
					
					if($item["health_station"]==$select)
						$cbx .= '<option value="'.$item['health_station'].'" selected>'.$item['village'].'</option>';
					else
						$cbx .= '<option value="'.$item['health_station'].'">'.$item['village'].'</option>';
				}	
				else{
					if($item["health_station"]==$select)
						$cbx .= '<option value="'.$item['health_station'].'" selected>'.$item['village'].'</option>';
					else
						$cbx .= '<option value="'.$item['health_station'].'">'.$item['village'].'</option>';
				}
			}  
         }
		$selected_box .= $cbx.'</select>';
	
    return $selected_box;
}

//***********************************NOI DUNG TRANG********************************
$smarty->assign('TitleForm',$LDPUTINPAPER);
$smarty->assign('LDEncoder',$LDEncoder);
$smarty->assign('LDDate',$LDDate);
$smarty->assign('LDDeliveryPerson',$LDDeliveryPerson);
$smarty->assign('LDPutInPerson',$LDPayOutPerson);
$smarty->assign('LDAddress',$LDAddress);
$smarty->assign('LDTypePutIn',$LDTypePayOut);
$smarty->assign('Note',$Note);
$smarty->assign('LDPlace',$LDPlace);
$smarty->assign('LDTotal',$LDTotal);
$smarty->assign('LDPutInID',$LDPayOutID);

$smarty->assign('LDTYPE',$LDTypePutIn1);

$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineID',$LDMedicineID);
$smarty->assign('LDMedicineName',$LDChemicalName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDSupplier',$LDExportFrom);
$smarty->assign('LDLotID',$LDLotID);
$smarty->assign('LDExpDate',$LDExpDate);
$smarty->assign('LDPrice',$LDPrice);
$smarty->assign('LDNumber',$LDNumber);
$smarty->assign('LDNumberInPaper',$LDNumberInPaper);
$smarty->assign('LDNumberInFact',$LDNumberInFact);
$smarty->assign('LDTotalPrice',$LDTotalPrice);
$smarty->assign('LDNote',$LDNote);

$smarty->assign('sTypePutInInput','<input type="text" value="'.$TypepayoutChemical.'" class="input1" readonly>');
$smarty->assign('AddRow','<a href="javascript:;" onclick="insertRow();">&nbsp;[+]&nbsp;'.$LDAddRowMedicine.'</a>');

if(!isset($target) || ($target=='new') || ($target=='create')){
    $target='new';
    if(!isset($maxid) || $maxid==''){
            $maxid=10; $flag=0;
            $rowReport='';
    } else {
            $listid = explode('_',$itemid);
            $flag=1;
            require_once($root_path.'include/care_api_classes/class_product.php');
            $Product = new Product;
    }
				
    $create_id = $_SESSION['sess_user_name'];		
    for ($i=1;$i<=$maxid;$i++){
            if($flag) {
                    $condition = " AND khole.product_encoder='".$listid[$i]."' ";
                    if($listReport = $Product->SearchChemicalCatalogKhoLe($condition)){
                            $rowReport = $listReport->FetchRow();
                    }
            }
            ob_start();
                    $stt_nr=$i+1;
                    require('includes/khochan_putin_addchemical.php');
                    $sTempDiv = $sTempDiv.ob_get_contents();
            ob_end_clean();

    }		
    $smarty->assign('divMedicine',$sTempDiv);
		
	$smarty->assign('sDateInput','<input name="date_payout" type="text" class="input1" value="'.$today = date("d/m/Y G:i:s").'"/>');
	$smarty->assign('sDeliveryPersonInput','<input type="text" name="payout_person" class="input1" />');
	$smarty->assign('sPutInPersonInput','<input type="text" name="receiver" class="input1"></input>');
	$smarty->assign('sNoteInput','<input type="text" name="generalnote" class="input1"/>');
	$smarty->assign('sSupplierInput','<input type="text" name="placefrom" value="'.$LDPlaceFrom.'" class="input1"/>');
	$smarty->assign('sPlaceInput',load_health_station($select, $LDKhoLe));
	$smarty->assign('sTotalInput','<input type="text" id="total_money" name="total_money" class="input1"/>');
	$smarty->assign('sPutInIDInput','<input type="text" name="voucher_id" class="input1"/>');
        $smarty->assign('sTypePut','<select name="typeput" id="typeput" class="input1" ><option value="0" selected>'.$LDBH.'</option><option value="1">'.$LDNoBH.'</option><option value="2">'.$LDCBTC.'</option></select>');
		
}else{	//target=update
	
		$smarty->assign('sEncoderInput','<input type="text" size=8 name="payoutId" value="'.$payout_id.'" class="input1" readonly>');

		$listReport = $Pharma->getDetailChemicalPayOutInfo($payout_id);
		if(is_object($listReport)){
			$sTempDivStt='';$sTempDiv='';
			$maxid=$listReport->RecordCount();
			for ($i=1;$i<=$maxid;$i++){
				$rowReport = $listReport->FetchRow();
				$stt_nr=$i+1;
				$rowReport['exp_date'] = formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy');
				ob_start();
				require('includes/khochan_putin_addchemical.php');
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();							
			}	
			$create_id= $rowReport['receiver'];	//nhan
			$date_time_temp = explode(" ",$rowReport['date_time']);
			$date_time= formatDate2Local($rowReport['date_time'],'dd/mm/yyyy').' '.$date_time_temp[1];
			$select=$rowReport['health_station'];
		}

		$smarty->assign('divMedicine',$sTempDiv);
		$smarty->assign('sDateInput','<input name="date_payout" type="text" class="input1" value="'.$date_time.'"/>');
		$smarty->assign('pbDelete','<a href="javascript:deleteIssue()" ><img '.createLDImgSrc($root_path,'delete.gif','0','middle').' title="'.$LDDelete.'" align="middle"></a>');
		
	$smarty->assign('sDateInput','<input name="date_payout" type="text" class="input1" value="'.$date_time.'"/>');
	$smarty->assign('sDeliveryPersonInput','<input type="text" name="payout_person" class="input1" value="'.$rowReport['pay_out_person'].'" />');
	$smarty->assign('sPutInPersonInput','<input type="text" name="receiver" class="input1" value="'.$rowReport['receiver'].'"></input>');
	$smarty->assign('sNoteInput','<input type="text" name="generalnote" class="input1" value="'.$rowReport['generalnote'].'"/>');
	$smarty->assign('sSupplierInput','<input type="text" name="placefrom" class="input1" value="'.$rowReport['placefrom'].'" >');
	$smarty->assign('sPlaceInput',load_health_station($select, $LDKhoLe));
	$smarty->assign('sTotalInput','<input type="text" id="total_money" name="total_money" class="input1" value="'.$rowReport['totalcost'].'"/>');
	$smarty->assign('sPutInIDInput','<input type="text" name="voucher_id" class="input1" value="'.$rowReport['voucher_id'].'" />');
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
        $smarty->assign('sTypePut','<select name="typeput" id="typeput" class="input1" ><option value="0" '.$flag1.'>'.$LDBH.'</option><option value="1" '.$flag2.'>'.$LDNoBH.'</option><option value="2" '.$flag3.'>'.$LDCBTC.'</option></select>');	
}


//sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" id="maxid" name="maxid" value="'.$maxid.'">
		<input type="hidden" name="target" value="'.$target.'">
		<input type="hidden" name="date_time" value="'.$date_time.'">
		<input type="hidden" name="payout_id" value="'.$payout_id.'">
		<input type="hidden" name="create_id" value="'.$create_id.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

//*********************************************************************************

$smarty->assign('LDSave','<a href="#"><image '.createComIcon($root_path,'pharmacy_save.png','0').' title="'.$LDSave.'" onClick="pay_out();"/>');

$smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/put_in_2.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

