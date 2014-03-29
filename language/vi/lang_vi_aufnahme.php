<?php

/* 2002-11-30 EL */
/* Hồ sơ cá nhân, bao gồm các thông tin cá nhân cho tất cả các cá nhân (bệnh nhân, nhân viên) 
** Một số thông tin các có thể ghi trong person.php
** 
*/
$LDPatientRegister='Hồ sơ cá nhân'; //'Person registration';
$LDRegDate='Ngày đăng ký'; //'Registration date';
$LDRegTime='Giờ đăng ký'; //'Registration time';
$LDRegBy='Người cho đăng ký'; //'Registered by';
$LDName2='Tên thứ hai'; //'Second name';
$LDName3='Tên thứ ba'; //'Third name';
$LDNameMid='Tên đệm'; //'Middle name';
$LDNameMaiden='Tên họ lúc chưa lấy chồng'; //'Maiden name';
$LDNameOthers='Tên khác'; //'Other names';
$LDStreet='Tên đường'; //'Street';
$LDStreetNr='Số nhà'; //'Nr.';
$LDTownCity='Tỉnh/Thành phố'; //'Town/City';
$LDProvState='Đặc khu/Bang'; //'Province/State';
$LDRegion='Miền'; //'Region';
$LDCountry='Quốc gia'; //'Country';
$LDCitizenship='Quốc tịch'; //'Citizenship';
$LDCivilStatus='Tình trạng hôn nhân'; //'Civil status'; /* Civil status = married, single, divorced, widow */
$LDSingle='Độc thân'; //'Single';
$LDMarried='Có gia đình'; //'Married';
$LDDivorced='Ly dị'; //'Divorced';
$LDWidowed='Góa (chồng, vợ)'; //'Widowed';
$LDSeparated='Ly thân'; //'Separated';
$LDCellPhone='Số di động'; //'Cellphone.';
$LDFax='Số fax'; //'Fax';
$LDEmail='Thư điện tử'; //'Email';
$LDZipCode='Mã bưu chính'; //'Zip';
$LDPhoto='Hình cá nhân'; //'Photo';

/* 2002-12-24 EL */
$LDSSSNr='Số an sinh xã hội'; //'SSS Nr.';
$LDNatIdNr='CMND'; //'National ID Nr.';
$LDEthnicOrigin='Dân tộc'; //'Ethnic origin';
$LDOtherNr='Mã số nhận diện khác'; //'Other number(s)';

# 2004-05-22 KB
$LDNr='Mã số'; //'Nr.';
$LDOtherHospitalNr='Mã số cơ sở y tế khác'; //'Other Hospital Nr.';
$LDSelectOtherHospital = 'Chọn cơ sở y tế hoặc bệnh viện'; //'Select other hospital to change the number';
$LDNoNrNoDelete = 'Để trống mã số nếu muốn xóa'; //'no number = delete';

/* Thông tin tiếp nhận khám bệnh và nhập viện */
$LDAdmission='Nhận bệnh'; //'Admission';
$LDAdmitDate='Ngày nhận bệnh'; //'Admission date';
$LDAdmitTime='Giờ nhận'; //'Admission time';
$LDCaseNr='Mã bệnh án'; //'Admission number';
$LDTitle='Danh xưng'; //'Title';
$LDLastName='Họ và tên đệm'; //'Family name';
$LDFirstName='Tên'; //'Given name';
$LDBday='Ngày sinh'; //'Date of birth';
$LDPhone='Điện thoại'; //'Phone';
$LDAdmitBy='Người nhập liệu'; //'Admitted by';
$LDSex='Giới tính'; //'Sex';
$LDMale='Nam'; //'male';
$LDFemale='Nữ'; //'female';
$LDAddress='Địa chỉ'; //'Address';
$LDAmbulant='Ngoại trú'; //'Outpatient';
$LDStationary='Nội trú'; //'Inpatient';
$LDSelfPay='Tự thanh toán'; //'Self pay';
$LDPrivate='Bảo hiểm tư'; //'Private Insurance';
$LDInsurance='Bảo hiểm y tế'; //'Health Fund';
$LDInsuranceDate='Ngày thẻ hết hạn';
$LDDiagnosis='Chẩn đoán'; //'Diagnosis';
$LDRecBy='Nơi chuyển đến(nếu có)'; //'Referred by';
$LDTherapy='Phương pháp điều trị'; //'Therapy';
$LDSpecials='Ghi nhận của nơi chuyển đến '; //'Referrer notes';

/* Hướng dẫn tìm kiếm khi tiếp nhận */

/* 2002-10-13 EL */
$LDPlsSelectPatientFirst='Trước tiên phải tìm bệnh nhân!'; //'Please find the patient first.';

$LDPatientSearch='Tìm bệnh nhân'; //'Search patient\'s data';
$LDAdmit='Nhận bệnh'; //'Admission';
$LDSearch='Tìm đơn giản'; //'Search';
$LDArchive='Kho hồ sơ'; //'Archive';
$LDCatPls='I like to see the cat please!';
$LDGoodMorning='Xin chào';//'Good Morning!';
$LDGoodDay='Xin chào';//'Hi! Nice to see you!';
$LDGoodAfternoon='Xin chào';//'Good afternoon!';
$LDGoodEvening='Xin chào';//'Good Evening';

$LDNewForm='Chọn một đăng ký mới'; //'I need an empty form please.';
$LDAdmWantEntry='Tiếp nhận bệnh nhân'; //'I need to admit a patient';
$LDAdmWantSearch='Tìm bệnh nhân'; //'I am looking for a patient';
$LDAdmWantArchive='Tìm thử trong tủ hồ sơ'; //'I need to research in the archive';

$fieldname=array('Mã bệnh nhân','Họ','Tên','Ngày sinh','Tùy chọn');
$LDEntryPrompt='Nhập từ khóa tìm kiếm như họ, tên'; 
//'Enter the search keyword. For example: lastname, or firstname, or date of birth, etc.';
$LDSEARCH='TÌM'; //'SEARCH';

$LDForceSave='Cứ tiếp tục lưu'; //'Save anyway';
$LDSaveData='Lưu dữ liệu'; //'Save data';
$LDResetData='Dùng lại dữ liệu';  //'Reset data';
$LDReset='Giữ lại'; //'Reset';
$LDSave='Lưu lại'; //'Save';
$LDCancel='Hủy bỏ'; //'Cancel';

$LDCancelClose='Hủy và quay lại trang chủ';//'Cancel and back to start page';
$LDCloseWin='Đóng cửa sổ nhận bệnh';//'Close admission window';
$LDError='Nhập thiếu thông tin trong mục <font color=red>màu đỏ</font>!';//'Information is missing in the input field marked <font color=red>red</font>!';
$LDErrorS='Nhập thiếu các thông tin trong mục <font color=red>màu đỏ</font>!';//'Some information are missing in the input fields marked with <font color=red>red</font>!';

/**************** note the ' ~nr~ ' must not be erased it will be replaced by the script with the number of search results ******/
$LDSearchFound='Đã tìm được <font color=red><b>~nr~</b></font> dữ liệu có liên quan.'; 
//'The search found <font color=red><b>~nr~</b></font> relevant data.';

$LDShowData='Cho xem dữ liệu'; //'Show data';
$LDPatientData='Thông tin tiếp nhận bệnh'; //'Admission Data';
$LDBack2Admit='Quay lại trang nhận bệnh';//'Back to admission';
$LDBack2Search='Trở lại bước tìm kiếm'; //'Back to search';
$LDBack2Archive='Quay lại tủ hồ sơ'; //'Back to archive';

$LDFrom='từ'; //'from';
$LDTo='đến'; //'to';
$LDUpdateData='Cập nhật dữ liệu';//'Update data';
$LDNewArchive='Tìm kiếm mới trong kho lưu trữ';//'New research in archive';
$LDAdmArchive='Nhận bệnh - Lưu trữ';//'Admission - Archive';

/************** note: do not erase the ' ~nr~ ' it will be replaced by the script with a number **************/
$LDFoundData='Đã tìm được ~nr~ dữ liệu có liên quan! <br>Xin chọn đúng dữ liệu.'; 
//'I found ~nr~ relevant data!<br>Please click the right one.';

$LDClk2Show='Nhấp vào đây để xem thông tin'; //'Click to show the data';

$LDElements=array(
								'',
								'Họ và tên đệm', //'Lastname',
								'Tên', //'Firstname',
								'Ngày sinh', //'Date of birth',
								'Mã bệnh nhân', //'Patient nr.',
								'Ngày nhập viện' //'Admission date'
								);

/* Thông tin liên quan đến medoc (xem lại có phải ý nghĩa bệnh án?) 
**
*/								
$LDSearchKeyword='Từ khóa tìm kiếm hoặc tình trạng'; //'Search keyword or condition';
$LDMEDOCS='Hệ thống ghi chép hồ sơ bệnh án'; //'Medical Documentation System (Medocs)';
$LDMedocsSearchTitle='Tìm kiếm hồ sơ Tổng Kết Bệnh Án';//'Medocs - Document search';
$LDHideCat='Click to hide the cat';
$LDNewDocu='Lưu trữ cho các bệnh nhân sau';//'Document the following patient';
$LDExtraInfo='Thông tin khác'; //'Extra information';
$LDMedAdvice='Tư vấn sức khỏe'; //'Medical Advice';
$LDMedocs='Thông tin bệnh án'; //'Medocs';
$LDMedocs1='Tổng kết bệnh án'; //'Medocs';
$LDBilling='Thông tin viện phí'; //'Billing';
$LDSoket='Sơ kết 15 ngày điều trị'; //'Medocs';
$LDThongTinTongKet='Thông tin hồ sơ tổng kết';
$LDYes='Đồng ý';//'Yes';
$LDNo='Không';//'No';
$LDYes1='Có';
$LDEditOn='Lưu trữ vào';//'Documented on';
$LDEditBy='Lưu trữ bởi';//'Documented by';
$LDKeyNr='Mã số';//'Key number';
$LDDocSearch='Tìm bệnh án tóm tắt';//'Search a medocs document';

