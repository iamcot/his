<?php
    if($date && $time){
        $cond="AND date='$date' AND time='$time'";
    }
    $noichuyenden=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=68");
    if($noichuyenden){
        $noichuyenden_text=$noichuyenden->FetchRow();
    }
    $phuongtien=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=69");
    if($phuongtien){
        $phuongtien_text=$phuongtien->FetchRow();
    }
    $name_ndden=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=70");
    if($name_ndden){
        $name_ndden_text=$name_ndden->FetchRow();
    }
//    $tuoi_ndden=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=71");
//    if($tuoi_ndden){
//        $tuoi_ndden_text=$tuoi_ndden->FetchRow();
//    }
//    $gioitinh_ndden=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=72");
//    if($gioitinh_ndden){
//        $gioitinh_ndden_text=$gioitinh_ndden->FetchRow();
//    }
    $diachi_ndden=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=73");
    if($diachi_ndden){
        $diachi_ndden_text=$diachi_ndden->FetchRow();
    }
    $lienhe_ndden=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=74");
    if($lienhe_ndden){
        $lienhe_ndden_text=$lienhe_ndden->FetchRow();
    }
    $ppdt=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=75");
    if($ppdt){
        $ppdt_text=$ppdt->FetchRow();
    }
    $time_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=76");
    if($time_tv){
        $time_tv_text=$time_tv->FetchRow();
    }
    $trigiac=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=77");
    if($trigiac){
        $trigiac_text=$trigiac->FetchRow();
    }
    $daniem=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=78");
    if($daniem){
        $daniem_text=$daniem->FetchRow();
    }
    $dongtu=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=79");
    if($dongtu){
        $dongtu_text=$dongtu->FetchRow();
    }
    $timmach=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=80");
    if($timmach){
        $timmach_text=$timmach->FetchRow();
    }
    $hohap=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=81");
    if($hohap){
        $hohap_text=$hohap->FetchRow();
    }
    $thuongton=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=82");
    if($thuongton){
        $thuongton_text=$thuongton->FetchRow();
    }
    $capcuu=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=83");
    if($capcuu){
        $capcuu_text=$capcuu->FetchRow();
    }
    $canthiep=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=84");
    if($canthiep){
        $canthiep_text=$canthiep->FetchRow();
    }
    $name_nxve=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=85");
    if($name_nxve){
        $name_nxve_text=$name_nxve->FetchRow();
    }
    $tuoi_nxve=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=86");
    if($tuoi_nxve){
        $tuoi_nxve_text=$tuoi_nxve->FetchRow();
    }
    $gioitinh_nxve=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=87");
    if($gioitinh_nxve){
        $gioitinh_nxve_text=$gioitinh_nxve->FetchRow();
    }
    $lienhe_nxve=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=88");
    if($lienhe_nxve){
        $lienhe_nxve_text=$lienhe_nxve->FetchRow();
    }
    $benhsu=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=89");
    if($benhsu){
        $benhsu_text=$benhsu->FetchRow();
    }
    $ngaytv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=90");
    if($ngaytv){
        $ngaytv_text=$ngaytv->FetchRow();
    }
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_HINHTHUC=$LDHinhthuc['0'];
    $hinhthuc=explode("@",$noichuyenden_text['notes']);
    $TP_INPUT_HINHTHUC="<input type='radio' name='hinhthuc' id='hinhthuc' value='1' ";
    if($hinhthuc['0']==1){
        $TP_INPUT_HINHTHUC.="checked";
    }
    $TP_INPUT_HINHTHUC.="/>".$LDHinhthuc['1'];
    $TP_INPUT_HINHTHUC.="&nbsp;&nbsp;<input type='radio' name='hinhthuc' id='hinhthuc' value='2' ";
    if($hinhthuc['0']==2 || $hinhthuc['0']==''){
        $TP_INPUT_HINHTHUC.="checked";
    }
    $TP_INPUT_HINHTHUC.="/>".$LDHinhthuc['2'];    
    $TP_INPUT_HINHTHUC.="<br/><br/>
                        <FONT SIZE=-1  FACE='Arial' color='#000066'>
                            <b>
                                Ghi chú thêm
                            </b>
                        </FONT><br/><input type='text' name='hinhthuc_text' id='hinhthuc_text' size='78' value='";
    if($hinhthuc['1']!=''){
        $TP_INPUT_HINHTHUC.=$hinhthuc['1'];
    }
    $TP_INPUT_HINHTHUC.="'/>";
    
    $TP_VANCHUYEN=$LDPhuongtienden['0'];
    $xeduaden=explode("@", $phuongtien_text['notes']);
    $TP_INPUT_VANCHUYEN="<input type='radio' name='vanchuyen' id='vanchuyen' value='1' ";
    if($xeduaden['0']==1){
        $TP_INPUT_VANCHUYEN.="checked";
    }
    $TP_INPUT_VANCHUYEN.="/>".$LDPhuongtienden['1'];
    $TP_INPUT_VANCHUYEN.="&nbsp;&nbsp;<input type='radio' name='vanchuyen' id='vanchuyen' value='2' ";
    if($xeduaden['0']==2){
        $TP_INPUT_VANCHUYEN.="checked";
    }
    $TP_INPUT_VANCHUYEN.="/>".$LDPhuongtienden['2'];
    $TP_INPUT_VANCHUYEN.="&nbsp;&nbsp;<input type='radio' name='vanchuyen' id='vanchuyen' value='3' ";
    if($xeduaden['0']==3 || $xeduaden['0']==''){
        $TP_INPUT_VANCHUYEN.="checked";
    }
    $TP_INPUT_VANCHUYEN.="/>".$LDPhuongtienden['3'];
    
    $TP_SOXE=$LDPhuongtienden['4'];
    $TP_INPUT_SOXE="<input type='text' name='soxe' id='soxe' size='78' maxlength='11' value='";
    if($xeduaden['1']!=''){
        $TP_INPUT_SOXE.=$xeduaden['1'];
    }
    $TP_INPUT_SOXE.="'/>";
    
    $TP_NGUOIDUADEN=$LDNguoiduaden;
    $TP_INPUT_NGUOIDUADEN="<input type='text' name='nguoiduaden' id='nguoiduaden' size='78' value='";
    if($name_ndden_text['notes']!=''){
        $TP_INPUT_NGUOIDUADEN.=$name_ndden_text['notes'];
    }
    $TP_INPUT_NGUOIDUADEN.="'/>";
