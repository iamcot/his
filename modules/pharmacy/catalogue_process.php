<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
//require($root_path.'include/core/inc_environment_global.php');

require_once($root_path.'include/care_api_classes/class_supplier.php');
require_once($root_path.'include/care_api_classes/class_product.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path.'include/core/inc_img_fx.php'); //file chứa hàm createComIcon

$img_obj=createComIcon($root_path,'pharmacy_object.png','0');
//load đơn vị tiền tệ
function load_currency($select)
{
    if ($select=='') $select=7;//mặc định là VND
    $selected_box='';
    $option_value='<option value="';
    $option_value1='">';
    $option_close='</option>';
             $selected_box='<select id="unit_of_price" name="unit_of_price" style="width:100%">';
         $_obj=new Product();
         $type_info=&$_obj->GetCurrency();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                   if ($type["item_no"]==$select)
                       $selected_box=$selected_box.'<option SELECTED value="'.$type["item_no"].$option_value1.$type["short_name"].$option_close;
                   else
                   $selected_box=$selected_box.$option_value.$type["item_no"].$option_value1.$type["short_name"].$option_close;
              }
         }
    $selected_box= $selected_box.'</select>';
    return $selected_box;
}
//load đơn vị tính
function load_unit($select)
{
    if ($select=='') $select='Vien';//mặc định là viên
    $selected_box='';
    $option_value='<option value="';
    $option_value1='">';
    $option_close='</option>';
    $selected_box='<select id="unit_name_of_medicine_input" name="unit_name_of_medicine_input" style="width:100%">';
    $_obj=new Product();
    $type_info=&$_obj->GetMedicineUnit();
    //đưa dữ liệu ra chuỗi
     if (is_object($type_info))
     {
           while($type=$type_info->FetchRow())
          {
               if ($type["unit_of_medicine"]==$select) 
                   $selected_box=$selected_box.'<option SELECTED value="'.$type["unit_of_medicine"].$option_value1.$type["unit_name_of_medicine"].$option_close;
               else
               $selected_box=$selected_box.$option_value.$type["unit_of_medicine"].$option_value1.$type["unit_name_of_medicine"].$option_close;
          }
     }
    $selected_box= $selected_box.'</select>';
    return $selected_box;
}

