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
<div><h2 align="center">Customer Master</h2></div>
<div id="search" style="margin-right:70px;">
<form action="" method="get" name="master" id="master">
<input type="text" name="Customer_Name" value="<?php echo $_GET['Customer_Name']; ?>" autocomplete='off' placeholder='Search By Name'/>
<input type="submit" name="submit" value="Go" class="buttonsg"/>
</form>  
</div>	
<div id="containerBD">
        <div class="clearfix"></div>
		<?php
		if($_GET['Customer_Name']!='')
		{
	    $qry="SELECT *  FROM `customer` where Customer_Name ='".$_GET['Customer_Name']."'  order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `customer` order by id asc";
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
   		<th class="rounded">Code<img src="../images/sort.png" width="13" height="13" /></th>
		<th>KD Code</th>
		<th>Name</th>
		<th>Address</th>
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
		<td><a href="viewCustomerID.php?customer_id=<?php echo $fetch['customer_id']; ?>" class="link" rel="facebox"><?php echo $fetch['customer_id'];?></a></td>
		<td><?php echo $fetch['KD_Code'];?></td>
		<td><?php echo $fetch['Customer_Name'];?></td>
		<td><?php echo $fetch['AddressLine1'].$fetch['AddressLine2'].$fetch['AddressLine3'];?></td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        </div>
<!--Pagination  -->
 
		<?php 
		if($num_rows > 3){?>     
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