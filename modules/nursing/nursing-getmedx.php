<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
global $db;
include_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($objPrescription))
$objPrescription=new Prescription;

$app_types=$objPrescription->getAppTypes();			//duong truyen
$pres_types=$objPrescription->getPrescriptionTypes();		//noi tru, noi tru BHYT
$unit_types=$objPrescription->getUnitTypes();		//don vi dich truyen
// load the encounter class to check if patient is discharged
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj= new Encounter;
$enc_obj->loadEncounterData($pn);
$isDischarged = $enc_obj->Is_Discharged($pn);	//xuat vien chua?

/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/

$lang_tables=array('departments.php');
define('LANG_FILE','nursing.php');
$local_user='ck_pflege_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_visual_signalling_fx.php');
$thisfile=basename(__FILE__);

///$db->debug=true;
if(!isset($prescriber)||empty($prescriber)) $prescriber=$_COOKIE[$local_user.$sid];

$title="$LDPhieuTheoDoiTruyenDich";

//Get info of dept, ward
//$wardinfo = $enc_obj->encounter['current_ward_nr'];
$ward_nr = trim($enc_obj->encounter['current_ward_nr']);

//$deptinfo = $enc_obj->CurrentDeptNr($en_nr);
$dept_nr=trim($enc_obj->encounter['current_dept_nr']);

if ($ward_nr!='' && $ward_nr!='0'){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} elseif ($dept_nr!='' && $dept_nr!='0'){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
	}
}

/*
	mode=''		: tao toa moi	=> cuoi file => save
	mode='save'	: luu toa moi (insert) => xong => header(mode='')
	mode='edit'	: show toa thuoc cu/moi tao => cuoi file => update
	mode='update': cap nhat toa thuoc (update) => xong => header(mode='')
*/


//Luu lai don thuoc (save prescription)
if($mode=='save'){
	$saved=false;
	if(!isset($maxelements))
		$maxelements = $_POST['maxelements'];
	if($maxelements) {
		//prescription_info 
		$objPrescription->usePrescription('prescription_info');
		$pres_id = $objPrescription->getLastIDPrescription();
		if ($pres_id==false)
			$lastId=1;
		else $lastId = $pres_id['prescription_id']+1;
		$datetime = date("Y-m-d G:i:s"); //$_POST['prescribe_date']
				
		$pharma_prescription_info = array('prescription_id' => $lastId,
					'prescription_type' => $prescription_type_nr,	
					'dept_nr' => $dept_nr,
					'ward_nr' => $ward_nr,
					'date_time_create'=> $datetime,
					'symptoms' => '',
					'diagnosis' => $diagnosis,
					'note' => $notes,
					'history' => 'Created by : '.$datetime.' : '.$prescriber,
					'doctor' => $prescriber,	
					'encounter_nr' => $pn,
					'sum_date' => '1',
					'modify_id' => '',
					'status_bill' => '0',
					'status_finish' => '0',
					'total_cost' => '0',
					'in_issuepaper'=> '0',
					'issue_user' =>'',
					'issue_note' =>'',
					'receive_user' => '',
					'phieutheodoi' => '1'
				);
		$objPrescription->insertDataFromArray($pharma_prescription_info);
		
		$objPrescription->usePrescription('prescription');
		for($i=1;$i<=$maxelements+1;$i++){
				$bdx='b'.$i; //ma thuoc
				$mdx='m'.$i; //ten thuoc
				$ddx='d'.$i; //ham luong
				$odx='o'.$i; //so luong
				$cdx='c'.$i; //Don gia
				$udx='u'.$i; //don vi
				$ldx='l'.$i; //so lo
				$tdx='t'.$i; //thoi gian
				$adx='a'.$i; //duong truyen
				$pdx='p'.$i; //toc do truyen
				$ndx='n'.$i; //ghi chu
				//$cdx='c'.$i; //chung voi cac thuoc khac?
				//$tmpTimes = explode("-",$$tdx);
				if($$mdx){
					$pharma_prescription = array(
							'nr' => $objPrescription->LastInsertPK('nr',$pk),
							'prescription_id' => $lastId,
							'product_encoder' => $$bdx,
							'product_name' => $$mdx,
							'lotid' => $$ldx,
							'sum_number' => $$odx, //30 (vien) = 1 hop
							'number_receive' => '',
							'number_of_unit' => '1', //ngay uong 3 lan
							'type_use' => $$adx,	//truyen tinh mach
							'desciption' => $$ddx, //uong 1 vien (/lan) ->  500ml
							'note' => $$udx, //(hop) 
							'cost' => $$cdx,
							'time_use' => $$tdx,
							'morenote'=> $$ndx,
							'speed' => $$pdx
						);
					$objPrescription->insertDataFromArray($pharma_prescription);
					$pharma_prescription=null;
				}
		}	
		$saved = true;		
		$objPrescription->updateCostPres($lastId);
	}
	if($saved){ 		//Sau khi luu xong, chuyen thanh tao toa thuoc moi
		header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&mode=&saved=1&pn=$pn&ward_nr=$ward_nr&dept_nr=$dept_nr");
		exit;
	}
			
}
// end of if(mode==save)

