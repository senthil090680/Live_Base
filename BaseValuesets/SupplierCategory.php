<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
if($_GET['no']==''||$_GET['no']=='3'||$_GET['no']=='9')
{
unset($_COOKIE['supplier_category']);
}


EXTRACT($_POST);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
$sql =("UPDATE supplier_category SET 
       supplier_category= '$supplier_category'
       WHERE id = $id");
mysql_query( $sql);

header("location:SupplierCategory.php?no=2");

   }
}

elseif($_POST['submit']=='Save'){
if($supplier_category=='')
{
header("location:SupplierCategory.php?no=9");exit;
}
else{
$sel="select * from supplier_category where supplier_category ='$supplier_category'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$sql="INSERT INTO `supplier_category`(`supplier_category`)values('$supplier_category')";
        mysql_query( $sql);
        header("location:SupplierCategory.php?no=1");
		}
		else {
		header("location:SupplierCategory.php?no=18");
	}
}
}

$id=$_GET['id'];
$list=mysql_query("select * from supplier_category where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$supplier_category = $row['supplier_category'];
	} 



?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">Stock Inward</div>
<div id="mytable" align="center">
<form method="post">
<table>
  <tr height="60px">
    <td class="pclr">Stock Inward*</td>
    <td><input type="text" name="supplier_category" value="<?php echo $supplier_category; ?>" autocomplete='off' maxlength="20"/></td>
   </tr>
 
<tr height="130px;" align="center">
<td colspan="10" >
<input type="submit" name="submit" id="submit" class="buttons basic" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="reset" name="reset" id="reset"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>
</td>
     </tr>
</table>
</form>
</div>

<div id="error">
<?php include("../include/error.php");?>
</div>

<div id="search">
<form method="get">
<input type="text" name="supplier_category" value="<?php $_GET['supplier_category']; ?>" autocomplete='off' placeholder='Search By StockInward'/>
<input type="submit" name="submit" class="buttonsg" value="GO"/>
</form>       
</div>
<div class="mcf"></div>
<div id="container">
		<?php
		//Delete Record
		if($_GET['delID']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['delID'];
		$query = "DELETE FROM supplier_category WHERE id = $id";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:SupplierCategory.php?no=3");
		}
		 }
		//Search Record
		if($_GET['submit']!=='')
		{
		$var = @$_GET['supplier_category'] ;
        $trimmed = trim($var);		
	    $qry="SELECT * FROM `supplier_category` where supplier_category like '%".$trimmed."%' order by supplier_category asc";
		}
		else
		{ 
		$qry="SELECT * FROM `supplier_category` order by supplier_category asc";  
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$result = $pager->paginate();
		$num_rows= mysql_num_rows($result);			
		?>
        <div class="con2">
        <table id="sort" class="tablesorter" align="center" width="100%">
        <thead>
		<tr>
		<th class="rounded">Stock Inward<img src="../images/sort.png" width="13" height="13" /></th>
		<!--<th align="right">Edit/Del</th>-->
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
		<td><?php echo $fetch['supplier_category'];?></td>
		<!--<td align="right">
        <a href="SupplierCategory.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="SupplierCategory.php?id=<?php echo $fetch['id'];?>&delID=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>-->
        </tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        </div>
  <!--  <div class="paginationfile" align="center">
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
        </div> -->
    <div class="msg" align="center" <?php if($_GET['delID']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
    <form action="#" method="post">
    <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='SupplierCategory.php'"/>
    </form>
    </div>          
   </div>
</div>
<?php include('../include/footer.php'); ?>