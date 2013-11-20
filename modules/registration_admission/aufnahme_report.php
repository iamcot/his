<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',"Báo cáo thống kê");

 $smarty->assign('breakfile',$breakfile);


 # Window bar title
 $smarty->assign('title','Báo cáo thống kê');
 $smarty->assign('aTKKNgT','<a href="javascript:opendiv5(\'kngtru\')">Thống kê khám bệnh</a>');
 $smarty->assign('aKKB','<a href="javascript:opendiv(\'kkb\')">Khoa Khám Bệnh</a>');
 $smarty->assign('aDTNT','<a href="javascript:opendiv(\'dtnt\')">Điều trị nội trú</a>');
 $smarty->assign('aDTALL','<a href="javascript:opendiv(\'all\')">Toàn bệnh viện (Biểu 15-BCH)</a>');
 $smarty->assign('aVaoKhoa','<a href="javascript:opendiv2(\'vaokhoa\')">TK BN Vào Khoa</a>');
 $smarty->assign('aRaKhoa','<a href="javascript:opendiv2(\'rakhoa\')">TK BN Ra Khoa</a>');
 $smarty->assign('aVaoVien','<a href="javascript:opendiv2(\'vaovien\')">TK BN Vào Viện</a>');
 $smarty->assign('aRaVien','<a href="javascript:opendiv2(\'ravien\')">TK BN Ra Viện</a>');
 $smarty->assign('aChuyenVien','<a href="javascript:opendiv2(\'chuyenvien\')">TK BN Chuyển Viện</a>');
 $smarty->assign('aDieutrinoitru','<a href="javascript:opendiv3(\'dieutrinoitru\')">TK Điều trị nội trú</a>');
 $smarty->assign('ab031dt','<a href="javascript:opendiv3(\'b031dt\')">Hoạt động điều trị (Biểu 03.1-ĐT)</a>');
 $smarty->assign('ab05skss','<a href="javascript:opendiv3(\'b05skss\')">Sức khỏe sinh sản(Biểu 05-SKSS)</a>');
 $smarty->assign('ab06cls','<a href="javascript:opendiv3(\'b06cls\')">Cận Lâm Sàng (Biểu 06-CLS)</a>');
 $smarty->assign('aKhamsuckhoe','<a href="javascript:opendiv3(\'tkksk\')">Thống kê khám sức khỏe</a>');
 $smarty->assign('aTKYHCTday','<a href="javascript:opendiv3(\'tkyhct\')">Thống kê Khoa YHCT</a>');
 $smarty->assign('aTKKBday','<a href="javascript:opendiv3(\'tkkb\')">Thống kê Khoa Khám bệnh</a>');
 $smarty->assign('aTKHSCCday','<a href="javascript:opendiv3(\'tkhscc\')">Thống kê Khám bệnh Khoa HSCC</a>');
 $smarty->assign('aBCTUKSAN','<a href="javascript:opendiv3(\'bctuks\')">Báo cáo tuần Khoa Sản</a>');
 $smarty->assign('aBCTKSAN','<a href="javascript:opendiv4(\'bctks\')">Báo cáo tháng Khoa Sản</a>');
 $smarty->assign('aBM07KSAN','<a href="javascript:opendiv4(\'bm07ks\')">Thống kê khám chữa phụ khoa và nạo phá thai</a>');

 ob_start();
 ?>
 <link type="text/css" rel="stylesheet" href="<?php echo  $root_path;?>js/cssjquery/jquery-ui-1.7.2.custom.css" />
 <script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
