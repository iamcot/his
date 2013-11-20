<?php
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	/* Load the ward object */
	require_once($root_path.'include/care_api_classes/class_ward.php');

	$dept_nr = $_POST['dept_nr']; 
	$ward_nr = $_POST['ward_nr'];
	$ward_obj=new Ward(NULL);
	$warditems = array('nr','ward_id','name');
	$wardlistdata = $ward_obj->getAllWardsItemsObjectofDept($warditems, $dept_nr);
	$sTemp = '<option value="-1"> </option>';
	if($wardlistdata != false){
		while($row=$wardlistdata->FetchRow()){
			$sTemp .= "<option value='".$row[nr]."' ".($row[nr]==$ward_nr?"selected":"")."> ".$row[ward_id]." - ".$row[name]." </option>";
		}
	} else echo "dberror";
	echo $sTemp;
?>