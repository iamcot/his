<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=16;
$report_auxtitlesize=10;
$report_authorsize=10;
$sex ='';
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
# Get the encouter data
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	//extract($encounter);
}

# Fetch insurance and encounter classes
$encounter_class=$enc_obj->getEncounterClassInfo($encounter['encounter_class_nr']);
$insurance_class=$enc_obj->getInsuranceClassInfo($encounter['insurance_class_nr']);

# Resolve the encounter class name
if (isset($$encounter_class['LD_var'])&&!empty($$encounter_class['LD_var'])){
	$eclass=$$encounter_class['LD_var'];
}else{
	$eclass= $encounter_class['name'];
} 
# Resolve the insurance class name
if (isset($$insurance_class['LD_var'])&&!empty($$insurance_class['LD_var'])) $insclass=$$insurance_class['LD_var']; 
    else $insclass=$insurance_class['name']; 

# Get ward or department infos
if($encounter['encounter_class_nr']==1){
	# Get ward name
	include_once($root_path.'include/care_api_classes/class_ward.php');
	$ward_obj=new Ward;
	$current_ward_name=$ward_obj->WardName($encounter['current_ward_nr']);
}elseif($encounter['encounter_class_nr']==2){
	# Get ward name
	include_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department;
	//$current_dept_name=$dept_obj->FormalName($current_dept_nr);
	$current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $current_dept_name=$$current_dept_LDvar;
		else $current_dept_name=$dept_obj->FormalName($encounter['current_dept_nr']);
}

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;
require_once($root_path.'include/care_api_classes/class_ecombill.php');
$ecombill_obj=new eCombill;

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");


function convertMoney($number){
	
	$donvi=" đồng ";
	$tiente=array("nganty" => "nghìn tỷ","ty" => "tỷ","trieu" => "triệu","ngan" =>" nghìn","tram" => "trăm");
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
				return $text;
				break;
		case '2':
				$ngan=doc3so($mang[0]);
				$text=$ngan.$tiente['ngan'];
				$tram=doc3so($mang[1]);
				if((int)$mang[1]!=0)
					$text.=$tram;
				$text.=$donvi;
				return $text;
				break;
		case '1':
				$tram=doc3so($mang[0]);
				$text=$tram.$donvi;
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
			$kq .= $achu[$tram] . "trăm";
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



class exec_String {
var $lower = '
a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z
|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ
|đ
|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ
|í|ì|ỉ|ĩ|ị
|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ
|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự
|ý|ỳ|ỷ|ỹ|ỵ';
var $upper = '
A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ
|Đ
|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ
|Í|Ì|Ỉ|Ĩ|Ị
|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ
|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự
|Ý|Ỳ|Ỷ|Ỹ|Ỵ';
var $arrayUpper;
var $arrayLower;
function BASIC_String(){
$this->arrayUpper = explode('|',preg_replace("/\n|\t|\r/","",$this->upper));
$this->arrayLower = explode('|',preg_replace("/\n|\t|\r/","",$this->lower));
}

function lower($str){
return str_replace($this->arrayUpper,$this->arrayLower,$str);
}
function upper($str){
return str_replace($this->arrayLower,$this->arrayUpper,$str);
}
}
//----------------Care2x
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');


$tpdf = new tFPDF('L','mm','a5');
$tpdf->AddPage();
$tpdf->SetTitle('Phieu tam ung vao vien');
$tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$tpdf->SetRightMargin(15);
$tpdf->SetLeftMargin(5);
$tpdf->SetTopMargin(5);
$tpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$tpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$tpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$tpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$tpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$tpdf->SetFont('DejaVu','B',10);
$tongtien=convertMoney($ecombill_obj->getTamung($encounter['encounter_nr']));
//---------------------------
$w=97;
$y=$tpdf->GetY();
$x=$tpdf->GetX();
$str=$encounter['name_last']." ".$encounter['name_first'];	
$s_obj=new exec_STRING();
$s=$s_obj->BASIC_String();	
$s=$s_obj->upper($str);
for($i=0;$i<2;$i++)
{
	$tpdf->SetY($y);
	
	$tpdf->SetX($x);
	$tpdf->SetFont('','B',10);
	$tpdf->MultiCell(40,5,"BỆNH VIỆN ĐA KHOA\nTÂN UYÊN",0,'C');
	$tpdf->SetX($x);
	$tpdf->SetFont('','B',11);
	$tpdf->Cell($w,6,'PHIẾU TẠM ỨNG VÀO VIỆN',0,1,'C');
	$tpdf->SetX($x);
	$tpdf->SetFont('','',10);
$tpdf->MultiCell($w,5,"HỌ VÀ TÊN: ".$s."\n"
."ĐỊA CHỈ: ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['citytown_name']."\n"
."LÍ DO THU: TẠM ỨNG \n"
."SỐ TIỀN: ".$ecombill_obj->getTamung($tamung_id)."\n"
."BẰNG CHỮ:".convertMoney($ecombill_obj->getTamung($tamung_id))."\n"
."ĐIỀU TRỊ TẠI KHOA:...........................................................",0,'L');
	$tpdf->SetX($x);
	$tpdf->Cell($w,5,"Tân Uyên, ngày ".date("d")." tháng " .date("m")." năm ".date("Y"),0,1,'R');
	$tpdf->SetX($x);
	$tpdf->Cell($w/2,5,'KT VIỆN PHÍ',0,0,'L');
	$tpdf->Cell($w/2,5,'HC KHOA         ',0,1,'C');

	$x=$x+$w;

}






//$tpdf->Output();
$tpdf->Output('PhieuTamUngVaoVien.pdf', 'I');


?>