$LDMedDocOf='Tổng kết bệnh án của';//'Medocs document of';
$LDMedocsElements=array(
								'',
								'Họ và tên đệm', //'Lastname',
								'Tên', //'Firstname',
								'Ngày sinh', //'Date of birth',
								'Mã bệnh nhân', //'Patient Nr.',
								'Mã bệnh án', //'Document Nr.',
								'Khoa', //'Department',
								'Ngày', //'Date',
								'Giờ' //'Time'
								);
$LDStartNewDoc='Lập bệnh án mới'; //'Start a new medocs document';
$LDNoMedocsFound='Không tìm thấy hồ sơ bệnh án nào của bệnh nhân'; //'No medocs document of the patient found!';
$LDAt='tại';//'at';		

		
$LDDept='Khoa'; //'Dept';
$LDRoomNr='Số phòng'; //'Room nr';
$LDWardNr='Thuộc khu'; //'Ward nr'
$LDAdmitType='Loại bệnh nhân'; //'Admission type';		
$LDCivilStat='Tình trạng hôn nhân'; //'Civil status';
$LDInsuranceNr='Số thẻ bảo hiểm'; //'Insurance nr';
$LDNameAddr='Tên và địa chỉ'; //'Name & Address';
$LDBillInfo='Gửi hóa đơn thanh toán cho'; //'Billing info';
$LDAdmitDiagnosis='Chẩn đoán khi tiếp nhận'; //'Admission diagnosis';
$LDInfo2='Gửi thông tin cho'; //'Info to';
$LDPrintDate='Ngày in'; //'Print date';
$LDReligion='Tôn giáo'; //'Religion';
$LDTherapyType='Phương pháp điều trị'; //'Therapy type';
$LDTherapyOpt='Chọn lựa điều trị'; //'Therapy option';
$LDServiceType='Loại dịch vụ'; //'Service type';

$LDClick2Print='Nhấn vào nhãn mã vạch để in';//'Click the barcode labels to print';

$LDEnterDiagnosisNote='Đính kèm liên kết đến các ghi chú chẩn đoán & các công bố liên quan';//'Attach links to diagnosis related notes & publications:';
$LDEnterTherapyNote='Đính kèm liên kết đến các ghi chú điều trị & các công bố liên quan:';//'Attach links to therapy related notes & publications:';
$LDSeeDiagnosisNote='Các ghi chú chẩn đoán & các công bố liên quan';//'Diagnosis related notes & publications:';
$LDSeeTherapyNote='Các ghi chú điều trị & các công bố liên quan:';//'Therapy related notes & publications:';
$LDMakeBarcodeLabels='Tạo nhãn mã vạch';//'Make barcode labels';

$LDPlsEnterDept='<b>Xin nhập Khoa của bạn, phòng khám, hay khu vực làm việc...</b>';//'<b>Please enter your department, clinic, or work area.</b><br>(e.g. PLOP, Internal Med2, or M4A, etc.)';
$LDOkSaveNow='Vâng, lưu lại ngay';//'OK save now';

$LD_ddpMMpyyyy='dd.mm.yyyy';
$LD_yyyyhMMhdd='yyyy-mm-dd';
$LD_MMsddsyyyy='mm/dd/yyyy';


/* 2002-12-02 EL*/
$LDPatientRegisterTxt='Đăng ký hồ sơ cá nhân và tìm kiếm hồ sơ'; //'Register patient, search registrations, archive research';
$LDAdmitNr='Mã bệnh án'; //'Admission Nr.';
$LDPatient='Bệnh nhân'; //'Patient';
$LDVisit='Khám bệnh'; //'Visit';
$LDVisitTxt='Nhận bệnh ngoại trú'; //'Ambulatory or outpatient admission';
$LDAdmissionTxt='Nhận và tìm bệnh nội trú'; //'Inpatient admission, search, research';
$LDImmunization='Tiêm chủng'; //'Immunization';
$LDESE='Ghi nhận, tìm kiếm, sửa đổi'; //'Enter, search, edit';
$LDImmunizationTxt=$LDESE.' báo cáo về tiêm chủng'; //' immunization report';
$LDDRG='DRG (DX)'; //'DRG (composite)';
$LDDRGTxt=$LDESE.' DRG (Các nhóm liên quan đến chẩn đoán)'; //' DRG (Diagnosis related groups)';
$LDProcedures='Phẫu thuật'; //'Procedures';
$LDProceduresTxt=$LDESE.' các ca Phẫu thuật'; //' therapy procedures';
$LDPrescriptions='Toa thuốc ngoại trú'; //'Prescriptions';
$LDPrescriptionsTxt=$LDESE.' toa thuốc'; //' Prescriptions';

/* 2002-12-03 EL*/
$LDDiagXResults='Kết quả cận lâm sàng'; //'Diagnostic Results';
$LDDiagXResultsTxt='Tìm, nghiên cứu, hiện các báo cáo & kết quả lâm sàng';//'Search, research, display diagnostic results or reports';
$LDAppointments='Hẹn khám'; //'Appointments';
$LDAppointmentsTxt=$LDESE.', xem xét các cuộc hẹn hay lịch trình'; //', research appointments or schedules';
$LDPatientDev='Tiến triển';//'Development';
$LDPatientDevTxt=$LDESE.', hiện các báo cáo về sự tiến triển của bệnh nhân'; //', display reports on patient\'s development';
$LDWtHt='Chiều cao & cân nặng';//'Weights & Heights';
$LDWtHtTxt=$LDESE.' cân nặng, chiều cao & chu vi vòng đầu'; //' weight, height & head circumference';
$LDPregnancies='Tình trạng sản phụ sau khi sinh';//'Pregnancies';
$LDPregnanciesTxt=$LDESE.' thông tin về việc mang thai'; //' pregnancy information';
$LDBirthDetails='Đặc điểm trẻ sơ sinh'; //'Birth details';
$LDBirthDetailsTxt=$LDESE.' chi tiết về lần sinh'; //' birth details';

/* 2002-12-07 EL*/
$LDInsuranceCo='Công ty bảo hiểm'; //'Insurance Company';
$LDInsuranceNr_2='Mã số công ty bảo hiểm phụ';//'Extra Insurance Nr.';
$LDInsuranceCo_2='Công ty bảo hiểm phụ';//'Extra Insurance Co.';
$LDBillType='Hình thức thanh toán'; //'Billing Type';
$LDWard='Khu phòng bệnh'; //'Ward/Station';
$LDMakeWristBand='Tạo dây đeo cổ tay';//'Make wristbands';
$LDClickImgToPrint='Nhấn vào hình ảnh để in';//'Click the image to print out.';
$LDPrintPortraitFormat='Thiết lập máy in của bạn sang định dạng chụp cảnh';//'Set your printer to landscape format.';

/* 2002-12-14 EL */
$LDRegistryNr='Mã cá nhân'; //'PID Nr.';
$LDRedirectToRegistry='Lưu ý: Tìm kiếm của bạn sẽ được chuyển đến trang đăng ký!';//'Note: Your search will be redirected to the registration module!';


/* 2002-12-25 EL */
$LDSendBill='Gửi hóa đơn cho'; //'Send bill to';
$LDContactPerson='Người liên hệ'; //'Contact person';
$LDOptsForPerson='Hồ sơ sức khỏe'; //'Options for this person';
$LDSickReport='Xác nhận tình trạng sức khỏe'; //'Confirmation of inability to work';
$LDAnamnesisForm='Mẫu khung tiền sử bệnh'; //'Anamnesis form';
$LDConsentDec='Chấp nhận khai báo'; //'Consent declaration';
$LDUpdate='Cập nhật'; //'Update';

/* 2002-12-29 EL */
$LDGuarantor='Bảo lãnh';//'Guarantor';
$LDCareServiceClass='Nhóm dịch vụ chăm sóc';//'Care service class';
$LDRoomServiceClass='Nhóm dịch vụ phòng';//'Room service class';
$LDAttDrServiceClass='Nhóm dịch vụ y tế';//'Medical service class';
$LDAdmitClass='Xét nhận bệnh'; //'Admission class';

/* 2003-02-15 EL*/
$LDEnterSearchKeyword='Nhập từ khóa tìm kiếm'; //'Please enter search keyword';
$LDSearchFoundData='Đã tìm được <font color=red><b>~nr~</b></font> dữ liệu có liên quan.';
$LDQuickList='Liệt kê nhanh';//'Quicklist';
$LDSeveralInsurances='Bệnh nhân có nhiều loại bảo hiểm. Nhấn vào đây để chỉnh sửa.';//'Patient has several insurances. Click here to edit.';
$LDTop='Trên cùng';//'Top';
$LDInsuranceClass='Loại hình bảo hiểm'; //'Insurance class';
$LDRecordsHistory='Nhật ký truy xuất thông tin'; //'DB Record\'s History';

