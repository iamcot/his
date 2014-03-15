<?php	
//current_page, location=1,2,3 , (number_items_per_page, total_items, total_pages)

if ($current_page=='' || !isset($current_page) || $current_page==0) {
	$current_page=1; $location=1;
}

if ($total_pages==1){
	//hide all: first, pre, next, last, [1],[2],[3]
}
else {	
	if ($total_pages<=3){
		//hide: first, pre, next, last
		for($i=1; $i<=$total_pages; $i++){
			if ($current_page==$i)
				$sTempPage.=" <a href='".$thisfile.URL_APPEND."&current_page=".$i."&location=".$i."'><font color='#800000'>[".$i."]</font></a> ";
			else
				$sTempPage.=" <a href='".$thisfile.URL_APPEND."&current_page=".$i."&location=".$i."'>[".$i."]</a> ";
		}
	}
	else {
		//show all
		for($i=$current_page-$location+1; $i<=$current_page-$location+3; $i++){
			if ($current_page==$i)
				$sTempPage.=" <a href='".$thisfile.URL_APPEND."&current_page=".$i."&location=".$i."'><font color='#800000'>[".$i."]</font></a> ";
			else
				$sTempPage.=" <a href='".$thisfile.URL_APPEND."&current_page=".$i."&location=".$i."'>[".$i."]</a> ";
		}

		// show: first, pre, next, last
		$first_pg = " <a href='".$thisfile.URL_APPEND."&current_page=1&location=1'><<</a> ";
		$last_pg = " <a href='".$thisfile.URL_APPEND."&current_page=".$total_pages."&location=3'>>></a> ";
		
		if($current_page==1) $temp_crr=1;
			else $temp_crr=$current_page-1;
		if($location<=1) $temp_lo=1;
			else $temp_lo=$location-1;
		$pre_pg = " <a href='".$thisfile.URL_APPEND.$dept_nr."&current_page=".$temp_crr."&location=".$temp_lo."'><</a> ";
		
		if($current_page==$total_pages) $temp_crr=$total_pages;
			else $temp_crr=$current_page+1;
		if($location>=3) $temp_lo=3;
			else $temp_lo=$location+1;
		$next_pg = " <a href='".$thisfile.URL_APPEND."&current_page=".$temp_crr."&location=".$temp_lo."'>></a> ";
	}
	
	$sTempPage = $first_pg.' '.$pre_pg.'-'.$sTempPage.'-'.$next_pg.' '.$last_pg;
}



?>
