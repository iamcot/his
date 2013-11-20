<?php
$Jan='Tháng Một'; //'January';
$Feb='Tháng Hai'; //'February';
$Mae='Tháng Ba'; //'March';
$Apr='Tháng Tư'; //'April';
$Mai='Tháng Năm'; //'May';
$Jun='Tháng Sáu'; //'June';
$Jul='Tháng Bảy'; //'July';
$Aug='Tháng Tám'; //'August';
$Sep='Tháng Chín'; //'September';
$Okt='Tháng Mười'; //'October';
$Nov='Tháng Mười Một'; //'November';
$Dez='Tháng Mười Hai'; //'December';

/**
* Note: the first element of $monat is set to empty string
*/
$monat=array('',$Jan,$Feb,$Mae,$Apr,$Mai,$Jun,$Jul,$Aug,$Sep,$Okt,$Nov,$Dez);

$LDDoctors='Bác sỹ'; //'Doctors';
$LDQView='Danh sách bác sỹ trực'; //'DOC Quickview';  // DOC = doctor on call
$LDQViewTxt='Xem qua lịch trực bác sỹ'; //'Quickview of today\'s DOC (doctor-on-call) schedule';
$LDDutyPlan='Lịch trực'; //'Duty plan';
$LDDutyPlanTxt='Xếp lịch trực, xem, sửa, xóa ...'; //'Duty plan, view, update, delete, manage, etc.';
$LDDocsList='Danh sách bác sỹ'; //'Doctors\' list';
$LDDocsListTxt='Lập hoặc điều chỉnh danh sách bác sỹ'; //'Create or update doctors\' list, enter data, etc..';
$LDDocsForum='Thảo luận trên mạng'; //'Forum';
$LDDocsForumTxt='Thảo luận trên mạng (dành cho bác sỹ)'; //'Discussions forum for doctors';
$LDNews='Tin tức'; //'News';
$LDNewsTxt='Soạn, đọc hoặc sửa bản tin'; //'Compose, read, edit news';
$LDMemo='Thông báo'; //'Memo';
$LDMemoTxt='Lập, đọc hoặc sửa thông báo'; //'Compose, read, edit memo';
$LDCloseAlt='Close physicians/surgeons\' window';
$LDDocsOnDuty='Bác sỹ trực'; //'Doctors on Call';
$LDNursOnDuty = 'Điều dưỡng trực';
$LDTabElements1=array(			 'Khoa', //'Department',								 
								 'Chấm công' //'Duty plan'
								 );
$LDTabElements=array(			 'Khoa', //'Department',
								 'Sáng',// 'DOC 1',
								 'Ngoài giờ sáng', //'Beeper/Phone',
								 'Tối', //'DOC 2',								 
								 'Lịch trực' //'Duty plan'
								 );
$LDNursTabElements=array(		 'Khoa', //'Department',
								 'ĐIỀU DƯỠNG 1',// 'DOC 1',
								 'Điện thoại', //'Beeper/Phone',
								 'ĐIỀU DƯỠNG 2', //'DOC 2',
								 'Điện thoại', //'Beeper/Phone',
								 'Lịch trực' //'Duty plan'
								 );
$LDShowActualPlan='Cho xem lịch trực'; //'Show actual duty plan';
$LDShortDay=array(				'CN', //'Su',
								'T2', //'Mo',
								'T3', //'Tu',
								'T4', //'We',
								'T5', //'Th',
								'T6', //'Fr',
								'T7' //'Sa'
								);
$LDFullDay=array(				'Chủ Nhật', //'Sunday',
								'Thứ Hai', //'Monday',
								'Thứ Ba', //'Tuesday',
								'Thứ Tư', //'Wednesday',
								'Thứ Năm', //'Thursday',
								'Thứ Sáu', //'Friday',
								'Thứ Bảy' //'Saturday'
								);
$LDDoc1='Ngày'; //'Doctor-On-Call 1';
$LDDoc2='Đêm'; //'Doctor-On-Call 2';
$LDNurser1='Điều dưỡng 1'; //'Doctor-On-Call 1';
$LDNurser2='Điều dưỡng 2'; //'Doctor-On-Call 2';
$LDClosePlan='Đóng lịch trực'; //'Close this plan';
$LDNewPlan='Xếp lịch trực'; //'Create a new plan';
$LDBack='Quay lại'; //'Back';
$LDHelp='Trợ giúp'; //'Help';
$LDMakeDutyPlan='Xếp lịch trực'; //'Create dutyplan';
$LDClk2Plan='Mở danh sách nhân viên'; //'Click to open personnel list';
$LDInfo4Duty='Thông tin'; //'Information';
$LDStayIn='Trực tại chỗ'; //'Stay-in duty';
$LDOnCall='Trực sẵn sàng (on-call)'; //'On call duty';
$LDPhone='Điện thoại'; //'Phone';
$LDBeeper='Di động'; //'Beeper';
$LDMoreInfo='Thông tin khác'; //'More Info';
$LDOn='on';
$LDCloseWindow='Close window';
$LDMonth='Tháng'; //'Month';
$LDYear='Năm'; //'Year';
$LDPerElements=array(				'Họ và tên đệm', //'Family name',
									'Tên', //'Given name',
									'Ngày sinh', //'Date of birth',
									'Beeper',
									'Điện thoại', //'Phone',
									'Beeper',
									'Điện thoại' //'Phone'
									);
