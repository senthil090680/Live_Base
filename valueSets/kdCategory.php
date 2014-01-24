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
<div align="center"><h2>KD Category</h2></div>
<div id="search">
<form action="" method="get">
<input type="text" name="kd_category" value="<?php $_GET['kd_category']; ?>" autocomplete='off' placeholder='Search By Cate1'/>
<input type="submit" name="submit" class="buttonsg" value="GO"/>
</form>       
</div>
<div class="mcf"></div>
<div id="container">
      	<?php
		if($_GET['submit']!=='')
		{
		$var = @$_GET['kd_category'] ;
        $trimmed = trim($var);		
	    $qry="SELECT * FROM `kd_category` where kd_category like '%".$trimmed."%' order by kd_category asc";
		}
		else
		{ 
		$qry="SELECT * FROM `kd_category` order by kd_category asc";  
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,10,10);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con2">
        <table id="sort" class="tablesorter" align="center" width="100%">
        <thead>
		<tr>
		<th class="rounded">KD Category<img src="../images/sort.png" width="13" height="13" /></th>
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
		</tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        </div>
        <div class="paginationfile" align="center">
        <table>
		<tr>
		<th  class="pagination">          
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