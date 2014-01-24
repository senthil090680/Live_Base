<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
extract($_GET);
if(isset($_GET[val]) && $_GET[val] !='') {
	$TxNum= trim($_GET[val]);
	$where	= "WHERE Transaction_Number = $TxNum";
}
if(isset($_GET[val]) && $_GET[val] !='') {
	$TxNum= trim($var);
	$where	= "WHERE Transaction_Number = $TxNum";
}
	if(isset($_GET) && $_GET !='')
	{
	$qry="SELECT * FROM `stock_receipts` $where order by id asc";
	}
	else
	{ 
	$qry="SELECT *  FROM `stock_receipts` order by id asc"; 
	}
	$results=mysql_query($qry);
	$pager = new PS_Pagination($bd, $qry,5,5);
	$results = $pager->paginate();
	$num_rows= mysql_num_rows($results) or die(mysql_error());			
	?>
	<div class="con">
	<table id="sort" class="tablesorter" width="100%">
	<thead>
	<tr>
	<th>KD Code</th>
	<th class="rounded">Transaction Number</th>
	<th>Line Number</th>
	<th>Product Code</th>
	<th>UOM</th>
	<th>Quantity</th>
	<!--<th align="right">Mod/Del</th>-->
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
	<td><?php echo $fetch['KD_Code'];?></td>
	<td><?php echo $fetch['Transaction_number'];?></td>
	<td><?php echo $fetch['line_number'];?></td>
	<td><?php echo $fetch['Product_code'];?></td>
	<td><?php echo $fetch['UOM'];?></td>
	<td><?php echo $fetch['quantity'];?></td>
	<!--<td align="right">
	<a href="../BaseMaster/dsr.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="../BaseMaster/dsr.php?id=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
	</td>-->
	</tr>
	<?php $c++; $cc++; }		 
	}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
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
 <div class="msg" align="center" <?php if($_GET['id']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
 <form action="" method="post">
 <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='dsr.php'"/>
  </form>
 </div>
 <?php exit(0); ?>