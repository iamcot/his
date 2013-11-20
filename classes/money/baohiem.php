<?php

require_once($root_path.'include/core/inc_environment_global.php');

//Tinh tien giam bao hiem vao ngay hien tai
//$BHtungay, $BHdenngay, $ngaychiphi format: yyyy-mm-dd
//id_traituyen: ko ro =0, dung tuyen=1, trai tuyen=2

// classes\money\baohiem.php
function TienBaoHiem($maBH, $BHtungay, $BHdenngay, $tongchiphi, $ngaytongchiphi, $is_traituyen){

	global $db;
	if(!isset($maBH) || $maBH=='') return 0;

	$ngaychiphi_x = strtotime($ngaytongchiphi);
	$BHtungay_x = strtotime($BHtungay);
	$BHdenngay_x = strtotime($BHdenngay);

	if ($BHtungay_x<=$ngaychiphi_x && $ngaychiphi_x<=$BHdenngay_x) {
		$xetmaBH= explode('-',$maBH); //DN-1-21-23-123-13123, HC-1-45-23-543-53456
		$sql="SELECT * 
			FROM care_insurance_health AS ins 
			WHERE ins.group='".$xetmaBH[0]."' AND ins.object='".$xetmaBH[1]."' ";
		if ($ketqua=$db->Execute($sql)){
			if($ketqua->RecordCount()){
				$items=$ketqua->FetchRow();
				if ($is_traituyen==1){		//khi kham dung tuyen!! :|
					if ($tongchiphi < $items['norms'])
						return $tongchiphi;
					else
						return $tongchiphi*$items['discount_correct'];					
				} else {
					return $tongchiphi*$items['discount_incorrect'];					
				}
			} else return 0;
		} else return $sql;
		 
	} else return 0;
}


?>