//    $TP_TUOI=$LDTuoi;
//    $TP_INPUT_TUOI="<input type='text' name='tuoindden' id='tuoindden' size='6' maxlength='6' value='";
//    if($tuoi_ndden_text['notes']!=''){
//        $TP_INPUT_TUOI.=$tuoi_ndden_text['notes'];
//    }
//    $TP_INPUT_TUOI.="' onChange='checkvalue_number(this.value,".'"tuoindden"'.")'/>";
//    $TP_GIOITINH=$LDSex;
//    $TP_INPUT_GIOITINH="<input type='radio' name='gioitinhndden' id='gioitinhndden' value='1' ";
//    if($gioitinh_ndden_text['notes']==1){
//        $TP_INPUT_GIOITINH.="checked";
//    }
//    $TP_INPUT_GIOITINH.="/>".$LDMale;
//    $TP_INPUT_GIOITINH.="&nbsp;&nbsp;<input type='radio' name='gioitinhndden' id='gioitinhndden' value='2' ";
//    if($gioitinh_ndden_text['notes']==2){
//        $TP_INPUT_GIOITINH.="checked";
//    }
//    $TP_INPUT_GIOITINH.="/>".$LDFemale; 
//    $TP_INPUT_GIOITINH.="&nbsp;&nbsp;<input type='radio' name='gioitinhndden' id='gioitinhndden' value='0' ";
//    if($gioitinh_ndden_text['notes']==0 || $gioitinh_ndden_text['notes']==''){
//        $TP_INPUT_GIOITINH.="checked";
//    }
//    $TP_INPUT_GIOITINH.="/>".$LDNoknown;
    $TP_DIACHI=$LDAddress;
    $TP_INPUT_DIACHI="<input type='text' name='diachindden' id='diachindden' size='78' value='";
    if($diachi_ndden_text['notes']!=''){
        $TP_INPUT_DIACHI.=$diachi_ndden_text['notes'];
    }
    $TP_INPUT_DIACHI.="'/>";    
    $TP_LIENHE=$LDTel;
    $TP_INPUT_LIENHE="<input type='text' name='lienhendden' id='lienhendden' size='78' maxlength='11' value='";
    if($lienhe_ndden_text['notes']!=''){
        $TP_INPUT_LIENHE.=$lienhe_ndden_text['notes'];
    }
    $TP_INPUT_LIENHE.="'/>";
    
    $TP_BENHSU=$LDBenhsu;
    $benhsu=explode("@",$benhsu_text['notes']);
    $TP_INPUT_BENHSU="<textarea name='benhsu' id='benhsu' cols='58' rows='3'>";
    if($benhsu[0]!=''){
        $TP_INPUT_BENHSU.=$benhsu[0];
    }
    $TP_INPUT_BENHSU.="</textarea>";
    $TP_PHUONGPHAP=$LDPhuongphapdttruoc['0'];
    $TP_INPUT_PHUONGPHAP="<textarea name='ppdttruoc' id='ppdttruoc' cols='58' rows='3'>";
    $ppdt=explode('@',$ppdt_text['notes']);
    if($ppdt['0']!=''){
        $TP_INPUT_PHUONGPHAP.=$ppdt['0'];
    }
    $TP_INPUT_PHUONGPHAP.="</textarea>";
