<?php
include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
include_once($root_path.'include/care_api_classes/class_encounter.php');
include_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
if(!isset($pres_obj)) $pres_obj=new PrescriptionMedipot;

//Get info of encounter
$en_nr = $_SESSION['sess_en'];

if(!isset($encounter_obj)) 
	$encounter_obj =& new Encounter($en_nr); $encounter_cbtc=new Encounter;
if($encounter_obj->loadEncounterData()){
	$encounter = $encounter_obj->getLoadedEncounterData();
} else $encounter= array();


if(!isset($ward_obj)) $ward_obj=new Ward;

//Get info of prescription
$pres_all_types = $pres_obj->getPrescriptionTypes($noitru_ngoaitru);	//BH, noi tru, ngoai tru
$status_bill=0;	//chua thanh toan
$status_finish=0;
$item_medicines =0;		//So thuoc trong toa (mode='update')
$file_add_medicine= $root_path.'modules/registration_admission/include/prescription_addmedipot.php';
$file_autocomplete= 'include/prescription_autocomplete_medipot.php';
$sepChars=array('-','.','/',':',',');

//sheettype='sheet','pres'
$haveissur=$encounter_obj->isCorrectIssurent($pid);
$cbtc= $encounter_cbtc->isCorrectCBTC($pid);
if ($mode=='update'){
	//general info of prescription
	$detail_pres = $pres_obj->getPrescriptionInfo($pres_id);
	if($detail_pres){
		$pres_id = $detail_pres['prescription_id'];
		$status_bill = $detail_pres['status_bill'];
		$status_finish = $detail_pres['status_finish'];
		$diagnosis = $detail_pres['diagnosis'];
		$symptoms = $detail_pres['symptoms'];
		$note = $detail_pres['note'];
		$prescription_type = $detail_pres['prescription_type'];
		$date_pres = $detail_pres['date_time_create'];
		$sumdate = $detail_pres['sum_date'];
		$totalcost_pres = $detail_pres['total_cost'];
	}
	
	if($status_bill){
		$tempbill=$LDFinish; $tempbill1='check-r.gif';}
	else{
		$tempbill=$LDNotYet; $tempbill1='warn.gif';}
	if($status_finish){
		$tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
	else{
		$tempfinish=$LDNotYet; $tempfinish1='warn.gif';}
	
	
	//medicine in prescription
	$medicine_result = $pres_obj->getAllMedicineInPres($pres_id);
	if(is_object($medicine_result)){
		$item_medicines = $medicine_result->RecordCount();	//co bao nhieu loai thuoc dc ke trong toa
		//n = $item_medicines /$medicine_pres = $medicine_result->FetchRow(); /$medicine_pres[' ']	
	}
	
}
else
{
	$date_pres = date("Y-m-d G:i:s");
}



//$wardinfo = $encounter_obj->CurrentWardNr($en_nr);
$wardid = $encounter['current_ward_nr'];
//$wardid= trim($wardid);
if ($wardid)
	$wardname = $ward_obj->WardName($wardid);	//Khu phong

$deptinfo = $encounter_obj->CurrentDeptNr($en_nr);
$dept_nr=$encounter['current_dept_nr'];

//$dept_nr=trim($dept_nr);

//echo $dept_nr.' '.$wardid;
	
if($mode=='create' || $mode=='new')
{
	$diagnosis = $encounter['refferer_diagnosis'];
}
//So BHYT
$insurance_nr = $encounter['insurance_nr'];
$insurance_exp = $encounter['insurance_exp'];

?>
<style type="text/css">
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
.v12 { font-family:verdana,arial;font-size:12; }
.v13 { font-family:verdana,arial;font-size:13; }
.v10 { font-family:verdana,arial;font-size:10; }
#hint ul {
	list-style-type: none;
	font-family: verdana;
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
</style>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/effects.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/controls.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/builder.js"></script>
<script src="<?php echo $root_path; ?>js/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/jquery.maskedinput-1.3.js"></script>
<script language="javascript">
<!-- Script Begin
$.noConflict();
function checkbhyt(select){//add 0810 cot
    var haveissur = <? echo $haveissur;?>;
    //alert(haveissur);
    if(haveissur==-2 && (select.value=='0490_2'||select.value=='0491_2')){
        alert("BHYT của BN đã hết hạn, vui lòng cập nhật lại");
        select.value=0;
    }
    else if(haveissur == -1 && (select.value=='0489_0'||select.value=='0495_0')){
        alert("BN này không có BHYT");
        select.value=0;
    }
}
function checkCBTC(select){
    var cbtc = <? echo $cbtc;?>;
    //alert(haveissur);
    if(cbtc==-1 && (select.value=='0492_2'||select.value=='0496_2')){
        alert("BN không thuộc CBTC, vui lòng chọn lại");
        select.value=0;
    }

}
function chkform(d) {
	if(d.totalday.value==""){
		alert("<?php echo $LDPlsEnterTotalDay; ?>");
		d.totalday.focus();
		return false;
	}else if(d.total.value=="0"){
		alert("<?php echo $LDPlsEnterMedicine; ?>");
		d.total.focus();
		return false;
	}else{
		
	
	document.reportform.action="include/save_depot.inc.php";
	document.reportform.submit();
	
	}
}

function deletePres()
{
	var r=confirm("<?php echo $LDWouldDeletePres; ?>");
	if (r==true) {
	  	document.reportform.action="include/save_depot.inc.php?isdelete=delete";
		document.reportform.submit();
	}
}

function printPres()
{
	//window.parent.reportform.focus(); 
	window.print();

}

function insertRow()
{
  var tbl = document.getElementById('tblMedicine');
  var lastRow = tbl.tBodies[0].rows.length;
	//alert(lastRow);
  
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

			var tblstt = document.getElementById('tblSTT');
			var laststt = tblstt.tBodies[0].rows.length;
			var row=tblstt.tBodies[0].insertRow(-1);
			
			row.innerHTML = '<tr><td align="center" bgColor="#ffffff" height="50"><a href="javascript:;" onclick="deleteRow('+laststt+')">[x]</a></td><td bgColor="#ffffff" align="center">'+laststt+'.</td></tr>';		
		}
	}
	var total = document.getElementById('total');
	total.value = (total.value - 1)+2;
	
	var maxid = document.getElementById('theValue');
	maxid.value = maxid.value*1+1;
	var idnum=maxid.value;
	
	xmlhttp.open("GET","<?php echo $file_add_medicine; ?>?i="+idnum,true);
	xmlhttp.send();
  
}

