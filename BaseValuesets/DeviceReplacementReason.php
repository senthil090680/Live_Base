<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
EXTRACT($_POST);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
$sql =("UPDATE category_1 SET 
       category1= '$category1'
       WHERE id = $id");
mysql_query( $sql);
header("location:customerCategory1.php?no=2");
}
}
if($_POST['submit']=='Save'){
$sel="select * from category_1 where category1 ='$category1'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$Effective_date=date("Y-m-d",strtotime($Effective_date));		
		$active='active';
		$sql="INSERT INTO `category_1`(`category1`,`Status`)values('$category1','$active')";
        mysql_query( $sql);
        header("location:customerCategory1.php?no=1");
		}
		else {
		header("location:customerCategory1.php?no=18");
	}
}

$id=$_GET['id'];
$list=mysql_query("select * from category_1 where id= '$id'"); 
//while($row = mysql_fetch_array($list)){ 
	$category1 = $row['category1'];
	//} 


?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">Device Replacement Reason</div>
<div id="mytable" align="center">
<form action="#" method="post" id="validation">
<table>
  <tr height="60px">
    <td class="pclr">Device Replace Reason*</td>
    <td><input type="text" name="category1" value="<?php echo $category1; ?>" class="required" autocomplete='off' maxlength="20"/></td>
   </tr>
 
<tr height="130px;" align="center">
<td colspan="10" >
<input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="reset" name="reset" id="reset"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='customerCategory1.php'"/>
</td>
     </tr>
</table>
</form>
</div>
<?php include("../include/error.php");?>
<div id="search">
<form action="#" method="get">
<input type="text" name="category1" value="<?php $_GET['category1']; ?>" autocomplete='off' placeholder='Search By Cate1'/>
<input type="submit" name="submit" class="buttonsg" value="GO"/>
</form>       
</div>
<div class="mcf"></div>
<div id="container">
	   	<?php
        if($_GET['no']=='3'){
        $id = $_GET['id'];
        //Set the query to return names of all employees
       	$query="update category_1 set Status='inactive' where id='$id'";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
       	 }
		?>   
       
		<?php
		if($_GET['submit']!=='')
		{
		$var = @$_GET['category1'] ;
        $trimmed = trim($var);		
	    $qry="SELECT * FROM `category_1` where category1 like '%".$trimmed."%' AND  Status='active' order by id asc";
		}
		else
		{ 
		$qry="SELECT * FROM `category_1` where Status='active' order by id asc";  
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		//$num_rows= mysql_num_rows($results);			
		?>
        <div class="con2">
        <table id="sort" class="tablesorter" align="center" width="100%">
        <thead>
		<tr>
		<th class="rounded">Device Replacement Reason<img src="../images/sort.png" width="13" height="13" /></th>
		<th align="right">Mod/Del</th>
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
		<td><?php echo $fetch['category1'];?></td>
		<td align="right">
        <a href="customerCategory1.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="customerCategory1.php?id=<?php echo $fetch['id'];?>&no=3" class="ask"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>
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