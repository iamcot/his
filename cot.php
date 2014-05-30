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