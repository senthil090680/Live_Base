<?php
session_start();
ob_start();
//require_once('../include/header.php');
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/
EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
//echo $id;
//exit;
?>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>

<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">STOCK RECEIPTS</h2></div> 

<div id="containerpr">

<span style="float:left;" id="showviewbutton" ><input type="button" name="kdproduct" value="View Stock Receipts" class="buttonsbig" onclick="window.location='StockReceiptsview.php'"></span>

<div style="clear:both"></div>

<div class="clearfix"></div>
        <?php
		if($_POST['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$query = "DELETE FROM `stock_receipts` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:StockReceiptsview.php?no=3");
			}
		 }
		?> 
	    <?php
		if($_GET['submit']!='')
		{
			$var = @$_GET['Product_name'] ;
			$trimmed = trim($var);	
			$qry="SELECT * FROM `stock_receipts` where Product_name like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
			$qry="SELECT *  FROM `stock_receipts` order by id asc"; 
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
		<th align="center">Serial Number</th>
		<th align="center" class="rounded">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th align="center">Product Name</th>
        <th align="center">UOM</th>
		<th align="center">Quantity</th>
       <!--  <th align="right">Edit/Del</th> -->
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
		<td align="center"><?php echo $fetch['line_number'];?></td>
		<td align="center"><?php echo $fetch['Product_code'];?></td>
	    <td align="center"><?php echo $fetch['Product_name'];?></td>
        <td align="center"><?php echo $fetch['UOM'];?></td>
        <td align="center"><?php echo $fetch['quantity'];?></td>
       <!-- 	<td align="right">
       	        <a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
       	        <a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
       	        </td> -->
        </tr>		
		<?php $c++; $cc++; }		 
		}else{  ?> 
		
			<tr>
				<td align='center' colspan='13'><b>No records found</b></td>	
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line2</td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
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
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='StockReceipts.php'"/>
      </form>
     </div>
   </div>
   <div class="mcf"></div>
</div>
<span id="printopen" style="padding-left:650px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>