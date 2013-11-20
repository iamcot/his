<?php
error_reporting ( E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR );
require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
/**
 *
 * Jean-Philippe LIOT  <flip-zali@tiscali.fr>
 * added the ff: functionalities
 * search for Ethnic origin types  2004-05-11
 * search for Immunization types 2004-04-12
 * zip_code addition to city town address 2004-05-13
 * revised by Elpidio for cross database compatibility 2004-05-13
 *
 * CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * elpidio@care2x.org, 
 *
 * See the file "copy_notice.txt" for the licence notice
 *
 */
define ( 'PNID', 'referrer_dr' );
define ( 'PNAME', 'referrer_name' );
define ( 'LANG_FILE', 'aufnahme.php' );
$local_user = 'aufnahme_user';
define('NO_2LEVEL_CHK',1);
require_once ($root_path . 'include/core/inc_front_chain_lang.php');
require_once ($root_path . 'include/core/inc_date_format_functions.php');
require_once ($root_path . 'classes/datetimemanager/class.dateTimeManager.php');

# Create time manager object
$datetime_obj = & new dateTimeManager ( );

$thisfile = basename ( __FILE__ );
$searchmask_bgcolor = "#f3f3f3";
$searchprompt = $LDEnterSearchKeyword;

$quicklistmaxnr = 10; // The maximum number of quicklist popular items


# Uncomment the following to debug the sql queries
///$db->debug=1;


$sql = '';

if (! isset ( $mode ))
	$mode = '';
	
# Set limited query result to false as default
$limitselect = FALSE;

# Set the default name of of the form
# Note to Jean-Philippe LIOT
# the form name was moved outside of the sql query because otherwise the element names become too long and get pruned by PostgreSQL

$formname = 'appt_form';

if (isset ( $target )) {
	switch ($target) {
		
		//add 16: y ta, 17: bs
		case 'referrer_dr' :
			$searchkey = str_replace(" ","%", $searchkey);
			$sql = "SELECT pn.nr AS '".PNID.".value', CONCAT(p.name_last, ' ',p.name_first) AS '".PNAME.".value', 0 AS use_frequency FROM care_personell AS pn, care_person AS p WHERE (pn.pid = p.pid) ";
			if ($mode == 'search') {
				$sql .= " AND ((nr $sql_LIKE '%$searchkey%') OR (CONCAT(p.name_last, ' ',p.name_first) $sql_LIKE '$searchkey%') OR (CONCAT(p.name_last, ' ', p.name_first) $sql_LIKE '%$searchkey%'))";
				$sql .= " ORDER BY p.name_first";
			} else {
				$sql .= " ORDER BY p.name_first";
				
			}
			$limitselect = FALSE;
			$title = $LDSearch . ' :: ' . $LDDoctor . ' (' . $LDDoctor . ')';
			$itemname = $LDDoctor;
			break;
		case 'doctor_nr':
			$searchkey = str_replace(" ","%", $searchkey);
			$sql = "SELECT pn.nr AS 'to_personell_nr.value', CONCAT(p.name_last, ' ',p.name_first) AS 'to_personell_name.value', 0 AS use_frequency FROM care_personell AS pn, care_person AS p, care_personell_assignment as pa WHERE (pn.pid = p.pid) and pa.personell_nr = pn.nr ";
			if ($mode == 'search') {
				$sql .= " AND ((nr $sql_LIKE '%$searchkey%') OR (CONCAT(p.name_last, ' ',p.name_first) $sql_LIKE '$searchkey%') OR (CONCAT(p.name_last, ' ', p.name_first) $sql_LIKE '%$searchkey%'))";
				$sql .= " ORDER BY p.name_first";
			} else {
				$sql .= " and pa.location_nr = ".$dept_nr." and pa.role_nr = 17 ORDER BY p.name_first"; //edit 03102012 - cot
				
			}
			$limitselect = FALSE;
			$title = $LDSearch . ' :: ' . $LDDoctor . ' (' . $LDDoctor . ')';
			$itemname = $LDDoctor;
			break;
	}
	
	# The adodb function of limited nr of return query is now used here
	

	if ($limitselect) {
		if ($result = $db->SelectLimit ( $sql, $quicklistmaxnr )) {
			$linecount = $result->RecordCount ();
		}
	} else {
		if ($result = $db->Execute ( $sql )) {
			$linecount = $result->RecordCount ();
		}
	}
	if ($linecount)
		$sql_value = $result->GetArray ();

}

/* Set color values for the search mask */
$entry_block_bgcolor = '#fff3f3';
$entry_border_bgcolor = '#66ee66';
$entry_body_bgcolor = '#ffffff';
?>
<?php

