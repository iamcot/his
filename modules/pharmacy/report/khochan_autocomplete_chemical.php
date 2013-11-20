<?php
$root_path = '../../../';
require($root_path.'/include/core/inc_environment_global.php');
global $db;
	
extract($_POST);

 
# clean input data
$keyword=$_POST['search'];
///$db->debug=true;

$sql="SELECT khochan.product_encoder, khochan.product_name, khochan.available_number, khochan.unit_of_chemical, khochan.price, khochan.caution, donvi.unit_name_of_chemical 
			FROM care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi 
			WHERE (khochan.product_name LIKE '$keyword%' OR khochan.product_name LIKE '%$keyword%') 
			AND donvi.unit_of_chemical=khochan.unit_of_chemical  
			ORDER BY khochan.product_name LIMIT 15";
$ergebnis=$db->Execute($sql);
	
if (!$ergebnis) {
	echo "<li>Could not successfully run query ($sql) from DB: " . mysql_error(). "</li>";
	exit;
}	

?>

<ul>
<?php while($zeile=$ergebnis->FetchRow()) { ?>
	<li id="<?php echo $zeile["product_encoder"]; ?>">
<div><font color="#FF0000"><?php echo $zeile["product_name"]; ?></font></div>
<span><?php echo '-- '.$zeile["product_encoder"].'<br>-- '.$zeile["content"].' -- '.$zeile["price"]." vnd/ ".$zeile["unit_name_of_chemical"]; ?><br>&nbsp;</span>
	</li>
<?php } ?>
</ul>