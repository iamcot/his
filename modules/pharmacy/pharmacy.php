<html><body onload="init_load()"></body><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> </html>
<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    /**
    * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
    * GNU General Public License
    * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
    * elpidio@care2x.org,
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    $lang_tables=array('departments.php');
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/care_api_classes/class_product.php');
    // Erase all cookies used for 2nd level script locking, all following scripst will be locked
    // reset all 2nd level lock cookies
    //require($root_path.'include/core/inc_2level_reset.php');

    if(!isset($_SESSION['sess_path_referer'])) $_SESSION['sess_path_referer'] = "";
    if(!isset($_SESSION['sess_user_origin'])) $_SESSION['sess_user_origin'] = "";

   // $thisfile= basename(__FILE__);
    $breakfile=$root_path.'main/logout_confirm.php'.URL_APPEND."&sid=$sid";

    $_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
    $_SESSION['sess_user_origin']='pharma';
    require ($root_path.'include/care_api_classes/class_access.php');
    $access = new Access($_SESSION['sess_login_userid'],$_SESSION['sess_login_pw']);
    $hideOrder = 0;
    if(ereg("_a_1_pharmadbadmin",$access->PermissionAreas()))
            $hideOrder = 1;

    $this_file="apotheke.php";

?>
<script language="javascript">

    function closewin() {
            location.href='startframe.php?sid=<?php echo "$sid&lang=$lang";?>';
    }
    function set_height_div()
    {
        var myWidth = 0, myHeight = 0;
        if( typeof( window.innerWidth ) == 'number' ) {
                    //Non-IE
                    myWidth = window.innerWidth;
                    myHeight = window.innerHeight;
            } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
                    //IE 6+ in 'standards compliant mode'
                    myWidth = document.documentElement.clientWidth;
                    myHeight = document.documentElement.clientHeight;
            } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
                    //IE 4 compatible
                    myWidth = document.body.clientWidth;
                    myHeight = document.body.clientHeight;
            }
    //        document.write(myWidth);
            document.getElementById("frame2").style.height=myHeight-50+"px";
            if (myWidth-300>984)
                document.getElementById("frame2").style.width=myWidth-300+"px";
            else document.getElementById("frame2").style.width=984+"px";

    }
    //load lúc khởi động
    function init_load()
    {
        set_height_div();
    }

</script>

<?php
function load_dropdownmenu($Name)
{
    GLOBAL $catalogue;
    GLOBAL $quick;  GLOBAL $attribute; GLOBAL $group_key;
     if ($group_key!='')
            $dropdown_menu='<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick",'','').'">'.$Name.'</a></li>';
        //------------------------NHA CUNG CAP-------------------------
     if ($catalogue=='supplier')
    {
         if ($group_key=='')
         $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$Name.'</span></a> <ul class="sub">';
         $_obj=new Supplier();
         $type_info=&$_obj->GetAllSupplierType();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                  if ($type["type_of_supplier"]!=$group_key)
                        $dropdown_menu=$dropdown_menu.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["type_of_supplier"],'','').'">'.$type["type_name_of_supplier"].'</a></li>';
                  else
                  $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["type_of_supplier"],'','').'" id="dropdown menu" class="top_link"><span class="down">'.$type["type_name_of_supplier"].'</span></a> <ul class="sub">';
              }
         }
    }
	//------------------------THUOC GOC / THUOC TAY Y-------------------------
     if ($catalogue=='generic_drug' || $catalogue=='medicine')
    {
         if ($group_key=='')
         $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$Name.'</span></a> <ul class="sub">';
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                   if ($type["pharma_group_id"]!=$group_key)
                        $dropdown_menu=$dropdown_menu.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["pharma_group_id"],'','').'">'.$type["pharma_group_name"].'</a></li>';
                   else
                        $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["pharma_group_id"],'','').'" id="dropdown menu" class="top_link"><span class="down">'.$type["pharma_group_name"].'</span></a> <ul class="sub">';
              }
         }
    }
	
	//----Thuoc Dong Y va VTYT ko co Nhom Thuoc tren Dropdown menu

    $dropdown_menu=$dropdown_menu_temp.$dropdown_menu.'</ul></li></ul>';
    return $dropdown_menu;
}
//****************************************************************************************************************************************
/**
 * LOAD Smarty
 */

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',$LDPharmacy);

 $smarty->assign('breakfile',$breakfile);

 $smarty->assign('Name',$LDPharmacy);

 if(isset($stb) && $stb) $smarty->assign('sOnLoadJs','onLoad="startbot()"');

 ob_start();
$sTemp = ob_get_contents();
ob_end_clean();

// Append javascript to JavaScript block

 $smarty->append('JavaScript',$sTemp);

// Prepare the submenu icons