html_rtl ( $lang );
?>
<head>
<?php
echo setCharSet ();
?>
<title><?php
echo $title?></title>


<script language="javascript">
<!-- Script Begin
function setValue(val) {
	mywin=parent.window.opener;
	var array_val= val.split("|");
	<?php
	$var_parent_document = array_keys ( $sql_value [0] );
	$indice = 0;
	for($i3 = 1; $i3 < sizeof ( $var_parent_document ) - 1; $i3 = $i3 + 2) {
		echo 'mywin.document.' . $formname . '.' . $var_parent_document [$i3] . '=array_val[' . $indice . ']; ';
		
		$indice ++;
	}
	;
	?>
	this.window.close();
	mywin.focus();
}


//  Script End -->
</script>
</head>
<body>
<font face=arial> <font size=3><b><?php
echo $title?></b></font>

<table border=0 cellpadding=10
	bgcolor="<?php
	echo $entry_border_bgcolor?>">
	<tr>
		<td>
<?php
include ($root_path . 'include/core/inc_patient_searchmask.php');

?>
</td>
	</tr>
</table>
   
<?php
if ($mode == 'search') {
	if (! $linecount)
		$linecount = 0;
	echo '<hr width=80% align=left>' . str_replace ( "~nr~", $linecount, $LDSearchFoundData ) . '<p>';
} else {
	echo '<hr width=80% align=left><font size=4 color="#990000">' . $LDTop . ' ' . $quicklistmaxnr . ' ' . $LDQuickList . '</font>';
}

//echo $mode;
if ($linecount) {
	$count = 0;
	echo '
						<table border=0 cellpadding=2 cellspacing=1> 
						<tr bgcolor="#66ee66" background="' . $root_path . 'gui/img/common/default/tableHeaderbg.gif">';
	
	echo '
						<td><font face=arial size=2 color="#336633"><b>' . $LDID . '</b></td>';
	echo '
						<td><font face=arial size=2 color="#336633"><b>' . $itemname . '</b></td>';
	
	echo '
						<td><font face=arial size=2 color="#336633">&nbsp;</td>';
	
	echo "</tr>";
	$sql_value_without_key = array_values ( $sql_value );
	for($i = 0; $i < sizeof ( $sql_value ); $i ++) {
		if (($mode != 'search') && ($count == $quicklistmaxnr && $limitselect))
			break;
		else
			$count ++;
		
		echo "
							<tr bgcolor=";
		if ($toggle) {
			echo "#efefef>";
			$toggle = 0;
		} else {
			echo "#ffffff>";
			$toggle = 1;
		}
		;
		echo "<td>&nbsp;" . ucfirst ( $sql_value [$i] [0] )."</td>";
		echo "<td><font face=arial size=2>";
		echo "&nbsp;" . ucfirst ( $sql_value [$i] [1] );
		
		# This formats the refresh date to local data if available
		# Uses the dateTimeManager object
		# Note: the second parameter of the method ::shift_dates is negative to shift the date to the future
		

		if (isset ( $sql_value_without_key [$i] ['refresh_date.value'] ) && ! empty ( $sql_value_without_key [$i] ['refresh_date.value'] )) {
			
			$shifted_date = $datetime_obj->shift_dates ( date ( 'Y-m-d' ), - ($sql_value_without_key [$i] ['refresh_date.value']), 'd' );
			
			$sql_value_without_key [$i] ['refresh_date.value'] = formatDate2Local ( $shifted_date, $date_format );
		}
		// Creation du tableau export vers java
		

		// Elimination des doublons
		// The original code of Jean-Philippe was modified to patch the bug of inaccurate result of "sizeof" command
		

		$sql_java_string = '';
		while ( list ( $x, $v ) = each ( $sql_value_without_key [$i] ) ) {
			if (is_integer ( $x ))
				continue;
			else
				$sql_java_string .= "$v|";
		}
		
		echo "</td>";
		echo "<td><font face=arial size=2>";
		echo '<a href="javascript:setValue(\'' . $sql_java_string . '\')">';
		echo '<img ' . createLDImgSrc ( $root_path, 'ok_small.gif', '0' ) . ' alt="' . $LDTestThisPatient . '"></a>&nbsp;';
		echo '</td></tr>';
	}
	echo "
						</table>";
	if ($mode == 'search' && $linecount > 15) {
		?>
         <p>


<table border=0 cellpadding=10
	bgcolor="<?php
		echo $entry_border_bgcolor?>">
	<tr>
		<td>
	   <?php
		
		$searchform_count = 2;
		include ($root_path . 'include/core/inc_patient_searchmask.php');
		
		?>
</td>
	</tr>
</table>
<?php
	}
}

?>

</font>
</body>
</html>
