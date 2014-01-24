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
$page=intval($_GET['page']);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
	
if(isset($_POST['submit'])=='Save'){

        $sql =("UPDATE host_information SET 
		Host_id='$Host_id',	   
        Host_IP='$Host_IP',
		Host_Url='$Host_Url',
		branch_id='$branch_id'
	   	WHERE id = 1");
		mysql_query($sql);
header("location:hostsetup.php?no=2$page=$page");exit;
}
}

$id=$_GET['id'];
$list=mysql_query("select * from host_information where id='1'"); 
while($row = mysql_fetch_array($list)){ 
    $Host_id = $row['Host_id'];
	$Host_IP = $row['Host_IP'];
	$Host_Url = $row['Host_Url'];
	$branch_id = $row['branch_id'];
	
	} 
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headings">Host Setup</div>
<div id="mytable" align="center">
<form action="" method="post" id="validation">
<table>
 <tr height="60px">
 
  <td>Host ID*</td>
  <td>
  <?php
  $param=mysql_query("select * from  host_information where id=1"); 
  $row=mysql_fetch_array($param);
?>
   <select name="Host_id">
    <option value="">--- Select ---</option>
    <option value="sfa_retail"  <?php if($row['Host_id']=='sfa_retail'){ echo 'selected'; }?>>sfa_retail</option>
    <option value="sfa_retail2" <?php if($row['Host_id']=='sfa_retail2'){ echo 'selected'; }?>>sfa_retail2</option>
    <option value="sfa_retail3" <?php if($row['Host_id']=='sfa_retail3'){ echo 'selected'; }?>>sfa_retail3</option>
    <option value="sfa_retail4" <?php if($row['Host_id']=='sfa_retail4'){ echo 'selected'; }?>>sfa_retail4</option>
    <option value="sfa_retail5" <?php if($row['Host_id']=='sfa_retail5'){ echo 'selected'; }?>>sfa_retail5</option>
    </select>
  
   	
  </td>
   
  <td class="align">Host IP*</td>
  <td><input type="text" name="Host_IP" value="<?php echo $Host_IP; ?>" id="Host_IP" size="15" autocomplete='off' maxlength="50"/></td>
  </tr>
  
  <tr>
    <td>Host Url*</td>
    <td><input type="text" name="Host_Url" value="<?php echo $Host_Url; ?>" id="Host_Url" size="15" autocomplete='off' maxlength="50"/></td>
  
    
      <td class="align">Branch*</td>
       <td>
		<?php
        $list=mysql_query("select * from branch order by branch asc");
        ?>
        <select name="branch_id" id="branch_id">
        <option value="">--- Select ---</option>
		<?php
		while($row_list=mysql_fetch_assoc($list)){
		$id=$row_list['branch'];
		?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$branch_id){ echo "selected"; } ?>><?php echo $row_list['branch']; ?></option>
        <?php  } ?>
        </select>
         </td>
      </tr>
 
   <tr align="center" height="70px;">
    <td colspan="10">
    <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="reset" name="reset" id="Clear"  class="buttons" value="Clear" onclick="window.location='hostsetup.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/></td>
      </tr>
</table>
</form>
</div>
<?php include("../include/error.php");?>
<div class="mcf"></div>
<div id="container">
       	<?php
	    $qry="SELECT * FROM `host_information`";  
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con2">
        <table id="sort" class="tablesorter" align="center" width="100%">
       	<thead>
		<tr>
        <th>HOST ID</th>
        <th>HOST IP</th>
		<th>Host URL</th>
        <th>Branch</th>
       	<th align="right">Edit</th>
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
		$branchid= $fetch['branch_id'];
		?>
		<tr>
        <td><?php echo $fetch['Host_id'];?></td>
        <td><?php echo $fetch['Host_IP'];?></td>
     	<td><?php echo $fetch['Host_Url'];?></td>
        <td>
		<?php
		$sambr=mysql_query("select * from  branch where id= '$branchid'");
        $row=mysql_fetch_array($sambr);
        $branid=$row['id'];
		$branchv=$row['branch'];
		if($branchid=$branchv){echo $branchv;}?>
		</td>
       	<td align="right">
        <a href="hostsetup.php?id=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
          </td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        </div>
      </div>
</div>
<?php include('../include/footer.php'); ?>