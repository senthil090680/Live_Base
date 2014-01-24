<?php
include('../include/header.php');
?>

<style type="text/css">
#errormsgcol {
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}

.myaligncol {
	 clear:both;
	padding-top:10px;
	margin:0 auto;
	color:#FF0000;
}

#closebutton {
  position:relative;
  top:-35px;
  right:-190px;
  border:none;
  background:url(../images/close_pop.png) no-repeat;
  color:transparent;
  }
</style>

<script type="text/javascript">

//$.ajax({
//    type: 'HEAD',
//    url: 'Upload/',
//    success: function() {
//        alert('Upload File Found.');
//    },  
//    error: function() {
//        alert('Upload not found.');
//    }
//});


function logProgress()
{
   var Devicecode = $("#devicecode").val();
   var posting = $.post("download.php",{Devicecode:Devicecode});
   posting.done(function(data) {
  // alert('Download Success');					 
  $("#log").html(data);
  });
}

</script>
<div id="mainarea">
<div class="clearfix"></div>
<div align="center" class="headingsgr">Master Download</div>
<div id="mytableformgr2">
<div id="containerBD">
 <div class="logfile">
                <fieldset class="alignment">
                    <legend> Master Download For Device</legend>
                    <div class="clearfix"></div>
                    <span>Device List : </span>
                    <select id="devicecode" onchange="return logProgress()">
                     <option value="">--- Select ---</option>
                        <?php
						
						$date = date('Y-m-d');
                        $query = "select device_code from cycle_assignment where Date LIKE '$date%' AND ((flag_status= '1' and end_flag_status='0') or (flag_status = '0' and end_flag_status='0'))";
                        $result = mysql_query($query);
                        while ($data = mysql_fetch_array($result)) {
							 echo '<option value="' . $data['device_code'] . '">' . $data['device_code'] . '</option>';
                        }
                        ?>
                    </select>
                         <div class="clearfix"></div>
                 </fieldset>
                    <div class="clearfix"></div>
                    <div id="log"></div>
                  
       </div>
     </div>  
<div id="errormsgcol"><?php echo  $strOutput; ?>
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>   
  </div> 
</div>   


<?php include('../include/footer.php'); ?>     