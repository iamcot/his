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
$lang_tables[]='departments.php';
define('LANG_FILE','doctors.php');
$local_user='ck_doctors_dienstplan_user';
if ($local_user='ck_doctors_dienstplan_user') define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');


$thisfile=basename(__FILE__);

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$pers_obj->useDutyplanTable();
require_once($root_path.'include/core/access_log.php');
require_once($root_path.'include/care_api_classes/class_access.php');
$logs = new AccessLog();

/* Establish db connection */
if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
if($dblink_ok)
{
    if($mode=='save')
    {


    }// end of if(mode==save)
    else
    {

    }
}
else { echo "$LDDbNoLink<br>"; }



# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in toolbar
$smarty->assign('sToolbarTitle',$sTitle);

# href for help button
$smarty->assign('pbHelp',"javascript:gethelp('docs_dutyplan_edit.php','$mode','$rows')");

# href for return button
$smarty->assign('pbBack','javascript:history.back();killchild();');

# href for close button
$smarty->assign('breakfile',$breakfile);

# Body onLoad javascript
//$smarty->assign('sOnLoadJs','onUnload="killchild()"');

# Window bar title
$smarty->assign('sWindowTitle',$sTitle);

$smarty->assign('datepickfrom','<input type="text" id="datepickfrom" value="">');
$smarty->assign('dateto','<input type="text" id="dateto" value="" readonly="true" onchange="getLich()">');
$smarty->assign('pagetitle','Lập lịch trực tuần');
$smarty->assign('pagehint','Chọn ngày đầu tuần để lập lịch trực cho tuần.');

# Collect extra javascript

ob_start();
?>
<link type="text/css" rel="stylesheet" href="<?php echo  $root_path;?>js/cssjquery/jquery-ui-1.7.2.custom.css" />
<script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
<script src="<?php echo $root_path;?>js/jquery-ui-1.7.2.custom.min.js"></script>
<script language="javascript">
  $(function() {
      $("#datepickfrom").datepicker({
          dateFormat:"yy-mm-dd",
          changeMonth: true,
          numberOfMonths: 1,
          onSelect: function (){ nextweekday();}
      });
      getfirstday();
  });
  function nextweekday() {
      if ($('#datepickfrom')) {
          var dateMin = $('#datepickfrom').datepicker("getDate");
          var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 7);
          $('#dateto').val($.datepicker.formatDate('yy-mm-dd', new Date(rMax)));
      }
  }
  function getfirstday(){
      var curr = new Date; // get current date
      var first = curr.getDate() - curr.getDay() + 1; // First day is the day of the month - the day of the week

      var firstday = new Date(curr.setDate(first));
      var curr_date = firstday.getDate();
      var curr_month = firstday.getMonth() + 1; //Months are zero based
      var curr_year = firstday.getFullYear();

      firstday = curr_year + "-" + curr_month + "-" + curr_date;
      $('#datepickfrom').val(firstday);
      nextweekday();
  }

  var urlholder;
  var infowinflag=0;

  function popselect(elem,mode)
  {
      w=window.screen.width;
      h=window.screen.height;
      ww=700;
      wh=500;
      var tmonth=document.dienstplan.month.value;
      var tyear=document.dienstplan.jahr.value;
      //nếu thếm $sid sẽ bị lỗi URI quá dài
      urlholder="doctors-dienstplan-poppersonselect.php?elemid="+elem+"&dept_nr=<?php echo $dept_nr ?>&month="+tmonth+"&year="+tyear+"&mode="+mode+"&retpath=qview<?php echo "&lang=$lang"; ?>";
      popselectwin=window.open(urlholder,"pop","width=" + ww + ",height=" + wh + ",menubar=no,resizable=yes,scrollbars=yes,dependent=yes");
      window.popselectwin.moveTo((w/2)+80,(h/2)-(wh/2));
  }
  function insertRow(mode,nr,num)
  {               var tbl = document.getElementById("mytest"+mode+nr);
      var lastRow = tbl.tBodies[0].rows.length;
      if (window.XMLHttpRequest)	  {
          xmlhttp=new XMLHttpRequest();
      }
      else  {
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function()
      {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
          {
              var rowadd =tbl.tBodies[0].insertRow(-1);
              rowadd.innerHTML = xmlhttp.responseText;
          }
      }
      xmlhttp.open("GET","getrow.php?mode="+mode+"&i="+nr+"&num="+lastRow,true);
      xmlhttp.send();


  }
  function delRow(mode,i,num)
  {
      var tbl = document.getElementById("mytest"+mode+i);
      //alert(tbl);
      var lastRow = tbl.tBodies[0].rows.length;
      //alert(lastRow);

      if (lastRow >= num) tbl.deleteRow(num-1);
      document.getElementById("h"+mode+i+"_"+(num-1)).innerHTML="";
  }

  function killchild()
  {
      if (window.popselectwin) if(!window.popselectwin.closed) window.popselectwin.close();
  }

  function cal_update()
  {
      var filename="doctors-dienstplan-planen.php?<?php echo "sid=$sid&lang=$lang" ?>&retpath=<?php echo $retpath ?>&dept_nr=<?php echo $dept_nr; ?>&pmonth="+document.dienstplan.month.value+"&pyear="+document.dienstplan.jahr.value;
      window.location.replace(filename);
  }
</script>
<?php

$sTemp=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$smarty->assign('sFormAction','action="dfck_create_plan."');

# collect hidden inputs

ob_start();
?>

<input type="hidden" name="mode" value="save">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="sid" value="<?php echo $sid; ?>">

<?php

$sTemp=ob_get_contents();
ob_end_clean();
$smarty->assign('sHiddenInputs',$sTemp);

if($saved) $sBuffer = createLDImgSrc($root_path,'close2.gif','0');
else $sBuffer = createLDImgSrc($root_path,'cancel.gif','0');

# Assign control links
$smarty->assign('sSave','<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'"></a>');
$smarty->assign('sClose',"<a href=\"$breakfile\" onUnload=\"killchild()\"><img ".$sBuffer." alt=\"$LDClosePlan\"></a>");

$sTemp='';

$smarty->assign('sMainBlockIncludeFile','timekeeping/dfck_create_plan.tpl');
/**
 * show Template
 */
$smarty->display('common/mainframe.tpl');

?>
