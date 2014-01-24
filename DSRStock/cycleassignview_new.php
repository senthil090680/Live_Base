<?php
session_start();
ob_start();
require_once('../include/header.php');
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_POST);
$id=$_REQUEST['id'];
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">CYCLE START ASSIGNMENT</h2></div> 
<div id="containerdaily">

<span style="float:left;"><input type="button" name="kdproduct" value="Add Cycle Start Assignment" class="buttonsbig" onclick="window.location='cycle_assignment.php'"></span><span style="float:right;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

<div class="clearfix"></div>
 <div id="search">
        <form action="" method="get">
        <input type="text" name="DSR_name" value="<?php echo $_GET['DSR_name']; ?>" autocomplete='off' placeholder='Search By DSR Name'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
 </div>
 <div class="clearfix"></div>
        <?php
		if($_POST['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){

				$query_assignid				=	"SELECT assign_id FROM cycle_assignment WHERE id = '$id'";			
				$res_assignid				=	mysql_query($query_assignid) or die(mysql_error());
				$row_assignid				=	mysql_fetch_array($res_assignid);
				$assignid					=	intval($row_assignid['assign_id']);

				$Sql						=	"DELETE from cycle_assignment WHERE id=$id";
				$delres						=	mysql_query($Sql) or die (mysql_error());

				$delflag_qry				=	"DELETE from cycle_flag WHERE id='$assignid'";
				$delflag_res				=	mysql_query($delflag_qry) or die (mysql_error());

				if($delres && $delflag_res) {
				   header("location:cycleassignview.php?no=3");
			   }
			}
		 }
		if($_GET['submit']!='')
		{
			$var = @$_GET['DSR_name'] ;
			$trimmed = trim($var);	
			$qry="SELECT * FROM `cycle_assignment` where dsr_name like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
			$qry="SELECT * FROM `cycle_assignment` order by id asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th class="rounded">DSR Name <img src="../images/sort.png" width="13" height="13" /></th>
		<th>Device</th>
        <th>Route</th>
		<th>Location</th>
		<th nowrap="nowrap">Vehicle Reg. No</th>
		<th nowrap="nowrap">Cycle Start flag</th>
		<th nowrap="nowrap">Cycle End flag</th>
		<th nowrap="nowrap">Date</th>
		<th align="right">Del</th>
	    <!-- <th align="right">Mod/Del</th> -->
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

		$query_dsrid				=	"SELECT DSRName FROM dsr WHERE id = '$fetch[dsr_id]'";			
		$res_dsrid					=	mysql_query($query_dsrid) or die(mysql_error());
		$row_dsrid					=	mysql_fetch_array($res_dsrid);
		$DSRName					=	$row_dsrid['DSRName'];

		$query_devid				=	"SELECT device_description FROM device_master WHERE id = '$fetch[device_id]'";			
		$res_devid					=	mysql_query($query_devid) or die(mysql_error());
		$row_devid					=	mysql_fetch_array($res_devid);
		$device_description			=	$row_devid['device_description'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE id = '$fetch[route_id]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_desc					=	$row_routeid['route_desc'];

		$query_locid				=	"SELECT location FROM location WHERE id = '$fetch[location_id]'";			
		$res_locid					=	mysql_query($query_locid) or die(mysql_error());
		$row_locid					=	mysql_fetch_array($res_locid);
		$location						=	$row_locid['location'];

		$query_vehid				=	"SELECT vehicle_reg_no FROM vehicle_master WHERE id = '$fetch[vehicle_id]'";			
		$res_vehid					=	mysql_query($query_vehid) or die(mysql_error());
		$row_vehid					=	mysql_fetch_array($res_vehid);
		$vehicle_desc				=	$row_vehid['vehicle_reg_no'];


		?>
		<tr>
		<td><?php echo $DSRName;?></td>
	    <td><?php echo $device_description;?></td>
        <td><?php echo $route_desc;?></td>
		<td><?php echo $location;?></td>
		<td><?php if($fetch[vehicle_id] == '') {
			echo $fetch[vehicle_reg_no];
		} else { 
			echo $vehicle_desc;
		}
		?></td>
		<td><?php if($fetch['flag_status'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
		<td><?php if($fetch['end_flag_status'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
		<td nowrap="nowrap"><?php echo $fetch['Date'];?></td>
       	<td align="right">
        <!-- <a href="../DSRStock/cycle_assignment.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp; -->
        <a href="../DSRStock/cycle_assignment.php?id=<?php echo $fetch['id'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>
        </tr>
		<?php $c++; $cc++; }		 
		}else { ?>
			<tr>
				<td align='center' colspan='13'><b>No records found</b></td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line2</td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line1</td>
			</tr>
		<?php }  ?>
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
     <div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='DailyStockLoadingview.php'"/>
      </form>
     </div>  
   </div>
 <?php require_once('../include/error.php'); ?>
</div>
<?php require_once('../include/footer.php');?>