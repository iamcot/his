<!DOCTYPE html PUBLIC >
<html>
<head>
    <meta charset="utf-8"/>
</head>
<body>
<h3>NHẬP THUỐC VÀO KHO CHẴN/LẺ </h3>

<form action="Update_Thuoc.php" method="post" enctype="multipart/form-data" >
Select file :<input type="file" name="uploadedfile" size="20"/> <br/>
<input type="submit" name="khole" value="Import Kho lẻ"/>
<input type="submit" name="khochan" value="Import Kho chẵn"/>
<br/>
Search for: <input type="text" name="find"/>
<input type="submit" name="search" value="Search"/>
<br/>

</form>
</body>
</html>
<?php
if (isset($_POST['khole']) || isset($_POST['khochan'])) {
    if ($_FILES['uploadedfile']['error'] == UPLOAD_ERR_OK //checks for errors
        && is_uploaded_file($_FILES['uploadedfile']['tmp_name'])
    ) {
        $file = file_get_contents($_FILES['uploadedfile']['tmp_name']);
        $lines = explode("\n", $file);
        $con = mysqli_connect("localhost", "root", "", "histudb");
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
    if (isset($_POST['khole'])) $type = 'khole';
    else $type = 'khochan';

    $sql1 = "UPDATE care_pharma_products_main_sub SET number =0 ";
    $sql0 = "UPDATE care_pharma_available_product SET available_number=0";
    if ($type == 'khochan') {
        if ($result = mysqli_query($con, $sql1))
            echo " Thành công \n\r<br>";
    } else {
//        echo $sql1;
        if ($result = mysqli_query($con, $sql0))
            echo " Thành công \n\r<br>";
        else echo $sql0;
    }

    foreach ($lines as $k => $line) {
        $arrthuoc = explode("\t", $line);
        importthuoc($arrthuoc, $type);
    }
} else if (isset($_POST['search'])) {

    if ($_POST['find'] == "") {
        echo "<p>Vui lòng nhập từ khóa cần tìm!";
        exit;
    } else {
        $find = $_POST['find'];

        if ($find) {
            $con = mysqli_connect("localhost", "root", "", "histudb");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

            $sql = "SELECT * FROM map_thuoc WHERE product_name = '$find'";
            if ($result = mysqli_query($con, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    echo "<pre>";
                    echo "<h2> Kết quả cần tìm : </h2><p>";
                    printf(" %s  %s ", $row[0], $row[1]);
                    echo "<pre>";
                } echo 'Không có kết quả nào cho từ khóa :"' . $find . '"';
            }
        }
    }
}

//NEU BAO CAO KHO CHAN
//update kho chan set soluong = 0 where productencoder = $mathuoc
//insert mathuoc, dongia, so luong, so lo ... vao kho chan
//NEU KHO LE
//update kho LE set soluong = 0 where productencoder = $mathuoc
//insert mathuoc, dongia, so luong, so lo ... vao kho LE
function importthuoc($arrthuoc, $type = 'khochan')
{
    $con = mysqli_connect("localhost", "root", "", "histudb");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $sql = "SELECT product_encoder FROM dfck_map_thuoc WHERE product_name = '" . trim($arrthuoc[1]) . "' ";
    if ($result = mysqli_query($con, $sql)) {
        $row = mysqli_fetch_row($result);
        // echo $row[0]."\t".$arrthuoc[5]."\t".$arrthuoc[13]."\t".$arrthuoc[16]."\n\r<br>";
        $mathuoc = $row[0];
        //  $dongia = explode(".",$arrthuoc[4]);
        $dongia = str_replace(",", "", $arrthuoc[4]);
        $soluong = explode(".", $arrthuoc[12]);
        $soluong = str_replace(",", "", $soluong[0]);
        $solo = $arrthuoc[15];
        $sql4=  "DELETE FROM care_pharma_products_main_sub WHERE number=0";
        $sql2 = "INSERT INTO care_pharma_products_main_sub(product_encoder,lotid,number,price) VALUES('$mathuoc','$solo','$soluong','$dongia') ";
        $sql3 = "INSERT INTO care_pharma_available_product(product_encoder,product_lot_id,available_number,price) VALUES('$mathuoc','$solo','$soluong','$dongia') ";
        if ($type == 'khochan') {
            if (mysqli_query($con, $sql2)) {
                if($soluong==0)
                {
                    if (mysqli_query($con, $sql4)) {
                        echo "Đã xóa thuốc cũ:".$mathuoc." \t";
                    }
                    else echo $sql4;
                    }

                echo "Thêm thành công thuốc : \t";
                echo $row[0] . "\t" . $solo . "\t" . $soluong . "\t" . $dongia . "\n\r<br>";
                }

            }




        else {
            if (mysqli_query($con, $sql3)) {
                echo "Thêm thành công thuốc : \t";
                echo $row[0] . "\t" . $solo . "\t" . $soluong . "\t" . $dongia . "\n\r<br>";
            }
            else   echo $sql3;
        }

    }
    else  echo $sql;

}

?>

