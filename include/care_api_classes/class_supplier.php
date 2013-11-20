<?php
/**
* @package care_api
*/
/**
*/
require_once($root_path.'include/care_api_classes/class_core.php');
/**
*  Supplier methods. 
*
* Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
* @author Gjergj Sheldija
* @version beta 1.0.1
* @copyright 2006 Gjergj Sheldija
* @package care_api
*/
class Supplier extends Core {
	/**#@+
	* @access private
	* @var string
	*/
	/**
	* Table name for medical depot main products
	*/
	var $tb_mmain='care_supplier';
	var $tb='care_supplier';
	var $supplier_count;
	/**#@-*/
	
	/**
	* Field names of care_pharma_products_main or care_med_products_main tables
	* @var array
	*/
	var $fld_furnmain=array('idcare_supplier',
										'supplier',
										'address',
										'telehone',
										'faxp',
										'postal_code',
										'representative',
                                        'supplier_name',
                                         'note',
                                        'type_of_supplier',
                                         'in_use');

	/**
	* Constructor
	*/				
	function Supplier(){
	}
	/**
	* Sets the core object to point  to either care_pharma_products_main or care_med_products_main table and field names.
	*
	* The table is determined by the parameter content. 
	* @access public
	* @param string Determines the final table name 
	* @return boolean.
	*/
	function useSupplier($type){
		$this->coretable=$this->tb_mmain;
		$this->ref_array=$this->fld_furnmain;
	}
	/**
	* Gets all data from the care_supplier table
	* @access private
	* @param string WHERE condition of the sql query
	* @param string Sort item
	* @param string  Determines the return type whether adodb object (_OBJECT) or assoc array (_ARRAY, '', empty) 
	* @return mixed boolean or adodb record object or assoc array, determined by param $ret_type
	*/
	function _getalldata($cond='1',$sort='',$ret_type=''){
	    global $db;
		if(empty($sort)) $sort='supplier';
		$this->sql="SELECT * FROM $this->tb WHERE $cond ORDER BY $sort";
                //edit by d_s
                // chẳng hiểu người ta return cái quái gì mà nó ko ra kết quả -> mạn phép sửa lại :))
               
                return ($db->Execute($this->sql));

                //file cũ
                /*
		if ($this->res['_gald']=$db->Execute($this->sql)) {
		    if ($this->dept_count=$this->res['_gald']->RecordCount()){
				$this->rec_count=$this->dept_count;
		        if($ret_type=='_OBJECT') return $this->res['_gald'];
					else return $this->res['_gald']->GetArray();
			}else{
				return FALSE;
			}
		}else{
		    return FALSE;
		}
                 * 
                 */
	}	
	/**
	* Gets all funitoret without condition. The result is assoc array sorted by departments formal name
	* @access public
	* @return mixed boolean or adodb record object or assoc array
	*/
	function getAllSupplier() {
		return $this->_getalldata('1');
	}	
	
	/**
	* Saves (inserts)  an item in the order catalog.
	*
	* The data must be passed by reference with associative array.
	* Data must have the index keys as outlined in the <var>$fld_ocat</var> array.
	* @access public
	* @param array Data to save
	* @param string Determines the final table name 
	* @return boolean
	*/
	function SaveSupplierItem(&$data,$type){
		if(empty($type)) return false;
		$this->useSupplier($type);
		$this->data_array=&$data;
		return $this->insertDataFromInternalArray();
	}
	/**
	* Checks if the supplier exists based on its primary key number.
	* @access public
	* @param int Item number
	* @param string Determines the final table name 
	* @return boolean
	*/
	function SupplierExists($nr=0,$type=''){
		global $db;
		if(empty($type)||!$nr) return false;
		$this->useSupplier($type);
		$this->sql="SELECT supplier FROM $this->coretable WHERE supplier='$nr'";

        if($buf=$db->Execute($this->sql)) {
            if($buf->RecordCount()) {
				return true;
			} else { return false; }
		} else { return false; }
	}

	/**
	* Returns the supplier name based on it's primary key
	* @access public
	* @param int Item number
	* @param string Determines the final table name 
	* @return boolean
	*/
	function FormalName($nr=0){
		global $db;
		if ($this->result=$db->Execute("SELECT supplier FROM care_supplier WHERE idcare_supplier='$nr'")) {
		    if ($this->result->RecordCount()) {
		    	$row=$this->result->FetchRow();
			    return $row['supplier'];
			} else {
				return FALSE;
			}
		}
		else {
		    return FALSE;
		}
	}
	
	/**
	* Returns the data of the selected supplier based on it's primary key
	* @access public
	* @param int Item number
	* @param string Determines the final table name 
	* @return boolean
	*/
	function ReturnSupplierData($nr=0){
		global $db;
		if ($this->result=$db->Execute("SELECT * FROM care_supplier WHERE idcare_supplier='$nr'")) {
		    if ($this->result->RecordCount()) {
		    	$row=$this->result->FetchRow();
			    return $row;
			} else {
				return FALSE;
			}
		}
		else {
		    return FALSE;
		}
	}


 //**********************************************************************************************************************************
       
	//function made by d_s (Bình Minh)
	
