<html><body onload="init_load();"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></body></html>
<?php
    /*
    * File hiển thị các danh mục
    * Tham số bao gồm:
    *      catalogue: mang các giá trị: supplier, generic_drug, medicine, vnmedicine, medipot
    *                 tương ứng là các danh mục: nhà cung cấp, thuốc gốc, thuốc tây y, thuốc đông y, vật tư y tế
    *      mode: mang các giá trị: view, new, save (xem, thêm mới, lưu đối tượng mới thêm vào danh mục)
    */
    //code init
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    //ngôn ngữ
    define('LANG_FILE','pharma_catalogue.php');
    define('NO_2LEVEL_CHK',1);
    $breakfile=$root_path.'modules/pharmacy/apotheke.php'.URL_APPEND; //đường dẫn khi click nút Đóng (Close)
    $breakfilecatalogue=$root_path.'modules/pharmacy/catalogue_unit.php'.URL_APPEND;
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require($root_path.'include/core/inc_2level_reset.php');
    if(!isset($_SESSION['sess_path_referer'])) $_SESSION['sess_path_referer'] = "";
    if(!isset($_SESSION['sess_user_origin'])) $_SESSION['sess_user_origin'] = "";
    $_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
    $_SESSION['sess_user_origin']='unit';
    require ($root_path.'include/care_api_classes/class_access.php');
    $access = new Access($_SESSION['sess_login_userid'],$_SESSION['sess_login_pw']);
    /*
    $hideOrder = 0;
    if(ereg("_a_1_pharmadbadmin",$access->PermissionAreas()))
            $hideOrder = 1;
    * 
    */
    //các file class
    //require_once($root_path.'include/care_api_classes/class_supplier.php');
    //require_once($root_path.'include/care_api_classes/class_product.php');

    require_once($root_path.'modules/pharmacy/catalogue_process.php');//file xử lý

    $this_file="catalogue_unit.php";
    $process_file='catalogue_process.php';
    $ajax_file="catalogue_unit_ajax.php";

    if(!isset($catalogue) || $catalogue=='')
            $catalogue='unit';
	
    $records_in_page=14;//số kết quả hiển thị mỗi trang

    //------------------------------IMAGE--------------------------------------
    $img_edit=createComIcon($root_path,'pharmacy_edit.jpg','0');
    $img_search=createComIcon($root_path,'pharmacy_search.gif','0');
    $img_save=createComIcon($root_path,'pharmacy_save.png','0');
    $img_cancel=createComIcon($root_path,'pharmacy_cancel.png','0');
    $img_new=createComIcon($root_path,'pharmacy_new.png','0');
?>

    <link rel="stylesheet" type="text/css" href="../../gui/css/dropdown_menu/dropdown_menu_1.css" />
    <script src="../../gui/css/dropdown_menu/stuHover.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="../../gui/css/dropdown_menu/dropdown_menu_2.css" />
    <script src="../../gui/css/dropdown_menu/stuHover_2.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="../../gui/css/dropdown_menu/dropdown_menu_3.css" />
    <script src="../../gui/css/dropdown_menu/stuHover_3.js" type="text/javascript"></script>


    <script type="text/javascript" src="../../js/jquery-1.7.js"></script>
    <script type="text/javascript" src="../../js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" ></script>
    <link type="text/css" href="../../gui/css/autocomplete/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script language="javascript">
    var process_file='<?php echo $ajax_file ?>';
    var stateObj = { foo: "bar" };
    //hàm tách chuỗi
    function mkhash()
    {
        var ret = new Object();
        for (var i = 0; i < arguments.length; ++i )
        {
                ret[arguments[i][0]] = arguments[i][1];
        }
        return ret;
    }
    function setFocus()
    {
        var value= document.getElementById("quick").value;
        if (value=='')
        document.getElementById("quick").focus();//chuyển con trỏ về textbox tìm kiếm khi load trang
    }
    //xác định chiều cao của scroll div
    function set_height_div()
    {
        var myWidth = 0, myHeight = 0;
        if( typeof( window.innerWidth ) == 'number' ) {
                    //Non-IE
                    myWidth = window.innerWidth;
                    myHeight = window.innerHeight;
            } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
                    //IE 6+ in 'standards compliant mode'
                    myWidth = document.documentElement.clientWidth;
                    myHeight = document.documentElement.clientHeight;
            } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
                    //IE 4 compatible
                    myWidth = document.body.clientWidth;
                    myHeight = document.body.clientHeight;
            }
    //        document.write(myWidth);

            document.getElementById("frame_all").style.width=myWidth-10+"px";
            document.getElementById("frame_all").style.height=myHeight-50+"px";
            document.getElementById("frame_result").style.height=myHeight-140+"px";
            document.getElementById("td_frame_info").style.width=myWidth-540+"px";
            document.getElementById("frame_info").style.width=myWidth-550+"px";
            document.getElementById("frame_info").style.height=myHeight-50+"px";
            if ((('<?php echo $catalogue ?>'=='supplier') || ('<?php echo $catalogue ?>'=='generic_drug')) && ('<?php echo $mode?>'!='new'))
                            document.getElementById("div_content").style.height=myHeight-250+"px";
            else
                if ('<?php echo $mode?>'=='new')
                    document.getElementById("div_content").style.height=myHeight-150+"px";//giảm 10px do với mode new, khung tiêu đề thêm 10px
                else document.getElementById("div_content").style.height=myHeight-140+"px";
    }
    //load lúc khởi động
    function init_load()
    {
        set_height_div();
        setFocus();//load nội dung thanh tìm kiếm
    }

    var process_file='<?php echo $ajax_file ?>';
    var stateObj = { foo: "bar" };
    function Generic_Drug_AutoComplete()
    {
        $.ajax({
        type: "GET",
        url: process_file+"?catalogue=generic_drug&key=autocomplete",
        cache: false,
        async: false,
        data: "quick="+document.getElementById("generic_drug_input").value,
        success: function(data) {
                    var a = data.split("@#");

                    $( "#generic_drug_input" ).autocomplete({
                                    source: a
                            });
                    }
        });

    }
    function Fill_Data_GroupName()
    {
            var temp=document.getElementById("generic_drug_input").value;
            //temp=temp.replace('+','@*');
            temp=temp.split('+').join('@*');

            $.ajax({
                    type: "GET",
                    url: "catalogue_ajax.php?catalogue=medicine_filldata&key=autocomplete",
                    cache: false,
                    async: false,
                    data: "quick="+temp,
                    success: function(data) {
                            var a= data.split("@#");
                            document.getElementById("pharma_group_name").innerHTML=a[1];
                            document.getElementById("exp_ger_drug_id").value=a[2];
                    }
        });

    }
    //hàm autocomplete nhà cung cấp
    function Supplier_AutoComplete()
    {
        $.ajax({
        type: "GET",
        url: process_file+"?catalogue=supplier&key=autocomplete",
        cache: false,
        async: false,
        data: "quick="+document.getElementById("supplier_input").value,
        success: function(data) {
        //alert(data);
    //    var myhash = eval( 'mkhash('+data+')' );
    //    var a = [myhash["1"],myhash["2"],myhash["3"],myhash["4"],myhash["5"],myhash["6"],myhash["7"],myhash["8"],myhash["9"],myhash["10"]];   
        var a = data.split("@#");

        $( "#supplier_input" ).autocomplete({
                            source: a
                    });
        }
        });
    }
//phân trang
function load_page(catalogue, last_page, current_page, records_in_page, cond)
{
    $.ajax({
    type: "GET",
    url: process_file+"?catalogue="+catalogue+"&cond="+cond+"&key=load_catalogue&records_in_page="+records_in_page+"&page="+current_page,
    cache: false,
    async: false,
    data: '',
    success: function(data)
        {
//            document.write(data);
            document.getElementById("catalogue").innerHTML=data;
            document.getElementById("current").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+current_page+','+records_in_page+','+"'"+cond+"'"+')"><b>['+current_page+']</b></a>';
            if (current_page==last_page)
                {
                    document.getElementById("last").innerHTML='';
                    document.getElementById("next").innerHTML='';
                    document.getElementById("next2").innerHTML='';
                    if (last_page>1)
                    document.getElementById("first").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+',1,'+records_in_page+','+"'"+cond+"'"+')">1</a>';
                    else document.getElementById("first").innerHTML='';
                    if (last_page>=3) document.getElementById("prev").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-1)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-1)+'</a>';
                    else document.getElementById("prev").innerHTML='';
                    if (last_page>=4) document.getElementById("prev2").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-2)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-2)+'</a>';
                    else document.getElementById("prev2").innerHTML='';
                    if (last_page>=5) document.getElementById("prev3").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-3)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-3)+'</a>';
                    else document.getElementById("prev3").innerHTML='';
                    if (last_page>=6) document.getElementById("prev4").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-4)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-4)+'</a>';
                    else document.getElementById("prev4").innerHTML='';
                    if (last_page>=7) document.getElementById("prev5").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-5)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-5)+'</a>';
                    else document.getElementById("prev5").innerHTML='';
                }
            else
                if (current_page==1)
                    {
                        document.getElementById("first").innerHTML='';
                        document.getElementById("prev").innerHTML='';
                        document.getElementById("prev2").innerHTML='';
                        if (last_page>=3) document.getElementById("next").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+1)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+1)+'</a>';
                        else document.getElementById("next").innerHTML='';
                        if (last_page>=4) document.getElementById("next2").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+2)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+2)+'</a>';
                        else document.getElementById("next2").innerHTML='';
                        if (last_page>=5) document.getElementById("next3").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+3)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+3)+'</a>';
                        else document.getElementById("next3").innerHTML='';
                        if (last_page>=6) document.getElementById("next4").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+4)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+4)+'</a>';
                        else document.getElementById("next4").innerHTML='';
                        if (last_page>=7) document.getElementById("next5").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+5)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+5)+'</a>';
                        else document.getElementById("next5").innerHTML='';
                        document.getElementById("last").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+last_page+','+records_in_page+','+"'"+cond+"'"+')">'+last_page+'</a>';

                    }
                    else
                        {
                                document.getElementById("first").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+',1,'+records_in_page+','+"'"+cond+"'"+')">1</a>';
                                document.getElementById("last").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+last_page+','+records_in_page+','+"'"+cond+"'"+')">'+last_page+'</a>';
                                if (current_page>2)
                                {
                                    document.getElementById("prev").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-1)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-1)+'</a>';
                                }
                            else  document.getElementById("prev").innerHTML='';
                            if (current_page>3)
                                {
                                    document.getElementById("prev2").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-2)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-2)+'</a>';
                                }
                            else document.getElementById("prev2").innerHTML='';
                            if (current_page>4)
                                {
                                    document.getElementById("prev3").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-3)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-3)+'</a>';
                                }
                            else document.getElementById("prev3").innerHTML='';
                            if (current_page>5)
                                {
                                    document.getElementById("prev4").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-4)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-4)+'</a>';
                                }
                            else document.getElementById("prev4").innerHTML='';
                            if (current_page>6)
                                {
                                    document.getElementById("prev5").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page-5)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page-5)+'</a>';
                                }
                            else document.getElementById("prev5").innerHTML='';
                            if (current_page<=last_page-2)
                                {
                                    document.getElementById("next").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+1)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+1)+'</a>';
                                }
                            else document.getElementById("next").innerHTML='';
                            if (current_page<=last_page-3)
                                {
                                    document.getElementById("next2").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+2)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+2)+'</a>';
                                }
                            else document.getElementById("next2").innerHTML='';
                            if (current_page<=last_page-4)
                                {
                                    document.getElementById("next3").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+3)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+3)+'</a>';
                                }
                            else document.getElementById("next3").innerHTML='';
                            if (current_page<=last_page-5)
                                {
                                    document.getElementById("next4").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+4)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+4)+'</a>';
                                }
                            else document.getElementById("next4").innerHTML='';
                            if (current_page<=last_page-6)
                                {
                                    document.getElementById("next5").innerHTML='<a href="#" onClick="load_page('+"'"+catalogue+"'"+','+last_page+','+(current_page+5)+','+records_in_page+','+"'"+cond+"'"+')">'+(current_page+5)+'</a>';
                                }
                            else document.getElementById("next5").innerHTML='';
                        }
            //select_obj(catalogue,'DANAPHA');
        }
    });
}

