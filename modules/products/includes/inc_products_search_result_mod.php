<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr ( "inc_products_search_result_mod.php", $_SERVER['SCRIPT_NAME'] ))
	die ( '<meta http-equiv="refresh" content="0; url=../">' );
	/*------end------*/
///$db->debug =  true;
# If smarty object is not available create one
if (! isset ( $smarty )) {
	/**
	 * LOAD Smarty
	 * param 2 = FALSE = dont initialize
	 * param 3 = FALSE = show no copyright
	 * param 4 = FALSE = load no javascript code
	 */
	include_once ($root_path . 'gui/smarty_template/smarty_care.class.php');
	$smarty = new smarty_care ( 'common', FALSE, FALSE, FALSE );
	
	# Set a flag to display this page as standalone
	$bShowThisForm = TRUE;
}

if ($bcat)
	$LDMSRCindex [''] = ""; // if parent is order catalog add one empty column at the end ?!?
if ($update || ($mode == "search")) {
	
	switch ( $cat) {
		case "pharma" :
			$imgpath = $root_path . "uploads/pharma/img/";
		break;
		case "medipot" :
			$imgpath = $root_path . "uploads/med_depot/img/";
		break;
	}
	
	if ($saveok || (! $update))
		$statik = true;
	
	if ($linecount) {
		# Assign form elements
		$smarty->assign ( 'LDOrderNr', $LDGeneric );			//Tên thuoc goc
		$smarty->assign ( 'LDArticleName', $LDArticleName );	//Ten biet duoc
		$smarty->assign ( 'LDGeneric', $LDPharmaGroup );		//Nhom thuoc
		$smarty->assign ( 'LDDescription', $LDDangThuoc );	//Dang thuoc
		$smarty->assign ( 'LDPacking', $LDComponent );			//Thanh phan
		$smarty->assign ( 'LDDose', $LDDose );					//Ham luong
		$smarty->assign ( 'LDCAVE', $LDDuongDung );				//Duong dung
		$smarty->assign ( 'LDCategory', $LDTacDung );			//Tac dung
		$smarty->assign ( 'LDMinOrder', $LDCachDung );						//Cach dung (decription)
		$smarty->assign ( 'LDMaxOrder', $LDThanTrong );						//Than trong
		$smarty->assign ( 'LDPcsProOrder', $LDNote );					//Ghi chu
		$smarty->assign ( 'LDIndustrialNr', $LDSupplier );					//Nha cung cap
		$smarty->assign ( 'LDLicenseNr', $LDLicenseNr );					//So dang ky
		$smarty->assign ( 'LDMinPieces', $LDUnit );					//Don vi
		$smarty->assign ( 'LDPicFile', $LDPicFile );
		
		//echo $linecount;
		if ($linecount == 1) {
			$zeile = $ergebnis->FetchRow ();
			# Assign the preview picture
			
			if (($statik || $update) && ($zeile ['picfile'] != "")) {
				$smarty->assign ( 'LDPreview', $LDPreview );
				$sTemp = '<img src="' . $imgpath . $zeile ['picfile'] . '" border=0 name="prevpic" ';
				if (! $update || $statik) {
					if (file_exists ( $imgpath . $zeile ['picfile'] )) {
						$imgsize = GetImageSize ( $imgpath . $zeile ['picfile'] );
						$sTemp = $sTemp . $imgsize [3];
					}
				}
				$smarty->assign ( 'sProductImage', $sTemp . '>' );
			} else {
				$smarty->assign ( 'sProductImage', '<img src="../../gui/img/common/default/pixel.gif" border=0 name="prevpic">' );
			}
			
			# Assign form inputs (or values)
			

			if ($statik || $update)
				$smarty->assign ( 'sOrderNrInput', $zeile ['generic_drug'] . '</b><input type="hidden" name="generic_drug" value="' . $zeile ['generic_drug'] . '">' ); 
			else
				$smarty->assign ( 'sOrderNrInput', '<input type="text" name="generic_drug" value="' . $zeile ['generic_drug'] . '" size=20 maxlength=20>' );
				
			if ($statik) {
				$smarty->assign ( 'sArticleNameInput', $zeile ['product_name'] . '<input type="hidden" name="product_name" value="' . $zeile ['product_name'] . '">' );
				$smarty->assign ( 'sGenericInput', $zeile ['pharma_group_name'] . '<input type="hidden" name="pharma_group_name" value="' . $zeile ['pharma_group_name'] . '">' );
				$smarty->assign ( 'sDescriptionInput',  $zeile ['type_name_of_medicine'] . '<input type="hidden" name="type_of_medicine" value="' . $zeile ['type_of_medicine'] . '">' );
				$smarty->assign ( 'sPackingInput', $zeile['component'] . '<input type="hidden" name="component" value="' . $zeile ['component'] . '">' );
				$smarty->assign ( 'sDoseInput', $zeile ['content'] . '<input type="hidden" name="content" value="' . $zeile ['content'] . '">' );
				$smarty->assign ( 'sCAVEInput', $zeile ['using_type'] . '<input type="hidden" name="using_type" value="' . $zeile ['using_type'] . '">' );
				$smarty->assign ( 'sCategoryInput', nl2br($zeile['effects']) . '<input type="hidden" name="effects" value="' . $zeile ['effects'] . '">' );
				$smarty->assign ( 'sMinOrderInput', nl2br($zeile['description']) . '<input type="hidden" name="description" value="' . $zeile ['description'] . '">' );
				$smarty->assign ( 'sMaxOrderInput', nl2br($zeile ['caution']) . '<input type="hidden" name="caution" value="' . $zeile ['caution'] . '">' );
				$smarty->assign ( 'sPcsProOrderInput', nl2br($zeile ['note']) . '<input type="hidden" name="note" value="' . $zeile ['note'] . '">' );
				$smarty->assign ( 'sIndustrialNrInput', $zeile ['care_supplier'] . '<input type="hidden" name="care_supplier" value="' . $zeile ['care_supplier'] . '">' );
				$smarty->assign ( 'sLicenseNrInput', $zeile ['product_encoder'] . '<input type="hidden" name="product_encoder" value="' . $zeile ['product_encoder'] . '">' );
				$smarty->assign ( 'sMinPiecesInput', $zeile ['unit_name_of_medicine'] . '<input type="hidden" name="unit_of_medicine" value="' . $zeile ['unit_of_medicine'] . '">' );
				$smarty->assign ( 'sPicFileInput', $zeile ['picfile'] . '<input type="hidden" name="bild" value="' . $zeile ['picfile'] . '">' );
			} else {
				$smarty->assign ( 'sArticleNameInput', '<input type="text" name="product_name" value="' . $zeile ['product_name']. '" size=40 maxlength=40>' );
				$smarty->assign ( 'sGenericInput', '<input type="text" name="pharma_group_name" value="' .  $zeile ['pharma_group_name'] . '" size=40 maxlength=60>' );
				$smarty->assign ( 'sDescriptionInput', '<input type="text" name="type_of_medicine" value="' . $zeile ['type_name_of_medicine'] . '"  size=40 >' );
				$smarty->assign ( 'sPackingInput', '<input type="text" name="component" value="' . $zeile ['component'] . '"  size=40 maxlength=40>' );
				$smarty->assign ( 'sDoseInput', '<input type="text" name="content" value="' . $zeile ['content'] . '" size=40 maxlength=80>' );
				$smarty->assign ( 'sCAVEInput', '<input type="text" name="using_type" value="' . $zeile ['using_type'] . '" size=40 maxlength=80>' );
				$smarty->assign ( 'sCategoryInput', '<textarea name="effects" cols="30" rows="3">' . $zeile ['effects'] . ' </textarea>' );
				$smarty->assign ( 'sMinOrderInput', '<textarea name="description" cols="30" rows="3">' . $zeile ['description'] . ' </textarea>' );
				$smarty->assign ( 'sMaxOrderInput', '<textarea name="caution" cols="30" rows="3">' . $zeile ['caution'] . '</textarea>' );
				$smarty->assign ( 'sPcsProOrderInput', '<textarea name="note" cols="30" rows="3">' . $zeile ['note'] . '</textarea>' );
				$smarty->assign ( 'sIndustrialNrInput', '<input type="text" name="care_supplier" value="' . $zeile ['care_supplier'] . '" size=20 maxlength=20>' );
				$smarty->assign ( 'sLicenseNrInput', '<input type="text" name="product_encoder" value="' . $zeile ['product_encoder'] . '" size=20 maxlength=20>' );
				$smarty->assign ( 'sMinPiecesInput', '<input type="text" name="unit_of_medicine" value="' . $zeile ['unit_name_of_medicine'] . '" size=20 maxlength=20>' );
				$smarty->assign ( 'sPicFileInput', '<input type="file" name="bild" onChange="getfilepath(this)">' );
			}
			# If display is forced
			if ($bShowThisForm)
				$smarty->display ( 'products/form.tpl' );
		
		} else {
			echo "<p>" . str_replace ( "~nr~", $linecount, $LDFoundNrData ) . "<br>$LDClk2SeeInfo<p>";
			
			echo "<table border=0 cellpadding=3 cellspacing=1> ";
			
			echo '<tr class="wardlisttitlerow">';
			
			for($i = 0; $i < sizeof ( $LDMSRCindex ) - 1; $i ++) {
				echo '<td>' . $LDMSRCindex [$i] . '</td>';
			}
			echo "</tr>";
			
			/* Load common icons */
			$img_info = createComIcon ( $root_path, 'info3.gif', '0' );
			$img_arrow = createComIcon ( $root_path, 'dwnarrowgrnlrg.gif', '0' );
			while ( $row = $ergebnis->fetchRow ($result) ) {
				echo "<tr class=";
				if ($toggle) {
					echo "wardlistrow2>";
					$toggle = 0;
				} else {
					echo "wardlistrow1>";
					$toggle = 1;
				};
				echo '<td valign="top"><a href="' . $thisfile . URL_APPEND . '&dept_nr=' . $dept_nr . '&keyword=' . $row ['product_encoder'] . '&mode=search&from=multiple&cat=' . $cat . '&userck=' . $userck . '"><img ' . $img_info . ' alt="' . $LDOpenInfo . $row ['artikelname'] . '"></a></td>
						<td valign="top"><font size=1>' . $row ['product_encoder'] . '</td>
						<td valign="top"><font size=1>' . $row ['product_name'] . '</td>
						<td valign="top"><font size=1>' . $row ['number'] . '</td>
						<td valign="top"><a href="' . $thisfile . URL_APPEND . '&dept_nr=' . $dept_nr . '&keyword=' . $row ['product_encoder'] . '&mode=search&from=multiple&cat=' . $cat . '&userck=' . $userck . '"><font size=2 color="#800000"><b>' . $row ['product_name'] . '</b></font></a></td>
						<td valign="top"><font size=1>' . $row ['generic_drug'] . '</td>
						<td valign="top"><font size=1>' . $row ['description'] . '</td>
						';
				// if parent is order catalog add this option column at the end
				if ($bcat)
					echo '<td valign="top"><a href="' . $thisfile . URL_APPEND . '&dept_nr=' . $dept_nr . '&mode=save&artikelname=' . str_replace ( "&", "%26", strtr ( $row ['product_name'], " ", "+" ) ) . '&bestellnum=' . $row ['product_encoder'] . '&proorder=' . str_replace ( " ", "+", $row ['proorder'] ) . '&hit=0&cat=' . $cat . '&userck=' . $userck . '"><img ' . $img_arrow . ' alt="' . $LDPut2Catalog . '"></a></td>';
				echo '</tr>';
			}
			echo "</table>";
			if ($linecount > 15) {
				echo '<a href="#pagetop">' . $LDPageTop . '</a>';
			} //end of if $linecount>15
		

		} //end of else
	} else {
		echo '
			<p><img ' . createMascot ( $root_path, 'mascot1_r.gif', '0', 'middle' ) . '>
			' . $LDNoDataFound;
	}
}
?>
