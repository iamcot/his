<script language="javascript">
function checkTime(d){
    var itemtime= d.value;
	if (itemtime=='__:__'){
		//alert('<?php echo $LDWarningNoTime; ?>');
		return;
	}
    var time= itemtime.split(":");
    if(time[0].length<=2 && time[1].length<=2){
		if(isNaN(time[0]) || parseInt(time[0])>23){
                    alert("<?php echo $LDWarningHour; ?>");
                    d.focus();                    
                    return false;
		}
		if(isNaN(time[1]) || parseInt(time[1])>59){
                    alert("<?php echo $LDWarningMinute; ?>");
                    d.focus();                    
                    return false;
		}
		if(time[1].length>2){
                    alert("<?php echo $LDWarningHour; ?>");
                    d.focus();                    
                    return false;
		}
        return true;
    }else{  
        alert("<?php echo $LDWarningTime; ?>");
        d.focus();
        return false;
    }
}
function checkIsNumber(value){
	value = value.replace(',','.');
	value = value.replace('/','.');
	if (parseInt(value)!=value && parseFloat(value)!=value){
		return false;
	}
	else
		return true;
}

</script>