/* 2003-02-16 EL*/
$LDNotYetAdmitted='Chưa chính thức tiếp nhận'; //'Not yet admitted';
$LDPatientCurrentlyAdmitted='Bệnh nhân đã chính thức được tiếp nhận'; //'Patient is currently admitted!';
$LDOptions='Chọn'; //'Options';

/** note the ' ~nr~ ' must not be erased it will be replaced by the script with the number of search results ******/
$LDSearchFoundAdmit='Đã tìm được <font color=red><b>~nr~</b></font> dữ liệu nhập viện có liên quan.'; //'I found <font color=red><b>~nr~</b></font> relevant admission data.';
$LDPatientNr='Mã bệnh nhân'; //'Patient Nr.';
$LDNoRecordYet='Không có thông tin nào!'; //'~tag~ chưa có ~obj~ nào.';
$LDNoRecordFor='Chưa có thông tin gì!'; //'No ~obj~ record for ~tag~ yet.';
$LDRegistrationNr='Mã hồ sơ cá nhân'; //'Registration Nr.';
$LDDate='Ngày'; //'Date';
$LDDate1='Ngày giờ';
$LDType='Dạng'; //'Type';
$LDMedicine='Thuốc'; //'Medicine';
$LDTiter='Độ chuẩn';//'Titer';
$LDRefreshDate='Ngày làm lại';//'Refresh date';
$LDReportingDept='Báo cáo chuyên sâu';//'Reporting Dept';
$LDReportNr='Mã báo cáo';//'Report Nr.';
$LDDelivery='Đỡ đẻ';//'Delivery';
$LDTime='Thời gian';//'Time';
$LDClass='Nhóm';//'Class';
$LDOutcome='Kết quả';//'Outcome';
$LDNrOfFetus='Mã số thai nhi';//'Nr. of Fetuses';
$LDDetails='Chi tiết'; //'Details';
/* 2003-03-02 */
$LDDosage='Liều dùng'; //'Dosage';
$LDAppType='Hình thức';//'Application type';
$LDAppBy='Tiêm bởi';//'Application by';
$LDNotes='Ghi chép'; //'Notes';
$LDEnterNewRecord='Ghi nhận thông tin'; //'Enter new record';
$LDPrescription='Toa thuốc'; //'Prescription';
$LDDrugClass='Loại thuốc'; //'Drug Class';
$LDPrescribedBy='Bác sỹ kê toa'; //'Prescribed by';
$LDPharmOrderNr='Mã Phiếu yêu cầu thuốc'; //'Pharmacy Order Number';
$LDEncounterNr='Mã lần khám'; //'Encounter Nr.';
$LDValue='Giá trị'; //'Value';
$LDUnit='Đơn vị'; //'Unit';
$LDWeight='Cân nặng'; //'Weight';
$LDHeight='Chiều cao'; //'Height';
$LDMeasuredBy='Người đo'; //'Measured by';
$LDSickUntil='Unable to work until (inclusive)';
$LDStartingFrom='Bắt đầu từ';//'Starting from';
$LDConfirmedOn='Xác nhận vào ngày'; //'Confirmed on';
$LDInsurersCopy='Bản sao của công ty bảo hiểm';//'Insurer\'s copy';
$LDDiagnosis2='Chẩn đoán'; //'Diagnosis';
/* 2003-03-03*/
$LDBy='Bác sỹ'; //'By';
$LDSendCopyTo='Gửi một bản cho'; //'Send copy to';

/* 2003-03-05 EL*/
$LDAndSym='&';
$LDReports='Báo cáo lâm sàng'; //'Reports';
$LDRefererDiagnosis='Chẩn đoán của bác sỹ gửi bệnh'; //'Referer Diagnosis';
$LDRefererRecomTherapy='Phương pháp điều trị của nơi gửi bệnh'; // 'Referer recommended therapy';
$LDShortNotes='Ghi tóm tắt'; //'Short Notes';

/* 2003-03-08 EL */
$LDCreateNewAppointment='Lấy hẹn'; //'Create new appointment';
$LDDepartment='Khoa'; //'Department';
$LDRemindPatient='Báo nhắc bệnh nhân'; //'Remind patient';
$LDRemindBy='Người báo'; //'Remind by';
$LDMail='Thư'; //'Mail';
$LDPurpose='Mục đích';//'Purpose';
$LDClinician='Bác sỹ lâm sàng';//'Clinician';
$LDPhysician='Bác sỹ điều trị';//'Physician';
$LDBackToOptions='Quay lại mục Hồ sơ sức khỏe';
$LDStatus='Tình trạng'; //'Status';

/* 2003-03-08 EL*/
$LDUrgency='Khẩn'; //'Urgency';
$LDNormal='Thường'; //'Normal';
$LDPriority='Ưu tiên'; //'Priority';
$LDUrgent='Khẩn'; //'Urgent';
$LDEmergency='Cấp cứu'; //'Emergency';

/* 2003-03-09 EL*/
$LDCancelReason='Lý do hoãn cuộc hẹn'; //'Reason for cancellation';
$LDSureCancelAppt='Thật sự muốn hoãn cuộc hẹn?'; //'Are you sure you want to cancel this appointment?';
$LDEnterCancelReason='Xin cho biết lý do hoãn cuộc hẹn'; //'Enter the reason for cancellation';
$LDpending='Đang chờ'; //'pending';
$LDcancelled='Đã hủy'; //'cancelled';

/* 2003-03-10 EL */
$LDGotMedAdvice='Bệnh nhân đã được tư vấn sức khỏe?'; //'Did patient receive medical advice?';

/* 2003-03-15 EL */
$LDShowDocList='Xem danh sách lần khám'; //'Show document list';
$LDScheduleNewAppointment='Xếp lịch hẹn'; //'Schedule New Appointment';

/* 2003-04-04 EL */
$LDNoPendingApptThisDay='Hôm đó không có cuộc hẹn nào'; //'There is no pending appointment for this day.';
$LDNoPendingApptToday='Hôm nay không có cuộc hẹn nào'; //'There is no pending appointment today.';

/* 2003-04-27 EL */
$LDOptsForPatient='Thông tin hồ sơ bệnh án'; //'Options for this patient';

/* 2003-05-06 EL */
$LDRegisterNewPerson='Đăng ký hồ sơ cá nhân'; //'Register a new person';

/* 2003-05-17 EL */
$LDEnterPersonSearchKey='Nhập từ khóa tìm kiếm như mã hồ sơ cá nhân, họ, tên, ngày sinh'; 
//'Enter search keyword: e.g. PID, first name, family name, or birth date';
$LDPersonData='Thông tin cá nhân'; //'Personal data';

/* 2003-05-26 EL*/
$LDDiagnoses='Chẩn đoán'; //'Diagnoses';
$LDCreateNewForm='Tạo mẫu đơn cho';//'Create a form for';
$LDOtherRecords='Các mục ghi khác';//'Other records';

/*2003-06-17 El*/
$LDFullForm='Mẫu đơn đầy đủ';//'Full form';
$LDAllContents='Tất cả nội dung';//'All contents';
$LDAllText='Chỉ phần nội dung không cố định';//'Dynamic contents only';
$LDDataOnly='Chỉ phần dữ liệu liên quan đến bệnh nhân';//'Encounter relevant data only';

/*2003-06-21 EL*/
$LDChartsRecords='Phiếu theo dõi lâm sàng'; //'Charts folder';

# 2003-07-26 EL
$LDMode='Mode';
$LDPatientIsDischarged='Bệnh nhân đã xuất/chuyển viện'; //'This patient is already discharged';
$LDShow='Xem';//'Show';
$LDPlannedEncType='Hình thức khám chữa bệnh'; //'Planned admission type';

# 2003-08-01 EL
$LDListEncounters='Danh sách các lần khám'; //'Encounters\' list';
$LDDischarged='Đã xuất/chuyển viện'; //'Discharged';
$LDDischargeDate='Ngày xuất/chuyển viện'; //'Discharge date';

# 2003-08-04 EL
$LDCancelThisAdmission='Hủy nhận bệnh';//'Cancel this admission';
$LDInsShortID[1]='  Bảo Hiểm Y Tế';  
$LDInsShortID[2]='  Thu Phí'; 
$LDInsShortID[3]='  Chữa bệnh miễn phí'; 
$LDInsShortID[4]='  Hình thức khác';
# 2003-08-26 EL
$LDMeasurements='Chiều cao-cân nặng'; //'Measurements';

