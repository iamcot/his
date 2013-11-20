<?php
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
$tb_property='care_property';
$tb_property_use='care_property_use';
$default_photo_path='uploads/property/picture';
$default_manual_path='uploads/property/manual';
$breakfile='property-admi-welcome.php'.URL_APPEND;
define('MAXBLOCKROW',15);
define('LANG_FILE','properties.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
include_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_image.php');

# Start the smarty templating
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');

if($mode){
	if(!isset($photo_filename)||empty($photo_filename)) $photo_filename='nopic';
	$valid_image=FALSE;
	$img_obj=& new Image;
	// Check the uploaded image file if exists and valid
	if($img_obj->isValidUploadedImage($_FILES['photo_filename'])){
		$valid_image=TRUE;
		# Get the file extension
		$picext=$img_obj->UploadedImageMimeType();
	}
	if ($valid_image){
		// Compose the new filename
		$photo_filename=$_POST['serie'].'.'.$picext;
		// Save the file
		$img_obj->saveUploadedImage($_FILES['photo_filename'],$root_path.$default_photo_path.'/',$photo_filename);
		$_POST['image']= $default_photo_path.'/'.$photo_filename;
	}
        else{
            $_POST['image'] = $_POST['tmp_img'];
        }
	
	if(!isset($manual_filename)||empty($manual_filename)) $manual_filename='nopic';
	$valid_image=FALSE; 
	// Check the uploaded image file if exists and valid
	if($img_obj->isValidUploadedImage($_FILES['manual_filename'], 'pdf,doc,docx,txt')){
		$valid_image=TRUE;
		# Get the file extension
		$picext=$img_obj->UploadedImageMimeType();
	}
	if ($valid_image){
		// Compose the new filename
		$manual_filename=$_POST['serie'].'.'.$picext;
		// Save the file
		$img_obj->saveUploadedImage($_FILES['manual_filename'],$root_path.$default_manual_path.'/',$manual_filename);
		$_POST['manual']= $default_manual_path.'/'.$manual_filename;
	}
        else{
            $_POST['manual'] = $_POST['tmp_manual'];
        }
	
	$_POST['productiondate']=@formatDate2STD($_POST['productiondate'],$date_format);
	$_POST['importdate']=@formatDate2STD($_POST['importdate'],$date_format);
	$_POST['useddate']=@formatDate2STD($_POST['useddate'],$date_format);
	$_POST['warranty']=@formatDate2STD($_POST['warranty'],$date_format);
	
        //remove comma of price
        $_POST['price'] = str_replace(',','',$_POST['price']);
        
	if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
	if($dblink_ok){
		switch($mode)
		{	
			case 'add':
				if($property->addNewProperty($_POST)){ 
                                    $smarty->assign('actionnotation',$SuccessfullAddPropterty.$_POST['name_formal']." - ".$_POST['model']);
                                    $smarty->assign('propmodel',$_POST['model']);
                                }
				else{ 
                                    $smarty->assign('propmodel',$_POST['model']);
                                    $smarty->assign('actionnotation',$FailAddPropterty.$_POST['name_formal']." - ".$_POST['model']);
                                }
			break;
			case 'modify':
				if(isset($edit) && ($edit == 'modify')){
					if($property->updatePropertyInfo($prop_nr, $oldshortname, $_POST)) $smarty->assign('actionnotation',$SuccessfullModifyropterty.$_POST['name_formal']." - ".$_POST['model']);
					else $smarty->assign('actionnotation',$FailModifyPropterty.$_POST['name_formal']." - ".$_POST['model']);
				} 
				$propitems = array('nr','model','serie','unit','price','power','source','name_formal', 'name_short', 'propfunction', 'status', 'importdate', 'importstatus', 'productiondate', 'useddate', 'warranty', 'factorer', 'vender', 'description', 'note', 'manual', 'image','usepercent','dept_mana','country','volta','proptype');
				$propinfo = $property->getInfomationOfProp($propitems, $prop_nr);
                                //var_dump($propinfo);
			break;
                        case 'copy': 
                            $propitems = array('nr','model','serie','unit','price','power','source','name_formal', 'name_short', 'propfunction', 'importdate', 'importstatus', 'productiondate', 'useddate', 'warranty', 'factorer', 'vender', 'description', 'note', 'manual', 'image','usepercent','dept_mana','country','volta','proptype');
                            //$propinfo = $property->getInfomationOfProp($propitems, $prop_nr);
                            $propinfo = $property->getInfomationOfPropFromModel($propitems, $_POST['modelinfo']);
                           //echo $_POST['seriesinfo'];
                            break;
		}
	}else{echo "$LDDbNoLink<br>";} 
}

# Added for the common header top block
$smarty->assign('sToolbarTitle',"$LDPropertyManagement::".($mode=='modify'?$LDModifyData:$LDCreateProperties));
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',"$LDPropertyManagement::".($mode=='modify'?$LDModifyData:$LDCreateProperties));
# Buffer page output
ob_start();
?>
<style type="text/css" name="formstyle">

</style>

<script language="javascript">
function check(d)
{
	if((d.name_formal.value=="") || (d.model.value=="") || (d.serie.value==""))
	{
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}
}

function checkExist(model){
	if(model != '<?php echo $propinfo['model']; ?>'){
		$.ajax({
			type: "POST",
			url: "<?php echo $root_path;?>modules/property/checkPropertyExist.php",
			data: "model="+model,
			success: function(result)
			{
				if(result == '1'){
					alert("<?php echo $LDAlertWardPropertyExist; ?>");
					return false;
				}
			}
		});
	}
}
<?php
require($root_path.'include/core/inc_checkdate_lang.php');
?>
</script>
<?php
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

# Assign form items
$smarty->assign('LDPropFormalName',$LDPropFormalName);
$smarty->assign('LDPropCount',$LDPropCount);
$smarty->assign('LDPropCountry',$LDPropCountry);
$smarty->assign('LDPropModel',$LDPropModel);
$smarty->assign('LDPropSerieNr',$LDPropSerieNr);
$smarty->assign('LDPropUnit',$LDPropUnit);
$smarty->assign('LDPropType',$LDPropType);
$smarty->assign('LDPropvolta',$LDPropvolta);

$smarty->assign('LDPropPrice',$LDPropPrice);
$smarty->assign('LDPropFunction',$LDPropFunction);
$smarty->assign('LDPropPower',$LDPropPower);
$smarty->assign('LDPropSource',$LDPropSource);
$smarty->assign('LDPropDescription',$LDPropDescription);
$smarty->assign('LDPropMaker',$LDPropMaker);
$smarty->assign('LDPropVendor',$LDPropVendor);
$smarty->assign('LDPropProductionYear',$LDPropProductionYear);
$smarty->assign('LDPropImDate',$LDPropImDate);
$smarty->assign('LDPropImStatus',$LDPropImStatus);
$smarty->assign('LDPropStartUseDate',$LDPropStartUseDate);
$smarty->assign('LDPropWarranty',$LDPropWarranty);
$smarty->assign('LDWorkOrStop',$LDWorkOrStop);
$smarty->assign('LDPropNote',$LDPropNote);
$smarty->assign('LDPropImage',$LDPropImage);
$smarty->assign('LDPropMannual',$LDPropMannual);
$smarty->assign('LDinstruction',$LDinstruction);
$smarty->assign('sRequest',$sRequest);
$smarty->assign('sColor','style="color:red;"');
$smarty->assign('LDPropUsedStatus',$LDPropUsedStatus);

$spropstatus = '';
foreach ($propstatus as $statusitem) {
	if(!isset($propinfo['status']))
		$propinfo['status'] = 0;
	$spropstatus .= "<input type='radio' name='status' value='".$statusitem[0]."' ".($propinfo['status']==$statusitem[0]?'checked=1':'')." ".$statusitem[2]."/>".$statusitem[1]." ";
}
$smarty->assign('propstatus',$spropstatus);
/*
if(isset($propinfo['status'])){
	$smarty->assign('propstatus',"<input type='radio' name='status' value='1' ".($propinfo['status']==1?'checked=1':'')." />".$propstatus[1]."
		<input type='radio' name='status' value='0' ".($propinfo['status']==0?'checked=1':'')."/>".$propstatus[0]."
		<input type='radio' name='status' value='2' ".($propinfo['status']==2?'checked=1':'')."/>".$propstatus[2]."
		<input type='radio' name='status' value='2' ".($propinfo['status']==3?'checked=1':'')."/>".$propstatus[3]);
} else {
	$smarty->assign('propstatus',"<input type='radio' name='status' value='1' checked='1'/>".$propstatus[1]."
		<input type='radio' name='status' value='0' />".$propstatus[0]."
		<input type='radio' name='status' value='2' />".$propstatus[2]."
		<input type='radio' name='status' value='3' />".$propstatus[3]);
}
*/
$propsourcetype = $property->getPropSourceTypeList(NULL);
$sourcetypelist = "";
while($row = $propsourcetype->FetchRow()){
	$sourcetypelist .= "<option value='".$row['nr']."' ".($propinfo['source']==$row['nr']?"selected='1'":"")."  >".$row['type']."</option>";
}
# make dept select

$deptmanastr = "";
foreach($deptlist as $dept){
    if($dept[0]==$propinfo['dept_mana']) $select = "selected='true'"; else $select="";
    $deptmanastr .= '<option value="'.$dept[0].'" '.$select.'>'.$dept[1].'</option>';
}
 $smarty->assign('dept_mana',$deptmanastr);               
# Assign input values

$smarty->assign('propformalname',$propinfo['name_formal']);

$smarty->assign('tmp_img',$propinfo['image']);
$smarty->assign('tmp_manual',$propinfo['manual']);

$smarty->assign('propcountry',$propinfo['country']);
$smarty->assign('propmodel',$propinfo['model']);
$smarty->assign('propserienr',$propinfo['serie']);
$smarty->assign('propunit',$propinfo['unit']);

$smarty->assign('volta',$propinfo['volta']);
$smarty->assign('proptype',$propinfo['proptype']);

$smarty->assign('propprice',$propinfo['price']);
$smarty->assign('proppower',$propinfo['power']);
$smarty->assign('prosource',$sourcetypelist);
$smarty->assign('importstatus',$propinfo['importstatus']);
$smarty->assign('profunction',$propinfo['propfunction']);
$smarty->assign('prodescription',$propinfo['description']);
$smarty->assign('propmaker',$propinfo['factorer']);
$smarty->assign('propvendor',$propinfo['vender']);
$smarty->assign('propnote',$propinfo['note']);
$smarty->assign('usepercent',$propinfo['usepercent']);

$smarty->assign('productionyear',$calendar->show_calendar($calendar,$date_format,'productiondate',$propinfo['productiondate']));
$smarty->assign('importdate',$calendar->show_calendar($calendar,$date_format,'importdate',$propinfo['importdate']));
$smarty->assign('propusedate',$calendar->show_calendar($calendar,$date_format,'useddate',$propinfo['useddate']));
$smarty->assign('propwarranty',$calendar->show_calendar($calendar,$date_format,'warranty',$propinfo['warranty']));
$smarty->assign('propimage','<input name="photo_filename" type="file"  value="'.$propinfo['image'].'">');
$smarty->assign('propmannual','<input name="manual_filename" type="file"  value="'.$propinfo['manual'].'">');
$smarty->assign('sCancel','<a style="float:left;" class="butcancel" href="javascript:history.back()"><img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>');
//$smarty->assign('sCancel','<input type="button" style="float:left" onclick="history.back()" value="Bỏ qua">');
$smarty->assign('sSaveButton','<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="mode" value="'.($mode=="modify"?$mode:"add").'">
<input type="hidden" name="edit" value="'.($mode=="modify"?$mode:"noedit").'">
<input type="hidden" name="oldshortname" value="'.$propinfo['name_short'].'">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="submit" class="butadd"  value="">');
//'.($mode!='modify'?$LDCreateStation:$LDButtonModify).'

$sTemp = "<tr>
    <th class='adm_item gray' style='width:60px' >ID</th>
    <th class='adm_item gray' >$LDPropType</th>
    <th class='adm_item gray' >$LDPropFormalName</th>
    <th class='adm_item gray' >$LDPropModel</th>
    <th class='adm_item gray' >$LDPropSerieNr</th>
<th class='adm_item gray' >$LDPropvolta</th>
<th class='adm_item gray' >$LDPropPower</th>
    <th class='adm_item gray' >$LDPropMaker</th>
<th class='adm_item gray' >$LDPropMannual</th>
    <th class='adm_item gray' >$LDPropCountry</th>
<th class='adm_item gray' >$LDPropStartUseDate</th>
    <th class='adm_item gray'>$LDWorkOrStop</th>
		 </tr>";
$query = "SELECT count(nr) FROM $tb_property";	 	 
$rows=$property->countResultRows($query);
$rowperpage=MAXBLOCKROW;
$pagination=ceil($rows/$rowperpage);
if(!isset($page)){
    $page = 1 ;
}
$start = ($page-1) * $rowperpage;
$propitems = array('nr','model','serie','name_formal', 'volta', 'proptype','power','manual','country','useddate', 'status', 'create_time', 'modify_time','factorer');
$query="SELECT ";
$querytmp = "";
while (list($key, $val) = each($propitems)) {
		$querytmp .= $val . ", ";
}
$query.=substr($querytmp,0,-2);
$query.=" FROM $tb_property ORDER BY ".($mode=="modify"?"modify_time":"create_time")." DESC"; 
$proplistdata=$property->getPropertyItemsObject($query, $start, $rowperpage);

$toggle=0;
if($proplistdata != false){
	while($row=$proplistdata->FetchRow()){
		if($toggle)	$trc='#dedede';
		else $trc='#efefef';
		$toggle=!$toggle;
		$sTemp .= "<tr bgcolor='$trc'><td  style='text-align:center'>".$row['nr']."</td>";
                $sTemp .= "<td>".$row['proptype']."</td>";
                $sTemp .="<td style='padding: 2px 5px 2px 5px;'><a href='".$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=".$row['nr']."' title='$LDViewDetailPropInfo'>".$row['name_formal']."</a></td>";
		$sTemp .= "<td style='text-align:center'>".$row['model']."</td>";
                $sTemp .= "<td style='text-align:center'>".$row['serie']."</td>";
                $sTemp .= "<td style='text-align:center'>".$row['volta']."</td>";
                $sTemp .= "<td style='text-align:center'>".$row['power']."</td>";
                $sTemp .= "<td style='text-align:center'>".$row['factorer']."</td>";
		$sTemp .= "<td style='text-align:center'><a href='".$root_path.$row['manual']."'>".$LDManuaDownloadlLink."</a></td>";
                $sTemp .= "<td style='text-align:center'>".$row['country']."</td>";
		$sTemp .= "<td style='text-align:center'>".$row['useddate']."</td>";
		$sTemp .= "<td style='text-align:center'>".$propstatus[$row['status']][1]."</td></tr>";
	}
}
$smarty->assign('allpropertylist',$sTemp);
$pagingurl = $root_path."modules/property/property-create-new.php".URL_REDIRECT_APPEND."&mode=changepage";
require_once('Pagenation.php');
$smarty->assign('pagelist',$sTemp);
$smarty->assign('sMainBlockIncludeFile','property/property_create_form.tpl');
$smarty->display('common/mainframe.tpl');
?>
