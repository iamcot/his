<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * elpidio@care2x.org, 
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','pharma.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php
html_rtl ( $lang );
?>
<HEAD>
<?php
echo setCharSet ();
?>
<TITLE><?php
	echo $LDShowDetails; ?>
</TITLE>

<style type="text/css">
.fva2_ml10{
	font-family: arial;
	font-size: 13;
}
</style>
<script language="javascript">
function PrintOut()
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/prescription/toangoaitru.php<?php echo URL_APPEND; ?>&pres_id=<?php echo $pres_id; ?>&enc_nr=<?php echo $enc_nr; ?>";
	testprintpdf=window.open(urlholder,"ToaNgoaiTru","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
</script>
</HEAD>

<BODY>

<?php

//echo $LDShowDetails.' '.$pid.' '.$pres_id;

//$pres_show['']: thong tin chung toa thuoc 
//$result['']: thong tin cua benh nhan
//$medicine_in_pres: danh sach thuoc trong toa
//$medicine_count = $medicine_in_pres->RecordCount()


//Get info of pres & medicine
include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
if(!isset($Pres)) $Pres=new PrescriptionMedipot;

echo '<table width="600"><tr><td>';
echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?lang=vn&fen='.$enc_nr.'&en='.$enc_nr.'" width=282 height=178>';
echo '</td><td align="center"><FONT SIZE=4  FACE="Arial"><b>'.$LDPhatThuocChoBN.'</b></font><br><br>
		<FONT SIZE=-1  FACE="Arial"><b>'.$LDPresID.': '.$pres_id.'</b></td></tr></table>';
?>
	<table border=0 cellpadding=3 width="95%" class="fva2_ml10">
		<?php
		$list_item = array();
		$list_date = array(); $k=0;
		$list_name = array();
		if($result = $Pres->listMedipotIssueByPresId($pres_id)){
			for($i=0;$i<$result->RecordCount();$i++){
				$item = $result->FetchRow();
				$list_item[$item['product_encoder']][$item['date_issue']]= $item['number'];
				if(!in_array($item['date_issue'], $list_date)){
					$k++;
					$list_date[$k]=$item['date_issue'];
				}
				if(!in_array($item['product_encoder'],$list_name))
					$list_name[$item['product_encoder']]=$item['product_name'];
			}
		}
		//In dong ngay
		echo '<tr><td></td>';
		foreach ($list_date as $d) {
			echo '<td align="center">'.formatDate2Local($d,'dd/mm/yyyy').'</td>';
		}
		echo '</tr>';
		$bgc='#ffffff';
		//In cac dong thuoc chi tiet
		foreach ($list_item as $x => $v) {
			// $x: encoder, $v['date_issue'] = number
			if($bgc=='#ffffff') $bgc='#E6E6E6';
			else $bgc='#ffffff';
			
			echo '<tr bgcolor="'.$bgc.'"><td>'.$list_name[$x].' </td>';
			foreach ($list_date as $v1) {
				echo '<td align="center">'.$v[$v1].'</td>';
			}
			echo '</tr>';
		}
		
		?>
	</table>


</BODY>
</HTML>
