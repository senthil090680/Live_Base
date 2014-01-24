<?php
session_start();
ob_start();
include('../include/header.php');
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
$param=mysql_query("select * from  parameters where id = 1"); 
$row=mysql_fetch_array($param);

$time_now=mktime(date('g')+4,date('i')-30,date('s'));
$time = date('H:i:s',$time_now); 

?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareasystemParam">
<div class="mcf"></div>
<div align="center" class="headingsparam">Setup Parameters</div>
<div class="mytableformsetup" align="center">
<div class="mcf"></div>
<form method="post" action="setupParamAction.php">
<div class="innerforml">
 <fieldset class="alignmentparam"><legend><strong>Format</strong></legend>
    <table width="100%">
    <tr height="30">
    <td>Date Format *</td>
    <td id="displaydateformat"> 
    <select name="displaydateformat" >
    <option value="DDMMYY" <?php if($row['displaydateformat']=='DDMMYY'){ echo 'selected';}?>>DD-MM-YYYY</option>
    <!--<option value="y-m-d" <?php if($row['displaydateformat']=='y-m-d'){ echo 'selected';}?>>DD-MM-YYYY</option>-->
    </select>
    </td>
    </tr>
    
    <tr height="30">
    <td>Currency *</td>
    <td>
    <select name="currency">
  <!--  <option value="">--- Select ---</option>-->
    <option value="Naira" <?php if($row['currency']=='Naira'){ echo 'selected' ; }?>>Naira</option>
    </select>&nbsp;
    <img src="../images/currency.gif" width="13" height="13" />
    </td>
    </tr>
    
    <tr>
    <td>Issue Number</td>
    <td> <input type="issue_no" name="issue_no" value="<?php echo $row['issue_no']; ?>"  autocomplete='off'  readonly="readonly" size="10"/> 
    </td>
    </tr>
    <tr></tr>
    <tr>
    <td>Data Sync Freq*</td>
    <td>
    <select name="Data_sync_freq">
    <option value="">--- Select ---</option>
    <option value="0" <?php if($row['Data_sync_freq']=='0'){ echo 'selected'; }?>>0 Hours</option>
    <option value="1" <?php if($row['Data_sync_freq']=='1'){ echo 'selected'; }?>>1 Hours</option>
    <option value="2" <?php if($row['Data_sync_freq']=='2'){ echo 'selected'; }?>>2 Hours</option>
    <option value="4" <?php if($row['Data_sync_freq']=='4'){ echo 'selected'; }?>>4 Hours</option>
    </select>
    </td>

    </tr>

</table>
	</fieldset>
</div>
<div class="innerformr">
 <fieldset class="alignmentparam">
  <legend><strong>Device Data Transfer to Base</strong></legend>
  
   <table width="100%">
    <tr>
    <td>GPS Data Transfer*</td>
    <td>
    <select name="Data_Transfer" id="Data_Transfer">
    <option value="1" <?php if($row['Data_Transfer']=='1'){ echo 'selected';}?> >Yes</option>
    <option value="0" <?php if($row['Data_Transfer']=='0'){ echo 'selected';}?>>No</option>
    </select>
    </td> 
    </tr>
    <tr height="30">
    <td>GPS Data Transfer Frequency*</td>
    <td> 
    <select name="Transfer_Frequency"  id="textbox1">
    <option value="">--- Select ---</option>
    <option value="5" <?php if($row['Transfer_Frequency']=='5'){ echo 'selected'; }?>>5min</option>
    <option value="10" <?php if($row['Transfer_Frequency']=='10'){ echo 'selected'; }?>>10min</option>
    <option value="15" <?php if($row['Transfer_Frequency']=='15'){ echo 'selected'; }?>>15min</option>
    </select>
    </td>
    </tr>
    
    <tr height="30">
    <td>Start Time *</td>
    <td><input type="text" name="Start_time" value="<?php echo $row['Start_time']; ?>"  size="10" id="start"/> </td>
    </tr>
    
    <tr height="30">
    <td>End Time *</td>
    <td><input type="text" name="End_time" value="<?php echo $row['End_time']; ?>"  size="10" id="end"/> </td>
    </tr>
 </table>
	</fieldset>
