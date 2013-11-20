<?php
    //error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR),

    ### The following arrays are the "role" levels each containing an access point or groups of access points
    $all='_a_0_all';
    $sysadmin='System_Admin';
    $allow_area = array(
        'address' => array('address','LDaddress',
                    array(
                      /*  array('address_manage','LDaddress_manage'),
                        array('address_manage_pass','LDaddress_manage_pass'),
                        array('citytown_info','LDcitytown_info'),
                        array('citytown_list','LDcitytown_list'),
                        array('citytown_new','LDcitytown_new'),
                        array('citytown_search','LDcitytown_search'),
                        array('citytown_update','LDcitytown_update'),
                        array('getquanhuyen','LDgetquanhuyen'),
                        array('phuongxa_info','LDphuongxa_info'),
                        array('phuongxa_list','LDphuongxa_list'),
                        array('phuongxa_new','LDphuongxa_new'),
                        array('phuongxa_search','LDphuongxa_search'),
                        array('phuongxa_update','LDphuongxa_update'),
                        array('phuongxa_info','LDphuongxa_info'),
                        array('phuongxa_list','LDphuongxa_list'),
                        array('quanhuyen_new','LDquanhuyen_new'),
                        array('quanhuyen_search','LDquanhuyen_search'),
                        array('quanhuyen_update','LDquanhuyen_update')  
                        */                      
                        )),
        'ambulatory' => array('ambulatory','LDambulatory',
                    array(
                      /*  array('amb_clinic_assignwaiting','LDamb_clinic_assignwaiting'),    
                        array('amb_clinic_discharge','LDamb_clinic_discharge'),
                        array('amb_clinic_patients','LDamb_clinic_patients'),
                        array('amb_clinic_patients_pass','LDamb_clinic_patients_pass'),
                        array('amb_clinic_transfer_save','LDamb_clinic_transfer_save'),
                        array('amb_clinic_transfer_select','LDamb_clinic_transfer_select'),
                        array('amb_target_paper','LDamb_target_paper'),
                        array('amb_yellow_paper','LDamb_yellow_paper'),
                        array('ambulatory','LDambulatory')                                    
                        */
                        )),
        'appointment_scheduler' => array('appointment_scheduler','LDappointment_scheduler',
                    array(
                        array('appt_main_pass','LDappt_main_pass'),                                        
                        array('appt_show','LDappt_show')
                        )),
        'cafeteria' => array('cafeteria','LDcafeteria',
                    array(
                        array('','LD'),                                        
                        )),
        'calendar' => array('calendar','LDcalendar',
                    array(
                        array('','LD'),                                        
                        )),
        'dept_admin' => array('dept_admin','LDdept_admin',
                    array(
                        array('','LD'),                                        
                        )),
        'doctors' => array('doctors','LDdoctors',
                    array(
                        array('','LD'),                                        
                        )),
        'document' => array('document','LDdocument',
                    array(
                        array('','LD'),                                        
                        )),
        'drg' => array('drg','LDdrg',
                    array(
                        array('','LD'),                                        
                        )),
        'ecombill' => array('ecombill','LDecombill',
                    array(#patient_due_first showfinalbill showpayment
                        array('patient_due_first','LDpatient_due_first'),                                        
                        array('showfinalbill','LDshowfinalbill'), 
                        array('showpayment','LDshowpayment')
                        )),
        'fotolab' => array('fotolab','LDfotolab',
                    array(
                        array('','LD'),                                        
                        )),
        'hxp' => array('hxp','LDhxp',
                    array(
                        array('','LD'),                                        
                        )),
        'immunization' => array('immunization','LDimmunization',
                    array(
                        array('','LD'),                                        
                        )),
        'insurance_co' => array('insurance_co','LDinsurance_co',
                    array(
                        array('','LD'),                                        
                        )),
        'intranet_email' => array('intranet_email','LDintranet_email',
                    array(
                        array('','LD'),                                        
                        )),
        'laboratory' => array('laboratory','LDlaboratory',
                    array(
                        array('','LD'),                                        
                        )),
        'med_depot' => array('med_depot','LDmed_depot',
                    array(
                        array('','LD'),                                        
                        )),
        'medocs' => array('medocs','LDmedocs',
                    array(
                        array('','LD'),                                        
                        )),
        'myintranet' => array('myintranet','LDmyintranet',
                    array(
                        array('','LD'),                                        
                        )),
        'news' => array('news','LDnews',
                    array(
                        array('','LD'),                                        
                        )),
        'nursing' => array('nursing','LDnursing',
                    array(
                        array('','LD'),                                        
                        )),
        'nursing_or' => array('nursing_or','LDnursing_or',
                    array(
                        array('','LD'),                                        
                        )),
        'op_document' => array('op_document','LDop_document',
                    array(
                        array('','LD'),                                        
                        )),
        'or' => array('or','LDor',
                    array(
                        array('','LD'),                                        
                        )),
        'or_admin' => array('or_admin','LDor_admin',
                    array(
                        array('','LD'),                                        
                        )),
        'or_logbook' => array('or_logbook','LDor_logbook',
                    array(
                        array('','LD'),                                        
                        )),
        'pdfmaker' => array('pdfmaker','LDpdfmaker',
                    array(
                        array('','LD'),                                        
                        )),
        'personell_admin' => array('personell_admin','LDpersonell_admin',
                    array(
                        array('','LD'),                                        
                        )),
        'pharmacy' => array('pharmacy','LDpharmacy',
                    array( #catalogue_chemical  chemical_request_khochan_payout  chemical_request_khochan_putin  payout payout_chemical payout_medipot 
                        array('catalogue_chemical','LDcatalogue_chemical'),
                        array('chemical_request_khochan_payout','LDchemical_request_khochan_payout'), 
                        array('chemical_request_khochan_putin','LDchemical_request_khochan_putin'), 
                        array('payout','LDpayout'), 
                        array('payout_chemical','LDpayout_chemical'), 
                        array('payout_medipot','LDpayout_medipot'), 
                        array('payout_medipot_list_medicine','LDpayout_medipot_list_medicine'), 
                        array('pharma_request_chemical_destroy','LDpharma_request_chemical_destroy'), 
                        array('pharma_request_chemical_patient','LDpharma_request_chemical_patient'), 
                        array('pharma_request_chemical_return','LDpharma_request_chemical_return'), 
                        array('pharma_request_chemical_ward','LDpharma_request_chemical_ward'), 
                        array('pharma_request_khochan_payout','LDpharma_request_khochan_payout'), 
                        array('pharma_request_khochan_payout_medipot','LDpharma_request_khochan_payout_medipot'), 
                        array('pharma_request_khochan_putin','LDpharma_request_khochan_putin'), 
                        array('pharma_request_khochan_putin_medipot','LDpharma_request_khochan_putin_medipot'), 
                        array('pharma_request_medicine_destroy','LDpharma_request_medicine_destroy'), 
                        array('pharma_request_medicine_patient','LDpharma_request_medicine_patient'), 
                        array('pharma_request_medicine_return','LDpharma_request_medicine_return'), 
                        array('pharma_request_medicine_ward','LDpharma_request_medicine_ward'), 
                        array('pharma_request_medipot_destroy','LDpharma_request_medipot_destroy'), 
                        array('pharma_request_medipot_patient','LDpharma_request_medipot_patient'), 
                        array('pharma_request_medipot_return','LDpharma_request_medipot_return'), 
                        array('pharma_request_medipot_ward','LDpharma_request_medipot_ward'), 
                        array('','LD'), 
                        )),
        'phone_directory' => array('phone_directory','LDphone_directory',
                    array(
                        array('','LD'),                                        
                        )),
        'products' => array('products','LDproducts',
                    array(
                        array('','LD'),                                        
                        )),
        'property' => array('property','LDproperty',
                    array(
                        array('changeDepartment','LDchangeDepartment'),                                        
                        array('changeWard','LDchangeWard'),
                        array('checkPropertyExist','LDcheckPropertyExist'),
                        array('data_search','LDdata_search'),
                        array('property_export','LDproperty_export'),
                        array('property-admi-welcome','LDproperty-admi-welcome'),
                        array('property-create-new','LDproperty-create-new'),
                        array('property-detail-show','LDproperty-detail-show'),
                        array('property-find-advance','LDproperty-find-advance'),
                        array('property-list-by-dept','LDproperty-list-by-dept'),                                        
                        array('property-main-pass','LDproperty-main-pass'),
                        array('property-operate','LDproperty-operate'),
                        array('property-operating-history','LDproperty-operating-history'),
                        array('property-repair-history','LDproperty-repair-history'),
                        array('property-select-dept','LDproperty-select-dept'),
                        array('property-transmit','LDproperty-transmit'),
                        array('property-using-history','LDproperty-using-history')
                        )),
        'radiology' => array('radiology','LDradiology',
                    array(
                        array('','LD'),                                        
                        )),
        'registration_admission' => array('registration_admission','LDregistration_admission',
                    array(
                        array('','LD'),                                        
                        )),
        'supplier' => array('supplier','LDsupplier',
                    array(
                        array('','LD'),                                        
                        )),
        'system_admin' => array('system_admin','LDsystem_admin',
                    array(
                        array('','LD'),                                        
                        )),
        'tech' => array('tech','LDtech',
                    array(
                        array('','LD'),                                        
                        )),
        'timekeeping' => array('timekeeping','LDtimekeeping',
                    array(
                        array('','LD'),                                        
                        )),
        'tools' => array('tools','LDtools',
                    array(
                        array('','LD'),                                        
                        )),
        'video_monitor' => array('video_monitor','LDvideo_monitor',
                    array(
                        array('','LD'),                                        
                        ))
        );
