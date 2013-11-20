<?php
    /**
    * @package care_api
    */
    /**
    */
    # Define to TRUE if you want to show the ward id with full name  on the selection box
    define('SHOW_COMBINE_WARDIDNAME',1);
    # Define to TRUE if you want to show the full name of  wards on the selection box
    define('SHOW_FULL_WARDNAME',FALSE);
    /**
    */
    require_once($root_path.'include/care_api_classes/class_core.php');
    class Med_Dept extends Core{
        var $tb_med_issue='care_med_issue_paper';
        var $tb_med_issue_info='care_med_issue_paper_info';
        var $tb_med='care_med_products_main';
        var $tb_med_avail='care_med_available_product';
        var $tb_med_unit='care_med_unit_of_medipot';
        var $tb_med_dept='care_med_available_department';
        var $tb_med_pro_sub='care_med_products_main_sub';
        var $tb_med_pro_sub1='care_med_products_main_sub1';
        var $tb_med_prescription='care_med_prescription'; 
        var $tb_med_prescription_info='care_med_prescription_info';
        var $tb_test='care_test_request_or';
        
        var $query='';
        var $result='';
        var $count='';        
        var $number='';
        var $nr1='';
        var $number_use='';
        
        //Lấy tất cả VTYT có trong khoa
        function getAllMedInDept($dept_nr='',$ward_nr='',$odir='',$oitem=''){
            global  $db;
            $this->sql="SELECT DISTINCT(med_avail.product_encoder) as product_encoder
                        FROM $this->tb_med_dept as tk,$this->tb_med_avail as med_avail
                        WHERE  tk.department=$dept_nr
							AND tk.ward_nr=$ward_nr
                            AND med_avail.available_product_id=tk.available_product_id";
            return $this->sql;
        }
        
        //Lấy tên tương ứng với mã VTYT
        function getMedInfo($product_encoder='',$dept_nr='',$ward_nr=''){
            global $db;
            $this->sql="SELECT DISTINCT(tb_med.product_name) AS product_name
                        FROM $this->tb_med_dept AS tb_dept
                        INNER JOIN $this->tb_med_avail AS med_avail ON med_avail.available_product_id=tb_dept.available_product_id
                        INNER JOIN $this->tb_med AS tb_med ON tb_med.product_encoder='$product_encoder'
                        WHERE tb_dept.department=$dept_nr
						AND tb_dept.ward_nr=$ward_nr";
            if($result1=$db->Execute($this->sql)){
                if ($this->count=$result1->FetchRow()) {
                    return $this->count;
                }
            }else return false;
        }
        
        //Lấy thông tin VTYT
        function getMedInfoDetail($product_encoder='', $dept_nr='', $ward_nr=''){
            global $db;
            $this->sql="SELECT tb_med.note AS note,tb_med.caution AS caution, loaivt.name_sub, tb_med.price
                        FROM $this->tb_med_dept AS tb_dept, $this->tb_med_avail AS med_avail, $this->tb_med AS tb_med, $this->tb_med_pro_sub AS loaivt
                        WHERE tb_dept.department=$dept_nr
								AND tb_dept.ward_nr=$ward_nr
                                AND loaivt.id=tb_med.id_sub
                                AND med_avail.available_product_id=tb_dept.available_product_id
                                AND tb_med.product_encoder='$product_encoder'";
            if($this->result=$db->Execute($this->sql)){
                if ($this->count=$this->result->FetchRow()) {
                    return $this->count;
                }
            }else return false;
        }
        
        function getInfoMedPrescription($batch_nr='', $dept_nr='', $ward_nr='', $date_request='',$encounter_nr=''){
            global $db;
            if($batch_nr=='aaa'){
                $this->sql="SELECT prescription_type,total_cost
                            FROM $this->tb_med_prescription_info 
                            WHERE dept_nr=$dept_nr 
                            AND ward_nr=$ward_nr
                            AND encounter_nr=$encounter_nr 
                            AND date_time_create='$date_request'";
            }else{
                $this->sql="SELECT prescription_info.prescription_id,test_request.encounter_nr,prescription_info.total_cost
                            FROM $this->tb_med_prescription_info AS prescription_info
                            LEFT JOIN $this->tb_test AS test_request ON test_request.batch_nr=$batch_nr AND test_request.encounter_nr=prescription_info.encounter_nr
                            WHERE dept_nr=$dept_nr
                            AND ward_nr=$ward_nr
                            AND test_request.date_request=prescription_info.date_time_create";
            }
            if($this->result=$db->Execute($this->sql)){
                if ($this->count=$this->result->FetchRow()) {
                    return $this->count;
                }
            }else return false;
        }
        
        //Tính tổng số lượng VTYT với mã tương ứng hiện có trong khoa
        function getnumberMed($product_encoder='',$dept_nr='', $ward_nr=''){
            global $db;
            $this->sql="SELECT SUM(tb_dept.available_number) AS number
                            FROM $this->tb_med_dept AS tb_dept, $this->tb_med_avail AS med_avail
                            WHERE tb_dept.department=$dept_nr
							AND tb_dept.ward_nr=$ward_nr
                            AND med_avail.available_product_id=tb_dept.available_product_id 
                            AND med_avail.product_encoder='$product_encoder'
                            AND CURDATE()< DATE(med_avail.exp_date)";
            if($this->result=$db->Execute($this->sql)){
                if ($this->count=$this->result->FetchRow()) {
                    return $this->count;
                }
            }else return false;
        }
        
        //Cho biết VTYT với mã tương ứng có đơn vị là gì(viên,hộp,...)
        function getunitMed($product_encoder=''){
            global $db;
            $this->sql="SELECT DISTINCT(tb_unit.unit_name_of_medicine) AS unit_name_of_medicine
                        FROM $this->tb_med AS tb_med, $this->tb_med_unit AS tb_unit
                        WHERE tb_unit.unit_of_medicine=tb_med.unit_of_medicine
                            AND tb_med.product_encoder='$product_encoder'";
            if($this->result=$db->Execute($this->sql)){
                if ($this->count=$this->result->FetchRow()) {
                    return $this->count;
                }
            }else return false;
        }
        
        function editNumberMedInDept($product_encoder='',$use='', $prescription_id='',$encounter_nr='', $dept_nr='', $ward_nr='',$price=''){
            global $db;
            if($product_encoder=='' && $prescription_id==''){
                return FALSE;
            }
            $location=array();
            $this->sql="SELECT tb_dept.available_number,tb_dept.ID
                            FROM $this->tb_med_dept AS tb_dept
                            LEFT JOIN $this->tb_med_avail AS med_avail ON med_avail.available_product_id=tb_dept.available_product_id
                            WHERE tb_dept.department=$dept_nr
								AND tb_dept.ward_nr=$ward_nr
								AND med_avail.product_encoder='$product_encoder'
								AND CURDATE()< DATE(med_avail.exp_date)";            
            if($this->result=$db->Execute($this->sql)){
                if ($this->count=$this->result->RecordCount()){
                    $i=0;
                    while($this->number=$this->result->FetchRow()){
                        $number_now=$this->number['available_number'];
                        $nr=$this->number['ID'];
                        $temp=$number_now - $use;
                        $check_exit=$this->checkMedPrescription($prescription_id,$product_encoder);
                        $number_use=$this->number_use;
                        $product_name=$this->getMedInfo($product_encoder, $dept_nr, $ward_nr);
                        $product_name=$product_name['product_name'];
                        if($temp<0){
                            if($check_exit=='' && $check_exit==0){
                                $cost=($number_now*$price);
                                $this->sql="INSERT $this->tb_med_prescription 
                                            SET prescription_id=$prescription_id, product_encoder='$product_encoder', product_name='$product_name', sum_number=$number_now, cost=$cost";
                            }else{
                                $cost=(($number_use+$use+$temp)*$price);
                                $this->sql="UPDATE $this->tb_med_prescription SET sum_number=($number_use+$use+$temp), cost=$cost WHERE nr=$this->nr1";
                            }
                            $this->query=$db->Execute($this->sql);
                            $this->updateAvailMedDept('0',$nr,$dept_nr,$ward_nr);
                            if($use!=0){
                                $location[$i]=$nr.'+'.$number_now.'-'.$cost;
                            }
                            $use=(-$temp);                            
                        }else{
                            if($check_exit=='' && $check_exit==0){
                                $cost=($use*$price);
                                $this->sql="INSERT $this->tb_med_prescription 
                                            SET prescription_id=$prescription_id, product_encoder='$product_encoder', product_name='$product_name', sum_number=$use, cost=$cost";
                            }else{
                                $cost=(($number_use+$use)*$price);
                                $this->sql="UPDATE $this->tb_med_prescription SET sum_number=($number_use+$use), cost=$cost WHERE nr=$this->nr1";
                            }
                            $this->query=$db->Execute($this->sql);
                            $this->updateAvailMedDept($temp,$nr,$dept_nr,$ward_nr);
                            if($use!=0){
                                $location[$i]=$nr.'+'.$use.'-'.$cost;
                            }
                            $use='0';                            
                        }
                        $i++;
                    }
                    return $location;
                }
            }
        }
        
        function UneditNumberMedInDept($product_encoder='', $use='',$prescription_id='', $encounter_nr='', $dept_nr='', $ward_nr='', $id='', $update_nr, $price=''){
            global $db;
            $this->sql="SELECT tb_dept.available_number
                            FROM $this->tb_med_dept AS tb_dept
                            LEFT JOIN $this->tb_med_avail AS med_avail ON med_avail.available_product_id=tb_dept.available_product_id
                            WHERE tb_dept.department=$dept_nr
								AND tb_dept.ward_nr=$ward_nr
								AND med_avail.product_encoder='$product_encoder'
								AND CURDATE()< DATE(med_avail.exp_date)
								AND tb_dept.ID=$id";
            if($this->result=$db->Execute($this->sql)){
                if ($this->number=$this->result->FetchRow()){                    
                    $number_now=$this->number['available_number'];                    
                    $check_exit=$this->checkMedPrescription($prescription_id,$product_encoder);
                    $number_use=$this->number_use;
                    if(($number_use - $update_nr)<0){
                        $temp=$update_nr-$number_use;
                    }else{
                        $temp=$number_use - $update_nr;
                    }
                    $this->updateAvailMedDept(($number_now+$update_nr),$id,$dept_nr,$ward_nr);
                    $cost=($temp*price);
                    $this->sql="UPDATE $this->tb_med_prescription SET sum_number=$temp, cost=$cost WHERE nr=$this->nr1";
                    $this->query=$db->Execute($this->sql);
                }
                return TRUE;
            }else{
                return FALSE;
            }            
        }
        
        function checkMedPrescription($prescription_id, $product_encoder){
            global $db;
            $this->sql="SELECT nr,sum_number
                        FROM $this->tb_med_prescription 
                        WHERE prescription_id=$prescription_id AND product_encoder='$product_encoder'";
            if($result1=$db->Execute($this->sql)){
                $this->count=$result1->RecordCount();
                $nr=$result1->FetchRow();
                $this->nr1=$nr['nr'];
                $this->number_use=$nr['sum_number'];
                return $this->count;
            }else return false;
        }
        
        function updateAvailMedDept($temp='', $nr='', $dept_nr='', $ward_nr=''){
            global $db;
            $sql="UPDATE $this->tb_med_dept SET available_number=$temp WHERE id=$nr AND department=$dept_nr AND ward_nr=$ward_nr";
            if($result1=$db->Execute($sql)){
                return $result1;
            }else return false;
        }
        
        function countnumberMed($dept_nr='', $ward_nr=''){
            global  $db;
            $this->sql="SELECT DISTINCT(med_avail.product_encoder) AS product_encoder 
                        FROM $this->tb_med_dept AS tk, $this->tb_med_avail AS med_avail
                        WHERE tk.department=$dept_nr
							AND tk.ward_nr=$ward_nr
							AND med_avail.available_product_id=tk.available_product_id";
            if($this->result=$db->Execute($this->sql)){
                if ($this->count=$this->result->RecordCount()) {
                    return $this->count;
                }
            }else return false;
        }
        
        function searchMedInfo($key,$dept_nr='',$ward_nr='',$limit=FALSE,$len=30,$so=0){
		global $db, $sql_LIKE;
		if(empty($key)) return FALSE;
        $oitem='product_encoder';
        $odir='ASC';      
		$this->sql= "SELECT DISTINCT(med_avail.product_encoder) AS product_encoder FROM $this->tb_med_dept AS tk, $this->tb_med_avail AS med_avail, $this->tb_med AS med, $this->tb_med_pro_sub AS type_med";
        if(is_numeric($key)){
			$key=(int)$key;
			$this->sql.=" WHERE med_avail.product_encoder = $key AND tk.department='$dept_nr' AND tk.ward_nr='$ward_nr'";
		}else{
			$this->sql.=" WHERE (med_avail.product_encoder $sql_LIKE '%$key%'
						          OR med.product_name $sql_LIKE '%$key%'
                                  OR type_med.name_sub $sql_LIKE '%$key%')
                                                AND tk.department=$dept_nr
												AND tk.ward_nr=$ward_nr
                                                AND med_avail.product_encoder=med.product_encoder
                                                AND tk.available_product_id=med_avail.available_product_id
                                                AND med.id_sub=type_med.id";
		}        
		$this->sql.=" ORDER BY med_avail.$oitem $odir";
                //chỉ hiện 30 record
		if($limit){
			$this->query=$db->SelectLimit($this->sql,$len,$so);
		}else{
			$this->query=$db->Execute($this->sql);
		}
                if ($this->query) {
                    if ($this->count=$this->query->RecordCount()) {
                            $this->count=$this->count; # Work around
                            return $this->query;
                    }else{return FALSE;}
		}else{return FALSE;}
	}        
}
?>