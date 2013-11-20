<?php
$LDPageTitle='Các Khoa'; //'Departments';
$LDDeptTxt='Khoa'; //'Department';
$LDOpenHrsTxt='Giờ hoạt động'; //'Working Hours';
$LDChkHrsTxt='Giờ thăm khám bệnh'; //'Medical Checkup Hours';
$LDClk4Info='Xem thông tin chi tiết hơn về'; //'Click for more Information about';
$LDDayTxt='Ngày'; //'Day';

/**
* A small dictionary:
* ENT = Eye, Nose, Throath
* OB-Gyne = Obstetrics-Gynecology
*/
$dept=array(		'Ngoại Tổng quát', //'General Surgery',
					'Khoa phẫu thuật cấp cứu', //'Emergency Surgery',
					'Phẫu thuật tạo hình', //'Plastic Surgery',
					'Tai Mũi Họng', //'ENT',
					'Khoa Mắt', //'Opthalmology',
					'Giải Phẫu Bệnh', //'Pathology',
					'Sản Phụ Khoa', //'OB-Gyne',
					'Vật Lý Trị Liệu', //'Physical Therapy',
					'Nội Khoa', //'Internal Medicine',
					'Ung Bướu', //'Oncology',
					'Khoa Bảo Trì', //'Maintenance',
					'Khoa chăm sóc phục hồi',//'Intermediate Care Unit',
					'Chăm Sóc Đặc Biệt', //'Intensive Care Unit',
					'Xét nghiệm Y Khoa', //'Medical Laboratory',
					'Khoa Cấp cứu', //'Emergency Ambulatory',
					'Khám Tổng Quát', //'General Ambulatory',
					'Siêu Âm', //'Sonography',
					'Khoa Y học hạt nhân', //'Nuclear Diagnostics',
					'');//'Internal Medicine Ambulatory');
					
/**
* Do not translate the $target variable
*/
$target=array('dept_generalsurgery',
					'dept_emergency',
					'dept_plasticsurgery',
					'dept_ent',
					'dept_eyesurgery',
					'dept_pathology',
					'dept_gynecology',
					'physiotherapy',
					'dept_internalmed',
					'dept_oncology',
					'dept_techservice',
					'dept_IMCU',
					'dept_ICU',
					'dept_lab',
					'unfamb',
					'allamb',
					'sono',
					'nuklear',
					'inmed');

$LDBackTxt='Quay Lại'; //'Back';

$LDOpenDays=array(	'Thứ Hai', //'Monday',
					'Thứ Ba', //'Tuesday',
					'Thứ Tư', //'Wednesday',
					'Thứ Năm', //'Thursday',
					'Thứ Sáu', //'Friday',
					'Thứ Bảy', //'Saturday',
					'Chủ Nhật'); //'Sunday');
$LDOpenTimes=array('8.30 - 21.00', //Mo
					'8.30 - 21.00', //Di
					'8.30 - 21.00', //Mi
					'8.30 - 21.00', //Do
					'8.30 - 21.00', //Fr
					'8.30 - 21.00', //Sa
					'Nghỉ'); //So
$LDVisitTimes=array('12.30 - 15.00 , 19.00 - 21.00', //Mo
					'12.30 - 15.00 , 19.00 - 21.00', //Di
					'12.30 - 15.00 , 19.00 - 21.00', //Mi
					'12.30 - 15.00 , 19.00 - 21.00', //Do
					'12.30 - 15.00 , 19.00 - 21.00', //Fr
					'12.30 - 15.00 , 19.00 - 21.00', //Sa
					'Nghỉ'); //So
?>
