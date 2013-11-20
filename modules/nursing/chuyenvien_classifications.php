<?php 
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    $lang_tables[]='departments.php';
    $lang_tables[]='actions.php';
    define('LANG_FILE','aufnahme.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');
    # Create obstetrics object and get all neonatal classifications
    require_once($root_path.'include/care_api_classes/class_department.php');
    $dept=new Department;
    $dept_nr= &$dept->getAllDept();
    $rows=$dept_nr->RecordCount();
    require_once($root_path.'include/care_api_classes/class_personell.php');
    $personell=new Personell();
    $check=explode(";",$name);
    $check_name=array();
    $i=0;
    foreach($check AS $k=>$v){
        if($v!=''){
            $temp=explode(" -",$v);
            $check_name[$i]=$temp[0];
        } 
        $i++;
    }
    html_rtl($lang); 
?>
<head>
<?php echo setCharSet(); ?>
<title>
    <?php echo $LDPersonell_dept; ?>
</title>

<script src="../../js/jquery-1.7.js"></script>
</head>
<body onLoad="if (window.focus) window.focus()">
    <font face=arial>
        <form name="classif" id="classif" onSubmit="return process(this)">
            <table border=0 cellpadding=0 cellspacing=0 bgcolor="#efefef" width="100%">
                <tr bgcolor='#f6f6f6'>
                    <td>	
                        <table width="100%" >
                            <tr>
                                <td background="../../gui/img/common/default/tableHeaderbg.gif" align="center">
                                    <font face=arial color="#efefef" size=3>
                                        <b>
                                            <?php echo $LDPersonell_dept  ?> 
                                        </b>
                                    </font>                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <font face=arial color="red" size=3>
                                        <b>
                                            <?php echo $LDSupport_chosepersonell;  ?> 
                                        </b>
                                    </font>
                                </td>
                            </tr>
                            <?php
                                $rows1=0;
                                if ($rows) 
                                {
                                    $c=0;
                                    while($row=$dept_nr->FetchRow()){
                                        echo '<tr bgcolor="#ffffff">
                                                <td colspan=6>
                                                    <font face=arial size=2>';
                                        $buffer= $$row['LD_var'];                                        
                                        echo '<span style="cursor:pointer;font-weight:bold;" OnClick="check('.$row['nr'].')" />
                                                    <font face=verdana,arial size=2 color=maroon> '.$buffer.'</font><img src="../../gui/img/common/default/arrow_down_blue.gif"/>
                                              </span>';
                                        echo '<br>';                                        
                                        if($list_personell=$personell->getAllOfDept($row['nr'])){
                                            $rows2=$personell->record_count;                                            
                                            $i=1;
                                            echo '<div id="'.$row['nr'].'" style="display:none;" >';
                                            echo '<table width="70%"><tr>';
                                            echo '<th align="center"><font face=arial color="#008080">'.$LDSTT.'</th>';
                                            echo '<th align="center"><font face=arial color="#008080">'.$LDFullName.'</th>';
                                            echo '<th align="center"><font face=arial color="#008080">'.$LDNrPersonell.'</th>';
                                            echo '<th align="center"><font face=arial color="#008080">'.$LDBday.'</th>';
                                            echo '<th align="center"><font face=arial color="#008080">'.$LDNameFunction.'</th>';
                                            echo '</tr>';
                                            while($info_a_personell=$list_personell->FetchRow()){
                                                $full_pnr=$info_a_personell['name_last'].' '.$info_a_personell['name_first'];
                                                $key = array_search($full_pnr, $check_name);
                                                if((string)$key!='')
                                                    $temp_check=(int)$key;
                                                else
                                                    $temp_check='';
                                                if ($i%2==1)
                                                    $bgc="#AFEEEE";
                                                else 
                                                    $bgc="#FFF8C6";
                                                $sql="select chucvu_nr from care_personell_assignment where personell_nr='".$row['nr']."'";
                                                $temp=$db->Execute($sql);
                                                $row1=$temp->fetchrow();
                                                if ($row1['chucvu_nr']==0){
                                                    $sql1="select LD_var from care_role_person where nr='".$info_a_personell['role_nr']."'";
                                                    $temp1=$db->Execute($sql1);
                                                    $row2=$temp1->fetchrow();
                                                    $chucvu=$$row2['LD_var'];
                                                    
                                                }
                                                elseif($row1['chucvu_nr']==1){$chucvu=$LDGiamDoc;}
                                                elseif($row1['chucvu_nr']==2){$chucvu=$LDPhoGiamDoc;}
                                                elseif($row1['chucvu_nr']==3){$chucvu=$LDTruongKhoa;}
                                                elseif($row1['chucvu_nr']==4){$chucvu=$LDPhoKhoa;} 
                                                echo '<tr bgColor="'.$bgc.'">';
                                                echo '<td align="center">'.$i.'</td>';                                                
                                                echo '<td><nobr><input ';                      
                                                echo 'type="checkbox" name="c" id="c'.($rows1+$i).'" value="'.$full_pnr.' - '.$chucvu.' '.$buffer.';"';
                                                
                                                if(gettype($temp_check)=='integer' && $temp_check>=0 && $temp_check<sizeof($check))
                                                    echo ' checked>';
                                                else
                                                    echo ' >';
                                                echo '&nbsp;'.$full_pnr.'</nobr></td>';
                                                echo '<td align="center">'.$info_a_personell['personell_nr'].'</td>';
                                                $birth=@formatDate2STD($info_a_personell['date_birth'],$date_format);
                                                echo '<td align="center">'.$birth.'</td>';                                                                                               
                                                echo '<td align="left">'.$chucvu.'</td>';
                                                echo '</td>';
                                                echo '</tr>';
                                                $i++;
                                            }
                                            echo '</tr></table>';
                                            echo '</div>';
                                            $rows1=$rows1+$rows2;
                                        }else{
                                            echo '<div id="'.$row['nr'].'" style="display:none;"><br/>';                                            
                                            echo '<font face=arial color="darkblue" size=3><b>'.$LDNoPersonell.'</b></font>';
                                            echo '</div>';
                                        }                                        
                                        $c++;
                                        echo '</font>
                                                </td>
                                            </tr>';
                                    }  
                                }
                            ?>		 
                        </table> 
                    </td>
                </tr>
            </table>
            <input type="submit" value="<?php echo $LDOk ?>" />
            <input type="button" value="<?php echo $LDClose ?>" onClick="window.close()" />  
        </form>
    </font>
</body>
</html>
<script>
    function check(id){
        $('#'+id).toggle();
    }
    function process(d) {
	if(<?php echo $rows1 ?>==0) 
            return false;         
	clo=false;
	wd=window.opener.document.discform.nguoiduadi_notes;
        eval("wd.value=''; clo=true;");   
	for(i = 1; i<<?php echo ($rows1+1) ?>; i++){
            eval("if(d.c"+i+".checked) {wd.value=wd.value + d.c"+i+".value + \"\\n\"; clo=true;}");
	}
	if(clo) window.close();
        else return false;	
    }
</script>