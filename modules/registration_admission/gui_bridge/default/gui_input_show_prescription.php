<?php
include_once($root_path.'include/care_api_classes/class_prescription.php');
include_once($root_path.'include/care_api_classes/class_encounter.php');
include_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
if(!isset($pres_obj)) $pres_obj=new Prescription;
if(!isset($encounter_obj)) $encounter_obj=new Encounter;
if(!isset($ward_obj)) $ward_obj=new Ward;

//Get info of prescription
$pres_all_types = $pres_obj->getPrescriptionTypes($noitru_ngoaitru);	//BH, noi tru, ngoai tru
$status_bill=0;	//chua thanh toan
$status_finish=0;
$item_medicines =0;		//So thuoc trong toa (mode='update')
$file_add_medicine= $root_path.'modules/registration_admission/include/prescription_addmedicine.php';
$file_autocomplete= 'include/prescription_autocomplete_medicine.php';
$sepChars=array('-','.','/',':',',');

$en_nr = $_SESSION['sess_en'];
//sheettype='sheet' (to dieu tri), 'pres' (toa ngoai tru)
$haveissur=$encounter_obj->isCorrectIssurent($pid);
//echo $haveissur;

if ($mode=='update'){
	//general info of prescription
	$detail_pres = $pres_obj->getPrescriptionInfo($pres_id);
	if($detail_pres){
		$pres_id = $detail_pres['prescription_id'];
		$status_bill = $detail_pres['status_bill'];
		$status_finish = $detail_pres['status_finish'];
		$diagnosis = stripslashes($detail_pres['diagnosis']);
		$symptoms = stripslashes($detail_pres['symptoms']);
		$note = $detail_pres['note'];
		$prescription_type = $detail_pres['prescription_type'];
		$date_pres = $detail_pres['date_time_create'];
		$sumdate = $detail_pres['sum_date'];
		$totalcost_pres = $detail_pres['total_cost'];
		$taikham=$detail_pres['taikham'];
		$nghiphep=$detail_pres['nghiphep'];
		$text_CLS=$detail_pres['cls'];
		$text_SinhHieu=$detail_pres['sinhhieu'];
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
else if($as_old){
	$detail_pres = $pres_obj->getOldPrescriptionInfo($en_nr);
	if($detail_pres){
		$pres_id = $detail_pres['prescription_id'];
//		$status_bill = $detail_pres['status_bill'];
//		$status_finish = $detail_pres['status_finish'];
        $status_bill=false;
        $status_finish=false;
		$diagnosis = stripslashes($detail_pres['diagnosis']);
		$symptoms = stripslashes($detail_pres['symptoms']);
		$note = $detail_pres['note'];
		$prescription_type = $detail_pres['prescription_type'];
		$date_pres = $detail_pres['date_time_create'];
		$sumdate = $detail_pres['sum_date'];
		$totalcost_pres = $detail_pres['total_cost'];
	}
	$medicine_result = $pres_obj->getAllMedicineInPres($pres_id);
	if(is_object($medicine_result)){
		$item_medicines = $medicine_result->RecordCount();	//co bao nhieu loai thuoc dc ke trong toa	
	}	
}
else
{
	$date_pres = date("Y-m-d H:i:s");
}


//Get info of encounter
$wardinfo = $encounter_obj->CurrentWardNr($en_nr);
$wardid = trim($wardinfo,'current_ward_nr');
$wardid= trim($wardid);
if ($wardid)
	$wardname = $ward_obj->WardName($wardid);	//Khu phong

$deptinfo = $encounter_obj->CurrentDeptNr($en_nr);
$dept_nr=trim($deptinfo,'current_dept_nr');
$dept_nr=trim($dept_nr);

//echo $dept_nr.' '.$wardid;
	
if($mode=='create' || $mode=='new')
{
	if($todo)
		$diagnosis='';
	else{	
		$diagnosis = $encounter_obj->RefererDiagnosis($en_nr);		//Chan doan
		$benhphu = $encounter_obj->BenhPhu($en_nr);
		if ($benhphu!='')
			$diagnosis = $diagnosis."\n ".$LDBenhPhu.': '.$benhphu;
		//$diagnosis = trim($diagnosis,'refferer_diagnosis');
	}
}
$temp_pid= $encounter_obj->PID($en_nr);
$pid = trim($temp_pid,'pid');	

require_once($root_path.'include/care_api_classes/class_person.php');
$Person = new Person();
if($re_person = $Person->getInfoInsurEnc($pid)){
	$insurance_st = $re_person['insurance_start'];
	$insurance_end = $re_person['insurance_exp'];
	$insurance_kcbbd = $re_person['madkbd'];
	$insurance_nr = $re_person['insurance_nr'];
}

//$temp_BH = $encounter_obj->InsuranceNr($en_nr);	//So BHYT
//$insurance_nr = trim($temp_BH,'insurance_nr');	
//$temp_BH = $encounter_obj->InsuranceMaKCB_BD($en_nr);
//$insurance_kcbbd = trim($temp_BH,'madk_kcbbd');


if($type!='sheet' && $mode!='update'){

	$sql_cls="SELECT dr.* FROM care_encounter_diagnostics_report AS dr 
				WHERE dr.encounter_nr='".$en_nr."'  
				ORDER BY dr.create_time";	
	if($result_cls=$db->Execute($sql_cls)){
		while ($item_cls = $result_cls->FetchRow()){
			if($item_cls['reporting_dept_nr']==14){
				$url = explode('/',$item_cls['script_call']);
				switch($url[0]){
					case 'xquang': $text_XQ .= ', '.$item_cls['reporting_dept']; break;
					case 'sieuam': $text_SA .= ', '.$item_cls['reporting_dept']; break;
					case 'dientim': $text_DT .= ', '.$item_cls['reporting_dept']; break;
				}
			}else{
				$text_XN .= ', '.$item_cls['reporting_dept'];
			}
		}
	}
	
	if(strlen($text_XQ)>2) $text_XQ = substr($text_XQ, 2); else $text_XQ='.........................';
	if(strlen($text_SA)>2) $text_SA = substr($text_SA, 2); else $text_SA='.........................';
	if(strlen($text_DT)>2) $text_DT = substr($text_DT, 2).', '; else $text_DT='.....................';
	if(strlen($text_XN)>2) $text_XN = substr($text_XN, 2); else $text_XN='.........................';
	if(strlen($text_NS)>2) $text_NS = substr($text_NS, 2); else $text_NS='';
	$text_CLS= 'XQ: '.$text_XQ."\nSA: ".$text_SA."\n".$LDKhac.": ".$text_DT." ".$text_NS."\nXN: ".$text_XN; 
	
	//phau thuat, thu thuat
	$sql_pttt= "SELECT bill.*,listitem.* FROM care_billing_bill_item AS bill, care_billing_item AS listitem 
				WHERE bill.bill_item_encounter_nr='".$en_nr."' AND bill.bill_item_status='1'
				AND  listitem.item_type='HS' AND listitem.item_group_nr IN (33,34)
				AND bill.bill_item_code=listitem.item_code";
				
	if($result_pttt=$db->Execute($sql_pttt)){
		while ($item_pttt = $result_pttt->FetchRow()){
			$text_PTTT .= $item_pttt['item_description']."\n";
		}
		$text_CLS=$text_CLS." \nTT/PT: ".$text_PTTT;
	}
	
	//type= mach:2, huyetap:1, cannang:6, nhietdo:3, 
	$sql_sinhhieu="SELECT msr_type_nr, value, MAX(msr_date), MAX(msr_time) FROM care_encounter_measurement 
			WHERE encounter_nr='".$en_nr."'  
			GROUP BY msr_type_nr";
	if($result_sh=$db->Execute($sql_sinhhieu)){
		while ($item_sh = $result_sh->FetchRow()){
			switch($item_sh['msr_type_nr']){
				case 1: $value_HA= $item_sh['value']; break;
				case 2: $value_Mach= $item_sh['value']; break;
				case 3: $value_ND= $item_sh['value']; break;
				case 6: $value_CN= $item_sh['value']; break;
			}
		}
	}
	$text_SinhHieu= $LDDiastolic.': '.$value_Mach.' '.$LDnpm."\n". $LDTemperature.': '.$value_ND." °C\n".'HA: '.$value_HA." mmHg\n".$LDWeight.': '.$value_CN.' Kg'; 	
}

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
.area1 {
	background:#EEE;
	border:none;
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
	if(haveissur==-2 && (select.value=='0398_0'||select.value=='0400_0')){
		alert("BHYT của BN đã hết hạn, vui lòng cập nhật lại");
		select.value=0;
	}
	else if(haveissur == -1 && (select.value=='0398_0'||select.value=='0400_0')){
		alert("BN này không có BHYT");
		select.value=0;
	}
}
function chkform(d) {
	var todo = '<?php echo $todo; ?>';
	//alert(d.diagnosis.value+' '+d.totalday.value+' '+d.total.value+' '+todo);
	if(d.diagnosis.value==""){
		alert("<?php echo $LDPlsEnterRefererDiagnosis; ?>");
		d.diagnosis.focus();
		return false;
	}else if(todo=='0'){
		if(d.totalday.value==""){ 
			alert("<?php echo $LDPlsEnterTotalDay; ?>");
			d.totalday.focus();
			return false;
		}
	}else if(d.total.value=="0" && todo=='0'){
		alert("<?php echo $LDPlsEnterMedicine; ?>");
		d.total.focus();
		return false;
	}
	if(d.prescription_type_nr.value==0) //add 03102012 - cot
	{
		alert("Vui lòng chọn loại toa.");
		d.prescription_type_nr.focus();
		return false;
	}
		
	document.reportform.action="include/save_prescription.inc.php";
	document.reportform.submit();
	document.getElementById('buttonsave').unable;
	
}

function deletePres()
{
	var r=confirm("<?php echo $LDWouldDeletePres; ?>");
	if (r==true) {
	  	document.reportform.action="include/save_prescription.inc.php?isdelete=delete";
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
			
			row.innerHTML = '<tr><td align="center" bgColor="#ffffff" height="130"><a href="javascript:;" onclick="deleteRow('+laststt+')">[x]</a></td><td bgColor="#ffffff" align="center">'+laststt+'.</td></tr>';		
		}
	}
	var total = document.getElementById('total');
	total.value = (total.value - 1)+2;
	
	var maxid = document.getElementById('theValue');
	maxid.value = maxid.value*1+1;
	var idnum=maxid.value;
	var type = document.getElementById('type').value;
	
	xmlhttp.open("GET","<?php echo $file_add_medicine; ?>?i="+idnum+'&type='+type,true);
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
	var win = 'search_medicine.php?' + 'id_number=' + id_number +"&typeput="+typeput;
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
  document.getElementById('totalpres').value = total.toFixed(1);
}


function CalDay(x){
  //sum = n*a*b
  var a = document.getElementById('totalday').value;
  var b = document.getElementById('times'+x).value;
  var c = document.getElementById('count'+x).value;
  //sum=a*b;
  
  document.getElementById('sum'+x).value = a*b*c;
	//alert(sum);
  calcost(x);
}
function ChangeAtTime(x){
	var n = document.getElementById('times'+x).value;
	var defaulttime, temptime;
	var type = 'sheet';
	if(type=='<?php echo $type; ?>'){	//toa noi tru
		switch(n){
			case '1': temptime='8h-'; break;
			case '2': temptime='8h-14h-'; break;
			case '3': temptime='8h-14h-20h-'; break;
			case '4': temptime='8h-12h-16h-20h-'; break;
			default: temptime='0h-6h-12h-18h-'; break;
		}
		defaulttime = temptime.split('h-');
		var texthtml='';
		for (i=1; i<=n; i++){
			texthtml = texthtml + '<input type="text" name="attime_'+x+'_'+i+'" id="attime_'+x+'_'+i+'" value="'+defaulttime[i-1]+'h" style="width:60px;">&nbsp;';
			/*for (j=0; j<=23; j++){
				if (j==defaulttime[i-1])
					texthtml = texthtml + '<option value="'+j+'h" selected >'+j+'h</option>';
				else 
					texthtml = texthtml + '<option value="'+j+'h">'+j+'h</option>';	
			}
			texthtml = texthtml + '</select> &nbsp;';*/
		}
	}else{	//toa ngoai tru (type='pres')
		switch(n){
			case '1': temptime='<?php echo 'sáng'; ?>'; break;
			case '2': temptime='<?php echo 'sáng-chiều'; ?>'; break;
			case '3': temptime='<?php echo 'sáng-trưa-tối'; ?>'; break;
			case '4': temptime='<?php echo 'sáng-trưa-chiều-tối'; ?>'; break;
			default: temptime='<?php echo 'sáng-trưa-chiều-tối'; ?>'; break;
		}
		defaulttime = temptime.split('-');
		var texthtml='';
		var index;
		var a = ["<?php echo 'sáng'; ?>", "<?php echo 'trưa'; ?>", "<?php echo 'chiều'; ?>", "<?php echo 'tối'; ?>"];	
		for (i=1; i<=4; i++){
			if(i<=n){
				texthtml = texthtml + '<select name="attime_'+x+'_'+i+'" id="attime_'+x+'_'+i+'" >';				
				for (index = 0; index < a.length; index++) {
					if (a[index]==defaulttime[i-1])
						texthtml = texthtml + '<option value="'+a[index]+'" selected >'+a[index]+'</option>';
					else 
						texthtml = texthtml + '<option value="'+a[index]+'">'+a[index]+'</option>';
				}
				texthtml = texthtml + '</select> &nbsp;';
			}
		}
	}
	document.getElementById('vaoluc'+x).innerHTML = texthtml;
}
function Medicine_AutoComplete(i){
			var typeput=document.getElementById("prescription_type_nr").value;
//            var arr= str.split('_');
//            var typeput=arr[1];
			var name_med='medicinea'+i;
			var includeScript = "<?php echo $file_autocomplete; ?>?mode=auto&k="+i+"&typeput="+typeput;
			new Ajax.Autocompleter(name_med,"hint",includeScript, {
					method: 'get',
					paramName: 'search',
					afterUpdateElement : setSelectionId				
				}
			);
}
		
function setSelectionId(div,li) {
			//$.noConflict();
			var a=li.id;
			var temp_id=a.split('@#');
			var k=temp_id[0];
			document.getElementById('encoder'+k).value = temp_id[1];
			document.getElementById('avai_id'+k).value = temp_id[2];
			
			var text=div.value; 
			//alert(text);
			var temp_value=text.split('-- ');
			document.getElementById('medicinea'+k).value = temp_value[0];
			var b=temp_value[1]; 
			var temp_cost=b.split(' vnd/');
			document.getElementById('cost'+k).value = temp_cost[0];
			document.getElementById('units'+k).value = temp_cost[1];
			document.getElementById('totalunits'+k).value = temp_cost[1];
			
			
			document.getElementById('inventory'+k).value = temp_value[2];		
			//$('input[name=inventory'+i+']').val(fill_value[1]);	
			
			calcost(k); //document.getElementById('totalcost'+k).value = temp_cost[0]*document.getElementById('sum'+k).value;
			CheckDuplicateMedicine();
			CheckNumberRequest(k);
			
}
function Fill_Data_Search(i){
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
			document.getElementById('totalunits'+i).value = a[2];
			document.getElementById('cost'+i).value = a[3];	
			document.getElementById('component'+i).value = a[4];	 
			//$('#component'+i).val(a[4]);
			document.getElementById('caution'+i).value = a[5]; 
			//$('#caution'+i).val(a[5]);		
			
			CheckDuplicateMedicine();
			CheckNumberRequest(i);
		}
	}
	xmlhttp.open("GET",process_file+"&encoder="+document.getElementById('encoder'+i).value+"&search="+document.getElementById('medicinea'+i).value,true);
    xmlhttp.send();

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
			//document.getElementById('encoder'+i).value = a[0]; 	
			//document.getElementById('inventory'+i).value = a[1];			
			//document.getElementById('units'+i).value = a[2];
			//document.getElementById('cost'+i).value = a[3];	
			document.getElementById('component'+i).value = a[4];	 
			//$('#component'+i).val(a[4]);
			document.getElementById('caution'+i).value = a[5]; 
			//$('#caution'+i).val(a[5]);		
			
			CheckDuplicateMedicine();
			CheckNumberRequest(i);
		}
	}
	xmlhttp.open("GET",process_file+"&avai_id="+document.getElementById('avai_id'+i).value+"&search="+document.getElementById('medicinea'+i).value,true);
    xmlhttp.send();

}