function deleteRow(i)
{
  var tbl = document.getElementById('tblMedicine');
  var lastRow = tbl.tBodies[0].rows.length;
  //i=i-1+2;
  if (lastRow > i)
	tbl.tBodies[0].deleteRow(i);
	
  var tblstt = document.getElementById('tblSTT');
  var laststt = tblstt.tBodies[0].rows.length;
	tblstt.tBodies[0].deleteRow(laststt-1);

  var total = document.getElementById('total');
  total.value = total.value -1;
}

function rowUp(id_up){

}

function rowDown(id_down){

}

function searchMedicine(id_number)
{
	var type = document.getElementById('prescription_type_nr').value;
	var a = type.toString().split('_');
	var typeput = a[1];
	var win = 'search_medipot.php?' + 'id_number=' + id_number +"&typeput="+typeput;
	window.open(win,'popuppage','width=700,toolbar=1,resizable=1,scrollbars=yes,height=600,top=100,left=100');
	//myWindow.focus();
}

function calcost(x){
  //sum1 * cost1 = totalcost1;
  a = document.reportform['sum'+x].value;
  b = document.reportform['cost'+x].value; 
  document.reportform['totalcost'+x].value = a*b;
  
  //id="totalpres" name="totalpres"
  var n = document.getElementById('theValue').value;
  var total=0;
  for (i = 1; i <= n; i++)
  {
	if(document.getElementsByName('totalcost'+i).length)
		total = total + document.reportform['totalcost'+i].value*1;
  }
  document.getElementById('totalpres').value = total;
}


