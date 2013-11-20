<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
$top_dir='modules/radiology/include/';
require_once($root_path.'include/core/inc_environment_global.php');

$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','radio.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<?php
html_rtl ( $lang );
?>
<HEAD>
<?php
echo setCharSet ();
?>
<TITLE><?php
	echo $LDMoreSearch; ?>
</TITLE>

<style type="text/css">
table {
	font-family: verdana, arial, tahoma;
	font-size: 12px;
	font-weight: normal;
	color: black;
}
.mybutton {
	background:url('../../../gui/img/common/default/select_it.png');
	width:32px;
	height:32px;
	cursor:pointer;
}
</style>

<script LANGUAGE="JavaScript">
<!-- Begin
function sendValue(name){
	window.opener.document.getElementById('encounter_nr'+'<?php echo $id_number; ?>').value = name;
	window.close();
}

//  End -->
</script>

</HEAD>

<body>
<form name="selectform">
	<table width="100%" cellpadding="1">
		<tr bgcolor="#ffffff"><td><font size="3" color="#85A4CD"><b><?php echo $LDSelectEncounterNr; ?></b><br>&nbsp;</td></tr>
		<tr bgcolor="#ffffff"><td>
			<table width="100%" cellpadding="3" >
				<tr><th><font color="#5f88be"><?php echo $LDCaseNr; ?></th>
					<th><font color="#5f88be"><?php echo $LDEncounterDate; ?></th>
					<th><font color="#5f88be"><?php echo $LDType; ?></th>
					<th><font color="#5f88be"><?php echo $LDDiagnosis; ?></th>
					<th></th>
				</tr>
		<?php 
			$sql="SELECT * FROM care_encounter WHERE pid='".$pid."' ORDER BY encounter_date DESC";
			if($ergebnis=$db->Execute($sql)){
				$toggle=1;
				$n=$ergebnis->RecordCount();
				for($i=0; $i<$n; $i++)
				{
					if($toggle) $bgc='#f3f3f3';
					else $bgc='#fefefe';
					$toggle=!$toggle;
					
					$item=$ergebnis->FetchRow();
					
					if($item['encounter_class_nr']==1)
						$type=$LDInPatient;
					else $type=$LDOutPatient;
					$item['encounter_date']=formatDate2Local($item['encounter_date'],'dd/mm/yyyy');
					
					echo '<tr bgcolor="'.$bgc.'"><td><b>'.$item['encounter_nr'].'</b></td>';
					echo '<td>'.$item['encounter_date'].'</td>';
					echo '<td align="center">'.$type.'</td>';
					echo '<td>'.$item['referrer_diagnosis'].'</td>';
					echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['encounter_nr'].'\');"></td></tr>';
				}
			} else echo '<tr><td colspan="5">'.$LDNoEncNr.' '.$pid.'</td></tr>';
		?>
			</table>			
		</td></tr>
	</table>
</form> 

</body>
</HTML>


