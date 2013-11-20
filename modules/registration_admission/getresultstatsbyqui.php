<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
if(!isset($currYear)||!$currYear) $currYear=date('Y');
if(!isset($currMonth)||!$currMonth) $currMonth=date('m');
$year=$_GET["year"];
$qui=$_GET["qui"];
$local_user="aufnahme_user";
require($root_path.'include/core/inc_front_chain_lang.php');
require($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
$enc_obj=&new Encounter();
$tongkbhyt=$enc_obj->getStatsByQui($year,$qui)-$enc_obj->getStatsByQuiBHYT($year,$qui);
$khamngoaikbh=$enc_obj->getStatsByQuiNgoai($year,$qui)- $enc_obj->getStatsByQuiNgoaiBHYT($year,$qui);
$khamnoikbh=$enc_obj->getStatsByQuiNoi($year,$qui)- $enc_obj->getStatsByQuiNoiBHYT($year,$qui);
echo'
<table id="result" border=0 cellpadding="3" cellspacing=2>
				<tbody>
					<tr class="wardlisttitlerow">
						<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Tổng Bệnh nhân&nbsp;</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">BHYT</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Không BHYT</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khám Ngoại</td>
						
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khám Nội</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhi</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhi < 6t</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;" colspan=2> > 60t </td>
					</tr>
					<tr class="wardlistrow1">
						<td rowspan=2 style="text-align:center;"><font size="-1" face="Arial" color="" >'.$enc_obj->getStatsByQui($year,$qui).'</font></td>
						<td rowspan=2 style="text-align:center;">'.$enc_obj->getStatsByQuiBHYT($year,$qui).'</td>
						<td rowspan=2 style="text-align:center;">'.$tongkbhyt.'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiNgoai($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiNoi($year,$qui).'</td>
						<td rowspan=2 style="text-align:center;">'.$enc_obj->getStatsByQuiNhi($year,$qui).'</td>
						<td rowspan=2 style="text-align:center;">'.$enc_obj->getStatsByQuinhi6($year,$qui).'</td>
						<td rowspan=2 style="text-align:center;" colspan=2>'.$enc_obj->getStatsByQuiGia($year,$qui).'</td>
					</tr>
					<tr class="wardlistrow1">
						
						<td>
							<table width=100% border=0 cellpadding="0" cellspacing=1>
							<tr class="wardlisttitlerow">
							<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">&nbsp;BHYT&nbsp;</td>
							<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">&nbsp;Không BHYT&nbsp;</td>
							</tr>
							<tr class="wardlistrow2">
								<td style="text-align:center;">'.$enc_obj->getStatsByQuiNgoaiBHYT($year,$qui).'</td>
								<td style="text-align:center;">'.$khamngoaikbh.'</td>
							</tr>
							</table>
						</td>
						<td>
							<table width=100% border=0 cellpadding="0" cellspacing=1>
							<tr class="wardlisttitlerow">
							<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">&nbsp;BHYT&nbsp;</td>
							<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">&nbsp;Không BHYT&nbsp;</td>
							</tr>
							<tr class="wardlistrow2">
								<td style="text-align:center;">'.$enc_obj->getStatsByQuiNoiBHYT($year,$qui).'</td>
								<td style="text-align:center;">'.$khamnoikbh.'</td>
							</tr>
							</table>
						</td>					
						
					</tr>
					
					<tr class="wardlisttitlerow">
						<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhập viện</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Ngoại</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nội nhi</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">HSCC</td>
						
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">YHCT</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Sản</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhiểm</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;"> Cúm </td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;"> Tiêu chảy </td>
					</tr>
					<tr class="wardlistrow1">
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatient($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatientNgoai($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatientNoi($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatientHSCC($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatientYHCT($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatientSan($year,$qui).'</td>
						<td style="text-align:center;">'.$enc_obj->getStatsByQuiInPatientNhiem($year,$qui).'</td>
						<td style="text-align:center;">d</td>
						<td style="text-align:center;">d</td>
					</tr>
					<tr>
						<td>
						<a href="javascript:printOut('.$year.','.$qui.')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="In ấn"  align="absmiddle"></a>
						</td>
					</tr>
					<tr>
						<td>
						<p>
						<img width="20" height="15" border="0" src="../../gui/img/common/default/varrow.gif">
						<a href="aufnahme_stats_week.php?ntid=false&lang=vi">Thống kê tuần</a>
						<br>
						<img width="20" height="15" border="0" src="../../gui/img/common/default/varrow.gif">
						<a href="aufnahme_stats.php?ntid=false&lang=vi&currMonth='.$currMonth.'&currYear='.$currYear.'">Thống kê tháng</a>
						</p>
						</td>
					</tr>
				</tbody>
	</table>';
	
?>
