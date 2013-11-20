<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','startframe.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

$cksid='ck_sid'.$sid;

if(!$_COOKIE[$cksid] && !$cookie) { header("location:".$root_path."cookies.php?lang=$lang&startframe=1"); exit;}

if(!isset($_SESSION['sess_news_nr'])) $_SESSION['sess_news_nr'] = "";

$readerpath='headline-read.php?sid='.$sid.'&lang='.$lang;
# reset all 2nd level lock cookies
require($root_path.'include/core/inc_2level_reset.php');
		
$dept_nr=1; # 1 = press relations

# Get the maximum number of headlines to be displayed
$config_type='news_headline_max_display';
include($root_path.'include/core/inc_get_global_config.php');

if(!isset($news_headline_max_display)||!$news_headline_max_display) $news_num_stop=5; # default is 3 edit 0410-cot 3->5
    else $news_num_stop=$news_headline_max_display;  # The maximum number of news article to be displayed
$thisfile=basename(__FILE__);
require_once($root_path.'include/care_api_classes/class_news.php');
$newsobj=new News;
//$news=&$newsobj->getHeadlinesPreview($dept_nr,$news_num_stop);//edit 0410 - cot

$news = $newsobj->mainGetNews($news_num_stop,$page);

# Set initial session environment for this module

if(!isset($_SESSION['sess_file_editor'])) $_SESSION['sess_file_editor'] = "";
if(!isset($_SESSION['sess_file_reader'])) $_SESSION['sess_file_reader'] = "";

$_SESSION['sess_file_break']=$top_dir.$thisfile;
$_SESSION['sess_file_return']=$top_dir.$thisfile;
$_SESSION['sess_file_editor']='headline-edit-select-art.php';
$_SESSION['sess_file_reader']='headline-read.php';
$_SESSION['sess_dept_nr']='1'; // 1= press relations dept
$_SESSION['sess_title']=$LDEditTitle.'::'.$LDSubmitNews;
$_SESSION['sess_user_origin']='main_start';
$_SESSION['sess_path_referer']=$top_dir.$thisfile;

$readerpath='headline-read.php'.URL_APPEND;
# Load the news display configs
require_once('includes/inc_news_display_config.php');

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 # Hide the title bar
 $smarty->assign('bHideTitleBar',TRUE);

 # Window title
 $smarty->assign('title',$LDPageTitle);
 $smarty->assign('news_normal_display_width',$news_normal_display_width);

 # Headline title
 $smarty->assign('LDHeadline',$LDHeadline);

 #Collect html code

  /**
 * Routine to display the headlines
 */
 /* remove 0410 - cot /*
for($j=1;$j<=$news_num_stop;$j++){

	$picalign=($j==2)? 'right' : 'left';

	 ob_start();
		include('includes/inc_news_preview.php');
		($j==2)? $smarty->display('news/headline_newslist_item2.tpl') : $smarty->display('news/headline_newslist_item.tpl');
		$sTemp = ob_get_contents();
	ob_end_clean();
	
	$smarty->assign('sNews_'.$j,$sTemp);
}
*/ 

$sNewsshow = "<div>";
if($news){
foreach($news as $newsi){
	$sNewsshow.= '<article  style="clear:both;margin-bottom:10px;padding:10px;">
	<font size="5" face="arial,verdana,helvetica,sans serif" color="#CC3333" style="display:block;'.(($newsi['status']==1)?'text-decoration: line-through;':'').'">'.$newsi['title'].'</font>
	'.(($newsi['pic'] !='')?'<img style="float:left;max-width:200px;" src="../../uploads/photos/news/'.$newsi['pic'].'" >':'').'
	<div style="text-indent:20px;float:left;width:60%;padding:10px;line-height:18px;text-align:justify;">'.$newsi['preface'].'</div>
	
	<div style="clear:both;margin-top:10px;"><a href="javascript:togglenews('.$newsi['nr'].')"><i>Xem thêm...</i></a>
	'.(($_SESSION['sess_login_userid']=='admin')?(($newsi['status']==0)?'[<a href="javascript:updatestatus(1,'.$newsi['nr'].')">Ẩn bài</a>]':'[<a href="javascript:updatestatus(0,'.$newsi['nr'].')">Hiện bài</a>]').'[<a href="'.$root_path.'modules/news/headline-edit.php'.URL_APPEND.'&type=edit&nr='.$newsi['nr'].'">Sửa bài</a>]':'').'
	</div>
	<div id="newscontent'.$newsi['nr'].'" style="display:none;cursor:pointer;text-indent:20px;padding:10px;" onclick="togglenews('.$newsi['nr'].')">'.$newsi['body'].'<br><i>(click vào nội dung để đóng )</i></div>
	</article>';
}
}
$sNewsshow.= '<center">';
if($newsobj->ishavelessnews($news_num_stop,($page-$news_num_stop))) $sNewsshow.='<<< <a href="'.$root_path.'modules/news/start_page.php?sid='.$sid.'&lang='.$lang.'&page='.($page-$news_num_stop).'">Trang trước</a> ';
if($newsobj->ishavemorenews($news_num_stop,($page+$news_num_stop))) $sNewsshow.=' <a href="'.$root_path.'modules/news/start_page.php?sid='.$sid.'&lang='.$lang.'&page='.($page+$news_num_stop).'">Trang tiếp</a> >>>';
$sNewsshow.='</center>';
$sNewsshow .= "</div>";


ob_start();
?>
<script type="text/javascript" src="../../js/jscalendar/jquery.min.js"></script>
<script language="javascript">
<!--
	function updatestatus(status,nr){
		$.ajax({
			url:"newsjs.php",
			data:"itype=updatestatus&status="+status+"&nr="+nr,
			type:"post",
			success: function(msg){
				if(msg==1){ 
					//alert("Cập nhật thành công");
				window.location.reload();
				}
				else alert("Cập nhật thất bại");
				
			}
		});
	}
	function togglenews(nr){
		$("#newscontent"+nr).toggle();
		
	}
-->
</script>
<?
$sJS = ob_get_contents();
ob_end_clean();	
$smarty->assign('sJS',$sJS);
$smarty->assign('sNewsshow',$sNewsshow);
# Collect html for the submenu blocks

ob_start();

	include($root_path.'include/core/inc_rightcolumn_menu.php');

	# Stop buffering, get contents

	$sTemp = ob_get_contents();
ob_end_clean();

# assign contents to subframe

$smarty->assign('sSubMenuBlock',$sTemp);

# Assign the subframe template file name to mainframe
$smarty->assign('LDBVTitleUp',$LDBVTitleUp);
$smarty->assign('LDBVName',$LDBVName);
$smarty->assign('sMainBlockIncludeFile','news/headline.tpl');

  /**
 * show Template
 */

 $smarty->display('common/mainframe.tpl');
 
?>
