<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
$lang='vi';
//define('NO_2LEVEL_CHK',1);
define('NO_CHAIN',1);
define('LANG_FILE','radio.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_encounter.php');


//$pid='0001';
if($encounter_nr){
	$Encounter=new Encounter;
	if( $Encounter->loadEncounterData($encounter_nr)) {
		$person_name=$Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first'];
		$birthday=str_replace("-","",$Encounter->encounter['date_birth']);		
		if(strlen($birthday)<8)  $birthday=str_pad($birthday, 8,'1', STR_PAD_RIGHT);
		$sex=$Encounter->encounter['sex'];
	}
}	
$date=date('Ymd');


$imgdir = $root_path."uploads/radiology/dicom_img/".$pid.'/'.$encounter_nr.'/'.$date.'/sa';
$suf=0;
$listfolder=glob($imgdir."*",GLOB_ONLYDIR);
if ($listfolder!=false){
	$lastname = $listfolder[count($listfolder)-1];
	$lastname = explode('sa_',$lastname);
	$suf = intval($lastname[1]) +1;								
}

$sql1=" SELECT * FROM care_test_request_radio_sub WHERE batch_nr='".$batch_nr."' AND item_bill_code='".$item_code."' ";
if($re_item=$db->Execute($sql1)){
	if($count1=$re_item->RecordCount()){
		$item=$re_item->FetchRow();
		$item_name=$item['item_bill_name'];
	}else{
		$item_name=$LDErrorData;
	}
} 

?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php
html_rtl ($lang);
?>
<HEAD>
<?php
echo setCharSet ();
?>
<TITLE><?php
	echo $LDThuNhanAnhSieuAm; ?>
</TITLE>
<!-- saved from url=(0014)about:internet -->

<!-- 
Smart developers always View Source. 

This application was built using Adobe Flex, an open source framework
for building rich Internet applications that get delivered via the
Flash Player or to desktops via Adobe AIR. 

Learn more about Flex at http://flex.org 
// -->



<!--  BEGIN Browser History required section -->
<link rel="stylesheet" type="text/css" href="history/history.css" />
<!--  END Browser History required section -->

<title></title>
<script src="AC_OETags.js" language="javascript"></script>

<!--  BEGIN Browser History required section -->
<script src="history/history.js" language="javascript"></script>
<!--  END Browser History required section -->

<style>
body { margin: 0px; overflow:hidden }
table {
	font-family: arial;
	font-size: 12px;
	font-weight: normal;
	color: black;
}
</style>
<script language="JavaScript" type="text/javascript">
<!--
// -----------------------------------------------------------------------------
// Globals
// Major version of Flash required
var requiredMajorVersion = 9;
// Minor version of Flash required
var requiredMinorVersion = 0;
// Minor version of Flash required
var requiredRevision = 124;
// -----------------------------------------------------------------------------
// -->
function closeWin(){
	//document.action
<!--	location.href='--><?php //echo '../view_person_search.php'.URL_APPEND.'&searchkey='.$encounter_nr; ?><!--';-->
//    myWindow=window.close(){};
    window.close();

}
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0">
	<tr bgcolor="#5F88BE">
		<td><font size="4" color="#ffffff"><b><?php echo $LDThuNhanAnhSieuAm; ?></b></td>
		<td align="right"><a href="javascript:closeWin();"><img <?php echo createLDImgSrc($root_path,'close2.gif','0','middle'); ?> ></a></td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="90%" cellspacing="2" cellpadding="2">
			<?php 
				echo '<tr>';
				echo 	'<td width="15%">'.$LDPID.': </td><td width="30%">'.$pid.'</td>';
				echo 	'<td width="15%">'.$LDCaseNr.': </td><td>'.$encounter_nr.'</td>'; 
				echo '</tr>';
				echo '<tr>';
				echo 	'<td>'.$LDPatientName.': </td><td>'.$person_name.'</td>'; 
				echo 	'<td>'.$LDDateTaken.': </td><td>'.date('d/m/Y').'</td>';
				echo '</tr>';
				echo '<tr>';
				echo 	'<td>'.$LDSoLuuTru.': </td><td>'.$batch_nr.'</td>'; 
				echo 	'<td>'.$LDYeuCau.': </td><td>'.$item_name.'</td>';
				echo '</tr>';
			?>
			
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
<script language="JavaScript" type="text/javascript">
<!--
// Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
var hasProductInstall = DetectFlashVer(6, 0, 65);

// Version check based upon the values defined in globals
var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

if ( hasProductInstall && !hasRequestedVersion ) {
	// DO NOT MODIFY THE FOLLOWING FOUR LINES
	// Location visited after installation is complete if installation is required
	var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
	var MMredirectURL = window.location;
    document.title = document.title.slice(0, 47) + " - Flash Player Installation";
    var MMdoctitle = document.title;

	AC_FL_RunContent(
		"src", "playerProductInstall",
		"FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
		"width", "997",
		"height", "560",
		"align", "middle",
		"id", "demo1",
		"quality", "high",
		"bgcolor", "#869ca7",
		"name", "demo1",
		"allowScriptAccess","sameDomain",
		"type", "application/x-shockwave-flash",
		"pluginspage", "http://www.adobe.com/go/getflashplayer"
	);

} else if (hasRequestedVersion) {
	// if we've detected an acceptable version
	// embed the Flash Content SWF when all tests are passed
	AC_FL_RunContent(
			"src", "demo1",
			"FlashVars", "pid=<?php echo $pid; ?>&enc_nr=<?php echo $encounter_nr; ?>&suf=<?php echo $suf; ?>&batchnr=<?php echo $batch_nr; ?>&itemcode=<?php echo $item_code; ?>&uid=<?php echo date('YmdHisB'); ?>",
			"width", "997",
			"height", "560",
			"align", "middle",
			"id", "demo1",
			"quality", "high",
			"bgcolor", "#869ca7",
			"name", "demo1",
			"allowScriptAccess","sameDomain",
			"type", "application/x-shockwave-flash",
			"pluginspage", "http://www.adobe.com/go/getflashplayer"
	);
  } else {  // flash is too old or we can't detect the plugin
    var alternateContent = 'Alternate HTML content should be placed here. '
  	+ 'This content requires the Adobe Flash Player. '
   	+ '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
    document.write(alternateContent);  // insert non-flash content

  }
// -->

</script>

<noscript>
  	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			id="demo1" width="997" height="553"
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="demo1.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#869ca7" />
			<param name="allowScriptAccess" value="sameDomain" />
			<embed src="demo1.swf" quality="high" bgcolor="#869ca7" 
				width="997" height="553" name="demo1" align="middle"
				play="true"
				loop="false"
				quality="high"
				allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer">
			</embed>
	</object>
</noscript>

		</td>
	</tr>
</table>
</body>
</html>

