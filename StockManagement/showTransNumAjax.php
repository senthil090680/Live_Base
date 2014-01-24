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
if(isset($_GET[dateval]) && $_GET[dateval] !='') {
	$dateval= trim($_GET[dateval]);
	$where	= "WHERE Date= $dateval";
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
	<select name="TxnNum" id="TxnNum">
	 <option value="">--Select--</option>
	 <?php $qry="SELECT *  FROM `stock_receipts` GROUP BY Transaction_number order by id asc";
		$results=mysql_query($qry);
		$num_rows= mysql_num_rows($results);			
		while($fetch = mysql_fetch_array($results)) { ?>
	 <option value="<?php echo $fetch[Transaction_number]; ?>"><?php echo $fetch[Transaction_number]; ?></option>
	 <?php } ?>
	 </select>
 <?php exit(0); ?>