<?php
/**
 * @package care_api
 */

/**
 */
require_once($root_path.'include/care_api_classes/class_core.php');
/**
 *  Billing Methods methods.
 *  Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance.
 * @author Gjergj Sheldija
 * @version beta 0.1
 * @copyright 2009,2010 Gjergj Sheldija
 * @package care_api
 */
class eComBill extends Core {

	var $billingArchive 	= 'care_billing_archive';	//Ghi log khi update dịch vụ, xét nghiệm
	var $billingBill 		= 'care_billing_bill';		//Từng hóa đơn nhỏ của mỗi bệnh nhân
	var $billItem 			= 'care_billing_bill_item';	//chi tiết từng hóa đơn nhỏ
	var $finalBill			= 'care_billing_final';		//Hóa đơn cuối cùng: tổng tiền các hóa đơn nhỏ, tổng tiền ứng trước, còn lại, trả
	var $billableItem		= 'care_billing_item';		//Danh sách các xét nghiệm, group theo cột item_type
	var $billPayment		= 'care_billing_payment';	//Từng phiếu tạm ứng của mỗi bệnh nhân
	var $billItemGroup		= 'care_billing_item_group';
	var $billItemType		= 'care_billing_item_type';


	function createBillableItem($testCode, $testName, $testPrice, $testType, $group_nr, $testDiscount) {
		$this->sql = "INSERT INTO $this->billableItem VALUES('". $testCode ."','".$testName."',".$testPrice.",'".$testType."',".$group_nr.",".$testDiscount.")";
		return $this->Transact();
	}
	
	function createBillArchiveItem($itemCode, $itemName, $itemPrice, $itemDiscount, $history) {
		$this->sql = "INSERT INTO $this->billingArchive VALUES('','". $itemCode ."','".$itemName."',".$itemPrice.",".$itemDiscount.",'".$history."')";
		return $this->Transact();
	}
	
