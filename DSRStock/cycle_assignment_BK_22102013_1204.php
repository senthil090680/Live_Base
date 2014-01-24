<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";

//ini_set("display_errors",false);
//echo ini_get("display_errors");
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
//echo $dateIdVal	=	date('dmYHis');
$msg								=	'';
$query_DSR 							=	"select id,DSRName,DSR_Code from dsr";
$query 								=	mysql_query($query_DSR) or die(mysql_error());
$sql_device   						=	"select id,device_description,device_code from device_master";
$sql   								=	mysql_query($sql_device) or die(mysql_error());
$route_sql							=	"select id,location,route_desc,route_code from route_master";
$route								=	mysql_query($route_sql) or die(mysql_error());
$vehicle_sql						=	"select id,vehicle_desc,vehicle_code,vehicle_reg_no from vehicle_master";
$vehicle							=	mysql_query($vehicle_sql) or die(mysql_error());
$location_sql						=	"select id,location from location";
$location_res						=	mysql_query($location_sql) or die(mysql_error());
$id									=	isset($_REQUEST['id']);

if($id=='') {
	if(isset($_POST['submit'])) {
		extract($_POST);
		$KD_Code					=	getKDCode();
		$KD_Name					=	getKDval($KD_Code,'KD_Name','KD_Code');
		$dsr_name_split				=	explode("~",$_POST['dsrname']);
		$dsr_name					=	$dsr_name_split[1];
		$dsr_code					=	$_POST['dsr_code'];
		$cur_date					=	$_POST['Date'];
		$device_name				=	$_POST['devicename'];
		//$routes_split				=	explode("~",$_POST['route']);
		//$routes						=	$routes_split[0];
		$routes						=	$_POST['route'];
		$location					=	$_POST['location_val'];
		$vehicles					=	$_POST['vehicle'];
		$cycle						=	$_POST['cycle_code'];
		$absvehcode					=	0;

		$query_dsrid				=	"SELECT id FROM dsr WHERE DSR_Code = '$dsr_code'";			
		$res_dsrid					=	mysql_query($query_dsrid) or die(mysql_error());
		$row_dsrid					=	mysql_fetch_array($res_dsrid);
		$dsrid						=	$row_dsrid['id'];

		$query_devid				=	"SELECT id,device_code FROM device_master WHERE device_description = '$device_name'";			
		$res_devid					=	mysql_query($query_devid) or die(mysql_error());
		$row_devid					=	mysql_fetch_array($res_devid);
		$devid						=	$row_devid['id'];
		$devcode					=	$row_devid['device_code'];

		$query_routeid				=	"SELECT id FROM route_master WHERE route_code = '$routes'";			
		$res_routeid				=	mysql_query($query_routeid) or die(mysql_error());
		$row_routeid				=	mysql_fetch_array($res_routeid);
		$routeid					=	$row_routeid['id'];

		$query_locid				=	"SELECT id FROM location WHERE location  = '$location'";			
		$res_locid					=	mysql_query($query_locid) or die(mysql_error());
		$row_locid					=	mysql_fetch_array($res_locid);
		$locid						=	$row_locid['id'];
				
		//$exist_code				=	"SELECT dsr_code from cycle_assignment WHERE dsr_code='$dsr_code' AND Date='$cur_date' AND route_name='$routes'";
		/*$exist_code					=	"SELECT dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_date LIKE '$cur_date%'";
		$exist						=	mysql_query($exist_code) or die(mysql_error());
		if(mysql_num_rows($exist) > 0) {
			header("location:cycle_assignment.php?no=51");exit;
		}*/
		
		if($cycle == "1") {
			$exist_code					=	"SELECT dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_flag = '1' AND cycle_end_flag = '0' ORDER BY id DESC";
			//exit;
			$exist						=	mysql_query($exist_code) or die(mysql_error());

			//echo mysql_num_rows($exist);
			//exit;
			if(mysql_num_rows($exist) > 0) {
				header("location:cycle_assignment.php?no=51"); exit;
			}
		} else {
			$exist_code					=	"SELECT id,dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_flag = '0' AND cycle_end_flag = '1' ORDER BY id DESC";
			//exit;
			$exist						=	mysql_query($exist_code) or die(mysql_error());
			if(mysql_num_rows($exist) > 0) {
				
				$row_exist						=	mysql_fetch_array($exist_code);
				$cycle_flag_id					=	$row_exist[id];
				
				$exist_code					=	"SELECT id,dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_flag = '1' AND cycle_end_flag = '0' AND id < '$row_exist[id]' ORDER BY id DESC";
				//exit;
				$exist						=	mysql_query($exist_code) or die(mysql_error());
				if(mysql_num_rows($exist) > 0) {
					header("location:cycle_assignment.php?no=52"); exit;
				} else {
					$query_sameday			=	"SELECT dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_flag = '0' AND cycle_end_flag = '0' AND cycle_start_date LIKE '$cur_date%' ORDER BY id DESC";
					//exit;
					$res_sameday			=	mysql_query($query_sameday) or die(mysql_error());
					if(mysql_num_rows($res_sameday) > 0) {
						header("location:cycle_assignment.php?no=51"); exit;
					}
				}
			} else {
				$exist_code					=	"SELECT id,dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_flag = '1' AND cycle_end_flag = '0' AND id < '$row_exist[id]' ORDER BY id DESC";
				//exit;
				$exist						=	mysql_query($exist_code) or die(mysql_error());
				if(mysql_num_rows($exist) > 0) {
					header("location:cycle_assignment.php?no=52"); exit;
				} else {
					$query_sameday			=	"SELECT dsr_id from cycle_flag WHERE dsr_id='$dsrid' AND cycle_start_flag = '0' AND cycle_end_flag = '0' AND cycle_start_date LIKE '$cur_date%' ORDER BY id DESC";
					//exit;
					$res_sameday						=	mysql_query($query_sameday) or die(mysql_error());
					if(mysql_num_rows($res_sameday) > 0) {
						header("location:cycle_assignment.php?no=51"); exit;
					}
				}
			}
		}

		$vehicleother						=	strtoupper($vehicleother);
		$query_vehcheck						=	"SELECT id from vehicle_master WHERE vehicle_reg_no='$vehicleother'";
		$res_vehcheck						=	mysql_query($query_vehcheck) or die(mysql_error());

		$query_vehregcheck					=	"SELECT id from vehicle_master WHERE vehicle_reg_no='$vehicle'";
		//exit;
		$res_vehregcheck					=	mysql_query($query_vehregcheck) or die(mysql_error());
		$row_vehregcheck					=	mysql_fetch_array($res_vehregcheck);

		//TO CHECK FOR SAME DEVICE FOR THE DIFFERENT DSRs OF THE SAME DAY STARTS HERE
		$query_samedev						=	"SELECT id from cycle_assignment WHERE (flag_status = '0' OR flag_status = '1') AND end_flag_status != '1' AND Date LIKE '$cur_date%' AND device_id = '$devid'";
		//echo $query_samedev;
		//exit;
		$res_samedev						=	mysql_query($query_samedev) or die(mysql_error());
		if(mysql_num_rows($res_samedev) > 0) {
			header("location:cycle_assignment.php?no=54"); exit;
		}
		//TO CHECK FOR SAME DEVICE FOR THE DIFFERENT DSRs OF THE SAME DAY ENDS HERE


		//TO CHECK FOR SAME VEHICLE FOR THE DIFFERENT DSRs OF THE SAME DAY STARTS HERE
		if(mysql_num_rows($res_vehregcheck) > 0) {

			$query_sameveh						=	"SELECT id from cycle_assignment WHERE (flag_status = '0' OR flag_status = '1') AND end_flag_status != '1' AND Date LIKE '$cur_date%' AND vehicle_id = '$row_vehregcheck[id]'";
			//echo $query_sameveh;
			//exit;
			$res_sameveh						=	mysql_query($query_sameveh) or die(mysql_error());
			if(mysql_num_rows($res_sameveh) > 0) {
				header("location:cycle_assignment.php?no=53"); exit;
			}
		}

		//exit;
		//TO CHECK FOR SAME VEHICLE FOR THE DIFFERENT DSRs OF THE SAME DAY ENDS HERE


		if($dsr_name=='' || $dsr_code==''  || $device_name=='' || $routes=='' || $location ==''|| $vehicles ==''|| $cycle =='') {
			header("location:cycle_assignment.php?no=9");exit;
		}
		else {

			if($vehicles == 'Others' || $vehicles == 'others') {				
				if(mysql_num_rows($res_vehcheck) == 0) {

					$query_vehcodecheck		=	"SELECT id,vehicle_code from vehicle_master WHERE (vehicle_reg_no != 'Others' OR vehicle_reg_no != 'others') ORDER BY id DESC";
					$res_vehcodecheck		=	mysql_query($query_vehcodecheck) or die(mysql_error());
					$row_vehcodecheck		=	mysql_fetch_array($res_vehcodecheck);

					$vehcodecheck			=	$row_vehcodecheck[vehicle_code];
					$absvehcode				=	abs(str_replace('VEH','',$vehcodecheck));

					$absvehcode++;
					//echo $absvehcode;
					//exit;
					if($absvehcode < 10) {
						$createdcode	=	"00".$absvehcode;
					} else if($absvehcode < 100) {
						$createdcode	=	"0".$absvehcode;
					} else {
						$createdcode	=	$absvehcode;
					}
					$newvehcode			=	"VEH".$createdcode;

					$query_vehid			=	"INSERT INTO vehicle_master SET KD_Code='$KD_Code',KD_Name='$KD_Name',vehicle_code='$newvehcode',vehicle_desc='Vehicle',vehicle_reg_no='$vehicleother'";			
					$res_vehid				=	mysql_query($query_vehid) or die(mysql_error());
					$vehid					=	mysql_insert_id();
				} else {
					$row_vehidval			=	mysql_fetch_array($res_vehcheck);
					$vehid					=	$row_vehidval['id'];
				}
			} else {
				$query_vehid			=	"SELECT id FROM vehicle_master WHERE vehicle_reg_no = '$vehicles'";			
				$res_vehid				=	mysql_query($query_vehid) or die(mysql_error());
				$row_vehid				=	mysql_fetch_array($res_vehid);
				$vehid					=	$row_vehid['id'];
			}


			$query_cycleflag			=	"INSERT INTO cycle_flag SET KD_Code='$KD_Code',dsr_id='$dsrid',cycle_start_flag='$cycle',cycle_start_date=NOW(),cycle_end_flag='0'";
			$res_cycleflag				=	mysql_query($query_cycleflag) or die (mysql_error());
			$last_inserted_id			=	mysql_insert_id();

			$ass_sql					=	"INSERT INTO cycle_assignment SET KD_Code='$KD_Code',flag_status='$cycle',dsr_id='$dsrid',dsr_code='$dsr_code',device_id='$devid',device_code='$devcode',route_id='$routeid',location_id='$locid',vehicle_id='$vehid',Date=NOW(),end_flag_status='0',assign_id='$last_inserted_id'";
			//exit;
			$isert						=	mysql_query($ass_sql) or die (mysql_error());

			if($res_cycleflag && $isert)
			{
				header("location:cycleassignview.php?no=1");
			}
		}
	}
}
if(isset($_REQUEST['id'])) {
	$id			=	$_REQUEST['id'];
	/*$up_sql		=	"select * from cycle_assignment WHERE id=$id";
	//$up_result	=	mysql_query($up_sql);
	if(mysql_num_rows($up_result) > 0) {
					
		$row		= 	mysql_fetch_array($up_result);
		$dsrcode	=	$row['dsr_code'];
		$dsrname	= $row['dsr_name'];
		$device		= $row['device_name'];
		$route_name = $row['route_name'];
		$location_name=$row['location'];
		$vehicle_name=$row['vehicle'];*/
					
	 if(isset($_POST['submit'])) {	   
		$dsr_name_split				=	explode("~",$_POST['dsrname']);
		$dsr_name					=	$dsr_name_split[1];
		$dsr_code					=	$_POST['dsr_code'];
		$device_name				=	$_POST['devicename'];
		//$routes_split				=	explode("~",$_POST['route']);
		//$routes						=	$routes_split[0];
		$routes						=	$_POST['route'];
		$location					=	$_POST['location_val'];
		$vehicles					=	$_POST['vehicle'];
		$update_cycle				=	$_POST['cycle_code'];
	   
	   $ass_sql						=	"UPDATE cycle_assignment SET dsr_name='$dsr_name',flag_status='$update_cycle',dsr_code='$dsr_code',device_name='$device_name',route_name='$routes',location='$location',vehicle='$vehicles' WHERE id=$id";												
	   $update						=	mysql_query($ass_sql) or die (mysql_error());		   
	   if($update) {
		   header("location:cycleassignview.php?no=2");
	   }
	}					
	//}				
	/*if(isset($_REQUEST['act'])=='D'){
		if($_POST['submit']=='ConfirmDelete'){		
			$id=$_REQUEST['id'];
			
			$query_assignid				=	"SELECT assign_id FROM cycle_assignment WHERE id = '$id'";			
			$res_assignid				=	mysql_query($query_assignid) or die(mysql_error());
			$row_assignid				=	mysql_fetch_array($res_assignid);
			$assignid					=	intval($row_assignid['assign_id']);

			$Sql						=	"DELETE from cycle_assignment WHERE id=$id";
			$delres						=	mysql_query($Sql) or die (mysql_error());

			echo $delflag_qry				=	"DELETE from cycle_flag WHERE id='$assignid'";
			exit;
			$delflag_res				=	mysql_query($delflag_qry) or die (mysql_error());

			if($delres && $delflag_res) {
			   header("location:cycleassignview.php?no=3");
		   }

			$cycle_state="SELECT dsr_code,flag_status from cycle_assignment WHERE id='$id'";
			$up_query=mysql_query($cycle_state);
			$mysql_fetch=mysql_fetch_array($up_query);
			$check_cycle=$mysql_fetch['flag_status'];

			if($check_cycle== 0){
				$msg="the cycle assignment not done";
			}
			else {		
				$Sql="DELETE from cycle_assignment WHERE id=$id";
				$del=	mysql_query($Sql) or die (mysql_error());
				if($del=='true') {				
					$update_flag=$mysql_fetch['dsr_code'];
					$sel_flag="SELECT dsr_code from cycle_flag WHERE dsr_code='$update_flag'";
					$flag_query=mysql_query($sel_flag);
					$fetch_flag=mysql_fetch_array($flag_query);
					if(mysql_num_rows($flag_query)> 0){
						$update_flag="update cycle_flag SET cycle_start_flag='null',cycle_start_date='null',cycle_end_flag='null',cycle_end_date='null'";
						$flag_update=mysql_query($update_flag) or die(mysql_error());
					}
					//$msg="successfully deleted";
					header("location:cycleassignview.php?no=3");
				}
			}				
		} //second if for delete
	}*/ // first if for delete
}
$list						=	mysql_query("select * from cycle_assignment where id= '$id'"); 
$row						=	mysql_fetch_array($list); 
$Date						=	$row['Date'];
$dsrcode					=	$row['dsr_code'];
$device						=	$row['device_id'];
$route_name					=	getrouteval($row['route_id'],'route_desc','id');
$location_name				=	$row['location_id'];
$vehicle_name				=	$row['vehicle_id'];
$flag_status				=	$row['flag_status'];
$vehicle_reg_no				=	$row['vehicle_reg_no'];
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Daily Assignment</div>
<div id="mytableformgr" align="center">
<!--<div></div>-->