//hàm biến đổi khi nhấn nút edit
//edit($catalogue,$_product_encoder,$_product_name,$_type_name_of_medicine,$_generic_drug,$_content,$_component,$_using_type,$_effects,$_description,$_caution,$_price,$_supplier,$_pharma_generic_drug_id,$_pharma_group_name,$_unit_name_of_medicine,$_unit_of_price_name,'','','',$_note,'',$_in_use)
function edit(catalogue,obj, value1, value2, value3, value4, value5, value6, value7, value8, value9, value10, value11, value12, value13, value14, value15, value16, value17, value18, note, history, status)
{
     var xmlhttp;
    //document.write("Minh");
    if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
//             document.write(xmlhttp.responseText);
             if (catalogue=='supplier')
                {                    
                    document.getElementById("type_name_of_supplier").innerHTML= xmlhttp.responseText;
                    document.getElementById("cancel").innerHTML='<input type="image" '+'<?php echo $img_cancel ?>' + ' onClick="cancel('+"'"+catalogue+"','"+obj+"','"+value1+"','"+document.getElementById("type_name_of_supplier_input").value+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDCancel ?>'+'"/>';
                    document.getElementById("supplier_name").innerHTML='<input type="text"  id="supplier_name_input" name="supplier_name" value="'+value1+'" style="width:100%" maxlength=100/>';
                    document.getElementById("address").innerHTML='<input type="text" id="address_input" name="address" value="'+value3+'" style="width:100%" maxlength=300/>';
                    document.getElementById("telephone").innerHTML='<input type="text" id="telephone_input" name="telephone" value="'+value4+'" style="width:100%" maxlength=300/>';
                    document.getElementById("fax").innerHTML='<input type="text" id="fax_input" name="fax" value="'+value5+'" style="width:100%" maxlength=300/>';
                    document.getElementById("note_supplier").innerHTML='<textarea id="note_supplier_input" style="width:100%" rows=2>'+note+'</textarea>';
                    document.getElementById("edit").innerHTML='<input type="hidden"/>';
                    document.getElementById("save").innerHTML='<input type="image" '+'<?php echo $img_save ?>' + ' onClick="save('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDSave ?>'+'"/>';
                    document.getElementById("history").innerHTML='<input type="text" name="supplier_name" value="'+history+'" style="width:100%" maxlength=300/>';
                    
                }
                if (catalogue=='generic_drug')
                {                    
                    document.getElementById("pharma_group_name").innerHTML= xmlhttp.responseText;
                    document.getElementById("drug_id").innerHTML='<input type="text" id="drug_id_input" name="drug_id" value="'+value3+'" style="width:100%" maxlength=300/>';
                    document.getElementById("generic_id").innerHTML='<input type="text" id="generic_id_input" name="generic_id" value="'+value8+'" style="width:100%" maxlength=300/>';
                    document.getElementById("using_type").innerHTML='<input type="text" id="using_type_input" name="using_type" value="'+value4+'" style="width:100%" maxlength=300/>';
					
					document.getElementById("hospital").innerHTML='<input type="checkbox" id="hospital_5th"><?php echo $LDhospital_5th; ?></input> <input type="checkbox" id="hospital_6th"><?php echo $LDhospital_6th; ?></input> <input type="checkbox" id="hospital_7th"><?php echo $LDhospital_7th; ?></input> <input type="checkbox" id="hospital_8th"><?php echo $LDhospital_8th; ?></input>';
					
                    document.getElementById("effects").innerHTML='<textarea id="effects_input" name="effects" style="width:100%" rows=2>'+value5+'</textarea>';
                    document.getElementById("description").innerHTML='<textarea id="description_input" style="width:100%" rows=2>'+value6+'</textarea>';
                    document.getElementById("note").innerHTML='<textarea id="note_input" style="width:100%" rows=2>'+note+'</textarea>';
                    document.getElementById("edit").innerHTML='<input type="hidden"/>';
                    document.getElementById("save").innerHTML='<input type="image" '+'<?php echo $img_save ?>' + ' onClick="save('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDSave ?>'+'"/>';
                    document.getElementById("cancel").innerHTML='<input type="image" '+'<?php echo $img_cancel ?>' + ' onClick="cancel('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDCancel ?>'+'"/>';
                    document.getElementById("history").innerHTML='<input type="text" name="supplier_name" value="'+history+'" style="width:100%" maxlength=300/>';
                }
                if ((catalogue=='medicine') || (catalogue=='vnmedicine'))
                {
                     var data= xmlhttp.responseText.split("@#");
                     document.getElementById("description").innerHTML='<textarea id="description_input" name="description" style="width:100%" rows=2>'+value8+'</textarea>';
                     document.getElementById("caution").innerHTML='<textarea id="caution_input" name="caution" style="width:100%" rows=2>'+value9+'</textarea>';
                     document.getElementById("supplier").innerHTML='<input type="text" id="supplier_input" name="supplier_input"  onkeyup="Supplier_AutoComplete()" onFocus="Supplier_AutoComplete()" value="'+value11+'" style="width:100%" maxlength=300>';
                     document.getElementById("price").innerHTML='<input type="text" id="price_input" name="price" value="'+value10+'" style="width:100%" maxlength=300>';
                     document.getElementById("currency").innerHTML=data[1];
                     document.getElementById("unit_name_of_medicine").innerHTML=data[0];
                     document.getElementById("note").innerHTML='<textarea id="note_input" name="note" style="width:100%" rows=2>'+note+'</textarea>';
                     document.getElementById("save").innerHTML='<input type="image" '+'<?php echo $img_save ?>' + ' onClick="save('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDSave ?>'+'"/>';
                     document.getElementById("cancel").innerHTML='<input type="image" '+'<?php echo $img_cancel ?>' + ' onClick="cancel('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDCancel ?>'+'"/>';

                     document.getElementById("edit").innerHTML='<input type="hidden"/>';

                     document.getElementById("type_name_of_medicine").innerHTML=data[2];
                     document.getElementById("using_type").innerHTML='<input type="text" id="using_type_input" name="using_type" value="'+value6+'" style="width:100%" maxlength=300>';
                     document.getElementById("effects").innerHTML='<textarea id="effects_input" name="effects" style="width:100%" rows=2>'+value7+'</textarea>';
                     document.getElementById("component").innerHTML='<textarea id="component_input" name="component" style="width:100%" rows=2>' +value5+ '</textarea>';
                     
					 if (catalogue=='medicine'){
						 document.getElementById("generic_drug").innerHTML='<input type="text" id="generic_drug_input" onkeyup="Generic_Drug_AutoComplete()" onFocus="Generic_Drug_AutoComplete()"  onBlur="Fill_Data_GroupName()" name="generic_drug" value="'+value3+'" style="width:100%" maxlength=300>';
						 document.getElementById("content").innerHTML='<input type="text" id="content_input" name="content" value="'+value4+'" style="width:100%" maxlength=300>';
						 document.getElementById("exp_ger_drug_id").value=value12;
					 }
                }
				if (catalogue=='medipot')
                {
                     var data= xmlhttp.responseText.split("@#");
                     document.getElementById("description").innerHTML='<textarea id="description_input" name="description" style="width:100%" rows=2>'+value8+'</textarea>';
                     document.getElementById("caution").innerHTML='<textarea id="caution_input" name="caution" style="width:100%" rows=2>'+value9+'</textarea>';
                     document.getElementById("supplier").innerHTML='<input type="text" id="supplier_input" name="supplier_input"  onkeyup="Supplier_AutoComplete()" onFocus="Supplier_AutoComplete()" value="'+value11+'" style="width:100%" maxlength=300>';
                     document.getElementById("price").innerHTML='<input type="text" id="price_input" name="price" value="'+value10+'" style="width:100%" maxlength=300>';
                     document.getElementById("currency").innerHTML=data[1];
                     document.getElementById("unit_name_of_medicine").innerHTML=data[0];
                     document.getElementById("note").innerHTML='<textarea id="note_input" name="note" style="width:100%" rows=2>'+note+'</textarea>';
                     document.getElementById("save").innerHTML='<input type="image" '+'<?php echo $img_save ?>' + ' onClick="save('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDSave ?>'+'"/>';
                     document.getElementById("cancel").innerHTML='<input type="image" '+'<?php echo $img_cancel ?>' + ' onClick="cancel('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDCancel ?>'+'"/>';

                     document.getElementById("edit").innerHTML='<input type="hidden"/>';
                }
                
        }
      }
    if ((catalogue=='medicine') || (catalogue=='vnmedicine') || (catalogue=='medipot'))
        xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&key=select_box&obj="+obj,true);
    if ((catalogue=='supplier') || (catalogue=='generic_drug'))
        xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&key=select_box&select="+value2,true);
    xmlhttp.send();
}
//hàm biến đổi khi nhấn nút cancel
function cancel(catalogue,obj, value1, value2, value3, value4, value5, value6, value7, value8, value9, value10, value11, value12, value13, value14, value15, value16, value17, value18, note, history, status)
{
    document.getElementById("save").innerHTML='<input type="hidden"/>';
    document.getElementById("cancel").innerHTML='<input type="hidden"/>';
    if (catalogue=='supplier')
    {
        document.getElementById("supplier_name").innerHTML=value1;
        //document.getElementById("status").innerHTML=status;
        document.getElementById("type_name_of_supplier").innerHTML=value6;
        document.getElementById("address").innerHTML=value3;
        document.getElementById("telephone").innerHTML=value4;
        document.getElementById("fax").innerHTML=value5;
        document.getElementById("note_supplier").innerHTML=note;       
        document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
//        document.getElementById("disable").innerHTML='<input type="button" value="'+'<?php echo $LDDisable ?>'+'"/>';
    }
    if (catalogue=='generic_drug')
    {
        document.getElementById("status").innerHTML=status;
        document.getElementById("pharma_group_name").innerHTML=value7;
        document.getElementById("drug_id").innerHTML=value3;
        document.getElementById("generic_id").innerHTML=value8;
        document.getElementById("using_type").innerHTML=value4;
        document.getElementById("effects").innerHTML=value5;
        document.getElementById("description").innerHTML=value6;
        document.getElementById("note").innerHTML=note;
		document.getElementById("hospital").innerHTML=value18;
        document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
//        document.getElementById("disable").innerHTML='<input type="button" value="'+'<?php echo $LDDisable ?>'+'"/>';
    }
    if ((catalogue=='medicine') || (catalogue=='vnmedicine'))
    {
         document.getElementById("save").innerHTML='<input type="hidden"/>';
         document.getElementById("cancel").innerHTML='<input type="hidden"/>';
         document.getElementById("product_name").innerHTML=value1;

         document.getElementById("description").innerHTML=value8;
         document.getElementById("caution").innerHTML=value9;
         document.getElementById("product_encoder").innerHTML=obj;
         document.getElementById("supplier").innerHTML='<a href="'+'<?php echo $this_file ?>' + '?catalogue=supplier&lang='+'<?php echo $lang ?>'+'&obj_primary=' +value11 + '"><b>' + value11 + '</b></a>';
         document.getElementById("price").innerHTML=value10;
         document.getElementById("type_name_of_medicine").innerHTML=value2;
		 document.getElementById("unit_name_of_medicine").innerHTML=value14;
		 document.getElementById("currency").innerHTML=value15;
		 //var temp_i = document.getElementById("unit_name_of_medicine_input").selectedIndex;
		 
         document.getElementById("note").innerHTML=note;
         document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onClick="edit('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';

         document.getElementById("component").innerHTML=value5;
         document.getElementById("using_type").innerHTML=value6;
         document.getElementById("effects").innerHTML=value7;

		 if(catalogue=='medicine'){
			 document.getElementById("pharma_group_name").innerHTML=value13;
			 document.getElementById("generic_drug").innerHTML='<a href="'+'<?php echo $this_file ?>' + '?catalogue=generic_drug&lang='+'<?php echo $lang ?>'+'&obj_primary=' +value12 + '"><b>' + value3 + '</b></a>';
			 document.getElementById("content").innerHTML=value4;
		 }

    }
    if (catalogue=='medipot')
    {
         document.getElementById("save").innerHTML='<input type="hidden"/>';
         document.getElementById("cancel").innerHTML='<input type="hidden"/>';
         document.getElementById("product_name").innerHTML=value1;

         document.getElementById("description").innerHTML=value8;
         document.getElementById("caution").innerHTML=value9;
         document.getElementById("product_encoder").innerHTML=obj;
         document.getElementById("supplier").innerHTML='<a href="'+'<?php echo $this_file ?>' + '?catalogue=supplier&lang='+'<?php echo $lang ?>'+'&obj_primary=' +value11 + '"><b>' + value11 + '</b></a>';
         document.getElementById("price").innerHTML=value10;

		 document.getElementById("unit_name_of_medicine").innerHTML=value14;
		 document.getElementById("currency").innerHTML=value15;
		 
         document.getElementById("note").innerHTML=note;
         document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onClick="edit('+"'"+catalogue+"','"+obj+"','"+value1+"','"+value2+"','"+value3+"','"+value4+"','"+value5+"','"+value6+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';

    }

}

