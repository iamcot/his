<?php
//$pres_show['']: thong tin chung toa thuoc 
//$result['']: thong tin cua benh nhan
//$medicine_in_pres: danh sach thuoc trong toa
//$medicine_count = $medicine_in_pres->RecordCount()

if ($issue_show['type']) {
    $typeissue = $LDSumIssue;
} else {
    $typeissue = $LDDepotIssue;
}
if ($issue_show['status_finish']) {
    $tempfinish = $LDFinish;
    $tempfinish1 = 'check-r.gif';
} else {
    $tempfinish = $LDNotYet;
    $tempfinish1 = 'warn.gif';
}

$date1 = formatDate2Local($issue_show['date_time_create'], 'dd/mm/yyyy');
$time1 = substr($issue_show['date_time_create'], -8);


//Get general info
require_once($root_path . 'include/care_api_classes/class_ward.php');
if (!isset($ward_obj)) $ward_obj = new Ward;
include_once($root_path.'include/care_api_classes/class_product.php');
if(!isset($Product)) $Product=new Product;
$wardid = $issue_show['ward_nr'];
if ($wardid)
    $wardname = $ward_obj->WardName($wardid); //Khu phong

//Su nghiep, BHYT, CBTC
switch ($issue_show['typeput']) {
    case 0:
        $usefor = $LDBHYT;
        break;
    case 1:
        $usefor = $LDSuNghiep;
        break;
    case 2:
        $usefor = $LDCBTC;
        break;
    default:
        $usefor = $LDSuNghiep;
        break;
}

