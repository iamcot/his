<?php
/**
 * @package care_api
 */


require_once($root_path.'include/care_api_classes/class_core.php');

/**
 *  GUI input form for person registration methods.
 *
 * Dependencies:
 * assumes the following files are in the given path
 * /include/care_api_classes/class_person.php
 * /include/care_api_classes/class_paginator.php
 * /include/care_api_classes/class_globalconfig.php
 * /include/core/inc_date_format_functions.php
 *  Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
 * @author Elpidio Latorilla
 * @version beta 2.0.1
 * @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
 * @KB by Kurt Brauchli
 * @package care_api
 */

$thisfile = basename($_SERVER['PHP_SELF']);

class GuiInputPerson {

    # Language tables
    var $langfiles= array('emr.php', 'person.php', 'date_time.php', 'aufnahme.php');

    # Default path for fotos. Make sure that this directory exists!
    var $default_photo_path='uploads/photos/registration';

    # Filename of file running this gui
    var $thisfile = '';

    # PID number
    var $pid=0;

    # Toggler var
    var $toggle=0;

    # Color of error text
    var $error_fontcolor='#ff0000';

    # Text block above form
    var $pretext='';
    # Text block below the form
    var $posttext='';

    # filename for displaying the data after saving
    var $displayfile='';

    # smarty template
    var $smarty;

    # Flag for output or returning form data
    var $bReturnOnly = FALSE;