//hàm biến đổi khi nhấn button save
//save($catalogue,$_product_encoder,$_product_name,$_type_name_of_medicine,$_generic_drug,$_content,$_component,$_using_type,$_effects,$_description,$_caution,$_price,$_supplier,$_pharma_generic_drug_id,$_pharma_group_name,$_unit_name_of_medicine,$_unit_of_price_name,'','','',$_note,'',$_in_use)

function save(catalogue,obj, value1, value2, value3, value4, value5, value6, value7, value8, value9, value10, value11, value12, value13, value14, value15, value16, value17, value18, note, history, status)
{
    if (catalogue=="generic_drug")
    {
        var hospital_th='';
		for (var j=5;j<=8;j++){
			if(document.getElementById("hospital_"+j+"th").checked)			
				hospital_th=hospital_th+'_1';	
			else 
				hospital_th=hospital_th+'_0';	
		}		
	}
	if (catalogue=="medicine" || catalogue=="vnmedicine" || catalogue=="medipot")
	{
		var temp_i = document.getElementById("unit_name_of_medicine_input").selectedIndex;
		var select_unit=document.getElementById("unit_name_of_medicine_input").options[temp_i].value;
		var select_unit_text=document.getElementById("unit_name_of_medicine_input").options[temp_i].text;
			
		temp_i = document.getElementById("unit_of_price").selectedIndex;
		var select_unitprice=document.getElementById("unit_of_price").options[temp_i].value;
		var select_unitprice_text=document.getElementById("unit_of_price").options[temp_i].text;
		
		if(catalogue!="medipot"){
			temp_i = document.getElementById("type_name_of_medicine_input").selectedIndex;
			var select_type=document.getElementById("type_name_of_medicine_input").options[temp_i].value;
			var select_type_text=document.getElementById("type_name_of_medicine_input").options[temp_i].text;
		}
	}
	
	var xmlhttp;
    if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
//            document.write("test");
            if (xmlhttp.responseText!='fail')	//if success
                {
                    if ('<?php echo $mode ?>'=='new')
                    {
                          var url='<?php echo $this_file.URL_APPEND ?>'+ '&catalogue=' + catalogue;
                          document.location.href=url;
                    }
                    else
                    {                       
                        if (catalogue=="supplier")
                        {
                            document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'"+catalogue+"','"+obj+"','"+document.getElementById("supplier_name_input").value+"','"+document.getElementById("type_name_of_supplier_input").value+"','"+document.getElementById("address_input").value+"','"+document.getElementById("telephone_input").value+"','"+document.getElementById("fax_input").value+"','"+xmlhttp.responseText+"','"+value7+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+value18+"','"+document.getElementById("note_supplier_input").value+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
							
                            document.getElementById("supplier_name").innerHTML=document.getElementById("supplier_name_input").value;
                            document.getElementById("type_name_of_supplier").innerHTML=xmlhttp.responseText;
                            document.getElementById("address").innerHTML=document.getElementById("address_input").value;
                            document.getElementById("telephone").innerHTML=document.getElementById("telephone_input").value;
                            document.getElementById("fax").innerHTML=document.getElementById("fax_input").value;
                            document.getElementById("note_supplier").innerHTML=document.getElementById("note_supplier_input").value;
                            
                        }
                        if (catalogue=="generic_drug")
                        {
                            var hospital_text='';
							if(document.getElementById("hospital_5th").checked)	hospital_text=hospital_text+'<?php echo $LDhospital_5th; ?>, ';
							if(document.getElementById("hospital_6th").checked)	hospital_text=hospital_text+'<?php echo $LDhospital_6th; ?>, ';
							if(document.getElementById("hospital_7th").checked)	hospital_text=hospital_text+'<?php echo $LDhospital_7th; ?>, ';
							if(document.getElementById("hospital_8th").checked)	hospital_text=hospital_text+'<?php echo $LDhospital_8th; ?>, ';
							hospital_text=hospital_text.substr(0,hospital_text.length-2);
							
							document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'"+catalogue+"','"+obj+"','"+value1+"','"+document.getElementById("pharma_group_name_input").value+"','"+document.getElementById("drug_id_input").value+"','"+document.getElementById("using_type_input").value+"','"+document.getElementById("effects_input").value+"','"+document.getElementById("description_input").value+"','"+xmlhttp.responseText+"','"+value8+"','"+value9+"','"+value10+"','"+value11+"','"+value12+"','"+value13+"','"+value14+"','"+value15+"','"+value16+"','"+value17+"','"+hospital_text+"','"+document.getElementById("note_input").value+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
							
                            document.getElementById("pharma_group_name").innerHTML=xmlhttp.responseText;
                            document.getElementById("drug_id").innerHTML=document.getElementById("drug_id_input").value;
                            document.getElementById("using_type").innerHTML=document.getElementById("using_type_input").value;
                            document.getElementById("effects").innerHTML=document.getElementById("effects_input").value;
                            document.getElementById("description").innerHTML=document.getElementById("description_input").value;
                            document.getElementById("note").innerHTML=document.getElementById("note_input").value;
                            document.getElementById("generic_id").innerHTML=document.getElementById("generic_id_input").value;
							document.getElementById("hospital").innerHTML=hospital_text;//value18->update
                        }
						if (catalogue=="medicine" || catalogue=="vnmedicine")
                        {
							 //cho button co gia tri moi nhap vao: 23 bien
							 
							var temp_product_name = value1; //document.getElementById("product_name").value;
							 
							var temp_generic_drug=''; var temp_content=''; var temp_ger_drug_id='';	 var temp_group_name='';
							if(catalogue=="medicine"){	 
								temp_generic_drug = document.getElementById("generic_drug_input").value;
								temp_content = document.getElementById("content_input").value;
								temp_ger_drug_id = document.getElementById("exp_ger_drug_id").value;
								temp_group_name = document.getElementById("pharma_group_name").value;
							}
							 var temp_component = document.getElementById("component_input").value;
							 var temp_using_type = document.getElementById("using_type_input").value;
							 var temp_effects = document.getElementById("effects_input").value;
							 var temp_description = document.getElementById("description_input").value;
							 var temp_caution = document.getElementById("caution_input").value;
							 var temp_price = document.getElementById("price_input").value;
							 var temp_supplier = document.getElementById("supplier_input").value;
							 
							 var temp_note = document.getElementById("note_input").value;
 
							 document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'"+catalogue+"','"+obj+"','"+temp_product_name+"','"+select_type_text+"','"+temp_generic_drug+"','"+temp_content+"','"+temp_component+"','"+temp_using_type+"','"+temp_effects+"','"+temp_description+"','"+temp_caution+"','"+temp_price+"','"+temp_supplier+"','"+temp_ger_drug_id+"','"+temp_group_name+"','"+select_unit_text+"','"+ select_unitprice_text+"','','','','"+temp_note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
							  
							 //gan gia tri update
							 document.getElementById("type_name_of_medicine").innerHTML = select_type_text;
							if(catalogue=="medicine"){ 
							 document.getElementById("generic_drug").innerHTML = '<a href="'+'<?php echo $this_file ?>'+ '?catalogue=generic_drug&lang='+'<?php echo $lang ?>'+'&obj_primary='+temp_ger_drug_id+'"><b>'+temp_generic_drug+'</b></a>';
							 document.getElementById("content").innerHTML= temp_content;
							} 
							 document.getElementById("component").innerHTML= temp_component;
							 document.getElementById("using_type").innerHTML= temp_using_type;
							 document.getElementById("effects").innerHTML= temp_effects;
							 document.getElementById("description").innerHTML= temp_description;
							 document.getElementById("caution").innerHTML=temp_caution;
                             document.getElementById("price").innerHTML=temp_price;
                             document.getElementById("supplier").innerHTML=temp_supplier;
                             //document.getElementById("pharma_group_name").value :ko doi
							 document.getElementById("unit_name_of_medicine").innerHTML=select_unit_text; 
                             document.getElementById("currency").innerHTML=select_unitprice_text;                         
                             document.getElementById("note").innerHTML=temp_note;
							 
							 //alert(xmlhttp.responseText);

                        }

						if(catalogue=="medipot")
						{
							 var temp_product_name = value1; //document.getElementById("product_name").value;
							 var temp_description = document.getElementById("description_input").value;
							 var temp_caution = document.getElementById("caution_input").value;
							 var temp_price = document.getElementById("price_input").value;
							 var temp_supplier = document.getElementById("supplier_input").value;							 
							 var temp_note = document.getElementById("note_input").value;
 
							 document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'"+catalogue+"','"+obj+"','"+temp_product_name+"','"+select_type_text+"','"+temp_generic_drug+"','"+temp_content+"','"+temp_component+"','"+temp_using_type+"','"+temp_effects+"','"+temp_description+"','"+temp_caution+"','"+temp_price+"','"+temp_supplier+"','"+temp_ger_drug_id+"','"+temp_group_name+"','"+select_unit_text+"','"+ select_unitprice_text+"','','','','"+temp_note+"','"+history+"','"+status+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
							  
							 //gan gia tri update
							 document.getElementById("description").innerHTML= temp_description;
							 document.getElementById("caution").innerHTML=temp_caution;
                             document.getElementById("price").innerHTML=temp_price;
                             document.getElementById("supplier").innerHTML=temp_supplier;
							 document.getElementById("unit_name_of_medicine").innerHTML=select_unit_text; 
                             document.getElementById("currency").innerHTML=select_unitprice_text;                         
                             document.getElementById("note").innerHTML=temp_note;
							 
							 //alert(xmlhttp.responseText);
						}
						
                        document.getElementById("cancel").innerHTML='<input type="hidden"/>';
                        document.getElementById("save").innerHTML='<input type="hidden"/>';
                    }
                   

                }
            else	//if save fail
                {
                    alert("Can't save");  
					
					cancel(catalogue,obj, value1, value2, value3, value4, value5, value6, value7, value8, value9, value10, value11, value12, value13, value14, value15, value16, value17, value18, note, history, status);
                      
                }
        }
      }
	  
	// xmlhttp.open: goi ham xu ly, de xmlhttp.send()
    if ('<?php echo $mode ?>'=='new')
    {
        if (catalogue=="supplier")
        {
            xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&value1="+document.getElementById("supplier_name_input").value+"&value2="+document.getElementById("type_name_of_supplier_input").value+"&value3="+document.getElementById("address_input").value+"&value4="+document.getElementById("telephone_input").value+"&value5="+document.getElementById("fax_input").value+"&value6="+value6+"&value7="+value7+"&value8="+value8+"&value9="+value9+"&value10="+value10+"&note="+document.getElementById("note_supplier_input").value+"&history="+history+"&key=new",true);
        }
         if (catalogue=="generic_drug")
        {				
            xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&value1="+document.getElementById("generic_drug_input").value+"&value2="+document.getElementById("pharma_group_name_input").value+"&value3="+document.getElementById("drug_id_input").value+"&value4="+document.getElementById("using_type_input").value+"&value5="+document.getElementById("description_input").value+"&value6="+document.getElementById("effects_input").value+"&value7="+value7+"&value8="+value8+"&value9="+value9+"&value10="+value10+"&note="+document.getElementById("note_input").value+"&hospital_th="+hospital_th+"&history="+history+"&key=new",true);
        }

    }
    else
    {
        if (catalogue=="supplier")
        {
            xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&value1="+document.getElementById("supplier_name_input").value+"&value2="+document.getElementById("type_name_of_supplier_input").value+"&value3="+document.getElementById("address_input").value+"&value4="+document.getElementById("telephone_input").value+"&value5="+document.getElementById("fax_input").value+"&value6="+value6+"&value7="+value7+"&value8="+value8+"&value9="+value9+"&value10="+value10+"&note="+document.getElementById("note_supplier_input").value+"&history="+history+"&key=save",true);
        }
         if (catalogue=="generic_drug")
        {
			xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&value1="+value1+"&value2="+document.getElementById("pharma_group_name_input").value+"&value3="+document.getElementById("drug_id_input").value+"&value4="+document.getElementById("using_type_input").value+"&value5="+document.getElementById("description_input").value+"&value6="+document.getElementById("effects_input").value+"&value7="+document.getElementById("generic_id_input").value+"&value8="+value8+"&value9="+value9+"&value10="+value10+"&note="+document.getElementById("note_input").value+"&hospital_th="+hospital_th+"&history="+history+"&key=save",true);
        }
        if (catalogue=="medicine" || catalogue=="vnmedicine")
        {			
			var temp_ger_drug_id='';
			var temp_content='';
			if(catalogue=="medicine"){
				temp_ger_drug_id = document.getElementById("exp_ger_drug_id").value;
				temp_content = document.getElementById("content_input").value;
			}
		
			xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&value1="+value1+"&value2="+temp_ger_drug_id+"&value3="+temp_content+"&value4="+document.getElementById("component_input").value+"&value5="+document.getElementById("using_type_input").value+"&value6="+select_type+"&value7="+select_unit+"&value8="+document.getElementById("caution_input").value+"&value9="+document.getElementById("supplier_input").value+"&value10="+document.getElementById("price_input").value+"&value11="+select_unitprice+"&value12="+status+"&value13="+document.getElementById("description_input").value+"&value14="+document.getElementById("effects_input").value+"&value15="+document.getElementById("note_input").value+"&history="+history+"&key=save",true);
        }
		if (catalogue=="medipot") //chua lam
        {			
			xmlhttp.open("GET",process_file+"?catalogue="+catalogue+"&obj="+obj+"&value1="+value1+"&value2=&value3=&value4=&value5=&value6=&value7="+select_unit+"&value8="+document.getElementById("caution_input").value+"&value9="+document.getElementById("supplier_input").value+"&value10="+document.getElementById("price_input").value+"&value11="+select_unitprice+"&value12="+status+"&value13="+document.getElementById("description_input").value+"&value14=&value15="+document.getElementById("note_input").value+"&history="+history+"&key=save",true);
        }
    }
    
    xmlhttp.send();    
}

