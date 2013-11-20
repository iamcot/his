<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    $root_path='../../../';
    require($root_path.'include/core/inc_environment_global.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    $search=$_GET["search"];
    $encoder=$_GET["encoder"];
    $response="";

    switch($mode){
	case 'auto': 
            $sql="SELECT khochan.*, donvi.unit_name_of_chemical     
                    FROM care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi  
                    WHERE donvi.unit_of_chemical=khochan.unit_of_chemical 
                    AND (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '% ".$search."%') 
                    ORDER BY product_name LIMIT 15 ";
                                
            if($result = $db->Execute($sql)){
                $n=$result->RecordCount();
                $item_id=""; $item_value=""; 
                if ($n){
                    echo '<ul>';
                    for ($i=0;$i<$n;$i++)
                    {
                            $chemical=$result->FetchRow();
                            echo '<li id="'.$k.'@#'.$chemical["product_encoder"].'">';
                            echo '<div><font color="#FF0000">'.$chemical["product_name"].'</font></div>';
                            echo '<span>-- '.$chemical["price"].' vnd/'.$chemical["unit_name_of_chemical"].'-- '.$chemical["available_number"].'<br>&nbsp;</span></li>';

                    }
                    echo '</ul>';
                }
            }
            break;
			
	case 'filldata': 
            $sql="SELECT khochan.*, donvi.unit_name_of_chemical     
                    FROM care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi  
                    WHERE donvi.unit_of_chemical=khochan.unit_of_chemical 	
                    AND product_name='".$search."'  ";
            //echo $sql;
            if($result = $db->Execute($sql)){
                $n=$result->RecordCount();
                if ($n){
                    $chemical=$result->FetchRow();

                    $response=$chemical["product_encoder"].'@#'.$chemical["price"].'@#'.$chemical["unit_name_of_chemical"].'@#'.$chemical["available_number"];
                }
            }
            echo $response;
            break;
				
	default: $response="";
}
		

?>