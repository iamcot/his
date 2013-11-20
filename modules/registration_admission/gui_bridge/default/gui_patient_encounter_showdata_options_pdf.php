<script language="javascript" >
<!-- 
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
function printbant(){
	urlholder="<?php echo $root_path;?>modules/pdfmaker/benhangoaitru/BenhAnNgoaiTru.php<?php echo URL_APPEND; ?>&enc=<?php echo $_SESSION['sess_en'];?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
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
//-->
</script>
<?php
# Let us detect if data entry is allowed
	//echo $enc_status['is_disharged'].'<p>'. $enc_status['encounter_status'].'<p>d= '. $enc_status['in_dept'].'<p>w= '. $enc_status['in_ward'];
/*	if($enc_status['is_disharged']){
		if(stristr('cancelled',$enc_status['encounter_status'])){
			$data_entry=false;
		}
	}elseif(!$enc_status['encounter_status']||stristr('cancelled',$enc_status['encounter_status'])){
		if(!$enc_status['in_ward']&&!$enc_status['in_dept']) $data_entry=false;
	}
*/
if(!$is_discharged&&!$enc_status['in_ward']&&!$enc_status['in_dept']&&(!$enc_status['encounter_status']||stristr('cancelled',$enc_status['encounter_status']))){
//if(!$enc_status['is_discharged']&&!$enc_status['in_ward']&&!$enc_status['in_dept']&&(!$enc_status['encounter_status']||stristr('cancelled',$enc_status['encounter_status']))){
	$data_entry=false;
}else{
	$data_entry=true;
}


# Create the template object
if(!is_object($TP_obj)){
	include_once($root_path.'include/care_api_classes/class_template.php');
	$TP_obj=new Template($root_path);
}

$TP_href_1="show_sick_confirm.php".URL_APPEND ."&pid=$pid&target=$target";
$TP_SICKCONFIRM="<a href=\"javascript:printbant()\">$LDBenhAnNgoaiTru</a>";

$TP_HISTORY="<a href=\"".$root_path."modules/registration_admission/aufnahme_start_cntt.php".URL_APPEND."&encounter_nr=".$_SESSION['sess_en']."&update=1&target=search\">$LDChungNhanThuongTich</a>";



# Load the template
$TP_options=$TP_obj->load('registration_admission/tp_pat_admit_options_pdf.htm');
eval("echo $TP_options;");
?>