<?php
session_start();
ob_start();
include('../include/header.php');
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
include "ps_pagination.php";
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<h3 align="center">Customer Master</h3>
<div>&nbsp;</div>
<div>&nbsp;</div>

		<form action="" method="get" name="master" id="master">
		<div id="searchReport">
		<label class="labelstyle">Customer Code / Name &nbsp;<input type="text" name="Name&Code" value="<?php echo $_GET['Name&Code']; ?>" /></label> &nbsp;
		<label class="labelstyle">From &nbsp;<input type="text" name="dateFrom" value="" class="datepicker" autocomplete="off" /></label>&nbsp; 
		<label class="labelstyle">To &nbsp;<input type="text" name="dateTo" value=""  class="datepicker" autocomplete="off" /></label>
		&nbsp;&nbsp;
		&nbsp;<input type="submit" name="go" value="Go" class="buttons"/></div>
		</form>  
		<?php
		if($_GET['Name&Code']!='')
		{
		$where.="Customer_Name LIKE '%".$_GET['Name&Code']."%' || customer_code LIKE '%".$_GET['Name&Code']."%'";
	    $qry="SELECT *  FROM `customer` where $where  order by id asc";
		}
		else if($_GET['dateFrom']!='' && $_GET['dateTo']!='')
		{
		$from=date('Y-m-d',strtotime($_GET['dateFrom']));
		$to=date('Y-m-d',strtotime($_GET['dateTo']));
		$where.="Date between '".$from."' AND '".$to."' ";
	    $qry="SELECT *  FROM `customer` where $where  order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `customer` order by id asc";
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,10,10);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
		<div id="container">
		<div class="right_content">
        <div class="clearfix"></div>		
		<div  class="contentToPrint">
    	<table id="rounded-corner" class="tablesorter">
		<thead>
   		<tr>
   		<th scope="col" class="rounded">Code<br /><img src="../images/sort.png" width="13" height="13" /></th>
		<th>KD Code</th>
		<th scope="col" class="rounded">Name</th>
		<th scope="col" class="rounded">Address</th>
		<th scope="col" class="rounded">Date</th>
		<th scope="col" class="rounded">View</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		?>
		<tr class="tbg">
		<td><?php echo $fetch['customer_code'];?></td>
		<td><?php echo $fetch['KD_Code'];?></td>
		<td><?php echo $fetch['Customer_Name'];?></td>
		<td><?php echo $fetch['AddressLine1'].$fetch['AddressLine2'].$fetch['AddressLine3'];?></td>
		<td><?php echo date('d-m-Y',strtotime($fetch['Date']));?></td>
		<td><a href="viewCustomerID.php?customer_code=<?php echo $fetch['customer_code']; ?>" class="link" rel="facebox">View</a></td>
  		</tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody></table></div>

		<?php 	if($num_rows > 10){?>
        <div class="paginationfile">
		<table>
		<tr>
		<td colspan="7" class="pagination" scope="col">          
		<?php 
		//Display the link to first page: First
		echo $pager->renderFirst()."&nbsp; ";
		//Display the link to previous page: <<
		echo $pager->renderPrev();
		//Display page links: 1 2 3
		echo $pager->renderNav();
		//Display the link to next page: >>
		echo $pager->renderNext()."&nbsp; ";
		//Display the link to last page: Last
		echo $pager->renderLast();  ?>      
		</td>
		</tr>
		</table>        
   		</div>
   		<?php } else{ echo "&nbsp;"; }?>

	 </div>
<div class="clearfix"></div>   
<!--Error Message -->  
<?php if($_GET['d']=='1'){ ?>
<div id="errormsg"><h3 align="center" class="myalign"><?php echo 'Deleted Successfully' ; ?> </h3></div>
<?php }?>

</div>
<div class="contentSection">
<a href="#" id="printOut"><img src="../images/print_icon.png" width="85" height="24" /></a>
</div>

</div>

<?php include('../include/footer.php'); ?>