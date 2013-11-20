<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$search=$_GET["search"];

	$sql="SELECT *     
			FROM care_supplier   
			WHERE (supplier LIKE '".$search."%' or supplier LIKE '% ".$search."%') 
			ORDER BY supplier LIMIT 15 ";

	if($result = $db->Execute($sql)){
		$n=$result->RecordCount(); 
		if ($n){
			echo '<ul>';
			for ($i=0;$i<$n;$i++)
			{
				$medicine=$result->FetchRow();
				echo '<li id="'.$medicine["supplier"].'">';
				echo '<div><font color="#FF0000">'.$medicine["supplier"].'</font></div>';
				echo '<span>-- '.$medicine["supplier_name"].'<br>&nbsp;</span></li>';

			}
			echo '</ul>';
		}
	}


	
?>