        function GetSupplierInfo($supplier,$username){
	    global $db;
		$this->sql="SELECT supplier, supplier_name, address, telephone, fax, note,in_use, type_name_of_supplier, $this->tb.type_of_supplier FROM $this->tb, care_pharma_type_of_supplier WHERE supplier = '".$supplier."' and care_pharma_type_of_supplier.type_of_supplier = $this->tb.type_of_supplier";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }
        //lấy toàn bộ danh mục theo điều kiện
        function GetAllSupplierInfo($quick, $begin, $total_records, $records_in_page, $group_key, $attribute){
	    global $db;
                 if ($quick!='')
                 {
                     $cond="(supplier LIKE '".$quick."%' or supplier LIKE '% ".$quick."%') ";
                 }
                if ($group_key=='')
                {
                    if ($cond!='')
                    $records=$db->Execute("SELECT COUNT(*) AS records FROM $this->tb WHERE ".$cond)->FetchRow();
                    else $records=$db->Execute("SELECT COUNT(*) AS records FROM $this->tb")->FetchRow();
                    $total_records=$records["records"];
                    if ($cond!='')
                        $this->sql="SELECT supplier, supplier_name FROM $this->tb WHERE ".$cond."ORDER BY supplier LIMIT $begin, $records_in_page";
                    else
                        $this->sql="SELECT supplier, supplier_name FROM $this->tb ORDER BY supplier LIMIT $begin, $records_in_page";
                }
                else
                {
                    if ($cond!='')
                    $records=$db->Execute("SELECT COUNT(*) AS records FROM $this->tb WHERE ".$cond." and type_of_supplier=$group_key")->FetchRow();
                    else $records=$db->Execute("SELECT COUNT(*) AS records FROM $this->tb  WHERE type_of_supplier=$group_key")->FetchRow();
                    $total_records=$records["records"];
                    if ($cond!='')
                        $this->sql="SELECT supplier, supplier_name FROM $this->tb WHERE ".$cond." and type_of_supplier=$group_key"." ORDER BY supplier LIMIT $begin, $records_in_page";
                    else
                        $this->sql="SELECT supplier, supplier_name FROM $this->tb WHERE type_of_supplier=$group_key ORDER BY supplier LIMIT $begin, $records_in_page";
                }

//                echo($this->sql);
                return ($db->Execute($this->sql));
        }
        //lấy 1 trang của danh mục (theo điều kiện)
        function GetSupplierCatalogue($quick, $current_page,$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute){
	    global $db;
                $records=0;//khởi tạo số kết quả = 0;
                $begin=($current_page-1)*$records_in_page;//khởi tạo kết quả đầu tiên của trang
                $catalogue_info=$this->GetAllSupplierInfo($quick, $begin, &$total_records, $records_in_page, $group_key, $attribute);
                 if (is_object($catalogue_info))
                 {
                       $i=0;
                       while($object=$catalogue_info->FetchRow())
                      {
                           $i++;
                           if ($i%2!=0)
                             $sCatalogueInfo = $sCatalogueInfo.$before."'$catalogue'".",'".$object["supplier"]."','".$current_page."')".'" '.'title="'.$object['supplier_name'].$font_before.$object['supplier'].$font_after.$after;
                           else
                             $sCatalogueInfo = $sCatalogueInfo.$before2."'$catalogue'".",'".$object["supplier"]."','".$current_page."')".'" '.'title="'.$object['supplier_name'].$font_before.$object['supplier'].$font_after.$after2;
                      }
                 }
         return $sCatalogueInfo;
        }
        //lấy thông tin của kết quả tìm kiếm đầu tiên
         function GetFirstSupplierCatalogue($quick, $group_key, $attribute){
	    global $db;
                 $catalogue_info=$this->GetAllSupplierInfo($quick, 0, $total_records, 1, $group_key, $attribute);
                 if (is_object($catalogue_info))
                 {
                      $object=$catalogue_info->FetchRow();
                      $supplier=$object["supplier"];
                      //echo $supplier;
                 }
         return $this->GetSupplierInfo($supplier,'');
        }
        function AddSupplier($supplier, $supplier_name,$type_of_supplier,$address,$telephone,$fax,$note){
	    global $db;
		$this->sql="insert into care_supplier (supplier, supplier_name, address, telephone, note, type_of_supplier, create_id, create_time, in_use, history) value ('".$supplier."','".$supplier_name."','".$address."','".$telephone."','".$note."',".$type_of_supplier.",'','',1,'')";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }
        function EditSupplier($supplier, $supplier_name, $type_of_supplier, $address, $telephone, $fax, $note, $user)
        {
            global $db;
		$this->sql="UPDATE care_supplier
                            SET supplier_name = '".$supplier_name."', address = '".$address."', telephone = '".$telephone."', fax = '".$fax."', note ='".$note."',
                            type_of_supplier = ".$type_of_supplier."
                            WHERE supplier = '".$supplier."'";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }
        function DeleteSupplier($supplier)
        {
            global $db;
			$this->sql="DELETE FROM care_supplier
                            WHERE supplier = '".$supplier."'";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }		
        function GetAllSupplierType(){
	    global $db;
		$this->sql="SELECT type_of_supplier,type_name_of_supplier FROM care_pharma_type_of_supplier ORDER BY type_of_supplier";
               //echo($this->sql);
                return ($db->Execute($this->sql));
        }
        function GetSupplierType($group_key){
	    global $db;
		$this->sql="SELECT type_of_supplier,type_name_of_supplier FROM care_pharma_type_of_supplier WHERE type_of_supplier=$group_key ORDER BY type_of_supplier";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }
        
        function GetSupplierChemicalInfo($supplier,$username){
	    global $db;
		$this->sql="SELECT supplier, supplier_name, address, telephone, fax, note,in_use, type_name_of_supplier, $this->tb.type_of_supplier FROM $this->tb, care_chemical_type_of_supplier WHERE supplier = '".$supplier."' and care_chemical_type_of_supplier.type_of_supplier = $this->tb.type_of_supplier";
                //echo($this->sql);
                return ($db->Execute($this->sql));
        }

        
}
?>
