<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

//extract($_POST);

//echo $month.'/'.$year; $typeput; $typedongtay

$patmenu="khole_thuoc_nhapxuatton.php".URL_REDIRECT_APPEND;
if(!isset($Pharma)) $Pharma=new Pharma;

//maybe change later...
$month1 = str_pad($month,2,'0',STR_PAD_LEFT);
$fromdate=$year.'-'.$month1.'-01';
$todate = date("Y-m-t", mktime(0, 0, 0, $month, 1, $year));

switch($target){
    case 'update':

        $Pharma->Khole_thuoc_updatetonkho($typedongtay, $ton_id, $fromdate, $todate, $month, $year, $typeput=0);
        $Pharma->deleteAllMedicineInTonKhoLe($typedongtay, $ton_id);
        $n = $_POST['maxid'];
        for ($i=1; $i<=$n; $i++) {
            $encoder_dx = $_POST['encoder'.$i];
//            $tondau_dx = 'Tondau'.$i;
//            $nhap_dx = 'Nhap'.$i;
//            $xuat_dx = 'Xuat'.$i;
            $lotid_dx = $_POST['product_lot_id'.$i];
            $exp_dx = $_POST['handung'.$i];
            $toncuoi_dx = $_POST['toncuoi'.$i];
            $gia_dx = $_POST['giatoncuoi'.$i];
            if($$encoder_dx && $ton_id>0 && $$toncuoi_dx!=0)
                $Pharma->Khole_thuoc_luutonkho_chitiet($typedongtay, $ton_id, $$encoder_dx, $$lotid_dx, $typeput, $$exp_dx, $$toncuoi_dx, $$gia_dx);
        }
        header("Location:".$patmenu);
        exit;
        break;

    case 'new':
    case 'create':
    case 'save':
        //report

        $monthreport= $year.'-'.$month1.'-00';
        //$monthreport = formatDate2STD($date_time[0],'dd-mm-yyyy');

        $Pharma->Khole_thuoc_luutonkho($typedongtay, $fromdate, $todate, $month, $year, $typeput=0);
        $monthyear="WHERE monthreport='".$month."' AND yearreport='".$year."' ";

        if($anyreport=$Pharma->checkAnyReport_TonKhoLe($monthyear)){
            $ton_id=$anyreport['id'];
        }
        $n = $_POST['maxid'];
        for ($i=1; $i<=$n; $i++) {
            $encoder_dx = $_POST['encoder'.$i];
//            $tondau_dx = 'Tondau'.$i;
//            $nhap_dx = 'Nhap'.$i;
//            $xuat_dx = 'Xuat'.$i;
            $lotid_dx = $_POST['product_lot_id'.$i];
            $exp_dx = $_POST['handung'.$i];
            $toncuoi_dx = $_POST['toncuoi'.$i];
            $gia_dx = $_POST['giatoncuoi'.$i];

            if($encoder_dx){
                //echo $$encoder_dx.' '.$monthreport.' '.$$tondau_dx.' '.$$nhap_dx.' '.$$xuat_dx.' '.$$toncuoi_dx.' '.$$gia_dx.' '.$user_report;
//                $Pharma->Khole_thuoc_luubaocao($$encoder_dx, $monthreport, str_replace(',','', $$tondau_dx), str_replace(',','', $$nhap_dx), str_replace(',','', $$xuat_dx), str_replace(',','', $$toncuoi_dx), str_replace(',','', $$gia_dx), $user_report, $_POST['typeput']);

                if($ton_id>0 && $toncuoi_dx!=0)
                    $Pharma->Khole_thuoc_luutonkho_chitiet($typedongtay, $ton_id, $encoder_dx, $lotid_dx, $typeput, $exp_dx, $toncuoi_dx, $gia_dx);
            }
        }



        //echo $Pharma->getLastQuery()
        header("Location:".$patmenu);
        exit;

        break;

}



?>