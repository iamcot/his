<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
/**
* eComBill 1.0.04 for Care2002 beta 1.0.04 
* (2003-04-30)
* adapted from eComBill beta 0.2 
* developed by ecomscience.com http://www.ecomscience.com 
* GPL License
*
* 19.Oct.2003 Daniel Hinostroza: Switch language implemented, but... What is the translation of outstanding?
*/
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_CHAIN',1);

define('LANG_FILE','billing.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_prescription.php');
require_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
require_once($root_path.'classes/money/convertMoney.php');
$eComBill = new eComBill;
$Encounter = new Encounter;
if(!isset($Pres)) $Pres = new Prescription;
if(!isset($PresMed)) $PresMed = new PrescriptionMedipot;

$Encounter->loadEncounterData($patientno);
//----nang-------
$patqry="SELECT e.*,p.* FROM care_encounter AS e, care_person AS p WHERE e.encounter_nr=$patientno AND e.pid=p.pid";
$resultpatqry=$db->Execute($patqry);
if(is_object($resultpatqry)) $patient=$resultpatqry->FetchRow();
else $patient=array();
$mh = $patient['muchuong'];
// xem dạng điều trị
$in_out = $patient['encounter_class_nr'];//noi tru hay ngoai tru
if($in_out==1) $in_out_patient= 'Nội trú';
else $in_out_patient='Ngoại trú';
//echo $mh;
$breakfile='patient_bill_links.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
$returnfile='patient_bill_links.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;

//$db->debug=true;

$ergebnis = $eComBill->listAllBills();
if(is_object($ergebnis)) $cntergebnis=$ergebnis->RecordCount();

$ybb=1;

//check for empty set
if($cntergebnis) {
	$result=$ergebnis->FetchRow();
	$bill_no=$result['bill_bill_no'];
	// add one to bill number for new bill
	$bill_no = $bill_no + 1;
} else {
	//generate new bill number
	$bill_no="00000001";
}

if($bill_no==100000000) $bill_no="00000001";
// limit to 9 digit, reset variables
$bill_no = str_pad($bill_no, 8,0,STR_PAD_LEFT);
//exit();
$billno=$bill_no;
$presdatetime=date("Y-m-d H:i:s");

$resultlabquery = $eComBill->listBillsByEncounter($patientno);
$cntLT=0;
// How many services of HS & LT
if(is_object($resultlabquery)){
	$itemcnt=$resultlabquery->RecordCount();
	while($labresult=$resultlabquery->FetchRow()){
		$resultlbqry=$eComBill->listServiceItemsByCode($labresult['bill_item_code']);
		if(is_object($resultlbqry)){
			$buffer=$resultlbqry->FetchRow();
			if($buffer['item_type']=="LT") {
				$cntLT=$cntLT+1;
			} if($buffer['item_type']=="HS") {
				$cntHS=$cntHS+1;
			}
		}
	}
}

if($cntLT>$cntHS)
	$itemcnt=$cntLT;
if($cntLT<=$cntHS)
	$itemcnt=$cntHS;

$itemcnt1=$cntLT+$cntHS;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDBilling . ' - ' . $BillList);

 # href for the return button
 $smarty->assign('pbBack',$returnfile);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('billing.php','payments')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling . ' - ' . $LDBill);




$smarty->assign('FormTitle',$LDPatientNumber . ' - ' . $full_en);

$smarty->assign('sFormTag','<form name="patientbill" method="POST">');

