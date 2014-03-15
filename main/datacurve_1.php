<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    require($root_path.'classes/datetimemanager/class.dateTimeManager.php');
    $dateshifter=new dateTimeManager();
    /*
    CARE2X Integrated Information System for Hospitals and Health Care Organizations and Services
    Copyright (C) 2002,2003,2004,2005  Elpidio Latorilla & Intellin.org	
    GNU GPL. For details read file "copy_notice.txt".
    */

    /**
    * This function aligns the date to the start of the grahical chart
    */
    if(!extension_loaded('gd')) dl('php_gd.dll');

    if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
    if($dblink_ok){	
        $dbtable='care_encounter_bdcd';

        $ok=false;
        $sql="SELECT * FROM $dbtable
                WHERE encounter_nr=$pn AND msr_date BETWEEN '$date' AND '$yr-12-31'
                ORDER BY msr_date,msr_time";

        if($bp_obj=$db->Execute($sql)){
            $bprows=$bp_obj->RecordCount();
            $ok=true;
        }
    }
    switch($flag){
        //Nhip tim thai
        case 1:
            # Initialize general  dimensions  
            $tabhi=192; # Height of graph chart in pixels
            $tablen=1200; # Total width of graph chart in pixels
            $tabcols=$tablen/48; # Total number of vertical lines
            $tabrows=$tabhi/16; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            /**
            * The next set of codes create the graph chart on-the-fly 
            * if the ready made image is not loaded successfully
            */
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<$tablen;$i+=$tabcols){            
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }
                for($i=$tabrows;$i<$tabhi;$i+=$tabrows){
                    if($i%24==0){
                        ImageLine($im,0,$i,$tablen-1,$i,$text_color_1);
                    }else{
                        ImageLine($im,0,$i,$tablen-1,$i,$text_color);
                    }          
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);
            }
            $text_blue = ImageColorAllocate ($im, 0, 0, 255); 
            $xoffs=$tabcols*2; // The width of a day's column in pixels
            $xunit=$xoffs/60; // Unit of 1 hour in pixels = Width of day's column divided by 24 hours
            if($ok){
                $ox1=0;$ox2_1=0;$oy1=0;$count=1;
                $time_bf=0;	
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();
                        if(empty($bp['msr_time'])||empty($bp['nhiptim'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);  
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(8*$xoffs);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(9*$xoffs);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(10*$xoffs);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(11*$xoffs);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(12*$xoffs);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(13*$xoffs);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(14*$xoffs);
                            }else{
                                $ox2=(15*$xoffs);
                            } 
                            if($time[1]!=0){
                                $ox2=$ox2+($time[1]*$xunit);                                
                            }
                            $oy2=(180-$bp['nhiptim'])*2.4;//$tabhi/80 khoang chia=2.4
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){                                
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(8*$xoffs);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(9*$xoffs);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(10*$xoffs);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(11*$xoffs);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(12*$xoffs);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(13*$xoffs);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(14*$xoffs);
                            }else{
                                $ox2_1=(15*$xoffs);
                            }  
                            if($time[1]>0){
                                $ox2=$ox1+($time[0]-$time_bf)*$xoffs+($time[1]*$xunit);
                            }else{
                                $ox2=$ox1+($time[0]-$time_bf)*$xoffs; 
                            }                            
                            $oy2=(180-$bp['nhiptim'])*2.4;//$tabhi/80 khoang chia=2.4
                            imagefilledarc($im,$ox2_1,$oy2,4,4,0,360,$text_blue,IMG_ARC_PIE);
                            imagefilledarc($im,$ox2,$oy2,4,4,0,360,$text_blue,IMG_ARC_PIE);
                            imagesetthickness($im, 1);
                            //ve duong dut khuc
                            $style = array($text_blue, $text_blue, $text_blue, $text_blue, $text_blue, $background_color, $background_color, $background_color, $background_color, $background_color);
                            imagesetstyle($im, $style);
                            imageline($im, $ox2+1,$oy2,$ox2_1,$oy2, IMG_COLOR_STYLED);
                            $count++;
                            $ox1_1=$ox2_1;
                        }else {
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }                            
                            if($i==0){
                                $ox2=0;
                            }else{
                                if($time[1]>0){
                                    $ox2=$ox1+($time[0]-$time_bf)*$xoffs+$time[1]*$xunit;                                 
                                }else{
                                    $ox2=$ox1+($time[0]-$time_bf)*$xoffs; 
                                }
                            }
                            $oy2=(180-$bp['nhiptim'])*2.4;//$tabhi/80 khoang chia=2.4
                        }
                        imagefilledarc($im,$ox2,$oy2,4,4,0,360,$text_blue,IMG_ARC_PIE);
                        if(($ox1 || $oy1) && $oy1==$oy2){
                            imagesetthickness($im, 1);
                            //ve duong dut khuc
                            $style = array($text_blue, $text_blue, $text_blue, $text_blue, $text_blue, $background_color, $background_color, $background_color, $background_color, $background_color);
                            imagesetstyle($im, $style);
                            imageline($im, $ox1+1,$oy1,$ox2,$oy2, IMG_COLOR_STYLED);
                        }else if($ox1 || $oy1){
                            imagesetthickness($im, 1);
                            //vẽ đường liền
                            ImageLine($im,$ox1,$oy1,$ox2,$oy2,$text_blue);
                        }                                 
                        $ox1=$ox2;
                        $oy1=$oy2;
                        $time_bf=$time[0];
                        $value_ctc=$bp['cotucung'];
                    }
                    $bp_obj->MoveFirst();
                } // end of for $n
            }
            break;
        //Nuoc oi & do chong khop
        case 2:
            $tabhi=60; # Height of graph chart in pixels
            $tablen=1200; # Total width of graph chart in pixels
            $tabcols=$tablen/48; # Total number of vertical lines
            $tabrows=$tabhi/2; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<$tablen;$i+=$tabcols){            
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }
                for($i=$tabrows;$i<$tabhi;$i+=$tabrows){                        
                    ImageLine($im,0,$i,$tablen-1,$i,$text_color_1);
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);
            }                      
            $xoffs=$tabcols*2; // The width of a day's column in pixels
            $xunit=$xoffs/60; // Unit of 1 hour in pixels = Width of day's column divided by 24 hours
            if($ok){
                $ox1=0;$ox2_1=0;$oy1=0;
                $time_bf=0;$count=1;
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);  
                        switch ($bp['nuocoi']){
                            case 'ối dẹt':
                                $oy2='D';
                                break;
                            case 'ối phồng':
                                $oy2='P';
                                break;
                            case 'vỡ trong':
                                $oy2='T';
                                $src = imagecreatefrompng($root_path.'gui/img/common/default/arrow_bdcd.PNG');                                
                                break;
                            case 'vỡ đục':
                                $oy2='B';
                                $src = imagecreatefrompng($root_path.'gui/img/common/default/arrow_bdcd.PNG');
                                break;
                            default :
                                $oy2='C';
                                $src = imagecreatefrompng($root_path.'gui/img/common/default/arrow_bdcd.PNG');
                                break;
                        }
                        if($bp['dochongkhop']=='có'){
                            $oy2_1='_';
                        }else{
                            $oy2_1='C';
                        }
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(8*$xoffs);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(9*$xoffs);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(10*$xoffs);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(11*$xoffs);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(12*$xoffs);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(13*$xoffs);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(14*$xoffs);
                            }else{
                                $ox2=(15*$xoffs);
                            } 
                            $ox2+=$tabcols/3;
                            // Copy
                            if($oy2!='C' && $oy2!='D' && $oy2!='P'){
                                imagecopyresized($im, $src, ($ox2-7), 3, 0, 0, 9, 19, 10, 21);
                            } 
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if($time['1']>30){
                                $ox2=$ox1+$tabcols+($time['0']-$time_bf)*$xoffs;                                 
                            }else{
                                $ox2=$ox1+($time['0']-$time_bf)*$xoffs; 
                            }
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(8*$xoffs);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(9*$xoffs);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(10*$xoffs);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(11*$xoffs);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(12*$xoffs);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(13*$xoffs);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(14*$xoffs);
                            }else{
                                $ox2_1=(15*$xoffs);
                            }
                            $ox2_1+=$tabcols/3;                            
                            // Copy
                            if($oy2!='C' && $oy2!='D' && $oy2!='P'){
                                imagecopyresized($im, $src, ($ox2-7), 3, 0, 0, 9, 19, 10, 21);
                                imagecopyresized($im, $src, ($ox2_1-7), 3, 0, 0, 9, 19, 10, 21);
                            }
                            imagettftext($im, 15, 0, $ox2_1, 18, $text_color_1, $font, $oy2);                         
                            imagettftext($im, 15, 0, $ox2_1, 49, $text_color_1, $font, $oy2_1);
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox2_1=0;
                            }    
                            if($i==0){
                                $ox2=$tabcols/3;
                            }else{
                                if($time['1']>30){
                                    $ox2=$ox1+($time['0']-$time_bf)*$xoffs+$tabcols;                                 
                                }else{
                                    $ox2=$ox1+($time['0']-$time_bf)*$xoffs; 
                                }
                            }
                            // Copy
                            if($oy2!='C' && $oy2!='D' && $oy2!='P'){
                                imagecopyresized($im, $src, ($ox2-7), 3, 0, 0, 9, 19, 10, 21);
                            }                            
                        }                            
                        imagettftext($im, 15, 0, $ox2, 18, $text_color_1, $font, $oy2);                         
                        imagettftext($im, 15, 0, $ox2, 49, $text_color_1, $font, $oy2_1);
                        $ox1=$ox2;
                        $ox1_1=$ox2_1;
                        $oy1=$oy2;
                        $time_bf=$time[0];
                        $value_ctc=$bp['cotucung'];
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        //CTC & do lot & Thoi gian chinh xac
        case 3:
            $tabhi=440; # Height of graph chart in pixels
            $tablen=960; # Total width of graph chart in pixels
            $tabcols=$tablen/24; # Total number of vertical lines
            $tabrows=$tabhi/11; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn
                for($i=$tabcols;$i<$tablen;$i+=$tabcols){ 
                    switch ($i){
                        case ($tabcols*15):
                            ImageLine($im,$i,0,$i-($tabcols*7),$tabrows*7,$text_color_1);
                            ImageLine($im,$i-1,0,$i-($tabcols*7),($tabrows*7)-1,$text_color_1);
                            ImageLine($im,$i+1,0,$i-($tabcols*7),($tabrows*7)+1,$text_color_1);
                            ImageLine($im,$i-2,0,$i-($tabcols*7),($tabrows*7)-2,$text_color_1);
                            ImageLine($im,$i+2,0,$i-($tabcols*7),($tabrows*7)+2,$text_color_1);
                            continue;
                        case ($tabcols*19):
                            ImageLine($im,$i,0,$i-($tabcols*7),$tabrows*7,$text_color_1);
                            ImageLine($im,$i-1,0,$i-($tabcols*7),($tabrows*7)-1,$text_color_1);
                            ImageLine($im,$i+1,0,$i-($tabcols*7),($tabrows*7)+1,$text_color_1);
                            ImageLine($im,$i-2,0,$i-($tabcols*7),($tabrows*7)-2,$text_color_1);
                            ImageLine($im,$i+2,0,$i-($tabcols*7),($tabrows*7)+2,$text_color_1);
                            continue;
                        case ($tabcols*8):
                            ImageLine($im,$i-1,$tabrows*7,$i-1,$tabhi-1,$text_color_1);
                            ImageLine($im,$i+1,$tabrows*7,$i+1,$tabhi-1,$text_color_1);
                            continue;
                    }
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }    
                $t=1;
                for($i=$tabrows;$i<=$tabhi+(13*$tabrows);$i+=$tabrows){ 
                    if($i==($tabrows*7)){
                        ImageLine($im,0,$i-3,$tabcols*8,$i-3,$text_color_1);
                        ImageLine($im,0,$i-2,$tabcols*8,$i-2,$text_color_1);
                        ImageLine($im,0,$i-1,$tabcols*8,$i-1,$text_color_1);
                        ImageLine($im,0,$i+1,$tabcols*8,$i+1,$text_color_1);
                        ImageLine($im,0,$i+2,$tabcols*8,$i+2,$text_color_1);
                    }
                    ImageLine($im,0,$i,$tablen-1,$i,$text_color_1); 
                    $font = 'arial.ttf';   
                    imagettftext($im, 14, 0, 365, 30, $text_color_1, $font, 'Pha tích cực');
                    imagettftext($im, 14, 0, 55, 310, $text_color_1, $font, 'Pha tiềm tàng');
                    imagettftext($im, 12, 0, 520, 100, $text_color_1, $font, 'Đường hành động');
                    imagettftext($im, 12, 0, 625, 165, $text_color_1, $font, 'Đường hành động');
                    imagettftext($im, 12, 0, $i-30, $tabhi-50, $text_color_1, $font, $t);
                    $t++;
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);                                
            }
            $text_blue = ImageColorAllocate ($im, 0, 0, 255);
            $text_red = ImageColorAllocate ($im, 255, 0, 0);
            $xunit=$tabcols/60; // Unit of 1 hour in pixels = Width of day's column divided by 24 hours
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time'])||empty($bp['cotucung'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5); 
                        //tinh tien den pha tich cuc vi ctc>3
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(8*$tabcols)-5;
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(9*$tabcols)-5;
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(10*$tabcols)-5;
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(11*$tabcols)-5;
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(12*$tabcols)-5;
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(13*$tabcols)-5;
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(14*$tabcols)-5;
                            }else{
                                $ox2=(15*$tabcols)-5;
                            } 
                            $ox_time=$ox2-$tabcols/2;
                            imagettftext($im, 10, 0, $ox_time+5, $tabhi-10, $text_color_1, $font, $time['0'].':'.$time['1']);
                            $oy2=((10-$bp['cotucung'])*$tabcols)+5;
                            $oy2_1=((10-$bp['dolot'])*$tabcols)+5;
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if($time['1']>0){
                                $ox2=$ox1+($time['1']*$xunit+($time['0']-$time_bf)*$tabcols)-2;                                 
                            }else{
                                $ox2=$ox1+(($time['0']-$time_bf)*$tabcols)-2; 
                            }
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(8*$tabcols)-5;
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(9*$tabcols)-5;
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(10*$tabcols)-5;
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(11*$tabcols)-5;
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(12*$tabcols)-5;
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(13*$tabcols)-5;
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(14*$tabcols)-5;
                            }else{
                                $ox2_1=(15*$tabcols)-5;
                            }
                            $ox_time=$ox1+($time['0']-$time_bf)*$tabcols;      
                            imagettftext($im, 10, 0, $ox_time-12, $tabhi-10, $text_color_1, $font, $time['0'].':'.$time['1']);
                            imagettftext($im, 10, 0, $ox2_1-12, $tabhi-10, $text_color_1, $font, $time['0'].':'.$time['1']); 
                            $oy2=((10-$bp['cotucung'])*$tabcols)+5;
                            $oy2_1=((10-$bp['dolot'])*$tabcols)+5;
                            if($ox2_1!=0 && $count<2){
                                imagettftext($im, 12, 0, $ox2_1, $oy2, $text_red, $font, 'X');
                                imagettftext($im, 11, 0, $ox2_1, $oy2_1, $text_blue, $font, 'O');
                            }
                            if($ox1 || $oy1){
                                imagesetthickness($im, 1);
                                //ve duong dut khuc
                                $style = array($text_red, $text_red, $text_red, $text_red, $text_red, $background_color, $background_color, $background_color, $background_color, $background_color);
                                imagesetstyle($im, $style);
                                imageline($im, $ox2+1,$oy2-5,$ox2_1,$oy2-5, IMG_COLOR_STYLED);
                                $style = array($text_blue, $text_blue, $text_blue, $text_blue, $text_blue, $background_color, $background_color, $background_color, $background_color, $background_color);
                                imagesetstyle($im, $style);
                                imageline($im, $ox2+2,$oy2_1-5,$ox2_1,$oy2_1-5, IMG_COLOR_STYLED);
                            }
                            $count++;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox2_1=0;                                
                            }
                            if($i==0){
                                $ox2=-4;
                                imagettftext($im, 10, 0, $ox2, $tabhi-10, $text_color_1, $font, $time['0'].':'.$time['1']);
                            }else{
                                if($time['1']>0){
                                    $ox2=$ox1+($time['1']*$xunit+($time['0']-$time_bf)*$tabcols);                                 
                                }else{
                                    $ox2=$ox1+(($time['0']-$time_bf)*$tabcols); 
                                }
                                $ox_time=$ox1+($time['0']-$time_bf)*$tabcols;
                                imagettftext($im, 10, 0, $ox_time-($tabcols/2)+7, $tabhi-10, $text_color_1, $font, $time['0'].':'.$time['1']);
                            }
                            $oy2=((10-$bp['cotucung'])*$tabcols)+5;
                            $oy2_1=((10-$bp['dolot'])*$tabcols)+5;
                        }                                                
                        imagettftext($im, 12, 0, $ox2, $oy2, $text_red, $font, 'X');
                        imagettftext($im, 11, 0, $ox2, $oy2_1, $text_blue, $font, 'O');
                        if($i==($bprows-1)){
                            imagettftext($im, 11, 0, 600, 220, $text_color_1, $font, $bp['ghichu']);
                        }
                        if(($ox1 || $oy1) && $oy1==$oy2){
                            imagesetthickness($im, 1);
                            //ve duong dut khuc
                            $style = array($text_red, $text_red, $text_red, $text_red, $text_red, $background_color, $background_color, $background_color, $background_color, $background_color);
                            imagesetstyle($im, $style);
                            imageline($im, $ox1+2,$oy1,$ox2,$oy2, IMG_COLOR_STYLED);
                            $style = array($text_blue, $text_blue, $text_blue, $text_blue, $text_blue, $background_color, $background_color, $background_color, $background_color, $background_color);
                            imagesetstyle($im, $style);
                            imageline($im, $ox1+2,$oy1_1-5,$ox2,$oy2_1-5, IMG_COLOR_STYLED);
                        }else if($ox1 || $oy1){
                            imagesetthickness($im, 1);
                            //vẽ đường liền
                            ImageLine($im,$ox1,$oy1-3,$ox2,$oy2-3,$text_red);
                            $style = array($text_blue, $text_blue, $text_blue, $text_blue, $text_blue, $background_color, $background_color, $background_color, $background_color, $background_color);
                            imagesetstyle($im, $style);
                            imageline($im, $ox1+2,$oy1_1-10,$ox2,$oy2_1-10, IMG_COLOR_STYLED);
                        }
                        $ox1=$ox2;
                        $ox1_1=$ox2_1;
                        $oy1=$oy2;
                        $oy1_1=$oy2_1;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        //So con co
        case 4:
            $tabhi=100; # Height of graph chart in pixels
            $tablen=1200; # Total width of graph chart in pixels
            $tabcols=$tablen/48; # Total number of vertical lines
            $tabrows=$tabhi/10; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<$tablen;$i+=$tabcols){            
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }
                for($i=$tabrows;$i<$tabhi;$i+=$tabrows){
                    if($i%20==0){
                        $color=$text_color_1;                        
                    }else{
                        $color=$text_color;
                    }
                    ImageLine($im,0,$i,$tablen-1,$i,$color);
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);
            }
            $text_blue = ImageColorAllocate ($im, 0, 0, 255);
            $xoffs=$tabcols*2; 
            $xunit=$xoffs/60; // Unit of 1 minute in pixels = Width of day's column divided by 60 minutes
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';                   
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time'])||empty($bp['soconco'])||empty($bp['sogiay'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);
                        $oy2=(50-$bp['sogiay'])*2;
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(16*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(18*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(20*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(22*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(24*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(26*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(28*$tabcols);
                            }else{
                                $ox2=(30*$tabcols);
                            }
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(16*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(18*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(20*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(22*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(24*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(26*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(28*$tabcols);
                            }else{
                                $ox2_1=(30*$tabcols);
                            } 
                            $ox2=$ox1+($time['0']-$time_bf)*$xoffs;
                            imagesetthickness($im, 5);
                            //ve duong ben trai
                            ImageLine($im,$ox2_1,$oy2-1,$ox2_1,$tabhi-1,$text_blue);
                            //ve duong nam ngang
                            ImageLine($im,$ox2_1-2,$oy2,$ox2_1+($tabcols*2)+2,$oy2,$text_blue);
                            //ve duong ben phai
                            ImageLine($im,$ox2_1+($tabcols*2),$oy2-1,$ox2_1+($tabcols*2),$tabhi-1,$text_blue);
                            //ve duong day
                            ImageLine($im,$ox2_1,$tabhi-1,$ox2_1+($tabcols*2),$tabhi-1,$text_blue);
                            if(20<$bp['soconco'] && $bp['soconco']<40){
                                $src = imagecreatefrompng($root_path.'gui/img/common/default/socphai_bdcd.png');
                                imagecopyresized($im, $src, $ox2_1+1, $oy2+1, 0, 0, ($tabcols*2)-2, 151, 195, 151);           
                            }else if($bp['soconco']>40){
                                $src = imagecreatefrompng($root_path.'gui/img/common/default/soctrai_bdcd.png');
                                imagecopyresized($im, $src, $ox2_1+1, $oy2+1, 0, 0, ($tabcols*2)-2, 151, 195, 151);
                            }
                            $count++;
                            $ox1_1=$ox2_1;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }
                            if($i==0){
                                $ox2=1;
                            }else{
                                $ox2=$ox1+($time['0']-$time_bf)*$xoffs; 
                            } 
                        }
                        imagesetthickness($im, 5);
                        //ve duong ben trai
                        ImageLine($im,$ox2,$oy2-1,$ox2,$tabhi-1,$text_blue);
                        //ve duong nam ngang
                        ImageLine($im,$ox2-2,$oy2,$ox2+($tabcols*2)+2,$oy2,$text_blue);
                        //ve duong ben phai
                        ImageLine($im,$ox2+($tabcols*2),$oy2-1,$ox2+($tabcols*2),$tabhi-1,$text_blue);
                        if(20<$bp['soconco'] && $bp['soconco']<40){
                            $src = imagecreatefrompng($root_path.'gui/img/common/default/socphai_bdcd.png');
                            imagecopyresized($im, $src, $ox2+1, $oy2+1, 0, 0, ($tabcols*2)-2, 151, 195, 151);           
                        }else if($bp['soconco']>40){
                            $src = imagecreatefrompng($root_path.'gui/img/common/default/soctrai_bdcd.png');
                            imagecopyresized($im, $src, $ox2+1, $oy2+1, 0, 0, ($tabcols*2)-2, 151, 195, 151);
                        }
                        $ox1=$ox2;
                        $oy1=$oy2;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        //Mach & huyet ap
        case 5:
            $tabhi=600; # Height of graph chart in pixels
            $tablen=950; # Total width of graph chart in pixels
            $tabcols=$tablen/48; # Total number of vertical lines
            $tabrows=$tabhi/24; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<=$tablen;$i+=$tabcols){            
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }
                for($i=$tabrows;$i<=$tabhi;$i+=$tabrows){
                    if($i%2==0){
                        $color=$text_color_1;  
                        ImageLine($im,0,$i-1,$tablen-1,$i-1,$color);
                    }else{
                        $color=$text_color;
                    }
                    ImageLine($im,0,$i,$tablen-1,$i,$color);
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);
            }
            $text_blue = ImageColorAllocate ($im, 0, 0, 255);
            $text_red = ImageColorAllocate ($im, 255, 0, 0);
            $xoffs=$tabcols*2; 
            $xunit=$xoffs/60; // Unit of 1 minute in pixels = Width of day's column divided by 60 minutes
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';                   
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time'])||empty($bp['mach'])||empty($bp['huyetap'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);
                        $oy2=(180-$bp['mach'])*5;  
                        $huyetap=explode("/",$bp['huyetap']);
                        $oy2_1=(180-$huyetap[0])*5;
                        $oy2_2=(180-$huyetap[1])*5;
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(16*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(18*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(20*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(22*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(24*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(26*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(28*$tabcols);
                            }else{
                                $ox2=(30*$tabcols);
                            }
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(16*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(18*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(20*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(22*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(24*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(26*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(28*$tabcols);
                            }else{
                                $ox2_1=(30*$tabcols);
                            } 
                            $ox2=$ox1+($time['0']-$time_bf)*$xoffs+$time['1']*$xunit;
                            imagesetthickness($im, 5);
                            imagefilledarc($im,$ox2_1,$oy2,5,10,0,360,$text_blue,IMG_ARC_PIE); 
                            imagesetthickness($im, 1);
                            //ve duong dut khuc
                            $style = array($text_red, $text_red, $text_red, $text_red, $text_red, $background_color, $background_color, $background_color, $background_color, $background_color);
                            imagesetstyle($im, $style);
                            imageline($im, $ox2,$oy2_1,$ox2_1,$oy2_1, IMG_COLOR_STYLED);
                            imageline($im, $ox2+1,$oy2_1,$ox2_1,$oy2_1, IMG_COLOR_STYLED);
                            imageline($im, $ox2+2,$oy2_1,$ox2_1,$oy2_1, IMG_COLOR_STYLED);
                            
                            imagesetthickness($im, 2);
                            //ve duong thang
                            ImageLine($im,$ox2_1,$oy2_1-3,$ox2_1,$oy2_2-3,$text_red);
                            imagesetthickness($im, 1);
                            //ve mui ten
                            ImageLine($im,$ox2_1-5,$oy2_1+20,$ox2_1,$oy2_1,$text_red);
                            ImageLine($im,$ox2_1+5,$oy2_1+20,$ox2_1,$oy2_1,$text_red);                        
                            ImageLine($im,$ox2_1-5,$oy2_2-20,$ox2_1,$oy2_2,$text_red);
                            ImageLine($im,$ox2_1+5,$oy2_2-20,$ox2_1,$oy2_2,$text_red);                            
                            $count++; 
                            $ox1_1=$ox2_1;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }
                            if($i==0){
                                $ox2=1;
                            }else{
                                if($time['1']>0){
                                    $ox2=$ox1+$time['1']*$xunit+($time['0']-$time_bf)*$xoffs;                                 
                                }else{
                                    $ox2=$ox1+($time['0']-$time_bf)*$xoffs; 
                                }
                            } 
                        }   
                        imagesetthickness($im, 5);
                        imagefilledarc($im,$ox2,$oy2,5,10,0,360,$text_blue,IMG_ARC_PIE);   
                        imagesetthickness($im, 2);
                        //ve duong thang
                        ImageLine($im,$ox2,$oy2_1-3,$ox2,$oy2_2-3,$text_red);
                        imagesetthickness($im, 1);
                        //ve mui ten
                        ImageLine($im,$ox2-5,$oy2_1+20,$ox2,$oy2_1,$text_red);
                        ImageLine($im,$ox2+5,$oy2_1+20,$ox2,$oy2_1,$text_red);                        
                        ImageLine($im,$ox2-5,$oy2_2-20,$ox2,$oy2_2,$text_red);
                        ImageLine($im,$ox2+5,$oy2_2-20,$ox2,$oy2_2,$text_red);                        
                        $ox1=$ox2;                        
                        $oy1=$oy2;
                        $oy1_1=$oy2_1;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        //Thuoc
        case 6:
            $tabhi=440; # Height of graph chart in pixels
            $tablen=960; # Total width of graph chart in pixels
            $tabcols=$tablen/24; # Total number of vertical lines
            $tabrows=$tabhi/11; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<=$tablen;$i+=$tabcols){ 
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }    
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);               
            }
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time'])||empty($bp['thuoc'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);  
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(8*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(9*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(10*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(11*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(12*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(13*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(14*$tabcols);
                            }else{
                                $ox2=(15*$tabcols);
                            }
                            $ox2=$ox2+$tabcols/4;
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(8*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(9*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(10*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(11*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(12*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(13*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(14*$tabcols);
                            }else{
                                $ox2_1=(15*$tabcols);
                            }
                            $ox2=$ox1+($time['0']-$time_bf)*$tabcols; 
                            $ox2_1+=$tabcols/4;
                            imagettftext($im, 16, 90, $ox2_1+4, $tabhi-20, $text_color_1, $font, $bp['thuoc']);
                            $count++;
                            $ox1_1=$ox2_1;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }   
                            if($i==0){
                                $ox2=$ox1+$tabcols/4;
                            }else{
                                $ox2=$ox1+($time['0']-$time_bf)*$tabcols;
                            }
                        }                           
                        imagettftext($im, 16, 90, $ox2+4, $tabhi-20, $text_color_1, $font, $bp['thuoc']);
                        $ox1=$ox2;
                        $oy1=$oy2;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        //Nhiet do
        case 7:
            $tabhi=110; # Height of graph chart in pixels
            $tablen=960; # Total width of graph chart in pixels
            $tabcols=$tablen/24; # Total number of vertical lines
            $tabrows=$tabhi/11; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn
                for($i=$tabcols;$i<$tablen;$i+=$tabcols){
                    if($i==($tabcols*8) || $i==($tabcols*12) || $i==($tabcols*16) || $i==($tabcols*20)){
                        ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                    }else{
                        ImageLine($im,$i,0,$i,$tabhi-1,$text_color);
                    }                      
                }   
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);                                
            }
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time'])||empty($bp['nhietdo'])||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);  
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(8*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(9*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(10*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(11*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(12*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(13*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(14*$tabcols);
                            }else{
                                $ox2=(15*$tabcols);
                            }
                            $ox2=$ox2+$tabcols/4;
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(8*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(9*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(10*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(11*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(12*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(13*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(14*$tabcols);
                            }else{
                                $ox2_1=(15*$tabcols);
                            }
                            $ox2=$ox1+($time['0']-$time_bf)*$tabcols; 
                            imagettftext($im, 16, 0, $ox2_1, $tabhi/2, $text_color_1, $font, $bp['nhietdo']);
                            imagettftext($im, 14, 0, $ox2_1, $tabhi-20, $text_color_1, $font, '(C)');
                            $count++;
                            $ox1_1=$ox2_1;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }
                            if($i==0){
                                $ox2=$ox1;
                            }else{
                                $ox2=$ox1+($time['0']-$time_bf)*$tabcols; 
                            }
                        }
                        imagettftext($im, 16, 0, $ox2, $tabhi/2, $text_color_1, $font, $bp['nhietdo']);
                        imagettftext($im, 14, 0, $ox2, $tabhi-20, $text_color_1, $font, '(C)');
                        $ox1=$ox2;                        
                        $oy1=$oy2;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];                        
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        //Dieu duong
        case 8:
            $tabhi=440; # Height of graph chart in pixels
            $tablen=960; # Total width of graph chart in pixels
            $tabcols=$tablen/24; # Total number of vertical lines
            $tabrows=$tabhi/11; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn
                for($i=$tabcols;$i<=$tablen;$i+=$tabcols){                    
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }    
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);                
            }
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if((empty($bp['msr_time'])||empty($bp['bdcd_by']))||(!empty($bp['lanmangthai']) || !empty($bp['lansinh']) || !empty($bp['giooivo']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);  
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(8*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(9*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(10*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(11*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(12*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(13*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(14*$tabcols);
                            }else{
                                $ox2=(15*$tabcols);
                            }
                            $ox2=$ox2+$tabcols/2;
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(8*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(9*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(10*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(11*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(12*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(13*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(14*$tabcols);
                            }else{
                                $ox2_1=(15*$tabcols);
                            }
                            $ox2=$ox1+($time['0']-$time_bf)*$tabcols; 
                            $ox2_1+=$tabcols/2;
                            imagettftext($im, 12, 90, $ox2_1, $tabhi-20, $text_color_1, $font, $bp['bdcd_by']);
                            $count++;
                            $ox1_1=$ox2_1;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }   
                            if($i==0){
                                $ox2=$ox1+$tabcols/2;
                            }else{
                                $ox2=$ox1+($time['0']-$time_bf)*$tabcols;
                            }
                        }                           
                        imagettftext($im, 12, 90, $ox2, $tabhi-20, $text_color_1, $font, $bp['bdcd_by']);
                        $ox1=$ox2;
                        $oy1=$oy2;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];
                    }                       
                    $bp_obj->MoveFirst();
                }
            }
            break;
        case 9:
            $tabhi=40; # Height of graph chart in pixels
            $tablen=1200; # Total width of graph chart in pixels
            $tabcols=$tablen/48; # Total number of vertical lines
            $tabrows=$tabhi/2; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<$tablen;$i+=$tabcols){            
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }
                for($i=$tabrows;$i<$tabhi;$i+=$tabrows){                        
                    ImageLine($im,0,$i,$tablen-1,$i,$text_color_1);
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);
            }
            break;
        //Nuoc tieu
        case 10:
            $tabhi=60; # Height of graph chart in pixels
            $tablen=1200; # Total width of graph chart in pixels
            $tabcols=$tablen/48; # Total number of vertical lines
            $tabrows=$tabhi/3; # Total number of horizontal lines
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png'); // Loads the ready made image (makes this routine faster)
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                # The vertical and horizontal lines are drawn

                for($i=$tabcols;$i<$tablen;$i+=$tabcols){            
                    ImageLine($im,$i,0,$i,$tabhi-1,$text_color_1);
                }
                for($i=$tabrows;$i<$tabhi;$i+=$tabrows){                        
                    ImageLine($im,0,$i,$tablen-1,$i,$text_color_1);
                }
                //ve border-left
                ImageLine($im,0,0,0,$tabhi,$text_color_1);
                //ve border-top
                ImageLine($im,0,0,$tablen,0,$text_color_1);
                //ve border-right
                ImageLine($im,$tablen-1,0,$tablen-1,$tabhi-1,$text_color_1);
                //ve border-bottom
                ImageLine($im,0,$tabhi-1,$tablen-1,$tabhi-1,$text_color_1);
            } 
            $xoffs=$tabcols*2; 
            $xunit=$xoffs/60;
            if($ok){
                $ox1=0;$ox1_1=0;$oy1=0;$oy1_1=0;$count=1;
                $time_bf=0;
                $font = 'arial.ttf';
                #**************** begin of curve tracing  Blood Pressure***************                    
                if($bprows){                        
                    for($i=0;$i<$bprows;$i++)
                    {                        
                        $bp=$bp_obj->FetchRow();                        
                        if(empty($bp['msr_time']) || (empty($bp['dam']) && empty($bp['acetone']) && empty($bp['luong']))) continue;
                        $time=explode(':',$bp['msr_time']);
                        imagesetthickness($im, 5);  
                        $str['1']=$bp['dam'];
                        $str['2']=$bp['aceton'];
                        $str['3']=$bp['luong'];                        
                        for($j=1;$j<4;$j++){
                            switch($str[$j]){
                                case 'không':
                                    $oy2[$j]='(--)';
                                    break;
                                case 'ít':
                                    $oy2[$j]='(+)';
                                    break;
                                case 'vừa':
                                    $oy2[$j]='(++)';
                                    break;
                                case 'nhiều':
                                    $oy2[$j]='(+++)';
                                    break;
                                default:
                                    $oy2[$j]='';
                                    break;
                            } 
                        } 
                        
                        if($bp['cotucung']>=3 && $i==0){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2=(16*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2=(18*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2=(20*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2=(22*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2=(24*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2=(26*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2=(28*$tabcols);
                            }else{
                                $ox2=(30*$tabcols);
                            }
                        }else if($bp['cotucung']>=3 && $value_ctc<3 && $i!=0 && $count<2){
                            if(3<=$bp['cotucung'] && $bp['cotucung']<4){
                                $ox2_1=(16*$tabcols);
                            }else if(4<=$bp['cotucung'] && $bp['cotucung']<5){
                                $ox2_1=(18*$tabcols);
                            }else if(5<=$bp['cotucung'] && $bp['cotucung']<6){
                                $ox2_1=(20*$tabcols);
                            }else if(6<=$bp['cotucung'] && $bp['cotucung']<7){
                                $ox2_1=(22*$tabcols);
                            }else if(7<=$bp['cotucung'] && $bp['cotucung']<8){
                                $ox2_1=(24*$tabcols);
                            }else if(8<=$bp['cotucung'] && $bp['cotucung']<9){
                                $ox2_1=(26*$tabcols);
                            }else if(9<=$bp['cotucung'] && $bp['cotucung']<10){
                                $ox2_1=(28*$tabcols);
                            }else{
                                $ox2_1=(30*$tabcols);
                            } 
                            if($time['1']>30){    
                                $ox2=$ox1+($time['0']-$time_bf)*$xoffs+$tabcols;
                            }else{      
                                $ox2=$ox1+($time['0']-$time_bf)*$xoffs;
                            }                        
                            imagettftext($im, 12, 0, $ox2_1, $tabrows-5, $text_color_1, $font, $oy2['1']);
                            imagettftext($im, 12, 0, $ox2_1, ($tabrows-2)*2, $text_color_1, $font, $oy2['2']);
                            imagettftext($im, 12, 0, $ox2_1, ($tabrows-2)*3, $text_color_1, $font, $oy2['3']);
                            $count++;
                            $ox1_1=$ox2_1;
                        }else{
                            if($ox1_1!=0){
                                $ox1=$ox1_1;
                                $ox1_1=0;
                            }
                            if($i==0){
                                $ox2=$tabcols/3;
                            }else{
                                if($time['1']>30){
                                    $ox2=$ox1+($time['0']-$time_bf)*$xoffs+$tabcols;                                 
                                }else{
                                    $ox2=$ox1+($time['0']-$time_bf)*$xoffs; 
                                }
                            }
                        }
                        imagettftext($im, 11, 0, $ox2, $tabrows-5, $text_color_1, $font, $oy2['1']);
                        imagettftext($im, 11, 0, $ox2, ($tabrows-2)*2, $text_color_1, $font, $oy2['2']);
                        imagettftext($im, 11, 0, $ox2, ($tabrows-2)*3, $text_color_1, $font, $oy2['3']);
                        $ox1=$ox2;
                        $oy1=$oy2;
                        $time_bf=$time['0'];
                        $value_ctc=$bp['cotucung'];                        
                    }                    
                    $bp_obj->MoveFirst();
                }
            }
            break;
        case 11:
            $tabhi=250; # Height of graph chart in pixels
            $tablen=90; # Total width of graph chart in pixels
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png');
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                $font = 'arial.ttf';
                ImageLine($im,2,2,25,2,$text_color_1);
                ImageLine($im,12,2,12,53,$text_color_1);
                imagettftext($im, 11, 90, 12, $tabhi-86, $text_color_1, $font, 'Cổ tử cung (cm)');
                imagettftext($im, 10, 90, 27, $tabhi-98, $text_color_1, $font, '(đánh dấu x)');
                ImageLine($im,2,$tabhi-33,25,$tabhi-33,$text_color_1);
                ImageLine($im,12,$tabhi-33,12,$tabhi-84,$text_color_1);
                
                ImageLine($im,40,93,$tablen-30,93,$text_color_1);
                ImageLine($im,50,93,50,104,$text_color_1);
                imagettftext($im, 11, 90, 53, $tabhi-50, $text_color_1, $font, 'Độ lọt của đầu');
                imagettftext($im, 10, 90, 68, $tabhi-70, $text_color_1, $font, '(khanh 0)');
                ImageLine($im,40,$tabhi-33,$tablen-30,$tabhi-33,$text_color_1);
                ImageLine($im,50,$tabhi-33,50,200,$text_color_1);
                imagettftext($im, 10, 0, 52, $tabhi-37, $text_color_1, $font, 'Số giờ');
                imagettftext($im, 10, 0, 17, $tabhi-14, $text_color_1, $font, 'Thời điểm');
            }
            break;
        case 12:
            $tabhi=80; # Height of graph chart in pixels
            $tablen=90; # Total width of graph chart in pixels
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png');
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                // $background_color = ImageColorAllocate ($im, 205,225,236);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                $font = 'arial.ttf';
                imagettftext($im, 10, 90, 12, $tabhi-10, $text_color_1, $font, 'Số cơn co');
                imagettftext($im, 10, 90, 27, $tabhi-5, $text_color_1, $font, 'trong 10 phút');                
                $src = imagecreatefrompng($root_path.'gui/img/common/default/soc_bdcd.PNG');
                imagecopyresized($im, $src, 35, 10, 0, 0, 18, 50, 18, 50);
                imagettftext($im, 9, 0, 57, 25, $text_color_1, $font, '<20');
                imagettftext($im, 9, 0, 57, 40, $text_color_1, $font, '20-40');
                imagettftext($im, 9, 0, 57, 55, $text_color_1, $font, '>40');
            }
            break;
        case 13:
            $tabhi=180; # Height of graph chart in pixels
            $tablen=40; # Total width of graph chart in pixels
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png');
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color = ImageColorAllocate ($im, 211, 211, 211);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                $font = 'arial.ttf';
                imagettftext($im, 10, 90, 12, $tabhi-30, $text_color_1, $font, 'Các thuốc đã cho');
                imagettftext($im, 10, 90, 27, $tabhi-25, $text_color_1, $font, 'và các dịch truyền');
            }
            break;
        default:
            $tabhi=180; # Height of graph chart in pixels
            $tablen=80; # Total width of graph chart in pixels
            header ('Content-type:image/PNG');
            $im=@ImageCreateFromPNG($root_path.'main/imgcreator/datacurve3.png');
            if(!$im){
                $im = @ImageCreate ($tablen, $tabhi);
                $background_color = ImageColorAllocate ($im, 255,255,255);
                $text_color_1 = ImageColorAllocate ($im, 0, 0, 0);
                $font = 'arial.ttf';
                imagettftext($im, 12, 0, 6, 70, $text_color_1, $font, 'Mạch');
                imagettftext($im, 12, 0, 11, 100, $text_color_1, $font, 'và');
                imagettftext($im, 12, 0, 1, 130, $text_color_1, $font, 'Huyết áp');
                imagefilledarc($im,72,45,13,13,0,360,$text_color_1,IMG_ARC_PIE);
                imagesetthickness($im, 2);
                ImageLine($im,65,55,$tablen,55,$text_color_1);
                ImageLine($im,72,55,72,$tabhi-33,$text_color_1);
                ImageLine($im,65,$tabhi-33,$tablen,$tabhi-33,$text_color_1);
            }
            break;
    }    
    $filename=$pn.substr($date,0,4).substr($date,5,2).substr($date,8,2).$flag.".png";
    ImagePNG($im,$root_path."uploads/photos/datacurve/".$filename);	//save img

    ImagePNG($im);		//show img on page
    ImageDestroy ($im); 
?>