//    $pp=explode("@",$ppdt_text['notes']);
//    $TP_INPUT_PHUONGPHAP="<input type='radio' name='ppdttruoc' id='ppdttruoc' value='1' ";
//    if($pp['0']==1){
//        $TP_INPUT_PHUONGPHAP.="checked";
//    }
//    $TP_INPUT_PHUONGPHAP.="/>".$LDPhuongphapdttruoc[1];
//    $TP_INPUT_PHUONGPHAP.="&nbsp;&nbsp;<input type='radio' name='ppdttruoc' id='ppdttruoc' value='2' ";
//    if($ppdt_text['notes']==2 || $ppdt_text['notes']==''){
//        $TP_INPUT_PHUONGPHAP.="checked";
//    }
//    $TP_INPUT_PHUONGPHAP.="/>".$LDPhuongphapdttruoc[2];
//    $TP_INPUT_PHUONGPHAP.="&nbsp;&nbsp;<input type='radio' name='ppdttruoc' id='ppdttruoc' value='3' ";
//    if($ppdt_text['notes']==3){
//        $TP_INPUT_PHUONGPHAP.="checked";
//    }
//    $TP_INPUT_PHUONGPHAP.="/>".$LDPhuongphapdttruoc[3];
//    $TP_INPUT_PHUONGPHAP.="&nbsp;&nbsp;<input type='radio' name='ppdttruoc' id='ppdttruoc' value='4' ";
//    if($ppdt_text['notes']==4){
//        $TP_INPUT_PHUONGPHAP.="checked";
//    }
//    $TP_INPUT_PHUONGPHAP.="/>".$LDPhuongphapdttruoc[4];
    $TP_THOIGIANBNTV=$LDBenhNhantuvong;
    $TP_INPUT_TIME="<input type='text' name='giotv' id='giotv' size='5' maxlength='5' value='";
    if($time_tv_text['notes']!=''){
        $TP_INPUT_TIME.=$time_tv_text['notes'];
    }
    $TP_INPUT_TIME.="'/>";
    $TP_NGAYMAT=$LDDate;
    if($ngaytv_text['notes']){
        $TP_NGAYMAT_INPUT = $calendar->show_calendar($calendar,$date_format,'ngaytv',$ngaytv_text['notes']);
    }else{
        $TP_NGAYMAT_INPUT = $calendar->show_calendar($calendar,$date_format,'ngaytv',date('Y-m-d'));
    } 
    $LDnhanxet='Nhận xét trên thi thể';
    $TP_TINHTRANG=$LDnhanxet;
    $TP_INPUT_TINHTRANG="<textarea name='tinhtrang' id='tinhtrang' cols='58' rows='3'>";
    if($ppdt['1']!=''){
        $TP_INPUT_TINHTRANG.=$ppdt['1'];
    }
    $TP_INPUT_TINHTRANG.="</textarea>";