	function listServiceItemsByType($type){
		global $db;
		$this->sql="SELECT i.*, g.item_group, g.group_name, t.type_name
					FROM $this->billableItem AS i, $this->billItemGroup AS g, $this->billItemType AS t
					WHERE i.item_type='". $type ."' 
					AND g.nr = i.item_group_nr 
					AND g.item_group = t.item_group 
					AND g.item_type = t.item_type
					ORDER BY i.item_group_nr,i.item_code ";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function listServiceItems(){
		global $db;
		$this->sql="SELECT * FROM $this->billableItem";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function listServiceItemsByCode($code){
		global $db;
		$this->sql="SELECT i.*, g.group_name FROM $this->billableItem AS i, $this->billItemGroup AS g 
					WHERE i.item_code='". $code ."' 
					AND i.item_group_nr=g.nr 
					ORDER BY g.nr";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function updateServiceItem($itemDescription, $itemCost, $itemDiscount, $itemCode) {
		$this->sql = "UPDATE $this->billableItem SET 
							item_description= '".$itemDescription."', 
							item_unit_cost= ".$itemCost.", 
							item_discount_max_allowed= ".$itemDiscount." 
						WHERE item_code='".$itemCode."'";
		return $this->Transact();
	}
	
	function checkFinalBillExist($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->finalBill WHERE final_encounter_nr='". $patient_no ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	
	function listBillsByEncounter($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->billItem WHERE bill_item_encounter_nr='". $patient_no ."'  AND bill_item_status IN ('0','')";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	
	function checkBillExist($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->billItem WHERE bill_item_encounter_nr='". $patient_no ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
    // lấy tổng tiền, giam BHYT, thanh toán của nội trú
    function checkBill($billid){
        global $db;
        $this->sql="SELECT *
					FROM care_billing_bill
					WHERE bill_bill_no='$billid'";
        if ($this->result=$db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            }else{return false;}
        }else{return false;}
    }
	function checkBillByBillId($billid){
		global $db;
		$this->sql="SELECT * 
					FROM $this->billItem 
					WHERE bill_item_bill_no='$billid' and bill_item_status='1'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	
	function checkPaymentExist($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->billPayment WHERE payment_encounter_nr='". $patient_no ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function listPayments(){
		global $db;
		$this->sql="SELECT payment_receipt_no FROM $this->billPayment ORDER BY payment_receipt_no DESC";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}

	function listFinalBills(){
		global $db;
		$this->sql="SELECT final_bill_no FROM $this->finalBill ORDER BY final_bill_no DESC LIMIT 1";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function getInfoBillByBillId($billid){
		global $db;
		$this->sql="SELECT * 
					FROM $this->billingBill 
					WHERE bill_bill_no='$billid'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function getBilledItemsByEncounter($patientno){
		global $db;
		$this->sql="SELECT bill_item_code,bill_item_units FROM $this->billItem  WHERE bill_item_encounter_nr='". $patientno ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}			
	}
	
	function createBillItem($patientno, $labcode, $unitcost, $no_units, $totalamt, $presdatetime,$billstatus='0',$bill_bill_no='0'){
		$this->sql = "INSERT INTO $this->billItem 	(bill_item_encounter_nr,bill_item_code,bill_item_unit_cost,bill_item_units,bill_item_amount,bill_item_date,bill_item_status,bill_item_bill_no) 
					VALUES($patientno,'$labcode',$unitcost,$no_units,$totalamt,'$presdatetime','$billstatus',$bill_bill_no)";
		
		if(!$this->Transact($this->sql)) return $this->getLastQuery();
		return true;
	}
	
	function createPaymentItem($patientno,$receipt_no,$presdatetime,$amtcash,$chkno,$amtcheque,$cdno,$amtcc,$totalamount,$type){
		$this->sql = "INSERT INTO $this->billPayment (payment_encounter_nr,payment_receipt_no,payment_date,payment_cash_amount,payment_cheque_no,payment_cheque_amount,payment_creditcard_no,payment_creditcard_amount,payment_amount_total,payment_type, create_id) 
						VALUES($patientno,$receipt_no,'$presdatetime',$amtcash,$chkno,$amtcheque,$cdno,$amtcc,$totalamount,'$type','".$_SESSION['sess_login_userid']."')";
		
		if(!$this->Transact($this->sql)) return $this->getLastQuery();
		return true;
	}
	
	function createBill($patientno,$presdatetime,$totalamt,$outstanding) {
		$this->sql = "INSERT INTO $this->billingBill (bill_encounter_nr,bill_date_time,bill_amount,bill_outstanding, create_id) 
						VALUES($patientno,'$presdatetime','$totalamt','$outstanding','".$_SESSION['sess_login_userid']."')";
		
		if(!$this->Transact($this->sql)) return $this->getLastQuery();
		echo $this->sql;
		return true;		
	}
	
	function createFinalBill($patientno, $final_bill_no, $presdate, $totalbill,$discount, $paidamt,$amtdue,$currentamt) {
		$this->sql = "INSERT INTO $this->finalBill (final_encounter_nr, final_bill_no,final_date,final_total_bill_amount,final_discount,final_total_receipt_amount,final_amount_due,final_amount_recieved, create_id)
					VALUES($patientno,$final_bill_no,'$presdate','$totalbill','$discount','$paidamt','$amtdue','$currentamt','".$_SESSION['sess_login_userid']."')";
		
		if(!$this->Transact($this->sql)) return $this->getLastQuery();
		return true;		
	}

	function listCurrentPayments($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->billPayment WHERE payment_encounter_nr='" . $patient_no ."' ORDER BY payment_date DESC";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function listCurrentPaymentsByRecipeNr($receiptid){
		global $db;
		$this->sql="SELECT * FROM $this->billPayment WHERE payment_receipt_no='" . $receiptid ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function billAmountPaymentbyEncounter($patientno){
		global $db;
		$this->sql="SELECT SUM(payment_amount_total) AS total_payment_amount 
					FROM $this->billPayment WHERE payment_encounter_nr='" . $patientno ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}

	function billAmountByEncounter($patientno){
		global $db;
		$this->sql="SELECT SUM(bill_amount) AS total_amount, SUM(bill_discount) AS total_discount ,SUM(bill_outstanding) AS total_outstanding 
					FROM $this->billingBill WHERE bill_encounter_nr='" . $patientno ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
		
	function listCurrentBills($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->billingBill WHERE bill_encounter_nr='" . $patient_no ."'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
		
	function listAllBills(){
		global $db;
		$this->sql="SELECT bill_bill_no FROM $this->billingBill ORDER BY bill_bill_no DESC";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	
	//	27/9/2011 --Tuyen
	function listItemsByBillId($bill_no){
		global $db;
		$this->sql="SELECT * FROM $this->billItem AS bill,$this->billableItem AS detail 
					WHERE bill.bill_item_bill_no='". $bill_no ."'  
					AND bill.bill_item_status='1' AND bill.bill_item_code=detail.item_code";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}	
	
	function listCurrentAdvancedPayments($patient_no){
		global $db;
		$this->sql="SELECT * FROM $this->billPayment WHERE payment_encounter_nr='" . $patient_no ."' AND payment_type='0' ORDER BY payment_date DESC";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	/* 01/11/11 - Tuyen
	* input: service = 'HS' or 'LT'
	* output: obj (table) of results
	*/
	function listAllTypeGroupItems($service)
	{
		global $db;
		$this->sql="SELECT g.*, t.type_name
					FROM $this->billItemGroup AS g, $this->billItemType AS t
					WHERE g.item_group = t.item_group 
					AND g.item_type = '".$service."'
					AND g.item_type = t.item_type";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/* 01/11/11 - Tuyen
	* input: item_group_nr
	* output: 1 row of result
	*/
	function getTypeGroupOfItem($item_group_nr)
	{
		global $db;
		$this->sql="SELECT g.*, t.type_name
					FROM $this->billItemGroup AS g, $this->billItemType AS t
					WHERE g.nr='".$item_group_nr."' 
					AND g.item_group = t.item_group 
					AND g.item_type = t.item_type";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	//Lay tat ca cac items trong tat ca cac hoa don cua benh nhan
	function listServiceItemsOfEncounter($patientno)
	{
		global $db;
		/*$this->sql="SELECT bill.*, serv.*
					FROM $this->billItem AS bill, $this->billableItem AS serv 
					WHERE bill.bill_item_encounter_nr='$patientno' 
					AND bill.bill_item_code = serv.item_code ";  */
        //nang lấy dịch vụ xét nghiệm trừ toa thuốc, VTYT, hóa chất
        $this->sql="SELECT g.group_name,bill.*, serv.*
					FROM $this->billItem AS bill, $this->billableItem AS serv,care_billing_item_group AS g
					WHERE bill.bill_item_encounter_nr='$patientno'
					AND bill.bill_item_code = serv.item_code
					AND serv.item_group_nr=g.nr
					AND serv.item_group_nr != 37
					AND serv.item_group_nr != 42
					AND serv.item_group_nr != 43";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
    // phieu in tong ket hoa don
    //Lay tat ca cac items trong tat ca cac hoa don cua benh nhan
    function listServiceItemsOfEncounter_in($patientno)
    {
        global $db;
        /*$this->sql="SELECT bill.*, serv.*
                    FROM $this->billItem AS bill, $this->billableItem AS serv
                    WHERE bill.bill_item_encounter_nr='$patientno'
                    AND bill.bill_item_code = serv.item_code ";  */
        //nang lấy dịch vụ xét nghiệm trừ toa thuốc, VTYT, hóa chất
        $this->sql="SELECT g.group_name,bill.*, serv.*,SUM(bill.bill_item_unit_cost* bill.bill_item_units) AS s, COUNT(bill.bill_item_units) AS soluong
					FROM $this->billItem AS bill, $this->billableItem AS serv,care_billing_item_group AS g
					WHERE bill.bill_item_encounter_nr='$patientno'
					AND bill.bill_item_code = serv.item_code
					AND serv.item_group_nr=g.nr
					AND serv.item_group_nr != 37
					AND serv.item_group_nr != 42
					AND serv.item_group_nr != 43
					GROUP BY g.group_name ";

        if ($this->result=$db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            }else{return false;}
        }else{return false;}
    }
	
	/* 8/11/11 -vy
	lay so tien tam ung
	*/
	function getTamung($payment_id){
		global $db;
		$this->enc_nr=$enc_nr;
		$this->sql="SELECT payment_cash_amount FROM $this->billPayment WHERE payment_receipt_no='$payment_id'";
		if($buf=$db->Execute($this->sql)) {
			if($buf->RecordCount()) {
			   $buf2=$buf->FetchRow();
				return $buf2['payment_cash_amount'];
			} else return FALSE;
		}else {
		    return FALSE;
		}
	}
	
	function report15Day($dept_nr,$ward_nr,$fromday,$today){
		global $db;	
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		else 
			list($f_year,$f_month,$f_day) = explode("-",$fromday);
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}
		else 
			list($t_year,$t_month,$t_day) = explode("-",$today);
			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
		
		//Test dept, ward
		$dept_ward='';
		if($dept_nr!='') $dept_ward=$dept_ward.' AND arc.dept_nr='.$dept_nr.' ';
		if($ward_nr!='') $dept_ward=$dept_ward.' AND arc.ward_nr='.$ward_nr.' ';
			
		$this->sql="SELECT arc.*, main.product_name, unit.unit_name_of_medicine, DAY(at_date_time) AS at_day, SUM(arc.number) AS total 
					FROM $this->tb_phar_archive AS arc, care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
					WHERE main.product_encoder=arc.product_encoder  
					AND unit.unit_of_medicine=main.unit_of_medicine 
					 ".$dept_ward." 
					AND (arc.pres_id>0 OR arc.use_id>0)
					AND arc.get_use=0 
					AND (YEAR(at_date_time)=YEAR('$today')) AND (MONTH(at_date_time)=MONTH('$today')) 
					AND (DAY(at_date_time) <= DAY('$today')) AND (DAY(at_date_time) >= DAY('$fromday')) 
					GROUP BY arc.product_encoder, DAY(at_date_time)   
					ORDER BY arc.product_encoder ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function getAllBillAtDay($today){
		global $db;	
		if($today=='')
			return false;		
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}
		else 
			list($t_year,$t_month,$t_day) = explode("-",$today);
			
		$this->sql="SELECT bill.* FROM care_billing_bill AS bill
					WHERE YEAR(bill.bill_date_time)='".$t_year."' AND MONTH(bill.bill_date_time)='".$t_month."' AND DAY(bill.bill_date_time)='".$t_day."'";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function getAllFinalBillAtDay($today){
		global $db;	
		if($today=='')
			return false;		
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}
		else 
			list($t_year,$t_month,$t_day) = explode("-",$today);
			
		$this->sql="SELECT * FROM care_billing_final
					WHERE YEAR(final_date)='".$t_year."' AND MONTH(final_date)='".$t_month."' AND DAY(final_date)='".$t_day."'
					AND final_amount_recieved<>0 ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function getAllPaymentAtDay($today){
		global $db;	
		if($today=='')
			return false;		
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}
		else 
			list($t_year,$t_month,$t_day) = explode("-",$today);
			
		$this->sql="SELECT * FROM care_billing_payment
					WHERE YEAR(payment_date)='".$t_year."' AND MONTH(payment_date)='".$t_month."' AND DAY(payment_date)='".$t_day."'
					AND payment_type='0' ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
    function listAllTotalCostNotPaid_noitru($encounter_nr){
        global $db;
        $this->sql="SELECT SUM(iss.number*prs.cost) AS total
					FROM care_pharma_prescription_issue AS iss, care_pharma_prescription AS prs
					WHERE iss.enc_nr='2014000167' AND prs.prescription_id=iss.pres_id AND prs.product_encoder=iss.product_encoder
					GROUP BY iss.product_encoder, iss.date_issue
					UNION ALL
					SELECT SUM(total_cost) AS total FROM care_med_prescription_info
					WHERE status_bill='0' AND encounter_nr='2014000167'
					UNION ALL
					SELECT SUM(total_cost) AS total FROM care_chemical_prescription_info
					WHERE status_bill='0' AND encounter_nr='2014000167'
					UNION ALL
					SELECT SUM(bill_item_amount) AS total FROM care_billing_bill_item
					WHERE bill_item_encounter_nr='2014000167' AND bill_item_status IN ('0','') ";
        if ($this->result=$db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            }else{return false;}
        }else{return false;}
    }
	function listAllTotalCostNotPaid($encounter_nr){
		global $db;	
		$this->sql="SELECT SUM(total_cost) AS total FROM care_pharma_prescription_info
					WHERE status_bill='0' AND encounter_nr='$encounter_nr'
					UNION ALL
					SELECT SUM(total_cost) AS total FROM care_med_prescription_info
					WHERE status_bill='0' AND encounter_nr='$encounter_nr'
					UNION ALL
					SELECT SUM(total_cost) AS total FROM care_chemical_prescription_info
					WHERE status_bill='0' AND encounter_nr='$encounter_nr'
					UNION ALL 
					SELECT SUM(bill_item_amount) AS total FROM care_billing_bill_item
					WHERE bill_item_encounter_nr='$encounter_nr' AND bill_item_status IN ('0','') ";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}		
	}
	
	function GetUserName($user_id){
		global $db;
		$this->sql="SELECT * FROM care_users WHERE login_id='".addslashes($user_id)."'";
		if($buf=$db->Execute($this->sql)) {
			if($buf->RecordCount()) {
			   $buf2=$buf->FetchRow();
				return $buf2['name'];
			} else return FALSE;
		}else {
		    return FALSE;
		}
	}

    function getPriceItemcode($item_code)
    {
        global $db;
        $this->sql="SELECT item_unit_cost FROM care_billing_item WHERE item_code='$item_code'";
        if($buf=$db->Execute($this->sql)) {
            if($buf->RecordCount()) {
                $buf2=$buf->FetchRow();
                return $buf2['item_unit_cost'];
            } else return 0;
        }else {
            return 0;
        }
    }
}
?>