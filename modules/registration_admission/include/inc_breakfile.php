<?php
if($_SESSION['sess_user_origin']=='admission') {
	$breakfile=$root_path.'modules/registration_admission/aufnahme_daten_zeigen.php'.URL_APPEND.'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target;
}elseif($_SESSION['sess_user_origin']=='registration'){
	$breakfile=$root_path.'modules/registration_admission/show_admit_general.php'.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target;
}elseif($_COOKIE['ck_login_logged'.$sid]){
	$breakfile=$root_path.'main/startframe.php';
}else{
	$breakfile='medocs_pass.php';
}
if(isset($flag)){
    switch ($flag){
        case 1:
        case 2:
        case 3:
            $breakfile=$root_path.'modules/registration_admission/show_admit_general.php'.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&flag=KS';
            break;
        default:
            $breakfile=$root_path.'modules/registration_admission/show_admit_general.php'.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target;
            break;
    }
}

# Patch for break urls that have lang param already

if(!stristr($breakfile,'lang=')) $breakfile.=URL_APPEND;
?>