/*
*   Array chua cac chuc vu trong khoa
*/
/**
*'catalogue_chemical_f','chemical_request_khochan_payout_f','chemical_request_khochan_putin_f','payout_f''payout_chemical_f','payout_medipot','payout_medipot_list_medicine','pharma_request_chemical_destroy','pharma_request_chemical_patient','pharma_request_chemical_return','pharma_request_chemical_ward','pharma_request_khochan_payout','pharma_request_khochan_payout_medipot','pharma_request_khochan_putin','pharma_request_khochan_putin_medipot','pharma_request_medicine_destroy','pharma_request_medicine_patient','pharma_request_medicine_return','pharma_request_medicine_ward','pharma_request_medipot_destroy','pharma_request_medipot_patient','pharma_request_medipot_return','pharma_request_medipot_ward'
**/
        $indept_allow = array(
            'LDBGD' => array('giamdoc'=>array('giamdoc','LDgiamdoc',array('_a_0_all')),
                            'phogiamdoc'=>array('phogiamdoc','LDphogiamdoc',array('_a_0_all')),
                          
                        ),
            'LDKD' => array('truongkhoaduoc'=>array('truongkhoaduoc','LDTruongKhoa',array('catalogue_chemical_f','chemical_request_khochan_payout_f')),
                            'phokhoaduoc'=>array('phokhoaduoc','LDPhoKhoa',array('catalogue_chemical_f','chemical_request_khochan_payout_f')),
                            'ddkhoaduoc'=>array('ddkhoaduoc','LDDieuduong',array('catalogue_chemical_e','chemical_request_khochan_payout_e')),
                            'hlkhoaduoc'=>array('hlkhoaduoc','LDHoly',array('catalogue_chemical_e','chemical_request_khochan_payout_e')),
                            'nvkhoaduoc'=>array('nvkhoaduoc','LDNhanvien',array('catalogue_chemical_e','chemical_request_khochan_payout_e')),
                            'bacsikd'=>array('bacsikd','LDBacsi',array('catalogue_chemical_e','chemical_request_khochan_payout_e')),
                            'ysikd'=>array('ysikd','LDYsi',array('catalogue_chemical_v','chemical_request_khochan_payout_v'))
                        ),
            
            'LDKKDK'=> array('truongkhoakhambenh'=>array('truongkhoakhambenh','LDTruongKhoa',array()),
                            'phokhoakhambenh'=>array('phokhoakhambenh','LDPhoKhoa',array()),
                            'ddkhoakhambenh'=>array('ddkhoakhambenh','LDDieuduong',array()),
                            'hlkhoakhambenh'=>array('hlkhoakhambenh','LDHoly',array()),
                            'nvkhoakhambenh'=>array('nvkhoakhambenh','LDNhanvien',array()),
                            'bacsikkb'=>array('bacsikkb','LDBacsi',array()),
                            'ysikkb'=>array('ysikkb','LDYsi',array())
                        ),
            'LDKHSCC'=> array(array('truongkhoahscc','LDTruongKhoa',array()),
                            array('phokhoahscc','LDPhoKhoa',array()),
                            array('ddkhoahscc','LDDieuduong',array()),
                            array('hlkhoahscc','LDHoly',array()),
                            array('nvkhoahscc','LDNhanvien',array()),
                            array('bacsihscc','LDBacsi',array()),
                            array('ysihscc','LDYsi',array())
                        ),
            'LDKN'=> array(array('truongkhoangoai','LDTruongKhoa',array()),
                            array('phokhoangoai','LDPhoKhoa',array()),
                            array('ddkhoangoai','LDDieuduong',array()),
                            array('hlkhoangoai','LDHoly',array()),
                            array('nvkhoangoai','LDNhanvien',array()),
                            array('bacsikn','LDBacsi',array()),
                            array('ysikn','LDYsi',array())
                        ),
            'LDKNNN'=> array(array('truongkhoanoi','LDTruongKhoa',array()),
                            array('phokhoanoi','LDPhoKhoa',array()),
                            array('ddkhoanoi','LDDieuduong',array()),
                            array('hlkhoanoi','LDHoly',array()),
                            array('nvkhoanoi','LDNhanvien',array()),
                            array('bacsiknnn','LDBacsi',array()),
                            array('ysiknnn','LDYsi',array())
                        ),
            'LDKCDHA' =>array(array('truongkhoacdha','LDTruongKhoa',array()),
                            array('phokhoacdha','LDPhoKhoa',array()),
                            array('ddkhoacdha','LDDieuduong',array()),
                            array('hlkhoacdha','LDHoly',array()),
                            array('nvkhoacdha','LDNhanvien',array()),
                            array('bacsikcdha','LDBacsi',array()),
                            array('ysikcdha','LDYsi',array())
                        ),
            'LDKXN'=> array(array('truongkhoaxn','LDTruongKhoa',array()),
                            array('phokhoaxn','LDPhoKhoa',array()),
                            array('ddkhoaxn','LDDieuduong',array()),
                            array('hlkhoaxn','LDHoly',array()),
                            array('nvkhoaxn','LDNhanvien',array()),
                            array('bacsikxn','LDBacsi',array()),
                            array('ysikxn','LDYsi',array())
                        ),
            'LDKYHCT' => array(array('truongkhoayhct','LDTruongKhoa',array()),
                            array('phokhoayhct','LDPhoKhoa',array()),
                            array('ddkhoayhct','LDDieuduong',array()),
                            array('hlkhoayhct','LDHoly',array()),
                            array('nvkhoayhct','LDNhanvien',array()),
                            array('bacsiyhct','LDBacsi',array()),
                            array('ysiyhct','LDYsi',array())
                        ),
            'LDPTCKT' => array(array('truongtckt','LDTruongKhoa',array()),
                            array('phokhoatckt','LDPhoKhoa',array()),
                            array('nvkhoatckt','LDNhanvien',array())
                        ),
            'LDPKHTH' => array(array('truongkhth','LDTruongKhoa',array()),
                            array('phokhoakhth','LDPhoKhoa',array()),
                            array('nvkhoakhth','LDNhanvien',array())
                        ),
            'LDPTCHC' => array(
                            array('truongtchc','LDTruongKhoa',array()),
                            array('phokhoatchc','LDPhoKhoa',array()),
                            array('nvkhoatchc','LDNhanvien',array())
                        ),
            'LDKS' => array(
                            array('truongkhoasan','LDTruongKhoa',array()),
                            array('phokhoasan','LDPhoKhoa',array()),
                            array('ddkhoasan','LDDieuduong',array()),
                            array('hlkhoasan','LDHoly',array()),
                            array('nvkhoasan','LDNhanvien',array()),
                            array('bacsiks','LDBacsi',array()),
                            array('ysiks','LDYsi',array())
                        )
            );
    /*
    $allow_area=array(

        //Đăng kí mã nhân viên hoặc mã bệnh nhân   

        'admit'=>array('_a_1_admitwrite', '_a_2_photoread'),
        //Tiếp nhận bệnh nhân
        'admission'=>array('_a_1_admissionwrite','_a_2_medocsread', '_a_2_photoread'),
        //Trưởng khoa 
        'dean'=>array('_a_1_admissionwrite',//Xem và ghi thông tin tiếp nhận
                        '_a_1_nursingstationallwrite',//Xem và ghi mọi thông tin điều dưỡng khoa phòng
                        '_a_2_nursingdutyplanread',//Chỉ được xem lịch trực y tá
                        '_a_1_diagnosticsresultwrite',//Xem và đưa ra chẩn đoán
                        '_a_2_diagnosticsreceptionwrite',//Xem và nhận yêu cầu chẩn đoán
                        '_a_1_medocswrite',//Xem và ghi bệnh án
                        '_a_3_pharmaorder',//Xem và gửi phiếu cấp phát thuốc
                        '_a_3_meddepotorder',//Xem và gửi yêu cầu vật tư-thiết bị
                        '_a_1_doctorsdutyplanwrite',//Xem và xếp lịch trực bác sỹ trong khoa
                        '_a_1_timestampallwrite',//Quản lý và phân công lịch trực (mọi khâu) chỉ cho khoa mình
                        '_a_1_dutyplanallwrite',//Quản lý và phân công nhân sự (mọi khâu) chỉ cho khoa mình
                        '_a_2_radioread',//Chỉ được xem phim, chỉ được đọc bản kết quả chẩn đoán X Quang\
                        '_a_4_pharmaread',
                        '',
                        '_a_2_photoread'),//Cho xem ảnh cá nhân
        //trưởng khoa phòng mổ
        'dean_op'=>array('_a_1_admissionwrite',//Xem và ghi thông tin tiếp nhận
                        '_a_1_nursingstationallwrite',//Xem và ghi mọi thông tin điều dưỡng khoa phòng
                        '_a_2_nursingdutyplanread',//Chỉ được xem lịch trực y tá
                        '_a_1_diagnosticsresultwrite',//Xem và đưa ra chẩn đoán
                        '_a_2_diagnosticsreceptionwrite',//Xem và nhận yêu cầu chẩn đoán
                        '_a_1_medocswrite',//Xem và ghi bệnh án
                        '_a_3_pharmaorder',//Xem và gửi phiếu cấp phát thuốc
                        '_a_3_meddepotorder',//Xem và gửi yêu cầu vật tư-thiết bị
                        '_a_1_doctorsdutyplanwrite',//Xem và xếp lịch trực bác sỹ
                        '_a_1_timestampallwrite',//Quản lý và phân công lịch trực (mọi khâu) chỉ cho khoa mình
                        '_a_1_dutyplanallwrite',//Quản lý và phân công nhân sự (mọi khâu) chỉ cho khoa mình
                        '_a_1_opdoctorallwrite',//Xem và ghi mọi hồ sơ phòng mổ (phẫu thuật viên)
                        '_a_2_opnurseallwrite',//Xem và ghi mọi hồ sơ điều dưỡng phòng mổ
                        '_a_2_radioread',//Chỉ được xem phim, chỉ được đọc bản kết quả chẩn đoán X Quang\
                        '_a_2_labresultsread',//Chỉ được xem kết quả xét nghiệm
                        '_a_2_photoread'),//Cho xem ảnh cá nhân

        //Điều dưỡng trưởng khoa
        'head_nursing'=>array('_a_1_admissionwrite',//Xem và ghi thông tin tiếp nhận
                            '_a_1_nursingstationallwrite',//Xem và ghi mọi thông tin điều dưỡng khoa phòng
                            '_a_1_nursingdutyplanwrite',//Xem và xếp lịch trực cho y tá
                            '_a_3_diagnosticsresultread',//Chỉ được xem chẩn đoán
                            '_a_1_laball',//Xem và ghi mọi xét nghiệm 
                            '_a_1_labresultswrite',//Xem và ghi kết quả xét nghiệm
                            '_a_1_medocswrite',//Xem và ghi bệnh án
                            '_a_3_pharmaorder',//Xem và gửi phiếu cấp phát thuốc
                            '_a_3_meddepotorder',//Xem và gửi yêu cầu vật tư-thiết bị
                            '_a_2_doctorsdutyplanread',//Chỉ được xem lịch trực bác sỹ
                            '_a_1_timestampallwrite',//Quản lý và phân công lịch trực (mọi khâu) chỉ cho khoa mình
                            '_a_1_dutyplanallwrite',//Quản lý và phân công nhân sự (mọi khâu) chỉ cho khoa mình
                            '_a_2_photoread'),//Cho xem ảnh cá nhân
        //Điều dưỡng trưởng khoa phòng mổ
        'head_nursing_op'=>array('_a_1_admissionwrite',//Xem và ghi thông tin tiếp nhận
                            '_a_1_nursingstationallwrite',//Xem và ghi mọi thông tin điều dưỡng khoa phòng
                            '_a_1_nursingdutyplanwrite',//Xem và xếp lịch trực cho y tá
                            '_a_3_diagnosticsresultread',//Chỉ được xem chẩn đoán
                            '$_a_2_labresultsread',//Xem kết quả xét nghiệm
                            '_a_1_medocswrite',//Xem và ghi bệnh án
                            '_a_3_pharmaorder',//Xem và gửi phiếu cấp phát thuốc
                            '_a_3_meddepotorder',//Xem và gửi yêu cầu vật tư-thiết bị
                            '_a_2_doctorsdutyplanread',//Chỉ được xem lịch trực bác sỹ
                            '_a_1_timestampallwrite',//Quản lý và phân công lịch trực (mọi khâu) chỉ cho khoa mình
                            '_a_1_dutyplanallwrite',//Quản lý và phân công nhân sự (mọi khâu) chỉ cho khoa mình
                            '_a_1_opnursedutyplanwrite',//Xem và xếp lịch trực của y tá phòng mổ
                            '_a_2_photoread'),//Cho xem ảnh cá nhân

        //Điều dưỡng hành chính khoa   
        'administrative_nursing'=>array('_a_2_admissionread',//Chỉ được xem thông tin tiếp nhận
                                        '_a_2_nursingstationallread',//Chỉ được xem thông tin điều dưỡng khoa phòng
                                        '_a_2_nursingdutyplanread',//Chỉ được xem lịch trực y tá 
                                        '_a_3_diagnosticsresultread',//Chỉ được xem chẩn đoán
                                        '_a_2_labresultsread',//Chỉ được xem kết quả xét nghiệm
                                        '_a_2_opnursedutyplanread',//Chỉ được xem lịch trực của y tá phòng mổ                                
                                        '_a_1_medocswrite',//Xem và ghi bệnh án
                                        '_a_4_pharmaread',//Chỉ xem phiếu cấp phát thuốc, không được gửi 
                                        '_a_4_meddepotread',//Chỉ được xem yêu cầu vật tư-thiết bị, không được gửi
                                        '_a_2_timestampallread',//Chỉ được xem bảng phân công lịch trực
                                        '_a_2_billpharmawrite',//Xem và ghi hóa đơn tiền thuốc
                                        '_a_2_billserviceswrite',//Xem và ghi hóa đơn dịch vụ (phi y tế)
                                        '_a_2_photoread'),//Cho xem ảnh cá nhân
        //Điều dưỡng
        'nursings' =>array('_a_2_admissionread',//Chỉ được xem thông tin tiếp nhận
                            '_a_2_nursingstationallread',//Chỉ được xem thông tin điều dưỡng khoa phòng
                            '_a_2_nursingdutyplanread',//Chỉ được xem lịch trực y tá 
                            '_a_3_diagnosticsresultread',//Chỉ được xem chẩn đoán
                            '_a_2_labresultsread',//Chỉ được xem kết quả xét nghiệm
                            '_a_2_opnursedutyplanread',//Chỉ được xem lịch trực của y tá phòng mổ                                
                            '_a_1_medocswrite',//Xem và ghi bệnh án
                            '_a_2_timestampallread',//Chỉ được xem bảng phân công lịch trực
                            '_a_1_medocpharmawrite',//Xem và ghi số lượng thuốc cho bệnh nhân
                            '_a_1_medocmed_write',//Xem và ghi từng dịch vụ cho bệnh nhân
                            '_a_2_photoread'),//Cho xem ảnh cá nhân
        //Căn tin
        'cafe'=>array('_a_2_newscafewrite', '_a_2_photoread'),
        //Quản lý bệnh án
        'medocs'=>array('_a_1_medocswrite', '_a_2_photoread'),
        //Thông tin điện thoại(mục điện thoại trên cây menu ) của từng người trong bệnh viện
        'phonedir'=>array('$all, $sysadmin', '_a_1_photowrite', '_a_1_hxpserver'),
        //Bác sĩ
        'doctors'=>array('_a_2_doctorsdutyplanread',
                        '_a_2_labresultsread',
                        '_a_1_radiowrite',
                        '_a_1_medocswrite',
                        '_a_2_dutyplanallread',
                        '_a_2_timestampallread',
                        '_a_2_photoread'),
        //Quản lý khu phòng, lịch trực, chấm công  
        'wards'=>array('_a_1_timestampallwrite',//Quản lý và phân công lịch trực (mọi khâu)
                        '_a_1_dutyplanallwrite',//Quản lý và phân công nhân sự (mọi khâu)
                        '_a_1_phonewrite',//Xem và quản lý thông tin điện thoại của toàn bệnh viện
                        '_a_1_photowrite',),//Nạp và xem hình cá nhân
        //'op_room'=>array('_a_1_opdoctorallwrite', '_a_1_opnursedutyplanwrite', '_a_2_opnurseallwrite'),
        //Hỗ trợ kỹ thuật
        'tech'=>array('_a_1_techreception', '_a_2_photoread'),
        //Toàn quyền trong xét nghiệm
        'lab_all'=>array('_a_1_laball',//Xem và ghi mọi xét nghiệm
                         '_a_2_photoread'),
        //Chỉ cho xem và tìm kiếm kết quả xét nghiệm
        'lab_r'=>array('_a_2_labresultsread', '_a_2_photoread'),
        //Xem và ghi kết quả xét nghiệm
        'lab_w'=>array('_a_1_labresultswrite', '_a_2_photoread'),
        //Toàn quyền chẩn đoán hình ảnh
        'radio_all'=>array('_a_1_radiowrite', '_a_2_photoread'),
        //chỉ được xem các chẩn đoán hình ảnh
        'radio_r'=>array('_a_2_radioread', '_a_2_photoread'),
        //Quản lý dược, VTYT
        'pharma_all'=>array('_a_1_pharmadbadmin',//Quản lý thuốc
                            '_a_2_pharmareception',//Kích hoạt công cụ tự động nhận và xử lý phiếu cấp phát thuốc
                            '_a_3_pharmaorder',//Xem và gửi phiếu cấp phát thuốc
                            '_a_1_meddepotdbadmin',//Quản lý vật tư-thiết bị
                            '_a_2_meddepotreception',//Hoạt hóa công cụ tự động nhận và xử lý yêu cầu vật tư-thiết bị
                            '_a_3_meddepotorder',//Xem và gửi yêu cầu vật tư-thiết bị
                            '_a_2_photoread'),
        //Được quyền lập phiếu và gửi các phiếu lĩnh thuốc, trả thuốc, phiếu lĩnh VTYT, trả VTYT
        'pharma_active'=>array('_a_2_pharmareception',//Kích hoạt công cụ tự động nhận và xử lý phiếu cấp phát thuốc
                                '_a_3_pharmaorder',//Xem và gửi phiếu cấp phát thuốc
                                '_a_2_meddepotreception',//Hoạt hóa công cụ tự động nhận và xử lý yêu cầu vật tư-thiết bị
                                '_a_3_meddepotorder',//Xem và gửi yêu cầu vật tư-thiết bị
                                '_a_2_photoread'),
        //Chỉ xem những thông tin về bên dược, VTYT
        'pharma'=>array( '_a_4_pharmaread', '_a_4_meddepotread', '_a_2_photoread'),
        //'pharma_db;=>array('_a_1_pharmadbadmin', '_a_2_photoread'),

        //'pharma_receive'=>array('_a_1_pharmadbadmin', '_a_2_pharmareception'),
        //
        //'pharma'=>array('_a_1_pharmadbadmin', '_a_2_pharmareception',  '_a_3_pharmaorder'),
        //
        //'depot_db'=>array('_a_1_meddepotdbadmin'),
        //
        //'depot_receive'=>array('_a_1_meddepotdbadmin', '_a_2_meddepotreception'),
        //
        //'depot'=>array('_a_1_meddepotdbadmin', '_a_2_meddepotreception', '_a_3_meddepotorder'),
        
        //Khóa toàn bộ không cho vô module nào
        'edp'=>array('no_allow_type_all'),
        //Tin tức
        'news'=>array('_a_1_newsallwrite', '_a_2_newsallmoderatedwrite', '_a_2_photoread'),

        //'cafenews'=>array('_a_1_newsallwrite', '_a_2_newscafewrite'),
        //'op_docs'=>array('_a_1_opdoctorallwrite'),
        //'duty_op'=>array('_a_1_opnursedutyplanwrite'),
        //'fotolab'=>array('_a_1_photowrite'),
        //Toàn quyền Chẩn đoán, hội chẩn
        'test_diagnose'=>array('_a_1_diagnosticsresultwrite', '_a_2_labresultsread'),
        //Xem và nhận yêu cầu Chẩn đoán, hội chẩn
        'test_receive'=>array( '_a_2_labresultsread', '_a_2_diagnosticsreceptionwrite'),
        //Chỉ được xem và đưa ra yêu cầu Chẩn đoán, hội chẩn
        'test_order'=>array('_a_1_labresultswrite', '_a_3_diagnosticsrequest'),
        //xem tất cả
        'admin'=>array($all, $sysadmin)
    )
    */

?>