function CalDay(x){
  //sum = n*a*b
  a = document.reportform['totalday'+x].value;
  b = document.reportform['times'+x].value;
  c = document.reportform['count'+x].value;
  //sum=a*b;
  
  document.reportform['sum'+x].value = a*b;
	//alert(sum);
  
}
function Medicine_AutoComplete(i){
			var name_med='medicinea'+i;
            var str=document.getElementById("prescription_type_nr").value;
            var arr= str.split('_');
            var typeput=arr[1];
			var includeScript = "<?php echo $file_autocomplete; ?>?mode=auto&k="+i+"&typeput="+typeput;
			new Ajax.Autocompleter(name_med,"hint",includeScript, {
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
			//alert(text);
			var temp_value=text.split('-- ');
			document.getElementById('medicinea'+k).value = temp_value[0];
			var b=temp_value[1]; 
			var temp_cost=b.split(' vnd/');
			document.getElementById('cost'+k).value = temp_cost[0];
			document.getElementById('units'+k).value = temp_cost[1];	
			
			document.getElementById('inventory'+k).value = temp_value[2];		
			//$('input[name=inventory'+i+']').val(fill_value[1]);			
			
			CheckDuplicateMedicine();
			
}
function Fill_Data(i)
{
	var process_file='<?php echo $file_autocomplete; ?>?mode=filldata';
	var name_med='medicinea'+i;

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
			document.getElementById('inventory'+i).value = a[1];			
			document.getElementById('units'+i).value = a[2];
			document.getElementById('cost'+i).value = a[3];	
			//$('#component'+i).val(a[4]);
			//$('#caution'+i).val(a[5]);		
		
			CheckDuplicateMedicine();
		}
	}
	xmlhttp.open("GET",process_file+"&encoder="+document.getElementById('encoder'+i).value+"&search="+document.getElementById('medicinea'+i).value,true);
    xmlhttp.send();

}

//Kiem tra thuoc trung 
function CheckDuplicateMedicine(){
	var n = document.getElementById('theValue').value;		
	var enco_j, enco_k;
	for (j=1; j<=n; j++){
		enco_j = document.getElementById("medicinea"+j);
		enco_j.style.backgroundColor="white";
	}
	for (j=1; j<=n; j++){	
		enco_j = document.getElementById("medicinea"+j);
		if (enco_j.value!='') {
			for (k=j; k<=n; k++){
				enco_k = document.getElementById("medicinea"+k);
				if (k!=j && enco_k.value!='')
					if (enco_j.value==enco_k.value){
						enco_j.style.backgroundColor="gold";
						enco_k.style.backgroundColor="gold";
					}
			}
		}
	}
}

//  Script End -->
</script>
<script type="text/javascript">
	jQuery(function($){
        $("#inputdate").mask("**/**/**** **:**:**");
    });
//  Script End -->
</script>
<form method="post" name="reportform" onSubmit="return chkform(this)">

<table border=0 cellpadding="2" width="100%">
    <tr>
		<td width="17%"><FONT color="#000066"><?php echo $LDWard; ?></td>
		<td width="40%"><?php  echo $wardname; ?></td>
		<td width="17%"><FONT color="#000066"><?php echo $LDPrescriptionMedipot; ?></td>
		<td width="26%"><select onblur="checkbhyt(this);checkCBTC(this)" name="prescription_type_nr" id="prescription_type_nr" >
                <option value="0">Chon loại toa</option>
			<?php
