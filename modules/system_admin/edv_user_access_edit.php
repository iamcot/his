<?php
    error_reporting ( E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR ) ;
    require ('./roots.php') ;
    require ($root_path . 'include/core/inc_environment_global.php') ;
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
    * GNU General Public License
    * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
    * elpidio@care2x.org,
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    $lang_tables [] = 'access.php' ;
    $lang_tables[]='departments.php';
    define ( 'LANG_FILE', 'edp.php' ) ;
    $local_user = 'ck_edv_user' ;
    require_once ($root_path . 'include/core/inc_front_chain_lang.php') ;
    ///$db->debug=true;
    /**
    * The following require loads the access areas that can be assigned for
    * user permissions.
    */
    require ($root_path . 'include/core/inc_accessplan_areas_functions.php') ;

    $breakfile = 'edv-system-admi-welcome.php' . URL_APPEND ;
    $returnfile = $_SESSION [ 'sess_file_return' ] . URL_APPEND ;
    $_SESSION [ 'sess_file_return' ] = basename ( __FILE__ ) ;

    //gjergji : load the department list
    require_once ($root_path . 'include/care_api_classes/class_department.php') ;
    $dept_obj = new Department ( ) ;
    $deptarray = $dept_obj->getAllActiveSort ( 'name_formal' ) ;

    require_once($root_path.'global_conf/areas_allow.php');
    //gjergji : load the access roles
    require_once($root_path.'include/care_api_classes/class_access.php');
    $role_obj = & new Access();

    #Xu li cac thao tac
    if(isset($_POST['addpermit'])){
       // var_dump($_POST);
        $permitstr = "";
        foreach ($_POST as $key => $post) {
          //  echo $key;
            //if($key!=$_POST['puser']&&$key!=$_POST['addpermit']){
             //   if($_POST['System_Admin'] == "on") $permitstr .= 'System_Admin ';
            //    else if($_POST['_a_0_all'] == "on") $permitstr .= '_a_0_all ';
            //    else $permitstr .= $key." ";
           // }
            if($_POST['System_Admin'] == "on" && $key == 'System_Admin') $permitstr .= 'System_Admin ';
            if($_POST['_a_0_all'] == "on" && $key == '_a_0_all') $permitstr .= '_a_0_all ';
        }
        echo $permitstr;
        if($_POST['puser']!='0')
            $rs = $role_obj->UpdateUserPermit($_POST['puser'],$permitstr);
        //var_dump($rs);
    }
    if(isset($_POST['createuser'])){
        $user = $_POST['username'];
        $passwd = $_POST['passwd'];
        $name = $_POST['personell'];
        $rs = $role_obj->saveNewLoginID($personell,$user,$passwd);
        var_dump($rs);
    }
    if(isset($_POST['updatepersonell'])){
        $login_id = $_POST['login_id'];
        $personell = $_POST['personell'];
     //   var_dump($login_id.'@'.$personell);
        $rs = $role_obj->updatepersonell($login_id,$personell);
       // var_dump($rs);
    }

    #Xu ly hien thi
    #Nhan vien voi login_id
    $sTemp = "";
    $usersrs = $role_obj->getAllActiveUserlogin();
    $selectusers = '<option value="0" >'.$LDSelectone.'</option>';
    while($user = $usersrs->FetchRow()){
        $selectusers .= '<option value="'.$user['login_id'].'">'.$user['login_id'].'</option>';
    }
    #Nhan vien personnell
    $selectusers .= '';
    $selectpersonell = "<option value='0'>Chọn nhân viên</option>";
    $arrpersonell = $role_obj->getAllpersonell();
    while($row = $arrpersonell->Fetchrow()){
        $selectpersonell .= "<option value='".$row['pnid']."'>".$row['pname']."</option>";
    }
    #Nhan vien voi personell_nr
    $usersrs = $role_obj->getAllActiveUserlogin();
    $selectloginhavepersonnr = '<option value="0" >'.$LDSelectone.'</option>';
    while($user = $usersrs->FetchRow()){
        $selectloginhavepersonnr .= '<option value="'.$user['personell_nr'].'">'.$user['login_id'].'</option>';
    }
    #Tao nguoi dung moi
    $formnewuser = '<form action="" method="POST"  style="width:30%;float:left;">
      <fieldset>
        <legend >'.$LDChangePass.'</legend>
        <table  style="width:90%"> 
        <tr><td colspan=2><select name="user" style="width:90%;display:block;padding:5px;margin-bottom:10px;clear:both;">'.$selectusers.'</select></td></tr>       
        <tr><td style="width:35%">'.$LDNewPass.':</td><td> <input type="text" name="newpass" style="width:90%;"/></td></tr>
        <tr><td></td><td><input type="submit" value="OK" class="butbg"/></td></tr>
        </table>
      </fieldset>
    </form>
    <form action="" method="POST" style="width:30%;float:left;">
      <fieldset >
        <legend >'.$LDCreateNewUser.'</legend>
        <table  style="width:90%">
        <tr><td style="width:25%">ID Login:</td><td> <input type="text" name="username"  style="width:90%;"/></td></tr>
        <tr><td style="width:25%">Nhân viên:</td><td><select name="personell" style="width:90%;display:block;padding:5px;margin-bottom:10px;clear:both;">'.$selectpersonell.'</select> </td></tr>
        <tr><td>Pass:</td><td> <input type="text" name="passwd"  style="width:90%;"/></td></tr>
        <tr><td></td><td><input type="submit" value="OK" class="butbg" name="createuser"/></td></tr>
        </table>
      </fieldset>
    </form>
    <form action="" method="POST" style="width:30%;float:left;">
      <fieldset >
        <legend >'.$LDupdatepersonell.'</legend>
        <table  style="width:90%">
        <tr><td colspan=2><select name="user" style="width:90%;display:block;padding:5px;margin-bottom:10px;clear:both;" onblur="getPersonellUpdate(this.value)" id="selectlogin">'.$selectloginhavepersonnr.'</select></td></tr>      
        <tr><td style="width:25%">Nhân viên:</td><td id="updatepersonelllist"><select name="personell" style="width:90%;display:block;padding:5px;margin-bottom:10px;clear:both;" >'.$selectpersonell.'</select> </td></tr>
        <tr><td></td><td><input type="hidden" name="login_id" id="updatepersonellloginid"/><input type="submit" value="OK" class="butbg" name="updatepersonell"/></td></tr>
        </table>
      </fieldset>
    </form>';
   //<input type="text" name="fullname"/>

   
    #Lay danh sach nguoi dung dang nhap
    
    $sTemp .= $formnewuser;
     $usersrs = $role_obj->getAllActiveUserlogin();
     $selectusers = '<select name="puser" style="width:90%;display:block;padding:5px;margin-bottom:10px;clear:both;" onblur="loadUser(this.value)"><option value="0"  >'.$LDSelectone.'</option>';
    while($user = $usersrs->FetchRow()){
        $selectusers .= '<option value="'.$user['login_id'].'">'.$user['name'].'</option>';
    }
    $selectusers .= '</select>';
    $sTemp .= '<form action="" method="POST" style="clear:both;">';
    $sTemp .= '<fieldset style="width:90%">
                <legend >'.$LDPermitManager.'</legend>'.$selectusers;
    
            
    $sTemp .= '<table class="property" ><tr>
            <td><input type="submit" name="addpermit" class="butadd" value=""></td><td style="text-align:right" id="actionstatus"></td></tr>
            <tr>
                <td class="adm_item" style="width:30%;border:1px solid #aaa;">'.$LDSelectKhoa.'</td>
                <td class="adm_item" style="border:1px solid #aaa;" ></td>
            </tr>';
    $sTemp .='<tr><td id="deptdisplay" style="border:1px solid #aaa;vertical-align:top;"></td><td id="permissiondisplay" style="border:1px solid #aaa;vertical-align:top;"></td></tr>
    </table>
    <tr><td><input type="submit" name="addpermit" class="butadd" value=""></td></tr></table>
    </fieldset></form>';
    /*
    <td class="adm_item" style="width:30%;border:1px solid #aaa;">'.$LDSelectbyChucvu.'</td>
                <td class="adm_item" style="border:1px solid #aaa;">'.$LDSelectByRole.'</td></tr>


    $role_obj = & new Access();
    $roles = $role_obj->loadAllRoles();
    
    $edit = 0 ;
    $error = 0 ;

    if (! isset ( $mode ))
    $mode = '' ;
    if (! isset ( $errorname ))
    $errorname = '' ;
    if (! isset ( $erroruser ))
    $erroruser = '' ;
    if (! isset ( $username ))
    $username = '' ;
    if (! isset ( $userid ))
    $userid = '' ;
    if (! isset ( $errorpass ))
    $errorpass = '' ;
    if (! isset ( $pass ))
    $pass = '' ;
    if (! isset ( $errorbereich ))
    $errorbereich = '' ;
    if (! isset ( $dept_nr ))
    $dept_nr = '0' ;
    if ($mode != '') {
        if ($mode != 'edit' && $mode != 'update' && $mode != 'data_saved') {
            # Trim white spaces off 
            $name = trim ( $name) ;
            $userid = trim ( $userid ) ;
            $pass = trim ( $pass ) ;
            if ($name == '') {
                $errorname = 1 ;
                $error = 1 ;
            }
            if ($userid == '') {
                $erroruser = 1 ;
                $error = 1 ;
            }
            if ($pass == '') {
                $errorpass = 1 ;
                $error = 1 ;
            }
        }

        if (($mode == 'save' && ! $error) || ($mode == 'update' && ! $erroruser)) {

            # If permission area is available, save it 
            
            if($permission != '') {
                if ($mode == 'save') {
                    $username=  explode('#', $name);
                    $get_permission= explode('#', $permission);
                    $sql = "SELECT name FROM care_users WHERE name='$username[0]'" ;
                    if ($ergebnis = $db->Execute($sql)) {
                        $user1 = $ergebnis->FetchRow() ;
                        if($user1['name']!= $username[0] || $user1['name']=''){
                            $sql1 = "INSERT INTO care_users (
                                    name,
                                    login_id,
                                    password,
                                    permission,
                                    personell_nr,
                                    s_date,
                                    s_time,
                                    dept_nr,
                                    user_role,
                                    status,
                                    modify_id,
                                    create_id,
                                    create_time
                                    ) VALUES (
                                    '" . addslashes ( $username[0] ) . "',
                                    '" . addslashes ( $userid ) . "',
                                    '" . md5 ( $pass ) . "',
                                    '" . $get_permission[0] . "',
                                    '" . $username[1] . "',
                                    '" . date ( 'Y-m-d' ) . "',
                                    '" . date ( 'H:i:s' ) . "',
                                    '" .  serialize($dept_nr)  . "',
                                    '" . $get_permission[1] . "',
                                    'normal',
                                    '',
                                    '" . $_SESSION [ 'sess_user_name' ] . "',
                                    '" . date ( 'YmdHis' ) . "'
                                    )" ;
                        }
                    }
                }else {
                    $sql = "SELECT password, personell_nr, permission, user_role, dept_nr FROM care_users WHERE personell_nr='$personell_nr'" ;
                    if ($ergebnis = $db->Execute($sql)) {
                        $user1 = $ergebnis->FetchRow() ;
                    }
                    if($permission1==''){
                        $permission1=$user1['permission'];
                        $selected_role=$user1['user_role'];
                    }else{
                        $get_permission= explode('#', $permission1);
                        $permission1=$get_permission[0];
                        $selected_role=$get_permission[1];
                    }
                    if(sizeof($dept_nr)==0){
                        $dept_nr=$user1['dept_nr'];
                    }
                    if($pass==$user1['password']){
                        $pass=$pass;
                    }else{
                        $pass=md5 ( $pass );
                    }
                    echo $pass;
                    $sql1 = "UPDATE care_users SET password='".$pass."', permission='$permission1', dept_nr='".serialize($dept_nr)."',user_role ='$selected_role' ,modify_id='" . $_COOKIE [ $local_user . $sid ] . "'  WHERE personell_nr='$personell_nr'" ;                   
                }
                    # Do the query 
                if(!$sql1){
                    $edit=0;
                    $mode='error_name';
                }else{
                    $db->BeginTrans () ;
                    $ok = $db->Execute ( $sql1 ) ;
                    if($ok && $db->CommitTrans()){
                        //echo $sql;
                        header ( 'Location:edv_user_access_edit.php' . URL_REDIRECT_APPEND . '&userid=' . strtr ( $userid, ' ', '+' ) . '&mode=data_saved&personell_nr='.$personell_nr ) ;
                        exit ();
                    }else{
                        $db->RollbackTrans () ;
                        if ($mode != 'save')
                        $edit = 1 ;
                        $mode = 'error_double' ;
                    }
                }
            }else{
                if ($mode != 'save')
                    $edit = 1 ;
                $mode = 'error_noareas' ;
            } // end if ($p_areas!="")
        } // end of if($mode=="save"
        if ($mode == 'edit' || $mode == 'data_saved' || $edit) {
            $sql = "SELECT name, login_id, password, personell_nr, permission, dept_nr, user_role FROM care_users WHERE login_id='$userid' OR personell_nr='$personell_nr'" ;
            if ($ergebnis = $db->Execute($sql)) {
                if ($ergebnis->RecordCount()) {
                    $user = $ergebnis->FetchRow() ;
                    $edit = 1 ;
                    $pass=$user['password'];
                }
            }
        }        
    }    
    */
    # Start Smarty templating here
    #/
    #* LOAD Smarty
    #*
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme


    require_once ($root_path . 'gui/smarty_template/smarty_care.class.php') ;
    $smarty = new smarty_care ( 'system_admin' ) ;

    # Title in toolbar
    $smarty->assign ( 'sToolbarTitle', $LDManageAccess ) ;

    # href for return button
    $smarty->assign ( 'pbBack', $returnfile ) ;

    # href for help button
    #$smarty->assign ( 'pbHelp', "javascript:gethelp('edp.php','access','$mode')" ) ;

    # href for close button
    $smarty->assign ( 'breakfile', $breakfile ) ;

    # Window bar title
    $smarty->assign ( 'sWindowTitle', $LDManageAccess ) ;

    # buffer script
    ob_start();
    ?>
    <script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
    <script type="text/javascript">
    function checkDept(checkbox,usernr,deptnr,deptLD){
        //alert(checkbox.checked);
        if(checkbox.checked == true){
            if(confirm("Có chắc muốn bỏ quyền ở khoa này")){
                removeDept(deptnr,deptLD,usernr);
                
                checkbox.checked = false;

            }            
        }
        else{
            saveDeptSelect(deptnr,usernr);
            loadDeptPermission(usernr,deptnr,deptLD);
          //  checkbox.checked = true;
        }
    }
    function loadDeptPermission(usernr,deptnr,deptLD){
        //alert(checkbox.checked);
        $("#permissiondisplay").html("...");
        $.ajax({
            url:"dev_getDeptpermis.php",
            data:"type=getdept&usernr="+usernr+"&deptnr="+deptnr+"&ldvar="+deptLD,
            type:"POST",
            success:function(msg){
                $("#permissiondisplay").html(msg);
                //alert(msg);
            }
        });
    }
    function loadUser(usernr){
        $("#deptdisplay").html("...");
        $.ajax({
            url:"dev_getDeptpermis.php",
            data:"type=getuser&usernr="+usernr,
            type:"POST",
            success:function(msg){
                $("#deptdisplay").html(msg);
               // alert(msg);
            }
        });
    }
    function getRole(name,LDvar,user){
        //permisLists
        $("#permisLists").html("...");
        $("#actionstatus").html("...");
        $.ajax({
            url:"dev_getDeptpermis.php",
            data:"type=getroleofdept&name="+name+"&ldvar="+LDvar+"&user="+user,
            type:"POST",
            success:function(msg){
                $("#permisLists").html(msg);
               $("#actionstatus").html("Cập nhật chức vụ trong khoa...");
            }
        });
    }
    function getPersonellUpdate(personell_nr){
        var text = $("#selectlogin option:selected").text();
        $("#updatepersonellloginid").val(text);
        //alert(text);
        //updatepersonelllist
        $.ajax({
            url:"dev_getDeptpermis.php",
            data:"type=getPersonellUpdate&personell_nr="+personell_nr+"&login_id="+text,
            type:"POST",
            success:function(msg){
              //  alert(msg);
              $("#updatepersonelllist").html(msg);
            }
        });
    }
    function saveDeptSelect(deptnr,userlogin){
        $("#actionstatus").html("...");
        $.ajax({
            url:"dev_getDeptpermis.php",
            data:"type=savedeptselect&deptnr="+deptnr+"&userlogin="+userlogin,
            type:"POST",
            success:function(msg){
              //  alert(msg);
              $("#actionstatus").html("Cập nhật khoa...");
            }
        });
    }
    function removeDept(deptnr,deptLD,userlogin){
        $.ajax({
            url:"dev_getDeptpermis.php",
            data:"type=removedept&usernr="+userlogin+"&deptnr="+deptnr+"&ldvar="+deptLD,
            type:"POST",
            success:function(msg){
                $("#permissiondisplay").html("");
                //alert(msg);
            }
        });
    }
    function saveDeptTitle(dtitle,userlogin){

    }
    function saveAllPermissOfTitle(dtitle,userlogin){

    }
    function save1Permiss(deptLD,permiss,userlogin){

    }
    </script>
    <?
    $sTemp .= ob_get_contents () ;
    ob_end_clean () ;