//Cap nhat lai toa thuoc cu voi prescriptionId
if($mode=='update' && $prescriptionId!=''){	
	$edited = false;	
	if(!isset($maxelements))
		$maxelements = $_POST['maxelements'];
	if($maxelements) {
		//prescription_info 
		$objPrescription->usePrescription('prescription_info');
		$datetime = date("Y-m-d G:i:s"); //$_POST['prescribe_date']
				
		$pharma_prescription_info = array(
					'prescription_type' => $prescription_type_nr,	
					'symptoms' => '',
					'diagnosis' => $diagnosis,
					'note' => $notes,
					'history' => 'Update by : '.$datetime.' : '.$prescriber,
					'doctor' => $prescriber,	
					'encounter_nr' => $pn,
					'sum_date' => '1',
					'modify_id' => $prescriber,
					'status_bill' => '0',
					'status_finish' => '0',
					'total_cost' => '0',
					'in_issuepaper'=> '0',
					'issue_user' =>'',
					'issue_note' =>'',
					'receive_user' => '',
					'phieutheodoi' => '1'
				);
		$objPrescription->where=' prescription_id='.$prescriptionId;						
		if($objPrescription->updateDataFromArray($pharma_prescription_info,$prescriptionId)) {	
			$objPrescription->usePrescription('prescription');
			$objPrescription->deleteAllMedicineInPres($prescriptionId);
			for($i=1;$i<=$maxelements+1;$i++){
					$bdx='b'.$i; //ma thuoc
					$mdx='m'.$i; //ten thuoc
					$ddx='d'.$i; //ham luong
					$odx='o'.$i; //so luong
					$cdx='c'.$i; //Don gia
					$udx='u'.$i; //don vi
					$ldx='l'.$i; //so lo
					$tdx='t'.$i; //thoi gian
					$adx='a'.$i; //duong truyen
					$pdx='p'.$i; //toc do truyen
					$ndx='n'.$i; //ghi chu
					if($$mdx){
						$pharma_prescription = array(
								'nr' => $objPrescription->LastInsertPK('nr',$pk),
								'prescription_id' => $prescriptionId,
								'product_encoder' => $$bdx,
								'product_name' => $$mdx,
								'lotid'=> $$ldx,
								'sum_number' => $$odx, //30 (vien) = 1 hop
								'number_receive' => '',
								'number_of_unit' => '1', //ngay uong 3 lan
								'type_use' => $$adx,  //truyen tinh mach 
								'desciption' => $$ddx, //uong 1 vien (/lan) -> 500ml
								'note' => $$udx, //(hop) 
								'cost' => $$cdx,
								'time_use' => $$tdx,
								'morenote'=> $$ndx,
								'speed' => $$pdx
							);
						$objPrescription->insertDataFromArray($pharma_prescription);

						$pharma_prescription=null;
					}
			}
		}		
		$edited = true;
		//echo $objPrescription->getLastQuery();
		$objPrescription->updateCostPres($prescriptionId);
	}
		
	if($edited) {		//Sau khi cap nhat xong, chuyen thanh tao toa thuoc moi
		header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&mode=&edited=1&pn=$pn&ward_nr=$ward_nr&dept_nr=$dept_nr");
		exit;
	}
}
// end of if(mode==update)

if(!isset($selecttab))
	$selecttab=0;
	
?>

<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo "$title &LDInputWin" ?></TITLE>
<?php
require($root_path.'include/core/inc_js_gethelp.php');
require($root_path.'include/core/inc_css_a_hilitebu.php');
?>

