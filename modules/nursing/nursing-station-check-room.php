<?php
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	/* Load the ward object */
	require_once($root_path.'include/care_api_classes/class_ward.php');
		
	$ward_nr = $_POST['ward_nr']; 
	$first_room_id = $_POST['first_room_id'];
	$last_room_id = $_POST['last_room_id'];
	$direction = $_POST['direction'];

	$ward_obj=new Ward($ward_nr);
	
	if($direction == 1){
		for($i=$first_room_id;$i<$last_room_id;$i++){
			if($ward_obj->RoomExists($i)){
				$patients = $ward_obj->countPatients($i);
				if($patients == false) {
					echo 'dberror';
					break;
				}
				else if($patients > 0) {
					echo $i;
					break;
				}
			}else {
				echo 'notexist';
				break;
			}
		}
		if($i == $last_room_id) echo 'ok';
	} else if($direction == 2){
		for($i=$last_room_id;$i>$first_room_id;$i--){
			if($ward_obj->RoomExists($i)){
				$patients = $ward_obj->countPatients($i);
				if($patients == false) {
					echo 'dberror';
					break;
				}
				else if($patients > 0) {
					echo $i;
					break;
				}
			}else {
				echo 'notexist';
				break;
			}
		}
		if($i == $first_room_id) echo 'ok';		
	}
        else if($direction == 3){//check maximum room_nr
            echo $ward_obj->getMaxRoomNr($ward_nr);
        }
        else if($direction == 4){//close room
            $nr = $_POST['nr'];
            
            echo $ward_obj->cot_closeRoom($nr);
        }
        else if($direction == 5){//close room
            $rnr = $_POST['room_nr'];
            $wnr = $_POST['ward_nr'];
            $rs = $ward_obj->cot_delRoom($rnr);
            if($rs){
                $ward_obj->cot_DecRoomCountInWard($wnr);
                
            }
            echo $rs;
        }
        else if($direction == 6){//reopen room
            $rnr = $_POST['nr'];
            $rs = $ward_obj->cot_reopenRoom($rnr);

            echo $rs;
        }
?>