<?php
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
$query 	= mysql_query("select id,DSRName,DSR_Code from dsr");
?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<link href="../css/popup.css" rel="stylesheet" type="text/css" />

<style type="text/css">
.confirmMAp {
	margin:0 auto;
	display:none;
	background:#EEEEEE;
	color:#fff;
	width:622px;
	height:350px;
	position:fixed;
	left:250px;
	top:100px;
	border:1px solid #EEEEEE;
	z-index:2;
	border-radius:5px 5px 5px 5px;
}
.ShowMap{
	display:none;
	z-index:2;
	position:fixed;
	_position:absolute; /* hack for internet explorer */
	width:620px;
	height:320px;
	color:#FFF;
	border-radius:5px;
	background-color:#FFF;
	border:1px solid #cecece;
}
#containerpr_cus {
	padding:0px;
	width:100%;
	margin-left:auto;
	margin-right:auto;
}
.con_str {
	width:100%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.con_str th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.con_str td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.con_str tbody tr:hover td {
	background: #c1c1c1;
}
#containerpr_str {
	padding:0px;
	width:100%;
	margin-left:auto;
	margin-right:auto;
}
</style>
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">SR DAILY CUSTOMER CONFIRMATION</div>
<div id="mytableformgr" align="center">
<form action="" method="post" >
<table width="100%" align="center">
 <tr>
  <td>
 <fieldset class="alignment"> <div align="center" id="error"></div>
  <legend><strong>SR Confirmation</strong></legend>
  <table width="100%">
    <tr>
		<td>Date:</td>
		<td><input type="text" readonly onchange="loadCustConfirmationDate(this.value);" class="datepicker" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" name="Date" id="Date" /></td>
	</tr>
	<?php //exit; ?>
    <tr height="28">
    <td>SR Name</td>
    <td>
    <select name="dsrname" id="dsrname" onChange="loadCustConfirmation(this.value);">
    <option value="">---Select---</option>
	<?php while($info = mysql_fetch_assoc($query)){?>
    <option value="<?php echo  $info['id'] ?>"><?php echo  $info['DSRName']; ?></option>
    <?php }?>
    </select>
    </td>
    <td>SR Code</td>
    <td><input type="text" name="cdsrcode" id="cdsrcode" readonly size="20" value="" autocomplete='off' maxlength="15"/></td>
    </tr>
    
    
	<tr height="28">
    <td>Route Desc</td>
    <td><input type="text" name="route_desc" id="route_desc" readonly size="20" value="" maxlength="15"/>
    </td>
    
    <td>Route Code</td>
    <td><input type="text" name="croute_code" id="croute_code" readonly size="20" value="" maxlength="15"/></td>
    
	
	</tr>
	<tr>
	<td  width="120" height="40">Location</td>
    <td><input type="text" name="clocation" id="clocation" readonly size="20" value="" maxlength="15"/>
	<input type="hidden" name="maplocation" id="maplocation" value=""/>	
	</td>
   
  <td colspan="2"><a href="javascript:void(0);" onclick="showmap();">Route Map</a> 
  
  <!-- / <a target="_blank" class="various iframe" href="http://maps.google.com/?output=embed&f=q&source=s_q&hl=en&geocode=&q=London+Eye,+County+Hall,+Westminster+Bridge+Road,+London,+United+Kingdom&hl=lv&ll=51.504155,-0.117749&spn=0.00571,0.016512&sll=56.879635,24.603189&sspn=10.280244,33.815918&vpsrc=6&radius=15000&t=h&z=17">Route Information</a> -->
  
  
  
  </td>   
	</tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>
<div class="clearfix"></div> 
<div class="clearfix"></div> 
 <!----------------------------------------------- Left Table End -------------------------------------->
</form>


<!---- Form End ----->
<div class="clearfix"></div>        

<div id="containerpr_cus">	  
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr id="titleRow">
			<th nowrap="nowrap">Seq. No.</th>
			<th nowrap="nowrap" class="rounded">Cust. Name<img src="../images/sort.png" width="13" height="13" /></th>
			<th nowrap="nowrap">Add Line1</th>
			<th nowrap="nowrap">Add Line2</th>
			<th nowrap="nowrap">LGA</th>
			<th align="right">City</th>
			<th nowrap="nowrap"align="right">Contact Person</th>
			<th nowrap="nowrap" align="right">Contact No.</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td align='center' colspan="8">No Records Found.</td>
			<td style="display:none;" >&nbsp;</td>
			<td style="display:none;" >&nbsp;</td>
			<td style="display:none;" >&nbsp;</td>
			<td style="display:none;" >&nbsp;</td>
			<td style="display:none;" >&nbsp;</td>
			<td style="display:none;" >&nbsp;</td>
			<td style="display:none;" >&nbsp;</td>
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
   	     <div id="errormsgcuscon" style="display:none;"><h3 align="center" class="myaligncuscon"></h3><button id="closebutton_blue">Close</button></div>
		 <div class="clearfix"></div>
		 <span id="printopen" style="display:none;"><input type="button" name="print" value="Print" class="buttons" onclick="printcusconfirm();"></span>		 
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span ><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

</div>
</div>
<div id="backgroundChatPopup"></div>
<?php require_once('../include/footer.php'); ?>