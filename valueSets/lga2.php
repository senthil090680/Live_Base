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
$Effective_date=date("Y-m-d",strtotime($Effective_date));	
echo $sql =("UPDATE lga SET 
        LGA='$lga',
		city_id='$city_id',
		Status='active'
       	WHERE id = '".$_GET['id']."'");
		mysql_query( $sql);
header("location:lga.php?no=2");
}
}
elseif($_POST['submit']=='Save'){
$sel="select * from lga where lga ='$lga' And city_id=$city_id";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$Effective_date=date("Y-m-d",strtotime($Effective_date));		
		$active='active';
		$sql="INSERT INTO `lga`(`LGA`,`state_id`,`Status`)values('$lga','$state_id','$active')";
        mysql_query($sql);
        header("location:lga.php?no=1");
		}
		else {
		header("location:lga.php?no=18");
		}
}

$id=$_GET['id'];
$list=mysql_query("select * from  lga where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$lga = $row['lga'];
	$city_id = $row['city_id'];
	} 

?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">lga</div>
<div id="mytable" align="center">
<form action="#" method="post" id="validation">
<table>
  <tr height="50px">
  <td>City*</td>
     <td>
       <?php 
        include('../include/config.php');
		//echo "select a.*,b.*,a.id as row_id ,b.state_id as state_id from state a,lga b where a.id=b.state_id AND b.id= '$id'";
       $list=mysql_query("select * from city"); 
        // Show records by while loop. 
	// End while loop. 
        ?>
       <select name="city_id">
        <option value="">--- Select ---</option>
		<?php 		
		while($row_list=mysql_fetch_assoc($list)){ 
		$id=$row_list['id'];
		?>
        <option value="<?php echo $row_list['id']; ?>" <? if($row_list['id']==$city_id){ echo "selected"; } ?>><? echo $row_list['state']; ?></option>
        <?php  } ?>
        </select>         
          </td>
           </tr>
       <tr>
    <td class="pclr" width="50">LGA*</td>
    <td><input type="text" name="lga" value="<?php echo $lga; ?>" id="lga" size="15" autocomplete='0ff' maxlength="20"/></td>
     </tr>      
   <tr align="center" height="100px;">
    <td colspan="10"><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="reset" name="reset" id="reset"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='lga.php'"/></td>
      </tr>
</table>
</form>
</div>
<?php include("../include/error.php");?>
<div id="search">
<form action="#" method="get">
<input type="text" name="lga" value="<?php $_GET['lga']; ?>" autocomplete='off' placeholder='Search By lga'/>
<input type="submit" name="submit" class="buttonsg" value="GO"/>
</form>       
</div>  
<div class="mcf"></div>
<div id="container">
      	<?php
		 if($_GET['delID']!='')
		{
        $id = $_GET['delID'];
		$lga_sql="select a.*,b.* from lga as a,lga as b where a.lga_id='$id' AND b.id='$id'";
		$reslga=mysql_query($lga_sql);
		$cnt=mysql_num_rows($reslga);
		if($cnt=='1'){
		//echo "Provice is Already Added to State"; 
		header("location:lga.php?no=21"); 
		}
		else{
    	$query="update lga set Status='inactive' where id='$id'";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
		echo 'Hi';
		header("location:lga.php?no=3");
		}
		}
		?>  
		<?php
		if($_GET['submit']!='')
		{
		$var = @$_GET['lga'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM `lga` where lga like '%".$trimmed."%' AND  Status='active' order by id asc";
		}
		else
		{
		echo $qry="select a.*,b.*,b.Status as StateStatus from state as b,lga as a where a.state_id=b.id AND a.Status='active' order by a.id asc";	 
		//$qry="SELECT * FROM `lga` where Status='active' order by id asc";  
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
        <th>State</th>
		<th class="rounded">lga*<input type="hidden" name="lga_id" value="<?php echo $cnt;?>" ><img src="../images/sort.png" width="13" height="13" /></th>
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
		<td><?php if($fetch['stateStatus']=='inactive'){echo 'Undefined'; }else {echo $fetch['state'];}?></td>
        <td><?php echo $fetch['lga'];?></td>
		<td align="right">
        <a href="lga.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="lga.php?delID=<?php echo $fetch['id'];?>" class="ask"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
    </div>
</div>
<?php include('../include/footer.php'); ?>