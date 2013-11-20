<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
    * GNU General Public License
    * Copyright 2002,2003,2004,2005 Elpidio Latorilla
    * elpidio@care2x.org, 
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    define('LANG_FILE','or.php');
    $local_user='ck_op_pflegelogbuch_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    require_once($root_path.'include/core/inc_config_color.php'); // load color preferences
	
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet();?><TITLE></TITLE>

<script language="javascript">
    function makelogbuch()
    {
        <?php 
            if($cfg['dhtml'])
            echo '
            w=window.parent.screen.width;
            h=window.parent.screen.height;';
            else
            echo '
            w=800;
            h=650;';
        ?>

        logbuchwin=window.open("<?php echo $root_path.$top_dir; ?>op-pflege-logbuch-start_1.php?sid=<?php echo "$sid&lang=$lang&mode=$mode&dept_nr=$dept_nr&ward_nr=$ward_nr&batch_nr=$batch_nr&enc_nr=$enc_nr"; ?>","logbuchwin<?php echo $sid ?>","menubar=no,resizable=yes,scrollbars=yes, width=" + (w-15) + ", height=" + (h-60));
        window.logbuchwin.moveTo(0,0);
        if('<?php echo $mode; ?>'=='edit'){
            window.location.replace('<?php echo $root_path."modules/op_document/op_list_e_kip.php".URL_REDIRECT_APPEND."&user_origin=op_e_kip&nointern=1&batch_nr=".$batch_nr."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr."&enc_nr=".$enc_nr."&flag=1&mode=".$mode;?>');
        }else
            window.location.replace('<?php if($retpath=="calendar_opt") echo $root_path."calendar/calendar-options.php?sid=$sid&lang=$lang&day=$pday&month=$pmonth&year=$pyear";else echo $root_path."modules/op_document/op_test_request_admin.php?sid=".$sid."&lang=".$lang."&target=".$_SESSION['sess_user_name']."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr."&subtarget=or&user_origin=op";?>&forcestation=1&nofocus=1&nointern=1');
    }
</script>

</HEAD>


<BODY BACKGROUND="#ffffff" onLoad="makelogbuch()">


</BODY>
</HTML>