<script type="text/javascript" src="../../js/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous/src/effects.js"></script>
<script type="text/javascript" src="../../js/scriptaculous/src/controls.js"></script>
<script type="text/javascript" src="../../js/scriptaculous/src/builder.js"></script>
<script src="../../js/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">
 
	function resetinput(){
		//refresh current url
		window.location = "<?php echo $thisfile ?>?sid=<?php echo "$sid&lang=$lang&edit=$edit&pn=$pn&mode=&ward_nr=$ward_nr&selecttab=1&dept_nr=$dept_nr"; ?>";
	}
	
	function editPrescription(prescriptionId){
		var answer = "<?php echo $LDWantToEdit; ?>";
		if( confirm(answer) ) {
			window.location = "<?php echo $thisfile ?>?sid=<?php echo "$sid&lang=$lang&edit=$edit&pn=$pn&mode=edit&ward_nr=$ward_nr&selecttab=1&dept_nr=$dept_nr&prescriptionId="; ?>" + prescriptionId;
			//TabbedPanels1.showPanel(1);
		} else {
			return false;
		}
	}

    function sethilite(d) {
        d.focus();
        d.value = d.value + "*";
        d.focus();
   }

    function endhilite(d) {
        d.focus();
        d.value = d.value + "**";
        d.focus();
    }
    
	function pullRosebar(cb) {			//thay doi hinh anh, gia tri cua rosebar khi click
		var oldValue, newValue;
		oldValue = document.getElementById(cb.name).value;
		if(oldValue == 0) {
			cb.src = '<?php echo $root_path; ?>gui/img/common/default/qbar_2_rose.gif';
			newValue = 1;
		} else if (oldValue == 1) {
			cb.src = '<?php echo $root_path; ?>gui/img/common/default/qbar_0_rose.gif';
			newValue = 0;
		}
		document.getElementById(cb.name).value = newValue;
	}
		    	
	function countRoseBars(){			//tra ve nhung gia tri nao cua rosebar da duoc click
		var nr = 0;
		var str = new String;
		if(document.getElementById('rose_24').value == 1) {
			str = String('00');
		}
		for( nr = 1; nr < 24; nr++ ) {
			if(document.getElementById('rose_'+nr).value == 1) {
				tmp = String(nr);
				if(tmp.length == 1) {
					str = str + '-' + String('0' + tmp);
				} else {
					str = str + '-' + nr;
				}
			}
		}
		if(str.charAt(0) == '-')
			return(str.substr(1));
		else
			return(str);
	}
	
	function cleanRoseBars(){				//cho tat ca gia tri rosebar ve 0
		for( nr = 1; nr < 25; nr++ ) {
			document.getElementById('rose_'+nr).value = 0;
			document.getElementById('r_'+nr).src = '<?php echo $root_path; ?>gui/img/common/default/qbar_0_rose.gif';
		}
	}
	
	
