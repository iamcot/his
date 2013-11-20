
<script language="javascript" src="../../js/wz_tooltip/wz_tooltip.js"></script>

<?php

if(!isset($tracker)||!$tracker) $tracker=1;

if($tracker>1)
{
   $list_report->Move($tracker-2);
   $pres_show =$list_report->FetchRow();
   $list_report->MoveFirst();
?>
<!--<a href="<?php echo $thisfile.URL_APPEND."&target=".$target."&subtarget=".$subtarget."&report_id=".$report_show['pay_out_id']."&user_origin=".$user_origin."&mode=".$mode."&tracker=".($tracker-1); ?>"><img <?php echo createComIcon($root_path,'uparrowgrnlrg.gif','0','left',TRUE) ?> alt="<?php echo $LDPrevRequest ?>"></a>-->
<?php
}
if($tracker<$batchrows)
{
   $list_report->Move($tracker);
   $pres_show =$list_report->FetchRow();
?>
<!--<a href="<?php echo $thisfile.URL_APPEND."&target=".$target."&subtarget=".$subtarget."&report_id=".$pres_show['pay_out_id']."&user_origin=".$user_origin."&mode=".$mode."&tracker=".($tracker+1); ?>"><img <?php echo createComIcon($root_path,'dwnarrowgrnlrg.gif','0','right',TRUE) ?>  alt="<?php echo $LDNextRequest ?>"></a>-->
<?php
}

$tracker=1;
echo "<br><br>";
$bgcolor="#FFFF55";
$send_date="";

/* Display the list of pending requests */
$list_report->MoveFirst();
while($report_show =$list_report->FetchRow())
{
  //echo $tracker."<br>";
	
	$timepres=substr($report_show['create_time'],-8);
	if($timepres)
		$timepres=convertTimeToStandard($timepres);
	else $timepres=$report_show['create_time'];
	
	if($mode=='khole'){
		$placeto=$LDKhoLe;
	}else{
		if($placeto=$Pharma->getNameHealthStation($report_show['health_station']))
			$placeto=$LDTramYTe.' '.$placeto['name'];
	}
	 
  list($buf_date,$x)=explode(" ",$report_show['create_time']);
  if($buf_date!=$send_date)
  {
     echo "<FONT size=2 color=\"#990000\"><b>".formatDate2Local($buf_date,$date_format)."</b></font><br>";
	 $send_date=$buf_date;

	 
  	 $info = $LDPayOutID.": ".$report_show['voucher_id']. "<br>" . $placeto . "<br>" . $timepres . "<br>".$LDTotal.": " . $report_show['totalcost'];
  } 
  if($report_id!=$report_show['pay_out_id'])
  {
  	   	$info = $LDPayOutID.": ".$report_show['voucher_id']. "<br>" . $placeto . "<br>" . $timepres . "<br>".$LDTotal.": " . $report_show['totalcost'];
		
        echo "<img src=\"".$root_path."gui/img/common/default/pixel.gif\" border=0 width=4 height=7> <a href=\"".$thisfile.URL_APPEND."&user_origin=".$user_origin."&report_id=".$report_show['pay_out_id']."&mode=".$mode."&tracker=".$tracker."\" onmouseover=\"Tip('". $info ."',BGCOLOR,'". $bgcolor ."')\" >".$report_show['pay_out_id']."</a><br>";
   }
   else
   {
   	
        echo "<img ".createComIcon($root_path,'redpfeil.gif','0','',TRUE)."> <FONT onmouseover=\"Tip('". $info ."',BGCOLOR,'". $bgcolor ."')\"  size=1 color=\"red\">".$report_show['pay_out_id']."</font><br>";
        $track_item=$tracker;
   }
   
  $tracker++;
}
/* Reset tracker to the actual request being shown */
$tracker=$track_item; 
?>
