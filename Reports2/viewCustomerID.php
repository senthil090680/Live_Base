<?php
//Connect to database from here
include "../include/config.php";
EXTRACT($_POST);
$sel="select * from  customer where customer_code='".$_REQUEST['customer_code']."'";
$sel_query=mysql_query($sel);
$fetch=mysql_fetch_array($sel_query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Host </title>
<script type="text/javascript">
	$(function(){
		$('#pop_printOut').click(function(e){
			e.preventDefault();
			var w = window.open();
			var pop_printOne = $('.pop_contentToPrint').html();
			//alert(printOne);
			w.document.write('<html><head><title></title></head><body><h1></h1>' + pop_printOne) + '</body></html>';
			w.window.print();
			w.document.close();
			return false;
		});
	});
</script>

</head>
<body topmargin="0">
<h2 align="center">Customer </h2>
<div  class="pop_contentToPrint">

<table width="100%" height="100%" border="1" align="center" style="border-collapse:collapse;">
<tr>
<td width="48%" height="33" class="text" align="center"><strong>KD Code</strong></td>
<td width="52%" class="text" align="center"><?php echo $fetch['KD_ID'] ;?></td>
<td height="32" class="text"  align="center"><strong>Date</strong></td>
<td class="text" align="center"><?php echo $fetch['Date'] ;?></td>
</tr>

<tr>
<td height="34" class="text" align="center"><strong>DSR Code</strong></td>
<td  class="text" align="center"><?php echo $fetch['DSR_id'] ;?></td>
<td height="32" class="text" align="center"><strong>City</strong></td>
<td  class="text" align="center"><?php echo $fetch['City'] ;?></td>
</tr>

<tr>
<td height="32" class="text" align="center"><strong>State</strong></td>
<td  class="text" align="center"><?php echo $fetch['State'] ;?></td>
<td height="33" class="text" align="center"><strong>Province</strong> </td>
<td  class="text" align="center"><?php echo $fetch['Province'] ;?></td>
</tr>

<tr> 
<td height="33" class="text" align="center"><strong>Lga</strong></td>
<td  class="text" align="center"><?php echo $fetch['Lga'];?></td>
<td height="35" class="text" align="center"><strong>Pin</strong></td>
<td  class="text" align="center"><?php echo $fetch['Pin'] ;?></td>
</tr>

<tr>

<td height="32" class="text" align="center"><strong>GPS</strong></td>
<td  class="text" align="center"><?php echo $fetch['GPS'] ;?></td>
<td height="35" class="text" align="center"><strong>Contactperson1</strong></td>
<td  class="text" align="center"><?php echo $fetch['contactperson1'] ;?></td>
</tr>

<tr>

<td height="32" class="text" align="center"><strong>Contactnumber1</strong></td>
<td  class="text" align="center"><?php echo $fetch['contactnumber1'] ;?></td>
<td height="32" class="text" align="center"><strong>Contactperson2</strong></td>
<td  class="text" align="center"><?php echo $fetch['contactperson2'] ;?></td>
</tr>

<tr>

<td height="33" class="text" align="center"><strong>Contactnumber2</strong> </td>
<td  class="text" align="center"><?php echo $fetch['contactnumber2'] ;?></td>
<td height="33" class="text" align="center"><strong>Route1</strong></td>
<td  class="text" align="center"><?php echo $fetch['route1'];?></td>
</tr>

<tr> 

<td height="35" class="text" align="center"><strong>Route2</strong></td>
<td  class="text" align="center"><?php echo $fetch['route2'] ;?></td>
<td height="32" class="text" align="center"><strong>Category1</strong></td>
<td  class="text" align="center"><?php echo $fetch['category1'] ;?></td>
</tr>

<tr>

<td height="32" class="text" align="center"><strong>Category2</strong></td>
<td  class="text" align="center"><?php echo $fetch['category2'] ;?></td>
<td height="32" class="text" align="center"><strong>Discount</strong></td>
<td  class="text" align="center"><?php echo $fetch['Discount'] ;?></td>
</tr>

<tr>
<td height="32" class="text" align="center"><strong>Category3</strong></td>
<td  class="text" align="center"><?php echo $fetch['category3'] ;?></td>
<td height="32" class="text" align="center"><strong>Max Discount</strong></td>
<td  class="text" align="center"><?php echo $fetch['Max_Discount'] ;?></td>
</tr>
</table>
</div>
<div>&nbsp;</div>
<div class="pop_contentSection">
<a href="#" id="pop_printOut"><img src="../images/print_icon.png" width="85" height="24" /></a>
</div>
</body>
</html>