</div>
<div style="clear:both"></div>
    <fieldset class="alignmentparfs"><legend><strong>System Flags</strong></legend>
	<table width="100%">
	<tr height="28">
    <td class="align" width="200">Batch Control*</td>
    <td>
    <select name="batchctrl" id="batchctrl">
    <option value="">--- Select ---</option>
    <option value="OFF" <?php if($row['batchctrl']=='OFF'){ echo 'selected' ; }?> >OFF</option>
    <option value="ON-ALL" <?php if($row['batchctrl']=='ON-ALL'){ echo 'selected' ; }?>>ON-ALL</option>
    <option value="ON-SELECT" <?php if($row['batchctrl']=='ON-SELECT'){ echo 'selected' ; }?>>ON-SELECT</option>
    </select>
    </td>
    
    <td class="align" width="200">Transaction Reprint*</td>
    <td>
    <select name="Trans_Reprint" id="Trans_Reprint">
    <option value="">--- Select ---</option>
    <option value="1" <?php if($row['Trans_Reprint']=='1'){ echo 'selected'; }?> >Yes</option>
    <option value="0" <?php if($row['Trans_Reprint']=='0'){ echo 'selected'; }?>>No</option>
    </select>
    </td> 
    </tr>

    <tr height="28">
    <td class="align" width="200">Permit Return*</td>
    <td>
    <select name="Permit_Return" id="permit_return">
    <option value="">--- Select ---</option>
    <option value="1" <?php if($row['Permit_Return']=='1'){ echo 'selected' ; }?> >Yes</option>
    <option value="0" <?php if($row['Permit_Return']=='0'){ echo 'selected' ; }?>>No</option>
    </select>
    </td>
    <td class="align" width="200">Transaction Copies*</td>
    <td>
    <select name="Tran_copies" id="Tran_copies">
        <option value="">--- Select ---</option>
        <option value="1" <?php if($row['Tran_copies']=='1'){ echo 'selected' ; }?>>1</option>
        <option value="2" <?php if($row['Tran_copies']=='2'){ echo 'selected' ; }?>>2</option>
        <option value="3" <?php if($row['Tran_copies']=='3'){ echo 'selected' ; }?>>3</option>
        <option value="4" <?php if($row['Tran_copies']=='4'){ echo 'selected' ; }?>>4</option>
        <option value="5" <?php if($row['Tran_copies']=='5'){ echo 'selected' ; }?>>5</option>
        <option value="6" <?php if($row['Tran_copies']=='6'){ echo 'selected' ; }?>>6</option>
        <option value="7" <?php if($row['Tran_copies']=='7'){ echo 'selected' ; }?>>7</option>
        <option value="8" <?php if($row['Tran_copies']=='8'){ echo 'selected' ; }?>>8</option>
        <option value="9" <?php if($row['Tran_copies']=='9'){ echo 'selected' ; }?>>9</option>
      </select>
    </td>
    

    </tr>
      
    <tr height="28">   
    <td class="align" width="200">Focus Item Stock*</td>
    <td>
    <select name="Focus_item_stock" id="Focus_item_stock">
    <option value="">--- Select ---</option>
    <option value="1" <?php if($row['Focus_item_stock']=='1'){ echo 'selected' ; }?> >Compel</option>
    <option value="0" <?php if($row['Focus_item_stock']=='0'){ echo 'selected' ; }?>>Optional</option>
    </select>
    </td>
    
    <td class="align" width="200">Customer Sign*</td>
    <td>
    <select name="Customer_Sign" id="Customer_Sign">
    <option value="">--- Select ---</option>
    <option value="1" <?php if($row['Customer_Sign']=='1'){ echo 'selected' ; }?> >Compel</option>
    <option value="0" <?php if($row['Customer_Sign']=='0'){ echo 'selected' ; }?>>Optional</option>
    </select>
    </td>
    </tr>
    
    
    </table>
    </fieldset>

<table width="50%" style="clear:both">
<tr align="center" height="50">
<td>
<input type="submit" name="submit" id="submit" class="buttons" value="Save" />
<input type="button" name="clear" value="Clear" id="clear" class="buttons" onClick="return systemParam();" />
<a href="../include/menu.php" style="text-decoration:none"><input type="button" name="cancel" id="cancel"  class="buttons" value="Cancel"/></a>
</td>
</tr>
</table>
</form>
<div class="mcf"></div>
<?php include("../include/error.php");?> 
</div>
</div>
<?php include("../include/footer.php");?>