#2003-08-28 eL
$LDPlsEnterReferer='Xin nhập tên bác sỹ gửi bệnh'; //'Please enter refering physician';
$LDPlsEnterRefererDiagnosis='Xin nhập chẩn đoán của bác sỹ'; //'Please enter referal diagnosis';
//$LDPlsXetnhanbenh ='Xin chọn nội trú hoặc nội trú';
$LDPlsSelectAdmissionType ='Xin chọn nội trú hoặc ngoại trú';
$LDPlsEnterRefererTherapy='Xin nhập phương pháp điều trị của bác sỹ'; //'Please enter referer\'s recommended therapy';
$LDPlsEnterRefererNotes='Xin nhập phần ghi nhận của bác sỹ'; //'Please enter referer\'s notes';
$LDPlsEnterTotalDay = 'Xin nhập tổng ngày sử dụng thuốc của toa';
$LDPlsEnterMedicine='Xin kê thuốc trong toa'; //'Please select admission type';
$LDForInpatient='Đối với bệnh nhân nội trú'; // 'For inpatient';
$LDForOutpatient='Đối với bệnh nhân ngoại trú'; //'For outpatient';
#2003-09-18 EL
$LDPersonSearch='Tìm cá nhân'; //'Search a person';
#2003-09-24 EL
$LDShowing='Xem'; //'Showing';
$LDPrevious='Trước'; //'Previous';
$LDNext='Tiếp theo'; //'Next';
$LDAdvancedSearch='Tìm mở rộng'; //'Advanced search';

#2003-10-28 EL
$LDIncludeFirstName='Tìm cả tên và họ'; //'Search for first names too.';
$LDTipsTricks='Hướng dẫn và Mẹo tìm'; //'Tips & tricks';
#2003-12-06 EL
$LDPrintPDFDoc='Xuất in ra tập tin dạng PDF'; //'Make PDF document';
$LDDeathDate='Ngày tử vong'; //'Death date';

# 2003-10-14 NDHC
$LDITA='Gây mê nội khí quản'; //'Intratracheal anesthesia';
$LDLA='Gây tê tại chỗ'; //'Local anesthesia';
$LDAS='An thần'; //'Analgesic sedation';
$LDOral='Uống'; //'Oral';
$LDAnticoagulant='Chống đông'; //'Anticoagulant';
$LDHemolytic='Tán huyết'; //'Hemolytic';
$LDDiuretic='Lợi tiểu'; //'Diuretic';
$LDAntibiotic='Kháng sinh'; //'Antibiotic';
$LDMask='Mặt nạ'; //'Mask';
$LDIntravenous='Tiêm tĩnh mạch'; //'Intravenous';
$LDSubcutaneous='Tiêm dưới da'; //'Subcutaneous';
$LDPreAdmission='Trước khi nhập viện';//'Pre-admission';
$LDTransIntravenous='Truyền tĩnh mạch';
$LDIntramuscular='Tiêm bắp';
$LDIntraArterial='Tiêm động mạch';
$LDInjectedInSkin='Tiêm trong da';
$LDSublingual='Ngậm';

#2004-01-01 EL
$LDPersonDuplicate='Cá nhân này rất có thể đã đăng ký rồi.'; //'This person seems to be registered already.';
$LDSimilarData='Cá nhân được liệt kê dưới đây có thông tin cá nhân tương tự'; //'The following listed person has similar personal data.';
$LDSimilarData2='Những cá nhân được liệt kê dưới đây có thông tin cá nhân tương tự'; //'The following listed persons have similar personal data.';
$LDPlsCheckFirst='Xin kiểm tra lại trước khi tiếp tục!'; //'Please check it out first before you decide the next step.';
$LDPlsCheckFirst2='Xin kiểm tra lại trước khi tiếp tục!'; //'Please check them out first before you decide the next step.';
$LDShowDetails='Xem thông tin chi tiết'; //'Show details';


$LDNoAddress='Chưa có nhập thành phố nào. Xin đến menu chính <br>chọn Công cụ -> Quản lý địa chỉ -> Thông tin mới và <br>nhập Thành Phố và Mã vùng<br>.';
//'No City defined yet. Please go to the main menu and<br>click Special Tools -> Address Manager -> New data and <br>enter City and ZIP data<br>.';

# 2010-09-16
$LDAdmitShowTypeInput ='Mã phân nhóm (Độ ưu tiên)';//'Triage Code';

# 2010-09-18 GJ/APM
$sAdmitTriageWhite = 'Trắng';//'White';
$sAdmitTriageGreen = 'Xanh lá';//'Green';
$sAdmitTriageYellow = 'Vàng';//'Yellow';
$sAdmitTriageRed = 'Đỏ';//'Red';
$sAdmitTriageBlue = 'Xanh dương';//'Blue'; // Hope to use later

$LDDoctor = 'Bác sỹ';//'Doctor';
$LDPharmacist = 'Dược sỹ';//'Pharmacist';
$LDBed = 'Giường';//'$LDBed';
$LDAllergy = 'Dị ứng';//'Allergy';
$LDHour = 'Giờ';//'Hour';
$LDQty = 'Chất lượng';//'Qty';
$LDSpeed = 'Tốc độ';//'Speed';
$LDDose = 'Liều lượng';//'Dose';
$LDApplicationType = 'Loại áp dụng';//'Type';
$LDDrug = 'Thuốc';//'Drug';
$LDPrice = 'Giá tiền';//'Price';
$LDValue = 'Giá trị';//'Value';

# 2011-10-17 Tuyen
$LDPrescriptionId='Mã toa thuốc'; //'Prescription';
$LDSymptoms='Triệu chứng';
$LDPaid = 'Thanh toán';
$LDGotDrug = 'Nhận thuốc';
$LDCreatePres='Tạo toa thuốc mới';
$LDFinish='xong';
$LDNotYet='chưa';
$LDNote = 'Ghi chú';
$LDTotalEstimate = 'Tổng tiền dự tính';
$LDTotalDay = 'Tổng ngày dùng';
$LDMedicineName = 'Tên thuốc';
$LDNumberOf = 'Số lượng';
$LDCost = 'Đơn giá';
$LDTotalCost = 'Thành tiền';
$LDUseTimes = 'lần';
$LDEachTime = 'Mỗi lần';
$LDMedicineUse = 'uống';
$LDMedicineUnit = 'viên';
$LDCannotEdit = 'Toa thuốc đã thanh toán, không thể chỉnh sửa. ';
$LDDbNoSave = 'Không lưu được!';
$LDWouldDeletePres = 'Có thật sự muốn xóa toa thuốc này?';
$LDLoiDan='Lời dặn chung';
$LDDauHieuSinhTon='Dấu hiệu sinh tồn';
$LDCanLamSang='Cận lâm sàng';


/* CoT */
$LDnghenghiep = 'Nghề nghiệp';
$LDnoilamviec = 'Nơi làm việc';
$LDdtbaotin = 'Điện Thoại Báo tin';
$LDhotenbaotin = 'Họ tên người báo tin';
$LDngoaikieu = 'Ngoai kieu';
$LDHuyenxa = 'Quận/Huyện';

$LDCanbotrungcao = 'CB Trung cao';
$LDNguoigia = 'Người già';
$LDTresosinh = 'Trẻ dưới 1 tuổi';
$LDNguoitantat = 'Người tàn tật';
$LDPhunumangthai = 'Phụ Nữ Mang Thai';
$LDNguoibt = 'Bình thường';

$LDTuoi= 'Tuổi';

$LDbreathing = 'Nhịp thở';
$LDSystolic = 'Huyết áp';
$LDDiastolic = 'Mạch';
$LDbreathing = 'Nhịp thở';
$LDnpm = 'Lần/phút';
$LDTemperature = 'Nhiệt độ';
$LDCelsius = 'Độ';

# 2011-10-19 vy
$LDBenhAnNgoaiTru = 'Bệnh Án Ngoại Trú';
$LDKhamChuyenKhoa = 'Phiếu Khám Chuyên Khoa';
$LDPhieuDienTim= 'Phiếu Điện Tim';
$LDTamUngVaoVien= 'Phiếu Tạm Ứng Vào Viên';
$LDOng='Ông';
$LDBa='Bà';
$LDChungNhanThuongTich= 'Chứng nhận thương tích';
$LDHoaSinhMau='Phiếu xét nghiệm hóa sinh máu';
$LDPhieuXquang='Phiếu chiếu chụp Xquang';
$LDKhamBenhVaoVien='Phiếu Khám Bệnh vào viện';
$LDNatIDDate='Ngày cấp';
$LDNatIDAddr='Nơi cấp';
$LDJob='Nghề nghiệp';
$LDJobAddr='Nơi làm việc';
$LDTtVaoVien='Tình trạng thương tích vào viện';
$LDTtRaVien='Tình trạng thương tích ra viện';
$LDTtHienTai='Tình trạng thương tích hiện tại';
$LDNgoaiKieu='Ngoại kiều';
$LDChuyenVienBHYT='Giấy Chuyển viện BHYT';

$LDLidovaovien="Lí do vào viện";
$LDPlsLidovaovien="Xin nhập lí do vao viện;";
$LDQuatrinhbenhly='Quá trình bệnh lý';
$LDInsuranceStart='Ngày cấp';
$LDInsuranceExp='Ngày hết hạn';
$LDNoicap='Nơi cấp thẻ';
$LDTSBenhCN='Tiền sử bệnh cá nhân';
$LDTSBenhGD='Tiền sử bệnh gia đình';
$LDIcd10='Icd10';
$LDThonPhuong='Xã/Phường';
$LDKhambenhtt='Khám bệnh toàn thân';
$LDKhambenhbp='Khám bệnh bộ phận';
$LDKetqualamsang='Kết quả cận lâm sàng';
$LDChandoanbenhchinh='Chẩn đoán bệnh chính';
$LDChandoanbenhphu='Chẩn đoán bệnh phụ';
$LDTinhtrangravien='Tình trạng ra viện';
$LDHuongdieutri='Hướng điều trị tiếp theo';
$LDThuongtichvao='Tình trạng thương tích vào viện';
$LDThuongtichra='Tình trạng thương tích ra viện';


