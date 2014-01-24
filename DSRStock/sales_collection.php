<?php
include('../include/header.php');
include "../include/ps_pagination.php";
$query 	= mysql_query("select DSRName,DSR_Code from dsr");
?>

<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.SalesTransPop {
	margin:0 auto;
	display:none;
	background:#A09E9E;
	color:#fff;
	width:800px;
	height:230px;
	position:fixed;
	left:250px;
	top:250px;
	border:1px solid #EEEEEE;
	z-index:2;
	border-radius:5px 5px 5px 5px;
}
.CusBalDuePop {
	margin:0 auto;
	display:none;
	background:#A09E9E;
	color:#fff;
	width:800px;
	height:230px;
	position:fixed;
	left:250px;
	top:250px;
	border:1px solid #EEEEEE;
	z-index:2;
	border-radius:5px 5px 5px 5px;
}
</style>

<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">SR SALES & COLLECTION</div>
<div id="mytableformgr" align="center">
<form action="" method="post" >
<table width="100%" align="center">
 <tr>
  <td>
 <fieldset class="alignment">
 <div align="center" id="error"></div>
  <legend><strong>SR Sales & Collection</strong></legend>
  
  <table width="100%">
    <tr>
		<td height="28" class="align">Date:</td><td><input type="text" readonly class="datepicker" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" name="Date" id="Date" onChange="loadsalescolDate(this.value);" /></td>
		
		<td>Currency</td>
		<td height="28" ><img style="vertical-align: middle" src='../images/currency.gif' width="15px" height="15px"/> Naira</td>
	</tr>

    <tr>
		<td style="padding-bottom:10px;" height="28" class="align">SR Name</td>
		<td>
			<select class="cdsrname" name="dsrname" id="dsrname" onChange="loadsalescol(this.value);">
			<option value="">---Select---</option>
			<?php while($info = mysql_fetch_assoc($query)){?>
			<option value="<?php echo  $info['DSR_Code'] ?>"><?php echo  $info['DSRName']; ?></option>
			<?php }?>
			</select>
		</td>
		<td height="28">SR Code</td>
		<td><input type="text" name="cdsrcode" id="cdsrcode" readonly size="20" value="" autocomplete='off' maxlength="15"/></td>
    </tr>
    
    
	<tr>
		<td style="padding-bottom:10px;" class="align" height="28">Vehicle Name</td>
		<td><input type="text" name="vehicle_name" id="vehicle_name" readonly size="20" value="" maxlength="15"/>
		</td>
		
		<td height="28">Device Code</td>
		<td><input type="text" name="device_code" id="device_code" readonly size="20" value="" maxlength="15"/></td>
	</tr>

	<!-- <tr>
	    <td >&nbsp;</td>   
	</tr> -->


	<!-- <tr>
	<td width="120" height="5">&nbsp;</td>
	    <td>&nbsp;</td></td>  
	<td colspan="2">&nbsp;</td>    
	</tr> -->
   </table>
 </fieldset>
   </td>
 </tr>
 <tr>
	<td height="20">&nbsp;</td>
	<td>&nbsp;</td>  
 </tr>
</table>
<div class="clearfix"></div> 
<div class="clearfix"></div> 
 <!----------------------------------------------- Left Table End -------------------------------------->
</form>


<!---- Form End ----->
<div class="clearfix"></div>        
<div id="containerpr">	  
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
			<tr id="titleRow">
				<!-- <th nowrap="nowrap">Currency</th> -->
				<th nowrap="nowrap" class="rounded">Total Sales Value<img src="../images/sort.png" width="13" height="13" /></th>
				<th nowrap="nowrap">Total Collection Value</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align='center' colspan="3">No Records Found.</td>
				<td style="display:none;" >Cust Name<img src="../images/sort.png" width="13" height="13" /></td>
				<td style="display:none;" nowrap="nowrap">Add Line1</td>
			</tr>			
		</tbody>
		</table>
         </div>   
        <div class="paginationfile" align="center">
         <table>
         <tr>
			 <th class="pagination" scope="col">          
			<?php 
			if(!empty($num_rows)){
			//Display the link to first page: First
			echo $pager->renderFirst()."&nbsp; ";
			//Display the link to previous page: <<
			echo $pager->renderPrev();
			//Display page links: 1 2 3
			echo $pager->renderNav();
			//Display the link to next page: >>
			echo $pager->renderNext()."&nbsp; ";
			//Display the link to last page: Last
			echo $pager->renderLast(); } else{ echo "&nbsp;"; } ?>      
			</th>
		</tr>
        </table>
      </div> 
   </div>
   <div class="clearfix"></div>
   	     <div id="errormsgsalcol" style="display:none;"><h3 align="center" class="myalignsalcol"></h3><button id="closebutton_blue">Close</button></div>
		 <div class="clearfix"></div>
		 <span ><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
</div>
</div>
<div id="backgroundChatPopup"></div>
<?php include('../include/footer.php'); ?>