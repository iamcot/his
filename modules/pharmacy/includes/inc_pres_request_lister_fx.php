
<script language="javascript" src="../../js/wz_tooltip/wz_tooltip.js"></script>

<?php

if(!isset($tracker)||!$tracker) $tracker=1;

if($tracker>1)
{
   $list_pres->Move($tracker-2);
   $pres_show =$list_pres->FetchRow();
   $list_pres->MoveFirst();
?>
<a href="<?php echo $thisfile.URL_APPEND."&target=".$target."&subtarget=".$subtarget."&pn=".$pres_show ['encounter_nr']."&pres_id=".$pres_show ['prescription_id']."&user_origin=".$user_origin."&radiovalue=".$radiovalue."&typeInOut=".$typeInOut."&tracker=".($tracker-1); ?>"><img <?php echo createComIcon($root_path,'uparrowgrnlrg.gif','0','left',TRUE) ?> alt="<?php echo $LDPrevRequest ?>"></a>
<?php
}
if($tracker<$batchrows)
{
   $list_pres->Move($tracker);
   $pres_show =$list_pres->FetchRow();
?>
<a href="<?php echo $thisfile.URL_APPEND."&target=".$target."&subtarget=".$subtarget."&pn=".$pres_show['encounter_nr']."&pres_id=".$pres_show['prescription_id']."&user_origin=".$user_origin."&radiovalue=".$radiovalue."&typeInOut=".$typeInOut."&tracker=".($tracker+1); ?>"><img <?php echo createComIcon($root_path,'dwnarrowgrnlrg.gif','0','right',TRUE) ?>  alt="<?php echo $LDNextRequest ?>"></a>
<?php
}

$tracker=1;
echo "<br><br>";
$bgcolor="#FFFF55";
$send_date="";

/* Display the list of pending requests */
$list_pres->MoveFirst();
while($pres_show =$list_pres->FetchRow())
{
  //echo $tracker."<br>";
  	if($pres_show['status_bill']) $payment=$LDFinish;	//Toa thuoc da thanh toan hay chua
	else $payment=$LDNotYet;
	 
	if($pres_show['prescription_type']=='0397' || $pres_show['prescription_type']=='0398') $typepres=$LDInpatient; //Benh nhan noi tru hay ngoai tru
	else $typepres=$LDOutpatient;
	
	$timepres=substr($pres_show['date_time_create'],-8);
	if($timepres)
		$timepres=convertTimeToStandard($timepres);
	else $timepres=$pres_show['date_time_create'];
	 
  list($buf_date,$x)=explode(" ",$pres_show ['date_time_create']);
  if($buf_date!=$send_date)
  {
     echo "<FONT size=2 color=\"#990000\"><b>".formatDate2Local($buf_date,$date_format)."</b></font><br>";
	 $send_date=$buf_date;
	 $enc_obj->loadEncounterData($pres_show['encounter_nr']);	//Lay info benh nhan
  	 $result=$enc_obj->encounter;

	 
  	 $info = $result['name_last']. " " . $result['name_first'] . "<br>" . $timepres . "<br> PID: " . $result['pid']. "<br>" .		$typepres. "<br>" .$LDPayBill.": ".$payment;
  } 
  if($pres_id!=$pres_show['prescription_id'])
  {
  	   	$enc_obj->loadEncounterData($pres_show['encounter_nr']);
  	   	$result=&$enc_obj->encounter;							//Lay info benh nhan
  	   	$info = $result['name_last']. " " . $result['name_first'] . "<br>" . $timepres . "<br> PID: " . $result['pid']. "<br>" 		.$typepres. "<br>" .$LDPayBill.": ".$payment;
		
        echo "<img src=\"".$root_path."gui/img/common/default/pixel.gif\" border=0 width=4 height=7> <a href=\"".$thisfile.URL_APPEND."&user_origin=".$user_origin."&radiovalue=".$radiovalue."&pn=".$pres_show['encounter_nr']."&pres_id=".$pres_show['prescription_id']."&typeInOut=".$typeInOut."&tracker=".$tracker."\" onmouseover=\"Tip('". $info ."',BGCOLOR,'". $bgcolor ."')\" >".$pres_show['prescription_id']."</a><br>";
   }
   else
   {
   	
        echo "<img ".createComIcon($root_path,'redpfeil.gif','0','',TRUE)."> <FONT onmouseover=\"Tip('". $info ."',BGCOLOR,'". $bgcolor ."')\"  size=1 color=\"red\">".$pres_show['prescription_id']."</font><br>";
        $track_item=$tracker;
   }
   /* Check for the barcode png image, if nonexistent create it in the cache */
   if(!file_exists($root_path."cache/barcodes/en_".$pres_show ['encounter_nr'].".png"))
   {
	  echo "<img src='".$root_path."classes/barcode/image.php?code=".$pres_show['encounter_nr']."&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=en' border=0 width=0 height=0>";
	}
   
  $tracker++;
}
/* Reset tracker to the actual request being shown */
$tracker=$track_item; 
?>
