<?php
//Connect to database from here
include "../include/config.php";
include "../include/ps_pagination.php";
EXTRACT($_POST);
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />
<title>Host </title>
<script type="text/javascript">
				logProgress();
				function logProgress()
						  	
				{
					var process = $("#route").val();
					var posting = $.post("log.php", {process: process});

					posting.done(function(data) {
						$("#log").html(data);
					});
					
									
			var val=$('#route option:selected').text();
	        $.ajax({
            url: 'get_cuscount.php?val=' + val,
            success: function(data) {
				//alert(data);
				var value=$.trim(data);//To Remove White Space in string
				var value1=data.substring(0,value.length-1);//To return part of the string
				var list= value1.split("|"); 
				for (var i=0; i<list.length; i++) {
					var arr_i= list[i].split("^");
					//alert(arr_i[6]);
					$("#dsr").val(arr_i[0]);
					$("#cuscount").val(arr_i[1]);
			}

			}
        });
				}


</script>
</head>
<body topmargin="0">
<h2 align="center">Customer</h2>
        <div class="clearfix"></div>
		<div class="headfile" align="center">
<form method="post" action="samp.php">        
<table width="70%" align="center">
  <tr>
    <td>Route*
	<td>
   
    <select name="route_code" class="route" id="route"  autocomplete="off"  value="" onchange="logProgress()"; > 
			<option value="">--- Select ---</option>
			<?php 
			$list=mysql_query("select * from  route_master"); 
			while($row=mysql_fetch_assoc($list)){
			$cus=$row['Customer_Name'];
			echo $cuscount=count($cus);
			?>
			<option value='<?php echo $row['route_code']; ?>'<?php if($row['route_code']==$route){ echo 'selected' ; }?>
			><?php echo $row['route_code']; ?></option>
			<?php 
			// End while loop. 
			} 
			?>
			</select>
         
    </td>
     <td>DSR</td>
    <td width=100><input type="text" name="DSRName" id="dsr" size="10" value=""  autocomplete="off" /></td>
     <td>Customer Count</td>
    <td width=100><input type="text" name="customer_count" id="cuscount" size="10" value=""  autocomplete="off" /></td>
  </tr>
</table>
</div>
   <div id="log"> </div>
 </form>   
       
<!--Pagination  -->
 
		<?php 
		if($num_rows > 10){?>     
        <div class="paginationfile" align="center">
	    <?php 
		//Display the link to first page: First
		echo $pager->renderFirst()."&nbsp; ";
		//Display the link to previous page: <<
		echo $pager->renderPrev();
		//Display page links: 1 2 3
		echo $pager->renderNav();
		//Display the link to next page: >>
		echo $pager->renderNext()."&nbsp; ";
		//Display the link to last page: Last
		echo $pager->renderLast();  ?>      
		</div>   
		<?php } else{ echo "&nbsp;"; }?>
        
        


<?php include("../include/error.php");?>
