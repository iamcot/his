<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_config_no_nameBV.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','editor.php');
$local_user='ck_editor_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
//end : gjergji

/* Check the article number artnum, if none, redirect to article num selector */
/*if(!isset($artopt) || !$artopt) {
    header('Location:./headline-edit-select-art.php'.URL_REDIRECT_APPEND);
	exit;
}
*/
/* Set navigation paths for this page*/
$breakfile=$root_path.$_SESSION['sess_file_break'].URL_APPEND;
$returnfile='headline-edit-select-art.php'.URL_APPEND;

/* Set the new return file for the suceeding page */
$_SESSION['sess_file_return']='headline-edit-select-art.php';

$_SESSION['sess_file_forward']='headline-read.php';

//$title= (!empty($title)) ? $title : $_SESSION['sess_title']; 

/* Load the date formatter */
require_once($root_path.'include/core/inc_date_format_functions.php');

/* Determine the picture alignment basing on the article number.
* You can change here the alignment. Preset are odd number= left, even number= right
*/
//if(!($artopt%2)) $alignright=true;
//    else $alignright=false;
if(isset($type) && $type=='edit'){
	require_once($root_path.'include/care_api_classes/class_news.php');
	$newsobj=new News;
	$news = $newsobj->get1News($nr);
}
?>
<?php html_rtl($lang); ?>

<head>

<?php echo setCharSet(); ?>
<title><?php echo $title ?></title>
<script src="<?php echo $root_path.'js/jupload/'; ?>jquery-1.7.2.min.js"></script>
<script src="<?php echo $root_path.'js/jupload/'; ?>vendor/jquery.ui.widget.js"></script>
<script src="<?php echo $root_path.'js/jupload/'; ?>jquery.iframe-transport.js"></script>
<script src="<?php echo $root_path.'js/jupload/'; ?>jquery.fileupload.js"></script>
<script language="javascript">
<!-- Script Begin


function showpic(d)
{
	if(d.value) document.images.headpic.src=d.value;
}

<?php 
# Load the javascript editor form checker 
require_once('includes/inc_js_editor_chkform.php');

# Load the dates js values
require($root_path.'include/core/inc_checkdate_lang.php'); 
?>
<!--  Root path for the html WYSIWYG editor -->
var _editor_url="<?php echo $root_path.'js/html_editor/'; ?>";

// -->
 </script>
<!-- load html editor scripts -->
<script language="javascript"  type="text/javascript" src="<?php echo $root_path.'js/html_editor/'; ?>htmlarea.js"></script>
<script language="javascript"  type="text/javascript" src="<?php echo $root_path.'js/html_editor/'; ?>lang/en.js"></script>
<script language="javascript"  type="text/javascript" src="<?php echo $root_path.'js/html_editor/'; ?>dialog.js"></script>
<style type="text/css">@import url("<?php echo $root_path.'js/html_editor/'; ?>htmlarea.css")</style>

<!--  Load validators -->
<script language="javascript" src="<?php echo $root_path; ?>js/editor_chkform.js" type="text/javascript"></script>
 
<?php require($root_path.'include/core/inc_css_a_hilitebu.php'); ?>

</head>

<body onLoad="HTMLArea.replace('newsbody')">

<form ENCTYPE="multipart/form-data" name="selectform" id="selectform" method="post" action="headline-edit-save.php" onSubmit="return chkForm()">
<FONT  SIZE=6 COLOR="#cc6600">
<b><?php echo $title ?></b></FONT>
<hr>
<table border=0>
  <tr >
<?php if($artopt!=2) : ?>
    <td valign=top><img style="max-width:200px" src="<? echo $root_path.((isset($news['pic']))?'uploads/photos/news/'.$news['pic']:'x-blank.gif'); ?>" id="headpic"><br>
  </td>
