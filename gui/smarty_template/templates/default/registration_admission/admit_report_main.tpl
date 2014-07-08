<style>
#menuleft{
	float:left;
	width: 30%;
	display: block;
	border-right: 1px solid #e9e9e9;
	min-height: 400px;
}
#contentright{
	float: left;
    width: 60%;
	display: block;
	padding: 10px;
}
.title{
	background: #e9e9e9;
	height: 18px;
	font-weight: bold;
	color: #000066;
}
.tablecontent td{
	font-size: 15px;
	padding: 3px 10px 5px 3px;
}
.item{
	padding-left: 10px !important;
}
</style>
<div>
	<div id="menuleft">
		<table class="tablecontent">
		<tr class="title"><td>Thống kê của Khoa</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aTKKNgT}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aTKKBday}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aTKHSCCday}}</td></tr>
	<!--	<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aTKYHCTday}}</td></tr> --!>
			<tr class="title"><td>Báo cáo Bệnh tật tử vong</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aKKB}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aDTNT}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aDTALL}}</td></tr>
		<tr class="title"><td>Báo cáo ra/vào khoa</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aVaoKhoa}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aRaKhoa}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aVaoVien}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aRaVien}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aChuyenVien}}</td></tr>
		<tr class="title"><td>Báo cáo thống kê</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aKhamsuckhoe}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aThongKeThuoc}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aDieutrinoitru}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$ab031dt}}</td></tr>
	<!--	<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$ab05skss}}</td></tr>
		<tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$ab06cls}}</td></tr>
-->
                <tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aBCTUKSAN}}</td></tr>
                <tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aBCTKSAN}}</td></tr>
                <tr><td class="item"><img width="4" height="7" border="0" align="absmiddle" src="../../gui/img/common/default/redpfeil.gif"> {{$aBM07KSAN}}</td></tr>
	</table>
	</div>
	<div id="contentright"></div>
</div>
{{$script}}