# 2011-10-17 Tuyen
$LDPresEncoder='Mã';
$LDPrescriptionId='Mã toa thuốc'; //'Prescription';
$LDSymptoms='Triệu chứng';
$LDPaid = 'Thanh toán';
$LDGotDrug = 'Nhận thuốc';
$LDCreateChemical='Tạo toa HC mới';
$LDFinish='xong';
$LDNotYet='chưa';
$LDNote = 'Ghi chú';
$LDTotal = 'Tổng tiền thuốc';
$LDTotalDay = 'Tổng ngày dùng';
$LDMedipotName = 'Tên VTYT';
$LDChemicalName = 'Tên HC';
$LDNumberOf = 'Số lượng';
$LDCost = 'Đơn giá';
$LDTotalCost = 'Thành tiền';
$LDUseTimes = 'lần';
$LDEachTime = 'Mỗi lần';
$LDMedicineUse = 'uống';
$LDMedicineUnit = 'viên';
$LDBenhNhanDuocNghiPhep ='Bệnh nhân được nghỉ phép';
$LDTaiKham=' Tái khám sau';
$LDCannotEdit = 'Toa thuốc đã thanh toán, không thể chỉnh sửa. ';
$LDCannotEditFinish = 'Thuốc trong toa đã nhận, không thể chỉnh sửa. ';
$LDCannotEditIssuePaper='Không thể chỉnh sửa, vì toa thuốc này đã gộp vào trong Phiếu Lĩnh Thuốc số ';
$LDDbNoSave = 'Không lưu được!';
$LDWouldDeletePres = 'Có thật sự muốn xóa toa thuốc này?';
$LDAtTime='Vào lúc';
$LDLyDoVaoVien='Lý do vào viện';
$LDKetQuaGPB='Kết quả giải phẩu bệnh';
$LDChanDoanVaoVien='Chẩn đoán vào viện';
$LDPhapTri='Pháp trị';
$LDThoiGianDieuTri='Thời gian điều trị';
$LDKetQuaDieuTri='Kết quả điều trị';
$LDQuaTrinhBenhLyVaDBLS='Quá trình bệnh lý và diễn biến lâm sàng';
$LDTomTatKQXN='Tóm tắt kết quả xét nghiệm CLS có giá trị chẩn đoán';
$LDTtNguoiBenhRaVien='Tình trạng người bệnh ra viện';
$LDHuongDieuTriTT='Hướng điều trị và các chế độ tiếp theo';
$LDChanDoanRaVien='Chẩn đoán ra viện';
$LDtextChanDoanRaVien="- Bệnh chính:\n- Bệnh kèm theo (nếu có):";
$LDtextYHCT1="-YHHĐ:\n-YHCT:";
$LDtextYHCT2="Tổng số ... ngày. Từ ngày ... đến ngày ... ";
$LDtextYHCT3="1. Khỏi  2. Đỡ  3. Không thay đổi  4. Nặng hơn  5. Chết";
$LDUpdateRecord='Cập nhật thông tin';
$LDPhauThuat='Phẫu thuật';
$LDThuThuat='Thủ thuật';
$LDNgayGio='Giờ, Ngày';
$LDPhauThuatVoCam='Phương pháp phẫu thuật/ vô cảm';
$LDBacSyPT='Bác sỹ phẫu thuật';
$LDBacSyGM='Bác sỹ gây mê';
$LDMota='Mô tả';
$LDBack2TongKet='Quay lại mục tổng kết';
$LDPlsEnterQuaTrinhBenhLy='Nhập quá trình bệnh lý và diễn biến lâm sàng!';
$LDPlsEnterTherapy='Nhập phương pháp điều trị!';
$LDPlsEnterDate='Nhập ngày tháng!';
$LDPlsEnterTinhTrangRaVien='Nhập tình trạng ra viện!';
$LDPlsEnterHuongDieuTri='Nhập Hướng điều trị tiếp theo!';
$LDHoSo='Hồ sơ';
$LDBenhAnNoiTru='Bệnh án nội trú, nhi khoa';
$LDBenhAnNgoaiTru='Bệnh án ngoại trú';
$LDBenhAnKhac='Bệnh án Tai-Mũi-Họng, Khoa Ngoại, Phụ Khoa...';
$LDBenhAnYHCT='Bệnh án YHCT';
$LDDienBienLamSang='Diễn biến lâm sàng trong đợt điều trị';
$LDXetNghiemCLS='Xét nghiệm cận lâm sàng';
$LDQuaTrinhDieuTri='Quá trình điều trị';
$LDDanhGiaKQ='Đánh giá kết quả';
$LDHuongDieuTri='Hướng điều trị tiếp và tiên lượng';
$LDDanhSachSoKet='Xem danh sách các lần sơ kết';
$LDCapNhatSoKet='Cập nhật';
$LDBenhPhu='Bệnh phụ';

///////////////////// add-Huỳnh /////////////////
$LDNotes1='Tóm tắt diễn biến bệnh,quá trình điều trị và chăm sóc người bệnh';
$LDShortNotes1='Kết luận';
$LDNextTreatment='Hướng điều trị tiếp';
$LDStreet='Đường';
$LDCity="Tỉnh/Thành phố";
$LDNO1 = 'Chưa biết';
$LDBy1='Thư kí';
$LDBy2='Chủ Tọa';
$LDMember='Thành viên tham gia';
$LDRequestOP='Yêu cầu Phẫu thuật';
$LDRequestLAB='Yêu cầu xét nghiệm';
$LDTestMedLab='Xét nghiệm hóa sinh';
$LDTestPathLab='Xét nghiệm giải phẫu bệnh';
$LDTestBacLab='Xét nghiệm vi trùng học';
$LDTestBloodBank='Xét nghiệm liên quan đến máu';
$LDTestDienTim='Điện tim';
$LDTestChuyenKhoa='Khám chuyên khoa';
$LDTestChanDoanHinhAnh='Chẩn đoán hình ảnh-Xquang';
$LDNameFunction='Chức vụ';
$LDEntryPrompt1='Nhập từ khóa tìm kiếm như <font color="darkred"><b>họ, tên, mã nhân viên</b></font>';
////////////////////////////////////////////////

