<?php
	/* Load the barcode img if it exists */
    
    if(file_exists($root_path.'cache/barcodes/pn_'.$fen.'.png'))
	{
	   $bc = ImageCreateFrompng($root_path.'cache/barcodes/pn_'.$fen.'.png');
	}elseif(file_exists($root_path.'cache/barcodes/en_'.$fen.'.png')){
	   $bc = ImageCreateFrompng($root_path.'cache/barcodes/en_'.$fen.'.png');
	}	 
	 /* Dimensions of the patient's label */
	 $label_w=282; 
	 $label_h=178;
	 
    // -- create label 
    $label=ImageCreate($label_w,$label_h);
    $ewhite = ImageColorAllocate ($label, 255,255,255); //white bkgrnd
    $elightgreen= ImageColorAllocate ($label, 205, 225, 236);
    $eblue=ImageColorAllocate($label, 0, 127, 255);
    //Màu đen
    $eblack = ImageColorAllocate ($label, 0, 0, 0);
    //Màu đỏ
    $eblack1 = ImageColorAllocate ($label, 0, 0, 255);
	$egray= ImageColorAllocate($label,127,127,127);
	//ImageFillToBorder($label,2,2,$egray,$ewhite);
	ImageRectangle($label,0,0,281,177,$egray);
	
	# Write the data on the label
	# Location info, admission class, blood group
        
