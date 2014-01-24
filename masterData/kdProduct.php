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
<div align="center"><h2>KD Product</h2></div>
 <div id="search">
        <form action="" method="get">
        <input type="text" name="kd_category" value="<?php $_GET['kd_category']; ?>" autocomplete='off' placeholder='Search By KD Cate'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>
<div class="mcf"></div>        
<div id="containerpr">
	   	<?php
		if($_GET['submit']!='')
		{
		$var = @$_GET['KD_Name'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM ` kd_product` where kd_category	 like '%".$trimmed."%' order by kd_category	 asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `kd_product` order by kd_category	asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,10,10);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th class="rounded">KD Category<img src="../images/sort.png" width="13" height="13" /></th>
		<th>UOM1</th>
        <th>Product Code</th>
        <th>Product Description</th>
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
      	<td><?php echo $fetch['kd_category'];?></td>
	    <td><?php echo $fetch['UOM1'];?></td>
        <td><?php echo $fetch['Product_code'];?></td>
        <td><?php echo $fetch['Product_description1'];?></td>
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
   </div>
</div>
<?php include('../include/footer.php'); ?>