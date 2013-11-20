<?php
$sTemp = $LDPage;
if( $page <= 0 || $page > $pagination ) {
	$page = 1;
}
if($page == $pagination) $nextpage = 1;
else $nextpage = $page + 1;
if($page == 1) $prevpage = $pagination;
else $prevpage = $page - 1;

if ( $page > 1 ) {
	$prev = "[<a href='".$pagingurl."&page=$prevpage'>$LDPreviousPage</a>] ";
	$first = "[<a href='".$pagingurl."&page=1'>$LDFirstPage</a>] ";		
}
$sTemp .=  $first . $prev;
for($i = ($page-5); $i <= ($page+5); $i++ ) {
	if ($i < 1) continue;
	if ($i > $pagination ) break;
	if ($i != $page ) {
		$sTemp .= "[<a href='".$pagingurl."&page=$i'>$i</a>]";
	} else {
		$sTemp .= "<font color='red'>[$i]</font>";
	}
} 
if ($page < $pagination ) {
	$last = "[<a href='".$pagingurl."&page=$pagination'>$LDLastPage</a>] ";
	$next = "[<a href='".$pagingurl."&page=$nextpage'>$LDNextPage</a>] "; 
}                      
$sTemp .=  $next . $last;
?>