//HÀM SELECT
function select_obj(catalogue,obj)
{   
    var xmlhttp;

    if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var url='<?php echo $this_file.URL_APPEND ?>' + '&catalogue=' + catalogue + '&obj_primary=' + obj;
            history.pushState(stateObj,"",url);            
            if ('<?php echo $mode ?>'=='new') window.open (url,'');
            else
            {
                //document.write(xmlhttp.responseText);
                var s=xmlhttp.responseText;
				
                var myhash = eval( 'mkhash('+s+')' );
				//var temp = mkhash(s);
				//var myhash = eval(temp);
				
                document.getElementById("save").innerHTML='<input type="hidden"/>';
                document.getElementById("cancel").innerHTML='<input type="hidden"/>';
                if (catalogue=='supplier')
                    {
                        document.getElementById("supplier").innerHTML=obj;
                        document.getElementById("supplier_name").innerHTML=myhash["supplier_name"];
                        document.getElementById("type_name_of_supplier").innerHTML=myhash["type_name_of_supplier"];
                        document.getElementById("address").innerHTML=myhash["address"];
                        document.getElementById("telephone").innerHTML=myhash["telephone"];
                        document.getElementById("fax").innerHTML=myhash["fax"];
                        document.getElementById("note_supplier").innerHTML=myhash["note"];
                        document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'supplier','"+obj+"','"+myhash["supplier_name"]+"','"+myhash["type_of_supplier"]+"','"+myhash["address"]+"','"+myhash["telephone"]+"','"+myhash["fax"]+"','"+myhash["type_name_of_supplier"]+"','','','','','','','','','','','','','"+myhash["note"]+"','','"+'1'+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';

                    }
                if (catalogue=='generic_drug')
                    {
                        document.getElementById("generic_drug").innerHTML=myhash["generic_drug"];
                        document.getElementById("drug_id").innerHTML=myhash["drug_id"];
                        document.getElementById("generic_id").innerHTML=myhash["generic_drug_id"];
                        document.getElementById("pharma_group_name").innerHTML=myhash["pharma_group_name"];
                        document.getElementById("using_type").innerHTML=myhash["using_type"];
                        document.getElementById("effects").innerHTML=myhash["effects"];
                        document.getElementById("description").innerHTML=myhash["description"];
                        document.getElementById("note").innerHTML=myhash["note"];
						
						var temp_th='';
						if(myhash["hospital_5th"]=='1') temp_th=temp_th+'<?php echo $LDhospital_5th; ?>, ';
						if(myhash["hospital_6th"]=='1') temp_th=temp_th+'<?php echo $LDhospital_6th; ?>, ';
						if(myhash["hospital_7th"]=='1') temp_th=temp_th+'<?php echo $LDhospital_7th; ?>, ';
						if(myhash["hospital_8th"]=='1') temp_th=temp_th+'<?php echo $LDhospital_8th; ?>, ';
						temp_th=temp_th.substr(0,temp_th.length-2);
						document.getElementById("hospital").innerHTML=temp_th;						
						
                        document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'generic_drug','"+obj+"','"+myhash["generic_drug"]+"','"+myhash["pharma_group_id"]+"','"+myhash["drug_id"]+"','"+myhash["using_type"]+"','"+myhash["effects"]+"','"+myhash["description"]+"','"+myhash["pharma_group_name"]+"','"+myhash["generic_drug_id"]+"','','','','','','','','','','"+temp_th+"','"+myhash["note"]+"','','"+'1'+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
                    }

                if ((catalogue=='medicine')||(catalogue=='vnmedicine'))
                    {
                        document.getElementById("product_name").innerHTML=myhash["product_name"];
                        document.getElementById("caution").innerHTML=myhash["caution"];
                        document.getElementById("description").innerHTML=myhash["description"];
                        document.getElementById("supplier").innerHTML='<a href="'+'<?php echo $this_file ?>' + '?catalogue=supplier&lang='+'<?php echo $lang ?>'+'&obj_primary=' +myhash["supplier"] + '"><b>' + myhash["supplier"] + '</b></a>';
                        document.getElementById("product_encoder").innerHTML=obj;
						document.getElementById("unit_name_of_medicine").innerHTML=myhash["unit_name_of_medicine"];
                        
                        document.getElementById("price").innerHTML=myhash["price"];
                        document.getElementById("currency").innerHTML=myhash["short_name"] ;
                        document.getElementById("note").innerHTML=myhash["note"];
						
                        document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit(\''+catalogue+"','"+obj+"','"+myhash["product_name"]+"','"+myhash["type_name_of_medicine"]+"','"+myhash["generic_drug"]+"','"+myhash["content"]+"','"+myhash["component"]+"','"+myhash["using_type"]+"','"+myhash["effects"]+"','"+myhash["description"]+"','"+myhash["caution"]+"','"+myhash["price"]+"','"+myhash["supplier"]+"','"+myhash["pharma_generic_drug_id"]+"','"+myhash["pharma_group_name"]+"','"+myhash["unit_name_of_medicine"]+"','"+myhash["short_name"]+"','','','','"+myhash["note"]+"','','"+'1'+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';

                        document.getElementById("component").innerHTML=myhash["component"];
                        document.getElementById("type_name_of_medicine").innerHTML=myhash["type_name_of_medicine"];
                        document.getElementById("using_type").innerHTML=myhash["using_type"];
                        document.getElementById("effects").innerHTML=myhash["effects"];
						if(catalogue=='medicine'){
							document.getElementById("pharma_group_name").innerHTML=myhash["pharma_group_name"];
							document.getElementById("generic_drug").innerHTML='<a href="'+'<?php echo $this_file ?>' + '?catalogue=generic_drug&lang='+'<?php echo $lang ?>'+'&obj_primary=' + myhash["pharma_generic_drug_id"] + '"><b>' + myhash["generic_drug"] + '</b></a>';
							document.getElementById("content").innerHTML=myhash["content"];
						}
                    }
                if (catalogue=='medipot')
                    {
                        document.getElementById("product_name").innerHTML=myhash["product_name"];
                        document.getElementById("caution").innerHTML=myhash["caution"];
                        document.getElementById("description").innerHTML=myhash["description"];
                        document.getElementById("supplier").innerHTML='<a href="'+'<?php echo $this_file ?>' + '?catalogue=supplier&lang='+'<?php echo $lang ?>'+'&obj_primary=' +myhash["supplier"] + '"><b>' + myhash["supplier"] + '</b></a>';
                        document.getElementById("product_encoder").innerHTML=obj;
						document.getElementById("unit_name_of_medicine").innerHTML=myhash["unit_name_of_medicine"];
                        
                        document.getElementById("price").innerHTML=myhash["price"];
                        document.getElementById("currency").innerHTML=myhash["short_name"] ;
                        document.getElementById("note").innerHTML=myhash["note"];

                        document.getElementById("edit").innerHTML='<input type="image" '+'<?php echo $img_edit ?>' + ' onclick="edit('+"'medipot','"+obj+"','"+myhash["product_name"]+"','"+myhash["type_name_of_medicine"]+"','','','','','"+myhash["effects"]+"','"+myhash["description"]+"','"+myhash["caution"]+"','"+myhash["price"]+"','"+myhash["supplier"]+"','','"+myhash["pharma_group_name"]+"','"+myhash["unit_name_of_medicine"]+"','"+myhash["short_name"]+"','','','','"+myhash["note"]+"','','"+'1'+"'"+')" title="'+'<?php echo $LDEdit ?>'+'"/>';
                    }

            }
        }
      }
    xmlhttp.open("GET",process_file+"?key=select_obj&catalogue="+catalogue+"&obj="+obj,true);
    xmlhttp.send();
}
</script>
<?php
//-------------HÀM TRUY VẤN CSDL -> TRẢ VỀ TRANG ĐẦU CỦA DANH MỤC----------------
function load_catalogue(){
    GLOBAL $root_path;
    GLOBAL $records; GLOBAL $records_in_page; GLOBAL $total_pages;
    GLOBAL $catalogue; GLOBAL $mode;
    GLOBAL $quick;  GLOBAL $cond; GLOBAL $attribute; GLOBAL $group_key;
    GLOBAL $obj_info;  GLOBAL $obj_primary;
    GLOBAL $LDTitle; GLOBAL $LDTitleSupplier; GLOBAL $LDTitleGeneric; GLOBAL $LDTitleMedicine;
    GLOBAL $LDTitleVnMedicine; GLOBAL $LDTitleMedipot; GLOBAL $LDSearchInputText;
    GLOBAL $LDFirst; GLOBAL $LDPrev; GLOBAL $LDPrev2; GLOBAL $LDCurrent; GLOBAL $LDLast; GLOBAL $LDNext; GLOBAL $LDNext2; GLOBAL $LDNext3; GLOBAL $LDNext4; GLOBAL $LDNext5;
    if ($quick==$LDSearchInputText) $quick='';

    $sCatalogueInfo=load_catalogue1($catalogue, $quick, $attribute, $group_key, $records_in_page, 1, &$records);//lấy trang 1 của danh mục + tổng số kết quả

    //lấy từng đối tượng đầu tiên của danh mục
    //------------------------SUPPLIER-------------------------
    if ($catalogue=='supplier')
    {
        $LDTitle=$LDTitleSupplier;//Tiêu đề danh mục
                if ($records>0)
                    {
                         $_obj=new Supplier();
                        if ($mode=='') $mode='view';
                        if ($obj_primary=='')
                            $obj_info=$_obj->GetFirstSupplierCatalogue($quick, $group_key, $attribute)->FetchRow();
                        else $obj_info=$_obj->GetSupplierInfo($obj_primary, $username)->FetchRow();
                    }
    }
    else
    {
         $_obj=new Product();
        //-------------------------GENERIC_DRUG----------------------------------
        if ($catalogue=='generic_drug')
        {
             $LDTitle=$LDTitleGeneric;//Tiêu đề danh mục
             if ($records>0)
                        {
                            if ($mode=='') $mode='view';
                            if ($obj_primary=='')
                                 $obj_info=$_obj->GetFirstGenericDrugCatalogue($quick, $group_key, $attribute)->FetchRow();
                            else $obj_info=$_obj->GetGenericDrugInfo($obj_primary, $username)->FetchRow();
                        }
        }
        else
        {
            //-------------MEDICINE, VNMEDICINE, MEDIPOT----------------------            
                if ($catalogue=='medicine')
                {
                     $LDTitle=$LDTitleMedicine;//tiêu đề
                     if ($records>0)
                        {
                            if ($mode=='') $mode='view';
                            if ($obj_primary=='')
                                 $obj_info=$_obj->GetFirstMedicineCatalogue($quick, $group_key, $attribute)->FetchRow();
                            else $obj_info=$_obj->GetMedicineInfo($obj_primary, $username)->FetchRow();
                        }
                }
                else
                    if ($catalogue=='vnmedicine')
                    {
                         $LDTitle=$LDTitleVnMedicine;
                         if ($records>0)
                            {
                                if ($mode=='') $mode='view';                                
                                if ($obj_primary=='')
                                    $obj_info=$_obj->GetFirstVnMedicineCatalogue($quick, $group_key, $attribute)->FetchRow();
                                else $obj_info=$_obj->GetVnMedicineInfo($obj_primary, $username)->FetchRow();
                            }
                    }
                    else
                        if ($catalogue=='medipot')
                        {
                             $LDTitle=$LDTitleMedipot;
                             if ($records>0)
                                {
                                    if ($mode=='') $mode='view';
                                    if ($obj_primary==''){
                                       if($result=$_obj->GetFirstMedipotCatalogue($quick, $group_key, $attribute))
											$obj_info=$result->FetchRow();
									}
                                    else if ($result=$_obj->GetMedipotInfo($obj_primary, $username))
										$obj_info=$result->FetchRow();
                                }
                        }
                 
                }
        }
    //phân trang, current_page=1
    $LDFirst='';
    $LDPrev='';
    $LDPrev2='';
    $LDPrev3='';
    $LDPrev4='';
    $LDPrev5='';
    if ($sCatalogueInfo=='')
     {
        //$sCatalogueInfo='<tr><td><br></br><p align="center" valign="middle"><font color=#000000 size=1><b>'.$LDNotFound.'<b><font></p><br></br></td></tr>';
        $LDCurrent='';
        $LDLast='';
        $LDNext='';
        $LDNext2='';
        $LDNext3='';
        $LDNext4='';
        $LDNext5='';

     }
     else
     {
         if ($records%$records_in_page==0) $total_pages=$records/$records_in_page;
         else $total_pages=(int)($records/$records_in_page) + 1;
         $LDCurrent='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.',1,'.$records_in_page.','."$quick".')"><b>[1]</b></a>';
        
         if ($total_pages>1) $LDLast='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.','.$total_pages.','.$records_in_page.','."'$quick'".')">'.$total_pages.'</a>';
         else
         {
             $LDLast='';
             $LDNext='';
             $LDNext2='';
             $LDNext3='';
             $LDNext4='';
             $LDNext5='';
         }
         if ($total_pages>=3)  $LDNext='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.',2,'.$records_in_page.','."'$quick'".')">2</a>';
         else $LDNext='';
         if ($total_pages>=4)  $LDNext2='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.',3,'.$records_in_page.','."'$quick'".')">3</a>';
         else $LDNext2='';
         if ($total_pages>=5)  $LDNext3='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.',3,'.$records_in_page.','."'$quick'".')">4</a>';
         else $LDNext3='';
         if ($total_pages>=6)  $LDNext4='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.',3,'.$records_in_page.','."'$quick'".')">5</a>';
         else $LDNext4='';
         if ($total_pages>=7)  $LDNext5='<a href="#" onClick="load_page('."'$catalogue'".','.$total_pages.',3,'.$records_in_page.','."'$quick'".')">6</a>';
         else $LDNext5='';
     }
