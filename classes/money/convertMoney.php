<?php
function convertMoney($number){
	
	$donvi=" đồng ";
	$tiente=array("nganty" => " nghìn tỷ ","ty" => " tỷ ","trieu" => " triệu ","ngan" =>" nghìn ","tram" => " trăm ");
	$num_f=$nombre_format_francais = number_format($number, 2, ',', ' ');
	$vitri=strpos($num_f,',');
	$num_cut=substr($num_f,0,$vitri);
	$mang=explode(" ",$num_cut);
	$sophantu=count($mang);
	switch($sophantu)
	{
		case '5':
				$nganty=doc3so($mang[0]);
				$text=$nganty;
				$ty=doc3so($mang[1]);
				$trieu=doc3so($mang[2]);
				$ngan=doc3so($mang[3]);
				$tram=doc3so($mang[4]);
				if((int)$mang[1]!=0)
				{
					$text.=$tiente['ngan'];
					$text.=$ty.$tiente['ty'];
				}
				else
				{
					$text.=$tiente['nganty'];
				}
				if((int)$mang[2]!=0)
					$text.=$trieu.$tiente['trieu'];
				if((int)$mang[3]!=0)
					$text.=$ngan.$tiente['ngan'];
				if((int)$mang[4]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '4':
				$ty=doc3so($mang[0]);
				$text=$ty.$tiente['ty'];
				$trieu=doc3so($mang[1]);
				$ngan=doc3so($mang[2]);
				$tram=doc3so($mang[3]);
				if((int)$mang[1]!=0)
					$text.=$trieu.$tiente['trieu'];
				if((int)$mang[2]!=0)
					$text.=$ngan.$tiente['ngan'];
				if((int)$mang[3]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '3':
				$trieu=doc3so($mang[0]);
				$text=$trieu.$tiente['trieu'];
				$ngan=doc3so($mang[1]);
				$tram=doc3so($mang[2]);
				if((int)$mang[1]!=0)
					$text.=$ngan.$tiente['ngan'];
				if((int)$mang[2]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '2':
				$ngan=doc3so($mang[0]);
				$text=$ngan.$tiente['ngan'];
				$tram=doc3so($mang[1]);
				if((int)$mang[1]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '1':
				$tram=doc3so($mang[0]);
				$text=$tram.$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		default:
			echo "Xin lỗi số quá lớn không thể đổi được";
		break;
	}
}	
	function doc3so($so)
	{
		$achu = array ( " không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín " );
		$aso = array ( "0","1","2","3","4","5","6","7","8","9" );
		$kq = "";
		$tram = floor($so/100); // Hàng trăm
		$chuc = floor(($so/10)%10); // Hàng chục
		$donvi = floor(($so%10)); // Hàng đơn vị
		if($tram==0 && $chuc==0 && $donvi==0) $kq = "";
		if($tram!=0)
		{
			$kq .= $achu[$tram] . " trăm ";
			if (($chuc == 0) && ($donvi != 0)) $kq .= " lẻ ";
		}
		if (($chuc != 0) && ($chuc != 1))
		{
				$kq .= $achu[$chuc] . " mươi";
				if (($chuc == 0) && ($donvi != 0)) $kq .= " linh ";
		}
		if ($chuc == 1) $kq .= " mười ";
		switch ($donvi)
		{
			case 1:
				if (($chuc != 0) && ($chuc != 1))
				{
					$kq .= " mốt ";
				}
				else
				{
					$kq .= $achu[$donvi];
				}
				break;
			case 5:
				if ($chuc == 0)
				{
					$kq .= $achu[$donvi];
				}
				else
				{
					$kq .= " lăm ";
				}
				break;
			default:
				if ($donvi != 0)
				{
					   $kq .= $achu[$donvi];
				}
				break;
		}
		if($kq=="")
		$kq=0;   
		return $kq;
	}
	function doc_so($so)
	{
		$so = preg_replace("([a-zA-Z{!@#$%^&*()_+<>?,.}]*)","",$so);
		if (strlen($so) <= 21)
		{
			$kq = "";
			$c = 0;
			$d = 0;
			$tien = array ( "", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ", " tỷ tỷ" );
			for ($i = 0; $i < strlen($so); $i++)
			{
				if ($so[$i] == "0")
					$d++;
				else break;
			}
			$so = substr($so,$d);
			for ($i = strlen($so); $i > 0; $i-=3)
			{
				$a[$c] = substr($so, $i, 3);
				$so = substr($so, 0, $i);
				$c++;
			}
			$a[$c] = $so;
			for ($i = count($a); $i > 0; $i--)
			{
				if (strlen(trim($a[$i])) != 0)
				{
					if (doc3so($a[$i]) != "")
					{
						if (($tien[$i-1]==""))
						{
							if (count($a) > 2)
								$kq .= " không trăm lẻ ".doc3so($a[$i]).$tien[$i-1];
							else $kq .= doc3so($a[$i]).$tien[$i-1];
						}
						else if ((trim(doc3so($a[$i])) == "mười") && ($tien[$i-1]==""))
						{
							if (count($a) > 2)
								$kq .= " không trăm ".doc3so($a[$i]).$tien[$i-1];
							else $kq .= doc3so($a[$i]).$tien[$i-1];
						}
						else
						{
						$kq .= doc3so($a[$i]).$tien[$i-1];
						}
					}
				}
			}
			return $kq;
		}
		else
		{
			return "Số quá lớn!";
		}
	}  

//convertMoney(1725600);
?> 