<script language="javascript" >
    function openDRGComposite(){
        <?php if($cfg['dhtml'])
            echo '
                            w=window.parent.screen.width;
                            h=window.parent.screen.height;';
            else
            echo '
                            w=800;
                            h=650;';
        ?>

        drgcomp_<?php echo $_SESSION['sess_full_en']."_".$op_nr."_".$dept_nr."_".$saal ?>=window.open("<?php echo $root_path ?>modules/drg/drg-composite-start.php<?php echo URL_REDIRECT_APPEND."&display=composite&pn=".$_SESSION['sess_full_en']."&edit=$edit&is_discharged=$is_discharged&ln=$name_last&fn=$name_first&bd=$date_birth&dept_nr=$dept_nr&oprm=$saal"; ?>","drgcomp_<?php echo $encounter_nr."_".$op_nr."_".$dept_nr."_".$saal ?>","menubar=no,resizable=yes,scrollbars=yes, width=" + (w-15) + ", height=" + (h-60));
        window.drgcomp_<?php echo $_SESSION['sess_full_en']."_".$op_nr."_".$dept_nr."_".$saal ?>.moveTo(0,0);
    } 

    function getinfo(pn){
        <?php /* if($edit)*/
            { echo '
            urlholder="'.$root_path.'modules/nursing/nursing-station-patientdaten.php'.URL_REDIRECT_APPEND;
            echo '&pn=" + pn + "';
            echo "&pday=$pday&pmonth=$pmonth&pyear=$pyear&edit=$edit&station=$station"; 
            echo '";';
            echo '
            patientwin=window.open(urlholder,pn,"width=700,height=600,menubar=no,resizable=yes,scrollbars=yes");
            ';
            }
            /*else echo '
            window.location.href=\'nursing-station-pass.php'.URL_APPEND.'&rt=pflege&edit=1&station='.$station.'\'';*/
        ?>
    }
    function cancelEnc(){
        if(confirm("<?php echo $LDSureToCancel ?>")){
                usr=prompt("<?php echo $LDPlsEnterFullName ?>","");
                if(usr&&usr!=""){
                        pw=prompt("Please enter your password.","");
                        if(pw&&pw!=""){
                                window.location.href="aufnahme_cancel.php<?php echo URL_REDIRECT_APPEND ?>&mode=cancel&encounter_nr=<?php echo $_SESSION['sess_en'] ?>&cby="+usr+"&pw="+pw;
                        }
                }
        }
    }

    function printOut1(url) {
	urlholder="<?php echo $root_path ?>"+url+"<?php echo URL_APPEND ?>&pid=<?php echo $_SESSION['sess_pid']; ?>&enc_nr=<?php echo $_SESSION['sess_en'] ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
    
	function printOut2(url) {
	urlholder="<?php echo $root_path ?>"+url+"<?php echo URL_APPEND ?>&enc=<?php echo $_SESSION['sess_en'] ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
	
    function printkbvv(){
	urlholder="<?php echo $root_path;?>modules/pdfmaker/khambenh/PhieuKhamBenhVaoVien.php<?php echo URL_APPEND; ?>&enc=<?php echo $_SESSION['sess_en'];?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    }
	function printbamat(){
	urlholder="<?php echo $root_path;?>modules/pdfmaker/benhan/benhanmatnoi.php<?php echo URL_APPEND; ?>&enc=<?php echo $_SESSION['sess_en'];?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    }
	function printbanhi(){
	urlholder="<?php echo $root_path;?>modules/pdfmaker/benhan/benhannhi.php<?php echo URL_APPEND; ?>&enc=<?php echo $_SESSION['sess_en'];?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    }
	function printbanoi(){
	urlholder="<?php echo $root_path;?>modules/pdfmaker/benhan/Benhannoikhoa.php<?php echo URL_APPEND; ?>&enc=<?php echo $_SESSION['sess_en'];?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    }
    function printOP(url){
        urlholder="<?php echo $root_path;?>"+url;
        testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    }
//-->
</script>
<?php
if(!$is_discharged&&!$enc_status['in_ward']&&!$enc_status['in_dept']&&(!$enc_status['encounter_status']||stristr('cancelled',$enc_status['encounter_status']))){
	$data_entry=false;
}else{
	$data_entry=true;
}

include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
//Ma noi bo
$dept=$dept_obj->getAllDept('AND nr="'.$enc_status['current_dept_nr'].'"');
if($dept){
    $id_dept=$dept->FetchRow();
}

$sql="SELECT * FROM care_test_request_or as test,care_person as p,care_encounter as e WHERE test.encounter_nr=e.encounter_nr AND e.pid=p.pid AND p.pid=$pid ORDER BY batch_nr DESC";
$request=$db->SelectLimit($sql,1);
# Create the template object
if(!is_object($TP_obj)){
	include_once($root_path.'include/care_api_classes/class_template.php');
	$TP_obj=new Template($root_path);
}

# Assign the icons

if(file_exists($root_path.'gui/img/common/default/post_discussion.gif')){
	$TP_iconPost = '<img '.createComIcon($root_path,'comments.gif','0').'>';
}else{
	$TP_iconPost = '';
}

if($id_dept['id']==7 && $id_dept['id']==6 && $id_dept['id']==1 && $id_dept['id']==2 && $encounter_class_nr==1){
    $TP_BENHANPHUKHOA="<a href='javascript:printOut1(".'"modules/pdfmaker/khoasan/Benhanphukhoa.php"'.");'>$LDPrintBenhanPhukhoa</a>";
}else{
    $TP_BENHANPHUKHOA="<font color='#333333'>$LDPrintBenhanPhukhoa</font>";
}

if($data_entry){
	$TP_KDTUVONG="<a href=\"show_notes_kiemdiemtuvong.php".URL_APPEND."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">$LDOther1</a>";
        $TP_HOICHAN="<a href=\"show_notes_hoichan.php".URL_APPEND ."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">$LDConsultNotes1</a>";
        $TP_TUVONG="<a href='javascript:printOut1(".'"modules/pdfmaker/giaytokhac/GiayXacNhanBNTuVong.php"'.");'>$LDTuvong</a>";
        $TP_KHAMBENHVAOVIEN="<a href=\"javascript:printkbvv()\">$LDKhamBenhVaoVien</a>";
		$TP_BANGOAITRU="<a href='javascript:printOut2(".'"modules/pdfmaker/benhangoaitru/BenhAnNgoaiTru.php"'.");'>$LDBenhAnNgoaiTru</a>";
		$TP_CNTHUONGTIC="<a href=\"aufnahme_daten_zeigen_pdf.php".URL_APPEND ."&from=such&encounter_nr=".$_SESSION['sess_en']."&target=search\">$LDChungNhanThuongTich</a>";
        $TP_BENHANMAT="<a href=\"javascript:printbamat()\">Bệnh án mắt</a>";
        $TP_BENHANNHI="<a href=\"javascript:printbanhi()\">Bệnh án nhi</a>";
        $TP_BENHANNOIKHOA="<a href=\"javascript:printbanoi()\">Bệnh án nội khoa</a>";
        $TP_GIAYCAMDOAN="<a href=\"show_notes_camdoan.php".URL_APPEND."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">$LDGiayCamdoan</a>";
		$TP_GIAYRAVIEN="<a href=\"show_notes_ravien.php".URL_APPEND."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">$LDRavien</a>";
        $TP_CAMDOANPT="<a href=\"show_notes_camdoanPT.php".URL_APPEND."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">$LDCamDoanPT</a>";
        $TP_TUVONGTRUOC="<a href=\"show_notes_benhantuvong.php".URL_APPEND."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">$LDTuvongtruoc</a>"; 
        $TP_BAOTU="<a href=\"show_notes_baotu.php".URL_APPEND."&pid=$pid&pn=".$_SESSION['sess_en']."&target=$target\">Giấy báo tử";
        $TP_CHUYENVIEN="<a href='javascript:printOut1(".'"modules/nursing/nursing-station-patient-release.php'.URL_APPEND."&enc_nr=".$pn.'")'.";'>".$LDDischargeSummary1."</a>";

}else{
		$TP_KDTUVONG="<font color='#333333'>$LDOther1</font>";
        $TP_HOICHAN="<font color='#333333'>$LDConsultNotes1</font>";
        $TP_TUVONG="<font color='#333333'>$LDTuvong</font>";
        $TP_KHAMBENHVAOVIEN="<font color='#333333'>$LDKhamBenhVaoVien</font>";
		$TP_BANGOAITRU="$LDBenhAnNgoaiTru";
		$TP_CNTHUONGTIC="$LDChungNhanThuongTich";
        $TP_BENHANMAT="<font color='#333333'>Bệnh án mắt</font>";
        $TP_BENHANNHI="<font color='#333333'>Bệnh án nhi</font>";
        $TP_BENHANNOIKHOA="<font color='#333333'>Bệnh án nội khoa/font>";		
        $TP_CAMDOANPT="<font color='#333333'>$LDCamDoanPT</font>";
        $TP_TUVONGTRUOC="<font color='#333333'>$LDTuvongtruoc</font>";
        $TP_GIAYCAMDOAN="<font color='#333333'>$LDGiayCamdoan</font>";
		$TP_GIAYRAVIEN="<font color='#333333'>$LDRavien</font>";
        $TP_BAOTU="<font color='#333333'>Giấy báo tử</font>";
        $TP_CHUYENVIEN="<font color='#333333'>$LDDischargeSummary1</font>";
}
//if($enc_status['discharged_type']=='2' || $is_discharged){
//    $TP_CHUYENVIEN="<a href='javascript:printOut1(".'"modules/pdfmaker/chuyenvien/GiayChuyenVien.php'.URL_APPEND."&enc_nr=".$pn.'")'.";'>".$LDDischargeSummary1."</a>";
//}else{
//    $TP_CHUYENVIEN="<font color='#333333'>$LDDischargeSummary1</font>";
//}

if($batch=$request->FetchRow()){
    $TP_BAOMO="<a href='javascript:printOP(".'"modules/pdfmaker/emr_generic/report_op.php'.URL_APPEND."&enc=".$_SESSION['sess_en'].'&ses_en='.$_SESSION['sess_en'].'&batch_nr='.$batch['batch_nr'].'&subtarget=or")'.";'>".$LDRequestOP."</a>";
}else{
    $TP_BAOMO="<font color='#333333'>$LDRequestOP</font>";
}
if($encounter_class_nr!=1){
    $TP_XACNHANDTNGOAITRU="<a href='javascript:printOut1(".'"modules/pdfmaker/giaytokhac/GiayXacnhanDTngoaitru.php'.URL_APPEND."&enc_nr=".$pn.'")'.";'>".$LDXNDTNgoaitru."</a>";
}else{
    $TP_XACNHANDTNGOAITRU="<font color='#333333'>$LDXNDTNgoaitru</font>";
}
$TP_options=$TP_obj->load('registration_admission/tp_pat_admit_options_notes.htm');
eval("echo $TP_options;");
?>