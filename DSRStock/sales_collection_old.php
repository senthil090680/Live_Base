<?php
include('../include/header.php');
include "../include/ps_pagination.php";
$query = mysql_query("select DSRName,DSR_code from dsr");
$route = mysql_query("select route_desc from route_master");


?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">DSR SALES & COLLECTIONS</div>
<div id="mytableformgr" align="center">
<form action="" method="post" id="validation">
<table width="100%" align="center">
<tr>
<td>
<fieldset class="alignment">

<legend><strong>DSR Sales & collections </strong></legend>
<table width="100%">
<tr height="40">
<td>Date:</td><td><input type="text" value="<?php echo date('Y-m-d')?>" id="datepicker" /></td>
</tr>

<tr height="40">
<td>DSR Name</td>
<td> 
<select class="sales_dsr" id="sales_dsr" name="sales_dsr">
<option>---Select---</option>
<?php while($info = mysql_fetch_assoc($query)){ ?>
<option value="<?php echo $info['DSRName'] ?>"><?php echo $info['DSRName'] ?></option>
<?php }?>
</select>
</td>
<td>DSR Code</td>
<td><input type="text" name="sdsr_code " size="20" value="" id="sdsr_code" autocomplete='off'  maxlength="15"/></td>
</tr>

<tr height="40">
<td>Vehicle Name</td>
<td><input type="text" name="svehicle" size="20" value="" id="svehicle"  autocomplete='off' maxlength="15"/></td>
<td>Device Code</td>
<td><input type="text" name="sdevice_code" size="20" value="" id="sdevice_code" autocomplete='off' maxlength="15"/></td>
</tr>
</table>
</fieldset>
</td>
</tr>
</table>

<table width="50%" style="clear:both">
<tr align="center" height="50px;">
<td>
<input type="reset" name="reset" class="buttons" value="Clear" id="clear" onclick="return saleclr();"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/></td>
      </tr>
 </table>
<!----------------------------------------------- Left Table End -------------------------------------->
</form>
</div>

<!---- Form End ----->




<div class="mcf"></div>
<div id="containerpr">
<div class="con">
<table id="sort" class="tablesorter" width="100%">
<thead>
<tr>
<th>Currency</th>
<th class="rounded">Total Sales Values<img src="../images/sort.png" width="13" height="13" /></th>
<th>Total Collection Values</th>
</tr>
</tr>
</thead>
<tbody>
<tr id="total"></tr>
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
</div>
<?php include('../include/footer.php'); ?>