//	$locstr=$location['dept_name'].' - '.$location['ward_name'].' '.$location['roomprefix'];
//	if($location['room_nr'])  $locstr.='-'.$location['room_nr'];
//	if($location['bed_nr']) $locstr.=' '.strtoupper(chr($location['bed_nr']+96));
//	$locstr1=$admit_type.' '.$LDInsShortID[$result['insurance_class_nr']];
	

	//if(function_exists(ImageTTFText)&&file_exists($font_path.$arial)&&file_exists($font_path.$verdana))
	//------Tuyen	
	$font_path_imgcreator = $root_path.'main/imgcreator/';
	$arial='ARIAL.TTF';
	if(function_exists(ImageTTFText)&&file_exists($font_path_imgcreator.$arial))
	{		
		$ttf_ok = true;
	}	
	//------
	
	if($ttf_ok){
		
		$tmargin=2;
		$lmargin=6;
		
            #  Full encounter nr
            ImageTTFText($label,10,0,$lmargin,$tmargin+14,$eblack,$arial,$fen);
            # Encounter admission date
            ImageTTFText($label,11,0,$lmargin,$tmargin+30,$eblack,$arial,$result['pdate']);		
            # Family name, first name
            ImageTTFText($label,16,0,$lmargin,$tmargin+56,$eblack,$arial,$result['name_last'].' '.$result['name_first']);
            # Date of birth                
            if(empty($result['tuoi'])){
                if(strlen($result['date_birth'])==4){
                    $tuoi=date('Y')-$result['date_birth'].' '.$LDyearsold;
                }else{
                    $tuoi=date('Y')-substr($result['date_birth'],6,4).' '.$LDyearsold;
                }                    
            }else{                    
                if($result['tuoi']>4){
                    $tuoi=$result['tuoi'].' '.$LDyearsold;
                }else{
                    $tuoi=$result['thang'].' '.$LDmonth1;
                }
            }
            ImageTTFText($label,11,0,$lmargin,$tmargin+74,$eblack1,$arial,$result['date_birth'].' - '.$tuoi.' - '.$LDInsShortID[$result['insurance_class_nr']]);
            # Address street nr, street name
            ImageTTFText($label,9,0,$lmargin,$tmargin+93,$eblack,$arial,$result['addr_str_nr'].' '.$result['addr_str'].' '.ucfirst($result['phuongxa_name']));
		//ImageTTFText($label,11,0,$lmargin,$tmargin+93,$eblack,$arial,ucfirst($result['addr_str']).' '.$result['addr_str_nr']);
            # Address, zip, city/town name
            ImageTTFText($label,10,0,$lmargin,$tmargin+108,$eblack,$arial,ucfirst($result['quanhuyen_name']).' '.$result['citytown_name']);
            # Sex
            switch($result['sex']){
                case 'f':
                    $sex=$LDFemale;
                    break;
                case 'm':
                    $sex=$LDMale;
                    break;
                default:
                    $sex='Không rõ';
                    break;
            }
            ImageTTFText($label,11,0,$lmargin,$tmargin+130,$eblack,$arial,$sex);
		# Insurance co name
            //ImageTTFText($label,14,0,$lmargin,$tmargin+150,$eblack,$arial,$ins_obj->getFirmName($result['insurance_firm_id']));		
            #Blood group
            if(stristr('AB',$result['blood_group'])){
                ImageTTFText($label,11,0,$lmargin+235,$tmargin+127,$eblack,$arial,$result['blood_group']);
            }else if(stristr('KX',$result['blood_group'])){
                ImageTTFText($label,11,0,$lmargin+235,$tmargin+127,$eblack,$arial,$result['blood_group']);
            }
            else{
                ImageTTFText($label,11,0,$lmargin+240,$tmargin+127,$eblack,$arial,$result['blood_group']);
            }
            # Location
            ImageTTFText($label,11,0,$lmargin,$tmargin+150,$eblack,$arial,$admit_type.' - '.$location['dept_name']);            
            #khu-phong-giuong
            ImageTTFText($label,11,0,$lmargin,$tmargin+170,$eblack,$arial,$location['ward_name'].'   P '.$location['room_nr'].'   G '.$location['bed_nr']);
	}else{ # Use system fonts
	
  		#  Full encounter nr
            ImageString($label,4,2,2,$fen,$eblack);
		# Encounter admission date
            ImageString($label,2,2,18,$result['pdate'],$eblack);
		# Family name, first name
            ImageString($label,5,10,40,$result['name_last'].' '.$result['name_first'],$eblack);
		# Date of birth
            if(empty($result['tuoi'])){
                if(strlen($result['date_birth'])==4){
                    $tuoi=date('Y')-$result['date_birth'];
                }else{
                    $tuoi=date('Y')-substr($result['date_birth'],6,4);
                }                    
            }else{                    
                if($result['tuoi']>3){
                    $tuoi=$result['tuoi'];
                }else{
                    $tuoi=$result['thang'];
                }
            }
            ImageString($label,3,10,55,$result['date_birth'].' - '.$tuoi.' '.$LDyearsold.' - '.$LDInsShortID[$result['insurance_class_nr']],$eblack1);
	
            //for($a=0,$l=75;$a<sizeof($addr);$a++,$l+=15) ImageString($label,4,10,$l,$addr[$a],$eblack);
		# Address street nr, street name
            ImageString($label,4,10,75,$LDAddress.': '.strtoupper($result['addr_str_nr']).' '.$result['addr_str'],$eblack);
		//ImageString($label,4,10,75,strtoupper($result['addr_str']).' '.$result['addr_str_nr'],$eblack);
		# Address, zip, city/town name
            ImageString($label,4,10,90,$LDZipCode.': '.strtoupper($result['addr_zip']).' '.$result['citytown_name'],$eblack);
		# Sex
            switch(strtoupper($result['sex'])){
                case 'f':
                    $sex=$LDFemale;
                    break;
                case 'm':
                    $sex=$LDMale;
                    break;
                default:
                    $sex='Không rõ';
                    break;
            }
            ImageString($label,5,10,125,$sex,$eblack);
		# Family name, repeat print
            ImageString($label,5,30,125,$result['name_last'],$eblack);
		# Insurance co name
            //ImageString($label,4,10,140,$ins_obj->getFirmName($result['insurance_firm_id']),$eblack);
		
            #Blood group
            if(stristr('AB',$result['blood_group'])){
                ImageString($label,5,252,125,$result['blood_group'],$eblack);
            }else if(stristr('KX',$result['blood_group'])){
                ImageString($label,5,252,125,$result['blood_group'],$eblack);
            }
            else{
                ImageString($label,5,260,125,$result['blood_group'],$eblack);
            }
			# Location
			ImageString($label,3,10,140,$admit_type.' - '.$location['dept_name'],$eblack);
            ImageString($label,3,10,160,$location['ward_name'].'   P '.$location['room_nr'].'   G '.$location['bed_nr'],$eblack);
	}

	// place the barcode img
    if($bc) ImageCopy($label,$bc,110,4,9,9,170,37);

	if(!$child_img)
	{
    	Imagepng($label);
	
	// *******************************************************************
    // * comment the following one line if you want to deactivate caching of 
	// * the barcode label image
	// *******************************************************************
/*    
	// START
    Imagepng ($im,"../cache/barcodes/pn_".$pn."_bclabel_".$lang.".png");
	// END
*/	
	// Do not edit the following lines
    ImageDestroy($label);
	}
	else
	{
	  if(file_exists($root_path.'main/imgcreator/gd_test_request_'.$subtarget.'.php'))   include_once($root_path.'main/imgcreator/gd_test_request_'.$subtarget.'.php');
	  else Imagepng($label);
	/*   Imagepng($label);*/
	  
	}
?>
