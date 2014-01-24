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
<div><h2 align="center">ROUTE MASTER PLAN</h2></div> 
<div id="containerdaily">

<span style="float:left;"><input type="button" name="kdproduct" value="Add Master Route Plan" class="buttonsbig" onclick="window.location='routeplan.php'"></span><span style="float:right;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

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
			$qry="SELECT * FROM `routemasterplan` order by id asc"; 
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
		<th>DSR Name</th>
		<th>Monday</th>
        <th>Tuesday</th>
		<th>Wednesday</th>
		<th>Thursday</th>
		<th>Friday</th>
		<th>Saturday</th>
		<th nowrap="nowrap">Date</th>
	    <th>Edit</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];

		$query_dsrid				=	"SELECT DSRName FROM dsr WHERE DSR_Code = '$fetch[DSR_Code]'";			
		$res_dsrid					=	mysql_query($query_dsrid) or die(mysql_error());
		$row_dsrid					=	mysql_fetch_array($res_dsrid);
		$DSRName					=	$row_dsrid['DSRName'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_mon]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_mon					=	$row_routeid['route_desc'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_tue]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_tue					=	$row_routeid['route_desc'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_wed]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_wed					=	$row_routeid['route_desc'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_thu]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_thu					=	$row_routeid['route_desc'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_fri]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_fri					=	$row_routeid['route_desc'];

		$query_routeid				=	"SELECT route_desc FROM route_master WHERE route_code = '$fetch[route_sat]'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$route_sat					=	$row_routeid['route_desc'];

		?>
		<tr>
		<td><?php echo $DSRName;?></td>
	    <td><?php echo $route_mon;?></td>
        <td><?php echo $route_tue;?></td>
		<td><?php echo $route_wed;?></td>
		<td><?php echo $route_thu;?></td>
		<td><?php echo $route_fri; ?></td>
		<td><?php echo $route_sat; ?></td>
		<td nowrap="nowrap"><?php echo $fetch['insertdatetime'];?></td>
       	<td align="right">
        <a href="../BaseMaster/routeplanedit.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
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