//    $TP_TRIGIAC=$LDKham[0];
//    $TP_INPUT_TRIGIAC="<input type='text' name='trigiac' id='trigiac' size='30' value='";
//    if($trigiac_text['notes']!=''){
//        $TP_INPUT_TRIGIAC.=$trigiac_text['notes'];
//    }
//    $TP_INPUT_TRIGIAC.="'/>";
//    $TP_DANIEM=$LDKham[1];
//    $TP_INPUT_DANIEM="<input type='text' name='daniem' id='daniem' size='30' value='";
//    if($daniem_text['notes']!=''){
//        $TP_INPUT_DANIEM.=$daniem_text['notes'];
//    }
//    $TP_INPUT_DANIEM.="'/>";
//    $TP_DONGTU=$LDKham[2];
//    $TP_INPUT_DONGTU="<input type='text' name='dongtu' id='dongtu' size='30' value='";
//    if($dongtu_text['notes']!=''){
//        $TP_INPUT_DONGTU.=$dongtu_text['notes'];
//    }
//    $TP_INPUT_DONGTU.="'/>";
//    $TP_TIMMACH=$LDKham[6];
//    $TP_INPUT_TIMMACH="<input type='text' name='timmach' id='timmach' size='30' value='";
//    if($timmach_text['notes']!=''){
//        $TP_INPUT_TIMMACH.=$timmach_text['notes'];
//    }
//    $TP_INPUT_TIMMACH.="'/>";
//    $TP_HOHAP=$LDKham[7];
//    $TP_INPUT_HOHAP="<input type='text' name='hohap' id='hohap' size='78' value='";
//    if($hohap_text['notes']!=''){
//        $TP_INPUT_HOHAP.=$hohap_text['notes'];
//    }
//    $TP_INPUT_HOHAP.="'/>";
    $TP_BENHCHINH=$LDDiagnosis;
    $TP_INPUT_BENHCHINH="<textarea name='benhchinh' id='benhchinh' cols='58' rows='2'>";
    if($thuongton_text['notes']!=''){
        $TP_INPUT_BENHCHINH.=$thuongton_text['notes'];
    }
    $TP_INPUT_BENHCHINH.="</textarea>";
    
    $TP_CAPCUU=$LDKham[10];
    $TP_INPUT_CAPCUU="<textarea name='capcuu' id='capcuu' cols='58' rows='3'/>";
    if($capcuu_text['notes']!=''){
        $TP_INPUT_CAPCUU.=$capcuu_text['notes'];
    }
    $TP_INPUT_CAPCUU.="</textarea>";
    $TP_CANTHIEP='Giữ xác lại';
    $TP_INPUT_CANTHIEP="<input type='text' name='canthiep' id='canthiep' size=78' value='";
    if($canthiep_text['notes']!=''){
        $TP_INPUT_CANTHIEP.=$canthiep_text['notes'];
    }
    $TP_INPUT_CANTHIEP.="' onchange='javascript:set_disable(this.value,".'"canthiep"'.");'/>";
    
    $TP_NGUOIXIN=$LDGiaiquyet['2'];
    $TP_INPUT_NGUOIXIN="<input type='text' name='nguoixin' id='nguoixin' size=78' value='";
    if($name_nxve_text['notes']!=''){
        $TP_INPUT_NGUOIXIN.=$name_nxve_text['notes'];
    }
    $TP_INPUT_NGUOIXIN.="' onchange='javascript:set_disable(this.value,".'"nguoixin"'.");'/>";
