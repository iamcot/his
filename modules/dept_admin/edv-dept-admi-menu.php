<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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
define('LANG_FILE','edp.php');
$local_user='ck_edv_user';
if ($local_user='ck_edv_user') define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
if(isset($ck_edv_admin_user)) setcookie('ck_edvzugang_user',$ck_edv_admin_user);
$breakfile=$root_path.'main/startframe.php'.URL_APPEND; 
$_SESSION['sess_path_referer'] = '../modules/dept_admin/dept-admi-welcome.php'.URL_APPEND;
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<?php 
require($root_path.'include/core/inc_js_gethelp.php');
require($root_path.'include/core/inc_css_a_hilitebu.php');
?>
</HEAD>
<BODY topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 bgcolor=<?php echo $cfg['body_bgcolor'];?>>
<table width=100% border=0 cellspacing=0 style="margin-top: 5px;">
<tr>
<td bgcolor="<?php echo $cfg['top_bgcolor']; ?>"><FONT  COLOR="<?php echo $cfg['top_txtcolor']; ?>"  SIZE=+2  FACE="Arial">
<STRONG> <?php echo $LDDeptManagement ?></STRONG></FONT></td>
</tr>
<tr>
<td bgcolor=<?php echo $cfg['body_bgcolor'];?> colspan=2>
<FONT    SIZE=-1  FACE="Arial">
<table border=0 cellspacing=1 cellpadding=2 style="width:100%;"> 
 <tr>
	<td bgcolor="#e9e9e9" colspan=2><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDDeptAdmin ?></b> </FONT></td>
  </tr>
  <tr>
      <td width="10%"></td>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/dept_admin/dept_new.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDCreate ?></a><br>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/dept_admin/dept_list.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDList ?></a><br>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/dept_admin/dept_list_config.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDConfigOptions ?></a><br>
	</td>
  </tr> 
  <!-- gjergji new ward management -->
  <tr>
	<td bgcolor="#e9e9e9" colspan=2><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDNursingManage ?></b> </FONT></td>
  </tr>
  <tr>
      <td width="10%"></td>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/nursing/nursing-station-new.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDCreate ?></a><br>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/nursing/nursing-station-info.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDWardListConfig ?></a><br>
	</td>
  </tr>
<!-- end : gjergji -->  
  <tr>
	<td bgcolor="#e9e9e9" colspan=2><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDORAdmin ?></b> </FONT></td>
  </tr>
  <tr>
      <td width="10%"></td>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/or_admin/or_new.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDCreate ?></a><br>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/or_admin/or_list_config.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDOPListConfig ?></a>
	</td>
  </tr>
   
  <tr>
	<td bgcolor="#e9e9e9" colspan=2><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDAdministrative ?></b> </FONT></td>
  </tr>
  <tr>
      <td width="10%"></td>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/property/property-create-new.php<?php echo URL_REDIRECT_APPEND; ?>&mode=new&target=new" target="SYSADMIN_WFRAME"><?php echo $LDDeptPropertyCreate ?></a><br>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/property/property-find-advance.php<?php echo URL_REDIRECT_APPEND; ?>&target=new" target="SYSADMIN_WFRAME"><?php echo $LDDeptPropertySearch ?></a><br>
	</td>
  </tr>
  
  <tr>
	<td bgcolor="#e9e9e9" colspan=2><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDeptAdministrative ?></b> </FONT></td>
  </tr>
  <tr>
      <td width="10%"></td>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
        <?php
            require_once($root_path.'include/care_api_classes/class_personell.php');
            $personell= new Personell();
            $chucvu = $personell->_getPersonellById($personell_nr);
            if($chucvu){
                $info_chucvu=$chucvu->FetchRow();
            }
          //  if($info_chucvu['chucvu_nr']==3 || $info_chucvu['chucvu_nr']==4 || $_SESSION['sess_login_username']=='admin'){
                $begin_link1='<a href="'.$root_path.'modules/property/property-select-dept.php'.URL_REDIRECT_APPEND.'&target=plist" target="SYSADMIN_WFRAME">';
                $end_link='</a>';
                $begin_link2='<a href="'.$root_path.'modules/property/property-select-dept.php'.URL_REDIRECT_APPEND.'&target=search" target="SYSADMIN_WFRAME">';
                $begin_link3='<a href="'.$root_path.'modules/property/property_export.php'.URL_REDIRECT_APPEND.'&target=select" target="SYSADMIN_WFRAME">';
          //  }
        ?>
<!--	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/property/property-select-dept.php<?php echo URL_REDIRECT_APPEND; ?>&target=plist" target="SYSADMIN_WFRAME"><?php echo $LDDeptPropertyList ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/property/property-select-dept.php<?php echo URL_REDIRECT_APPEND; ?>&target=search" target="SYSADMIN_WFRAME"><?php echo $LDDeptPropertySearch ?></a><br>-->
        <img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> 
            <?php 
                echo $begin_link1;
                echo $LDDeptPropertyList;
                echo $end_link;
            ?>
        <br>
	<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>>
            <?php 
                echo $begin_link2;
                echo $LDDeptPropertySearch;
                echo $end_link;
            ?>
        <br>
         <img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>>
         
          <?php 
          //<a href="javascript:printOut();">
          echo $begin_link3;
          echo $LDTongket;
          echo $end_link;
          ?>
          <br>
	</td>
  </tr>          

</table>

</FONT>
<p>
</td>
</tr>
</table>        
<p>
<a href="<?php echo $breakfile ?>" target="_parent"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>></a>

</FONT>
</BODY>
</HTML>
<script language="javascript">
    function printOut() {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/std_plates/Baocaothietbi.php<?php echo URL_APPEND ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>
