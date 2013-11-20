<style>
#tbselect{
border-collapse: collapse;
width:70%;
}
#tbselect td{
    padding:3px;
    border:1px solid #c5c5dd;
    vertical-align: middle;
}
#td input[type=text],td select{
    width:90%;
    padding:3px;
}
</style>
<strong>Chọn mục để xuất báo cáo thống kê thiết bị</strong>
<form action="" method="post" name ="formselect">
<table id="tbselect">
    <tr>
        <td  class="adm_item" style="width:20%">{{$LDDeptMana}}</td>
        <td><select name="dept_mana">
                {{$deptmana}}
            </select></td></tr>
    <tr><td class="adm_item" >{{$LDPropSelect}}</td><td>{{$PropSelectList}}</td></tr>
    <tr><td class="adm_item" ></td><td><input type="button" value="Chọn tất" onclick="checkall()"><input type="button" value="Bỏ chọn tất" onclick="uncheckall()"></td></tr>
    <tr><td class="adm_item" >Ngày nhập</td><td>Bắt đầu {{$importfromdate}} Kết thúc {{$importtodate}}</td></tr>
    <tr><td class="adm_item" >Báo cáo tổng kết</td><td><input type="submit" name="viewpropreport" value="Xem" class="butbg" />
    <input type="submit" name="exportpropreport" value="Xuất" class="butbg" /></td></tr>
</table>
    
    </form>
{{$tbcontent}}
<script>
/*jQuery(function($){
        $("#f-calendar-field-1").mask("99/99/9999");    
        $("#f-calendar-field-2").mask("99/99/9999");
        });*/
function checkall(){
    for(var i=0;i<document.formselect.elements.length;i++){
        var e=document.formselect.elements[i];
        if ((e.type=='checkbox'))
        {
            e.checked=true;
        }
    }
}
function uncheckall(){
    for(var i=0;i<document.formselect.elements.length;i++){
        var e=document.formselect.elements[i];
        if ((e.type=='checkbox') && e.disabled!=true)
        {
            e.checked=false;
        }
    }
}
</script>