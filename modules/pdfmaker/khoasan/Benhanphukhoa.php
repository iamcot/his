<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

    $report_textsize=12;
    $report_titlesize=16;
    $report_auxtitlesize=10;
    $report_authorsize=10;
    $sex ='';
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    $lang_tables[]='obstetrics.php';
    $lang_tables[]='emr.php';
    $lang_tables[]='departments.php';
    define('LANG_FILE','aufnahme.php');
    define('NO_2LEVEL_CHK',1);
    $local_user='ck_pflege_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');    
    //Hoi benh phu khoa
    require_once($root_path.'include/care_api_classes/class_obstetrics.php');
    $obj1=new Obstetrics();
    $obj1->useHistory_Phu();
    require_once($root_path.'include/care_api_classes/class_khambenh.php');
    $obj2=new Khambenh();
    //Kham toan than
    $obj2->Khambenh();   
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $obj=new Encounter();
    if($enc_nr){
        $pregs=&$obj1->history_Phu($enc_nr,'_ENC');
        $pregs1=&$obj2->getEncounterKhambenh_Phu($enc_nr,2);
        //Kham chuyen khoa
        $obj2->KhamChuyenkhoa();
        $pregs2=&$obj2->getEncounterKhamChuyenkhoa($enc_nr);        
        $status=$obj->loadEncounterData1($enc_nr,1);
    }else{
        $pregs=&$obj1->history_Phu($pid,'_REG');
        require_once($root_path.'include/care_api_classes/class_person.php');
        $person_obj=new Person();
        $list='title,name_first,name_last,name_2,name_3,name_middle,name_maiden,name_others,date_birth,
                                sex,addr_str,addr_str_nr,addr_zip,addr_citytown_nr,addr_quanhuyen_nr,addr_phuongxa_nr,
                                photo_filename,tiensubenhcanhan,tiensubenhgiadinh';

        $person_obj->setPID($pid);
        if($row=&$person_obj->getValueByList($list)) {
            foreach($row AS $k=>$v){
                $status1[$k]=$v;
                $status[$k]=$status1[$k];
            }      
        } 
    }
    if($pregs) $pregnancy=$pregs->FetchRow(); 
    if($pregs2) $pregnancy1=$pregs2->FetchRow(); 
    
    //Thong tin benh nhan
    
    if($status){        
        $enc_nr=$pregnancy['encounter_nr'];
        $status2=$obj->loadEncounterData1($enc_nr,1);
        foreach($status2 AS $k=>$v){
            $status2[$k]=$v;
            $status[$k]=$status2[$k];
        }
        if($status1['encounter_date']){
            $ngaynhapvien=substr($status2['encounter_date'],0,10);
            $convert_ngaynhapvien=@formatDate2STD($ngaynhapvien,$date_format);
        }
        if($status['encounter_date']){
            $ngaynhapvien=substr($status['encounter_date'],0,10);
            $convert_ngaynhapvien=@formatDate2STD($ngaynhapvien,$date_format);
        }
    }
    $encounter=$obj->getLoadedEncounterData();      
    require_once($root_path.'include/care_api_classes/class_measurement.php');
    $measurement_obj=new Measurement;
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");


    $classpathFPDF=$root_path.'classes/fpdf/';
    $fontpathFPDF=$classpathFPDF.'font/unifont/';
    define("_SYSTEM_TTFONTS",$fontpathFPDF);

    include_once($classpathFPDF.'tfpdf.php');

    $fpdf = new tFPDF('P','mm','a4');
    $fpdf->AddPage();
    $fpdf->SetTitle('BENH AN');
    $fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $fpdf->SetRightMargin(5);
    $fpdf->SetLeftMargin(5);
    $fpdf->SetTopMargin(20);
    $fpdf->SetAutoPageBreak('true','5');


    // Add a Unicode font (uses UTF-8)
    $fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
    $fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
    $fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);

    $y=$fpdf->GetY();
    $fpdf->SetFont('DejaVu','B',18);
    $fpdf->Cell(0,7,'A-BỆNH ÁN',0,0,'L');
    $fpdf->Ln(10);
    //Noi dung benh an
    $fpdf->SetFont('DejaVu','B',15);
    $fpdf->Cell(45,5,"I. ".$LDLidovaovien.": ",0,0,'L');
    $fpdf->SetFont('DejaVu','',11);
    if(!empty($status['lidovaovien'])){
        $fpdf->Cell(0,5,$status['lidovaovien'],0,1,'L');
    }else{
        $fpdf->Cell(0,5,"............................................................................................................................................",0,1,'L');
    }
    $fpdf->Ln(3);
    $fpdf->SetFont('DejaVu','B',15);
    $fpdf->Cell(0,5,"II. ".$question.": ",0,1,'L');
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Ln(2);
    $fpdf->Cell(45,5,"1. ".$LDQuatrinhbenhly.": ",0,0,'L');
    $fpdf->SetFont('DejaVu','',11);
    if(!empty($status['quatrinhbenhly'])){
        $fpdf->Cell(10,5," ",0,0,'L');
        $fpdf->Cell(0,5,$status['quatrinhbenhly'],0,1,'L');
    }else{
        for($i=0;$i<7;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"............................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,'L');
            }
        }    
    }
    $fpdf->Ln(2);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(0,5,"2. ".$history_sick.": ",0,1,'L');
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Ln(1);
    $fpdf->Cell(0,5,"+ ".$LDTSBenhCN."(".$note.")",0,1,'L');
    if(!empty($status['tiensubenhcanhan'])){
        $fpdf->MultiCell(0,5,'     '.$status['tiensubenhcanhan'],0,'L');
    }else{
        for($i=0;$i<5;$i++){
            $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,'L');
        }    
    }
    $fpdf->Ln(1);
    $fpdf->Cell(0,5,"+ ".$LDTSBenhGD,0,1,'L');
    if(!empty($status['tiensubenhgiadinh'])){
        $fpdf->MultiCell(0,5,'     '.$status['tiensubenhgiadinh'],0,'L');
    }else{
        for($i=0;$i<5;$i++){
            $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,'L');
        }    
    }

    $fpdf->Ln(4);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(0,5,"3. ".$history_Obstetrics_Gynecology.":",0,1,'L');
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Ln(1);
    if($pregnancy['batdauthaykinh']){
        $fpdf->Cell(65,5,"- ".$LD['batdauthaykinh'].': '.$pregnancy['batdauthaykinh'],0,0,'L');
    }else{
        $fpdf->Cell(65,5,"- ".$LD['batdauthaykinh'].":...................",0,0,'L');   
    }
    if($pregnancy['tuoithaykinh']){
        $fpdf->Cell(0,5,$LDTuoi.': '.$pregnancy['tuoithaykinh'],0,1,'L');
    }else{
        $fpdf->Cell(0,5,$LDTuoi.":...................",0,1,'L');   
    }
    
    $fpdf->Ln(1);
    if(!empty($pregnancy['tinhchatkinh'])){
        $fpdf->Cell(65,5,"- ".$LD['tinhchatkinh'].': '.$pregnancy['tinhchatkinh'],0,0,'L');
    }else{
        $fpdf->Cell(65,5,"- ".$LD['tinhchatkinh'].":.....................",0,0,'L');   
    }
    if(!empty($pregnancy['tuoithaykinh'])){
        $fpdf->Cell(35,5,$LD['chuki'].': '.$pregnancy['tuoithaykinh'].' '.$LDday1.'. ',0,0,'L');
    }else{
        $fpdf->Cell(35,5,$LD['chuki'].":..........".$LDday1.'. ',0,0,'L');   
    }
    if($pregnancy['songaykinh']){
        $fpdf->Cell(50,5,$LD['songaykinh'].': '.$pregnancy['songaykinh'].'. ',0,0,'L');
    }else{
        $fpdf->Cell(50,5,$LD['songaykinh'].":................",0,0,'L');   
    }
    if(!empty($pregnancy['luongkinh'])){
        $fpdf->Cell(0,5,$LD['luongkinh'].': '.$pregnancy['luongkinh'],0,1,'L');
    }else{
        $fpdf->Cell(0,5,$LD['luongkinh'].":................".$LDday1,0,1,'L');   
    }

    if(!empty($pregnancy['kinhcuoitu'])){
        $fpdf->Cell(65,5,"- ".$LDKinhlancuoi.": ".@formatDate2STD($pregnancy['kinhcuoitu'],$date_format),0,0,'L');
    }else {
        $fpdf->Cell(65,5,"- ".$LDKinhlancuoi.": ..................",0,0,"L");
    }    
    if($pregnancy['daubung']){        
        $fpdf->Cell(20,5,$LD['daubung'].": ",0,0,'L');        
        $x=$fpdf->GetX();
        $y=$fpdf->GetY();
        $fpdf->DrawRect($x,$y,5,4.5,1);
        $fpdf->Cell(10,5,"X",0,0,"L");
    }else {
        $fpdf->Cell(20,5,$LD['daubung'].": ",0,0,"L");
        $x=$fpdf->GetX();
        $y=$fpdf->GetY();
        $fpdf->DrawRect($x,$y,5,4.5,1);
        $fpdf->Cell(10,5,"",0,0,"L");
    }
    $fpdf->Cell(20,5,$LD['time'][0].': ',0,0,'L');
    if($pregnancy['time']){
        $time=explode(";",$pregnancy['time']);
        
        for($i=0;$i<3;$i++){            
            if($time[$i]){
                $fpdf->Cell(17,5,($i+1).". ".$LD['time'][$i+1],0,0,'L');
                $x=$fpdf->GetX();
                $y=$fpdf->GetY();
                $fpdf->DrawRect($x,$y,5,4.5,1);
                $fpdf->Cell(8,5,"X",0,0,'L');               
            }else{
                $fpdf->Cell(17,5,($i+1).". ".$LD['time'][$i+1],0,0,'L');
                $x=$fpdf->GetX();
                $y=$fpdf->GetY();
                $fpdf->DrawRect($x,$y,5,4.5,1);
                $fpdf->Cell(8,5,"",0,0,'L');  
            }            
        }
        $fpdf->Cell(0,5,"",0,1,'L');
    }else {
        $fpdf->Cell(0,5,$LD['time'][0].": 1.".$LD['time'][1]."...... 2.".$LD['time'][2]."...... 3.".$LD['time'][3],0,1,"L");
    }
    
    $fpdf->Ln(1);
    if(!empty($pregnancy['namlaychong'])){
        $fpdf->Cell(50,5,"- ".$LD['namlaychong'].': '.$pregnancy['namlaychong'],0,0,'L');
    }else{
        $fpdf->Cell(50,5,"- ".$LD['namlaychong'].":..................",0,0,'L');   
    }
    if(!empty($pregnancy['tuoilaychong'])){
        $fpdf->Cell(25,5,$LDyearsold.': '.$pregnancy['tuoilaychong'],0,0,'L');
    }else{
        $fpdf->Cell(25,5,$LDyearsold.":................",0,0,'L');   
    }
    if(!empty($pregnancy['namhetkinh'])){
        $fpdf->Cell(43,5," ".$LD['namhetkinh'].': '.$pregnancy['namhetkinh'],0,0,'L');
    }else{
        $fpdf->Cell(43,5," ".$LD['namhetkinh'].":...............",0,0,'L');   
    }
    if(!empty($pregnancy['tuoihetkinh'])){
        $fpdf->Cell(0,5,$LDyearsold.': '.$pregnancy['tuoihetkinh'],0,1,'L');
    }else{
        $fpdf->Cell(0,5,$LDyearsold.":...............",0,1,'L');   
    }
    
    $fpdf->Ln(1);
    $fpdf->Cell(62,5,'- '.$LD['benhphukhoa'].':',0,1,'L');
    if($pregnancy['benhphukhoa']){
        $fpdf->MultiCell(0,5,'     '.$pregnancy['benhphukhoa'],0,'L');
    }else{
        for($i=0;$i<3;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"..............................................................................................................................",0,1,"L");
            }else{
                $fpdf->Cell(0,5,"......................................................................................................................................................................................",0,1,"L");
            }  
        } 
    }
    
    $fpdf->Ln(7);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(60,5,"4. ".$history_Obstetrics.':',0,0,'L');
    $fpdf->Cell(0,5,"S   S   S   S",0,1,'L');
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Ln(1);
    $fpdf->Cell(61,5,"- ".$LD['tienthai'][0],0,0,'L');
    if($pregnancy['tienthai']){
        $tienthai=explode(";",$pregnancy['tienthai']);
        $x=$fpdf->GetX();
        $y=$fpdf->GetY();        
        for($i=0;$i<=4;$i++){
            $fpdf->DrawRect($x,$y,5,4.5,$i);
            if($tienthai[$i]){ 
                $fpdf->Cell(6,5,"X",0,0,'L'); 
            }else{
                $fpdf->Cell(6,5,"",0,0,'L');
            }          
        }
        $fpdf->Cell(0,6,"(".$LD['tienthai'][1].', '.$LD['tienthai'][2].', '.$LD['tienthai'][3].', '.$LD['tienthai'][4],0,1,'L');
    }else {
        $x=$fpdf->GetX();
        $y=$fpdf->GetY();
        for($i=0;$i<=4;$i++){
            $fpdf->DrawRect($x,$y,5,4.5,$i);
        }
    }
    $fpdf->Ln(3);

    //Kham benh
    if($pregs1) $pregnancy=$pregs1->FetchRow();
    $fpdf->SetFont('DejaVu','B',15);
    $fpdf->Cell(0,7,"III. ".$LDKhambenh.": ",0,1,'L');
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(30,5,"1. ".$LDToanthan.": ",0,0,'L');    
    if(!empty($pregnancy['da_notes'])){ 
        $fpdf->SetFont('DejaVu','I',11);
        $fpdf->Cell(25,5,$LD['da_notes'],0,0,'L');
        $fpdf->Cell(80,5,"",0,0,'L');
        $y1=$fpdf->GetY()+4;
        $x1=$fpdf->GetX();
        $fpdf->Ln();
        $fpdf->SetFont('DejaVu','',11);
        $fpdf->Cell(10,5," ",0,0,'L');
        $fpdf->MultiCell(125,5,'     '.$pregnancy['da_notes'],0,'L');
    }else{        
        $fpdf->SetFont('DejaVu','I',11);
        $fpdf->Cell(25,5,$LD['da_notes'],0,0,'L');
        $fpdf->SetFont('DejaVu','',11);
        for($i=0;$i<2;$i++){            
            if($i==0){                
                $fpdf->Cell(90,5,"....................................................................................................................................",0,'L'); 
                $y1=$fpdf->GetY()+4;
                $x1=$fpdf->GetX();
                $fpdf->Ln();
            }else{
                $fpdf->Cell(0,5,"..................................................................................................................................",0,1,'L');
            }            
        }    
    }
    $fpdf->Ln(2);  
    $fpdf->Cell(5,5," ",0,0,'L');
    if(!empty($pregnancy['hach_notes'])){
        $fpdf->MultiCell(125,5,$LD['hach_notes'].':  '.$pregnancy['da_notes'],0,'L');
    }else{
        $fpdf->Cell(10,5,$LD['hach_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"....................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,"..................................................................................................................................",0,1,"L");
            } 
        } 
    }
    $fpdf->Ln(1); 
    $fpdf->Cell(5,5," ",0,0,'L');
    if(!empty($pregnancy['vu_notes'])){
        $fpdf->MultiCell(125,5,$LD['vu_notes'].':  '.$pregnancy['vu_notes'],0,'L');
    }else{
        $fpdf->Cell(7,5,$LD['vu_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,".......................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,"..................................................................................................................................",0,1,"L");
            } 
        } 
    }
    //Kẻ ô lấy dấu sinh hiệu
    $fpdf->SetY($y1);
    $fpdf->SetX($x1);    
    if($measurement_obj->getMach($enc_nr)){
        $t1=$measurement_obj->getMach($enc_nr);
    }else{
        $t1='..................';
    }
    if($measurement_obj->getTemper($enc_nr)){
        $t2=$measurement_obj->getTemper($enc_nr);
    }else{
        $t2='...............';
    }
    if($measurement_obj->getBloodPressure($enc_nr)){
        $t3=$measurement_obj->getBloodPressure($enc_nr);
    }else{
        $t3='...../........';
    }
    if($measurement_obj->getNhiptho($enc_nr)){
        $t4=$measurement_obj->getNhiptho($enc_nr);
    }else{
        $t4='...............';
    }
    if($measurement_obj->getNhiptho($enc_nr)){
        $t5=$measurement_obj->getWeight($enc_nr);
    }else{
        $t5='.................';
    }
    $fpdf->MultiCell(53,6,"    Mạch ".$t1." lần/ph
    Nhiệt độ ".$t2." °C
    Huyết áp ".$t3." mmHg
    Nhịp thở ".$t4." lần/ph
    Cân nặng ".$t5." kg",1,'L');
    $fpdf->Ln(7);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(27,5,$LDCaccoquan.": ",0,1,'L'); 
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Ln(1);    
    if(!empty($pregnancy['tuanhoan_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LD['tuanhoan_notes'].':  '.$pregnancy['tuanhoan_notes'],0,'L');
    }else{
        $fpdf->Cell(25,5,"+ ".$LD['tuanhoan_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"...............................................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,"L");
            } 
        } 
    }
    $fpdf->Ln(1);    
    if(!empty($pregnancy['hohap_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LD['hohap_notes'].':  '.$pregnancy['hohap_notes'],0,'L');
    }else{
        $fpdf->Cell(20,5,"+ ".$LD['hohap_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"...................................................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,"L");
            } 
        } 
    }
    $fpdf->Ln(1);    
    if(!empty($pregnancy['tieuhoa_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LD['tieuhoa_notes'].':  '.$pregnancy['tieuhoa_notes'],0,'L');
    }else{
        $fpdf->Cell(21,5,"+ ".$LD['tieuhoa_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"..................................................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,"L");
            }  
        } 
    }
    $fpdf->Ln(1);    
    if(!empty($pregnancy['thankinh_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LD['thankinh_notes'].':  '.$pregnancy['thankinh_notes'],0,'L');
    }else{
        $fpdf->Cell(23,5,"+ ".$LD['thankinh_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"................................................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,"L");
            }  
        } 
    }
    $fpdf->Ln(1);    
    if(!empty($pregnancy['coxuongkhop_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LD['coxuongkhop_notes'].':  '.$pregnancy['coxuongkhop_notes'],0,'L');
    }else{
        $fpdf->Cell(38,5,"+ ".$LD['coxuongkhop_notes'].':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,"...................................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,"L");
            }  
        } 
    }
    $fpdf->Ln(1);    
    if(!empty($pregnancy['thantietnieusinhduc_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LDThantietnieusinhduc.':  '.$pregnancy['thantietnieusinhduc_notes'],0,'L');
    }else{
        $fpdf->Cell(51,5,"+ ".$LDThantietnieusinhduc.':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,".......................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,".....................................................................................................................................................................................",0,1,"L");
            }  
        } 
    }
    $fpdf->Ln(1);    
    if(!empty($pregnancy['khac_notes'])){
        $fpdf->MultiCell(0,5,"+ ".$LDOther2.':  '.$pregnancy['khac_notes'],0,'L');
    }else{
        $fpdf->Cell(14,5,"+ ".$LDOther2.':',0,0,'L');
        for($i=0;$i<2;$i++){
            if($i==0){
                $fpdf->Cell(0,5,".......................................................................................................................................................................",0,1,'L');
            }else{
                $fpdf->Cell(0,5,"....................................................................................................................................................................................",0,1,"L");
            }  
        } 
    }
    $fpdf->Ln(2);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(0,5,"3. ".$LDTestChuyenKhoa.": ",0,1,'L'); 
    $fpdf->SetFont('DejaVu','BI',11);
    $fpdf->Cell(0,5,"a. ".$LDBeside.": ",0,1,'L');
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['dauhieu_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['dauhieu_notes'].':  '.$pregnancy1['dauhieu_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['dauhieu_notes'].": ....................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['moilon_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['moilon_notes'].':  '.$pregnancy1['moilon_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['moilon_notes'].": ..........................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['moibe_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['moibe_notes'].':  '.$pregnancy1['moibe_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['moibe_notes'].": ...........................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['amvat_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['amvat_notes'].':  '.$pregnancy1['amvat_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['amvat_notes'].": ..........................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['amho'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['amho'].':  '.$pregnancy1['amho'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['amho'].": ...........................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['mangtrinh_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['mangtrinh_notes'].':  '.$pregnancy1['mangtrinh_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['mangtrinh_notes'].": ....................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['tangsinhmon'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['tangsinhmon'].':  '.$pregnancy1['tangsinhmon'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['tangsinhmon'].": ..............................................................................................................................................",0,1,"L");         
    }
    $fpdf->SetFont('DejaVu','BI',11);
    $fpdf->Cell(0,5,"b. ".$LDBeside.": ",0,1,'L');
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['amdao'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['amdao'].':  '.$pregnancy1['amdao'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['amdao'].": .........................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['TC'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['TC'].':  '.$pregnancy1['TC'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['TC'].": ....................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['thanhtc_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['thanhtc_notes'].':  '.$pregnancy1['thanhtc_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['thanhtc_notes'].": ................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['phanphu'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['phanphu'].':  '.$pregnancy1['phanphu'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['phanphu'].": ......................................................................................................................................................",0,1,"L");         
    }
    $fpdf->Cell(12,5,'',0,0,'L');
    if(!empty($pregnancy1['tuicung_notes'])){
        $fpdf->MultiCell(0,5,"       - ".$LD['tuicung_notes'].':  '.$pregnancy1['tuicung_notes'],0,'L');
    }else{
        $fpdf->Cell(15,5,"- ".$LD['tuicung_notes'].": .................................................................................................................................................",0,1,"L");         
        for($i=0;$i<2;$i++){
            $fpdf->Cell(0,5,'....................................................................................................................................................................................',0,1,'L');
        }        
    }
    $fpdf->Ln(2);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(89,5,"4. ".$LD['xetnghiem_notes'].": ",0,0,'L'); 
    $fpdf->SetFont('DejaVu','',11);
    $sql="SELECT DISTINCT(dr.reporting_dept) AS reporting_dept FROM care_encounter_diagnostics_report AS dr 
            WHERE dr.encounter_nr=".$enc_nr." 
            ORDER BY dr.create_time DESC";
    if($result=$db->Execute($sql)){
        $fpdf->Cell(0,5,'',0,1,'L');
        $rows=$result->RecordCount();
        $i=1;
        while($row=$result->FetchRow()){
            $fpdf->Cell(12,5,'',0,0,'L');
            $deptnr_ok=false;
            if($row['reporting_dept']){
                $fpdf->Cell(0,5,$i.'. '.$row['reporting_dept'],0,1,'L');
            }
            $i++;
        }     
    }else{
        for($i=0;$i<4;$i++)
        {
            if($i==0){
                $fpdf->Cell(0,5,'....................................................................................................',0,1,'L');
            }            
            $fpdf->Cell(0,5,'....................................................................................................................................................................................',0,1,'L');
        }
    }
    $fpdf->Ln(2);
    $fpdf->SetFont('DejaVu','B',12);
    $fpdf->Cell(43,5,"5. ".$LDTomtat.": ",0,0,'L'); 
    $fpdf->SetFont('DejaVu','',11);
    if($status['referrer_diagnosis']){
        $fpdf->Cell(0,5,'',0,1,'L');
        $fpdf->MultiCell(0,5,"      ".$status['referrer_diagnosis'],0,'L');
    }else{
        for($i=0;$i<4;$i++)
        {
            if($i==0){
                $fpdf->Cell(0,5,'..............................................................................................................................................',0,1,'L');
            }            
            $fpdf->Cell(0,5,'....................................................................................................................................................................................',0,1,'L');
        }
    }
    $fpdf->Ln(3);
    $fpdf->SetFont('DejaVu','B',15);
    $fpdf->Cell(0,5,"IV. ".$LDChandoankhivaokhoa.": ",0,1,'L'); 
    $fpdf->SetFont('DejaVu','',11);
    $fpdf->Cell(26,5,"+ ".$LDBenhchinh.": ",0,0,'L');
    if($status['benhchinh']){
        $fpdf->MultiCell(0,5,"      ".$status['benhchinh'],0,'L');
    }else{
        $fpdf->Cell(0,5,'.............................................................................................................................................................',0,1,'L');
    }
    $fpdf->Cell(48,5,"+ ".$LDBenhphu.": ",0,0,'L');
    if($status['benhphu']){
        $fpdf->MultiCell(0,5,"      ".$status['benhphu'],0,'L');
    }else{
        $fpdf->Cell(0,5,'.........................................................................................................................................',0,1,'L');
    }
    $fpdf->Cell(22,5,"+ ".$LDPhanbiet.": ",0,0,'L');
    if($status['phanbiet']){
        $fpdf->MultiCell(0,5,"      ".$status['phanbiet'],0,'L');
    }else{
        $fpdf->Cell(0,5,'.................................................................................................................................................................',0,1,'L');
    }
    $fpdf->Ln(3);
    $fpdf->SetFont('DejaVu','B',15);
    $fpdf->Cell(38,5,"V. ".$LD['tienluong_notes'].": ",0,0,'L'); 
    $fpdf->SetFont('DejaVu','',11);
    if($status['chandoangioithieu']){
        $fpdf->Cell(0,5,'',0,1,'L');
        $fpdf->Cell(12,5,'',0,0,'L');
        $fpdf->Cell(0,5,$status['chandoangioithieu'],0,1,'L');
    }else{
        $fpdf->Cell(0,5,'...................................................................................................................................................',0,1,'L');
        $fpdf->Cell(0,5,'.....................................................................................................................................................................................',0,1,'L');
    }
    $fpdf->Ln(3);
    $fpdf->SetFont('DejaVu','B',15);
    $fpdf->Cell(50,5,"VI. ".$LDHDT.": ",0,0,'L'); 
    $fpdf->SetFont('DejaVu','',11);
    if($status['huongdieutritiep']){
        $fpdf->Cell(0,5,'',0,1,'L');
        $fpdf->Cell(12,5,'',0,0,'L');
        $fpdf->Cell(0,5,$status['huongdieutritiep'],0,1,'L');
    }else{
        $fpdf->Cell(0,5,'........................................................................................................................................',0,1,'L');
        $fpdf->Cell(0,5,'.....................................................................................................................................................................................',0,1,'L');
    }
    $fpdf->Ln(3);
    //
//Ký tên
//$fpdf->SetX(0);
//$fpdf->SetY(250);
$fpdf->SetFont('','I');
$fpdf->Cell(0,5,'Ngày............tháng............năm...............',0,1,'R');
$fpdf->SetFont('','B');
$fpdf->Cell(110,5,' ',0,0,'C');
$fpdf->Cell(0,5,$LDNguoilambenhan,0,1,'C');
$fpdf->SetFont('','');
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->Cell(110,5,' ',0,0,'C');
$fpdf->SetFont('','I');
$fpdf->Cell(0,5,'Họ và tên:.....................................',0,1,'C');


//$fpdf->Output();
$fpdf->Output('GiayChuyenVien.pdf', 'I');


?>
