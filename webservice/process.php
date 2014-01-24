<?php
session_start();
ob_start();
include("../include/header.php");
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
	$query= "select * from ping_table";
	$result = mysql_query($query);
?>
	<div id="mainarea">
		<fieldset>
	<legend>Upload / Download Process to Device </legend>
<table>
<?php
while($data = mysql_fetch_array($result)) {
	if($data['STATUS'] == "ONLINE")
		$color="lightgreen";
	else 
		$color = "lightcoral";
?>
<tr>
	<td>
		<input  style="text-align:center;background-color:<?php echo $color;?>" type="text" id="<?php echo $data['DEVICE_CODE']; ?>" value="<?php echo $data['DEVICE_CODE']; ?>" disabled />
	</td>
	<td>
		<input type= "button" value ="Download"  class ="button" id="D_<?php echo $data['DEVICE_CODE']; ?>" <?php if($data['STATUS']=="OFFLINE") echo "disabled";?> />
	</td>
	<td>
		<input type= "button" value ="Upload" class ="button" id="U_<?php echo $data['DEVICE_CODE']; ?>" <?php if($data['STATUS']=="OFFLINE") echo "disabled";?> />
	</td>
	<td>
		<input type= "button" value ="Reconsile"  class ="button" id="R_<?php echo $data['DEVICE_CODE']; ?>" <?php if($data['STATUS']=="OFFLINE") echo "disabled";?> />
	</td>
	<td>
		Start Date :
		<input type="text" id ="sdate_<?php echo $data['DEVICE_CODE']; ?>"   style="text-align:center;" value ="<?php echo date('Y-m-d'); ?>"  />
	</td>
	<td>
		End Date :	<input type="text" id ="edate_<?php echo $data['DEVICE_CODE']; ?>"  style="text-align:center;" value ="<?php echo date('Y-m-d'); ?>" />
	</td>
</tr>
<?php
}
?>
</table>
</fieldset>
<script type="text/jscript">
$(function(){
  statusfn();
});
function statusfn(){
    var status = $.ajax({
        type: "POST",
        url: "status.php",
        async: false
    }).success(function(){
        setTimeout(function(){statusfn();}, 1000);
    }).responseText;
	var value = status.split(",");
	$.each(value, function( key, code ) {		
		code=code.split(":");
		$("#" + code[0]).css("background-color",code[1]);		
		if(code[1] == "lightgreen") {
			$("#D_" + code[0]).removeAttr('disabled');
			$("#U_" + code[0]).removeAttr('disabled');
			$("#R_" + code[0]).removeAttr('disabled');		
		}
		else {
			$("#D_" + code[0]).attr('disabled','disabled');
			$("#U_" + code[0]).attr('disabled','disabled');
			$("#R_" + code[0]).attr('disabled','disabled');		
		}		
		//alert (code);			
	});
}
$(".button").click(function(){
	var id = this.id;	 
	alert(id);
	var array_id=id.split("_");
	id = array_id[1];
	var action = array_id[0];
	 if(action == "R") {
		 var sdate = $("#sdate_" + id).val();
		 var edate = $("#edate_" + id).val();
	 }	 
	 var posting = $.post( "save.php", { deviceCode : id, action : action, sdate:sdate , edate : edate } );
		posting.done(function( data ) {
		alert(data);
	 });	 
});
</script>
	</div>
<?php include('../include/footer.php');?>