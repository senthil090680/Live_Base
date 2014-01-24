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
<div><h2 align="center">DSR Master</h2></div>
    <div id="search" style="margin-right:70px;">
    <form action="" method="get" name="master" id="master">
    <input type="text" name="Salesrep_name" value="<?php echo $_GET['Salesrep_name']; ?>" autocomplete='off' placeholder='Search By Name'/>
    <input type="submit" name="submit" value="Go" class="buttonsg"/>
    </form>  
    </div>
<div id="containerBD">
        <div class="clearfix"></div>
		<?php
		if($_GET['Salesrep_name']!='')
		{
	    $qry="SELECT *  FROM `dsr` where Salesrep_name='".$_GET['Salesrep_name']."'  order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `dsr` order by id asc";
		}	
		$results=mysql_query($qry);
		$num_rows= mysql_num_rows($results);			
		$pager = new PS_Pagination($bd, $qry,15,15);
		$results = $pager->paginate();
		?>
        <div class="con3">
        <table id="sort" class="tablesorter" align="center" width="100%">
		<thead>
		<tr>
  		<th class="rounded">Code<img src="../images/sort.png" width="13" height="13" /></th>
		<th>KD Code</th>
   		<th>SalesRepresentative Name</th>
		<th>Contact Number</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Code</th>
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
		<td><?php echo $fetch['Dsr_id'];?></td>
		<td><?php echo $fetch['KD_Code'];?></td>
		<td><?php echo $fetch['Salesrep_name'];?></td>
		<td><?php echo $fetch['Salesrep_contact_num'];?></td>
		<td><?php echo $fetch['Salesrep_addr_line1'].$fetch['Salesrep_addr_line2'].$fetch['Salesrep_addr_line3'];?></td>
		<td><?php echo $fetch['City'];?></td>
		<td><?php echo $fetch['State'];?></td>
		<td><?php echo $fetch['Salesperson_id'];?></td>
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