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
$sel="select * from feedback_type where feedback_type ='$feedback_type'";
$sel_query=mysql_query($sel);
if(mysql_num_rows($sel_query)=='0') {
$sql =("UPDATE feedback_type SET 
        feedback_type='$feedback_type'
       	WHERE id =$id");
mysql_query( $sql);
header("location:feedbackType.php?no=2");
}
else{
header("location:feedbackType.php?no=18");
}
}
}
elseif($_POST['submit']=='Save'){
if($feedback_type=='')
{
header("location:feedbackType.php?no=9");exit;
}
else{
$sel="select * from feedback_type where feedback_type ='$feedback_type'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$sql="INSERT INTO `feedback_type`(`feedback_type`)values('$feedback_type')";
        mysql_query( $sql);
        header("location:feedbackType.php?no=1");
		}
		else {
		header("location:feedbackType.php?no=18");
		}
}

}

$id=$_GET['id'];
$list=mysql_query("select * from feedback_type where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$feedback_type = $row['feedback_type'];
	} 

?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">Feedback Type</div>
<div class="mytable" align="center">
<form action="#" method="post" id="validation">
<table>
  <tr height="60px;">
   <td class="pclr" width="150">Feedback Type*</td>
   <td><input type="text" name="feedback_type" value="<?php echo $feedback_type; ?>" maxlength="20" autocomplete='off'/></td>
   </tr>
   
   <tr align="center" height="70px;">
       <td colspan="10">
        <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="reset" name="reset" id="reset"  class="buttons" value="Clear" id="clear" onclick="return feedClear();"/>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/>
      </tr>
</table>
</form>
</div>
<?php include("../include/error.php");?>
    <div id="search">
    <form action="" method="get">
    <input type="text" name="feedback_type" value="<?php $_GET['feedback_type']; ?>" autocomplete='off' placeholder='Search By CompType'/>
    <input type="submit" name="submit" class="buttonsg" value="GO"/>
    </form>       
    </div>
<div class="mcf"></div>
<div id="container">
	   	<?php
        if($_POST['submit']=='ConfirmDelete'){
	    $id = $_GET['delID'];
	    $query = "DELETE FROM feedback_type WHERE id = $id";
		mysql_query($query) or die(mysql_error());
	    header("location:feedbackType.php?no=3");
		}
		?>  
        <?php
		if($_GET['submit']!='')
		{
		$var = @$_GET['feedback_type'] ;
        $trimmed = trim($var);		
	    $qry="SELECT * FROM `feedback_type` where feedback_type like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
		$qry="SELECT * FROM `feedback_type` order by id asc";  
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con2">
        <table id="sort" class="tablesorter" align="center" width="100%">
		<thead>
		<tr>
		<th class="rounded">Feedback Type<img src="../images/sort.png" width="13" height="13" /></th>
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
		<td><?php echo $fetch['feedback_type'];?></td>
		<td align="right">
        <a href="feedbackType.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="feedbackType.php?id=<?php echo $fetch['id'];?>&delID=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
		<th class="pagination">          
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
      <div class="msg" align="center" <?php if($_GET['delID']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
       <form action="" method="post">
       <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
       <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='feedbackType.php'"/>
        </form>
     </div>      
   </div>
</div>
<?php include('../include/footer.php'); ?>