//load don vi vtyt
function load_unit_medipot($select)
{
    $selected_box='';
    $option_value='<option value="';
    $option_value1='">';
    $option_close='</option>';
    $selected_box='<select id="unit_name_of_medicine_input" name="unit_name_of_medicine_input" style="width:100%">';
    $_obj=new Product();
    $type_info=&$_obj->GetMedipotUnit();
    //đưa dữ liệu ra chuỗi
     if (is_object($type_info))
     {
           while($type=$type_info->FetchRow())
          {
			   if ($type["unit_of_medicine"]==$select)
                   $selected_box=$selected_box.'<option SELECTED value="'.$type["unit_of_medicine"].$option_value1.$type["unit_name_of_medicine"].$option_close;
               else
               $selected_box=$selected_box.$option_value.$type["unit_of_medicine"].$option_value1.$type["unit_name_of_medicine"].$option_close;
          }
     }
    $selected_box= $selected_box.'</select>';
    return $selected_box;
}
function load_group_medipot($select)
{
    $selected_box='';
    $option_value='<option value="';
    $option_value1='">';
    $option_close='</option>';
    $selected_box='<select id="group_of_medipot_input" name="group_of_medipot_input" style="width:100%">';
    $_obj=new Product();
    $type_info=&$_obj->GetMedipotGroup();
    //đưa dữ liệu ra chuỗi
     if (is_object($type_info))
     {
        while($type=$type_info->FetchRow())
        {
            if ($type["id"]==$select)
                $selected_box=$selected_box.'<option SELECTED value="'.$type["id"].$option_value1.$type["name_sub"].$option_close;
            else
				$selected_box=$selected_box.$option_value.$type["id"].$option_value1.$type["name_sub"].$option_close;
        }
			   

     }
    $selected_box= $selected_box.'</select>';
    return $selected_box;
}
//---------------------HÀM TRUY VẤN CƠ SỞ DỮ LIỆU -> TRẢ VỀ 1 TRANG CỦA DANH MỤC (PHÂN TRANG)--------------------
function load_catalogue1($catalogue,$quick,$attribute, $group_key, $records_in_page, $current_page, $total_records, $page)
{
    GLOBAL $img_obj;
    $records=0;
    $begin=($current_page-1)*$records_in_page + 1;//khởi tạo kết quả đầu tiên của trang';
    $end=$current_page*$records_in_page;
    //phần trước nội dung danh mục (mở ô mới + link)
    $before='<tr height=40px>
                <td style="border-bottom: solid 1px #C3C3C3;">
                    <img '.$img_obj.'/>
                </td>
                <td style="border-bottom: solid 1px #C3C3C3;" >
                    <a href="javascript:select_obj(';
    $before2='<td style="border-bottom: solid 1px #C3C3C3;"><img '.$img_obj.'/></td><td style="border-bottom: solid 1px #C3C3C3;" ><a href="javascript:select_obj(';
    //phần chỉnh font cho danh mục
    $font_before='"><font color=#003399><font style="font-size:16px; font-family:Arial;"><div>';
    //kết thúc phần chỉnh font
    $font_after='&nbsp;</div></font>';
    //phần sau nội dung danh mục (đóng ô + link)
    $after='</font></a></td><td ></td>';
    $after2='</font></a></td></tr>';
    //thông tin truyền vào chung
    $general_info=$this_file.URL_APPEND.'&mode=view&catalogue='.$catalogue.'&cond='.$quick.'&quick='.$quick.'&search='.$search;
    if ($catalogue=='supplier')
    {
        if($flag!=1){
            $_obj=new Supplier();
            $sCatalogueInfo=&$_obj->GetSupplierCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute);   
        }else{
            $_obj=new Product();
            $sCatalogueInfo=&$_obj->GetChemicalCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute);
        }         
    }
    else
    {
         $_obj=new Product();
        if ($catalogue=='generic_drug')
        {            
             $sCatalogueInfo=&$_obj->GetGenericDrugCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute);
        }
        else
        {       
                if ($catalogue=='medicine')
                {
                    $sCatalogueInfo=&$_obj->GetMedicineCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute);
                }
                else
                    if ($catalogue=='vnmedicine')
                    {
                        $sCatalogueInfo=&$_obj->GetVnMedicineCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2,  $general_info, $catalogue, $group_key, $attribute);
                    }
                    else
                        if ($catalogue=='medipot')
                        {
                            $sCatalogueInfo=&$_obj->GetMedipotCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute);

                        }else
                            if ($catalogue=='chemical')
                            {
                                if($page){
                                    $current_page=$page;
                                }
                                $sCatalogueInfo=&$_obj->GetChemicalCatalogue($quick, $current_page, &$total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute);

                            }
        }
    }