//Kiem tra thuoc trung 
function CheckDuplicateMedicine(){
	var n = document.getElementById('theValue').value;		
	var enco_j, enco_k, warn_j, warn_k;
	for (j=1; j<=n; j++){
		enco_j = document.getElementById("encoder"+j);
		if(enco_j){
			warn_j = document.getElementById("warning"+j);
			warn_j.style.backgroundColor="white";
		}	
	}
	for (j=1; j<=n; j++){	
		enco_j = document.getElementById("encoder"+j);
		if (enco_j.value!='') {
			for (k=j; k<=n; k++){
				enco_k = document.getElementById("encoder"+k);
				if (k!=j && enco_k.value!='')
					if (enco_j.value==enco_k.value){
						warn_j = document.getElementById("warning"+j);
						warn_k = document.getElementById("warning"+k);
						warn_j.style.backgroundColor="gold";
						warn_k.style.backgroundColor="gold";
					}
			}
		}
	}
}

//Kiem tra so luong thuoc
function CheckNumberRequest(i){
	var enco, warn, inventory, sum, color;
	enco = document.getElementById("encoder"+i);
	warn = document.getElementById("warning"+i);
	inventory = document.getElementById("inventory"+i);
	sum = document.getElementById("sum"+i);

	if (enco.value=='' || (sum.value>inventory.value))
		warn.style.backgroundColor="red";
	//else warn.style.backgroundColor="white";
	
}