    /**
     * Constructor
     */
    function GuiInputPerson($filename = ''){
        global $thisfile, $root_path;
        if(empty($filename)) $this->thisfile = $thisfile;
        else $this->thisfile = $filename;
    }
    /**
     * Sets the PID number
     */
    function setPID($pid=0){
        if(!empty($pid)) $this->pid = $pid;
    }
    /**
     * Sets the PID number
     */
    function setDisplayFile($fn=''){
        if(!empty($fn)) $this->displayfile = $fn;
    }
    /**
     * Create a row of input element in the form
     */
    function createTR($error_handler, $input_name, $ld_text, $input_val, $colspan = 1, $input_size = 35,$red=FALSE,$option=""){
        ob_start();
        $this->smarty->assign('must','');
        if ($error_handler || $red) {
            $sBuffer="<font color=\"$this->error_fontcolor\">* $ld_text</font>";
            $this->smarty->assign('must',1);
        }
        else $sBuffer=$ld_text;
        //$this->smarty->assign('must',1);
        $this->smarty->assign('sItem',$sBuffer);
        $this->smarty->assign('sColSpan2',"colspan=$colspan");
        $this->smarty->assign('sInput','<input name="'.$input_name.'" type="text" size="'.$input_size.'" value="'.$input_val.'" '.$option.' >');
        $this->smarty->display('registration_admission/reg_row.tpl');
        $sBuffer = ob_get_contents();
        ob_end_clean();

        //$this->toggle=!$this->toggle;

        return $sBuffer;
    }
    /**
     * Displays the GUI input form
     */
    function display(){
        global $db, $sid, $lang, $root_path, $pid, $insurance_show, $user_id, $mode, $dbtype, $breakfile, $cfg,
               $update, $photo_filename, $_POST,  $_FILES, $_SESSION;

        extract($_POST);

        # Load the language tables
        $lang_tables =$this->langfiles;
        include($root_path.'include/core/inc_load_lang_tables.php');

        # Load the other hospitals array
        include_once($root_path.'global_conf/other_hospitals.php');

        include_once($root_path.'include/core/inc_date_format_functions.php');
        include_once($root_path.'include/care_api_classes/class_insurance.php');
        include_once($root_path.'include/care_api_classes/class_person.php');
        require_once($root_path.'include/core/access_log.php');
        require_once($root_path.'include/care_api_classes/class_access.php');
        $logs = new AccessLog();
        //$db->debug=true;

        # Create the new person object
        $person_obj=& new Person($pid);

        # Create a new person insurance object
        $pinsure_obj=& new PersonInsurance($pid);

        if(!isset($insurance_show)) $insurance_show=TRUE;

        $newdata=1;

        $error=0;
        $dbtable='care_person';

        if(!isset($photo_filename)||empty($photo_filename)) $photo_filename='nopic';
        # Assume first that image is not uploaded
        $valid_image=FALSE;

        //* Get the global config for person's registration form*/
        include_once($root_path.'include/care_api_classes/class_globalconfig.php');
        $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
        $glob_obj->getConfig('person_%');

        //extract($GLOBAL_CONFIG);

        # Check whether config foto path exists, else use default path
        $photo_path = (is_dir($root_path.$GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $this->default_photo_path;

        if (($mode=='save') || ($mode=='forcesave')) {

            # If saving is not forced, validate important elements
            if($mode!='forcesave') {
                # clean and check input data variables
                if (trim($encoder)=='') $encoder=$aufnahme_user;
                if (trim($name_last)=='') { $errornamelast=1; $error++;}
                if (trim($name_first)=='') { $errornamefirst=1; $error++; }
                if (trim($date_birth)=='') { $errordatebirth=1; $error++;}


                if ($addr_citytown_nr=='') { $errortown=1; $error++;}


                //	if($insurance_show) {
                //		if(trim($insurance_nr) && (trim($insurance_firm_name)=='')) { $errorinsurancecoid=1; $error++;}
                //	}
            }


            # If the validation produced no error, save the data
            if(!$error) {
                # Save the old filename for testing
                $old_fn=$photo_filename;

                # Create image object
                include_once($root_path.'include/care_api_classes/class_image.php');
                $img_obj=& new Image;

                # Check the uploaded image file if exists and valid
                if($img_obj->isValidUploadedImage($_FILES['photo_filename'])){
                    $valid_image=TRUE;
                    # Get the file extension
                    $picext=$img_obj->UploadedImageMimeType();
                }

                if(($update)) {
                    if(check_date($insurance_start)==0 || check_date($insurance_exp)==0) {
                        echo "<script type='text/javascript'>";
                        echo "alert('Ngày tháng trong BHYT sai. Cập nhật lại');";
                        echo "</script>";
                    } else{
                    //echo formatDate2STD($geburtsdatum,$date_format);
                    $sql="UPDATE $dbtable SET
							 name_last='$name_last',
							 name_first='$name_first',
							 name_2='$name_2',
							 name_3='$name_3',
							 name_middle='$name_middle',
							 name_maiden='$name_maiden',
							 name_others='$name_others',
							 date_birth='".formatDate2STD($date_birth,$date_format)."',
							 blood_group='".trim($blood_group)."',
							 sex='$sex',
							 addr_str='$addr_str',
							 addr_str_nr='$addr_str_nr',
							 addr_zip='$addr_zip',
							 addr_citytown_nr='$addr_citytown_nr',
							 addr_quanhuyen_nr='$addr_quanhuyen_nr',
							 addr_phuongxa_nr='$addr_phuongxa_nr',
							 phone_1_nr='$phone_1_nr',
							 phone_2_nr='$phone_2_nr',
							 cellphone_1_nr='$cellphone_1_nr',
							 cellphone_2_nr='$cellphone_2_nr',
							 fax='$fax',
							 email='$email',
							insurance_nr='$insurance_nr',
							insurance_firm_id='$insurance_firm_id',
							insurance_class_nr='$insurance_class_nr',
							insurance_local='$insurance_local',
							insurance_start='".formatDate2STD($insurance_start,$date_format)."',
							insurance_exp='".formatDate2STD($insurance_exp,$date_format)."',
							madkbd='$madkbd',
							is_traituyen='$is_traituyen',
							 citizenship='$citizenship',
							 civil_status='$civil_status',
							 sss_nr='$sss_nr',
							 nat_id_nr='$nat_id_nr',
							 religion='$religion',
							 ethnic_orig='$ethnic_orig',
							 date_update='".date('Y-m-d H:i:s')."',
							 nghenghiep = '".$nghenghiep."',
							 nghenghiepcode='".$nghenghiepcode."',";
                    //add more CoT
                    $sql .= "  noilamviec = '$noilamviec', hotenbaotin = '$hotenbaotin', dtbaotin = '$dtbaotin',tiensubenhcanhan='$tiensubenhcanhan',tiensubenhgiadinh='$tiensubenhgiadinh',tuoi='$tuoi', ";

                    if ($valid_image){
                        # Compose the new filename
                        $photo_filename=$pid.'.'.$picext;
                        # Save the file
                        $img_obj->saveUploadedImage($_FILES['photo_filename'],$root_path.$photo_path.'/',$photo_filename);
                        # add to the sql query
                        $sql.=" photo_filename='$photo_filename',";
                    }

                    # complete the sql query
                    $sql.=" history=".$person_obj->ConcatHistory("Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']." \n").", modify_id='".$_SESSION['sess_user_name']."' WHERE pid=$pid";
//echo $sql;
                    //$db->debug=true;
                    $db->BeginTrans();
                    $ok=$db->Execute($sql);
                    if($ok) {
                        $logs->writeline_his($_SESSION['sess_login_userid'], 'class_gui_input_person.php',$sql, date('Y-m-d H:i:s'));
                        $db->CommitTrans();
                        # Update the insurance data
                        # Lets detect if the data is already existing
                        /*	if($insurance_show) {
                                if($insurance_item_nr) {
                                    if(!empty($insurance_nr) && !empty($insurance_firm_name) && $insurance_firm_id) {

                                        $insure_data=array('insurance_nr'=>$insurance_nr,
                                                'firm_id'=>$insurance_firm_id,
                                                'class_nr'=>$insurance_class_nr,
                                                'history'=>"Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']." \n",
                                                'modify_id'=>$_SESSION['sess_user_name'],
                                                'modify_time'=>date('YmdHis')
                                                );

                                        $pinsure_obj->updateDataFromArray($insure_data,$insurance_item_nr);
                                    }
                                } elseif ($insurance_nr && $insurance_firm_name  && $insurance_class_nr) {
                                    $insure_data=array('insurance_nr'=>$insurance_nr,
                                                'firm_id'=>$insurance_firm_id,
                                                'pid'=>$pid,
                                                'class_nr'=>$insurance_class_nr,
                                                'history'=>"Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']." \n",
                                                'create_id'=>$_SESSION['sess_user_name'],
                                                'create_time'=>date('YmdHis')
                                            );
                                    $pinsure_obj->insertDataFromArray($insure_data);
                                }
                            }
                            */
                        $newdata=1;
                        //$db->debug=1;
                        // KB: save other_his_no
                        if( isset($_POST['other_his_org']) && !empty($_POST['other_his_org'])){
                            $person_obj->OtherHospNrSet($_POST['other_his_org'], $_POST['other_his_no'], $_SESSION['sess_user_name'] );
                        }

                        if(file_exists($this->displayfile)){
                            header("Location: $this->displayfile".URL_REDIRECT_APPEND."&pid=$pid&from=$from&newdata=1&target=entry");
                            exit;
                        }else{
                            echo "Error! Target display file not defined!!";
                        }
                    } else {
                        //echo "error";
                        $db->RollbackTrans();
                    }
                    }

                } else {
                    # Prepare internal data to be stored together with the user input data
                    if(check_date($_POST['insurance_start'])==0 || check_date($_POST['insurance_exp'])==0) {
                        echo "<script type='text/javascript'>";
                        echo "alert('Ngày tháng trong BHYT sai. Cập nhật lại');";
                        echo "</script>";
                    }else
                    {
                        $from='entry';
                        $_POST['date_birth']=@formatDate2STD($date_birth,$date_format);
                        $_POST['date_reg']=@formatDate2STD($_POST['dat_reg'],$date_format)." ".$_POST['time_reg'];

                        $_POST['date_input']=date('Y-m-d H:i:s');
                        $_POST['blood_group']=trim($_POST['blood_group']);
                        $_POST['status']='normal';
                        $_POST['insurance_start']=@formatDate2STD($_POST['insurance_start'],$date_format);
                        $_POST['insurance_exp']=@formatDate2STD($_POST['insurance_exp'],$date_format);
                        $_POST['history']="Init.reg. ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
                        //$_POST['modify_id']=$_SESSION['sess_user_name'];
                        $_POST['create_id']=$_SESSION['sess_user_name'];
                        $_POST['create_time']=date('YmdHis');

                    if(!$person_obj->InitPIDExists($GLOBAL_CONFIG['person_id_nr_init'])){
                        # If db is mysql, insert the initial pid value  from global config
                        # else let the dbms make an initial value via the sequence generator e.g. postgres
                        # However, the sequence generator must be configured during db creation to start at
                        # the initial value set in the global config
                        if($dbtype=='mysql'){
                            $_POST['pid']=$GLOBAL_CONFIG['person_id_nr_init'];
                        }
                    }else{
                        # Persons are existing. Check if duplicate might exist
                        if(is_object($duperson=$person_obj->PIDbyData($_POST))){
                            $error_person_exists=TRUE;
                        }
                    }
                    //echo $person_obj->getLastQuery();

                    if(!$error_person_exists||$mode=='forcesave'){
                        if($person_obj->insertDataFromInternalArray()){
                            $logs->writeline_his($_SESSION['sess_login_userid'], 'class_gui_input_person.php',$person_obj->getLastQuery(), date('Y-m-d H:i:s'));
                            # If data was newly inserted, get the insert id if mysql,
                            # else get the pid number from the latest primary key

                            if(!$update){
                                $oid = $db->Insert_ID();
                                if (empty($oid)) $oid = $_POST['pid'];
                                $pid=$person_obj->LastInsertPK('pid',$oid);
                                //EL: set the new pid
                                $person_obj->setPID($pid);
                            }

                            // KB: save other_his_no
                            if( isset($_POST['other_his_org']) && !empty($_POST['other_his_org'])){
                                $person_obj->OtherHospNrSet($_POST['other_his_org'], $_POST['other_his_no'], $_SESSION['sess_user_name'] );
                            }

                            # Save the valid uploaded photo
                            if($valid_image){
                                # Compose the new filename by joining the pid number and the file extension with "."
                                $photo_filename=$pid.'.'.$picext;
                                # Save the file
                                if($img_obj->saveUploadedImage($_FILES['photo_filename'],$root_path.$photo_path.'/',$photo_filename)){
                                    # Update the filename to the databank
                                    $person_obj->setPhotoFilename($pid,$photo_filename);
                                }
                            }

                            //echo $pid;
                            # Update the insurance data
                            # Lets detect if the data is already existing
                            /*if($insurance_show) {
                                  if($insurance_item_nr) {
                                    if(!empty($insurance_nr) && !empty($insurance_firm_name) && $insurance_firm_id) {
                                        $insure_data=array('insurance_nr'=>$insurance_nr,
                                                    'firm_id'=>$insurance_firm_id,
                                                    'class_nr'=>$insurance_class_nr);
                                        $pinsure_obj->updateDataFromArray($insure_data,$insurance_item_nr);
                                    }
                                } elseif ($insurance_nr && $insurance_firm_name  && $insurance_class_nr) {
                                    $insure_data=array('insurance_nr'=>$insurance_nr,
                                                    'firm_id'=>$insurance_firm_id,
                                                    'pid'=>$pid,
                                                    'class_nr'=>$insurance_class_nr);
                                    $pinsure_obj->insertDataFromArray($insure_data);
                                }
                            }*/
                            $newdata=1;
                            if(file_exists($this->displayfile)){
                                header("Location: $this->displayfile".URL_REDIRECT_APPEND."&pid=$pid&from=$from&newdata=1&target=entry");
                                exit;
                            }else{
                                echo "Error! Target display file not defined!!";
                            }
                        }else {
                            echo "<p>$db->ErrorMsg()<p>$LDDbNoSave";
                            }
                        }
                    }
                }
            } // end of if(!$error)
        }elseif(!empty($this->pid)){
            # Get the person�s data
            if($data_obj=&$person_obj->getAllInfoObject()){

                $zeile=$data_obj->FetchRow();
                extract($zeile);

                # Get the related insurance data
                $p_insurance=&$pinsure_obj->getPersonInsuranceObject($pid);
                if($p_insurance==FALSE) {
                    $insurance_show=TRUE;
                } else {
                    if(!$p_insurance->RecordCount()) {
                        $insurance_show=TRUE;
                    } elseif ($p_insurance->RecordCount()==1){
                        $buffer= $p_insurance->FetchRow();
                        extract($buffer);
                        $insurance_show=TRUE;
                        $insurance_firm_name=$pinsure_obj->getFirmName($insurance_firm_id);
                    } else {
                        $insurance_show=FALSE;
                    }
                }
            }
        } else {
            $date_reg=date('Y-m-d H:i:s');
            $date_input=date('Y-m-d H:i:s');
        }
        # Get the insurance classes
        $insurance_classes=&$pinsure_obj->getInsuranceClassInfoObject('class_nr,name,LD_var AS "LD_var"');

        include_once($root_path.'include/core/inc_photo_filename_resolve.php');

        #
        #
        ########  Here starts the GUI output #######################################################
        #
        #

        # Start Smarty templating here
        # Create smarty object without initiliazing the GUI (2nd param = FALSE)

        include_once($root_path.'gui/smarty_template/smarty_care.class.php');
        $this->smarty = new smarty_care('common',FALSE);

        $img_male=createComIcon($root_path,'spm.gif','0');
        $img_female=createComIcon($root_path,'spf.gif','0');

        if(!empty($this->pretext)) $this->smarty->assign('pretext',$this->pretext);

        # Collect extay javascript code
        $sTemp='';
        ob_start();
        require_once ('../../js/jscalendar/calendar.php');
        $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
        $calendar->load_files();
        require('./include/js_popsearchwindow.inc.php');
        ?>

        <script  language="javascript">
        //add cot
        focusfirstinput();
        function focusfirstinput(){
            $("#iname_last").focus();
        }
        //add 0310 - cot
        function autobhyt(){
            $("input[name=insurance_firm_id]").val("BHYT");
            $("input[name=insurance_firm_name]").val("Bảo hiểm y tế");
        }
        //add 2709 - cot
        function ChangeCase(elem)
        {
            elem.value = elem.value.toUpperCase();
        }
        //end add 2709
        function forceSave(){
            document.aufnahmeform.mode.value="forcesave";
            document.aufnahmeform.submit();
        }

        function showpic(d){
            if(d.value) document.images.headpic.src=d.value;
            if(d.value) document.images.headpic.src=d.value;
        }

        function popSearchWin(target,obj_val,obj_name){
            urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
            DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
        }

        function chkform(d) {
            //alert(d.tresosinh.checked);
            if(d.name_last.value==""){
                alert("<?php echo $LDPlsEnterLastName; ?>");
                d.name_last.focus();
                return false;
            }else if(d.name_first.value==""){
                alert("<?php echo $LDPlsEnterFirstName; ?>");
                d.name_first.focus();
                return false;
            }
            else if(d.sex.value==0){
                alert("Nhập giới tính");
                d.sex.focus();
                return false;
            }
            else if(d.tresosinh.checked){
                if(d.hotenbaotin.value =="") {
                    alert("Trẻ em cần nhập người báo tin.");
                    return false;
                }
            }
            else if(d.date_birth.value==""){
                alert("<?php echo $LDPlsEnterDateBirth; ?>");
                d.date_birth.focus();
                return false;
            }else if(d.user_id.value==""){
                alert("<?php echo $LDPlsEnterFullName; ?>");
                d.user_id.focus();
                return false;
            }else if(d.insurance_class_nr.value==1){
                if(d.insurance_firm_name.value==""){
                    alert("Nhập tên công ty bảo hiểm");
                    d.insurance_firm_name.focus();
                    return false;
                }
                else if(d.insurance_nr.value==""){
                    alert("Nhập số thẻ bảo hiểm");
                    d.insurance_nr.focus();
                    return false;
                }
                else if(d.insurance_start.value==""){
                    alert("Nhập ngày cấp");
                    d.insurance_start.focus();
                    return false;
                }
                else if(d.insurance_exp.value==""){
                    alert("Nhập ngày hết hạn");
                    d.insurance_exp.focus();
                    return false;
                }
                else if(d.madkbd.value==""){
                    alert("Nhập mã đăng kí khám chữa bệnh ban đầu");
                    d.madkbd.focus();
                    return false;
                }
                /*
                 else{
                 if (confirm("Xác nhân lưu thông tin đăng ký")){
                 d.submit();
                 return true;
                 }

                 else return false;
                 }
                 */
            }
            /*
             else{
             if (confirm("Xác nhân lưu thông tin đăng ký")){
             d.submit();
             return true;
             }

             else return false;
             }
             */
        }
        function tabE(obj,e){
            var e=(typeof event!='undefined')?window.event:e;// IE : Moz
            if(e.keyCode==13){

                /*
                 var ele = document.forms[0].elements;
                 for(var i=0;i<ele.length;i++){
                 var q=(i==ele.length-1)?0:i+1;// if last element : if any other
                 if(obj==ele[i]){
                 console.log(ele[q]);
                 console.log(ele[q].getAttribute("display"));
                 ele[q].focus();
                 break
                 }
                 }
                 */

                var currentIndex = $(obj).attr("tabindex");
                var nextIndex = parseInt(currentIndex)+1;
                var quit = true;
                if($(obj).val()!= "1" && currentIndex==9) nextIndex=14;//truong hop rieng cua bao hiem
                //	console.log($("input[tabindex='"+nextIndex+"']"));
                //	console.log($("select[tabindex='"+nextIndex+"']"));
                if (($("input[tabindex='"+nextIndex+"']").val() != undefined)){
                    $("input[tabindex='"+nextIndex+"']").focus();
                }
                else if (($("select[tabindex='"+nextIndex+"']").val() != undefined)){
                    $("select[tabindex='"+currentIndex+"']").css("font-weight","normal");
                    $("select[tabindex='"+nextIndex+"']").focus();
                    $("select[tabindex='"+nextIndex+"']").css("font-weight","bold");
                }


                return false;

            }

        }
        function popSearchWin(target,obj_val,obj_name){
            urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
            DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
        }

        function showQuanhuyen(){
            str=document.getElementById("addr_citytown_name").value;
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
                    document.getElementById("quanhuyen").innerHTML=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","getquanhuyen.php?citytown_name="+str,true);
            xmlhttp.send();

        }
        function showXaphuong(){
            str=document.getElementById("quanhuyen").value;
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
                    document.getElementById("xaphuong").innerHTML=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","getxaphuong.php?quanhuyen_name="+str,true);
            xmlhttp.send();

        }
        function geticd10(str,id){
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
                    document.getElementById(id).innerHTML=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","rpc.php?diagnosis_code="+str,true);
            xmlhttp.send();

        }
        function checkKCB(kcb){
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
                    document.getElementById("traituyen").innerHTML=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","checkkcb.php?makcb="+kcb,true);
            xmlhttp.send();
        }
        /*
         Add function calculator age - cot - 0310
         */
        var dat = new Date();
        var curday = dat.getDate();
        var curmon = dat.getMonth()+1;
        var curyear = dat.getFullYear();
        function checkleapyear(datea)
        {
            if(datea.getYear()%4 == 0)
            {
                if(datea.getYear()% 10 != 0)
                {
                    return true;
                }
                else
                {
                    if(datea.getYear()% 400 == 0)
                        return true;
                    else
                        return false;
                }
            }
            return false;
        }
        function DaysInMonth(Y, M) {
            with (new Date(Y, M, 1, 12)) {
                setDate(0);
                return getDate();
            }
        }
        function datediff(date1, date2) {
            var y1 = date1.getFullYear(), m1 = date1.getMonth(), d1 = date1.getDate(),
                y2 = date2.getFullYear(), m2 = date2.getMonth(), d2 = date2.getDate();
            if (d1 < d2) {
                m1--;
                d1 += DaysInMonth(y2, m2);
            }
            if (m1 < m2) {
                y1--;
                m1 += 12;
            }
            return [y1 - y2, m1 - m2, d1 - d2];
        }
        //end
        function checkTuoi(tuoi){
            if(tuoi<=5 && tuoi != ''){
                alert("Trẻ dưới 6 tuổi thì nên check vào mục trẻ sơ sinh");
                $("#f-calendar-field-4").focus();
            }
            else{
                var t = new Date();
                var ty = t.getFullYear();
                $("#f-calendar-field-4").val(ty-tuoi);

            }
        }
        function calctuoi(tuoi,input){
            if(tuoi!=""&&tuoi!="__/__/____"){

                //var t = new Date();
                //var ty = t.getFullYear();
                re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
                if(tuoi.length==10 && tuoi.match(re)){
                    var td=tuoi.split("/");

                    var curd = new Date(curyear,curmon-1,curday);
                    var cald = new Date(td[2],td[1]-1,td[0]);
                    var diff =  Date.UTC(curyear,curmon,curday,0,0,0) - Date.UTC(td[2],td[1],td[0],0,0,0);
                    var dife = datediff(curd,cald);

                    if(!$("#tresosinh").is(':checked')){
                        $("#tuoi").val(curyear-td[2]);
                        if((curyear-td[2])<=5){
                            alert("Trẻ dưới 6 tuổi thì nên check vào mục trẻ sơ sinh");
                            $("#f-calendar-field-4").focus();
                        }
                    }
                    else if($("#tresosinh").is(':checked')){
                        var monleft = (dife[0]*12)+dife[1];
                        $("#thang").val(monleft);}
                }else if(tuoi.length==4){
                    $("#tuoi").val(curyear-tuoi);
                }
                else{input.value="";}
            }else{
                //alert('Nhập năm sinh');
            }
        }
        function check(){
            if(aufnahmeform.tresosinh.checked){
                $("#showtuoi").hide();
                $("#showthang").show();
                $("#showthang").css("display","inline");
                $("#baotin").css("color","red");

            }else{
                $("#showtuoi").show();
                $("#showthang").hide();
                $("#thang").val('');
                $("#baotin").css("color","black");
            }

        }
        function getnghenghiep(nr){
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
                    document.getElementById("nghenghiep").value=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","getnghenghiep.php?mann="+nr,true);
            xmlhttp.send();

        }
        function getdantoc(nr){
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
                    document.getElementById("ethnic_orig_txt").value=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","getdantoc.php?mann="+nr,true);
            xmlhttp.send();

        }
        function calthang(thang){
            var t=new Date();
            var year=t.getFullYear();
            var month=t.getMonth();
            if((thang%12)== 0){
                if(month<=8){
                    $("#f-calendar-field-4").val("/0"+(month+1)+"/"+(year-(thang/12)));
                }else{
                    $("#f-calendar-field-4").val("/"+(month+1)+"/"+(year-(thang/12)));
                }
            }else {
                if((thang%12)<(month+1)){
                    if((month+1-(thang%12))<=9){
                        $("#f-calendar-field-4").val("/0"+(month+1-(thang%12))+"/"+(year-Math.floor(thang/12)));
                    }else{
                        $("#f-calendar-field-4").val("/"+(month+1-(thang%12))+"/"+(year-Math.floor(thang/12)));
                    }
                }else if((thang%12)>(month+1)){
                    if((12-((thang%12)-month-1))<=9){
                        $("#f-calendar-field-4").val("/0"+(12-((thang%12)-month-1))+"/"+(year-Math.round(thang/12)));
                    }else{
                        $("#f-calendar-field-4").val("/"+(12-((thang%12)-month-1))+"/"+(year-Math.round(thang/12)));
                    }
                }
                else{
                    $("#f-calendar-field-4").val("/12/"+(year-Math.round(thang/12)));
                }
            }

        }


        //$('select#insurance_class_nr').click(function()

        function blurbhyt(select){
            //str= document.getElementById("insurance_class_nr").value;
            str = select.val()
            if(str==1){

                $('#box').show();
                $('#box1').show();
                $('#box2').show();
                $('#box3').show();
                $('#box4').show();
                autobhyt();

            }else{

                $('#box').hide();
                $('#box1').hide();
                $('#box2').hide();
                $('#box3').hide();
                $('#box4').hide();
                $('#inputisurance').val('');
                $('#insurance_firm_id').val('');
                $('#madkbd').val('');
                $('#traituyen').val(0);
                $('#insurance_local').val('');
                $("#f-calendar-field-2").val('');
                $("#f-calendar-field-3").val('');
            }
        }
        $(function(){
            blurbhyt($("#insurance_class_nr"));
            $("#f-calendar-field-1").mask("99/99/9999");
            $("#f-calendar-field-2").mask("99/99/9999");
            $("#f-calendar-field-3").mask("99/99/9999");
            $("#inputisurance").mask("aa-9-99-99-999-99999");
            $("#madkbd").mask("99-999");
            $('#phone_1_nr').mask("9999999999");
            $('#nat_id_nr').mask("999999999");
            $('#time_reg').mask("99:99");
        });


        <?php
                require($root_path.'include/core/inc_checkdate_lang.php');
        ?>
        </script>
<?php
//echo $_POST['addr_citytown_nr'];
        //gjergji : new calendar

        //end : gjergji
        $sTemp = ob_get_contents();
        ob_end_clean();

        $this->smarty->assign('sRegFormJavaScript',$sTemp);

        $this->smarty->assign('thisfile',$thisfile);

        if($error) {
            $this->smarty->assign('error',TRUE);
            $this->smarty->assign('sErrorImg','<img '.createMascot($root_path,'mascot1_r.gif','0','bottom').' align="absmiddle">');
            if ($error>1) $this->smarty->assign('sErrorText',$LDErrorS);
            else $this->smarty->assign('sErrorText',$LDError);

        }elseif($error_person_exists){
            $this->smarty->assign('errorDupPerson',TRUE);
            $this->smarty->assign('sErrorImg','<img '.createMascot($root_path,'mascot1_r.gif','0','bottom').' align="absmiddle">');
            $this->smarty->assign('LDPersonDuplicate',$LDPersonDuplicate);
            if($duperson->RecordCount()>1) $this->smarty->assign('sErrorText',"$LDSimilarData2 $LDPlsCheckFirst2");
            else $this->smarty->assign('sErrorText',"$LDSimilarData $LDPlsCheckFirst");

            $this->smarty->assign('sDupDataColNameRow',"<tr class=\"reg_div\">
					<td><FONT  SIZE=-1  FACE=\"Arial\" color=\"#000066\"><b>
						$LDRegistryNr</b></td>
					<td><FONT  SIZE=-1  FACE=\"Arial\" color=\"#000066\"><b>
						$LDLastName</b></td>
					<td><FONT  SIZE=-1  FACE=\"Arial\" color=\"#000066\"><b>
						$LDFirstName</b></td>
					<td><FONT  SIZE=-1  FACE=\"Arial\" color=\"#000066\"><b>
						$LDBday</b></td>
					<td><FONT  SIZE=-1  FACE=\"Arial\" color=\"#000066\"><b>
						$LDSex</b></td>
					<td><FONT  SIZE=-1  FACE=\"Arial\" color=\"#000066\"><b>
						$LDOptions</b></td>
					</tr>");

            # List and show the probable same person(s)

            $toggler=FALSE;
            $sTemp='';
            while($dup=$duperson->FetchRow()){
                if($toggler) $sRowClass='wardlistrow2';
                else $sRowClass='wardlistrow1';
                $toggler = !$toggler;
                $sTemp= $sTemp."\n".'
					<tr class="'.$sRowClass.'">
					<td>'.$dup['pid'].'</td>
					<td>'.$dup['name_last'].'</td>
					<td>'.$dup['name_first'].'</td>
					<td>'.formatDate2Local($dup['date_birth'],$date_format).'</td>
					<td>';
                switch($dup['sex']){
                    case 'f': $sTemp = $sTemp.'<img '.$img_female.'>'; break;
                    case 'm': $sTemp = $sTemp.'<img '.$img_male.'>'; break;
                    default: $sTemp = $sTemp.'&nbsp;';
                }
                $sTemp = $sTemp.'
					</td>
					<td>:: <a href="person_reg_showdetail.php'.URL_APPEND.'&pid='.$dup['pid'].'&from=$from&newdata=1&target=entry" target="_blank">'.$LDShowDetails.'</a> ::
					<a href="patient_register.php'.URL_APPEND.'&pid='.$dup['pid'].'&update=1">'.$LDUpdate.'</a>
					</td>
					</tr>';
            }
            $this->smarty->assign('sDupDataRows',$sTemp);
        }

        if($pid) $this->smarty->assign('LDRegistryNr',$LDRegistryNr);
        $this->smarty->assign('pid',$pid);
        $this->smarty->assign('img_source',$img_source);
        $this->smarty->assign('LDPhoto',$LDPhoto);
        if(isset($photo_filename)) $pfile= $photo_filename;
        else $pfile='';
        $this->smarty->assign('sFileBrowserInput','<input name="photo_filename" type="file" size="15"   onChange="showpic(this)" value="'.$pfile.'">');

        # iRowSpanCount counts the rows on the left of the photo image. Begin with 5 because there are 5 static rows.
        $iRowSpanCount = 6;

        $this->smarty->assign('LDRegDate',$LDRegDate);
        if($update){
            $this->smarty->assign('sRegDate',formatDate2Local($date_reg,$date_format).'<input onkeypress="return tabE(this,event)" name="date_reg" type="hidden" value="'.$date_reg.'">');
        }else{
            $this->smarty->assign('sRegDate',$calendar->show_calendar($calendar,$date_format,'dat_reg',date('d/m/Y')));
        }
        //$iRowSpanCount++;
        $this->smarty->assign('LDRegTime',$LDRegTime);
        $this->smarty->assign('LDInputDate',$LDInputDate);
        if($update){
            $this->smarty->assign('sRegTime',convertTimeToLocal(formatDate2Local($date_reg,$date_format,0,1)));
        }else{
            $this->smarty->assign('sRegTime','<input onkeypress="return tabE(this,event)"  tabindex=1 name="time_reg" id="time_reg" type="text" value="'.date('H:i').'">');
        }
        $this->smarty->assign('sInputTime',convertTimeToLocal(formatDate2Local($date_input,$date_format,0,1)));
        $this->smarty->assign('sInputDate',formatDate2Local($date_input,$date_format).'<input name="date_input" type="hidden" value="'.$date_input.'">');
        // Made hideable as suggested by Kurt brauchli
        /*
        if (!$GLOBAL_CONFIG['person_title_hide']){
            $this->smarty->assign('LDTitle',$LDTitle);
            if($title=='Ông'){
            $this->smarty->assign('sPersonTitle','<select name="title"><option value="Không rõ">Không rõ</option><option value="Ông" selected>Ông</option><option value="Bà">Bà</option></select>');
            }else if($title='Bà'){
            $this->smarty->assign('sPersonTitle','<select name="title"><option value="Không rõ">Không rõ</option><option value="Ông">Ông</option><option value="Bà" selected>Bà</option></select>');
            }else{
            $this->smarty->assign('sPersonTitle','<select name="title"><option value="Không rõ" selected>Không rõ</option><option value="Ông">Ông</option><option value="Bà">Bà</option></select>');
            }
            $iRowSpanCount++;
        }
        */
        $this->smarty->assign('LDLastName',"$LDLastName<font color=red>*</font>");//add 2709 - cot

        $this->smarty->assign('sNameLast','<input onkeypress="return tabE(this,event)" tabindex=2 id="iname_last" name="name_last"  onblur="ChangeCase(this);" type="text" maxlength="25" style="width:96%;" value="'.$name_last.'">');
        //$iRowSpanCount++;
        $this->smarty->assign('LDFirstName',"$LDFirstName<font color=red>*</font>");
        $this->smarty->assign('sNameFirst','<input  onkeypress="return tabE(this,event)" tabindex=3 name="name_first" onblur="ChangeCase(this);" type="text" maxlength="15" style="width:96%;" value="'.$name_first.'">');
        //$iRowSpanCount++;

        /*
                if (!$GLOBAL_CONFIG['person_name_2_hide']){
                    $this->smarty->assign('sName2',$this->createTR($errorname2, 'name_2', $LDName2,$name_2));
                    $iRowSpanCount++;
                }

                if (!$GLOBAL_CONFIG['person_name_3_hide']){
                    $this->smarty->assign('sName3',$this->createTR($errorname3, 'name_3', $LDName3,$name_3));
                    $iRowSpanCount++;
                }

                if (!$GLOBAL_CONFIG['person_name_middle_hide']){
                    $this->smarty->assign('sNameMiddle',$this->createTR($errornamemid, 'name_middle', $LDNameMid,$name_middle));
                    $iRowSpanCount++;
                }

                if (!$GLOBAL_CONFIG['person_name_maiden_hide']){
                    $this->smarty->assign('sNameMaiden',$this->createTR($errornamemaiden, 'name_maiden', $LDNameMaiden,$name_maiden));
                    $iRowSpanCount++;
                }

                if (!$GLOBAL_CONFIG['person_name_others_hide']){
                    $this->smarty->assign('sNameOthers',$this->createTR($errornameothers, 'name_others', $LDNameOthers,$name_others));
                    $iRowSpanCount++;
                }
        */
        # Set the rowspan value for the photo image <td>
        $this->smarty->assign('sPicTdRowSpan',"rowspan=$iRowSpanCount");

        if ($errordatebirth) $this->smarty->assign('LDBday',"$LDBday<font color=red>*</font>:");
        else $this->smarty->assign('LDBday',"$LDBday<font color=red>*</font> ");

        //gjergji : new calendar //remove onchange="calctuoi(this.value,this)" 0310 - cot
        $this->smarty->assign('sBdayInput','<input  onkeypress="return tabE(this,event)" tabindex=4 id="f-calendar-field-4" onblur="calctuoi(this.value,this)"  type="text"  style="width:90px;" value="'.formatDate2Local($date_birth,$date_format).'" name="date_birth">
									<a id="f-calendar-trigger-4" href="#">
										<img border="0" align="middle" alt="" src="../../js/jscalendar/img.gif">
									</a>
									<script type="text/javascript">
									Calendar.setup({"ifFormat":"%d/%m/%Y","daFormat":"%d/%m/%Y","firstDay":1,"showOthers":true,"button":"f-calendar-trigger-4","inputField":"f-calendar-field-4"});
									</script>');
        //end gjergji
        //$this->smarty->assign('LDTuoi',$LDTuoi.':');

        $this->smarty->assign('scheckbox','Trẻ sơ sinh <input onkeypress="return tabE(this,event)" type="checkbox" id="tresosinh" name="tresosinh" onchange="check()" value="tresosinh"  >');


        if(isset($date_birth)&&($date_birth!='0000-00-00')){
            $tuoi=date("Y")-substr($date_birth,0,4);
        }
        $this->smarty->assign('sInputTuoi','<div id="showtuoi" style="display:inline;">'.$LDTuoi.' <input  onkeypress="return tabE(this,event)"  id="tuoi" name="tuoi" type="text" size="5" value="'.$tuoi.'" onblur="checkTuoi(this.value)" ></div>
		
		');

        $this->smarty->assign('sInputThang','<div id="showthang" style="display:none;"> Tháng: <input onkeypress="return tabE(this,event)" name="thang" id="thang" onblur="calthang(this.value)" type="text" size="5" value="'.$thang.'"  > </div>');
        $this->smarty->assign('LDSex', "$LDSex<font color=#ff0000>*</font>");

        if($sex=='m'){
            $sSexMBuffer='<select onkeypress="return tabE(this,event)" tabindex=5  name="sex" style="width:96%"><option value="0">Không rõ</option><option value="m" selected="selected">'.$LDMale.'</option><option value="f">'.$LDFemale.'</option></select>';
        }else if($sex=='f'){
            $sSexMBuffer='<select onkeypress="return tabE(this,event)" tabindex=5  name="sex"  style="width:96%"><option value="0">Không rõ</option><option value="m">'.$LDMale.'</option><option value="f" selected="selected">'.$LDFemale.'</option></select>';
        }else{
            $sSexMBuffer='<select onkeypress="return tabE(this,event)" tabindex=5  name="sex"  style="width:96%"><option value="0" selected="selected">Không rõ</option><option value="m">'.$LDMale.'</option><option value="f">'.$LDFemale.'</option></select>';
        }
        $this->smarty->assign('sSex',$sSexMBuffer);


        # But patch 2004-03-10
        # Clean blood group

        $blood_group = trim($blood_group);
        $sql="SELECT * FROM care_type_blood";
        if($temp=$db->Execute($sql)){
            $temp->RecordCount();

            //  Made hideable as suggested by Kurt Brauchli
            if (!$GLOBAL_CONFIG['person_bloodgroup_hide'] ){
                $this->smarty->assign('LDBloodGroup',$LDBloodGroup);
                $sBGBuffer='<select name="blood_group" style="width:96%">';
                while($buf=$temp->FetchRow()){
                    if($blood_group==$buf['name']) $selected='selected';else $selected='';
                    $sBGBuffer=$sBGBuffer.'<option value="'.$buf['name'].'" '.$selected.'>'.$$buf['LD_var'].'</option>';
                }
                $sBGBuffer.='</select>';
                $this->smarty->assign('sBGInput',$sBGBuffer);
            }
        }
        // KB: make civil status hideable
        if (!$GLOBAL_CONFIG['person_civilstatus_hide']){
            $this->smarty->assign('LDCivilStatus',$LDCivilStatus);
            $sCSInput='<select name="civil_status" style="width:96%"  onkeypress="return tabE(this,event)">
					<option value="không rõ">'.$LDKhongRo.'</option>
					<option value="single">'.$LDSingle.'</option>
					<option value="married">'.$LDMarried.'</option>
					<option value="divorced">'.$LDDivorced.'</option>
					<option value="widowed">'.$LDWidowed.'</option>
					<option value="separated">'.$LDSeparated.'</option>
					</select>
			';


            $this->smarty->assign('sCSInput',$sCSInput);


        }



        if ($erroraddress) $this->smarty->assign('LDAddress',"<font color=red>$LDAddress</font>:");
        else $this->smarty->assign('LDAddress',"$LDAddress:");


        $this->smarty->assign('LDStreet'," $LDStreet ");

        $this->smarty->assign('sStreetInput','<input onkeypress="return tabE(this,event)" name="addr_str" type="text" maxlenght="50" style="width:96%;" value="'.$addr_str.'">');

        $this->smarty->assign('LDStreetNr'," $LDStreetNr ");

        $this->smarty->assign('sStreetNrInput','<input onkeypress="return tabE(this,event)" name="addr_str_nr" type="text" maxlenght="25" style="width:96%;" value="'.$addr_str_nr.'">');

        if ($errortown) $this->smarty->assign('LDStreet',"<font color=red><font color=#ff0000>*</font> $LDTownCity</font>:");
        else $this->smarty->assign('LDTownCity',"$LDTownCity<font color=#ff0000>*</font>");

        require_once($root_path.'include/care_api_classes/class_address.php');
        $sAddress = '<input id="addr_zip" size="3" name="addr_zip" type="text" value="'.$addr_zip.'"><select onkeypress="return tabE(this,event)" tabindex=6 style="width:80%;" id="addr_citytown_name" name="addr_citytown_nr" onblur="showQuanhuyen()">';
        $address_obj=new Address;
        $address = $address_obj->getAllActiveCityTown();
        if(!empty($address)) {
            if($address->RecordCount()) {
                while($addr=$address->FetchRow()){
                    if($addr_citytown_nr == $addr['nr'] ) $selected = ' selected '; else $selected = ' ';
                    $sAddress .= '<option value="' . $addr['nr'] . '"' . $selected . ' >' . $addr['name'] . '</option>';

                }
                $sAddress .= '</select>';
                $this->smarty->assign('sTownCityInput',$sAddress);
            } else {
                $this->smarty->assign('sTownCityInput',$LDNoAddress);
            }
        } else {
            $this->smarty->assign('sTownCityInput',$LDNoAddress);
        }
        /*
                 if ($errorzip) $this->smarty->assign('LDZipCode',"<font color=red> $LDZipCode</font> ");
                 else  $this->smarty->assign('LDZipCode'," $LDZipCode ");
                 $this->smarty->assign('sZipCodeInput','');*/

        //add 17-11
        $this->smarty->assign('LDHuyenxa',$LDHuyenxa.' ');
        $sAddrQH='<select onkeypress="return tabE(this,event)" tabindex=7 style="width:96%;" id="quanhuyen"  name="addr_quanhuyen_nr" onblur="showXaphuong()">';
        if(!empty($addr_quanhuyen_nr)){
            $sql="SELECT name FROM care_address_quanhuyen WHERE nr='".$addr_quanhuyen_nr."' order by use_frequency DESC"; //edit 0310 - cot
            $temp=$db->Execute($sql);
            if($temp->RecordCount()){
                $temp1=$temp->FetchRow();
                $sAddrQH.='<option  value="'.$addr_quanhuyen_nr.'">'.$temp1['name'].'</option>';
            }
        }else{
            //$sAddrQH='<select  onkeypress="return tabE(this,event)"  tabindex=7 name="addr_quanhuyen_nr" id="addr_quanhuyen_name" style="width:96%;" onblur="showXaphuong()">';
            $sql="SELECT name, nr FROM care_address_quanhuyen WHERE citytown_id = (select a.nr from care_address_citytown a where a.use_frequency = (select max(b.use_frequency) from care_address_citytown b)) order by use_frequency DESC"; //edit 0310 - cot
            $temp=$db->Execute($sql);
            if($temp->RecordCount()){
                while($temp1=$temp->FetchRow())
                    $sAddrQH .='<option  value="'.$temp1['nr'].'">'.$temp1['name'].'</option>';
            }
        }
        $sAddrQH.='</select>';
        $this->smarty->assign('sHuyenxaInput',$sAddrQH);
        $this->smarty->assign('LDThonPhuong',$LDThonPhuong.' ');
        $sAddrPX='<select onkeypress="return tabE(this,event)" tabindex=8 style="width:96%;" id="xaphuong"  name="addr_phuongxa_nr" >';
        if(!empty($addr_phuongxa_nr)){
            $sql="SELECT name FROM care_address_phuongxa WHERE nr='".$addr_phuongxa_nr."'";
            $temp=$db->Execute($sql);
            if($temp->RecordCount()){
                $temp1=$temp->FetchRow();
                $sAddrPX.='<option  value="'.$addr_phuongxa_nr.'">'.$temp1['name'].'</option>';
            }
        }else{
            $sql="SELECT name, nr FROM care_address_phuongxa WHERE quanhuyen_id = (select a.nr from care_address_quanhuyen a where a.use_frequency = (select max(b.use_frequency) from care_address_quanhuyen b))"; //edit 0310 - cot
            $temp=$db->Execute($sql);
            if($temp->RecordCount()){
                while($temp1=$temp->FetchRow())
                    $sAddrPX .='<option  value="'.$temp1['nr'].'">'.$temp1['name'].'</option>';
            }

        }
        $sAddrPX.='</select>';
        $this->smarty->assign('sThonPhuongInput',$sAddrPX);

        // KB: make insurance completely hideable
        /*
        if (!$GLOBAL_CONFIG['person_insurance_hide']){
            if($insurance_show) {
                if (!$person_insurance_1_nr_hide) {

                    $this->smarty->assign('bShowInsurance',TRUE);

                    $this->smarty->assign('sInsuranceNr',$this->createTR($errorinsurancenr, 'insurance_nr', $LDInsuranceNr.' 1',$insurance_nr,2,35,true,' id="inputisurance" '));

                    if ($errorinsuranceclass) $this->smarty->assign('sErrorInsClass',"<font color=\"$error_fontcolor\">");

                    if($insurance_classes!=false){
                        $sInsClassBuffer='';
                        while($result=$insurance_classes->FetchRow()) {

                            $sInsClassBuffer.='<input class="reg_input_must" name="insurance_class_nr" type="radio"  value="'.$result['class_nr'].'" ';
                            if($insurance_class_nr==$result['class_nr']) $sInsClassBuffer.='checked';
                            $sInsClassBuffer.='>';

                            $LD=$result['LD_var'];
                            if(isset($$LD)&&!empty($$LD)) $sInsClassBuffer.=$$LD; else $sInsClassBuffer.=$result['name'];
                            $sInsClassBuffer.='&nbsp;';
                        }

                        $this->smarty->append('sInsClasses',$sInsClassBuffer);

                    } else {
                        $this->smarty->assign('sInsClasses','Nuk jane konfiguruar klasat e sigurimit');
                    }

                    if ($errorinsurancecoid) $this->smarty->assign('LDInsuranceCo',"<font color=red>$LDInsuranceCo</font> ");
                    else  $this->smarty->assign('LDInsuranceCo',"$LDInsuranceCo ");
                    //gjergji mod per insurance
                    $insurance_firm_name = $pinsure_obj->getFirmName($GLOBAL_CONFIG['person_insurace_firm_default_id']);
                    $this->smarty->assign('sInsCoNameInput','<input name="insurance_firm_name" type="text" size="35" value="'.$insurance_firm_name.'">');
                    $this->smarty->assign('sInsCoMiniCalendar',"<a href=\"javascript:popSearchWin('insurance','aufnahmeform.insurance_firm_id','aufnahmeform.insurance_firm_name')\"><img ".createComIcon($root_path,'b-write_addr.gif','0')."></a>");
                }
            } else {
                $this->smarty->assign('bNoInsurance',TRUE);
                $this->smarty->assign('LDSeveralInsurances','<a href="#">$LDSeveralInsurances <img '.createComIcon($root_path,'frage.gif','0').'></a>');
            }
        }*/
        $this->smarty->assign('LDPhone1',$LDPhone);
        $this->smarty->assign('sPhone1','<input onkeypress="return tabE(this,event)" id="phone_1_nr" name="phone_1_nr" type="text" maxlength=10 style="width:96%;" value="'.$phone_1_nr.'">');

        //thay doi nhap bhyt tu tiep nhan sang dang ki

        $this->smarty->assign('LDBillType',"<font color=red>$LDBillType</font>");


        $sTemp = '<select onkeyup="blurbhyt($(this))" onblur="blurbhyt($(this))" onkeypress="return tabE(this,event)" tabindex=9 id="insurance_class_nr"  name="insurance_class_nr" style="width:96%;">';
        if(is_object($insurance_classes)){
            while($result=$insurance_classes->FetchRow()) {
                if($insurance_class_nr==$result['class_nr'])  $selected = ' selected '; else $selected = ' ';
                $LD=$result['LD_var'];
                $sname='';
                if(isset($$LD)&&!empty($$LD)) $sname = $sname.$$LD;
                else $sname = $sname.$result['name'];
                $sTemp = $sTemp.'<option value="'.$result['class_nr'].'"' . $selected . ' >'.$sname.'</option>';

            }
            $sTemp.='</select>';
        }

        $this->smarty->assign('sBillTypeInput',$sTemp);
        $sTemp = '';
        $this->smarty->assign('LDInsuranceNr',"<font color=red>$LDInsuranceNr</font>");

        if(isset($insurance_nr) && $insurance_nr) $sTemp = $insurance_nr;
        $this->smarty->assign('insurance_nr','<input onkeypress="return tabE(this,event)" tabindex=10 name="insurance_nr" type="text" style="width:96%;" value="'.$insurance_nr.'" id="inputisurance" onblur="ChangeCase(this);"> '); //edit 0310 - cot
        //add 3-1-2012
        $this->smarty->assign('LDMaDKKCB',$LDMaDKKCB);
        $this->smarty->assign('madk_kcbbd','<input onkeypress="return tabE(this,event)" tabindex=13 name="madkbd" id="madkbd" type="text" maxlenght="5" style="width:96%" value="'.$madkbd.'" onblur="checkKCB(this.value)">');
        $this->smarty->assign('LDTinhtrang',$LDTinhtrang);
        if($is_traituyen==0){
            $stemp='<select onkeypress="return tabE(this,event)" id="traituyen" name="is_traituyen" style="width:96%">
			<option value="0" selected>Không rõ</option>
			<option value="1" >Đúng tuyến</option>
			<option value="2">Trái tuyến</option>
			</select>';
        }elseif($is_traituyen==1){
            $stemp='<select onkeypress="return tabE(this,event)" id="traituyen" name="is_traituyen" style="width:96%">
			<option value="0" >Không rõ</option>
			<option value="1" selected>Đúng tuyến</option>
			<option value="2">Trái tuyến</option>
			</select>';
        }elseif($is_traituyen==2){
            $stemp='<select onkeypress="return tabE(this,event)" id="traituyen" name="is_traituyen" style="width:96%">
			<option value="0" >Không rõ</option>
			<option value="1" >Đúng tuyến</option>
			<option value="2" selected>Trái tuyến</option>
			</select>';
        }
        $this->smarty->assign('sTinhtrangInput',$stemp);
        //add 9-11
        $this->smarty->assign('LDInsuranceStart',$LDInsuranceStart);
        //$this->smarty->assign('sInsStartDayInput',$calendar->show_calendar($calendar,$date_format,'insurance_start',$insurance_start));
        if(!empty($insurance_start)) $sTemp = $insurance_start;
        $this->smarty->assign('sInsStartDayInput','<input onkeypress="return tabE(this,event)" id="f-calendar-field-2" tabindex=11  type="text" name="insurance_start"  value="'.$sTemp.'" style="width:96%;" >');
        if(!empty($insurance_exp)) $sTemp =$insurance_exp;
        $this->smarty->assign('sInsExpDayInput','<input onkeypress="return tabE(this,event)" id="f-calendar-field-3" tabindex=12  type="text" name="insurance_exp"  value="'.$sTemp.'" style="width:96%;">');
        $this->smarty->assign('LDInsuranceExp',$LDInsuranceExp);


//        if(!empty($insurance_exp)) $sTemp = @formatDate2STD($insurance_exp,$date_format);
//        $this->smarty->assign('sInsExpDayInput','<input onkeypress="return tabE(this,event)" id="f-calendar-field-3" tabindex=12  type="text" name="insurance_exp"  value="'.$sTemp.'" style="width:96%;">');
        //$this->smarty->assign('sInsExpDayInput',$calendar->show_calendar($calendar,$date_format,'insurance_exp',$insurance_exp));
        $this->smarty->assign('LDNoicap',$LDNoicap);
        if(!empty($insurance_local)){
            $this->smarty->assign('insurance_loca','<input onkeypress="return tabE(this,event)" name="insurance_local" id="insurance_local" type="text" style="width:96%;" value="'.$insurance_local.'"> ');
        }else{
            $this->smarty->assign('insurance_loca','<input onkeypress="return tabE(this,event)" name="insurance_local" id="insurance_local" type="text" style="width:96%;" value="Bình Dương"> ');
        }
        if(!isset($insurance_firm_id)) $insurance_firm_id = 'BHYT'; //edit 0310 - cot
        $sTemp = '';	$insurance_firm_name=$pinsure_obj->getFirmName($insurance_firm_id);
        if(isset($insurance_firm_name)) $sTemp = $insurance_firm_name;


        $this->smarty->assign('LDInsuranceCo',$LDInsuranceCo);

        $sBuffer ="<a href=\"javascript:popSearchWin('insurance','aufnahmeform.insurance_firm_id','aufnahmeform.insurance_firm_name')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
        $this->smarty->assign('insurance_firm_name','<input name="insurance_firm_name" id="insruance_firm_name" type="text" style="width:90%;" value="'.$sTemp.'" readonly>'.$sBuffer);



        //

        $this->smarty->assign('LDCellPhone1',$LDCellPhone);
        $this->smarty->assign('sCellPhone1','<input onkeypress="return tabE(this,event)" id="cellphone_1_nr" name="cellphone_1_nr" type="text" maxlength=11 style="width:96%;" value="'.$cellphone_1_nr.'">');

        $this->smarty->assign('LDFax',$LDFax);
        $this->smarty->assign('sFax','<input onkeypress="return tabE(this,event)" name="fax" type="text" maxlength=10 style="width:96%;" value="'.$fax.'">');
        $this->smarty->assign('LDEmail',$LDEmail);
        $this->smarty->assign('sEmail','<input onkeypress="return tabE(this,event)" name="email" type="text" id="email" maxlenght="100" style="width:96%;" value="'.$email.'">');
        $this->smarty->assign('LDCitizenship',$LDCitizenship);
        $this->smarty->assign('sCitizenship','<input onkeypress="return tabE(this,event)" name="citizenship" type="text" maxlength="25" style="width:96%;" value="Việt Nam">');
        $this->smarty->assign('LDSSSNr',$LDSSSNr);
        $this->smarty->assign('sSSSNr','<input onkeypress="return tabE(this,event)" id="sss_nr" name="sss_nr" type="text" style="width:96%;" value="'.$sss_nr.'">');
        $this->smarty->assign('LDNatIdNr',$LDNatIdNr);
        $this->smarty->assign('sNatIdNr','<input onkeypress="return tabE(this,event)" id="nat_id_nr" name="nat_id_nr" type="text" maxlength="9" style="width:96%" value="'.$nat_id_nr.'">');

        $this->smarty->assign('LDReligion',$LDReligion);
        if(!empty($religion)){
            $this->smarty->assign('sReligion','<input onkeypress="return tabE(this,event)" name="religion" type="text" maxlenght="25" style="width:96%;" value="'.$religion.'">');
        }else{
            $this->smarty->assign('sReligion','<input onkeypress="return tabE(this,event)" name="religion" type="text" maxlenght="25" style="width:96%;" value="Không">');
        }
        //Nghe nghiep
        $this->smarty->assign('LDnghenghiep',$LDnghenghiep);
        $this->smarty->assign('sNghenghiep','<input onkeypress="return tabE(this,event)" tabindex=14 name="nghenghiepcode" maxlength="2" type="text" onblur="getnghenghiep(this.value)" style="width:10%;" value="'.$nghenghiepcode.'">&nbsp;<input name="nghenghiep" id="nghenghiep" maxlength="30" type="text" style="width:85%;" value="'.$nghenghiep.'">');

        //Noi lam viec
        $this->smarty->assign('LDnoilamviec',$LDnoilamviec);
        if(!empty($noilamviec)){
            $this->smarty->assign('sNoilamviec','<input onkeypress="return tabE(this,event)" name="noilamviec" maxlength="30" type="text" style="width:96%;" value="'.$noilamviec.'">');
        }else{
            $this->smarty->assign('sNoilamviec','<input onkeypress="return tabE(this,event)" name="noilamviec" maxlength="30" type="text" style="width:96%;" value="Bình Dương">');
        }
        //Ho ten nguoi bao tin
        $this->smarty->assign('LDhotenbaotin','<p id="baotin">'.$LDhotenbaotin.'</p>');
        $this->smarty->assign('sHotenbaotin','<input onblur="ChangeCase(this);" tabindex=15 onkeypress="return tabE(this,event)" name="hotenbaotin" maxlength="60" type="text" style="width:96%;" value="'.$hotenbaotin.'">');

        //Dien thoai bao tin
        $this->smarty->assign('LDdtbaotin',$LDdtbaotin);
        $this->smarty->assign('sDTbaotin','<input onkeypress="return tabE(this,event)" name="dtbaotin" type="text" maxlength="11" style="width:96%;" value="'.$dtbaotin.'">');

        //tien su benh ca nhan
        $this->smarty->assign('LDTSBenhCN'," $LDTSBenhCN ");
        if(!empty($tiensubenhcanhan)){
            $this->smarty->assign('sTSBenhCN','<input onkeypress="return tabE(this,event)" type="text" size="4" value="" style="vertical-align:top;" onblur="geticd10(this.value,\'tsbcanhan\')">&nbsp;<textarea id="tsbcanhan" name="tiensubenhcanhan" maxlength="255" style="width:75%;" rows="1">'.$tiensubenhcanhan.'</textarea>');
        }else{
            $this->smarty->assign('sTSBenhCN','<input onkeypress="return tabE(this,event)" type="text" size="4" value="" style="vertical-align:top;" onblur="geticd10(this.value,\'tsbcanhan\')">&nbsp;<textarea id="tsbcanhan" name="tiensubenhcanhan" maxlength="255" style="width:75%;" rows="1">Khỏe</textarea>');
        }
        //tien su benh gia dinh
        $this->smarty->assign('LDTSBenhGD'," $LDTSBenhGD ");
        if(!empty($tiensubenhgiadinh)){
            $this->smarty->assign('sTSBenhGD','<input onkeypress="return tabE(this,event)" type="text" size="3" value="" style="vertical-align:top;" onblur="geticd10(this.value,\'tsbgiadinh\')">&nbsp;<textarea id="tsbgiadinh" name="tiensubenhgiadinh" maxlength="255" style="width:75%;" rows="1">'.$tiensubenhgiadinh.'</textarea>');
        }else{
            $this->smarty->assign('sTSBenhGD','<input onkeypress="return tabE(this,event)" type="text" size="3" value="" style="vertical-align:top;" onblur="geticd10(this.value,\'tsbgiadinh\')">&nbsp;<textarea id="tsbgiadinh" name="tiensubenhgiadinh" maxlength="255" style="width:75%;" rows="1">Khỏe</textarea>');
        }
        if (!$GLOBAL_CONFIG['person_ethnic_orig_hide']){

            /** Add by Jean-Philippe LIOT 13/05/2004 **/
            $this->smarty->assign('LDEthnicOrig',$LDEthnicOrigin);
            if(!empty($ethnic_orig)){
                $this->smarty->assign('sEthnicOrigInput','<input onkeypress="return tabE(this,event)" type="text" name="ethnic_orig" onblur="getdantoc(this.value)" size="3" value="'.$ethnic_orig.'"><input  onkeypress="return tabE(this,event)" id="ethnic_orig_txt" name="ethnic_orig_txt" type="text" maxlength="15" style="90%" value="'.$ethnic_orig_txt.'" readonly >');
            }else{
                $this->smarty->assign('sEthnicOrigInput','<input onkeypress="return tabE(this,event)" type="text" name="ethnic_orig" onblur="getdantoc(this.value)" size="3" value="25"><input id="ethnic_orig_txt" name="ethnic_orig_txt" type="text" maxlength="15" style="90%" value="Kinh" readonly >');
            }
        }
        // KB: add a field for other HIS nr
        /*	if (!$GLOBAL_CONFIG['person_other_his_nr_hide']){
                $this->smarty->assign('bShowOtherHospNr',TRUE);

                $this->smarty->assign('LDOtherHospitalNr',$LDOtherHospitalNr);

                $other_hosp_list = $person_obj->OtherHospNrList();
                $sOtherNrBuffer='';
                foreach( $other_hosp_list as $k=>$v ){
                    $sOtherNrBuffer.="<b>".$kb_other_his_array[$k].":</b> ".$v."<br />\n";
                }

                $this->smarty->assign('sOtherNr',$sOtherNrBuffer);

                $sOtherNrBuffer='';
                $sOtherNrBuffer.="<SELECT style=\"width:36%\" name=\"other_his_org\">".
                            "<OPTION value=\"\">--</OPTION>";
                foreach( $kb_other_his_array as $k=>$v ){
                    $sOtherNrBuffer.="<OPTION value=\"$k\" $check>$v</OPTION>";
                }
                $sOtherNrBuffer.="</SELECT>\n".
                        "&nbsp;&nbsp;".
                        "$LDNr:&nbsp;&nbsp;<INPUT name=\"other_his_no\" style=\"width:35%\">\n";



    $this->smarty->assign('LDSelectOtherHospital',$LDSelectOtherHospital);
            $this->smarty->assign('LDNoNrNoDelete',$LDNoNrNoDelete);
                $this->smarty->assign('sOtherNrSelect',$sOtherNrBuffer);
            }
            */
        $this->smarty->assign('LDRegBy',$LDRegBy);
        if(isset($user_id) && $user_id) $buffer=$user_id; else  $buffer = $_SESSION['sess_user_name'];
        $this->smarty->assign('sRegByInput','<input  onkeypress="return tabE(this,event)" name="user_id" type="text" value="'.$buffer.'"  style="width:96%" readonly>');

        # Collect the hidden inputs

        ob_start();
        ?>
        <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000">
        <input type="hidden" name="itemname" value="<?php echo $itemname; ?>">
        <input type="hidden" name="sid" value="<?php echo $sid; ?>">
        <input type="hidden" name="lang" value="<?php echo $lang; ?>">
        <input type="hidden" name="linecount" value="<?php echo $linecount; ?>">
        <input type="hidden" name="mode" value="save">

        <input type="hidden" name="insurance_item_nr" value="<?php echo $insurance_item_nr; ?>">
        <input type="hidden" name="insurance_firm_id" value="BHYT"> <?php // remove 0310 cot - echo $GLOBAL_CONFIG['person_insurace_firm_default_id']; ?>
        <input type="hidden" name="insurance_show" value="<?php echo $insurance_show; ?>">

        <?php
        if($update){
            $this->smarty->assign('sUpdateHiddenInputs','<input type="hidden" name="update" value=1><input type="hidden" name="pid" value="'.$pid.'">');
        }

        $sTemp= ob_get_contents();
        ob_end_clean();
        $this->smarty->assign('sHiddenInputs',$sTemp);

        $this->smarty->assign('pbSubmit','<input  tabindex=16  type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'  alt="'.$LDSaveData.'" align="absmiddle">');
        $this->smarty->assign('pbReset','<a href="javascript:document.aufnahmeform.reset()"><img '.createLDImgSrc($root_path,'reset.gif','0').' alt="'.$LDResetData.'"   align="absmiddle"></a>');

        if($error||$error_person_exists) $this->smarty->assign('pbForceSave','<input  type="button" value="'.$LDForceSave.'" onClick="forceSave()">');

        if (!$newdata){
            ob_start();
            ?>
            <form action=<?php echo $thisfile; ?> method=post>
                <input type=hidden name=sid value=<?php echo $sid; ?>>
                <input type=hidden name=patnum value="">
                <input type=hidden name="lang" value="<?php echo $lang; ?>">
                <input type=hidden name="date_format" value="<?php echo $date_format; ?>">
                <input type=submit value="<?php echo $LDNewForm ?>" >
            </form>
            <?php
            $sTemp= ob_get_contents();
            ob_end_clean();
            $this->smarty->assign('sNewDataForm',$sTemp);
        }

        # Set the form template as form
        $this->smarty->assign('bSetAsForm',TRUE);

        if($this->bReturnOnly){
            ob_start();
            $this->smarty->display('registration_admission/reg_form.tpl');
            $sTemp=ob_get_contents();
            ob_end_clean();
            return $sTemp;
        }else{
            # show Template
            $this->smarty->display('registration_admission/reg_form.tpl');
        }
    } // end of function

    function create(){
        $this->bReturnOnly = TRUE;
        return $this->display();
    }
} // end of class
?>