<form method="post" action="" id="cyclestartvalidation" onSubmit="return checkcyclestart();">
<table width=100%>
<tr>
<td>
<fieldset class="alignment">
  <legend><strong>Daily Assignment</strong></legend>
  <table width="100%">
    <tr>
    <td height="28" class="align">Date</td>
    <td><input type="text" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly name="Date" />	
	<!-- <input type="text" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly name="datepicker" /> -->
	</td>    
    <td height="28" class="align">Cycle Start Flag*</td>
    <td><select class="dsrname" name="cycle_code" id="cycle_code">
		 <option value="">---Select---</option>
		 <option value="1" <?php if($flag_status == "1") { echo "selected"; } ?> >Yes </option>
		 <option value="0" <?php if($flag_status == "0") { echo "selected"; } ?> >No</option>
    </select>	
	<!-- <input type="text" name="cycle_code" size="20" value="<?php  if(isset($row['flag_status'])) echo $row['flag_status'];?>"  readonly="readonly" id="flag" maxlength="15" autocomplete='off'/> -->
	</td>
    </tr>
<tr>
    <td height="28" class="align">SR Name*</td>
    <td>
    <select class="dsrname" name="dsrname" id="dsrname" onChange="cycleStartDSR(this.value);">
      <option value="">---Select---</option>
	<?php while($info = mysql_fetch_assoc($query)){
		$routemonth					=	ltrim(date('m'),0);
		$routeyear					=	date('Y');
		$dayvalue					=	ltrim(date('d'),0);
		$daycolumn					=	"day".$dayvalue;

		$sel_routecode				=	"SELECT $daycolumn FROM routemonthplan WHERE DSR_Code = '$info[DSR_Code]' AND routemonth = '$routemonth' AND routeyear = '$routeyear'";
		$res_routecode				=	mysql_query($sel_routecode) or die(mysql_error());
		$rowcnt_routecode			=	mysql_num_rows($res_routecode);
		if($rowcnt_routecode > 0) { 
			$row_routecode			=	mysql_fetch_array($res_routecode);
			$routecode				=	$row_routecode[$daycolumn];
			$sel_location			=	"SELECT id,location FROM route_master WHERE route_code = '$routecode'";
			$res_location			=	mysql_query($sel_location);
			$rowcnt_location		=	mysql_num_rows($res_location);
			if($rowcnt_location > 0) {
				$row_location		=	mysql_fetch_array($res_location);
				$location			=	$row_location[location];
			}
		}	
	?>
    <option value="<?php echo $info['DSR_Code']."~".$info['DSRName']."~".$routecode."~".$location; ?>" <?php if($dsrcode == $info['DSR_Code']) { echo "selected"; } ?> > <?php echo  $info['DSRName'] ?></option>
    <?php } ?> 
    </select>
    </td>
    
    <td class="align" height="28">SR Code*</td>
    <td><input type="text" name="dsr_code" id="dsr_code" size="20" value="<?php  if(isset($dsrcode)) echo $dsrcode; ?>" readonly id="dsrcode" class="required" maxlength="15"/></td>
    </tr>
        
    <tr>
    <td height="28" class="align">Device*</td>
    <td><select name="devicename">
      <option value="">---Select---</option>
	<?php while($result = mysql_fetch_assoc($sql)){?>
    <option value="<?php echo  $result['device_description'] ?>" <?php if($device == $result['id']) { echo "selected"; } ?> ><?php echo  $result['device_description'] ?></option>
<?php } ?>
    </select>
    </td>
    
    <td class="align" height="28" >Route*</td>
    <td>
	<input type="text" name="route" id="route" readonly value="<?php echo $route_name; ?>"/>
	<!-- <select class="location" name="route" id="route" onChange="loadLocation(this.value);">
	    <option value="">---Select---</option>
	<?php while($results = mysql_fetch_assoc($route)){?>
	     <option value="<?php echo $results['route_desc']."~".$results['location']; ?>" <?php if($route_name == $results['id']) { echo "selected"; } ?> ><?php echo  $results['route_desc'] ?></option>
	<?php }?>
	    </select> -->


    </td>
    </tr>
    
    <tr>
    <td height="28" class="align">Location*</td>
    <td><input type="text" name="location_val" readonly value="<?php 
	while($location_row = mysql_fetch_array($location_res)) {
		if($location_name == $location_row[id]) {
			echo $location_row[location];
		}
	}
	?>" size="20"  
    id="location_val" autocomplete='off' maxlength="15"/></td>
   
    <td class="align">Vehicle*</td>
    <td><select name="vehicle" id="vehicle" onChange="vehiclenewreg(this.value);">
      <option value="">---Select---</option>
	<?php while($vehicle_result = mysql_fetch_assoc($vehicle)){?>
    <option value="<?php echo $vehicle_result['vehicle_reg_no']; ?>" <?php if($vehicle_name == $vehicle_result['id']) { echo "selected"; } ?> ><?php echo  strtoupper($vehicle_result['vehicle_reg_no']); ?></option>
   <?php }?>
   </select>&nbsp;&nbsp;<span id="vehother" style="display:none;" ><input type="text" value="" name="vehicleother" autocomplete="off" id="vehicleother" maxLength="10" /></span>
    </td> 
    </tr>
