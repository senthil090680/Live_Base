<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_POST);
$id=$_REQUEST['id'];
?>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>

<style type="text/css">
#containerdailysto {
	padding:0px;
	width:90%;
	margin-left:auto;
	margin-right:auto;
}
</style>

<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">DAILY STOCK LOADING</h2></div> 
<div id="containerdailysto">

<span style="float:left;" id="showviewbutton"><input type="button" name="kdproduct" value="View Daily Stock Loading" class="buttonsbig" onclick="window.location='DailyStockLoadingview.php'"></span>

<div style="clear:both"></div>
        <div class="clearfix"></div>
        <?php
		if($_POST['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$query = "DELETE FROM `dailystockloading` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:DailyStockLoadingview.php?no=3");
			}
		 }
		?> 
	    <?php
		if($_GET['submit']!='')
		{
			$var = @$_GET['Product_code'] ;
			$trimmed = trim($var);	
			$qry="SELECT * FROM `dailystockloading` where Product_code like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
			$qry="SELECT *  FROM `dailystockloading` order by id asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%" border="1">
		<thead>
		<tr>
		<th nowrap="nowrap">Product Name</th>
		<th class="rounded">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th nowrap="nowrap">UOM</th>
		<th nowrap="nowrap">SR</th>
        <th nowrap="nowrap">Loaded Quantity</th>
		<th nowrap="nowrap">Confirmed Quantity</th>
		<th nowrap="nowrap">Focus Flag</th>
		<th nowrap="nowrap">Scheme Flag</th>
		<th nowrap="nowrap">Date</th>
		<th nowrap="nowrap">Product Type</th>
		</tr>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php $sel_prname	=	"SELECT * FROM `product` WHERE Product_code = '$fetch[Product_code]'"; 
			$results_prname=mysql_query($sel_prname);
			$row_prname= mysql_fetch_array($results_prname);
			$prod_desc		=	ucwords(strtolower($row_prname['Product_description1']));
			if($prod_desc != '') {
				echo $prod_desc;
				} else {
					echo "&nbsp;"; 
				}
		?></td>
		<td><?php if($fetch['Product_code'] != '') {
				echo $fetch['Product_code'];
				} else {
					echo "&nbsp;"; 
				} ;?></td>
	    <td><?php if($fetch['UOM'] != '') {
				echo $fetch['UOM'];
				} else {
					echo "&nbsp;"; 
				} ; ?></td>
        <td><?php $sel_prname	=	"SELECT * FROM `product` WHERE Product_code = '$fetch[Product_code]'"; 
			$results_prname=mysql_query($sel_prname);
			$row_prname= mysql_fetch_array($results_prname);
		if($fetch['DSR_Code'] != '') {
				echo ucwords(strtolower($fetch['DSR_Code']));
				} else {
					echo "&nbsp;"; 
				} ?></td>
		<td align="center"><?php if($fetch['Loaded_Qty'] != '') {
				echo $fetch['Loaded_Qty'];
				} else {
					echo "&nbsp;"; 
				} ?></td>
		<td align="center"><?php if($fetch['Confirmed_Qty'] != '') {
				echo $fetch['Confirmed_Qty'];
				} else {
					echo "&nbsp;"; 
				} ?></td>
		<td align="center"><?php if($fetch['focus_Flag'] != '') {
				echo $fetch['focus_Flag'];
				} else {
					echo "&nbsp;"; 
				} ?></td>
        <td align="center"><?php if($fetch['scheme_Flag'] != '') {
				echo $fetch['scheme_Flag'];
				} else {
					echo "&nbsp;"; 
				} ?></td>
		<td><?php if($fetch['Date'] != '') {
				echo $fetch['Date'];
				} else {
					echo "&nbsp;"; 
				} ?></td>
		<td><?php if($fetch['ProductType'] != '') {
				echo $fetch['ProductType'];
				} else {
					echo "&nbsp;"; 
				} ?></td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  ?>
			<tr>
				<td align='center' colspan='13'><b>No records found</b></td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line2</td>
				<td style="display:none;" >LGA</td>
				<td style="display:none;" >City</th>
				<td style="display:none;" >Contact Person</td>
				<td style="display:none;" >Contact Number</td>
			</tr>
		<?php }  ?>
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
     <div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='DailyStockLoadingview.php'"/>
      </form>
     </div>
	 <span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
   </div>
 <?php require_once('../include/error.php'); ?>
</div>