//			if(is_object($pres_all_types)){
//				$temp1=0;
//				while($rowtype=$pres_all_types->FetchRow())
//				{
//					//if(($type=='sheet' && $rowtype['group_pres']=='1') || ($type=='pres' && $rowtype['group_pres']=='0')){
//						if ($mode=='create' && $temp1=='0')
//							$styleselect='SELECTED';
//						elseif ($mode=='update' && $rowtype['prescription_type']==$prescription_type)
//							$styleselect='SELECTED';
//						else
//							$styleselect=' ';
//
//						echo '<option value="'.$rowtype['prescription_type'].'_'.$rowtype['typeput'].'" '.$styleselect.'>';
//						echo $rowtype['prescription_type_name'];
//						echo '</option>';
//						$temp1++;
//					//}
//				}
//			}
            if(is_object($pres_all_types)){
                $temp1=0;
                while($rowtype=$pres_all_types->FetchRow())
                {
                    if(($type=='pres' && $rowtype['group_pres']=='1') ||($type=='pres' && $rowtype['group_pres']=='0')|| $type==''){
                        if ($mode=='new' || $mode=='create'){//edit 0810 cot
                            if($haveissur==1 && $rowtype['typeput']==0)
                                $styleselect=' SELECTED ';
                            else $styleselect=' ';
                        }
                        //$styleselect='SELECTED';
                        elseif ($mode=='update' && $rowtype['prescription_type']==$prescription_type)
                            $styleselect=' SELECTED ';
                        else
                            $styleselect=' ';

                        echo '<option value="'.$rowtype['prescription_type'].'_'.$rowtype['typeput'].'" '.$styleselect.'>';
                        echo $rowtype['prescription_type_name'];
                        echo '</option>';
                        $temp1++;
                    }
                }
            }
			?>
			</select>
         </td>
   </tr> 
   <tr>
		<td><FONT color="#000066"><?php echo $LDDate1; ?></td>
		<td><?php 
				if($mode=='update')
					$textdate = $date_pres; 
				else
					$textdate = date('Y-m-d H:i:s'); //date("Y-m-d");
				$texttime = substr($textdate,-8);
				$textdate = formatDate2Local($textdate,"DD/MM/YYYY",false,false,$sepChars);
				echo '<input type="text" name="inputdate" id="inputdate" value="'.$textdate.' '.$texttime.'" >'; ?>	 	 </td>
		<td><FONT color="#000066"><?php echo $LDInsuranceNr; ?></td>
		<td><?php echo $insurance_nr; ?></td>
   </tr>
    <tr>
		<td><FONT color="#000066"><?php echo $LDTotalDay; ?></td>
		<td><input type="text" name="totalday" size=11 value="<?php if($mode=='update') echo $sumdate; else echo '1'; ?>"></td>
		<td><FONT color="#000066"><?php echo $LDInsuranceExp; ?></td>
		<td><input type="text" id="expdate" size=11 value="<?php echo formatDate2Local($encounter['pinsurance_exp'],"DD/MM/YYYY",false,false,$sepChars); ?>" style="border:none;" readonly></td>
	</tr>   
   <tr>
		<td><FONT color="#000066"><?php if ($mode=='update') echo $LDPaid;?></td>
		<td><FONT color="#000066">
			<?php
				if ($mode=='update'){
					echo $tempbill.' ';?>
					<img <?php echo createComIcon($root_path,$tempbill1,'0','',TRUE); ?>><?
				}
			?></td>
		<td valign="top"><FONT color="#000066"><?php if($mode=='update') echo $LDGotDrug; ?></td>
		<td valign="top"><FONT color="#000066">
			<?php
				if ($mode=='update'){
					echo $tempfinish.' '; ?>
					<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>><?
				}
			?></td>
   </tr>	
	<tr>
		<td colspan="4"><FONT color="#000066"><?php echo $LDMedipot.'<p>'; ?></td>
	</tr>	
	<tr>
		<td colspan="4">

			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E1E1E1"> 	<?php //-- Them thuoc vao toa thuoc -- ?>
				<tr>						
					<td valign="top" width="9%"> <!-- STT -->	
						<table id="tblSTT" bgColor="#E1E1E1" cellpadding="2" cellspacing="1" border="0" width="100%">
							<tr bgColor="#EEEEEE">
								<td height="40" colspan="2" align="center" ><?php echo 'STT'; ?></td>
							</tr>
							<?php if($mode=='update'){ 
								for($i=1;$i<=$item_medicines;$i++) { 
									echo '<tr bgColor="#ffffff">
											<td align="center" height="50"><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
											<td align="center">'.$i.'.</td>
										</tr>';
								}							
							} else {
								$i=1;
								echo '<tr bgColor="#ffffff" height="50">
										<td align="center"><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
										<td align="center">'.$i.'.</td>
									</tr>';
							} ?>
						</table>
					</td>
					<td>						<!-- Thuoc -->
						<table id="tblMedicine" bgColor="#E1E1E1" cellpadding="2" cellspacing="1" border="0" width="100%">
							<tr  bgColor="#EEEEEE">
								<td align="center" width="5%"><?php echo $LDSearch1; ?></td>
								<td align="center" width="35%" height="40"><?php echo $LDMedipotName; ?></td>
								<td align="center" width="10%"><?php echo $LDInventoryVTYT; ?></td>
								<td align="center" width="20%"><?php echo $LDNumberOf; ?></td>
								<td align="center" width="14%"><?php echo $LDCost; ?></td>
								<td align="center" width="12%"><?php echo $LDTotalCost; ?></td>
								<td align="center" width="5%"><?php echo $LDNote; ?></td>
							</tr>
							<?php if($mode=='update'){ 
								$sTempDiv='';
								for($i=1;$i<=$item_medicines;$i++) { 			
									$medicine_pres = $medicine_result->FetchRow();	
									$totalcostmedicine = $medicine_pres['sum_number']*$medicine_pres['cost'];						
									$split_desciption = explode(" ",$medicine_pres['desciption']);
									
									ob_start();
									require($file_add_medicine);
									$sTempDiv = $sTempDiv.ob_get_contents();				
									ob_end_clean();	
								} 
								echo $sTempDiv;
								
							} else {
								$i=1; $sTempDiv='';
								ob_start();
								require($file_add_medicine);
								$sTempDiv = ob_get_contents();				
								ob_end_clean();	
								
								echo $sTempDiv;
							} ?>
						</table>
					</td>					
				</tr>
				<tr>
					<td colspan="2" bgColor="#EEEEEE" height="25"><a href="javascript:;" onclick="insertRow();" ><?php echo '&nbsp;['.$LDAddRowMedipot.']&nbsp;'; ?></a></td>
				</tr>
			</table>
			
		</td>
	</tr>
	
	<?php //-- Loi dan bac si & button -- ?>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
    <tr>
		<td><FONT color="#000066"><?php echo $LDNote; ?></td>
		<td><textarea name="note" cols="35" rows="3" wrap="physical" ><?php if($mode=='update') echo $note; ?> </textarea>
		</td>
		<td><FONT color="#000066"><?php echo $LDTotal1; ?></td>
		<td><input type="text" id="totalpres" name="totalpres" size=11 value="<?php if($mode=='update') echo $totalcost_pres; else echo '0'; ?>" style="border:0px;" readonly></td>
 	</tr> 
	<tr>
		<td><FONT color="#000066"><?php echo $LDNurse; ?></td>
		<td><input type="text" name="doctor" size=35 value="<?php echo $_SESSION['sess_user_name']; ?>" style="border:0px;" readonly></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="4"> &nbsp; </td>
	</tr>
	<tr>
		<td colspan="4" ><FONT SIZE=1  FACE="Arial"> <?php echo $LDNoteMedipotPres; ?></td>
	</tr>