</table>
</fieldset>
</td>
</tr>

 <table width="100%" style="clear:both">
    <tr>
    <td colspan="5" align="center" height="40px"><?php if($_REQUEST['del'] != 'del'){ ?>
    <?php if($_REQUEST['id']!='' && $_REQUEST['del'] != 'del') { ?>
	<input type="submit" name="submit" id="submit" class="buttons" value="Update" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php } else { ?>
	<input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php } ?>
    <input type="reset" name="reset" id="reset"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" name="View" value="View" class="buttons" onclick="window.location='cycleassignview.php'"/>
	<?php } else echo "&nbsp;"; ?>
	</td>
    </tr>
  </table>
  
</form>
<?php include("../include/error.php");?>
<div class="mcf"></div> 
 <!--  <div id="search">
        <form action="" method="get">
        <input type="text" name="dsr_name" value="<?php isset($_REQUEST['dsr_name']); ?>" autocomplete='off' placeholder='Search By DSR Name'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>  -->    
<!-- <div id="containerpr">	   
		<?php
		if(isset($_REQUEST['submit'])!='')
		{
		$var = @$_REQUEST['dsr_name'] ;
		
        $trimmed = trim($var);	
	    $qry="SELECT * FROM cycle_assignment where dsr_name like '%".$trimmed."%' order by dsr_name asc";
		}
		else
		{ 
			$qry="SELECT * FROM cycle_assignment order by dsr_name asc"; 
		}
		$search=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$search = $pager->paginate();
		$num_rows= mysql_num_rows($search);			
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th>DSR Name <img src="../images/sort.png" width="13" height="13" /></th>
		<th class="rounded">Device</th>
        <th>Route</th>
		<th>Location</th>
		<th>CycleStart flag</th>
	    <th align="right">Mod/Del</th>
		</tr>
		</thead>
        
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($search)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php echo $fetch['dsr_name'];?></td>
	    <td><?php echo $fetch['device_name'];?></td>
        <td><?php echo $fetch['route_name'];?></td>
		<td><?php echo $fetch['location'];?></td>
      <td><?php echo $fetch['flag_status'];?></td>
       	<td align="right">
        <a href="cycle_assignment.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="cycle_assignment.php?id=<?php echo $fetch['id'];?>&delId=<?php echo $fetch['id'];?>&act=D"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
      </div> -->
    <div class="msg" align="center" <?php if($_REQUEST['id']!='' && $_REQUEST['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="cycleassignview.php" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_REQUEST[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='cycleassignview.php'"/>
      </form>
     </div> 
     </div>
  <div id="errormsgcystart" style="display:none;"><h3 align="center" class="myaligncystart"></h3><button id="closebutton">Close</button></div>
  </div>
</div>
<?php include('../include/footer.php');?>