<?php
/**
* @package care_api
*/
/** */
require_once($root_path.'include/care_api_classes/class_core.php');
/**
* Prescription methods. 
*
* Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
* @author Tuyen
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class Pharma extends Core {
	/**#@+
	* @access private
	* @var string
	*/
		
	/** 15/01/2012  - Tuyen-------------------------------------------------------------------------------
	* Kho chan
	*/
	var $tb_phar_main='care_pharma_products_main';
	
	var $tb_med_main='care_med_products_main';
	
	/** 15/01/2012
	* Nhap kho chan
	*/
	var $tb_phar_putin_info='care_pharma_put_in_info';
	var $tb_phar_putin='care_pharma_put_in';
	
	var $tb_med_putin='care_med_put_in';
	
	/** 15/01/2012
	* Xuat kho chan 
	*/
	var $tb_phar_payout_info='care_pharma_pay_out_info';
	var $tb_phar_payout = 'care_pharma_pay_out';
	
	var $tb_med_payout = 'care_med_pay_out';
								
	/** 05/04/2012
         * Hoa chat
	* Nhap kho chan
	*/
	var $tb_chemical_putin='care_chemical_put_in';
        var $tb_chemical_putin_info='care_chemical_put_in_info';
        //Xuat kho chan
        var $tb_chemical_payout_info='care_chemical_pay_out_info';
	var $tb_chemical_payout = 'care_chemical_pay_out';
	
	/**
	* SQL query result buffer
	* @var adodb record object
	*/
	var $result;

	/**
	* Preloaded data flag
	* @var boolean
	*/
	var $is_preloaded=false;

						
	/**
	* Constructor
	*/
	function Pharma(){
		//$this->setTable($this->tb_phar_destroy_info);
		//$this->setRefArray($this->tab_des_fields);
	}
	