return '<TABLE width=90% style="height:auto;" border=0 cellpadding="0" cellspacing=0 valign="top">
                    <TBODY>
                            <TR height=10px>
                                    <TD width=4%>
                                    </TD>
                                    <TD width=41%>
                                    </TD>
                                    <TD width=5%>
                                    </TD>
                                    <TD width=4%>
                                    </TD>
                                    <TD width=41%>
                                    </TD>
                            </TR>'.$sCatalogueInfo.'</TBODY></TABLE>';
}
//-------------HÀM TRUY VẤN CƠ SỞ DỮ LIỆU -> TRẢ VỀ SELECT BOX---------------
function load_select_box1($catalogue, $select)
{
    $selected_box='';
    $option_value='<option value="';
    $option_value1='">';
    $option_close='</option>';
    //------------------------SUPPLIER-------------------------
     if ($catalogue=='supplier')
    {
         $selected_box='<select id="type_name_of_supplier_input" name="type_of_supplier" style="width:100%">';
         $_obj=new Supplier();
         $type_info=&$_obj->GetAllSupplierType();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                    if ($type["type_of_supplier"]==$select)
                         $selected_box=$selected_box.$option_value.$type["type_of_supplier"].'" SELECTED >'.$type["type_name_of_supplier"].$option_close;
                    else
                         $selected_box=$selected_box.$option_value.$type["type_of_supplier"].$option_value1.$type["type_name_of_supplier"].$option_close;
              }
         }
    }
    if ($catalogue=='generic_drug')
    {
         $tempselect=explode('__',$select);
		 $selected_box='<select id="pharma_group_name_input" name="pharma_group_id" style="width:100%"  onChange="OnChange_GroupNameSub()">';
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                    if ($type["pharma_group_id"]==$tempselect[0])
                        $selected_box=$selected_box.$option_value.$type["pharma_group_id"].'" SELECTED >'.$type["pharma_group_name"].$option_close;
                    else
                        $selected_box=$selected_box.$option_value.$type["pharma_group_id"].$option_value1.$type["pharma_group_name"].$option_close;
              }
         }
		 
		 $selected_box_sub='<select id="pharma_group_name_sub_input" name="pharma_group_id_sub" style="width:100%">';
		 $type_sub = $_obj->ListPharmaGroupNameSub($tempselect[0]);
         if (is_object($type_sub))
         {
               while($name_sub=$type_sub->FetchRow())
              {
                    if ($name_sub["pharma_group_id_sub"]==$tempselect[1])
                        $selected_box_sub=$selected_box_sub.'<option value="'.$name_sub["pharma_group_id_sub"].'" selected >'.$name_sub["pharma_group_name_sub"].'</option>';
                    else
                        $selected_box_sub=$selected_box_sub.'<option value="'.$name_sub["pharma_group_id_sub"].'">'.$name_sub["pharma_group_name_sub"].'</option>';
              }
         }		 
		$selected_box=$selected_box.'</select>@@@'.$selected_box_sub;
    }
    if ($catalogue=='medicine' || $catalogue=='vnmedicine')
    {
         if ($select=='') $select=17;//mặc định là viên
         if ($table==0) $selected_box='<select id="type_name_of_medicine_input" name="type_of_medicine" style="width:100%">';
         else $selected_box='<select width=40>';
         $_obj=new Product();
         $type_info=&$_obj->GetMedicineType();
         //dua du lieu ra chuoi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                   if ($type["type_of_medicine"]==$select)
                        $selected_box=$selected_box.'<option SELECTED value="'.$type["type_of_medicine"].$option_value1.$type["type_name_of_medicine"].$option_close;
                   else
                        $selected_box=$selected_box.$option_value.$type["type_of_medicine"].$option_value1.$type["type_name_of_medicine"].$option_close;
              }
         }
    }
    if ($catalogue=='chemical')
    {
         if ($select=='') $select=1;//mặc định là Chai
         if ($table==0) $selected_box='<select id="type_name_of_medicine_input" name="type_of_chemical" style="width:100%">';
         else $selected_box='<select width=40>';
         $_obj=new Product();
         $type_info=&$_obj->GetChemicalType();
         //dua du lieu ra chuoi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                   if ($type["type_of_chemical"]==$select)
                        $selected_box=$selected_box.'<option SELECTED value="'.$type["type_of_chemical"].$option_value1.$type["type_name_of_chemical"].$option_close;
                   else
                        $selected_box=$selected_box.$option_value.$type["type_of_chemical"].$option_value1.$type["type_name_of_chemical"].$option_close;
              }
         }
    }
    $selected_box= $selected_box.'</select>';
    return $selected_box;
}

    //-------------Hoa chat---------------    
    //load don vi hoa chat
    function load_unit_Chemical($select)
    {
        $selected_box='';
        $option_value='<option value="';
        $option_value1='">';
        $option_close='</option>';
        $selected_box='<select id="unit_name_of_chemical_input" name="unit_name_of_chemical_input" style="width:100%">';
        $_obj=new Product();
        $type_info=&$_obj->GetChemicalUnit();
        //đưa dữ liệu ra chuỗi
        if (is_object($type_info))
        {
            while($type=$type_info->FetchRow())
            {
                if ($type["unit_of_chemical"]==$select)
                    $selected_box=$selected_box.'<option SELECTED value="'.$type["unit_of_chemical"].$option_value1.$type["unit_name_of_chemical"].$option_close;
                else
                $selected_box=$selected_box.$option_value.$type["unit_of_chemical"].$option_value1.$type["unit_name_of_chemical"].$option_close;
            }
        }
        $selected_box= $selected_box.'</select>';
        return $selected_box;
    }
    function load_group_Chemical($select)
    {
        $selected_box='';
        $option_value='<option value="';
        $option_value1='">';
        $option_close='</option>';
        $selected_box='<select id="group_of_chemical_input" name="group_of_chemical_input" style="width:100%">';
        $_obj=new Product();
        $type_info=&$_obj->GetChemicalGroup();
        //đưa dữ liệu ra chuỗi
        if (is_object($type_info))
        {
            while($type=$type_info->FetchRow())
            {
                if ($type["chemical_group_id"]==$select)
                    $selected_box=$selected_box.'<option SELECTED value="'.$type["chemical_group_id"].$option_value1.$type["chemical_group_name"].$option_close;
                else
                    $selected_box=$selected_box.$option_value.$type["chemical_group_id"].$option_value1.$type["chemical_group_name"].$option_close;
            }


        }
        $selected_box= $selected_box.'</select>';
        return $selected_box;
    }
?>
