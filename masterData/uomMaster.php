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
<div align="center"><h2>UOM</h2></div>
<!--   <div id="search">
    <form action="#" method="get" autocomplete=off>
    <input type="text" name="UOM_code" value="<?php $_GET['UOM_code']; ?>"/>
    <input type="submit" name="submit" class="buttonsg" value=""/>
    </form>       
    </div> -->
<div class="mcf"></div>    
<div id="container">
	    <?php
		if($_GET['submit']=="")
		{
		$var = @$_GET['UOM_code'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM `uom` where UOM_code like '%".$trimmed."%' order by UOM_code asc";
		}
		else
		{ 
		$qry="SELECT * FROM `uom` order by UOM_code asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con2">
        <table id="sort" class="tablesorter" align="center">
     	<thead>
		<tr>
		<th class="rounded">UOM Code<img src="../images/sort.png" width="13" height="13" /></th>
		<th>UOM Description</th>
      	</tr>
		</tr>
		</thead>
        <tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls ="class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php echo $fetch['UOM_code'];?></td>
	    <td><?php echo $fetch['UOM_description'];?></td>
       	</tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        </div>
<!--Pagination  -->      
        <div class="paginationfile" align="center">
        <table>
     	<tr>
		<th class="pagination">  
	  		</tr>
        </table> 
        <br />
       <input type="button" name="close" class="buttons" value="Close" onclick="window.location='../include/empty.php'"/>      
		</div>   
	</div>       
</div>
<?php include('../include/footer.php'); ?>