/*
    # Buffer page output
    ob_start () ;
?>
<script type="text/javascript" src="<?php echo $root_path; ?>js/colortip-1.0/colortip-1.0-jquery.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/colortip-1.0/script.js"></script>
<ul>
<?php
    if (($mode != '' || $error) && $mode != 'edit') {
?>
<table border=0>
    <tr>
        <td>
            <img <?php echo createMascot ( $root_path, 'mascot1_r.gif', '0', 'bottom' ) ?> align="absmiddle" />
        </td>
        <td class="warnprompt">
            <?php
                if ($error)
                    echo $LDInputError ;
                elseif ($mode == 'data_saved')
                    echo $LDUserInfoSaved ;
                elseif ($mode == 'error_save')
                    echo $LDUserInfoNoSave ;
                elseif ($mode == 'error_noareas')
                    echo $LDNoAreas ;
                elseif ($mode == 'error_double')
                    echo $LDUserDouble ;
                elseif($mode == 'error_name')
                    echo $LDAccessName;
            ?>
        </td>
    </tr>
</table>
<?php
    }
?>
<FONT class="prompt">
    <?php
        if (($mode == "") and ($remark != 'fromlist')) {
                $gtime = date ( 'H.i' ) ;
                if ($gtime < '9.00')
                echo $LDGoodMorning ;
                if (($gtime > '9.00') and ($gtime < '18.00'))
                echo $LDGoodDay ;
                if ($gtime > '18.00')
                echo $LDGoodEvening ;
                echo ' ' . $_COOKIE [ $local_user . $sid ] ;
        }
    ?>
</FONT>
	<p>
	
	
	<FORM action="edv_user_access_list.php" name="all"><input type="hidden"
		name="sid" value="<?php echo $sid ; ?>"> <input type="hidden"
		name="lang" value="<?php echo $lang ; ?>"> <input type="submit"
		name=message value="<?php echo $LDListActual ?>"></FORM>
	<p>
	
	
	<form method="post" action="edv_user_access_edit.php" name="user"><input
		type="image"
		<?php echo createLDImgSrc ( $root_path, 'savedisc.gif', '0', 'absmiddle' ) ?>>

		<?php
		if ($mode == 'data_saved' || $edit) {
			echo '<input type="button" value="' . $LDEnterNewUser . '" onClick="javascript:window.location.href=\'edv_user_access_edit.php' . URL_REDIRECT_APPEND . '&remark=fromlist\'">' ;
		}
		?> 

	<table border=0 bgcolor="#000000" cellpadding=0 cellspacing=0>
            <tr>
		<td>
                    <table border="0" cellpadding="5" cellspacing="1">
                        <tr bgcolor="#dddddd">
                            <td colspan="3">
                                <?php echo $LDNewAccess ?>:
                            </td>
                        </tr>
                        <tr bgcolor="#dddddd">
                            <td>
                                <?php
                                    if ($errorname) {
                                        echo "<font color=red > <b>$LDName</b>" ;
                                    }else{
                                        echo $LDName ;
                                    }
                                    require_once($root_path.'include/care_api_classes/class_personell.php');
                                    $person= new Personell();
                                    $get_name= $person->getPid();
                                    if($edit){
                                        echo ' '.$LDChose.':    <font color="darkred"><b>'.$user['name'].'</b></font><br>';
                                ?>
                                <input type="hidden" name="username" value="<?php echo $user['name'] ; ?>" />
                                <input type="hidden" name="personell_nr" value="<?php echo $user['personell_nr'] ; ?>" />
                                <?php
                                    }elseif(isset($is_employee) && $is_employee) {
                                ?>
                                <input name="name" type="hidden"
                                <?php
                                        if ($username != "" && $personell_nr)
                                            echo ' value="' . $username .'#'.$personell_nr. '"> <b>' . $username . '</b>' ;
                                        else
                                            echo '>' ;
                                    }else{
                                ?>
                                        <select name="name">
                                            <?php
                                                while($name=$get_name->FetchRow()){
                                                    $namefull=$name['name_last'].' '.$name['name_first'];
                                                    $name_str=$namefull.'#'.$name['nr'];
                                                    echo '<option value="'.$name_str.'"';                                                    
                                                    if($name==$name_str) echo ' selected';
                                                    echo 'title="'.$name['job_function_title'].'"> '.$namefull.'</option>';
                                                }
                                            ?>
                                        </select>
                                    <?php
                                        }
                                    ?>
                                <input type="button" value="<?php echo $LDFindEmployee ; ?>"
                                        onClick="javascript:window.location.href='edv_user_search_employee.php<?php echo URL_REDIRECT_APPEND ;?>&remark=fromlist'" />
                                <input type=hidden name=route value=validroute>
                            </td>
                            <td>
                                <?php
                                    if($erroruser){
                                        echo "<font color=red > <b>$LDUserId</b>" ;
                                    }else{
                                        echo $LDUserId;
                                    }
                                    if ($edit){
                                        echo ' '.$LDChose.':    <font color="darkred"><b>'.$user['login_id'].'</b></font><br>';
                                    }else{
                                ?> 
                                    <input type=text name="userid" <?php if ($userid != "") echo 'value="' . $userid . '"' ;?> />
                                <?php
                                    }
                                ?>
                            </td>
                            <td>
                            <?php
                                if($errorpass){
                                    echo "<font color=red > <b>$LDPassword</b>" ;
                                }else{
                                    echo $LDPassword ;
                                }
                            ?>
                            <input type="password" name="pass" <?php if ($pass != "") echo "value=" . $pass ; ?> />
                            </td>
                        </tr>
                        <tr bgcolor="#dddddd">
                            <td valign="top">
                                <b>
                                    <?php echo $LDRole ?> :
                                </b>
                                <br>
                                <br>
                                <?php
                                    if($edit){
                                        $role_user=$role_obj->getrolename($user['user_role']);
                                        echo ' '.$LDChose.':    <font color="darkred"><b>'.$role_user['role_name'].'</b></font>';
                                        echo '<br><br>'.$LDEdit.':    ';
                                        echo '<select name="permission1">';
                                        echo '<option value="">'.$LDNote.'</option>';
                                        while ( list( $x, $v ) = each( $roles ) ) {
                                ?>
                                        <option value="<?php echo $v['permission'].'#'.$v['id'] ?>"<?php 
                                            if ($permission1 == $user['permission'].'#'.$user['user_role']) echo ' selected' ?>>
                                        <?php
                                            echo $v['role_name'];
                                        ?>
                                        </option>
                                <?php
                                        }
                                        echo '</select>';
                                        echo '<input type="hidden" name="permission" value="'.$user['permission'].'#'.$user['user_role'].'" />';
                                    }else{
                                ?>
                                    <select name="permission"> 
                                        <?php
                                            echo '<option value="">'.$LDNote.'</option>';
                                            while ( list( $x, $v ) = each( $roles ) ) {
                                        ?>
                                                <option value="<?php echo $v['permission'].'#'.$v['id'] ?>"<?php 
                                                    if ($permission == $user['permission'].'#'.$user['user_role']) echo ' selected' ?>>
                                                <?php
                                                    echo $v['role_name'];
                                                ?>
                                                </option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                <?php
                                    }
                                ?>
                            </td>
                            <td colspan="2">
                                <b>
                                    <?php echo $LDDept ?> :
                                </b>
                                <br>
                                <?php 
                                    while(list($x,$dept)=each($deptarray)){
                                        $actualDept = unserialize($user['dept_nr']);                                        
                                        $subDepts = $dept_obj->getAllSubDepts($dept['nr']);
                                ?>
                                <input type="checkbox" name="dept_nr[]" id="<?php echo $dept['nr'] ?>" value="<?php echo $dept['nr']?>" <?php if( in_array($dept['nr'],$actualDept)) echo 'checked' ?> />                               
                                
                                <?php 
                                    
                                        if(isset($$dept['LD_var'])&&!empty($$dept['LD_var'])) echo $$dept['LD_var'] . '<br>';
                                            else echo $dept['name_formal'] . '<br>';
                                        if($subDepts) {
                                                while (list($y,$sDept) = each($subDepts)) {
                                ?>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <sup>L</sup>&nbsp; 
                                                    <input type="checkbox" name="dept_nr[]" id="<?php echo $sDept['nr'] ?>" 
                                                           value="<?php echo $sDept['nr']?>" <?php if( in_array($sDept['nr'],$actualDept)) echo 'checked' ?> >
                                                    
                                <?php 
                                                        if(isset($$sDept['LD_var'])&&!empty($$sDept['LD_var'])) echo $$sDept['LD_var'] . '<br>';
                                                        else echo $sDept['name_formal'] . '<br>';
                                                }
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr bgcolor="#dddddd">
                            <td colspan=3>
                            <input type="hidden" name="itemname" value="<?php echo $itemname ?>" />
                            <input type="hidden" name="sid" value="<?php echo $sid ; ?>" />
                            <input type="hidden" name="lang" value="<?php echo $lang ; ?>" />
                            <input type="hidden" name="mode" value="<?php
                            if ($edit || $mode == 'data_saved' || $mode == 'edit')
                                    echo 'update' ; else
                                    echo 'save' ;
                            ?>" />
                            <input type="image"  <?php
                            echo createLDImgSrc ( $root_path, 'savedisc.gif', '0', 'absmiddle' ) ?>>
                            <input type="reset"  value="<?php
                            echo $LDReset ?>" /> 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>

    <a href="<?php echo $breakfile ?>" >
        <img <?php echo createLDImgSrc ( $root_path, 'cancel.gif', '0' ) ?> alt="<?php echo $LDCancel ?>" align="middle" />
    </a>

</ul>

<?php

$sTemp = ob_get_contents () ;
ob_end_clean () ;
*/
# Assign page output to the mainframe template


$smarty->assign ( 'sMainFrameBlockData', $sTemp ) ;
/**
 * show Template
 */
$smarty->display ( 'common/mainframe.tpl' ) ;

?>