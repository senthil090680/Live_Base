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
$sql =("UPDATE check_out_reason SET 
       reason= '$reason'
       WHERE id = $id");
mysql_query( $sql);

header("location:checkoutReason.php?no=2");

   }
}

elseif($_POST['submit']=='Save'){
if($reason=='')
{
header("location:checkoutReason.php?no=9");exit;
}
else{
$sel="select * from check_out_reason where reason ='$reason'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$sql="INSERT INTO `check_out_reason`(`reason`)values('$reason')";
        mysql_query( $sql);
        header("location:checkoutReason.php?no=1");
		}
		else {
		header("location:checkoutReason.php?no=18");
	}
}
}

$id=$_GET['id'];
$list=mysql_query("select * from check_out_reason where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$reason = $row['reason'];
	} 



?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">Checkout Reasons</div>
<div id="mytable" align="center">
<form method="post">
<table>
  <tr height="60px">
    <td class="pclr">Checkout Reasons*</td>
    <td><input type="text" name="reason" value="<?php echo $reason; ?>" autocomplete='off' maxlength="25"/></td>
   </tr>
 
<tr height="130px;" align="center">
<td colspan="10" >
<input type="submit" name="submit" id="submit" class="buttons basic" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="reset" name="reset" id="reset"  class="buttons" value="Clear" onclick="window.location='checkoutReason.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
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
<input type="text" name="reason" value="<?php $_GET['reason']; ?>" autocomplete='off' placeholder='Search By Reasons'/>
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
		$query = "DELETE FROM check_out_reason WHERE id = '$id'";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:checkoutReason.php?no=3");
		}
		 }
		//Search Record
		if($_GET['submit']!=='')
		{
		$var = @$_GET['reason'] ;
        $trimmed = trim($var);		
	    $qry="SELECT * FROM `check_out_reason` where reason like '%".$trimmed."%' order by reason asc";
		}
		else
		{ 
		$qry="SELECT * FROM `check_out_reason` order by reason asc";  
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
		<th class="rounded">Checkout Reasons<img src="../images/sort.png" width="13" height="13" /></th>
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
		<td><?php echo $fetch['reason'];?></td>
		<!--<td align="right">
        <a href="checkoutReason.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="checkoutReason.php?id=<?php echo $fetch['id'];?>&delID=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
    <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='checkoutReason.php'"/>
    </form>
    </div>          
   </div>
</div>
<?php include('../include/footer.php'); ?>