$smarty->assign('LDGeneralInfo',$LDGeneralInfo);
$smarty->assign('LDPatientName',$LDPatientName);
//$smarty->assign('LDPatientNameData',$Encounter->encounter['title'] . ' - ' . $Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first']);
$smarty->assign('LDPatientNameData',$Encounter->encounter['title']. $Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first']);     //====>nang
$smarty->assign('LDReceiptNumber',$LDBillNo);
$smarty->assign('LDReceiptNumber',$LDBillNo);
if($billid == "currentbill") { 
	$receiptid =  $billno; 
	$smarty->assign('LDReceiptNumberData',$LDChuaThanhToan);
} 
else { 
	$receiptid = $billid; 
	$smarty->assign('LDReceiptNumberData',$receiptid);
}
$smarty->assign('LDPatientAddress',$LDPatientAddress);
//$smarty->assign('LDPatientAddressData',$Encounter->encounter['addr_str'].' '.$Encounter->encounter['addr_str_nr'].'<br>'.$Encounter->encounter['addr_zip'].' '.$Encounter->encounter['addr_citytown_nr']);
$smarty->assign('LDPatientAddressData',$Encounter->encounter['phuongxa_name'].' '.$Encounter->encounter['quanhuyen_name'].'<br>'.$Encounter->encounter['citytown_name']);

$smarty->assign('LDPaymentDate', $LDBillDate);
 //xét là hóa đơn hiện tại hay đã thanh toán rồi
if($billid == "currentbill") {
	$billDate = formatDate2Local($presdatetime,$date_format,1);
	
} else {
	$oldbillqueryresult = $eComBill->getInfoBillByBillId($billid);
	if(is_object($oldbillqueryresult)){
		$buffer = $oldbillqueryresult->FetchRow();
		$oldbilldate = $buffer['bill_date_time'];
		$billDate = formatDate2Local($oldbilldate,$date_format,1);
		
		$oldbilltotal=$buffer['bill_amount'];
		$oldbilloutstanding=$buffer['bill_outstanding'];
		$discount = $buffer['bill_discount'];
       // $discount = $mh*
		$totaldue = $oldbilltotal-$oldbilloutstanding;
	}
	$smarty->assign('discount', number_format($discount));
}

$smarty->assign('LDPaymentDateData', $billDate);
$smarty->assign('LDPatientType', $LDPatientType );
//$smarty->assign('LDPatientTypeData', $Encounter->encounter['encounter_class_nr']);
$smarty->assign('LDPatientTypeData', $in_out_patient);//==>nang
$smarty->assign('LDDateofBirth', $LDDateofBirth );
$smarty->assign('LDDateofBirthData', formatDate2Local($Encounter->encounter['date_birth'],$date_format) );
$smarty->assign('LDSex', $LDSex );
if($Encounter->encounter['sex']=='f'){
	$sex=$LDFemale;
}else{
	$sex=$LDMale;
}
$smarty->assign('LDSexData', $sex);
$smarty->assign('LDPatientNumber', $LDPatientNumber);
$smarty->assign('LDPatientNumberData', $full_en);
$smarty->assign('LDDateofAdmission', $LDDateofAdmission);
$smarty->assign('LDDateofAdmissionData', formatDate2Local($Encounter->encounter['encounter_date'],$date_format));
$smarty->assign('LDPaymentInformation', $LDBillingInformation);
$smarty->assign('LDInsurance', $LDInsurance);
$smarty->assign('LDInsurance_start', $LDInsurance_start);
$smarty->assign('LDInsurance_exp', $LDInsurance_exp);
$smarty->assign('LDMaKCB', $LDMaKCB);
require_once($root_path.'include/care_api_classes/class_person.php');
$Person = new Person();
if($re_person = $Person->getInfoInsurEnc($Encounter->encounter['pid'])){
    $insurance_nr = $re_person['insurance_nr'];
    if($insurance_nr){
        $insurance_start = $re_person['insurance_start'];
        $insurance_exp = $re_person['insurance_exp'];
        $smarty->assign('Insurance_start', formatDate2Local($insurance_start,$date_format));
        $smarty->assign('Insurance_exp', formatDate2Local($insurance_exp,$date_format));
    }
    else
    {$smarty->assign('LDInsurance_start', $LDInsurance_start);
        $smarty->assign('LDInsurance_exp', $LDInsurance_exp);}
    $madk_kcbbd = $re_person['madkbd'];
    $is_traituyen = $re_person['is_traituyen'];
}else{
    $insurance_nr = $Encounter->encounter['insurance_nr'];
    if($insurance_nr){
        $insurance_start = $Encounter->encounter['insurance_start'];
        $insurance_exp = $Encounter->encounter['insurance_exp'];
        $smarty->assign('Insurance_start', formatDate2Local($insurance_start,$date_format));
        $smarty->assign('Insurance_exp', formatDate2Local($insurance_exp,$date_format));
    }
    else
    {$smarty->assign('LDInsurance_start', $LDInsurance_start);
        $smarty->assign('LDInsurance_exp', $LDInsurance_exp);}
    $madk_kcbbd = $Encounter->encounter['madk_kcbbd'];
    $is_traituyen = $Encounter->encounter['is_traituyen'];

}
$smarty->assign('Insurance', $insurance_nr);
//$smarty->assign('Insurance_start', formatDate2Local($insurance_start,$date_format));
//$smarty->assign('Insurance_exp', formatDate2Local($insurance_exp,$date_format));
$smarty->assign('makcb', $madk_kcbbd);

$smarty->assign('LDBillList', TRUE);

$smarty->assign('LDDescription', $LDDescription);
$smarty->assign('LDCostPerUnit', $LDCostPerUnit);
$smarty->assign('LDUnits', $LDUnits);
$smarty->assign('LDTotalCost', $LDTotalCost);
$smarty->assign('LDItemDate', $LDItemDate);
$smarty->assign('LDItemType', $LDItemType);
$smarty->assign('LDItemCheck', $LDItemCheck);


$sListRows='';
$outstanding=0;
$totaldue =0;
$count=0;

//if($target=='nursing')
	$readonly=' disabled="disabled" ';
//else $readonly='';	

//current bill listing	----------------------------------------------------------------------------
//hiện thông tin hóa đơn
$kgiam =0;
if($billid == "currentbill") {
	$total_for_insur=0;
	$BHtungay_x = strtotime($insurance_start);
	$BHdenngay_x = strtotime($insurance_exp);
	
	if ($resultlabquery)
	{
		$resultlabquery->MoveFirst();
		$HStotal=0;$LTtotal=0;
		$group_id =0;
		
		
		//FOR SERVICE
		for($i=0;$i<$itemcnt1;$i++) {
			//labres: row in billing_bill_item table (cac service cua benh nhan)
			//lb1: row in billing_item table (danh muc service)
			$labres = $resultlabquery->FetchRow();
			$resultlbqry1=$eComBill->listServiceItemsByCode($labres['bill_item_code']);
			if(is_object($resultlbqry1)) $lb1=$resultlbqry1->FetchRow();
			$nounits=$labres['bill_item_units'];
			$cpu=$labres['bill_item_unit_cost'];

            //Tính tiền Oxy
            $temp_cost= $cpu*$nounits;
            switch($lb1['item_code'])
            {
                case 'HSCC01':
                    $totcost=$temp_cost*1;
                    break;
                case 'HSCC02':
                    $totcost=$temp_cost*2;
                    break;
                case 'HSCC03':
                    $totcost=$temp_cost*3;
                    break;
                case 'HSCC04':
                    $totcost=$temp_cost*4;
                    break;
                case 'HSCC05':
                    $totcost=$temp_cost*5;
                    break;
                default:
                    $totcost=$cpu*$nounits;
            }

			$type=$lb1['item_type'];
			$itemdate_x = strtotime($labres['bill_item_date']);	//xet bao hiem
			if(($itemdate_x>=$BHtungay_x) && ($itemdate_x<=$BHdenngay_x))
				$total_for_insur += ($labres['bill_item_unit_cost'])*($labres['bill_item_units']);

			
			//info of service
			$smarty->assign('DescriptionData', '+ '.$lb1['item_description']);
			$smarty->assign('CostPerUnitData', number_format($labres['bill_item_unit_cost']));
			$smarty->assign('UnitsData', $labres['bill_item_units']);
			$smarty->assign('TotalCostData', number_format($totcost));
			
			//typename
			if($type=="HS") {
				$smarty->assign('ItemTypeData', $LDMedicalServices);
			} else if($type=="LT") { 
				$smarty->assign('ItemTypeData', $LDLaboratoryTests); 
			}
			//itemdate
			$smarty->assign('ItemDateData', formatDate2Local($labres['bill_item_date'],$date_format));
			
			//total cost of bill = HStotal + LTtotal
			if($lb1['item_type']=="HS") {
                switch($lb1['item_code'])
                {
                    case 'HSCC01':
                        $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*1;
                        break;
                    case 'HSCC02':
                        $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*2;
                        break;
                    case 'HSCC03':
                        $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*3;
                        break;
                    case 'HSCC04':
                        $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*4;
                        break;
                    case 'HSCC05':
                        $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*5;
                        break;
                    default:
                        $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units']);
                }
               // $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units']);
            }
			if($lb1['item_type']=="LT") { $LTtotal=$LTtotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units']); }
			
			//Check thanh toan
			$smarty->assign('ItemCheck', '<input type="checkbox" name="cbx'.$count.'" checked onclick="ChangeSum(this,'.$totcost.')" '.$readonly.'> <input type="hidden" name="item'.$count.'" value="billitem_id_'.$labres['bill_item_id'].'">');
			$count++;
            // trừ cho hồ sơ và chuyển viện và xét nghiệm HbsAg và xét nghiệm serodia
            if( $lb1['item_group_nr']==41) {
                $kgiam = $kgiam + ($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*$mh;
            //    echo $kgiam;
            }
            if($lb1['item_code']=='XNK07' || $lb1['item_code']=='XNK02' || $lb1['item_code']=='0407'){
            $kgiam = $kgiam + ($labres['bill_item_unit_cost'])*($labres['bill_item_units'])*$mh;
            //echo $kgiam;
            }

			//groupname
			$flag_g = false;
			if ($group_id!=$lb1['item_group_nr'])
			{			
				$smarty->assign('GroupName',$lb1['group_name']);			
				$group_id = $lb1['item_group_nr'];
				$flag_g =true;
			}
			$smarty->assign('flag_g', $flag_g); 
			
			
			ob_start();
			$smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
			$sListRows = $sListRows.ob_get_contents();
			ob_end_clean(); 		

		}
	}
	//FOR PRESCRIPTION
    //2014-03-25: CoT, khong can phai tinh lai gia hien tai vi BHYT da chinh sua trong toa
//	$presresult = $Pres->getAllPresOfEncounterByBillId($patientno,'0');		//list cac toa chua thanh toan
//	if(is_object($presresult)) {	//update currrent medicine cost, total cost of this pres
//		for($i=0;$i<$presresult->RecordCount();$i++) {
//			$pres = $presresult->FetchRow();
//			$Pres->updateCostPres($pres['prescription_id']);
//		}
//	}
    //hiện các toa thuốc chưa thanh toán, kiểm tra xem bệnh nhân là nội trú hay ngoại trú
    //nếu nội trú thì hiện thuốc theo cấp phát     =>n
   // $tongthuoc=0;
    if($in_out == 1){
        $pres_noitru = "SELECT iss.*, sum(iss.number) AS sum, prs.product_name, prs.note AS unit, prs.cost
					FROM care_pharma_prescription_issue AS iss, care_pharma_prescription AS prs
					WHERE iss.enc_nr='".$patientno."' AND prs.prescription_id=iss.pres_id
					AND prs.product_encoder=iss.product_encoder
					AND iss.status_bill='0'
					GROUP BY iss.product_encoder, iss.date_issue
					ORDER BY iss.product_encoder, iss.date_issue";
        $stt=1;
        $Pres_total=0;
        $list_item = array();
        $list_date = array();
        $k=0;
        $list_name = array();
        $list_info = array();
        if($pres_item_noitru=$db->Execute($pres_noitru)){
            for($i=0;$i<$pres_item_noitru->RecordCount();$i++){
                $item = $pres_item_noitru->FetchRow();
                $list_item[$item['product_encoder']][$item['date_issue']]= $item['sum'];   //==>n đổi $item['number'] thành  $item['sum']
                if(!in_array($item['date_issue'], $list_date)){
                    $k++;
                    $list_date[$k]=$item['date_issue'];
                }
                if(!in_array($item['product_encoder'],$list_name)){
                    $list_info[$item['product_encoder']]['name']=$item['product_name'];
                    $list_info[$item['product_encoder']]['unit']=$item['unit'];
                    $list_info[$item['product_encoder']]['cost']=$item['cost'];
                }
            }
        }
        foreach ($list_item as $x => $v) {
            $tongthuoc=0;
            foreach ($list_date as $v1) {
                $tongthuoc += $v[$v1];
            }
            $Pres_total=$Pres_total+$tongthuoc*$list_info[$x]['cost'];
            $smarty->assign('DescriptionData','THUỐC'.'<br>'.'+ '.$list_info[$x]['name']);
            $smarty->assign('CostPerUnitData', number_format($tongthuoc*$list_info[$x]['cost']));
            $smarty->assign('UnitsData', $tongthuoc);
            $smarty->assign('TotalCostData',number_format($tongthuoc*$list_info[$x]['cost']));
            $smarty->assign('ItemTypeData',$list_info[$x]['unit']);
            ob_start();
            $smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
            $sListRows = $sListRows.ob_get_contents();
            ob_end_clean();
            $stt++;
        }

        //VTYT nội trú
        $medresult = $PresMed->getAllPresOfEncounterByBillId($patientno,'0');	  //list lai cac toa chua thanh toan, da duoc cap nhat gia
        if(is_object($medresult))
        {
           // $Med_total=0;
            $smarty->assign('flag_g', true);
            $smarty->assign('GroupName',$LDPrescriptionMed);
            for($i=0;$i<$medresult->RecordCount();$i++)
            {
                $pres = $medresult->FetchRow();
                $Med_total=$Med_total+$pres['total_cost'];

                $itemdate_y = strtotime($pres['date_time_create']);
                if(($itemdate_y>=$BHtungay_x) && ($itemdate_y<=$BHdenngay_x))
                    $total_for_insur += ($pres['total_cost']);

                //info of service
                $infodetail='<a href="javascript:viewDetailMed('.$patientno.','.$pres['prescription_id'].')">
			<img '.createComIcon($root_path,'info3.gif','0','',TRUE).'></a>';
                $smarty->assign('DescriptionData', $infodetail.' '.$LDPresId.': '.$pres['prescription_id']);
                $smarty->assign('CostPerUnitData', number_format($pres['total_cost']));
                $smarty->assign('UnitsData', '1');
                $smarty->assign('TotalCostData', number_format($pres['total_cost']));
                $smarty->assign('ItemTypeData',$pres['type_name']);
                $smarty->assign('ItemCheck', '<input type="checkbox" name="cbx'.$count.'" checked onclick="ChangeSum(this,'.$pres['total_cost'].')" '.$readonly.'> <input type="hidden" name="item'.$count.'" value="med_id_'.$pres['prescription_id'].'">');
                $count++;

                if($i>0)
                    $smarty->assign('flag_g', false);

                ob_start();
                $smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
                $sListRows = $sListRows.ob_get_contents();
                ob_end_clean();
            }
        }
        /*
        //VTYT theo list hienj ra
        $depotqry="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_med_prescription AS med, care_med_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
        $depotresult=$db->Execute($depotqry);
        $Med_total=0;
        //$smarty->assign('flag_g', true);
        // $smarty->assign('GroupName',$LDPrescriptionMed);
        for($i=0;$i<$depotresult->RecordCount();$i++)
        {
            $pres = $depotresult->FetchRow();
            $ma = $pres['product_encoder'];
           /*
            if($ma==51 || $ma==52 || $ma ==53 || $ma==65 || $ma==123){ // 51- Dây oxy số 8 10, 52- Dây oxy 2 nhánh(L), 53- Dây oxy 2 nhánh nhi, 65- Dây oxy hai nhánh size (S), 123- lọ đựng nước tiểu
                $kgiam = $kgiam + ($pres['sum_number']* $pres['cost'])*$mh;
            }
            // 150 - túi đựng nước tiểu, 47- Dây thông tiểu 12 naleton, 68- Dây thông tiểu Foley 14 16, 71-Dây thông tiểu Foley 12, 69- Dây thông tiểu nữ số 14, 70- Dây thông tiểu số 10 naleton, 54 - Dây cho ăn số 12 , 262- dây cho ăn số 16, 55- Dây cho ăn số 14 , 56- Dây cho ăn số 16, 57-Dây cho ăn số 8
            if($ma==51 || $ma==52 || $ma ==53 || $ma==65 || $ma==123 || $ma==150  || $ma== 47 || $ma == 68|| $ma == 71|| $ma == 69 || $ma == 70 || $ma == 54 || $ma == 262 || $ma==55 || $ma == 56 || $ma==57 || $ma==61 || $ma == 224 || $ma == 62 || $ma ==60){ // 51- Dây oxy số 8 10, 52- Dây oxy 2 nhánh(L), 53- Dây oxy 2 nhánh nhi, 65- Dây oxy hai nhánh size (S), 123- lọ đựng nước tiểu
                $kgiam = $kgiam + ($pres['sum_number']* $pres['cost'])*$mh;                                                                                                                                                                                         // 61 - Dây hút nhớt số 12, 224- Dây hút nhớt 14, 62- Dây hút nhớt 14 có van, 60- Dây hút nhớt số 8
            }
            $Med_total=$Med_total+$pres['sum_number']* $pres['cost'];
            //info of service
            $smarty->assign('DescriptionData','+ '.$pres['product_name']);
            $smarty->assign('CostPerUnitData', number_format($pres['sum_number']* $pres['cost']));
            $smarty->assign('UnitsData',$pres['sum_number']);
            $smarty->assign('TotalCostData',number_format($pres['sum_number']* $pres['cost']));
            $smarty->assign('ItemTypeData',$pres['note']);
            ob_start();
            $smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
            $sListRows = $sListRows.ob_get_contents();
            ob_end_clean();
    } */
    }
    //nếu ngoại trú thì hiện thuốc lấy nguyên toa ==>n
    else{
	$presresult = $Pres->getAllPresOfEncounterByBillId($patientno,'0');	  //list lai cac toa chua thanh toan, da duoc cap nhat gia
	if(is_object($presresult))
	{
		$Pres_total=0;
		$smarty->assign('flag_g', true);
		$smarty->assign('GroupName',$LDPrescription);
		for($i=0;$i<$presresult->RecordCount();$i++)
		{
			$pres = $presresult->FetchRow();
			$Pres_total=$Pres_total+$pres['total_cost'];

			$itemdate_y = strtotime($pres['date_time_create']);
			if(($itemdate_y>=$BHtungay_x) && ($itemdate_y<=$BHdenngay_x))
				$total_for_insur += ($pres['total_cost']);
			
			//info of service
			$infodetail='<a href="javascript:viewDetail('.$patientno.','.$pres['prescription_id'].')"> 
			<img '.createComIcon($root_path,'info3.gif','0','',TRUE).'></a>';
			$smarty->assign('DescriptionData', $infodetail.' '.$LDPresId.': '.$pres['prescription_id'].', '.$LDTherapy.': '.stripcslashes(nl2br($pres['diagnosis'])).'<br>'.$pres['note']);
			$smarty->assign('CostPerUnitData', number_format($pres['total_cost']));
			$smarty->assign('UnitsData', '1');
			$smarty->assign('TotalCostData', number_format($pres['total_cost']));		
			$smarty->assign('ItemTypeData',$pres['type_name']);
			$smarty->assign('ItemCheck', '<input type="checkbox" name="cbx'.$count.'" checked onclick="ChangeSum(this,'.$pres['total_cost'].')" '.$readonly.'> <input type="hidden" name="item'.$count.'" value="pres_id_'.$pres['prescription_id'].'">');
			$count++;
			if($i>0)
				$smarty->assign('flag_g', false);
			
			ob_start();
			$smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
			$sListRows = $sListRows.ob_get_contents();
			ob_end_clean(); 
		}
	}

       //VTYT Ngoại trú toa
        $medresult = $PresMed->getAllPresOfEncounterByBillId($patientno,'0');	  //list lai cac toa chua thanh toan, da duoc cap nhat gia
        if(is_object($medresult))
        {
           // $Med_total=0;
            $smarty->assign('flag_g', true);
            $smarty->assign('GroupName',$LDPrescriptionMed);
            for($i=0;$i<$medresult->RecordCount();$i++)
            {
                $pres = $medresult->FetchRow();
                $Med_total=$Med_total+$pres['total_cost'];

                $itemdate_y = strtotime($pres['date_time_create']);
                if(($itemdate_y>=$BHtungay_x) && ($itemdate_y<=$BHdenngay_x))
                    $total_for_insur += ($pres['total_cost']);

                //info of service
                $infodetail='<a href="javascript:viewDetailMed('.$patientno.','.$pres['prescription_id'].')">
			    <img '.createComIcon($root_path,'info3.gif','0','',TRUE).'></a>';
                $smarty->assign('DescriptionData', $infodetail.' '.$LDPresId.': '.$pres['prescription_id']);
                $smarty->assign('CostPerUnitData', number_format($pres['total_cost']));
                $smarty->assign('UnitsData', '1');
                $smarty->assign('TotalCostData', number_format($pres['total_cost']));
                $smarty->assign('ItemTypeData',$pres['type_name']);
                $smarty->assign('ItemCheck', '<input type="checkbox" name="cbx'.$count.'" checked onclick="ChangeSum(this,'.$pres['total_cost'].')" '.$readonly.'> <input type="hidden" name="item'.$count.'" value="med_id_'.$pres['prescription_id'].'">');
                $count++;

                if($i>0)
                    $smarty->assign('flag_g', false);

                ob_start();
                $smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
                $sListRows = $sListRows.ob_get_contents();
                ob_end_clean();
            }
        }
          /*
        //VTYT ngoại theo list hien ra
        $depotqry="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_med_prescription AS med, care_med_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
        $depotresult=$db->Execute($depotqry);
            $Med_total=0;
            //$smarty->assign('flag_g', true);
           // $smarty->assign('GroupName',$LDPrescriptionMed);
            for($i=0;$i<$depotresult->RecordCount();$i++)
            {
                $pres = $depotresult->FetchRow();
                $ma = $pres['product_encoder'];
                // 150 - túi đựng nước tiểu, 47- Dây thông tiểu 12 naleton, 68- Dây thông tiểu Foley 14 16, 71-Dây thông tiểu Foley 12, 69- Dây thông tiểu nữ số 14, 70- Dây thông tiểu số 10 naleton, 54 - Dây cho ăn số 12 , 262- dây cho ăn số 16, 55- Dây cho ăn số 14 , 56- Dây cho ăn số 16, 57-Dây cho ăn số 8
                if($ma==51 || $ma==52 || $ma ==53 || $ma==65 || $ma==123 || $ma==150  || $ma== 47 || $ma == 68|| $ma == 71|| $ma == 69 || $ma == 70 || $ma == 54 || $ma == 262 || $ma==55 || $ma == 56 || $ma==57 || $ma==61 || $ma == 224 || $ma == 62 || $ma ==60){ // 51- Dây oxy số 8 10, 52- Dây oxy 2 nhánh(L), 53- Dây oxy 2 nhánh nhi, 65- Dây oxy hai nhánh size (S), 123- lọ đựng nước tiểu
                    $kgiam = $kgiam + ($pres['sum_number']* $pres['cost'])*$mh;                                                                                                                                                                                         // 61 - Dây hút nhớt số 12, 224- Dây hút nhớt 14, 62- Dây hút nhớt 14 có van, 60- Dây hút nhớt số 8
                }

                $Med_total=$Med_total+$pres['sum_number']* $pres['cost'];
                //info of service
                $smarty->assign('DescriptionData','+ '.$pres['product_name']);
                $smarty->assign('CostPerUnitData', number_format($pres['sum_number']* $pres['cost']));
                $smarty->assign('UnitsData',$pres['sum_number']);
                $smarty->assign('TotalCostData',number_format($pres['sum_number']* $pres['cost']));
                $smarty->assign('ItemTypeData',$pres['note']);
                ob_start();
                $smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
                $sListRows = $sListRows.ob_get_contents();
                ob_end_clean();
            }         */
    }
	//FOR MEDIPOT  VTYT
    //2014-03-25: CoT, khong can phai tinh lai gia hien tai vi BHYT da chinh sua trong toa
//	$medresult = $PresMed->getAllPresOfEncounterByBillId($patientno,'0');		//list cac toa chua thanh toan
//	if(is_object($medresult)) {	//update currrent medicine cost, total cost of this pres
//		for($i=0;$i<$medresult->RecordCount();$i++) {
//			$pres = $medresult->FetchRow();
//			$PresMed->updateCostPres($pres['prescription_id']);
//		}
//	}
    /*
	$medresult = $PresMed->getAllPresOfEncounterByBillId($patientno,'0');	  //list lai cac toa chua thanh toan, da duoc cap nhat gia
	if(is_object($medresult))
	{
		$Med_total=0;
		$smarty->assign('flag_g', true);
		$smarty->assign('GroupName',$LDPrescriptionMed);
		for($i=0;$i<$medresult->RecordCount();$i++)
		{
			$pres = $medresult->FetchRow();
			$Med_total=$Med_total+$pres['total_cost'];

			$itemdate_y = strtotime($pres['date_time_create']);
			if(($itemdate_y>=$BHtungay_x) && ($itemdate_y<=$BHdenngay_x))
				$total_for_insur += ($pres['total_cost']);
			
			//info of service
			$infodetail='<a href="javascript:viewDetailMed('.$patientno.','.$pres['prescription_id'].')"> 
			<img '.createComIcon($root_path,'info3.gif','0','',TRUE).'></a>';
			$smarty->assign('DescriptionData', $infodetail.' '.$LDPresId.': '.$pres['prescription_id']);
			$smarty->assign('CostPerUnitData', number_format($pres['total_cost']));
			$smarty->assign('UnitsData', '1');
			$smarty->assign('TotalCostData', number_format($pres['total_cost']));		
			$smarty->assign('ItemTypeData',$pres['type_name']);
			$smarty->assign('ItemCheck', '<input type="checkbox" name="cbx'.$count.'" checked onclick="ChangeSum(this,'.$pres['total_cost'].')" '.$readonly.'> <input type="hidden" name="item'.$count.'" value="med_id_'.$pres['prescription_id'].'">');
			$count++;

			if($i>0)
				$smarty->assign('flag_g', false);
			
			ob_start();
			$smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
			$sListRows = $sListRows.ob_get_contents();
			ob_end_clean(); 
		}
	}
	   */
	//FOR CHEMICAL Hóa chất
    //2014-03-25: CoT, khong can phai tinh lai gia hien tai vi BHYT da chinh sua trong toa
//	$cheresult = $Pres->getAllChemicalOfEncounterByBillId($patientno,'0');		//list cac toa chua thanh toan
//	if(is_object($cheresult)) {	//update currrent medicine cost, total cost of this pres
//		for($i=0;$i<$cheresult->RecordCount();$i++) {
//			$pres = $cheresult->FetchRow();
//			$Pres->updateCostChemical($pres['prescription_id']);
//		}
//	}
	$cheresult = $Pres->getAllChemicalOfEncounterByBillId($patientno,'0');	  //list lai cac toa chua thanh toan, da duoc cap nhat gia
	if(is_object($cheresult))
	{
		$Che_total=0;
		$smarty->assign('flag_g', true);
		$smarty->assign('GroupName',$LDPrescriptionChemical);
		for($i=0;$i<$cheresult->RecordCount();$i++)
		{
			$pres = $cheresult->FetchRow();
			$Che_total=$Che_total+$pres['total_cost'];

			$itemdate_y = strtotime($pres['date_time_create']);
			if(($itemdate_y>=$BHtungay_x) && ($itemdate_y<=$BHdenngay_x))
				$total_for_insur += ($pres['total_cost']);
			
			//info of service
			$infodetail='<a href="javascript:viewDetailChemical('.$patientno.','.$pres['prescription_id'].')"> 
			<img '.createComIcon($root_path,'info3.gif','0','',TRUE).'></a>';
			$smarty->assign('DescriptionData', $infodetail.' '.$LDPresId.': '.$pres['prescription_id']);
			$smarty->assign('CostPerUnitData', number_format($pres['total_cost']));
			$smarty->assign('UnitsData', '1');
			$smarty->assign('TotalCostData', number_format($pres['total_cost']));		
			$smarty->assign('ItemTypeData',$pres['type_name']);
			$smarty->assign('ItemCheck', '<input type="checkbox" name="cbx'.$count.'" checked onclick="ChangeSum(this,'.$pres['total_cost'].')" '.$readonly.'> <input type="hidden" name="item'.$count.'" value="che_id_'.$pres['prescription_id'].'">');
			$count++;			
			if($i>0)
				$smarty->assign('flag_g', false);
			
			ob_start();
			$smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
			$sListRows = $sListRows.ob_get_contents();
			ob_end_clean(); 
		}
	}
	
	
	$total=$HStotal+$LTtotal+$Pres_total+$Med_total+$Che_total;
	$smarty->assign('ItemLine',$sListRows);
	$totaldue =  $total - $outstanding;

//bill payed, so print it	----------------------------------------------------------------------------
//toa đã được lưu thanh toán, xét nội trú và ngoại trú =>n
} else {
    //lấy thuốc đã thanh toán
      if($in_out==1){
          $pres_noitru = "SELECT iss.*, sum(iss.number) AS sum, prs.product_name, prs.note AS unit, prs.cost
					FROM care_pharma_prescription_issue AS iss, care_pharma_prescription AS prs
					WHERE iss.enc_nr='".$patientno."' AND prs.prescription_id=iss.pres_id
					AND prs.product_encoder=iss.product_encoder
					AND iss.status_bill='1'
					GROUP BY iss.product_encoder, iss.date_issue
					ORDER BY iss.product_encoder, iss.date_issue";
          $stt=1;
          $Pres_total=0;
          $list_item = array();
          $list_date = array();
          $k=0;
          $list_name = array();
          $list_info = array();
          if($pres_item_noitru=$db->Execute($pres_noitru)){
              for($i=0;$i<$pres_item_noitru->RecordCount();$i++){
                  $item = $pres_item_noitru->FetchRow();
                  $list_item[$item['product_encoder']][$item['date_issue']]= $item['sum'];   //==>n đổi $item['number'] thành  $item['sum']
                  if(!in_array($item['date_issue'], $list_date)){
                      $k++;
                      $list_date[$k]=$item['date_issue'];
                  }
                  if(!in_array($item['product_encoder'],$list_name)){
                      $list_info[$item['product_encoder']]['name']=$item['product_name'];
                      $list_info[$item['product_encoder']]['unit']=$item['unit'];
                      $list_info[$item['product_encoder']]['cost']=$item['cost'];
                  }
              }
          }
          foreach ($list_item as $x => $v) {
              $tongthuoc=0;
              foreach ($list_date as $v1) {
                  $tongthuoc += $v[$v1];
              }
              $Pres_total=$Pres_total+$tongthuoc*$list_info[$x]['cost'];
              $smarty->assign('DescriptionData','THUỐC'.'<br>'.'+ '.$list_info[$x]['name']);
              $smarty->assign('CostPerUnitData', number_format($tongthuoc*$list_info[$x]['cost']));
              $smarty->assign('UnitsData', $tongthuoc);
              $smarty->assign('TotalCostData',number_format($tongthuoc*$list_info[$x]['cost']));
              $smarty->assign('ItemTypeData',$list_info[$x]['unit']);
              ob_start();
              $smarty->display('ecombill/bill_payment_header_line_hoadon.tpl');
              $sListRows = $sListRows.ob_get_contents();
              ob_end_clean();
              $stt++;
          }
          //lấy các dịch vụ xét nghiệm
          $group_id =0;

          $oldbdqueryresult=$eComBill->checkBillByBillId($billid);
          if(is_object($oldbdqueryresult)) $billitemcount=$oldbdqueryresult->RecordCount();

          for ($obc=0;$obc<$billitemcount;$obc++) {
              //oldbd: row in billing_bill_item table
              //it: row in billing_item table
              $oldbd=$oldbdqueryresult->FetchRow();

              $itemdescresult = $eComBill->listServiceItemsByCode($oldbd['bill_item_code']);
              $cpu = $oldbd['bill_item_unit_cost'];
              $nounits = $oldbd['bill_item_units'];
              //Tính tiền Oxy
              $temp_cost= $cpu*$nounits;
              switch($oldbd['bill_item_code'])
              {
                  case 'HSCC01':
                      $totcost=$temp_cost*1;
                      break;
                  case 'HSCC02':
                      $totcost=$temp_cost*2;
                      break;
                  case 'HSCC03':
                      $totcost=$temp_cost*3;
                      break;
                  case 'HSCC04':
                      $totcost=$temp_cost*4;
                      break;
                  case 'HSCC05':
                      $totcost=$temp_cost*5;
                      break;
                  default:
                      $totcost=$cpu*$nounits;
              }
              if(is_object($itemdescresult)) $it=$itemdescresult->FetchRow();


              $smarty->assign('DescriptionData', '+ '.$it['item_description']);
              $smarty->assign('CostPerUnitData', number_format($oldbd['bill_item_unit_cost']));
              $smarty->assign('UnitsData', $oldbd['bill_item_units']);
              $smarty->assign('TotalCostData', number_format( $totcost));
             // $smarty->assign('TotalCostData', number_format($oldbd['bill_item_amount']));

              if($it['item_type']=="HS") {
                  $smarty->assign('ItemTypeData', $LDMedicalServices);
              } else if($it['item_type']=="LT") {
                  $smarty->assign('ItemTypeData', $LDLaboratoryTests);
              }
              //itemdate
              $smarty->assign('ItemDateData', formatDate2Local($oldbd['bill_item_date'],$date_format));

              if($lb1['item_type']=="HS") {
                  switch($oldbd['bill_item_code'])
                  {
                      case 'HSCC01':
                          $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*1;
                          break;
                      case 'HSCC02':
                          $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*2;
                          break;
                      case 'HSCC03':
                          $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*3;
                          break;
                      case 'HSCC04':
                          $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*4;
                          break;
                      case 'HSCC05':
                          $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*5;
                          break;
                      default:
                        $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units']);
                  }
                  //$HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units']);
              }
              if($lb1['item_type']=="LT") { $LTtotal=$LTtotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units']); }

              //groupname
              $flag_g = false;
              if ($group_id!=$it['item_group_nr'])
              {
                  $flag_g =true;
                  $smarty->assign('GroupName',$it['group_name']);
                  $group_id = $it['item_group_nr'];
              }
              $smarty->assign('flag_g', $flag_g);


              ob_start();
              $smarty->display('ecombill/bill_payment_header_line.tpl');
              $sListRows = $sListRows.ob_get_contents();
              ob_end_clean();

              //$oldbilltotal=$oldbilltotal+$oldbd['bill_item_amount'];
          }
      }

     else{
	//$oldbilltotal=0;
	$group_id =0;

	$oldbdqueryresult=$eComBill->checkBillByBillId($billid);
	if(is_object($oldbdqueryresult)) $billitemcount=$oldbdqueryresult->RecordCount();
	
	for ($obc=0;$obc<$billitemcount;$obc++) {
		//oldbd: row in billing_bill_item table
		//it: row in billing_item table
		$oldbd=$oldbdqueryresult->FetchRow();

		$itemdescresult = $eComBill->listServiceItemsByCode($oldbd['bill_item_code']);

        $cpu = $oldbd['bill_item_unit_cost'];
        $nounits = $oldbd['bill_item_units'];
        //Tính tiền Oxy
        $temp_cost= $cpu*$nounits;
        switch($oldbd['bill_item_code'])
        {
            case 'HSCC01':
                $totcost=$temp_cost*1;
                break;
            case 'HSCC02':
                $totcost=$temp_cost*2;
                break;
            case 'HSCC03':
                $totcost=$temp_cost*3;
                break;
            case 'HSCC04':
                $totcost=$temp_cost*4;
                break;
            case 'HSCC05':
                $totcost=$temp_cost*5;
                break;
            default:
                $totcost=$cpu*$nounits;
        }
		if(is_object($itemdescresult)) $it=$itemdescresult->FetchRow();

		
		$smarty->assign('DescriptionData', '+ '.$it['item_description']);
		$smarty->assign('CostPerUnitData', number_format($oldbd['bill_item_unit_cost']));
		$smarty->assign('UnitsData', $oldbd['bill_item_units']);
        $smarty->assign('TotalCostData', number_format( $totcost));
        // $smarty->assign('TotalCostData', number_format($oldbd['bill_item_amount']));

		if($it['item_type']=="HS") { 
			$smarty->assign('ItemTypeData', $LDMedicalServices);
		} else if($it['item_type']=="LT") { 
			$smarty->assign('ItemTypeData', $LDLaboratoryTests); 
		}
		//itemdate
		$smarty->assign('ItemDateData', formatDate2Local($oldbd['bill_item_date'],$date_format));
		
		if($lb1['item_type']=="HS") { switch($oldbd['bill_item_code'])
        {
            case 'HSCC01':
                $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*1;
                break;
            case 'HSCC02':
                $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*2;
                break;
            case 'HSCC03':
                $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*3;
                break;
            case 'HSCC04':
                $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*4;
                break;
            case 'HSCC05':
                $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units'])*5;
                break;
            default:
                $HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units']);
        }
            //$HStotal=$HStotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units']);
        }
		if($lb1['item_type']=="LT") { $LTtotal=$LTtotal+($oldbd['bill_item_unit_cost'])*($oldbd['bill_item_units']); }
		//groupname
		$flag_g = false;
		if ($group_id!=$it['item_group_nr'])
		{
			$flag_g =true;
			$smarty->assign('GroupName',$it['group_name']);			
			$group_id = $it['item_group_nr'];
		}
		$smarty->assign('flag_g', $flag_g); 
		
		
		ob_start();
		$smarty->display('ecombill/bill_payment_header_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean(); 		
		
		//$oldbilltotal=$oldbilltotal+$oldbd['bill_item_amount'];
	}
}
	$smarty->assign('ItemLine',$sListRows);
	
	//old bill
	//$oldbillotherqry="SELECT bill_outstanding FROM care_billing_bill where bill_bill_no='$billid'";
	//$oldbillotherqryresult=$eComBill->getInfoBillByBillId($billid);  //$db->Execute($oldbillotherqry);

}





 # Collect extra javascrit code

 ob_start();
?>
<script language="javascript">
<!--
	function popitup(patientno, receiptid, total, outstanding, totaldue) {
		var win = 'print_bill.php?patientno=' + patientno + '&receiptid='  + receiptid + '&total=' + total + '&outstanding=' + outstanding + '&totaldue=' + totaldue;
		window.open( win , 'print_bill' , 'height=400,width=650' );
		return false;
	}

	
	function savebill() {
		document.patientbill.action="patientbill_printsave.php";
		document.patientbill.submit();
	}
	
	function viewDetail(enc_nr,pres_id)
	{
		var win = '<?php echo $rootpath.'../registration_admission/show_prescription_detail.php'.URL_APPEND; ?>' + '&enc_nr='+ enc_nr +'&pres_id=' + pres_id +'&bill=true';
		myWindow=window.open( win , 'View Details' , 'height=500,width=650' );
		myWindow.focus();
	}
	function viewDetailMed(enc_nr,pres_id)
	{
		var win = '<?php echo $rootpath.'../registration_admission/show_prescription_depot_detail.php'.URL_APPEND; ?>' + '&enc_nr='+ enc_nr +'&pres_id=' + pres_id +'&bill=true';
		myWindow=window.open( win , 'View Details' , 'height=500,width=650' );
		myWindow.focus();
	}
	function viewDetailChemical(enc_nr,pres_id)
	{
		var win = '<?php echo $rootpath.'../registration_admission/show_prescription_chemical_detail.php'.URL_APPEND; ?>' + '&enc_nr='+ enc_nr +'&pres_id=' + pres_id +'&bill=true';
		myWindow=window.open( win , 'View Details' , 'height=500,width=650' );
		myWindow.focus();
	}
	function ChangeOutStd(){
		var giamBH= document.getElementById("discount").value;
		var tongtien = document.getElementById("totalcost").value;
		document.getElementById("outstd").value = tongtien - giamBH;
		//update hide data
		document.getElementById("total").value = document.getElementById("totalcost").value;
		document.getElementById("outstanding").value = document.getElementById("outstd").value;
	}
	function ChangeSum(cbx,value){
		//document.getElementById("check1").checked=false
		var total = document.getElementById("totalcost").value;
		total = total.replace(',','');
		if(cbx.checked==false)
			document.getElementById("totalcost").value= total - value;
		else
			document.getElementById("totalcost").value= total*1 + value;
		ChangeOutStd();	
	}	
//-->
</script>
<?php 
$sTemp = ob_get_contents();
ob_end_clean();


$smarty->append('JavaScript',$sTemp);


if($billid == "currentbill" && $target!='nursing') {
	$smarty->assign('pbSubmit','<a href="javascript:savebill();"><input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'></a>');
} else {
	$smarty->assign('pbSubmit','<a href="javascript:window.print();"><input type="image"  '.createLDImgSrc($root_path,'printout.gif','0','middle').'></a>');  
}

$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');


//Tong
$smarty->assign('LDTotal',$LDTotal);
if($billid=="currentbill") { 
	$LDTotalBillAmountData = $total; 
	include($root_path.'classes/money/baohiem.php');
	//$discount = TienBaoHiem($insurance_nr, $insurance_start, $insurance_exp,$total_for_insur, $insurance_start, $is_traituyen);
   // $discount = $mh*$LDTotalBillAmountData ;
    $discount = $mh*$LDTotalBillAmountData - $kgiam; //------>n sửa số tiền giảm bảo hiểm y tế dựa trên mức hưởng
	//echo $total_for_insur;
	if($target=='nursing')
		$smarty->assign('discount', number_format($discount));
	else
		$smarty->assign('discount', '<input type="text" id="discount" name="discount" value="'.number_format($discount) .'" size="10" onchange="ChangeOutStd()">');
}
else { 
	$LDTotalBillAmountData = $oldbilltotal;
}

$smarty->assign('LDTotalBillAmountData','<input type="text" id="totalcost" name="totalcost" value="'.$LDTotalBillAmountData.'" readonly style="border:0px;width:150px;text-align:right;font-weight:bold;">');
//Doc tien thanh chu
$smarty->assign('LDConvertMoney', $LDConvertMoney);
$sTempMoney = convertMoney($LDTotalBillAmountData);
$smarty->assign('money_total_Reader',$sTempMoney);

//BHYT
$smarty->assign('LDDiscountonTotalAmount', $LDDiscountonTotalAmount);




$sTempMoney = convertMoney($discount);
$smarty->assign('money_discount_Reader',$sTempMoney);

//Input outstanding---Tuyen
$smarty->assign('LDOutstandingAmount',$LDOutstanding);
if($billid == "currentbill"){
	$smarty->assign('LDCurrentBill', TRUE);
	if($target=='nursing')
		$smarty->assign('outstd','<b>'.number_format($LDTotalBillAmountData - $discount).'</b>');
	else
		$smarty->assign('outstd', '<input type="text" id="outstd" name="outstd" value="'.number_format($LDTotalBillAmountData - $discount).'" size="10">');
} else {
	$smarty->assign('LDOldBill', TRUE);
	$smarty->assign('outstd',number_format($oldbilloutstanding));
  //  $smarty->assign('outstd',number_format($LDTotalBillAmountData - $discount));


    $sTempMoney = convertMoney($oldbilloutstanding);
	$smarty->assign('money_outstd_Reader',$sTempMoney);
	
	$LDAmountDueData = $LDTotalBillAmountData-$oldbilloutstanding-$discount; // còn lại
	$smarty->assign('LDAmountDue',$LDAmountDue);
	$smarty->assign('LDAmountDueData',number_format($LDAmountDueData));
    //$smarty->assign('LDAmountDueData',($LDAmountDueData));
	$outstanding = $oldbilloutstanding;     //thanh toán
	
	$sTempMoney = convertMoney($LDAmountDueData);
	$smarty->assign('money_due_Reader',$sTempMoney);
}




//<input type="hidden" name="outstanding" value="'. $outstanding .'">
//thêm  <input type="hidden" id="discount1" name="discount1" value="'. $discount .'">
//<input type="hidden" id="outstd1" name="outstd1" value="'. ($LDTotalBillAmountData - $discount) .'">
$smarty->assign('sHiddenInputs','<input type="hidden" name="patientno" value="'. $patientno .'">
								<input type="hidden" name="billno" value="'. $billno .'">
								<input type="hidden" name="count" value="'. $count .'">
								<input type="hidden" id="total" name="total" value="'. $total .'">
								<input type="hidden" id="discount1" name="discount1" value="'. $discount .'">
								<input type="hidden" id="outstd1" name="outstd1" value="'. ($LDTotalBillAmountData - $discount) .'">
								<input type="hidden" id="outstanding" name="outstanding" value="'. $outstanding .'">
								<input type="hidden" name="target" value="'. $target .'">
								<input type="hidden" name="lang" value="'. $lang .'">
								<input type="hidden" name="sid" value="'. $sid .'">
								<input type="hidden" name="full_en" value="'. $full_en .'">');

/**
* show Template
*/
$smarty->assign('sMainBlockIncludeFile','ecombill/bill_payment_header.tpl');

$smarty->display('common/mainframe.tpl');
?>