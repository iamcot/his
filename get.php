<?php
include_once('classes/adodb/adodb.inc.php');

$db =& ADONewConnection('access');
	$dsn = "Driver={Microsoft Access Driver (*.mdb)};Dbq=//OK-PC/testdata/Database1.mdb;Uid=;Pwd=;";
	$db->Connect($dsn);
	$rs = $db->Execute("select * from Table1");
	$arr = $rs->GetArray();
	print_r($arr);
$cfg_dsn = "DRIVER=Microsoft Access Driver (*.mdb);
DBQ=////OK-PC//testdata//Database1.mdb;
UserCommitSync=Yes;
Threads=3;
SafeTransactions=0;
PageTimeout=5;
MaxScanRows=8;
MaxBufferSize=2048;
DriverId=281;
DefaultDir=////OK-PC//testdata";

$cfg_dsn_login = "";
$cfg_dsn_mdp = "";

$conn=@odbc_connect($cfg_dsn,$cfg_dsn_login,$cfg_dsn_mdp)or die ("Unable to connect to server");
  $qry = "SELECT * FROM Table1";
echo'<tr>
<td>ID</td>
<td>Name</td>
</tr>';
  // Get Result
  $result = odbc_exec($conn,$qry);
  while (odbc_fetch_array($result)){
  $id= odbc_result($result,"Id");
   $value = odbc_result($result,"Name");
   echo '<tr border="1"><td>'.$id.'</td>';
   echo '<td>'.$value.'</td></tr>';
   }
?>