return $sCatalogueInfo;
}

//-------------HÀM TRUY VẤN CƠ SỞ DỮ LIỆU -> TRẢ VỀ DROPDOWN MENU---------------
function load_dropdownmenu($Name)
{
    GLOBAL $catalogue;
    GLOBAL $quick;  GLOBAL $attribute; GLOBAL $group_key;
     if ($group_key!='')
            $dropdown_menu='<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick",'','').'">'.$Name.'</a></li>';
        //------------------------SUPPLIER-------------------------
     if ($catalogue=='supplier')
    {
         if ($group_key=='')
         $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$Name.'</span></a> <ul class="sub">';
         $_obj=new Supplier();
         $type_info=&$_obj->GetAllSupplierType();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                  if ($type["type_of_supplier"]!=$group_key)
                        $dropdown_menu=$dropdown_menu.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["type_of_supplier"],'','').'">'.$type["type_name_of_supplier"].'</a></li>';
                  else
                  $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["type_of_supplier"],'','').'" id="dropdown menu" class="top_link"><span class="down">'.$type["type_name_of_supplier"].'</span></a> <ul class="sub">';
              }             
         }
    }
     if ($catalogue=='generic_drug' || $catalogue=='medicine')
    {
         if ($group_key=='')
         $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$Name.'</span></a> <ul class="sub">';
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName();
         //đưa dữ liệu ra chuỗi
         if (is_object($type_info))
         {
               while($type=$type_info->FetchRow())
              {
                   if ($type["pharma_group_id"]!=$group_key)
                        $dropdown_menu=$dropdown_menu.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["pharma_group_id"],'','').'">'.$type["pharma_group_name"].'</a></li>';
                   else
                        $dropdown_menu_temp='<ul id="nav"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue."&attribute=$attribute&quick=$quick&group_key=".$type["pharma_group_id"],'','').'" id="dropdown menu" class="top_link"><span class="down">'.$type["pharma_group_name"].'</span></a> <ul class="sub">';
              }
         }
    }


    $dropdown_menu=$dropdown_menu_temp.$dropdown_menu.'</ul></li></ul>';
    return $dropdown_menu;
}
//---------------------------------------------------DROPDOWN MENU----------------------------------------------------------------
//tạo dropdown danh mục
function load_dropdown_catalogue()
{
    GLOBAL $dropdown_catalogue; GLOBAL $catalogue;
    GLOBAL $LDMedicineCatalogueTxt; GLOBAL $LDVNMedicineCatalogueTxt; GLOBAL $LDMedipotCatalogueTxt; GLOBAL $LDGenericDrugCatalogueTxt; GLOBAL $LDSupplierCatalogueTxt;
    if ($catalogue=='medicine')
        $dropdown_catalogue_temp='<ul id="nav2"><li class="top"><a href="'.strtr($this_file.URL_APPEND."&catalogue=$catalogue",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDMedicineCatalogueTxt.'</span></a> <ul class="sub">';
    else
        $dropdown_catalogue=$dropdown_catalogue.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=medicine','','').'">'.$LDMedicineCatalogueTxt.'</a></li>';
    if ($catalogue=='vnmedicine')
        $dropdown_catalogue_temp='<ul id="nav2"><li class="top"><a href="'.strtr($this_file.URL_APPEND."&catalogue=$catalogue",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDVNMedicineCatalogueTxt.'</span></a> <ul class="sub">';
    else
        $dropdown_catalogue=$dropdown_catalogue.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=vnmedicine','','').'">'.$LDVNMedicineCatalogueTxt.'</a></li>';
    if ($catalogue=='medipot')
        $dropdown_catalogue_temp='<ul id="nav2"><li class="top"><a href="'.strtr($this_file.URL_APPEND."&catalogue=$catalogue",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDMedipotCatalogueTxt.'</span></a> <ul class="sub">';
    else
        $dropdown_catalogue=$dropdown_catalogue.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=medipot','','').'">'.$LDMedipotCatalogueTxt.'</a></li>';
    if ($catalogue=='generic_drug')
        $dropdown_catalogue_temp='<ul id="nav2"><li class="top"><a href="'.strtr($this_file.URL_APPEND."&catalogue=$catalogue",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDGenericDrugCatalogueTxt.'</span></a> <ul class="sub">';
    else
        $dropdown_catalogue=$dropdown_catalogue.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=generic_drug','','').'">'.$LDGenericDrugCatalogueTxt.'</a></li>';
    if ($catalogue=='supplier')
        $dropdown_catalogue_temp='<ul id="nav2"><li class="top"><a href="'.strtr($this_file.URL_APPEND."&catalogue=$catalogue",'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDSupplierCatalogueTxt.'</span></a> <ul class="sub">';
    else
        $dropdown_catalogue=$dropdown_catalogue.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier','','').'">'.$LDSupplierCatalogueTxt.'</a></li>';
    $dropdown_catalogue=$dropdown_catalogue_temp.$dropdown_catalogue.'</ul></li></ul>';
}

//tạo dropdown search option
function load_dropdown_search()
{
    GLOBAL $dropdown_search; GLOBAL $catalogue;
    GLOBAL $quick;  GLOBAL $cond; GLOBAL $attribute; GLOBAL $group_key;
    GLOBAL $LDBySupplierName;    GLOBAL $LDByFullName;     GLOBAL $LDByAddress;     GLOBAL $LDByAll;
    GLOBAL $LDByGenericDrugName;    GLOBAL $LDMedicine;    GLOBAL $LDVNMedicine;    GLOBAL $LDBySupplier;    GLOBAL $LDByEncoder;    GLOBAL $LDByComponent;
    if ($catalogue=='supplier')
    {
        if ($attribute=='')//''=Tất cả
            $dropdown_search='<ul id="nav3"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute='.$attribute.'&quick='.$quick.'&group_key='.$group_key,'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDByAll.'</span></a> <ul class="sub">';
        else
            $dropdown_search=$dropdown_search.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute=&quick='.$quick.'&group_key='.$group_key,'','').'">'.$LDByAll.'</a></li>';
        if ($attribute==1) //1=Tên viết tắt
            $dropdown_search_temp='<ul id="nav3"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute='.$attribute.'&quick='.$quick.'&group_key='.$group_key,'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDBySupplierName.'</span></a> <ul class="sub">';
        else
            $dropdown_search=$dropdown_search.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute=1&quick='.$quick.'&group_key='.$group_key,'','').'">'.$LDBySupplierName.'</a></li>';
        if ($attribute==2) //1=Tên đầy đủ
            $dropdown_search_temp='<ul id="nav3"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute='.$attribute.'&quick='.$quick.'&group_key='.$group_key,'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDByFullName.'</span></a> <ul class="sub">';
        else
            $dropdown_search=$dropdown_search.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute=2&quick='.$quick.'&group_key='.$group_key,'','').'">'.$LDByFullName.'</a></li>';
        if ($attribute==3) //1=Địa chỉ
            $dropdown_search_temp='<ul id="nav3"><li class="top"><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute='.$attribute.'&quick='.$quick.'&group_key='.$group_key,'','').'" id="dropdown menu" class="top_link"><span class="down">'.$LDByAddress.'</span></a> <ul class="sub">';
        else
            $dropdown_search=$dropdown_search.'<li><a href="'.strtr($this_file.URL_APPEND.'&catalogue=supplier&attribute=3&quick='.$quick.'&group_key='.$group_key,'','').'">'.$LDByAddress.'</a></li>';
        $dropdown_search=$dropdown_search_temp.$dropdown_search.'</ul></li></ul>';
    }
}


//********************************************************************************************************
//-----------SMARTY-----------------------
//---------------------------------------------------------------------------------------------------------
//smarty-> gán giá trị cho các template 
 $smarty = new smarty_care('common');
 # Added for the common header top block
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPharmacy')");
 $smarty->assign('breakfile',$breakfile);
 # Window bar title
 $smarty->assign('Name',$LDPharmacy);
 # Add the bot onLoad code
 if(isset($stb) && $stb) $smarty->assign('sOnLoadJs','onLoad="startbot()"');
 #Collect extra javascript code
 ob_start(); 
 $sTemp = ob_get_contents();
 ob_end_clean();
// Append javascript to JavaScript block
 $smarty->append('JavaScript',$sTemp);

 //-----------------CÁC PHẦN CHUNG CỦA TEMPLATE----------------------
 //---------------------------------------------------------------------------------------------------------
 //------------------BUTTON, PAGE NUMBER----------------------
 //$smarty->assign('LDAll','<p id="all"/><input type="button" value="'.$LDAll.'"/>'); 
 $smarty->assign('LDAll','<u><a href="'.strtr($this_file.URL_APPEND.'&catalogue='.$catalogue,'','').'">'.$LDAll.'</a></u>');
 $smarty->assign('LDNew','<a href="'.strtr($this_file.URL_APPEND.'&mode=new&catalogue='.$catalogue,'','').'"><image '.$img_new.' title="'.$LDNew.'"/>');
 $smarty->assign('LDSave','<p id="save"/>');
 $smarty->assign('LDCancel','<p id="cancel"/>');
 load_dropdown_search();
 $smarty->assign('LDDropdownSearch',$dropdown_search);
 load_dropdown_catalogue();
 $smarty->assign('LDDropDownCatalogue',$dropdown_catalogue);
 


 //---------------SEARCH-----------------------------
 $LDSearchInput=$quick; //Gán nội dung tìm kiếm cũ vào thanh tìm kiếm
 //$smarty->assign('LDSearchInput','<html><body onload="init_load()"><form effects="'.strtr($this_file.'?'.$_GET["ntid"].''.$_GET["lang"].''.$_GET["mode"].''.$_GET["catalogue"].''.$_GET["quick"],'','').'" method="get"><input type="text" size=43 name="quick" id="quick" value="'."".'"></body></html>');
 $smarty->assign('LDSearchInput','<form style="margin: 0px; padding: 0px;" effects="'.strtr($this_file.'?'.$_GET["ntid"].''.$_GET["lang"].''.$_GET["mode"].''.$_GET["catalogue"].''.$_GET["quick"],'','').'" method="get"><input type="text" style="width:100%;"  name="quick" id="quick" onfocus="if(this.value=='."'".$LDSearchInputText."'".') this.value='."''".'" onblur="if(this.value=='."''".') this.value='."'".$LDSearchInputText."'".'" value="'.$LDSearchInput.'"'.'>');
 //$smarty->assign('LDSearchInput','<input type="text" style="width:96%"  name="quick" id="quick" onfocus="if(this.value=='."'".$LDSearchInputText."'".') this.value='."''".'" onblur="if(this.value=='."''".') this.value='."'".$LDSearchInputText."'".'" value="'.$LDSearchInput.'"'.'>');
 $smarty->assign('LDSearchButton','<input type="image" '.$img_search.' name="search_button" title="'.$LDSearchButton.'"><input type="hidden" name="ntid" value="false"><input type="hidden" name="mode" value=""><input type="hidden" name="lang" value="'.$lang.'"><input type="hidden" name="catalogue" value="'.$catalogue.'"></form>');
 $smarty->assign('LDSearchBy',$LDSearchBy);

 //*********************************************************************************************************
//load smarty (chỉ mục-> các biến ngôn ngữ) cho từng loại danh mục riêng
//-------------SUPPLIER-----------------------
//--------------------------------------------------------------------------------------------------------
 if ($catalogue=='supplier')
 {
     $smarty->assign('LDOption', load_dropdownmenu($Type_Supplier));
     $Title=$LDTitleSupplier;
     $smarty->assign('LDTitle2',$LDTitle2Supplier);
     $smarty->assign('LDSupplier',''.$LDSupplier);
     $smarty->assign('LDSupplierName',''.$LDSupplier_name);
     $smarty->assign('LDStatus',''.$LDStatus);//trạng thái
     $smarty->assign('LDType',''.$LDSupplier_Type);//dạng cung cấp
     $smarty->assign('LDAddress',''.$LDAddress);
     $smarty->assign('LDTel',''.$LDTelephone);
     $smarty->assign('LDFax',''.$LDFax);
     $smarty->assign('LDNote',''.$LDNote);//ghi chú
     if ($group_key!='')
     {
         $_obj=new Supplier();
         $type_info=&$_obj->GetSupplierType($group_key)->FetchRow();
         $group_name=$type_info["type_name_of_supplier"];
         $smarty->assign('LDGroupName', $group_name);
     }
 }
 //--------------GENERIC_DRUG-----------------
 //---------------------------------------------------------------------------------------------------------
  if ($catalogue=='generic_drug')
 {
     $smarty->assign('LDOption', load_dropdownmenu($Type_Generic_Drug));
     $Title=$LDTitleGeneric;
     $smarty->assign('LDTitle2',$LDTitle2Generic);
     $smarty->assign('LDGenericName',$LDGeneric);//tên thuốc gốc
     $smarty->assign('LDGenericId',$LDNumber);//thứ tự hoạt chất trong danh mục
     $smarty->assign('LDDrugId',$LDDrugNumber);//thứ tự thuốc trong danh mục
     $smarty->assign('LDGroup',$LDGroup);//nhóm thuốc
     $smarty->assign('LDStatus',$LDStatus);//trạng thái
     $smarty->assign('LDUsingType',$LDUsing_Type);//đường dùng
     $smarty->assign('LDHospital',$LDHospital);//Tuyến sử dụng
     $smarty->assign('LDEffects',$LDEffects);//Tác dụng
     $smarty->assign('LDUsing',$LDUsing);//Cách sử dụng
     $smarty->assign('LDCaution',$LDCaution);//Thận trọng
      if ($group_key!='')
     {
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName($group_key)->FetchRow();
         $group_name=$type_info["pharma_group_name"];
         $smarty->assign('LDGroupName', $group_name);
     }
 }
 //------------MEDICINE----------------------
 //---------------------------------------------------------------------------------------------------------
  if ($catalogue=='medicine')
 {
     $smarty->assign('LDOption', load_dropdownmenu($Type_Generic_Drug));
     $Title=$LDTitleMedicine;
     $smarty->assign('LDTitle2',$LDTitle2Medicine);
     $smarty->assign('LDArticleName',$LDArticleName);//Biệt dược
     $smarty->assign('LDStatus',''.$LDStatus); //Trạng thái
     $smarty->assign('LDGeneric',''.$LDGeneric);//Thuốc gốc
	 $smarty->assign('LDGroupNameMed',''.$LDGroupNameMed);//Nhóm thuốc
     $smarty->assign('LDContent',''.$LDContent);//Hàm lượng
     $smarty->assign('LDComponent',''.$LDComponent);//Thành phần     
     $smarty->assign('LDType_of_Medicine',''.$LDType_of_Medicine);//Dạng thuốc
     $smarty->assign('LDUsing_Type',$LDUsing_Type);//Đường dùng     
     $smarty->assign('LDEffects',$LDEffects);//Tác dụng
     $smarty->assign('LDDescription',''.$LDDescription);//Cách dùng
     $smarty->assign('LDCaution',''.$LDCaution);//Thận trọng
     $smarty->assign('LDRegNo',''.$LDRegNo);//Số đăng kí
     $smarty->assign('LDSupplier',''.$LDSupplier);//Nhà cung cấp
     $smarty->assign('LDPrice',''.$LDPrice);//Giá
     $smarty->assign('LDUnit',''.$LDUnit);//Đơn vị tính     
     $smarty->assign('LDNote',''.$LDNote);//Ghi chú
     if ($group_key!='')
     {
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName($group_key)->FetchRow();
         $group_name=$type_info["pharma_group_name"];
         $smarty->assign('LDGroupName', $group_name);
     }
     }
 //------------VNMEDICINE----------------------
 //---------------------------------------------------------------------------------------------------------
 if ($catalogue=='vnmedicine')
 {
    $smarty->assign('LDOption', load_dropdownmenu($Type_Generic_Drug));
     $Title=$LDTitleMedicine;
     $smarty->assign('LDTitle2',$LDTitle2Medicine);
     $smarty->assign('LDArticleName',$LDVNMedicineName);//Tên thuốc
     $smarty->assign('LDStatus',''.$LDStatus); //Trạng thái
	 //$smarty->assign('LDGeneric',''.$LDGeneric);//Thuốc gốc
	 //$smarty->assign('LDGroupNameMed',''.$LDGroupNameMed);//Nhóm thuốc
	 //$smarty->assign('LDContent',''.$LDContent);//Hàm lượng
     $smarty->assign('LDComponent',''.$LDComponent);//Thành phần
     $smarty->assign('LDType_of_Medicine',''.$LDType_of_Medicine);//Dạng thuốc
     $smarty->assign('LDUsing_Type',$LDUsing_Type);//Đường dùng
     $smarty->assign('LDEffects',$LDEffects);//Tác dụng
     $smarty->assign('LDDescription',''.$LDDescription);//Cách dùng
     $smarty->assign('LDCaution',''.$LDCaution);//Thận trọng
     $smarty->assign('LDRegNo',''.$LDRegNo);//Số đăng kí
     $smarty->assign('LDSupplier',''.$LDSupplier);//Nhà cung cấp
     $smarty->assign('LDPrice',''.$LDPrice);//Giá
     $smarty->assign('LDUnit',''.$LDUnit);//Đơn vị tính
     $smarty->assign('LDNote',''.$LDNote);//Ghi chú
     if ($group_key!='')
     {
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName($group_key)->FetchRow();
         $group_name=$type_info["pharma_group_name"];
         $smarty->assign('LDGroupName', $group_name);
     }
 }
 //------------MEDIPOT----------------------
 //---------------------------------------------------------------------------------------------------------
    if ($catalogue=='medipot')
 {
     $smarty->assign('LDOption', load_dropdownmenu($Type_Generic_Drug));
     $Title=$LDTitleMedicine;
     $smarty->assign('LDTitle2',$LDTitle2Medicine);
     $smarty->assign('LDArticleName',$LDArticleName);//Tên vật tư y tế
     $smarty->assign('LDStatus',''.$LDStatus); //Trạng thái
     $smarty->assign('LDUsing_Type',$LDUsing_Type);//Đường dùng
     $smarty->assign('LDDescription',''.$LDDescription);//Cách dùng
     $smarty->assign('LDCaution',''.$LDCaution);//Thận trọng
     $smarty->assign('LDRegNo',''.$LDRegNo);//Số đăng kí
     $smarty->assign('LDSupplier',''.$LDSupplier);//Nhà cung cấp
     $smarty->assign('LDPrice',''.$LDPrice);//Giá
     $smarty->assign('LDUnit',''.$LDUnit);//Đơn vị tính
     $smarty->assign('LDNote',''.$LDNote);//Ghi chú
     if ($group_key!='')
     {
         $_obj=new Product();
         $type_info=&$_obj->GetPharmaGroupName($group_key)->FetchRow();
         $group_name=$type_info["pharma_group_name"];
         $smarty->assign('LDGroupName', $group_name);
     }
 }
//---------GÁN CÁC TIÊU ĐỀ----------------
 $Pharma_Title='<a href="'.$breakfile.'" style="color:#FFF; font-weight:bold;">'.$LDPharmacy.'</a>';
 $smarty->assign('sToolbarTitle',$Pharma_Title);

//***********************************************************************************************************
 //---------------------------PHẦN XỬ LÝ CÁC MODE--------------------------------
//-----------------------------------------------------------------------------------------------------------
//---------------------------SAVE-----------------------------------------------
//lưu thông tin từ đối tượng MỚI tạo của danh mục
if ($mode=='save') //Lưu vào CSDL
{
    //-------------SUPPLIER-----------------------
    if ($catalogue=='supplier')
    {              
        $_obj=new Supplier();
        $catalogue_info=&$_obj->AddSupplier($supplier, $supplier_name, 1, $address, $telephone, $fax, $note);
        $obj_primary=$supplier;

    }
    else
    //-------------GENERIC_DRUG-----------------------
    if ($catalogue=='generic_drug')
    {
        $_obj=new Product();
        $obj_primary=$_obj->AddGenericDrug($generic_drug, $pharma_group_id, $generic_drug_id, $drug_id, $generic_drug_id, $using_type, $hospital_5th, $hospital_6th, $hospital_7th, $hospital_8th, $description, $effects, $note, $in_use, $user);
    }
     //-------------MEDICINE-----------------------
    if ($catalogue=='medicine')
    {
         if ($price=='') $price=0;
         $_obj=new Product();
         $obj_primary=$_obj->AddMedicine($product_encoder, $product_name, $generic_drug, $content, $component, $using_type, $type_of_medicine, $unit_name_of_medicine_input, $caution, $supplier, $note, $price, $unit_of_price, $effects, $in_use, $description, $user);
    }
    //-------------VNMEDICINE-----------------------
    if ($catalogue=='vnmedicine')
    {
        $_obj=new Product();		
		$obj_primary=$_obj->AddVnMedicine($product_encoder, $product_name, $generic_drug, $content, $component, $using_type, $type_of_medicine, $unit_name_of_medicine_input, $caution, $supplier, $note, $price, $unit_of_price, $effects, $in_use, $description, $user);

    }
    //-------------MEDIPOT-----------------------
    if ($catalogue=='medipot')
    {
		$_obj=new Product();		
		//echo $product_encoder.' '.$product_name.' '.$group_of_medipot_input.' '.$unit_name_of_medicine_input.' '.$caution.' '.$supplier.' '.$note.' '.$price.' '.$unit_of_price.' '.$in_use.' '.$description.' '.$user;
		$obj_primary=$_obj->AddMedipot($product_encoder, $product_name, $group_of_medipot_input, $unit_name_of_medicine_input, $caution, $supplier, $note, $price, $unit_of_price, $in_use, $description, $user);
    }
    $mode='view'; //load lại danh mục
	echo '<center>'.$LDSaveOk.'<br><br><a href="'.$breakfilecatalogue.'&catalogue='.$catalogue.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a><center>';
}

//cho danh mục hiển thị lên template
$sCatalogueInfo=load_catalogue();
$smarty->assign('LDCatalogueInfo',''.$sCatalogueInfo);
$smarty->assign('LDResult',''.$LDResult_before.' '.$records.' '.$LDResult_after);
//gán số trang
$smarty->assign('LDFirst','<u id="first">'.$LDFirst.'</u>');
$smarty->assign('LDPrev','<u id="prev">'.$LDPrev.'</u>');
$smarty->assign('LDPrev2','<u id="prev2">'.$LDPrev2.'</u>');
$smarty->assign('LDPrev3','<u id="prev3">'.$LDPrev3.'</u>');
$smarty->assign('LDPrev4','<u id="prev4">'.$LDPrev4.'</u>');
$smarty->assign('LDPrev5','<u id="prev5">'.$LDPrev5.'</u>');
$smarty->assign('LDCurr','<u id="current">'.$LDCurrent.'</u>');
$smarty->assign('LDNext','<u id="next">'.$LDNext.'</u>');
$smarty->assign('LDNext2','<u id="next2">'.$LDNext2.'</u>');
$smarty->assign('LDNext3','<u id="next3">'.$LDNext3.'</u>');
$smarty->assign('LDNext4','<u id="next4">'.$LDNext4.'</u>');
$smarty->assign('LDNext5','<u id="next5">'.$LDNext5.'</u>');
$smarty->assign('LDLast','<u id="last">'.$LDLast.'</u>');
$smarty->assign('sHiddenInput','<input type="hidden" id="exp_ger_drug_id" value="">');



//**********************************************************************************************************
//---------------------------MODE = VIEW-----------------------------------------------
//chế độ chỉ hiển thị danh mục -> không hiển thị form thông tin
//dùng khi tạo mới, tìm kiếm
 if ($mode == 'view')//chế độ view cho phép hiển thị thông tin từng phần tử trong danh mục
 {
//     $smarty->assign('LDProducts',$LDProducts);
    //----------------SUPPLIER---------------------
    if($catalogue=='supplier')
    {
        if ($obj_info!='')
        {
            $_supplier=$obj_info["supplier"];
            $_supplier_name=$obj_info["supplier_name"];
            $in_use=$obj_info["in_use"];
            $_type_of_supplier=$obj_info["type_of_supplier"];
            $_type_name_of_supplier=$obj_info["type_name_of_supplier"];
            $_address=$obj_info["address"];
            $_telephone=$obj_info["telephone"];
            $_fax=$obj_info["fax"];
            $_note=$obj_info["note"];
        }

         //Tên nhà cung cấp
         $smarty->assign('sSupplierInput','<p id="supplier">'.$_supplier.'</p>');

         //Tên đầy đủ
         $smarty->assign('sSupplierNameInput','<p id="supplier_name">'.$_supplier_name.'</p>');

        //Đang/Ngưng cung cấp
         if ($in_use==1) $stt=$LDIn_use; else $stt=$LDUn_use;
         $smarty->assign('sStatusInput','<p id="status"> <font color=#22B14C>'.$stt.'</font></p>');

        //Dạng cung cấp
         $smarty->assign('sTypeInput','<p id="type_name_of_supplier">'.$_type_name_of_supplier.'</p>');

         //Địa chỉ
         $smarty->assign('sAddressInput','<p id="address">'.$_address.'</p>');

         //Số điện thoại
         $smarty->assign('sTelInput','<p id="telephone">'. $_telephone.'</p>');

         //Số fax
         $smarty->assign('sFaxInput','<p id="fax">'.$_fax.'</p>');

          //Ghi chú
         $smarty->assign('sNoteInput','<p id="note_supplier">'.$_note.'</p>');

         $smarty->assign('LDEdit','<p id="edit"><input type="image" '.$img_edit.' onclick="edit('."'supplier','".$_supplier."','".$_supplier_name."','".$_type_of_supplier."','".$_address."','".$_telephone."','".$_fax."','".$_type_name_of_supplier."','','','','','','','','','','','','','".$_note."','','".$_in_use."'".')" title="'.$LDEdit.'"/></p>');
//         $smarty->assign('LDDelete','<p id="disable"><input type="button" value="'.$LDDisable.'"/></p>');

         $smarty->assign('LDProducts',$LDProducts);

         $smarty->assign('sSubBlockIncludeFile','pharmacy/form_supplier.tpl');
    }
    //-------------GENERIC_DRUG-----------------
      if($catalogue=='generic_drug')
     {
           if ($obj_info!='')
        {
            $_pharma_generic_drug_id=$obj_info["pharma_generic_drug_id"];//khóa chính
            $_generic_drug=$obj_info["generic_drug"];
            $_drug_id=$obj_info["drug_id"];
            $_generic_id=$obj_info["generic_drug_id"];
            $in_use=$obj_info["in_use"];
            $_group=$obj_info["pharma_group_name"];
            $_group_id=$obj_info["pharma_group_id"];
            $_using_type=$obj_info["using_type"];
            $_effects=$obj_info["effects"];
            $_description=$obj_info["description"];
           
            $_note=$obj_info["note"];
			
			$_hospital='';
			if($obj_info["hospital_5th"])	$_hospital .=$LDhospital_5th.', ';
			if($obj_info["hospital_6th"])	$_hospital .=$LDhospital_6th.', ';
            if($obj_info["hospital_7th"])	$_hospital .=$LDhospital_7th.', ';
			if($obj_info["hospital_8th"])	$_hospital .=$LDhospital_8th;
        }

         //Tên thuốc/hoạt chất
         $smarty->assign('sGenericNameInput','<p id="generic_drug">'.$_generic_drug.'</p>');

         //Thứ tự hoạt chất
         $smarty->assign('sGenericIdInput','<p id="generic_id">'.$_generic_id.'</p>');

         //Thứ tự thuốc
         $smarty->assign('sDrugIdInput','<p id="drug_id">'.$_drug_id.'</p>');

        //Đang/Ngưng cung cấp
         if ($in_use==1) $stt=$LDIn_use; else $stt=$LDUn_use;
         $smarty->assign('sStatusInput','<p id="status">'.$stt.'</p>');

        //Nhóm thuốc
         $smarty->assign('sGroupInput','<p id="pharma_group_name">'.$_group.'</p>');

         //Dạng sử dụng
         $smarty->assign('sUsingTypeInput','<p id="using_type">'.$_using_type.'</p>');

         //Tuyến sử dụng
         $smarty->assign('sHospitalInput','<p id="hospital">'.$_hospital.'</p>');
		//$smarty->assign('sHospitalInput','<p id="hospital"><input type="checkbox" name="hospital_5th">'.$LDhospital_5th.'</input> <input type="checkbox" name="hospital_6th">'.$LDhospital_6th.'</input> <input type="checkbox" name="hospital_7th">'.$LDhospital_7th.'</input> <input type="checkbox" name="hospital_8th">'.$LDhospital_8th.'</input> </p>');

         //Tác dụng
         $smarty->assign('sEffectsInput','<p id="effects">'. $_effects.'</p>');

         //Cách sử dụng
         $smarty->assign('sUsingInput','<p id="description">'.$_description.'</p>');

       

          //Chú ý
         $smarty->assign('sCautionInput','<p id="note">'.$_note.'</p>');

         $smarty->assign('LDEdit','<p id="edit"><input type="image" '.$img_edit.' onclick="edit('."'generic_drug','".$_pharma_generic_drug_id."','".$_generic_drug."','".$_group_id."','".$_drug_id."','".$_using_type."','".$_effects."','".$_description."','".$_group."','$_generic_id','','','','','','','','','','$_hospital','".$_note."','','".$_in_use."'".')" title="'.$LDEdit.'"/></p>');
//         $smarty->assign('LDDelete','<p id="disable"><input type="button" value="'.$LDDisable.'"/></p>');

         $smarty->assign('LDProducts',$LDProducts);

         $smarty->assign('sSubBlockIncludeFile','pharmacy/form_generic_drug.tpl');
     }
     //--------------MEDICINE--------------------
     if($catalogue=='medicine' || $catalogue=='vnmedicine' || $catalogue=='medipot' )
     {
          if ($obj_info!='')
          {
              $_product_encoder=$obj_info["product_encoder"];
              $_product_name=$obj_info["product_name"];
              $_in_use=$obj_info["in_use"];
              $_generic_drug=$obj_info["generic_drug"];
              $_pharma_generic_drug_id=$obj_info["pharma_generic_drug_id"];
			  $_pharma_group_name=$obj_info["pharma_group_name"];
              $_content=$obj_info["content"];
              $_component=$obj_info["component"];
              $_type_of_medicine=$obj_info["type_of_medicine"];
              $_type_name_of_medicine=$obj_info["type_name_of_medicine"];
              $_using_type=$obj_info["using_type"];
              $_effects=$obj_info["effects"];
              $_description=$obj_info["description"];
              $_caution=$obj_info["caution"];
              $_supplier_name=$obj_info["supplier_name"];
              $_supplier=$obj_info["care_supplier"];
              $_unit_of_medicine=$obj_info["unit_of_medicine"];
              $_unit_name_of_medicine=$obj_info["unit_name_of_medicine"];
              $_price=$obj_info["price"];
              $_unit_of_price=$obj_info["unit_of_price"];
              $_unit_of_price_name=$obj_info["short_name"];
              $_note=$obj_info["note"];
          }

         //Biệt dược
         $smarty->assign('sArticleNameInput','<p id="product_name">'.$_product_name.'</p>');

          //Trạng thái
         $smarty->assign('sStatusInput',''.'<p id="status">'.$_in_use.'</p>');

         //Thuốc gốc
         $smarty->assign('sGenericInput',''.'<p id="generic_drug"><a href="'.$this_file.'?catalogue=generic_drug&lang='.$lang.'&obj_primary='.$_pharma_generic_drug_id.'"><b>'.$_generic_drug.'</b></a></p>');
		 
		 //Thuốc gốc
         $smarty->assign('sGroupNameInput',''.'<p id="pharma_group_name">'.$_pharma_group_name.'</p>');

         //Hàm lượng
         $smarty->assign('sContentInput',''.'<p id="content">'.$_content.'</p>');

         //Thành phần
         $smarty->assign('sComponentInput',''.'<p id="component">'.$_component.'</p>');

         //Dạng thuốc
         $smarty->assign('sType_of_MedicineInput',''.'<p id="type_name_of_medicine">'.$_type_name_of_medicine.'</p>');

         //Đường dùng
         $smarty->assign('sUsing_TypeInput','<p id="using_type">'.$_using_type.'</p>');

         //Tác dụng
         $smarty->assign('sEffectsInput','<p id="effects">'.$_effects.'</p>');

         //Cách dùng
         $smarty->assign('sDescriptionInput',''.'<p id="description">'.$_description.'</p>');

         //Thận trọng
         $smarty->assign('sCautionInput',''.'<p id="caution">'.$_caution.'</p>');

         //Số đăng kí
         $smarty->assign('sRegNoInput',''.'<p id="product_encoder">'.$_product_encoder.'</p>');

         //Nhà cung cấp
         $smarty->assign('sSupplierInput',''.'<p id="supplier"><a href="'.$this_file.'?catalogue=supplier&lang='.$lang.'&obj_primary='.$_supplier.'"><b>'.$_supplier.'</b></a></p>');

         //Giá
         $smarty->assign('sPriceInput',''.'<p id="price">'.$_price.'</p>');

         //Đơn vị giá
         $smarty->assign('sCurrencyInput',''.'<p id="currency">'.$_unit_of_price_name.'</p>');

         //Đơn vị tính
         $smarty->assign('sUnitInput',''.'<p id="unit_name_of_medicine">'.$_unit_name_of_medicine.'</p>');

         //Ghi chú
         $smarty->assign('sNoteInput',''.'<p id="note">'.$_note.'</p>');

         $smarty->assign('LDEdit','<p id="edit"><input type="image" '.$img_edit.' onClick="edit('."'$catalogue','".$_product_encoder."','".$_product_name."','".$_type_name_of_medicine."','".$_generic_drug."','".$_content."','".$_component."','".$_using_type."','".$_effects."','".$_description."','".$_caution."','".$_price."','".$_supplier."','".$_pharma_generic_drug_id."','".$_pharma_group_name."','".$_unit_name_of_medicine."','".$_unit_of_price_name."','','','','".$_note."','','".$_in_use."'".')" title="'.$LDEdit.'"/></p>');
         $smarty->assign('LDDelete','<p id="disable"><input type="button" value="'.$LDDisable.'"/></p>');
         $smarty->assign('sSubBlockIncludeFile','pharmacy/form_medicine.tpl');
     }


 }
//**********************************************************************************************************
//---------------------------MODE = NEW-----------------------------------------------
//chế độ chỉ hiển thị form thông tin khi chưa gán giá trị sSubBlockIncludeFile
  if ($mode == 'new')
  {
//      $smarty->assign('LDProducts',$LDProducts);
      $smarty->assign('LDEdit','<p id="edit"/>');
      //select box
       $selected_box=load_select_box1($catalogue, $select);
      //----------------SUPPLIER---------------------
       if($catalogue=='supplier')
       {
//           
             $smarty->assign('sSupplierInput','<form style="margin: 0px; padding: 0px;" action="'.$this_file.URL_APPEND.'" method="POST"><p id="supplier"><input type="text" name="supplier" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sSupplierNameInput','<p id="supplier_name"><input type="text" name="supplier_name" value="" style="width:100%" maxlength=300/></p>');
             //$smarty->assign('sStatusInput','<input type="hidden" name="status" value="Đang cung cấp" style="width:100%" maxlength=300/><p id="status">'.$LDIn_use.'</p>');
             $smarty->assign('sTypeInput','<p id="type_name_of_supplier">'.$selected_box.'</p>');
             //$smarty->assign('sPackingInput','<input type="text" name="type_name_of_supplier" value=""  style="width:100%" maxlength=300>');
             $smarty->assign('sAddressInput','<p id="address"><input type="text" name="address" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sTelInput','<p id="telephone"><input type="text" name="telephone" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sFaxInput','<p id="fax"><input type="text" name="fax" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sNoteInput','<p id="note_supplier"><textarea name="note" style="width:100%" rows=2></textarea></p></form>');
             $smarty->assign('sSubBlockIncludeFile','pharmacy/form_supplier.tpl');
       }
       //-------------GENERIC_DRUG-----------------
        if($catalogue=='generic_drug')
       {
             $smarty->assign('sGenericNameInput','<form style="margin: 0px; padding: 0px;" action="'.$this_file.URL_APPEND.'" method="POST"><p id="generic_drug"><input type="text" name="generic_drug" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sDrugIdInput','<p id="drug_id"><input type="text" name="drug_id" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sGenericIdInput','<p id="generic_id"><input type="text" name="drug_id" value="" style="width:100%" maxlength=300/></p>');
//             $smarty->assign('sStatusInput','<input type="hidden" name="status" value="Đang cung cấp" style="width:100%" maxlength=300/><p id="status">'.$LDIn_use.'</p>');
             $smarty->assign('sGroupInput','<p id="pharma_group_name">'.$selected_box.'</p>');
             $smarty->assign('sUsingTypeInput','<p id="using_type"><input type="text" name="using_type" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sHospitalInput','<p id="hospital"><input type="checkbox" name="hospital_5th">'.$LDhospital_5th.'</input> <input type="checkbox" name="hospital_6th">'.$LDhospital_6th.'</input> <input type="checkbox" name="hospital_7th">'.$LDhospital_7th.'</input> <input type="checkbox" name="hospital_8th">'.$LDhospital_8th.'</input> </p>');
             $smarty->assign('sEffectsInput','<p id="effects"><textarea name="effects" value="" style="width:100%" maxlength=300></textarea></p>');

             $smarty->assign('sUsingInput','<p id="description"><textarea name="description" style="width:100%" maxlength=300></textarea></p>');
             $smarty->assign('sCautionInput','<p id="note"><textarea name="note" style="width:100%" rows=2></textarea></p>');
//             $smarty->assign('LDSave','<p id="save"><type="image" '.$img_save.' title="'.$LDSave.'"/></p>');
//             $smarty->assign('LDCancel','<input type="hidden" name="lang" value="'.$lang.'"/><input type="hidden" name="ntid" value="false"/><input type="hidden" name="mode" value="save"/><input type="hidden" name="catalogue" value="supplier"/>'.'<p id="cancel"><a href="'.strtr($this_file.URL_APPEND.'&catalogue=generic_drug','','').'"/><input type="button" value="'.$LDCancel.'"/></p></form>');
             $smarty->assign('sSubBlockIncludeFile','pharmacy/form_generic_drug.tpl');
       }
       //--------------MEDICINE---MEDIPOT---VNMEDICINE--------------
       if($catalogue=='medicine' || $catalogue=='vnmedicine' || $catalogue=='medipot')
       {
             $curency_select=load_currency('');
             if($catalogue=='medicine' || $catalogue=='vnmedicine') $_unit_name_of_medicine=load_unit('');
             if($catalogue=='medicine')
				$smarty->assign('sGroupNameInput','<p id="pharma_group_name"></p>');
			 if($catalogue=='medipot'){
				$_unit_name_of_medicine=load_unit_medipot('');
				$_med_group_name=load_group_medipot('');
				$smarty->assign('LDGroupNameMed',''.$LDGroupNameMedipot); //Nhóm VTYT
				$smarty->assign('sGroupNameInput',''.$_med_group_name);
			 }		
             $smarty->assign('sArticleNameInput','<form style="margin: 0px; padding: 0px;" action="'.$this_file.URL_APPEND.'" method="POST"><p id="product_name"><input type="text" name="product_name" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sStatusInput',''.'<p id="status">'.$_in_use.'</p>');
             $smarty->assign('sGenericInput',''.'<p id="generic_drug"><input type="text" id="generic_drug_input" name="generic_drug"  onkeyup="Generic_Drug_AutoComplete()" onFocus="Generic_Drug_AutoComplete()" onBlur="Fill_Data_GroupName()"  value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sContentInput',''.'<p id="content"><input type="text" name="content" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sComponentInput',''.'<p id="component"><textarea name="component" style="width:100%" rows=2></textarea></p>');
             $smarty->assign('sType_of_MedicineInput',''.'<p id="type_name_of_medicine">'.$selected_box.'</p>');
             $smarty->assign('sUsing_TypeInput','<p id="using_type"><input type="text" name="using_type" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sEffectsInput','<p id="effects"><textarea name="effects" style="width:100%" rows=2></textarea></p>');
             $smarty->assign('sDescriptionInput',''.'<p id="description"><textarea name="description" style="width:100%" rows=2></textarea></p>');
             $smarty->assign('sCautionInput',''.'<p id="caution"><textarea name="caution" style="width:100%" rows=2></textarea></p>');
             $smarty->assign('sRegNoInput',''.'<p id="product_encoder"><input type="text" name="product_encoder" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sSupplierInput',''.'<p id="supplier"><input type="text" onkeyup="Supplier_AutoComplete()" onFocus="Supplier_AutoComplete()" id="supplier_input" name="supplier" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sPriceInput',''.'<p id="price"><input type="text" name="price" value="" style="width:100%" maxlength=300/></p>');
             $smarty->assign('sCurrencyInput','<p id="currency">'.$curency_select.'</p>');
             $smarty->assign('sUnitInput',''.'<p id="unit_name_of_medicine">'.$_unit_name_of_medicine.'</p>');
             $smarty->assign('sNoteInput',''.'<p id="note"><textarea name="note" style="width:100%" rows="2"></textarea></p>');
             $smarty->assign('sSubBlockIncludeFile','pharmacy/form_medicine.tpl');
           
       }
       $smarty->assign('LDSave','<p id="save"><input type="image" '.$img_save.'  title="'.$LDSave.'"/></p>');
       $smarty->assign('LDCancel','<p id="cancel"/><a href="'.strtr($this_file.URL_APPEND."&catalogue=$catalogue",'','').'"/><img '.$img_cancel.' title="'.$LDCancel.'"/></p>'.'<input type="hidden" name="mode" value="save"/><input type="hidden" name="catalogue" value="'.$catalogue.'"/>');

  }

//*******************************************************************************************************
//hiển thị Template
 $smarty->assign('sMainBlockIncludeFile','pharmacy/catalogue.tpl');
 $display = $smarty->display('common/mainframe2.tpl');
 ?>