</script>

<script type="text/javascript">
	jQuery(function($){
        $("#inputdate").mask("**/**/**** **:**:**");
    });
//  Script End -->
</script>

<form method="post" name="reportform" onSubmit="return chkform(this)">

<table border=0 cellpadding="2" width="100%">
    <?php if($type!='sheet'){ ?>
		<tr bgcolor="#EEE">
			<td colspan="2" align="center"><font color="#039"><b><?php echo $LDDauHieuSinhTon ?></b></font>
				<?php echo '<table><tr><td><textarea name="sinhhieu" cols="25" rows="5" wrap="physical"  >';
					echo $text_SinhHieu;
					echo '</textarea></td></tr></table>'; ?>
			</td>
			<td colspan="2" align="center"><font color="#039"><b><?php echo $LDCanLamSang ?></b></font>
				<?php echo '<table><tr><td><textarea name="cls" cols="30" rows="6" wrap="physical"  ondblclick="this.select()">';
					echo $text_CLS;				
					echo '</textarea></td></tr></table>'; ?>
			</td>
		</tr>
	<?php } ?>
	<tr>
		<td width="17%"><FONT color="#000066"><?php echo $LDWard; ?></td>
		<td width="40%"><?php  echo $wardname; ?></td>		
	<?php if($todo)	echo '<td colspan="2"><input type="hidden" name="prescription_type_nr" id="prescription_type_nr" value="0397_1"></td>';
		else {	
	?>
		<td width="17%"><FONT color="#000066"><?php echo $LDPrescription; ?></td>
		<td width="26%"><select onblur="checkbhyt(this)" name="prescription_type_nr" id="prescription_type_nr" >
		<option value="0">Chon loai toa</option> <!-- add 03102012 - cot -->
			<?php
			if(is_object($pres_all_types)){
				$temp1=0;
				while($rowtype=$pres_all_types->FetchRow())
				{
					if(($type=='sheet' && $rowtype['group_pres']=='1') || ($type=='pres' && $rowtype['group_pres']=='0')){
						if ($mode=='new'|| $mode=='create'){//edit 0810 cot
							if($haveissur==1 && $rowtype['typeput']==0)
								$styleselect=' SELECTED ';
							else $styleselect=' ';
						}
							//$styleselect='SELECTED';
						elseif ($mode=='update' && $rowtype['prescription_type']==$prescription_type)
							$styleselect=' SELECTED ';
						else
							$styleselect=' ';
						
						echo '<option value="'.$rowtype['typeput'].'" '.$styleselect.'>';
						echo $rowtype['prescription_type_name'];
						echo '</option>';
						$temp1++;
					}
				}
			}
			?>
			</select>
         </td>
	<?php } ?>	 
   </tr>
   
   <tr>
		<td><FONT color="#000066"><?php echo $LDDate1; ?></td>
		<td><?php 
				if($as_old)
					$textdate =date("Y-m-d H:i:s");
				else
					$textdate = $date_pres; 
				$texttime = substr($textdate,-8);
				$textdate = formatDate2Local($textdate,"DD/MM/YYYY",false,false,$sepChars);
				echo '<input type="text" name="inputdate" id="inputdate" value="'.$textdate.' '.$texttime.'" >'; ?>	 	 </td>
	<?php if($todo)	echo '<td colspan="2"></td>';
		else {	?>
		<td><FONT color="#000066"><?php echo $LDInsuranceNr; ?></td>
		<td><?php echo $insurance_nr.' / '.$insurance_kcbbd; ?></td>
		<?php } ?>
   </tr>
   <tr>
		 <td rowspan=2><FONT color="#000066"><?php if ($todo) echo $LDDienBienBenh; else echo $LDSymptoms; ?></td>
		 <td rowspan=2><FONT color="#000066"><textarea name="symptoms" cols="35" rows="2" wrap="physical" ><?php echo $symptoms; ?> </textarea>
			 </td>
		<td><FONT color="#000066"><?php if ($mode=='update'  & $todo==0) echo $LDPaid;?></td>
		<td><FONT color="#000066">
			<?php
				if ($mode=='update' && $todo==0){
					echo $tempbill.' ';?>
					<img <?php echo createComIcon($root_path,$tempbill1,'0','',TRUE); ?>><?
				}
			?></td>
   </tr>
   <tr>
		<td valign="top"><FONT color="#000066"><?php if($mode=='update' & $todo==0) echo $LDGotDrug; ?></td>
		<td valign="top"><FONT color="#000066">
			<?php
				if ($mode=='update' && $todo==0){
					echo $tempfinish.' ';?>
					<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>><?
				}
			?></td>
   </tr>
    <tr>
		<td><FONT color="#000066"><?php if($todo) echo $LDTreatment; else echo $LDDiagnosis; ?></td>
		<td><FONT color="#000066"><textarea name="diagnosis" cols="35" rows="3" wrap="physical" > <?php echo $diagnosis; ?> </textarea> 	</td>