?>&nbsp;<br>
<table border=0 cellpadding=3 width="98%">
    <tr bgcolor="#f6f6f6">
        <td width="20%"><FONT SIZE=-1 color="#000066"><?php echo $LDIssueId; ?></td>
        <td width="35%"><FONT SIZE=-1><?php echo $issue_id; ?></td>
        <td width="15%"><FONT SIZE=-1 color="#000066"><?php echo $LDGotDrug; ?></td>
        <td width="30%"><FONT SIZE=-1><?php echo $tempfinish . ' '; ?>
                <img <?php echo createComIcon($root_path, $tempfinish1, '0', '', TRUE); ?>></td>
    </tr>
    <tr bgcolor="#f6f6f6">
        <td width="20%"><FONT SIZE=-1 color="#000066"><?php echo $LDWard; ?></td>
        <td width="35%"><FONT SIZE=-1><?php echo '<b>' . $issue_show['name_formal'] . '</b>' . (($wardname!='')?' - ':'').$wardname; ?></td>
        <td width="15%"><FONT SIZE=-1 color="#000066"><?php echo $LDUseFor; ?></td>
        <td width="30%"><FONT SIZE=-1><b><?php echo $usefor; ?></b></td>
    </tr>
    <tr bgcolor="#f6f6f6">
        <td><FONT SIZE=-1 color="#000066"><?php echo $LDIssueBy; ?></td>
        <td><FONT SIZE=-1><?php echo $issue_show['nurse']; ?></td>
        <td><FONT SIZE=-1 color="#000066"><?php echo $LDDate; ?></td>
        <td><FONT SIZE=-1><?php echo $date1 . ' ' . $time1; ?></td>
    </tr>
    <tr bgcolor="#f6f6f6">
        <td><FONT SIZE=-1 color="#000066"><?php echo $LDNoteCreator; ?></td>
        <td><FONT SIZE=-1><?php echo $issue_show['note']; ?></td>
        <td><FONT SIZE=-1 color="#000066"><?php echo $LDTypeIssue; ?></td>
        <td><FONT SIZE=-1><?php echo $typeissue; ?></td>
    </tr>

    <!-- Them thuoc vao toa thuoc -->
    <tr>
        <td colspan="4" align="center"><br>
            <table bgcolor="#EEEEEE" width="100%" cellpadding="2">
                <tr bgColor="#E1E1E1">
                    <td width="4%" align="center" rowspan="2"><u><?php echo $LDSTT ?></u></td>
                    <td width="20%" align="center" rowspan="2"><u><?php echo $LDMedicineName ?></u></td>
                    <td width="10%" align="center" rowspan="2"><u><?php echo $LDUnit ?></u></td>
                    <td width="9%" align="center" rowspan="2"><u><?php echo $LDRequest ?></u></td>
                    <td width="8%" align="center" rowspan="2"><u><?php echo $LDPlus ?></u></td>
                    <td width="10%" align="center" rowspan="2"><font
                            color="#E41B17"><u><?php echo $LDTonKhoLe ?></u></font></td>
                    <td width="10%" align="center" rowspan="2"><font
                            color="#E41B17"><u><?php echo 'Giá' ?></u></font></td>
                    <td width="20%" align="center" colspan="2"><u><?php echo $LDNumberOf ?></u></td>
                    <td width="10%" align="center" rowspan="2"><u><?php echo $LDNote ?></u></td>
                </tr>
                <tr bgColor="#E1E1E1">
                    <td align="center"><u><?php echo $LDTotal ?></u></td>
                    <td align="center"><u><?php echo $LDIssue ?></u></td>
                </tr>

                <?php for ($i = 1; $i <= $medicine_count; $i++) {
                    $rowIssue = $medicine_in_pres->FetchRow();
//                    $tonkhole = $IssuePaper->searchMedicineInAvaiProduct($rowIssue['product_encoder'], $issue_show['typeput']);
                  //  $tonkhole = 0;
                   // $giatien = 0;
                  //  $lotid = '';
                    $cbx_price='';
                    $lastlot = $Product->getLastLotID($rowIssue['product_encoder'],$issue_show['typeput']);
                   /*
                    if($lastlot != null){
                        $tonkhole = $lastlot['available_number'];
                        $giatien = $lastlot['price'];
                        $available_product_id = $lastlot['available_product_id'];
                    }   */
                    $cbx_price = '<select name="cost'.$i.'" id="cost'.$i.'" onFocus="startCalc('.$i.');" onBlur="stopCalc();">';
                    if (is_object($lastlot)){
                        $giamacdinh =0; $text_tonkhole_hidden='';
                        for ($i1=1;$i1<=$lastlot->RecordCount();$i1++){
                            $temp_price = $lastlot->FetchRow();
                            if (abs($temp_price['price']- $rowIssue['c'])<=0.1){
                                $cbx_price .='<option value="'.$temp_price['price'].'" selected>'.$temp_price['price'].'</option>';
                                $giamacdinh = $temp_price['price'];
                                $tonkhole = $temp_price['available_number'];
                                $available_product_id = $temp_price['available_product_id'];
                            }
                            else {
                                $cbx_price .='<option value="'.$temp_price['price'].'">'.$temp_price['price'].'</option>';
                                if($giamacdinh==0){
                                    $giamacdinh = $temp_price['price'];
                                    $tonkhole = $temp_price['available_number'];
                                    $available_product_id = $temp_price['available_product_id'];
                                }
                            }
                            $text_tonkhole_hidden .= '@'.$temp_price['available_number'];
                        }
                    }else {
                        $cbx_price .='<option value="0">0</option>';
                        $giamacdinh =0; $tonkhole =0;
                        $text_tonkhole_hidden='';
                    }

                    $cbx_price .= '</select>';
                    echo '<tr bgColor="#ffffff" >
					<td align="center" bgColor="#ffffff">' . $i . '.<input type="hidden" name="medicine_nr[' . $i . ']" value="' . $rowIssue['nr'] . '"></td>
					<td bgColor="#ffffff"><b>' . $rowIssue['product_name'] . '</b></td>
					<td align="center" bgColor="#ffffff"><b>' . $rowIssue['units'] . '</b></td>
					<td align="right" bgColor="#ffffff">' . number_format($rowIssue['sumpres']) . '</td>
					<td align="right" bgColor="#ffffff">' . number_format($rowIssue['plus']) . '</td>
					<td bgColor="#ffffff"><input type="text" id="tonkho' . $i . '" value="' . intval($tonkhole) . '" size="8"  style="text-align:right;border-color:white;border-style:solid;color:red;" readonly></td>
					<td align="right" width="12%">
						'.$cbx_price.' <input type="hidden" id="hidden_tonkho'.$i.'" value="'.$text_tonkhole_hidden.'">
                          <input type="hidden" name="available_product_id' . $i . '" value="' . $available_product_id . '">

					</td>

					<td align="right" bgColor="#ffffff"><b>' . number_format($rowIssue['number_request']) . '</b></td>
					<td align="right" bgColor="#ffffff">
					<input id="receive[' . $i . ']" name="receive[' . $i . ']" type="text" size=3 value="' . $rowIssue['number_request'] . '">
					</td>
					<td align="center" bgColor="#ffffff">' . $rowIssue['note'] . '</td>
				</tr>';
                } ?>
            </table>
            &nbsp;<br>
        </td>
    </tr>

    <!-- Loi dan bac si & button -->
    <tr bgcolor="#f6f6f6">
        <td><FONT SIZE=-1 color="#000066"><?php echo $LDIssueUser; ?></td>
        <td><FONT SIZE=-1><input name="issue_user" type="text" size="24"
                                 value="<?php echo $_SESSION['sess_user_name']; ?>"
                                 style="border-color:white;border-style:solid;" readonly></td>
        <td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDReceiveUser; ?></td>
        <td><FONT SIZE=-1><input name="receive_user" type="text" size="24" value="<?php echo $issue_show['nurse']; ?>">
        </td>
    </tr>
    <tr bgcolor="#f6f6f6">
        <td valign="top"><FONT SIZE=-1 color="#000066"><?php echo $LDNoteIssue; ?></td>
        <td colspan="3"><FONT SIZE=-1><textarea name="noteissue" cols="27" rows="3" wrap="physical"></textarea></td>
    </tr>
    <tr bgcolor="#f6f6f6">
        <td colspan="4">&nbsp;<p>

        </td>
    </tr>

</table>