<script src="<?php echo $root_path;?>js/jquery-ui-1.7.2.custom.min.js"></script>

 <script type="text/javascript">
 function opendiv(id){
 	$("#contentright").load("<? echo $root_path?>modules/registration_admission/admitreport_bttv_kkb.php?id="+id);

 }
 function opendiv2(id){
 	$("#contentright").load("<? echo $root_path?>modules/registration_admission/admitreport_inout_dept.php?id="+id);

 }
 function opendiv3(id){
 	$("#contentright").load("<? echo $root_path?>modules/registration_admission/admitreport_bctk.php?id="+id);

 }
 function opendiv4(id){
 	$("#contentright").load("<? echo $root_path?>modules/registration_admission/admitreport_bctk_t.php?id="+id);
 }
 function opendiv5(id){
    $("#contentright").load("<? echo $root_path?>modules/registration_admission/admitreport_tkkhoa.php?id="+id);
 }
 function viewreporkngtru(id){
    var dept = $("#deptselect").val();
    url = 'admitreport_kngtru.php';
        urlholder="<?php echo $root_path ?>modules/registration_admission/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"&dept="+dept;
        testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
        return true;
 }
 function viewreportinout(id){
 	if($('#datefrom').val() =="" || $('#dateto').val()=="") {
 		alert("Vui lòng nhập đủ ngày bắt đầu và kết thúc"); 
 		return;
 	}
 	var dept = $("#deptselect").val();
 	var url="admission/report_inout_dept.php";
 	urlholder="<?php echo $root_path ?>modules/pdfmaker/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"&dept="+dept;
    testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
 }
 function viewreportbttv(id){
 	if($('#datefrom').val() =="" || $('#dateto').val()=="") {
 		alert("Vui lòng nhập đủ ngày bắt đầu và kết thúc"); 
 		return;
 	}
 	var url="admission/report_bttv.php";
 	urlholder="<?php echo $root_path ?>modules/pdfmaker/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"";
    testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
 }
 function viewreportk(id){
	if($('#datefrom').val() =="" || $('#dateto').val()=="") {
            alert("Vui lòng nhập đủ ngày bắt đầu và kết thúc"); 
            return;
 	}
        if($('#datefrom').val()>$('#dateto').val()){
            alert("Ngày trước lớn hơn ngày sau. Vui lòng chọn lại");
            return;
        }
 	var url= "";
 	if(id == 'dieutrinoitru')
 		url="admission/report_bctk.php";
 	else if(id == 'b031dt')
 		url='admission/report_b031dt.php';
 	else if(id == 'tkksk')
 		url = 'admission/report_tkksk.php';
 	else if(id=='tkyhct')
 		{
 			url = 'admitreport_yhct.php';
 			urlholder="<?php echo $root_path ?>modules/registration_admission/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"";
    		testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
 			return true;
 	}
 	else if(id == 'tkkb'){
 		url = 'admitreport_kkb.php';
 		urlholder="<?php echo $root_path ?>modules/registration_admission/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"";
    	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
 		return true;
 	}
 	else if(id == 'tkhscc'){
 		url = 'admitreport_hscc.php';
 		urlholder="<?php echo $root_path ?>modules/registration_admission/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"";
    	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
 		return true;
 	}
        else if(id == 'bctuks'){
            var d = document.getElementById('report');
            url = 'thongketuan.php';
            urlholder = "<?php echo $root_path ?>modules/pdfmaker/khoasan/"+url+"<?php echo URL_REDIRECT_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&dept_nr=7";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
            return true;
        }
        else if(id == 'bctks'){
            var d = document.getElementById('report');
            url = 'thongkethang.php';
            urlholder = "<?php echo $root_path ?>modules/pdfmaker/khoasan/"+url+"<?php echo URL_REDIRECT_APPEND ?>&month="+d.month.value+"&year="+d.year.value+"&dept_nr=7";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
            return true;
        }
        else if(id == 'bm07ks'){
            var d = document.getElementById('report');
            url = 'BM07_PHUKHOA_NAOPHATHAI.php';
            urlholder = "<?php echo $root_path ?>modules/pdfmaker/khoasan/"+url+"<?php echo URL_REDIRECT_APPEND ?>&month="+d.month.value+"&year="+d.year.value+"&dept_nr=7";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
            return true;
        }
 	urlholder="<?php echo $root_path ?>modules/pdfmaker/"+url+"<?php echo URL_APPEND ?>&datefrom="+$('#datefrom').val()+"&dateto="+$('#dateto').val()+"&id="+id+"";
    testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
 }
 </script>
 <?
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('script',$sTemp);

$target='report';
$parent_admit = TRUE;
include('./gui_bridge/default/gui_tabs_patadmit.php');

# Stop buffering, assign contents and display template
$smarty->assign('sMainIncludeFile','registration_admission/admit_report_main.tpl');

$smarty->assign('sMainBlockIncludeFile','registration_admission/admit_plain.tpl');

$smarty->display('common/mainframe.tpl');