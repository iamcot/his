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

define('LANG_FILE','timekeeping.php');
$local_user='ck_edv_user';
if ($local_user='ck_edv_user') define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
if(isset($ck_edv_admin_user)) setcookie('ck_edvzugang_user',$ck_edv_admin_user);
$breakfile=$root_path.'main/startframe.php'.URL_APPEND;
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
<table width=100% border=0 cellspacing=0 style="margin-top:2px;" >
<tr  width=100% height=26>
<td bgcolor="<?php echo $cfg['top_bgcolor']; ?>"><FONT  COLOR="<?php echo $cfg['top_txtcolor']; ?>"  SIZE=+1  FACE="Arial">
<STRONG> <?php echo $LDTimeTable ?></STRONG></FONT></td>
</tr>
<tr width=100%>
<td  bgcolor=<?php echo $cfg['body_bgcolor'];?> >
<FONT    SIZE=-1  FACE="Arial">
<table width=100% border=0 cellspacing=1 cellpadding=2>
    <tr width=100%>
        <td bgcolor="#e9e9e9"><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo 'Lịch trực toàn BV' ?></b> </FONT></td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" valign="top">
            <FONT  color="#0000cc" FACE="verdana,arial" size=2>
                &nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/timekeeping/dfck_create_plan.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo 'Lập lịch trực' ?></a><br>
                &nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/timekeeping/dfck_view_plan.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo 'Xem lịch trực' ?></a><br>

        </td>
    </tr>
  <!-- gjergji new ward management -->
  <tr width=100%>
	<td bgcolor="#e9e9e9"><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDStaffSchedule ?></b> </FONT></td>
  </tr>
  <tr>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/doctors/doctors-select-dept.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDCreateDrSchedule ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/doctors/doctors-shift-fastview.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDViewDrSchedule ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/nursing_or/nursing-or-select-dept.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDCreateNurSchedule ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/nursing_or/nursing-or-shift-fastview.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDViewNurSchedule ?></a><br>
	</td>
  </tr>
<!-- end : gjergji -->  
  <tr width=100%>
	<td bgcolor="#e9e9e9"><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDTimeKeeping ?></b> </FONT></td>
  </tr>
  <tr width=100%>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/doctors/doctors-select-dept-chamcong.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDTPDoctor ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/doctors/doctors-shift-fastview-chamcong.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDViewTPDoctor ?></a><br>
	<!--&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/nursing_or/nursing-or-select-dept-chamcong.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDTPNurse ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/nursing_or/nursing-or-shift-fastview-chamcong.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDViewTPNurse ?></a>
	--></td>
  </tr > 
   <tr width=100%>
	<td bgcolor="#e9e9e9"><FONT  color="#0000cc" FACE="verdana,arial" size=2><b><?php echo $LDSalary ?></b> </FONT></td>
  </tr>
  <tr width=100%>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/doctors/doctors-shift-fastview-luong.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDViewSalary ?></a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="<?php echo $root_path; ?>modules/doctors/doctors-select-dept-luong.php<?php echo URL_APPEND; ?>" target="SYSADMIN_WFRAME"><?php echo $LDSetSalary ?></a><br>
	</td>
  </tr> 
  <tr width=100%>
	<td bgcolor="#e9e9e9"><FONT  color="#0000cc" FACE="verdana,arial" size=2><b>Tính tiền phụ cấp</b> </FONT></td>
  </tr>
  <tr width=100%>
	<td bgcolor="#ffffff" valign="top">
	<FONT  color="#0000cc" FACE="verdana,arial" size=2>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="" target="SYSADMIN_WFRAME">Xem tiền phụ cấp</a><br>
	&nbsp;&nbsp;&nbsp;<img <?php echo createComIcon($root_path,'redpfeil.gif','0','absmiddle') ?>> <a href="" target="SYSADMIN_WFRAME">Tính tiền phụ cấp</a><br>
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
