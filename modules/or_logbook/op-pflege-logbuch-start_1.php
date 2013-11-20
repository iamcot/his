<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    define('LANG_FILE','or.php');
    $local_user='ck_op_pflegelogbuch_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    if (!$internok&&!$_COOKIE['ck_opdoku_user'.$sid]){ 
        header("Location:../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
        exit;
    }
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php html_rtl($lang); ?>
<HEAD>
    <title>
        <?php echo "$LDOrEkip" ?>
    </title>
    <!--    Định dạnh kiểu chữ là UTF8-->
    <?php echo setCharSet(); ?>
</HEAD>
    <frameset rows="40%,*" border="0">
        <?php
            $d=date(d);
            $m=date(m);
            $y=date(Y);
            $thisday=$d.'/'.$m.'/'.$y;			
        ?>
        <frame name="LOGINPUT"  src="<?php echo $root_path."modules/op_document/lap_e_kip_mo.php?sid=$sid&lang=$lang&target=$target&enc_nr=$enc_nr&batch_nr=$batch_nr&mode=$mode&op_nr=$op_nr&dept_nr=$dept_nr&ward_nr=$ward_nr&thisday=".$thisday."";?>">
    <frameset cols="100%,*">
    <frame name="OPLOGIMGBAR" src="">
    </frameset>
   
<noframes>
    <BODY BACKGROUND="#ffffff" onLoad="if (window.focus) window.focus()">
    </BODY>
</noframes>
</HTML>
