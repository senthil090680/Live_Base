<?php
include('../include/header.php');
session_start();

//echo phpinfo();

//ini_set("max_input_vars","10000000");

/*echo ini_get("max_input_vars")."<br>";
echo ini_get("max_execution_time")."<br>";
echo ini_get("max_input_time")."<br>";

exit;*/



?>
<!------------------------------- Form -------------------------------------------------->

<div id="mainarea">

<div  style="padding-top:10%;margin-left:auto;margin-right:auto" align="center">
<div id='loadingmessagediv' style='display:none'>
  <img src='../images/loading.gif'/><br/><br/><br/>
</div>
<!--<input type="button"  class="buttonsbig" value="Download Device Data" onclick="load()"/>-->
<input type="button"  class="buttonsbig" value="VehicleStockload" onclick="vehicle()"/>
<input type="button"  class="buttonsbig" value="Upload Device Data" onclick="upload()"/>
<!--<input type="button"  class="buttonsbig" value="Auto Upload Device Data" onclick="uploadauto()"/>-->
<input type="button"  class="buttonsbig" value="Upload GPS Data" onclick="uploadgps()"/>
</div>

    
    
<script>
function load(){
   var posting = $.post("download.php", {id:"tt33"});
        posting.done(function(data) {
            alert("done");
        });

   
}
   
   
function vehicle(){
   var posting = $.post("vehicle.php", {id:"tt33"});
        posting.done(function(data) {
            //alert(data);
			//alert("done");
			alert("Data Successfully Uploaded to Base System");
        });
}


function upload(){
   $('#loadingmessagediv').show();  // show the loading message.
   var posting = $.post("upload.php", {id:"tt33"});
        posting.done(function(data) {
           //alert(data);
			if(data == "fail1") {
				alert("Upload Failed At Index 1");
				return false;
			} if(data == "fail12") {
				alert("Upload Failed At Index 12");
				return false;
			} if(data == "fail6") {
				alert("Upload Failed At Index 6");
				return false;
			} if(data == "nofile") {
				alert("No Upload File Found");
				return false;
				$('#loadingmessagediv').hide(); // hide the loading message
			} else {
				$('#loadingmessagediv').hide(); // hide the loading message
				alert("Data Successfully Uploaded to Base System");
			}
			//alert("done");			
        });
}

function uploadauto(){
   var posting = $.post("uploadsync.php", {id:"tt33"});
        posting.done(function(data) {
            alert(data);
			//alert("done");
			if(data == "2") {
				alert("Device is not Online, Please Check!");
			} else {
				alert("Data Successfully Uploaded to Base System");
			}
			//alert("Data Successfully Uploaded to Base System");
        });
}
    
function uploadgps(){
   var posting = $.post("gps_track_details.php", {id:"tt33"});
        posting.done(function(data) {
            //alert(data);
			//alert("done");
			alert("GPS Data Successfully Uploaded to Base System");
        });
}
    
    </script>
    
</div>
<?php include('../include/footer.php'); ?>