</table>
	
 <?php
	//-- Input Hidden --
 if($mode=='update') { ?>
	<input type="hidden" id="theValue" name="theValue" value="<?php echo $item_medicines; ?>" >
	<input type="hidden" id="total" name="total" value="<?php echo $item_medicines; ?>" >
	<input type="hidden" id="idpres" name="idpres" value="<?php echo $pres_id; ?>" >
<?php } else { ?>
	<input type="hidden" id="theValue" name="theValue" value="1" >
	<input type="hidden" id="total" name="total" value="1" >
<?php } ?>

<input type="hidden" name="encounter_nr"  value="<?php echo $_SESSION['sess_full_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="status_bill" value="<?php echo $status_bill; ?>">
<input type="hidden" name="status_finish" value="<?php echo $status_finish; ?>">
<input type="hidden" name="mode" value="<?php echo $mode; ?>">
<input type="hidden" name="ward_nr" value="<?php echo $wardid; ?>">
<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">

<br>
<?php //-- Button Save & Print -- ?>
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>&nbsp;
	<img <?php echo createLDImgSrc($root_path,'printout.gif','0'); ?>  OnClick="printPres()" />
&nbsp;
	<img <?php echo createLDImgSrc($root_path,'delete.gif','0'); ?> OnClick="deletePres()" />
	
<p>&nbsp;</p>
</form>
