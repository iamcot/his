<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    $root_path='../../../';
    require($root_path.'include/core/inc_environment_global.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    $search=$_GET["search"];
    $lotid=$_GET["lotid"];
    $response="";
    $dept_ward = '';
    if ($dept_nr!='')
        $dept_ward = " AND taikhoa.department='".$dept_nr."' ";
    if ($ward_nr!=0)
        $dept_ward.= " AND taikhoa.ward_nr='".$ward_nr."' ";

    switch($mode){
	case 'auto': 
            $sql="SELECT DISTINCT taikhoa.*, khochan.product_name, donvi.unit_name_of_chemical, tatcakhoa.product_encoder, 
                        tatcakhoa.product_lot_id, tatcakhoa.exp_date, khochan.price, taikhoa.ward_nr 
                    FROM care_chemical_available_department AS taikhoa, care_chemical_available_product AS tatcakhoa, 
                    care_chemical_products_main AS  khochan, care_chemical_unit_of_medicine AS donvi, care_ward AS ward  
                    WHERE taikhoa.available_number>0 
                    ".$dept_ward." 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND taikhoa.available_product_id=tatcakhoa.available_product_id 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                    AND (product_name LIKE '".$search."%' or product_name LIKE '% ".$search."%') 
                    ORDER BY product_name LIMIT 15 ";

            if($result = $db->Execute($sql)){
                $n=$result->RecordCount();
                $item_id=""; $item_value=""; 
                if ($n){
                    echo '<ul>';
                    for ($i=0;$i<$n;$i++)
                    {
                        $chemical=$result->FetchRow();
						switch($chemical["typeput"]){
							case 0: $dang='BHYT';break;
							case 1: $dang='KP';break;
							case 2: $dang='CBTC';break;
						}					
                        $chemical["exp_date"] = formatDate2Local($chemical["exp_date"],'dd/mm/yyyy');
                        echo '<li id="'.$k.'@#'.$chemical["product_encoder"].'">';
                        echo '<div><font color="#FF0000">'.$chemical["product_name"].'</font></div>';
                        echo '<span>-- '.$chemical["product_lot_id"].'-- '.$chemical["exp_date"].'<br>-- '.$chemical["price"].' vnd/'.$chemical["unit_name_of_chemical"].'-- '.$chemical["available_number"].'-- '.$dang.'<br>&nbsp;</span></li>';
                        /*if($chemical['ward_nr']!=0){
                            $sql1="SELECT name FROM care_ward WHERE nr=".$chemical['ward_nr']."";
                            $result1 = $db->Execute($sql1);
                            $chemical1=$result1->FetchRow();
                            echo '<br>-- '.$ward1.': '.$chemical1['ward_name'].'-'.$chemical['ward_nr'].'<br>&nbsp;</span></li>';
                        }else{
                            echo '<br>-- '.$ward1.': '.$all.'-'.$chemical['ward_nr'].'<br>&nbsp;</span></li>';
                        }*/
                    }
                    echo '</ul>';
                }
            }
            break;
			
	case 'filldata': 
            $sql="SELECT taikhoa.*, khochan.product_name, donvi.unit_name_of_chemical, tatcakhoa.product_encoder, 
                    tatcakhoa.product_lot_id, tatcakhoa.exp_date, khochan.price , taikhoa.ward_nr   
                    FROM care_chemical_available_department AS taikhoa, care_chemical_available_product AS tatcakhoa, 
                            care_chemical_products_main AS  khochan, care_chemical_unit_of_medicine AS donvi  
                    WHERE taikhoa.available_number>0 
                    ".$dept_ward." 	 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND taikhoa.available_product_id=tatcakhoa.available_product_id 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical	
                    AND product_name='".$search."'  
                    AND tatcakhoa.product_lot_id='".$lotid."'";
            //echo $sql;
            if($result = $db->Execute($sql)){
                $n=$result->RecordCount();
                if ($n){
                    $chemical=$result->FetchRow();
                    if ($chemical["available_number"]=="")
                            $chemical["available_number"]="0";
                    $chemical["exp_date"] = formatDate2Local($chemical["exp_date"],'dd/mm/yyyy');

                    $response=$chemical["product_encoder"].'@#'.$chemical["available_number"].'@#'.$chemical["unit_name_of_chemical"].'@#'.$chemical["price"].'@#'.$chemical["product_lot_id"].'@#'.$chemical["exp_date"].'@#'.$chemical["ward_nr"];	
                }
            }
            echo $response;
            break;
				
	default: $response="";
}
		

?>