//Them thuoc (add row)
function addPrescription(){
	if (document.getElementById('search').value==''){
		alert("<?php echo $LDSelectMedicament; ?>");
		return false;
	}
	/*else if(isNaN(infoform.pspeed.value)){
		alert("<?php echo $LDAlertInsertNumber; ?>");
		infoform.pspeed.focus();
		return false;
	}*/	
	notfound = false;
	var companionBNum = '';
	var prescriptionNr = document.getElementById('maxelements').value;
	
	//get the values
	elembName = document.getElementById('bestellnum').value;			//ma thuoc (hidden)
	elemmName = document.getElementById('search').value;				//ten thuoc
	elemdName = document.getElementById('dosage').value;				//Ham luong
	elemoName = document.getElementById('number').value;				//So luong
	elemcName = document.getElementById('cost').value;					//Don gia

	elemlName = document.getElementById('lotid').value;					//So lo
	elemtName = document.getElementById('time').value;					//Thoi gian
		
	var tmpatn = document.getElementById('application_type_nr');		//duong truyen
	var appTmp = tmpatn.options[tmpatn.selectedIndex].text;
	elemaName = tmpatn.value;
	
	var tmpunit = document.getElementById('unit');
	elemuName = tmpunit.options[tmpunit.selectedIndex].text;					//Don vi
	
	elempName = document.getElementById('pspeed').value;				//toc do truyen
	elemnName = document.getElementById('notesMed').value;				//ghi chu
	
	//check if i have it in the pharmacy
		prescriptionNr++;
		//create name & number
		var elemId = 'elem' + prescriptionNr;		//id of this row in table
		var elembNr = 'b' + prescriptionNr;			//ma thuoc (hidden)
		var elemmNr = 'm' + prescriptionNr;			//ten thuoc
		var elemdNr = 'd' + prescriptionNr;			//Ham luong
		var elemoNr = 'o' + prescriptionNr;			//So luong
		var elemcNr = 'c' + prescriptionNr;			//Don gia
		var elemlNr = 'l' + prescriptionNr;			//So lo
		var elemtNr = 't' + prescriptionNr;			//Thoi gian
		var elemuNr = 'u' + prescriptionNr;			//Don vi
		var elemaNr = 'a' + prescriptionNr;			//duong truyen
		var elempNr = 'p' + prescriptionNr;			//toc do truyen
		var elemnNr = 'n' + prescriptionNr;			//ghi chu
		//var elemcNr = 'c' + prescriptionNr;			
		
		//Create row, then insert
		var pres_table = document.getElementById('prescriptionTable');
		var trFirst = pres_table.tBodies[0].insertRow(-1);  
		trFirst.id = elemId;
		trFirst.innerHTML = '<tr bgcolor="#fefefe" valign="top"></tr>';  //trFirst = Builder.node('tr',{id:elemId,bgcolor:'#fefefe',valign:'top'});
		 
		var medicine,dosage,number,unit,lotid,timing,appType,speed,notesMed,elemRemove; //cell
		medicine = trFirst.insertCell(0);	//ma + ten
		dosage = trFirst.insertCell(1);		//ham luong
		number = trFirst.insertCell(2);		//so luong
		unit = trFirst.insertCell(3);		//don vi
		lotid = trFirst.insertCell(4);		//so lo
		speed = trFirst.insertCell(5);		//toc do truyen
		time = trFirst.insertCell(6);		//thoi gian bat dau, ket thuc
		appType = trFirst.insertCell(7);	//duong truyen
		notesMed = trFirst.insertCell(8);	//ghi chu
		elemRemove = trFirst.insertCell(9);	//nut xoa
			
			//encoder + name	
			medicine.innerHTML ='<td><input type="hidden" name="'+elembNr+'" value="'+elembName+'"><img src="../../gui/img/common/default/info3.gif" onclick="popinfo(\''+elembName+'\')"><input type="hidden" name="'+elemmNr+'" value="'+elemmName+'">'+elemmName+'</td>';
			//Ham luong
			dosage.innerHTML ='<td><input type="hidden" name="'+elemdNr+'" value="'+elemdName+'">'+elemdName+'</td>';
			//So luong, Don gia
			number.innerHTML ='<td><input type="hidden" name="'+elemoNr+'" value="'+elemoName+'">'+elemoName+'<input type="hidden" name="'+elemcNr+'" value="'+elemcName+'"></td>';
			//Don vi
			unit.innerHTML ='<td><input type="hidden" name="'+elemuNr+'" value="'+elemuName+'">'+elemuName+'</td>';
			//So lo
			lotid.innerHTML ='<td><input type="hidden" name="'+elemlNr+'" value="'+elemlName+'">'+elemlName+'</td>';
			//thoi gian
			time.innerHTML ='<td><input type="hidden" name="'+elemtNr+'" value="'+elemtName+'">'+elemtName+'</td>';		//+roseBars+
			//duong truyen
			appType.innerHTML ='<td><input type="hidden" name="'+elemaNr+'" value="'+appTmp+'">'+appTmp+'</td>';
			//speed
			speed.innerHTML ='<td><input type="hidden" name="'+elempNr+'" value="'+elempName+'">'+elempName+'</td>';
			//ghi chu
			notesMed.innerHTML ='<td><input type="hidden" name="'+elemnNr+'" value="'+elemnName+'">'+elemnName+'</td>';
			//elemRemove
			elemRemove.innerHTML ='<td valign="middle" ><img src="../../gui/img/common/default/delete2.gif" style="cursor:pointer;" onclick="removeMedicament(\''+elemId+'\')" ></td>';
			

		trFirst.appendChild(medicine);
		trFirst.appendChild(dosage);
		trFirst.appendChild(number);
		trFirst.appendChild(unit);
		trFirst.appendChild(lotid);
		trFirst.appendChild(speed);
		trFirst.appendChild(time);
		trFirst.appendChild(appType);
		trFirst.appendChild(notesMed);
		trFirst.appendChild(elemRemove);

	  	pres_table.appendChild(trFirst);

		
		//clean up to add new medicine

			document.getElementById('bestellnum').value = '';
			document.getElementById('search').value = '';
			document.getElementById('dosage').value = '';
			document.getElementById('lotid').value = '';
			document.getElementById('pspeed').value = '';
			document.getElementById('time').value = '';
			//document.getElementById('application_type_nr').value = '';
			document.getElementById('notesMed').value = '';
			//cleanRoseBars();
	  	
		//update the maxelements
		document.getElementById('maxelements').value = prescriptionNr;		
		
}
	
function removeMedicament(id) {
	//var pres_table = document.getElementById('prescriptionTable');
	var row = document.getElementById(id);
	//pres_table.removeChild(row);
	row.parentNode.removeChild(row);

}
	
function popinfo(b) {
	urlholder="../products/products-bestellkatalog-popinfo.php?sid=7071bab054d376600a2ecf70ac6128a5&lang=sq&keyword="+b+"&mode=search&cat=pharma";
	ordercatwin=window.open(urlholder,"ordercat","width=850,height=550,menubar=no,resizable=yes,scrollbars=yes");
}

	
function printPrescription(enc) {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/prescription/truyendich.php<?php echo URL_REDIRECT_APPEND; ?>&enc="+enc;
	window.open(urlholder,'PhieuTheoDoiTruyenDich',"width=1000,height=800,menubar=no,resizable=yes,scrollbars=yes");
}
	
function submitMainForm() {
	var nr = document.getElementById('maxelements').value;
	if(nr<1) {
		alert('<?php echo $LDPlsAddMedicine; ?>');
		return false;
	} 
	document.infoform.action="<?php echo $thisfile ?>"
	document.infoform.submit();
}
</script>