//    $TP_TUOI_NX=$LDTuoi;
//    $TP_INPUT_TUOI_NX="<input type='text' name='tuoinxin' id='tuoinxin' size='6' maxlength='6' value='";
//    if($tuoi_nxve_text['notes']!=''){
//        $TP_INPUT_TUOI_NX.=$tuoi_nxve_text['notes'];
//    }
//    $TP_INPUT_TUOI_NX.="' onChange='checkvalue_number(this.value,".'"tuoinxin"'.")' />";
//    $TP_GIOITINH_NX=$LDSex;
//    $TP_INPUT_GIOITINH_NX="<input type='radio' name='gioitinhnxin' id='gioitinhnxin' value='1' ";
//    if($gioitinh_nxve_text['notes']==1){
//        $TP_INPUT_GIOITINH_NX.="checked";
//    }
//    $TP_INPUT_GIOITINH_NX.="/>".$LDMale;
//    $TP_INPUT_GIOITINH_NX.="&nbsp;&nbsp;<input type='radio' name='gioitinhnxin' id='gioitinhnxin' value='2' ";
//    if($gioitinh_nxve_text['notes']==2){
//        $TP_INPUT_GIOITINH_NX.="checked";
//    }
//    $TP_INPUT_GIOITINH_NX.="/>".$LDFemale;
//    $TP_INPUT_GIOITINH_NX.="&nbsp;&nbsp;<input type='radio' name='gioitinhnxin' id='gioitinhnxin' value='3' ";
//    if($gioitinh_nxve_text['notes']==3){
//        $TP_INPUT_GIOITINH_NX.="checked";
//    }
//    $TP_INPUT_GIOITINH_NX.="/>".$LDNoknown;
    $TP_LIENHE_NX=$LDAddress;
    $TP_INPUT_LIENHE_NX="<input type='text' name='lienhenxin' id='lienhenxin' size='78' value='";
    if($lienhe_nxve_text['notes']!=''){
        $TP_INPUT_LIENHE_NX.=$lienhe_nxve_text['notes'];
    }
    $TP_INPUT_LIENHE_NX.="' onchange='javascript:set_disable(this.value,".'"lienhenxin"'.");'/>";
    $LDTaisan='Tài sản của BN bao gồm: ';
    $TP_TAISAN=$LDTaisan;
    $TP_INPUT_TAISAN="<textarea name='taisan' id='taisan' cols='58' rows='3'/>";
    if($benhsu['1']!=''){
        $TP_INPUT_TAISAN.=$benhsu['1'];
    }
    $TP_INPUT_TAISAN.="</textarea>";
    
    $TP_DOCBY=$LDDocBy;  
    if($time_tv_text['create_id']!='' && $time_tv_text['modify_id']==''){
        $TP_DBY=$time_tv_text['create_id'];
    }else if($time_tv_text['create_id']!='' && $time_tv_text['modify_id']!=''){
        $TP_DBY=$time_tv_text['modify_id'];
    }else{
        $TP_DBY=$_SESSION['sess_user_name'];
    }    
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_benhantuvong.htm');
    eval("echo $tp_preg;");
?>
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo trim($target); ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="hidden" name="nr" value="<?php echo $nr;?>" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />
</form>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script language="JavaScript">    
//    function chkform() {   
//        var d = document.getElementById('report');
//        if(d.soxe.value==""){
//            alert("<?php echo $LDAlertPhuongtien; ?>");
//            d.soxe.focus();
//            return false;
//        }else if(d.nguoiduaden.value==""){
//            alert("<?php echo $LDAlertNguoiduaden; ?>");
//            d.nguoiduaden.focus();
//            return false;
//        }else if(d.tuoindden.value==""){
//            alert("<?php echo $LDAlertTuoindden; ?>");
//            d.tuoindden.focus();
//            return false;
//        }else if(d.giotv.value.length<5){
//            alert("<?php echo $LDAlertTimeTv; ?>");
//            d.giotv.focus();
//            return false;
//        }else if(d.benhsu.value==""){
//            alert("<?php echo $LDAlertBenhsu; ?>");
//            d.benhsu.focus();
//            return false;
//        }else if(d.nguoixin.value=="" && d.tuoinxin.value!=""){
//            alert("<?php echo $LDAlertNguoixinve; ?>");
//            d.nguoixin.focus();
//            return false;
//        }else if(d.nguoixin.value!="" && d.tuoinxin.value==""){
//            alert("<?php echo $LDAlertNguoixinve; ?>");
//            d.tuoinxin.focus();
//            return false;
//        }
//    }
    
    function set_disable(value,id){
        if(value!='' && id=='canthiep'){
            $("#nguoixin").val('');
            $("#lienhenxin").val('');
        }else{
            $("#canthiep").val('');
        }
    }
    
    $(function(){
        $("#giotv").mask("**:**");
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>
