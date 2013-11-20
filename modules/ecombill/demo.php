<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/care_api_classes/class_encounter.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
$eComBill=new eComBill;
$Encounter = new Encounter;


echo $patientno."<br>";

//$sListRows='';
$billresult = $eComBill->listCurrentBills($patientno);
if(is_object($billresult)) {
	while ($result=$billresult->FetchRow()) { 
	    //bill
		$billno = $result['bill_bill_no'];
	    //$smarty->assign('date', formatDate2Local($result['bill_date_time'],$date_format));
		echo $billno.' '.$result['bill_date_time']."<br />";
		
				//items in bill
				$billitemresult = $eComBill->listItemsByBillId($billno);
				
				if(is_object($billitemresult)) {
					while ($result_item=$billitemresult->FetchRow()) { 
						$billitem = $result_item['bill_item_code'];
							//name of item
							$nameitemresult = $eComBill->listServiceItemsByCode($billitem);
							if(is_object($nameitemresult)) {
								$nameitem = $nameitemresult->FetchRow();
								$name = $nameitem['item_description'];
								
							}else $name='';
						
						echo $billitem.' '.$name.' '.$result_item['bill_item_unit_cost'].' '.$result_item['bill_item_units'].' '.$result_item['bill_item_amount']."<br />";
					}
				}
		echo "<br />";
		/*		
	    ob_start();
		$smarty->display('ecombill/bill_payment_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean(); 
		*/
	}
} else {
	echo 'khong co';
}






?>