#Medicine
	function getInfoMedicine($encoder){
	    global $db;
		$this->sql="SELECT khochan.*, donvi.*
					FROM care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
					WHERE khochan.product_encoder='$encoder' 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function getInfoMedipot($encoder){
	    global $db;
		$this->sql="SELECT khochan.*, donvi.*
					FROM care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi  
					WHERE khochan.product_encoder='$encoder' 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function getInfoChemical($encoder){
	    global $db;
		$this->sql="SELECT khochan.*, donvi.*
					FROM care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi  
					WHERE khochan.product_encoder='$encoder' 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}	
	//-----------------------------------PutIn & PayOut-------------------------------
	
	/** 18/10/2011
	 * Get all info of a PutIn, Payout, based on the id
	 * Tuyen
	 * @param int number
	 * @return table result or boolean
	 */
	function getDetailPutInInfo($nr){
	    global $db;
		$this->sql="SELECT putininfo.*,putin.*,putininfo.note AS generalnote, khochan.product_name, khochan.price AS price1, khochan.nuocsx, donvi.unit_name_of_medicine  
					FROM $this->tb_phar_putin_info AS putininfo, $this->tb_phar_putin AS putin, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
					WHERE putininfo.put_in_id='".$nr."' AND putin.put_in_id=putininfo.put_in_id 
					AND khochan.product_encoder=putin.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getDetailPayOutInfo($nr){
	    global $db;
		$this->sql="SELECT payoutinfo.*,payout.*,payoutinfo.note AS generalnote, khochan.product_name, khochan.price AS price1, khochan.nuocsx,  donvi.unit_name_of_medicine  
					FROM $this->tb_phar_payout_info AS payoutinfo, $this->tb_phar_payout AS payout, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
					WHERE payoutinfo.pay_out_id='".$nr."' AND payout.pay_out_id=payoutinfo.pay_out_id 
					AND khochan.product_encoder=payout.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get general info, based on the id
	 * Tuyen
	 * @param int number
	 * @return mixed array or boolean
	 */
	function getPutInInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin_info 
					WHERE put_in_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getPayOutInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout_info 
					WHERE pay_out_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	/** 18/10/2011
	 * Get all medicine in a CabinetPharma, based on the CabinetPharma id
	 * Tuyen
	 * @param int CabinetPharma number
	 * @return table result or boolean
	 */
	function getAllMedicineInPutIn($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin 
					WHERE put_in_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getAllMedicineInPayOut($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout 
					WHERE pay_out_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get ID of last Pharma
	 * Tuyen
	 * @return mixed array or boolean
	 */
	function getLastPutInID(){
		global $db;
		$this->sql="SELECT MAX(put_in_id) AS put_in_id FROM $this->tb_phar_putin_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getMedicineInPutIn($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_phar_putin WHERE id='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getLastPayOutID(){
		global $db;
		$this->sql="SELECT MAX(pay_out_id) AS pay_out_id FROM $this->tb_phar_payout_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getMedicineInPayOut($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_phar_payout WHERE id='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}	
	/** 18/10/2011
	 * Updates the status finish of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string new status
	 * @return boolean
	 */
	function setPutInStatusFinish($putin_id,$status) {
	    global $db;
		if(!$putin_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_putin_info
						SET status_finish='$status'
						WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
	
	function setPayOutStatusFinish($payout_id,$status) {
	    global $db;
		if(!$payout_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_payout_info
						SET status_finish='$status'
						WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}
	
	function setReceiveMedicineInPutIn($id,$number){
	    global $db;
		if(!$id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_putin
						SET number_voucher='$number'
						WHERE id='$id'";
		return $this->Transact($this->sql);	
	}	
	
	function setReceiveMedicineInPayOut($id,$number){
	    global $db;
		if(!$id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_payout
						SET number_voucher='$number'
						WHERE id='$id'";
		return $this->Transact($this->sql);	
	}
	
	function setInfoPutInWhenAccept($putin_id,$put_in_person,$totalcost,$user_accept, $hoidongkiemnhap, $ngaynhap, $hinhthucthanhtoan){
	    global $db;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_putin_info 
					SET put_in_person='$put_in_person', totalcost='$totalcost', user_accept='$user_accept', hoidongkiemnhap='$hoidongkiemnhap', ngaynhap='$ngaynhap', hinhthucthanhtoan='$hinhthucthanhtoan'						
					WHERE put_in_id='$putin_id'";
		return $this->Transact($this->sql);		
	}
	
	function setInfoPayOutWhenAccept($payout_id,$user_receive,$totalcost,$user_accept){
	    global $db;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_payout_info 
						SET receive='$user_receive', totalcost='$totalcost', user_accept='$user_accept' 
						WHERE pay_out_id='$payout_id'";
		return $this->Transact($this->sql);		
	}
	/** 18/10/2011
	 * Updates the status finish of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string new status
	 * @return boolean
	 */
	function deleteAllMedicineInPutIn($putin_id) {
	    global $db;
		if(!$putin_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_putin
					WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}	
	
	function deletePutIn($putin_id) {
	    global $db;
		if(!$putin_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_putin_info
					WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}	
	
	function deleteAllMedicineInPayOut($payout_id) {
	    global $db;
		if(!$payout_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_payout
					WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}	
	
	function deletePayOut($payout_id) {
	    global $db;
		if(!$payout_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_payout_info
					WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}	
	/** 28/11/2011
	 * List all CabinetPharma, with condition
	 * Tuyen
	 * @param string condition
	 * @PayOut table result or boolean
	 */
	function listAllPutIn($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
		
	function listAllPayOut($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/** 28/11/2011
	 * List some CabinetPharma, with condition
	 * Tuyen
	 * @param string condition, int current_page, int number_items_per_pag
	 * @PayOut table result or boolean
	 */
	function listSomePutInSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function countPutInItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_phar_putin_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	function listSomePayOutSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function countPayOutItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_phar_payout_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	/** 5/12/2011
	 * Updates the issue_user, issue_note, receive_user of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string issue_user, issue_note, receive_user
	 * @return boolean
	 */
	function setInfoPersonWhenPutIn($putin_id,$user_accept) {
	    global $db;
		if(!$putin_id) return FALSE;
		$this->sql="UPDATE $this->tb_phar_putin_info
						SET user_accept='$user_accept' 
						WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
	
	function setInfoPersonWhenPayOut($payout_id,$user_accept) {
	    global $db;
		if(!$payout_id) return FALSE;
		$this->sql="UPDATE $this->tb_phar_payout_info
						SET user_accept='$user_accept' 
						WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}
	
	/** 5/12/2011
	 * Insert
	 * @param int Pharma id
	 * @param string 
	 * @return boolean
	 */
        function InsertPutInInfo($pharma_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput, $place, $totalcost, $note, $user, $history)
        {
            global $db;
			//$totalcost=$totalcost+$totalcost*($vat/100);
			$this->sql="INSERT INTO care_pharma_put_in_info (put_in_id, pharma_type_put_in, supplier, date_time, put_in_person, delivery_person, voucher_id, vat, typeput, place, totalcost, note, create_time, create_id, history)
                    VALUES (0, '$pharma_type_put_in', '$supplier', '$date_time', '$put_in_person', '$delivery_person', '$voucher_id', '$vat', '$typeput', '$place', '$totalcost', '$note', CURRENT_TIMESTAMP, '$user', '$history')";
            //echo($this->sql);
            return $this->Transact($this->sql);	
        }
        function InsertPharmaPutIn($put_in_id, $product_encoder, $lotid, $product_date, $exp_date, $number_put_in, $number_voucher, $price, $note, $vat)
        {
            global $db;
			$price=$price+$price*($vat/100);
			$this->sql="INSERT INTO care_pharma_put_in (id, put_in_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)		VALUES (0, '$put_in_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_put_in', '$number_voucher', '$price', '$note')";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
        function InsertMedPutIn($put_in_id, $product_encoder, $lotid, $product_date, $exp_date, $number_put_in, $number_voucher, $price, $note, $vat)
        {
            global $db;
			$price=$price+$price*($vat/100);
			$this->sql="INSERT INTO care_pharma_put_in (id, put_in_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)		VALUES (0, $put_in_id, '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_put_in', '$number_voucher', '$price', '$note')";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
         function InsertPayOutInfo($pharma_type_pay_out, $placefrom, $date_time, $pay_out_person, $receiver, $voucher_id, $typeput, $note, $health_station, $totalcost)
        {
             global $db;
			$this->sql="INSERT INTO care_pharma_pay_out_info (pharma_type_pay_out, placefrom, date_time, pay_out_person, receiver, voucher_id, typeput, note, create_id, create_time, history, totalcost, health_station)
                            VALUES ($pharma_type_pay_out, '$placefrom', '$date_time','$pay_out_person', '$receiver', '$voucher_id', '$typeput', '$note', '$user', CURRENT_TIMESTAMP, '$history','$totalcost', '$health_station' )";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
        function InsertPharmaPayOut($pay_out_id, $product_encoder, $lotid, $product_date, $exp_date, $number_pay_out, $number_voucher, $price, $note)
        {
             global $db;
			$this->sql="INSERT INTO care_pharma_pay_out (id, pay_out_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)		VALUES (0, '$pay_out_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_pay_out', '$number_voucher', '$price', '$note')";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
         function InsertMedPayOut($pay_out_id, $product_encoder, $lotid, $product_date, $exp_date, $number_pay_out, $number_voucher, $price, $note)
        {
             global $db;
			$this->sql="INSERT INTO care_pharma_pay_out (id, pay_out_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)		VALUES (0, '$pay_out_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_pay_out', '$number_voucher', $price, '$note')";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
		
	/** 5/12/2011
	 * Update
	 * @param int Pharma id
	 * @param string 
	 * @return boolean
	 */
        function UpdatePutInInfo($putin_id, $pharma_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput, $place, $totalcost, $note, $user, $history)
        {
             global $db;
			$this->sql="UPDATE care_pharma_put_in_info 
						SET pharma_type_put_in='$pharma_type_put_in', 
							supplier='$supplier',
							date_time='$date_time', 
							put_in_person='$put_in_person', 
							delivery_person='$delivery_person', 
							voucher_id='$voucher_id', 
							vat='$vat',
							typeput='$typeput',
							place='$place', 
							totalcost='$totalcost', 
							note='$note', 
							create_time=CURRENT_TIMESTAMP, 
							create_id='$user', 
							history='$history'
						WHERE put_in_id='$putin_id'";
            return $this->Transact($this->sql);	
        }	 
	    function UpdatePayOutInfo($payout_id, $pharma_type_pay_out, $placefrom, $date_time, $pay_out_person, $receiver, $voucher_id, $typeput, $health_station, $totalcost, $note, $user, $history)
        {
             global $db;
			$this->sql="UPDATE care_pharma_pay_out_info 
						SET pharma_type_pay_out='$pharma_type_pay_out', 
							placefrom='$placefrom',
							date_time='$date_time', 
							pay_out_person='$pay_out_person', 
							receiver='$receiver', 
							voucher_id='$voucher_id', 
							typeput='$typeput',
							note='$note', 
							create_time=CURRENT_TIMESTAMP, 
							create_id='$user', 
							history='$history',
							health_station='$health_station', 
							totalcost=$totalcost 	
						WHERE pay_out_id='$payout_id'";
            return $this->Transact($this->sql);
        }
		
		# Health Station 
		
        function HealthStation()
        {
             global $db;
			$this->sql="SELECT ht.*, tp.*  
						FROM care_pharma_health_station AS ht, care_pharma_type_health_station AS tp 
						WHERE ht.type=tp.nr 
						ORDER BY ht.type, ht.village";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }
		function getNameHealthStation($id){
			global $db;
			$this->sql="SELECT ht.village AS name, ht.type, tp.typename  
						FROM care_pharma_health_station AS ht, care_pharma_type_health_station AS tp 
						WHERE ht.type=tp.nr
						AND ht.health_station='$id' ";
			
			if ($this->result=$db->Execute($this->sql)) {
				if ($this->result->RecordCount()) {				
					return $this->result->FetchRow();
				}else{return false;}
			}else{return false;}		
		}
		
#Medipot	
	//-----------------------------------PutIn & PayOut-------------------------------
	
	/** 18/10/2011
	 * Get all info of a PutIn, Payout, based on the id
	 * Tuyen
	 * @param int number
	 * @return table result or boolean
	 */
	function getDetailPutInMedInfo($nr){
	    global $db;
		$this->sql="SELECT putininfo.*,putin.*,putininfo.note AS generalnote, khochan.product_name, khochan.price AS price1, khochan.nuocsx, donvi.unit_name_of_medicine  
					FROM $this->tb_phar_putin_info AS putininfo, $this->tb_med_putin AS putin, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi 
					WHERE putininfo.put_in_id='".$nr."' AND putin.put_in_id=putininfo.put_in_id 
					AND khochan.product_encoder=putin.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getDetailPayOutMedInfo($nr){
	    global $db;
		$this->sql="SELECT payoutinfo.*,payout.*,payoutinfo.note AS generalnote, khochan.product_name, khochan.price AS price1, khochan.nuocsx, donvi.unit_name_of_medicine  
					FROM $this->tb_phar_payout_info AS payoutinfo, $this->tb_med_payout AS payout, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi 
					WHERE payoutinfo.pay_out_id='".$nr."' AND payout.pay_out_id=payoutinfo.pay_out_id 
					AND khochan.product_encoder=payout.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine ";					

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get general info, based on the id
	 * Tuyen
	 * @param int number
	 * @return mixed array or boolean
	 */
	function getPutInMedInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin_info 
					WHERE put_in_id='".$nr."' ";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	function getPayOutMedInfo($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout_info 
					WHERE pay_out_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	/** 18/10/2011
	 * Get all medicine in a CabinetPharma, based on the CabinetPharma id
	 * Tuyen
	 * @param int CabinetPharma number
	 * @return table result or boolean
	 */
	function getAllMedicineInPutInMed($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_putin 
					WHERE put_in_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function getAllMedicineInPayOutMed($nr){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_med_payout 
					WHERE pay_out_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	/** 18/10/2011
	 * Get ID of last Pharma
	 * Tuyen
	 * @return mixed array or boolean
	 */
	function getLastPutInMedID(){
		global $db;
		$this->sql="SELECT MAX(put_in_id) AS put_in_id FROM $this->tb_phar_putin_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getMedicineInPutInMed($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_med_putin WHERE id='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getLastPayOutIDMed(){
		global $db;
		$this->sql="SELECT MAX(pay_out_id) AS pay_out_id FROM $this->tb_phar_payout_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function getMedicineInPayOutMed($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_med_payout WHERE id='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}	
	/** 18/10/2011
	 * Updates the status finish of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string new status
	 * @return boolean
	 */
	function setPutInMedStatusFinish($putin_id,$status) {
	    global $db;
		if(!$putin_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_putin_info
						SET status_finish='$status'
						WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
	
	function setPayOutMedStatusFinish($payout_id,$status) {
	    global $db;
		if(!$payout_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_payout_info
						SET status_finish='$status'
						WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}
	
	function setReceiveMedicineInPutInMed($id,$number){
	    global $db;
		if(!$id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_med_putin
						SET number_voucher='$number'
						WHERE id='$id'";
		return $this->Transact($this->sql);	
	}	
	
	function setReceiveMedicineInPayOutMed($id,$number){
	    global $db;
		if(!$id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_med_payout
						SET number_voucher='$number'
						WHERE id='$id'";
		return $this->Transact($this->sql);	
	}
	
	function setInfoPutInMedWhenAccept($putin_id,$put_in_person,$totalcost,$user_accept, $hoidongkiemnhap, $ngaynhap, $hinhthucthanhtoan){
	    global $db;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_putin_info 
						SET put_in_person='$put_in_person', totalcost='$totalcost', user_accept='$user_accept', hoidongkiemnhap='$hoidongkiemnhap', ngaynhap='$ngaynhap', hinhthucthanhtoan='$hinhthucthanhtoan' 
						WHERE put_in_id='$putin_id'";
		return $this->Transact($this->sql);		
	}
	
	function setInfoPayOutMedWhenAccept($payout_id,$user_receive,$totalcost,$user_accept){
	    global $db;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_phar_payout_info 
						SET receive='$user_receive', totalcost='$totalcost', user_accept='$user_accept' 
						WHERE pay_out_id='$payout_id'";
		return $this->Transact($this->sql);		
	}
	/** 18/10/2011
	 * Updates the status finish of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string new status
	 * @return boolean
	 */
	function deleteAllMedicineInPutInMed($putin_id) {
	    global $db;
		if(!$putin_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_putin
					WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}	
	
	function deletePutInMed($putin_id) {
	    global $db;
		if(!$putin_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_putin_info
					WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}	
	
	function deleteAllMedicineInPayOutMed($payout_id) {
	    global $db;
		if(!$payout_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_med_payout
					WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}	
	
	function deletePayOutMed($payout_id) {
	    global $db;
		if(!$payout_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_phar_payout_info
					WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}	
	/** 28/11/2011
	 * List all CabinetPharma, with condition
	 * Tuyen
	 * @param string condition
	 * @PayOut table result or boolean
	 */
	function listAllPutInMed($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
		
	function listAllPayOutMed($condition){
	    global $db;
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	/** 28/11/2011
	 * List some CabinetPharma, with condition
	 * Tuyen
	 * @param string condition, int current_page, int number_items_per_pag
	 * @PayOut table result or boolean
	 */
	function listSomePutInMedSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_phar_putin_info ".$condition."  
					LIMIT $start_from,$number_items_per_page ";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function countPutInMedItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_phar_putin_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	
	function listSomePayOutMedSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
					FROM $this->tb_phar_payout_info ".$condition."  
					LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	function countPayOutMedItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_phar_payout_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	/** 5/12/2011
	 * Updates the issue_user, issue_note, receive_user of a CabinetPharma, based on the CabinetPharma id
	 * @param int CabinetPharma id
	 * @param string issue_user, issue_note, receive_user
	 * @return boolean
	 */
	function setInfoPersonWhenPutInMed($putin_id,$user_accept) {
	    global $db;
		if(!$putin_id) return FALSE;
		$this->sql="UPDATE $this->tb_phar_putin_info
						SET user_accept='$user_accept' 
						WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
	
	function setInfoPersonWhenPayOutMed($payout_id,$user_accept) {
	    global $db;
		if(!$payout_id) return FALSE;
		$this->sql="UPDATE $this->tb_phar_payout_info
						SET user_accept='$user_accept' 
						WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}
	
	/** 5/12/2011
	 * Insert
	 * @param int Pharma id
	 * @param string 
	 * @return boolean
	 */
        function InsertPutInMedInfo($pharma_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput,  $place, $totalcost, $note, $user, $history)
        {
             global $db;
			 //$totalcost=$totalcost+$totalcost*($vat/100);
			$this->sql="INSERT INTO care_pharma_put_in_info (put_in_id, pharma_type_put_in, supplier, date_time, put_in_person, delivery_person, voucher_id, vat, typeput, place, totalcost, note, create_time, create_id, history)
                    VALUES (0, '$pharma_type_put_in', '$supplier', '$date_time', '$put_in_person', '$delivery_person', '$voucher_id', '$vat', '$typeput', '$place', '$totalcost', '$note', CURRENT_TIMESTAMP, '$user', '$history')";
            //echo ($this->sql);
            return $this->Transact($this->sql);	
        }
        function InsertPharmaPutInMed($put_in_id, $product_encoder, $lotid, $product_date, $exp_date, $number_put_in, $number_voucher, $price, $note, $vat)
        {
            global $db;
			$price=$price+$price*($vat/100);
			$this->sql="INSERT INTO care_med_put_in (id, put_in_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)		VALUES (0, '$put_in_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_put_in', '$number_voucher', '$price', '$note')";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }

         function InsertPayOutMedInfo($pharma_type_pay_out, $placefrom, $date_time, $pay_out_person, $receiver, $voucher_id, $typeput, $note, $health_station, $totalcost)
        {
             global $db;
			$this->sql="INSERT INTO care_pharma_pay_out_info (pharma_type_pay_out, placefrom, date_time, pay_out_person, receiver, voucher_id, typeput, note, create_id, create_time, history, totalcost, health_station)
                            VALUES ($pharma_type_pay_out, '$placefrom', '$date_time','$pay_out_person', '$receiver', '$voucher_id', '$typeput', '$note', '$user', CURRENT_TIMESTAMP, '$history','$totalcost', '$health_station' )";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
        function InsertPharmaPayOutMed($pay_out_id, $product_encoder, $lotid, $product_date, $exp_date, $number_pay_out, $number_voucher, $price, $note)
        {
             global $db;
			$this->sql="INSERT INTO care_med_pay_out (id, pay_out_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)		VALUES (0, '$pay_out_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_pay_out', '$number_voucher', '$price', '$note')";
                //echo($this->sql);
            return $this->Transact($this->sql);	
        }
		
	/** 5/12/2011
	 * Update
	 * @param int Pharma id
	 * @param string 
	 * @return boolean
	 */
        function UpdatePutInMedInfo($putin_id, $pharma_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput, $place, $totalcost, $note, $user, $history)
        {
             global $db;
			$this->sql="UPDATE care_pharma_put_in_info 
						SET pharma_type_put_in='$pharma_type_put_in', 
							supplier='$supplier',
							date_time='$date_time', 
							put_in_person='$put_in_person', 
							delivery_person='$delivery_person', 
							voucher_id='$voucher_id', 
							vat='$vat',
							typeput='$typeput',
							place='$place', 
							totalcost='$totalcost', 
							note='$note', 
							create_time=CURRENT_TIMESTAMP, 
							create_id='$user', 
							history='$history'
						WHERE put_in_id='$putin_id'";
			//echo 	$this->sql;		
            return $this->Transact($this->sql);	
        }	 
	    function UpdatePayOutMedInfo($payout_id, $pharma_type_pay_out, $placefrom, $date_time, $pay_out_person, $receiver, $voucher_id, $typeput, $health_station, $totalcost, $note, $user, $history)
        {
             global $db;
			$this->sql="UPDATE care_pharma_pay_out_info 
						SET pharma_type_pay_out='$pharma_type_pay_out', 
							placefrom='$placefrom',
							date_time='$date_time', 
							pay_out_person='$pay_out_person', 
							receiver='$receiver', 
							voucher_id='$voucher_id',
							typeput = '$typeput',
							note='$note', 
							create_time=CURRENT_TIMESTAMP, 
							create_id='$user', 
							history='$history',
							health_station='$health_station', 
							totalcost=$totalcost 	
						WHERE pay_out_id='$payout_id'";
            return $this->Transact($this->sql);
        }
	
	#Bao cao nhap xuat ton kho chan
	//Kiem tra co bao cao nao chua
	function checkAnyReport($condition){
		global $db;
		$this->sql="SELECT *, MONTH(monthreport) AS getmonth, YEAR(monthreport) AS getyear 
					FROM care_pharma_khochan_report 
					 ".$condition."
					ORDER BY monthreport";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	function checkAnyReportMed($condition){
		global $db;
		$this->sql="SELECT *, MONTH(monthreport) AS getmonth, YEAR(monthreport) AS getyear 
					FROM care_med_khochan_report 
					 ".$condition."
					ORDER BY monthreport";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}	
	//Kiem tra co nhap lo hang nao chua
	function checkAnyPutIn($condition){
		global $db;
		$this->sql="SELECT *, MONTH(date_time) AS getmonth, YEAR(date_time) AS getyear 
					FROM $this->tb_phar_putin_info
					WHERE pharma_type_put_in='1' 
					AND status_finish=1
					 ".$condition." 
					ORDER BY date_time";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}
	function checkAnyPutInMed($condition){
		global $db;
		$this->sql="SELECT *, MONTH(date_time) AS getmonth, YEAR(date_time) AS getyear 
					FROM $this->tb_phar_putin_info
					WHERE pharma_type_put_in='2' 
					AND status_finish=1
					 ".$condition." 
					ORDER BY date_time";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}	
	function checkAnyReport_TonKhoChan($condition){
		global $db;
		$this->sql="SELECT * , monthreport AS getmonth, yearreport AS getyear 
					FROM care_pharma_khochan_ton_info  
					 ".$condition."
					ORDER BY yearreport, monthreport DESC";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	function checkAnyReport_TonKhoChanVTYT($condition){
		global $db;
		$this->sql="SELECT * , monthreport AS getmonth, yearreport AS getyear 
					FROM care_med_khochan_ton_info  
					 ".$condition."
					ORDER BY yearreport, monthreport DESC";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	function checkAnyReport_TonKhoChanHC($condition){
		global $db;
		$this->sql="SELECT * , monthreport AS getmonth, yearreport AS getyear 
					FROM care_chemical_khochan_ton_info  
					 ".$condition."
					ORDER BY yearreport, monthreport DESC";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}	
	function Thuoc_TonKhoChan($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_medicine 
					FROM care_pharma_khochan_ton AS re, care_pharma_khochan_ton_info AS reinfo,  
						care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
					WHERE reinfo.monthreport='$month' AND reinfo.yearreport='$year' 
					AND main.product_encoder=re.product_encoder 
					AND re.ton_id=reinfo.id 
					AND main.unit_of_medicine=unit.unit_of_medicine 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function VTYT_TonKhoChan($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_medicine 
					FROM care_med_khochan_ton AS re, care_med_khochan_ton_info AS reinfo,  
						care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE reinfo.monthreport='$month' AND reinfo.yearreport='$year' 
					AND main.product_encoder=re.product_encoder 
					AND re.ton_id=reinfo.id 
					AND main.unit_of_medicine=unit.unit_of_medicine 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}	
	function HC_TonKhoChan($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_chemical 
					FROM care_chemical_khochan_ton AS re, care_chemical_khochan_ton_info AS reinfo,  
						care_chemical_products_main AS main, care_chemical_unit_of_medicine AS unit
					WHERE reinfo.monthreport='$month' AND reinfo.yearreport='$year' 
					AND main.product_encoder=re.product_encoder 
					AND re.ton_id=reinfo.id 
					AND main.unit_of_chemical=unit.unit_of_chemical 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}	
	function Khochan_NhapThuoc($month, $year, $condition){
		global $db;
		$this->sql="SELECT putin.product_encoder, putin.lotid, putin.exp_date, main.product_name, unit.unit_name_of_medicine, 
						putin.number_voucher AS nhap, putin.price AS gianhap 
					FROM care_pharma_put_in AS putin, care_pharma_put_in_info AS putininfo, 
						care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
					WHERE putin.product_encoder = main.product_encoder 
						AND main.unit_of_medicine=unit.unit_of_medicine
						AND putininfo.put_in_id=putin.put_in_id
						 ".$cond_typeput." 
						AND putininfo.pharma_type_put_in='1' AND putininfo.status_finish='1'
						AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
						ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function Khochan_XuatThuoc($month, $year, $condition){
		global $db;
		$this->sql="SELECT payout.product_encoder, main.product_name, unit.unit_name_of_medicine, 
							payout.number_voucher AS xuat, payout.price AS giaxuat 
					FROM care_pharma_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
							care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
					WHERE payout.product_encoder = main.product_encoder 
							AND main.unit_of_medicine=unit.unit_of_medicine
							AND payoutinfo.pay_out_id=payout.pay_out_id
							 ".$cond_typeput." 
							AND payoutinfo.pharma_type_pay_out='1' AND payoutinfo.status_finish='1'
							AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}	
	//Nhap-xuat-ton kho chan, return: product_encoder, product_name, unit_name_of_medicine, nhap, gianhap, xuat, giaxuat
	function Khochan_baocaothuoc_nhapxuatton($month, $year, $cond_typeput){
		global $db;
		$this->sql="SELECT tb1.product_encoder, tb1.product_name, tb1.unit_name_of_medicine, tb1.price, tb1.nhap, tb1.lotid, tb1.exp_date, tb1.gianhap, tb2.xuat, tb2.giaxuat 
					FROM (	
							SELECT putin.product_encoder, putin.lotid, putin.exp_date, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.price AS gianhap 
							FROM care_pharma_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='1' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					LEFT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_pharma_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='1' AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
					UNION
					SELECT tb2.product_encoder, tb2.product_name, tb2.unit_name_of_medicine, tb2.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat
					FROM (	
							SELECT putin.product_encoder, putin.lotid, putin.exp_date, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.price AS gianhap 
							FROM care_pharma_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='1' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					RIGHT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_pharma_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='1'  AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
						WHERE tb1.product_encoder IS NULL
					ORDER BY product_name";
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	function Khochan_baocaovtyt_nhapxuatton($month, $year, $cond_typeput){
		global $db;
		$this->sql="SELECT tb1.product_encoder, tb1.product_name, tb1.unit_name_of_medicine, tb1.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat 
					FROM (	
							SELECT putin.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.lotid, putin.exp_date, putin.price AS gianhap 
							FROM care_med_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='2' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					LEFT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_med_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='2' AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
					UNION
					SELECT tb2.product_encoder, tb2.product_name, tb2.unit_name_of_medicine, tb2.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat
					FROM (	
							SELECT putin.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.lotid, putin.exp_date, putin.price AS gianhap 
							FROM care_med_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='2' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					RIGHT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_med_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='2'  AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
						WHERE tb1.product_encoder IS NULL
					ORDER BY product_name";
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	//Ton truoc cua 1 encoder
	function Khochan_thuoc_tontruoc($encoder, $condition){
		global $db;
		$this->sql="SELECT toninfo.fromdate, toninfo.todate, ton.*, SUM(ton.number) AS last_number, ton.price AS last_cost 
					FROM care_pharma_khochan_ton AS ton, care_pharma_khochan_ton_info AS toninfo 
					WHERE ton.product_encoder='$encoder' 
					AND ton.ton_id=toninfo.id
					 ".$condition." 
					GROUP BY ton.product_encoder, ton.ton_id   
					ORDER BY toninfo.yearreport, toninfo.monthreport DESC";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function Khochan_thuoc_tongnhapxuat_theongay($encoder, $fromday, $today, $cond_typeput, $flag_equal){
		global $db;
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
			
		if($flag_equal)	$sign = "=";
		else $sign = "";	
			
		$this->sql="SELECT SUM(putin.number_voucher) AS tongnhap, SPACE(10) AS tongxuat 
					FROM care_pharma_put_in_info AS putininfo, care_pharma_put_in AS putin
					WHERE putin.put_in_id=putininfo.put_in_id
						AND putininfo.pharma_type_put_in ='1' AND status_finish='1' ".$cond_typeput."  
						AND DATE(putininfo.date_time)>".$sign."'$fromday' AND DATE(putininfo.date_time)<".$sign."'$today'
						AND putin.product_encoder='$encoder'
					GROUP BY putin.product_encoder, putin.put_in_id	
					UNION	
					SELECT SPACE(10) AS tongnhap, SUM(payout.number_voucher) AS tongxuat
					FROM care_pharma_pay_out_info AS payoutinfo, care_pharma_pay_out AS payout
					WHERE payout.pay_out_id=payoutinfo.pay_out_id
						AND payoutinfo.pharma_type_pay_out='1' AND status_finish='1' ".$cond_typeput." 
						AND DATE(payoutinfo.date_time)>".$sign."'$fromday' AND DATE(payoutinfo.date_time)<".$sign."'$today'
						AND payout.product_encoder='$encoder'
					GROUP BY payout.product_encoder, payout.pay_out_id";	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	function Khochan_vtyt_tontruoc($encoder, $condition){
		global $db;
		$this->sql="SELECT toninfo.fromdate, toninfo.todate, ton.*, SUM(ton.number) AS last_number, ton.price AS last_cost 
					FROM care_med_khochan_ton AS ton, care_med_khochan_ton_info AS toninfo 
					WHERE ton.product_encoder='$encoder' 
					AND ton.ton_id=toninfo.id
					 ".$condition." 
					GROUP BY ton.product_encoder, ton.ton_id   
					ORDER BY toninfo.yearreport, toninfo.monthreport DESC";
		//echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function Khochan_vtyt_tongnhapxuat_theongay($encoder, $fromday, $today, $cond_typeput, $flag_equal){
		global $db;
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
			
		if($flag_equal)	$sign = "=";
		else $sign = "";	
			
		$this->sql="SELECT SUM(putin.number_voucher) AS tongnhap, SPACE(10) AS tongxuat 
					FROM care_pharma_put_in_info AS putininfo, care_med_put_in AS putin
					WHERE putin.put_in_id=putininfo.put_in_id
						AND putininfo.pharma_type_put_in ='2' AND status_finish='1' ".$cond_typeput."  
						AND DATE(putininfo.date_time)>".$sign."'$fromday' AND DATE(putininfo.date_time)<".$sign."'$today'
						AND putin.product_encoder='$encoder'
					GROUP BY putin.product_encoder 	
					UNION	
					SELECT SPACE(10) AS tongnhap, SUM(payout.number_voucher) AS tongxuat
					FROM care_pharma_pay_out_info AS payoutinfo, care_med_pay_out AS payout
					WHERE payout.pay_out_id=payoutinfo.pay_out_id
						AND payoutinfo.pharma_type_pay_out='2' AND status_finish='1' ".$cond_typeput." 
						AND DATE(payoutinfo.date_time)>".$sign."'$fromday' AND DATE(payoutinfo.date_time)<".$sign."'$today'
						AND payout.product_encoder='$encoder'
					GROUP BY payout.product_encoder";
		//echo $this->sql;	
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	//Bao cao ton thang da luu
	function Khochan_thuoc_tonthangtruoc($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_medicine 
					FROM care_pharma_khochan_report AS re, care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
					WHERE MONTH(re.monthreport)='$month' AND YEAR(re.monthreport)='$year' 
					AND main.product_encoder=re.product_encoder 
					AND main.unit_of_medicine=unit.unit_of_medicine 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function Khochan_vtyt_tonthangtruoc($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_medicine 
					FROM care_med_khochan_report AS re, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE MONTH(re.monthreport)='$month' AND YEAR(re.monthreport)='$year' 
					AND main.product_encoder=re.product_encoder 
					AND main.unit_of_medicine=unit.unit_of_medicine 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function Khochan_thuoc_luubaocao($product_encoder, $monthreport, $first_number, $putin=0, $payout=0, $last_number=0, $last_cost=0, $user_report, $typeput){
		global $db;
		$this->sql="INSERT INTO care_pharma_khochan_report (product_encoder, monthreport, 
		first_number, putin, payout, last_number, last_cost, create_time, user_report, typeput)
				VALUES ('$product_encoder', '$monthreport', '$first_number', '$putin', '$payout', '$last_number', '$last_cost', CURRENT_TIMESTAMP, '$user_report', '$typeput')";
		return $this->Transact($this->sql);
	}
	function Khochan_vtyt_luubaocao($product_encoder, $monthreport, $first_number, $putin, $payout, $last_number, $last_cost, $user_report, $typeput){
		global $db;
		$this->sql="INSERT INTO care_med_khochan_report (product_encoder, monthreport, 
		first_number, putin, payout, last_number, last_cost, create_time, user_report)
				VALUES ('$product_encoder', '$monthreport', '$first_number', '$putin', '$payout', '$last_number', '$last_cost', CURRENT_TIMESTAMP, '$user_report', '$typeput')";
		return $this->Transact($this->sql);
	}
	function Khochan_thuoc_thekho($encoder, $fromday, $today, $cond_typeput){
		global $db;
		//format days to yyyy-mm-dd
		
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
		
		$this->sql="SELECT DATE(putininfo.date_time) AS ngay, putininfo.put_in_id AS manhap, SPACE(10) AS maxuat, 	
			putininfo.voucher_id, putininfo.supplier AS lydo, 
			putin.product_encoder, putin.number_voucher, putin.price, putin.lotid, putin.exp_date
		FROM care_pharma_put_in_info AS putininfo, care_pharma_put_in AS putin
		WHERE putin.put_in_id=putininfo.put_in_id
			AND putininfo.pharma_type_put_in ='1' AND status_finish='1' ".$cond_typeput."  
			AND DATE(putininfo.date_time)>='$fromday' AND DATE(putininfo.date_time)<='$today'
			AND putin.product_encoder='$encoder'
		UNION	
		SELECT DATE(payoutinfo.date_time) AS ngay, SPACE(10) AS manhap, payoutinfo.pay_out_id AS maxuat, 	
			payoutinfo.voucher_id, payoutinfo.health_station AS lydo,
			payout.product_encoder, payout.number_voucher, payout.price, payout.lotid, payout.exp_date
		FROM care_pharma_pay_out_info AS payoutinfo, care_pharma_pay_out AS payout
		WHERE payout.pay_out_id=payoutinfo.pay_out_id
			AND payoutinfo.pharma_type_pay_out='1' AND status_finish='1' ".$cond_typeput." 
			AND DATE(payoutinfo.date_time)>='$fromday' AND DATE(payoutinfo.date_time)<='$today'
			AND payout.product_encoder='$encoder'
		ORDER BY ngay ";
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	function Khochan_vtyt_thekho($encoder, $fromday, $today, $cond_typeput){
		global $db;
		//format days to yyyy-mm-dd
		
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
		
		$this->sql="SELECT DATE(putininfo.date_time) AS ngay, putininfo.put_in_id AS manhap, SPACE(10) AS maxuat, 	
			putininfo.voucher_id, putininfo.supplier AS lydo, 
			putin.product_encoder, putin.number_voucher, putin.price, putin.lotid, putin.exp_date
		FROM care_pharma_put_in_info AS putininfo, care_med_put_in AS putin
		WHERE putin.put_in_id=putininfo.put_in_id
			AND putininfo.pharma_type_put_in ='2' AND status_finish='1' ".$cond_typeput."  
			AND DATE(putininfo.date_time)>='$fromday' AND DATE(putininfo.date_time)<='$today'
			AND putin.product_encoder='$encoder'
		UNION	
		SELECT DATE(payoutinfo.date_time) AS ngay, SPACE(10) AS manhap, payoutinfo.pay_out_id AS maxuat, 	
			payoutinfo.voucher_id, payoutinfo.health_station AS lydo,
			payout.product_encoder, payout.number_voucher, payout.price, payout.lotid, payout.exp_date
		FROM care_pharma_pay_out_info AS payoutinfo, care_med_pay_out AS payout
		WHERE payout.pay_out_id=payoutinfo.pay_out_id
			AND payoutinfo.pharma_type_pay_out='2' AND status_finish='1' ".$cond_typeput." 
			AND DATE(payoutinfo.date_time)>='$fromday' AND DATE(payoutinfo.date_time)<='$today'
			AND payout.product_encoder='$encoder'
		ORDER BY ngay ";
		
		//echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	#Bao cao nhap xuat ton kho le
	//Kiem tra co bao cao nao chua
	function checkAnyReport_KhoLe($condition){
		global $db;
		$this->sql="SELECT *, MONTH(monthreport) AS getmonth, YEAR(monthreport) AS getyear 
					FROM care_pharma_khole_report 
					 ".$condition."
					ORDER BY monthreport";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
	function checkAnyReportMed_KhoLe($condition){
		global $db;
		$this->sql="SELECT *, MONTH(monthreport) AS getmonth, YEAR(monthreport) AS getyear 
					FROM care_med_khole_report 
					 ".$condition."
					ORDER BY monthreport";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}	
	//Kiem tra co nhap lo hang nao vao kho le chua
	function checkAnyPayOut($condition){
		global $db;
		$this->sql="SELECT *, MONTH(date_time) AS getmonth, YEAR(date_time) AS getyear 
					FROM $this->tb_phar_payout_info
					WHERE pharma_type_pay_out='1' 
					AND status_finish=1 AND health_station='0' 
					 ".$condition." 
					ORDER BY date_time";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}
	function checkAnyPayOutMed($condition){
		global $db;
		$this->sql="SELECT *, MONTH(date_time) AS getmonth, YEAR(date_time) AS getyear 
					FROM $this->tb_phar_payout_info
					WHERE pharma_type_pay_out='2' 
					AND status_finish=1 AND health_station='0' 
					 ".$condition." 
					ORDER BY date_time";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}	
	//Nhap-xuat-ton kho le, return: product_encoder, product_name, unit_name_of_medicine, nhap, gianhap, xuat, giaxuat
	function Khole_baocaothuoc_nhapxuatton($month, $year, $cond_typeput){
		global $db;
		$this->sql="SELECT tb1.product_encoder, tb1.product_name, tb1.unit_name_of_medicine, tb1.price, tb1.nhap, tb1.lotid, tb1.exp_date, tb1.gianhap, tb2.xuat, tb2.giaxuat 
					FROM (	
							SELECT putin.product_encoder, putin.lotid, putin.exp_date, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.price AS gianhap 
							FROM care_pharma_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='1' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					LEFT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_pharma_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='1' AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
					UNION
					SELECT tb2.product_encoder, tb2.product_name, tb2.unit_name_of_medicine, tb2.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat
					FROM (	
							SELECT putin.product_encoder, putin.lotid, putin.exp_date, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.price AS gianhap 
							FROM care_pharma_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='1' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					RIGHT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_pharma_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='1'  AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
						WHERE tb1.product_encoder IS NULL
					ORDER BY product_name";
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	function Khole_baocaovtyt_nhapxuatton($month, $year, $cond_typeput){
		global $db;
		$this->sql="SELECT tb1.product_encoder, tb1.product_name, tb1.unit_name_of_medicine, tb1.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat 
					FROM (	
							SELECT putin.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.lotid, putin.exp_date, putin.price AS gianhap 
							FROM care_med_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='2' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					LEFT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_med_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='2' AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
					UNION
					SELECT tb2.product_encoder, tb2.product_name, tb2.unit_name_of_medicine, tb2.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat
					FROM (	
							SELECT putin.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(putin.number_voucher) AS nhap, putin.lotid, putin.exp_date, putin.price AS gianhap 
							FROM care_med_put_in AS putin, care_pharma_put_in_info AS putininfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE putin.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND putininfo.put_in_id=putin.put_in_id
								 ".$cond_typeput." 
								AND putininfo.pharma_type_put_in='2' AND putininfo.status_finish='1'
								AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
							GROUP BY putin.product_encoder) AS tb1
					RIGHT JOIN (
							SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_medicine, 
								SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat 
							FROM care_med_pay_out AS payout, care_pharma_pay_out_info AS payoutinfo, 
								care_med_products_main AS main, care_med_unit_of_medipot AS unit
							WHERE payout.product_encoder = main.product_encoder 
								AND main.unit_of_medicine=unit.unit_of_medicine
								AND payoutinfo.pay_out_id=payout.pay_out_id
								 ".$cond_typeput." 
								AND payoutinfo.pharma_type_pay_out='2'  AND payoutinfo.status_finish='1'
								AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
							GROUP BY payout.product_encoder) AS tb2
						ON tb1.product_encoder=tb2.product_encoder
						WHERE tb1.product_encoder IS NULL
					ORDER BY product_name";
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	//Ton truoc cua 1 encoder
	function Khole_thuoc_tontruoc($encoder, $condition){
		global $db;
		$this->sql="SELECT * FROM care_pharma_khole_report 
					WHERE product_encoder='$encoder' 
					 ".$condition." 
					ORDER BY monthreport";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function Khole_vtyt_tontruoc($encoder, $condition){
		global $db;
		$this->sql="SELECT * FROM care_med_khole_report 
					WHERE product_encoder='$encoder'
					 ".$condition."
					ORDER BY monthreport";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	
	//Bao cao ton thang da luu
	function Khole_thuoc_tonthangtruoc($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_medicine 
					FROM care_pharma_khole_report AS re, care_pharma_products_main AS main, care_pharma_unit_of_medicine AS unit
					WHERE MONTH(re.monthreport)='$month' AND YEAR(re.monthreport)='$year' 
					AND main.product_encoder=re.product_encoder 
					AND main.unit_of_medicine=unit.unit_of_medicine 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	function Khole_vtyt_tonthangtruoc($month, $year, $condition){
		global $db;
		$this->sql="SELECT re.*, main.product_name, unit.unit_name_of_medicine 
					FROM care_med_khole_report AS re, care_med_products_main AS main, care_med_unit_of_medipot AS unit
					WHERE MONTH(re.monthreport)='$month' AND YEAR(re.monthreport)='$year' 
					AND main.product_encoder=re.product_encoder 
					AND main.unit_of_medicine=unit.unit_of_medicine 
					 ".$condition." 
					ORDER BY main.product_name";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}	
	}
	
	function Khole_thuoc_luubaocao($product_encoder, $monthreport, $first_number, $nhap=0, $xuat=0, $last_number=0, $last_cost=0, $user_report, $typeput){
		global $db;
		$this->sql="INSERT INTO care_pharma_khochan_report (product_encoder, monthreport, 
		first_number, nhap, xuat, last_number, last_cost, create_time, user_report, typeput)
				VALUES ('$product_encoder', '$monthreport', '$first_number', '$nhap', '$xuat', '$last_number', '$last_cost', CURRENT_TIMESTAMP, '$user_report', '$typeput')";
		return $this->Transact($this->sql);
	}
	function Khole_vtyt_luubaocao($product_encoder, $monthreport, $first_number, $nhap, $xuat, $last_number, $last_cost, $user_report, $typeput){
		global $db;
		$this->sql="INSERT INTO care_med_khochan_report (product_encoder, monthreport, 
		first_number, nhap, xuat, last_number, last_cost, create_time, user_report)
				VALUES ('$product_encoder', '$monthreport', '$first_number', '$nhap', '$xuat', '$last_number', '$last_cost', CURRENT_TIMESTAMP, '$user_report', '$typeput')";
		return $this->Transact($this->sql);
	}
	
        //**********************************************************************************************************************
        //**********************************************************************************************************************
        //H�a ch?t 05-04
        //**********************************************************************************************************************
        //**********************************************************************************************************************
        function getLastPutInChemicalID(){
		global $db;
		$this->sql="SELECT MAX(put_in_id) AS put_in_id FROM $this->tb_chemical_putin_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
        
        function InsertPutInChemicalInfo($chemical_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput, $place, $totalcost, $note, $user, $history)
        {
            global $db;
            //$totalcost=$totalcost+$totalcost*($vat/100);
            $this->sql="INSERT INTO care_chemical_put_in_info (put_in_id, chemical_type_put_in, supplier, date_time, put_in_person, delivery_person, voucher_id, vat, typeput, place, totalcost, note, create_time, create_id, history)
                        VALUES (0, '$chemical_type_put_in', '$supplier', '$date_time', '$put_in_person', '$delivery_person', '$voucher_id', '$vat', '$typeput', '$place', '$totalcost', '$note', CURRENT_TIMESTAMP, '$user', '$history')";
//            echo $this->sql;
            return $this->Transact($this->sql);	
        }
        
        function InsertPutInChemical($put_in_id, $product_encoder, $lotid, $product_date, $exp_date, $number_put_in, $number_voucher, $price, $note, $vat)
        {
            global $db;
            $price=$price+$price*($vat/100);
            $this->sql="INSERT INTO care_chemical_put_in (id, put_in_id, product_encoder, lotid, product_date, exp_date, number, number_voucher, price, note)
                            VALUES (0, '$put_in_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', '$number_put_in', '$number_voucher', '$price', '$note')";
//            echo $this->sql;
            return $this->Transact($this->sql);	
        }
        
        function UpdatePutInChemicalInfo($putin_id, $chemial_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput, $place, $totalcost, $note, $user, $history)
        {
            global $db;
            //$totalcost=$totalcost+$totalcost*($vat/100);
            $this->sql="UPDATE care_chemical_put_in_info 
                                    SET chemical_type_put_in='$chemial_type_put_in', 
                                            supplier='$supplier',
                                            date_time='$date_time', 
                                            put_in_person='$put_in_person', 
                                            delivery_person='$delivery_person', 
                                            voucher_id='$voucher_id',
                                            vat='$vat',
                                            typeput='$typeput',                                                        
                                            place='$place', 
                                            totalcost='$totalcost', 
                                            note='$note', 
                                            create_time=CURRENT_TIMESTAMP, 
                                            create_id='$user', 
                                            history='$history'
                                    WHERE put_in_id='$putin_id'";
//            echo $this->sql;
            return $this->Transact($this->sql);	
        }
        
        function deleteAllMedicineInPutInChemical($putin_id) {
	    global $db;
		if(!$putin_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_chemical_putin
					WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
        
        function deletePutInChemical($putin_id) {
	    global $db;
		if(!$putin_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_chemical_putin_info
					WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
        
        function listSomePutInChemSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
                                FROM $this->tb_chemical_putin_info ".$condition."  
                                LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function listAllPutInChemical($condition){
	    global $db;
		$this->sql="SELECT * 
                                FROM $this->tb_chemical_putin_info ".$condition;
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function getPutInChemicalInfo($nr){
	    global $db;
		$this->sql="SELECT * 
                                FROM $this->tb_chemical_putin_info
                                WHERE put_in_id='".$nr."' ";

	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
        
        function getDetailPutInChemicalInfo($nr){
	    global $db;
		$this->sql="SELECT putininfo.*,putin.*,putininfo.note AS generalnote, khochan.product_name, khochan.nuocsx, donvi.unit_name_of_chemical 
                                FROM $this->tb_chemical_putin_info AS putininfo, $this->tb_chemical_putin AS putin, care_chemical_products_main AS khochan, 
                                    care_chemical_unit_of_medicine AS donvi, care_currency AS currency 
                                WHERE putininfo.put_in_id='".$nr."' AND putin.put_in_id=putininfo.put_in_id 
                                AND khochan.product_encoder=putin.product_encoder 
                                AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                                AND khochan.unit_of_price=currency.item_no";
//                echo $this->sql;
                               // AND khochan.care_supplier=putininfo.supplier, currency.short_name 
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
        function countPutInChemicalItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_chemical_putin_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
        
        function getDetailChemicalPutInInfo($nr){
	    global $db;
            $this->sql="SELECT putininfo.*,putin.*,putininfo.note AS generalnote, khochan.product_name, khochan.price AS price1, khochan.nuocsx, donvi.unit_name_of_chemical  
                        FROM $this->tb_chemical_putin_info AS putininfo, $this->tb_chemical_putin AS putin, 
                            care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi  
                        WHERE putininfo.put_in_id='".$nr."' AND putin.put_in_id=putininfo.put_in_id 
                        AND khochan.product_encoder=putin.product_encoder 
                        AND donvi.unit_of_chemical=khochan.unit_of_chemical ";
//            echo $this->sql;
            if ($this->result=$db->Execute($this->sql)) {
                    if ($this->result->RecordCount()) {
                            return $this->result;
                    }else{return false;}
            }else{return false;}
	}
        
        function getDetailChemicalPayOutInfo($nr){
	    global $db;
            $this->sql="SELECT payoutinfo.*,payout.*,payoutinfo.note AS generalnote, khochan.product_name, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.price AS price1 
                        FROM $this->tb_chemical_payout_info AS payoutinfo, $this->tb_chemical_payout AS payout, 
                            care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi, care_currency As currency  
                        WHERE payoutinfo.pay_out_id='".$nr."' AND payout.pay_out_id=payoutinfo.pay_out_id 
                        AND khochan.product_encoder=payout.product_encoder 
                        AND khochan.unit_of_price=currency.item_no
                        AND donvi.unit_of_chemical=khochan.unit_of_chemical ";					
//            echo $this->sql;//, currency.short_name 
            if ($this->result=$db->Execute($this->sql)) {
                    if ($this->result->RecordCount()) {
                            return $this->result;
                    }else{return false;}
            }else{return false;}
	}
        
        function InsertPayOutChemicalInfo($chemical_type_pay_out, $placefrom, $date_time, $pay_out_person, 
                        $receiver, $voucher_id, $typeput, $note, $health_station, $totalcost)
        {
            global $db;
            $this->sql="INSERT INTO care_chemical_pay_out_info 
                            (chemical_type_pay_out, placefrom, date_time, pay_out_person, receiver, 
                                voucher_id, typeput, note, create_id, create_time, history, totalcost, health_station)
                        VALUES ($chemical_type_pay_out, '$placefrom', '$date_time','$pay_out_person', '$receiver', '$voucher_id', '$typeput', '$note', '$user',
                                CURRENT_TIMESTAMP, '$history','$totalcost', '$health_station' )";

            return $this->Transact($this->sql);	
        }
        
        function InsertChemicalPayOut($pay_out_id, $product_encoder, $lotid, $product_date, 
                $exp_date, $number_pay_out, $number_voucher, $price, $note)
        {
            global $db;
            $this->sql="INSERT INTO care_chemical_pay_out (id, pay_out_id, product_encoder, lotid, 
                            product_date, exp_date, number, number_voucher, price, note)		
                        VALUES (0, '$pay_out_id', '$product_encoder', '$lotid', '$product_date', '$exp_date', 
                            '$number_pay_out', '$number_voucher', '$price', '$note')";

            return $this->Transact($this->sql);	
        }
        
        function UpdateChemicalPayOutInfo($payout_id, $chemical_type_pay_out, $placefrom, $date_time, 
                            $pay_out_person, $receiver, $voucher_id, $typeput, $health_station, $totalcost, $note, $user, $history)
        {
            global $db;
            $this->sql="UPDATE care_chemical_pay_out_info 
                                SET chemical_type_pay_out='$chemical_type_pay_out', 
                                        placefrom='$placefrom',
                                        date_time='$date_time', 
                                        pay_out_person='$pay_out_person', 
                                        receiver='$receiver', 
                                        voucher_id='$voucher_id',
                                        typeput='$typeput',
                                        note='$note', 
                                        create_time=CURRENT_TIMESTAMP, 
                                        create_id='$user', 
                                        history='$history',
                                        health_station='$health_station', 
                                        totalcost=$totalcost 	
                                WHERE pay_out_id='$payout_id'";
            return $this->Transact($this->sql);
        }
        
        function deleteAllChemicalInPayOut($payout_id) {
	    global $db;
            if(!$payout_id) return FALSE;

            $this->sql="DELETE FROM $this->tb_chemical_payout
                            WHERE pay_out_id=$payout_id";
            return $this->Transact($this->sql);	
	}
        
        function deleteChemicalPayOut($payout_id) {
	    global $db;
		if(!$payout_id) return FALSE;

		$this->sql="DELETE FROM $this->tb_chemical_payout_info
                                WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}
        
        function getLastChemicalPayOutID(){
		global $db;
		$this->sql="SELECT MAX(pay_out_id) AS pay_out_id FROM $this->tb_chemical_payout_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
        
        function setReceiveChemicalInPutIn($id,$number){
	    global $db;
            if(!$id) return FALSE;
            //prescriprion_info
            $this->sql="UPDATE $this->tb_chemical_putin
                            SET number_voucher='$number'
                            WHERE id='$id'";
            return $this->Transact($this->sql);	
	}
        
        function getChemicalInPutIn($id){
            global $db;
            $this->sql="SELECT * FROM $this->tb_chemical_putin WHERE id='$id'";
            if ($this->result=$db->Execute($this->sql)) {
                if ($this->result->RecordCount()) {
                    return $this->result->FetchRow();
                    }else{return false;}
            }else{return false;}		
	}
        
        function getChemicalInPutInEncoder($product_encoder){
            global $db;
            $this->sql="SELECT * FROM $this->tb_chemical_putin WHERE product_encoder='$product_encoder'";
            if ($this->result=$db->Execute($this->sql)) {
                if ($this->result->RecordCount()) {
                    return $this->result->FetchRow();
                    }else{return false;}
            }else{return false;}		
	}
        
        function setChemicalInfoPutInWhenAccept($putin_id,$put_in_person,$totalcost,$user_accept,$hoidongkiemnhap, $ngaynhap, $hinhthucthanhtoan){
	    global $db;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_putin_info 
                                SET put_in_person='$put_in_person', totalcost='$totalcost', user_accept='$user_accept', hoidongkiemnhap='$hoidongkiemnhap', ngaynhap='$ngaynhap', hinhthucthanhtoan='$hinhthucthanhtoan'				 
                                WHERE put_in_id='$putin_id'";
		return $this->Transact($this->sql);		
	}
        
        function setChemicalPutInStatusFinish($putin_id,$status) {
	    global $db;
		if(!$putin_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_putin_info
                                SET status_finish='$status'
                                WHERE put_in_id=$putin_id";
		return $this->Transact($this->sql);	
	}
        
        function countChemicalPayOutItems($condition)
	{
		global $db;
		$this->sql="SELECT COUNT(*) AS sum_item FROM $this->tb_chemical_payout_info ".$condition;
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
        
        function listSomeChemicalPayOutSplitPage($current_page,$number_items_per_page,$condition){
	    global $db;
		$current_page=intval($current_page);
		$number_items_per_page=intval($number_items_per_page);
		
		$start_from =($current_page-1)*$number_items_per_page; 
		
		$this->sql="SELECT * 
                            FROM $this->tb_chemical_payout_info ".$condition."  
                            LIMIT $start_from,$number_items_per_page";
					
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function listAllChemicalPayOut($condition){
	    global $db;
		$this->sql="SELECT * 
                            FROM $this->tb_chemical_payout_info ".$condition;

	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        function getChemicalPayOutInfo($nr){
	    global $db;
            $this->sql="SELECT * 
                        FROM $this->tb_chemical_payout_info
                        WHERE pay_out_id='".$nr."'";
	    if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
        
        function getchemicalInPayOut($id){
		global $db;
		$this->sql="SELECT * FROM $this->tb_chemical_payout WHERE id='$id'";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
        
        function setReceiveChemicalInPayOut($id,$number){
	    global $db;
		if(!$id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_payout
                            SET number_voucher='$number'
                            WHERE id='$id'";

		return $this->Transact($this->sql);	
	}
        
        function setInfoChemicalPayOutWhenAccept($payout_id,$user_receive,$totalcost,$user_accept){
	    global $db;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_payout_info 
                            SET receive='$user_receive', totalcost='$totalcost', user_accept='$user_accept' 
                            WHERE pay_out_id='$payout_id'";
//                echo $this->sql;
		return $this->Transact($this->sql);		
	}
        
        function setChemicalPayOutStatusFinish($payout_id,$status) {
	    global $db;
		if(!$payout_id) return FALSE;
		//prescriprion_info
		$this->sql="UPDATE $this->tb_chemical_payout_info
                            SET status_finish='$status'
                            WHERE pay_out_id=$payout_id";
		return $this->Transact($this->sql);	
	}
        
        #Bao cao nhap xuat ton
	//Kiem tra co bao cao nao chua
	function checkAnyReportChemical($condition){
		global $db;
		$this->sql="SELECT *, MONTH(monthreport) AS getmonth, YEAR(monthreport) AS getyear 
                            FROM care_chemical_khochan_report
							 ".$condition." 
                            ORDER BY monthreport";
//                echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}	
	}
        //Kiem tra co nhap lo hang nao chua
	function checkAnyChemicalPutIn($condition){
		global $db;
		$this->sql="SELECT *, MONTH(date_time) AS getmonth, YEAR(date_time) AS getyear 
                            FROM $this->tb_chemical_putin_info
							WHERE status_finish=1
							 ".$condition." 
                            ORDER BY date_time";
//                echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}			
	}
        //Bao cao ton thang da luu
	function Khochan_hoachat_tonthangtruoc($month, $year, $condition){
            global $db;
            $this->sql="SELECT re.*, main.product_name, unit.unit_name_of_chemical 
                        FROM care_chemical_khochan_report AS re, care_chemical_products_main AS main, care_chemical_unit_of_medicine AS unit, care_currency AS currency
                        WHERE MONTH(re.monthreport)='$month' AND YEAR(re.monthreport)='$year' 
                        AND main.product_encoder=re.product_encoder 
                        AND main.unit_of_chemical=unit.unit_of_chemical 
						 ".$condition." 
                        AND main.unit_of_price=currency.item_no
                        ORDER BY main.product_name";//, currency.short_name
            if ($this->result=$db->Execute($this->sql)) {
                    if ($this->result->RecordCount()) {				
                            return $this->result;
                    }else{return false;}
            }else{return false;}	
	}
        
        //Nhap-xuat-ton kho chan, return: product_encoder, product_name, unit_name_of_medicine, nhap, gianhap, xuat, giaxuat
	function Khochan_baocaohoahchat_nhapxuatton($month, $year, $cond_typeput){
		global $db;
		$this->sql="SELECT tb1.product_encoder, tb1.product_name, tb1.unit_name_of_chemical, tb1.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat
                            FROM (	
                                    SELECT putin.product_encoder, main.product_name, main.price, unit.unit_name_of_chemical, 
                                            SUM(putin.number_voucher) AS nhap, putin.lotid, putin.exp_date, putin.price AS gianhap, currency.short_name
                                    FROM care_chemical_put_in AS putin, care_chemical_put_in_info AS putininfo, 
                                            care_chemical_products_main AS main, care_chemical_unit_of_medicine AS unit, care_currency AS currency
                                    WHERE putin.product_encoder = main.product_encoder 
                                            AND main.unit_of_chemical=unit.unit_of_chemical
                                            AND putininfo.put_in_id=putin.put_in_id
											 ".$cond_typeput." 
                                            AND putininfo.status_finish='1'
                                            AND currency.item_no=main.unit_of_price
                                            AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
                                    GROUP BY putin.product_encoder) AS tb1
                                    LEFT JOIN (
                                                SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_chemical, 
                                                        SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat, currency.short_name 
                                                FROM care_chemical_pay_out AS payout, care_chemical_pay_out_info AS payoutinfo, 
                                                        care_chemical_products_main AS main, care_chemical_unit_of_medicine AS unit, care_currency AS currency
                                                WHERE payout.product_encoder = main.product_encoder 
                                                        AND main.unit_of_chemical=unit.unit_of_chemical
                                                        AND payoutinfo.pay_out_id=payout.pay_out_id
														 ".$cond_typeput." 
                                                        AND payoutinfo.status_finish='1'
                                                        AND currency.item_no=main.unit_of_price
                                                        AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
                                                GROUP BY payout.product_encoder) AS tb2
                                    ON tb1.product_encoder=tb2.product_encoder
                                    UNION
                                    SELECT tb2.product_encoder, tb2.product_name, tb2.unit_name_of_chemical, tb2.price, tb1.nhap, tb1.gianhap, tb1.lotid, tb1.exp_date, tb2.xuat, tb2.giaxuat
                                    FROM (	
                                            SELECT putin.product_encoder, main.product_name, main.price, unit.unit_name_of_chemical, 
                                                    SUM(putin.number_voucher) AS nhap, putin.lotid, putin.exp_date, putin.price AS gianhap, currency.short_name
                                            FROM care_chemical_put_in AS putin, care_chemical_put_in_info AS putininfo, care_chemical_products_main AS main, 
                                                care_chemical_unit_of_medicine AS unit, care_currency AS currency
                                            WHERE putin.product_encoder = main.product_encoder 
                                                    AND main.unit_of_chemical=unit.unit_of_chemical
                                                    AND putininfo.put_in_id=putin.put_in_id
													 ".$cond_typeput." 
                                                    AND putininfo.status_finish='1'
                                                    AND currency.item_no=main.unit_of_price
                                                    AND MONTH(putininfo.date_time)='$month' AND YEAR(putininfo.date_time)='$year'
                                            GROUP BY putin.product_encoder) AS tb1
                                    RIGHT JOIN (
                                                SELECT payout.product_encoder, main.product_name, main.price, unit.unit_name_of_chemical, 
                                                        SUM(payout.number_voucher) AS xuat, payout.price AS giaxuat, currency.short_name 
                                                FROM care_chemical_pay_out AS payout, care_chemical_pay_out_info AS payoutinfo, 
                                                        care_chemical_products_main AS main, care_chemical_unit_of_medicine AS unit, care_currency AS currency
                                                WHERE payout.product_encoder = main.product_encoder 
                                                        AND main.unit_of_chemical=unit.unit_of_chemical
                                                        AND payoutinfo.pay_out_id=payout.pay_out_id
														 ".$cond_typeput." 
                                                        AND payoutinfo.status_finish='1'
                                                        AND currency.item_no=main.unit_of_price
                                                        AND MONTH(payoutinfo.date_time)='$month' AND YEAR(payoutinfo.date_time)='$year'
                                                GROUP BY payout.product_encoder) AS tb2
                                    ON tb1.product_encoder=tb2.product_encoder
                                    WHERE tb1.product_encoder IS NULL
                                    ORDER BY product_name";
//		echo $this->sql;//, tb1.short_name
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
        
        //Ton truoc cua 1 encoder
	function Khochan_hoachat_tontruoc($encoder, $condition){
		global $db;
		$this->sql="SELECT toninfo.fromdate, toninfo.todate, ton.*, SUM(ton.number) AS last_number, ton.price AS last_cost 
					FROM care_chemical_khochan_ton AS ton, care_chemical_khochan_ton_info AS toninfo 
					WHERE ton.product_encoder='$encoder' 
					AND ton.ton_id=toninfo.id
					 ".$condition." 
					GROUP BY ton.product_encoder, ton.ton_id  
					ORDER BY toninfo.yearreport, toninfo.monthreport DESC";
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}
	}
	function Khochan_hoachat_luubaocao($product_encoder, $monthreport, $first_number, $putin, $payout, $last_number, $last_cost, $user_report, $typeput){
		global $db;
		$this->sql="INSERT INTO care_chemical_khochan_report (product_encoder, monthreport, 
		first_number, putin, payout, last_number, last_cost, create_time, user_report)
				VALUES ('$product_encoder', '$monthreport', '$first_number', '$putin', '$payout', '$last_number', '$last_cost', CURRENT_TIMESTAMP, '$user_report', '$typeput')";
		return $this->Transact($this->sql);
	}	
	function Khochan_HC_thekho($encoder, $fromday, $today, $cond_typeput){
		global $db;
		//format days to yyyy-mm-dd
		
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
		
		$this->sql="SELECT DATE(putininfo.date_time) AS ngay, putininfo.put_in_id AS manhap, SPACE(10) AS maxuat, 	
			putininfo.voucher_id, putininfo.supplier AS lydo, 
			putin.product_encoder, putin.number_voucher, putin.price, putin.lotid, putin.exp_date
		FROM care_chemical_put_in_info AS putininfo, care_chemical_put_in AS putin
		WHERE putin.put_in_id=putininfo.put_in_id
			AND status_finish='1' ".$cond_typeput."  
			AND DATE(putininfo.date_time)>='$fromday' AND DATE(putininfo.date_time)<='$today'
			AND putin.product_encoder='$encoder'
		UNION	
		SELECT DATE(payoutinfo.date_time) AS ngay, SPACE(10) AS manhap, payoutinfo.pay_out_id AS maxuat, 	
			payoutinfo.voucher_id, payoutinfo.health_station AS lydo,
			payout.product_encoder, payout.number_voucher, payout.price, payout.lotid, payout.exp_date
		FROM care_chemical_pay_out_info AS payoutinfo, care_chemical_pay_out AS payout
		WHERE payout.pay_out_id=payoutinfo.pay_out_id
			AND status_finish='1' ".$cond_typeput." 
			AND DATE(payoutinfo.date_time)>='$fromday' AND DATE(payoutinfo.date_time)<='$today'
			AND payout.product_encoder='$encoder'
		ORDER BY ngay ";
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function Khochan_HC_tongnhapxuat_theongay($encoder, $fromday, $today, $cond_typeput, $flag_equal){
		global $db;
		//Test format fromday
		if (strpos($fromday,'-')<3) {
			list($f_day,$f_month,$f_year) = explode("-",$fromday);
			$fromday=$f_year.'-'.$f_month.'-'.$f_day;
		}
		//Test format today
		if (strpos($today,'-')<3) {
			list($t_day,$t_month,$t_year) = explode("-",$today);
			$today=$t_year.'-'.$t_month.'-'.$t_day;
		}			
		//Test fromday & today
		if(($f_year!=$t_year)||($f_month!=$t_month)||($f_day>$t_day))
			return FALSE;
			
		if($flag_equal)	$sign = "=";
		else $sign = "";	
			
		$this->sql="SELECT SUM(putin.number_voucher) AS tongnhap, SPACE(10) AS tongxuat 
					FROM care_chemical_put_in_info AS putininfo, care_chemical_put_in AS putin
					WHERE putin.put_in_id=putininfo.put_in_id
						AND status_finish='1' ".$cond_typeput."  
						AND DATE(putininfo.date_time)>".$sign."'$fromday' AND DATE(putininfo.date_time)<".$sign."'$today'
						AND putin.product_encoder='$encoder'
					GROUP BY putin.product_encoder 	
					UNION	
					SELECT SPACE(10) AS tongnhap, SUM(payout.number_voucher) AS tongxuat
					FROM care_chemical_pay_out_info AS payoutinfo, care_chemical_pay_out AS payout
					WHERE payout.pay_out_id=payoutinfo.pay_out_id
						AND status_finish='1' ".$cond_typeput." 
						AND DATE(payoutinfo.date_time)>".$sign."'$fromday' AND DATE(payoutinfo.date_time)<".$sign."'$today'
						AND payout.product_encoder='$encoder'
					GROUP BY payout.product_encoder";
			
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}		
	
	//------------------------------------------ Cac bao cao thuoc khac ---------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------
	function Khochan_thuoc_nhapxuatton($typedongtay, $cond_typeput, $month, $year){
		global $db;
		if ($month>1){
			$ton_month = $month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		switch($typedongtay){
			case 'tayy': $view_ton ='view_thuoc_ton'; 
						$dongtayy =' AND main.pharma_type IN (1,2,3) ';
						$dongtayy_1 = ' AND pharma_type IN (1,2,3) ';
						break;	
			case 'dongy': $view_ton ='view_thuoc_ton_dongy'; 
						$dongtayy = ' AND main.pharma_type IN (4,8,9,10) ';
						$dongtayy_1 = ' AND pharma_type IN (4,8,9,10) ';
						break;	
		}		

        $this->sql = "SELECT DISTINCT source.monthreport,unit.unit_name_of_medicine, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name
			FROM  ( SELECT *
					  FROM $view_ton WHERE $view_ton.monthreport='$ton_month' AND $view_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE view_thuoc_nhap.monthreport='$ton_month' AND view_thuoc_nhap.yearreport='$ton_year'  AND pharma_type IN (1,2,3)
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE view_thuoc_xuat.monthreport='$ton_month' AND view_thuoc_xuat.yearreport='$ton_year'  AND pharma_type IN (1,2,3)
					) AS source
			LEFT JOIN $view_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport='$ton_month' AND N.yearreport='$ton_year'
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='$ton_month' AND X.yearreport='$ton_year'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder  AND main.pharma_type IN (1,2,3)
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY source.product_encoder";

       /*SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat,
 T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name
			FROM  ( SELECT *
					  FROM view_thuoc_ton WHERE view_thuoc_ton.monthreport='01' AND view_thuoc_ton.yearreport='2013'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE view_thuoc_nhap.monthreport='01' AND view_thuoc_nhap.yearreport='2013'  AND pharma_type IN (1,2,3)
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE view_thuoc_xuat.monthreport='01' AND view_thuoc_xuat.yearreport='2013'  AND pharma_type IN (1,2,3)
					) AS source
			LEFT JOIN view_thuoc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='01' AND X.yearreport='2013'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder  AND main.pharma_type IN (1,2,3)
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY source.product_encoder */

	//	echo $this->sql;
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	//Thong ke theo NHAP XUAT TON
	function Khochan_sudungthuoc_thang($tayy_dongy, $pharma_group_id, $month, $year){
		global $db;
		if ($month>1){
			$ton_month = $month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		switch ($tayy_dongy){
			case 'dongy': $cond_dongtay = ' AND main.pharma_type IN (4,8,9,10) '; break;
			case 'tayy': $cond_dongtay = ' AND main.pharma_type IN (1,2,3) '; break;
			default: $cond_dongtay ='';
		}
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_thuoc_ton WHERE view_thuoc_ton.monthreport='$ton_month' AND view_thuoc_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE view_thuoc_nhap.monthreport>='$from_month' AND view_thuoc_nhap.monthreport<='$to_month' AND view_thuoc_nhap.yearreport='$year'
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE view_thuoc_xuat.monthreport>='$from_month' AND view_thuoc_xuat.monthreport<='$to_month' AND view_thuoc_xuat.yearreport='$year'
					) AS source
			LEFT JOIN view_thuoc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport='$month' AND N.yearreport='$year'
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='$month' AND X.yearreport='$year'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder ".$cond_dongtay."
			JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id AND genericgroup.pharma_group_id IN ($pharma_group_id)	
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
				
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	//Thong ke theo NHAP XUAT TON
	function Khochan_sudungthuoc_nhieuthang($tayy_dongy, $pharma_group_id, $from_month, $to_month, $year){
		global $db;
				
		switch ($tayy_dongy){
			case 'dongy': $cond_dongtay = ' AND main.pharma_type IN (4,8,9,10) '; break;
			case 'tayy': $cond_dongtay = ' AND main.pharma_type IN (1,2,3) '; break;
			default: $cond_dongtay ='';
		}	
		$temp_sql = "SELECT MAX(monthreport) AS ton_month FROM view_thuoc_ton 
					WHERE yearreport='$year' AND monthreport<(SELECT MIN(monthreport) FROM (
						SELECT monthreport FROM view_thuoc_nhap WHERE monthreport>='$from_month' AND monthreport<='$to_month' AND yearreport='$year'
						UNION
						SELECT monthreport FROM view_thuoc_xuat WHERE monthreport>='$from_month' AND monthreport<='$to_month' AND yearreport='$year') AS table1 ) ";
		if($tempresult = $db->Execute($temp_sql)){
			$tontemp=$tempresult->FetchRow();
			$ton_month =  $tontemp['ton_month'];
			$ton_year = $year;
		}else if ($from_month>1){
			$ton_month = $from_month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_thuoc_ton WHERE view_thuoc_ton.monthreport='$ton_month' AND view_thuoc_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE view_thuoc_nhap.monthreport>='$from_month' AND view_thuoc_nhap.monthreport<='$to_month' AND view_thuoc_nhap.yearreport='$year'
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE view_thuoc_xuat.monthreport>='$from_month' AND view_thuoc_xuat.monthreport<='$to_month' AND view_thuoc_xuat.yearreport='$year'
					) AS source
			LEFT JOIN view_thuoc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport>='$from_month' AND N.monthreport<='$to_month' AND N.yearreport='$year'
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport>='$from_month' AND X.monthreport<='$to_month' AND X.yearreport='$year'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder ".$cond_dongtay."
			JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id AND genericgroup.pharma_group_id IN ($pharma_group_id)	
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
				
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	//Chi thong ke theo XUAT
	function Khochan_sudungthuockhac_thang($tayy_dongy, $pharma_group_id, $nuocsx, $month, $year){
		global $db;
		switch ($tayy_dongy){
			case 'dongy': $cond_dongtay = ' AND main.pharma_type IN (4,8,9,10) '; break;
			case 'tayy': $cond_dongtay = ' AND main.pharma_type IN (1,2,3) '; break;
			default: $cond_dongtay ='';
		}
		if($pharma_group_id>0){
			$cond_groupid = ' AND genericgroup.pharma_group_id IN ('.$pharma_group_id.') ';
		}else $cond_groupid = '';
		
		if($nuocsx=='noi'){
			$cond_nuocsx=" AND main.nuocsx LIKE '%V%N%' ";
		}else if ($nuocsx=='ngoai'){
			$cond_nuocsx=" AND main.nuocsx NOT LIKE '%V%N%' ";
		}else $cond_nuocsx='';
		
		$this->sql="SELECT X.monthreport, X.yearreport, X.product_encoder, X.number AS xuat, X.price AS giaxuat, main.product_name, main.nuocsx, unit.unit_name_of_medicine 
		FROM view_thuoc_xuat AS X  
		JOIN care_pharma_products_main AS main ON main.product_encoder = X.product_encoder ".$cond_dongtay." ".$cond_nuocsx." AND X.monthreport='$month' AND X.yearreport='$year'
		JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id ".$cond_groupid."	
		JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
		ORDER BY main.product_name, X.product_encoder, X.monthreport ";
		
		//echo $this->sql;
				
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	//Chi thong ke theo XUAT
	function Khochan_sudungthuockhac_nhieuthang($tayy_dongy, $pharma_group_id, $nuocsx, $from_month, $to_month, $year){
		global $db;
		switch ($tayy_dongy){
			case 'dongy': $cond_dongtay = ' AND main.pharma_type IN (4,8,9,10) '; break;
			case 'tayy': $cond_dongtay = ' AND main.pharma_type IN (1,2,3) '; break;
			default: $cond_dongtay ='';
		}
		if($pharma_group_id>0){
			$cond_groupid = ' AND genericgroup.pharma_group_id IN ('.$pharma_group_id.') ';
		}else $cond_groupid = '';
		
		if($nuocsx=='noi'){
			$cond_nuocsx=" AND main.nuocsx LIKE '%V%N%' ";
		}else if ($nuocsx=='ngoai'){
			$cond_nuocsx=" AND main.nuocsx NOT LIKE '%V%N%' ";
		}else $cond_nuocsx='';
		
		$this->sql="SELECT X.monthreport, X.yearreport, X.product_encoder, X.number AS xuat, X.price AS giaxuat, main.product_name, main.nuocsx, unit.unit_name_of_medicine 
		FROM view_thuoc_xuat AS X  
		JOIN care_pharma_products_main AS main ON main.product_encoder = X.product_encoder ".$cond_dongtay." ".$cond_nuocsx." AND X.monthreport>='$from_month' AND X.monthreport<='$to_month' AND X.yearreport='$year'
		JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id ".$cond_groupid."	
		JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
		ORDER BY main.product_name, X.product_encoder, X.monthreport ";	
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	

	function Khochan_sudungthuockhangsinh_thang($tayy_dongy, $pharma_group_id, $nuocsx, $month, $year){
		global $db;
		switch ($tayy_dongy){
			case 'dongy': $cond_dongtay = ' AND main.pharma_type IN (4,8,9,10) '; break;
			case 'tayy': $cond_dongtay = ' AND main.pharma_type IN (1,2,3) '; break;
			default: $cond_dongtay ='';
		}
		if($pharma_group_id>0){
			$cond_groupid = ' AND genericgroup.pharma_group_id IN ('.$pharma_group_id.') ';
		}else $cond_groupid = '';
		
		if($nuocsx=='noi'){
			$cond_nuocsx=" AND main.nuocsx LIKE '%V%N%' ";
		}else if ($nuocsx=='ngoai'){
			$cond_nuocsx=" AND main.nuocsx NOT LIKE '%V%N%' ";
		}else $cond_nuocsx='';
		
		$this->sql="SELECT X.monthreport, X.yearreport, X.product_encoder, X.number AS xuat, X.price AS giaxuat, genericgroup.pharma_generic_drug_id, genericgroup.generic_drug, genericgroup.ATC,  main.product_name, main.content, main.nuocsx, main.using_type, unit.unit_name_of_medicine 
		FROM view_thuoc_xuat AS X  
		JOIN care_pharma_products_main AS main ON main.product_encoder = X.product_encoder ".$cond_dongtay." ".$cond_nuocsx." AND X.monthreport='$month' AND X.yearreport='$year'
		JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id ".$cond_groupid."	
		JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
		ORDER BY genericgroup.pharma_generic_drug_id, main.product_name, X.product_encoder ";
		
		//echo $this->sql;
				
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	function Khochan_sudungthuockhangsinh_nhieuthang($tayy_dongy, $pharma_group_id, $nuocsx, $from_month, $to_month, $year){
		global $db;
		switch ($tayy_dongy){
			case 'dongy': $cond_dongtay = ' AND main.pharma_type IN (4,8,9,10) '; break;
			case 'tayy': $cond_dongtay = ' AND main.pharma_type IN (1,2,3) '; break;
			default: $cond_dongtay ='';
		}
		if($pharma_group_id>0){
			$cond_groupid = ' AND genericgroup.pharma_group_id IN ('.$pharma_group_id.') ';
		}else $cond_groupid = '';
		
		if($nuocsx=='noi'){
			$cond_nuocsx=" AND main.nuocsx LIKE '%V%N%' ";
		}else if ($nuocsx=='ngoai'){
			$cond_nuocsx=" AND main.nuocsx NOT LIKE '%V%N%' ";
		}else $cond_nuocsx='';
		
		$this->sql="SELECT X.monthreport, X.yearreport, X.product_encoder, X.number AS xuat, X.price AS giaxuat, genericgroup.pharma_generic_drug_id, genericgroup.generic_drug, genericgroup.ATC, main.product_name, main.content, main.nuocsx, main.using_type, unit.unit_name_of_medicine 
		FROM view_thuoc_xuat AS X  
		JOIN care_pharma_products_main AS main ON main.product_encoder = X.product_encoder ".$cond_dongtay." ".$cond_nuocsx." AND X.monthreport>='$from_month' AND X.monthreport<='$to_month' AND X.yearreport='$year'
		JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id ".$cond_groupid."	
		JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
		ORDER BY genericgroup.pharma_generic_drug_id, main.product_name, X.product_encoder ";	
		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	//Thong ke theo NHAP XUAT TON
	function Khochan_sudungthuocdacbiet_thang($nhomdacbiet, $month, $year){
		global $db;
		if ($month>1){
			$ton_month = $month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		if($nhomdacbiet!=''){
			$cond_nhomdacbiet = " AND main.nhomdacbiet ='".$nhomdacbiet."'";
		}else $cond_nhomdacbiet='';
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_thuoc_ton WHERE view_thuoc_ton.monthreport='$ton_month' AND view_thuoc_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE view_thuoc_nhap.monthreport>='$from_month' AND view_thuoc_nhap.monthreport<='$to_month' AND view_thuoc_nhap.yearreport='$year'
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE view_thuoc_xuat.monthreport>='$from_month' AND view_thuoc_xuat.monthreport<='$to_month' AND view_thuoc_xuat.yearreport='$year'
					) AS source
			LEFT JOIN view_thuoc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport='$month' AND N.yearreport='$year'
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='$month' AND X.yearreport='$year'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder  AND main.pharma_type IN (1,2,3) ".$cond_nhomdacbiet."	
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
				
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	//Thong ke theo NHAP XUAT TON
	function Khochan_sudungthuocdacbiet_nhieuthang($nhomdacbiet, $from_month, $to_month, $year){
		global $db;
		$temp_sql = "SELECT MAX(monthreport) AS ton_month FROM view_thuoc_ton 
					WHERE yearreport='$year' AND monthreport<(SELECT MIN(monthreport) FROM (
						SELECT monthreport FROM view_thuoc_nhap WHERE monthreport>='$from_month' AND monthreport<='$to_month' AND yearreport='$year'
						UNION
						SELECT monthreport FROM view_thuoc_xuat WHERE monthreport>='$from_month' AND monthreport<='$to_month' AND yearreport='$year') AS table1 ) ";
		if($tempresult = $db->Execute($temp_sql)){
			$tontemp=$tempresult->FetchRow();
			$ton_month =  $tontemp['ton_month'];
			$ton_year = $year;
		}else if ($from_month>1){
			$ton_month = $from_month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		if($nhomdacbiet!=''){
			$cond_nhomdacbiet = " AND main.nhomdacbiet ='".$nhomdacbiet."'";
		}else $cond_nhomdacbiet='';
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_thuoc_ton WHERE view_thuoc_ton.monthreport='$ton_month' AND view_thuoc_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE view_thuoc_nhap.monthreport>='$from_month' AND view_thuoc_nhap.monthreport<='$to_month' AND view_thuoc_nhap.yearreport='$year'
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE view_thuoc_xuat.monthreport>='$from_month' AND view_thuoc_xuat.monthreport<='$to_month' AND view_thuoc_xuat.yearreport='$year'
					) AS source
			LEFT JOIN view_thuoc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport>='$from_month' AND N.monthreport<='$to_month' AND N.yearreport='$year'
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport>='$from_month' AND X.monthreport<='$to_month' AND X.yearreport='$year'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder AND main.pharma_type IN (1,2,3) ".$cond_nhomdacbiet."
			JOIN care_pharma_generic_drug AS genericgroup ON main.pharma_generic_drug_id = genericgroup.pharma_generic_drug_id AND genericgroup.pharma_group_id IN ($pharma_group_id)	
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
				
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
	//Kiem ke
	function Khochan_thuoc_nhapxuatton_theongay($typedongtay, $cond_typeput, $last_date_report, $date_show){
		global $db;
		switch($typedongtay){
			case 'tayy': $view_ton ='view_thuoc_ton'; 
						$dongtayy =' AND main.pharma_type IN (1,2,3) ';
						$dongtayy_1 = ' AND pharma_type IN (1,2,3) ';
						break;	
			case 'dongy': $view_ton ='view_thuoc_ton_dongy'; 
						$dongtayy = ' AND main.pharma_type IN (4,8,9,10) ';
						$dongtayy_1 = ' AND pharma_type IN (4,8,9,10) ';
						break;	
		}		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, 
					N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat,
					main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM ".$view_ton." WHERE DATE(date_time)='$last_date_report'
					  UNION
					  SELECT *
					  FROM view_thuoc_nhap WHERE DATE(view_thuoc_nhap.date_time)<='$date_show' AND DATE(view_thuoc_nhap.date_time)>'$last_date_report'  ".$dongtayy_1."
					  UNION
					  SELECT *
					  FROM view_thuoc_xuat WHERE DATE(view_thuoc_xuat.date_time)<='$date_show' AND DATE(view_thuoc_xuat.date_time)>'$last_date_report'  ".$dongtayy_1."
					) AS source
			LEFT JOIN ".$view_ton." AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND DATE(T.date_time)='$last_date_report'
			LEFT JOIN view_thuoc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND DATE(N.date_time)<='$date_show' AND DATE(N.date_time)>'$last_date_report'
			LEFT JOIN view_thuoc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND DATE(X.date_time)<='$date_show' AND DATE(X.date_time)>'$last_date_report'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder ".$cond_typeput." ".$dongtayy."
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			AND (T.number>0 OR N.number>0 OR X.number>0) 
			ORDER BY main.product_name, source.product_encoder, source.monthreport, source.date_time, T.price, N.price, X.price ";
		//echo $this->sql;		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function Khochan_thuoc_luutonkho($typedongtay, $fromdate, $todate, $monthreport, $yearreport, $typeput){
		global $db;
		switch($typedongtay){
			case 'tayy': $tbl_ton_info ='care_pharma_khochan_ton_info';  break;	
			case 'dongy': $tbl_ton_info ='care_pharma_khochan_dongy_ton_info'; break;	
		}	
		$this->sql="INSERT INTO ".$tbl_ton_info." (id, fromdate, todate, monthreport, yearreport, typeput)
				VALUES ('', '$fromdate', '$todate', '$monthreport', '$yearreport', '$typeput')";
		return $this->Transact($this->sql);
	}
	function Khochan_thuoc_updatetonkho($typedongtay, $id, $fromdate, $todate, $monthreport, $yearreport, $typeput){
		global $db;
		switch($typedongtay){
			case 'tayy': $tbl_ton_info ='care_pharma_khochan_ton_info';  break;	
			case 'dongy': $tbl_ton_info ='care_pharma_khochan_dongy_ton_info'; break;	
		}			
		$this->sql="UPDATE ".$tbl_ton_info." 
					SET 
					fromdate = '$fromdate' , 
					todate = '$todate' , 
					monthreport = '$monthreport' , 
					yearreport = '$yearreport' , 
					typeput = '$typeput'					
					WHERE
					id = '$id' ";
		return $this->Transact($this->sql);
	}
	function Khochan_thuoc_luutonkho_chitiet($typedongtay, $ton_id, $product_encoder, $lotid, $typeput, $exp_date, $number, $price){
		global $db;
		switch($typedongtay){
			case 'tayy': $tbl_ton ='care_pharma_khochan_ton';  break;	
			case 'dongy': $tbl_ton ='care_pharma_khochan_dongy_ton'; break;	
		}			
		$this->sql="INSERT INTO ".$tbl_ton." (id, ton_id, product_encoder, lotid, typeput, exp_date, number, price, create_time)
				VALUES ('', '$ton_id', '$product_encoder', '$lotid', '$typeput', '$exp_date', '$number', '$price', CURRENT_TIMESTAMP)";
		return $this->Transact($this->sql);
	}
	function getLastTonKhoID($typedongtay){
		global $db;
		switch($typedongtay){
			case 'tayy': $tbl_ton_info ='care_pharma_khochan_ton_info';  break;	
			case 'dongy': $tbl_ton_info ='care_pharma_khochan_dongy_ton_info'; break;	
		}			
		$this->sql="SELECT MAX(id) AS ton_id FROM ".$tbl_ton_info;
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}
	function deleteAllMedicineInTonKho($typedongtay, $ton_id) {
	    global $db;
		if(!$ton_id) return FALSE;
		
		switch($typedongtay){
			case 'tayy': $tbl_ton ='care_pharma_khochan_ton';  break;	
			case 'dongy': $tbl_ton ='care_pharma_khochan_dongy_ton'; break;	
		}	
		$this->sql="DELETE FROM ".$tbl_ton." 
					WHERE ton_id=$ton_id";
		return $this->Transact($this->sql);	
	}	

	function Khochan_vtyt_nhapxuatton_theongay($condition, $cond_typeput, $last_date_report, $date_show){
		global $db;
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_vtyt_ton WHERE DATE(view_vtyt_ton.date_time)='$last_date_report' 
					  UNION
					  SELECT *
					  FROM view_vtyt_nhap WHERE DATE(view_vtyt_nhap.date_time)<='$date_show' AND DATE(view_vtyt_nhap.date_time)>'$last_date_report' 
					  UNION
					  SELECT *
					  FROM view_vtyt_xuat WHERE DATE(view_vtyt_xuat.date_time)<='$date_show' AND DATE(view_vtyt_xuat.date_time)>'$last_date_report'
					) AS source
			LEFT JOIN view_vtyt_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND DATE(T.date_time)='$last_date_report'
			LEFT JOIN view_vtyt_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND DATE(N.date_time)<='$date_show' AND DATE(N.date_time)>'$last_date_report'
			LEFT JOIN view_vtyt_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND DATE(X.date_time)<='$date_show' AND DATE(X.date_time)>'$last_date_report'
			JOIN care_med_products_main AS main ON main.product_encoder = source.product_encoder ".$condition." ".$cond_typeput." 
			JOIN care_med_unit_of_medipot AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			AND (T.number>0 OR N.number>0 OR X.number>0)
			ORDER BY main.product_name, source.product_encoder, source.monthreport, source.date_time, T.price, N.price, X.price ";
		//echo $this->sql;		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function Khochan_vtyt_luutonkho($fromdate, $todate, $monthreport, $yearreport, $typeput){
		global $db;
		$this->sql="INSERT INTO care_med_khochan_ton_info (id, fromdate, todate, monthreport, yearreport, typeput)
				VALUES ('', '$fromdate', '$todate', '$monthreport', '$yearreport', '$typeput')";
		return $this->Transact($this->sql);
	}	
	function Khochan_vtyt_updatetonkho($id, $fromdate, $todate, $monthreport, $yearreport, $typeput){
		global $db;
		$this->sql="UPDATE care_med_khochan_ton_info 
					SET 
					fromdate = '$fromdate' , 
					todate = '$todate' , 
					monthreport = '$monthreport' , 
					yearreport = '$yearreport' , 
					typeput = '$typeput'					
					WHERE
					id = '$id' ";
		return $this->Transact($this->sql);
	}
	function deleteAllMedipotInTonKho($ton_id) {
	    global $db;
		if(!$ton_id) return FALSE;

		$this->sql="DELETE FROM care_med_khochan_ton
					WHERE ton_id=$ton_id";
		return $this->Transact($this->sql);	
	}	
	function Khochan_vtyt_luutonkho_chitiet($ton_id, $product_encoder, $lotid, $typeput, $exp_date, $number, $price){
		global $db;
		$this->sql="INSERT INTO care_med_khochan_ton (id, ton_id, product_encoder, lotid, typeput, exp_date, number, price, create_time)
				VALUES ('', '$ton_id', '$product_encoder', '$lotid', '$typeput', '$exp_date', '$number', '$price', CURRENT_TIMESTAMP)";
		return $this->Transact($this->sql);
	}
	function getLastTonKhovtytID(){
		global $db;
		$this->sql="SELECT MAX(id) AS ton_id FROM care_med_khochan_ton_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}	
	function Khochan_vtyt_nhapxuatton($cond_typeput, $month, $year){
		global $db;
		if ($month>1){
			$ton_month = $month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_vtyt_ton WHERE view_vtyt_ton.monthreport='$ton_month' AND view_vtyt_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_vtyt_nhap WHERE view_vtyt_nhap.monthreport='$month' AND view_vtyt_nhap.yearreport='$year'
					  UNION
					  SELECT *
					  FROM view_vtyt_xuat WHERE view_vtyt_xuat.monthreport='$month' AND view_vtyt_xuat.yearreport='$year'
					) AS source
			LEFT JOIN view_vtyt_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_vtyt_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport='$month' AND N.yearreport='$year'
			LEFT JOIN view_vtyt_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='$month' AND X.yearreport='$year'
			JOIN care_med_products_main AS main ON main.product_encoder = source.product_encoder ".$cond_typeput." 
			JOIN care_med_unit_of_medipot AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
		//echo $this->sql;		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}	
	
	function Khochan_hoachat_nhapxuatton_theongay($condition, $cond_typeput, $last_date_report, $date_show){
		global $db;
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name, unit.unit_name_of_chemical, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_hc_ton WHERE DATE(view_hc_ton.date_time)='$last_date_report' 
					  UNION
					  SELECT *
					  FROM view_hc_nhap WHERE DATE(view_hc_nhap.date_time)<='$date_show' AND DATE(view_hc_nhap.date_time)>'$last_date_report' 
					  UNION
					  SELECT *
					  FROM view_hc_xuat WHERE DATE(view_hc_xuat.date_time)<='$date_show' AND DATE(view_hc_xuat.date_time)>'$last_date_report'
					) AS source
			LEFT JOIN view_hc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND DATE(T.date_time)='$last_date_report'
			LEFT JOIN view_hc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND DATE(N.date_time)<='$date_show' AND DATE(N.date_time)>'$last_date_report'
			LEFT JOIN view_hc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND DATE(X.date_time)<='$date_show' AND DATE(X.date_time)>'$last_date_report'
			JOIN care_chemical_products_main AS main ON main.product_encoder = source.product_encoder ".$condition." ".$cond_typeput." 
			JOIN care_chemical_unit_of_medicine AS unit ON unit.unit_of_chemical=main.unit_of_chemical
			AND (T.number>0 OR N.number>0 OR X.number>0)
			ORDER BY main.product_name, source.product_encoder, source.monthreport, source.date_time, T.price, N.price, X.price ";
		//echo $this->sql;		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	function Khochan_hoachat_luutonkho($fromdate, $todate, $monthreport, $yearreport, $typeput){
		global $db;
		$this->sql="INSERT INTO care_chemical_khochan_ton_info (id, fromdate, todate, monthreport, yearreport, typeput)
				VALUES ('', '$fromdate', '$todate', '$monthreport', '$yearreport', '$typeput')";
		return $this->Transact($this->sql);
	}	
	function Khochan_hoachat_updatetonkho($id, $fromdate, $todate, $monthreport, $yearreport, $typeput){
		global $db;
		$this->sql="UPDATE care_chemical_khochan_ton_info 
					SET 
					fromdate = '$fromdate' , 
					todate = '$todate' , 
					monthreport = '$monthreport' , 
					yearreport = '$yearreport' , 
					typeput = '$typeput'					
					WHERE
					id = '$id' ";
		return $this->Transact($this->sql);
	}
	function deleteAllChemicalInTonKho($ton_id) {
	    global $db;
		if(!$ton_id) return FALSE;

		$this->sql="DELETE FROM care_chemical_khochan_ton
					WHERE ton_id=$ton_id";
		return $this->Transact($this->sql);	
	}	
	function Khochan_hoachat_luutonkho_chitiet($ton_id, $product_encoder, $lotid, $typeput, $exp_date, $number, $price){
		global $db;
		$this->sql="INSERT INTO care_chemical_khochan_ton (id, ton_id, product_encoder, lotid, typeput, exp_date, number, price, create_time)
				VALUES ('', '$ton_id', '$product_encoder', '$lotid', '$typeput', '$exp_date', '$number', '$price', CURRENT_TIMESTAMP)";
		return $this->Transact($this->sql);
	}
	function getLastTonKhoHCID(){
		global $db;
		$this->sql="SELECT MAX(id) AS ton_id FROM care_chemical_khochan_ton_info";
		if ($this->result=$db->Execute($this->sql)) {
		    if ($this->result->RecordCount()) {
		        return $this->result->FetchRow();
			}else{return false;}
		}else{return false;}		
	}	
	function Khochan_hoachat_nhapxuatton($cond_typeput, $month, $year){
		global $db;
		if ($month>1){
			$ton_month = $month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name, unit.unit_name_of_chemical, main.nuocsx 
			FROM  ( SELECT * 
					  FROM view_hc_ton WHERE view_hc_ton.monthreport='$ton_month' AND view_hc_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_hc_nhap WHERE view_hc_nhap.monthreport='$month' AND view_hc_nhap.yearreport='$year'
					  UNION
					  SELECT *
					  FROM view_hc_xuat WHERE view_hc_xuat.monthreport='$month' AND view_hc_xuat.yearreport='$year'
					) AS source
			LEFT JOIN view_hc_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_hc_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport='$month' AND N.yearreport='$year'
			LEFT JOIN view_hc_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='$month' AND X.yearreport='$year'
			JOIN care_chemical_products_main AS main ON main.product_encoder = source.product_encoder ".$cond_typeput." 
			JOIN care_chemical_unit_of_medicine AS unit ON unit.unit_of_chemical=main.unit_of_chemical
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
		//echo $this->sql;		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}

	/*							-------------------Xuat Nhap Ton - Kho le------------------						*/
	function Khole_thuoc_nhapxuatton($typedongtay, $cond_typeput, $month, $year){
		global $db;
		if ($month>1){
			$ton_month = $month-1;
			$ton_year = $year;
		}else{
			$ton_month = 12;
			$ton_year = $year-1;
		}
		switch($typedongtay){
			case 'tayy': $view_ton ='view_thuoc_khole_ton'; 
						$dongtayy =' AND main.pharma_type IN (1,2,3) ';
						$dongtayy_1 = ' AND pharma_type IN (1,2,3) ';
						break;	
			case 'dongy': $view_ton ='view_thuoc_khole_ton_dongy'; 
						$dongtayy = ' AND main.pharma_type IN (4,8,9,10) ';
						$dongtayy_1 = ' AND pharma_type IN (4,8,9,10) ';
						break;	
		}		
		$this->sql="SELECT DISTINCT source.monthreport, source.product_encoder, T.number AS ton, T.price AS giaton, N.number AS nhap, N.price AS gianhap, X.number AS xuat, X.price AS giaxuat, T.exp_date AS hanton, N.exp_date AS hannhap, T.lotid AS loton, N.lotid AS lonhap, X.lotid AS loxuat, main.product_name, unit.unit_name_of_medicine, main.nuocsx 
			FROM  ( SELECT * 
					  FROM $view_ton WHERE $view_ton.monthreport='$ton_month' AND $view_ton.yearreport='$ton_year'
					  UNION
					  SELECT *
					  FROM view_thuoc_khole_nhap WHERE view_thuoc_khole_nhap.monthreport='$month' AND view_thuoc_khole_nhap.yearreport='$year' ".$dongtayy_1."
					  UNION
					  SELECT *
					  FROM view_thuoc_khole_xuat WHERE view_thuoc_khole_xuat.monthreport='$month' AND view_thuoc_khole_xuat.yearreport='$year' ".$dongtayy_1."
					) AS source
			LEFT JOIN $view_ton AS T ON source.product_encoder = T.product_encoder AND source.monthreport=T.monthreport AND source.price=T.price AND T.monthreport='$ton_month' AND T.yearreport='$ton_year'
			LEFT JOIN view_thuoc_khole_nhap AS N ON source.product_encoder = N.product_encoder AND source.monthreport = N.monthreport AND source.price=N.price AND N.monthreport='$month' AND N.yearreport='$year'
			LEFT JOIN view_thuoc_khole_xuat AS X ON source.product_encoder = X.product_encoder AND source.monthreport = X.monthreport AND source.price=X.price AND X.monthreport='$month' AND X.yearreport='$year'
			JOIN care_pharma_products_main AS main ON main.product_encoder = source.product_encoder ".$dongtayy." ".$cond_typeput." 
			JOIN care_pharma_unit_of_medicine AS unit ON unit.unit_of_medicine=main.unit_of_medicine
			ORDER BY main.product_name, source.product_encoder, source.monthreport ";
		//echo $this->sql;		
		if ($this->result=$db->Execute($this->sql)) {
			if ($this->result->RecordCount()) {				
				return $this->result;
			}else{return false;}
		}else{return false;}
	}
	
}
?>
