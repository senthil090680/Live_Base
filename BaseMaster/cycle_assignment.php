<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
$query = mysql_query("select DSRName,DSR_code from dsr");
$sql = mysql_query("select device_description from device_master");
$route=mysql_query("select route_desc from route_master");
$vehicle=mysql_query("select vehicle_desc from vehicle_master");




?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Cycle Start Assignment</div>
<div id="mytableformgr" align="center">
<form action="" method="post" id="validation">
<table width="100%" align="center">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Cycle Start Assignment</strong></legend>
  <table width="100%">
  <tr height="40">
    <td width="120" class="pclr">Date*</td>
    <td><?php $time_now=mktime(date('g')+4,date('i')-30,date('s')); $time = date('d-M-Y /g:i',$time_now); echo $time; ?></td>
    <td width="120">Cycle Start Flag</td>
     <td></td>
    </tr>
    
    <tr  height="40">
    <td  width="120">DSR NAME</td>
    <td>   <select class="dsrname"  name="dsrname">
	<?php
	while($info = mysql_fetch_assoc($query)){
		?>
 <option value="<?php echo  $info['DSRName'] ?>"><?php echo  $info['DSRName'] ?></option>
<?php
}
	
	?>
        
       
        </select></td>
      <td  width="120">DSR CODE</td>
    <td><input type="text" name="Address_Line_1" size="20" value="" id="dsrcode" class="required" maxlength="15"/></td>
    </tr>
	<tr height="40"><td  width="120">DEVICE NAME</td>
    <td> <select name="location">
	<?php
	while($result = mysql_fetch_assoc($sql)){
		?>
 <option value="<?php echo  $result['device_description'] ?>"><?php echo  $result['device_description'] ?></option>
<?php
}
 ?>
       
        </select></td>
    <td  width="120">ROUTE NAME</td>
    <td><select class="location" name="location">
	<?php
	while($results = mysql_fetch_assoc($route)){
		?>
 <option value="<?php echo  $results['route_desc'] ?>"><?php echo  $results['route_desc'] ?></option>
<?php
}
 ?>
       
        </select></td>
    </tr>
	<tr>
	<td  width="120">LOCATION NAME</td>
    <td><select id="location" name="location">
        <option value="">--- Select ---</option>
       
        </select></td>
    <td  width="120">VEHICLE</td>
    <td><select name="vehicle">
	<?php
	while($vehicle_result = mysql_fetch_assoc($vehicle)){
		?>
 <option value="<?php echo $vehicle_result ['vehicle_desc'] ?>"><?php echo  $vehicle_result['vehicle_desc'] ?></option>
<?php
}
 ?>
       
        </select></td>
    
	</tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>
 <!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" id="reset"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='kd.php'"/></td>
      </tr>
 </table>     
</form>
</div>

<!---- Form End ----->



<?php include("../include/error.php");?>
  <div id="search">
        <form action="" method="get">
        <input type="text" name="KD_Name" value="<?php $_GET['KD_Name']; ?>" autocomplete='off' placeholder='Search By Code'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>
<div class="mcf"></div>        
<div id="containerpr">
	   	<?php
        if($_GET['no']=='3'){
        $id = $_GET['id'];
        //Set the query to return names of all employees
       	$query="update kd set Status='inactive' where id='$id'";
        //Run the query
        $result = mysql_query($query);
       	 }
		?>
		<?php
		if($_GET['submit']!='')
		{
		$var = @$_GET['KD_Name'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM `kd` where KD_Name like '%".$trimmed."%' AND  Status='active' order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `kd` where Status='active' order by id asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		//$num_rows= mysql_num_rows($results);			
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th>KD Name</th>
		<th class="rounded">Vehicle Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Description</th>
        <th>Reg No</th>
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
		<td><?php echo $fetch['KD_Name'];?></td>
	    <td><?php echo $fetch['Code'];?></td>
        <td><?php echo $fetch['Contact_Number'];?></td>
        <td><?php echo $fetch['KD_Category'];?></td>
       	<td align="right"><a href="kd.php?id=<?php echo $fetch['id'];?>" class="popup"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="kd.php?id=<?php echo $fetch['id'];?>&no=3" class="ask"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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