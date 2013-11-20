<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

	$thisfile= basename(__FILE__);
	$forwardfile='khochan_sudungthuoc_baocaokhac.php'.URL_APPEND;
	$breakfile='../report_khochan.php'.URL_APPEND;


    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common');

    # Title in the toolbar
    $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDSuDungThuocNhomThuoc);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDSuDungThuocNhomThuoc')");

    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('Name',$LDSuDungThuocNhomThuoc);

    # Onload Javascript code
    $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

    # Hide the return button
    $smarty->assign('pbBack',FALSE);

    ob_start();
?>
<style type="text/css">
.stylesub {
	margin-left: 20px;
	display: none;
} 
</style>
<script type="text/javascript" src="<?php echo $root_path; ?>js/jquery-1.7.js"></script>
<script  language="javascript">
function chkform(d) {
	document.reportform.action="<?php echo $forwardfile; ?>";
	document.reportform.submit();
}
function set_mouse_pointer(obj)
{
	obj.style.cursor='pointer';
}
</script>
<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp); 
    ob_start();
?>
<form name="reportform" method="POST"  onSubmit="return chkform(this)">
    <center>
    <p>
    <br/>
<table border="0" width="90%" cellpadding="5">
    <tr><td colspan="2" class="prompt"><?php echo $LDBaoCaoTheo ?></td></tr>
	<tr><td width="75%">
		<table>
		<tr><td colspan="2"><font color="#5F88BE" size="2"><b><?php echo $LDNhomThuoc; ?></b></font></td></tr>
		<?php
			$sql="SELECT * FROM view_thuoc_nhomthuoc ORDER BY pharma_group_id";
			$result = $db->Execute($sql);
			if(is_object($result)){
				$i=0;
				while($row = $result->FetchRow()){
					if($row['pharma_group_id']!=$i){
						if ($i>0) 
							echo "</div>
								<script>
									  $('#title".$i."').click(function() {
										$('#subtitle".$i."').toggle('slow');
									  });
								</script>
								</td></tr>";
						if($row['pharma_group_id_sub']!='')	$onmouseover = 'onmouseover="set_mouse_pointer(this);"';
						else $onmouseover = '';
						
						echo '<tr><td valign="top"><input type="radio" name="group_cb" value="'.$row['pharma_group_id'].'"></td><td>
								<div id="title'.$row['pharma_group_id'].'" '.$onmouseover.'><b>'.$row['pharma_group_id'].'. '.$row['pharma_group_name'].'</b></div><input type="hidden" name="groupname'.$row['pharma_group_id'].'" value="'.$row['pharma_group_name'].'" ></td></tr>';
						$i=$row['pharma_group_id'];
						echo '<tr><td></td><td><div id="subtitle'.$i.'" class="stylesub">';
					}
					if($row['pharma_group_id_sub']!='' && substr_count($row['pharma_group_id_sub'],'.')==1)
						echo $row['pharma_group_id_sub'].' '.$row['pharma_group_name_sub'].'<br>';
				}
			}
		?>
		</table>
	</td>
	<td valign="top">
		<table><tr><td colspan="2"><font color="#5F88BE" size="2"><b><?php echo $LDThuocNoiNgoai; ?></b></font></td></tr>
			<tr><td width="1%"><input type="radio" name="group_cb" value="0" checked></td><td><b><?php echo $LDThuocNoiNgoai; ?></b><input type="hidden" name="groupname0" value="<?php echo $LDThuocNoiNgoai; ?>"></td></tr>
		</table>
	</td></tr>	
</table>
	<p>
	
	<table>
		<tr>
			<td><input type="image" <?php echo createLDImgSrc($root_path,'continue.gif','0','middle'); ?> >&nbsp;</td>
			<td><a href="<?php echo $breakfile?>" ><img <?php echo createLDImgSrc($root_path,'close2.gif','0','middle'); ?> title="<?php echo $LDBackTo; ?>" align="middle"></a></td>
		</tr>
	</table>
    </center>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

