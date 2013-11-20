<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

//require_once($root_path.'include/core/access_log.php');
//$logs = new AccessLog();
$thisfile=basename(__FILE__);

require_once($root_path.'modules/pharmacy/catalogue_process.php');//file xử lý

function select_obj1($catalogue, $obj)
{
    //$response->result='no result';
    $user=$_GET["user"];
    if ($catalogue=='supplier')
    {
        $supplier=$_GET["obj"];
        $_obj=new Supplier();
        $result=$_obj->GetSupplierInfo($supplier, $user);
        $result=$result->FetchRow();
        $response='["supplier_name","'.$result["supplier_name"].'"],["type_name_of_supplier","'.$result["type_name_of_supplier"].'"]'.
        ','.'["address","'.$result["address"].'"],["telephone","'.$result["telephone"].'"],["fax","'.$result["fax"].'"]'.
        ','.'["note","'.$result["note"].'"],["type_of_supplier","'.$result["type_of_supplier"].'"]';
        //return $result;
    }
     if ($catalogue=='generic_drug')
    {
        $pharma_generic_drug_id=$_GET["obj"];
        $_obj=new Product();
        $result=$_obj->GetGenericDrugInfo($pharma_generic_drug_id, $user);
        $result=$result->FetchRow();
        $response='["generic_drug","'.$result["generic_drug"].'"],["generic_drug_id","'.$result["generic_drug_id"].'"],["pharma_group_name","'.$result["pharma_group_name"].'"],["pharma_group_name_sub","'.$result["pharma_group_name_sub"].'"],["pharma_group_id","'.$result["pharma_group_id"].'"],["pharma_group_id_sub","'.$result["pharma_group_id_sub"].'"],["using_type","'.$result["using_type"].'"],["note","'.$result["note"].'"],["effects","'.$result["effects"].'"],["description","'.$result["description"].'"],["drug_id","'.$result["drug_id"].'"],["hospital_5th","'.$result["hospital_5th"].'"],["hospital_6th","'.$result["hospital_6th"].'"],["hospital_7th","'.$result["hospital_7th"].'"],["hospital_8th","'.$result["hospital_8th"].'"]';
        //return $result;
    }
    if ($catalogue=='medicine')
    {
          $product_encoder=$_GET["obj"];
          $_obj=new Product();
          $result=$_obj->GetMedicineInfo($product_encoder, $user);
          $result=$result->FetchRow();
          $response='["product_name","'.$result["product_name"].'"],["generic_drug","'.$result["generic_drug"].'"],["pharma_generic_drug_id","'.$result["pharma_generic_drug_id"].'"],["in_use","'.$result["in_use"].'"],["content","'.$result["content"].'"],["using_type","'.$result["using_type"].'"],["effects","'.$result["effects"].'"],["short_name","'.$result["short_name"].'"],["description","'.$result["description"].'"],["component","'.$result["component"].'"],["caution","'.$result["caution"].'"],["supplier_name","'.$result["supplier_name"].'"],["supplier","'.$result["care_supplier"].'"],["type_of_medicine","'.$result["type_of_medicine"].'"],["type_name_of_medicine","'.$result["type_name_of_medicine"].'"],["price","'.$result["price"].'"],["unit_of_medicine","'.$result["unit_of_medicine"].'"],["unit_name_of_medicine","'.$result["unit_name_of_medicine"].'"],["unit_of_price","'.$result["unit_of_price"].'"],["available_number","'.$result["available_number"].'"],["note","'.$result["note"].'"],["pharma_group_name","'.$result["pharma_group_name"].'"],["sodangky","'.$result["sodangky"].'"],["hangsx","'.$result["hangsx"].'"],["nuocsx","'.$result["nuocsx"].'"]';

    }
    if ($catalogue=='vnmedicine')
    {
          $product_encoder=$_GET["obj"];
          $_obj=new Product();
          $result=$_obj->GetVnMedicineInfo($product_encoder, $user);
          $result=$result->FetchRow();
          $response='["product_name","'.$result["product_name"].'"],["generic_drug","'.$result["generic_drug"].'"],["pharma_generic_drug_id","'.$result["pharma_generic_drug_id"].
          '"],["in_use","'.$result["in_use"].'"],["content","'.$result["content"].'"],["using_type","'.$result["using_type"].'"],["effects","'.$result["effects"].'"],["short_name","'.$result["short_name"].'"],["description","'.$result["description"].'"],["component","'.$result["component"].'"],["caution","'.$result["caution"].'"],["supplier_name","'.$result["supplier_name"].'"],["supplier","'.$result["care_supplier"].'"],["type_of_medicine","'.$result["type_of_medicine"].'"],["type_name_of_medicine","'.$result["type_name_of_medicine"].'"],["price","'.$result["price"].'"],["unit_of_medicine","'.$result["unit_of_medicine"].'"],["unit_name_of_medicine","'.$result["unit_name_of_medicine"].'"],["unit_of_price","'.$result["unit_of_price"].'"],["available_number","'.$result["available_number"].'"],["note","'.$result["note"].'"],["pharma_group_name","'.$result["pharma_group_name"].'"],["generic_drug","'.$result["generic_drug"].'"],["sodangky","'.$result["sodangky"].'"],["hangsx","'.$result["hangsx"].'"],["nuocsx","'.$result["nuocsx"].'"]';

    }
    if ($catalogue=='medipot')
    {
          $product_encoder=$_GET["obj"];
          $_obj=new Product();
          $result=$_obj->GetMedipotInfo($product_encoder, $user);
          $result=$result->FetchRow();
          $response='["product_name","'.$result["product_name"].'"],["generic_drug","'.$result["generic_drug"].'"],["pharma_generic_drug_id","'.$result["pharma_generic_drug_id"].
          '"],["in_use","'.$result["in_use"].'"],["content","'.$result["content"].'"],["using_type","'.$result["using_type"].'"],["effects","'.$result["effects"].'"],["short_name","'.$result["short_name"].'"],["description","'.$result["description"].'"],["component","'.$result["component"].'"],["caution","'.$result["caution"].'"],["supplier_name","'.$result["supplier_name"].'"],["supplier","'.$result["care_supplier"].'"],["type_of_medicine","'.$result["type_of_medicine"].'"],["type_name_of_medicine","'.$result["type_name_of_medicine"].'"],["price","'.$result["price"].'"],["unit_of_medicine","'.$result["unit_of_medicine"].'"],["unit_name_of_medicine","'.$result["unit_name_of_medicine"].'"],["unit_of_price","'.$result["unit_of_price"].'"],["available_number","'.$result["available_number"].'"],["note","'.$result["note"].'"],["sodangky","'.$result["sodangky"].'"],["hangsx","'.$result["hangsx"].'"],["nuocsx","'.$result["nuocsx"].'"]';

    }
    if ($catalogue=='chemical')
    {
          $product_encoder=$_GET["obj"];
          $_obj=new Product();
          $result=$_obj->GetChemicalInfo($product_encoder, $user);
          $result=$result->FetchRow();
          $response='["product_name","'.$result["product_name"].'"],["generic_drug","'.$result["generic_drug"].'"],["chemical_generic_drug_id","'.$result["chemical_generic_drug_id"].
          '"],["in_use","'.$result["in_use"].'"],["content","'.$result["content"].'"],["using_type","'.$result["using_type"].'"],["effects","'.$result["effects"].'"],["short_name","'.$result["short_name"].'"],["description","'.$result["description"].'"],["component","'.$result["component"].'"],["caution","'.$result["caution"].'"],["supplier_name","'.$result["supplier_name"].'"],["supplier","'.$result["care_supplier"].'"],["type_of_chemical","'.$result["type_of_chemical"].'"],["type_name_of_chemical","'.$result["type_name_of_chemical"].'"],["price","'.$result["price"].'"],["unit_of_chemical","'.$result["unit_of_chemical"].'"],["unit_name_of_chemical","'.$result["unit_name_of_chemical"].'"],["unit_of_price","'.$result["unit_of_price"].'"],["available_number","'.$result["available_number"].'"],["chemical_group_name","'.$result["chemical_group_name"].'"],["note","'.$result["note"].'"],["sodangky","'.$result["sodangky"].'"],["hangsx","'.$result["hangsx"].'"],["nuocsx","'.$result["nuocsx"].'"]';

    }
    return $response;
}
function save1($catalogue)
{
        $response='fail';
        $user=$_GET["user"];
        if ($catalogue=='supplier')
        {
            $supplier=$_GET["obj"];
            $supplier_name=$_GET["value1"];
            $type_of_supplier=$_GET["value2"];
            $address=$_GET["value3"];
            $telephone=$_GET["value4"];
            $fax=$_GET["value5"];
            $note=$_GET["note"];
            $_obj=new Supplier();
            if($_obj->EditSupplier($supplier, $supplier_name, $type_of_supplier, $address, $telephone, $fax, $note, $user)!=false){
				//$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $_obj->getLastQuery(), date('Y-m-d H:i:s'));
				if($result=$_obj->GetSupplierInfo($supplier, $user)){
					$result=$result->FetchRow();
					return $result["type_name_of_supplier"];
				}else return 'fail';
			}
            else return 'fail';          

        }
        if ($catalogue=='generic_drug')
        {
            $pharma_generic_drug_id=$_GET["obj"];
            $generic_drug=$_GET["value1"];
            $pharma_group_id=$_GET["value2"];
			$pharma_group_id_sub=$_GET["value9"];
            $drug_id=$_GET["value3"];
            $using_type=$_GET["value4"];
            $description=$_GET["value5"];
            $effects=$_GET["value6"];
            $generic_drug_id=$_GET["value7"];
            $note=$_GET["note"];
			$hospital_th=$_GET["hospital_th"];
			$th=explode("_", $hospital_th);

            $_obj=new Product();
            if($_obj->EditGenericDrug($pharma_generic_drug_id, $generic_drug, $pharma_group_id, $pharma_group_id_sub, $generic_drug_id, $drug_id, $using_type, $th[1],$th[2],$th[3],$th[4], $description, $effects, $note, $user)!=false){
				//$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $_obj->getLastQuery(), date('Y-m-d H:i:s'));
				if($result=$_obj->GetGenericDrugInfo($pharma_generic_drug_id, $user)){
					$result=$result->FetchRow();			
					return $result["pharma_group_name"].'@@@'.$result["pharma_group_name_sub"];
				} else return 'fail';
			}
            else 
				return 'fail';
				
        }
        if ($catalogue=='medicine'|| $catalogue=='vnmedicine' )
        {
            $product_encoder=$_GET["obj"];
            $product_name=$_GET["value1"];
            if($catalogue=='vnmedicine')
				$pharma_generic_drug_id=1;
			else	
				$pharma_generic_drug_id=$_GET["value2"];
            $content=$_GET["value3"];
            $component=$_GET["value4"];
            $using_type=$_GET["value5"];
            $type_of_medicine=$_GET["value6"];
            $unit_of_medicine=$_GET["value7"];
            $caution=$_GET["value8"];
            $care_supplier=$_GET["value9"];
            $price=$_GET["value10"];
            $unit_of_price=$_GET["value11"];
			$in_use=$_GET["value12"];
            $description=$_GET["value13"];
            $effects=$_GET["value14"];
            $note=$_GET["value15"];
			$sodangky=$_GET["value16"];
			$hangsx=$_GET["value17"]; 
			$nuocsx=$_GET["value18"];			
			$history=$_GET["history"];
			if($history==''){
				$temp1=date('d-M-Y');
				$history=$_SESSION['sess_user_name'].' '.$temp1;
			}
            $_obj=new Product();           
			
			if($_obj->EditMedicine($product_encoder, $product_name, $pharma_generic_drug_id, $content, $component, $using_type, $type_of_medicine, $unit_of_medicine, $caution, $care_supplier, $note, $price, $unit_of_price, $effects, $in_use, $description, $history, $sodangky, $hangsx, $nuocsx)!=false){
				//$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $_obj->getLastQuery(), date('Y-m-d H:i:s'));
				return $product_name.' '.$in_use;	
			}	
            else 
				return 'fail';
	
        }
		
        if ($catalogue=='medipot')
        {
			$product_encoder=$_GET["obj"];
            $product_name=$_GET["value1"];
			$using_type=$_GET["value5"];
            $unit_of_medicine=$_GET["value7"];
            $caution=$_GET["value8"];
            $care_supplier=$_GET["value9"];
            $price=$_GET["value10"];
            $unit_of_price=$_GET["value11"];
            $description=$_GET["value13"];
            $note=$_GET["value15"];
			$sodangky=$_GET["value16"];
			$hangsx=$_GET["value17"]; 
			$nuocsx=$_GET["value18"];			
			$history=$_GET["history"];
			if($history==''){
				$temp1=date('d-M-Y');
				$history=$_SESSION['sess_user_name'].' '.$temp1;
			}
            $_obj=new Product();           
			
			if($_obj->EditMedipot($product_encoder, $product_name, $using_type, $unit_of_medicine, $caution, $care_supplier, $note, $price, $unit_of_price, $description, $history, $sodangky, $hangsx, $nuocsx)!=false){
				//$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $_obj->getLastQuery(), date('Y-m-d H:i:s'));
				return $product_encoder.' '.$product_name.' '.$using_type.' '.$unit_of_medicine.' '.$caution.' '.$care_supplier.' '.$note.' '.$price.' '.$unit_of_price.' '.$description.' '.$history.' '.$sodangky.' '.$nuocsx.' '.$hangsx;	
			}	
            else 
				return 'fail';	
			
        }
        if ($catalogue=='chemical')
        {
            $product_encoder=$_GET["obj"];
            $product_encoder_before=$_GET["encoder_1"];
            $product_name=$_GET["value1"];
            $using_type=$_GET["value7"];
            $unit_of_chemical=$_GET["value14"];
            $caution=$_GET["value9"];
            $care_supplier=$_GET["value11"];
            $price=$_GET["value10"];
            $unit_of_price=$_GET["value15"];
			$sodangky=$_GET["value16"];
			$hangsx=$_GET["value17"]; 
			$nuocsx=$_GET["value18"];			
            $description=$_GET["value8"];
            $note=$_GET["note"];
            $history=$_GET["history"];
            $id_group=$_GET["value2"];
            if($history==''){
                    $temp1=date('d-M-Y');
                    $history=$_SESSION['sess_user_name'].' '.$temp1;
            }
            $_obj=new Product();  
            if($_obj->EditChemical($product_encoder, $product_encoder_before, $product_name, $using_type, $unit_of_chemical, $caution, $care_supplier, $note, $price, $unit_of_price, $description, $history, $id_group, $sodangky, $hangsx, $nuocsx)!=false){
				//$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $_obj->getLastQuery(), date('Y-m-d H:i:s'));
                return $product_encoder.' '.$product_name.' '.$using_type.' '.$unit_of_chemical.' '.$caution.' '.$care_supplier.' '.$note.' '.$price.' '.$unit_of_price.' '.$description.' '.$history.' '.$id_group.' '.$sodangky.' '.$nuocsx.' '.$hangsx;	
			}	
            else 
                return 'fail';	
			
        }
        return $response;
}
function autocomplete($catalogue, $quick)
{
    if ($catalogue=='supplier')
    {
        $obj=new Supplier();
        $result=$obj->GetAllSupplierInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["supplier"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["supplier"];
            $i++;
        }
    }
    if ($catalogue=='generic_drug')
    {
        $obj=new Product();
        $result=$obj->GetAllGenericDrugInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["generic_drug"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["generic_drug"];
            $i++;
        }
    }
    if ($catalogue=='medicine_filldata')
    {
        $obj=new Product();
		$quick = str_replace('@*','+', $quick); //$quick= replace($quick,'@*','+');
        if($result=$obj->GetPharmaGroupNameByGeneric($quick)){
			$result1=$result->FetchRow();
			$response=$result1["pharma_group_id"].'@#'.$result1["pharma_group_name"].'@#'.$result1["pharma_generic_drug_id"];
		}
    }
    if ($catalogue=='medicine')
    {
        $obj=new Product();
        $result=$obj->GetAllMedicineInfos($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
        $response=$result1["product_encoder"].'@#'.$result1["product_name"].'@#'.$result1["unit_name_of_medicine"].'@#'.$result1["price"].'@#'.$result1["product_lot_id"].'@#'.$result1["DAY(exp_date)"].'@#'.$result1["MONTH(exp_date)"].'@#'.$result1["YEAR(exp_date)"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_encoder"].'@#'.$result1["product_name"].'@#'.$result1["unit_name_of_medicine"].'@#'.$result1["price"].'@#'.$result1["product_lot_id"].'@#'.$result1["DAY(exp_date)"].'@#'.$result1["MONTH(exp_date)"].'@#'.$result1["YEAR(exp_date)"];
            $i++;
        }
    }
    if ($catalogue=='vnmedicine')
    {
        $obj=new Product();
        $result=$obj->GetAllVnMedicineInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["product_name"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_name"];
            $i++;
        }
    }
    if ($catalogue=='medipot')
    {
        $obj=new Product();
        $result=$obj->GetAllMedipotInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["product_name"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_name"];
            $i++;
        }
    }
    if ($catalogue=='chemical')
    {
        $obj=new Product();
        $result=$obj->GetAllChemicalInfos($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
        $response=$result1["product_encoder"].'@#'.$result1["product_name"].'@#'.$result1["unit_name_of_medicine"].'@#'.$result1["price"].'@#'.$result1["product_lot_id"].'@#'.$result1["DAY(exp_date)"].'@#'.$result1["MONTH(exp_date)"].'@#'.$result1["YEAR(exp_date)"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_encoder"].'@#'.$result1["product_name"].'@#'.$result1["unit_name_of_medicine"].'@#'.$result1["price"].'@#'.$result1["product_lot_id"].'@#'.$result1["DAY(exp_date)"].'@#'.$result1["MONTH(exp_date)"].'@#'.$result1["YEAR(exp_date)"];
            $i++;
        }
    }
    return $response;
}
function info($catalogue, $quick)
{
    if ($catalogue=='supplier')
    {
        $obj=new Supplier();
        $result=$obj->GetAllSupplierInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["supplier"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["supplier"];
            $i++;
        }
    }
    if ($catalogue=='generic_drug')
    {
        $obj=new Product();
        $result=$obj->GetAllGenericDrugInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
        $response=$result1["generic_drug"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["generic_drug"];
            $i++;
        }
    }
    if ($catalogue=='medicine')
    {
        $obj=new Product();
        $result=$obj->GetAllMedicineInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
        $response=$result1["product_name"].'@#'.$result1["product_encoder"].'@#'.$result1["unit_name_of_medicine"].'@#'.$result1["price"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_name"].'@#'.$result1["product_encoder"].'@#'.$result1["unit_name_of_medicine"].'@#'.$result1["price"];
            $i++;
        }
    }
    if ($catalogue=='vnmedicine')
    {
        $obj=new Product();
        $result=$obj->GetAllVnMedicineInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["product_name"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_name"];
            $i++;
        }
    }
    if ($catalogue=='medipot')
    {
        $obj=new Product();
        $result=$obj->GetAllMedipotInfo($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["product_name"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_name"];
            $i++;
        }
    }
    if ($catalogue=='chemical')
    {
        $obj=new Product();
        $result=$obj->GetAllChemicalInfos($quick, 0, $total_records, 10, $group_key, $attribute);
        $result1=$result->FetchRow();
//        $response='["1","'.$result1["supplier"].'"]';
        $response=$result1["product_name"];
        $i=2;
        while ($result1=$result->FetchRow())
        {
//              $response=$response.','.'["'.$i.'","'.$result1["supplier"].'"]';
            $response=$response.'@#'.$result1["product_name"];
            $i++;
        }
    }
    return $response;
}

$response="No response";
$key=$_GET["key"];
$catalogue=$_GET["catalogue"];
if ($key=="save")
{
    $response=save1($catalogue);
}
else
    if ($key=="select_box")
    {
        $select=$_GET["select"];
        if ($catalogue=='supplier' || $catalogue=='generic_drug') $response=load_select_box1($catalogue,$select);
        else
        {
            $product_encoder=$_GET["obj"];
            $product=new Product();
            if ($catalogue=='medipot')
            {
                $temp=$product->GetMedipotInfo($product_encoder, '')->FetchRow();
                $unit=$temp["unit_of_medicine"];
                $currency=$temp["unit_of_price"];
                $response=load_unit_medipot($unit).'@#'. load_currency($currency);
            }
            if ($catalogue=='medicine')
            {
                $temp=$product->GetMedicineInfo($product_encoder, '')->FetchRow();
                $unit=$temp["unit_of_medicine"];
                $currency=$temp["unit_of_price"];
                $type=$temp["type_of_medicine"];
                $group=$temp["pharma_group_id"];
                $response=load_unit($unit).'@#'. load_currency($currency).'@#'.  load_select_box1($catalogue, $type);
            }
            if ($catalogue=='vnmedicine')
            {
                $temp=$product->GetVnMedicineInfo($product_encoder, '')->FetchRow();
                $unit=$temp["unit_of_medicine"];
                $currency=$temp["unit_of_price"];
                $type=$temp["type_of_medicine"];
                $response=load_unit($unit).'@#'. load_currency($currency).'@#'.  load_select_box1($catalogue, $type);
            }
            if ($catalogue=='chemical')
            {
                $temp=$product->GetChemicalInfo($product_encoder, '')->FetchRow();
                $unit=$temp["unit_of_chemical"];
                $currency=$temp["unit_of_price"];
                $group_id=$temp["chemical_generic_drug_id"];
                $response=load_unit_Chemical($unit).'@#'. load_currency($currency).'@#'.load_select_box1($catalogue, $type).'@#'.load_group_Chemical($group_id);
            }
        }
    }
    else
        if ($key=="load_catalogue")
        {
             $cond=$_GET["cond"];
             $records_in_page=$_GET["records_in_page"];
             $current_page=$_GET["page"];
             $attribute=$_GET["attribute"];
             $group_key=$_GET["group_key"];
             $response=load_catalogue1($catalogue, $cond, $attribute, $group_key, $records_in_page, $current_page, $total_records);
        }
        else
            if ($key=="select_obj")
            {
                $obj=$_GET["obj"];
                $response=select_obj1($catalogue, $obj);
            }
            else
                if ($key=="autocomplete")
                {
                    $quick=$_GET["quick"];
                    $response=autocomplete($catalogue, $quick);
                }
                else
                    if ($key=="info")
                    {
                        $quick=$_GET["quick"];
                        $response=info($catalogue, $quick);
                    }
    echo $response;
?>