#add by vy
$LDRequestXquang='Yêu cầu chẩn đoán hình ảnh';
$LDLanKhamLanDay='Lần khám gần đây';
$LDNgayNhapVien='Ngày nhập viện';
$LDTileuudai='Tỉ lệ ưu đãi';
$LDEncounterReferral='Referral';
$LDEmergency='Khẩn cấp';
$LDBirthDelivery='Sinh con';
$LDWalkIn='Walk in';
$LDAccident='Tai nạn';
$LDMaDKKCB='Mã ĐKKCB Ban đầu';
$LDTinhtrang='Tình Trạng';
$LDDateJoinNow='Ngày bắt đầu';
//--Tuyen
$LDListPres='Danh sách toa thuốc';
$LDSheetTreatment='Y lệnh / Tờ điều trị';
$LDDepot='Toa VTYT';
$LDChemical='Toa hóa chất';
$LDHealthStatus='Diễn biến bệnh';
$LDTreatment='Y lệnh';
$LDAddTreatment='Thêm Y lệnh';
$LDAddOldPresTreatment='Kê đơn thuốc dựa vào toa trước';
$LDAddNewPresTreatment='Kê đơn thuốc mới';
$LDPrescriptionMedipotId='Mã toa VTYT';
$LDPrescriptionChemicalId='Mã toa HC';
$LDListDepot='Danh sách toa VTYT';
$LDListChemical='Danh sách toa Hóa Chất';
$LDCreateDepot='Tạo toa VTYT mới';
$LDCaution='Chú ý';
$LDAddRowMedicine='Thêm thuốc';
$LDAddRowMedipot='Thêm VTYT';
$LDAddRowChemical='Thêm HC';
$LDInventory='Tồn kho lẻ';
$LDInventoryVTYT='Tồn kho lẻ';
$LDMedicineID='Mã thuốc';
$LDGotVTYT='Nhận VTYT';
$LDGotHC='Nhận HC';
$LDCannotEditSheet='Chỉ có thể thay đổi y lệnh trong ngày';
$LDNoteMedicinePres='<u>Lưu ý:</u><br>- Giá thuốc trên sẽ được cập nhật tại thời điểm thanh toán.<br>- Toa thuốc sẽ bị hủy nếu sau 36h không thanh toán hoặc nhận thuốc.<br>- Khi tái khám nhớ đem theo toa thuốc này hay sổ khám bệnh.';
$LDNoteMedipotPres='<u>Lưu ý:</u><br>- Giá trên sẽ được cập nhật tại thời điểm thanh toán.<br>- Toa này sẽ bị hủy nếu sau 36h không thanh toán hoặc nhận.';
$LDNoteIssue='Ghi chú người phát';
$LDIssueUser='Người phát';
$LDReceiveUser='Người nhận';
$LDTotal='Tổng tiền';
$LDID = "MSBS";
$LDPersonalPhysicalEval='Thang đo sức khỏe tiêu chuẩn';
$LDWaist='Vòng eo';
$LDPulse='Nhịp tim';
$LDDiastolic='Mạch';
$LDSystolic1='tâm thu';
$LDNoteLogarit='Nếu chỉ điền 1 đơn vị khối lượng (Kg hoặc Lb) và chiều cao (cm hoặc feet) <br> Chương trình sẽ tự động tính đơn vị còn lại';
$LDNoteLogarit1='Đây là thông tin tham khảo, bạn có thể đến các cơ sở y tế để kiểm tra';
//Vaccine
$LDSexOfChild='Giới tính của trẻ';
$LDBirthdayOfChild='Ngày sinh của trẻ';
$LDImmunisationSchedule='Lịch tiêm phòng';
$LDRecommendImmunisationSchedule='Lịch tiêm phòng được yêu cầu như sau: '; //The recommended immunisation schedule of your child is as follows:
$LDChild='Trẻ';
$LDChildIs='được';
$LDyear1='Năm';
$LDmonth1='tháng';
$LDday1='ngày';
$LDyearsold='tuổi';
$LDand='và';
$LDVaccineName='Tên vắc-xin';
$FirstDose='Lần 1';
$SecondDose='Lần 2';
$ThirdDose='Lần 3';
$FourthDose='Lần 4';
$FifthDose='Lần 5';
$BoosterDoses='Các đợt tiêm sắp tới';
$DueDay='Vào ngày';
$AndDay='Và ngày';
$LDOnceEvery10YearsAfter='10 năm 1 lần từ ngày';
$LDOnceEvery3YearsAfter='3 năm 1 lần từ ngày';
$ForgirlsAbove12yrs='Cho bé gái trên 12 tuổi';
$NoteVaccine='Nếu bạn đã bỏ lỡ lần tiêm nào trong số này, vui lòng tham khảo ý kiến bác sỹ khoa nhi nhanh chóng';
$NoteVaccine1='Lưu ý: quyết định của bác sỹ quan trọng hơn';
$NoteVaccine2='Một số vắc-xin còn gây tranh cãi, không áp dụng cho tất cả đối tượng';
$NoteVaccine3='(Ngày màu <font color=#FF0033>đỏ</font> là ngày đã trôi qua)';
$LDCalcutalte='Tính toán';


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
$LDInsuranceType='Loại bảo hiểm';
$LDReferrDoc='BS nhận bệnh';
//vy
$LDToanthan='Toàn thân';
$LDCaccoquan='2.Các cơ quan';
$LDTuanhoan='Tuần hoàn';
$LDHohap='Hô hấp';
$LDTieuhoa='Tiêu hóa';
$LDThantietnieusinhduc='Thận - Tiết niệu -Sinh dục';
$LDThankinh='Thần kinh';
$LDCoxuongkhop='Cơ - Xương - Khớp';
$LDTaimuihong='Tai - Mũi - Họng';
$LDRanghammat='Răng - Hàm - Mặt';
$LDMat='Mắt';
$LDKhac='Nội tiết, dinh dưỡng và các bệnh lý khác';

    //Huynh-khoa sản
    $baby='bé sơ sinh thứ';
    $LDPlsEnterDeliveryDate='Bạn chưa nhập Ngày';
    $LDPlsEnterDeliveryTime='Bạn chưa nhập Thời gian';
    $LDPlsEnterPara='Bạn chưa nhập Mã cuộc sinh nở này';
    $LDEntryNrInvalidChar='Lần mang thai nhập vào không hợp lệ';
    $LDEntryNrInvalidChar1='Mã cuộc sinh nở không hợp lệ';
    $LDEntryInvalidChar='Số lượng thai nhi nhập vào không hợp lệ';
    $LDEntryInvalidChar1='Giá trị Tâm thu cao nhập vào không hợp lệ';
    $LDEntryInvalidChar2='Giá trị Tâm trương cao nhập vào không hợp lệ';
    $LDEntryInvalidChar3='Giá trị Mất máu không hợp lệ';
    $LDNotNegValue='Số lượng thai nhi không được âm';
    $LDNotNegValue1='Giá trị Tâm thu cao không được âm';
    $LDNotNegValue2='Giá trị Tâm trương cao không được âm';
    $LDNotNegValue2='Giá trị Mất máu không được âm';
    $LDPlsEnterFullName='Vui lòng nhập chính xác tên người ghi nhận hồ sơ ca sinh này';
    $LDPlsEnterDeliveryPlace='Bạn chưa nhập nơi sinh';
    $LDEntryInvalidChar4='Giá trị cân nặng của bé không hợp lệ';
    $LDEntryInvalidChar5='Giá trị chiều cao của bé không hợp lệ';
    $LDEntryInvalidChar6='Giá trị chu vi vòng đầu của bé không hợp lệ';
    $LDEntryInvalidChar7='Số lượng thai nhi nhập vào không hợp lệ';
    $LDNotNegValue='Giá trị cân nặng không được âm';
    $LDNotNegValue1='Giá trị chiều cao không được âm';
    $LDNotNegValue2='Giá trị chu vi vòng đầu không được âm';
    $LDWarningTime='Thời gian bạn nhập không hợp lệ';
    $LDWarningHour='Giờ bạn nhập không hợp lệ';
    $LDWarningMinute='Phút bạn nhập không hợp lệ';
    $LDWarningSecond='Giây bạn nhập không hợp lệ';
    $question='Hỏi bệnh';
    $question_Obstetrics='Hỏi bệnh phụ sản';
    $list_sick='Quá trình kì thai này';
    $history_Obstetrics='Tiền sử sản khoa';
    $history_Obstetrics_Gynecology='Tiền sử sản phụ khoa';
    $history_Gynecology='Tiền sử phụ khoa';
    $history_all='Tiền sử phụ khoa';
    $history_sick='Tiền sử bệnh';
    $note='những bệnh đã mắc, dị ứng, thói quen ăn uống, sinh hoạt khác ...';
    $LDPlsEnterTuoithai='Giá trị tuổi thai không hợp lệ';
    $LDEnterdauhieulucdau='Bạn chưa nhập dấu hiệu lúc đầu';
    $LDEnterNgaychuyenda='Bạn chưa nhập giá trị lúc đầu';
    $LDEnterbatdauthaykinh='Nội dung bắt đầu thấy kinh không hợp lệ';
    $LDEnterchuki='Chu kỳ không hợp lệ';
    $LDEnternamlaychong='Năm lấy chồng không hợp lệ';
    $LDEntertuoilaychong='Tuổi lấy chồng không hợp lệ';
    $LDEnternammangthai='Năm mang thai lần thứ ';
    $LDEntercannang='Giá trị Cân nặng lần thứ';
    $LDWarning='không hợp lệ';
    $SorauInfo='Đặc điểm sổ rau';
    $LDNrMuiKhau_NotNegValue='Số mũi khâu không được âm';
    $LDWarningNrMuiKhau='Số mũi khâu không hợp lệ';
    $LDCond_number='là số nguyên dương';
    $LDWarningCheckbox='Tầng sinh môn chỉ được chọn 1 trường hợp trong 3 trường hợp đã nêu';
    $LDWarningCheckbox1='cổ tử cung chỉ được chọn 1 trường hợp trong 2 trường hợp đã nêu';
    $LDNrBloodlossNotNegValue='Lượng mất máu không được âm';
    $LDWarningNrBloodloss='Lượng mất máu không hợp lệ';
    $LDWarningCannang='Cân nặng không hợp lệ';
    $LDWarningCannang1='Bạn chưa nhập giá trị Cân nặng';
    $LDWarningcrdai='Chiều dài cuốn rau không hợp lệ';
    $LDWarningcrdai1='Bạn chưa nhập Chiều dài cuốn rau';
    $LDWarningRau='Bạn chưa chọn Rau như thế nào?';
    $LDWarningCachsorau='Bạn chưa cho biết Cách sổ rau là gì?';
    $LDWarningMatmang='Bạn chưa nhập Mặt màng';
    $LDWarningMatmui='Bạn chưa nhập Mặt múi';
    $LDKhamtoanthan='Khám toàn thân';
    $LDToantrang='Toàn trạng';
    $LDTietnieu='Tiết niệu';
    $LDBPkhac='Các bộ phận khác';
    $LDShowDocList1='Khám bệnh Nội/Ngoại khoa'; //'Show document list';
    $LDShowDocList2='Khám bệnh Phụ sản';
	$LDShowDocListYHCT='Khám bệnh YHCT';
    $LDShowDocList3='Hỏi bệnh Phụ khoa';
    $LDShowDocList4='Hỏi bệnh Sản khoa';
    $LDIn='Khám trong';
    $LDBeside='Khám ngoài';
    $LDKhamKhac='Khám các phần khác';
    $LDKhambenh='Khám bệnh';
    $LDPregQuestion='Hỏi bệnh phụ sản';
    $LDKhamPhu='Khám phụ khoa';
    $LDKhamSan='Khám sản khoa';
    $LDChuyenkhoa='Khám chuyên khoa';  
    $LDGiayto='Các giấy tờ cần in ấn <br> trong quá trình điều trị';
    $LDInfoGiayto='Thông tin các giấy tờ cần in ấn';
    $LDPersonell_dept='Liệt kê nhân viên hiện có trong mỗi khoa';
    $LDSupport_chosepersonell='Người bạn muốn tìm thuộc khoa nào thì "click chuột vào dấu  mũi tên khoa đó"';
    $LDSTT='STT';    
    $LDFullName='Họ và tên';
    $LDNrPersonell='Mã nhân viên';
    //Chuc vu
    $LDNurse='Điều dưỡng';
    $LDAssistingAnesthesiologist='Kỹ thuật viên gây mê';
    $LDAnesthesiaNurse='Hộ lý';
    $LDNoPersonell='Chưa có nhân viên nào'; 
    $LDWarningTime1='Thời gian tử vong bạn nhập không hợp lệ';
    $LDWarningHour1='Giờ tử vong bạn nhập không hợp lệ';
    $LDWarningMinute1='Phút tử vong bạn nhập không hợp lệ';
    $LDWarningSecond1='Giây tử vong bạn nhập không hợp lệ';
    $LDWarningTime2='Thời gian kiểm điểm tử vong bạn nhập không hợp lệ';
    $LDWarningHour2='Giờ kiểm điểm tử vong bạn nhập không hợp lệ';
    $LDWarningMinute2='Phút kiểm điểm tử vong bạn nhập không hợp lệ';
    $LDWarningSecond2='Giây kiểm điểm tử vong bạn nhập không hợp lệ';
    $LDOneperson='Bản thân';
    $LDGroupperson='Gia đình';
	
	