$LDChgDept='Đổi khoa  '; //'Change department: ';
$LDChange='Đổi'; //'Change';
$LDCreatePersonList='Tạo một danh sách nhân lực'; //'Create a list for personnel';
$LDNoPersonList='Chưa có danh sách nhân lực!<br> Vui lòng lập ra một danh sách trước khi tiếp tục.'; //'The list of personnel is not yet created. Please create the list first.';
$LDShow='Xem'; //'Show';

$LDDOCS='Bác sỹ trực'; //'DOC Scheduler';
$LDDOCSTxt='Xếp lịch trực, xem và điều chỉnh'; //'Doctor On Call Scheduler, plan, view, update, edit, etc.';
$LDDOCSR='DOCSR';
$LDDOCSRTxt='Doctor On Call Schedule Requester';
/* 2002-09-15 EL */
$LDTestRequest='Chỉ định cận lâm sàng'; //'Test request';
/* 2003-03-16 EL */
$LDContactInfo='Thông tin liên hệ'; //'Contact Info';
$LDPersonalContactInfo='Liên hệ cá nhân'; //'Personal Contact Info';
$LDOnCallContactInfo='Liên hệ trong giờ trực'; //'On-Call Contact Info';
$LDPlsSelectDept='Xin chọn một khoa phòng'; //'Please select a department';
$LDCreateDoctorsList='Lập danh sách bác sỹ'; //'Create doctors\' list';
$LDPlsCreateList='Trước tiên phải lập danh sách'; //'Please create the list first.';
$LDPlsClickButton='Nhấn vào nút sau'; //'Click on the following button.';
$LDFamilyName='Họ và tên đệm'; //'Family name';
$LDGivenName='Tên'; //'Given name';
$LDDateOfBirth='Ngày tháng năm sinh'; //'Date of birth';
$LDEntryPrompt='Xin nhập từ khóa tìm kiếm như họ, tên, mã nhân viên'; //'Please enter a search keyword:<br>(e.g. family name, given name, personnel number, etc.)<br>';
$LDPersonellNr='Mã nhân viên'; //'Personell Nr.';
$LDFunction='Nhiệm vụ'; //'Function';
$LDOptions='Options';
$LDSearchFound='Đã tìm được ~nr~ dữ liệu có liên quan'; //'Search found ~nr~ relevant data.';
$LDAddDoctorToList='Thêm bác sỹ vào danh sách.'; //'Add a doctor to list.';
$LDAdd='Thêm vào'; //'Add';
$LDDelete='Xóa bỏ'; //'Delete';
$LDSureToDeleteEntry='Thật sự muốn xóa thông tin này?'; //'Are you sure you want to delete this entry?';
/* 2003-03-18 EL */
$LDChangeOnlyDept='Đổi khoa'; //'Change the department';
$LDCreateNursesList='Lập danh sách y tá'; //'Create Nurses\' List';
//////////////// add 15/11-Huỳnh //////////////////////
$LDEntryPrompt_1='Xin nhập từ khóa tìm kiếm như họ, tên, mã bệnh nhân';
$LDAlert='Người này có thể trực ngày trước hoặc ngày sau đó rồi. Bạn có muốn chọn người này trực nữa không';
$LDOn1='vào';
$LDAlert1='Người này đã được chọn trực trong ngày rồi.\nBạn vui lòng chọn người khác!';
///////////////////////////////////////////////////////
//add by vy
$LDChamcong='Chấm công';
$LDSang='Sáng';
$LDChieu='Chiều';
$LDMakeChamcong='Lập chấm công';
$LDDocsChamcong='Chấm công bác sỹ';
$LDCapbac='Cấp bậc';
$LDNgoaigio1='Ngoài giờ ngày';
$LDNgoaigio2='Ngoài giờ chiều';
$LDMakeLuong='Tính lương';
$LDLuong='Lương';
?>