// $aSubMenuIcon=array(createComIcon($root_path,'bestell.gif','0'),
//					createComIcon($root_path,'help_tree.gif','0'),
//					createComIcon($root_path,'templates.gif','0'),
//					createComIcon($root_path,'documents.gif','0'),
//					createComIcon($root_path,'storage.gif','0'),
//					createComIcon($root_path,'sitemap_animator.gif','0'),
//					createComIcon($root_path,'bubble.gif','0'),
//					createComIcon($root_path,'redlist.gif','0'),
//					createComIcon($root_path,'pharma.jpg','0'),	//Tuyen-- cap phat thuoc
//					createComIcon($root_path,'pharma.jpg','0')
//					);

// Prepare the submenu item descriptions
//
//					$LDReporteFarmaciTxt,
//					$LDRequestMedicinePatientTxt, //Tuyen-- cap phat thuoc
//					$LDRequestMedicineWardTxt
//					);

// Prepare the submenu item links indexed by their template tags
//img
 $img_put_in=createComIcon($root_path,'bestell.gif','0');
 $img_pay_out=createComIcon($root_path,'bestell.gif','0');
 $img_catalogue=createComIcon($root_path,'bestell.gif','0');
 $img_allocation=createComIcon($root_path,'bestell.gif','0');
 $img_report=createComIcon($root_path,'bestell.gif','0');


 $smarty->assign('LDTitlePutIn', $LDTitlePutIn);
 $smarty->assign('LDTitleCatalogue', $LDTitleCatalogue);
  
 //Menu nhap thuoc + hoa chat + VTYT
 $smarty->assign('LDPharmaPutInMedicine', '<a href="putin.php'.URL_APPEND.'&type=medicine" title="'.$LDPharmaPutInMedicineTxt.'">'.$LDPharmaPutInMedicine.'</a>');
 $smarty->assign('LDPharmaPutInChemical', '<a href="putin_chemical.php'.URL_APPEND.'&type=chemical" title="'.$LDPharmaPutInChemicalTxt.'">'.$LDPharmaPutInChemical.'</a>');
 $smarty->assign('LDPharmaPutInMedipot', '<a href="putin_medipot.php'.URL_APPEND.'&type=medipot" title="'.$LDPharmaPutInMedipotTxt.'">'.$LDPharmaPutInMedipot.'</a>');
 $smarty->assign('LDPharmaPutInImg', $img_put_in);
 
 //Menu xuat thuoc + hoa chat + VTYT
 $smarty->assign('LDPharmaPayOutMedicine', '<a href="payout.php'.URL_APPEND.'&type=medicine" title="'.$LDPharmaPayOutMedicineTxt.'" >'.$LDPharmaPayOutMedicine.'</a>');
 $smarty->assign('LDPharmaPayOutChemical', '<a href="payout_chemical.php'.URL_APPEND.'&type=chemical" title="'.$LDPharmaPayOutChemicalTxt.'" >'.$LDPharmaPayOutChemical.'</a>');
 $smarty->assign('LDPharmaPayOutMedipot', '<a href="payout_medipot.php'.URL_APPEND.'&type=medipot" title="'.$LDPharmaPayOutMedipotTxt.'" >'.$LDPharmaPayOutMedipot.'</a>');
 $smarty->assign('LDPharmaPayOutImg',$img_pay_out );
 
 //Menu Cap phat hang ngay
 $smarty->assign('LDPharmaAllocation', '<a href="allocation.php'.URL_APPEND.'" title="'.$LDPharmaAllocationTxt.'" >'.$LDPharmaAllocation.'</a>');
 $smarty->assign('LDPharmaAllocationImg',$img_allocation );
 
  //Bao cao thong ke
 $smarty->assign('LDTitleReport', $LDTitleReport);
  $smarty->assign('LDPharmaReportImg', $img_report);
 //Bao cao Nhap Xuat Ton
 $smarty->assign('LDPharmaReportKhoChan', '<a href="report_khochan.php'.URL_APPEND.'" title="'.$LDPharmaReportKhoChanTxt.'" >'.$LDKhoChan.'</a>');
 //Bao cao su dung
 $smarty->assign('LDPharmaReportKhoLe', '<a href="report_khole.php'.URL_APPEND.'" title="'.$LDPharmaReportKhoLeTxt.'" >'.$LDKhoLe.'</a>');
 
 //Danh muc thuoc Tay Y
 $smarty->assign('LDMedicineCatalogue', '<a href="catalogue_chemical.php'.URL_APPEND."&catalogue=medicine".'" title="'.$LDMedicineCatalogueTxt.'" >'.$LDMedicineCatalogue.'</a>');
 $smarty->assign('LDMedicineCatalogueImg', $img_catalogue);
 
 //Danh muc thuoc Dong Y
 $smarty->assign('LDVNMedicineCatalogue', '<a href="catalogue_chemical.php'.URL_APPEND."&catalogue=vnmedicine".'" title="'.$LDVNMedicineCatalogueTxt.'" >'.$LDVNMedicineCatalogue.'</a>');
 $smarty->assign('LDVNMedicineCatalogueImg', $img_catalogue);
 
 //Danh muc Hoa chat
 $smarty->assign('LDChemicalCatalogue', '<a href="catalogue_chemical.php'.URL_APPEND."&catalogue=chemical".'" title="'.$LDChemicalCatalogue.'" >'.$LDChemicalCatalogue.'</a>');
 $smarty->assign('LDChemicalCatalogueImg', $img_catalogue);
 //Danh muc VTYT
 $smarty->assign('LDMedipotCatalogue', '<a href="catalogue_chemical.php'.URL_APPEND."&catalogue=medipot".'" title="'.$LDMedipotCatalogueTxt.'" >'.$LDMedipotCatalogue.'</a>');
 $smarty->assign('LDMedipotCatalogueImg', $img_catalogue);
 
 //Danh muc thuoc goc
 $smarty->assign('LDGenericDrugCatalogue', '<a href="catalogue_chemical.php'.URL_APPEND."&catalogue=generic_drug".'" title="'.$LDGenericDrugCatalogueTxt.'" >'.$LDGenericDrugCatalogue.'</a>');
 $smarty->assign('LDGenericDrugCatalogueImg', $img_catalogue);
 
 //Danh muc nha cung cap
 $smarty->assign('LDSupplierCatalogue', '<a href="catalogue_chemical.php'.URL_APPEND."&catalogue=supplier".'" title="'.$LDSupplierCatalogueTxt.'" >'.$LDSupplierCatalogue.'</a>');
 $smarty->assign('LDSupplierCatalogueImg', $img_catalogue);

 //Group
 //$smarty->assign('LDGroupMedicineCatalogue', $LDGroupMedicineCatalogue);
 //$smarty->assign('LDGroupMedicineCatalogueImg', $img_catalogue);
 //$smarty->assign('LDGroupChemicalCatalogueImg', $img_catalogue);
 
 //Danh mục Nhóm, đơn vị
 //Unit
 $smarty->assign('LDTitleUnit', $LDTitleGroup);
 $smarty->assign('LDPharmaUnitImg', $img_catalogue);
 $smarty->assign('LDGroupCatalogue', '<a href="group_unit/listgroup.php'.URL_APPEND.'" >'.$LDGroupCatalogue.'</a>');
 $smarty->assign('LDPharmaTypeMedicine', '<a href="group_unit/listtype.php'.URL_APPEND.'" >'.$LDPharmaTypeMedicine.'</a>');
 $smarty->assign('LDPharmaUnit', '<a href="group_unit/listunit.php'.URL_APPEND.'" >'.$LDPharmaUnit.'</a>');
 $smarty->assign('LDPharmaTramYTe', '<a href="group_unit/tramyte.php'.URL_APPEND.'" >'.$LDPharmaTramYTe.'</a>');


 $smarty->assign('TitleTable', $TitleTable);
 $smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineID',$LDMedicineID1);