<?php if($todo){
		echo '<td colspan="2"></td>';
	} else { ?>
		<td><FONT color="#000066"><?php //echo $LDTotalDay; ?></td>		
		<td><input type="hidden" name="totalday" id="totalday" size=11 value="<?php if($mode=='update' || $as_old) echo $sumdate; else echo '1'; ?>"></td>
<?php 
		} ?>
	</tr>   
<?php
	if ($todo==0){
?>		
	<tr>
		<td colspan="4"><FONT color="#000066"><?php echo $LDMedicine.'<p>'; ?></td>
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
							<?php if($mode=='update' || $as_old){ 
								for($i=1;$i<=$item_medicines;$i++) { 
									echo '<tr bgColor="#ffffff">
											<td align="center" height="130"><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
											<td align="center">'.$i.'.</td>
										</tr>';
								}
								
							} else {
								$i=1;
								echo '<tr bgColor="#ffffff" height="130">
										<td align="center"><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
										<td align="center">'.$i.'.</td>
									</tr>';
							} ?>
						</table>
					</td>
					<td>						<!-- Thuoc -->
						<table id="tblMedicine" bgColor="#E1E1E1" cellpadding="2" cellspacing="1" border="0" width="100%">
							<tr  bgColor="#EEEEEE">
								<td align="center" width="2%"><?php echo 'Tìm'; ?></td>
								<td align="center" width="43%" height="40"><?php echo $LDMedicineName; ?></td>
								<td align="center" width="12%"><?php echo $LDInventory; ?></td>
								<td align="center" width="15%"><?php echo $LDNumberOf; ?></td>
								<td align="center" width="14%"><?php echo $LDCost; ?></td>
								<td align="center" width="12%"><?php echo $LDTotalCost; ?></td>
								<td align="center" width="5%"><?php echo $LDNote; ?></td>
								<td align="center" width="2%"></td>
							</tr>
							<?php if($mode=='update' || $as_old){ 
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
					<td colspan="2" bgColor="#EEEEEE" height="25"><a href="javascript:;" onclick="insertRow();" ><?php echo '&nbsp;['.$LDAddRowMedicine.']&nbsp;'; ?></a></td>
				</tr>
			</table>
			
		</td>
	</tr>
	
	<?php //-- Loi dan bac si & button -- ?>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
    <tr>
		<td><FONT color="#000066"><?php echo $LDNote; ?></font><br><br>
			<?php echo '+ '.$LDLoiDan.':'; ?></td>
		<td><br><textarea name="note" cols="35" rows="2" wrap="physical" ><?php if($mode=='update' || $as_old) echo $note; ?> </textarea></td>
		<td><FONT color="#000066"><?php echo $LDTotalEstimate; ?></td>
		<td><input type="text" id="totalpres" name="totalpres" size=11 value="<?php if($mode=='update' || $as_old) echo round($totalcost_pres,2); else echo '0'; ?>" style="border:0px;" readonly></td>
 	</tr> 
<?php
	}
?>	
	<tr>		
			<?php if($type!='sheet'){ 
				echo '<td colspan="2">'; 
				echo '+ '.$LDTaiKham.' <input type="text" name="taikham" size="2" value="'.$taikham.'"> '.$LDday1.'<br>';
				echo '+ '.$LDBenhNhanDuocNghiPhep.'<input type="text" name="nghiphep" size="2" value="'.$nghiphep.'"> '.$LDday1; 
				echo '</td>';
				} ?>
		<td valign="top"><FONT color="#000066"><?php if($todo) echo $LDDoctor; else echo $LDPrescribedBy; ?></td>
		<td valign="top"><input type="text" name="doctor" size=35 value="<?php echo $_SESSION['sess_user_name']; ?>" style="border:0px;" readonly></td>
			<?php if($type=='sheet'){ echo '<td colspan="2"></td>'; } ?>
	</tr>
		<td colspan="4"> &nbsp; </td>
	</tr>
	<tr>
		<td colspan="4" ><FONT SIZE=1  FACE="Arial"> <?php if($todo==0) echo $LDNoteMedicinePres; ?></td>
	</tr>
</table>
	
 <?php
	//-- Input Hidden --
 if($mode=='update' || $as_old) { ?>
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
<input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
<input type="hidden" name="encounter_class_nr" value="<?php echo trim($encounter_class_nr); ?>">
<input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">

<br>
<?php //-- Button Save & Print -- ?>
<input id="buttonsave" type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>&nbsp;
	<img <?php echo createLDImgSrc($root_path,'printout.gif','0'); ?>  OnClick="printPres()" />
&nbsp;
	<img <?php echo createLDImgSrc($root_path,'delete.gif','0'); ?> OnClick="deletePres()" />
	
<p>&nbsp;</p>
</form>
