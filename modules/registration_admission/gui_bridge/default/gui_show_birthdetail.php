<table border=0 cellpadding=1 cellspacing=1 width=100% class="frame">
<?php
    if($parent_admit){
        # Get the pregnancy data of this encounter
    //                $pregs=&$obj->Pregnancies($_SESSION['sess_en'],'_ENC');
        if($pregs=&$obj->Obstetrics1($_SESSION['sess_pid'], $_SESSION['sess_en'])){
            $pregnancy=$pregs->FetchRow();
        }                
    }else{
        # Get all pregnancies  of this person
    //                $pregs=&$obj->Pregnancies($_SESSION['sess_pid'],'_REG');
        if($pregs=&$obj->Obstetrics2($_SESSION['sess_pid'])){
            $pregnancy=$pregs->FetchRow();
        } 
    }  
    $i=1;
    $new=''; 
    $flag=0;
    if($pregnancy['child_encounter_nr']){
        $child_nr=explode(";",$pregnancy['child_encounter_nr']);
    }
    while($i<=$pregnancy['nr_of_fetuses']){
        $result1=&$obj->BirthDetails1($_SESSION['sess_pid'],$i);
        if($obj->LastRecordCount()){
            $baby_record=$result1->FetchRow();
            $flag=1;
        }else{
            $baby_record="";
            $flag=0;
        }
        echo '<tr class="adm_item">';
        if($child_nr_chose == $child_nr[$i]){
            echo '<td colspan="2" bgcolor="yellow"><font color="red">';
        }else{
            echo '<td colspan="2"><font color="blue">';
        }
?>
        <img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?> />
        <?php
            if($flag==0){
                echo '<b>'.$baby.' '.$i.'   -   '.$LDNr.': '.$child_nr[$i].'</b></font></td></tr>';
                if($target!=''){
                    echo "<tr class=adm_input><td colspan=2><a id=link onclick=checkClick(this,'$i','$child_nr[$i]','$rows');>";
        ?>
        <img <?php echo createComIcon($root_path,'update2.gif','0','absmiddle'); ?> />
        <?php
                    echo $LDEnterNewRecord.'</a></td></tr>';
                }
            }else{
                echo '<b>'.$baby.' '.$i.'   -   '.$LDNr.': '.$child_nr[$i].'</b></font></td></tr>'; 
                    # Get the field names
                    $array=array('nr','pid','parent_encounter_nr','delivery_nr','sex','encounter_nr','delivery_place','delivery_mode','c_s_reason','date','delivery_time','born_before_arrival','face_presentation','posterio_occipital_position','delivery_rank','apgar_1_min', 'apgar_5_min', 'apgar_10_min', 'time_to_spont_resp', 'condition', 'weight', 'length', 'head_circumference', 'scored_gestational_disability','tatbamsinh','cohaumon', 'feeding', 'congenital_abnormality', 'classification', 'disease_category', 'outcome');
                    $obj->setRefArray($array);
                    $fields=&$obj->coreFieldNames();
                    while(list($z,$x)=each($fields)){
                        if($x=='status') break;
                        if(empty($baby_record[$x])||stristr('nr,encounter_nr,pid',$x)||$x=='modify_id'||$x=='modify_time') continue;
                        if($child_nr_chose == $child_nr[$i]){
                            echo '<tr bgcolor="yellow">';
                        }else{
                            echo '<tr bgcolor="#fefefe">';
                        }
        ?>        
            <td style="padding-left:15px;">
                <FONT color="#006600">
                    <b>
                        <?php 
                            if($x=="delivery_rank"){
                                echo $LD['duoctiem'];
                            }else{
                                echo $LD[$x]; 
                            }                            
                        ?>
                    </b>
            </td>
            <td style="padding-left:15px;">
                <?php 
                        switch($x){
                                            //  header('Location:aufnahme_daten_zeigen.php'.URL_REDIRECT_APPEND.'&encounter_nr='.$encounter_nr.'&origin=admit&sem=isadmitted&target=entry');
                                case 'sex':
                                    if($baby_record[$x]==1){
                                        echo $LDTrai;
                                    }else{
                                        echo $LDGai;
                                    }
                                    break;
                                case 'parent_encounter_nr': 
                                    echo'<a href="aufnahme_daten_zeigen.php'.URL_APPEND.'&encounter_nr='.$baby_record[$x].'&origin=admit&target='.$target.'">'.$baby_record[$x].'</a>'; 
                                    break;
                                case 'date': 
                                    echo formatDate2Local($baby_record[$x],$date_format); 
                                    break;
                                case 'c_s_reason': 	
                                    echo nl2br($baby_record[$x]); break;
                                case 'delivery_mode':
                                    $buf=&$obj->getDeliveryMode($baby_record[$x]);
                                    if(isset($$buf['LD_var']) && $$buf['LD_var']) echo $$buf['LD_var'];
                                            else echo $buf['name'];
                                    break;
                                case 'feeding':
                                    $buf=&$obj->getFeedingType($baby_record[$x]);
                                    if(isset($$buf['LD_var']) && $$buf['LD_var']) echo $$buf['LD_var'];
                                            else echo $buf['name'];
                                    break;
                                case 'disease_category':
                                    $buf=&$obj->getDiseaseCategory($baby_record[$x]);
                                    if(isset($$buf['LD_var']) && $$buf['LD_var']) echo $$buf['LD_var'];
                                            else echo $buf['name'];
                                    break;
                                case 'outcome':
                                    $buf=&$obj->getOutcome($baby_record[$x]);
                                    if(isset($$buf['LD_var']) && $$buf['LD_var']) echo $$buf['LD_var'];
                                            else echo $buf['name'];
                                    break;
                                case 'born_before_arrival':
                                    if($baby_record[$x]) echo $LDYes_s;
                                            else echo $LDNo;
                                    break;
                                case 'posterio_occipital_position':
                                    if($baby_record[$x]) echo $LDYes_s;
                                            else echo $LDNo;
                                    break;
                                case 'face_presentation':
                                    if($baby_record[$x]) echo $LDYes_s;
                                            else echo $LDNo;
                                    break;
                                case 'classification': 	
                                    echo nl2br($baby_record[$x]); 
                                    break;
                                
                                case 'weight': 
                                    echo $baby_record[$x].' gram'; 
                                    break;
                                
                                case 'length': 
                                    echo $baby_record[$x].' cm'; 
                                    break;
                                
                                case 'head_circumference': 
                                    echo $baby_record[$x].' cm'; 
                                    break;
                                
                                case 'tatbamsinh': 
                                    if($baby_record[$x]) echo $LDYes_s;
                                            else echo $LDNo;
                                    break;
                                    
                                case 'cohaumon': 
                                    if($baby_record[$x]) echo $LDYes_s;
                                            else echo $LDNo;
                                    break;
                                    
                                case 'time_to_spont_resp':
                                    echo $baby_record[$x].' '.$LDMinutes;
                                    break;
                                
                                case 'apgar_1_min':
                                case 'apgar_5_min':
                                case 'apgar_10_min':
                                    echo $baby_record[$x].' '.$diem;
                                    break;
                                default: 
                                    echo $baby_record[$x];
                                    break;
                        }
                ?>
        </td>
    </tr>
    
<?php

		}
                if($baby_record['modify_time']!='0000-00-00 00:00:00'){
?>
                    <tr bgcolor="#fefefe">
                        <td style="padding-left:15px;">
                            <FONT color="#006600">
                            <b>
                            <?php 
                                echo $LD['modify_id'];
                            ?>
                            </b>
                            </FONT>
                        </td>
                        <td style="padding-left:15px;"> 
                            <?php echo $baby_record['modify_id']; ?>
                        </td>
                    </tr>
                    <tr bgcolor="#fefefe">
                        <td style="padding-left:15px;">
                            <FONT color="#006600">
                            <b>
                            <?php 
                                echo $LDTimeEdit;
                            ?>
                            </b>
                            </FONT>
                        </td>
                        <td style="padding-left:15px;"> 
                            <table>
                                <tr>
                                    <td>
                                        <?php 
                                            $t=explode(' ', $baby_record['modify_time']);
                                            echo $t[1]; 
                                        ?>
                                    </td>
                                    <td width="100px"></td>
                                    <td>
                                        <FONT color="#006600">
                                        <b>
                                        <?php echo $LDDate; ?>
                                        </b>
                                        </FONT>
                                    </td>
                                    <td>
                                        <?php echo formatDate2Local($t[0],$date_format); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>                        
<?php                    
                }
?>
                    <tr bgcolor="#fefefe">
                        <td style="padding-left:15px;">
                            <FONT color="#006600">
                            <b>
                            <?php 
                                echo $LD['docu_by'];
                            ?>
                            </b>
                            </FONT>
                        </td>
                        <td style="padding-left:15px;">
                            <?php
                                echo $baby_record['create_id'];
                            ?>
                        </td>
                    </tr>
    <?php
        if($parent_admit && $edit && $target!='') {
    ?>
    <tr class="adm_input">
        <td colspan="2">
            <img <?php echo createComIcon($root_path,'update2.gif','0','absmiddle'); ?> />
            <a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=newdata&allow_update=1&nr='.$i.'&para='.$child_nr[$i]; ?>">
            <?php
                echo $LD['update_bd'].'</a>';
            ?>
        </td>
    </tr>
    <tr bgcolor="#fefefe">
        <td colspan="2" align="center">
            &nbsp;
        </td>
    </tr>
    <?php
        }
            }            
        $i++;
    }
    ?>
</table>

