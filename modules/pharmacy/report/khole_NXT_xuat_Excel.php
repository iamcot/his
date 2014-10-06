<?php
ob_start();

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('./roots.php');
require_once ($root_path.'classes/PHPExcel.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma = new Pharma;

switch($select_type){
    case 0: $cond_typeput = ''; break;
    case 1: $cond_typeput = ' AND source.typeput=1 '; $titlereport=' KINH PHÍ '; break;		//su nghiep
    case 2: $cond_typeput = ' AND source.typeput=0 '; $titlereport=' BHYT '; break;		//bhyt
    case 3: $cond_typeput = ' AND source.typeput=2 '; $titlereport=' CBTC '; break;		//cbtc
    default: $cond_typeput = ' '; $titlereport='';
}

switch($typedongtay){
    case 'tayy': $dongtayy ='tayy';
        $titlereport=' TÂY Y'.$titlereport; break;
    case 'dongy': $dongtayy = 'dongy';
        $titlereport=' ĐÔNG Y'.$titlereport; break;
    default: $dongtayy = ''; break;
}

$listReport = $Pharma->Khole_thuoc_nhapxuatton($dongtayy, $cond_typeput, $month, $year);

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', "maso");
$objPHPExcel->getActiveSheet()->setCellValue('B1', "mavattu");
$objPHPExcel->getActiveSheet()->setCellValue('C1', "donvi");
$objPHPExcel->getActiveSheet()->setCellValue('D1', "SumOfsoluongtondau");
$objPHPExcel->getActiveSheet()->setCellValue('E1', "SumOfdongiatondau");
$objPHPExcel->getActiveSheet()->setCellValue('F1', "thanhtientondau");
$objPHPExcel->getActiveSheet()->setCellValue('G1', "SumOfsoluongnhap");
$objPHPExcel->getActiveSheet()->setCellValue('H1', "SumOfdongianhap");
$objPHPExcel->getActiveSheet()->setCellValue('I1', "thanhtiennhap");
$objPHPExcel->getActiveSheet()->setCellValue('J1', "SumOfsoluongxuat");
$objPHPExcel->getActiveSheet()->setCellValue('K1', "dongia");
$objPHPExcel->getActiveSheet()->setCellValue('L1', "Fieeld50");
$objPHPExcel->getActiveSheet()->setCellValue('M1', "toncuoi");
$objPHPExcel->getActiveSheet()->setCellValue('N1', "Field56");
$objPHPExcel->getActiveSheet()->setCellValue('O1', "Text119");
$objPHPExcel->getActiveSheet()->setCellValue('P1', "Text112");

$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
for ($i = 0; $i< $listReport->RecordCount(); $i++) {
    $rowreport=$listReport->FetchRow();
    if($value['gianhap']!='') $shownhap=number_format($rowreport['gianhap']);
    else $shownhap=number_format($rowreport['giaTRAVE']);
    if (round($value['giaxuat'],3)>round($value['giaxuat'])) $showxuat = number_format($value['giaxuat'],3);
    else $showxuat = number_format($value['giaxuat']);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2), ($i+1))
        ->setCellValue('B'.($i+2),$rowreport['product_name'] )
        ->setCellValue('C'.($i+2),$rowreport['unit_name_of_medicine'] )
        ->setCellValue('D'.($i+2),number_format($rowreport['number']) )
        ->setCellValue('E'.($i+2),number_format($rowreport['giaton']) )
        ->setCellValue('F'.($i+2),(number_format($rowreport['number']*$rowreport['giaton'])) )
        ->setCellValue('G'.($i+2),(number_format($rowreport['SUMNhap'])) )
        ->setCellValue('H'.($i+2),(number_format($shownhap)) )
        ->setCellValue('I'.($i+2),number_format($rowreport['SUMNhap']*$shownhap) )
        ->setCellValue('J'.($i+2),number_format($rowreport['SUMXuat']) )
        ->setCellValue('K'.($i+2),number_format($showxuat) )
        ->setCellValue('L'.($i+2),number_format($rowreport['SUMXuat']*$showxuat) )
        ->setCellValue('M'.($i+2),number_format($rowreport['number']+$rowreport['SUMNhap']-$rowreport['SUMXuat']) )
        ->setCellValue('N'.($i+2),number_format($rowreport['number']*$rowreport['giaton'])+($rowreport['SUMNhap']*$shownhap) - ($rowreport['SUMXuat']*$showxuat) )
        ->setCellValue('O'.($i+2),@formatDate2Local($rowreport['handung'],'dd/mm/yyyy') )
        ->setCellValue('P'.($i+2),$rowreport['product_lot_id'] );
}
$objPHPExcel->getActiveSheet()->setTitle('sheet');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Báo cáo NXT kho lẻ '.date('Y-m-d').'.xls"');
header('Cache-Control: max-age=0');
//// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_clean();
$objWriter->save('php://output');