$LDKhamYHCTNoiTru='Khám bệnh YHCT nội trú';
$LDChanDoan='Chẩn đoán';
$LDBienChungLuanTri='Biện chứng luận trị';
$LDChanDoanNote='1- Bệnh danh:
2- Chẩn đoán bát cương:
3- Chẩn đoán tạng phủ, kinh lạc:
4- Chẩn đoán nguyên nhân:';
//chuyen khoa mat
$LDDienBienBenh='Diễn biến bệnh';
	$LDThiluckokinhT="T.lực k.kính MT";
	$LDThiluckokinhP="T.lực k.kính MP";
	$LDThiluccokinhT="T.lực c.kính MT";
	$LDThiluccokinhP="T.lực c.kính MP";
	$LDNhanapT="Nhãn áp MT";
	$LDNhanapP="Nhãn áp MP";
	$LDThitruongT="Thị trường MT";
	$LDThitruongP="Thị trường MP";
	$LDLedaoT="Lệ đạo MT";
	$LDLedaoP="Lệ đạo MP";
	$LDMimatT="Mi mắt MT";
	$LDMimatP="Mi mắt MP";
	$LDKetmacT="Kết mạc MT";
	$LDKetmacP="Kết mạc MP";
	$LDMathotT="T/h mắt hột MT";
	$LDMathotP="T/h mắt hột MP";
	$LDGiacmacT="Giác mạc MT";
	$LDGiacmacP="Giác mạc MP";
	$LDCungmacT="Củng mạc MT";
	$LDCungmacP="Củng mạc MP";
	$LDTienphongT="Tiền phòng MT";
	$LDTienphongP="Tiền phòng MP";
	$LDMongmatT="Mống mắt MT";
	$LDMongmatP="Mống mắt MP";
	$LDDongtuT="Đồng tử-Phản xạ MT";
	$LDDongtuP="Đồng tử-Phản xạ MP";
	$LDThuytinhtheT="Thủy tinh thể MT";
	$LDThuytinhtheP="Thủy tinh thể MP";
	$LDThuytinhdichT="Thủy tinh dịch MT";
	$LDThuytinhdichP="Thủy tinh dịch MP";
	$LDAnhdongtuT="Soi ánh đồng tử MT";
	$LDAnhdongtuP="Soi ánh đồng tử MP";
    $LDNhancauT="Tình hình nhãn cầu MT";
    $LDNhancauP="Tình hình nhãn cầu MP";
 	$LDHocmatT="Hốc mắt MT";
 	$LDHocmatP="Hốc mắt MP";
	$LDDaymatT="Đáy mắt MT";
	$LDDaymatP="Đáy mắt MP";
	$LDVongchan="Vong chẩn";
	$LDVanchan="Văn chẩn";
	$LDVanchan1="Vấn chẩn";
	$LDThietchan="Thiết chẩn";
	$LDChandoan="Chẩn đoán";
	$LDBenhdanh="Bệnh danh";
	$LDBatcuong="Bát cương";
	$LDTangphu="Tạng phủ";
	$LDNguyennhan="Nguyên nhân";
	$LDDieutri="Điều trị";
	$LDPhepchua="Phép chữa";
	$LDPhuongthuoc="Phương thuốc";
	$LDPhuonghuyet="Phương huyệt";
	$LDKhambenhYHCT="Khám bệnh YHCT";
	$LDXoabop="Xoa bóp";
	$LDChedoan="Chế độ ăn tại nhà";
	$LDChedoholy="Chế độ hộ lý tại nhà";
	$LDTienluong="Tiên lượng";
	$LDTTBA="Tóm tắt bệnh án";
	$LDThanhquan="Thanh quản";
	$LDTongquan="Tổng quan";
	$LDHong="Cổ họng";
	$LDConghiengT="Cổ nghiêng trái";
	$LDConghiengP="Cổ nghiêng phải";
	$LDKhambenhRHM="Khám bệnh RHM";
	$LDKhambenhTMH="Khám bệnh TMH";
	
	$LDNoiNhi="Hỏi bệnh Nội nhi";
	$LDTinhtrangkhisinh="Tình trạng khi sinh";
	$LDTestDuongHuyet="Xét nghiệm đường huyết";
	$LDTim="Tím";
	$LDSpo2="SpO2";
	$LD['trigiac']=array(
					'Tri giác',
					'1-Tỉnh',
					'2-Li bì',
					'3-Hôn mê'
				);
	$LDLoetmieng="Loét miệng";
	$LDPhatban="Phát ban";
	$LD['tiengtim']=array('Tiếng tim',
						  'Rõ',
						  'Mờ',
						  'Gallop',
						  'Âm thổi',
						  'Dấu hiệu tĩnh mạch cổ nổi',
						  'Thời gian đổ đầy mao mạch',
						  'Vã mồ hôi',
						  'Da nổi bông'		
							);
	$LDDauhieukhac="Dấu hiệu khác";
	$LD['hohap']=array('Cơn ngưng thở',
					   'Thở nông',
					   'Thở bụng',
					   'Khò khè',
					   'Thở rít thanh quản',
					   'Rút lõm ngực',
					   'Ran phổi'
		);
	$LDGanto='Gan to';
	$LD['thankinh']= array('Đồng tử',
						   'PXAS',
						   'Cổ gượng',
						   'Giật mình lúc khám',
						   'Thất điều',
						   'Rung giật nhãn cầu',
						   'Lé',
						   'Yếu chi/Liệt mềm cấp',
						   'Liệt TK sọ',
						   'Ngủ gà'
					);
	$LDOthers="TMH-RHM & Khác";
	$LDMota='- <u>Mô tả</u>';
	$LD['vongchan']=array(
		'I.VỌNG CHẨN',
		'1.Hình thái',
		'1.Gầy',
		'2.Béo',
		'3.Cân đối',
		'4.Nằm co',
		'5.Ưa tĩnh',
		'6.Nằm duỗi',
		'7.Hiếu động',
		'8.Khác'
	);
	$LD['thansac']=array(
		'2.Thần sắc',
		'- Tỉnh táo tiếp xúc tốt',
		'- Sắc:',
		'1.Bệch/trắng',
		'2.Đỏ',
		'3.Vàng',
		'4.Xanh',
		'5.Đen',
		'6.Khác',
		'7.Bình thường',
		'- Trạch:',
		'8.Tươi nhuận',
		'9.Khô',
		'10.Khác'
		);
	$LD['luoi']=array(
	'3.Lưỡi',
	'- Chất lưỡi',
	'1.Bình thường',
	'2.Mọng to',
	'3.Gầy mỏng',
	'4.Nứt',
	'5.Loét',
	'6.Cứng',
	'7.Lệch',
	'8.Rút',
	'9.Khác',
	'- Sắc lưỡi',
	'1.Hồng, bình thường',
	'2.Nhợt',
	'3.Đỏ',
	'4.Đỏ sẩm, giáng',
	'5.Xanh tím',
	'6.Đám ứ huyết',
	'7.Khô',
	'8.Nhuận',
	'9.Khác',
	'- Rêu lưỡi',
	'1.Có rêu',
	'2.Không rêu',
	'3.Rêu bong',
	'4.Rêu dày',
	'5.Rêu mỏng',
	'6.Rêu ướt',
	'7.Rêu khô',
	'8.Rêu bẩn dính',
	'9.Màu trắng',
	'10.Vàng',
	'11.Đen',
	'12.Khác'
	);
	$LDBophanbenh="4.Bộ phận bị bệnh";
	$LDVANCHAN='II.VẤN CHẨN';
	$LD['amthanh']=array(
	'1.Âm thanh',
	'- Tiếng nói',
	'1.Bình thường',
	'2.To khỏe nhiều',
	'3.Nhỏ nhẹ ít đứt quãng',
	'4.Khàn',
	'5.Ngọng',
	'6.Mất tiếng',
	'7.Khóc thét',
	'8.Nói lẩm bẩm một mình',
	'9.Khác',
	'- Hơi thở',
	'2.Đứt quãng',
	'3.Ngắn',
	'4.Yếu',
	'5.Thô',
	'6.Có tiếng rít',
	'7.Khò khè',
	'8.Thở chậm',
	'9.Thở gấp nhanh',
	'10.Khác',
	'- Ho',
	'1.Liên tục',
	'2.Ho cơn',
	'3.Ho ít',
	'4.Ho nhiều',
	'5.Ho khan',
	'6.Ho có đờm',
	'7.Khác',
	'- Ợ nấc'
	);
	$LD['mui']=array(
	'2.Mùi',
	'- Chất thải biểu hiện bệnh lý',
	'1.Đờm',
	'2.Chất nôn',
	'3.Phân',
	'4.Nước tiểu',
	'5.Khí hư',
	'6.Kinh nguyệt',
	'7.Khác',
	'- Hơi người, hơi thở có mùi',
	'1.Mùi chua',
	'2.Khẳm',
	'3.Tanh',
	'4.Thối',
	'5.Hôi',
	'6.Khác'
	);
	$LD['hannhiet']=array(
	'1.Hàn nhiệt',
	'- Biểu hiện bệnh lý:',
	'1.Thích nóng',
	'2.Sợ nóng',
	'3.Thích mát lạnh',
	'4.Sợ gió lạnh',
	'5.Trong người nóng',
	'6.Trong người lạnh',
	'7.Rét run',
	'8.Hàn nhiệt vãng lai',
	'9.Khác',
	'- Bệnh thay đổi theo mùa:'
	);
	$LDVAN_CHAN="III. VẤN CHẨN";
	$LD['mohoi']=array(
	'2.Mồ hôi',
	'1.Bình thường',
	'2.Không mồ hôi',
	'3.Tự hãn',
	'4.Đạo hãn',	
	'5.Ít',	
	'6.Nhiều',
	'7.Khác'
	);
	$LD['daumat']=array(
	'3.Đầu mặt:',
	'- Biểu hiện bệnh lý:',
	'- Đau đầu',
	'1.Một chỗ',
	'2.Nữa đầu',
	'3.Cả đầu',
	'4.Di chuyển',
	'5.Ê ẩm như buộc lại',
	'6.Nhói',
	'7.Căng',
	'8.Đau nửa đầu',
	'9.Nặng đầu',
	'- Mắt:',
	'10.Hoa mắt chóng mặt',
	'11.Nhìn không rõ',
	'- Tai:',
	'12.Tai ù',
	'13.Tai điếc',
	'14.Nặng tai',
	'15.Đau tai',
	'- Mũi:',
	'16.Ngạt mũi',
	'17.Chảy nước mũi',
	'18.Đau mũi',
	'19.Chảy máu cam',
	'- Họng:',
	'20.Đau họng',
	'21.Khô',
	'- Cổ vai:',
	'22.Mỏi',
	'23.Đau',
	'24.Khó vận động',
	'25.Khác'
	);
	$LD['lung']=array('4.Lưng:','- Biểu hiện bệnh lý');
	$LD['bungnguc']=array(
	'5.Bụng và ngực:',
	'- Biểu hiện bệnh lý',
	'1.Tức',
	'2.Đau',
	'3.Sôi',
	'4.Nóng ruột',
	'5.Đầy trướng',
	'6.Ngột ngạt khó thở',
	'7.Đau tức cạnh sường',
	'8.Bồn chồn không yên',
	'9.Đánh trống ngực',
	'10.Khác'
	);
	$LD['chantay']=array('6.Chân tay:','- Biểu hiện bệnh lý');
	$LD['an']= array(
	'7.Ăn',
	'- Biểu hiện bệnh lý',
	'1.Thích ăn nóng',
	'2.Thích ăn mát',
	'3.Ăn nhiều',
	'4.Ăn ít',
	'5.Đắng miệng',
	'6.Nhạt miệng',
	'7.Thèm ăn',
	'8.Chán ăn',
	'9.Ăn nhiều tiêu nhanh',
	'10.Ăn vào bụng trướng',
	'11.Khác'
	);
	$LD['uong']=array(
	'8.Uống',
	'- Biểu hiện bệnh lý',
	'1.Thích uống mát',
	'2.Thích uống ấm nóng',
	'3.Khát uống thì hết khát',
	'4.Khác uống không hết khác',
	'5.Uống nhiều',
	'6.Uống ít',
	'7.Khác'
	);
	$LD['daitieutien']=array(
	'9.Đại tiểu tiện',
	'- Tiểu tiện',
	'1.Như cao',
	'2.Vàng',
	'3.Vỏ',
	'4.Đục',
	'5.Đái đau buốt',
	'6.Đái dắt',
	'7.Đái không tự chủ',
	'8.Bí đái',
	'9.Đái khó',
	'10.Khác',
	'- Đại tiện',
	'1.Phân khô',
	'2.Phân vón hòn',
	'3.Phân nhão',
	'4.Phân sống',
	'5.Phân toàn nước',
	'6.Phân có mùi',
	'7.Phân có máu',
	'8.Đại tiện dễ',
	'9.Đại tiện khó phải rặng',
	'10.Bí đại tiện',
	'11.Khác'
	);
	$LD['ngu']=array(
	'10.Ngủ',
	'1.Khó vào giấc',
	'2.Nữa đêm thức giấc ngủ lại khó/không được',
	'3.Ngủ dậy quá sớm không ngủ lại được',
	'4.Chập chờn mộng nhiều',
	'5.Khác'
	);
	$LD['kn_sd']=array(
	'11.Kinh nguyệt, sinh dục',
	'- Kinh nguyệt biểu hiện bệnh lý',
	'+ Rối loạn kinh nguyệt',
	'1.Kinh nguyệt đến trước kỳ',
	'2.Kinh nguyệt đến sau kỳ',
	'3.Lúc đến trước,lúc đến sau kỳ',
	'4.Tắc kinh',
	'5.Khác',
	'+ Thống kinhSS',
	'1.Đau trước kỳ',
	'2.Đau trong kỳ',
	'3.Đau sau kỳ',
	'4.Khác',
	'+ Đới hạ biểu hiện bệnh lý',
	'1.Vàng',
	'2.Trắng',
	'3.Hồng',
	'4.Bọt',
	'5.Hôi',
	'6.Lượng nhiều',
	'7.Lượng ít',
	'8.Loãng',
	'9.Đặc',
	'10.Khác',
	'- Khả năng sinh dục rối loạn',
	'+ Nam:',
	'1.Yếu, không đáp ứng hành vi giao hợp',
	'2.Di tinh',
	'3.Hoạt tinh',
	'4.Mộng tinh',
	'5.Lãnh tinh',
	'+ Nữ:',
	'6.Không thụ thai được',
	'7.Sảy thai/ động thai',
	'8.Sảy thai liên tục',
	'9.Khác'
	);
	$LD['dkxuathien']=array(
	'12.Điều kiện xuất hiện:',
	'1.Lục dâm',
	'2.Thất tình',
	'3.Hoạt động sống'
	);
	$LDTHIETCHAN="IV.THIẾT CHẨN";
	$LD['xucchan']=array(
	'1.Xúc chẩn',
	'- Da:',
	'1.Da bình thường',
	'2.Da khô',
	'3.Da nóng',
	'4.Da lạnh',
	'5.Da ướt',
	'6.Chân tay nóng',
	'7.Chân tay lạnh',
	'8.Ấn lõm',
	'9.Cục cứng',
	'10.Ấn đau',
	'11.Khác',
	'- Mồ hôi:',
	'1.Mồ hôi toàn thân',
	'2.Mồ hôi trán',
	'3.Mồ hôi tay chân',
	'4.Khác',
	'-Cơ nhục:',
	'1.Săn chắc',
	'2.Mềm nhẽo',
	'3.Căng cứng',
	'4.Cơ co có ấn đau',
	'5.Gân đau',
	'6.Xương khớp đau',
	'7.Khác',
	'-Bụng:',
	'1.Bụng mềm',
	'2.Bụng trướng',
	'3.Cổ trướng',
	'4.Có hòn cục',
	'5.Đau thiện án',
	'6.Đau cự án',
	'7.Khác'
	);
	$LD['machchan']=array(
	'2.Mạch chẩn',
	'1.Phù',
	'2.Trầm',
	'3.Sắc',
	'4.Trì',
	'5.Hoãn',
	'6.Tiều tế',
	'7.Huyền',
	'8.Hoạt',
	'9.Vô lực',
	'10.Có lực',
	'11.Hư',
	'12.Thực',
	'13.Khác',
	'-Mạch tay trái:',
	'Thốn',
	'Quan',
	'Xích',
	'-Mạch tay phải',
	'Tổng khán',
	'Bên phải',
	'Bên trái'
	);
	$LDBAMat="Bệnh án mắt";
	$LDTreem="Trẻ em dưới 6 tuổi đi học";
	$LDSinhvien="Sinh viên - học sinh";
	$LDDuoi60="Hưu và trên 60 tuổi";
	$LDCongnhan="Công nhân";
	$LDNongdan="Nông dân";
	$LDVutrang="Lực lượng vũ trang";
	$LDTrithuc="Trí thức";
	$LDHanhchanh="Hành chánh, sự nghiệp";
	$LDYte="Y tế";
	$LDDichvu="Dịch vụ";
	$LDVietkieu="Việt kiều";
	$LDGia="Già";
	$LDCNV="Công nhân viên";
	$LDThomay="Thợ may";
	$LDKhac="Khác";
	$LDInputDate="Ngày giờ nhập liệu";
	$LDMui='Khám Mũi';
	$LDTai='Khám Tai';
	$LDTongquat='Tổng quát';
	$LDChuyenKhoa='Bệnh chuyên khoa';
	?>