$smarty->assign('LDMedicineName',$LDMedicineName1);
$smarty->assign('LDUnit',$LDUnit1);
$smarty->assign('LDLotID',$LDLotID);
$smarty->assign('LDExpDate',$LDExpDate);
$smarty->assign('LDNumber',$LDNumber1);
$smarty->assign('LDNote',$LDNote1);
$i=0;

//MAN HINH CHINH: LIET KE CAC THUOC TRONG KHO CHAN
$sTempDiv="";
//$list=new Product();
//if($result=$list->ShowExp()){	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! trong tat ca khoa???
//	while ($temp_result=$result->FetchRow())
//	{
//    $i++;
//	$temp_today = strtotime(date("Y-m-d"));
//	$temp_exp_date = strtotime($temp_result['exp_date']);
//	if($temp_exp_date < $temp_today)
//		$bgc="#FF9966";
//	else $bgc="#ffffff";
//    $sTempDiv=$sTempDiv.'<tr style="height:25px;" bgColor="'.$bgc.'">
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;">'.$i.'</td>
//                            <td style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;"><a href="'."catalogue.php".URL_APPEND."&catalogue=medicine&obj_primary=".$temp_result["product_encoder"].'">'.$temp_result["product_name"].'</a>'.'</td>
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;">'.$temp_result["unit_name_of_medicine"].'</td>
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;">'.$temp_result["product_encoder"].'</td>
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;">'.$temp_result["product_lot_id"].'</td>
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;">'.$temp_result["DAY(exp_date)"].'/'.$temp_result["MONTH(exp_date)"].'/'.$temp_result["YEAR(exp_date)"].'</td>
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;">'.$temp_result["available_number"].'</td>
//                            <td align="center" style="border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;">'.$$temp_result['LD_var'].'</td>
//                        </tr>';
//	}
//}
$smarty->assign('divMedicine',$sTempDiv);

//'LDRequestMedicinePatient' => "<a href=\"apotheke-pass.php".URL_APPEND."&mode=pres\">$LDRequestMedicinePatient</a>", //Tuyen--- cap phat thuoc
//'LDRequestMedicineWard' => "<a href=\"apotheke-pass.php".URL_APPEND."&mode=issuepaper\">$LDRequestMedicineWard</a>"
//);


 $smarty->assign('sMainBlockIncludeFile','pharmacy/pharmacy.tpl');
 $smarty->display('common/mainframe.tpl');
?>

