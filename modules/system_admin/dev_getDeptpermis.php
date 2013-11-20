<?php
error_reporting ( E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR ) ;
    require ('./roots.php') ;
    require ($root_path . 'include/core/inc_environment_global.php') ;

    $lang_tables [] = 'access.php' ;
    $lang_tables[]='departments.php';
    define ( 'LANG_FILE', 'edp.php' ) ;
    $local_user = 'ck_edv_user' ;
    require_once ($root_path . 'include/core/inc_front_chain_lang.php') ;

    //gjergji : load the access roles
    require_once($root_path.'include/care_api_classes/class_access.php');
    $role_obj = & new Access();
    require_once ($root_path . 'include/care_api_classes/class_department.php') ;
	$dept_obj = new Department ( ) ;
    require_once($root_path.'global_conf/areas_allow.php');

    if(isset($_POST['type']) && $_POST['type']=='getdept'){ // khi click vao ten khoa hien ra danh sach chuc danh va quyen
    	$userpermit ="<table style='width:100%'></tr></tr>";
    	
    	//require_once($root_path.'global_conf/areas_allow.php');
    	$deptLD = $_POST['ldvar'];
    	$deptnr = $_POST['deptnr'];
    	$selectDept = $indept_allow[$deptLD];
    	//print_r($selectDept);
    	//echo $deptLD.'='.$selectDept[0];
    	$deptrole = $role_obj->getDeptRole($_POST['usernr']);

    	$userpermit .="<tr><td style='width:30%;vertical-align:top;border:1px solid #aaa;'><i>Chức vụ trong ".$$deptLD."</i><ul>";
   
    	foreach ($selectDept as $role) {
    		//echo $deptrole.'@@'.$deptLD.'='.$role[0];
    		$userpermit .= '<li><input type="radio" name="chucdanh" value="'.$role[0].'" '.((strpos($deptrole,$deptLD.'='.$role[0])>-1)?'checked="checked"':'').' onclick="getRole(\''.$role[0].'\',\''.$deptLD.'\',\''.$_POST['usernr'].'\')"> '.$$role[1].'</li>';
    	}

    	$deptpermission = $role_obj->getuserDeptPermission($_POST['usernr']);
    	//echo $deptpermission;
    	$userpermit .= '</ul></td>';
    	$userpermit .= '<td style="width:65%;vertical-align:top;"><i>Chi tiết quyền trong '.$$deptLD.'</i><ul id="permisLists">';
		     $userpermit .= '<li><input type="checkbox" name="_a_0_all"  '.((strpos($deptpermission,'_a_0_all')> -1)?' checked="checked" ':"").' >All</li>';#$all='_a_0_all';$sysadmin='System_Admin';
		    $userpermit .= '<li><input type="checkbox" name="System_Admin"  '.((strpos($deptpermission,'System_Admin') > -1)?' checked="checked" ':"").'>System</li>';
		   // echo $deptpermission;
		    foreach ($allow_area as $module) {
		        $userpermit .= '<li><input type="checkbox">'.(($$module[1])?$$module[1]:$module[0]).'</li>';
		        $userpermit .='<ul >';
		        foreach($module[2] as $permit){
		        	$userpermit .= '<li>'.(($$permit[1])?$$permit[1]:$permit[0]).'</li><ul>';
		        	$userpermit .= '<li><input type="radio" name="'.$permit[0].'" value="'.$permit[0].'_f"  '.((strpos($deptpermission,'_'.$deptnr.'_'.$permit[0].'_f')> -1)?' checked="checked" ':"").' >'.$LDpermitf.'
					<input type="radio" name="'.$permit[0].'" value="'.$permit[0].'_e"'.((strpos($deptpermission,'_'.$deptnr.'_'.$permit[0].'_e' )> -1)?' checked="checked" ':"").' >'.$LDpermite.'
					<input type="radio" name="'.$permit[0].'" value="'.$permit[0].'_v"'.((strpos($deptpermission,'_'.$deptnr.'_'.$permit[0].'_v')> -1)?' checked="checked" ':"").' >'.$LDpermitv.'
					<input type="radio" name="'.$permit[0].'" value="0">'.$LDpermit0.'
		        	</li>';
		            
		            $userpermit .= '</ul>';
		        }
		        $userpermit .='</ul>';
		    }
		$userpermit .= '</ul></td></tr></table>';
		echo $userpermit;
    }
    else if(isset($_POST['type']) && $_POST['type']=='getPersonellUpdate'){
        $selectpersonell = '<select name="personell" style="width:90%;display:block;padding:5px;margin-bottom:10px;clear:both;" ><option value="0">Chọn nhân viên</option>';
        $arrpersonell = $role_obj->getAllpersonell();
        while($row = $arrpersonell->Fetchrow()){
        $selectpersonell .= "<option value='".$row['pnid']."' ".(($row['pnid']==$_POST['personell_nr'])?'selected="true"':'')." >".$row['pname']."</option>";
        
        }
        $selectpersonell .= '</select>';
        echo $selectpersonell;
    }
    else if(isset($_POST['type']) && $_POST['type']=='getuser'){ //sau khi chon ten user hien ra danh sach khoa
    	  #Form chinh
    	   
    		$sTemp = "";
		    $listdept = $dept_obj->cot_getAllDept();
		     $sTemp .= '<i>Click tên khoa để xem các chức danh trong khoa, chọn checkbox để gán quyền user vào khoa</i>';
		    $sTemp .='<ul>';
		    $crrdeptnr = $role_obj->getDeptSelect($_POST['usernr']);
		   // echo $crrdeptnr;
		    foreach ($listdept as $depti) {
		        # code...
		        $sTemp .= '<li><input type="checkbox" name="'.$depti['LD_var'].'" onmousedown="checkDept(this,\''.$_POST['usernr'].'\','.$depti['nr'].',\''.$depti['LD_var'].'\')" '.((strpos($crrdeptnr,'['.$depti['nr'].']')>-1)?' checked="checked" ':'').'><span onclick="loadDeptPermission(\''.$_POST['usernr'].'\','.$depti['nr'].',\''.$depti['LD_var'].'\')" style="cursor:pointer">'.$$depti['LD_var'].'</span> </li>';
		    }
		    $sTemp.='</ul>';
		    echo $sTemp;
    }
    else if(isset($_POST['type']) && $_POST['type']=='getroleofdept'){ // khi click vao chuc danh trong khoa

    	$LDvar = $_POST['ldvar'];    	
    	$name  = $_POST['name'];
    	$user = $_POST['user'];
    	//$strSearch = '$'.$LDvar.'='.$name.'$';
    	$role_obj->saveDeptTitle($name,$user,$LDvar);
    	$dept = $indept_allow[$LDvar];
    	$chucvu = $dept[$name];
    	$arrquyen = $chucvu[2];
    	 $userpermit = '<li><input type="checkbox" name="_a_0_all" '.((in_array('_a_0_all' ,$arrquyen))?' checked="checked" ':"").'>All</li>';#$all='_a_0_all';$sysadmin='System_Admin';
		    $userpermit .= '<li><input type="checkbox" name="System_Admin" '.((in_array('System_Admin' ,$arrquyen))?' checked="checked" ':"").'>System</li>';
		    
		    foreach ($allow_area as $module) {
		        $userpermit .= '<li><input type="checkbox">'.(($$module[1])?$$module[1]:$module[0]).'</li>';
		        $userpermit .='<ul >';
		        foreach($module[2] as $permit){
		        	$userpermit .= '<li>'.(($$permit[1])?$$permit[1]:$permit[0]).'</li><ul>';
		        	//var_dump(in_array($permit[0].'_f' ,$arrquyen));
		        	$userpermit .= '<li><input type="radio" name="'.$permit[0].'" value="'.$permit[0].'_f" '.((in_array($permit[0].'_f' ,$arrquyen))?' checked="checked" ':"").'>'.$LDpermitf.'
					<input type="radio" name="'.$permit[0].'" value="'.$permit[0].'_e"  '.((in_array($permit[0].'_e' ,$arrquyen))?' checked="checked" ':"").'>'.$LDpermite.'
					<input type="radio" name="'.$permit[0].'" value="'.$permit[0].'_v"  '.((in_array($permit[0].'_v' ,$arrquyen))?' checked="checked" ':"").'>'.$LDpermitv.'
					<input type="radio" name="'.$permit[0].'" value="0">'.$LDpermit0.'
		        	</li>';
		            
		            $userpermit .= '</ul>';
		        }
		        $userpermit .='</ul>';
		    }
		    $strrole = "";
		    $deptnr = $role_obj->getDeptNRfromLD($LDvar);
		    foreach($arrquyen as $quyen){
		    	$strrole.= '_'.$deptnr.'_'.$quyen.'|';
		    }
		    //echo $strrole;
		    if($role_obj->updateRoleOfDeptFromTitle($strrole,$LDvar,$user))
			    echo $userpermit;
			else echo 'Lỗi khi cập nhật quyền...';
    }
    else if(isset($_POST['type']) && $_POST['type']=='savedeptselect'){ // khi stick vao checkbox khoa
    	$deptnr = $_POST['deptnr'];
    	$user = $_POST['userlogin'];
    	$role_obj->saveDeptSelect($deptnr,$user);
    }
    else if(isset($_POST['type']) && $_POST['type']=='removedept'){
    	$ldvar = $_POST['ldvar'];
    	$usernr = $_POST['usernr'];
    	$deptnr = $_POST['deptnr'];
    	echo $role_obj->removedept($deptnr,$ldvar,$usernr);
    }
    else echo 'Dữ liệu không hợp lệ';
#end of file
   ?>