<?php endif ?>
    <td class="submenu" colspan=2><FONT color="#0000cc" size=3><b><?php echo $LDTitleTag ?>:</b><br>
	<font size=1><?php echo $LDTitleMaxNote ?><br>
	<input type="text" name="newstitle" size=50 maxlength=255 value="<? echo $news['title'];?>"><br>
	<FONT color="#0000cc" size=3><b><?php echo $LDHeader ?>:</b><br>
	<font size=1><?php echo $LDHeaderMaxNote ?><br>
	<!-- <input type="text" name="preface" size=50 maxlength=50><br> -->
	
	<textarea name="preface" cols=50 rows=5 wrap="physical" id="preface"><? echo $news['preface'];?></textarea><br>
	
	<FONT color="#0000cc" size=3><b><?php echo $LDNews ?>:</b><br>
	<font size=1>
	
	<textarea name="newsbody" cols=50 rows=14 wrap="physical" id="newsbody"><? echo $news['body'];?></textarea><br>
	<FONT color="#0000cc" size=2><b><?php echo 'Upload file' ?>:</b><br>
	<input id="picuploadn" type="file" name="files[]" data-url="calljupload.php" multiple> <br>
	<div id="listupload" style="width:90%"></div><br>
	
  	<FONT color="#0000cc" size=2><b><?php echo $LDPicFile ?>:</b><br>
  	<input id="pictitle" type="file" name="files[]" data-url="calljupload.php" multiple> <br>
	<input type="text" id="pictitlecontent" name="pic" value="<? echo $news['pic']?>"><br>
  <!--  <input type="button" value="<?php echo $LDPreviewPic ?>" onClick="showpic(document.selectform.pic)"> -->
  <br>
  	<FONT color="#0000cc" size=2><b><?php echo $LDAuthor ?>:</b><br>
	<input type="text" name="author" size=30 maxlength=40 value="<? echo $news['author'];?>"><br>
  	<FONT color="#0000cc" size=2><b><?php echo $LDPublishDate ?>:</b><br>
	<?php
	//gjergji : new calendar
	//var_dump($news['submit_date']);
	echo $calendar->show_calendar($calendar,$date_format,'publishdate',((isset($news['submit_date']))?date("Y-m-d H:i:s",strtotime($news['submit_date'])):date("Y-m-d H:i:s")));
	//end gjergji
	 ?>
</td>
<?php if($alignright) : ?>
    <td valign=top><img <?php echo createLDImgSrc($root_path,'x-blank.gif','0','absmiddle') ?> id="headpic"><br>
  </td>
<?php endif ?>
  </tr>
  <tr>
<?php if(!$alignright) : ?>
    <td align=right >
		<!--<a href="<?php echo $returnfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>-->
	&nbsp;
  </td>
<?php endif ?>
    <td >
<?php if($alignright) : ?>
		<!--<a href="<?php echo $returnfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>-->
<?php endif ?>
 <input type="image" <?php echo createLDImgSrc($root_path,'continue.gif','0') ?>> 
<!-- <a href="javascript:chkForm(document.selectform)"><img <?php echo createLDImgSrc($root_path,'continue.gif','0') ?>></a> -->
<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a>
  </td>
   
<?php if($alignright) : ?>
    <td align=right >&nbsp;
  </td>
<?php endif ?>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="title" value="<?php echo $title ?>">
<input type="hidden" name="artnum" value="<?php echo $artopt ?>">

<? if(isset($type) && $type=='edit')
echo '
<input type="hidden" name="nr" value="'.$nr.'">
<input type="hidden" name="mode" value="edit">';
else
echo '<input type="hidden" name="mode" value="save">';?>

<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000">

</form>
</body>
</html>
<script>
$(function () {
    $('#picuploadn').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('#listupload').html($('#listupload').html()+"<input type='text' onclick='this.select()' value='<?php echo SITE_URL.'uploads/photos/news/'?>"+file.name+"' style='width:300px;'><br>");
                
            });
        }
    });
});
$(function () {
    $('#pictitle').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('#pictitlecontent').val(file.name);
                $('#headpic').attr('src','<?php echo SITE_URL.'uploads/photos/news/'?>'+file.name);
                
            });
               //$("#ai_pic").val(data.0.url);
        }
    });
});
</script>
