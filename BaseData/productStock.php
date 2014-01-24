<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div><h2 align="center">Product Stock</h2></div>
 <div id="search" style="margin-right:70px;">
<form action="" method="get" name="master" id="master">
<input type="text" name="product_code" value="<?php echo $_GET['product_code']; ?>"  autocomplete='off' placeholder='Search By Code'/>
<input type="submit" name="submit" value="Go" class="buttonsg"/>
</form>  
</div>
<div id="containerBD">
        <div class="clearfix"></div>
		<?php
		if($_GET['product_code']!='')
		{
	    $qry="SELECT *  FROM `product` where product_code ='".$_GET['product_code']."'  order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `product` order by id asc";
		}
		$results=mysql_query($qry);
		$num_rows= mysql_num_rows($results);			
		$pager = new PS_Pagination($bd, $qry,10,10);
		$results = $pager->paginate();
		?>
        <div class="con3">
        <table id="sort" class="tablesorter" align="center">
		<thead>
		<tr>
  		<th class="rounded">KD Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Date</th>
		<th>Product Code</th>
		<th>Description</th>
		<th>UOM1</th>
		<th>UOM2</th>
        <th>UOM Conversion</th>
		<th>Stock Quantity UOM1</th>
		<th>Stock Quantity UOM1</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls ="class='odd'"; }
		?>		
		<tr>
		<td><?php echo $fetch['KD_ID'];?></td>
        <td><?php echo $fetch['Date'];?></td>
      	<td><?php echo $fetch['Product_code'];?></td>
		<td><?php echo $fetch['Product_description1'];?></td>
		<td><?php echo $fetch['UOM1'];?></td>
		<td><?php echo $fetch['UOM2'];?></td>
        <td><?php echo $fetch['Uom_conversion'];?></td>
		<td><?php echo $fetch['Stock_Quantity_UOM1'];?></td>
		<td><?php echo $fetch['Stock_Quantity_UOM2'];?></td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
</div>
<!--Pagination  -->
 
		<?php 
		if($num_rows > 10){?>     
        <div class="paginationfile" align="center">
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
		</div>   
		<?php } else{ echo "&nbsp;"; }?>

	</div>   
        
<!--Messages-->
</div>
<?php include('../include/footer.php'); ?>