<link href="../../js/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<STYLE type="text/css">
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
.v12 { font-family:verdana,arial;font-size:12; }
.v13 { font-family:verdana,arial;font-size:13; }
.v10 { font-family:verdana,arial;font-size:10; }

#search{
	padding: 3px;
	width: 270px;
	border: 1px solid #999;
	font-family: verdana;
 	arial, sans-serif;
	font-size: 12px;
	background: #ffc;
}
#notes{
	padding: 3px;
	border: 1px solid #999;
	font-family: verdana;
 	arial, sans-serif;
	font-size: 12px;
}
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
	color: red;
}
li.selected {
	background: #FCC;
}
.nav:hover {
	background:#FFFF99;
}
.together { border-left:thick solid #0000FF; }
</style>
</HEAD>
<BODY  bgcolor="#99ccff" TEXT="#000000" LINK="#0000FF" VLINK="#800080"    topmargin="0" marginheight="0" if (window.focus) window.focus(); window.focus();" >
<table border=0 width="100%">
  <tr>
    <td><b><font face="verdana,arial" size="5" color="maroon">
<?php
	echo $title.'<br><font size=4>';
?>
	</font></b>
	</td>
    <td align="right" valign="top"><a href="javascript:gethelp('nursing_feverchart_xp.php','<?php echo $winid ?>','','','<?php echo $title ?>')"><img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?> </a>
    <a href="<?php echo "nursing-station-patientdaten.php".URL_APPEND."&station=$station&pn=$pn&edit=$edit&ward_nr=$ward_nr&dept_nr=$dept_nr" ?>" ><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> </a></nobr>
	</td>
  </tr>
  <tr><td><font size="3"><b><?php echo $LDDept.': '.$deptname;?></b></font><p><b><?php echo $LDStation.': '.$wardname;?></b></td>
  <td><?php echo $LDEncounterID.': '.$pn;?><br>
	<?php echo $LDName.': '.$enc_obj->encounter['name_last'].' '.$enc_obj->encounter['name_first'];?><br>
	<?php echo $LDBirthDate.': '.formatDate2Local($enc_obj->encounter['date_birth'],$date_format);?>
	</td></tr>
</table>
<form name="infoform" id="infoform"  method="post" >
<div id="TabbedPanels1" class="TabbedPanels" >
  <ul class="TabbedPanelsTabGroup">
    <ul class="TabbedPanelsTab" tabindex="0"><font face=verdana,arial size=2 color=maroon><?php echo $LDOldPrescriptions; ?></font></ul>
    <?php if(!$isDischarged) {?>
    <ul class="TabbedPanelsTab" tabindex="0"><font face=verdana,arial size=2 color=maroon><?php echo $LDNewPrescription; ?></font></ul>
    <?php } ?>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
	
	
															<!--tab old prescription -->
<table border=0 width=100%  cellspacing=0 cellpadding=0 >
  <tr>
    <td>
	
<?php
  
//$medis=$objPrescription->getAllPresOfEncounter($pn,$dept_nr,$ward_nr,'1');
$medis=$objPrescription->getAllPresOfEncounter_1($pn,'1');
if(is_object($medis)){
	$count=$medis->RecordCount();
}
	
if($count){
	$tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/tableHeaderbg3.gif"';

    // Load the editor functions
	include_once($root_path.'modules/news/includes/inc_editor_fx.php');
	$toggle=0;
	$old_nr = 0;
	$i = 0;
	//$count=0;


	$companionBestellnum ='';
	
	$row=$medis->FetchRow();
	$old_nr = $row['prescription_id'];
	$rowNr = $row['nr'];
	$medis->Move($i);
	do {
			//$companionBestellnum =  explode(",",unserialize($row['companion']));
			//echo "<br><span style=\"cursor:pointer;font-weight:bold;float: left;\" onclick=\"new Effect.toggle('_". $row['prescription_id']  ."_', 'blind' );\" /><font face=verdana,arial size=2 color=maroon>".$LDPreId." : " .$row['prescription_id']. " - ".$LDDate.": " .formatDate2Local($row['date_time_create'],'dd/mm/yyyy')."</font></span>";
			$ward_nr = $row['ward_nr'];
        $dept_nr = $row['dept_nr'];
        if ($ward_nr!='' && $ward_nr!='0'){
            if($wardinfo = $Ward->getWardInfo($ward_nr)) {
                $wardname = $wardinfo['name'];
                $deptname = ($$wardinfo['LD_var']);
                $dept_nr = $wardinfo['dept_nr'];
                echo "<br><span style=\"cursor:pointer;font-weight:bold;float: left;\" onclick=\"new Effect.toggle('_". $row['prescription_id']  ."_', 'blind' );\" /><font face=verdana,arial size=2 color=maroon>".$LDPreId." : " .$row['prescription_id']. " - ".$LDDate." : " .formatDate2Local($row['date_time_create'],'dd/mm/yyyy')." - ".$LDDept.' : '.$deptname." - ".$LDStation.' : '.$wardname."</font></span>";
            }
        } elseif ($dept_nr!='' && $dept_nr!='0'){
            if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
                $deptname = ($$deptinfo['LD_var']);
                $wardname = $LDAllWard;
                echo "<br><span style=\"cursor:pointer;font-weight:bold;float: left;\" onclick=\"new Effect.toggle('_". $row['prescription_id']  ."_', 'blind' );\" /><font face=verdana,arial size=2 color=maroon>".$LDPreId." : " .$row['prescription_id']. " - ".$LDDate.": " .formatDate2Local($row['date_time_create'],'dd/mm/yyyy')."-".$LDDept.': '.$deptname."-".$LDStation.': '.$wardname."</font></span>";
            }
	}		
			if(!$isDischarged && !$row['status_bill'] && !$row['status_finish']) {		//neu chua xuat vien, thanh toan, nhan thuoc
				echo '<span style="float:right;cursor:pointer;"><img onClick="editPrescription('.$row['prescription_id'].');" '.createLDImgSrc($root_path,'redo.gif','0').' title="'.$LDEdit.'">&nbsp;';
			} else {
				echo '<span style="float:right;cursor:pointer;"><img '.createComIcon($root_path,'check-r.gif','0').'>';
			}
			
			echo '</span><br><br>';
			echo '<div id="_' . $row['prescription_id'] .'_" style="display:none;">';
			echo '<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">';
			echo '<tr>
					<td><strong>'.$LDMedFormDose.'</strong></td>
					<td><strong>'.$LDDose.'</strong></td>
					<td><strong>'.$LDNumber.'</strong></td>
					<td><strong>'.$LDUnit.'</strong></td>
					<td><strong>'.$LDLotid.'</strong></td>
					<td><strong>'.$LDSpeed.'</strong></td>
					<td><strong>'.$LDTime.'</strong></td>
					<td><strong>'.$LDRouting.'</strong></td>
					<td><strong>'.$LDNotes.'</strong></td>
				</tr>';
			
			do { 
				if($toggle) $bgc='#ffffff';
					else $bgc='#eeeeee';
				$toggle=!$toggle;

				?>
					  <tr bgcolor="<?php echo $bgc; ?>" valign="top">
					    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['product_name']; //Ten thuoc ?> </font></td>
					    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['desciption']; //Ham luong ?></font></td>
						<td><FONT SIZE=-1  FACE="Arial"><?php echo $row['sum_number']; //So luong ?></font></td>
						<td><FONT SIZE=-1  FACE="Arial"><?php echo $row['note']; //Don vi ?></font></td>
						<td><FONT SIZE=-1  FACE="Arial"><?php echo $row['lotid']; //So lo ?></font></td>
					    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['speed']; //Toc do truyen ?></font></td>
					    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['time_use']; //Thoi gian ?></font></td>
					    <td><FONT SIZE=-1  FACE="Arial"><?php echo  $row['type_use']; //Duong truyen ?> </font></td>
					    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['morenote']; //Ghi chu ?></font></td>
					  </tr>
				<?php
					$i++;
					$medis->Move($i);
					$row=$medis->fields;
			} while ( $row['prescription_id'] == $old_nr && ( $i < $count) );

			$medis->Move($i-1) ;
			$row=$medis->fields;
			echo'<tr bgcolor="#DCDCDC">';
			echo '	<td colspan="9"> ' .$LDNotes. ' : ' . hilite($row['totalnote']);
			echo '	</td>
			    </tr>';
			echo '</table></div>';
			$medis->Move($i) ;
			//end note for the prescription
			
		$row=$medis->fields;
		$old_nr = $row['prescription_id'];
		$rowNr = $row['nr'];
		
	}while($medis->Move($i-1)  && ( $i < $count )  );
}
?>
</td>
</tr>
<tr>
<td align="right"><br><img onclick="printPrescription('<?php echo $row['encounter_nr']; ?>')" <?php echo createLDImgSrc($root_path,'printer.png','0'); ?>  title="<?php echo $LDPrint; ?>" > </td>
</tr>
 </table>
 <!-- end : old prescription -->
