<!---->
<!--phpinfo();-->
<?php
/**
 * Created by IntelliJ IDEA.
 * User: MINHPHUONG
 * Date: 5/22/14
 * Time: 3:56 PM
 * To change this template use File | Settings | File Templates.
 */
include("Smarty/libs/Smarty.class.php");
$df=new Smarty();
//$df->display("cau20.tpl");
if(isset($_POST["OK"]))
{
    $enterValue="";
    if($_POST["enterValue"]==NULL)
    {
        echo "Please enter any value";
    }
//    else if(isset($_POST["enterValue"]))
    else
    {
//        $enterValue = $_POST["enterValue"];
        $df->assign("enterValueS",$_POST["enterValue"]);
    }
//echo $enterValue;
}
$df->display("clearText.tpl");
     ?>

<form name="listmedform" method="POST" onSubmit="return chkform(this)">
    <center>
        <table cellSpacing="1" cellPadding="3" border="0" width="90%">
            <tr>
                <th align="left"><font size="3" color="#5f88be"><?php echo $LDDept . ': ' . $deptname; ?></th>

<td align="left" rowspan="2">
    <table>
        <tr>
            <td> Thuốc:     <br>
                <input type="radio" name="typeMedicine"
                    <?php if (isset($typeMedicine) && $typeMedicine=="TatCa")
                        echo "checked";
                    //                                    print $tatca_status;
                    ?>
                       value="tatca">Tất cả
                <!--                            --><?php //echo $LDTatCaMedicine ;  ?><!--    -->

                <input type="radio" name="typeMedicine"
                    <?php if (isset($typeMedicine) && $typeMedicine=="TamThan")
                        echo "checked";
                    //                                print $tamthan_status;
                    ?>
                       value="tamthan">Hướng Tâm Thần
                <!--                            --><?php //echo $LDTamThanMedicine ;  ?>   <br>
            </td>
            <td><a href="javascript:searchMedicine()"><input
                        type="image" <?php echo createComIcon($root_path, 'Search.png', '0', '', TRUE) ?>
                        onclick=""></a></td>
        </tr>
    </table>