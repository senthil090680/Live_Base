<?php
include('../include/header.php');
include "../include/ps_pagination.php";
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareametrics">
<div class="mcf"></div>
<div align="center" class="headingsgr">DSR DAY METRICS</div>
<div id="mytableformmetric" align="center">
<form action="" method="post" id="validation">
<table width="100%" align="center">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>DSR-Day Metrics</strong></legend>
  <table width="100%">
  <tr>
    <td width="200">Date*:</td><td><input type="text" id="datepicker" name="DSRDate" id="DSRDate" value="<?php echo date('Y-m-d'); ?>" readonly onChange="loadDSRMetricsDate(this.value);" /></td>
	<td width="150" style="padding-left:30px;">Currency</td>
	<?php
	$query_currency			=	mysql_query("select currency from parameters");
	$info_currency			=	mysql_fetch_assoc($query_currency); ?>
	<td><input type="text" name="currency" id="currency" readonly size="20" value="<?php echo $info_currency[currency]; ?>"  class="required" maxlength="15"/></td>
	</tr>
    
	<tr  height="40">
    <td  width="120">DSR NAME*</td>
    <td><select class="day_dsr" name="DSR_Code" id="DSR_Code" onChange="loadDSRMetrics(this.value);">
	<option value="">--Select--</option>
	<?php
	$query_dsr_code			=	mysql_query("select DSRName,DSR_code from dsr GROUP BY DSRName");
	while($info_dsr_code	=	mysql_fetch_assoc($query_dsr_code)){ ?>
	 <option value="<?php echo  $info_dsr_code['DSR_code'] ?>"><?php echo  $info_dsr_code['DSRName'] ?></option>
	<?php }	?>
       </select></td>
      <td  width="200" height="40" style="padding-left:30px;">DSR CODE</td>
    <td style="padding-right:30px;"><input type="text" name="dsrcode" id="dsrcode" size="20" value="" readonly class="required" maxlength="15"/></td>
    </tr>
		<tr>
		<td><span id="showmetrics"> <!-- Ajax part starts here -->
 <tr><td width="50%" colspan="2"><fieldset class="alignment1">
  <legend><strong>Visit</strong></legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="220">Target Visit:</td>
	<td width="160" align="center"><span id="targetvisit">Nil</span></td>
   </tr>
   <tr>
    <td width="250">Total Visits:</td>
	<td width="270" align="center"><span id="totalvisit">Nil</span></td>
   </tr>
   <tr>
	<td>%coverage:</td>
	<td align="center"><span id="percoverage">Nil</span></td>
   </tr>
  </table>           
  </fieldset>
 </td>
 <td colspan="2"><fieldset class="alignment1">
<legend><strong>Productivity</strong></legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td height="30" width="50%">Total Sales Invoice:</td>
	<td width="50%" align="center"><span id="totalsalesinvoice">Nil</span></td>
  </tr>
  <tr rowspan="2">
	<td height="35" width="50%">Productivity Coverage:</td>
	<td width="50%" align="center"><span id="procoverage">Nil</span></td>
  </tr>
  </table>
 </fieldset>
 </td> 
</tr>

<tr>
    <td width="50%" colspan="2"><fieldset class="alignment1">
  <legend><strong>Effective Coverage</strong></legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0"> 	
    <tr>
		<td width="340" >Total Line Items:</td>
		<td width="435" align="center"><span id="totallineitems">Nil</span></td>
	</tr>
    <tr>
		<td width="270">Basket Size:</td>
		<td width="270" align="center"><span id="basketsize">Nil</span></td>
	</tr>
    <tr>
		<td>Effective SKU Coverage:</td>
		<td align="center"><span id="effskucover">Nil</span></td>
    </tr>
 </table>
 </fieldset>
 </td>
<td colspan="2"><fieldset class="alignment1">
  <legend><strong>Focus Item Coverage</strong></legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  	<td width="0" nowrap="nowrap">Total Focus items in invoice line items:</td>
	<td width="270" align="left"><span id="focuslineitems">Nil</span></td>
  </tr>
  <tr>
	<td width="270" nowrap="nowrap">Total Focus item in stock:</td>
	<td width="270" align="left"><span id="focusitemsstock">Nil</span></td>	
  </tr>
  <tr>
	<td nowrap="nowrap">Focus Coverage:</td>
	<td align="left"><span id="focuscover">Nil</span></td>
  </tr>
 </table>
  </fieldset></td>
</tr>    

<tr><td width="50%" colspan="2"><fieldset class="alignment1">
  <legend><strong>Focus Item Zero Stock Coverage</strong></legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	  <td nowrap="nowrap" width="0">Total Focus Items with Zero Stock Sold:</td>
	  <td align="left" width="340"><span id="totalfocusitemssold">Nil</span></td>
  </tr>
  <tr>
	  <td nowrap="nowrap" width="270">Total Focus Items with Zero Stock:</td>
	  <td align="left" width="270"><span id="totalfocusitemsstock">Nil</span></td>
  <tr>
	  <td nowrap="nowrap">Zero Stock Coverage:</td>
      <td align="left"><span id="zerostock">Nil</span></td>
  </tr>
</table>
</fieldset>
  </td>
  
<td colspan="2"><fieldset class="alignment1">
  <legend><strong>Drop Size</strong></legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td height="30" width="270">Total Sale Value</td>
	<td align="center" width="300"><span id="totalsalevalue">Nil</span></td>
  </tr>
  <tr>
	<td height="35" width="270">Drop Size</td>
	<td align="center" width="270"><span id="dropsize">Nil</span></td>
  </tr>
  </table>
  </fieldset>
  </td>
  
  </tr>

   </table>
 </fieldset>
   </span> <!-- AJAX PART ENDS HERE -->
   </td>
 </tr>
</table>
</form>
<!--<div id="errormsgdev" style="display:none;"><h3 align="center" class="myaligndev"></h3><button id="closebutton">Close</button></div>-->
    <div id="errormsgmetrics" ><h3 align="center" class="myalignmetrics"></h3><button id="closebutton_blue">Close</button></div>
</div>
 <!----------------------------------------------- Left Table End --------------------------------------><!---- Form End ----->
<?php include('../include/footer.php'); ?>