</div>





															<!--tab new prescription -->
<?php if(!$isDischarged) { ?>
<div class="TabbedPanelsContent" >
<table>
  <tr>
    <td><?php echo $LDMedFormDose; ?>: </td>
    <td>&nbsp;</td>
    <td><?php echo $LDDose; ?>: </td>
	<td><?php echo $LDNumber; ?>: </td>
	<td><?php echo $LDUnit; ?>: </td>
	<td><?php echo $LDLotid; ?>: </td>  
    <td><?php echo $LDSpeed; ?>: </td>
	<td><?php echo $LDTime2; ?>: </td>
	<td><?php echo $LDRouting; ?>: </td>
    <td><?php echo $LDNotes; ?>: </td>
    <td rowspan="4"> </td>
  </tr>
    <script type="text/javascript">
		function AutoComplete(){
			var maxelements = document.getElementById('maxelements').value;
			var tmpBestellNum='';
			for(var i=0;i<=maxelements;i++){	
				if(document.getElementsByName('b'+i)[0])
					tmpBestellNum = tmpBestellNum +'_'+document.getElementsByName('b'+i)[0].value;
			}
			var includeScript = "include/inc_search_medicaments.php?mode=auto&tmpBestellNum="+tmpBestellNum;
			new Ajax.Autocompleter("search","hint",includeScript, {
					method: 'post',
					paramName: 'search',
					afterUpdateElement : setSelectionId
				}
			);
		}
		
		function setSelectionId(div,li) {
			document.getElementById('bestellnum').value = li.id
			var text=div.value; //li.id;
			var a=text.split('-- ');
			document.getElementById('search').value = a[0];
			document.getElementById('dosage').value = a[2];
			document.getElementById('lotid').value = a[5];
		}

	</script>
  <tr>
    <td>
        <input type="text" id="search" name="search" onFocus="AutoComplete()" >
        <input type="hidden" id="bestellnum" value="" >
		<div id="hint"></div>

    </td>
    <td>&nbsp;</td>
	<td><input type="text" id="dosage" name="dosage" size="7"></td>
	<td><input type="text" id="number" name="number" size="3" value="1">
		<input type="hidden" id="cost" name="cost" value="0" >
		</td>
	<td>
		<select name="unit" id="unit">
        <option value=""></option>
        <?php
			reset($unit_types);
			while(list($x,$v)=each($unit_types)){
				if($v['unit_of_medicine']=='Chai')
					echo '<option value="'.$v['unit_of_medicine'].'" selected>';	//Chai
				else echo '<option value="'.$v['unit_of_medicine'].'">';
				
				echo $v['unit_name_of_medicine'];					
				echo '</option>';
			}
		?>
		</select>
	</td>
	<td><input type="text" id="lotid" name="lotid" size="10"></td>
	<td><input type="text" id="pspeed" name="pspeed" size="7"></td>
	<td><input type="text" id="time" name="time" size="10"></td>
    <td>
	  <select name="application_type_nr" id="application_type_nr">
        <option value=""></option>
        <?php
			reset($app_types);
			while(list($x,$v)=each($app_types)){
				if($v['type']=='iv')
					echo '<option value="'.$v['nr'].'" selected>';	//Tiem tinh mach
				else echo '<option value="'.$v['nr'].'">';
				
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
					
				echo '</option>';
			}
		?>
      </select>

    <td><input type="text" size="15" id="notesMed" name="notesMed">
    </td>
  </tr>
  <tr>
    <td colspan="6"></td>
    <td>&nbsp;</td>
  </tr>
</table>

<?php echo '<a href="javascript:addPrescription();" ><img style="cursor:pointer;" '. createLDImgSrc($root_path,'add.png','0') . ' title="'.  $LDAdd.'"> '.$LDAdd1.'</a>'; ?>  
<br>
<br />
<table id="prescriptionTable" width="100%">
<tr>
	<td><strong><?php echo $LDMedFormDose; ?></strong></td>
	<td><strong><?php echo $LDDose; ?></strong></td>
	<td><strong><?php echo $LDNumber; ?></strong></td>
	<td><strong><?php echo $LDUnit; ?></strong></td>
	<td><strong><?php echo $LDLotid; ?></strong></td>
	<td><strong><?php echo $LDSpeed; ?></strong></td>
	<td><strong><?php echo $LDTime; ?></strong></td>
	<td><strong><?php echo $LDRouting; ?></strong></td>
	<td><strong><?php echo $LDNotes; ?></strong></td>
	<td>&nbsp;</td>
</tr>
<?php 
if($mode==''){
	$init_maxelements=0;
	$mode='save';
}
if($mode=='edit' && $prescriptionId!=''){
	$old_pres = $objPrescription->getDetailPrescriptionInfo($prescriptionId);
	if(is_object($old_pres)){
		//echo '<tr><td>check!</td></tr>';
		$init_maxelements = $old_pres->RecordCount();
		for($i=1; $i<=$init_maxelements; $i++){
			$old_pres_row = $old_pres->FetchRow();
			echo '<tr id="elem'.$i.'">';
				//encoder + name
				echo 	'<td><input type="hidden" name="b'.$i.'" value="'.$old_pres_row['product_encoder'].'"><img src="../../gui/img/common/default/info3.gif" onclick="popinfo(\''.$old_pres_row['product_encoder'].'\')"><input type="hidden" name="m'.$i.'" value="'.$old_pres_row['product_name'].'">'.$old_pres_row['product_name'].'</td>';
				//Ham luong
				echo 	'<td><input type="hidden" name="d'.$i.'" value="'.$old_pres_row['desciption'].'">'.$old_pres_row['desciption'].'</td>';
				//So luong, Don gia
				echo 	'<td><input type="hidden" name="o'.$i.'" value="'.$old_pres_row['sum_number'].'">'.$old_pres_row['sum_number'].'<input type="hidden" name="c'.$i.'" value="'.$old_pres_row['cost'].'"></td>';
				//Don vi
				echo 	'<td><input type="hidden" name="u'.$i.'" value="'.$old_pres_row['note'].'">'.$old_pres_row['note'].'</td>';
				//So lo
				echo 	'<td><input type="hidden" name="l'.$i.'" value="'.$old_pres_row['lotid'].'">'.$old_pres_row['lotid'].'</td>';
				//speed
				echo 	'<td><input type="hidden" name="p'.$i.'" value="'.$old_pres_row['speed'].'">'.$old_pres_row['speed'].'</td>';
				//thoi gian
				echo 	'<td><input type="hidden" name="t'.$i.'" value="'.$old_pres_row['time_use'].'">'.$old_pres_row['time_use'].'</td>';		
				//duong truyen
				echo 	'<td><input type="hidden" name="a'.$i.'" value="'.$old_pres_row['type_use'].'">'.$old_pres_row['type_use'].'</td>';
				//ghi chu
				echo 	'<td><input type="hidden" name="n'.$i.'" value="'.$old_pres_row['morenote'].'">'.$old_pres_row['morenote'].'</td>';
				//elemRemove
				echo 	'<td valign="middle" ><img src="../../gui/img/common/default/delete2.gif" style="cursor:pointer;" onclick="removeMedicament(\'elem'.$i.'\')" ></td>';		
			echo '</tr>';
		}
		$generalnote=$old_pres_row['generalnote']; 
		$mode='update';
	}
}
?>
</table>
<table>
	<tr valign="top">
		<td valign="top"><label><?php echo $LDNotes; ?> : </label></td>
		<td>
		<textarea name="notes" id="notes" cols="40" rows="3"><?php echo $generalnote; ?></textarea>
		<br>&nbsp;
		<a href = "javascript: sethilite(document.infoform.notes)"><img <?php echo createComIcon($root_path, 'hilite-s.gif', '0') ?>></a>
		<a href = "javascript: endhilite(document.infoform.notes)"><img <?php echo createComIcon($root_path, 'hilite-e.gif', '0') ?>></a>
		</td>
		<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDTypePres.': '; ?>&nbsp;
			<select name="prescription_type_nr">
			<?php
			if(is_object($pres_types)){
				$j=0;
				while($rowtype=$pres_types->FetchRow())
				{
					if($rowtype['group_pres']=='1'){		//Noi tru
						if ($mode=='save' && $j==0)
							$styleselect='SELECTED';
						elseif ($mode=='update' && $rowtype['prescription_type']==$old_pres_row['prescription_type'])
							$styleselect='SELECTED';
						else
							$styleselect=' ';
							
						echo '<option value="'.$rowtype['prescription_type'].'" '.$styleselect.'>';
						echo $rowtype['prescription_type_name'];
						echo '</option>';
						$j++;
					}				
				}
			}
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="3">
		<br>&nbsp;<br>
		<a href="javascript:submitMainForm();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> alt="<?php echo $LDSave ?>"></a>&nbsp;&nbsp;
		<a href="javascript:resetinput()"><img <?php echo createLDImgSrc($root_path,'reset.gif','0') ?> alt="<?php echo $LDReset ?>"></a>&nbsp;&nbsp;
	</td></tr>
</table>
  </div>
  <?php } ?>
<!-- end : new prescription -->
</div>
</div>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="pn" value="<?php echo $pn ?>">
<input type="hidden" name="prescriptionId" value="<?php echo $prescriptionId; ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
<input type="hidden" name="mode" value="<?php echo $mode; ?>">
<input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
<input type="hidden" name="maxelements" id="maxelements" value="<?php echo $init_maxelements; ?>">
<!-- no comment...please -->




<p>

<?php if($saved || $edited)  : ?>
<a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClose ?>"></a>
<?php else : ?>

<p><a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> alt="<?php echo $LDClose ?>"></a>
  <?php endif ; ?>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", { defaultTab: <?php echo $selecttab; ?> });

//-->
</script>
</form>
</BODY>
</HTML>