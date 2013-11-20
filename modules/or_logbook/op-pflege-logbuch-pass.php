<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    define('LANG_FILE','stdpass.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    require_once($root_path.'global_conf/areas_allow.php');
    /* Set the allowed area basing on the target */
    if($_SESSION['sess_login_username']!='admin'){
        require_once($root_path.'include/care_api_classes/class_access.php');
        $access= & new Access();
        $role= $access->checkNameRole($_SESSION['sess_login_username']);
        if((strpos($role['role_name'], 'Trưởng khoa')!='' || strpos($role['role_name'], 'Trưởng khoa')==0) && ( strpos($role['dept_nr'], '"'.$dept_nr.'"')!='' || $role['location_nr']==$dept_nr)) 
            $allowedarea=&$allow_area['dean_op'];
    }else{
        $allowedarea=&$allow_area['admin'];
    }
	
    if($retpath=='calendar_opt'){
            $append=URL_APPEND."&dept_nr=$dept_nr&retpath=$retpath&pday=$pday&pmonth=$pmonth&pyear=$pyear";
            $breakfile=$root_path."modules/calendar/calendar-options.php".URL_APPEND."&dept_nr=$dept_nr&ward_nr=$ward_nr&retpath=$retpath&day=$pday&month=$pmonth&year=$pyear";
    }else{
            $append=URL_APPEND; 
            $breakfile=$root_path."main/op-doku_1.php".URL_APPEND;
    }

    if(!isset($dept_nr)) $dept_nr='';

    switch($target)
    {
        case 'e_kip':$fileforward="op-pflege-logbuch-javastart_1.php".URL_REDIRECT_APPEND."&dept_nr=$dept_nr&ward_nr=$ward_nr&batch_nr=$batch_nr&enc_nr=$enc_nr&mode=$mode";
                                $target="entry";
                                $title=$LDNewData;
        default:
            //// edit-6/12-Huỳnh
            //mở cửa sổ lập ê-kíp mổ
                $fileforward="op-pflege-logbuch-javastart_1.php".URL_REDIRECT_APPEND."&dept_nr=$dept_nr&ward_nr=$ward_nr&batch_nr=$batch_nr&mode=$mode&enc_nr=$enc_nr";
                $target="entry";
                $title=$LDNewData;
    }
    $thisfile=basename(__FILE__);

    $lognote="OP Logs $title ok";

    $userck='ck_op_pflegelogbuch_user';
    //reset cookie;
    // reset all 2nd level lock cookies
    setcookie($userck.$sid,'');
    require($root_path.'include/core/inc_2level_reset.php'); 
    setcookie(ck_2level_sid.$sid,'');

    require($root_path.'include/core/inc_passcheck_internchk.php');
    if ($pass=='check') 	
            include($root_path.'include/core/inc_passcheck.php');

    $errbuf="OP Logs $title";

    require($root_path.'include/core/inc_passcheck_head.php');
?>

<BODY 
    <?php if (!$nofocus)
                { echo 'onLoad="document.passwindow.userid.focus();';
                        if($retpath=="calendar_opt") echo "window.resizeTo(800,600);window.moveTo(20,20);";
                        echo '"';
                }
                echo  ' bgcolor='.$cfg['body_bgcolor']; 
                if (!$cfg['dhtml']){ echo ' link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } 
    ?>
>
    <FONT    SIZE=-1  FACE="Arial">
        <p>
        <img <?php echo createComIcon($root_path,'people.gif','0','absmiddle') ?>/>
        <font  COLOR="<?php echo $cfg[top_txtcolor] ?>"  SIZE=5  FACE="verdana" >
            <b><?php echo "$LDOrLogBook $title" ?></b>
        </font>
        <!--    Khung hiển thị không có quyền truy xuất-->
        <table width=100% border=0 cellpadding="0" cellspacing="0"> 
        <?php require($root_path.'include/core/inc_passcheck_mask.php') ?>  
        <?php
            require($root_path.'include/core/inc_load_copyrite.php');
        ?>
        </table>
        </p>
    </FONT>
</BODY>
</HTML>
